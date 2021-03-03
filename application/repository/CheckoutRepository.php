<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Checkout Repository
 * ============================================================================
 * Author: Jasper   
 */

namespace app\repository;

use think\Db;
use app\repository\CartRepository;
use app\repository\AddressRepository;
use app\repository\ProductRepository;
use app\repository\ShippingFreightRepository;

class CheckoutRepository
{   
    /**
     * @param int $params['user_id']
     * @param int $params['address_id']
     */
    public function initData_cart($params = [])
    {
        $data = [];
        $cart = app(CartRepository::class)->getSelectedCart($params['user_id']);
        if (empty($cart['cart_products'])) return $data;
        if (isset($params['address_id'])) {
            $address = app(AddressRepository::class)->getAddress($params['address_id'], $params['user_id']);
        } else {
            $address = app(AddressRepository::class)->getDefaultAddress($params['user_id']);
        }
        // 计算运费
        $shipping_freight_total_price = '0.00';
        $shipping_freight_prices = [];
        foreach ($cart['cart_products'] as $key => $value) {
            $shipping_freight_price = app(ShippingFreightRepository::class)->getProductShoppingFreight($value['product_id'], $value['count'], $address['province_id'], $address['city_id'], $address['district_id']);
            if ($shipping_freight_price > 0) $shipping_freight_prices[] = $shipping_freight_price;
        }
        if (!empty($shipping_freight_prices)) {
            $shipping_freight_total_price = max($shipping_freight_prices);
        }
        $data['product'] = $cart['cart_products'];
        $data['shipping_freight_total_price'] = $shipping_freight_total_price;
        $data['product_count'] = count($cart['cart_products']);
        $data['product_total_price'] = $cart['total_price'];
        $data['total_price'] = bcadd($data['product_total_price'], $data['shipping_freight_total_price'], 2);
        $data['address'] = $address;
        return $data;
    }

    /**
     * @param int $params['user_id']
     * @param string $params['sku']
     * @param int $params['count']
     * @param int $params['address_id']
     */
    public function initData_onekeybuy($params = [])
    {
        $data = [];
        $product = app(ProductRepository::class)->getProduct(['sku' => $params['sku']]);
        if (empty($product)) return $data;
        if (isset($params['address_id']) && !empty($params['address_id'])) {
            $address = app(AddressRepository::class)->getAddress($params['address_id'], $params['user_id']);
        } else {
            $address = app(AddressRepository::class)->getDefaultAddress($params['user_id']);
        }
        // 计算运费
        $shipping_freight_total_price = app(ShippingFreightRepository::class)->getProductShoppingFreight($product['id'], $params['count'], $address['province_id'], $address['city_id'], $address['district_id']);
        // 组装数据
        $data['product'] = $product;
        $data['product']['count'] = $params['count'];
        $data['product']['total_price'] = bcmul($product['sale_price'], $params['count'], 2);
        $data['shipping_freight_total_price'] = $shipping_freight_total_price;
        $data['product_count'] = 1;
        $data['product_total_price'] = $data['product']['total_price'];
        $data['total_price'] = bcadd($data['product_total_price'], $data['shipping_freight_total_price'], 2);
        $data['address'] = $address;
        return $data;
    }
}