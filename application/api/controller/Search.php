<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Api Search Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\api\controller;

use think\Db;
use think\Request;

class Search extends Base
{
    public function get_search_hot_word()
    {
        $res = Db::name('search_hot_word')
                ->where('status', 1)
                ->order('sort', 'desc')
                ->select();
        return json($res);        
    }
}
