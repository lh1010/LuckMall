<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * 配送方式 Repository Admin
 * ============================================================================
 * Author: Jasper   
 */

namespace app\repository\admin;

use think\Db;

class ShippingMarkRepository
{   
    public function getMarksPaginate()
    {
        $query = Db::name('shipping_mark');
        $query->where('status', '<>', 99);
        $marks = $query->paginate();
        return $marks;
    }

    public function getMarks($params = [])
    {
        $query = Db::name('shipping_mark');
        $query->where('status', '<>', 99);
        $this->setGetMarksParams($query, $params);
        $marks = $query->select();
        return $marks;
    }

    private function setGetMarksParams($query, $params)
    {
        if (isset($params['status']) && $params['status'] != '') $query->where('status', $params['status']);
    }

    public function getMark($id)
    {
        $mark = Db::name('shipping_mark')->where('id', $id)->where('status', '<>', 99)->find();
        return $mark;
    }

    public function create($params = [])
    {
        $data = $this->setCreateUpdateData($params);
        Db::name('shipping_mark')->insert($data);
        return arraySuccess();
    }

    public function update($params, $id)
    {
        $data = $this->setCreateUpdateData($params);
        Db::name('shipping_mark')->where('id', $id)->update($data);
        return arraySuccess();
    }

    private function setCreateUpdateData($params = [])
    {
        $data = [];
        if (isset($params['name'])) $data['name'] = $params['name'];
        if (isset($params['sort'])) $data['sort'] = $params['sort'];
        if (isset($params['description'])) $data['description'] = $params['description'];
        if (isset($params['status'])) $data['status'] = $params['status'];
        return $data;
    }
}