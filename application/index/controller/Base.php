<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Base Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\index\controller;

use think\Controller;
use app\repository\ProductCategoryRepository;

class Base extends Controller
{
    public function initialize()
    {
        $this->assign('productCategorys', app(ProductCategoryRepository::class)->getMenuCategorys($is_use_image = 0));
    }
}
