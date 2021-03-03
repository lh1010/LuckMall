<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Brand Repository
 * ============================================================================
 * Author: Jasper
 */

namespace app\repository;

use think\Db;

class BrandRepository 
{
    /**
     * Get Brands
     * @param string $params['keyword']
     * @param int $params['status']
     * @param int $params['product_category_id']
     * @param array $params['product_category_ids']
     * @param int $limit
     * @return array
     */
    public function getBrands($params = [], $limit = 0)
    {
        $query = Db::name('brand')->alias('brand');
        $query->field('brand.*, product_category.name as product_category_name');
        $query->leftJoin('product_category', 'product_category.id = brand.product_category_id');
        $query->order('brand.sort', 'desc');
        $this->setGetBrandsParams($query, $params);
        if ($limit > 0) $query->limit($limit);
        $brands = $query->select();
        $brands = $this->setThumbnail($brands, 2);
        return $brands;
    }

    private function setGetBrandsParams($query, $params = [])
    {
        if (isset($params['keyword'])) $query->where('brand.name', 'like', '%'.$params['keyword'].'%');
        if (isset($params['status']) && $params['status'] != '') $query->where('brand.status', $params['status']);
        if (isset($params['product_category_id'])) $query->where('brand.product_category_id', $params['product_category_id']);
        if (isset($params['product_category_ids'])) $query->whereIn('brand.product_category_id', $params['product_category_ids']);
    }

    public function getBrand($id)
    {
        $brand = Db::name('brand')->where('id', $id)->find();
        return $brand;
    }

    /**
     * Set Brand Thumbnail Show
     * @param array $data
     * @param int $type 1|2|3
     * @return array
     */
    public function setThumbnail($data, $type = 3)
    {
        if (empty($data)) return [];
        switch ($type) {
            case '1':
                empty($data['thumbnail']) ? $data['thumbnail'] = Config('image.brand_default') : null;
                break;
            case '2':
                foreach ($data as $key => $value) {
                    empty($value['thumbnail']) ? $data[$key]['thumbnail'] = Config('image.brand_default') : null;
                }
                break;    
            case '3':
                $data->each(function($item, $key) {
                    if (empty($item['thumbnail'])) {
                        $item['thumbnail'] = Config('image.brand_default');
                    }
                    return $item;
                });
                break;
        }
        return $data;
    }
}