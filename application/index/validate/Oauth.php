<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Index Oauth Validate
 * ============================================================================
 * Author: Jasper
 */

namespace app\index\validate;

use think\Validate;

class Oauth extends Validate
{
    protected $rule = [
        'entrance' => 'require|in:bind,login,register',
    ];
    
    protected $message = [
        'entrance.require' => '参数错误',
        'entrance.in' => '参数错误',
    ];

    protected $scene = [
        'validateEntrance' => ['entrance'],
    ];
}
