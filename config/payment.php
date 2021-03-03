<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * 支付方式 对应ID禁止修改
 */

use think\facade\Env;

$res = file_exists(Env::get('config_path') . 'readfile/payment.php') ? json_decode(include 'readfile/payment.php', 1) : [];

return [

    1 => [
        'id' => 1,
        'name' => '支付宝支付',
        'show_name' => '支付宝支付',
        'ident' => 'alipay',
        'mobile_image' => Config('app.app_url') . '/static/images/payment/mobile_alipay.png',
        'pc_image' => Config('app.app_url') . '/static/images/payment/pc_alipay.jpg',
        'config' => [
            'appid' => isset($res[1]['config']['appid']) ? $res[1]['config']['appid'] : '',
            // 商户私钥
            'rsaPrivateKey' => isset($res[1]['config']['rsaPrivateKey']) ? $res[1]['config']['rsaPrivateKey'] : '',
            // 支付宝公钥
            'alipayPublicKey' => isset($res[1]['config']['alipayPublicKey']) ? $res[1]['config']['alipayPublicKey'] : '',
        ],
        'status' => isset($res[1]['status']) ? $res[1]['status'] : 0
    ],

    // 微信支付 公众号
    2 => [
        'id' => 2,
        'name' => '公众号支付',
        'show_name' => '微信支付',
        'ident' => 'weixinpay',
        'mobile_image' => Config('app.app_url') . '/static/images/payment/mobile_wxpay.png',
        'pc_image' => Config('app.app_url') . '/static/images/payment/pc_wxpay.jpg',
        'config' => [
            // 商户号ID，身份标识
            'mchid' => isset($res[2]['config']['mchid']) ? $res[2]['config']['mchid'] : '',
            // 商户号API密钥，https://pay.weixin.qq.com 帐户设置-安全设置-API安全-API密钥-设置API密钥
            'apikey' => isset($res[2]['config']['apikey']) ? $res[2]['config']['apikey'] : '',
            // 公众号开发者ID
            'appid' => isset($res[2]['config']['appid']) ? $res[2]['config']['appid'] : '',
        ],
        'status' => isset($res[2]['status']) ? $res[2]['status'] : 0
    ],

    // 微信支付 小程序
    3 => [
        'id' => 3,
        'name' => '小程序支付',
        'show_name' => '微信支付',
        'ident' => 'weixinpay',
        'mobile_image' => Config('app.app_url') . '/static/images/payment/mobile_wxpay.png',
        'pc_image' => Config('app.app_url') . '/static/images/payment/pc_wxpay.jpg',
        'config' => [
            // 商户号ID，身份标识
            'mchid' => isset($res[3]['config']['mchid']) ? $res[3]['config']['mchid'] : '',
            // 商户号API密钥，https://pay.weixin.qq.com 帐户设置-安全设置-API安全-API密钥-设置API密钥
            'apikey' => isset($res[3]['config']['apikey']) ? $res[3]['config']['apikey'] : '',
            // 小程序开发者ID
            'appid' => isset($res[3]['config']['appid']) ? $res[3]['config']['appid'] : '',
            // 小程序开发者密码
            'appsecret' => isset($res[3]['config']['appsecret']) ? $res[3]['config']['appsecret'] : '',
        ],
        'status' => isset($res[3]['status']) ? $res[3]['status'] : 0
    ],

    // 余额支付
    4 => [
        'id' => 4,
        'name' => '余额支付',
        'show_name' => '余额支付',
        'ident' => 'wallet',
        'mobile_image' => Config('app.app_url') . '/static/images/payment/mobile_money.png',
        'pc_image' => Config('app.app_url') . '/static/images/payment/pc_money.png',
        'status' => isset($res[4]['status']) ? $res[4]['status'] : 0
    ],
    
];
