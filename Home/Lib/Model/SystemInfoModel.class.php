<<<<<<< HEAD
<?php

//系统消息表
class SystemInfoModel extends BaseModel {
	
	
	//获取系统信息
	public function get_sys_mess() {
		$data =  $this->field('content,time')->where(array('status'=>0))->select();
		parent::setTime($data);
		return $data;
	}
	

	
	
}

?>
=======
<?php

//系统消息表
class SystemInfoModel extends BaseModel {
	
	
	//获取系统信息
	public function get_sys_mess() {
		$data =  $this->field('content,time')->where(array('status'=>0))->select();
		parent::setTime($data);
		return $data;
	}
	

	
	
}

?>
>>>>>>> 875753ed9bff50cc845fe9ec340485710b57dc3a
