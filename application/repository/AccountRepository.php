<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Account Repository
 * ============================================================================
 * Author: Jasper   
 */

namespace app\repository;

use think\Db;
use app\repository\UserRepository;
use app\repository\SmsRepository;
use think\facade\Cookie;
use Jenssegers\Agent\Agent;

class AccountRepository 
{
    /**
     * register
     * @param string $params['phone']
     * @param string $params['password']
     * @return array
     */
    public function register($params = [])
    {
        Db::startTrans();
        try {
            $data = [];
            $data['phone'] = $params['phone'];
            if (isset($params['password'])) $data['password'] = md5($params['password']);
            $data['nickname'] = 'u'.time();
            $res = app(UserRepository::class)->create($data);
            $this->loginSuccess(['user_id' => $res['data']['user_id'], 'nickname' => $data['nickname']], $login_device = 'web');
            Db::commit();
            return arraySuccess();
        } catch (\Throwable $th) {
            Db::rollback();
            return arrayFailed();
        }
    }

    /**
     * account login
     * @param string $username
     * @param string $password
     * @return json
     */
    public function login($username, $password)
    {
        try {
            $query = Db::name('user');
            $query->where(function($query) use ($username) {
                $query->where('phone', $username)->whereOr('email', $username);
            });
            if (!$query->find()) return arrayFailed('账户不存在');
            $query->where(function($query) use ($username) {
                $query->where('phone', $username)->whereOr('email', $username);
            });
            $query->where('password', md5($password));
            if (!$user = $query->find()) return arrayFailed('登录密码错误');
            if ($user['status'] == 0) return arrayFailed('账户已关闭');
            $this->loginSuccess(['user_id' => $user['id'], 'nickname' => $user['nickname']], $login_device = 'web');
            return arraySuccess();
        } catch (\Throwable $th) {
            return arrayFailed('登录失败');
        }
    }

    /**
     * 动态短信验证码登录
     * @param string $phone
     * @param int $code
     * @return array
     */
    public function login_sms($phone, $code)
    {
        try {
            $res = app(SmsRepository::class)->validateCode($phone = $phone, $code = $code, $type = 'login');
            if ($res['code'] != 200) return arrayFailed($res['message']);
            if (!$user = Db::name('user')->where('phone', $phone)->find()) {
                $this->register(['phone' => $phone]);
            } else {
                if ($user['status'] == 0) return arrayFailed('该手机号已被禁用');
                $this->loginSuccess(['user_id' => $user['id'], 'nickname' => $user['nickname']], $login_device = 'web');
            }
            return arraySuccess();
        } catch (\Throwable $th) {
            return arrayFailed('登录失败');
        }
    }

    /**
     * weixin app login
     * @param string weixin openid
     * @param array $user_info 微信提供用户信息
     */
    public function wxapp_login($openid, $user_info = [])
    {
        Db::startTrans();
        try {
            $user = Db::name('user')->where('wx_openid', $openid)->find();
            if (!empty($user)) {
                if ($user['status'] == 0) return arrayFailed('该账户已关闭');
                $user_id = $user['id']; 
            } else {
                if (empty($user_info)) return arrayFailed('账户不存在');
                $data = [];
                $data['phone'] = $user_info['phoneNumber'];
                $data['nickname'] = $user_info['nickName'];
                $data['avatar'] = $user_info['avatarUrl'];
                $data['sex'] = $user_info['gender'] ? $user_info['gender'] : 0;
                $user_id = Db::name('user')->insertGetId($data);
            }
            $log = $this->loginSuccess(['user_id' => $user_id], $login_device = 'wxapp');
            Db::commit();
            return arraySuccess($log['token']);
        } catch (\Throwable $th) {
            Db::rollback();
            return arrayFailed('登录失败' . $th->getMessage());
        }
    }

    /**
     * 验证用户登录
     * @param string $token
     * @param string $login_device
     * @return array 200|400
     */
    public function checkLogin($token = '', $login_device = 'web')
    {
        if (empty($token)) return arrayFailed();
        $log = Db::name('user_login_log')->where('token', $token)->where('login_device', $login_device)->where('status', 1)->find();
        if (empty($log)) return arrayFailed();
        return arraySuccess();
    }

    /**
     * 获取登录用户信息
     * 移动端设备
     * 使用token标识
     * @param string $token
     * @param string $login_device
     */
    public function getLoginUser($token, $login_device = 'web')
    {
        $user = [];
        $log = Db::name('user_login_log')->where('token', $token)->where('login_device', $login_device)->where('status', 1)->find();
        if (empty($log)) return [];
        $user = Db::name('user')->where('id', $log['user_id'])->where('status', 1)->find();
        $user['avatar'] = !empty($user['avatar']) ? Config('app.app_url') . $user['avatar'] : Config('image.user_avatar_default');
        return $user;
    }

