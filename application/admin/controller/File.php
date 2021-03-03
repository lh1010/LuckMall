<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利。
 * ----------------------------------------------------------------------------
 * Admin File Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\admin\controller;

use think\Request;
use think\Image;
use think\facade\Env;
use app\repository\admin\FileRepository;

class File extends Base
{
    // 多文件上传
    public function uploads(Request $request)
    {
        $path = 'images/';
        $path .=  !empty($request->param('dir')) ? $request->param('dir').'/' : '';
        $files = $request->file('files');
        if (!$files) return jsonFailed();
        $image_urls = [];
        foreach($files as $file){
            $file_name = '';
            $file_name = $file->getInfo()['name'];
            $file_name = substr($file_name, 0, strripos($file_name, '.')).'[luck'.time().'luck]';
            $info = $file->move($path, $file_name);
            if ($info) {
                $image_url = $path.$info->getSaveName();
                $image_url = str_replace('\\','/', $image_url);
                $image_urls[] = $image_url;
            }
        }
        if (empty($image_urls)) return jsonFailed('上传失败');
        return jsonSuccess($image_urls);
    }

    // 单文件上传
    public function upload(Request $request)
    {
        $path = 'images/';
        $path .=  !empty($request->param('dir')) ? $request->param('dir').'/' : '';
        $file = $request->file('file');
        if ($file) {
            $file_name = $file->getInfo()['name'];
            $file_name = substr($file_name, 0, strripos($file_name, '.')).'[luck'.time().'luck]';
            $info = $file->move($path, $file_name);
            if ($info) {
                $image_url = $path.$info->getSaveName();
                $image_url = str_replace('\\','/', $image_url);
                return jsonSuccess($image_url);
            } else {
                return jsonFailed($file->getError());
            }
        }
    }

    // 创建文件
    public function createFolder(Request $request)
    {
        $res = $this->validate($request->param(), 'Upload.createFolder');
        if($res !== true) return jsonFailed($res);
        $path = 'images/';
        $path .=  !empty($request->post('dir')) ? $request->post('dir').'/' : '';
        $path .= $request->post('new_dir');
        if (is_dir($path)) return jsonFailed('文件夹已存在');
        try {
            mkdir($path, 0777);
            return jsonSuccess();
        } catch (\Throwable $th) {
            return jsonFailed('创建失败，请检查是否存在特殊字符');
        }
    }

    // 文件管理
    public function image(Request $request)
    {
        $data = app(FileRepository::class)->getImagesPaginate($request->param(), $page_size = 20);
        $this->assign('data', $data);
        return $this->fetch();
    }

    // 文件调取使用
    public function fileManager(Request $request)
    {
        $data = app(FileRepository::class)->getImagesPaginate($request->param());
        $this->assign('data', $data);
        return $this->fetch();
    }

    // 删除文件/文件夹
    public function delete(Request $request)
    {
        try {
            foreach ($request->param('filepaths') as $key => $value) {
                $filepath = str_replace('\\', '/', Env::get('root_path').Env::get('ds').'public/images/').$value;
                deleteFile($filepath);
            }
            return jsonSuccess();
        } catch (\Throwable $th) {
            return jsonFailed($th->getMessage());
        } 
    }
}