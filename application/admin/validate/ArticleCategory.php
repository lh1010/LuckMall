<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Admin Article Category Validate
 * ============================================================================
 * Author: Jasper 
 */

namespace app\admin\validate;

use think\Validate;
use think\Db;

class ArticleCategory extends Validate
{
    function __construct()
    {
        $this->rule = [
            'id' => 'require|validateCategory',
            'name' => 'require',
            'sort' => 'number'
        ];
        
        $this->message = [
            'id.require' => '文档ID不能为空',
            'name.require' => '分类名不能为空',
            'sort.number' => '排序值只能为数字类型',
        ];

        $this->scene = [
            'store' => ['name', 'sort'],
            'update' => ['title', 'sort'],
            'destory' => ['id'],
        ];
    }

    protected function validateCategory($value, $rule, $param = [])
    {
        $category = Db::name('article_category')->where('id', $value)->find();
        if (!empty($category) && $category['type'] == 'system') return '系统使用，不允许删除';
        if (Db::name('article_category')->where('parent_id', $value)->find()) return '请先删除该分类下的子分类';
        if (Db::name('article')->where('category_id', $value)->find()) return '请先删除该分类下的文档';
        return true;
    }
}
