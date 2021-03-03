<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Payment Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\wxapi\controller;

use think\Request;
use think\Db;
use app\repository\PaymentRepository;

class Payment extends Base
{
    protected $middleware = [
        'CheckAppUserLogin' => ['except' => ['getPayments', 'weixinPayNotify']],
    ];

    public function index(Request $request)
    {
        $res = $this->validate($request->param(), 'Payment.index');
        if ($res !== true) return jsonFailed($res);
        Db::startTrans();
        try {
            $payment_id = $request->post('payment_id');
            $order_id = $request->post('order_id');
            $user_id = getUserId();
            $order = Db::name('order')->where('user_id', $user_id)->where('id', $order_id)->where('status', 10)->find();
            if (empty($order)) return jsonFailed('订单异常');
            Db::name('order')->where('id', $order_id)->update(['payment_id' => $payment_id]);

            if ($payment_id == 4) {
                $wallet = app(\app\repository\UserRepository::class)->getUserWallet($user_id);
                if ($wallet < $order['total_price']) {
                    Db::rollback();
                    return jsonFailed('余额不足');
                }
            }

            $params = ['order_id' => $order['id'], 'total_price' => $order['total_price'], 'user_id' => $user_id];
            $res = app(\app\repository\PaymentRepository::class)->pay($payment_id, $params);
            Db::commit();
            return json($res);
        } catch (\Throwable $th) {
            Db::rollback();
            return jsonFailed($th->getMessage());
        }
    }

    public function getPayments()
    {
        $payments = ['3' => Config('payment.3'), '4' => Config('payment.4')];
        return jsonSuccess($payments);
    }

    public function weixinPayNotify()
    {
        app(PaymentRepository::class)->weixinPayNotify();
    }
}
