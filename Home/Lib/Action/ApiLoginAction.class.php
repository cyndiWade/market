<?php

/**
 * 用户登录注册控制器
 */
class ApiLoginAction extends ApiBaseAction {
	
	//需要验证的模块
	protected $aVerify = array(
		
	);
	
	//登录验证
	public function login () {
		
		if ($this->isPost()) {
			$Users = D('Users');									//用户表模型
			
			$account = $_POST['account'];					//用户账号
			$password = md5($_POST['password']);	//用户密码
			
			$this->check_me();									//验证提交数据
			
			//数据库验证用户信息
			$user_info = $Users->get_user_info(array('account'=>$account,'status'=>0));
			if (empty($user_info)) {
				parent::callback(C('STATUS_NOT_DATA'),'此用户不存在，或已被禁用');
			} else {
				if ($password != $user_info['password']) {
					parent::callback(C('STATUS_OTHER'),'密码错误');
				} else {
					
					//生成秘钥
					$encryption = $user_info['id'].':'.$user_info['account'].':'.date('Y-m-d');					//生成解密后的数据
					$identity_encryption = passport_encrypt($encryption,C('UNLOCAKING_KEY'));	//生成加密字符串,给客户端
					
					//更新用户登录信息
					$Users->up_login_info($user_info['id']);
					
					//读取系统消息
					$condition = array(
						'type' => 'seek',
						'user_id' => $user_info['id'],
					);
					$system_message = parent::system_message($condition);		
					if (empty($system_message)) $system_message = array(array('content'=>'暂无消息'));
					
					//返回给客户端数据
					parent::callback(C('STATUS_SUCCESS'),'登录成功',
						array(
							'user_key'=>$identity_encryption,
							'account'=>$user_info['account'],
							'name'=>$user_info['name'],
							'xmpp_account'=>$user_info['account'].'@'.C('OPEN_FIRE.host'),
							'type'=>$user_info['type'],
							'system_message'=>$system_message
						)
					);
				}	
			}
		}
		//$this->display('Login:register');
	}
	
	
	//用户注册	
	public function register () {	
		if ($this->isPost()) {		
			//验证提交数据
			$this->check_me();								
			//初始化数据
			$Users = D('Users');							//用户表模型	
			$account = $_POST['account'];			//注册账号
	
			//短信验证模块
			parent::check_verify($account,1);			//验证类型1为注册验证
			
			//账号验证、数据写入模块
			$is_have = $Users->account_is_have($account);		//查看账号是否存在
			if ($is_have) {
				parent::callback(C('STATUS_OTHER'),'此账号已存在');
			} else {		//添加注册用户
				$Users->create();
				$Users->add_account(C('ACCOUNT_TYPE.USER')) ? parent::callback(C('STATUS_SUCCESS'),'注册成功') : parent::callback(C('STATUS_UPDATE_DATA'),'注册失败');
			}
		} 
			
		//$this->display('Login:register');
	}
	

	//验证提交数据
	private function check_me() {
		import("@.Tool.Validate");							//验证类
		//数据验证
		if (Validate::checkNull($_POST['account'])) parent::callback(C('STATUS_OTHER'),'账号为空');
		if ($_POST['account'] != 'admin') {
			if (!Validate::checkPhone($_POST['account'])) parent::callback(C('STATUS_OTHER'),'账号必须为11位的手机号码');
		}
		if (Validate::checkNull($_POST['password'])) parent::callback(C('STATUS_OTHER'),'密码为空');		
	}
	
	


	
}

?>