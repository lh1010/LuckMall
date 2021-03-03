<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Api Product Validate
 * ============================================================================
 * Author: Jasper
 */

namespace app\api\validate;

use think\Validate;

class Product extends Validate
{
    function __construct()
    {
        $this->rule = [
            'product_id' => 'require|number',
            'shop_id' => 'require|number',
            'section_id' => 'require|number',
        ];
        
        $this->message = [
            'product_id.require' => '参数错误',
            'product_id.number' => '参数格式错误',
            'shop_id.require' => '参数错误',
            'shop_id.number' => '参数格式错误',
            'section_id.require' => '参数错误',
            'section_id.number' => '参数格式错误',
        ];

        $this->scene = [
            'collect' => ['product_id'],
            'getShopRecommend' => ['shop_id'],
            'getShopRand' => ['shop_id'],
            'getSection' => ['section_id']
        ];
    }
}
