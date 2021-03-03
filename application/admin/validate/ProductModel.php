<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Product Model Validate
 * ============================================================================
 * Author: Jasper
 */

namespace app\admin\validate;

use think\Validate;
use think\Lang;

class ProductModel extends Validate
{
    function __construct()
    {
        $this->rule = [
            'id' => 'require',
            'name'  =>  'require',
            '__token__' => 'token'
        ];
        
        $this->message = [
            'id.require' => '缺少必需参数：id',
            'name.require' => '商品模型名称不能为空',
            '__token__.token' => '重复提交，请刷新页面',
        ];

        $this->scene = [
            'store' => ['name', '__token__'],
            'delete' => ['id'],
            'edit' => ['id'],
            'update' => ['id', 'name', '__token__'],
        ];
    }
}
