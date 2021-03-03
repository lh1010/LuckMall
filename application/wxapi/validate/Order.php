<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Order Validate
 * ============================================================================
 * Author: Jasper
 */

namespace app\wxapi\validate;

use think\Validate;
use app\repository\PaymentRepository;

class Order extends Validate
{
    protected $rule = [
        'type' => "require|in:cart,onekeybuy",
        'address_id' => "require|number",
        'payment_id' => "require|number|in:3,4|validatePayment",
        'id' => "require|number",
        'sku' => "require"
    ];
    
    protected $message = [
        'type.require' => '参数错误',
        'type.in' => '参数错误',
        'address_id.require' => '请选择地址',
        'address_id.number' => '请选择地址',
        'payment_id.require' => '请选择支付方式',
        'payment_id.number' => '请选择支付方式',
        'payment_id.in' => '不支持的支付方式',
        'id.require' => '参数错误',
        'id.number' => '参数错误',
        'sku.number' => '参数错误',
    ];

    protected $scene = [
        'create' => ['type', 'address_id', 'payment_id'],
        'create_order_onekeybuy' => ['sku'],
        'confirmReceipt' => ['id'],
        'cancelOrder' => ['id'],
        'deleteOrder' => ['id'],
    ];

    protected function validatePayment($value, $rule, $param = [])
    {
        $payment = Config('payment.' . $value);
        if (empty($payment) || $payment['status'] != 1) return '当前支付方式未开通';
        return true;
    }
}
