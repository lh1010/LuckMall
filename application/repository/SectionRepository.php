<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Section Repository
 * ============================================================================
 * Author: Jasper   
 */

namespace app\repository;

use think\Db;
use app\repository\ProductRepository;

class SectionRepository
{
    public function getSection($section_id)
    {
        $section = Db::name('section')->where('id', $section_id)->find();
        if (empty($section)) return $section;
        $product_ids = Db::name('section_value')->where('section_id', $section_id)->column('value_id');
        
        $products = app(ProductRepository::class)->getProducts_format2(['product_ids' => $product_ids]);
        $section['values'] = $products;
        return $section;
    }

    public function getSections($section_ids)
    {
        $array = explode(',', $section_ids);
        foreach ($array as $key => $value) {
            $array[$key] = $this->getSection($value);
        }
        return $array;
    }
}