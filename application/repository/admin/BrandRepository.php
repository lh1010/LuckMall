<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Brand Repository Admin
 * ============================================================================
 * Author: Jasper
 */

namespace app\repository\admin;

use think\Db;

class BrandRepository
{
    public function getBrandsPaginate($params = [])
    {
        $query = Db::name('brand');
        $this->setGetBrandsParams($query, $params);
        $query->order('create_time desc');
        $brands = $query->paginate();
        if ($brands->total() == 0) return $brands;
        $brands = $this->setCover($brands);
        $brands = $this->getProductCount($brands);
        return $brands;
    }

    public function getBrands($params = [])
    {
        $query = Db::name('brand');
        $this->setGetBrandsParams($query, $params);
        $brands = $query->select();
        return $brands;
    }

    private function setGetBrandsParams($query, $params)
	{
        if (isset($params['keyword'])) $query->where('name', 'like', "%".$params['keyword']."%");
        if (isset($params['status']) && $params['status'] != '') $query->where('status', $params['status']);
	}

    public function getBrand($id)
    {
        $brand = Db::name('brand')->where('id', $id)->find();
        return $brand;
    }

    public function setCover($data, $type = 3)
    {
        if (empty($data)) return [];
        switch ($type) {
            case '1':
                empty($data['cover']) ? $data['cover'] = Config('image.brand_default') : null;
                break;
            case '2':
                foreach ($data as $key => $value) {
                    empty($value['cover']) ? $data[$key]['cover'] = Config('image.brand_default') : null;
                }
                break;    
            case '3':
                $data->each(function($item, $key) {
                    if (empty($item['cover'])) {
                        $item['cover'] = Config('image.brand_default');
                    }
                    return $item;
                });
                break;
        }
        return $data;
    }

    private function getProductCount($brands)
    {
        $brand_ids = array_column($brands->items(), 'id');
        $products = Db::name('product')->whereIn('brand_id', $brand_ids)->select();
        $array = [];
        foreach ($products as $key => $value) {
            $array[$value['brand_id']][] = $value;
        }
        $brands->each(function($value, $key) use ($array) {
            $value['product_count'] = isset($array[$value['id']]) ? count($array[$value['id']]) : 0;
            return $value;
        });
        return $brands;
    }

    public function create($params = [])
    {
        $query = Db::name('brand');
        $data = $this->setCreateUpdateData($params);
        $query->insert($data);
        return arraySuccess();
    }

    public function update($params = [], $id)
    {
        $query = Db::name('brand');
        $data = $this->setCreateUpdateData($params);
        $query->where('id', $id)->update($data);
        return arraySuccess();
    }

    private function setCreateUpdateData($params = [])
    {
        $data = [];
        if (isset($params['name'])) $data['name'] = $params['name'];
        if (isset($params['cover'])) $data['cover'] = $params['cover'];
        if (isset($params['keyword'])) $data['keyword'] = $params['keyword'];
        if (isset($params['description'])) $data['description'] = $params['description'];
        if (isset($params['sort'])) $data['sort'] = $params['sort'];
        if (isset($params['status'])) $data['status'] = $params['status'];
        return $data;
    }

    public function delete($id)
    {
        if (Db::name('product')->where('brand_id', $id)->find()) {
            return arrayFailed('请先移除该品牌下的产品');
        }
        Db::name('brand')->whereIn('id', $id)->delete();
        return arraySuccess();
    }
}