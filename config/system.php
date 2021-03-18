<?php

use think\facade\Env;

$res = file_exists(Env::get('config_path') . 'readfile/system.php') ? json_decode(include 'readfile/system.php', 1) : [];

return [

    // 项目名字
    'app_name' => isset($res['app_name']) ? $res['app_name'] : Config('app.app_name'),

    // 项目域名
    'app_url' => isset($res['app_url']) ? $res['app_url'] : Config('app.app_url'),

    // 项目LOGO
    'app_logo' => isset($res['app_logo']) ? $res['app_logo'] : '/static/index/images/mall_logo_0.png',

    // 腾讯QQ
    'qq' => isset($res['qq']) ? $res['qq'] : '000000000',

    // 阿里旺旺
    'wangwang' => isset($res['wangwang']) ? $res['wangwang'] : '000000000',

    // 联系方式
    'phone' => isset($res['phone']) ? $res['phone'] : '00000000000',

    // 工作时间
    'work_time' => isset($res['work_time']) ? $res['work_time'] : '9:00至18:00',

    // 详细地址
    'address' => isset($res['address']) ? $res['address'] : '详细地址内容',

    // 备案信息
    'beian' => isset($res['beian']) ? $res['beian'] : '备案信息内容',

    // 版权
    'copyright' => isset($res['copyright']) ? $res['copyright'] : 'Copyright **** 版权所有',

    // 统计代码
    'statistical_code' => isset($res['statistical_code']) ? $res['statistical_code'] : '',

];
