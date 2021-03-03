<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Sms Repository
 * ============================================================================
 * Author: Jasper
 */

namespace app\repository;

use think\Db;
use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

class SmsRepository
{
    private $config;

    public function __construct()
    {
        $this->config = Config('sms.');
    }

    /**
     * Send Message
     * @param string $params['type'] login|register|security_validate|set_phone
     * @param int $params['phone']
     * @param string $params['code']
     * @return array
     */
    public function send($params)
    {
        $this->accessKeyClient();
        try {
            switch ($params['type']) {
                case 'login':
                    $options = $this->getDefaultOptions($params);
                    break;
                case 'register':
                    $options = $this->getDefaultOptions($params);
                    break;
                case 'security_validate':
                    $options = $this->getDefaultOptions($params);
                    break;
                case 'set_phone':
                    $options = $this->getDefaultOptions($params);
                    break;                     
            }
            $query = AlibabaCloud::rpc();
            $query->product('Dysmsapi');
            $query->version($this->config['aliyun']['version']);
            $query->action('SendSms');
            $query->method('POST');
            $query->host($this->config['aliyun']['host']);
            $query->options($options);
            $result = $query->request();
            $result = $result->toArray();
            if (!isset($result['Message']) || $result['Message'] != 'OK') {
                $log = '';
                $log .= "type: ".$params['type']."\n";
                $log .= "phone: ".$params['phone']."\n";
                $log .= "result: ".json_encode($result);
                logs($log, 'message');
                return arrayFailed('发送失败');
            }
            return $this->sendSuccess($params);
        } catch (\Throwable $th) {
            $log = '';
            $log .= "type: ".$params['type']."\n";
            $log .= "phone: ".$params['phone']."\n";
            $log .= "errorMessage: ".$th->getMessage();
            logs($log, 'message');
            return arrayFailed('发送失败');
        }
    }

    /**
     * 获取默认短信模板
     * 您的验证码1234，该验证码5分钟内有效，请勿泄漏于他人！
     * Config('sms.template')[1]['tpl_code']
     * @param string $params['phone']
     * @param string $params['code']
     * @return array
     */
    private function getDefaultOptions($params)
    {
        $options = [
            'query' => [
                'RegionId' => $this->config['aliyun']['RegionId'],
                'SignName' => $this->config['signature'],
                'PhoneNumbers' => $params['phone'],
                'TemplateCode' => $this->config['template'][1]['tpl_code'],
                'TemplateParam' => "{'code':'".$params['code']."'}",
            ],
        ];
        return $options;
    }

    private function sendSuccess($params)
    {
        try {
            $data = [];
            switch ($params['type']) {
                case 'login':
                    $data['content'] = '您的验证码'.$params['code'].'，该验证码5分钟内有效，请勿泄漏于他人！';
                    $this->createSmsCode($params);
                    break;
                case 'register':
                    $data['content'] = '您的验证码'.$params['code'].'，该验证码5分钟内有效，请勿泄漏于他人！';
                    $this->createSmsCode($params);
                    break; 
                case 'security_validate':
                    $data['content'] = '您的验证码'.$params['code'].'，该验证码5分钟内有效，请勿泄漏于他人！';
                    $this->createSmsCode($params);
                    break;
                case 'set_phone':
                    $data['content'] = '您的验证码'.$params['code'].'，该验证码5分钟内有效，请勿泄漏于他人！';
                    $this->createSmsCode($params);
                    break;           
            }
            $data['phone'] = $params['phone'];
            $data['type'] = $params['type'];
            Db::name('sms_log')->insert($data);
            return arraySuccess();
        } catch (\Throwable $th) {
            return arrayFailed('Server Error');
        }
    }

    private function accessKeyClient()
    {
        $accessKeyId = $this->config['aliyun']['accessKeyId'];
        $accessSecret = $this->config['aliyun']['accessSecret'];
        AlibabaCloud::accessKeyClient($accessKeyId, $accessSecret)
                    ->regionId($this->config['aliyun']['RegionId'])
                    ->asDefaultClient();
    }

    /**
     * Create Sms Code
     * @param string $params['phone']
     * @param string $params['code']
     * @param string $params['type']
     */
    public function createSmsCode($params)
    {
        Db::startTrans();
        try {
            $data = [];
            $data['phone'] = $params['phone'];
            $data['code'] = $params['code'];
            $data['type'] = $params['type'];
            Db::name('sms_code')->where('phone', $params['phone'])->where('is_use', 0)->where('type', $params['type'])->update(['is_use' => 1]);
            Db::name('sms_code')->insert($data);
            Db::commit();
        } catch (\Throwable $th) {
            Db::rollback();
        }
    }

    /**
     * Validate Sms Code
     * @param string $phone
     * @param string $code
     * @param string $type
     * @return array
     */
    public function validateCode($phone, $code, $type)
    {
        $query = Db::name('sms_code');
        $query->where('phone', $phone);
        $query->where('code', $code);
        $query->where('type', $type);
        $query->where('is_use', 0);
        $query->order('create_time desc');
        $sms_code = $query->find();
        if (empty($sms_code)) return arrayFailed('短信验证码错误');
        $date = date('Y-m-d H:i:s', strtotime('-5minute'));
        if ($sms_code['create_time'] < $date) return arrayFailed('短信验证码已过期');
        Db::name('sms_code')->where('id', $sms_code['id'])->update(['is_use' => 1]);
        return arraySuccess();
    }

    /**
     * Get Sms Code
     * @param string $params['phone']
     * @param string $params['code']
     * @param string $params['type']
     * @return array
     */
    public function getSmsCode($params = [])
    {
        $query = Db::name('sms_code');
        if (isset($params['phone'])) $query->where('phone', $params['phone']);
        if (isset($params['code'])) $query->where('code', $params['code']);
        if (isset($params['type'])) $query->where('type', $params['type']);
        $query->where('is_use', 0);
        $query->order('create_time desc');
        return $query->find();
    }
}