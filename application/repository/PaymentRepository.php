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

namespace app\repository;

use think\Db;
use app\repository\UserRepository;

class PaymentRepository
{
    /**
     * 支付入口
     * @param $payment_id 支付方式
     * @param int $params['user_id']
     * @param int $params['order_id']
     * @param float $params['total_price']
     * @return array 
     */
    public function pay($payment_id, $params = [])
    {
        // create payment log
        $payment_log = [
            'payment_id' => $payment_id,
            'order_id' => $params['order_id'],
            'total_price' => $params['total_price'],
            'user_id' => $params['user_id'],
            'subject' => Config('app.app_name') . '产品'
        ];
        $res = $this->createPaymentLog($payment_log);
        if ($res['code'] != 200) return arrayFaile('支付记录创建失败');
        $params = $res['data'];

        // 调度支付
        switch ($payment_id) {
            // 余额支付
            case 4:
                $res = $this->wallet($params);
                break;
            // 微信支付 小程序
            case 3:
                $res = $this->weixinpay_jsapi($params);
                break;
            // 微信支付 公众号
            case 2:
                $qrCode = $this->weixinpay_native($params);
                $res['qrCode'] = $qrCode;
                $res['payment_log'] = $params;
                break;
            // 支付宝
            case 1:
                $res = $this->alipay_web($params);
                break;       
            default:
                return arrayFailed('不支持的支付方式');
                break;
        }
        return $res;
    }

    /**
     * 余额支付
     * @param float $params['total_price']
     * @param int $params['user_id']
     * @param int $params['order_id']
     */
    public function wallet($params)
    {
        if (!Db::name('user')->where('id', $params['user_id'])->setDec('wallet', $params['total_price'])) return arrayFailed();
        $res = app(PaymentRepository::class)->paySuccess($payment_log_id = $params['id']);
        if ($res['code'] != 200) return arrayFailed();
        return arraySuccess(['order_id' => $params['order_id']]);
    }

    /**
     * 支付宝网页端支付
     * @param string $params['number']
     * @param float $params['total_price']
     * @param string $params['subject']
     * @return html
     */
    public function alipay_web($params)
    {
        $config = Config('payment.1.config');
        $returnUrl = url('account/order');
        $notifyUrl = Config('system.app_url') . '/index/payment/alipay_notify';
        $outTradeNo = $params['number'];
        $payAmount = $params['total_price'];
        $subject = $params['subject'];
        $aliPay = new \payment\AliPay\pc();
        $aliPay->setAppid($config['appid']);
        $aliPay->setReturnUrl($returnUrl);
        $aliPay->setNotifyUrl($notifyUrl);
        $aliPay->setRsaPrivateKey($config['rsaPrivateKey']);
        $aliPay->setTotalFee($payAmount);
        $aliPay->setOutTradeNo($outTradeNo);
        $aliPay->setSubject($subject);
        $sHtml = $aliPay->goPay();
        return $sHtml;
    }

    /**
     * 支付宝支付 异步通知
     * @return string success|failed
     */
    public function alipay_notify()
    {   
        try {
            $res = false;
            $log = '';
            $config = Config('payment.1.config');
            $aliPay = new \payment\AliPay\notify();
            $aliPay->setAlipayPublicKey($config['alipayPublicKey']);
            if (!Request()->post() || !Request()->has('sign')) exit('error');
            $res = $aliPay->rsaCheck(Request()->post(), Request()->sign_type);
            // 验证 asin 失败
            if (!$res) exit('error');
            // 支付失败
            if (Request()->trade_status != 'TRADE_SUCCESS') {
                $log .= "message: trade_status not TRADE_SUCCESS\n";
                $log .= "res: ".json_encode($res);
                logs($log, 'payment/alipay');
                exit('error');
            }
            // 订单匹配 防止异常请求
            $payment_log = $this->getPaymentLog(['number' => Request()->out_trade_no, 'status' => 0]);
            if (empty($payment_log)) exit('error');
            // 总价是否匹配 防止异常请求
            if ($payment_log['total_price'] != Request()->total_amount) exit('error');
            // 执行paySuccess
            $update_data['trade_no'] = Request()->trade_no;
            $res = app(PaymentRepository::class)->paySuccess($payment_log['id'], $update_data);
            if ($res['code'] != 200) {
                $log .= "type: pay success error\n";
                $log .= "res: ".json_encode($res);
                logs($log, 'payment/alipay');
            }
            echo 'success';
        } catch (\Throwable $th) {
            $log .= "type: exceptional\n";
            $log .= "message: ".$th->getMessage()."\n";
            $log .= "params: ".json_encode(Request()->param());
            logs($log, 'payment/alipay');
            exit('error');
        }
    }

