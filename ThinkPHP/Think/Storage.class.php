<?php
/**
 * Created by PhpStorm.
 * User: yangfeilong
 * Date: 2018/9/2
 * Time: 23:25
 */

namespace Think;

// 分布式文件存储类
class Storage {
    protected static $handler;

    /**
     * @param string $type
     * @param array $options
     */
    public static function connect($type='File', $options=array()){
        $class = 'Think\\Storage\\Driver'.ucwords($type);
        self::$handler = new $class($options);
    }

    public static function __callStatic($name, $arguments)
    {
        // TODO: Implement __callStatic() method.
        // 调用缓存驱动的方法
        if (method_exists(self::$handler, $name)){
            return call_user_func_array(array(self::$handler, $name), $arguments);
        }
    }
}