<?php

/**
 * 用户加盟处理
 */
class ApiJoinAction extends ApiBaseAction {
	
	//需要身份验证的模块
	protected $aVerify = array(
		'join',
	);
	
	//trade所属行业，映射
	private $trade = array(
		/*
		1 => '医疗卫生',
		2 => '旅游',
		3 => '服装鞋帽',
		4 => '文化艺术',
		5 => '美容美发',
		6 => '休闲会所',
		7 => '酒吧茶馆',
		8 => '食品烟酒',
		9 => '汽车护理',
		10 => '房产楼盘',
		11 => '餐饮饭馆',
		12 => '酒店宾馆',
		13 => '教育培训',
		14 => '3C数码',
		15 => '其他行业',
	*/
		
		11 => '餐饮美食',
		16 => '超市便利店',	//新增
		2 => '旅游景点',
		12 => '酒店宾馆',		
		5 => '美容美发',
		6 => '休闲会所',
		7 => '酒吧茶馆',
		8 => '食品烟酒',
		9 => '汽车护理',
		10 => '房产楼盘',
		1 => '医疗卫生',
		4 => '文化艺术',
		
		13 => '教育培训',
		3 => '时尚服饰',
		25 => '丽人婚嫁',		//新增
		26 => '投资理财',		//新增
		17 => '小额代款',		//新增
		27 => '医疗门诊',		
		18 => '基督教堂',		//新增
		24 => '华企会',		//新增
		
		19 => '统驭系统',		//新增
		20 => '投资世界',		//新增
		21 => '文华商会',		//新增
		22	 => '财富银行',		//新增
		
		//下个28
				
		
	);
	
	//添加商铺
	public function join() {

		if ($this->isPost()) {
			//初始化数据
			$Join = D('Join');			//店铺表
			$City = D('City');			//店铺表
			$FILE = D('File');			//文件表
			$Users = D('Users');		//用户表

			$user_id = $this->oUser->id;			//提交用户的id
			$user_type = $this->oUser->type;			//用户类型
			$city_name = $_POST['city'];		//城市
			$store_phone = $_POST['store_phone'];	//店主手机号码
			
			/**
			 * 1、验证码验证模块
			 */
			parent::check_verify($store_phone,2);			//2:为加盟商铺验证
			
			/**
			 * 2、通过提交的城市名，搜索城市ID
			 */
			$city_id = parent::get_city_id($city_name);
			if (empty($city_id)) parent::callback(C('STATUS_NOT_DATA'),'没有找到该城市，请重试');
			
			/**
			 * 3、上传图片处理
			*/
			$store_pic = parent::upload_file($_FILES['store_pic'],1,'../'.C('UPLOAD_DIR.IMAGES'));			//上传文件名、上传文件类型、上传目录
			$licence_pic = parent::upload_file($_FILES['licence_pic'],1,'../'.C('UPLOAD_DIR.IMAGES'));		
			if ($store_pic == false) parent::callback(C('STATUS_OTHER'),'店铺图片上传失败，请重试');
			if ($licence_pic == false) parent::callback(C('STATUS_OTHER'),'店铺营业执照图片上传失败，请重试');
			//店铺缩略图片类型为1
			$FILE->type = 1;
			$FILE->file_address = $store_pic[0]['savename'];
			$store_pic_id = $FILE->add();		//保存到数据库
			//营业执照图片
			$FILE->type = 2;
			$FILE->file_address = $licence_pic[0]['savename'];
			$licence_pic_id = $FILE->add();
			
			/**
			 * 4、店主身份、生成店主账号模块
			 */
			$store_uid = $Users->account_is_have($store_phone);		//通过店主手机号查找店主ID		

			//如果填写的店主手机账号不存在，则为店主创建一个手机账号账号
			if (empty($store_uid)) {
				//为店主生成一条用户
				$Users->id = null;
				$Users->account = $store_phone;
				$Users->name = $store_phone;
				$Users->password = '123456';
				$store_uid = $Users->add_account(C('ACCOUNT_TYPE.ShopKeeper'));			//店主账号

				$ShopKeeper_account = array('account'=>$store_phone,'pass'=>123456);		//保存店主，生成的账号信息
			}
	
			if (empty($store_uid)) parent::callback(C('STATUS_NOT_DATA'),'店主身份验证错误');
			
			/**
			 * 5、写入加盟店铺信息
			 */
			$Join->create();							//表单提交数据			
			$Join->user_id = $user_id;			//提交用户ID
			$Join->store_uid = $store_uid;		//店主ID
			$Join->city = $city_id;					//城市ID
			$Join->time = time();						//提交时间
			$Join->store_pic = $store_pic_id;			//店铺图片文件id	
			$Join->licence_pic = $licence_pic_id;	//营业执照文件id	
			$Join->status = 0;
			$join_status = $Join->add();		//写入数据库

			if ($join_status == true) {
				//系统消息通知
				$system = array(
					'type'=> 'notify',
					'user_id' => $store_uid,
					'content' => ''.date('Y-m-d H:i').'　提交了店铺申请',
				);
				parent::system_message($system);
				
				//返回客户端数据
				if (!empty($ShopKeeper_account)) {
					parent::callback(C('STATUS_SUCCESS'),'提交成功,请等待审核结果。店主尚未申请账号,系统为店主申请的账号为：'.$ShopKeeper_account['account'].'，密码默认为：'.$ShopKeeper_account['pass'].'。请记录下来。');
				} else {
					parent::callback(C('STATUS_SUCCESS'),'提交成功，请等待审核结果');
				}
				
			} else {
				parent::callback(C('STATUS_UPDATE_DATA'),'提交失败，请重新登录后尝试');
			}

		}
		
		//$this->display('Login:join');
	}
	
	
	
