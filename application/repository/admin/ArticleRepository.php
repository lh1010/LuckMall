<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Article Repository Admin
 * ============================================================================
 * Author: Jasper   
 */

namespace app\repository\admin;

use think\Db;

class ArticleRepository
{
    private $treeList = [];
    public $noDeleteCategoryIds = [1];

    /**
     * Get Article
     * @param int $params['id'] article id
     * @return array article
     */
    public function getArticle($params = [])
    {
        $query = Db::name('article')->alias('article');
        if (isset($params['id']) && !empty($params['id'])) $query->where('article.id', $params['id']);
        $article = $query->find();
        return $article;
    }

    /**
     * Get Articles
     * @param array $params
     * @return object
     */
    public function getArticlesPaginate($params = [])
    {
        $select = 'article.*, article_category.name as category_name';
        $query = Db::name('article')->alias('article');
        $query->field($select);
        $this->setGetArticlesParams($query, $params);
        $query->leftJoin('article_category', 'article_category.id = article.category_id');
        $query->order('create_time', 'desc');
        $articles = $query->paginate();
        return $articles;
    }

    private function setGetArticlesParams($query, $params = [])
    {
        if (isset($params['keyword']) && !empty($params['keyword'])) $query->where('article.title', 'like', '%'.$params['keyword'].'%');
        if (isset($params['category_id']) && !empty($params['category_id'])) $query->where('article.category_id', $params['category_id']);
        if (isset($params['category_type']) && !empty($params['category_type'])) $query->where('article_category.type', $params['category_type']);
        if (isset($params['status']) && $params['status'] !== '') $query->where('article.status', $params['status']);
    }

    public function create($params = [])
    {
        $query = Db::name('article');
        $data = $this->setCreateUpdateData($params);
        $query->insert($data);
        return arraySuccess();
    }

    public function update($params, $id)
    {
        $query = Db::name('article');
        $data = $this->setCreateUpdateData($params);
        $query->where('id', $id)->update($data);
        return arraySuccess();
    }

    private function setCreateUpdateData($params = [])
    {
        $data = [];
        if (isset($params['category_id'])) $data['category_id'] = $params['category_id'];
        if (isset($params['title'])) $data['title'] = $params['title'];
        if (isset($params['author'])) $data['author'] = $params['author'];
        if (isset($params['source'])) $data['source'] = $params['source'];
        if (isset($params['keyword'])) $data['keyword'] = $params['keyword'];
        if (isset($params['description'])) $data['description'] = $params['description'];
        if (isset($params['thumbnail'])) $data['thumbnail'] = $params['thumbnail'];
        if (isset($params['content'])) $data['content'] = $params['content'];
        if (isset($params['sort'])) $data['sort'] = $params['sort'];
        if (isset($params['status'])) $data['status'] = $params['status'];
        return $data;
    }

    /**
	 * Get Categorys
	 * @param int $params['parent_id']
     * @param int $params['status']
	 * @return array
	 */
	public function getCategorys($params = [], $type = 'tree')
    {
        $query = Db::name('article_category');
        if (isset($params['status']) && !empty($params['status'])) $query->where('status', $params['status']);
        if (isset($params['type']) && !empty($params['type'])) $query->where('type', $params['type']);
        $query->order(['sort desc', 'id asc']);
        $categorys = $query->select();
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
                $this->treeList[] = $v;
                $this->tree($data, $v['id'], $level + 1);
            }
        }
        return $this->treeList;
    }

    /**
     * Get Article Categorys
     * @param array $params
     * @return object
     */
    public function getCategorysPaginate($params = [])
    {
        $query = Db::name('article_category');
        if (isset($params['status']) && !empty($params['status'])) $query->where('status', $params['status']);
        if (isset($params['type']) && !empty($params['type'])) $query->where('type', $params['type']);
        $query->order('sort', 'desc');
        $categorys = $query->paginate();
        return $categorys;
    }

    /**
     * Get Article Category
     * @param int $params['id'] article catrgory id
     * @param array article catrgory
     */
    public function getCategory($params = [])
    {
        $query = Db::name('article_category');
        if (isset($params['id']) && !empty($params['id'])) $query->where('id', $params['id']);
        if (isset($params['status']) && !empty($params['status'])) $query->where('status', $params['status']);
        $category = $query->find();
        return $category;
    }
}