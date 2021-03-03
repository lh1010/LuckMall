<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Admin Product Category Validate
 * ============================================================================
 * Author: Jasper    
 */

namespace app\admin\validate;

use think\Validate;

class ProductCategory extends Validate
{
	protected $rule = [
		'id' => "require",
        'name' => "require",
        'sort' => "integer",
        '__token__' => 'token'
    ];

    protected $message = [
    	'id.require' => '缺少必需参数：id',
        'name.require' => '分类名字不能为空',
        'sort.integer' => '排序值格式不正确',
        '__token__.token' => '重复提交，请刷新页面',
    ];

    protected $scene = [
        'store' => ['name', 'sort', '__token__'],
        'delete' => ['id'],
        'update' => ['id', 'name', 'sort', '__token__'],
    ];
}
