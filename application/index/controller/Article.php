<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Article Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\index\controller;

use think\Request;
use app\repository\ArticleRepository;

class Article extends Base
{
    public function show($article_id, Request $request)
    {
        $article = app(ArticleRepository::class)->getArticle($article_id);
        if (empty($article)) abort(404);
        $this->assign('article', $article);
        return $this->fetch();
    }

    public function help_show($article_id, Request $request)
    {
        $article = app(ArticleRepository::class)->getArticle($article_id);
        if (empty($article)) abort(404);
        $categorys = getHelp();
        $this->assign('article', $article);
        $this->assign('categorys', $categorys);
        return $this->fetch();
    }
}
