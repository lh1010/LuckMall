<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Admin Login Controller
 * ============================================================================
 * Author: Jasper      
 */

namespace app\admin\controller;

use think\Request;
use think\facade\Session;
use app\repository\admin\AdminRepository;
use app\repository\admin\AdministratorRepository;

class Account extends Base
{
    public function login()
    {
    	return $this->fetch();
	}
	
	public function doLogin(Request $request)
	{
		$res = $this->validate($request->post(), 'Account.doLogin');
		if ($res !== true) return jsonFailed($res);
		return app(AdminRepository::class)->login(['username' => $request->username, 'password' => $request->password]);
	}

    public function logout()
    {
        Session::delete('admin');
        return jsonSuccess();
    }

	public function edit(Request $request)
	{
		$administrator = Session::get('admin');
		$administrator = app(AdministratorRepository::class)->getAdministrator($administrator['id']);
		$this->assign('sexArray', app(AdministratorRepository::class)->sexArray);
		$this->assign('administrator', $administrator);
		return $this->fetch();
	}

	public function update(Request $request)
	{
		$params = $request->post();
		$administrator = Session::get('admin');
		$params['id'] = $administrator['id'];
		$res = $this->validate($params, 'Administrator.update');
        if ($res !== true) return jsonFailed($res);
		if (!empty($params['update_password'])) $params['password'] = $params['update_password'];
		return app(AdministratorRepository::class)->update($params, $params['id']);
	}

    public function updatePassword(Request $request)
    {
    	$res = $this->validate($request->post(), 'Account.updatePassword');
		if ($res !== true) return jsonFailed($res);
    	$admin = Session::get('admin');
    	$data['password'] = md5($request->param('password'));
    	$data['update_time'] = \Tool::getNowTime();
    	$res = Admin::where('id', $admin['id'])->update($data);
    	if ($res) {
            return jsonSuccess();
        } else {
            return jsonFailed();
        }
    }
}
