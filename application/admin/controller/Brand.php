<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Admin Brand Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\admin\controller;

use think\Request;
use app\repository\admin\BrandRepository;
use app\repository\admin\ProductCategoryRepository;

class Brand extends Base
{
    public function index(Request $request)
    {
        $brands = app(BrandRepository::class)->getBrandsPaginate($request->get());
        $this->assign('brands', $brands);
        return $this->fetch();
    }

    public function create()
    {
        return $this->fetch();
    }

    public function store(Request $request)
    {
        $res = $this->validate($request->post(), 'brand.store');
        if ($res !== true) return jsonFailed($res);
        return app(BrandRepository::class)->create($request->post());
    }

    public function edit(Request $request)
    {
        $brand = app(BrandRepository::class)->getBrand($request->id);
        if (empty($brand)) abort(404);
        $this->assign("brand", $brand);
        return $this->fetch();
    }

    public function update(Request $request)
    {
        $res = $this->validate($request->post(), 'Brand.update');
        if ($res !== true) return jsonFailed($res);
        return app(BrandRepository::class)->update($request->post(), $request->id);
    }

    public function delete(Request $request)
    {
        $res = $this->validate($request->param(), 'brand.delete');
        if ($res !== true) return jsonFailed($res);
        return app(BrandRepository::class)->delete($request->id);
    }
}