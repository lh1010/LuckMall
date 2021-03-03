<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Brand Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\index\controller;

use think\Request;
use app\repository\BrandRepository;

class Brand extends Base
{
    public function list($id, Request $request)
    {
        dd($id);
    }
}
