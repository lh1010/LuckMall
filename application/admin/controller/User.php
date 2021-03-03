<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Admin User Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\admin\controller;

use think\Request;
use app\repository\admin\UserRepository;

class User extends Base
{
    public function index(Request $request)
    {
        $users = app(UserRepository::class)->getUsersPaginate($request->get());
        $this->assign('users', $users);
        return $this->fetch();
    }

    public function edit(Request $request)
    {
        $user = app(UserRepository::class)->getUser(['id' => $request->id]);
        if (empty($user)) abort(404);
        $this->assign('user', $user);
        return $this->fetch();
    }

    public function update(Request $request)
    {
        $params = $request->post();
        $params['view_price_permissions'] = isset($params['view_price_permissions']) ? 1 : 0;
        return app(UserRepository::class)->update($params, $request->id);
    }

    public function delete(Request $request)
    {
        $res = $this->validate($request->param(), 'User.delete');
        if ($res !== true) return jsonFailed($res);
        $res = app(UserRepository::class)->delete($request->param('id'));
        return json($res);
    }

    // 充值
    public function topUp(Request $request)
    {
        if ($request->isPost()) {
            return app(UserRepository::class)->update(['wallet' => $request->post('wallet')], $id = $request->post('user_id'));
        }
        $user = app(UserRepository::class)->getUser(['id' => $request->get('user_id')]);
        $this->assign('user', $user);
        return $this->fetch();
    }
}