    /**
     * 退出登录
     * @param int $user_id
     * @param string $login_device
     * @return array
     */
    public function logout($user_id, $login_device = 'web')
    {
        Db::name('user_login_log')->where('user_id', $user_id)->where('login_device', $login_device)->where('status', 1)->update(['status' => 0]);
        if ($login_device = 'web') {
            Cookie::delete('_token');
            Cookie::delete('nickname');
        }
        return arraySuccess();
    }

    /**
     * 登录验证通过
     * 创建登录日志
     * 客户端保存cookie
     * @param string $params['user_id']
     * @param string $params['nickname'] web端时存在 客户端cookie使用
     * @param string $login_device 登录设备
     */
    private function loginSuccess($params, $login_device = 'web')
    {
        $log = app(UserRepository::class)->createLoginLog($user_id = $params['user_id'], $login_device = $login_device);
        if ($login_device = 'web') {
            Cookie::set('_token', $log['token'], Config('common.login_save_password_time'));
            Cookie::set('nickname', $params['nickname'], Config('common.login_save_password_time'));
        }
        return $log;
    }

    /**
     * OAuth2
     * @param string $params['openid']
     * @param string $params['unionid']
     * @param string $params['type']
     * @param string $params['device']
     * @param array $params['data'] 第三方提供数据
     * @param string $login_device
     * @return array
     */
    public function login_third($params, $login_device = 'web')
    {
        $oauth = Config('oauth.' . $params['type']);
        $search_params = [];
        $search_params['type'] = $params['type'];
        $search_params['status'] = 1;
        $search_params['openid'] = $params['openid'];
        $thirdAccount = app(UserRepository::class)->getThirdAccount($search_params);

        if (empty($thirdAccount)) {
            $data = [];
            if (isset($params['unionid']) && !empty($params['unionid'])) {
                $search_params['unionid'] = $params['unionid']; unset($search_params['openid']);
                $thirdAccount = app(UserRepository::class)->getThirdAccount($search_params);
                $data['unionid'] = $params['unionid'];
            }
            if (empty($thirdAccount)) {
                $user_data = $this->set_login_third_user_data($params['data'], $params['type'], $login_device, $is_upload_avatar = 1);
                $res = app(UserRepository::class)->create($user_data);
                $user_id = $res['data']['user_id'];
                $nickname = $res['data']['nickname'];
            } else {
                $user_id = $thirdAccount['user_id'];
                $nickname = $thirdAccount['nickname'];
            }
            $data['user_id'] = $user_id;
            $data['type'] = $params['type'];
            $data['openid'] = $params['openid'];
            $data['nickname'] = $nickname;
            $data['data'] = json_encode($params['data']);
            $data['device'] = $login_device;
            Db::name('user_thirdlogin')->insert($data); 
        } else {
            $user_data = $this->set_login_third_user_data($params['data'], $params['type']);
            Db::name('user_thirdlogin')->where('id', $thirdAccount['id'])->update(['nickname' => $user_data['nickname'], 'data' => json_encode($params['data'])]);
            $user_id = $thirdAccount['user_id'];
            $nickname = $thirdAccount['nickname'];
        }

        $log = $this->loginSuccess(['user_id' => $user_id, 'nickname' => $nickname], $login_device);
        return arraySuccess($log['token']);
    }

    /**
     * OAuth2.0登陆，格式化所需第三方平台用户资料
     * @param array $data 第三方平台提供用户资料
     * @param string $type 第三方平台
     * @param int $is_upload_avatar 上传头像图片
     * @param array
     */
    public function set_login_third_user_data($data, $type, $device = 'web', $is_upload_avatar = 0)
    {
        $params['nickname'] = '';
        $params['avatar'] = '';
        // 微信网页端
        if ($type == 'weixin' && $device == 'web') {
            $params['nickname'] = $data['nickname'];
            if ($is_upload_avatar = 1 && isset($data['headimgurl']) && !empty($data['headimgurl'])) $params['avatar'] = '/' . download_image($data['headimgurl'], 'uploads/images/user/avatar/weixin/');
        }
        // 微信小程序端
        if ($type == 'weixin' && $device == 'wxapp') {
            $params['nickname'] = $data['nickName'];
            $params['phone'] = $data['phoneNumber'];
            if (isset($data['gender'])) $params['sex'] = $data['gender'];
            if ($is_upload_avatar = 1 && isset($data['avatarUrl']) && !empty($data['avatarUrl'])) $params['avatar'] = '/' . download_image($data['avatarUrl'], 'uploads/images/user/avatar/weixin/');
        }
        // 微博网页端
        if ($type == 'weibo') {
            $params['nickname'] = $data['name'];
            if ($is_upload_avatar = 1 && isset($data['avatar_large']) && !empty($data['avatar_large'])) $params['avatar'] = '/' . download_image($data['avatar_large'], 'uploads/images/user/avatar/weibo/');
        }
        // QQ网页端
        if ($type == 'qq') {
            $params['nickname'] = $data['nickname'];
            if ($is_upload_avatar = 1 && isset($data['figureurl_qq']) && !empty($data['figureurl_qq'])) $params['avatar'] = '/' . download_image($data['figureurl_qq'], 'uploads/images/user/avatar/qq/');
        }
        return $params;
    }
}