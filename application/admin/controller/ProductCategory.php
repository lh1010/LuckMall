<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Admin Product Category Controller
 * ============================================================================
 * Author: Jasper
 * 
 */

namespace app\admin\controller;

use think\Request;
use think\Db;
use app\repository\admin\ProductCategoryRepository;

class ProductCategory extends Base
{
    public function index()
    {
    	$this->assign("categorys", app(ProductCategoryRepository::class)->getCategorys(['if_use_ccc' => 1, 'if_use_pc' => 1]));
        return $this->fetch(); 
    }

    public function create(Request $request)
    {
        $this->assign("categorys", app(ProductCategoryRepository::class)->getCategorys());
        return $this->fetch();
    }

    public function store(Request $request)
    {
        $res = $this->validate($request->post(), 'ProductCategory.store');
        if ($res !== true) return jsonFailed($res);
        return app(ProductCategoryRepository::class)->create($request->post());
    }

    public function edit(Request $request)
    {
        $category = app(ProductCategoryRepository::class)->getCategory($request->id);
        if (empty($category)) abort(404);
        $categorys = app(ProductCategoryRepository::class)->getCategorys();
        foreach ($categorys as $key => $value) {
            if (in_array($value['id'], explode(',', $category['child_ids']))) unset($categorys[$key]);
        }
        $this->assign("category", $category);
        $this->assign("categorys", $categorys);
        return $this->fetch();
    }

    public function update(Request $request)
    {
        $res = $this->validate($request->post(), 'ProductCategory.update');
        if ($res !== true) return jsonFailed($res);
        return app(ProductCategoryRepository::class)->update($request->post(), $request->id);
    }

    public function delete(Request $request)
    {
        $res = $this->validate($request->get(), 'ProductCategory.delete');
        if ($res !== true) return jsonFailed($res);
        $res = app(ProductCategoryRepository::class)->delete($request->id);
        return json($res);
    }
}
