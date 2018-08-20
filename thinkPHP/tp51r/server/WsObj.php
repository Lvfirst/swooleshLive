<?php 
class Ws{

    const port='8811';
    const wsaddr="0.0.0.0"; 

    private $serv=null;

    public function __construct()
    {
        $this->serv=new swoole_websocket_server(self::wsaddr, self::port);
        $this->serv->set(
            [
                'document_root'=>'/root/swoole/swooleSaishi/thinkPHP/tp51r/public/static',
                'enable_static_handler'=>true,
                'worker_num'=>4,
                'task_worker_num'=>2,
            ]
        );

        $this->serv->on('WorkerStart',[$this,'onWorkerStart']);
        $this->serv->on('Request',[$this,'onRequest']);
        $this->serv->on('Task',[$this,'onTask']);
        $this->serv->on('Message',[$this,'onMessage']);
        $this->serv->on('Finish',[$this,'onFinish']);


    }


    public function onWorkerStart($serv,$workerId)
    {
        // 定义应用目录
        define('APP_PATH', __DIR__ . '/../application/');
        //请求应该在request里面执行
        // require __DIR__ . '/../thinkphp/start.php';
        // 加载框架文件
        // require __DIR__ . '/../thinkphp/base.php';
        require __DIR__ . '/../thinkphp/start.php';

    }

    public function onRequest($request,$response)
    {
        // print_r($request->server);
        // 获取请求的内容
        $_SERVER  =  [];
        // swool进程是不释放超全局变量的,判断存在从获取一次
        if(isset($request->server)) {
            foreach($request->server as $k => $v) {
                $_SERVER[strtoupper($k)] = $v;
            }
        }
        if(isset($request->header)) {
            foreach($request->header as $k => $v) {
                $_SERVER[strtoupper($k)] = $v;
            }
        }

        $_GET = []; //初始化空 解决变量为空
        if(isset($request->get)) {
            foreach($request->get as $k => $v) {
                $_GET[$k] = $v;
            }
        }
        $_POST = [];
        if(isset($request->post)) {
            foreach($request->post as $k => $v) {
                $_POST[$k] = $v;
            }
        }
        $_POST['http_server']=$this->serv;
        // 执行响应,这里是输出在了控制台
        // 可以从缓冲区获取内容
        ob_start();
        try {
            think\Container::get('app', [APP_PATH])
                ->run()
                ->send();
        }catch (\Exception $e) {
            // todo
            var_dump($e);
        }


        #因为是路径的问题 所以用路径这开始追朔
        // echo '-action-'.request()->action().PHP_EOL;
        $con=ob_get_contents();
        ob_end_clean();

        // 取出缓冲区的内容
        $response->header('content-type','text/html;charset=utf-8');
        $response->end($con);
        // 释放掉资源，
        // $http->close();
        # 这块终端出现报错，但是还可以正常显示tp，是因为swoole一个worker倒下了 会拉起第二个进程来
    }
    /**
     * [onTask 处理 Task任务]
     *
     * @DateTime 2018-08-18
     *
     * @param    [type] $serv
     * @param    [type] $taskId
     * @param    [type] $fromId
     * @param    [type] $data
     *
     * @return   [type]
     */
    public function onTask($serv,$taskId,$fromId,$data)
    {
        // 处理这个task的方法,
        $method=$data['method'];
        // task 投递过来的信息数据 
        
        $smsdata=$data['data'];
        // 处理task方法
        $obj=new app\common\lib\task\Task;

        $flag=$obj->$method($smsdata);

      /*  try {

            $options=[
                'accountsid'=>'3c6bfb7871ed3008ba700e4654d30811',
                'token'=>'43fc4570ab7c0c13b555e62d914f181c',
            ];
            //初始化 $options必填
            $ucpass = new app\common\lib\yzx\Ucpaas($options);
            $templateid = "180973";
            $uid='';
            $r=$ucpass->SendSms($templateid,$smsdata['code'],$smsdata['phone'],$uid);           
            $response=json_decode($r,true);
            print_r($response);
            
            
        } catch (\Exception $e) {
            // return app\common\lib\Util::show(config('code.error'), 'error');
        }  */

        return $flag;
        // return 'on task finish'; //是否出发	
    }

    /**
     * [onMessage 处理message]
     *
     * @DateTime 2018-08-20
     *
     * @param    [type] $serv
     * @param    [type] $frame
     *
     * @return   [type]
     */
    public function onMessage($serv,$frame)
    {

    }

    /**
     * [onFinish 处理 Finish]
     *
     * @DateTime 2018-08-18
     *
     * @param    [type] $serv
     * @param    [type] $taskId
     * @param    [type] $data
     *
     * @return   [type]
     */
    public function onFinish($serv,$taskId,$data)
    {
        echo "Task Id:{$taskId}\n";
        echo "finish received data '{$data}'".PHP_EOL;
    }	

    /**
     * [start start server]
     *
     * @DateTime 2018-08-18
     *
     * @return   [type]
     */
    public function start()
    {
        $this->serv->start();
    }
}


$ws=new Ws();
$ws->start();
