<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * File Repository Admin
 * ============================================================================
 * Author: Jasper   
 */

namespace app\repository\admin;

use think\Db;
use think\facade\Env;

class FileRepository
{   
    /**
     * 获取图片空间
     * @param string $params['dir']
     */
    public function getImagesPaginate($params = [], $page_size = 16)
    {
        $page = 1;
        $page_size = $page_size;
        $prev = '';
        $data = [];
        $dirs = [];
        $files = [];
        $folder = $default_folder = str_replace('\\', '/', Env::get('root_path') . Env::get('ds') . 'public/images/');
        $current_url = url().'?token='.time();
        if (Request()->has('use_ident')) $current_url .= '&use_ident=' . Request()->get('use_ident');
        if (isset($params['dir'])) {
            $folder = $folder.$params['dir'].'/';
            if ($position = strrpos($params['dir'], '/')) {
                $prev = $current_url.'&dir='.urlencode(substr($params['dir'], 0, $position));
            } else {
                $prev = $current_url;
            }
        }
        if (isset($params['page'])) $page = $params['page'];
        $dirs = glob($folder.'*', GLOB_ONLYDIR);
        $files = glob($folder.'*.{jpg,jpeg,png,gif,JPG,JPEG,PNG,GIF}', GLOB_BRACE);
        usort($dirs, function($a, $b) {return filemtime($b) - filemtime($a);});
        usort($files, function($a, $b) {return filemtime($b) - filemtime($a);});
        $dirs_files = array_merge($dirs, $files);
        $dirs_files_total = count($dirs_files);
        $dirs_files = array_splice($dirs_files, ($page - 1) * $page_size, $page_size);
        foreach ($dirs_files as $key => $value) {
            $path = '';
            if (is_dir($value)) {
                $type = 'dir';
                $path = str_replace($default_folder, '', $value);
                $url = $current_url . '&dir=' . urlencode($path);
            } else {
                $type = 'file';
                $path = str_replace($default_folder, '', $value);
                $url = '/images/' . $path;
            }
            $temp_array = explode('/', $value);
            $name = $temp_array[(count($temp_array) - 1)];
            //$name = basename($value);
            if (preg_match_all("/\[luck([\d]+)luck\]/", $name, $array)) $name = str_replace($array[0], '', $name);
            $data['items'][] = [
                'path' => $path,
                'url' => $url,
                'name' => $name,
                'type' => $type
            ];
        }
        $pagination = new \app\repository\admin\PageRepository();
        $pagination->total = $dirs_files_total;
        $pagination->page = $page;
        $pagination->limit = $page_size;
        $pagination->url = setUrlParams(['page' => '{page}']);
        $data['pagination'] = $pagination;
        $data['prev'] = $prev;
        return $data;
    }

    public function addition_filemtime($a, $b)
    {
        return filemtime($b) - filemtime($a);
    }
}