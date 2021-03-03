<?php

function topMsg($message = 'message')
{
	echo '<div style="width:100%;padding:8px 0;color:#fff;background-color:#42b943;text-align:center;font-size:14px;position:fixed;top:0;left:0;z-index:999999;">'.$message.'</div>';
	exit();
}

function topMsgJump($message = 'message', $url = '/admin/login')
{
	echo '<div style="width:100%;padding:8px 0;color:#fff;background-color:#42b943;text-align:center;font-size:14px;position:fixed;top:0;left:0;z-index:999999;">'.$message.'</div>';
	echo '<meta http-equiv="Refresh" content="2; url='.$url.'"/>';
	exit();
}

function topMsgBack($message = 'message')
{
    echo '<div style="width:100%;padding:8px 0;color:#fff;background-color:#42b943;text-align:center;font-size:14px;position:fixed;top:0;left:0;z-index:999999;">'.$message.'</div>';
    echo '<script type="text/javascript">setTimeout(function(){ history.back(); }, 1500)</script>';
    exit();
}

function topMsgReload($message = 'message')
{
	echo '<div style="width:100%;padding:8px 0;color:#fff;background-color:#42b943;text-align:center;font-size:14px;position:fixed;top:0;left:0;z-index:999999;">'.$message.'</div>';
	echo '<script type="text/javascript">setTimeout(function(){ window.location.reload(); }, 1500)</script>';
	exit();
}

/**
 * @param string $message
 * @param int $code
 * @return json
 */
function jsonFailed($message = '操作失败', $code = 400)
{
    return json(['code'=>$code, 'message'=>$message]);
}

/**
 * @param array $data
 * @param int $code
 * @return json
 */
function jsonSuccess($data = '', $code = 200, $message = '操作成功')
{
	if (!$data) {
        $data = (object)[];
    }
	return json(['code'=>$code, 'data'=>$data, 'message'=>$message]);
}

/**
 * @param string $message
 * @param int $code
 * @return array
 */
function arrayFailed($message = '操作失败', $code = 400)
{
    return ['code' => $code, 'message' => $message];
}

/**
 * @param array $data
 * @param int $code
 * @return json
 */
function arraySuccess($data = '', $code = 200, $message = '操作成功')
{
	return ['code' => $code, 'data' => $data, 'message' => $message];
}