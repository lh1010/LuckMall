<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Product Attribute Repository
 * ============================================================================
 * Author: Jasper   
 */

namespace app\repository;

use think\Db;

class ProductAttributeRepository
{
    public function getProductToAttributes($product_id)
    {
        $product_to_attributes = Db::name('product_to_attribute')
                                ->alias('product_to_attribute')
                                ->field('product_to_attribute.*, product_attribute.name as product_attribute_name')
                                ->leftJoin('product_attribute', 'product_attribute.id = product_to_attribute.product_attribute_id')
                                ->where('product_to_attribute.product_id', $product_id)
                                ->select();
        return $product_to_attributes;                        
    }
}