<?php 


class AsyMysql{

    // mysql对象
    public $dbSource='';
    public $dbConfig=[];
    public function __construct()
    {
        $this->$dbSource=new Swoole\Mysql;
        $this->$dbConfig=[
            'host' => '127.0.0.1',
            'port' => 3306,
            'user' => 'root',
            'password' => 'root',
            'database' => 'mblog',
            'charset' => 'utf8', //指定字符集
            'timeout' => 2,  // 可选：连接超时时间（非查询超时时间），默认为SW_MYSQL_CONNECT_TIMEOUT（1.0）			
        ];
    }

    /**
     * [execute description]
     *
     * @DateTime 2018-08-14
     *
     * @return   [type]
     */
    public function execute($id='',$params='')
    {	
        $this->$dbSource->connect($this->$dbConfig, function ($db, $r) use($id,$params) {
            if ($r === false) {
                var_dump($db->connect_errno, $db->connect_error);
                die;
            }
            $sql = 'show tables';
            $db->query($sql, function(swoole_mysql $db, $r) {
                if ($r === false)
                {
                    var_dump($db->error, $db->errno);
                }
                elseif ($r === true )
                {
                    var_dump($db->affected_rows, $db->insert_id);
                }
                var_dump($r);
                $db->close();
            });
        });
    }
}

$db = new swoole_mysql;
$server = array(
    'host' => '127.0.0.1',
    'port' => 3306,
    'user' => 'root',
    'password' => 'root',
    'database' => 'mblog',
    'charset' => 'utf8', //指定字符集
    'timeout' => 2,  // 可选：连接超时时间（非查询超时时间），默认为SW_MYSQL_CONNECT_TIMEOUT（1.0）
);

$db->connect($server, function ($db, $r) {
    if ($r === false) {
    	echo 1;
        var_dump($db->connect_errno, $db->connect_error);
        die;
    }
    $sql = 'show tables';
    $db->query($sql, function(swoole_mysql $db, $r) {
        if ($r === false)
        {
        	echo 2;
            var_dump($db->error, $db->errno);
        }
        elseif ($r === true )
        {
        	
            var_dump($db->affected_rows, $db->insert_id);
        }
        var_dump($r);
        $db->close();
    });
});
