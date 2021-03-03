<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Address Repository
 * ============================================================================
 * Author: Jasper   
 */

namespace app\repository;

use think\Db;
use app\repository\CityRepository;

class AddressRepository
{
    /**
     * Get Addresses
     * @return array
     */
    public function getAddresses($user_id)
    {
        $query = Db::name('user_address');
        $query->where('user_id', $user_id);
        $query->where('status', '<>', 99);
        $query->limit(8);
        $addresses = $query->select();
        if (!empty($addresses)) {
            foreach ($addresses as $key => $value) {
                $addresses[$key]['province_name'] = app(CityRepository::class)->getName($value['province_id']);
                $addresses[$key]['city_name'] = app(CityRepository::class)->getName($value['city_id']);
                $addresses[$key]['district_name'] = app(CityRepository::class)->getName($value['district_id']);
            }
        }
        return $addresses;
    }

    /**
     * Get User Address
     * @param int $id
     * @param int $user_id
     * @return array
     */
    public function getAddress($id, $user_id)
    {
        $address = Db::name('user_address')->where('id', $id)->where('user_id', $user_id)->where('status', '<>', 99)->find();
        if (empty($address)) return $address;
        $address['province_name'] = app(CityRepository::class)->getName($address['province_id']);
        $address['city_name'] = app(CityRepository::class)->getName($address['city_id']);
        $address['district_name'] = app(CityRepository::class)->getName($address['district_id']);
        return $address;
    }

    /**
     * Get User Address
     * 包含省份城市
     * @param int $id
     * @param int $user_id
     * @return array
     */
    public function getAddress_with_cd($id, $user_id)
    {
        $address = $this->getAddress($id, $user_id);
        if (empty($address)) return $address;
        $address['citys'] = Db::name('city')->where('parent_id', $address['province_id'])->select();
        $address['districts'] = Db::name('city')->where('parent_id', $address['city_id'])->select();
        return $address;
    }

    public function getDefaultAddress($user_id)
    {
        $address = Db::name('user_address')->where('status', 1)->where('user_id', $user_id)->find();
        if (empty($address)) {
            $address = Db::name('user_address')->where('status', '<>', 99)->where('user_id', $user_id)->find();
        }
        if (!empty($address)) {
            $address['province_name'] = app(CityRepository::class)->getName($address['province_id']);
            $address['city_name'] = app(CityRepository::class)->getName($address['city_id']);
            $address['district_name'] = app(CityRepository::class)->getName($address['district_id']);
        }
        return $address;
    }

    public function create($params = [])
    {
        if (Db::name('user_address')->where('status', '<>', 99)->where('user_id', $params['user_id'])->count() >= 8) {
            return arrayFailed('最多可创建 8 条地址');
        }
        $data = $this->setCreateUpdateData($params);
        Db::startTrans();
        try {
            if ($data['status'] == 1) Db::name('user_address')->where('user_id', $params['user_id'])->where('status', 1)->update(['status' => 2]);
            Db::name('user_address')->insert($data);
            Db::commit();
            return arraySuccess();
        } catch (\Throwable $th) {
            Db::rollback();
            return arrayFailed($th->getMessage());
        }
    }

    /**
     * Update User
     * @param array $params
     * @param int $id
     * @param int $user_id
     * @return array
     */
    public function update($params, $id, $user_id)
    {
        $data = $this->setCreateUpdateData($params);
        Db::startTrans();
        try {
            if ($data['status'] == 1) Db::name('user_address')->where('user_id', $user_id)->where('status', 1)->update(['status' => 2]);
            Db::name('user_address')->where('id', $id)->where('user_id', $user_id)->update($data);
            Db::commit();
            return arraySuccess();
        } catch (\Throwable $th) {
            Db::rollback();
            return arrayFailed();
        }
    }

    private function setCreateUpdateData($params)
    {
        $data = [];
        if (isset($params['name'])) $data['name'] = $params['name'];
        if (isset($params['phone'])) $data['phone'] = $params['phone'];
        if (isset($params['user_id'])) $data['user_id'] = $params['user_id'];
        if (isset($params['province_id'])) $data['province_id'] = $params['province_id'];
        if (isset($params['city_id'])) $data['city_id'] = $params['city_id'];
        if (isset($params['district_id'])) $data['district_id'] = $params['district_id'];
        if (isset($params['detailed_address'])) $data['detailed_address'] = $params['detailed_address'];
        $data['status'] = isset($params['default_address']) && $params['default_address'] == 1 ? 1 : 2;
        return $data;
    }

    /**
     * set address status
     * @param int $id
     * @param int $status
     * @param int $user_id
     */
    public function setStatus($id, $user_id, $status = 1)
    {
        Db::startTrans();
        try {
            if ($status == 1) {
                Db::name('user_address')->where('user_id', $user_id)->where('status', 1)->update(['status' => 2]);
            }
            $query = Db::name('user_address')->where('id', $id)->where('user_id', $user_id)->update(['status' => $status]);
            Db::commit();
            return arraySuccess();
        } catch (\Throwable $th) {
            Db::rollback();
            return arrayFailed();
        }
    }
}