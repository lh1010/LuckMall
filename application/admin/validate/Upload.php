<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Admin Upload Validate
 * ============================================================================
 * Author: Jasper 
 */

namespace app\admin\validate;

use think\Validate;

class Upload extends Validate
{
    protected $rule = [
        'new_dir' => 'require',
    ];
    
    protected $message = [
        'new_dir.require' => '文件夹名字不能为空',
    ];

    protected $scene = [
        'createFolder' => ['new_dir'],
    ];
}
