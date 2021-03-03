<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Section Repository Admin
 * ============================================================================
 * Author: Jasper
 */

namespace app\repository\admin;

use think\Db;
use app\repository\admin\ProductRepository;

class SectionRepository
{
    public $clientArray = ['wxapp' => '微信小程序', 'pc' => '电脑端'];

    public function getSectionsPaginate($params = [])
    {
        $query = Db::name('section');
        $this->setGetSectionsParams($query, $params);
        $sections = $query->paginate()->each(function($value, $key) {
            $value['value_count'] = Db::name('section_value')->where('section_id', $value['id'])->count();
            $value['client_str'] = in_array($value['client'], array_keys($this->clientArray)) ? $this->clientArray[$value['client']] : '';
            return $value;
        });
        return $sections;
    }

    private function setGetSectionsParams($query, $params)
    {
        if (isset($params['client']) && !empty($params['client'])) $query->where('client', $params['client']);
        if (isset($params['keyword']) && !empty($params['keyword'])) $query->where('name', 'like', "%".$params['keyword']."%");
    }

    public function getSection($section_id)
    {
        $section = Db::name('section')->where('id', $section_id)->find();
        if (empty($section)) return [];
        $product_ids = Db::name('section_value')->where('section_id', $section_id)->column('value_id');
        $section['values'] = app(ProductRepository::class)->getProducts(['product_ids' => $product_ids]);             
        return $section;
    }

    public function create($params)
    {
        Db::startTrans();
        try {
            $data = $this->setCreateUpdateData($params);
            $section_id = Db::name('section')->insertGetId($data);
            if (isset($params['productIds'])) $this->setValue($section_id, $params['productIds']);
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
            $data = $this->setCreateUpdateData($params);
            Db::name('section')->where('id', $id)->update($data);
            $productIds = [];
            $haveProductIds = [];
            if (isset($params['productIds'])) $productIds = $params['productIds'];
            if (isset($params['haveProductIds'])) $haveProductIds = $params['haveProductIds'];
            $this->setValue($id, $productIds, $haveProductIds, $type = 'update');
            Db::commit();
            return arraySuccess();
        } catch (\Throwable $th) {
            Db::rollback();
            return arrayFailed($th->getMessage());
        }
    }

    private function setCreateUpdateData($params = [])
    {
        $data = [];
        if (isset($params['client'])) $data['client'] = $params['client'];
        if (isset($params['name'])) $data['name'] = $params['name'];
        if (isset($params['description'])) $data['description'] = $params['description'];
        return $data;
    }

    /**
     * Set Section Value
     * @param int $section_id
     * @param array $productIds
     * @param array $haveProductIds $type=update时存在，旧ID
     * @param string $type create|update
     */
    private function setValue($section_id, $productIds, $haveProductIds = [], $type = 'create')
    {
        $data = [];
        if ($type == 'create') {
            foreach ($productIds as $key => $value) {
                $data[$key]['value_id'] = $value;
                $data[$key]['section_id'] = $section_id;
            }
            Db::name('section_value')->insertAll($data);
        }
        if ($type == 'update') {
            if (empty($productIds) && !empty($haveProductIds)) {
                Db::name('section_value')->where('section_id', $section_id)->delete();
            } else {
                foreach ($productIds as $key => $value) {
                    if (!in_array($value, $haveProductIds)) {
                        $data[$key]['value_id'] = $value;
                        $data[$key]['section_id'] = $section_id;
                    }
                }
                Db::name('section_value')->insertAll($data);
                $product_ids = [];
                foreach ($haveProductIds as $key => $value) {
                    if (!in_array($value, $productIds)) {
                        $product_ids[] = $value;
                    }
                }
                Db::name('section_value')->where('section_id', $section_id)->whereIn('value_id', $product_ids)->delete();
            }
        }
    }

    public function delete($id)
    {
        Db::startTrans();
        try {
            $sections = Db::name('section')->whereIn('id', $id)->select();
            foreach ($sections as $key => $value) {
                if ($value['type'] == 'system') return arrayFailed('系统使用，不允许删除');
            }
            Db::name('section')->whereIn('id', $id)->delete();
            Db::name('section_value')->whereIn('section_id', $id)->delete();
            Db::commit();
            return arraySuccess();
        } catch (\Throwable $th) {
            Db::rollback();
            return arrayFailed();
        }
    }
}