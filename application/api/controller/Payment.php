<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Api Payment Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\api\controller;

use think\Request;
use think\Db;
use app\repository\PaymentRepository;
use app\repository\OrderRepository;
use app\repository\UserRepository;

class Payment extends Base
{
	protected $middleware = ['CheckUserLogin'];

    /**
     * 支付入口
     * 分配支付方式
     */
    public function index(Request $request)
    {
        $res = $this->validate($request->post(), 'Payment.index');
        if ($res !== true) return jsonFailed($res);

        $payment_id = $request->post('payment_id');
        $order_id = $request->post('order_id');
        $user_id = getUserId();

        $order = Db::name('order')->where(['id' => $order_id, 'user_id' => $user_id, 'status' => 10])->find();
        if (empty($order)) return jsonFailed('订单异常');
        Db::name('order')->where('id', $order_id)->update(['payment_id' => $payment_id]);

        // 需在新页面完成的支付方式
        if (in_array($payment_id, [1, 2])) {
            $url = '/payment.html?order_id=' . $order_id;
            return arraySuccess(['url' => $url], $code = 200, $message = '正在跳转...');
        }

        $params = ['order_id' => $order['id'], 'total_price' => $order['total_price'], 'user_id' => $order['user_id']];
        switch ($payment_id) {
            // 余额支付
            case 4:
                $wallet = app(UserRepository::class)->getUserWallet($order['user_id']);
                if ($wallet < $order['total_price']) return arrayFailed('余额不足');
                $res = app(PaymentRepository::class)->pay($payment_id, $params);
                break;
        }

        if ($res['code'] != 200) return jsonFailed('支付失败');
        return jsonSuccess('支付成功');
    }

    public function check_is_pay(Request $request)
    {
        return app(PaymentRepository::class)->check_is_pay($order_id = $request->param('order_id'), $user_id = getUserId());
    }
}
