<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Admin Article Controller
 * ============================================================================
 * Author: Jasper   
 */

namespace app\admin\controller;

use think\Request;
use think\Db;
use app\repository\admin\ArticleRepository;

class Article extends Base
{
    public function index(Request $request)
    {
        $this->assign("categorys", app(ArticleRepository::class)->getCategorys());
        $this->assign('articles', app(ArticleRepository::class)->getArticlesPaginate($request->get()));
        return $this->fetch();
    }

    public function create(Request $request)
    {
        $this->assign("categorys", app(ArticleRepository::class)->getCategorys());
        return $this->fetch();
    }

    public function store(Request $request)
    {
        $res = $this->validate($request->post(), 'Article.store');
        if ($res !== true) return jsonFailed($res);
        app(ArticleRepository::class)->create($request->post());
        return jsonSuccess();
    }

    public function edit(Request $request)
    {
        $article = app(ArticleRepository::class)->getArticle(['id' => $request->id]);
        if (empty($article)) abort(404);
        $this->assign('article', $article);
        $this->assign("categorys", app(ArticleRepository::class)->getCategorys());
        return $this->fetch();
    }

    public function update(Request $request)
    {
        $res = $this->validate($request->post(), 'Article.update');
        if ($res !== true) return jsonFailed($res);
        app(ArticleRepository::class)->update($request->post(), $request->post('id'));
        return jsonSuccess();
    }

    public function destory(Request $request)
    {
        $res = $this->validate($request->param(), 'Article.destory');
        if ($res !== true) return jsonFailed($res);
        Db::name('article')->whereIn('id', $request->id)->delete();
        return jsonSuccess();
    }

    public function category()
    {
        $categorys = app(ArticleRepository::class)->getCategorys();
        $this->assign('categorys', $categorys);
        return $this->fetch();
    }

    public function createCategory()
    {
        $this->assign("categorys", app(ArticleRepository::class)->getCategorys());
        return $this->fetch();
    }

    public function storeCategory(Request $request)
    {
        $res = $this->validate($request->post(), 'ArticleCategory.store');
        if ($res !== true) return jsonFailed($res);
        $data = $request->post();
        Db::name('article_category')->insert($data);
        return jsonSuccess();
    }

    public function editCategory(Request $request)
    {
        $category = app(ArticleRepository::class)->getCategory(['id' => $request->id]);
        if (empty($category)) abort(404);
        $categorys = app(ArticleRepository::class)->getCategorys();
        foreach ($categorys as $key => $value) {
            if ($value['id'] == $request->id) unset($categorys[$key]);
        }
        $this->assign('category', $category);
        $this->assign("categorys", $categorys);
        return $this->fetch();
    }

    public function updateCategory(Request $request)
    {
        $res = $this->validate($request->post(), 'ArticleCategory.update');
        if ($res !== true) return jsonFailed($res);
        $data = $request->post();
        Db::name('article_category')->where('id', $data['id'])->update($data);
        return jsonSuccess();
    }

    public function destoryCategory(Request $request)
    {
        $res = $this->validate($request->param(), 'ArticleCategory.destory');
        if ($res !== true) return jsonFailed($res);
        Db::name('article_category')->where('id', $request->id)->delete();
        return jsonSuccess();
    }
}
