<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Api Comment Validate
 * ============================================================================
 * Author: Jasper
 */

namespace app\api\validate;

use think\Validate;
use think\Db;

class Comment extends Validate
{
    function __construct()
    {
        $this->rule = [
            'order_id' => "require|number|validateData",
            'shop_id' => "require|number",
            'product_id' => "require|number",
            'comment_id' => "require|number"
        ];
        
        $this->message = [
            'order_id.require' => '参数错误',
            'order_id.number' => '参数错误',
            'shop_id.require' => '参数错误',
            'shop_id.number' => '参数错误',
            'product_id.require' => '参数错误',
            'product_id.number' => '参数错误',
            'comment_id.require' => '参数错误',
            'comment_id.number' => '参数错误'
        ];

        $this->scene = [
            'create' => ['order_id'],
            'getShopScore' => ['shop_id'],
            'getProductScore' => ['product_id'],
            'getProductCommentCount' => ['product_id'],
            'getProductComments' => ['product_id'],
            'zan' => ['comment_id']
        ];
    }

    protected function validateData($value, $rule, $param = [])
    {
        $user = getUser();
        
        // validate order
        $order = Db::name('order')->where('id', $value)->where('user_id', $user['id'])->find();
        if (empty($order)) return '订单不存在';
        if ($order['is_comment'] == 1) return '该订单评价已完成';

        // validate shop
        $shop_comment = Db::name('shop_comment')->where('status', 1)->where('order_id', $param['order_id'])->where('user_id', $user['id'])->find();
        if (isset($param['shop'])) {
            if (!empty($shop_comment)) {
                return '该店铺已评论';
            } else {
                if (!isset($param['shop']['star1']) || empty($param['shop']['star1'])) return '请为店铺商品符合度打分';
                if (!isset($param['shop']['star2']) || empty($param['shop']['star2'])) return '请为店铺卖家服务态度打分';
                if (!isset($param['shop']['star3']) || empty($param['shop']['star3'])) return '请为店铺物流发货速度';
            }
        } else {
            if (empty($shop_comment)) return '请为店铺评价';
        }
        
        // validate product
        if (isset($param['products'])) {
            foreach ($param['products'] as $k => $v) {
                if (Db::name('product_comment')->where('status', 1)->where('product_id', $v['product_id'])->where('order_id', $param['order_id'])->where('user_id', $user['id'])->find()) {
                    return '商品已评价';
                } else {
                    if (!empty($v['comment'])) if (empty($v['star'])) return '商品评分不允许为空';
                }
            }
        }
        return true;
    }
}
