<?php

header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('PRC');
error_reporting(E_ALL & ~E_NOTICE);
define('APP_DIR', dirname(substr(dirname(__FILE__), 0, -8))); // 项目目录
define('SITE_DIR', substr(dirname(__FILE__), 0, -8)); // 入口文件目录
@set_time_limit(1000);

include 'auto.php';

if (file_exists('./install.lock')) {
    echo '
		<html>
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        </head>
        <body>
        	你已安装过该系统，如果想重新安装，请先删除 install 目录下的 install.lock 文件。
        </body>
        </html>';
    exit;
}

if (PHP_EDITION > phpversion()) exit('您的php版本过低，不能安装本软件，请升级到' . PHP_EDITION . '或更高版本再安装，谢谢！');
if (phpversion() > '7.4') exit('您的php版本太高，不能安装本软件，兼容php版本7.1~7.3，谢谢！');

$sqlFile = 'luckmall.sql';
$configFile = '.env.example';
if (!file_exists(SITE_DIR . '/install/' . $sqlFile) || !file_exists(SITE_DIR . '/install/' . $configFile)) {
    exit('缺少必要的安装文件!');
}

$title = "LuckMall安装向导";
$powered = "Powered by LuckMall";
$steps = array(
    '1' => '安装许可协议',
    '2' => '运行环境检测',
    '3' => '安装参数设置',
    '4' => '安装详细过程',
    '5' => '安装完成',
);
$step = isset($_GET['step']) ? $_GET['step'] : 1;

