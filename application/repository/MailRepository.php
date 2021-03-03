<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Mail Repository
 * ============================================================================
 * Author: Jasper   
 */

namespace app\repository;

use think\Db;
use PHPMailer\PHPMailer\PHPMailer;

class MailRepository
{
    /**
     * Send Mail
     * @param string $to 接收者邮箱地址
     * @param string $title 邮件的标题
     * @param string $content 邮件内容
     * @return boolean true|false
     */
    function sendMail($to, $title, $content)
    {
        $mail = new PHPMailer;
        // 是否启用smtp的debug进行调试
        $mail->SMTPDebug = Config('mail.SMTPDebug');
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = Config('mail.Host');
        // 设置使用ssl加密方式登录鉴权
        $mail->SMTPSecure = Config('mail.SMTPSecure');
        $mail->Port = Config('mail.Port');
        // 设置smtp的helo消息头，可有可无
        $mail->Helo = '';
        // 设置发件人的主机域 可有可无 默认为localhost 内容任意，建议使用你的域名
        $mail->Hostname = Config('mail.Hostname');
        // 设置发送的邮件的编码
        $mail->CharSet = Config('mail.CharSet');
        // 设置发件人姓名（昵称）
        $mail->FromName = Config('mail.FromName');
        // smtp登录的账号
        $mail->Username = Config('mail.Username');
        // smtp授权码
        $mail->Password = Config('mail.Password');
        // 设置发件人邮箱地址
        $mail->From = Config('mail.From');
        $mail->isHTML(true);
        // 设置收件人邮箱地址
        $mail->addAddress($to, 'this is notification');
        // 添加邮件主题
        $mail->Subject = $title;
        // 添加邮件正文
        $mail->Body = $content;
        $status = $mail->send();
        if ($status) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Send Mail Success
     * @param string $params['type'] set_email|register|security_validate
     * @param string $params['email']
     * @param string $params['code']
     * @param string $params['content']
     */
    public function sendSuccess($params = [])
    {
        Db::startTrans();
        try {
            switch ($params['type']) {
                case 'set_email':
                    $this->createMailCode($params);
                    break;
                case 'register':
                    $this->createMailCode($params);
                    break;    
                case 'security_validate':
                    $this->createMailCode($params);
                    break;          
            }
            $data = [];
            $data['email'] = $params['email'];
            $data['content'] = $params['content'];
            $data['type'] = $params['type'];
            Db::name('mail_log')->insert($data);
            Db::commit();
            return arraySuccess();
        } catch (\Throwable $th) {
            Db::rollback();
            return arrayFailed();
        }
    }

    /**
     * Create Mail Code
     * @param string $params['email']
     * @param string $params['code']
     * @param string $params['type']
     */
    public function createMailCode($params)
    {
        $data = [];
        $data['email'] = $params['email'];
        $data['code'] = $params['code'];
        $data['type'] = $params['type'];
        Db::name('mail_code')->insert($data);
    }

    /**
     * Validate Mail Code
     * @param string $email
     * @param string $code
     * @param string $type
     * @return array
     */
    public function validateCode($email, $code, $type)
    {
        $query = Db::name('mail_code');
        $query->where('email', $email);
        $query->where('code', $code);
        $query->where('type', $type);
        $query->where('is_use', 0);
        $query->order('create_time desc');
        $mail_code = $query->find();
        if (empty($mail_code)) return arrayFailed('验证码错误');
        $date = date('Y-m-d H:i:s', strtotime('-5minute'));
        if ($mail_code['create_time'] < $date) return arrayFailed('验证码已过期');
        Db::name('mail_code')->where('id', $mail_code['id'])->update(['is_use' => 1]);
        return arraySuccess();
    }
}