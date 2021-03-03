<?php

function getUser()
{
	return app(\app\repository\AccountRepository::class)->getLoginUser($token = Request()->param('_token'), $login_type = 'wxapp');
}

function getUserId()
{
	$user = getUser();
	$user_id = !empty($user) ? $user['id'] : '';
	return $user_id;
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
