<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Admin Article Validate
 * ============================================================================
 * Author: Jasper 
 */

namespace app\admin\validate;

use think\Validate;

class Article extends Validate
{
    function __construct()
    {
        $this->rule = [
            'id' => 'require',
            'title' => 'require',
            'sort' => 'number'
        ];
        
        $this->message = [
            'id.require' => '文档ID不能为空',
            'title.require' => '标题不能为空',
            'sort.number' => '排序值只能为数字类型',
        ];

        $this->scene = [
            'store' => ['title', 'sort'],
            'update' => ['id', 'title', 'sort'],
            'destory' => ['id'],
        ];
    }
}
