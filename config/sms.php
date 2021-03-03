<?php

/**
 * 短信服务配置
 * 阿里云短信服务
 */

use think\facade\Env;

$res = file_exists(Env::get('config_path') . 'readfile/sms.php') ? json_decode(include 'readfile/sms.php', 1) : [];

return [
	
	// 阿里云短信服务
    'aliyun' => [
        'accessKeyId' => isset($res['aliyun']['accessKeyId']) ? $res['aliyun']['accessKeyId'] : '',
        'accessSecret' => isset($res['aliyun']['accessSecret']) ? $res['aliyun']['accessSecret'] : '',
        'RegionId' => 'cn-hangzhou',
        'host' => 'dysmsapi.aliyuncs.com',
        'version' => '2017-05-25'
    ],

    // 短信签名
    'signature' => isset($res['signature']) ? $res['signature'] : 'LuckMall',

    // 短信模板
    'template' => [
        1 => [
            'id' => 1,
            'tpl_code' => isset($res['template'][1]['tpl_code']) ? $res['template'][1]['tpl_code'] : '',
            'name' => '通用验证码',
            'content' => '您的验证码${code}，该验证码5分钟内有效，请勿泄漏于他人！',
            'type' => '验证码'
        ]
    ]

];
