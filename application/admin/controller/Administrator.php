<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Admin Administrator Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\admin\controller;

use think\Request;
use think\Db;
use app\repository\admin\AdministratorRepository;

class Administrator extends Base
{
    public function index(Request $request)
    {
        $administrators = app(AdministratorRepository::class)->getAdministratorsPaginate($request->get());
        $this->assign('administrators', $administrators);
        return $this->fetch();
    }

    public function create()
    {
        $this->assign('sexArray', app(AdministratorRepository::class)->sexArray);
        return $this->fetch();
    }

    public function store(Request $request)
    {
        $res = $this->validate($request->post(), 'Administrator.store');
        if ($res !== true) return jsonFailed($res);
        return app(AdministratorRepository::class)->create($request->post());
    }

    public function edit(Request $request)
    {
        $administrator = app(AdministratorRepository::class)->getAdministrator($request->get('id'));
        if (empty($administrator)) abort(404);
        $this->assign('administrator', $administrator);
        $this->assign('sexArray', app(AdministratorRepository::class)->sexArray);
        return $this->fetch();
    }

    public function update(Request $request)
    {
        $params = $request->post();
        $res = $this->validate($params, 'Administrator.update');
        if ($res !== true) return jsonFailed($res);
        if (!empty($params['update_password'])) $params['password'] = $params['update_password'];
        return app(AdministratorRepository::class)->update($params, $request->post('id'));
    }

    public function destory(Request $request)
    {
        // 保留至少一位管理员
        if (Db::name('administrator')->where('status', 1)->limit(1)->count() <= 1) {
            return jsonFailed('请保留至少一位管理员');
        }
        Db::name('administrator')->where('id', $request->id)->update(['status' => 99]);
        return jsonSuccess();
    }
}
