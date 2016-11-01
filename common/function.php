<?php
/*
根据request_uri来返回对应全名空间的方法
*/
function route ($path_info, $request, $response) {

    $pathar = explode('/', $path_info);
    $class = null;
    if (count($pathar) == 4) {
        list(, $na, $cl, $fu) = $pathar;
    } else if (count($pathar) == 3) {
        list(, $na, $cl) = $pathar;
        $fu = 'index';
    } else {
        return '';
    }
    $namespace = ucfirst($na);
    $classname = ucfirst($cl);
    $classpath = __DIR__ . "/../app/" . $namespace . '/' . $classname . '.php';
//    return  $classpath;
    $corepath = __DIR__ . "/../core/Core.php";
//    return $corepath;
    //加载核心文件
    include_once($corepath);
    if (is_file($classpath)) {
        include_once($classpath);
    }
    $class = '\\' . $namespace . '\\' . $classname;

    if (class_exists($class)) {
        $obj = new $class($request, $response);

        $cls_methods = get_class_methods($obj);
        if (in_array($fu, $cls_methods)) {
            return $obj->$fu();
        } else {
            return ['error' => '10001', 'info' => '找不到' . $class . '类里面的' . $fu . '方法'];
        }

    } else {
        return ['error' => '10000', 'info' => '找不到' . $class . '类'];
    }
}


?>