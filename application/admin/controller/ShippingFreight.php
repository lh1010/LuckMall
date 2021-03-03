<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * 运费模板
 * ============================================================================
 * Author: Jasper
 */

namespace app\admin\controller;

use think\Request;
use think\Db;
use app\repository\admin\ShippingFreightRepository;

class ShippingFreight extends Base
{
    public function index()
    {
        $this->assign('freights', app(ShippingFreightRepository::class)->getFreightsPaginate($value = 1));
        return $this->fetch();
    }

    public function create()
    {
        $this->assign('parentCitys', app(\app\repository\CityRepository::class)->getCitys(['parent_id' => 0]));
        return $this->fetch();
    }

    public function store(Request $request)
    {
        $res = $this->validate($request->post(), 'ShippingFreight.store');
        if ($res !== true) return jsonFailed($res);
        return app(ShippingFreightRepository::class)->create($request->post());
    }

    public function edit(Request $request)
    {
        $this->assign('freight', app(ShippingFreightRepository::class)->getFreight($request->id, $value = 1));
        $this->assign('parentCitys', app(\app\repository\CityRepository::class)->getCitys(['parent_id' => 0]));
        return $this->fetch();
    }

    public function update(Request $request)
    {
        $res = $this->validate($request->post(), 'ShippingFreight.update');
        if ($res !== true) return jsonFailed($res);
        return app(ShippingFreightRepository::class)->update($request->post(), $request->id);
    }

    public function delete(Request $request)
    {
        $res = $this->validate($request->param(), 'ShippingFreight.delete');
        if ($res !== true) return jsonFailed($res);
        $res = app(ShippingFreightRepository::class)->delete($request->param('id'));
        return json($res);
    }
}