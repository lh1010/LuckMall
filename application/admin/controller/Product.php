<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Admin Product Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\admin\controller;

use think\Request;
use app\repository\admin\ProductRepository;
use app\repository\admin\ProductCategoryRepository;
use app\repository\admin\ShippingFreightRepository;
use app\repository\admin\ProductModelRepository;
use app\repository\admin\BrandRepository;

class Product extends Base
{
    public function index(Request $request)
    {
        $params = $request->get();
        if ($request->has('category_id') && !empty($request->get('category_id'))) {
            $params['category_ids'] = app(ProductCategoryRepository::class)->getCategoryChildIds($request->get('category_id'));
            unset($params['category_id']);
        }
        $products = app(ProductRepository::class)->getProductsPaginate($params);
        $this->assign('products', $products);
        $this->assign('categorys', app(ProductCategoryRepository::class)->getCategorys([], $type = 'tree'));
        $this->assign("brands", app(BrandRepository::class)->getBrands());
        return $this->fetch();
    }

    public function selectCategory()
    {
        $categorys = app(ProductCategoryRepository::class)->getCategorys(['parent_id' => 0, 'status' => 1]);
        $this->assign('categorys', $categorys);
        return $this->fetch();
    }

    public function create(Request $request)
    {
        if (!$request->has('category_id') || empty($request->category_id)) return redirect(url('/admin/product/selectCategory'));
        $selected_category = app(ProductRepository::class)->getFullCategoryName($request->category_id);
        if (empty($selected_category)) return redirect(url('/admin/product/selectCategory'));
        $this->assign('selected_category', $selected_category);
        $this->assign('freights', app(ShippingFreightRepository::class)->getFreights());
        $this->assign('product_models', app(ProductModelRepository::class)->getProductModels());
        $this->assign("brands", app(BrandRepository::class)->getBrands());
        return $this->fetch();
    }

    public function store(Request $request)
    {
        $res = $this->validate($request->post(), 'Product.store');
        if ($res !== true) return jsonFailed($res);
        return app(ProductRepository::class)->create($request->param());
    }

    public function edit(Request $request)
    {
        $product = app(ProductRepository::class)->getProduct($request->get('id'));
        $this->assign('product', $product);
        $this->assign('freights', app(ShippingFreightRepository::class)->getFreights());
        $this->assign('product_models', app(ProductModelRepository::class)->getProductModels());
        $this->assign("brands", app(BrandRepository::class)->getBrands());
        $this->assign('categorys', app(ProductCategoryRepository::class)->getCategorys());
        return $this->fetch();
    }

    public function update(Request $request)
    {
        $res = $this->validate($request->post(), 'Product.update');
        if ($res !== true) return jsonFailed($res);
        return app(ProductRepository::class)->update($request->post(), $request->id);
    }

    public function delete(Request $request)
    {
        $res = $this->validate($request->get(), 'Product.delete');
        if ($res !== true) return jsonFailed($res);
        return app(ProductRepository::class)->delete($request->id);
    }

    public function getCategorys(Request $request)
    {
        $categorys = app(ProductCategoryRepository::class)->getCategorys(['parent_id' => $request->parent_id, 'status' => 1], $type = 'select');
        return jsonSuccess($categorys);
    }

    /**
     * 获取产品模型
     * 获取产品模型下属性与规格
     * @param int product_model_id
     */
    public function getProductModel(Request $request)
    {
        $res = $this->validate($request->param(), 'Product.getProductModel');
        if ($res !== true) return jsonFailed($res);
        $product_model = app(ProductModelRepository::class)->getProductModelAllData($request->param('product_model_id'));
        if (empty($product_model)) return jsonFailed('产品模型不存在');
        return jsonSuccess($product_model);
    }

    public function getProducts(Request $request)
    {
        $params = $request->param();
        if ($request->has('category_id')) {
            $params['category_ids'] = app(ProductCategoryRepository::class)->getCategoryChildIds($request->param('category_id'));
            unset($params['category_id']);
        }
        $products = app(ProductRepository::class)->getProducts($params);
        return jsonSuccess($products);
    }

    public function sku(Request $request)
    {
        $params = $request->param();
        if ($request->has('category_id') && !empty($request->get('category_id'))) {
            $params['category_ids'] = app(ProductCategoryRepository::class)->getCategoryChildIds($request->get('category_id'));
            unset($params['category_id']);
        }
        $skus = app(ProductRepository::class)->getProductSkusPaginate($params);
        $this->assign('skus', $skus);
        $this->assign('categorys', app(ProductCategoryRepository::class)->getCategorys([], $type = 'tree'));
        return $this->fetch();
    }

    public function editSku(Request $request)
    {
        $sku = app(ProductRepository::class)->getProductSku($request->get('id'));
        $this->assign("sku", $sku);
        return $this->fetch();
    }

    public function updateSku(Request $request)
    {
        return app(ProductRepository::class)->updateSku($request->post(), $request->post('id'));
    }
}