<?php

use app\repository\AccountRepository;

if (function_exists('app')) {
	function app($abstract = null)
	{
	    return new $abstract;
	}
}

function dd($var)
{
	dump($var);
	exit();
}

/**
 * 去除字符串中的空格
 * @param string $data
 * @return string
 */
function clearSpacing($data)
{
	$a = array(" ", "　", "\t", "\n", "\r");
	$b = array("", "", "", "", "");
    return str_replace($a, $b, $data);
}

/**
 * 二维数组按指定键值去重
 * @param $array 需要去重的二维数组
 * @param $key 需要去重所根据的索引/键值
 * @return array
 */
function array_uniqueness($array, $key)
{
	$key_array = [];
	foreach ($array as $k => $v) {
		if (in_array($v[$key], $key_array)) {
			unset($array[$k]);
		} else {
			$key_array[] = $v[$key];
		}
	}
	sort($array);
	return $array;
}

/**
 * Log Write
 * @param string $data
 * @param string $path
 */
function logs($data = '', $path = 'error')
{
	if (empty($data)) return false;
	$path = Env::get('runtime_path').'log/'.$path;
	if (!is_dir($path)) $mkdir_re = mkdir($path, 0777, TRUE);	
	$path = $path.'/'.date('Y-m-d').'.log';
	$data = date('Y-m-d H:i:s') . "\n" . (is_array($data) ? serialize($data) : $data) . "\n\n";
	$handle = fopen($path, 'a');
	fwrite($handle, $data);
	fclose($handle);
}

/**
 * 数字转汉字
 * @param int $num
 */
function numToWord($num)
{
	$chiNum = array('零', '一', '二', '三', '四', '五', '六', '七', '八', '九');
	$chiUni = array('','十', '百', '千', '万', '亿', '十', '百', '千');
	$chiStr = '';
	$num_str = (string)$num;
	$count = strlen($num_str);
	$last_flag = true;
	$zero_flag = true;
	$temp_num = null;
	$chiStr = '';
	if ($count == 2) {
		$temp_num = $num_str[0];
		$chiStr = $temp_num == 1 ? $chiUni[1] : $chiNum[$temp_num].$chiUni[1];
		$temp_num = $num_str[1];
		$chiStr .= $temp_num == 0 ? '' : $chiNum[$temp_num]; 
	} else if ($count > 2) {
		$index = 0;
		for ($i = $count - 1; $i >= 0; $i--) { 
			$temp_num = $num_str[$i];
			if ($temp_num == 0) {
				if (!$zero_flag && !$last_flag ) {
					$chiStr = $chiNum[$temp_num].$chiStr;
					$last_flag = true;
				}
			} else {
				$chiStr = $chiNum[$temp_num].$chiUni[$index%9].$chiStr;
				$zero_flag = false;
				$last_flag = false;
			}
			$index ++;
		}
	} else {
		$chiStr = $chiNum[$num_str[0]];
	}
	return $chiStr;
}

/**
 * 对象转数组
 * object transition array
 * @param object $object
 * @return array
 */
function object_to_array($object)
{
    $object = (array)$object;
    foreach ($object as $key => $value) {
        if (gettype($value) == 'object' || gettype($value) == 'array') {
            $object[$key] = (array)object_to_array($value);
        }
    }
    return $object;
}

/**
 * 设置 URL 参数
 * @param array $params
 * @return string
 */
function setUrlParams($params, $url = '')
{
	$parse_url = $url === '' ? parse_url($_SERVER["REQUEST_URI"]) : parse_url($url);
	$query = isset($parse_url['query']) ? $parse_url['query'] : '';
	$querys = explode('&', $query);
	$current_params = [];
	if ($querys[0] !== '') {
		foreach ($querys as $param){
			list($name, $value) = explode('=', $param);
			$current_params[urldecode($name)] = urldecode($value);
		}       
	}
	foreach ($params as $key => $value) {
		$current_params[$key] = $value;
	}
	return $parse_url['path'].'?'.http_build_query($current_params);
}

/**
 * 删除文件夹/文件
 * @param string $path 文件夹/文件路径
 * @return float
 */
function deleteFile($path)
{
	// 文件
	if (!is_dir($path)) {
		if (unlink($path)) {
			return true;
		} else {
			return false;
		}
	}

	// 文件夹
	$dh = opendir($path);
	while ($file = readdir($dh)) {
		if ($file != "." && $file != "..") {
			$fullpath = $path."/".$file;
			if (!is_dir($fullpath)) {
				unlink($fullpath);
			} else {
				deleteFile($fullpath);
			}
		}
	}
	closedir($dh);
	if (rmdir($path)) {
		return true;
	} else {
		return false;
	}
}

/**
 * 检查价格格式
 */
function validatePriceFormat($price)
{
    if (preg_match('/^[0-9]+\d*(.\d{1,2})?$/', $price)) {
        return true;
    } else {
        return false;
    }
}

/**
 * curl get
 * @param string $url
 * @param array $header example:['Content-Type:application/x-www-form-urlencoded', 'access-token:'123'];
 * @return array
 */ 
function curl_get($url, $header = [])
{
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	if ($header) curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_URL, $url);
    $res = curl_exec($curl);
    curl_close($curl);
    return $res;
}

function curl_post($url, $params = '', $header = [])
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_POST, 1);
    if ($params) {
        if (is_array($params)) {
            $str = "";
            foreach ($params as $key => $value) { 
                $str .= $key.'='.urlencode($value)."&";
            }
            $str = substr($str, 0, -1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $str);
        } else {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        }
    }
    if ($header) curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    $res = curl_exec($curl);
    curl_close($curl);
    return $res;
}

/**
 * 下载远程图片到本地
 * @param string url 远程图片url
 * @param string 本地保存路径
 * @param string 本地保存名字
 * @return string
 */
function download_image($url, $path, $file_name = '')
{
	if (!is_dir($path)) mkdir($path, 0777);
	if (!is_readable($path)) chmod($path, 0777);
	
	if (empty($file_name)) $file_name = md5(time()) . '.png';
	$file_name = $path . $file_name;

	ob_start();
    readfile($url);   
    $content = ob_get_contents();   
    ob_end_clean();   
	$size = strlen($content);
	
	$fp = @fopen($file_name, "a");   
    fwrite($fp, $content);   
	fclose($fp);
	
	return $file_name;
}

/**
 * 数组排序
 * 数组根据某个键值排序
 * @param array $array
 * @param string $param_key 键值
 * @param string asc|desc
 * @return array
 */
function arraySort($array, $param_key, $sort = 'asc')
{
    if (empty($array)) return $array;
    $newArr = $valArr = array();
    foreach ($array as $key=>$value) {
        $valArr[$key] = $value[$param_key];
    }
    $sort == 'asc' ?  asort($valArr) : arsort($valArr);
    reset($valArr);
    foreach ($valArr as $key => $value) {
        $newArr[$key] = $array[$key];
    }
    return $newArr;
}

function get_public_path()
{
	return Env::get('root_path') . 'public';
}