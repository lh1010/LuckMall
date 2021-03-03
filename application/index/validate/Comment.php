<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Index Comment Validate
 * ============================================================================
 * Author: Jasper
 */

namespace app\index\validate;

use think\Validate;

class Comment extends Validate
{
    function __construct() 
    {
        $this->rule = [
            'order_id' => 'require|number',
        ];
        
        $this->message = [
            'order_id.require' => 'param error',
            'order_id.number' => 'param error',
        ];

        $this->scene = [
            'create' => ['order_id'],
        ];
    }
}
