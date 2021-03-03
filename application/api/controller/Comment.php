<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Api Comment Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\api\controller;

use think\Request;
use think\Db;
use app\repository\CommentRepository;

class Comment extends Base
{
    protected $middleware = [ 
    	'CheckUserLogin' => ['only' => ['create', 'zan']],
    ];

    public function create(Request $request)
    {
        Db::startTrans();
        try {
            $res = $this->validate($request->param(), 'Comment.create');
            if ($res !== true) return jsonFailed($res);
            $user = getUser();
            $params = $request->param();
            if (isset($params['shop'])) {
                $shopParams = [];
                $shopParams['shop_id'] = $params['shop']['shop_id'];
                $shopParams['order_id'] = $params['order_id'];
                $shopParams['user_id'] = $user['id'];
                $shopParams['star1'] = $params['shop']['star1'];
                $shopParams['star2'] = $params['shop']['star2'];
                $shopParams['star3'] = $params['shop']['star3'];
                $shopParams['anonymity'] = isset($params['anonymity']) ? 1 : 0;
                app(CommentRepository::class)->createShop($shopParams);
            }
            if (isset($params['products'])) {
                $productParams = [];
                foreach ($params['products'] as $key => $value) {
                    if (!empty($value['star'])) {
                        $productParams['product_id'] = $value['product_id'];
                        $productParams['order_id'] = $params['order_id'];
                        $productParams['user_id'] = $user['id'];
                        $productParams['star'] = $value['star'];
                        $productParams['content'] = $value['comment'];
                        $productParams['anonymity'] = isset($params['anonymity']) ? 1 : 0;
                        $images = isset($value['images']) ? $value['images'] : [];
                        app(CommentRepository::class)->createProduct($productParams, $images);
                    }
                }
            }
            Db::commit();
            if (app(CommentRepository::class)->checkCommentEnd($params['order_id'], $user['id'])) {
                Db::name('order')->where('id', $params['order_id'])->update(['is_comment' => 1]);
            }
            return jsonSuccess();
        } catch (\Throwable $th) {
            Db::rollback();
            return jsonFailed($th->getMessage());
        }
    }

    public function getShopScore(Request $request)
    {
        $res = $this->validate($request->param(), 'Comment.getShopScore');
        if ($res !== true) return jsonFailed($res);
        $data = app(CommentRepository::class)->getShopScore($request->shop_id);
        return jsonSuccess($data);
    }

    public function getProductScore(Request $request)
    {
        $res = $this->validate($request->param(), 'Comment.getProductScore');
        if ($res !== true) return jsonFailed($res);
        $data = app(CommentRepository::class)->getProductScore($request->product_id);
        return jsonSuccess($data);
    }

    public function getProductCommentCount(Request $request)
    {
        $res = $this->validate($request->param(), 'Comment.getProductCommentCount');
        if ($res !== true) return jsonFailed($res);
        $data = app(CommentRepository::class)->getProductCommentCount($request->product_id);
        return jsonSuccess($data);
    }

    /**
     * Get Product Comments
     * @param
     * @return
     */
    public function getProductComments(Request $request)
    {
        $res = $this->validate($request->param(), 'Comment.getProductComments');
        if ($res !== true) return jsonFailed($res);
        $params = [];
        $params['product_id'] = $request->product_id;
        $params['type'] = $request->type;
        if (getUser()) $params['user_id'] = getUser()['id'];
        $data = app(CommentRepository::class)->getProductComments_page($params);
        return jsonSuccess($data);
    }

    public function zan(Request $request)
    {
        $res = $this->validate($request->param(), 'Comment.zan');
        if ($res !== true) return jsonFailed($res);
        return app(CommentRepository::class)->zanProduct($request->comment_id, getUser()['id']);
    }
}
