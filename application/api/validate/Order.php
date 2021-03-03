<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利。
 * ----------------------------------------------------------------------------
 * Order Validate
 * ============================================================================
 * Author: Jasper
 */

namespace app\api\validate;

use think\Validate;
use app\repository\PaymentRepository;


class Order extends Validate
{
    function __construct() 
    {
        $this->rule = [
            'type' => "require|in:cart,onekeybuy",
            'address_id' => "require|number",
            'id' => "require|number",
            'sku' => "require"
        ];
        
        $this->message = [
            'type.require' => '参数错误',
            'type.in' => '参数错误',
            'address_id.require' => '请选择地址',
            'address_id.number' => '请选择地址',
            'id.require' => '参数错误',
            'id.number' => '参数错误',
            'sku.number' => '参数错误',
        ];

        $this->scene = [
            'create' => ['type', 'address_id'],
            'create_onekeybuy' => ['sku'],
            'confirmReceipt' => ['id'],
            'cancelOrder' => ['id'],
            'deleteOrder' => ['id'],
        ];
    }
}
