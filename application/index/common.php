<?php

use think\facade\Cookie;

function getUser()
{
	return app(\app\repository\AccountRepository::class)->getLoginUser($token = Cookie::get('_token'), $login_device = 'web');
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

// 获取帮助文档
function getHelp()
{
	$categorys = Db::name('article_category')->where('parent_id', 1)->where('status', 1)->select();
	$category_id = array_column($categorys, 'id');
	$articles = Db::name('article')->whereIn('category_id', $category_id)->where('status', 1)->select();
	$array = [];
	foreach ($articles as $key => $value) {
		$array[$value['category_id']][] = $value;
	}
	foreach ($categorys as $key => $value) {
		$categorys[$key]['articles'] = isset($array[$value['id']]) ? $array[$value['id']] : [];
	}
	return $categorys;
}

// 获取导航
function getNav()
{
	$navs = Db::name('design_nav')->where('status', 1)->order('sort desc')->select();
	return $navs;
}

// 获取友情链接
function getFriendlink()
{
	$friendlinks = Db::name('friendlink')->where('status', 1)->order('sort desc')->select();
	return $friendlinks;
}

