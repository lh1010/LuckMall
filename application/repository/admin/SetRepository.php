<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * 设置中心 Repository Admin
 * ============================================================================
 * Author: Jasper
 */

namespace app\repository\admin;

use think\Db;
use think\facade\Env;

class SetRepository
{
    public function updateSystem($params = [])
    {
        $readfile_path = Env::get('config_path') . 'readfile';
        if (!file_exists($readfile_path)) mkdir($readfile_path, 0777);
        $system_path = $readfile_path . '/system.php';
        $file = fopen($system_path, "w+");
        $data = $this->setUpdateSystemData($params);
        fwrite($file, $data);
        fclose($file);
        return arraySuccess();
    }

    private function setUpdateSystemData($params)
    {
        $array = [];
        if (isset($params['app_name']) && !empty($params['app_name'])) $array['app_name'] = $params['app_name'];
        if (isset($params['app_url']) && !empty($params['app_url'])) $array['app_url'] = $params['app_url'];
        if (isset($params['app_logo']) && !empty($params['app_logo'])) $array['app_logo'] = $params['app_logo'];
        if (isset($params['qq']) && !empty($params['qq'])) $array['qq'] = $params['qq'];
        if (isset($params['wangwang']) && !empty($params['wangwang'])) $array['wangwang'] = $params['wangwang'];
        if (isset($params['phone']) && !empty($params['phone'])) $array['phone'] = $params['phone'];
        if (isset($params['work_time']) && !empty($params['work_time'])) $array['work_time'] = $params['work_time'];
        if (isset($params['address']) && !empty($params['address'])) $array['address'] = $params['address'];
        if (isset($params['copyright']) && !empty($params['copyright'])) $array['copyright'] = $params['copyright'];
        if (isset($params['beian']) && !empty($params['beian'])) $array['beian'] = $params['beian'];
        $data = '';
        $data = "<?php\n\n";
        $data .= 'return ';
        $data .= '\'';
        $data .= !empty($array) ? json_encode($array, 1) : '';
        $data .= '\'';
        $data .= ';';
        return $data;
    }

    /**
     * 创建readfile文件
     * @param array $array
     * @param string $file_name
     */
    public function crf($array, $file_name)
    {
        $data = '';
        $data = "<?php\n\n";
        $data .= 'return ';
        $data .= '\'';
        $data .= !empty($array) ? json_encode($array, 1) : '';
        $data .= '\'';
        $data .= ';';

        $readfile_path = Env::get('config_path') . 'readfile';
        if (!file_exists($readfile_path)) mkdir($readfile_path, 0777);
        $path = $readfile_path . '/' . $file_name;
        $file = fopen($path, "w+");
        fwrite($file, $data);
        fclose($file);
    }
}