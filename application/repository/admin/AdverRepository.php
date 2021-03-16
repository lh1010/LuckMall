<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Adver Repository Admin
 * ============================================================================
 * Author: Jasper
 */

namespace app\repository\admin;

use think\Db;

class AdverRepository
{
    public $clientArray = ['wxapp' => '微信小程序', 'pc' => '电脑web端'];

    /**
     * Get Advers Paginate
     * @param int $paginate
     * @return array
     */
    public function getAdversPaginate($params = [])
    {
        $query = Db::name('adver');
        $this->setGetAdversParams($query, $params);
        $advers = $query->paginate();
        $advers->each(function($item, $key) {
            $item['client_str'] = in_array($item['client'], array_keys($this->clientArray)) ? $this->clientArray[$item['client']] : '';
            return $item;
        });
        return $advers;
    }

    private function setGetAdversParams($query, $params)
    {
        if (isset($params['client']) && !empty($params['client'])) $query->where('client', $params['client']);
        if (isset($params['keyword']) && !empty($params['keyword'])) $query->where('name', 'like', "%".$params['keyword']."%");
        if (isset($params['status']) && $params['status'] !== '') $query->where('status', $params['status']);
    }

    /**
     * Get Adver
     * @param int $id
     * @return array
     */
    public function getAdver($id, $value = 1)
    {
        $adver = Db::name('adver')->where('id', $id)->find();
        if (empty($adver)) return [];
        if ($value == 1) {
            $adver['values'] = Db::name('adver_value')->where('adver_id', $id)->select();
        }
        return $adver;
    }

    public function create($params = [])
    {
        Db::startTrans();
        try {
            $query = Db::name('adver');
            $data = $this->setCreateUpdateData($params);
            $adver_id = $query->insertGetId($data);
            if (isset($params['images'])) {
                $data = [];
                foreach ($params['images'] as $key => $value) {
                    if (empty($value)) return arrayFailed('广告图不能为空');
                    $data[$key]['adver_id'] = $adver_id;
                    $data[$key]['image'] = $value;
                    $data[$key]['link'] = $params['links'][$key];
                    $data[$key]['link_ident'] = $params['link_idents'][$key];
                    $data[$key]['sort'] = is_numeric($params['sorts'][$key]) ? $params['sorts'][$key] : 0;
                }
                Db::name('adver_value')->insertAll($data);
            }
            Db::commit();
            return arraySuccess();
        } catch (\Throwable $th) {
            Db::rollback();
            return arrayFailed();
        }
    }

    public function update($params = [], $id)
    {
        Db::startTrans();
        try {
            $query = Db::name('adver');
            $data = $this->setCreateUpdateData($params);
            $query->where('id', $id)->update($data);
            Db::name('adver_value')->where('adver_id', $id)->delete();
            if (isset($params['images'])) {
                $data = [];
                foreach ($params['images'] as $key => $value) {
                    if (empty($value)) return arrayFailed('广告图不能为空');
                    $data[$key]['adver_id'] = $id;
                    $data[$key]['image'] = $value;
                    $data[$key]['link'] = $params['links'][$key];
                    $data[$key]['link_ident'] = $params['link_idents'][$key];
                    $data[$key]['sort'] = is_numeric($params['sorts'][$key]) ? $params['sorts'][$key] : 0;
                }
                Db::name('adver_value')->insertAll($data);
            }
            Db::commit();
            return arraySuccess();
        } catch (\Throwable $th) {
            Db::rollback();
            return arrayFailed();
        }
    }

    private function setCreateUpdateData($params = [])
    {
        $data = [];
        if (isset($params['name'])) $data['name'] = $params['name'];
        if (isset($params['description'])) $data['description'] = $params['description'];
        if (isset($params['client'])) $data['client'] = $params['client'];
        if (isset($params['status'])) $data['status'] = $params['status'];
        return $data;
    }

    public function delete($id)
    {
        Db::startTrans();
        try {
            $advers = Db::name('adver')->whereIn('id', $id)->select();
            foreach ($advers as $key => $value) {
                if ($value['type'] == 'system') return arrayFailed('系统使用，不允许删除');
            }
            Db::name('adver')->whereIn('id', $id)->delete();
            Db::name('adver_value')->whereIn('adver_id', $id)->delete();
            Db::commit();
            return arraySuccess();
        } catch (\Throwable $th) {
            Db::rollback();
            return arrayFailed();
        }
    }
}