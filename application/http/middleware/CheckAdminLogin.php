<?php

namespace app\http\middleware;

use app\repository\admin\AdminRepository;

class CheckAdminLogin
{
    public function handle($request, \Closure $next)
    {
        if ($this->exclude($request)) return $next($request); 
        $res = app(AdminRepository::class)->checkAdminLogin();
        if ($res['code'] == 400) {
            if ($request->isAjax()) return jsonFailed('请先登录', 401);
            return redirect(url('/admin/login'));
        } 
        return $next($request);
    }

    private function exclude($request)
    {
        $currentController = $request->controller();
        $currentAction = $request->action();
        $currentControllerAction = $currentController.'/'.$currentAction;
        $excludes = [
            'Account/login',
            'Account/dologin'
        ];
        if (in_array($currentControllerAction, $excludes)) return true;
        return false;
    }
}
