<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Index Checkout Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\index\controller;

use think\Request;
use think\Db;
use app\repository\AddressRepository;
use app\repository\CartRepository;
use app\repository\PaymentRepository;
use app\repository\OrderRepository;

class Checkout extends Base
{
	protected $middleware = ['CheckUserLogin'];

    public function index(Request $request)
    {
        $this->assign('addresses', app(AddressRepository::class)->getAddresses($user_id = getUserId()));
        return $this->fetch();
    }

    public function onekeybuy(Request $request)
    {
        $res = $this->validate($request->param(), 'Checkout.onekeybuy');
        if ($res !== true) abort(404);
        $this->assign('addresses', app(AddressRepository::class)->getAddresses($user_id = getUserId()));
        return $this->fetch();
    }

    public function pay(Request $request)
    {
        $user_id = getUserId();
        $order = Db::name('order')->where('id', $request->order_id)->where('user_id', $user_id)->where('status', '<>', 99)->find();
        if (empty($order)) return redirect(url('account/order'));
        if ($order['status'] != 10) return redirect(url('payment/payResult') . '?order_id=' . $order['id']);
        $this->assign('order', $order);
        $payments = ['1' => Config('payment.1'), '2' => Config('payment.2'), '4' => Config('payment.4')];
        $this->assign('payments', $payments);
        return $this->fetch();
    }
}