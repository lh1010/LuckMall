<?php

namespace app\http\middleware;

use app\repository\AccountRepository;

class CheckAppUserLogin
{
    public function handle($request, \Closure $next)
    {
        $res = app(AccountRepository::class)->checkLogin($token = $request->param('_token'), $type = 'wxapp');
        if ($res['code'] == 400) {
            return jsonFailed('请先登录', 401);
        } 
        return $next($request);
    }
}