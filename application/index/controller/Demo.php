<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * 测试文件 Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\index\controller;

use think\Request;

class Demo
{
    // 批量处理产品缩略图
    public function setProductImage()
    {
        //return app(\app\repository\CommonRepository::class)->setProductImage();
    }

    // 批量处理产品分类关系
    public function setProductCategory()
    {
        //$res = app(\app\repository\admin\ProductCategoryRepository::class)->setProductCategory($type = 'update_parent_ids');
        //dd($res);
    }
}
