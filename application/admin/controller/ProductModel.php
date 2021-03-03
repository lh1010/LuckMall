<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Admin 产品模型 Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\admin\controller;

use think\Request;
use app\repository\admin\ProductModelRepository;
use think\Db;

class ProductModel extends Base
{
    public function index()
    {
        $product_models = app(ProductModelRepository::class)->getProductModelsPaginate();
        $this->assign('product_models', $product_models);
        return $this->fetch();
    }

    public function create()
    {
        return $this->fetch();
    }

    public function store(Request $request)
    {
        $res = $this->validate($request->post(), 'ProductModel.store');
        if ($res !== true) return jsonFailed($res);
        return app(ProductModelRepository::class)->create($request->post());
    }

    public function edit(Request $request)
    {
        $product_model = app(ProductModelRepository::class)->getProductModel($request->get('id'));
        $this->assign("product_model", $product_model);
        return $this->fetch();
    }

    public function update(Request $request)
    {
        $res = $this->validate($request->post(), 'ProductModel.update');
        if ($res !== true) return jsonFailed($res);
        return app(ProductModelRepository::class)->update($request->post(), $id = $request->post('id'));
    }

    public function delete(Request $request)
    {
        $res = $this->validate($request->param(), 'ProductModel.delete');
        if ($res !== true) return jsonFailed($res);
        return app(ProductModelRepository::class)->delete($request->param('id'));
    }
}