<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利。
 * ----------------------------------------------------------------------------
 * Admin City Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\admin\controller;

use think\Request;
use app\repository\CityRepository;

class City extends Base
{
    public function getCitys(Request $request)
    {
        $citys = app(CityRepository::class)->getCitys(['parent_id' => $request->parent_id]);
        return jsonSuccess($citys);
    }
}