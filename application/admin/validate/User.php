<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Admin User Validate
 * ============================================================================
 * Author: Jasper
 */

namespace app\admin\validate;

use think\Validate;

class User extends Validate
{
    function __construct()
    {
        $this->rule = [
            'id' => 'require',
            '__token__' => 'token'
        ];
        
        $this->message = [
            'id.require' => '缺少必需参数：id',
        ];

        $this->scene = [
            'delete' => ['id'],
        ];
    }
}
