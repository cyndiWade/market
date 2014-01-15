<?php
if (!defined('THINK_PATH'))exit();

$DB = require("config.inc.php");	//数据库配置

//系统配置
$system = array(
		//路由配置
		'URL_MODEL'             => 3,							//URL重写，兼容模式  如：home.php?s=/User/user   或者  home.php/User/user
		'URL_ROUTER_ON'   => true, 				//开启路由
		'PREV_URL' => $_SERVER["HTTP_REFERER"],		//上一页地址配置

		//session及时区配置
		'SESSION_AUTO_START' => true,				//session常开
		'DEFAULT_TIMEZONE'=>'Asia/Shanghai', 	// 设置默认时区
			
		/*以下添加扩展配置*/
		'OUTPUT_ENCODE' => false,
		'VAR_PAGE'=>'p',
		
		//上传文件目录
		'UPLOAD_DIR' => array(
			'IMAGES' => '/files/lehuo/images/',		//图片地址
		),
		
		//外部文件访问地址(用来填写专用的文件服务器)
		'PUBLIC_VISIT' => array(
			'DOMAIN' =>	'http://'.$_SERVER['SERVER_NAME'],
			'DIR' => '/files/lehuo/'							//项目文件目录
		),
		
		//客户端加密、解密钥匙
		'UNLOCAKING_KEY' => 'yuyuan',
		
		//用户类型
		'ACCOUNT_TYPE' => array (
			'ADMIN' => 0,			//管理员
			'USER' => 1,				//乐活族
			'AGENT' => 2,			//推销商
			'ShopKeeper' => 3	//店主
		),
		
		//短信平台账号
		'SHP' => array(
			'NAME'=>'lehuo_sz',
			'PWD'=>'lehuo8001'
		),
				
		//即时通讯配置
		'OPEN_FIRE' => array (
			'host' => $_SERVER['SERVER_NAME'],
			'port' => '5222',
			'prefix' => 'notice_',	//账户前缀
		),
);


$result_status = array (
		
	'STATUS_SUCCESS' => '0',					//没有错误
	'STATUS_NOT_LOGIN'	=> '1002',			//未登录
	'STATUS_UPDATE_DATA'	=> '2001',		//没有成功修改数据
	'STATUS_NOT_DATA'	=> '2004',			//没有这条数据
	'STATUS_OTHER' => '9999',					//其他错误
	
);

return array_merge($DB, $system,$result_status);
?>