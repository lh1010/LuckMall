<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Admin Shipping Freight Validate
 * ============================================================================
 * Author: Jasper
 */

namespace app\admin\validate;

use think\Validate;
use think\Db;
use app\repository\admin\ShippingFreightRepository;

class ShippingFreight extends Validate
{
    function __construct()
    {
        $this->rule = [
            'id' => 'require|affiliation_id',
            'name' => 'require',
            'type' => 'require',
            'ship_area' => 'require|validateShipArea',
            '__token__' => 'token'
        ];
        
        $this->message = [
            'id.require' => '缺少必需参数：id',
            'name.require' => '名字不能为空',
            'type.require' => '计价方式不能为空',
            'ship_area.require' => '配送区域不能为空',
            '__token__.token' => '重复提交，请刷新页面',
        ];

        $this->scene = [
            'store' => ['name', 'type', 'ship_area', '__token__'],
            'update' => ['id', 'name', 'type', 'ship_area', '__token__'],
            'delete' => ['id'],
        ];
    }

    protected function validateShipArea($value, $rule, $param = [])
    {
        $message = '';
        foreach ($param['ship_area'] as $key => $value) {
            if (empty($param['first_key'][$key]) || empty($param['first_value'][$key])) {
                $message = '首'.app(ShippingFreightRepository::class)->typeArray[$param['type']].'和运费不能为空';
            }
            if ($value == '') $message = '配送区域不能为空';
        }
        return !empty($message) ?  $message : true;
    }

    protected function affiliation_id($value, $rule, $param = [])
    {
        if (!Db::name('shipping_freight')->where('id', $value)->find()) return '所属运费模板不存在';
        return true;
    }
}
