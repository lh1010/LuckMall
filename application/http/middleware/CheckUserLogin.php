<?php

namespace app\http\middleware;

use think\facade\Cookie;
use app\repository\AccountRepository;

class CheckUserLogin
{
    public function handle($request, \Closure $next)
    {
        $res = app(AccountRepository::class)->checkLogin($token = Cookie::get('_token'), $type = 'web');
        if ($res['code'] == 400) {
            if ($request->isAjax()) return jsonFailed('请先登录', 401);
            return redirect('Account/login');
        } 
        return $next($request);
    }
}
