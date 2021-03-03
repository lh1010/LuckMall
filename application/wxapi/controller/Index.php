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

namespace app\wxapi\controller;

use think\Request;
use think\Db;

class Index extends Base
{
    public function getSudokus()
    {
        $sudokus = Db::name('sudoku')->where('status', 1)->order('sort desc')->limit(10)->select();
        foreach ($sudokus as $key => $value) {
            $sudokus[$key]['image'] = !empty($value['image']) ? Config('app.app_url') . $value['image'] : '';
        }
        return jsonSuccess($sudokus);
    }
}
