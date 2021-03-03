<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Api Mail Validate
 * ============================================================================
 * Author: Jasper
 */

namespace app\api\validate;

use think\Validate;

class Mail extends Validate
{
    function __construct()
    {
        $this->rule = [
            'code' => "require|captcha",
            'email' => "require|email",
        ];
        
        $this->message = [
            'email.require' => '邮箱不能为空',
            'email.email' => '邮箱格式不正确',
            'code.require' => '验证码不能为空',
            'code.captcha' => '验证码错误',
        ];

        $this->scene = [
            'register' => ['email', 'code'],
            'set_email' => ['email'],
        ];
    }
}
