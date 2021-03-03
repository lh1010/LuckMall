<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Api Upload Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\api\controller;

use think\Request;

class Upload extends Base
{
    protected $middleware = ['CheckUserLogin'];
    
    private $rules = ['ext' => 'jpeg,jpg,png', 'size' => 1024 * 1024 * 4];

    /**
     * upload user avatar
     */
    public function userAvatar(Request $request)
    {
        $file = $request->file('avatarFile');
        if ($file) {
            $user_id = getUserId();
            $info = $file->validate($this->rules)->move('uploads/images/user/avatar/'.$user_id.'/');
            if ($info) {
                $data = [];
                $imageUrl = '/uploads/images/user/avatar/'.$user_id.'/'.$info->getSaveName();
                $imageUrl = str_replace('\\','/', $imageUrl);
                $data['image'] = Config('system.app_url').$imageUrl;
                $data['image_50x50'] = $this->crop($imageUrl, 50, 50);
                $data['image_120x120'] = $this->crop($imageUrl, 120, 120);
                return jsonSuccess($data);
            } else {
                return jsonFailed('仅支持 JPG、PNG、JPEG 格式，文件小于4MB');
            }
        }
    }

    /**
     * real name auth
     * upload user card image
     */
    public function userCard(Request $request)
    {
        $file = $request->file('cardFile');
        if ($file) {
            $user = getUser();
            $info = $file->validate($this->rules)->move('images/user/card/'.$user['id'].'/');
            if ($info) {
                $imageUrl = '/images/user/card/'.$user['id'].'/'.$info->getSaveName();
                $imageUrl = str_replace('\\','/', $imageUrl);
                return jsonSuccess($imageUrl);
            } else {
                return jsonFailed('仅支持 JPG、PNG、JPEG 格式，文件小于4MB');
            }
        }
    }

    /**
     * shop apply auth info
     */
    public function shopApplyAuthInfo(Request $request)
    {
        $file = $request->file('file');
        if ($file) {
            $user = getUser();
            $info = $file->validate($this->rules)->move('images/user/authInfo/'.$user['id'].'/');
            if ($info) {
                $imageUrl = '/images/user/authInfo/'.$user['id'].'/'.$info->getSaveName();
                $imageUrl = str_replace('\\','/', $imageUrl);
                return jsonSuccess($imageUrl);
            } else {
                return jsonFailed('仅支持 JPG、PNG、JPEG 格式，文件小于4MB');
            }
        }
    }

    /**
     * upload comment image
     */
    public function comment(Request $request)
    {
        $file = $request->file('file');
        if ($file) {
            $user = getUser();
            $info = $file->validate($this->rules)->move('images/comment/'.$user['id'].'/');
            if ($info) {
                $imageUrl = '/images/comment/'.$user['id'].'/'.$info->getSaveName();
                $imageUrl = str_replace('\\','/', $imageUrl);
                return jsonSuccess($imageUrl);
            } else {
                return jsonFailed('仅支持 JPG、PNG、JPEG 格式，文件小于4MB');
            }
        }
    }

    /**
     * crop image
     * @param string $imgPath
     * @param string crop image path
     */
    public function crop($imgPath, $width = 50, $height = 50)
    {
        $path = substr($imgPath, 0, strrpos($imgPath, '.')).'_'.$width.'x'.$height.'.png';
        $image = \think\Image::open('.'.$imgPath);
        $image->thumb($width, $height, \think\Image::THUMB_SCALING)->save('.'.$path);
        return $path;
    }
}
