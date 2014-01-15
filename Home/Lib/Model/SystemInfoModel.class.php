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
