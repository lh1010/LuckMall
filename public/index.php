<?php

namespace think;

if (file_exists("./install/") && !file_exists("./install/install.lock")) {
    if ($_SERVER['PHP_SELF'] != '/index.php') {
        header("Content-type: text/html; charset=utf-8");
        exit("请在域名根目录下安装，如:<br/> www.xxx.com/index.php 正确 <br/>  www.xxx.com/www/index.php 错误，域名后面不能圈套目录，但项目没有根目录存放限制，可以放在任意目录，apache虚拟主机配置一下即可");
    }
    header('Location:/install/index.php');
    exit();
}

require __DIR__ . '/../thinkphp/base.php';

Container::get('app')->run()->send();