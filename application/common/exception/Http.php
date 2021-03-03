<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * 异常处理
 * GET: 404显示404页面，其余异常均显示500页面
 * POST: 异常统一返回Server Error
 * 错误日志: 除404外，均写入错误日志
 * ============================================================================
 * Author: Jasper
 */

namespace app\common\exception;

use Exception;
use think\exception\Handle;
use think\exception\HttpException;

class Http extends Handle
{
    public function render(Exception $e)
    {
        // debug开启时，输出错误信息
        if (Config('app.app_debug')) {
            Config('exception_tmpl', env('think_path') . 'tpl/think_exception.tpl');
            return parent::render($e);
        }
        
        // 500
        if (!$e instanceof HttpException) {
            $errorLog = '';
            $errorLog .= "errorCode：500";
            $errorLog .= "\nerrorMessage：".$e->getMessage();
            $errorLog .= "\nip：".request()->ip();
            $errorLog .= "\nurl：".request()->url(true);
            $errorLog .= "\nmethod：".request()->method();
            !empty(request()->param()) ? $errorLog .= "\nparam：".json_encode(request()->param()) : null;
            if (request()->isAjax()) $errorLog .= "\najax：true";
            // write log
            logs($errorLog);
        }
        
        // Ajax提交，统一返回Server Error
        if (request()->isAjax() || request()->isPost() || request()->module() == 'wxapi') return jsonFailed('Server Error');

        return parent::render($e);
    }
}