<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Integral Repository
 * ============================================================================
 * Author: Jasper   
 */

namespace app\repository;

use think\Db;
use app\repository\UserRepository;
use app\repository\AddressRepository;

class IntegralRepository
{
    public function createLog($params)
    {
        $data = $this->setCreateUpdateLogData($params);
        Db::name('integral_log')->insert($data);
        return arraySuccess();
    }

    private function setCreateUpdateLogData($params = [])
	{
		$data = [];
        if (isset($params['user_id'])) $data['user_id'] = $params['user_id'];
		if (isset($params['integral'])) $data['integral'] = $params['integral'];
        if (isset($params['ident'])) $data['ident'] = $params['ident'];
        if (isset($params['type'])) $data['type'] = $params['type'];
		if (isset($params['description'])) $data['description'] = $params['description'];
        return $data;
    }
    
    public function getProducts($params = [])
    {
        $query = Db::name('integral_product');
        $this->setGetProductsParams($query, $params);
        $query->where('status', 1);
        $query->order('sort', 'desc')->order('id', 'desc');
        $products = $query->select();
        $products = $this->setImageShow($products);
        return $products;
    }

    public function getProductsPaginate($params = [], $page_size = 15)
    {
        $query = Db::name('integral_product');
        $this->setGetProductsParams($query, $params);
        $query->where('status', 1);
        $query->order('sort', 'desc')->order('id', 'desc');
        $products = $query->paginate($page_size);
        $products = $this->setImageShow($products, $type = 3);
        return $products;
    }

    private function setGetProductsParams($query, $params = [])
    {
        if (isset($params['keyword']) && !empty($params['keyword'])) $query->where('name', 'like', '%'.$params['keyword'].'%');
    }

    public function getProduct($id)
    {
        $product = Db::name('integral_product')->where('id', $id)->where('status', 1)->find();
        $product = $this->setImageShow($product, $type = 1);
        return $product;
    }

    /**
     * Set Product image Show
     * @param array $data
     * @param int $type 1|2|3
     * @return array
     */
    public function setImageShow($data, $type = 2)
    {
        if (empty($data)) return [];
        switch ($type) {
            case '1':
                empty($data['image']) ? $data['image'] = Config('image.integral_product_default') : null;
                break;
            case '2':
                foreach ($data as $key => $value) {
                    empty($value['image']) ? $data[$key]['image'] = Config('image.integral_product_default') : null;
                }
                break;    
            case '3':
                $data->each(function($item, $key) {
                    if (empty($item['image'])) {
                        $item['image'] = Config('image.integral_product_default');
                    }
                    return $item;
                });
                break;
        }
        return $data;
    }

    public function getGaneralData($user_id)
    {
        $data = [];
        $user = app(UserRepository::class)->getUser(['id' => $user_id]);
        $data['avatar'] = $user['avatar'];
        $data['integral'] = $user['integral'];
        return $data;
    }

    /**
     * 兑换商品
     * @param int $params['user_id']
     * @param int $params['product_id']
     * @param int $params['address_id']
     */
    public function exchangeProduct($params)
    {
        Db::startTrans();
        try {
            $user_integral = app(UserRepository::class)->getUserIntegral($params['user_id']);
            $product = Db::name('integral_product')->where('id', $params['product_id'])->where('status', 1)->find();
            if (empty($product['integral'])) return arrayFailed('商品不存在');
            if ($product['integral'] > $user_integral) return arrayFailed('积分不足');
            $address = app(AddressRepository::class)->getAddress($params['address_id'], $params['user_id']);
            if (empty($address)) return arrayFailed('地址不存在');
            Db::name('user')->where('id', $params['user_id'])->setDec('integral', $product['integral']);
            // 创建积分记录
            $this->createLog(['user_id' => $params['user_id'], 'integral' => $product['integral'], 'ident' => 'dec', 'description' => '兑换积分商品', 'type' => 'exchange_product']);
            $data = ['user_id' => $params['user_id'],
                    'product_id' => $params['product_id'],
                    'product_name' => $product['name'],
                    'product_image' => $product['image'],
                    'product_integral' => $product['integral'],
                    'name' => $address['name'],
                    'province_name' => $address['province_name'],
                    'city_name' => $address['city_name'],
                    'district_name' => $address['district_name'],
                    'detailed_address' => $address['detailed_address'],
                    'phone' => $address['phone']
                ];
            // 创建兑换记录
            Db::name('integral_order')->insert($data);
            Db::commit();
            return arraySuccess();
        } catch (\Throwable $th) {
            Db::rollback();
            return arrayFailed($th->getMessage());
        }
    }

    public function getOrdersPaginate($user_id, $page_size = 15)
    {
        $orders = Db::name('integral_order')
                ->where('user_id', $user_id)
                ->order('id', 'desc')
                ->paginate($page_size);
        return $orders;
    }

    public function getOrder($params = [])
    {
        $query = Db::name('integral_order');
        if (isset($params['id'])) $query->where('id', $params['id']);
        if (isset($params['user_id'])) $query->where('user_id', $params['user_id']);
        $order = $query->find();
        return $order;
    }

    public function getLogsPaginate($params = [], $page_size = 15)
    {
        $query = Db::name('integral_log');
        if (isset($params['user_id'])) $query->where('user_id', $params['user_id']);
        $query->order('id', 'desc');
        $logs = $query->paginate($page_size)->each(function($value, $key) {
            $value['integral_addition'] = $value['ident'] == 'inc' ? '+' . $value['integral'] : '-' . $value['integral'];
            return $value;
        });
        return $logs;
    }
}