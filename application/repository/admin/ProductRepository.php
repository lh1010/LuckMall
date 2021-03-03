<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Product Repository Admin
 * ============================================================================
 * Author: Jasper
 */

namespace app\repository\admin;

use think\Db;
use app\repository\admin\ImageRepository;
use app\repository\admin\ProductImageRepository;
use app\repository\admin\ProductAttributeRepository;
use app\repository\admin\ProductSpecificationRepository;
use app\repository\admin\ProductCorrelationRepository;

class ProductRepository
{
	public function getProductsPaginate($params = [])
	{
		$query = Db::name('product')->alias('product');
        $select = ['product.*, brand.name as brand_name'];
		$query->field($select);
		$this->setGetProductsParams($query, $params);
		$query->where('product.status', '<>', 99);
		$query->leftJoin('product_category', 'product_category.id = product.category_id');
		$query->leftJoin('brand', 'brand.id = product.brand_id');
		$query->order('product.id desc');
		$products = $query->paginate();
		if ($products->total() == 0) return $products;
		$this->getProductsPaginateAddition($products, $params);
        return $products;
	}

	public function getProducts($params = [], $limit = 0)
	{
		$query = Db::name('product')->alias('product');
        $select = ['product.*, brand.name as brand_name'];
		$query->field($select);
		$this->setGetProductsParams($query, $params);
		$query->where('product.status', '<>', 99);
		$query->leftJoin('product_category', 'product_category.id = product.category_id');
		$query->leftJoin('brand', 'brand.id = product.brand_id');
		$query->order('product.id desc');
		if ($limit > 0) $query->limit($limit);
		$products = $query->select();
		if (empty($products)) return $products;
		$products = $this->getProductsAddition($products, $params);
        return $products;
	}

	private function setGetProductsParams($query, $params)
	{
		if (isset($params['category_id']) && !empty($params['category_id'])) $query->where('product.category_id', $params['category_id']);
		if (isset($params['category_ids'])) $query->whereIn('product.category_id', $params['category_ids']);
		if (isset($params['brand_id']) && !empty($params['brand_id'])) $query->where('product.brand_id', $params['brand_id']);
		if (isset($params['product_ids'])) $query->whereIn('product.id', $params['product_ids']);
		if (isset($params['keyword'])) $query->where('product.name', 'like', "%".$params['keyword']."%");
		if (isset($params['exclude_product_ids'])) $query->whereNotIn('product.id', $params['exclude_product_ids']);
	}

	private function getProductsPaginateAddition($products, $params)
	{
		$products->each(function($value, $key) {
			$value['full_category_name'] = $this->getFullCategoryName($value['category_id']);
			return $value;
		});
	}

	private function getProductsAddition($products, $params)
	{
		foreach ($products as $key => $value) {
			$products[$key]['full_category_name'] = $this->getFullCategoryName($value['category_id']);
		}
		return $products;
	}

