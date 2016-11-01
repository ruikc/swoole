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
 * 基础类
 */
class Core
{

    protected $db = null;
    protected $request;
    protected $response;
    protected $_get;
    protected $_post;
    protected $redis = null;
    protected $config;

    function __construct ($request, $response) {
        $this->config = include_once(__DIR__ . '/../config/config.php');
        $this->request = $request;
        $this->_get = isset($request->get) ? $request->get : [];
        $this->_post = isset($request->post) ? $request->post : [];
        $this->response = $response;

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
     * 返回get方式得到的数据
     * @name: get
     * @param $key
     * @return void
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2016/11/1 下午2:41
     */
    protected function get ($key = '',$default='') {
        if (!$key) {
            return $this->_get;
        }
        return $this->isisset($this->_get[$key],$default);
    }

    /**
     * 返回post方式得到的数据
     * @name: post
     * @param $key
     * @return void
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2016/11/1 下午2:41
     */
    protected function post ($key,$default='') {
        return $this->isisset($this->_post[$key],$default);
    }

    /**
     * 返回request方式的数据
     * @name: request
     * @return array
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2016/11/1 下午6:21
     */
    protected function request(){

        $get = $this->isisset($this->get(),[]);
        $post = $this->isisset($this->post(),[]);
        return array_merge($get,$post);
    }

    /**
     *
     * @name: _empty
     * @return array
     * @author: rickeryu <lhyfe1987@163.com>
     * @time:
     */
    protected function _empty () {
        return ['error' => '0', 'info' => 'empyt', 'data' => ''];
    }

    /**
     * cookie相关操作
     * @name: cookie
     * @return void
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2016/11/1 下午3:34
     */
    protected function cookie ($key='', $value = '', $expire = 0, $domain = '', $path = '/', $secure = false, $httponly = false) {
        if( $value && $key ){
            $this->response->cookie($key, $value, $expire, $path, $domain, $secure, $httponly);
        }else{
            if(!$key){
                return $this->isisset($this->request->cookie,[]);
            }else{
                return $this->isisset($this->request->cookie["$key"],[]);
            }
        }

    }

    /**
     * isset判断操作
     * @name: isisset
     * @param $val
     * @param string $default
     * @return string
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2016/11/1 下午3:54
     */
    protected function isisset($val,$default=''){
        return isset($val) ? $val : $default;
    }


    /**
     * 得到缓存key
     * @name: getCacheKey
     * @param $table
     * @param $map
     * @param string $field
     * @param string $order
     * @return string
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2016/11/1 下午5:07
     */
    protected function getCacheKey ($map, $field = '', $order = '') {
        $p = $this->get('p', 1);
        $page =$this->get('size', 10);
        $tmp = '';
        ksort($map);
        foreach ($map as $key => $val) {
            $tmp .= '_' . $key . '_' . serialize($val);
        }
        $tmp .= '_' . str_replace(',', '_', $field);
        $tmp .= '_' . str_replace(' ', '_', str_replace(',', '_', $order));
        $tmp .= '_p_' . $p . '_size_' . $page;
        $str = $this->getPreCacheKey($model . $tmp);
        return $str . '_' . $table;
    }

    /**
     * 得到缓存前缀
     * @name: getPreCacheKey
     * @param string $str
     * @return string
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2016/11/1 下午5:07
     */
    protected function getPreCacheKey ($str = '') {
        $host = get_host();
        return md5($host . '_' . $str);
    }


}