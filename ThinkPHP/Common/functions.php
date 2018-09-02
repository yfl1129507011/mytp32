<?php
/**
 * Created by PhpStorm.
 * User: yangfeilong
 * Date: 2018/8/23
 * Time: 23:13
 */

/**
 * 获取和设置配置参数
 * @param null $name
 * @param null $value
 * @param null $default
 * @return array|mixed|null
 */
function C($name=null, $value=null, $default=null){
    static $_config = array();
    // 无参数时获取所有配置
    if (empty($name)){
        return $_config;
    }
    if (is_string($name)){
        if (!strpos($name, '.')){
            $name = strtoupper($name);
            if (is_null($value)) {  // 获取配置信息
                return isset($_config[$name]) ? $_config[$name] : $default;
            }

            // 设置配置信息
            $_config[$name] = $value;
            return null;
        }

        // 二维数组设置和获取支持
        $name = explode('.', $name);
        $name[0] = strtoupper($name[0]);
        if (is_null($value)) {
            return isset($_config[$name[0]][$name[1]]) ? $_config[$name[0]][$name[1]] : $default;
        }
        $_config[$name[0]][$name[1]] = $value;
        return null;
    }
    // 批量设置
    if (is_array($name)){
        $_config = array_merge($_config, array_change_key_case($name, CASE_UPPER));
        return null;
    }

    return null;
}


function load_config($file, $parse=''){
    // 获取文件后缀
    $ext = pathinfo($file, PATHINFO_EXTENSION);
    switch ($ext){
        case 'php':
            return include $file;
        case 'ini':
            return parse_ini_file($file);
        case 'yaml':
            return yaml_parse_file($file);
        case 'xml':
            return (array) simplexml_load_file($file);
        case 'json':
            return json_decode(file_get_contents($file, true));
        default:
            throw new Exception('not support : '.$ext);
    }
}

/**
 * 获取客户端IP地址
 * @param int $type 返回类型：0->返回IP地址  1->返回IPV4地址数字
 * @param bool $adv 是否进行高级
 * @return mixed
 */
function get_client_ip($type = 0, $adv = false)
{
    $type = $type ? 1 : 0;
    static $ip = null;
    if (null !== $ip){
        return $ip[$type];
    }

    if ($adv){
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos = array_search('unknown', $arr);
            if (false !== $pos){
                unset($arr[$pos]);
            }

            $ip = trim($arr[0]);
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])){
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif (isset($_SERVER['REMOTE_ADDR'])){
            $ip = $_SERVER['REMOTE_ADDR'];
        }
    }elseif(isset($_SERVER['REMOTE_ADDR'])){
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    //ip地址合法验证
    $long = sprintf("%u", ip2long($ip));
    $ip = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}


function send_http_status($code){
    static $_status = array(
        // 服务端接收到信息 1xx
        100 => 'Continue',
        101 => 'Switching Protocols',
        // 处理成功 2xx
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        // 重定向 3xx
        300 => 'Multiple Choices',
        // 客服端请求错误 4xx
        400 => 'Bad Request',
        401 => 'Unauthorized',  //  未认证
        403 => 'Forbidden',
        404 => 'Not Found',
        // 服务端请求错误
        500 => 'Internal Server Error',  // 内部服务错误
        501 => 'Not Implemented',  //未执行
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
    );

    if (isset($_status[$code])){
        header('HTTP/1.1 ' . $code . ' ' . $_status[$code]);
        header('Status ' . $code . ' ' . $_status[$code]);
    }
}