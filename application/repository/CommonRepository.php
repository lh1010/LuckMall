<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * 公共辅助类 Repository
 * ============================================================================
 * Author: Jasper   
 */

namespace app\repository;

use think\Db;
use think\facade\Env;

class CommonRepository
{
    public function installCity()
    {
        set_time_limit(0);
        $file = Env::get('root_path') . 'data/sql/luck_city.php';
        if (!file_exists($file)) return arrayFailed('安装文件不存在');
        $res = Db::query("select * from information_schema.tables where table_name = '" . Config('database.prefix') . "city' and table_schema = '" . Config('database.database') . "'");
        if (!empty($res)) {
            return arrayFailed('数据库 ' . Config('database.prefix') . 'city 表已存在。<br/>请备份后删除该表再进行安装。');
        }
        $array = include($file);
        if (Config('database.prefix') != "luck_") {
            $array[0] = str_replace("luck_", Config('database.prefix'), $array[0]);
            $array[1] = str_replace("luck_", Config('database.prefix'), $array[1]);
        }
        Db::query($array[0]);
        Db::query($array[1]);
        return arraySuccess();
    }

    /**
     * 批量处理产品图
     * 检查图片压缩图
     * 批量生成产品压缩图
     */
    public function setProductImage()
    {
        set_time_limit(0);
        $product_images = Db::name('product_image')->field('image')->select();
        foreach ($product_images as $key => $value) {
            app(\app\repository\admin\ImageRepository::class)->setProductImage($value['image']);
        }
        return jsonSuccess($product_images);
    }

    /**
     * 批量处理产品分类关系
     * @param string $type
     * update_child_ids: 重置下级分类集
     * update_parent_ids: 重置上级分类集
     */
    public function setProductCategory($type)
    {
        switch ($type) {
            case 'update_child_ids':
                try {
                    Db::name('product_category')->where('id', '>', 0)->update(['child_ids' => '']);
                    $category_ids = Db::name('product_category')->column('id');
                    foreach ($category_ids as $key => $value) {
                        $child_ids = [];
                        $child_ids = app(\app\repository\admin\ProductCategoryRepository::class)->getChildIds($value);
                        $child_ids = implode(',', $child_ids);
                        Db::name('product_category')->where('id', $value)->update(['child_ids' => $child_ids]);
                    }
                    Db::commit();
                    return arraySuccess();
                } catch (\Throwable $th) {
                    Db::rollback();
                    return arrayFailed($th->getMessage());
                }
                break;
            case 'update_parent_ids':
                try {
                    Db::name('product_category')->where('id', '>', 0)->update(['parent_ids' => '']);
                    $category_ids = Db::name('product_category')->column('id');
                    foreach ($category_ids as $key => $value) {
                        $parent_ids = [];
                        $parent_ids = app(\app\repository\admin\ProductCategoryRepository::class)->getParentIds($value);
                        $parent_ids = implode(',', $parent_ids);
                        Db::name('product_category')->where('id', $value)->update(['parent_ids' => $parent_ids]);
                    }
                    Db::commit();
                    return arraySuccess();
                } catch (\Throwable $th) {
                    Db::rollback();
                    return arrayFailed($th->getMessage());
                }
                break;
        }
    }
}