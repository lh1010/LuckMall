<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Admin Integral Validate
 * ============================================================================
 * Author: Jasper
 */

namespace app\admin\validate;

use think\Validate;

class Integral extends Validate
{
    protected $rule = [
        'id' => 'require',
        'name' => 'require',
        'integral' => 'require|number|gt:0',
        'sort' => 'integer',
        '__token__' => 'token'
    ];
    
    protected $message = [
        'id.require' => '缺少必需参数：id',
        'name.require' => '产品名不能为空',
        'integral.require' => '兑换积分只能为正数数字',
        'integral.number' => '兑换积分只能为正数数字',
        'integral.gt' => '兑换积分只能为正数数字',
        'sort.integer' => '排序值必须为数字',
        '__token__.token' => '重复提交，请刷新页面',
    ];

    protected $scene = [
        'storeProduct' => ['name', 'integral', 'sort', '__token__'],
        'updateProduct' => ['id', 'name', 'integral', 'sort', '__token__'],
        'deleteProduct' => ['id']
    ];
}