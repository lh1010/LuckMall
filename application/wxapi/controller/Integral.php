<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Integral Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\wxapi\controller;

use think\Request;
use app\repository\IntegralRepository;

class Integral extends Base
{
    protected $middleware = [
        'CheckAppUserLogin' => ['except' => ['getProductsPaginate', 'getProduct']]
    ];

    public function getProductsPaginate(Request $request)
    {
        $products = app(IntegralRepository::class)->getProductsPaginate([], $request->post('page_size'));
        $products = $products->toArray();
        return jsonSuccess($products);
    }

    public function getProduct(Request $request)
    {
        $product = app(IntegralRepository::class)->getProduct($request->post('id'));
        return jsonSuccess($product);
    }

    public function getOrdersPaginate(Request $request)
    {
        $orders = app(IntegralRepository::class)->getOrdersPaginate(getUserId(), $page_size = $request->post('page_size'));
        $orders = $orders->toArray();
        return jsonSuccess($orders);
    }

    /**
     * 获取基础数据
     * @param string _token
     * @return json
     */
    public function getGaneralData()
    {
        $data = app(IntegralRepository::class)->getGaneralData(getUserId());
        return jsonSuccess($data);
    }

    /**
     * 兑换商品
     * @param int product_id
     */
    public function exchangeProduct(Request $request)
    {
        $params = [];
        $params['user_id'] = getUserId();
        $params['product_id'] = $request->post('product_id');
        $params['address_id'] = $request->post('address_id');
        $res = app(IntegralRepository::class)->exchangeProduct($params);
        return json($res);
    }

    public function getLogsPaginate(Request $request)
    {
        $params = [];
        $params['user_id'] = getUserId();
        $logs = app(IntegralRepository::class)->getLogsPaginate($params, $page_size = $request->post('page_size'));
        $logs = $logs->toArray();
        return jsonSuccess($logs);
    }

    public function getOrder(Request $request)
    {
        $order = app(IntegralRepository::class)->getOrder(['id' => $request->post('id'), 'user_id' => $request->post('user_id')]);
        return jsonSuccess($order);
    }
}
