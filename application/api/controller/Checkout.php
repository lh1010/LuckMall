<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Api Checkout Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\api\controller;

use think\Request;
use app\repository\CheckoutRepository;

class Checkout extends Base
{
    protected $middleware = ['CheckUserLogin'];

    public function index(Request $request)
    {
        $params = [];
        $params['user_id'] = getUserId();
        if ($request->has('address_id')) $params['address_id'] = $request->param('address_id');
        $data = app(CheckoutRepository::class)->initData_cart($params);
        return jsonSuccess($data);
    }

    public function onekeybuy(Request $request)
    {
        $res = $this->validate($request->param(), 'Checkout.onekeybuy');
        if ($res !== true) return jsonFailed($res);
        $params = [];
        $params['user_id'] = getUserId();
        $params['sku'] = $request->param('sku');
        $params['count'] = $request->has('count') ? $request->param('count') : 1;
        if ($request->has('address_id')) $params['address_id'] = $request->param('address_id');
        $data = app(CheckoutRepository::class)->initData_onekeybuy($params);
        return jsonSuccess($data);
    }
}
