<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Payment Repository
 * ============================================================================
 * Author: Jasper   
 */

namespace app\repository\admin;

use think\Db;
use think\facade\Env;

class PaymentRepository
{
    public function update($params = [])
    {
        $payments = Config('payment.');
        // alipay
        if ($params['id'] == 1) {
            $payments[1]['config']['appid'] = $params['appid'];
            $payments[1]['config']['rsaPrivateKey'] = $params['rsaPrivateKey'];
            $payments[1]['config']['alipayPublicKey'] = $params['alipayPublicKey'];
            $payments[1]['status'] = $params['status'];
        }
        // 公众号支付
        if ($params['id'] == 2) {
            $payments[2]['config']['appid'] = $params['appid'];
            $payments[2]['config']['mchid'] = $params['mchid'];
            $payments[2]['config']['apikey'] = $params['apikey'];
            $payments[2]['status'] = $params['status'];
        }
        // 小程序支付
        if ($params['id'] == 3) {
            $payments[3]['config']['appid'] = $params['appid'];
            $payments[3]['config']['appsecret'] = $params['appsecret'];
            $payments[3]['config']['mchid'] = $params['mchid'];
            $payments[3]['config']['apikey'] = $params['apikey'];
            $payments[3]['status'] = $params['status'];
        }
        // wallet
        if ($params['id'] == 4) {
            $payments[4]['status'] = $params['status'];
        }

        $data = '';
        $data = "<?php\n\n";
        $data .= 'return ';
        $data .= '\'';
        $data .= !empty($payments) ? json_encode($payments, 1) : '';
        $data .= '\'';
        $data .= ';';

        $readfile_path = Env::get('config_path') . 'readfile';
        if (!file_exists($readfile_path)) mkdir($readfile_path, 0777);
        $path = $readfile_path . '/payment.php';
        $file = fopen($path, "w+");
        fwrite($file, $data);
        fclose($file);
        return arraySuccess();

    }
}