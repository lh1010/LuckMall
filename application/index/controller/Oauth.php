<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Web OAuth Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\index\controller;

use think\Request;
use think\facade\Cookie;
use app\repository\AccountRepository;
use app\repository\UserRepository;

class Oauth extends Base
{
    private $oauth_entrance = '';

    public function __construct()
    {
        parent::__construct();
        $this->oauth_entrance = Cookie::has('oauth_entrance') ? Cookie::get('oauth_entrance') : '';
    }

    public function qq(Request $request)
    {
        $config = Config('oauth.qq');
        if (empty($config) || $config['status'] != 1) $this->error('当前登录方式未开启');
        $return_url = Config('system.app_url').'/oauth/qq_return.html';
        $OAuth = new \Yurun\OAuthLogin\QQ\OAuth2($config['appid'], $config['appkey'], $return_url);
        $url = $OAuth->getAuthUrl();
        Cookie::set('oauth_qq_state', $OAuth->state);
        Cookie::set('oauth_entrance', $request->entrance);
        return redirect($url);
    }

    public function qq_return(Request $request)
    {
        $config = Config('oauth.qq');
        if (empty($config) || $config['status'] != 1) return redirect('/');
        if (!Cookie::has('oauth_qq_state')) return redirect(Config('system.app_url')); 
        $return_url = Config('system.app_url').'/oauth/qq_return.html';
        $OAuth = new \Yurun\OAuthLogin\QQ\OAuth2($config['appid'], $config['appkey'], $return_url);
        $accessToken = $OAuth->getAccessToken(Cookie::get('oauth_qq_state'));
        $userInfo = $OAuth->getUserInfo();
        $openid = $OAuth->openid;
        $params['oauth_entrance'] = $this->oauth_entrance;
        $params['type'] = 'qq';
        $params['openid'] = $openid;
        $params['data'] = $userInfo;
        // 绑定
        if ($this->oauth_entrance == 'bind') {
            if (!$user_id = getUserId()) return redirect(url('account/login'));
            $params['user_id'] = $user_id;
            $res = app(UserRepository::class)->bindThirdAccount($params);
        } 
        // 登录
        if ($this->oauth_entrance == 'login') {
            $res = app(AccountRepository::class)->login_third($params); 
        }

        $url = $this->setRedirectUrl($this->oauth_entrance, 'error');
        if ($res['code'] != 200) $this->error($res['message'], $url);
        $url = $this->setRedirectUrl($this->oauth_entrance);
        return redirect($url);
	}

    public function weibo(Request $request) 
    {
        $config = Config('oauth.weibo');
        if (empty($config) || $config['status'] != 1) $this->error('当前登录方式未开启');
        $return_url = Config('system.app_url') . '/oauth/weibo_return.html';
        $OAuth = new \Yurun\OAuthLogin\Weibo\OAuth2($config['appid'], $config['appkey'], $return_url);
        $url = $OAuth->getAuthUrl();
        Cookie::set('oauth_weibo_state', $OAuth->state);
        Cookie::set('oauth_entrance', $request->entrance);
        return redirect($url);
    }

    public function weibo_return(Request $request)
    {
        $config = Config('oauth.weibo');
        if (empty($config) || $config['status'] != 1) return redirect('/');
        if (!Cookie::has('oauth_weibo_state')) return redirect('/');
        $return_url = Config('system.app_url') . '/oauth/weibo_return.html';
        $OAuth = new \Yurun\OAuthLogin\Weibo\OAuth2($config['appid'], $config['appkey'], $return_url);
        $accessToken = $OAuth->getAccessToken(Cookie::get('oauth_weibo_state'));
        $userInfo = $OAuth->getUserInfo();
        $params['oauth_entrance'] = $this->oauth_entrance;
        $params['type'] = 'weibo';
        $params['openid'] = $OAuth->openid;
        $params['data'] = $userInfo;
        // 绑定
        if ($this->oauth_entrance == 'bind') {
            if (!$user_id = getUserId()) return redirect(url('account/login'));
            $params['user_id'] = $user_id;
            $res = app(UserRepository::class)->bindThirdAccount($params);
        }
        // 登录
        if ($this->oauth_entrance == 'login') {
            $res = app(AccountRepository::class)->login_third($params);
        }

        $url = $this->setRedirectUrl($this->oauth_entrance, 'error');
        if ($res['code'] != 200) $this->error($res['message'], $url);
        $url = $this->setRedirectUrl($this->oauth_entrance);
        return redirect($url);
    }

    public function weixin(Request $request)
    {
        $config = Config('oauth.weixin');
        if (empty($config) || $config['status'] != 1) $this->error('当前登录方式未开启');
        $return_url = Config('system.app_url') . '/oauth/weixin_return.html';
        $OAuth = new \Yurun\OAuthLogin\Weixin\OAuth2($config['appid'], $config['appkey']);
        $url = $OAuth->getAuthUrl($return_url);
        Cookie::set('oauth_weixin_state', $OAuth->state);
        Cookie::set('oauth_entrance', $request->entrance);
        return redirect($url);
    }

    public function weixin_return(Request $request)
    {
        $config = Config('oauth.weixin');
        if (empty($config) || $config['status'] != 1) return redirect('/');
        if (!Cookie::has('oauth_weixin_state')) return redirect('/');
        $OAuth = new \Yurun\OAuthLogin\Weixin\OAuth2($config['appid'], $config['appkey']);
        $accessToken = $OAuth->getAccessToken(Cookie::get('oauth_weixin_state'));
        $userInfo = $OAuth->getUserInfo();
        $params['oauth_entrance'] = $this->oauth_entrance;
        $params['type'] = 'weixin';
        $params['device'] = 'web';
        $params['openid'] = $OAuth->openid;
        $params['unionid'] = isset($userInfo['unionid']) ? $userInfo['unionid'] : '';
        $params['data'] = $userInfo;
        // 绑定
        if ($this->oauth_entrance == 'bind') {
            if (!$user_id = getUserId()) return redirect(url('account/login'));
            $params['user_id'] = $user_id;
            $res = app(UserRepository::class)->bindThirdAccount($params);
        }
        // 登录
        if ($this->oauth_entrance == 'login') {
            $res = app(AccountRepository::class)->login_third($params);
        }
        
        $url = $this->setRedirectUrl($this->oauth_entrance, 'error');
        if ($res['code'] != 200) $this->error($res['message'], $url);
        $url = $this->setRedirectUrl($this->oauth_entrance);
        return redirect($url);
    }

    private function setRedirectUrl($oauth_entrance, $type = 'success')
    {
        $url = url('account/index');
        switch ($oauth_entrance) {
            case 'bind':
                $url = url('account/bind');
                break;
            case 'login':
                if ($type == 'error') $url = url('account/login');
                break;
            case 'register':
                if ($type == 'error') $url = url('account/register', ['type' => 'phone']);
                break;       
        }
        return $url;
    }
}
