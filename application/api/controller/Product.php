<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Api Product
 * ============================================================================
 * Author: Jasper
 */

namespace app\api\controller;

use think\Request;
use app\repository\FreightRepository;
use app\repository\ProductRepository;
use app\repository\SectionRepository;
use app\repository\ProductCategoryRepository;

class Product extends Base
{
    protected $middleware = [
    	'CheckUserLogin' => ['only' => ['collect']],
    ];

    public function getCategorys()
    {
        $categorys = app(ProductCategoryRepository::class)->getMenuCategorys($is_use_brand = 0);
        return jsonSuccess($categorys);
    }

    public function getProductsPaginate(Request $request)
    {
        $products = app(ProductRepository::class)->getProductsPaginate_sku($request->param(), $page_size = $request->param('page_size'));
        return jsonSuccess($products->toArray());
    }

    public function getProduct(Request $request)
    {
        $product = app(ProductRepository::class)->getProduct(['sku' => $request->sku]);
        return jsonSuccess($product);
    }

    public function initFreight(Request $request)
    {
        $res = app(FreightRepository::class)->initFreight($request->product_id, $request->count, $request->region_ids);
        return json($res);
    }
    
    public function getRandProducts(Request $request)
    {
        $params = [];
        $params['sort'] = 'rand';
        $products = app(ProductRepository::class)->getProducts_format2($params, $limit = 2);
        return jsonSuccess($products);
    }

    public function getRecommendProducts(Request $request)
    {
        $products = app(ProductRepository::class)->getProducts_format2([], $limit = 6);
        return jsonSuccess($products);
    }

    /**
     * Get Section Products
     * @param section_id
     */
    public function getSection(Request $request)
    {
        $res = $this->validate($request->param(), 'Product.getSection');
        if ($res !== true) return jsonFailed($res);
        $section = app(SectionRepository::class)->getSection($request->section_id);
        return jsonSuccess($section);
    }

    /**
     * 收藏商品
     * @param string sku
     */
    public function collect(Request $request)
    {
        $user_id = getUserId();
        $res = app(ProductRepository::class)->collect($request->param('sku'), $user_id);
        return json($res);
    }
}
