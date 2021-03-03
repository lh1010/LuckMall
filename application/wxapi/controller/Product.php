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

namespace app\wxapi\controller;

use think\Request;
use app\repository\ProductRepository;
use app\repository\ProductCategoryRepository;
use app\repository\SectionRepository;

class Product extends Base
{
    protected $middleware = [
        'CheckAppUserLogin' => ['only' => ['collect', 'getCollectProducts', 'removeCollectProducts']]
    ];

    public function getCategorys()
    {
        $categorys = app(ProductCategoryRepository::class)->getMenuCategorys($is_use_brand = 0);
        return jsonSuccess($categorys);
    }

    public function getProductsPaginate(Request $request)
    {
        $params = $request->param();
        if ($request->has('category_id')) {
            $params['category_ids'] = app(ProductCategoryRepository::class)->getChildIds($request->param('category_id'));
            unset($params['category_id']);
        }
        $products = app(ProductRepository::class)->getProductsPaginate_format2($params, $page_size = $request->param('page_size'));
        return jsonSuccess($products->toArray());
    }

    public function getProduct(Request $request)
    {
        $user_id = getUserId();
        $product = app(ProductRepository::class)->getProduct(['sku' => $request->sku]);
        if (empty($product)) return jsonSuccess($product);
        // set product attributes
        $freight = isset($product['freight']) && $product['freight'] == 2 ? '不包邮' : '包邮';
        $array = [
            ['product_attribute_name' => '商品编号', 'product_attribute_value' => $product['sku']],
            ['product_attribute_name' => '是否包邮', 'product_attribute_value' => $freight]
        ];
        $product['attributes'] = array_merge($array, $product['attributes']);
        $product['is_collect'] = app(ProductRepository::class)->is_collect($product['sku'], $user_id);
        return jsonSuccess($product);
    }

    public function getCollectProducts()
    {
        $user_id = getUserId();
        $collectProducts = app(ProductRepository::class)->getCollectProducts($user_id);
        return jsonSuccess($collectProducts);
    }

    public function removeCollectProducts(Request $request)
    {
        $skus = explode(',', $request->param('skus'));
        app(ProductRepository::class)->removeCollectProducts($skus, getUserId());
        return jsonSuccess();
    }

    public function collect(Request $request)
    {
        $user_id = getUserId();
        $res = app(ProductRepository::class)->collect($request->param('sku'), $user_id);
        return json($res);
    }

    public function getSections(Request $request)
    {
        $id = '';
        $site = $request->param('site');
        switch ($site) {
            case 'index':
                $id = '2';
                break;
        }
        if (empty($id)) return jsonFailed('参数错误');
        $sections = app(SectionRepository::class)->getSections($id);
        return jsonSuccess($sections);
    }
}
