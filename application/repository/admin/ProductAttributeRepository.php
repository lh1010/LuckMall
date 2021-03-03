<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * 产品属性 Repository Admin
 * ============================================================================
 * Author: Jasper
 */

namespace app\repository\admin;

use think\Db;

class ProductAttributeRepository
{
	public $typeArray = ['select' => '从列表中选择', 'string' => '手动输入'];

    public function getProductAttributesPaginate($params = [])
    {
		$query = Db::name('product_attribute')->alias('product_attribute');
		$query->field('product_attribute.*, product_model.name as product_model_name');
		$query->leftJoin('product_model', 'product_model.id = product_attribute.product_model_id');
		$this->setGetProductAttributesParams($query, $params);
		$query->where('product_attribute.status', '<>', 99);
        return $query->paginate();
	}

	/**
	 * Get Product Attributes
	 * @param array $params
	 * @param int $is_use_options 是否使用options
	 * @return array
	 */
	public function getProductAttributes($params = [], $is_use_options = 0)
	{
		$query = Db::name('product_attribute')->alias('product_attribute');
		$query->field('product_attribute.*, product_model.name as product_model_name');
		$query->leftJoin('product_model', 'product_model.id = product_attribute.product_model_id');
		$this->setGetProductAttributesParams($query, $params);
		$query->where('product_attribute.status', '<>', 99);
		$product_attributes = $query->select();
		if ($is_use_options == 1) {
			$array = [];
			$product_attribute_ids = array_column($product_attributes, 'id');
			$options = Db::name('product_attribute_option')->whereIn('product_attribute_id', $product_attribute_ids)->where('status', '<>', 99)->select();
			foreach ($options as $key => $value) {
				$array[$value['product_attribute_id']][] = $value;
			}
			foreach ($product_attributes as $key => $value) {
				if ($value['type'] == 'select') {
					$product_attributes[$key]['options'] = isset($array[$value['id']]) ? $array[$value['id']] : [];
				}
			}
		}
		return $product_attributes;
	}
	
	private function setGetProductAttributesParams($query, $params = [])
	{
		if (isset($params['product_model_id'])) $query->where('product_attribute.product_model_id', $params['product_model_id']);
	}

	public function getProductAttribute($id)
	{
		$query = Db::name('product_attribute');
		$query->where('id', $id);
		$product_attribute = $query->find();
		if (!empty($product_attribute)) {
			$product_attribute['options'] = '';
			$product_attribute['options'] = Db::name('product_attribute_option')->where('product_attribute_id', $id)->where('status', '<>', 99)->select();
		}
		return $product_attribute;
	}

	public function create($params = [])
	{
		Db::startTrans();
    	try {
			$data = $this->setCreateUpdateData($params);
			$product_attribute_id = Db::name('product_attribute')->insertGetId($data);
			// 处理选项
			if (isset($params['option_values']) && !empty($params['option_values'])) {
				$option_values = $params['option_values']; $data = [];
				foreach ($option_values as $key => $value) {
					$data[$key]['product_attribute_id'] = $product_attribute_id;
					$data[$key]['value'] = $value;
				}
				Db::name('product_attribute_option')->insertAll($data);
			}
			Db::commit();
			return arraySuccess();
    	} catch (Exception $e) {
    		Db::rollback();
    		return arrayFailed();
    	}
	}

	public function update($params = [], $id)
	{
		Db::startTrans();
    	try {
			$data = $this->setCreateUpdateData($params);
			Db::name('product_attribute')->where('id', $id)->update($data);
			// 处理选项
			if (isset($params['option_ids']) && !empty($params['option_ids'])) {
				$option_ids = $params['option_ids']; $option_values = $params['option_values']; $data = [];
				Db::name('product_attribute_option')->where('product_attribute_id', $id)->whereNotIn('id', $option_ids)->update(['status' => 99]);
				foreach ($option_ids as $key => $value) {
					if (empty($value)) {
						$data[$key]['product_attribute_id'] = $id;
						$data[$key]['value'] = $option_values[$key];
					}
				}
				if (!empty($data)) Db::name('product_attribute_option')->insertAll($data);
			} else {
				Db::name('product_attribute_option')->where('product_attribute_id', $id)->where('status', 1)->update(['status' => 99]);
			}
			Db::commit();
			return arraySuccess();
    	} catch (Exception $e) {
    		Db::rollback();
    		return arrayFailed();
    	}
	}

	private function setCreateUpdateData($params = [])
	{
		$data = [];
        if (isset($params['product_model_id'])) $data['product_model_id'] = $params['product_model_id'];
		if (isset($params['name'])) $data['name'] = $params['name'];
		if (isset($params['type'])) $data['type'] = $params['type'];
		if (isset($params['sort'])) $data['sort'] = $params['sort'];
        if (isset($params['status'])) $data['status'] = $params['status'];
        return $data;
	}

	public function delete($id)
	{
		try {
			Db::startTrans();
			Db::name('product_attribute_option')->whereIn('product_attribute_id', $id)->update(['status' => 99]);
			Db::name('product_attribute')->whereIn('id', $id)->update(['status' => 99]);
			Db::commit();
			return arraySuccess();
		} catch (\Throwable $th) {
			Db::rollback();
			return arrayFailed();
		}
	}
}