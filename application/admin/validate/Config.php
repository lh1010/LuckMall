<?php
/**
 * luck
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利。
 * 网站地址: luck.study
 * ----------------------------------------------------------------------------
 * 配置验证
 * ============================================================================
 * Author: Jasper  
 */

namespace app\admin\validate;
use think\Validate;
use app\admin\model\Code;

class Config extends Validate
{
	protected $rule = [
        'system_name' => 'require',
		'system_url' => 'require',
    ];

    protected $message = [
		'system_name.require' => '系统名字不能为空',
		'system_url.require' => '系统URL不能为空',
    ];

    protected $scene = [
        'systemUpdateOrCreate' => ['system_name', 'system_url'],
    ];

}
