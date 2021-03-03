<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Address
 * ============================================================================
 * Author: Jasper
 */

namespace app\index\controller;

use think\Request;
use app\repository\AddressRepository;
use app\repository\CityRepository;

class Address extends Base
{
    protected $middleware = ['CheckUserLogin'];

    public function createPopup()
    {
        $this->assign('citys', app(CityRepository::class)->getCitys(['parent_id' => 0]));
        return $this->fetch();
    }

    public function editPopup(Request $request)
    {
        $address = app(AddressRepository::class)->getAddress_with_cd($id = $request->id, $user_id = getUserId());
        if (empty($address)) abort(404, '数据为空');
        $this->assign('address', $address);
        $this->assign('citys', app(CityRepository::class)->getCitys(['parent_id' => 0]));
        return $this->fetch();
    }
}
