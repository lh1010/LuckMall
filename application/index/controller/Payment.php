<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Web Payment Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\index\controller;

use think\Request;
use think\Db;
use app\repository\PaymentRepository;
use app\repository\OrderRepository;

class Payment extends Base
{
	protected $middleware = [
        'CheckUserLogin' => ['except' => ['alipay_notify', 'weixinpay_notify_app', 'weixinpay_notify_mp']],
    ];
    
    /**
     * 支付入口
     * 支持微信扫码支付、支付宝电脑端支付
     */
    public function index(Request $request)
    {
        $order_id = $request->get('order_id');
        $user_id = getUserId();
        $order = Db::name('order')->where(['id' => $order_id, 'user_id' => $user_id, 'status' => 10])->find();
        if (empty($order)) $this->error('订单异常', '/');

        $payment_id = $order['payment_id'];
        $params = ['order_id' => $order['id'], 'total_price' => $order['total_price'], 'user_id' => $order['user_id']];
        switch ($payment_id) {
            // 支付宝电脑端支付
            case 1:
                $res = app(PaymentRepository::class)->pay($payment_id, $params);
                break;
            // 微信扫码支付    
            case 2:
                $res = app(PaymentRepository::class)->pay($payment_id, $params);
                $this->assign('qrCode', $res['qrCode']);
                $this->assign('payment_log', $res['payment_log']);
                $res = $this->fetch('payment/weixinpay_native');
                break;
            default:
                $this->error('该支付方式未开通', '/');
                break;
        }
        return $res;
    }

    // 支付宝支付 异步通知
    public function alipay_notify()
    {
        app(PaymentRepository::class)->alipay_notify();
    }

    /**
     * 微信支付 异步通知
     * 统一下单 小程序
     */
    public function weixinpay_notify_app()
    {
        app(PaymentRepository::class)->weixinpay_notify($type = 'app');
    }

    /**
     * 微信支付 异步通知
     * 扫码支付
     */
    public function weixinpay_notify_mp()
    {
        app(PaymentRepository::class)->weixinpay_notify($type = 'mp');
    }

    /**
     * 订单支付结果页
     * @param int $order_id
     */
    public function payResult(Request $request)
    {
        $user_id = getUserId();
        $order_id = $request->get('order_id');
        $order = Db::name('order')->where('id', $order_id)->where('user_id', $user_id)->where('status', '<>', 99)->find();
        if (empty($order)) $this->error('订单不存在', '/'); 
        $this->assign('order', $order);
        return $this->fetch();
    }
}