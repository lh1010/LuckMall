<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Freight
 * ============================================================================
 * Author: Jasper   
 */

namespace app\repository;

use think\Db;
use app\repository\CityRepository;

class FreightRepository
{
    /**
     * 设置配送信息
     * @param int $product_id
     * @param int $count 产品个数
     * @param string $region_ids 省市县ID集
     * @return array $data
     */
    public function initFreight($product_id, $count = 1, $region_ids = '')
    {
        $data = [];
        $data['provinces'] = [];
        $data['citys'] = [];
        $data['districts'] = [];
        // 初始地址
        $data['province_id'] = 16;
        $data['city_id'] = 240;
        $data['district_id'] = 2654;
        
        if (!empty($region_ids)) {
            $array = explode(',', $region_ids);
            if (count($array) != 3) return arrayFailed('数据错误，请刷新页面重试');
            $data['province_id'] = $array[0];
            $data['city_id'] = $array[1];
            $data['district_id'] = $array[2];
        }
        $data['province'] = app(CityRepository::class)->getName($data['province_id']);
        $data['city'] = app(CityRepository::class)->getName($data['city_id']);
        $data['district'] = app(CityRepository::class)->getName($data['district_id']);
        $data['address'] = $data['province'].' '.$data['city'].' '.$data['district'];

        $res = app(CityRepository::class)->getCitys(['parent_id' => 0]);
        if (!empty($res)) {
            foreach ($res as $key => $value) {
                $res[$key]['selected'] = 0;
                if ($value['id'] == $data['province_id']) $res[$key]['selected'] = 1;
            }
            $data['provinces'] = $res;
        }

        $res = app(CityRepository::class)->getCitys(['parent_id' => $data['province_id']]);
        if (!empty($res)) {
            foreach ($res as $key => $value) {
                $res[$key]['selected'] = 0;
                if ($value['id'] == $data['city_id']) $res[$key]['selected'] = 1;
            }
            $data['citys'] = $res;
        }

        $res = app(CityRepository::class)->getCitys(['parent_id' => $data['city_id']]);
        if (!empty($res)) {
            foreach ($res as $key => $value) {
                $res[$key]['selected'] = 0;
                if ($value['id'] == $data['district_id']) $res[$key]['selected'] = 1;
            }
            $data['districts'] = $res;
        }

        $data['freight_price'] = number_format($this->calculateFreight($product_id, $count, $data['province_id'], $data['city_id'], $data['district_id']), 2);

        return arraySuccess($data);
    }

    /**
     * 计算产品运费值
     * @param int $product_id
     * @param int $count 产品个数
     * @param int $province_id 省份ID
     * @param int $city_id 市ID
     * @param int $district_id 县/区ID
     * @return float $freight_price 运费值
     */
    public function calculateFreight($product_id, $count, $province_id, $city_id, $district_id)
    {
        $freight_price = 0;
        try {
            // 检测运费条件及获取数据
            $product_freight = Db::name('product_freight')->where('product_id', $product_id)->find();
            if ($product_freight['freight'] == 1 || empty($product_freight['freight_id'])) return $freight_price;
            $freight = Db::name('freight')->where('id', $product_freight['freight_id'])->where('status', 1)->find();            
            if (!$freight) return $freight_price;
            $freight_values = Db::name('freight_value')->where('freight_id', $freight['id'])->select();
            if (!$freight_values) return $freight_price;

            // 定位运费模板选项优先级
            $freight_value = [];
            foreach ($freight_values as $key => $value) {
                $array = [];
                $array = explode(',', $value['ship_area']);
                if (in_array($district_id, $array) && empty($freight_value)) $freight_value = $value;
                if (in_array($city_id, $array) && empty($freight_value)) $freight_value = $value;
                if (in_array($province_id, $array) && empty($freight_value)) $freight_value = $value;
                if (empty($freight_value) && $value['ship_area'] == 0) $freight_value = $value;
            }
            if (!$freight_value) return $freight_price;

            // 确定计价方式相对应值
            $current_value = 0;
            switch ($freight['type']) {
                case '1':
                    $current_value = $count;
                    break;
                case '2':
                    $current_value = bcmul($product_freight['weight'], $count);
                    break;
                case '3':
                    $current_value = bcmul($product_freight['volume'], $count);
                    break;
            }

            // 计算运费值
            $freight_price += $this->calculate($current_value, $freight_value['first_key'], $freight_value['first_value'], $freight_value['second_key'], $freight_value['second_value']);
            return $freight_price;
        } catch (\Throwable $th) {
            return $freight_price;
        }
    }

    /**
     * 计算运费值辅助类
     * @param string $current_value
     * @param int $first_key
     * @param float $first_value
     * @param int $second_key
     * @param float $second_value
     * @return float $freight_price 运费值
     */
    private function calculate($current_value, $first_key, $first_value, $second_key, $second_value)
    {
        $freight_price = 0;
        if ($current_value <= 0) return $freight_price; 
        if ($current_value <= $first_key) {
            $freight_price += $first_value;
        } else {
            $freight_price += $first_value;
            $surplus_price = ceil( ($current_value - $first_key) / $second_key ) * $second_value;
            $freight_price += $surplus_price;
        }
        return $freight_price;
    }
}