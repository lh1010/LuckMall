<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * 安全设置 Repository
 * ============================================================================
 * Author: Jasper   
 */

namespace app\repository;

use think\Db;
use app\repository\UserRepository;
use app\repository\SmsRepository;
use think\facade\Cookie;
use Jenssegers\Agent\Agent;

class SecurityRepository 
{
    public function getSecurityData($user_id)
    {
        $data = [];
        $data['user'] = app(UserRepository::class)->getUser($user_id);
        $data['login_log'] = Db::name('user_login_log')->where('user_id', $user_id)->order('id desc')->find();
        $data['safety_grade'] = $this->getUserSafetyGrade($data['user']);
        return $data;
    }

    /**
     * 账户安全等级
     * @param array $user
     * @return array
     */
    public function getUserSafetyGrade($user)
    {
        $safetyGrade = [];
        $safetyGrade['grade'] = 1;
        $safetyGrade['describe'] = '低';
        if (!empty($user['password'])) $safetyGrade['grade'] += 1;
        if (!empty($user['email'])) $safetyGrade['grade'] += 1;
        if (!empty($user['phone'])) $safetyGrade['grade'] += 1;
        if ($safetyGrade['grade'] == 3) $safetyGrade['describe'] = '中';
        if ($safetyGrade['grade'] == 4) $safetyGrade['describe'] = '高';
        return $safetyGrade;
    }

    /**
     * 安全设置验证
     * 设置密码 身份验证 密码
     * @param int $user_id
     * @param string $password
     * @return array
     */
    public function validateSecurityPassword($user_id, $password)
    {
        $user = Db::name('user')->where('id', $user_id)->where('password', md5($password))->where('status', 1)->find();
        if (empty($user)) return arrayFailed('验证失败');
        $array = [];
        $array['validate_type'] = 'password';
        $array['validate_ident'] = md5($user['password'].Config('app.app_key'));
        Cookie::set('_security_validate', $array);
        return arraySuccess();
    }

    /**
     * 安全设置验证
     * 设置密码 身份验证 邮箱
     * @param string $email
     * @param string $code
     * @return array
     */
    public function validateSecurityEmail($email, $code)
    {
        $res = app(MailRepository::class)->validateCode($email, $code, $type = 'security_validate');
        if ($res['code'] != 200) return $res;
        $array = [];
        $array['validate_type'] = 'email';
        $array['validate_ident'] = md5($email.Config('app.app_key'));
        Cookie::set('_security_validate', $array);
        return arraySuccess();
    }

    /**
     * 安全设置验证
     * 设置密码 身份验证 手机
     * @param string $phone
     * @param string $code
     * @return array
     */
    public function validateSecurityPhone($phone, $code)
    {
        $res = app(SmsRepository::class)->validateCode($phone, $code, $type = 'security_validate');
        if ($res['code'] != 200) return $res;
        $array = [];
        $array['validate_type'] = 'phone';
        $array['validate_ident'] = md5($phone.Config('app.app_key'));
        Cookie::set('_security_validate', $array);
        return arraySuccess();
    }

    public function validateSecurityIdent($user)
    {
        if (!Cookie::has('_security_validate')) return arrayFailed('验证失败');
        $array = Cookie::get('_security_validate');
        $str = '';
        if ($array['validate_type'] == 'password') $str = md5($user['password'].Config('app.app_key'));
        if ($array['validate_type'] == 'email') $str = md5($user['email'].Config('app.app_key'));
        if ($array['validate_type'] == 'phone') $str = md5($user['phone'].Config('app.app_key'));
        if ($str != $array['validate_ident']) return arrayFailed('验证失败');
        return arraySuccess();
    }
}