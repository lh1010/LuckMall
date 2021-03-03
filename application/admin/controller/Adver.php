<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Admin Adver Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\admin\controller;

use think\Request;
use app\repository\admin\AdverRepository;

class Adver extends Base
{
    public function index(Request $request)
    {
        $advers = app(AdverRepository::class)->getAdversPaginate($request->get());
        $this->assign('advers', $advers);
        $this->assign('clientArray', app(AdverRepository::class)->clientArray);
        return $this->fetch();
    }

    public function create()
    {
        $this->assign('clientArray', app(AdverRepository::class)->clientArray);
        return $this->fetch();
    }

    public function store(Request $request)
    {
        $res = $this->validate($request->post(), 'Adver.store');
        if ($res !== true) return jsonFailed($res);
        return app(AdverRepository::class)->create($request->post());
    }

    public function edit(Request $request)
    {
        $adver = app(AdverRepository::class)->getAdver($request->id);
        if (empty($adver)) abort(404);
        $this->assign('clientArray', app(AdverRepository::class)->clientArray);
        $this->assign('adver', $adver);
        return $this->fetch();
    }

    public function update(Request $request)
    {
        $res = $this->validate($request->post(), 'Adver.update');
        if ($res !== true) return jsonFailed($res);
        $data = $request->post();
        return app(AdverRepository::class)->update($data, $request->id);
    }

    public function delete(Request $request)
    {
        $res = $this->validate($request->param(), 'Adver.delete');
        if ($res !== true) return jsonFailed($res);
        return app(AdverRepository::class)->delete($request->id);
    }
}