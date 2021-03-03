<?php
/**
 * luck
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利。
 * 网站地址: luck.study
 * ----------------------------------------------------------------------------
 * 意见反馈
 * ============================================================================
 * Author: Jasper   
 */

namespace app\admin\controller;
use app\admin\model\Feedback as FeedbackModel;

class Feedback extends Base
{
    public function index()
    {
        $feedbacks = FeedbackModel::scope('field')->where(function($query) {
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

        $FeedbackModel = new FeedbackModel();
        $this->assign("typeArray", $FeedbackModel->typeArray);
        $this->assign('count', $feedbacks->toArray()['total']);
        $this->assign("feedbacks", $feedbacks);
        return $this->fetch();
    }

    public function del()
    {
        if($this->validate($this->request->param(), 'Feedback.del') !== true){
            return $this->failed_json($this->validate($this->request->param(), 'Feedback.del'));
        }

        $res = FeedbackModel::where('id', $this->request->param('id'))->update(['status'=>99]);
        if($res) {
            return $this->success_json();
        } else {
            return $this->failed_json();
        }
    }

    public function batchDel()
    {
        if($this->validate($this->request->param(), 'Feedback.batchDel') !== true){
            return $this->failed_json($this->validate($this->request->param(), 'Feedback.batchDel'));
        }

        $idArray = explode(',', $this->request->param('ids'));

        foreach ($idArray as $key => $value) {
            $res = FeedbackModel::where('id', $value)->update(['status'=>99]);
        };
        
        if($res) {
            return $this->success_json();
        } else {
            return $this->failed_json();
        }
    }
 
}
