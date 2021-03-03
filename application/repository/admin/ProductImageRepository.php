<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Product Repository Admin
 * ============================================================================
 * Author: Jasper
 */

namespace app\repository\admin;

use think\Db;

class ProductImageRepository
{
    public function getImages($params = [])
    {
        $query = Db::name('product_image');
        $this->setGetImagesParams($query, $params);
        $query->order('default desc');
        return $query->select();
    }

    private function setGetImagesParams($query, $params)
    {
        if (isset($params['sku'])) $query->where('sku', $params['sku']);
        if (isset($params['skus'])) $query->whereIn('sku', $params['skus']);
    }
}