<?php
// +----------------------------------------------------------------------
// | EduComm [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://educomm.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Byron Sampson <xiaobosun@gridinfo.com.cn>
// +----------------------------------------------------------------------
namespace Core;


/**
 * 数据库类
 */
class Model
{

    protected $db = null;
    protected $redis = null;
    protected $config;

    function __construct ($request, $response) {
        $this->config = include_once(__DIR__ . '/../config/config.php');
        $this->initDb();
    }

    /**
     * 初始化数据库
     * @name: initDb
     * @return void
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2016/11/1 下午4:45
     */
    private function initDb(){

        //实例化mysql数据库
        if (count($this->config['mysql'])) {
            $this->db = new \MysqliDb($this->config['mysql']);
        }
        //实例化redis数据库
        if (count($this->config['redis'])) {
            $this->redis = new \Predis\Client($this->config['redis']);
        }
    }

    /**
     * 设置缓存操作
     * @name: cache
     * @return void
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2016/11/1 下午5:08
     */
    protected function resultCache($tablename,$field,$value='',$expire=null){

        if(!$this->redis){
            return false;
        }
        if(is_null($field)){
            return $this->redis->del($tablename);
        }

        if(!$value){
            $this->redis->hSet($tablename,$field,$value);
            if($expire){
                $this->redis->expire($tablename,$expire);
            }
        }else{
            return $this->redis->hGet($tablename,$field);
        }
    }







}