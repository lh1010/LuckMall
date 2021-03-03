<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Address Validate
 * ============================================================================
 * Author: Jasper
 */

namespace app\wxapi\validate;

use think\Validate;
use think\Db;

class Address extends Validate
{
    function __construct()
    {
        $this->rule = [
            'name' => "require",
            'province_id' => "require",
            'city_id' => "require|number",
            'district_id' => "require|number",
            'detailed_address' => "require",
            'phone' => "require|mobile",
            'id' => 'require|number',
        ];
        
        $this->message = [
            'name.require' => '姓名不能为空',
            'province_id.require' => '所在区域不能为空',
            'province_id.number' => '参数格式错误',
            'city_id.require' => '所在区域不能为空',
            'city_id.number' => '参数格式错误',
            'district_id.require' => '所在区域不能为空',
            'district_id.number' => '参数格式错误',
            'detailed_address.require' => '详细地址不能为空',
            'phone.require' => '联系电话不能为空',
            'phone.mobile' => '联系电话格式不正确',
            'id.require' => '参数错误',
            'id.number' => '参数格式错误',
        ];

        $this->scene = [
            'create' => ['name', 'province_id', 'city_id', 'district_id', 'detailed_address', 'phone'],
            'update' => ['id', 'name', 'province_id', 'city_id', 'district_id', 'detailed_address', 'phone'],
            'delete' => ['id'],
            'setDefault' => ['id'],
        ];
    }
}
