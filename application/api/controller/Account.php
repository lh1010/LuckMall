<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Account Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\api\controller;

use think\Request;
use app\repository\AccountRepository;
use app\repository\SecurityRepository;

class Account extends Base
{
	protected $middleware = [ 
    	'CheckUserLogin' => ['except' => ['register', 'login']],
    ];

    public function register(Request $request)
    {
		$res = $this->validate($request->post(), 'Account.register');
		if ($res !== true) return jsonFailed($res);
		$params = ['phone' => $request->phone, 'password' => $request->password];
		$res = app(AccountRepository::class)->register($params);
		return json($res);
	}

	public function login(Request $request)
	{
		$type = $request->has('type') ? $request->type : 'default';
		switch ($type) {
			case 'default':
				$res = $this->validate($request->post(), 'Account.login_default');
				if ($res !== true) return jsonFailed($res);
				$res = app(AccountRepository::class)->login($request->username, $request->password);
				break;
			case 'sms':
				$res = $this->validate($request->post(), 'Account.login_sms');
				if ($res !== true) return jsonFailed($res);
				$res = app(AccountRepository::class)->login_sms($request->phone, $request->phoneCode);
				break;
		}
		return json($res);
	}

	/**
	 * 安全设置验证
	 */
	public function validateSecurity(Request $request)
	{
		$res = $this->validate($request->post(), 'Account.validateSecurity');
		if ($res !== true) return jsonFailed($res);
		$user = getUser();
		switch ($request->securityModel['type']) {
			case 'password':
				$res = $this->validate($request->post(), 'Account.validateSecurityPassword');
				if($res !== true) return jsonFailed($res);
				$res = app(SecurityRepository::class)->validateSecurityPassword($user['id'], $request->securityModel['password']);
				break;
			case 'email':
				$res = $this->validate($request->post(), 'Account.validateSecurityEmail');
				if($res !== true) return jsonFailed($res);
				if (empty($user['email'])) return jsonFailed('当前账户未设置邮箱');
				$res = app(SecurityRepository::class)->validateSecurityEmail($user['email'], $request->securityModel['emailCode']);
				break;
			case 'phone':
				$res = $this->validate($request->post(), 'Account.validateSecurityPhone');
				if($res !== true) return jsonFailed($res);
        		if (empty($user['phone'])) return jsonFailed('当前账户未设置手机号');
				$res = app(SecurityRepository::class)->validateSecurityPhone($user['phone'], $request->securityModel['phoneCode']);
				break;		
		}
		return $res;
	}
}
