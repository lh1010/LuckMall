<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Address Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\wxapi\controller;

use think\Request;
use app\repository\AddressRepository;

class Address extends Base
{
    protected $middleware = ['CheckAppUserLogin'];

    public function getAddresses()
    {
        $addresses = app(AddressRepository::class)->getAddresses($user_id = getUserId());
        return jsonSuccess($addresses);
    }

    public function getAddress(Request $request)
    {
        $address = app(AddressRepository::class)->getAddress($id = $request->param('id'), $user_id = getUserId());
        return jsonSuccess($address);
    }

    public function create(Request $request)
    {
        $res = $this->validate($request->param(), 'Address.create');
        if ($res !== true) return jsonFailed($res);
        $params = $request->param();
        $params['user_id'] = getUserId();
        $res = app(AddressRepository::class)->create($params);
        return json($res);
    }

    public function update(Request $request)
    {
        $res = $this->validate($request->param(), 'Address.update');
        if ($res !== true) return jsonFailed($res);
        $res = app(AddressRepository::class)->update($request->param(), $id = $request->param('id'), $user_id = getUserId());
        return json($res);
    }

    public function delete(Request $request)
    {
        $res = $this->validate($request->param(), 'Address.delete');
        if ($res !== true) return jsonFailed($res);
        $res = app(AddressRepository::class)->setStatus($id = $request->param('id'), $user_id = getUserId(), $status = 99);
        return json($res);
    }

    public function setDefault(Request $request)
	{
		$res = $this->validate($request->param(), 'Address.setDefault');
		if ($res !== true) return jsonFailed($res);
        $res = app(AddressRepository::class)->setStatus($id = $request->param('id'), $user_id = getWxAppUserId(), $status = 1);
        return json($res);
	}
}
