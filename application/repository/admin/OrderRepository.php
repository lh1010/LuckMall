<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Order Repository Admin
 * ============================================================================
 * Author: Jasper
 */

namespace app\repository\admin;

use think\Db;

class OrderRepository
{
    public $statusArray = ['-10' => '已取消', '10' => '待付款', '20' => '待发货', '30' => '待收货', '40' => '已完成'];

    public $typeArray = ['cart' => '购物车', 'onekeybuy' => '立即购买'];

    public function getOrdersPaginate($params = [])
    {
        $field = 'order.*, user.nickname as user_nickname';
        $query = Db::name('order')->alias('order');
        $query->field($field);
        $query->leftJoin('user', 'user.id = order.user_id');
        $this->setGetOrdersParams($query, $params);
        $query->where('order.status', '<>', 99);
        $query->order('order.id desc');
        $orders = $query->paginate();
        if ($orders->total() < 1) return $orders;
        
        $orders->each(function($value, $key) {
            $value['status_str'] = $this->statusArray[$value['status']];
            $value['payment_name'] = Config('payment.' . $value['payment_id'] . '.name');
            return $value;
        });
        return $orders;
    }

    public function getOrders($params = [], $limit = 10)
    {
        $field = 'order.*, user.nickname as user_nickname';
        $query = Db::name('order')->alias('order');
        $query->field($field);
        $query->leftJoin('user', 'user.id = order.user_id');
        $this->setGetOrdersParams($query, $params);
        $query->where('order.status', '<>', 99);
        $query->order('order.id desc');
        $query->limit($limit);
        $orders = $query->select();
        if (empty($orders)) return $orders;
        foreach ($orders as $key => $value) {
            $orders[$key]['status_str'] = $this->statusArray[$value['status']];
            $orders[$key]['payment_name'] = Config('payment.' . $value['payment_id'] . '.name');
        }
        return $orders;
    }

    private function setGetOrdersParams($query, $params)
    {
        if (isset($params['keyword']) && !empty($params['keyword'])) $query->where('order.number', $params['keyword']);
        if (isset($params['status']) && !empty($params['status'])) $query->where('order.status', $params['status']);
        if (isset($params['start_time']) && !empty($params['start_time'])) $query->where('order.create_time', '>=', $params['start_time']);
        if (isset($params['end_time']) && !empty($params['end_time'])) $query->where('order.create_time', '<=', $params['end_time']);
        if (isset($params['payment_id']) && !empty($params['payment_id'])) $query->where('order.payment_id', $params['payment_id']);
    }

    public function getOrder($id)
    {
        $field = 'order.*, user.nickname as user_nickname';
        $query = Db::name('order')->alias('order');
        $query->field($field);
        $query->where('order.id', $id);
        $query->leftJoin('user', 'user.id = order.user_id');
        $order = $query->find();
        if (empty($order)) return $order;
        $order['status_str'] = $this->statusArray[$order['status']];
        $order['type_str'] = $this->typeArray[$order['type']];
        $order['payment_name'] = Config('payment.' . $order['payment_id'] . '.name');
        $snaps = Db::name('order_snap')->where('order_id', $id)->select();
        foreach ($snaps as $key => $value) {
            $snaps[$key]['specifications'] = json_decode($value['specifications'], 1);
        }
        $order['snaps'] = $snaps;
        if ($order['status'] > 20) {
            $order['tracking'] = Db::name('order_tracking')->where('order_id', $id)->find();
        }
        return $order;
    }

    public function setOrderStatus($order_id, $status)
    {
        $order = Db::name('order')->where('id', $order_id)->find();
        if (empty($order)) return arrayFailed('订单不存在');
        switch ($status) {
            // 取消订单
            case '-10':
                if ($order['status'] == 10 || ($order['status'] == 20 && $order['payment_id'] == 5)) {

                } else {
                    return arrayFailed();
                }
                break;
            // 确认收货    
            case 40:
                if ($order['status'] != 30) return arrayFailed();
                break;    
            default:
                return arrayFailed('无效订单状态值');
                break;
        }
        Db::name('order')->where('id', $order_id)->update(['status' => $status]);
        return arraySuccess();
    }

    public function shipment($params, $order_id)
    {
        $order = Db::name('order')->where('id', $order_id)->find();
        if ($order['status'] != 20) return arrayFailed('订单状态异常');
        $data['order_id'] = $order_id;
        $data['shipping_mark_id'] = $params['shipping_mark_id'];
        $data['shipping_mark_name'] = Db::name('shipping_mark')->where('id', $params['shipping_mark_id'])->value('name');
        $data['tracking_number'] = $params['tracking_number'];
        Db::startTrans();
        try {
            Db::name('order_tracking')->insert($data);
            Db::name('order')->where('id', $order_id)->update(['status' => 30]);
            Db::commit();
            return arraySuccess();
        } catch (\Throwable $th) {
            Db::rollback();
            return arrayFailed();
        }
    }
}