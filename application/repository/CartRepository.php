<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Cart Repository
 * ============================================================================
 * Author: Jasper   
 */

namespace app\repository;

use think\Db;
use app\repository\ProductRepository;
use app\repository\ProductSpecificationRepository;

class CartRepository
{
    /**
     * Add Cart
     * @param string need $params['sku']
     * @param int need $params['user_id']
     * @param int $params['count']
     * @return array
     */
    public function addCart($params = [])
    {
        $data['sku'] = $params['sku'];
        $data['user_id'] = $params['user_id'];
        $data['count'] = isset($params['count']) ? $params['count'] : 1;
        if (Db::name('cart')->where('user_id', $params['user_id'])->where('sku', $params['sku'])->find()) {
            Db::name('cart')->where('user_id', $params['user_id'])->where('sku', $params['sku'])->setInc('count', $data['count']);
        } else {
            Db::name('cart')->insert($data);
        }
        return arraySuccess();
    }

    /**
     * Minus Cart
     * @param string need $params['sku']
     * @param int need $params['user_id']
     * @param int $params['count']
     * @return array
     */
    public function minusCart($params = [])
    {
        $data['sku'] = $params['sku'];
        $data['user_id'] = $params['user_id'];
        $data['count'] = isset($params['count']) ? $params['count'] : 1;
        if ($cart = Db::name('cart')->where('user_id', $params['user_id'])->where('sku', $params['sku'])->find()) {
            if (bcsub($cart['count'], $data['count']) <= 0) return arrayFailed('超出购物车现有数量');
            Db::name('cart')->where('user_id', $params['user_id'])->where('sku', $params['sku'])->setDec('count', $data['count']);
        } else {
            Db::name('cart')->insert($data);
        }
        return arraySuccess();
    }

    /**
     * Set Cart Product Count
     * @param string need $params['sku']
     * @param int need $params['user_id']
     * @param int need $params['count']
     * @return array
     */
    public function setCount($params = [])
    {
        $count = isset($params['count']) ? $params['count'] : 1;
        $data['sku'] = $params['sku'];
        $data['user_id'] = $params['user_id'];
        $data['count'] = $count;
        if (Db::name('cart')->where('user_id', $params['user_id'])->where('sku', $params['sku'])->find()) {
            Db::name('cart')->where('user_id', $params['user_id'])->where('sku', $params['sku'])->update(['count' => $count]);
        } else {
            Db::name('cart')->insert($data);
        }
        return arraySuccess();
    }

    public function getCart($user_id)
    {
        $cart_products = $this->getCartProducts($user_id);
        $selected_count = 0;
        $selected_total_price = 0.00;
        foreach ($cart_products as $key => $value) {
            $cart_products[$key]['total_price'] = bcmul($value['sale_price'], $value['count'], 2);
            if ($value['selected'] == 1) {
                $selected_count += 1;
                $selected_total_price = bcadd(bcmul($value['sale_price'], $value['count'], 2), $selected_total_price, 2);
            }
        }
        $data = [];
        $data['selected_count'] = $selected_count;
        $data['selected_total_price'] = $selected_total_price;
        $data['cart_products'] = $cart_products;
        return $data;
    }

    public function getSelectedCart($user_id)
    {
        $cart_products = $this->getCartProducts($user_id, $selected = 1);
        $total_price = 0.00;
        foreach ($cart_products as $key => $value) {
            $total_price = bcadd(bcmul($value['sale_price'], $value['count'], 2), $total_price, 2);
            $cart_products[$key]['total_price'] = bcmul($value['sale_price'], $value['count'], 2);
        }
        $data = [];
        $data['total_price'] = $total_price;
        $data['cart_products'] = $cart_products;
        return $data;
    }

    /**
     * 获取购物车产品
     * @param int $user_id
     * @param int $selected
     * @return array
     */
    public function getCartProducts($user_id, $selected = '')
    {
        $query = Db::name('cart')->where('user_id', $user_id);
        if (!empty($selected)) $query->where('selected', $selected);
        $cart_products = $query->select();
        if (empty($cart_products)) return $cart_products;
        $skus = array_column($cart_products, 'sku');
        $product_skus = app(ProductRepository::class)->getProductSkus(['skus' => $skus]);
        $array = [];
        foreach ($product_skus as $key => $value) {
            $array[$value['sku']] = $value;
        }
        foreach ($cart_products as $key => $value) {
            if (isset($array[$value['sku']])) {
                $cart_products[$key]['product_id'] = $array[$value['sku']]['product_id'];
                $cart_products[$key]['name'] = $array[$value['sku']]['name'];
                $cart_products[$key]['status'] = $array[$value['sku']]['status'];
                $cart_products[$key]['original_price'] = $array[$value['sku']]['original_price'];
                $cart_products[$key]['sale_price'] = $array[$value['sku']]['sale_price'];
                $cart_products[$key]['stock'] = $array[$value['sku']]['stock'];
                $cart_products[$key]['image'] = $array[$value['sku']]['image'];
                $cart_products[$key]['specifications'] = $array[$value['sku']]['specifications'];
            } else {
                unset($cart_products[$key]);
            }
        }
        return $cart_products;
    }

    /**
     * 获取购物车单个产品
     * @param int $sku
     * @param int $user_id
     * @return array
     * @return int $data['count'] 个数
     * @return float $data['total'] 合计金额
     */
    public function getCartProduct($sku, $user_id)
    {
        $data = [];
        $select = 'product_sku.sale_price, cart.count';
        $cart = Db::name('cart')->alias('cart')
                ->where('cart.user_id', $user_id)
                ->where('cart.sku', $sku)
                ->leftJoin('product_sku', 'product_sku.sku = cart.sku')
                ->find();
        $data['count'] = $cart['count'];
        $data['total'] = bcmul($cart['count'], $cart['sale_price'], 2);
        return $data;
    }

    public function getCartCount($user_id)
	{
        $count = Db::name('cart')->where('user_id', $user_id)->count();
        return $count;
    }

    /**
     * Set Cart Product Selected
     * @param array $skus
     * @param int $user_id
     */
    public function setSelected($skus, $user_id)
	{
        Db::startTrans();
        try {
            Db::name('cart')->where('user_id', $user_id)->whereNotIn('sku', $skus)->update(['selected' => 0]);
            Db::name('cart')->where('user_id', $user_id)->whereIn('sku', $skus)->update(['selected' => 1]);
            Db::commit();
        } catch (\Throwable $th) {
            Db::rollback();
            return arrayFailed();
        }
        return arraySuccess();
    }

    /**
     * Get Cart Total
     * @param int $user_id
     * @param int $selected
     * @return array
     */
    public function getCartTotal($user_id, $selected = 1)
    {
        $select = 'product_sku.sale_price, cart.count';
        $query = Db::name('cart');
        $query->alias('cart');
        $query->field($select);
        $query->leftJoin('product_sku', 'product_sku.sku = cart.sku');
        if ($selected == 1) $query->where('cart.selected', 1);
        if (!empty($user_id)) $query->where('cart.user_id', $user_id);
        $products = $query->select();
        $data = [];
        $data['count'] = count($products);
        $data['total_price'] = '0.00';
        if (!empty($products)) {
            foreach ($products as $key => $value) {
                $data['total_price'] = bcadd($data['total_price'], bcmul($value['sale_price'], $value['count'], 2), 2);
            }
        }
        return $data;
    }
}