    /**
     * 微信支付 扫码支付
     * @param string $params['number']
     * @param float $params['total_price']
     * @param string $params['subject']
     * @return 二维码
     */
    public function weixinpay_native($params)
    {
        $config = Config('payment.2.config');
        $wxPay = new \payment\WeixinPay\native($config['mchid'], $config['appid'], $config['apikey']);
        $outTradeNo = $params['number'];
        $payAmount = $params['total_price'];
        $orderName = $params['subject'];
        $notifyUrl = Config('system.app_url').'/index/payment/weixinpay_notify_mp';
        $payTime = time();
        $array = $wxPay->createJsBizPackage($payAmount, $outTradeNo, $orderName, $notifyUrl, $payTime);
        $qrCode = 'http://api.k780.com:88/?app=qr.get&data='.$array['code_url'];
        return $qrCode;
    }

    /**
     * 微信支付 统一下单 小程序
     * @param string $params['number']
     * @param float $params['total_price']
     * @param string $params['subject']
     * @param int $params['order_id']
     * @return array
     * @return int order_id
     * @return array jsApiParams
     */
    public function weixinpay_jsapi($params)
    {
        $config = Config('payment.3.config');
        $wxPay = new \payment\WeixinPay\jsapi($config['mchid'], $config['appid'], $config['appsecret'], $config['apikey']);
        // 使用存储的wx_openid
        $thirdlogin = app(UserRepository::class)->getThirdAccount(['user_id' => $params['user_id']]);
        if (empty($thirdlogin)) return arrayFailed('用户信息中缺少微信openid标识'); $openId = $thirdlogin['openid'];
        // 组装发送数据
        $outTradeNo = $params['number'];
        $payAmount = $params['total_price']; // 付款金额，单位:元
        $orderName = $params['subject'];
        $notifyUrl = Config('app.app_url').'/index/payment/weixinpay_notify_app';
        $payTime = time(); // 付款时间
        $jsApiParams = $wxPay->createJsBizPackage($openId, $payAmount, $outTradeNo, $orderName, $notifyUrl, $payTime);
        return arraySuccess(['order_id' => $params['order_id'], 'jsApiParams' => $jsApiParams]);
    }

    /**
     * 微信支付 异步通知
     * @param string $type app|mp
     * @return string success|failed
     */
    public function weixinpay_notify($type)
    {
        try {
            $res = false;
            $log = '';
            if ($type == 'mp') {
                $config = Config('payment.2.config');
            }
            if ($type == 'app') {
                $config = Config('payment.3.config');
            }
            $wxPay = new \payment\WeixinPay\notify($config['mchid'], $config['appid'], $config['apikey']);
            $res = $wxPay->notify();
            // 校验失败
            if (!$res) exit('error');
            // 支付失败
            if ($res['return_code'] != 'SUCCESS') {
                $log .= "message: return_code not SUCCESS\n";
                $log .= "res: ".json_encode($res);
                logs($log, 'payment/weixinpay');
                exit('error');
            }
            // 订单匹配 防止异常请求
            $payment_log = $this->getPaymentLog(['number' => $res['out_trade_no'], 'status' => 0]);
            if (empty($payment_log)) exit('error');
            // 总价是否匹配 防止异常请求
            if ($payment_log['total_price'] != $res['total_fee'] / 100) exit('error');
            // 执行paySuccess
            $update_data['trade_no'] = $res['transaction_id'];
            $res = app(PaymentRepository::class)->paySuccess($payment_log['id'], $update_data);
            if ($res['code'] != 200) {
                $log .= "type: pay success error\n";
                $log .= "res: ".json_encode($res);
                logs($log, 'payment/weixinpay');
            }
            echo 'success';
        } catch (\Throwable $th) {
            $log .= "type: exceptional\n";
            $log .= "message: ".$th->getMessage()."\n";
            $log .= "params: ".json_encode(Request()->param());
            logs($log, 'payment/weixinpay');
            exit('error');
        }
    }

