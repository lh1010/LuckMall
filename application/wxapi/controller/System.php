<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * System Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\wxapi\controller;

use think\Request;

class System extends Base
{
    /**
     * 获取项目配置
     */
    public function getConfig()
    {
        $config = Config('system.');
        return jsonSuccess($config);
    }
}
