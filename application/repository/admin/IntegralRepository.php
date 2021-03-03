<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Integral Repository Admin
 * ============================================================================
 * Author: Jasper   
 */

namespace app\repository\admin;

use think\Db;

class IntegralRepository
{
    public function getProductsPaginate($params = [])
    {
        $query = Db::name('integral_product');
        $this->setGetProductsParams($query, $params);
        $query->order('sort', 'desc')->order('id', 'desc');
        $products = $query->paginate();
        $products = $this->setImageShow($products);
        return $products;
    }

    private function setGetProductsParams($query, $params = [])
    {
        if (isset($params['keyword']) && !empty($params['keyword'])) $query->where('name', 'like', '%'.$params['keyword'].'%');
        if (isset($params['status']) && $params['status'] !== '') $query->where('status', $params['status']);
    }

    public function getProduct($id)
    {
        $product = Db::name('integral_product')->where('id', $id)->find();
        return $product;
    }

    public function createProduct($params)
    {
        $data = $this->setCreateUpdateProductData($params);
        Db::name('integral_product')->insert($data);
        return arraySuccess();
    }

    public function updateProduct($params = [], $id)
    {
        $query = Db::name('integral_product');
        $data = $this->setCreateUpdateProductData($params);
        $query->where('id', $id)->update($data);
        return arraySuccess();
    }

    private function setCreateUpdateProductData($params = [])
	{
		$data = [];
        if (isset($params['name'])) $data['name'] = $params['name'];
		if (isset($params['image'])) $data['image'] = $params['image'];
		if (isset($params['content'])) $data['content'] = $params['content'];
        if (isset($params['integral'])) $data['integral'] = $params['integral'];
        if (isset($params['sort'])) $data['sort'] = $params['sort'];
        if (isset($params['status'])) $data['status'] = $params['status'];
        return $data;
    }
    
    /**
     * Set Product image Show
     * @param array $data
     * @param int $type 1|2|3
     * @return array
     */
    public function setImageShow($data, $type = 3)
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

    public function deleteProduct($id)
    {
        Db::name('integral_product')->whereIn('id', $id)->delete();
        return arraySuccess();
    }

    public function getOrdersPaginate($params = [])
    {
        $select = 'integral_order.*, user.nickname as nickname';
        $query = Db::name('integral_order')->alias('integral_order');
        $query->field($select);
        $query->leftJoin('user', 'user.id = integral_order.user_id');
        $query->order('integral_order.id', 'desc');
        if (isset($params['keyword']) && !empty($params['keyword'])) $query->where('integral_order.id', $params['keyword']);
        $orders = $query->paginate();
        return $orders;
    }
}