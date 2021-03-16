<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Account Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\wxapi\controller;

use think\Request;

class Upload extends Base
{
	protected $middleware = [
        'CheckAppUserLogin' => ['only' => ['upload_user_avatar']],
    ];

    public function upload_user_avatar(Request $request)
    {
        $user_id = getUserId();
        $file = request()->file('user_avatar');
        if ($file) {
            $info = $file->move('uploads/images/user/avatar/' . $user_id . '/');
            if ($info) {
                $imagePath = '/uploads/images/user/avatar/' . $user_id . '/' . $info->getSaveName();
                $imageUrl = Config('app.app_url') . str_replace('\\','/', $imagePath);
                return jsonSuccess($imageUrl);
            } else {
                return jsonFailed('上传失败，图片尺寸不合适');
            }
        }
    }
}
