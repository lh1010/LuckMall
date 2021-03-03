<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Api Sms Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\api\controller;

use think\Request;
use think\Db;
use app\repository\SmsRepository;

class Sms extends Base
{
    protected $middleware = [ 
    	'CheckUserLogin' => ['only' => ['security', 'set_phone']],
    ];

    /**
	 * 设置/更新手机号
	 */
	public function set_phone(Request $request)
	{
		$res = $this->validate($request->param(), 'Sms.set_phone');
        if ($res !== true) return jsonFailed($res);
        $user = getUser();
		if (Db::name('user')->where('id', '<>', $user['id'])->where('status', '<>', 99)->where('phone', $request->phone)->value('id')) {
			return jsonFailed('该手机号已存在');
        }
        if ($user['phone'] == $request->phone) return jsonFailed('该手机号为当前绑定手机号');
    	return app(SmsRepository::class)->send(['type' => 'set_phone', 'phone' => $request->phone, 'code' => rand(1000, 9999)]);
	}

    /**
	 * 注册账号
	 */
    public function register(Request $request)
    {
        $res = $this->validate($request->param(), 'Sms.register');
        if ($res !== true) return jsonFailed($res);
        if (Db::name('user')->where('phone', $request->param('phone'))->find()) return jsonFailed('该手机号已存在');
    	return app(SmsRepository::class)->send(['type' => 'register', 'phone' => $request->phone, 'code' => rand(1000, 9999)]);
    }

    /**
	 * 短信登录
	 */
    public function login(Request $request)
    {
        $res = $this->validate($request->param(), 'Sms.login');
        if ($res !== true) return jsonFailed($res);
    	return app(SmsRepository::class)->send(['type' => 'login', 'phone' => $request->phone, 'code' => rand(1000, 9999)]);
    }

    /**
	 * 安全设置验证
	 */
    public function security_validate(Request $request)
    {
        $user = getUser();
        if (empty($user['phone'])) return jsonFailed('当前账户未设置手机号');
    	return app(SmsRepository::class)->send(['type' => 'security_validate', 'phone' => $user['phone'], 'code' => rand(1000, 9999)]);
    }
}
