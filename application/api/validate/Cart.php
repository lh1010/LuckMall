<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利。
 * ----------------------------------------------------------------------------
 * Api Cart Validate
 * ============================================================================
 * Author: Jasper
 */

namespace app\api\validate;

use think\Validate;
use think\Db;

class Cart extends Validate
{
    function __construct() 
    {
        $this->rule = [
            'sku' => "require",
            'count' => 'integer|validateData',
        ];
        
        $this->message = [
            'sku.require' => '参数错误',
            'count.integer' => '参数错误',
        ];

        $this->scene = [
            'addCart' => ['sku', 'count'],
            'setCount' => ['sku', 'count'],
        ];
    }

    protected function validateData($value, $rule, $param = [])
    {
        $user_id = getUserId();
        $cart = Db::name('cart')->where('user_id', $user_id)->where('sku', $param['sku'])->find();
        $stock = Db::name('product_sku')->where('sku', $param['sku'])->value('stock');
        if (!$stock) return '产品库存不足';
        if ($cart['count'] + $value > $stock) return '产品库存不足';
        return true;        
    }
}
