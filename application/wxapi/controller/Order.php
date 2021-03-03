<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Order Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\wxapi\controller;

use think\Request;
use think\Db;
use app\repository\OrderRepository;

class Order extends Base
{
    protected $middleware = ['CheckAppUserLogin'];

    public function getOrdersPaginate(Request $request)
    {
        $params = [];
        $params['user_id'] = getUserId();
        $page_size = $request->has('page_size') ? $request->param('page_size') : 6;
        if (in_array($request->param('status'), array_keys(app(OrderRepository::class)->statusArray))) $params['status'] = $request->param('status');
        $orders = app(OrderRepository::class)->getOrdersPaginate($params, $page_size);
        return jsonSuccess($orders);
    }

    public function getOrder(Request $request)
    {
        $order = app(OrderRepository::class)->getOrder($id = $request->param('id'), $user_id = getUserId());
        return jsonSuccess($order);
    }

    public function create(Request $request)
    {
        $res = $this->validate($request->param(), 'Order.create');
        if ($res !== true) return jsonFailed($res);
        $params = [];
        $params['user_id'] = getUserId();
        $params['address_id'] = $request->param('address_id');
        $params['payment_id'] = $request->param('payment_id');
        $params['message'] = $request->param('message');
        $params['type'] = $request->param('type');
        if ($params['type'] == 'onekeybuy') {
            $params['sku'] = $request->param('sku');
            $params['count'] = $request->param('count');
        }
        $res = app(\app\wxapi\repository\OrderRepository::class)->create($params);
        return json($res);
    }

    public function confirmReceipt(Request $request)
    {
        $res = $this->validate($request->param(), 'Order.confirmReceipt');
        if ($res !== true) return jsonFailed($res);
        Db::name('order')
        ->where('user_id', getUserId())
        ->where('id', $request->param('id'))
        ->where('status', 30)
        ->update(['status' => 40]);
        return jsonSuccess();
    }

    public function cancelOrder(Request $request)
    {
        $res = $this->validate($request->param(), 'Order.cancelOrder');
        if ($res !== true) return jsonFailed($res);
        $res = app(OrderRepository::class)->cancelOrder($order_id = $request->param('id'), $user_id = getUserId());
        return json($res);
    }

    public function deleteOrder(Request $request)
    {
        $res = $this->validate($request->param(), 'Order.deleteOrder');
        if ($res !== true) return jsonFailed($res);
        $res = app(OrderRepository::class)->deleteOrder($order_id = $request->param('id'), $user_id = getUserId());
        return json($res);
    }
}
