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
namespace V1;

use Core\Core;

/**
 * 用户测试类
 */
class User extends Core
{

    function token () {
        $tmp = $this->get('k');
        $this->cookie('user','22222');
        $tmp2 = $this->cookie('user');
        $user = ['name' => "yuzhiyuan", 'age' => 30, 'info' => 'this is V1\User\tokcen,k is' . $tmp.'-----'.$tmp2];
//        return $this->ajaxReturn($user);
        return ['error' => 0, 'info' => '得到用户成功', 'data' => $user];
    }
}