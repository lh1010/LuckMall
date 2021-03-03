<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利。
 * ----------------------------------------------------------------------------
 * Admin 产品属性 Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\admin\controller;

use think\Request;
use app\repository\admin\ProductAttributeRepository;
use app\repository\admin\ProductModelRepository;

class ProductAttribute extends Base
{
    public function index(Request $request)
    {
        $product_attributes = app(ProductAttributeRepository::class)->getProductAttributesPaginate(['product_model_id' => $request->get('product_model_id')]);
        $this->assign('product_attributes', $product_attributes);
        $this->assign('typeArray', app(ProductAttributeRepository::class)->typeArray);
        return $this->fetch();
    }

    public function create()
    {
        $this->assign('product_models', app(ProductModelRepository::class)->getProductModels());
        $this->assign('typeArray', app(ProductAttributeRepository::class)->typeArray);
        return $this->fetch();
    }

    public function store(Request $request)
    {
        $res = $this->validate($request->post(), 'ProductAttribute.store');
        if ($res !== true) return jsonFailed($res);
        return app(ProductAttributeRepository::class)->create($request->post());
    }

    public function edit(Request $request)
    {
        $this->assign('product_attribute', app(ProductAttributeRepository::class)->getProductAttribute($request->get('id')));
        $this->assign('product_models', app(ProductModelRepository::class)->getProductModels());
        $this->assign('typeArray', app(ProductAttributeRepository::class)->typeArray);
        return $this->fetch();
    }

    public function update(Request $request)
    {
        $res = $this->validate($request->post(), 'ProductAttribute.update');
        if ($res !== true) return jsonFailed($res);
        return app(ProductAttributeRepository::class)->update($request->post(), $request->post('id'));
    }

    public function delete(Request $request)
    {
        $res = $this->validate($request->param(), 'ProductAttribute.delete');
        if ($res !== true) return jsonFailed($res);
        return app(ProductAttributeRepository::class)->delete($request->param('id'));
    }
}