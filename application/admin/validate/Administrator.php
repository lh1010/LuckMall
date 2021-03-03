<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Admin Brand Validate
 * ============================================================================
 * Author: Jasper
 */

namespace app\admin\validate;

use think\Validate;

class Administrator extends Validate
{
    protected $rule = [
        'id' => 'require',
        'username' => 'require|unique:administrator',
        'password' => 'require|length:5,20',
        'update_password' => 'length:5,20',
        '__token__' => 'token'
    ];
    
    protected $message = [
        'id.require' => '参数错误',
        'username.require' => '用户名不能为空',
        'username.unique' => '用户名已存在',
        'password.require' => '登录密码不能为空',
        'password.length' => '登录密码长度为5-20个字符',
        'update_password.length' => '登录密码长度为5-20个字符',
        '__token__.token' => '重复提交，请刷新页面'
    ];

    protected $scene = [
        'store' => ['username', 'password', '__token__'],
        'update' => ['id', 'username', 'update_password', '__token__'],
        'delete' => ['id']
    ];
}
