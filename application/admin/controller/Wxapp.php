<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Admin 微信小程序 Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\admin\controller;

use think\Request;
use think\Db;

class Wxapp extends Base
{
    public function sudoku(Request $request)
    {
        $sudokus = Db::name('sudoku')->order('sort desc')->paginate();
        $this->assign('sudokus', $sudokus);
        return $this->fetch();
    }

    public function create_sudoku()
    {
        return $this->fetch();
    }

    public function store_sudoku(Request $request)
    {
        $data = [];
        $data['title'] = $request->post('title');
        $data['image'] = $request->post('image');
        $data['page_url'] = $request->post('page_url');
        $data['page_ident'] = $request->post('page_ident');
        $data['sort'] = $request->post('sort');
        $data['status'] = $request->post('status');
        Db::name('sudoku')->insert($data);
        return jsonSuccess();
    }

    public function edit_sudoku(Request $request)
    {
        $sudoku = Db::name('sudoku')->where('id', $request->get('id'))->find();
        $this->assign('sudoku', $sudoku);
        return $this->fetch();
    }

    public function update_sudoku(Request $request)
    {
        $data = [];
        $data['title'] = $request->post('title');
        $data['image'] = $request->post('image');
        $data['page_url'] = $request->post('page_url');
        $data['page_ident'] = $request->post('page_ident');
        $data['sort'] = $request->post('sort');
        $data['status'] = $request->post('status');
        Db::name('sudoku')->where('id', $request->post('id'))->update($data);
        return jsonSuccess();
    }

    public function delete_sudoku(Request $request)
    {
        Db::name('sudoku')->whereIn('id', $request->get('id'))->delete();
        return jsonSuccess();
    }
}