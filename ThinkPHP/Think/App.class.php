<?php
/**
 * Created by PhpStorm.
 * User: yfl
 * Date: 2018/8/8
 * Time: 23:10
 */

namespace Think;

class App{

    /**
     * 应用程序初始化
     * @access public
     * @return void
     */
    public static function init(){
        // 定义当前请求的系统常量
        define('NOW_TIME', $_SERVER['REQUEST_TIME']);
        define('REQUEST_METHOD', $_SERVER['REQUEST_METHOD']);
        define('IS_GET', REQUEST_METHOD == 'GET' ? true : false);
        define('IS_POST', REQUEST_METHOD == 'POST' ? true : false);
    }

    /**
     * 执行应用程序
     */
    public static function run(){
        App::init();
    }
}