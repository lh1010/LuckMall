<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Admin Brand Validate
 * ============================================================================
 * Author: Jasper
 */

namespace app\admin\validate;

use think\Validate;

class Brand extends Validate
{
    function __construct() 
    {
        $this->rule = [
            'id' => 'require',
            'name' => 'require',
            'sort' => 'number',
            '__token__' => 'token'
        ];
        
        $this->message = [
            'id.require' => '参数错误',
            'name.require' => '品牌名不能为空',
            'sort.number' => '排序值只能为数字'
        ];

        $this->scene = [
            'store' => ['name', 'sort', '__token__'],
            'update' => ['id', 'name', 'sort', '__token__'],
            'delete' => ['id'],
        ];
    }
}
