<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利。
 * ----------------------------------------------------------------------------
 * Admin Index Controller
 * ============================================================================
 * Author: Jasper      
 */

namespace app\admin\controller;

use think\Db;

class Index extends Base
{
    public function index()
    {
        return $this->fetch('layouts/layout');
    }

    public function welcome()
    {
        return $this->fetch();
    }

    public function getAppMessage()
    {
    	$message = '';
        // 地区表
    	$res = Db::query("select * from information_schema.tables where table_name = '" . Config('database.prefix') . "city' and table_schema = '" . Config('database.database') . "'");
    	if (empty($res)) {
    		$message .= '<p class="bg-warning message">缺少必需插件：<b>地区库</b><a href="javascript:void(0);" onclick="installCity();">点击安装</a></p>';
    	}
    	// install文件夹是否删除或重命名
    	if (file_exists(get_public_path() . '/install')) {
    		$message .= '<p class="bg-warning message">为了您的项目安全，请尽快移除根目录下的 <b class="text-danger">install</b> 文件夹。</p>';
    	}
    	return json($message);
    }

    public function installCity()
    {
    	$res = app(\app\repository\CommonRepository::class)->installCity();
    	return json($res);
    }
}
