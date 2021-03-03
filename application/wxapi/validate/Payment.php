<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利。
 * ----------------------------------------------------------------------------
 * Payment Validate
 * ============================================================================
 * Author: Jasper
 */

namespace app\wxapi\validate;

use think\Validate;
use app\repository\PaymentRepository;

class Payment extends Validate
{
    protected $rule = [
        'order_id' => "require|number",
        'payment_id' => "require|number|in:3,4|validatePayment",
    ];
    
    protected $message = [
        'order_id.require' => '参数错误',
        'order_id.number' => '参数错误',
        'payment_id.require' => '请选择支付方式',
        'payment_id.number' => '请选择支付方式',
        'payment_id.in' => '不支持的支付方式',
    ];

    protected $scene = [
        'index' => ['order_id', 'payment_id'],
    ];

    protected function validatePayment($value, $rule, $param = [])
    {
        $payment = Config('payment.' . $value);
        if (empty($payment) || $payment['status'] != 1) return '当前支付方式未开通';
        return true;
    }
}
