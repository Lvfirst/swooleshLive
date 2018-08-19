<?php
namespace app\index\controller;
use app\common\lib\yzx\Ucpaas;

class Index
{
    public function index()
    {

        return ;
    	// return json(['name'=>'lvzhiwei','age'=>18,'level'=>'A']);
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }

    public function sms()
    {
    	try {
			$options=[
	    		'accountsid'=>'3c6bfb7871ed3008ba700e4654d30811',
	    		'token'=>'43fc4570ab7c0c13b555e62d914f181c',
	    	];
			//初始化 $options必填
	    	$ucpass = new app\common\lib\yzx\Ucpaas($options);
	    	$templateid = "180973";    
	    	$param ='lvzhiwei,9999'; 
	    	$mobile = 18686130812;
	    	$uid='';
	    	return  $ucpass->SendSms($templateid,$param,$mobile,$uid);    		
    		
    	} catch (\Exception $e) {
    		var_dump($e);
    	}
    	
    }

    /**
     * [login login 方法]
     *
     * @DateTime 2018-08-16
     *
     * @return   [type]
     */
    public function login()
    {
    	return json(['name'=>'lvzhiwei','age'=>18,'level'=>'A']);
    }
}
