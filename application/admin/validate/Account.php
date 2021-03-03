<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Admin Account Validate
 * ============================================================================
 * Author: Jasper      
 */

namespace app\admin\validate;

use think\Validate;

class Account extends Validate
{
    protected $rule = [
        'username' => 'require',
        'password' => 'require|length:5,20',
    ];
    
    protected $message = [
        'username.require' => '用户名不能为空',
        'username.unique' => '用户名已存在',
        'password.require' => '登录密码不能为空',
        'password.length' => '登录密码长度为5-20个字符',
    ];

    protected $scene = [
        'doLogin' => ['username','password'],
        'updatePassword' => ['password'],
    ];
}
