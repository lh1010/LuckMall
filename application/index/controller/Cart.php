<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Cart Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\index\controller;

use think\Request;
use app\repository\CartRepository;

class Cart extends Base
{
	protected $middleware = ['CheckUserLogin'];

    public function index()
    {
        $user_id = getUserId();
        $data = app(CartRepository::class)->getCart($user_id);
        $this->assign('data', $data);
        $this->assign('count', app(CartRepository::class)->getCartCount($user_id));
        return $this->fetch();
    }
}
