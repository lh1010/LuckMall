<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Product Category Repository
 * ============================================================================
 * Author: Jasper   
 */

namespace app\repository;

use think\Db;

class ProductCategoryRepository
{
    private $childIds;

    /**
     * Get Product Categorys
     * @param int $params['status']
     * @param int $params['parent_id']
     * @param array $params['parent_ids']
     * @param int $params['is_use_image']
     * @return array
     */
    public function getCategorys($params = [])
    {
        $query = Db::name('product_category')->alias('product_category');
        $this->setGetCategorysParams($query, $params);
        $query->order('product_category.sort desc');
        $categorys = $query->select();
        $categorys = $this->setGetCategorysAddition($categorys, $params);
        return $categorys;
    }

    private function setGetCategorysParams($query, $params = [])
    {
        if (isset($params['status'])) $query->where('product_category.status', $params['status']);
        if (isset($params['parent_id'])) $query->where('product_category.parent_id', $params['parent_id']);
        if (isset($params['parent_ids'])) $query->whereIn('product_category.parent_id', $params['parent_ids']);
    }

    private function setGetCategorysAddition($categorys, $params = [])
    {
        $categorys = $this->setImage($categorys);
        return $categorys;
    }

    /**
     * Set Product Category Image
     * @param array $data
     * @param int $type 1|2|3
     * @return array
     */
    public function setImage($data, $type = 2)
    {
        if (empty($data)) return [];
        switch ($type) {
            case '2':
                foreach ($data as $key => $value) {
                    empty($value['wxapp_cover']) && $value['parent_id'] != 0 ? $data[$key]['wxapp_cover'] = Config('image.product_catrgory.wxapp_cover_default') : null;
                }
                break;    
        }
        return $data;
    }

    /**
     * Get Menu Product Categorys
     * @return array
     */
    public function getMenuCategorys()
    {
        $categorys = $this->getCategorys(['status' => 1, 'parent_id' => 0]);
        if (!$categorys) return [];
        $category_ids = array_column($categorys, 'id');
        // Get Secondary Categorys
        $array = [];
        $twoCategorys = $this->getCategorys(['status' => 1, 'parent_ids' => $category_ids]);
        foreach ($twoCategorys as $value) {
            $array[$value['parent_id']][] = $value;
        }
        foreach ($categorys as $key => $value) {
            $categorys[$key]['twoCategorys'] = isset($array[$value['id']]) ? $array[$value['id']] : [];
        }
        // Get Three Categorys
        $array = [];
        $category_ids = array_column($twoCategorys, 'id');
        $threeCategorys = $this->getCategorys(['status' => 1, 'parent_ids' => $category_ids]);
        foreach ($threeCategorys as $value) {
            $array[$value['parent_id']][] = $value;
        }     
        foreach ($categorys as $key => $value) {
            if (!empty($value['twoCategorys'])) {
                foreach ($value['twoCategorys'] as $key_two => $value_two) {
                    $categorys[$key]['twoCategorys'][$key_two]['threeCategorys'] = isset($array[$value_two['id']]) ? $array[$value_two['id']] : [];
                }
            }
        }
        return $categorys;
    }

    // 设置页面title、nav
    public function setAdditionData($params)
    {
        $title = '全部产品';
        $nav = '<a href="'.Config('system.app_url').'">首页</a>';
        if (isset($params['k']) && !empty($params['k'])) {
            $title = $params['k'] . ' - 搜索结果';
            $nav .= ' > 搜索结果 > <span>'.$params['k'].'</span>';
        } 
        if (isset($params['category_id'])) {
            $category_parent_ids = $this->getCategoryParentIds($params['category_id']);
            $categorys = Db::name('product_category')->field('id, name')->whereIn('id', $category_parent_ids)->select();
            $category_names = array_column($categorys, 'name');
            $title = implode(' ', array_reverse($category_names));
            $title .= ' - 产品分类';
            foreach ($categorys as $key => $value) {
                $nav .= ' > <a href="'.url('product/products', ['category_id' => $value['id']]).'">'.$value['name'].'</a>';
            }
        }
        if (isset($params['min_price']) && isset($params['max_price'])) $nav .= ' > <label class="label" data-ident="price">价格：'.$params['min_price'].'-'.$params['max_price'].'<em>x</em></label>';
        if (isset($params['min_price']) && !isset($params['max_price'])) $nav .= ' > <label class="label" data-ident="price">价格：'.$params['min_price'].' +<em>x</em></label>';
        if (!isset($params['min_price']) && isset($params['max_price'])) $nav .= ' > <label class="label" data-ident="price">价格：0-'.$params['max_price'].'<em>x</em></label>';
        if ($title == '全部产品') $nav .= ' > 全部产品';

        $data['title'] = $title;
        $data['nav'] = $nav;
        return $data;
    }

    /**
	 * Get All Child Category ID
	 * @param int $id current category id
	 * @return array Child Category ID
	 */
	public function getChildIds($id, $i = 0)
	{
		$query = Db::name('product_category')->where('parent_id', $id);
		$query->where('status', 1);
		$childIds = $query->column('id');
		$i == 0 ? $this->childIds[] = (int)$id : null;
		$this->childIds = array_merge($this->childIds, $childIds);
		if (!empty($childIds)) {
			foreach ($childIds as $key => $value) {
				$i++;
				$this->getChildIds($value, $i);
			}
		}
		return $this->childIds;
    }

    public function getCategoryParentIds($id, $type = 2)
    {
        $parent_ids = Db::name('product_category')->where('id', $id)->value('parent_ids');
        if ($type == 2) $parent_ids = explode(',', $parent_ids);
        return $parent_ids;
    }
}