<<<<<<< HEAD
<?php

/**
 * Api基础类----通用的方法
 */
class ApiBaseAction extends BaseAction {
	
	/**
	 * 保存用户信息，供子类调用
	 */
	protected $oUser;					//用户数据
	
	/**
	 * 子类继承后，重写此方法，用来验证需要用户身份的数据
	 * 如：
		protected $aVerify = array(
			'login',	//标示login控制器需要验证用户身份，否则拒绝访问
		);
	 */
	protected $aVerify = array();	//需要验证的方法
	
	
	
	/**
	 * 构造方法
	 */
	public function __construct() {
		parent:: __construct();			//重写父类构造方法
		
		//初始化
		$this->_init();
	}
	
	
	//初始化用户数据
	private function _init() {
		
// 		$demo = array(
// 			'id'=>'1',
// 			'account'=>'user1',
// 			'type'=>'1',
// 		) ;
// 		 $this->oUser = (object) $demo;
		 
		//验证需要登录的模块
		if (in_array(ACTION_NAME,$this->aVerify)) {
			
			if (empty($this->oUser)) {
				$this->deciphering_user_info();
			} 
		} 
	}
	
	
	/**
	 * 解密客户端秘钥，获取用户数据
	 */
	private function deciphering_user_info() {
		//获取加密身份标示
		$identity_encryption = $this->_post('user_key');		
		
		//解密获取用户数据
		$decrypt = passport_decrypt($identity_encryption,C('UNLOCAKING_KEY'));	
		$user_info = explode(':',$decrypt);		
		$uid = $user_info[0];				//用户id
		$account = $user_info[1];		//用户账号
		$date = $user_info[2];			//账号时间

		//安全过滤
		if (count($user_info) < 3) $this->callback(C('STATUS_OTHER'),'身份验证失败');			
		if (countDays($date,date('Y-m-d'),1) >= 30 ) $this->callback(C('STATUS_NOT_LOGIN'),'登录已过期，请重新登录');		//钥匙过期时间为30天

		//去数据库获取用户数据
		$user_data = D('Users')->field('id,account,type,name')->where(array('id'=>$uid,'status'=>0))->find();

		if (empty($user_data)) {
			$this->callback(C('STATUS_NOT_DATA'),'此用户不存在，或被禁用');
		} else {
			$this->oUser = (object) $user_data;
		}
		
	}
	
	
	/**
	 * 统一数据返回
	 * @param unknown_type $status
	 * @param unknown_type $msg
	 * @param unknown_type $data
	 */
	protected function callback($status, $msg = 'Yes!',$data = array()) {
		$return = array(
			'status' => $status,
			'msg' => $msg,
			'data' => $data,
			'num' => count($data),
		);
		
		header('Content-Type:text/html;charset=utf-8');

		//die(json_encode($return));
		die(JSON($return));
	}
	
	
	
	
	
	
	
	
	
}


=======
<?php

/**
 * Api基础类----通用的方法
 */
class ApiBaseAction extends BaseAction {
	
	/**
	 * 保存用户信息，供子类调用
	 */
	protected $oUser;					//用户数据
	
	/**
	 * 子类继承后，重写此方法，用来验证需要用户身份的数据
	 * 如：
		protected $aVerify = array(
			'login',	//标示login控制器需要验证用户身份，否则拒绝访问
		);
	 */
	protected $aVerify = array();	//需要验证的方法
	
	
	
	/**
	 * 构造方法
	 */
	public function __construct() {
		parent:: __construct();			//重写父类构造方法
		
		//初始化
		$this->_init();
	}
	
	
	//初始化用户数据
	private function _init() {
		
// 		$demo = array(
// 			'id'=>'1',
// 			'account'=>'user1',
// 			'type'=>'1',
// 		) ;
// 		 $this->oUser = (object) $demo;
		 
		//验证需要登录的模块
		if (in_array(ACTION_NAME,$this->aVerify)) {
			
			if (empty($this->oUser)) {
				$this->deciphering_user_info();
			} 
		} 
	}
	
	
	/**
	 * 解密客户端秘钥，获取用户数据
	 */
	private function deciphering_user_info() {
		//获取加密身份标示
		$identity_encryption = $this->_post('user_key');		
		
		//解密获取用户数据
		$decrypt = passport_decrypt($identity_encryption,C('UNLOCAKING_KEY'));	
		$user_info = explode(':',$decrypt);		
		$uid = $user_info[0];				//用户id
		$account = $user_info[1];		//用户账号
		$date = $user_info[2];			//账号时间

		//安全过滤
		if (count($user_info) < 3) $this->callback(C('STATUS_OTHER'),'身份验证失败');			
		if (countDays($date,date('Y-m-d'),1) >= 30 ) $this->callback(C('STATUS_NOT_LOGIN'),'登录已过期，请重新登录');		//钥匙过期时间为30天

		//去数据库获取用户数据
		$user_data = D('Users')->field('id,account,type,name')->where(array('id'=>$uid,'status'=>0))->find();

		if (empty($user_data)) {
			$this->callback(C('STATUS_NOT_DATA'),'此用户不存在，或被禁用');
		} else {
			$this->oUser = (object) $user_data;
		}
		
	}
	
	
	/**
	 * 统一数据返回
	 * @param unknown_type $status
	 * @param unknown_type $msg
	 * @param unknown_type $data
	 */
	protected function callback($status, $msg = 'Yes!',$data = array()) {
		$return = array(
			'status' => $status,
			'msg' => $msg,
			'data' => $data,
			'num' => count($data),
		);
		
		header('Content-Type:text/html;charset=utf-8');

		//die(json_encode($return));
		die(JSON($return));
	}
	
	
	
	
	
	
	
	
	
}


>>>>>>> 875753ed9bff50cc845fe9ec340485710b57dc3a
?>