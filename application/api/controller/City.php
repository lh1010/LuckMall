<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * City Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\api\controller;

use think\Request;
use app\repository\CityRepository;

class City extends Base
{
	public function getCitys(Request $request)
	{
		$parent_id = $request->parent_id ? $request->parent_id : 0;
		return jsonSuccess(app(CityRepository::class)->getCitys(['parent_id' => $parent_id]));
	}

	public function getCitys_app()
	{
		$data = [];
		$data['provinces'] = [];
		$data['citys'] = [];
		$data['areas'] = [];
		$res = app(CityRepository::class)->getCitys();
		foreach ($res as $key => $value) {
			if ($value['parent_id'] == 0) $data['provinces'][] = $value;
		}
		$province_ids = array_column($data['provinces'], 'id');
		foreach ($res as $key => $value) {
			if (in_array($value['parent_id'], $province_ids)) $data['citys'][$value['parent_id']][] = $value;
		}
		$city_ids = [];
		foreach ($data['citys'] as $key => $value) {
			foreach ($value as $key1 => $value1) {
				array_push($city_ids, $value1['id']);
			}
		}
		foreach ($res as $key => $value) {
			if (in_array($value['parent_id'], $city_ids)) $data['areas'][$value['parent_id']][] = $value;
		}
		return jsonSuccess($data);
	}
}
