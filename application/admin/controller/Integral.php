<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Admin Integral Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\admin\controller;

use think\Request;
use app\repository\admin\IntegralRepository;

class Integral extends Base
{
    public function product(Request $request)
    {
        $products = app(IntegralRepository::class)->getProductsPaginate($request->get());
        $this->assign('products', $products);
        return $this->fetch();
    }

    public function createProduct()
    {
        return $this->fetch();
    }

    public function storeProduct(Request $request)
    {
        $res = $this->validate($request->post(), 'Integral.storeProduct');
        if ($res !== true) return jsonFailed($res);
        return app(IntegralRepository::class)->createProduct($request->post());
    }

    public function editProduct(Request $request)
    {
        $product = app(IntegralRepository::class)->getProduct($request->get('id'));
        $this->assign('product', $product);
        return $this->fetch();
    }

    public function updateProduct(Request $request)
    {
        $res = $this->validate($request->post(), 'Integral.updateProduct');
        if ($res !== true) return jsonFailed($res);
        return app(IntegralRepository::class)->updateProduct($request->post(), $request->post('id'));
    }

    public function deleteProduct(Request $request)
    {
        $res = $this->validate($request->param(), 'Integral.deleteProduct');
        if ($res !== true) return jsonFailed($res);
        return app(IntegralRepository::class)->deleteProduct($request->id);
    }

    public function order(Request $request)
    {
        $orders = app(IntegralRepository::class)->getOrdersPaginate($request->get());
        $this->assign('orders', $orders);
        return $this->fetch();
    }
}