<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Index Checkout Validate
 * ============================================================================
 * Author: Jasper
 */

namespace app\index\validate;

use think\Validate;
use app\repository\ProductRepository;

class Checkout extends Validate
{
    function __construct() 
    {
        $this->rule = [
            'number' => 'require',
            'count' => 'require|number',
            'sku' => 'require',
        ];
        
        $this->message = [
            'number.require' => '参数错误',
            'count.require' => '参数错误',
            'count.number' => '参数错误',
            'sku.require' => '参数错误',
        ];

        $this->scene = [
            'payResult' => ['number'],
            'onekeybuy' => ['count', 'sku'],
        ];
    }
}
