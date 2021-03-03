<?php
/**
 * luck
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利。
 * 网站地址: luck.study
 * ----------------------------------------------------------------------------
 * 管理员
 * ============================================================================
 * Author: Jasper   
 */

namespace app\admin\controller;
use app\admin\model\Node as NodeModel;

class Node extends Base
{
    public function index()
    {
        $nodes = NodeModel::Field()->where('module_name', 'like', '%'.$this->request->param('keyword').'%')->paginate();
        $this->assign('count', $nodes->toArray()['total']);
        $this->assign("nodes", $nodes);
        return $this->fetch();
    }

    public function create()
    {
        return $this->fetch();
    }

    public function store()
    {
        if($this->validate($this->request->param(), 'Node.store') !== true){
            return $this->failed_json($this->validate($this->request->param(), 'Node.store'));
        }

        $data = $this->request->param();

        $res = NodeModel::create($data);
        if($res) {
            return $this->success_json();
        } else {
            return $this->failed_json();
        }
    }

    public function del()
    {
        if($this->validate($this->request->param(), 'Node.del') !== true){
            return $this->failed_json($this->validate($this->request->param(), 'Node.del'));
        }

        $res = NodeModel::where('id', $this->request->param('id'))->update(['status'=>99]);
        if($res) {
            return $this->success_json();
        } else {
            return $this->failed_json();
        }
    }

    public function edit()
    {
        if($this->validate($this->request->param(), 'Node.edit') !== true){
            return $this->failed_json($this->validate($this->request->param(), 'Node.edit'));
        }

        $node = NodeModel::find($this->request->param('id'));
        $this->assign("node", $node);
        return $this->fetch();
    }

    public function update()
    {
        if($this->validate($this->request->param(), 'Node.update') !== true){
            return $this->failed_json($this->validate($this->request->param(), 'Node.update'));
        }
        $data = $this->request->param();
        $data['update_time'] = \Tool::getNowTime();
        $res = NodeModel::where('id', $this->request->param('id'))->update($data);
        if($res) {
            return $this->success_json();
        } else {
            return $this->failed_json();
        }
    }

}
