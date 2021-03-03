<?php

/**
 * 客户端配置
 * 微信小程序、微信公众号
 */

use think\facade\Env;

$res = file_exists(Env::get('config_path') . 'readfile/client.php') ? json_decode(include 'readfile/client.php', 1) : [];

return [

    // 微信小程序
    'wx_app' => [
        'name' => isset($res['wx_app']['name']) ? $res['wx_app']['name'] : '',
        'logo' => isset($res['wx_app']['logo']) ? $res['wx_app']['logo'] : '',
        'appid' => isset($res['wx_app']['appid']) ? $res['wx_app']['appid'] : '',
        'secret' => isset($res['wx_app']['secret']) ? $res['wx_app']['secret'] : '',
    ],

    // 微信公众号
    // 'wx_mp' => [
    //     'appid' => isset($res['wx_mp']['appid']) ? $res['wx_mp']['appid'] : '',
    //     'secret' => isset($res['wx_mp']['secret']) ? $res['wx_mp']['secret'] : ''
    // ]

];