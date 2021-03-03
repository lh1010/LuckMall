<?php
/**
 * luck
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利。
 * 网站地址: luck.study
 * ----------------------------------------------------------------------------
 * 评论验证
 * ============================================================================
 * Author: Jasper 
 * Date：2018-03-28     
 */

namespace app\admin\validate;
use think\Validate;
use app\admin\model\Code;

class Comment extends Validate
{

	protected $rule = [
		'id' => 'require',
        'ids' => 'require',
        'status' => 'require',
    ];

    protected $message = [
    	'id' => Code::E_PARAM_EMPTY.'：id',
        'ids.require' => Code::E_PARAM_EMPTY.'：ids',
        'status' => Code::E_PARAM_EMPTY.'：status',
    ];

    protected $scene = [
        'del' => ['id'],
        'batchDel' => ['ids'],
        'verify' => ['id', 'status'],
    ];

}
