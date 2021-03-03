<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Api Address Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\api\controller;

use think\Request;
use app\repository\AddressRepository;

class Address extends Base
{
	protected $middleware = ['CheckUserLogin'];

	public function create(Request $request)
	{
		$res = $this->validate($request->post(), 'Address.create');
		if ($res !== true) return jsonFailed($res);
		$params = $request->post(); $params['user_id'] = getUserId();
		return app(AddressRepository::class)->create($params);
	}

	public function update(Request $request)
	{
		$res = $this->validate($request->post(), 'Address.update');
		if ($res !== true) return jsonFailed($res);
		return app(AddressRepository::class)->update($request->post(), $id = $request->post('id'), $user_id = getUserId());
	}

	public function delete(Request $request)
	{
		$res = $this->validate($request->post(), 'Address.delete');
		if ($res !== true) return jsonFailed($res);
		$res = app(AddressRepository::class)->setStatus($id = $request->param('id'), $user_id = getUserId(), $status = 99);
		return json($res);
	}

	public function setDefault(Request $request)
	{
		$res = $this->validate($request->param(), 'Address.setDefault');
		if ($res !== true) return jsonFailed($res);
		$res = app(AddressRepository::class)->setStatus($id = $request->param('id'), $user_id = getUserId(), $status = 1);
		return json($res);
	}
}
