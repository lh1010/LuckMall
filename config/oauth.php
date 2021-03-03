<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * OAuth2.0 Config
 * ============================================================================
 * Author: Jasper
 */

use think\facade\Env;

$res = file_exists(Env::get('config_path') . 'readfile/oauth.php') ? json_decode(include 'readfile/oauth.php', 1) : [];

return [

    'qq' => [
        'name' => 'QQ登录',
        'appname' => 'QQ',
        'logo' => '/static/images/applogo/qq.png',
        'appid' => isset($res['qq']['appid']) ? $res['qq']['appid'] : '',
        'appkey' => isset($res['qq']['appkey']) ? $res['qq']['appkey'] : '',
        'status' => isset($res['qq']['status']) ? $res['qq']['status'] : 1,
    ],

    'weibo' => [
        'name' => '微博登录',
        'appname' => '微博',
        'logo' =>  '/static/images/applogo/weibo.png',
        'appid' => isset($res['weibo']['appid']) ? $res['weibo']['appid'] : '',
        'appkey' => isset($res['weibo']['appkey']) ? $res['weibo']['appkey'] : '',
        'status' => isset($res['weibo']['status']) ? $res['weibo']['status'] : 1,
    ],
    
    'weixin' => [
        'name' => '微信登录',
        'appname' => '微信',
        'logo' =>  '/static/images/applogo/weixin.png',
        'appid' => isset($res['weixin']['appid']) ? $res['weixin']['appid'] : '',
        'appkey' => isset($res['weixin']['appkey']) ? $res['weixin']['appkey'] : '',
        'status' => isset($res['weixin']['status']) ? $res['weixin']['status'] : 1
    ],

];
