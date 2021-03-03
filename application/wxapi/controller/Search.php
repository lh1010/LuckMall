<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Search Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\wxapi\controller;

use think\Request;
use think\Db;

class Search extends Base
{
    public function getSearchHotWord(Request $request)
    {
        $search_hot_words = Db::name('search_hot_word')->where('status', 1)->order('sort desc')->select();
        return jsonSuccess($search_hot_words);
    }
}
