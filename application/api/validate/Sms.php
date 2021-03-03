<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Api Sms Validate
 * ============================================================================
 * Author: Jasper
 */

namespace app\api\validate;

use think\Validate;

class Sms extends Validate
{
    protected $rule = [
        'code' => 'require|captcha',
        'phone' => "require|mobile",
    ];

    protected $message = [
        'phone.require' => '手机号不能为空',
        'phone.mobile' => '手机号格式不正确',
        'code.require' => '验证码不能为空',
        'code.captcha' => '验证码错误',
    ];

    protected $scene = [
        'register' => ['phone', 'code'],
        'login' => ['phone'],
        'set_phone' => ['phone'],
    ];
}
