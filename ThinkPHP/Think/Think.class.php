<?php
/**
 * Created by PhpStorm.
 * User: yfl
 * Date: 2018/8/6
 * Time: 22:55
 */

namespace Think;

class Think{
    // 类映射
    private static $_map = array();

    // 实例化对象
    private static $_instance = array();

    /**
     * 应用程序初始化
     * @access public
     * @return void
     */
    public static function start(){
        // 注册AUTOLOAD方法
        spl_autoload_register('Think\Think::autoload');
        // 设定错误和异常处理
        register_shutdown_function('Think\Think::fatalError');
        set_error_handler('Think\Think::appError');
        set_exception_handler('Think\Think::appException');

        // 设置系统时区
        date_default_timezone_set('PRC');

        App::run();
    }

    // 致命错误捕获
    public static function fatalError(){
        var_dump('fatalError');
        if($e = error_get_last()){
            switch ($e['type']){
                case E_ERROR:
                case E_PARSE:
                case E_CORE_ERROR:
                case E_COMPILE_ERROR:
                case E_USER_ERROR:
                    ob_end_clean();
                    var_dump($e);
                    break;
            }
        }
    }

    /**
     * 自定义错误处理
     * @access public
     * @param int $errno 错误类型
     * @param string $errstr 错误信息
     * @param string $errfile 错误文件
     * @param int $errline 错误行数
     * @return void
     * 例子：trigger_error('致命错误信息', E_USER_ERROR);
     */
    public static function appError($errno, $errstr, $errfile, $errline){
        var_dump('appError:'.$errno);
        switch($errno){
            case E_ERROR:
            case E_PARSE:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
                ob_end_clean();
                $errorStr = "$errstr " . $errfile . " 第 $errfile 行";
                var_dump($errorStr);
                break;
            default:
                $errorStr = "NOTICE: [$errno] $errstr " . $errfile . " 第 $errline 行.";
                var_dump($errorStr);
                break;
        }
    }

    /**
     * @param $e
     * 实现自定义异常处理函数
     * 例子：throw new \Exception('异常信息');
     */
    public static function appException($e){
        var_dump('appException');
        $error = array();
        $error['message'] = $e->getMessage();
        $trace = $e->getTrace();
        if('E' == $trace[0]['function']){
            $error['file'] = $trace[0]['file'];
            $error['line'] = $trace[0]['line'];
        }else{
            $error['file'] = $e->getFile();
            $error['line'] = $e->getLine();
        }
        $error['trace'] = $e->getTraceAsString();
        header('HTTP/1.1 404 Not Found');
        header('Status:404 Not Found');
        var_dump($error);
    }

    /**
     * @param $class
     * 实现自定义自动加载函数
     */
    public static function autoload($class){
        if(isset(self::$_map[$class])){
            include self::$_map[$class];
        }elseif(false !== strpos($class, '\\')){
            $name = strstr($class, '\\', true);
            if(in_array($name, array('Think')) || is_dir(THINK_PATH.$name)){
                $path = THINK_PATH;
            }else{
                $path = APP_PATH;
            }
            $filename = $path . str_replace('\\', '/', $class) . EXT;
            if(is_file($filename)){
                // Win环境下面严格区分大小写
                if(IS_WIN && false === strpos(str_replace('/', '\\', realpath($filename)), $class . EXT)){
                    return;
                }
                include $filename;
            }
        }
    }
}