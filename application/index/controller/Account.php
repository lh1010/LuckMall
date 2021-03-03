<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Index Account Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\index\controller;

use think\Request;
use think\Db;
use app\repository\AccountRepository;
use app\repository\SecurityRepository;
use app\repository\OrderRepository;
use app\repository\UserRepository;
use app\repository\AddressRepository;
use app\repository\ProductRepository;

class Account extends Base
{
    protected $middleware = [
    	'CheckUserLogin' => ['except' => ['register', 'login', 'logout']],
    ];

	public function register(Request $request)
    {
    	return $this->fetch('register');
    }

    public function login(Request $request)
    {
    	return $this->fetch();
    }

    public function logout()
    {
        app(AccountRepository::class)->logout($user_id = getUserId(), $login_device = 'web');
        return redirect(url('account/login'));
    }

    public function index()
    {
        return redirect(url('account/user'));
        return $this->fetch();
    }

    public function user()
    {
        $this->assign('user', app(UserRepository::class)->getUser(getUserId()));
        return $this->fetch();
    }

    // 头像管理
    public function avatar()
    {
        $this->assign('user', app(UserRepository::class)->getUser(getUserId()));
        return $this->fetch();
    }

    // 订单管理
    public function order(Request $request)
    {
        $user_id = getUserId();
        $params = [];
        $params['user_id'] = $user_id;
        if ($request->get('status') == 'delete') {
            $params['delete_status'] = 1;
        } else {
            $params['delete_status'] = 0;
            $params['status'] = $request->get('status');
        }
        $params['number'] = trim($request->get('number'));
        $data = [];
        $data['orders'] = app(OrderRepository::class)->getOrdersPaginate($params);
        $data['counts']['all'] = app(OrderRepository::class)->getCount(['user_id' => $user_id]);
        $data['counts'][10] = app(OrderRepository::class)->getCount(['status' => 10, 'user_id' => $user_id]);
        $data['counts'][20] = app(OrderRepository::class)->getCount(['status' => 20, 'user_id' => $user_id]);
        $data['counts'][30] = app(OrderRepository::class)->getCount(['status' => 30, 'user_id' => $user_id]);
        $data['counts'][40] = app(OrderRepository::class)->getCount(['status' => 40, 'user_id' => $user_id]);
        $this->assign('data', $data);
        return $this->fetch();
    }

    // 订单详情
    public function order_show(Request $request)
    {
        $order = app(OrderRepository::class)->getOrder($id = $request->get('id'), $user_id = getUserId());
        if (!$order) abort(404);
        $this->assign('order', $order);
        return $this->fetch();
    }

    // 地址管理
    public function address()
    {
        $addresses = app(AddressRepository::class)->getAddresses($user_id = getUserId());
        $this->assign('addresses', $addresses);
        return $this->fetch();
    }

    // 收藏产品
    public function collectProduct()
    {
        $collectProducts = app(ProductRepository::class)->getCollectProductsPaginate(getUserId());
        $this->assign('collectProducts', $collectProducts);
        return $this->fetch();
    }

    // 绑定账号
    public function bind(Request $request)
    {
        $thirdAccounts = app(UserRepository::class)->getThirdAccounts(['user_id' => getUserId(), 'status' => 1]);
        $this->assign('thirdAccounts', $thirdAccounts);
        return $this->fetch();
    }

    // 安全设置
    public function security()
    {
        $data = app(SecurityRepository::class)->getSecurityData(getUserId());
        $this->assign('data', $data);
        return $this->fetch();
    }

    // 设置密码
    public function set_password()
    {
        $this->assign('user', getUser());
        return $this->fetch();
    }

    // 设置邮箱
    public function set_email()
    {
        $this->assign('user', getUser());
        return $this->fetch();
    }

    // 设置电话
    public function set_phone()
    {
        $this->assign('user', getUser());
        return $this->fetch();
    }
}
