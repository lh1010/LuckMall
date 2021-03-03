<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * 产品销售规格 Repository Admin
 * ============================================================================
 * Author: Jasper
 */

namespace app\repository\admin;

use think\Db;

class ProductSpecificationRepository
{
	/**
     * Get Product Specifications
     * @param array $skus
     * @return array
     */
    public function getCurrentProductToSpecifications($params = [])
    {
        $filed = 'product_to_specification.product_id, product_to_specification.sku, product_to_specification.product_specification_id as specification_id, product_to_specification.product_specification_option_id as specification_option_id, product_specification.name as specification_name, product_specification_option.value as specification_option_value';
        $query = Db::name('product_to_specification')->alias('product_to_specification');
        $query->field($filed);
        $query->leftJoin('product_specification', 'product_specification.id = product_to_specification.product_specification_id');
        $query->leftJoin('product_specification_option', 'product_specification_option.id = product_to_specification.product_specification_option_id');
        if (isset($params['skus'])) $query->whereIn('sku', $params['skus']);
        if (isset($params['sku'])) $query->where('sku', $params['sku']);
        $product_to_specifications = $query->select();
        return $product_to_specifications;                        
    }

	public function getProductSpecificationsPaginate($params = [])
    {
		$query = Db::name('product_specification')->alias('product_specification');
		$query->field('product_specification.*, product_model.name as product_model_name');
		$query->leftJoin('product_model', 'product_model.id = product_specification.product_model_id');
		$this->setGetProductSpecificationsParams($query, $params);
		$query->where('product_specification.status', '<>', 99);
        return $query->paginate();
	}
	
	/**
	 * Get Product Specifications
	 * @param array $params
	 * @param int $is_use_options 是否使用options
	 * @return array
	 */
	public function getProductSpecifications($params = [], $is_use_options = 0)
	{
		$query = Db::name('product_specification')->alias('product_specification');
		$query->field('product_specification.*, product_model.name as product_model_name');
		$query->leftJoin('product_model', 'product_model.id = product_specification.product_model_id');
		$this->setGetProductSpecificationsParams($query, $params);
		$product_specifications = $query->select();
		if ($is_use_options == 1) {
			$array = [];
			$product_specification_ids = array_column($product_specifications, 'id');
			$options = Db::name('product_specification_option')->whereIn('product_specification_id', $product_specification_ids)->where('status', '<>', 99)->select();
			foreach ($options as $key => $value) {
				$array[$value['product_specification_id']][] = $value;
			}
			foreach ($product_specifications as $key => $value) {
				$product_specifications[$key]['options'] = isset($array[$value['id']]) ? $array[$value['id']] : [];
			}
		}
		return $product_specifications;
	}

	private function setGetProductSpecificationsParams($query, $params = [])
	{
		if (isset($params['product_model_id'])) $query->where('product_specification.product_model_id', $params['product_model_id']);
		if (isset($params['status'])) $query->where('product_specification.status', $params['status']);
	}

	public function getProductSpecification($id)
	{
		$query = Db::name('product_specification');
		$query->where('id', $id);
		$product_specification = $query->find();
		if (empty($product_specification)) return $product_specification;
		$product_specification['options'] = Db::name('product_specification_option')->where('product_specification_id', $id)->where('status', '<>', 99)->select();
		return $product_specification;
	}

	public function create($params = [])
	{
		Db::startTrans();
    	try {
			$data = $this->setCreateUpdateData($params);
			$product_specification_id = Db::name('product_specification')->insertGetId($data);
			// 处理选项
			if (isset($params['option_values']) && !empty($params['option_values'])) {
				$option_values = $params['option_values']; $data = [];
				foreach ($option_values as $key => $value) {
					$data[$key]['product_specification_id'] = $product_specification_id;
					$data[$key]['value'] = $value;
				}
				Db::name('product_specification_option')->insertAll($data);
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
    		Db::name('product_specification')->where('id', $id)->update($data);
			// 处理选项
			if (isset($params['option_ids']) && !empty($params['option_ids'])) {
				$option_ids = $params['option_ids']; $option_values = $params['option_values']; $data = [];
				Db::name('product_specification_option')->where('product_specification_id', $id)->whereNotIn('id', $option_ids)->update(['status' => 99]);
				foreach ($option_ids as $key => $value) {
					if (empty($value)) {
						$data[$key]['product_specification_id'] = $id;
						$data[$key]['value'] = $option_values[$key];
					}
				}
				if (!empty($data)) Db::name('product_specification_option')->insertAll($data);
			} else {
				Db::name('product_specification_option')->where('product_specification_id', $id)->where('status', 1)->update(['status' => 99]);
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
		if (isset($params['sort'])) $data['sort'] = $params['sort'];
        if (isset($params['status'])) $data['status'] = $params['status'];
        return $data;
	}

	public function delete($id)
	{
		try {
			Db::startTrans();
			Db::name('product_specification_option')->whereIn('product_specification_id', $id)->update(['status' => 99]);
			Db::name('product_specification')->whereIn('id', $id)->update(['status' => 99]);
			Db::commit();
			return arraySuccess();
		} catch (\Throwable $th) {
			Db::rollback();
			return arrayFailed();
		}
	}
}