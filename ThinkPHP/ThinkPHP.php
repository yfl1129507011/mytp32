<?php
/**
 * Created by PhpStorm.
 * User: yfl
 * Date: 2018/7/30
 * Time: 23:09
 */

// 记录开始运行时间
$GLOBALS['_beginTime'] = microtime(true);

// 记录内存初始使用
define('MEMORY_LIMIT_ON', function_exists('memory_get_usage'));
if(MEMORY_LIMIT_ON) {
    $GLOBALS['_startUseMems'] = memory_get_usage();
}

// 版本信息
const THINK_VERSION = '3.2.3';

// URL 模式定义
const URL_COMMON = 0; // 普通模式
const URL_PATHINFO = 1; // PATHINFO模式
const URL_REWRITE = 2; // REWRITE模式
const URL_COMPAT = 3; // 兼容模式

// 类文件后缀
const EXT = '.class.php';

defined('THINK_PATH') or define('THINK_PATH', __DIR__.'/');
defined('APP_PATH') or define('APP_PATH', dirname($_SERVER['SCRIPT_FILENAME']) . '/');

if(version_compare(PHP_VERSION, '5.4.0', '<')){
    ini_set('magic_quotes_runtime', 0);
    define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc()?true:false);
}else{
    define('MAGIC_QUOTES_GPC', false);
}

define('IS_CGI', (0 === strpos(PHP_SAPI, 'cgi') || false !== strpos(PHP_SAPI, 'fcgi')) ? 1 : 0);
define('IS_WIN', strstr(PHP_OS, 'WIN') ? 1 : 0);
define('IS_CLI', PHP_SAPI == 'cli' ? 1 : 0);

if(!IS_CLI){
    if(!defined('_PHP_FILE_')) {
        if (IS_CGI) {
            // CGI/FASTCGI模式下
            $_temp = explode('.php', $_SERVER['PHP_SELF']);
            define('_PHP_FILE_', rtrim(str_replace($_SERVER['HTTP_HOST'], '', $_temp[0] . '.php'), '/'));
        } else {
            define('_PHP_FILE_', rtrim($_SERVER['SCRIPT_NAME'], '/'));
        }
    }
    if(!defined('__ROOT__')){
        $_root = rtrim(dirname(_PHP_FILE_), '/');
        define('__ROOT__', (('/' == $_root || '\\' == $_root) ? '' : $_root));
    }
}

require THINK_PATH . 'Think/Think' . EXT;
\Think\Think::start();