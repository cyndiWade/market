<?php

/**
 * 基础类
 */
class HtmlBaseAction extends BaseAction {
	
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
	
		
	}
	
	

	
}


?>