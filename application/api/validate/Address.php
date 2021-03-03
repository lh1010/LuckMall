<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Api Address Validate
 * ============================================================================
 * Author: Jasper
 */

namespace app\api\validate;

use think\Validate;
use think\Db;

class Address extends Validate
{
    function __construct()
    {
        $this->rule = [
            'name' => "require",
            'province_id' => "require",
            'city_id' => "require",
            'district_id' => "require",
            'detailed_address' => "require",
            'phone' => "require|mobile",
            'id' => 'require',
        ];
        
        $this->message = [
            'email.require' => '收货人姓名',
            'province_id.require' => '收货地址不能为空',
            'city_id.require' => '收货地址不能为空',
            'district_id.require' => '收货地址不能为空',
            'detailed_address.require' => '收货详细地址不能为空',
            'phone.require' => '手机号不能为空',
            'phone.mobile' => '手机号格式不正确',
            'id.require' => '参数错误',
        ];

        $this->scene = [
            'create' => ['name', 'province_id', 'city_id', 'district_id', 'detailed_address', 'phone'],
            'update' => ['id', 'name', 'province_id', 'city_id', 'district_id', 'detailed_address', 'phone'],
            'delete' => ['id'],
            'setDefault' => ['id'],
        ];
    }
}
