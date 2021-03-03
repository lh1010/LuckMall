<?php

/**
 * 邮件配置
 */

use think\facade\Env;

$res = file_exists(Env::get('config_path') . 'readfile/mail.php') ? json_decode(include 'readfile/mail.php', 1) : [];

return [
	'SMTPDebug' => 0,
	'Host' => isset($res['Host']) ? $res['Host'] : '',
	'SMTPSecure' => 'ssl',
	'Port' => 465,
	'Hostname' => 'localhost',
	'CharSet' => 'UTF-8',
	'FromName' => isset($res['FromName']) ? $res['FromName'] : '',
	'From' => isset($res['From']) ? $res['From'] : '',
	'Username' => isset($res['Username']) ? $res['Username'] : '',
	'Password' => isset($res['Password']) ? $res['Password'] : '',
];
