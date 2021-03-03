<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * 运费模板 Repository Admin
 * ============================================================================
 * Author: Jasper   
 */

namespace app\repository\admin;

use think\Db;

class ShippingFreightRepository
{   
    public $typeArray = [1 => '件数', 2 => '重量', 3 => '体积'];
    public $unitArray = [1 => '件', 2 => '克', 3 => '立方米'];

    /**
     * @param int $value 0|1 是否调取子数据
     * @return object
     */
    public function getFreightsPaginate($value = 0)
    {
        $query = Db::name('shipping_freight');
        $query->where('status', 1);
        $freights = $query->paginate()->each(function($item, $key) use ($value) {
            $item['typeStr'] = $this->typeArray[$item['type']];
            $item['unit'] = $this->unitArray[$item['type']];
            if ($value == 1) {
                $values = Db::name('shipping_freight_value')->where('freight_id', $item['id'])->order('ship_area', 'asc')->select();
                if ($values) {
                    foreach ($values as $key => $value) {
                        if (!empty($value['ship_area']) || $value['ship_area'] == '0') {
                            $value['ship_area_str'] = '';
                            if ($value['ship_area'] == '0') {
                                $values[$key]['ship_area_str'] = '全国';
                            } else {
                                if ($citys = Db::name('city')->whereIn('id', $value['ship_area'])->select()) {
                                    $citys = array_column($citys, 'name');
                                    $values[$key]['ship_area_str'] = implode(',', $citys);
                                }
                            }
                        }
                    }
                }
                $item['values'] = $values;
            }
            return $item;
        });
		return $freights;
    }

    public function getFreights()
    {
        $query = Db::name('shipping_freight');
        $query->where('status', 1);
        $freights = $query->select();
		return $freights;
    }

    public function getFreight($id, $value = 0)
    {
        $freight = Db::name('shipping_freight')->where('id', $id)->find();
        if ($value == 1) {
            if ($freight) {
                $freight['typeStr'] = $this->typeArray[$freight['type']];
                $freight['unit'] = $this->unitArray[$freight['type']];
                $freight['default_freight'] = 0;
                $values = Db::name('shipping_freight_value')->where('freight_id', $freight['id'])->order('ship_area', 'asc')->select();
                if ($values) {
                    foreach ($values as $key => $value) {
                        if (!empty($value['ship_area']) || $value['ship_area'] == '0') {
                            $value['ship_area_str'] = '';
                            if ($value['ship_area'] == '0') {
                                $values[$key]['ship_area_str'] = '全国';
                                $freight['default_freight'] = 1;
                            } else {
                                if ($citys = Db::name('city')->whereIn('id', $value['ship_area'])->select()) {
                                    $citys = array_column($citys, 'name');
                                    $values[$key]['ship_area_str'] = implode(',', $citys);
                                }
                            }
                        }
                    }
                }
                $freight['values'] = $values;
            }
        }
        return $freight;
    }

    public function create($params = [])
    {
        Db::startTrans();
        try {
            $data_freight = $this->setCreateUpdateData($params);
            $id = Db::name('shipping_freight')->insertGetId($data_freight);
            if (!$id) return arrayFailed();
            if (!empty($params['ship_area'])) {
                $data_freight_value = [];
                foreach ($params['ship_area'] as $key => $value) {
                    if (!empty($value) || $value == '0') {
                        if (empty($params['first_key'][$key]) || empty($params['first_value'][$key])) {
                            Db::rollback();
                            return arrayFailed('模板运费输入错误');
                        }
                        $data_freight_value[$key]['freight_id'] = $id;
                        $data_freight_value[$key]['first_key'] = $params['first_key'][$key];
                        $data_freight_value[$key]['first_value'] = $params['first_value'][$key];
                        $data_freight_value[$key]['second_key'] = $params['second_key'][$key];
                        $data_freight_value[$key]['second_value'] = $params['second_value'][$key];
                        $data_freight_value[$key]['ship_area'] = $value;
                    }
                }
                Db::name('shipping_freight_value')->insertAll($data_freight_value);
            }
            Db::commit();
            return arraySuccess();
        } catch (\Throwable $th) {
            Db::rollback();
            return arrayFailed();
        }
    }

    public function update($params = [], $id)
    {
        Db::startTrans();
        try {
            $data_freight = $this->setCreateUpdateData($params);
            Db::name('shipping_freight')->where('id', $id)->update($data_freight);
            if (!empty($params['ship_area'])) {
                Db::name('shipping_freight_value')->where('freight_id', $id)->delete();
                $data_freight_value = [];
                foreach ($params['ship_area'] as $key => $value) {
                    if (!empty($value) || $value == '0') {
                        if (empty($params['first_key'][$key]) || empty($params['first_value'][$key])) {
                            Db::rollback();
                            return arrayFailed('模板运费输入错误');
                        }
                        $data_freight_value[$key]['freight_id'] = $id;
                        $data_freight_value[$key]['first_key'] = $params['first_key'][$key];
                        $data_freight_value[$key]['first_value'] = $params['first_value'][$key];
                        $data_freight_value[$key]['second_key'] = $params['second_key'][$key];
                        $data_freight_value[$key]['second_value'] = $params['second_value'][$key];
                        $data_freight_value[$key]['ship_area'] = $value;
                    }
                }
                Db::name('shipping_freight_value')->insertAll($data_freight_value);
            }
            Db::commit();
            return arraySuccess();
        } catch (\Throwable $th) {
            Db::rollback();
            return arrayFailed($th->getMessage());
        }
    }

    private function setCreateUpdateData($params = [])
    {
        $data = [];
        if (isset($params['name'])) $data['name'] = $params['name'];
        if (isset($params['type'])) $data['type'] = $params['type'];
        if (isset($params['description'])) $data['description'] = $params['description'];
        return $data;
    }

    public function delete($id)
    {
        Db::startTrans();
        try {
            Db::name('shipping_freight')->where('id', $id)->delete();
            Db::name('shipping_freight_value')->where('freight_id', $id)->delete();
            Db::commit();
            return arraySuccess();
        } catch (\Throwable $th) {
            Db::rollback();
            return arrayFailed();
        }
    }
}