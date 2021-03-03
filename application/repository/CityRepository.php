<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * City Repository
 * ============================================================================
 * Author: Jasper   
 */

namespace app\repository;

use think\Db;

class CityRepository
{   
    /**
     * @param int $params['parent_id']
     * @return array
     */
    public function getCitys($params = [])
    {
        $query = Db::name('city');
        if (isset($params['parent_id'])) $query->where('parent_id', $params['parent_id']);
        $citys = $query->select();
        return $citys;
    }

    public function getName($id)
    {
        $name = '';
        $name = Db::name('city')->where('id', $id)->value('name');
        return $name;
    }    
}