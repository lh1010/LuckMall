<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Admin 产品销售规格 Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\admin\controller;

use think\Request;
use app\repository\admin\ProductSpecificationRepository;
use app\repository\admin\ProductModelRepository;

class ProductSpecification extends Base
{
    public function index(Request $request)
    {
        $product_specifications = app(ProductSpecificationRepository::class)->getProductSpecificationsPaginate(['product_model_id' => $request->get('product_model_id')]);
        $this->assign('product_specifications', $product_specifications);
        return $this->fetch();
    }

    public function create()
    {
        $this->assign('product_models', app(ProductModelRepository::class)->getProductModels());
        return $this->fetch();
    }

    public function store(Request $request)
    {
        $res = $this->validate($request->post(), 'ProductSpecification.store');
        if ($res !== true) return jsonFailed($res);
        return app(ProductSpecificationRepository::class)->create($request->post());
    }

    public function edit(Request $request)
    {
        $product_specification = app(ProductSpecificationRepository::class)->getProductSpecification($request->get('id'));
        $this->assign('product_specification', $product_specification);
        $this->assign('product_models', app(ProductModelRepository::class)->getProductModels());
        return $this->fetch();
    }

    public function update(Request $request)
    {
        $res = $this->validate($request->post(), 'ProductSpecification.update');
        if ($res !== true) return jsonFailed($res);
        return app(ProductSpecificationRepository::class)->update($request->post(), $request->post('id'));
    }

    public function delete(Request $request)
    {
        $res = $this->validate($request->param(), 'ProductSpecification.delete');
        if ($res !== true) return jsonFailed($res);
        return app(ProductSpecificationRepository::class)->delete($request->param('id'));
    }
}