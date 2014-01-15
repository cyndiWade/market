<?php

//系统通知用户信息表
class SystemMessageModel extends BaseModel {
	
	
	//系统发送消息给用户
	public function add_message_data ($user_id,$content) {
		 $this->user_id = $user_id;
		 $this->content = $content;
		 $this->time = time();
		 return $this->add();
	}
	
	
	//读取指定用户的系统消息
	public function seek_message_date ($user_id) {
		$data =  $this->field('content,time')->where(array('user_id'=>$user_id))->order('id DESC')->select();
		parent::setTime($data);
		return $data;
	}
	
}

?>
