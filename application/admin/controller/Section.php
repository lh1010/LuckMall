<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Admin Section Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\admin\controller;

use think\Request;
use app\repository\admin\SectionRepository;
use app\repository\admin\ProductCategoryRepository;

class Section extends Base
{
    public function index(Request $request)
    {
        $sections = app(SectionRepository::class)->getSectionsPaginate($request->get());
        $this->assign('sections', $sections);
        $this->assign('clientArray', app(SectionRepository::class)->clientArray);
        return $this->fetch();
    }

    public function create()
    {
        $this->assign("categorys", app(ProductCategoryRepository::class)->getCategorys(['status' => 1], $type = 'tree'));
        $this->assign('clientArray', app(SectionRepository::class)->clientArray);
        return $this->fetch();
    }

    public function store(Request $request)
    {
        $res = $this->validate($request->post(), 'Section.store');
        if ($res !== true) return jsonFailed($res);
        return app(SectionRepository::class)->create($request->post());
    }

    public function edit(Request $request)
    {
        $section = app(SectionRepository::class)->getSection($request->id);
        if (empty($section)) abort(404);
        $this->assign('section', $section);
        $this->assign('clientArray', app(SectionRepository::class)->clientArray);
        $this->assign("categorys", app(ProductCategoryRepository::class)->getCategorys(['status' => 1], $type = 'tree'));
        return $this->fetch();
    }

    public function update(Request $request)
    {
        $res = $this->validate($request->post(), 'Section.update');
        if ($res !== true) return jsonFailed($res);
        return app(SectionRepository::class)->update($request->post(), $request->id);
    }

    public function delete(Request $request)
    {
        $res = $this->validate($request->param(), 'Section.delete');
        if ($res !== true) return jsonFailed($res);
        return app(SectionRepository::class)->delete($request->id);
    }
}