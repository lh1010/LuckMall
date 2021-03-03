<?php
/**
 * luck
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利。
 * 网站地址: luck.study
 * ----------------------------------------------------------------------------
 * 管理员验证
 * ============================================================================
 * Author: Jasper  
 */

namespace app\admin\validate;
use think\Validate;
use app\admin\model\Code;

class Node extends Validate
{
	protected $rule = [
        'id' => 'require',
		'module_name' => 'require',
        'module' => 'require',
        'action_name' => 'require',
        'action' => 'require',
    ];

    protected $message = [
        'id' => Code::E_PARAM_EMPTY.'：id',
		'module_name.require' => '控制器显示名不能为空',
		'module.require' => '控制器不能为空',
		'action_name.require' => '方法显示名不能为空',
		'action.require' => '方法不能为空',
    ];

    protected $scene = [
        'store' => ['module_name','module', 'action_name', 'action'],
        'del' => ['id'],
        'edit' => ['id'],
    ];

}
