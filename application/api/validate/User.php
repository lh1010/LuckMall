<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Api User Validate
 * ============================================================================
 * Author: Jasper
 */

namespace app\api\validate;

use think\Validate;

class User extends Validate
{
    function __construct()
    {
        $this->rule = [
            'nickname' => "require|length:1,11",
            'avatar' => "require",
            'real_name' => "require",
            'card_number' => "require",
            'type' => "require|in:qq,weibo,weixin",
            'password' => "require|length:6,20",
            'email' => "require|email",
            'emailCode' => "require",
            'phone' => "require|mobile",
            'phoneCode' => "require",
        ];
        
        $this->message = [
            'nickname.require' => '昵称不能为空',
            'nickname.length' => '昵称长度不能超过11位字符',
            'avatar.require' => '请上传头像',
            'real_name.require' => '真实姓名不能为空',
            'card_number.require' => '身份证号不能为空',
            'type.require' => '参数错误',
            'type.in' => '参数错误',
            'password.require' => '密码不能为空',
            'password.length' => '密码长度必须为6到20位',
            'email.require' => '邮箱不能为空',
            'email.email' => '邮箱格式不正确',
            'emailCode.require' => '验证码不能为空',
            'phone.email' => '手机格式不正确',
            'phoneCode.require' => '验证码不能为空',
        ];

        $this->scene = [
            'update' => ['nickname'],
            'updateAvatar' => ['avatar'],
            'updatePassword' => ['password'],
            'realNameAuth' => ['real_name', 'card_number'],
            'unbindThirdAccount' => ['type'],
            'updateEmail' => ['email', 'emailCode'],
            'updatePhone' => ['phone', 'phoneCode']
        ];
    }
}
