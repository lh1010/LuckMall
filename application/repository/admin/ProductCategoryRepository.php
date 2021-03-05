<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ===============================================---------
 * Product Category Repository Admin
 * ============================================================================
 * Author: Jasper
 */

namespace app\repository\admin;

use think\Db;

class ProductCategoryRepository
{
    private $tree_list = [];
    private $child_ids = [];
    private $parent_ids = [];

    public function getCategory($id)
    {
        $category = Db::name('product_category')->where('id', $id)->find();
        return $category;
    }

	/**
	 * Get Categorys
	 * @param int $params['parent_id']
     * @param int $params['status']
     * @param int $params['if_use_ccc'] 下级分类数量
	 * @return array
	 */
	public function getCategorys($params = [], $type = 'tree')
    {
        $query = Db::name('product_category');
        if (isset($params['status'])) $query->where('status', $params['status']);
        if (isset($params['parent_id'])) $query->where('parent_id', $params['parent_id']);
        $categorys = $query->select();
        if (isset($params['if_use_ccc'])) $categorys = $this->getChildCategoryCount($categorys);
        switch ($type) {
            case 'select':
                return $categorys;
                break;
            case 'tree':
                $categorys = $this->tree($categorys);
                return $categorys;
                break;
        }
    }

    /**
	 * tree
	 * @param array $data
	 * @param int $parent_id
	 * @param int $level
	 * @return array
	 */
    private function tree($data, $parent_id = 0, $level = 1)
    {
        foreach ($data as $v){
            if ($v['parent_id'] == $parent_id) {
                $v['level'] = $level;
                $this->tree_list[] = $v;
                $this->tree($data, $v['id'], $level + 1);
            }
        }
        return $this->tree_list;
    }

    private function getChildCategoryCount($categorys)
    {
        foreach ($categorys as $key => $value) {
            $array = explode(',', $value['child_ids']);
            $categorys[$key]['child_category_count'] = count($array) - 1;
        }
        return $categorys;
    }

    public function create($params = [])
    {
        Db::startTrans();
        try {
            $data = $this->setCreateUpdateData($params);
            $id = Db::name('product_category')->insertGetId($data);
            $this->createAssist($id);
            Db::commit();
            return arraySuccess();
        } catch (\Throwable $th) {
            Db::rollback();
            return arrayFailed();
        }
    }

    private function createAssist($id)
    {
        // 上级分类更新下级分类集
        $parent_ids = $this->getParentIds($id);
        foreach ($parent_ids as $key => $value) {
            if ($value == $id) {
                $parent_ids = implode(',', $parent_ids);
                Db::name('product_category')->where('id', $value)->update(['child_ids' => $id, 'parent_ids' => $parent_ids]);
            } else {
                $child_ids = Db::name('product_category')->where('id', $value)->value('child_ids');
                $child_ids .= ',' . $id;
                Db::name('product_category')->where('id', $value)->update(['child_ids' => $child_ids]);
            }
        }
    }

    public function update($params = [], $id)
    {
        Db::startTrans();
        try {
            $category = Db::name('product_category')->where('id', $id)->find();
            $old_parent_ids = $this->getParentIds($id);
            $old_child_ids = $category['child_ids'];
            $data = $this->setCreateUpdateData($params);
            Db::name('product_category')->where('id', $id)->update($data);
            if ($category['parent_id'] != $params['parent_id']) $this->updateAssist($id, $old_parent_ids, $old_child_ids);
            Db::commit();
            return arraySuccess();
        } catch (\Throwable $th) {
            Db::rollback();
            return arrayFailed($th->getMessage());
        }
    }