	public function create($params = [])
	{
		Db::startTrans();
		try {
			// 产品基础信息
			$data = $this->setCreateUpdateData($params);
			$product_id = Db::name('product')->insertGetId($data);
			// 默认 SKU 信息
			$sku_default_data = [];
			$sku_default_data['product_id'] = $product_id;
			$sku_default_data['sku'] = !empty($params['sku']) ? $params['sku'] : $this->getUniqueSku();
			$sku_default_data['stock'] = $params['stock'];
			$sku_default_data['sale_price'] = $params['sale_price'];
			$sku_default_data['default'] = 1;
			$sku_default_data['status'] = 1;
			if (isset($params['specification_option_id'])) {
				// insertAll 不支持返回自增ID 将在循环中执行操作
				foreach ($params['specification_option_id'] as $key => $value) {
					$sku_data = [];
					$product_to_specification_data = [];
					$sku_data['product_id'] = $product_id;
					$sku_data['sku'] = !empty($value['sku']) ? $value['sku'] : $this->getUniqueSku();
					$sku_data['stock'] = $value['stock'];
					$sku_data['sale_price'] = $value['sale_price'];
					// 检查自定义的 sku 是否已存在，确保 sku 的唯一性
					if (Db::name('product_sku')->where('sku', $sku_data['sku'])->value('sku')) {
						Db::rollback();
						return arrayFailed('商家编码：'.$sku_data['sku'].' 已存在');
					}
					Db::name('product_sku')->insert($sku_data);
					// skus images
					if (isset($params['images']) && isset($params['images'][$key])) {
						$image_data = [];
						foreach ($params['images'][$key] as $key_image => $value_image) {
							$image_data[$key_image]['default'] = $key_image == 999 ? 1 : 0;
							$image_data[$key_image]['product_id'] = $product_id;
							$image_data[$key_image]['sku'] = $sku_data['sku'];
							$image_data[$key_image]['image'] = $value_image;
							app(ImageRepository::class)->setProductImage($value_image, $product_id);
						}
						Db::name('product_image')->insertAll($image_data);
					}
					// 销售规格
					$array = explode('_', $key);
					foreach ($array as $k => $v) {
						$product_to_specification_data[$k]['product_id'] = $product_id;
						$product_to_specification_data[$k]['sku'] = $sku_data['sku'];
						$product_to_specification_data[$k]['product_specification_id'] = Db::name('product_specification_option')->where('id', $v)->value('product_specification_id');
						$product_to_specification_data[$k]['product_specification_option_id'] = $v;
					}
					Db::name('product_to_specification')->insertAll($product_to_specification_data);
				}
				$sku_default_data['status'] = 0;
			}
			if (isset($params['image'])) {
				$image_data = [];
				foreach ($params['image'] as $key => $value) {
					app(ImageRepository::class)->setProductImage($value, $product_id);
					$image_data[$key]['default'] = $key == 999 ? 1 : 0;
					$image_data[$key]['sku'] = $sku_default_data['sku'];
					$image_data[$key]['image'] = $value;
					$image_data[$key]['product_id'] = $product_id;
				}
				Db::name('product_image')->insertAll($image_data);
			}
			Db::name('product_sku')->insertGetId($sku_default_data);
			// 产品属性
			if (isset($params['attributes'])) {
				$product_attribute_data = [];
				foreach ($params['attributes'] as $key => $value) {
					if (!empty($value)) {
						$product_attribute_data[$key]['product_id'] = $product_id;
						$product_attribute_data[$key]['product_attribute_id'] = $key;
						$product_attribute_data[$key]['product_attribute_value'] = $value;
					}
				}
				if (!empty($product_attribute_data)) Db::name('product_to_attribute')->insertAll($product_attribute_data);
			}
			// 运费信息
			$freight_id = isset($params['freight_id']) ? $params['freight_id'] : 0;
			$product_freight_data = ['product_id' => $product_id, 'freight' => $params['freight'], 'freight_id' => $freight_id, 'weight' => $params['weight'], 'volume' => $params['volume']];
			Db::name('product_freight')->insert($product_freight_data);
			Db::commit();
			return arraySuccess();
		} catch (\Throwable $th) {
			Db::rollback();
		    return arrayFailed($th->getMessage());
		}
	}

