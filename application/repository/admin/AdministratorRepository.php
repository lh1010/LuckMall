<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Administrator Repository Admin
 * ============================================================================
 * Author: Jasper   
 */

namespace app\repository\admin;

use think\Db;

class AdministratorRepository
{
    public $sexArray = [1 => '男', 2 => '女'];

    public function getAdministratorsPaginate($params = [])
    {
        $query = Db::name('administrator');
        $this->setGetAdministratorsParams($query, $params);
        $query->where('status', '<>', 99);
        $administrators = $query->paginate();
        if ($administrators->total() == 0) return $administrators;
		$this->getAdministratorsPaginateAddition($administrators, $params);
        return $administrators;
    }

    private function setGetAdministratorsParams($query, $params)
    {
        if (isset($params['keyword']) && !empty($params['keyword'])) {
            $query->where(function($query) use ($params) {
				$query->where('username', 'like', "%".$params['keyword']."%")
                ->whereOr('email', $params['keyword'])
                ->whereOr('phone', $params['keyword']);
			});
        }
        if (isset($params['status']) && $params['status'] != '') $query->where('status', $params['status']);
    }

    private function getAdministratorsPaginateAddition($administrators, $params)
    {
        $administrators->each(function($value, $key) {
            $value['sex_str'] = in_array($value['sex'], array_keys($this->sexArray)) ? $this->sexArray[$value['sex']] : '未知';
			return $value;
		});
    }

    public function getAdministrator($id)
    {
        $query = Db::name('administrator');
        $query->where('id', $id);
        $query->where('status', '<>', 99);
        $administrator = $query->find();
        return $administrator;
    }

    public function create($params = [])
    {
        $query = Db::name('administrator');
        $data = $this->setCreateUpdateData($params);
        $query->insert($data);
        return arraySuccess();
    }

    public function update($params, $id)
    {
        $query = Db::name('administrator');
        $data = $this->setCreateUpdateData($params);
        $query->where('id', $id)->update($data);
        return arraySuccess();
    }

    private function setCreateUpdateData($params = [])
    {
        $data = [];
        if (isset($params['username'])) $data['username'] = $params['username'];
        if (isset($params['password'])) $data['password'] = md5($params['password']);
        if (isset($params['sex'])) $data['sex'] = $params['sex'];
        if (isset($params['phone'])) $data['phone'] = $params['phone'];
        if (isset($params['email'])) $data['email'] = $params['email'];
        if (isset($params['remark'])) $data['remark'] = $params['remark'];
        if (isset($params['status'])) $data['status'] = $params['status'];
        return $data;
    }
}