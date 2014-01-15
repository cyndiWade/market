<<<<<<< HEAD
<?php

/**
 * 消息留言表
 */
class ApiMessageAction extends ApiBaseAction {
	
	//需要身份验证的模块
	protected $aVerify = array(
		'get_system_info',
	);
	
	
	//
	public function get_mess() {
		
	}
	
	
	
	
	//获取系统消息
	public function get_system_info () {

		$SystemInfo = D('SystemInfo');
		$info = $SystemInfo->get_sys_mess();
		if ($info) {
			parent::callback(C('STATUS_SUCCESS'),'获取成功',$info);
		} else {
			parent::callback(C('STATUS_NOT_DATA'),'没有数据');
		}
	}
		
	
	//
	public function demo () {
		$data = M()->query("SELECT id,name FROM lh_city WHERE name Like '%市%'");
		header('Content-Type:text/html;charset=utf-8');

		echo JSON($data);


	}
	
	
	
	
}

=======
<?php

/**
 * 消息留言表
 */
class ApiMessageAction extends ApiBaseAction {
	
	//需要身份验证的模块
	protected $aVerify = array(
		'get_system_info',
	);
	
	
	//
	public function get_mess() {
		
	}
	
	
	
	
	//获取系统消息
	public function get_system_info () {

		$SystemInfo = D('SystemInfo');
		$info = $SystemInfo->get_sys_mess();
		if ($info) {
			parent::callback(C('STATUS_SUCCESS'),'获取成功',$info);
		} else {
			parent::callback(C('STATUS_NOT_DATA'),'没有数据');
		}
	}
		
	
	//
	public function demo () {
		$data = M()->query("SELECT id,name FROM lh_city WHERE name Like '%市%'");
		header('Content-Type:text/html;charset=utf-8');

		echo JSON($data);


	}
	
	
	
	
}

>>>>>>> 875753ed9bff50cc845fe9ec340485710b57dc3a
?>