	public function update($params = [], $product_id)
	{
		Db::startTrans();
		try {
			// 产品基础信息
			$data = $this->setCreateUpdateData($params);
			Db::name('product')->where('id', $product_id)->update($data);
			// SKU 信息
			$this->setUpdate_sku($product_id, $params);
			// 产品属性
			if (isset($params['attributes'])) {
				$product_attribute_data = [];
				$already_attribute_ids = [];
				foreach ($params['attributes'] as $key => $value) {
					if (!empty($value)) {
						$already_attribute_ids[] = $key;
						if ($product_to_attribute_id = Db::name('product_to_attribute')->where('product_id', $product_id)->where('product_attribute_id', $key)->value('id')) {
							Db::name('product_to_attribute')->where('id', $product_to_attribute_id)->update(['product_attribute_value' => $value]);
						} else {
							$product_attribute_data[$key]['product_id'] = $product_id;
							$product_attribute_data[$key]['product_attribute_id'] = $key;
							$product_attribute_data[$key]['product_attribute_value'] = $value;
						}
					}
				}
				Db::name('product_to_attribute')->where('product_id', $product_id)->where('product_attribute_id', 'NOT IN', $already_attribute_ids)->delete();
				if (!empty($product_attribute_data)) Db::name('product_to_attribute')->insertAll($product_attribute_data);
			} else {
				Db::name('product_to_attribute')->where('product_id', $product_id)->delete();
			}
			// 运费信息
			$freight_id = isset($params['freight_id']) ? $params['freight_id'] : 0;
			$product_freight_data = ['product_id' => $product_id, 'freight' => $params['freight'], 'freight_id' => $freight_id, 'weight' => $params['weight'], 'volume' => $params['volume']];
			Db::name('product_freight')->where('product_id', $product_id)->update($product_freight_data);
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
		if (isset($params['category_id'])) $data['category_id'] = $params['category_id'];
		if (isset($params['product_model_id'])) $data['model_id'] = $params['product_model_id'];
		if (isset($params['brand_id'])) $data['brand_id'] = $params['brand_id'];
		if (isset($params['content'])) $data['content'] = $params['content'];
		if (isset($params['sort'])) $data['sort'] = $params['sort'];
		if (isset($params['status'])) $data['status'] = $params['status'];
        return $data;
	}

	private function setUpdate_sku($product_id, $params)
	{
		$new_skus = [];
		$already_skus = [];
		$already_sku_skus = [$params['sku']];
		$sku_default_data = [];
		$sku_default_data['stock'] = $params['stock'];
		$sku_default_data['sale_price'] = $params['sale_price'];
		$sku_default_data['status'] = 1;
		if (isset($params['specification_option_id'])) {
			foreach ($params['specification_option_id'] as $key => $value) {
				if (isset($value['sku_id'])) {
					$already_sku_skus[] = $value['sku'];
					$already_skus[$key] = $value;
				} else {
					$new_skus[$key]['sale_price'] = $value['sale_price'];
					$new_skus[$key]['stock'] = $value['stock'];
					$new_skus[$key]['sort'] = $value['sort'];
					$new_skus[$key]['sku'] = !empty($value['sku']) ? $value['sku'] : $this->getUniqueSku();
					$already_sku_skus[] = $new_skus[$key]['sku'];
					$new_skus[$key]['product_id'] = $product_id;
				}
			}
			// 处理已存在的 SKU
			if (!empty($already_skus)) {
				foreach ($already_skus as $key => $value) {
					$sku_data = [];
					$sku_data['stock'] = $value['stock'];
					$sku_data['sale_price'] = $value['sale_price'];
					$sku_data['sort'] = $value['sort'];
					Db::name('product_sku')->where('id', $value['sku_id'])->update($sku_data);
					if (isset($params['images']) && isset($params['images'][$key])) {
						$image_data = [];
						$already_image_ids = [];
						foreach ($params['images'][$key] as $key_image => $value_image) {
							app(ImageRepository::class)->setProductImage($value_image, $product_id);
							if (strstr($key_image, 'already')) {
								$array = explode('_', $key_image);
								$already_image_ids[] = $array[1];
								Db::name('product_image')->where('id', $array[1])->update(['image' => $value_image]);
							} else {
								$image_data[$key_image]['product_id'] = $product_id;
								$image_data[$key_image]['sku'] = $value['sku'];
								$image_data[$key_image]['image'] = $value_image;
								$image_data[$key_image]['default'] = $key_image == 999 ? 1 : 0;
							}
						}
						Db::name('product_image')->where('sku', $value['sku'])->where('id', 'NOT IN', $already_image_ids)->delete();
						if (!empty($image_data)) Db::name('product_image')->insertAll($image_data);
					} else {
						Db::name('product_image')->where('sku', $value['sku'])->delete();
					}
				}
			}
			// 处理新添加的 SKU
			if (!empty($new_skus)) {
				foreach ($new_skus as $key => $value) {
					// 检查自定义的 sku 是否已存在，确保 sku 的唯一性
					if (Db::name('product_sku')->where('sku', $value['sku'])->value('sku')) {
						Db::rollback();
						return arrayFailed('商家编码：'.$value['sku'].' 已存在');
					}
					$sku_data = [];
					$sku_data['product_id'] = $product_id;
					$sku_data['sku'] = $value['sku'];
					$sku_data['stock'] = $value['stock'];
					$sku_data['sort'] = $value['sort'];
					$sku_data['sale_price'] = $value['sale_price'];
					Db::name('product_sku')->insert($sku_data);
					if (isset($params['images']) && isset($params['images'][$key])) {
						$image_data = [];
						foreach ($params['images'][$key] as $key_image => $value_image) {
							app(ImageRepository::class)->setProductImage($value_image, $product_id);
							$image_data[$key_image]['product_id'] = $product_id;
							$image_data[$key_image]['sku'] = $sku_data['sku'];
							$image_data[$key_image]['image'] = $value_image;
							$image_data[$key_image]['default'] = $key_image == 999 ? 1 : 0;
						}
						Db::name('product_image')->insertAll($image_data);	
					}
					$array = explode('_', $key);
					foreach ($array as $k => $v) {
						$product_to_specification_data[$k]['product_id'] = $product_id;
						$product_to_specification_data[$k]['sku'] = $value['sku'];
						$product_to_specification_data[$k]['product_specification_id'] = Db::name('product_specification_option')->where('id', $v)->value('product_specification_id');
						$product_to_specification_data[$k]['product_specification_option_id'] = $v;
					}
					Db::name('product_to_specification')->insertAll($product_to_specification_data);
				}
			}
			$sku_default_data['status'] = 0;
		}
		// 清除 SKU
		Db::name('product_sku')->where('product_id', $product_id)->where('sku', 'NOT IN', $already_sku_skus)->delete();
		Db::name('product_to_specification')->where('product_id', $product_id)->where('sku', 'NOT IN', $already_sku_skus)->delete();
		Db::name('product_image')->where('product_id', $product_id)->where('sku', 'NOT IN', $already_sku_skus)->delete();
		// 默认 SKU
		Db::name('product_sku')->where('product_id', $product_id)->where('default', 1)->update($sku_default_data);
		if (isset($params['image'])) {
			$image_data = [];
			foreach ($params['image'] as $key => $value) {
				app(ImageRepository::class)->setProductImage($value, $product_id);
				if (strstr($key, 'already')) {
					$array = explode('_', $key);
					$already_image_ids[] = $array[1];
					Db::name('product_image')->where('id', $array[1])->update(['image' => $value]);
				} else {
					$image_data[$key]['default'] = $key == 999 ? 1 : 0;
					$image_data[$key]['product_id'] = $product_id;
					$image_data[$key]['sku'] = $params['sku'];
					$image_data[$key]['image'] = $value;
				}
			}
			if (!empty($image_data)) Db::name('product_image')->insertAll($image_data);
		} else {
			Db::name('product_image')->where('sku', $params['sku'])->delete();
		}
	}

	public function getUniqueSku()
	{
		$sku = microtime(true);
		$sku = str_replace('.', '', $sku);
		if (Db::name('product_sku')->where('sku', $sku)->value('sku')) {
			$this->getUniqueSku(); return false;
		}
		return $sku;
	}

	public function getProduct($product_id)
	{
		$query = Db::name('product')->alias('product');
		$query->field('product.*');
		$query->leftJoin('product_category', 'product_category.id = product.category_id');
		$query->where('product.id', $product_id);
		$product = $query->find();
		if (empty($product)) return [];
		$product['full_category_name'] = $this->getFullCategoryName($product['category_id']);
		// get freight
		$product['freight'] = Db::name('product_freight')->where('product_id', $product_id)->find();
		// get sku
		$skus = Db::name('product_sku')->where('product_id', $product_id)->select();
		$sku_array = array_column($skus, 'sku');
		$product_images = app(ProductImageRepository::class)->getImages(['skus' => $sku_array]);
		$array = [];
		foreach ($product_images as $key => $value) $array[$value['sku']][] = $value;
		foreach ($skus as $key => $value) $skus[$key]['images'] = isset($array[$value['sku']]) ? $array[$value['sku']] : [];
		foreach ($skus as $key => $value) {
			if ($value['default'] == 1) {
				$product['default_sku'] = $value;
				unset($skus[$key]);
			}
		}
		$product_to_specifications = Db::name('product_to_specification')->alias('product_to_specification')
							->field('product_to_specification.*, product_specification.name as product_specification_name, product_specification_option.value as product_specification_option_value')
							->leftJoin('product_specification', 'product_specification.id = product_to_specification.product_specification_id')
							->leftJoin('product_specification_option', 'product_specification_option.id = product_to_specification.product_specification_option_id')
							->where('product_to_specification.product_id', $product_id)
							->select();
		$array = [];
		foreach ($product_to_specifications as $key => $value) $array[$value['sku']][] = $value;
		foreach ($skus as $key => $value) {
			$skus[$key]['specifications'] = isset($array[$value['sku']]) ? $array[$value['sku']] : [];
		}
		foreach ($skus as $key => $value) {
			$skus[$key]['option_id_connect'] = '';
			if (!empty($value['specifications'])) {
				$option_id_connect = array_column($value['specifications'], 'product_specification_option_id');
				$skus[$key]['option_id_connect'] = implode('_', $option_id_connect);
				$option_value_connect = array_column($value['specifications'], 'product_specification_option_value');
				$skus[$key]['option_value_connect'] = implode('/', $option_value_connect);
			}
		}
		array_multisort($skus);
		$product['skus'] = $skus;
		// get product model
		$product['model'] = app(ProductModelRepository::class)->getProductModelAllData($product['model_id'], $product_id);
		return $product;
	}

	public function getDefaultSku($product_id)
	{
		$sku = Db::name('product_sku')->where('product_id', $product_id)->where('default', 1)->find();
		return $sku;
	}

	/**
     * Set Product Image Show
     * @param array $data
     * @param int $type 1|2|3
     * @return array
     */
    public function setImageShow($data, $type = 3)
    {
        if (empty($data)) return [];
        switch ($type) {
            case '1':
                empty($data['image']) ? $data['image'] = Config('image.product.image_default') : null;
                break;
            case '2':
                foreach ($data as $key => $value) {
                    empty($value['image']) ? $data[$key]['image'] = Config('image.product.image_default') : null;
                }
                break;    
            case '3':
                $data->each(function($item, $key) {
                    if (empty($item['image'])) {
                        $item['image'] = Config('image.product.image_default');
                    }
                    return $item;
                });
                break;
        }
        return $data;
    }

	public function getProductSkusPaginate($params = [])
	{
		$select = 'product.name, product.create_time, product_sku.id, product_sku.product_id, product_sku.sku, product_sku.original_price,
                   product_sku.sale_price, product_sku.stock, product_image.image, product.status as product_status';
        $query = Db::name('product_sku')->alias('product_sku');
        $query->field($select);
        $query->leftJoin('product_image', ['product_image.sku = product_sku.sku', 'product_image.default = 1']);
        $query->leftJoin('product', 'product.id = product_sku.product_id');
        $this->setGetProductSkusParams($query, $params);
        $query->where('product_sku.status', 1)->where('product.status', '<>', 99);
		$product_skus = $query->paginate();
		// 调取销售规格
        $skus = array_column($product_skus->items(), 'sku');
        $product_to_specifications = app(ProductSpecificationRepository::class)->getCurrentProductToSpecifications(['skus' => $skus]);
        $array = [];
        foreach ($product_to_specifications as $key => $value) {
            $array[$value['sku']][] = $value;
        }
        $product_skus->each(function($value, $key) use ($array) {
            $value['specifications'] = isset($array[$value['sku']]) ? $array[$value['sku']] : [];
            return $value;
		});
		$product_skus = $this->setImageShow($product_skus);
		return $product_skus;
	}

	private function setGetProductSkusParams($query, $params)
    {
        if (isset($params['skus'])) $query->whereIn('product_sku.sku', $params['skus']);
        if (isset($params['category_id']) && !empty($params['category_id'])) $query->where('product.category_id', $params['category_id']);
		if (isset($params['category_ids']) && !empty($params['category_ids'])) $query->whereIn('product.category_id', $params['category_ids']);
		if (isset($params['keyword']) && !empty($params['keyword'])) {
			$query->where(function($query) use ($params) {
				$query->where('product.name', 'like', "%".$params['keyword']."%")
				->whereOr('product_sku.sku', $params['keyword']);
			});
		}
	}
	
	public function getProductSku($sku_id)
	{
		$select = 'product.name, product.create_time, product_sku.id, product_sku.product_id, product_sku.sku, product_sku.original_price,
                   product_sku.sale_price, product_sku.stock, product_image.image';
        $query = Db::name('product_sku')->alias('product_sku');
        $query->field($select);
        $query->leftJoin('product_image', ['product_image.sku = product_sku.sku', 'product_image.default = 1']);
        $query->leftJoin('product', 'product.id = product_sku.product_id');
		$query->where('product_sku.id', $sku_id)->where('product_sku.status', 1)->where('product.status', '<>', 99);
		$product_sku = $query->find();
		if (empty($product_sku)) return $product_sku;
		$product_sku['specifications'] =  app(ProductSpecificationRepository::class)->getCurrentProductToSpecifications(['sku' => $product_sku['sku']]);
		return $product_sku;
	}

	public function updateSku($params, $id)
	{
		$query = Db::name('product_sku');
        $data = $this->setCreateUpdateSkuData($params);
        $query->where('id', $id)->update($data);
        return arraySuccess();
	}

	private function setCreateUpdateSkuData($params = [])
	{
		$data = [];
        if (isset($params['stock'])) $data['stock'] = $params['stock'];
		if (isset($params['sale_price'])) $data['sale_price'] = $params['sale_price'];
        return $data;
	}

	public function recycleBin($id)
	{
		Db::name('product')->whereIn('id', $id)->update(['status'=>99]);
		return arraySuccess();
	}

	public function delete($id)
	{
		try {
			Db::startTrans();
			// 删除产品相关数据
			Db::name('product_to_attribute')->whereIn('product_id', $id)->delete();
			Db::name('product_to_specification')->whereIn('product_id', $id)->delete();
			Db::name('product_freight')->whereIn('product_id', $id)->delete();
			Db::name('product_image')->whereIn('product_id', $id)->delete();
			Db::name('product_sku')->whereIn('product_id', $id)->delete();
			Db::name('product')->whereIn('id', $id)->delete();
			Db::commit();
			return arraySuccess();
		} catch (\Throwable $th) {
			Db::rollback();
			return arrayFailed();
		}
	}

	public function getFullCategoryName($category_id)
	{
		$category_parent_ids = app(ProductCategoryRepository::class)->getCategoryParentIds($category_id);
		$categorys = Db::name('product_category')->whereIn('id', $category_parent_ids)->column('name');
		$full_category_name = implode(' > ', $categorys);
		return $full_category_name;
	}
}