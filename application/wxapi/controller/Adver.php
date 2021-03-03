<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Adver Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\wxapi\controller;

use think\Request;
use app\repository\AdverRepository;

class Adver extends Base
{
    public function getAdver(Request $request)
    {
        $adver_id = '';
        $site = $request->param('site');
        switch ($site) {
            case 'index':
                $adver_id = 1;
                break;
        }
        if (empty($adver_id)) return jsonFailed('参数错误');
        $adver = app(AdverRepository::class)->getAdver($adver_id);
        return jsonSuccess($adver);
    }
}
