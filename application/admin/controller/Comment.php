<?php
/**
 * luck
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利。
 * 网站地址: luck.study
 * ----------------------------------------------------------------------------
 * 评论
 * ============================================================================
 * Author: Jasper   
 */

namespace app\admin\controller;
use app\admin\model\Comment as CommentModel;


class Comment extends Base
{
    public function index()
    {
        $comments = CommentModel::scope('field')->where(function($query) {
            if($this->request->has('type') && !empty($this->request->param('type'))) {
                $query->where('type', '=', $this->request->param('type'));
            }
            if($this->request->has('start_time') && !empty($this->request->param('start_time'))) {
                $query->where('create_time', '>=', $this->request->param('start_time').' 00:00:00');
            }
            if($this->request->has('end_time') && !empty($this->request->param('end_time'))) {
                $query->where('create_time', '<=', $this->request->param('end_time').' 23:59:59');
            }
        })->paginate();

        $CommentModel = new CommentModel();
        $this->assign("typeArray", $CommentModel->typeArray);
        $this->assign('count', $comments->toArray()['total']);
        $this->assign("comments", $comments);
        return $this->fetch();
    }

    public function del()
    {
        if($this->validate($this->request->param(), 'Comment.del') !== true){
            return $this->failed_json($this->validate($this->request->param(), 'Comment.del'));
        }

        $res = CommentModel::where('id', $this->request->param('id'))->update(['status'=>99]);
        if($res) {
            return $this->success_json();
        } else {
            return $this->failed_json();
        }
    }

    public function batchDel()
    {
        if($this->validate($this->request->param(), 'Comment.batchDel') !== true){
            return $this->failed_json($this->validate($this->request->param(), 'Comment.batchDel'));
        }

        $idArray = explode(',', $this->request->param('ids'));

        foreach ($idArray as $key => $value) {
            $res = CommentModel::where('id', $value)->update(['status'=>99]);
        };
        
        if($res) {
            return $this->success_json();
        } else {
            return $this->failed_json();
        }
    }

    public function verify()
    {
        if($this->validate($this->request->param(), 'Comment.verify') !== true){
            return $this->failed_json($this->validate($this->request->param(), 'Comment.verify'));
        }

        $res = CommentModel::where('id', $this->request->param('id'))->update(['status' => $this->request->param('status'), 'update_time' => \Tool::getNowTime()]);
        if($res) {
            return $this->success_json();
        } else {
            return $this->failed_json();
        }
    }
 
}
