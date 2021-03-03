<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * User
 * ============================================================================
 * Author: Jasper
 */

namespace app\api\controller;

use think\Request;
use app\repository\UserRepository;
use app\repository\MailRepository;
use app\repository\SmsRepository;
use app\repository\AccountRepository;
use app\repository\SecurityRepository;
use think\facade\Cookie;

class User extends Base
{
	protected $middleware = ['CheckUserLogin'];

    public function update(Request $request)
    {
        $res = $this->validate($request->post(), 'User.update');
        if ($res !== true) return jsonFailed($res);
        $res = app(UserRepository::class)->update($request->post(), getUserId());
        Cookie::set('nickname', $request->post('nickname'), Config('common.login_save_password_time'));
        return jsonSuccess();
    }

    public function updateAvatar(Request $request)
    {
        $params = $request->param();
        if ($params['avatar'] == Config('image.user_avatar_default')) $params['avatar'] = '';
        $res = $this->validate($params, 'User.updateAvatar');
        if ($res !== true) return jsonFailed($res);
        return app(UserRepository::class)->update(['avatar' => $request->avatar], getUserId());
    }

    public function updatePassword(Request $request)
    {
        $res = $this->validate($request->param(), 'User.updatePassword');
        if ($res !== true) return jsonFailed($res);
        $user = getUser();
        if (!empty($user['password'])) {
            $res = app(SecurityRepository::class)->validateSecurityIdent($user);
            if ($res['code'] != 200) return $res;
        }
        return app(UserRepository::class)->update(['password' => md5($request->password)], $user['id']);
    }

    public function updateEmail(Request $request)
    {
        $res = $this->validate($request->param(), 'User.updateEmail');
        if ($res !== true) return jsonFailed($res);
        $user = getUser();
        if (!empty($user['email'])) {
            $res = app(SecurityRepository::class)->validateSecurityIdent($user);
            if ($res['code'] != 200) return $res;
        }
        $res = app(MailRepository::class)->validateCode($email = $request->email, $code = $request->emailCode, $type = 'set_email');
        if ($res['code'] != 200) return $res;
        return app(UserRepository::class)->update(['email' => $request->email], $user['id']);
    }

    public function updatePhone(Request $request)
    {
        $res = $this->validate($request->param(), 'User.updatePhone');
        if ($res !== true) return jsonFailed($res);
        $user = getUser();
        if (!empty($user['phone'])) {
            $res = app(SecurityRepository::class)->validateSecurityIdent($user);
            if ($res['code'] != 200) return $res;
            if ($user['phone'] == $request->phone) {
                return jsonFailed('该手机号为当前使用手机号');
            }
        }
        $res = app(SmsRepository::class)->validateCode($phone = $request->phone, $code = $request->phoneCode, $type = 'set_phone');
        if ($res['code'] != 200) return $res;
        return app(UserRepository::class)->update(['phone' => $request->phone], $user['id']);
    }

    /**
     * 解除第三方账号绑定
     */
    public function unbindThirdAccount(Request $request)
    {
        $res = $this->validate($request->param(), 'User.unbindThirdAccount');
        if ($res !== true) return jsonFailed($res);
        return app(UserRepository::class)->unbindThirdAccount($request->type, getUserId());
    }
}
