<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Index ShopApply Validate
 * ============================================================================
 * Author: Jasper
 */

namespace app\index\validate;

use think\Validate;

class ShopApply extends Validate
{
    protected $rule = [
        'shop_type' => 'require|in:1,2',  
    ];

    protected $scene = [
        'authInfo'  =>  ['shop_type'],
    ];
}
