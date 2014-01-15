<?php

/**
 * 	全局参数类
 */
class GlobalParameterAction extends Action {
	
	
	//trade所属行业，映射
	protected  $trade = array(
			1 => '饰品',
			2 => '化妆品',	//新增
			3 => '古董',
			4 => '围巾服饰',
			5 => '工艺品',
			6 => '手机配件',
			7 => '文具',
			8 => '服装辅料',
			9 => '毛巾',
			10 => '玩具动漫',
			11 => '箱包皮具',
			12 => '袜子',
			13 => '钓鱼风筝',
	);
	
	
	/**
	 * 构造方法
	 */
	public function __construct() {
		parent:: __construct();			//重写父类构造方法
	}

	

	
}


?>