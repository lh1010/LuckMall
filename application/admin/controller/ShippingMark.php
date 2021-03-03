<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * 配送方式
 * ============================================================================
 * Author: Jasper
 */

namespace app\admin\controller;

use think\Request;
use think\Db;
use app\repository\admin\ShippingMarkRepository;

class ShippingMark extends Base
{
    public function index()
    {
        $this->assign('marks', app(ShippingMarkRepository::class)->getMarksPaginate());
        return $this->fetch();
    }

    public function create()
    {
        return $this->fetch();
    }

    public function store(Request $request)
    {
        $res = $this->validate($request->post(), 'ShippingMark.store');
        if ($res !== true) return jsonFailed($res);
        return app(ShippingMarkRepository::class)->create($request->post());
    }

    public function edit(Request $request)
    {
        $this->assign('mark', app(ShippingMarkRepository::class)->getMark($request->id));
        return $this->fetch();
    }

    public function update(Request $request)
    {
        $res = $this->validate($request->post(), 'ShippingMark.update');
        if ($res !== true) return jsonFailed($res);
        return app(ShippingMarkRepository::class)->update($request->post(), $request->id);
    }

    public function delete(Request $request)
    {
        $res = $this->validate($request->param(), 'ShippingMark.delete');
        if ($res !== true) return jsonFailed($res);
        if (!Db::name('shipping_mark')->where('id', $request->id)->update(['status' => 99])) {
            return jsonFailed();
        }
        return jsonSuccess();
    }
}