switch ($step) {
    case '1':
        include_once("tpl/step1.php"); exit();
    case '2':
        $phpv = @ phpversion();
        $os = PHP_OS;
        $err = 0;
        if (function_exists('mysqli_connect')) {
            $mysql = '<span class="correct_span">&radic;</span> 已安装';
        } else {
            $mysql = '<span class="correct_span error_span">&radic;</span> 请安装mysqli扩展'; $err++;
        }
        if (ini_get('file_uploads')) {
            $uploadSize = '<span class="correct_span">&radic;</span> ' . ini_get('upload_max_filesize');
        } else {
            $uploadSize = '<span class="correct_span error_span">&radic;</span>禁止上传'; $err++;
        }
        if (function_exists('session_start')) {
            $session = '<span class="correct_span">&radic;</span> 支持';
        } else {
            $session = '<span class="correct_span error_span">&radic;</span> 不支持'; $err++;
        }
        if (function_exists('curl_init')) {
            $curl = '<span class="correct_span">&radic;</span> 支持';
        } else {
            $curl = '<span class="correct_span error_span">&radic;</span> 不支持'; $err++;
        }
        if (function_exists('bcadd')) {
            $bcmath = '<span class="correct_span">&radic;</span> 支持';
        } else {
            $bcmath = '<span class="correct_span error_span">&radic;</span> 不支持'; $err++;
        }
        if (function_exists('openssl_encrypt')) {
            $openssl = '<span class="correct_span">&radic;</span> 支持';
        } else {
            $openssl = '<span class="correct_span error_span">&radic;</span> 不支持'; $err++;
        }

        // 文件权限
        $folder = ['public/install', 'public/uploads', 'public/images', 'public/imgextra', 'config/readfile', 'runtime']; // 创建
        $file = ['.env']; // 删除文件，创建.env文件

        // 必须开启的函数
        if (function_exists('file_put_contents')) {
            $file_put_contents = '<span class="correct_span">&radic;</span> 开启';
        } else {
            $file_put_contents = '<span class="correct_span error_span">&radic;</span> 关闭'; $err++;
        }
        if (function_exists('imagettftext')) {
            $imagettftext = '<span class="correct_span">&radic;</span> 开启';
        } else {
            $imagettftext = '<span class="correct_span error_span">&radic;</span> 关闭'; $err++;
        }

        include_once("tpl/step2.php"); exit();
    case '3':
        // 处理数据
        $dbName = strtolower(trim($_POST['dbName']));
        $_POST['dbport'] = $_POST['dbport'] ? $_POST['dbport'] : '3306';
        if ($_GET['testdbpwd']) {
            $dbHost = $_POST['dbHost'];
            $conn = @mysqli_connect($dbHost, $_POST['dbUser'], $_POST['dbPwd'], NULL, $_POST['dbport']);
            if (mysqli_connect_errno($conn)) {
                die(json_encode(0));
            } else {
                $result = mysqli_query($conn, "SELECT @@global.sql_mode");
                $result = $result->fetch_array();
                $version = mysqli_get_server_info($conn);
                if ($version >= 5.7) {
                    if (strstr($result[0], 'STRICT_TRANS_TABLES') || strstr($result[0], 'STRICT_ALL_TABLES') || strstr($result[0], 'TRADITIONAL') || strstr($result[0], 'ANSI'))
                        exit(json_encode(-1));
                }
                $result = mysqli_query($conn, "select count(table_name) as c from information_schema.`TABLES` where table_schema='$dbName'");
                $result = $result->fetch_array();
                if ($result['c'] > 0) exit(json_encode(-2));
            }
            exit(json_encode(1));
        }

        include_once("tpl/step3.php"); exit();
    case '4':
        if (intval($_GET['install'])) {
            $n = intval($_GET['n']);
            if ($i == 999999) exit();
            $arr = [];

            $dbHost = trim($_POST['dbhost']);
            $dbPort = $_POST['dbport'] ? $_POST['dbport'] : '3306';
            $dbName = strtolower(trim($_POST['dbname']));
            $dbUser = trim($_POST['dbuser']);
            $dbPwd = trim($_POST['dbpw']);
            $dbPrefix = empty($_POST['dbprefix']) ? 'luck_' : trim($_POST['dbprefix']);

            $username = trim($_POST['manager']);
            $password = trim($_POST['manager_pwd']);

            if (!function_exists('mysqli_connect')) {
                $arr['msg'] = "请安装 mysqli 扩展!";
                echo json_encode($arr);
                exit;
            }

            $conn = @mysqli_connect($dbHost, $dbUser, $dbPwd, NULL, $dbPort);
            if (mysqli_connect_errno($conn)) {
                $arr['msg'] = "连接数据库失败!" . mysqli_connect_error($conn);
                echo json_encode($arr);
                exit;
            }
            mysqli_set_charset($conn, "utf8");
            $version = mysqli_get_server_info($conn);
            if ($version < 5.1) {
                $arr['msg'] = '数据库版本不能低于5.1';
                echo json_encode($arr);
                exit;
            }

            if (!mysqli_select_db($conn, $dbName)) {
                if (!mysqli_query($conn, "CREATE DATABASE `" . $dbName . "` DEFAULT CHARACTER SET utf8;")) {
                    $arr['msg'] = '创建数据库 ' . $dbName . ' 失败！';
                    echo json_encode($arr);
                    exit;
                }
                if ($n == -1) {
                    $arr['n'] = 0;
                    $arr['msg'] = "成功创建数据库:{$dbName}<br>";
                    echo json_encode($arr);
                    exit;
                }
                mysqli_select_db($conn, $dbName);
            }

            // 读取数据文件
            $sqldata = file_get_contents(SITE_DIR . '/install/' . $sqlFile);
            $sqlFormat = sql_split($sqldata, $dbPrefix);

            // 执行SQL语句
            $counts = count($sqlFormat);
            for ($i = $n; $i < $counts; $i++) {
                $sql = trim($sqlFormat[$i]);
                if (strstr($sql, 'CREATE TABLE')) {
                    preg_match('/CREATE TABLE (IF NOT EXISTS)? `'.$dbPrefix.'([^ ]*)`/is', $sql, $matches);
                    $table_name = $dbPrefix . $matches[2];
                    mysqli_query($conn, "DROP TABLE IF EXISTS `$table_name");
                    $ret = mysqli_query($conn, $sql);
                    if ($ret) {
                        $message = '<li><span class="correct_span">&radic;</span>创建数据表[' . $table_name . ']完成!<span style="float: right;">' . date('Y-m-d H:i:s') . '</span></li> ';
                    } else {
                        $err = mysqli_error($conn);
                        $message = '<li><span class="correct_span error_span">&radic;</span>创建数据表[' . $table_name . ']失败!失败原因：' . $err . '<span style="float: right;">' . date('Y-m-d H:i:s') . '</span></li>';
                    }
                    $i++;
                    $arr = array('n' => $i, 'msg' => $message);
                    echo json_encode($arr); exit;
                } else {
                    if (trim($sql) == '') continue;
                    $ret = mysqli_query($conn, $sql);
                    $message = $sql;
                    $arr = array('n' => $i, 'msg' => $message);
                }
            }
            // 清空测试数据
            if (!$_POST['demo']) {
                $result = mysqli_query($conn, "show tables");
                $tables = mysqli_fetch_all($result); // 参数MYSQL_ASSOC、MYSQLI_NUM、MYSQLI_BOTH规定产生数组类型
                $bl_table = [
                    $dbPrefix . 'product_category',
                    $dbPrefix . 'product',
                    $dbPrefix . 'product_attribute',
                    $dbPrefix . 'product_attribute_option',
                    $dbPrefix . 'product_to_attribute;',
                    $dbPrefix . 'product_specification',
                    $dbPrefix . 'product_specification_option',
                    $dbPrefix . 'product_to_specification',
                    $dbPrefix . 'product_freight',
                    $dbPrefix . 'product_image',
                    $dbPrefix . 'product_model',
                    $dbPrefix . 'product_sku',
                    $dbPrefix . 'brand',
                    $dbPrefix . 'friendlink',
                    $dbPrefix . 'shipping_mark',
                    $dbPrefix . 'shipping_freight',
                    $dbPrefix . 'shipping_freight_value',
                    $dbPrefix . 'administrator',
                    $dbPrefix . 'search_hot_word',
                ];
                foreach ($tables as $key => $val) {
                    if (in_array($val[0], $bl_table)) {
                        mysqli_query($conn, "truncate table " . $val[0]);
                    }
                }
                // 清空测试图片
                delFile(SITE_DIR . '/imgextra');
                delFile(SITE_DIR . '/images/product');
                delFile(SITE_DIR . '/images/product_category');
                delFile(SITE_DIR . '/images/brand');
            }

            // 创建配置文件
            $strConfig = file_get_contents(SITE_DIR . '/install/' . $configFile);
            $site_url = 'http://b2c.luckmall.lh1010.com';
            if (isset($_SERVER['SERVER_NAME'])) {
                $site_url = 'http://' . $_SERVER['SERVER_NAME'];
            }
            $strConfig = str_replace('#APP_URL#', $site_url, $strConfig);
            $strConfig = str_replace('#DB_HOST#', $dbHost, $strConfig);
            $strConfig = str_replace('#DB_NAME#', $dbName, $strConfig);
            $strConfig = str_replace('#DB_USER#', $dbUser, $strConfig);
            $strConfig = str_replace('#DB_PWD#', $dbPwd, $strConfig);
            $strConfig = str_replace('#DB_PORT#', $_POST['dbport'], $strConfig);
            $strConfig = str_replace('#DB_PREFIX#', $dbPrefix, $strConfig);
            @chmod(APP_DIR . '/.env', 0777);
            @file_put_contents(APP_DIR . '/.env', $strConfig);

            // 创建管理员
            $username = $_POST['manager'];
            $password = md5($_POST['manager_pwd']);
            $sql = "INSERT INTO `{$dbPrefix}administrator` (`username`, `password`) VALUES ('" . $username . "', '" . $password . "')";
            $res = mysqli_query($conn, $sql);

            if ($res) {
                $message = '成功添加管理员<br />成功写入配置文件<br>安装完成．';
                $arr = array('n' => 999999, 'msg' => $message);
                echo json_encode($arr);
                exit;
            } else {
                $message = '添加管理员失败<br />成功写入配置文件<br>安装完成．';
                $arr = array('n' => 999999, 'msg' => $message);
                echo json_encode($arr);
                exit;
            }
        }
        include_once("tpl/step4.php"); exit();
    case '5':
        include_once("tpl/step5.php");
        @touch('./install.lock');
        exit();    
}

