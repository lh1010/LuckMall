<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Article Repository
 * ============================================================================
 * Author: Jasper   
 */

namespace app\repository;

use think\Db;

class ArticleRepository
{
    /**
     * Get Article
     * @param int $article_id
     * @return array article
     */
    public function getArticle($article_id)
    {
        $select = 'article.*, article_category.type';
        $query = Db::name('article')->alias('article');
        $query->field($select);
        $query->leftJoin('article_category', 'article_category.id = article.category_id');
        $query->where('article.id', $article_id);
        $query->where('article.status', 1);
        $article = $query->find();
        return $article;
    }

    /**
     * Get Article Categorys
     * @param string $params['type']
     * @return array
     */
    public function getCategorys($params = [])
    {
        $query = Db::name('article_category');
        $query->order('sort', 'desc');
        $this->setGetCategorysParams($query, $params);
        $categorys = $query->select();
        if (empty($categorys)) return $categorys;
        $category_ids = array_column($categorys, 'id');
        $articles = Db::name('article')->where('status', 1)->whereIn('category_id', $category_ids)->order('sort', 'desc')->select();
        $array = [];
        foreach ($articles as $key => $value) {
            $array[$value['category_id']][] = $value;
        }
        foreach ($categorys as $key => $value) {
            $categorys[$key]['articles'] = isset($array[$value['id']]) ? $array[$value['id']] : [];
        }
        return $categorys;
    }

    private function setGetCategorysParams($query, $params = [])
    {
        if (isset($params['type'])) $query->where('type', $params['type']);
        if (isset($params['status'])) $query->where('status', $params['status']);
        if (isset($params['limit'])) $query->limit($params['limit']);
    }
}