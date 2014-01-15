<?php

/**
 * 收藏店铺
 * @author Administrator
 */
class ApiCollectAction extends ApiBaseAction {
	
	//需要身份验证的模块
	protected $aVerify = array(
		'add','show','del_collect'
	);
	
	
	//显示不同用户收藏的店铺
	public function show_collect () {
		$Collect = D('Collect');
		$user_id = $this->oUser->id;						//用户ID
	
		$data = $Collect->get_collect($user_id);		//查找收藏的店铺数据
		//组合访问地址
		parent::public_file_dir($data,'file_address','images/');
	
		if ($data == true) {
			parent::callback(C('STATUS_SUCCESS'),'已找到',$data);
		} else {
			parent::callback(C('STATUS_NOT_DATA'),'无收藏的店铺');
		}
	}
	
	//用户收藏店铺
	public function add_collect () {
		
		if ($this->isPost()) {
			//初始化数据
			$Collect = D('Collect');
			import("@.Tool.Validate");								//验证类
			$user_id = $this->oUser->id;							//用户ID
			$store_id = $this->_post('store_id');				//店铺id
			
			//数据验证	
			if (!Validate::checkNum($store_id)) parent::callback(C('STATUS_OTHER'),'店铺ID不存在');
	
			//添加用户关系
			$status = $Collect->add_collect($user_id,$store_id);
			
			if ($status == true) {
				parent::callback(C('STATUS_SUCCESS'),'添加成功');
			} else {
				parent::callback(C('STATUS_UPDATE_DATA'),'已添加');
			}
		}
		
	//	$this->display('Login:collect');
	}
	
	
	//删除我收藏的店铺
	public function del_collect () {
		
		if ($this->isPost()) {
			$Collect = D('Collect');
			import("@.Tool.Validate");							//验证类
			$user_id = $this->oUser->id;						//用户ID
			$store_id = $this->_post('store_id');			//店铺id
				
			//数据验证
			if (!Validate::checkNum($store_id)) parent::callback(C('STATUS_OTHER'),'店铺ID错误');
			
			//执行取消收藏
			$status = $Collect->del_collect($user_id,$store_id);
			
			if ($status == true) {
				parent::callback(C('STATUS_SUCCESS'),'取消成功');
			} else {
				parent::callback(C('STATUS_UPDATE_DATA'),'请勿重复操作');
			}	
		}
		
		//$this->display('Login:collect');
	}
	
	
	
	
	
}

?>