echo $step;

// 判断权限
function testwrite($d)
{
    if (is_file($d)) {
        if (is_writeable($d)) {
            return true;
        }
        return false;
    } else {
        $tfile = "_test.txt";
        $fp = @fopen($d . "/" . $tfile, "w");
        if (!$fp) {
            return false;
        }
        fclose($fp);
        $rs = @unlink($d . "/" . $tfile);
        if ($rs) {
            return true;
        }
        return false;
    }
}

function dir_create($path, $mode = 0777)
{
    if (is_dir($path)) return true;
    $ftp_enable = 0;
    $path = dir_path($path);
    $temp = explode('/', $path);
    $cur_dir = '';
    $max = count($temp) - 1;
    for ($i = 0; $i < $max; $i++) {
        $cur_dir .= $temp[$i] . '/';
        if (@is_dir($cur_dir))
            continue;
        @mkdir($cur_dir, 0777, true);
        @chmod($cur_dir, 0777);
    }
    return is_dir($path);
}

function sql_split($sql, $dbPrefix)
{
    if ($dbPrefix != "luck_") $sql = str_replace("luck_", $dbPrefix, $sql);
    $sql = preg_replace("/TYPE=(InnoDB|MyISAM|MEMORY)( DEFAULT CHARSET=[^; ]+)?/", "ENGINE=\\1 DEFAULT CHARSET=utf8", $sql);
    $sql = str_replace("\r", "\n", $sql);
    $ret = array();
    $num = 0;
    $queriesarray = explode(";\n", trim($sql));
    unset($sql);
    foreach ($queriesarray as $query) {
        $ret[$num] = '';
        $queries = explode("\n", trim($query));
        $queries = array_filter($queries);
        foreach ($queries as $query) {
            $str1 = substr($query, 0, 1);
            if ($str1 != '#' && $str1 != '-')
                $ret[$num] .= $query;
        }
        $num++;
    }
    return $ret;
}

function dir_path($path)
{
    $path = str_replace('\\', '/', $path);
    if (substr($path, -1) != '/') $path = $path . '/';
    return $path;
}

// 递归删除文件夹
function delFile($dir, $file_type = '')
{
    if (is_dir($dir)) {
        $files = scandir($dir);
        //打开目录 //列出目录中的所有文件并去掉 . 和 ..
        foreach ($files as $filename) {
            if ($filename != '.' && $filename != '..') {
                if (!is_dir($dir . '/' . $filename)) {
                    if (empty($file_type)) {
                        unlink($dir . '/' . $filename);
                    } else {
                        if (is_array($file_type)) {
                            //正则匹配指定文件
                            if (preg_match($file_type[0], $filename)) {
                                unlink($dir . '/' . $filename);
                            }
                        } else {
                            //指定包含某些字符串的文件
                            if (false != stristr($filename, $file_type)) {
                                unlink($dir . '/' . $filename);
                            }
                        }
                    }
                } else {
                    delFile($dir . '/' . $filename);
                    rmdir($dir . '/' . $filename);
                }
            }
        }
    } else {
        if (file_exists($dir)) unlink($dir);
    }
}