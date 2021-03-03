<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Admin Repository Admin
 * ============================================================================
 * Author: Jasper   
 */

namespace app\repository\admin;

use think\Db;
use think\facade\Session;
use Jenssegers\Agent\Agent;

class AdminRepository
{
    /**
     * Check Admin Login
     * @param array
     */
    public function checkAdminLogin()
    {
        if (!Session::has('admin')) return arrayFailed();
        $admin = Session::get('admin');
        $token = Db::name('administrator_login_log')->where('administrator_id', $admin['id'])->order('create_time desc')->value('token');
        if ($token != $admin['token']) {
            Session::delete('admin');
            return arrayFailed();
        }
        return arraySuccess();
    }

    /**
     * Admin Login
     * @param string $params['username']
     * @param string $params['password']
     * @return array
     */
    public function login($params = [])
    {
        try {
            $admin = Db::name('administrator')
                ->where('username', $params['username'])
                ->where('password', md5($params['password']))
                ->where('status', '<>', 99)
                ->find();   
            if (empty($admin)) return arrayFailed('用户名或密码错误');
            if ($admin['status'] != 1) return arrayFailed('该账号未开启');
            $data = [];
            $data['administrator_id'] = $admin['id'];
            $data['ip'] = Request()->ip();
            $data['browser'] = app(Agent::class)->browser();
            $data['browser_version'] = app(Agent::class)->version($data['browser']);
            $data['token'] = md5($params['password'] . $admin['id'] . time() . rand(1000, 9999));
            $data['create_time'] = date('Y-m-d H:i:s');
            Db::name('administrator_login_log')->insert($data);
			$admin['token'] = $data['token'];
            Session::set('admin', $admin);
            return arraySuccess();
        } catch (\Throwable $th) {
            return arrayFailed($th->getMessage());
        }
    }
}