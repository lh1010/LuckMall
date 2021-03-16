<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利。
 * ----------------------------------------------------------------------------
 * User Repository
 * ============================================================================
 * Author: Jasper   
 */

namespace app\repository;

use think\Db;
use app\repository\AccountRepository;
use Jenssegers\Agent\Agent;

class UserRepository
{
    public function getUser($id)
    {
        $user = Db::name('user')->where('id', $id)->where('status', 1)->find();
        if (empty($user)) return $user;
        $user['avatar'] = !empty($user['avatar']) ? $user['avatar'] : Config('image.user_avatar_default');
        return $user;
    }

    public function getUserWallet($user_id)
    {
        $wallet = Db::name('user')->where('id', $user_id)->where('status', 1)->value('wallet');
        return $wallet;
    }

    public function getUserIntegral($user_id)
    {
        $integral = Db::name('user')->where('id', $user_id)->where('status', 1)->value('integral');
        return $integral;
    }

    public function getUserAvatar($user_id)
    {
        $avatar = Db::name('user')->where('id', $user_id)->value('avatar');
        $avatar = !empty($avatar) ? $avatar : Config('image.wxapp_user_avatar_default');
        return $avatar;
    }

    /**
     * Create User Info
     * @param string $params['email']
     * @param string $params['phone']
     * @param string $params['password']
     * @param string $params['register_type']
     * @param string $params['face']
     * @return array
     */
    public function create($params = [])
    {
        $data = $this->setCreateUpdateData($params);
        $user_id = Db::name('user')->insertGetId($data);
        return arraySuccess(['user_id' => $user_id, 'nickname' => $data['nickname']]);
    }

    public function update($params, $id)
    {
        $query = Db::name('user');
        $data = $this->setCreateUpdateData($params);
        $query->where('id', $id)->update($data);
        return arraySuccess();
    }

    private function setCreateUpdateData($params = [])
    {
        $data = [];
        $data['nickname'] = isset($params['nickname']) && !empty($params['nickname']) ? $params['nickname'] : 'u'.time();;
        if (isset($params['email'])) $data['email'] = $params['email'];
        if (isset($params['phone'])) $data['phone'] = $params['phone'];
        if (isset($params['avatar'])) $data['avatar'] = str_replace(Config('app.app_url'), '', $params['avatar']);
        if (isset($params['sex'])) $data['sex'] = $params['sex'];
        if (isset($params['password'])) $data['password'] = $params['password'];
        if (isset($params['wx_openid'])) $data['wx_openid'] = $params['wx_openid'];
        if (isset($params['wallet'])) $data['wallet'] = $params['wallet'];
        if (isset($params['status'])) $data['status'] = $params['status'];
        return $data;
    }

    /**
     * Create User Login Log
     * @param int $user_id
     * @param string $login_device
     * @return array
     */
    public function createLoginLog($user_id, $login_device = 'web')
    {
        Db::name('user_login_log')
            ->where('user_id', $user_id)
            ->where('login_device', $login_device)
            ->where('status', 1)
            ->update(['status' => 0]);
        $data = [];
        $data['user_id'] = $user_id;
        $data['ip'] = Request()->ip();
        $data['browser'] = app(Agent::class)->browser();
        $data['browser_version'] = app(Agent::class)->version($data['browser']);
        $data['login_device'] = $login_device;
        $data['token'] = md5($user_id . time());
        Db::name('user_login_log')->insert($data);
        return $data;
    }

    /**
     * Get User Third Account Data
     * 获取用户第三方帐户数据
     * @param string $params['type']
     * @param string $params['openid']
     * @param string $params['unionid']
     * @param int $params['status']
     * @return array
     */
    public function getThirdAccount($params = [])
    {
        $query = Db::name('user_thirdlogin')->alias('user_thirdlogin');
        $query->field('user_thirdlogin.*, user.nickname, user.status as user_status');
        $query->leftjoin('user', 'user.id = user_thirdlogin.user_id');
        if (isset($params['user_id'])) $query->where('user_thirdlogin.user_id', $params['user_id']);
        if (isset($params['type'])) $query->where('user_thirdlogin.type', $params['type']);
        if (isset($params['openid'])) $query->where('user_thirdlogin.openid', $params['openid']);
        if (isset($params['unionid'])) $query->where('user_thirdlogin.unionid', $params['unionid']);
        if (isset($params['status'])) $query->where('user_thirdlogin.status', $params['status']);
        $query->where('user.status', '<>', 99);
        $thirdlogin = $query->find();
        return $thirdlogin;
    }

    /**
     * Get User Third Accounts Data
     * 获取用户第三方帐户数据
     * @param int $params['user_id']
     * @param string $params['type']
     * @param string $params['key']
     * @param int $params['status']
     * @param int $arrange 是否整理格式
     * @return array
     */
    public function getThirdAccounts($params = [], $arrange = 1)
    {
        $query = Db::name('user_thirdlogin')->alias('user_thirdlogin');
        if (isset($params['user_id'])) $query->where('user_thirdlogin.user_id', $params['user_id']);
        if (isset($params['type'])) $query->where('user_thirdlogin.type', $params['type']);
        if (isset($params['key'])) $query->where('user_thirdlogin.key', $params['key']);
        if (isset($params['status'])) $query->where('user_thirdlogin.status', $params['status']);
        $thirdlogins = $query->select();
        if (!empty($thirdlogins)) {
            foreach ($thirdlogins as $key => $value) {
                $thirdlogins[$key]['data'] = object_to_array(json_decode($value['data']));
            }
        }
        $array = $thirdlogins;
        if ($arrange == 1) {
            $array = [];
            foreach (Config('oauth.') as $key => $value) {
                foreach ($thirdlogins as $key1 => $value1) {
                    if ($key = $value1['type']) $array[$key] = $value1;
                }
            }
        }
        return $array;
    }

    /**
     * 绑定第三方账户
     * @param string $params['openid']
     * @param string $params['unionid']
     * @param string $params['type']
     * @param int $params['user_id']
     * @return array
     */
    public function bindThirdAccount($params)
    {
        $oauth = Config('oauth.' . $params['type']);
        Db::startTrans();
        try {
            if (Db::name('user_thirdlogin')->where(['openid' => $params['openid'], 'status' => 1])->where('user_id', '<>', $params['user_id'])->find()) {
                return arrayFailed('该'. $oauth['appname'] .'账号已被绑定，请登录查看');
            }
            $user_data = app(AccountRepository::class)->set_login_third_user_data($params['data'], $params['type']);
            $data = [];
            $data['user_id'] = $params['user_id'];
            $data['type'] = $params['type'];
            $data['openid'] = $params['openid'];
            $data['nickname'] = $user_data['nickname'];
            $data['data'] = json_encode($params['data']);
            // 微信客户端时存在
            if (isset($params['unionid'])) $data['unionid'] = $params['unionid'];
            if (isset($params['device'])) $data['device'] = $params['device'];
            Db::name('user_thirdlogin')->insert($data);
            Db::commit();
            return arraySuccess();
        } catch (Exception $e) {
            Db::rollback();
            return arrayFailed($oauth['appname'] . '登录失败');    
        }
    }

    /**
     * 解除绑定第三方账户
     * @param string $type
     * @param int $user_id
     * @return array
     */
    public function unbindThirdAccount($type, $user_id)
    {
        Db::name('user_thirdlogin')->where('type', $type)->where('user_id', $user_id)->update(['status' => 2]);
        return arraySuccess();
    }
}