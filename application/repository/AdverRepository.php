<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Adver Repository
 * ============================================================================
 * Author: Jasper   
 */

namespace app\repository;

use think\Db;

class AdverRepository
{
    public function getAdver($id)
    {
        $adver = Db::name('adver')->where('status', 1)->where('id', $id)->find();
        if (empty($adver)) return $adver;
        $values = Db::name('adver_value')->where('adver_id', $id)->order('sort desc')->select();
        $adver['values'] = $this->setImage($values);
        return $adver;
    }

    public function setImage($data)
    {
        if (empty($data)) return $data;
        foreach ($data as $key => $value) {
            $data[$key]['image'] = !empty($value['image']) ? Config('app.app_url') . $value['image'] : '';
        }
        return $data;
    }
}