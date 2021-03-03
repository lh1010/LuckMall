<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Api Cart Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\api\controller;

use think\Db;
use think\Request;
use app\repository\CartRepository;

class Cart extends Base
{
	protected $middleware = ['CheckUserLogin'];

	public function addCart(Request $request)
	{
		$res = $this->validate($request->param(), 'Cart.addCart');
		if ($res !== true) return jsonFailed($res);
		$params = [];
		$params['sku'] = $request->sku;
		$params['user_id'] = getUserId();
		$params['count'] = $request->count;
		return app(CartRepository::class)->addCart($params);
	}

	public function setCount(Request $request)
	{
		$res = $this->validate($request->param(), 'Cart.setCount');
		if ($res !== true) return jsonFailed($res);
		$user_id = getUserId();
		$params['sku'] = $request->sku;
		$params['user_id'] = $user_id;
		$params['count'] = $request->count;
		$res = app(CartRepository::class)->setCount($params);
		if ($res['code'] != 200) return jsonFailed();
		$data['product'] = app(CartRepository::class)->getCartProduct($request->sku, $user_id);
		$data['cart'] = app(CartRepository::class)->getCartTotal($user_id);
		return jsonSuccess($data);
	}

	public function getCartCount()
	{
		$count = app(CartRepository::class)->getCartCount(getUserId());
        return $count;
	}

	public function delete(Request $request)
	{
		$params = [];
		$params['user_id'] = getUser()['id'];
		$params['product_id'] = $request->product_id;
		$res = app(CartRepository::class)->delete($params, $request->type);
		if ($res['code'] != 200) return jsonFailed($res['message']);
		return jsonSuccess();
	}

	public function setSelected(Request $request)
	{
		$skus = $request->param('skus');
		$skus = explode(',', $skus);
		$user_id = getUserId();
		$res = app(CartRepository::class)->setSelected($skus, $user_id);
		if ($res['code'] != 200) return jsonFailed($res['message']);
		return jsonSuccess(app(CartRepository::class)->getCartTotal($user_id));
	}
}
