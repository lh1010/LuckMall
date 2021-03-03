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

namespace app\wxapi\controller;

use think\Db;
use think\Request;
use app\repository\UserRepository;
use app\repository\AccountRepository;
use app\repository\QiandaoRepository;

class Account extends Base
{
	protected $middleware = [
        'CheckAppUserLogin' => ['except' => ['wxapp_login', 'wxapp_login_phone', 'checkLogin', 'getQiandaoData']],
    ];

	/**
	 * 微信授权登录
	 * 获取code2seesion
	 */
	public function wxapp_login(Request $request)
	{
		$config = Config('client.wx_app');
		$appid = $config['appid'];
		$secret = $config['secret'];
		if (empty($config['appid']) || empty($config['secret'])) return jsonFailed('小程序未配置');
        $code = $request->param('code');
		$url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.$appid.'&secret='.$secret.'&js_code='.$code.'&grant_type=authorization_code';
        $res = curl_get($url);
		$res = json_decode($res, true);
		$unionid = $res['unionid'];
		$openid = $res['openid'];
		$sessionKey = $res['session_key'];
		$code2seesion = base64_encode($unionid .'[luck]'. $openid .'[luck]'. $sessionKey);
		
		$data = []; $data['code2seesion'] = ''; $data['_token'] = '';
		$thirdAccount = app(UserRepository::class)->getThirdAccount(['type' => 'weixin', 'openid' => $openid, 'status' => 1]);
		if (!empty($thirdAccount)) {
			if ($thirdAccount['user_status'] == 0) return jsonFailed('该账户已关闭');
			$log = app(UserRepository::class)->createLoginLog($thirdAccount['user_id'], $login_device = 'wxapp');
			$data['_token'] = $log['token'];
		} else {
			$data['code2seesion'] = $code2seesion;
		}
		return jsonSuccess($data);
	}

	/**
	 * 微信手机号授权登录
	 * 系统登录
	 */
	public function wxapp_login_phone(Request $request)
	{
		$config = Config('client.wx_app');
		$appid = $config['appid'];
		if (empty($config['appid']) || empty($config['secret'])) return jsonFailed('小程序未配置');
		$code2seesion = base64_decode($request->param('code2seesion'));
		$array = explode('[luck]', $code2seesion);
		$unionid = $array['0'];
		$openid = $array['1'];
		$sessionKey = $array['2'];
		$iv = $request->param('iv');
		$encryptedData = $request->param('encryptedData');
		$user_info = json_decode($request->param('user_info'), true);

		$WXBizDataCrypt = new \wxApp\WXBizDataCrypt($appid, $sessionKey);
		$errCode = $WXBizDataCrypt->decryptData($encryptedData, $iv, $data);
		if ($errCode) return jsonFailed('登录失败');

		$data = json_decode($data, true);
		$user_info['phoneNumber'] = $data['phoneNumber'];
		$params = [];
		$params['unionid'] = $unionid;
		$params['openid'] = $openid;
		$params['type'] = 'weixin';
		$params['data'] = $user_info;
		$res = app(AccountRepository::class)->login_third($params, $login_device = 'wxapp');
        return json($res);
	}

	public function checkLogin(Request $request)
	{
		$res = app(AccountRepository::class)->checkLogin($token = $request->param('_token'), $login_device = 'wxapp');
		return json($res);
	}

	public function getUser(Request $request)
	{
		$user = app(AccountRepository::class)->getLoginUser($token = $request->param('_token'), $login_device = 'wxapp');
		return jsonSuccess($user);
	}

	public function updateUser(Request $request)
	{
		$res = app(UserRepository::class)->update($request->param(), $id = getUserId());
		return json($res);
	}

	public function logout(Request $request)
	{
		$res = app(AccountRepository::class)->logout($user_id = getUserId(), $login_device = 'wxapp');
		return json($res);
	}

	public function getQiandaoData()
	{
		$user_id = getUserId();
		$data = app(QiandaoRepository::class)->getQiandaoData($user_id);
		return jsonSuccess($data);
	}

	public function qiandao()
	{
		$user_id = getUserId();
		$res = app(QiandaoRepository::class)->qiandao($user_id);
		return json($res);
	}
}