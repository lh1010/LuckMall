<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Admin Section Validate
 * ============================================================================
 * Author: Jasper 
 */

namespace app\admin\validate;

use think\Validate;

class Section extends Validate
{
    protected $rule = [
        'id' => 'require',
        'name' => 'require',
    ];
    
    protected $message = [
        'id.require' => '参数错误',
        'name.require' => '版块名不能为空',
    ];

    protected $scene = [
        'store' => ['name'],
        'update' => ['id', 'name'],
        'delete' => ['id'],
    ];
}
