<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Admin 搜索热词 Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\admin\controller;

use think\Request;
use think\Db;

class HotWord extends Base
{
    public function index(Request $request)
    {
        $search_hot_words = Db::name('search_hot_word')->paginate();
        $this->assign('search_hot_words', $search_hot_words);
        return $this->fetch();
    }

    public function create()
    {
        return $this->fetch();
    }

    public function store(Request $request)
    {
        $data = [];
        $data['sort'] = $request->post('sort');
        $data['value'] = $request->post('value');
        $data['status'] = $request->post('status');
        Db::name('search_hot_word')->insert($data);
        return jsonSuccess();
    }

    public function edit(Request $request)
    {
        $search_hot_word = Db::name('search_hot_word')->where('id', $request->get('id'))->find();
        $this->assign('search_hot_word', $search_hot_word);
        return $this->fetch();
    }

    public function update(Request $request)
    {
        $data = [];
        $data['sort'] = $request->post('sort');
        $data['value'] = $request->post('value');
        $data['status'] = $request->post('status');
        Db::name('search_hot_word')->where('id', $request->post('id'))->update($data);
        return jsonSuccess();
    }

    public function delete(Request $request)
    {
        Db::name('search_hot_word')->whereIn('id', $request->get('id'))->delete();
        return jsonSuccess();
    }
}