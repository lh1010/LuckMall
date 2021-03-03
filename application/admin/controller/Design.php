<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Admin Design Controller
 * ============================================================================
 * Author: Jasper   
 */

namespace app\admin\controller;

use think\Request;
use think\Db;
use app\repository\admin\DesignRepository;

class Design extends Base
{
    // 导航设置
    public function nav()
    {
        $navs = Db::name('design_nav')->paginate();
        $this->assign("navs", $navs);
        return $this->fetch();
    }

    public function nav_create(Request $request)
    {
        if ($request->isPost()) {
            $data = $request->post();
            Db::name('design_nav')->insert($data);
            return jsonSuccess();
        }
        return $this->fetch();
    }

    public function nav_edit(Request $request)
    {
        if ($request->isPost()) {
            $data = $request->post();
            $id = $data['id']; unset($data['id']);
            Db::name('design_nav')->where('id', $id)->update($data);
            return jsonSuccess();
        }
        $nav = Db::name('design_nav')->where('id', $request->param('id'))->find();
        if (empty($nav)) abort(404);
        $this->assign("nav", $nav);
        return $this->fetch();
    }

    public function nav_delete(Request $request)
    {
        Db::name('design_nav')->whereIn('id', $request->param('id'))->delete();
        return jsonSuccess();
    }

    // 友情链接
    public function friendlink()
    {
        $friendlinks = Db::name('friendlink')->paginate();
        $this->assign("friendlinks", $friendlinks);
        return $this->fetch();
    }

    public function friendlink_create(Request $request)
    {
        if ($request->isPost()) {
            $data = $request->post();
            Db::name('friendlink')->insert($data);
            return jsonSuccess();
        }
        return $this->fetch();
    }

    public function friendlink_edit(Request $request)
    {
        if ($request->isPost()) {
            $data = $request->post();
            $id = $data['id']; unset($data['id']);
            Db::name('friendlink')->where('id', $id)->update($data);
            return jsonSuccess();
        }
        $friendlink = Db::name('friendlink')->where('id', $request->param('id'))->find();
        if (empty($friendlink)) abort(404);
        $this->assign("friendlink", $friendlink);
        return $this->fetch();
    }

    public function friendlink_delete(Request $request)
    {
        Db::name('friendlink')->whereIn('id', $request->param('id'))->delete();
        return jsonSuccess();
    }
}
