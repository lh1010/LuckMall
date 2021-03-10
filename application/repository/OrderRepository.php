<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Order Repository
 * ============================================================================
 * Author: Jasper   
 */

namespace app\repository;

use think\Db;
use app\repository\CityRepository;
use app\repository\CheckoutRepository;
use app\repository\UserRepository;
use app\repository\PaymentRepository;
use app\repository\ImageRepository;
use Jenssegers\Agent\Agent;

class OrderRepository
{
    public $statusArray = ['-10' => '已取消', '10' => '待付款', '20' => '待发货', '21' => '申请退款中', '30' => '待收货', '40' => '已完成', '99' => '已删除'];

    public function getOrders($params = [])
    {
        $select = 'order.*, payment.name as payment_name';
        $query = Db::name('order');
        $query->alias('order');
        $query->field($select);
        $query->order('order.create_time desc');
        $query->leftJoin('payment', 'payment.id = order.payment_id');
        $this->setGetOrdersParams($query, $params);
        $orders = $query->select();
        if (empty($orders)) return $orders;
        // get order snaps
        $order_ids = array_column($orders, 'id');
        $order_snaps = Db::name('order_snap')->whereIn('order_id', $order_ids)->select();
        $array = [];
        foreach ($order_snaps as $key => $value) {
            if (!empty($value['specifications'])) $value['specifications'] = json_decode($value['specifications'], 1);
            $array[$value['order_id']][] = $value;
            $order_snaps[$key]['product_image'] = app(ImageRepository::class)->setProductImage($value['product_image'], $value['product_id']);
        }
        foreach ($orders as $key => $value) {
            $orders[$key]['order_snaps'] = isset($array[$value['id']]) ? $array[$value['id']] : [];
        }
        return $orders;
    }

    public function getOrdersPaginate($params = [], $page_size = 15)
    {
        $select = 'order.*';
        $query = Db::name('order');
        $query->alias('order');
        $query->field($select);
        $query->order('order.create_time desc');
        $query->where('order.status', '<>', 99);
        $this->setGetOrdersParams($query, $params);
        $orders = $query->paginate($page_size);
        if ($orders->total() == 0) return $orders;
        // get order snaps
        $order_ids = array_column($orders->items(), 'id');
        $order_snaps = $this->getOrderSnaps(['order_ids' => $order_ids]);;
        $array = [];
        foreach ($order_snaps as $key => $value) {
            $value['product_image'] = app(ImageRepository::class)->setProductImage($value['product_image'], $value['product_id']);
            $array[$value['order_id']][] = $value;
        }
        $orders->each(function($value, $key) use ($array) {
            $value['order_snaps'] = isset($array[$value['id']]) ? $array[$value['id']] : [];
            // set order status
            $value['status_str'] = $this->statusArray[$value['status']];
            $value['delete_status'] = 1;
            $value['payment_name'] = Config('payment.' . $value['payment_id'] . '.name');
            return $value;
        });
        return $orders;
    }

    private function setGetOrdersParams($query, $params)
    {
        if (isset($params['number']) && !empty($params['number'])) $query->where('order.number',  $params['number']);
        if (isset($params['user_id']) && !empty($params['user_id'])) $query->where('order.user_id', $params['user_id']);
        if (isset($params['status']) && !empty($params['status'])) $query->where('order.status',  $params['status']);
    }

    public function getOrder($id, $user_id)
    {
        $select = 'order.*';
        $query = Db::name('order');
        $query->alias('order');
        $query->field($select);
        $query->where('order.status', '<>', 99);
        $query->where('order.id', $id);
        $query->where('order.user_id', $user_id);
        $order = $query->find();
        if (empty($order)) return $order;
        $order['status_str'] = $this->statusArray[$order['status']];
        $order['order_snaps'] = $this->getOrderSnaps(['order_id' => $order['id']]);
        $order['payment_name'] = Config('payment.' . $order['payment_id'] . '.show_name');
        return $order;
    }

