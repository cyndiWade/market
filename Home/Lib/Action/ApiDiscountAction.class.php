<<<<<<< HEAD
<?php

/**
 * 折扣优惠类
 * @author Administrator
 */
class ApiDiscountAction extends ApiBaseAction {
	
	//需要身份验证的模块
	protected $aVerify = array(
		
	);
	
	//优惠数据列表,按照城市id来搜索
	public function discount_list () {
		
		if ($this->isPost()) {
			$DB	= M();		//连接数据库
			
			//获取城市ID
			$city_id = parent::get_city_id($_POST['city']);
			if (empty($city_id)) parent::callback(C('STATUS_NOT_DATA'),'没有找到该城市，请重试');
			
			//获取指定城市，打折优惠数据
			$list = $DB->table('lh_discount AS d')->join('lh_file AS f ON d.pic_id = f.id')->field('d.store_id,d.content,f.file_address')->where( array('d.city_id'=>$city_id,'d.status'=>0,'f.type'=>3))->select();
			if (empty($list)) parent::callback(C('STATUS_NOT_DATA'),'此城市无打折优惠数据',$list);
			
			//组合访问地址
			parent::public_file_dir($list,'file_address','images/');
			
			//输出
			parent::callback(C('STATUS_SUCCESS'),'获取成功',$list);
		}
		
		//$this->display('Login:city');
		
	}
	

	
	
	
}

=======
<?php

/**
 * 折扣优惠类
 * @author Administrator
 */
class ApiDiscountAction extends ApiBaseAction {
	
	//需要身份验证的模块
	protected $aVerify = array(
		
	);
	
	//优惠数据列表,按照城市id来搜索
	public function discount_list () {
		
		if ($this->isPost()) {
			$DB	= M();		//连接数据库
			
			//获取城市ID
			$city_id = parent::get_city_id($_POST['city']);
			if (empty($city_id)) parent::callback(C('STATUS_NOT_DATA'),'没有找到该城市，请重试');
			
			//获取指定城市，打折优惠数据
			$list = $DB->table('lh_discount AS d')->join('lh_file AS f ON d.pic_id = f.id')->field('d.store_id,d.content,f.file_address')->where( array('d.city_id'=>$city_id,'d.status'=>0,'f.type'=>3))->select();
			if (empty($list)) parent::callback(C('STATUS_NOT_DATA'),'此城市无打折优惠数据',$list);
			
			//组合访问地址
			parent::public_file_dir($list,'file_address','images/');
			
			//输出
			parent::callback(C('STATUS_SUCCESS'),'获取成功',$list);
		}
		
		//$this->display('Login:city');
		
	}
	

	
	
	
}

>>>>>>> 875753ed9bff50cc845fe9ec340485710b57dc3a
?>