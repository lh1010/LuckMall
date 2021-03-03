<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Product Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\index\controller;

use think\Request;
use think\Db;
use app\repository\ProductRepository;
use app\repository\ProductCategoryRepository;

class Product extends Base
{
	public function products($category_id = '', Request $request)
	{
		$params = $request->get();
		if (!empty($category_id)) {
			$category = Db::name('product_category')->where('id', $category_id)->where('status', 1)->find();
			$params['category_id'] = $category_id;
			$params['category_ids'] = $category['child_ids'];
		}
		$data = [];
		$res = app(ProductCategoryRepository::class)->setAdditionData($params);
		$data['nav'] = $res['nav'];
		$data['title'] = $res['title'];
		unset($params['category_id']);
		$data['products'] = app(ProductRepository::class)->getProductsPaginate_format1($params);
		$this->assign('data', $data);
		return $this->fetch();
	}

    public function show($sku, Request $request)
    {
		$data = [];
		$product = app(ProductRepository::class)->getProduct(['sku' => $sku]);
		if (empty($product)) abort(404);
		$data['nav'] = app(ProductRepository::class)->setNav($product['category_id']);
		$product['is_collect'] = app(ProductRepository::class)->is_collect($product['sku'], getUserId());
		$data['product'] = $product;
		$this->assign('data', $data);
		return $this->fetch();
	}
}