    private function updateAssist($id, $old_parent_ids, $old_child_ids)
    {
        $old_child_ids_array = explode(',', $old_child_ids);

        // 旧上级分类更新下级分类集
        foreach ($old_parent_ids as $key => $value) {
            if ($value == $id) continue;
            $child_ids = Db::name('product_category')->where('id', $value)->value('child_ids');
            $child_ids = explode(',', $child_ids);
            $child_ids = array_merge(array_diff($child_ids, $old_child_ids_array));
            $child_ids = implode(',', $child_ids);
            Db::name('product_category')->where('id', $value)->update(['child_ids' => $child_ids]);
        }

        // 新上级分类更新下级分类集
        $new_parent_ids = $this->getParentIds($id);
        foreach ($new_parent_ids as $key => $value) {
            if ($value == $id) continue;
            Db::name('product_category')->where('id', $value)->update(['child_ids' => Db::raw('concat(child_ids, ",'.$old_child_ids.'")')]);
        }

        // 旧下级分类更新上级分类集
        foreach ($old_child_ids_array as $key => $value) {
            $parent_ids = Db::name('product_category')->where('id', $value)->value('parent_ids');
            $parent_ids = implode(',', $new_parent_ids) . str_replace($id, '', strstr($parent_ids, $id));
            Db::name('product_category')->where('id', $value)->update(['parent_ids' => $parent_ids]);
        }
    }

    private function setCreateUpdateData($params = [])
    {
        $data = [];
        if (isset($params['parent_id'])) $data['parent_id'] = $params['parent_id'];
        if (isset($params['name'])) $data['name'] = $params['name'];
        if (isset($params['keyword'])) $data['keyword'] = $params['keyword'];
        if (isset($params['description'])) $data['description'] = $params['description'];
        $data['wxapp_cover'] = isset($params['wxapp_cover']) ? $params['wxapp_cover'] : '';
        if (isset($params['sort'])) $data['sort'] = $params['sort'];
        if (isset($params['status'])) $data['status'] = $params['status'];
        return $data;
    }

    public function delete($id)
    {
        if (Db::name('product_category')->where('parent_id', $id)->find()) return arrayFailed('请先删除该分类下的子分类');
        if (Db::name('product')->where('category_id', $id)->find()) return arrayFailed('请先移除该分类下的产品');
        Db::startTrans();
        try {
            $this->deleteAssist($id);
            Db::name('product_category')->whereIn('id', $id)->delete();
            Db::commit();
            return arraySuccess();
        } catch (\Throwable $th) {
            Db::rollback();
            return arrayFailed();
        }
    }

    private function deleteAssist($id)
    {
        // 上级分类更新下级分类集
        $parent_ids = $this->getParentIds($id);
        foreach ($parent_ids as $key => $value) {
            if ($value == $id) continue;
            $child_ids = Db::name('product_category')->where('id', $value)->value('child_ids');
            $array = explode(',', $child_ids);
            array_splice($array, array_search($id, $array), 1);
            $child_ids = implode(',', $array);
            Db::name('product_category')->where('id', $value)->update(['child_ids' => $child_ids]);
        }
    }

    public function getCategoryChildIds($id, $type = 2)
    {
        $child_ids = Db::name('product_category')->where('id', $id)->value('child_ids');
        if ($type == 2) $child_ids = explode(',', $child_ids);
        return $child_ids;
    }

    public function getCategoryParentIds($id, $type = 2)
    {
        $parent_ids = Db::name('product_category')->where('id', $id)->value('parent_ids');
        if ($type == 2) $parent_ids = explode(',', $parent_ids);
        return $parent_ids;
    }

	public function getChildIds($id, $i = 0)
	{
        $child_ids = Db::name('product_category')->where('parent_id', $id)->column('id');
        if ($i == 0) {
            $this->child_ids = [];
            $this->child_ids[] = (int)$id;
        }
        $this->child_ids = array_merge($this->child_ids, $child_ids);
		if (!empty($child_ids)) {
			foreach ($child_ids as $key => $value) {
				$i++;
				$this->getChildIds($value, $i);
			}
		}
		return $this->child_ids;
    }

    public function getParentIds($id, $i = 0)
    {
        $parent_id = Db::name('product_category')->where('id', $id)->value('parent_id');
        if ($i == 0) {
            $this->parent_ids = [];
            $this->parent_ids[] = (int)$id;
        }
        if ($parent_id != 0) {
            array_unshift($this->parent_ids, $parent_id);
            $i++;
            $this->getParentIds($parent_id, $i);
        }
        return $this->parent_ids;
    }
}