<?php

/**
 * 核心类基础类
 */
class BaseAction extends Action {
	
	/**
	 * 构造方法
	 */
	public function __construct() {
		parent:: __construct();			//重写父类构造方法
		
	}

	
	/**
	 * 组合图片外部访问地址
	 * @param Array $arr								//要组合地址的数组
	 * @param String Or Array	 $field			//组合的字段key  如：pic 或  array('pic','head')
	 * @param String $dir_type						//目录类型  如：/images
	 */
	protected function public_file_dir (Array &$arr,$field,$dir_type) {
		$public_file_dir =  C('PUBLIC_VISIT.DOMAIN').C('PUBLIC_VISIT.DIR').$dir_type;			//域名、文件目录
		
		//递归
		if (is_array($field)) {		
			for ($i=0;$i<count($field);$i++) {
				self::public_file_dir($arr,$field[$i],$dir_type);
			}			
		} else {	
			foreach ($arr AS $key=>$val) {
				$arr[$key][$field] = $public_file_dir.$val[$field];
			}
		}
	}
	
	
	/**
	 * 系统消息通知与查询
	 * @param array $arr	//通知内容
	 */
	protected function system_message(Array $arr) {
		$SystemMessage = D('SystemMessage');		//系统消息表
	
		if ($arr['type'] == 'notify') {		//添加通知信息
			return $SystemMessage->add_message_data($arr['user_id'],$arr['content']);
		} elseif ($arr['type'] == 'seek') {		//查询通知信息
			return $SystemMessage->seek_message_date($arr['user_id']);
		}
	
	}
	
	
	/**
	 * 城市映射，通过城市名，获取城市id
	 * @param String $city_name		//市级城市名
	 */
	protected function get_city_id ($city_name) {
		$City = D('City');		//店铺模型表
		$all_city = $City->get_city_cache();			//读取城市缓存数据
		foreach ($all_city AS $val) {			//获取匹配后的城市id
			if (find_string($val['name'],$city_name)) {
				$city = $val['id'];
				break;
			}
		}
		return $city;
	}
	
	/**
	 * 上传文件
	 * @param Array    $file  $_FILES['pic']	  上传的数组
	 * @param String   $type   上传文件类型    pic为图片
	 * @return Array	  上传成功返回文件保存信息，失败返回错误信息
	 */
	protected function upload_file($file,$type,$dir) {
		import('@.ORG.Util.UploadFile');				//引入上传类
	
		$upload = new UploadFile();
		$upload->maxSize  = 3145728 ;			// 设置附件上传大小
		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');		// 上传文件的(后缀)（留空为不限制），，
		//上传保存
		$upload->savePath =  $dir;					// 设置附件上传目录
		$upload->autoSub = true;					// 是否使用子目录保存上传文件
		$upload->subType = 'date';					// 子目录创建方式，默认为hash，可以设置为hash或者date日期格式的文件夹名
		$upload->saveRule =  'uniqid';				// 上传文件的保存规则，必须是一个无需任何参数的函数名
			
		//执行上传
		$execute = $upload->uploadOne($file);
		//执行上传操作
		if(!$execute) {						// 上传错误提示错误信息
			//$upload->getErrorMsg();
			return false;
		}else{	//上传成功 获取上传文件信息
			return $execute;
		}
	}
	
	
	/**
	 * 短信验证模块
	 * @param String $telephone		//验证的手机号码
	 * @param Number $type				//验证类型：1为注册验证，2为商铺验证
	 */
	protected function check_verify ($telephone,$type) {
		$Verify = D('Verify');							//短信表
		$verify_code = $_POST['verify'];		//短信验证码
	
		$shp_info = $Verify->seek_verify_data($telephone,$type);
	
		//手机验证码验证
		if (empty($shp_info)) {
			self::callback(C('STATUS_NOT_DATA'),'验证码不存在');
		} elseif ($verify_code != $shp_info['verify']) {
			self::callback(C('STATUS_OTHER'),'验证码错误');
		} elseif ($shp_info['expired'] - time() < 0 ) {
			self::callback(C('STATUS_OTHER'),'验证码已过期');
		}
		//把验证码状态变成已使用
		$Verify->save_verify_status($shp_info['id']);
	}
	
	
}


?>