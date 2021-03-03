<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Order wxapi Repository
 * ============================================================================
 * Author: Jasper   
 */

namespace app\wxapi\repository;

use think\Db;

class OrderRepository
{
    /**
     * 微信小程序创建订单
     * 使用默认创建订单方法 \app\repository\OrderRepository
     * 微信小程序无支付收银台 创建订单后直接发起微信支付
     * 微信小程序支持的支付方式：微信支付、余额支付
     * 过程验证失败不创建订单
     */
    public function create($params)
    {
        Db::startTrans();
        try {
            if ($params['type'] == 'cart') {
                $res = app(\app\repository\OrderRepository::class)->create_order_cart($params);
            }
            if ($params['type'] == 'onekeybuy') {
                $res = app(\app\repository\OrderRepository::class)->create_order_onekeybuy($params);
            }
            if ($res['code'] != 200) return $res;
    
            $order = $res['data']['order'];
            if ($order['payment_id'] == 4) {
                $wallet = app(\app\repository\UserRepository::class)->getUserWallet($order['user_id']);
                if ($wallet < $order['total_price']) {
                    Db::rollback();
                    return arrayFailed('余额不足');
                }
            }

            $params = ['order_id' => $order['id'], 'total_price' => $order['total_price'], 'user_id' => $order['user_id']];
            $res = app(\app\repository\PaymentRepository::class)->pay($order['payment_id'], $params);
            Db::commit();
            return $res;
        } catch (\Throwable $th) {
            Db::rollback();
            return arrayFailed();
        }
    }
}