    /**
     * 支付成功
     * @param int $payment_log_id
     * @param array $update_data 三方平台唯一标识
     * @param array
     */
    public function paySuccess($payment_log_id, $update_data = [])
    {
        Db::startTrans();
        try {
            $payment_log = Db::name('payment_log')->where('id', $payment_log_id)->where('status', 0)->find();
            if (empty($payment_log)) return arrayFailed('请勿重复支付');
            $update_data['status'] = 1;
            Db::name('payment_log')->where('id', $payment_log_id)->update($update_data);
            Db::name('order')->where('id', $payment_log['order_id'])->update(['status' => 20]);
            $snaps = Db::name('order_snap')->where('order_id', $payment_log['order_id'])->select();
            foreach ($snaps as $key => $value) {
                Db::name('product_sku')->where('sku', $value['sku'])->setInc('sale', $value['count']);
            }
            Db::commit();
            return arraySuccess();
        } catch (\Throwable $th) {
            Db::rollback();
            return arrayFailed();
        }
    }

    /**
     * create payment log
     * @param int $params['order_id']
     * @param float $params['total_price']
     * @param int $params['user_id']
     * @param int $params['payment_id']
     * @param int $params['status']
     * @return array
     */
    public function createPaymentLog($params)
    {
        $data = $this->setCreateUpdatePaymentLogData($params);
        $data['id'] = Db::name('payment_log')->insertGetId($data);
        return arraySuccess($data);
    }

    private function setCreateUpdatePaymentLogData($params = [])
    {
        $data['number'] = date('YmdHis').time();
        if (isset($params['order_id'])) $data['order_id'] = $params['order_id'];
        if (isset($params['total_price'])) $data['total_price'] = $params['total_price'];
        if (isset($params['subject'])) $data['subject'] = $params['subject'];
        if (isset($params['body'])) $data['body'] = $params['body'];
        if (isset($params['user_id'])) $data['user_id'] = $params['user_id'];
        if (isset($params['payment_id'])) $data['payment_id'] = $params['payment_id'];
        if (isset($params['trade_no'])) $data['trade_no'] = $params['trade_no'];
        if (isset($params['status'])) $data['status'] = $params['status'];
        return $data;
    }

    /**
     * Get Payment Log
     * @param int $params['id']
     * @param string $params['number']
     * @param int $params['order_id']
     * @param int $params['user_id']
     * @param int $params['status']
     * @return array
     */
    public function getPaymentLog($params = [])
    {
        $query = Db::name('payment_log');
        if (isset($params['id']) && !empty($params['id'])) $query->where('id', $params['id']);
        if (isset($params['number']) && !empty($params['number'])) $query->where('number', $params['number']);
        if (isset($params['order_id']) && !empty($params['order_id'])) $query->where('order_id', $params['number']);
        if (isset($params['user_id']) && !empty($params['user_id'])) $query->where('user_id', $params['user_id']);
        if (isset($params['status'])) $query->where('status', $params['status']);
        $query->order('id desc');
        $payment_log = $query->find();
        return $payment_log;
    }

    /**
     * 验证订单是否支付成功
     * @param int $order_id
     * @param int $user_id
     * @return array 200|400
     */
    public function check_is_pay($order_id, $user_id)
    {
        $status = Db::name('order')->where('id', $order_id)->where('user_id', $user_id)->value('status');
        if ($status != 20) return arrayFailed('支付未成功');
        return arraySuccess(); 
    }
}