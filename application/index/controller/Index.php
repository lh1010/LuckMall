<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Index Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\index\controller;

use app\repository\ProductRepository;

class Index extends Base
{
    public function index()
    {
        $data = [];
        $data['products'] = app(ProductRepository::class)->getCategoryRecommendProducts();
        $this->assign('data', $data);
    	return $this->fetch();
    }
}
