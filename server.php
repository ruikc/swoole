<?php
require_once('./vendor/autoload.php');
require_once('./common/function.php');

$http = new swoole_http_server("127.0.0.1", 9502);
$http->on('request', function ($request, $response) {
//    var_dump($request);
    $path_info = $request->server['path_info'];
    // ob_start();
    $content = route($path_info, $request, $response);
    $response->header('Content-Type', 'text/html;charset=utf-8');
    if (is_array($content)) {
        $content = json_encode($content);
    }
    $response->end($content);
//    header("Content-Type: text/html;charset=utf-8");
//    $response->header('Content-Type', 'text/html;charset=utf-8');
//    $response->end(json_encode($content));
//    $response->end("<h1>Hello Swoole--" . $content . ". #" . rand(1000, 9999) . "</h1>");
});

$http->start();