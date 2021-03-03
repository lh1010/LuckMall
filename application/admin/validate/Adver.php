<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Admin Adver Validate
 * ============================================================================
 * Author: Jasper 
 */

namespace app\admin\validate;

use think\Validate;

class Adver extends Validate
{
    function __construct()
    {
        $this->rule = [
            'id' => 'require',
            'name' => 'require'
        ];
        
        $this->message = [
            'id.require' => 'ID不能为空',
            'name.require' => '广告名不能为空',
        ];

        $this->scene = [
            'store' => ['name'],
            'update' => ['id', 'name'],
            'delete' => ['id'],
        ];
    }
}
