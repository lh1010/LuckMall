<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Api Shop Validate
 * ============================================================================
 * Author: Jasper
 */

namespace app\api\validate;

use think\Validate;

class Shop extends Validate
{
    function __construct()
    {
        $this->rule = [
            'shop_id' => 'require|number',
        ];
        
        $this->message = [
            'shop_id.require' => 'Parameter cannot empty: shop_id',
            'shop_id.number' => 'Parameter format error: shop_id',
        ];

        $this->scene = [
            'getProductCount' => ['shop_id'],
        ];
    }
}
