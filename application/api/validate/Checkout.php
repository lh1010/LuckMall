<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利。
 * ----------------------------------------------------------------------------
 * Checkout Validate
 * ============================================================================
 * Author: Jasper
 */

namespace app\api\validate;

use think\Validate;

class Checkout extends Validate
{
    function __construct() 
    {
        $this->rule = [
            'sku' => "require",
            'count' => 'number'
        ];
        
        $this->message = [
            'sku.require' => '参数错误',
            'count.number' => '参数错误',
        ];

        $this->scene = [
            'onekeybuy' => ['sku', 'count'],
        ];
    }
}
