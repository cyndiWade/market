<?php

//收藏关系表
class CollectModel extends BaseModel {
	
	
	//添加收藏关系
	public function add_collect($user_id,$store_id) {
	
		//查找用户是否已经收藏此店铺
		$is_have = $this->where(array('user_id'=>$user_id,'store_id'=>$store_id))->getField('id');
		if ($is_have) {		//存在不处理
			$status = 0;	
		} else {					//不存在则添加
			$this->user_id = $user_id;				//用户id
			$this->store_id = $store_id;			//店铺ID
			$this->time = time();
			$this->add() ? $status = 1 : $status=0;
		}
		return $status;
	}
	
	
	//显示收藏的店铺信息
	public function get_collect ($user_id) {
		$data = $this->table('lh_collect AS c')
		->join('LEFT JOIN lh_join AS j ON c.store_id=j.id')
		->join('LEFT JOIN lh_file AS f ON j.store_pic=f.id ')
		->field('j.id,j.store_name,j.address,f.file_address')
		->where(array('c.user_id'=>$user_id))
		->select();
		return $data;
	}

	
	//查看收藏过此店铺的用户
	public function get_user_collect () {
		
	}
	
	
	
	//删除我收藏的店铺
	public function del_collect ($user_id,$store_id) {
		return $this->where(array('user_id'=>$user_id,'store_id'=>$store_id))->delete();
	}
	
	
	
}

?>
