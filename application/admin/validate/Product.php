<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Admin Product Validate
 * ============================================================================
 * Author: Jasper
 */

namespace app\admin\validate;

use think\Validate;

class Product extends Validate
{
    protected $rule = [
        'id' => 'require',
        'product_model_id' => 'require',
        'sale_price' => 'require',
        'name' => 'require|validateSkus',
        'category_id' => 'require',
        'sort' => 'number',
        'stock' => 'number',
        'sku' => 'number',
        'freight' => 'require|validateFreight',
        '__token__' => 'token'
    ];
    
    protected $message = [
        'id.require' => '缺少必需参数：id',
        'product_model_id.require' => '缺少必需参数：product_model_id',
        'name.require' => '产品名不能为空',
        'sale_price.require' => '销售价不能为空',
        'category_id.require' => '产品类目不能为空',
        'sort.number' => '排序值必须为数字',
        'stock.number' => '产品库存格式不正确，产品库存必须为数字',
        'sku.number' => '商家编码格式不正确，商家编码必须由数字组成',
        'freight.require' => '请选择是否包邮',
        '__token__.token' => '重复提交，请刷新页面',
    ];

    protected $scene = [
        'delete' => ['id'],
        'store' => ['name', 'sale_price', 'category_id', 'sort', 'stock', 'sku', 'freight', '__token__'],
        'update' => ['id', 'name', 'sale_price', 'sort', 'stock', 'sku', 'freight', '__token__'],
        'getProductModel' => ['product_model_id'],
    ];
    
    // 验证销售规格字段格式
    protected function validateSkus($value, $rule, $params = [])
    {
        if (isset($params['specification_option_id']) && !empty($params['specification_option_id'])) {
            foreach ($params['specification_option_id'] as $key => $value) {
                if (empty($value['sale_price'])) {
                    return '销售规格组中的销售价不能为空';
                }
                if (!empty($value['stock']) && !is_numeric($value['stock'])) {
                    return '产品库存格式不正确，产品库存必须为数字';
                }
                if (!empty($value['sale_price']) && !validatePriceFormat($value['sale_price'])) {
                    return '产品销售价格式不正确';
                }
                if (!empty($value['sku']) && !is_numeric($value['sku'])) {
                    return '商家编码格式不正确，商家编码必须由数字组成';
                }
            }
        }
        if (!empty($params['sale_price']) && !validatePriceFormat($params['sale_price'])) {
            return '产品销售价格式不正确';
        }
        return true; 
    }

    protected function validateFreight($value, $rule, $param = [])
    {
        $message = '';
        if ($value == 2) {
            if (empty($param['freight_id'])) $message = '产品非包邮时，请选择运费模板';
        }
        return !empty($message) ?  $message : true;      
    }
}