    /**
     * Create Order Type Cart
     * @param int $params['user_id']
     * @param int $params['address_id']
     * @param int $params['payment_id']
     * @return array
     */
    public function create_order_cart($params)
    {
        Db::startTrans();
        try {
            $res = app(CheckoutRepository::class)->initData_cart(['user_id' => $params['user_id']]);
            if (empty($res)) return arrayFailed('购物车不能为空');
            $products = $res['product'];
            $address = $res['address'];
            $product_total_price = $res['product_total_price'];
            $shipping_freight_total_price = $res['shipping_freight_total_price'];
            $total_price = $res['total_price'];
            $data = [];
            // 检查库存
            foreach ($products as $key => $value) {
                if ($value['count'] > $value['stock']) return arrayFailed('库存不足！【'.$value['name'].'】剩余库存为：'.$value['stock']);
            }
            $res = $this->setAddress($address);
            if ($res['code'] != 200) return arrayFailed($res['message']);
            $data = array_merge($data, $res['data']);
            $data['number'] = $this->getNumber();
            $data['user_id'] = $params['user_id'];
            $data['product_total_price'] = $product_total_price;
            $data['shipping_freight_total_price'] = $shipping_freight_total_price;
            $data['total_price'] = $total_price;
            $data['message'] = $params['message'];
            if (isset($params['payment_id'])) $data['payment_id'] = $params['payment_id'];
            $order_id = Db::name('order')->insertGetId($data);
            $this->setSnap($products, $order_id);
            Db::name('cart')->where('selected', 1)->where('user_id', $params['user_id'])->delete();
            // 处理库存
            foreach ($products as $key => $value) {
                Db::name('product_sku')->where('sku', $value['sku'])->setDec('stock', $value['count']);
            }
            Db::commit();
            $order = $data;
            $order['id'] = $order_id;
            return arraySuccess(['order' => $order]);
        } catch (\Throwable $th) {
            Db::rollback();
            return arrayFailed($th->getMessage());
        }
    }

    public function create_order_onekeybuy($params)
    {
        Db::startTrans();
        try {
            $res = app(CheckoutRepository::class)->initData_onekeybuy(['user_id' => $params['user_id'], 'sku' => $params['sku'], 'count' => $params['count']]);
            if (empty($res)) return arrayFailed('商品已下架');
            $product = $res['product'];
            $address = $res['address'];
            $product_total_price = $res['product_total_price'];
            $shipping_freight_total_price = $res['shipping_freight_total_price'];
            $total_price = $res['total_price'];
            $data = [];
            // 检查库存
            if ($product['count'] > $product['stock']) return arrayFailed('库存不足，当前剩余库存：'.$product['stock']);
            $res = $this->setAddress($address);
            if ($res['code'] != 200) return arrayFailed($res['message']);
            $data = array_merge($data, $res['data']);
            $data['number'] = $this->getNumber();
            $data['user_id'] = $params['user_id'];
            $data['product_total_price'] = $product_total_price;
            $data['shipping_freight_total_price'] = $shipping_freight_total_price;
            $data['total_price'] = $total_price;
            $data['message'] = $params['message'];
            $data['type'] = 'onekeybuy';
            if (isset($params['payment_id'])) $data['payment_id'] = $params['payment_id'];
            $order_id = Db::name('order')->insertGetId($data);
            $this->setSnap($product, $order_id, $type = 'onekeybuy');
            // 处理库存
            Db::name('product_sku')->where('sku', $product['sku'])->setDec('stock', $params['count']);
            Db::commit();
            $order = $data;
            $order['id'] = $order_id;
            return arraySuccess(['order' => $order]);
        } catch (\Throwable $th) {
            Db::rollback();
            return arrayFailed($th->getMessage());
        }
    }

    private function setAddress($address = [])
    {
        $data = [];
        if (empty($address)) return arrayFailed('收货地址不能为空');
        $data['name'] = $address['name'];
        $data['province_id'] = $address['province_id'];
        $data['province_name'] = app(CityRepository::class)->getName($address['province_id']);
        $data['city_id'] = $address['city_id'];
        $data['city_name'] = app(CityRepository::class)->getName($address['city_id']);
        $data['district_id'] = $address['district_id'];
        $data['district_name'] = app(CityRepository::class)->getName($address['district_id']);
        $data['detailed_address'] = $address['detailed_address'];
        $data['phone'] = $address['phone'];
        return arraySuccess($data);
    }

    /**
     * Set Order Snap
     * @param array $product_data
     * @param int $order_id
     * @param string $type cart|onekeybuy default=cart
     */
    private function setSnap($product_data, $order_id, $type = 'cart')
    {
        $data = [];
        if ($type == 'cart') {
            foreach ($product_data as $key => $value) {
                $data[$key]['order_id'] = $order_id;
                $data[$key]['product_id'] = $value['id'];
                $data[$key]['sku'] = $value['sku'];
                $data[$key]['product_name'] = $value['name'];
                $data[$key]['product_image'] = $value['image'];
                $data[$key]['count'] = $value['count'];
                $data[$key]['sale_price'] = $value['sale_price'];
                $data[$key]['total_price'] = $value['total_price'];
                $data[$key]['shipping_freight_price'] = '0.00';
                $data[$key]['specifications'] = '';
                if (!empty($value['specifications'])) {
                    $data[$key]['specifications'] = json_encode($value['specifications']);
                }
            }
        } else {
            $data[0]['order_id'] = $order_id;
            $data[0]['product_id'] = $product_data['id'];
            $data[0]['sku'] = $product_data['sku'];
            $data[0]['product_name'] = $product_data['name'];
            $data[0]['product_image'] = $product_data['image'];
            $data[0]['count'] = $product_data['count'];
            $data[0]['sale_price'] = $product_data['sale_price'];
            $data[0]['total_price'] = $product_data['total_price'];
            $data[0]['shipping_freight_price'] = '0.00';
            $data[0]['specifications'] = '';
            if (!empty($product_data['specifications'])) {
                $data[0]['specifications'] = json_encode($product_data['specifications']);
            }
        }
        Db::name('order_snap')->insertAll($data);
    }

