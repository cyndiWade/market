<?php

/**
 * 短信发送处理类
 * 网址：http://www.139000.com/index.asp
 */

class SHP { 
		
	private $name;		//账号名
	private $pwd;		//密码

	public function __construct($name,$pwd) {
		$this->name = $name;
		$this->pwd = $pwd;
	}
	
	/**
	 * 发送接口
	 * @param num(11) $phone		电话号码，多条用逗号分隔
	 * @param string $msg				短信消息
	 * @param string $time				定时发送，格式：YYYYMMDDHHMM
	 */
	public function send($phone,$msg,$time='') {

		$msg = urlencode(@iconv('UTF-8', 'GB2312', $msg));		//字体编码转换
		//请求地址
		$url = "http://203.81.21.34/send/gsend.asp?name=$this->name&pwd=$this->pwd&dst=$phone&msg=$msg&time=$time";
		$result = @file_get_contents($url);
		
		return explode('&',$result);	
	}
	
}




?>