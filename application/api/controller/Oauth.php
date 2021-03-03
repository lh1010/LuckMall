<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Api OAuth Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\api\controller;

use think\Request;

class Oauth extends Base
{
    public function qq(Request $request)
    {
        $returnUrl = Config('app.app_host').url('/api/oauth/qqReturn');
        $OAuth = new \Yurun\OAuthLogin\QQ\OAuth2(Config('oauth.qq.appid'), Config('oauth.qq.appkey'), $returnUrl);
        $url = $OAuth->getAuthUrl();
        //$_SESSION['YURUN_QQ_STATE'] = $OAuth->state;
        return redirect($url);
    }

    public function qqReturn(Request $request)
    {
        dd(Config('app.app_host').url('/api/oauth/qqReturn'));
        dd(Config('app.app_host'));
    }

    public function weibo() 
    {
        $returnUrl = Config('app.app_host').url('/api/oauth/weiboReturn');
        $OAuth = new \Yurun\OAuthLogin\Weibo\OAuth2(Config('oauth.weibo.appid'), Config('oauth.weibo.appkey'), $returnUrl);
        $url = $OAuth->getAuthUrl();
        return redirect($url);
    }

    public function weiboReturn() {}

    public function weixin()
    {

    }
}