    private function setLog($order_id)
    {
        $data = [];
        $data['order_id'] = $order_id;
        $data['ip'] = Request()->ip();
        $data['browser'] = app(Agent::class)->browser();
        $data['browser_version'] = app(Agent::class)->version($data['browser']);
        Db::name('order_log')->insert($data);
    }

    public function getNumber()
    {
        $number = Db::name('order')->order('number desc')->value('number');
        if (!empty($number)) {
            $number += 1;
        } else {
            $number = 1000000;
        }
        return $number;
    }

    public function getOrderSnaps($params = [])
    {
        $query = Db::name('order_snap');
        if (isset($params['order_id'])) $query->where('order_id', $params['order_id']);
        if (isset($params['order_ids'])) $query->whereIn('order_id', $params['order_ids']);
        $order_snaps = $query->select();
        if (empty($order_snaps)) return $order_snaps;
        foreach ($order_snaps as $key => $value) {
            if (!empty($value['specifications'])) $order_snaps[$key]['specifications'] = json_decode($value['specifications'], 1);
            $order_snaps[$key]['product_image'] = app(ImageRepository::class)->setProductImage($value['product_image'], $value['product_id']);
        }
        return $order_snaps;
    }

    /**
     * 订单取消
     * @param int $order_id
     * @param int $user_id
     * @return array
     */
    public function cancelOrder($order_id, $user_id)
    {
        Db::startTrans();
        try {
            $order = Db::name('order')->where('id', $order_id)->where('user_id', $user_id)->find();
            if (empty($order)) return arrayFailed('订单不存在');
            if ($order['status'] != 10) return arrayFailed('待支付的订单才可被取消');
            Db::name('order')->where('id', $order['id'])->update(['status' => -10]);
            // 恢复库存
            $order_snaps = Db::name('order_snap')->where('order_id', $order_id)->select();
            foreach ($order_snaps as $key => $value) {
                Db::name('product_sku')->where('sku', $value['sku'])->setInc('stock', $value['count']);
            }
            Db::commit();
            return arraySuccess();
        } catch (\Throwable $th) {
            Db::rollback();
            return arrayFailed();
        }
    }

    /**
     * 订单确认收货
     * @param int $order_id
     * @param int $user_id
     */
    public function confirmOrder($order_id, $user_id)
    {
        $order = Db::name('order')->where('id', $order_id)->where('user_id', $user_id)->find();
        if (empty($order)) return arrayFailed('订单不存在');
        Db::name('order')->where('id', $order['id'])->update(['status' => 40]);
        return arraySuccess();
    }

    /**
     * 订单删除
     * @param int $order_id
     * @param int $user_id
     * @return array
     */
    public function deleteOrder($order_id, $user_id)
    {
        $order = Db::name('order')->where('user_id', $user_id)->where('id', $order_id)->find();
        if (empty($order)) return arrayFailed('订单不存在');
        if (!in_array($order['status'], ['-10', 40])) return arrayFailed('已取消和已完成的订单才可被删除');
        Db::name('order')->where('id', $order['id'])->update(['status' => 99]);
        return arraySuccess();
    }

    /**
     * 订单申请退款
     */
    public function refund($order_id, $user_id)
    {
        $order = Db::name('order')->where('user_id', $user_id)->where('id', $order_id)->find();
        if (empty($order)) return arrayFailed('订单不存在');
        if ($order['status'] != 20) return arrayFailed('订单只有在已付款、待发货状态下，才可申请退款。');
        Db::name('order')->where('id', $order['id'])->update(['status' => 21]);
        return arraySuccess($data = '', $code = 200, $message = '申请成功，请等待商家确认。');
    }

    /**
     * Get Order Count
     * @param int $params['status']
     * @param int $params['user_id']
     * @return int
     */
    public function getCount($params = [])
    {
        $query = Db::name('order');
        $query->where('status', '<>', 99);
        if (isset($params['status']) && !empty($params['status'])) $query->where('status', $params['status']);
        if (isset($params['user_id']) && !empty($params['user_id'])) $query->where('user_id', $params['user_id']);
        return $query->count();
    }

    /**
     * 验证订单归属关系
     * @param int $order_id
     * @param int $user_id
     * @param int $status
     * @return array order data
     */
    public function validateAffiliation($order_id, $user_id, $status = '')
    {
        $query = Db::name('order')->where('id', $order_id)->where('user_id', $user_id);
        if (!empty($status)) $query->where('status', $status);
        $order = $query->find();
        if (empty($order)) return arrayFailed();
        return arraySuccess($order);
    }
}