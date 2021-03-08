<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Admin 设置中心 Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\admin\controller;

use think\Request;
use think\Db;
use think\facade\Env;
use app\repository\admin\SetRepository;

class Set extends Base
{
    // 基础配置
    public function system(Request $request)
    {
        if ($request->isPost()) {
            return app(SetRepository::class)->updateSystem($request->post());
        }
        $this->assign('system', Config('system.'));
        return $this->fetch();
    }

    // 支付方式
    public function payment($id, Request $request)
    {
        if ($request->isPost()) {
            $res = app(\app\repository\admin\PaymentRepository::class)->update($request->param());
            return json($res);
        }

        $payment = Config('payment.' . $id);
        if (empty($payment)) abort(404);
        $this->assign('payment', $payment);
        return $this->fetch();
    }

    // 互联登录
    public function oauth($id, Request $request)
    {
        if ($request->isPost()) {
            $oauths = Config('oauth.');
            $oauths[$request->param('id')]['appid'] = $request->param('appid');
            $oauths[$request->param('id')]['appkey'] = $request->param('appkey');
            $oauths[$request->param('id')]['status'] = $request->param('status');

            $data = '';
            $data = "<?php\n\n";
            $data .= 'return ';
            $data .= '\'';
            $data .= !empty($oauths) ? json_encode($oauths, 1) : '';
            $data .= '\'';
            $data .= ';';

            $readfile_path = Env::get('config_path') . 'readfile';
            if (!file_exists($readfile_path)) mkdir($readfile_path, 0777);
            $path = $readfile_path . '/oauth.php';
            $file = fopen($path, "w+");
            fwrite($file, $data);
            fclose($file);
            return arraySuccess();
        }

        $oauth = Config('oauth.' . $id);
        if (empty($oauth)) abort(404);
        $this->assign('oauth', $oauth);
        return $this->fetch();
    }

    // 短信服务
    public function sms(Request $request)
    {
        $sms = Config('sms.');
        if ($request->isPost()) {
            $sms['aliyun']['accessKeyId'] = $request->param('accessKeyId');
            $sms['aliyun']['accessSecret'] = $request->param('accessSecret');
            $sms['signature'] = $request->param('signature');
            $data = '';
            $data = "<?php\n\n";
            $data .= 'return ';
            $data .= '\'';
            $data .= !empty($sms) ? json_encode($sms, 1) : '';
            $data .= '\'';
            $data .= ';';

            $readfile_path = Env::get('config_path') . 'readfile';
            if (!file_exists($readfile_path)) mkdir($readfile_path, 0777);
            $path = $readfile_path . '/sms.php';
            $file = fopen($path, "w+");
            fwrite($file, $data);
            fclose($file);
            return arraySuccess();
        }

        $this->assign('sms', $sms);
        return $this->fetch();
    }

    // 短信模板
    public function sms_template()
    {
        $sms_template = Config('sms.template');
        $this->assign('sms_template', $sms_template);
        return $this->fetch();
    }

    // 设置短信模板code
    public function set_sms_template_code(Request $request)
    {   
        if ($request->isPost()) {
            $sms = Config('sms.');
            $sms['template'][$request->post('id')]['tpl_code'] = $request->param('tpl_code');
            app(SetRepository::class)->crf($sms, 'sms.php');
            return arraySuccess();
        }
        $sms_template = Config('sms.template.' . $request->id);
        $this->assign('sms_template', $sms_template);
        return $this->fetch();
    }

    // 邮件设置
    public function mail(Request $request)
    {
        $mail = Config('mail.');
        if ($request->isPost()) {
            $mail['Host'] = $request->param('Host');
            $mail['FromName'] = $request->param('FromName');
            $mail['From'] = $request->param('From');
            $mail['Username'] = $request->param('Username');
            $mail['Password'] = $request->param('Password');
            app(SetRepository::class)->crf($mail, 'mail.php');
            return arraySuccess();
        }
        $this->assign('mail', $mail);
        return $this->fetch();
    }

    // 应用端配置
    public function client(Request $request)
    {
        $client = Config('client.');
        if ($request->isPost()) {
            $client['wx_app']['name'] = $request->param('name');
            $client['wx_app']['appid'] = $request->param('appid');
            $client['wx_app']['secret'] = $request->param('secret');
            $client['wx_app']['qrcode'] = $request->has('qrcode') ? $request->param('qrcode') : '';
            $data = '';
            $data = "<?php\n\n";
            $data .= 'return ';
            $data .= '\'';
            $data .= !empty($client) ? json_encode($client, 1) : '';
            $data .= '\'';
            $data .= ';';
            $readfile_path = Env::get('config_path') . 'readfile';
            if (!file_exists($readfile_path)) mkdir($readfile_path, 0777);
            $path = $readfile_path . '/client.php';
            $file = fopen($path, "w+");
            fwrite($file, $data);
            fclose($file);
            return jsonSuccess();
        }
        $this->assign('client', $client);
        return $this->fetch();
    }
}