<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Api Shop Apply Validate
 * ============================================================================
 * Author: Jasper
 */

namespace app\api\validate;

use think\Validate;

class ShopApply extends Validate
{
    function __construct() {

        $this->rule = [
            'real_name' => 'require',
            'card_number' => 'require',
            'phone' => 'require',
            'card_front' => 'require',
            'card_reverse' => 'require',
            'card_in_hand' => 'require',
            'company_name' => 'require',
            'society_code' => 'require',
            'legal_person' => 'require',
            'business_license' => 'require',
            'shop_name' => 'require',
            'validity' => 'require',
        ];
        
        $this->message = [
            'real_name.require' => '真实姓名不能为空',
            'card_number.require' => '身份证号不能为空',
            'phone.require' => '手机号不能为空',
            'card_front.require' => '请上传身份证正面照',
            'card_reverse.require' => '请上传身份证反面照',
            'card_in_hand.require' => '请上传本人手持身份证照',
            'company_name.require' => '公司名称不能为空',
            'society_code.require' => '统一社会信用代码不能为空',
            'legal_person.require' => '法人代表姓名不能为空',
            'business_license.require' => '请上传企业营业执照',
            'shop_name.require' => '店铺名称不能为空',
            'validity.require' => '平台使用金不能为空',
        ];

        $this->scene = [
            'setShopAuthInfoPerson' => ['real_name', 'card_number', 'phone', 'card_front', 'card_reverse', 'card_in_hand'],
            'setShopAuthInfoCompany' => ['company_name', 'society_code', 'legal_person', 'business_license', 'card_number', 'phone', 'card_front', 'card_reverse'],
            'perfectShop' => ['shop_name', 'validity'],
        ];
    }
}
