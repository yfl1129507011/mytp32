<?php
/**
 * Created by PhpStorm.
 * User: yangfeilong
 * Date: 2018/9/2
 * Time: 23:29
 */
namespace Think\Storage\Driver;

use Think\Storage;

// 本地文件写入存储类
class File extends Storage{
    private $contents = array();

    public function __construct()
    {
    }

    /**
     * 读取文件内容
     * @param $filename
     * @return bool|mixed
     */
    public function read($filename){
        return $this->get($filename, 'content');
    }

    /**
     * 文件写入
     * @param $filename
     * @param $content
     * @return bool
     * @throws \Exception
     */
    public function put($filename, $content){
        $dir = dirname($filename);
        if (!is_dir($dir)){
            mkdir($dir, 0777, true);
        }
        if (false === file_put_contents($filename, $content)){
            throw new \Exception('文件写入错误:'.$filename);
        }else{
            $this->contents[$filename] = $content;
            return true;
        }
    }

    /**
     * 追加文件写入
     * @param $filename
     * @param $content
     * @return bool
     * @throws \Exception
     */
    public function append($filename, $content){
        if (is_file($filename)){
            $content = $this->read($filename).$content;
        }
        return $this->put($filename, $content);
    }

    public function load($filename, $vars=null){
        if (!is_null($vars)){
            extract($vars, EXTR_OVERWRITE);
        }
        include $filename;
    }

    /**
     * 文件是否存在
     * @param $filename
     * @return bool
     */
    public function has($filename){
        return is_file($filename);
    }

    /**
     * 删除文件
     * @param $filename
     * @return bool
     */
    public function unlink($filename){
        unset($this->contents[$filename]);
        return is_file($filename) ? unlink($filename) : false;
    }

    /**
     * 读取文件信息
     * @param $filename
     * @param $name
     * @return bool|mixed
     */
    public function get($filename, $name){
        if (!isset($this->contents[$filename])){
            if (!is_file($filename)){
                return false;
            }
            $this->contents[$filename] = file_get_contents($filename);
        }
        $content = $this->contents[$filename];
        $info = array(
            'mtime' => filemtime($filename),
            'content' => $content,
        );

        return $info[$name];
    }
}