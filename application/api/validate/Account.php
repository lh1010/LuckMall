<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Api Account Validate
 * ============================================================================
 * Author: Jasper
 */

namespace app\api\validate;

use think\Validate;
use think\Request;
use think\Db;
use think\facade\Cookie;
use app\repository\MailRepository;

class Account extends Validate
{
    protected $rule = [
        'username' => 'require',
        'password' => 'require|length:6,20',
        'phoneCode' => 'require',
        'phone' => "require|mobile|unique:user",
        'securityModel.type' => 'require|in:password,email,phone',
        'securityModel.password' => 'require|length:6,20',
        'securityModel.emailCode' => 'require',
        'securityModel.phoneCode' => 'require',
    ];

    protected $message = [
        'phone.unique' => '该手机号已存在',
        'phone.require' => '手机号不能为空',
        'phone.mobile' => '手机号格式不正确',
        'username.require' => '账户名不能为空',
        'password.require' => '密码不能为空',
        'password.length' => '密码长度必须为6到20位',
        'phoneCode.require' => '验证码不能为空',
        'securityModel.type.require' => '验证身份方式不能为空',
        'securityModel.type.in' => '不存在的验证身份方式',
        'securityModel.password.require' => '密码不能为空',
        'securityModel.password.length' => '密码长度必须为6到20位',
        'securityModel.emailCode.require' => '验证码不能为空',
        'securityModel.phoneCode.require' => '验证码不能为空',
    ];

    protected $scene = [
        'register' => ['phone', 'password', 'phoneCode'],
        'login_default' => ['username', 'password'],
        'login_sms' => ['phone', 'phoneCode'],
        'validateSecurity' => ['securityModel.type'],
        'validateSecurityPassword' => ['securityModel.password'],
        'validateSecurityEmail' => ['securityModel.emailCode'],
        'validateSecurityPhone' => ['securityModel.phoneCode'],
    ];

    /**
     * 
     */
    public function sceneLogin_sms()
    {
        return $this->only(['phone', 'phoneCode'])->remove('phone', 'unique');
    }
}
