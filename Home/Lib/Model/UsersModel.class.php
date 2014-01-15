<<<<<<< HEAD
<?php

//用户数据模型
class UsersModel extends BaseModel {
	
	
	//添加账号
	public function add_account($type) {
		//写入数据库
		$this->password = md5($this->password);
		$time = time();
		$this->last_login_time = $time;
		$this->last_login_ip = get_client_ip();
		$this->create_time = $time;
		$this->update_time = $time;
		$this->type = $type;				//用户类型
		return $this->add();
	}
	
	
	//通过账号验证账号是否存在
	public function account_is_have ($account) {

		return $this->where(array('account'=>$account,'status'=>0))->getField('id');
	}
	
	//获取账号数据
	public function get_user_info ($condition) {
		return $this->where($condition)->find();
	}
	
	
	//更新登录信息
	public function up_login_info ($uid) {
		$time = time();
		$this->last_login_time = $time;
		$this->last_login_ip = get_client_ip();
		$this->login_count = $this->login_count + 1; 
			
		$this->where(array('id'=>$uid))->save();
	
	}
	
	
}

?>
=======
<?php

//用户数据模型
class UsersModel extends BaseModel {
	
	
	//添加账号
	public function add_account($type) {
		//写入数据库
		$this->password = md5($this->password);
		$time = time();
		$this->last_login_time = $time;
		$this->last_login_ip = get_client_ip();
		$this->create_time = $time;
		$this->update_time = $time;
		$this->type = $type;				//用户类型
		return $this->add();
	}
	
	
	//通过账号验证账号是否存在
	public function account_is_have ($account) {

		return $this->where(array('account'=>$account,'status'=>0))->getField('id');
	}
	
	//获取账号数据
	public function get_user_info ($condition) {
		return $this->where($condition)->find();
	}
	
	
	//更新登录信息
	public function up_login_info ($uid) {
		$time = time();
		$this->last_login_time = $time;
		$this->last_login_ip = get_client_ip();
		$this->login_count = $this->login_count + 1; 
			
		$this->where(array('id'=>$uid))->save();
	
	}
	
	
}

?>
>>>>>>> 875753ed9bff50cc845fe9ec340485710b57dc3a
