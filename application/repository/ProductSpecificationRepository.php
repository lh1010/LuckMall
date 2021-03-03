<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Product Specification Repository
 * ============================================================================
 * Author: Jasper   
 */

namespace app\repository;

use think\Db;

class ProductSpecificationRepository
{
    /**
     * Get Product Specifications
     * @param array $skus
     * @return array
     */
    public function getCurrentProductToSpecifications($params = [])
    {
        $filed = 'product_to_specification.product_id, product_to_specification.sku, product_to_specification.product_specification_id as specification_id, product_to_specification.product_specification_option_id as specification_option_id, product_specification.name as specification_name, product_specification_option.value as specification_option_value';
        $query = Db::name('product_to_specification')->alias('product_to_specification');
        $query->field($filed);
        $query->leftJoin('product_specification', 'product_specification.id = product_to_specification.product_specification_id');
        $query->leftJoin('product_specification_option', 'product_specification_option.id = product_to_specification.product_specification_option_id');
        if (isset($params['skus'])) $query->whereIn('sku', $params['skus']);
        if (isset($params['sku'])) $query->where('sku', $params['sku']);
        $product_to_specifications = $query->select();
        return $product_to_specifications;                        
    }

    public function getProductToSpecifications($sku, $product_id)
    {
        // 当前 sku 下的销售规格
        $current_specifications = Db::name('product_to_specification')->where('sku', $sku)->select();
        $current_specification_ids = array_column($current_specifications, 'product_specification_id');
        $current_specification_option_ids = array_column($current_specifications, 'product_specification_option_id');

        // 当前商品下的销售规格
        $product_to_specifications = Db::name('product_to_specification')->alias('product_to_specification')
                                    ->field('product_to_specification.*, product_specification.name as specification_name, product_specification_option.value as specification_option_value, product_sku.stock')
                                    ->leftJoin('product_specification', 'product_specification.id = product_to_specification.product_specification_id')
                                    ->leftJoin('product_specification_option', 'product_specification_option.id = product_to_specification.product_specification_option_id')
                                    ->leftJoin('product_sku', 'product_sku.sku = product_to_specification.sku')
                                    ->where('product_to_specification.product_id', $product_id)
                                    ->select();

        // 分配销售规格组
        $skus = [];
        foreach ($product_to_specifications as $key => $value) $skus[$value['sku']][] = $value;

        // 获取与当前销售规格有关联的sku
        // 获取与当前销售规格有关联的商品关联规格ID
        // 设置商品关联规格是否有效/可点击
        $have_product_skus = [];
        $have_product_to_specification_ids = [];
        foreach ($product_to_specifications as $key => $value) {
            if (count($current_specification_option_ids) > 1) {
                if (in_array($value['product_specification_option_id'], $current_specification_option_ids)) {
                    $current_sku_specification_option_ids = array_column($skus[$value['sku']], 'product_specification_option_id');
                    $the_same_date_count = array_intersect($current_sku_specification_option_ids, $current_specification_option_ids);
                    if (count($the_same_date_count) >= count($current_specification_option_ids) - 1) {
                        $have_product_skus[] = $value['sku'];
                        $have_product_to_specification_ids[] = $value['id'];
                    }
                }
            } else {
                $have_product_skus[] = $value['sku'];
                $have_product_to_specification_ids[] = $value['id'];
            }
        }
        foreach ($product_to_specifications as $key => $value) {
            $product_to_specifications[$key]['valid'] = 0;
            if (in_array($value['sku'], $have_product_skus)) {
                $product_to_specifications[$key]['valid'] = 1;
            }
        }

        // 组装数据
        $array = [];
        foreach ($product_to_specifications as $key => $value) {
            $array[$value['product_specification_id']]['product_specification_id'] = $value['product_specification_id'];
            $array[$value['product_specification_id']]['specification_name'] = $value['specification_name'];
            $array[$value['product_specification_id']]['options'][$value['product_specification_option_id']]['product_specification_option_id'] = $value['product_specification_option_id'];
            $array[$value['product_specification_id']]['options'][$value['product_specification_option_id']]['specification_option_value'] = $value['specification_option_value'];
            if ($value['valid'] == 1) {
                $array[$value['product_specification_id']]['options'][$value['product_specification_option_id']]['valid'] = $value['valid'];
                $array[$value['product_specification_id']]['options'][$value['product_specification_option_id']]['sku'] = $value['sku'];
                $array[$value['product_specification_id']]['options'][$value['product_specification_option_id']]['stock'] = $value['stock'];
            } 
            if ($value['sku'] == $sku) $array[$value['product_specification_id']]['options'][$value['product_specification_option_id']]['selected'] = 1;
        }

        // 清除选中规格的sku信息，因为它是无效的
        foreach ($array as $key => $value) {
            foreach ($value['options'] as $key_option => $value_option) {
                if (isset($value_option['selected'])) {
                    unset($array[$key]['options'][$key_option]['sku']);
                    unset($array[$key]['options'][$key_option]['stock']);
                }
            }
        }

        return $array;
    }
}