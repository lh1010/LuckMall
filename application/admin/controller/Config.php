<?php
/**
 * luck
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利。
 * 网站地址: luck.study
 * ----------------------------------------------------------------------------
 * 配置
 * ============================================================================
 * Author: Jasper   
 */

namespace app\admin\controller;
use think\Request;
use app\admin\model\Config as ConfigModel;
use app\admin\model\City;

class Config extends Base
{
    public function index()
    {
        $this->redirect('Config/system');
    }

    public function system()
    {
        $config = ConfigModel::where('name', 'system')->find();
        $system = [];
        if(!empty($config)) {
            $system = json_decode($config['data'], 1);
        }
        $this->assign('system', $system);
        return $this->fetch();
    }

    public function systemUpdateOrCreate(Request $request)
    {
        if($this->validate($this->request->param(), 'Config.systemUpdateOrCreate') !== true){
            return $this->failed_json($this->validate($this->request->param(), 'Config.systemUpdateOrCreate'));
        }

        $data['name'] = 'system';
        $data['data'] = json_encode($request->param(), 1);
        $res = ConfigModel::updateOrCreate($data);
        if($res) {
            return $this->success_json();
        } else {
            return $this->failed_json();
        }
    }

}
