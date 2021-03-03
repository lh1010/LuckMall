<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Product Specification Validate
 * ============================================================================
 * Author: Jasper
 */

namespace app\admin\validate;

use think\Validate;

class ProductSpecification extends Validate
{
    function __construct()
    {
        $this->rule = [
            'id' => 'require',
            'name'  =>  'require',
            'sort' => 'number',
            '__token__' => 'token'
        ];
        
        $this->message = [
            'id.require' => '缺少必需参数：id',
            'name.require' => '规格名不能为空',
            'sort.number' => '排序值只能为数字',
            '__token__.token' => '重复提交，请刷新页面',
        ];

        $this->scene = [
            'store' => ['name', 'sort', '__token__'],
            'update' => ['id', 'name', 'sort', '__token__'],
            'delete' => ['id'],
            'edit' => ['id'],
        ];
    }
}
