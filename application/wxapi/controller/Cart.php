<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Cart Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\wxapi\controller;

use think\Request;
use think\Db;
use app\repository\CartRepository;

class Cart extends Base
{
    protected $middleware = [
        'CheckAppUserLogin' => ['except' => ['getCount']],
    ];

    public function addCart(Request $request)
    {
        $res = $this->validate($request->param(), 'Cart.addCart');
        if ($res !== true) return jsonFailed($res);
        $user_id = getUserId();
        $params = [];
        $params['user_id'] = $user_id;
        $params['sku'] = $request->param('sku');
        $params['count'] = $request->param('count') ? $request->param('count') : 1;
        $res = app(CartRepository::class)->addCart($params);
        return json($res); 
    }

    public function minusCart(Request $request)
    {
        $res = $this->validate($request->param(), 'Cart.minusCart');
        if ($res !== true) return jsonFailed($res);
        $user_id = getUserId();
        $params = [];
        $params['user_id'] = $user_id;
        $params['sku'] = $request->param('sku');
        $params['count'] = $request->param('count') ? $request->param('count') : 1;
        $res = app(CartRepository::class)->minusCart($params);
        return json($res); 
    }

    public function getCart(Request $request)
    {
        $cart = app(CartRepository::class)->getCart(getUserId());
        return jsonSuccess($cart);
    }

    public function setSelected(Request $request)
    {
        $res = $this->validate($request->param(), 'Cart.setSelected');
        if ($res !== true) return jsonFailed($res);
        Db::name('cart')->where('user_id', getUserId())->where('sku', $request->param('sku'))->update(['selected' => $request->param('selected')]);
        return jsonSuccess();
    }

    public function setAllSelected(Request $request)
    {
        $user_id = getUserId();
        if ($request->has('type') && $request->param('type') == 'cancel') {
            Db::name('cart')->where('user_id', $user_id)->where('selected', 1)->update(['selected' => 0]);
        } else {
            Db::name('cart')->where('user_id', $user_id)->where('selected', 0)->update(['selected' => 1]);
        }
        return jsonSuccess();
    }

    public function deleteCart()
    {
        Db::name('cart')->where('user_id', getUserId())->where('selected', 1)->delete();
        return jsonSuccess();
    }

    public function getCount()
    {
        $count = 0;
        $user_id = getUserId();
        if (empty($user_id)) return jsonSuccess($count);
        $count = app(CartRepository::class)->getCartCount($user_id);
        return jsonSuccess($count);
    }
}
