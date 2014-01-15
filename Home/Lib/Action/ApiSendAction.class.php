<?php

/**
 * 短信发送类
 */
class ApiSendAction extends ApiBaseAction {
	
	private $telephone;			//目标手机号码
	private $verify;					//短信验证码
	private $msg;					//短信内容
	private $date;					//发送时间
	private $send_status;		//发送状态

	//需要身份验证的模块
	protected $aVerify = array(
		'store_send'
	);

	//构造方法
	public function __construct() {
		parent::__construct();
		//短信内容
		$this->verify =  mt_rand(111111,999999);		//生成随机验证码
		$this->date = date('Y-m-d H:i');						//日期
	}
	
	
	//短信发送
	private function _send () {
		//请求数据
		if (empty($this->telephone)) {
			parent::callback(C('STATUS_OTHER'),'电话号码不存在');
		} elseif (!preg_match("/^1[358]\d{9}$/", $this->telephone)) {
			parent::callback(C('STATUS_OTHER'),'电话号码格式错误');
		}
	
		//执行发送短信
		import("@.Tool.SHP");	//SHP短信发送类
		$SHP = new SHP(C('SHP.NAME'),C('SHP.PWD'));			//账号信息
		$send = $SHP->send($this->telephone,$this->msg);		//执行发送
		$send_status = explode('=',$send[0]);		//对回馈内容处理
		$send_status = $send_status[1];
		
		$this->send_status = $send_status;		//发送后的状态
	}
	
	
	//保存到数据库
	private function _add_data ($type) {
		$this->_send();	//发送短信
	
		//对发送结果进行处理
		if ($this->send_status > 0) {		//发送成功后
			//写入数据库
			$Verify = M('Verify');										//短信表
			$Verify->telephone = $this->telephone;			//电话号码
			$Verify->verify = $this->verify;						//验证码
			$Verify->expired = strtotime('+5 minute',time());				//过期时间设置为3分钟后
			$Verify->type = $type;													//过期时间设置为3分钟后
			//写入数据库
			$Verify->add() ? parent::callback(C('STATUS_SUCCESS'),'发送成功') : parent::callback(C('STATUS_UPDATE_DATA'),'发送超时');
			//失败处理
		} else {
			parent::callback(C('STATUS_OTHER'),'发送失败，请检查网络');
		}
	}
	
	
	
	//注册发送短信
	public function register_send () {
		//手机号码
		if ($this->isPost()) {
			$this->telephone = $this->_post('telephone');		//电话号码
			$this->msg = '您在'.$this->date.' 提交了手机注册申请。6位验证码为'.$this->verify.'，5分钟后过期。如果没有请忽略';
			$this->_add_data(1);
		}
		//echo C('PUBLIC_VISIT.DOMAIN');
		//$this->assign('name','telephone');
		//$this->display('Login:sendSHP');
	}
	
	
	//店铺注册短信验证
	public function store_send () {
		//手机号码
		if ($this->isPost()) {
			$this->telephone = $this->_post('store_phone');		//电话号码
			$this->msg = '您在'.$this->date.' 提交了商铺申请验证。6位验证码为'.$this->verify.'，5分钟后过期。如果没有请忽略';
			$this->_add_data(2);
		}
	//	$this->assign('name','store_phone'); 
		//$this->display('Login:sendSHP');
	}
	
	
	
	
	
}

?>