	//获取筛选数据
	public function get_result () {
		header('Content-Type:text/html;charset=utf-8');
		
		//$lng = 121.505665;
		//$lat = 31.241402;
		
		$Join = D('Join');				//店铺模型表
		$File = D('File');				//文件表
		
		$lng = $this->_get('lng');	//经度
		$lat = $this->_get('lat');		//纬度
		$distance = isset($_GET['distance']) ? $_GET['distance'] : 100;				//范围KM
		$map = array();	//筛选数据条件
		
		//其他逻辑条件
		$map['status'] = array('eq',0);	//正常状态
		
		//组装查询条件
		foreach ($_GET AS $key=>$val) {
			if (!empty($val) && $key != '_URL_') {
				$map[$key] = array('eq',$val);		//组合查询条件
			} 
		}

		//获取边界值，用来匹配范围内经纬度数据
		$squares = _SquarePoint($lng,$lat,$distance);		
			
		//去数据库匹配，在此范围内的经纬度数据	
		$map['lat'] = array(
			array('neq',0),		//纬度不等于0
			array('gt',$squares['right-bottom']['lat']),	//纬度>右下点
			array('lt',$squares['left-top']['lat']),				//纬度<左上
		'AND') ;
		$map['lng'] = array(						
			array('gt',$squares['left-top']['lng']),			//经度>左上
			array('lt',$squares['right-bottom']['lng']),	//经度<又下
		'AND') ;
		
		//去数据库查找对应的数据
		$purview = $Join->field('id,store_name,trade,estate,draw,lng,lat,address,store_phone,city,start_business,classification,official,store_pic')->where($map)->select();	
		if (empty($purview)) parent::callback(C('STATUS_NOT_DATA'),'没有数据');
		
		$store_ids = array();	//保存店铺图片id
		//计算当前经纬度，与匹配到的经纬之间的距离。单位为km
		foreach ($purview AS $key=>$val) {
			$purview[$key]['distance'] = round(GetDistance($lat,$lng,$val['lat'], $val['lng']),2);
			array_push($store_ids,$val['store_pic']);			//取得店铺的图片
		}
		
		//去文件表中取得店铺的图片地址信息
		$store_ids = implode(',',$store_ids);
		$store_pics = $File->get_store_pic($store_ids);		//图片信息
		$store_pics = regroupKey($store_pics,'id');
		
		//组合图片访问地址
		parent::public_file_dir($store_pics,'file_address','images/');
		//对应商铺表中图片id指向的图片地址
		foreach ($purview AS $key=>$val) {
			$purview[$key]['store_pic'] = $store_pics[$val['store_pic']]['file_address'];
			$purview[$key]['trade'] = $this->trade[$val['trade']];
		}
		
		//按照距离由近到远排序，
		$purview = quickSort($purview,'distance');
		
		//去openfire服务器，获取用户在线状态
		$host = C('OPEN_FIRE.host');		//域名地址
		foreach ($purview AS $key=>$val) {
			$purview[$key]['distance'] .= 'Km';		//添加KM字符	
			$purview[$key]['shop_jid'] = empty($val['store_phone']) ? '' : $val['store_phone'].'@'.C('OPEN_FIRE.host');		//添加店铺xmpp账号

			$presence = simplexml_load_file('http://'.$host.':9090/plugins/presence/status?jid='.$val['store_phone'].'@'.$host.'&type=text');
			if (!empty($presence)) {
				if ($presence->priority < 1) {
					//离线
					$purview[$key]['priority'] = 'unavailable';
				} else {
					//在线
					$purview[$key]['priority'] = 'available';
				}	
				
			}
			$presence = NULL;	//销毁对象
			 
			
			/**
			 $presence = file_get_contents('http://'.$host.':9090/plugins/presence/status?jid='.$val['store_phone'].'@'.$host.'&type=text');
			if (find_string($presence,'Online')) {
				$purview[$key]['priority'] = 'available';
			} else {
				$purview[$key]['priority'] = 'unavailable';
			}
			 */
			
		}

		//输出给客户端
		parent::callback(C('STATUS_SUCCESS'),'获取成功',$purview);
		
	}

	
	//上线店铺详细信息
	public function join_info () {
		$Join = D('Join');		//店铺模型表
		$id = $this->_get('id');		//指定店铺id
		
		//数据库获取店铺数据
		$data = $Join->get_store_info($id);
		if (empty($data)) parent::callback(C('STATUS_NOT_DATA'),'没有数据');
		
		//对应商铺表中图片id指向的图片地址
		foreach ($data AS $key=>$val) {
			$data[$key]['trade'] = $this->trade[$val['trade']];
		}
		
		//组合访问地址
		parent::public_file_dir($data,'file_address','images/');
		
		//返回给客户端
		parent::callback(C('STATUS_SUCCESS'),'获取成功',$data);
	}
	
	
	
	
	
}

?>