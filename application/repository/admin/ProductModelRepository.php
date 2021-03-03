<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * 产品模型 Repository Admin
 * ============================================================================
 * Author: Jasper   
 */

namespace app\repository\admin;

use think\Db;
use app\repository\admin\ProductAttributeRepository;
use app\repository\admin\ProductSpecificationRepository;

class ProductModelRepository
{   
    public function getProductModelsPaginate()
    {
        $query = Db::name('product_model');
        $query->where('status', '<>', 99);
        return $query->paginate();
    }

    public function getProductModels()
    {
        $query = Db::name('product_model');
        $query->where('status', '<>', 99);
        return $query->select();
    }

    public function getProductModel($id)
    {
        $query = Db::name('product_model');
        $query->where('id', $id);
        $query->where('status', '<>', 99);
        return $query->find();
    }

    /**
     * 获取产品模型信息
     * 获取产品模型下的所有属性规格信息
     * @param int $id product model id
     * @param int $product_id
     * @return array
     */
    public function getProductModelAllData($id, $product_id = 0)
    {
        $product_model = Db::name('product_model')->where('id', $id)->where('status', '<>', 99)->find();
        if (empty($product_model)) return [];
        // get product attributes
        $product_attributes = app(ProductAttributeRepository::class)->getProductAttributes(['product_model_id' => $id], $is_use_options = 1);
        // get product specification
        $product_specifications = app(ProductSpecificationRepository::class)->getProductSpecifications(['product_model_id' => $id], $is_use_options = 1);
        if ($product_id > 0) {
            $product_to_attributes = Db::name('product_to_attribute')->where('product_id', $product_id)->select();
            $array = [];
            foreach ($product_to_attributes as $key => $value) $array[$value['product_attribute_id']] = $value;
           
            foreach ($product_attributes as $key => $value) {
                $product_attributes[$key]['value'] = isset($array[$value['id']]) ? $array[$value['id']]['product_attribute_value'] : '';
            }
        }
        $product_model['product_attributes'] = $product_attributes;
        $product_model['product_specifications'] = $product_specifications;
        
        return $product_model;
    }

    public function create($params = [])
    {
        $data = $this->setCreateUpdateData($params);
        $query = Db::name('product_model');
        $query->insert($data);
        return arraySuccess();
    }

    public function update($params = [], $id)
    {
        $data = $this->setCreateUpdateData($params);
        $query = Db::name('product_model');
        $query->where('id', $id);
        $query->update($data);
        return arraySuccess();
    }

    private function setCreateUpdateData($params = [])
    {
        $data = [];
        if (isset($params['name'])) $data['name'] = $params['name'];
        if (isset($params['description'])) $data['description'] = $params['description'];
        if (isset($params['status'])) $data['status'] = $params['status'];
        return $data;
    }

    public function delete($id)
    {
        try {
			Db::startTrans();
            // product_attribute
            $product_attribute_ids = Db::name('product_attribute')->whereIn('product_model_id', $id)->column('id');
			Db::name('product_attribute_option')->whereIn('product_attribute_id', $product_attribute_ids)->update(['status' => 99]);
			Db::name('product_attribute')->whereIn('id', $product_attribute_ids)->update(['status' => 99]);
            // product_specification
            $product_specification_ids = Db::name('product_specification')->whereIn('product_model_id', $id)->column('id');
			Db::name('product_specification_option')->whereIn('product_specification_id', $product_specification_ids)->update(['status' => 99]);
			Db::name('product_specification')->whereIn('id', $product_specification_ids)->update(['status' => 99]);
            // product_model
            Db::name('product_model')->whereIn('id', $id)->update(['status' => 99]);
			Db::commit();
			return arraySuccess();
		} catch (\Throwable $th) {
			Db::rollback();
			return arrayFailed();
		}
    }
}