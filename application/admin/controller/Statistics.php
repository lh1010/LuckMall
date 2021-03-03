<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Admin Statistics Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\admin\controller;

use think\Request;
use think\Db;
use app\repository\admin\OrderRepository;
use app\repository\admin\UserRepository;
use app\repository\admin\ProductRepository;

class Statistics extends Base
{
    public function getData(Request $request)
    {
        $data = [];
        $data['count']['order'] = Db::name('order')->where('status', '<>', 99)->count();
        $data['count']['user'] = Db::name('user')->where('status', '<>', 99)->count();
        $data['count']['product'] = Db::name('product')->where('status', '<>', 99)->count();
        $data['count']['product_category'] = Db::name('product_category')->where('status', '<>', 99)->count();
        $data['orders'] = app(OrderRepository::class)->getOrders();
        $data['users'] = app(UserRepository::class)->getUsers();
        $data['products'] = app(ProductRepository::class)->getProducts([], $limit = 10);
        return jsonSuccess($data);
    }
}
