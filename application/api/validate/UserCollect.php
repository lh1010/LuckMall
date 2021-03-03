<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Api UserCollect Validate
 * ============================================================================
 * Author: Jasper
 */

namespace app\api\validate;

use think\Validate;

class UserCollect extends Validate
{
    function __construct()
    {
        $this->rule = [
            'shop_id' => 'require|number',
            'product_id' => 'require|number',
        ];
        
        $this->message = [
            'shop_id.require' => '参数错误',
            'shop_id.number' => '参数格式错误',
            'product_id.require' => '参数错误',
            'product_id.number' => '参数格式错误',
        ];

        $this->scene = [
            'collectShop' => ['shop_id'],
            'collectProduct' => ['product_id'],
            'getCollectShop' => ['shop_id'],
        ];
    }
}
