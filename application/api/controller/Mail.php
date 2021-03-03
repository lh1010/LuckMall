<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Api Mail Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\api\controller;

use think\Request;
use think\Db;
use think\facade\Cookie;
use app\repository\MailRepository;

class Mail extends Base
{
	protected $middleware = [ 
    	'CheckUserLogin' => ['only' => ['security_validate', 'set_email']],
    ];

	/**
	 * 设置/更新邮箱
	 */
	public function set_email(Request $request)
	{
		$res = $this->validate($request->param(), 'Mail.set_email');
		if ($res !== true) return jsonFailed($res);
		$user = getUser();
		if (Db::name('user')->where('id', '<>', $user['id'])->where('status', '<>', 99)->where('email', $request->email)->value('id')) {
			return jsonFailed('该邮箱已存在');
		}
		if ($user['email'] == $request->email) return jsonFailed('该邮箱为当前绑定邮箱');
		$code = rand(1000, 9999);
    	$title = '验证码查收';
		$content = '您的验证码为：'.$code.'，五分钟内有效。请不要向任何人提供验证码！【'.Config('system.app_name').'】';
    	if (app(MailRepository::class)->sendMail($request->email, $title, $content)) {
			$res = app(MailRepository::class)->sendSuccess(['type' => 'set_email', 'content' => $content, 'email' => $request->email, 'code' => $code]);
			if ($res['code'] != 200) return $res;
    		return jsonSuccess('已发送');
    	} else {
    		return jsonFailed('发送失败');
    	}
	}

	/**
	 * 注册账号
	 */
	public function register(Request $request)
	{
		$res = $this->validate($request->param(), 'Mail.register');
		if($res !== true) return jsonFailed($res);
		$code = rand(1000, 9999);
    	$title = '验证码查收';
    	$content = '注册验证码为：'.$code.'，五分钟内有效。请不要向任何人提供验证码！【'.Config('system.app_name').'】';
    	if (app(MailRepository::class)->sendMail($request->email, $title, $content)) {
			$res = app(MailRepository::class)->sendSuccess(['type' => 'register', 'content' => $content, 'email' => $request->email, 'code' => $code]);
			if ($res['code'] != 200) return $res;
    		return jsonSuccess('已发送');
    	} else {
    		return jsonFailed('发送失败');
    	}
	}

	/**
	 * 安全设置验证
	 */
	public function security_validate(Request $request)
	{
		$user = getUser();
		if (empty($user['email'])) return jsonFailed('当前账户未设置邮箱');
		$code = rand(1000, 9999);
    	$title = '验证码查收';
    	$content = '您的验证码为：'.$code.'，五分钟内有效。请不要向任何人提供验证码！【'.Config('system.app_name').'】';
    	if (app(MailRepository::class)->sendMail($user['email'], $title, $content)) {
    		$res = app(MailRepository::class)->sendSuccess(['type' => 'security_validate', 'content' => $content, 'email' => $user['email'], 'code' => $code]);
			if ($res['code'] != 200) return $res;
    		return jsonSuccess('已发送');
    	} else {
    		return jsonFailed('发送失败');
    	}
	}	
}
