<?php
/**
 * Created by PhpStorm.
 * User: yfl
 * Date: 2018/7/30
 * Time: 22:59
 */

// 检测PHP环境
if(version_compare(PHP_VERSION, '5.3.0', '<')){
    die('require PHP > 5.3.0');
}

require './ThinkPHP/ThinkPHP.php';
var_dump(__LINE__,__FILE__);