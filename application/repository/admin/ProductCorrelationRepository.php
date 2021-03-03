<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Product Correlation Repository Admin
 * ============================================================================
 * Author: Jasper
 */

namespace app\repository\admin;

use think\Db;

class ProductCorrelationRepository 
{
    /**
	 * 处理关联产品
	 * @param int $product_id
	 * @param array $correlationProductIds
	 */
	public function createOrUpdate($product_id, $correlationProductIds, $type = 'create')
	{
        if ($type == 'update') {
            $group_id = Db::name('product_correlation')->where('product_id', $product_id)->value('group_id');
            if (!empty($group_id)) {
                Db::name('product_correlation')->where('group_id', $group_id)->delete();
            }
        }
        if (empty($correlationProductIds)) return false;
        $data = [];
        $group_id = time();
        $current = [['group_id' => $group_id, 'product_id' => $product_id]];
		foreach ($correlationProductIds as $key => $value) {
            Db::name('product_correlation')->where('product_id', $value)->delete();
			$data[$key]['group_id'] = $group_id;
			$data[$key]['product_id'] = $value;
        }
        $data = array_merge($current, $data);
        $data = array_uniqueness($data, 'product_id');
        foreach ($data as $key => $value) {
            Db::name('product_correlation')->where('product_id', $value['product_id'])->delete();
        }
        Db::name('product_correlation')->insertAll($data);
	}
}