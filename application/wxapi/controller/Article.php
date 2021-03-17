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

namespace app\wxapi\controller;

use think\Request;
use think\Db;

class Article extends Base
{
    public function getArticles(Request $request)
    {
        $articles = Db::name('article')->where('category_id', 3)->where('status', 1)->order('sort desc')->select();
        return jsonSuccess($articles);
    }

    public function getArticle(Request $request)
    {
        $article = Db::name('article')->where('id', $request->param('id'))->find();
        if (!empty($article)) {
            $preg = "/<img(.*?)src=\"(.*?)\"(.*?)>/is";
            if (preg_match_all($preg, $article['content'], $matches)) {
                $article['content'] = preg_replace($preg, '<img src="' . Config('app.app_url') . '$2" />', $article['content']);
            }
        }
        return jsonSuccess($article);
    }
}
