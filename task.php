<?php

class server {
    private $serv;

    /**  * [__construct description]  * 构造方法中,初始化 $serv 服务  */
    public function __construct() {
        $this->serv = new swoole_server('0.0.0.0', 9801); //初始化swoole服务
        $this->serv->set(
            array('worker_num' => 8, 'daemonize' => 0, //是否作为守护进程,此配置一般配合log_file使用
                'max_request' => 1000, 'dispatch_mode' => 2, 'debug_mode' => 1, 'log_file' => './swoole.log',
            )
        ); //设置监听
        $this->serv->on('Start', array($this, 'onStart'));
        $this->serv->on('WorkerStart', array($this, 'onWorkerStart'));
        $this->serv->on('Connect', array($this, 'onConnect'));
        $this->serv->on("Receive", array($this, 'onReceive'));
        $this->serv->on("Close", array($this, 'onClose')); // bind callback
//        $this->serv->on('Timer', array($this, 'onTimer')); //开启
        $this->serv->start();
    }

    public function onStart($serv) {
        echo SWOOLE_VERSION . " onStart\n";
    }

    public function onWorkerStart($serv, $worker_id) { // 在Worker进程开启时绑定定时器
        echo $worker_id . " onWorkerStart \n";         // 只有当worker_id为0时才添加定时器,避免重复添加
        if ($worker_id == 0) {
            $serv->tick(1000,function(){
                echo "Do Thing A at interval 1000\n";
            });//毫秒数
            $serv->tick(3000,function(){
                echo "Do Thing A at interval 3000\n";
            });
            $serv->tick(10000,function(){
                echo "Do Thing A at interval 10000\n";
            });
        }
    }

    public function onConnect($serv, $fd) {
        echo $fd . "Client Connect.\n";
    }

    public function onReceive($serv, $fd, $from_id, $data) {
        echo "Get Message From Client {$fd}:{$data}\n"; // send a task to task worker.
        $param = array('fd' => $fd);
        $serv->send($fd, 'Swoole: ' . $data);
    }

    public function onClose($serv, $fd) {
        echo $fd . "Client Close.\n";
    }

    public function onTimer($serv, $interval) {
        switch ($interval) {
            case 1000: {
                echo "Do Thing A at interval 1000\n";
                break;
            }
            case 3000: {
                echo "Do Thing B at interval 3000\n";
                break;
            }
            case 10000: {
                echo "Do Thing C at interval 10000\n";
                break;
            }
        }
    }
}

$server = new server();
?>
