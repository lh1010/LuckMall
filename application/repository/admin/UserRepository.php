<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利。
 * ----------------------------------------------------------------------------
 * User Repository Admin
 * ============================================================================
 * Author: Jasper   
 */

namespace app\repository\admin;

use think\Db;

class UserRepository
{
    public $sexArray = ['0' => '未知', '1' => '男', '2' => '女'];

    public function getUsersPaginate($params = [])
    {
        $query = Db::name('user');
        $this->setGetUsersParams($query, $params);
        $query->order('id desc');
        $users = $query->paginate();
        if ($users->total() < 1) return $users;
        $users->each(function($value, $key) {
            $value['avatar'] = !empty($value['avatar']) ? $value['avatar'] : Config('image.user_avatar_default');
            $value['sex_str'] = $this->sexArray[$value['sex']];
            return $value;
        });
        return $users;
    }

    public function getUsers($params = [], $limit = 10)
    {
        $query = Db::name('user');
        $this->setGetUsersParams($query, $params);
        $query->limit($limit);
        $query->order('id desc');
        $users = $query->select();
        if (empty($users)) return $users;
        foreach ($users as $key => $value) {
            $users[$key]['sex_str'] = $this->sexArray[$value['sex']];
        }
        return $users;
    }

    private function setGetUsersParams($query, $params)
    {
        if (isset($params['keyword']) && !empty($params['keyword'])) $query->where('nickname', 'LIKE', "%".$params['keyword']."%");
        if (isset($params['status']) && $params['status'] != '') $query->where('status', $params['status']);
    }

    public function getUser($params = [])
    {
        $query = Db::name('user');
        if (isset($params['wx_openid'])) $query->where('wx_openid', $params['wx_openid']);
        if (isset($params['id'])) $query->where('id', $params['id']);
        $user = $query->find();
        if (empty($user)) return $user;
        $user['sex_str'] = $this->sexArray[$user['sex']];
        return $user;
    }

    public function getUserWallet($user_id)
    {
        $wallet = Db::name('user')->where('id', $user_id)->value('wallet');
        return $wallet;
    }

    public function update($params = [], $id)
    {
        $data = $this->setCreateUpdateData($params);
        Db::name('user')->where('id', $id)->update($data);
        return arraySuccess();
    }

    private function setCreateUpdateData($params)
    {
        $data = [];
        if (isset($params['nickname'])) $data['nickname'] = $params['nickname'];
        if (isset($params['wallet'])) $data['wallet'] = $params['wallet'];
        if (isset($params['status'])) $data['status'] = $params['status'];
        return $data;
    }

    public function delete($id)
    {
        Db::startTrans();
        try {
            Db::name('shipping_freight')->where('id', $id)->delete();
            Db::name('shipping_freight_value')->where('freight_id', $id)->delete();
            Db::commit();
            return arraySuccess();
        } catch (\Throwable $th) {
            Db::rollback();
            return arrayFailed();
        }
    }
}