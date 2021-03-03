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

namespace app\api\controller;

use think\Request;
use app\repository\OrderRepository;

class Order extends Base
{
	protected $middleware = ['CheckUserLogin'];

    public function create(Request $request)
    {
        $res = $this->validate($request->param(), 'Order.create');
        if ($res !== true) return jsonFailed($res);
        $params = [];
        $params['user_id'] = getUserId();
        $params['address_id'] = $request->param('address_id');
        $params['payment_id'] = $request->param('payment_id');
        $params['message'] = $request->param('message');
        switch ($request->type) {
            case 'cart':
                $res = app(OrderRepository::class)->create_order_cart($params);
                break;
            case 'onekeybuy':
                $res = $this->validate($request->param(), 'Order.create_onekeybuy');
                if ($res !== true) return jsonFailed($res);
                $params['sku'] = $request->sku;
                $params['count'] = $request->count;
                $res = app(OrderRepository::class)->create_order_onekeybuy($params);
                break;
        }
        return json($res);
    }

    // 订单取消
    public function cancel(Request $request)
    {
        return app(OrderRepository::class)->cancelOrder($request->param('order_id'), $user_id = getUserId());
    }

    // 订单删除
    public function delete(Request $request)
    {
        return app(OrderRepository::class)->deleteOrder($request->param('order_id'), $user_id = getUserId());
    }
    
    // 订单确认收货
    public function confirm(Request $request)
    {
        return app(OrderRepository::class)->confirmOrder($request->param('order_id'), $user_id = getUserId());
    }

    // 申请退款
    public function refund(Request $request)
    {
        return app(OrderRepository::class)->refund($request->param('order_id'), $user_id = getUserId());
    }
}
