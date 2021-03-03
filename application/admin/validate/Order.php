<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Admin Order Validate
 * ============================================================================
 * Author: Jasper
 */

namespace app\admin\validate;

use think\Validate;

class Order extends Validate
{
    function __construct() 
    {
        $this->rule = [
            'shipping_mark_id' => 'require',
            'tracking_number' => 'require',
            '__token__' => 'token'
        ];
        
        $this->message = [
            'shipping_mark_id.require' => '配送方式不能为空',
            'tracking_number.require' => '快递单号不能为空',
            '__token__.token' => '重复提交，请刷新页面',
        ];

        $this->scene = [
            'updateShipment' => ['name', 'sort', '__token__'],
        ];
    }
}
