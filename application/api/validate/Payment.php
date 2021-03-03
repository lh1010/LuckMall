<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Api Payment Validate
 * ============================================================================
 * Author: Jasper
 */

namespace app\api\validate;

use think\Validate;

class Payment extends Validate
{
    protected $rule = [
        'payment_id' => "require|validatePayment",
        'order_id' => "require",
    ];
    
    protected $message = [
        'payment_id.require' => '请选择支付方式',
        'payment_id.in' => '该支付方式未开启',
        'order_id.require' => '参数错误',
    ];

    protected $scene = [
        'index' => ['order_id', 'payment_id']
    ];

    protected function validatePayment($value, $rule, $param = [])
    {
        $payment = Config('payment.' . $value);
        if (empty($payment) || $payment['status'] != 1) return '当前支付方式未开通';
        return true;
    }
}
