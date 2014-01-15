<<<<<<< HEAD
<?php
// 筛选出网页数据
class HtmlJoinAction extends HtmlBaseAction {
    
	//trade所属行业，映射
	private $trade = array(
			/**
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
			**/
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
	);
	
	
	//所有店铺数据列表
	public function index () {
		import('@.ORG.Util.Page');
		$Join = D('Join');				//店铺模型表
		$File = D('File');				//文件表
		
		$map = array();	//筛选数据条件
		
		//其他逻辑条件
		$map['status'] = array('eq',0);	//正常状态
		
		$num = $Join->field('id')->where($map)->count();
		$Page = new Page($num,10);	//分页
		
		//去数据库查找对应的数据
		$purview = $Join->field('id,store_name,trade,estate,draw,lng,lat,address,city,start_business,classification,official,store_pic')->where($map)->limit($Page->firstRow.','.$Page->listRows)->select();

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
		
		
		//设置分页样式
		$Page->setConfig('prev','上一页');
		$Page->setConfig('next','下一页');
		$Page->setConfig('first','首页');
		$Page->setConfig('last','尾页');
		$Page->setConfig('theme','共(%totalRow%)条数据　%first%　%upPage%　%linkPage%　%downPage%　%end%　%nowPage%/%totalPage%页');
		$this->assign('page',$Page->show());
		
		$this->assign('purview',$purview);

		$this->display();
	}
	
	
	//编辑
	public function edit() {
		
		//初始化数据
		$Join = D('Join');				//店铺表
		$City = D('City');				//店铺表
		$FILE = D('File');				//文件表
		$Users = D('Users');			//用户表
		$id = $this->_get('id');		//指定店铺id
		
		if ($this->isPost()) {
			$this->check_data();		//验证数据

			$user_id = 1;			//提交用户的id
			$user_type = 0;			//用户类型
			$city_name = $_POST['city'];		//城市
			$store_phone = $_POST['store_phone'];	//店主手机号码
			
			
			/**
			 * 功能1、通过提交的城市名，搜索城市ID
			 */
			$city_id = parent::get_city_id($city_name);
			if (empty($city_id)) $this->error('城市名输入错误');
			
			/**
			 * 功能2、上传图片处理
			 */
			$store_pic = parent::upload_file($_FILES['store_pic'],1,'../'.C('UPLOAD_DIR.IMAGES'));			//上传文件名、上传文件类型、上传目录
			$licence_pic = parent::upload_file($_FILES['licence_pic'],1,'../'.C('UPLOAD_DIR.IMAGES'));
			if ($store_pic == false) $this->error('店铺图片不存在');
			if ($licence_pic == false) $this->error('店铺营业执照图片不存在');
			//店铺缩略图片类型为1
			$FILE->type = 1;
			$FILE->file_address = $store_pic[0]['savename'];
			$store_pic_id = $FILE->add();		//保存到数据库
			//营业执照图片
			$FILE->type = 2;
			$FILE->file_address = $licence_pic[0]['savename'];
			$licence_pic_id = $FILE->add();
			
			//修改店铺信息
			if (!empty($id)) {		
				$Join->create();									//表单提交数据
				$Join->city = $city_id;							//城市ID
				$Join->store_pic = $store_pic_id;			//店铺图片文件id
				$Join->licence_pic = $licence_pic_id;	//营业执照文件id
				$join_save = $Join->where(array('id'=>$id))->save();		//写入数据库
				$join_save ? $this->success('修改成功') : $this->error('没有数据被修改');

			//添加店铺信息
			} else {			
				
				/**
				 * 写入加盟店铺信息
				 */
				$Join->create();							//表单提交数据
				$Join->user_id = $user_id;			//提交用户ID
				$Join->store_uid = 1;					//店主ID
				$Join->city = $city_id;					//城市ID
				$Join->time = time();						//提交时间
				$Join->store_pic = $store_pic_id;			//店铺图片文件id
				$Join->licence_pic = $licence_pic_id;	//营业执照文件id
				$Join->status = 0;
				$join_status = $Join->add();		//写入数据库
				
				if ($join_status == true) {
					//返回客户端数据
					if (!empty($ShopKeeper_account)) {
						$this->success('店主尚未申请账号,系统为店主申请的账号为：'.$ShopKeeper_account['account'].'，密码默认为：'.$ShopKeeper_account['pass'].'。请记录下来。');
					} else {
						$this->success('提交成功');
					}
				} else {
					$this->error('提交失败，请重新尝试');
				}
				
			}

		} else {	//获取用户数据
			
			//数据库获取店铺数据
			$data = $Join->get_store_info($id);
			$store_data = $data[0];
			
			//对应商铺表中图片id指向的图片地址
			$store_data['city'] = $City->where(array('id'=>$store_data['city']))->getField('name');	//取得城市名
			//组合访问地址
			$this->assign('trade',$this->trade);
			$this->assign('store_data',$store_data);
			$this->display();
		}
		
		
	}
	
	
	//删除店铺
	public function del() {
		$Join = D('Join');				//店铺模型表
		$Join->del(array('id'=>$_GET['id'])) ? $this->success('删除成功！') : $this->error('删除失败！');

	}
	
	

	//待审核列表
	public function auditing(){
		$Join = D('Join');
		$list = $Join->seek_auditing_data();
	
		parent::public_file_dir($list,array('store_pic','licence_pic'),'images/');
		
		$this->assign('list',$list);
		$this->display();
    }
    

    //审核通过
    public function adopt () {
    	$id = $this->_get('id');
    	$Join = D('Join');
    	$status = $Join->where(array('id'=>$id))->data(array('status'=>0))->save();
    	if ($status) {
    		
    		//系统消息通知
    		$user_id = $Join->where(array('id'=>$id))->getField('store_uid');
    		$system = array(
				'type'=> 'notify',
				'user_id' => $user_id,
				'content' => date('Y-m-d H:i').'　店铺审核已通过',
			);
			parent::system_message($system);
			
    		$this->success('已通过');
    	} else {
    		$this->error('通过失败');
    	}
    }

    
    //验证提交数据
    private function check_data() {
    	import("@.Tool.Validate");							//验证类
    	//数据验证
    	if (Validate::checkNull($_POST['store_name'])) $this->error('店铺名不得为空');
    	if (Validate::checkNull($_POST['store_phone'])) $this->error('手机号码不得为空');
    //	if (!Validate::checkPhone($_POST['store_phone'])) $this->error('必须为11位的手机号码');
    	if (Validate::checkNull($_POST['address'])) $this->error('地址不得为空');
    	if (Validate::checkNull($_POST['lng'])) $this->error('经度不得为空');
    	if (Validate::checkNull($_POST['lat'])) $this->error('纬度不得为空');
    	if (Validate::checkNull($_POST['classification'])) $this->error('请选择店铺属性');
    	if (Validate::checkNull($_POST['official'])) $this->error('负责人不得为空');
    }
=======
<?php
// 筛选出网页数据
class HtmlJoinAction extends HtmlBaseAction {
    
	//trade所属行业，映射
	private $trade = array(
			/**
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
			**/
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
	);
	
	
	//所有店铺数据列表
	public function index () {
		import('@.ORG.Util.Page');
		$Join = D('Join');				//店铺模型表
		$File = D('File');				//文件表
		
		$map = array();	//筛选数据条件
		
		//其他逻辑条件
		$map['status'] = array('eq',0);	//正常状态
		
		$num = $Join->field('id')->where($map)->count();
		$Page = new Page($num,10);	//分页
		
		//去数据库查找对应的数据
		$purview = $Join->field('id,store_name,trade,estate,draw,lng,lat,address,city,start_business,classification,official,store_pic')->where($map)->limit($Page->firstRow.','.$Page->listRows)->select();

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
		
		
		//设置分页样式
		$Page->setConfig('prev','上一页');
		$Page->setConfig('next','下一页');
		$Page->setConfig('first','首页');
		$Page->setConfig('last','尾页');
		$Page->setConfig('theme','共(%totalRow%)条数据　%first%　%upPage%　%linkPage%　%downPage%　%end%　%nowPage%/%totalPage%页');
		$this->assign('page',$Page->show());
		
		$this->assign('purview',$purview);

		$this->display();
	}
	
	
	//编辑
	public function edit() {
		
		//初始化数据
		$Join = D('Join');				//店铺表
		$City = D('City');				//店铺表
		$FILE = D('File');				//文件表
		$Users = D('Users');			//用户表
		$id = $this->_get('id');		//指定店铺id
		
		if ($this->isPost()) {
			$this->check_data();		//验证数据

			$user_id = 1;			//提交用户的id
			$user_type = 0;			//用户类型
			$city_name = $_POST['city'];		//城市
			$store_phone = $_POST['store_phone'];	//店主手机号码
			
			
			/**
			 * 功能1、通过提交的城市名，搜索城市ID
			 */
			$city_id = parent::get_city_id($city_name);
			if (empty($city_id)) $this->error('城市名输入错误');
			
			/**
			 * 功能2、上传图片处理
			 */
			$store_pic = parent::upload_file($_FILES['store_pic'],1,'../'.C('UPLOAD_DIR.IMAGES'));			//上传文件名、上传文件类型、上传目录
			$licence_pic = parent::upload_file($_FILES['licence_pic'],1,'../'.C('UPLOAD_DIR.IMAGES'));
			if ($store_pic == false) $this->error('店铺图片不存在');
			if ($licence_pic == false) $this->error('店铺营业执照图片不存在');
			//店铺缩略图片类型为1
			$FILE->type = 1;
			$FILE->file_address = $store_pic[0]['savename'];
			$store_pic_id = $FILE->add();		//保存到数据库
			//营业执照图片
			$FILE->type = 2;
			$FILE->file_address = $licence_pic[0]['savename'];
			$licence_pic_id = $FILE->add();
			
			//修改店铺信息
			if (!empty($id)) {		
				$Join->create();									//表单提交数据
				$Join->city = $city_id;							//城市ID
				$Join->store_pic = $store_pic_id;			//店铺图片文件id
				$Join->licence_pic = $licence_pic_id;	//营业执照文件id
				$join_save = $Join->where(array('id'=>$id))->save();		//写入数据库
				$join_save ? $this->success('修改成功') : $this->error('没有数据被修改');

			//添加店铺信息
			} else {			
				
				/**
				 * 写入加盟店铺信息
				 */
				$Join->create();							//表单提交数据
				$Join->user_id = $user_id;			//提交用户ID
				$Join->store_uid = 1;					//店主ID
				$Join->city = $city_id;					//城市ID
				$Join->time = time();						//提交时间
				$Join->store_pic = $store_pic_id;			//店铺图片文件id
				$Join->licence_pic = $licence_pic_id;	//营业执照文件id
				$Join->status = 0;
				$join_status = $Join->add();		//写入数据库
				
				if ($join_status == true) {
					//返回客户端数据
					if (!empty($ShopKeeper_account)) {
						$this->success('店主尚未申请账号,系统为店主申请的账号为：'.$ShopKeeper_account['account'].'，密码默认为：'.$ShopKeeper_account['pass'].'。请记录下来。');
					} else {
						$this->success('提交成功');
					}
				} else {
					$this->error('提交失败，请重新尝试');
				}
				
			}

		} else {	//获取用户数据
			
			//数据库获取店铺数据
			$data = $Join->get_store_info($id);
			$store_data = $data[0];
			
			//对应商铺表中图片id指向的图片地址
			$store_data['city'] = $City->where(array('id'=>$store_data['city']))->getField('name');	//取得城市名
			//组合访问地址
			$this->assign('trade',$this->trade);
			$this->assign('store_data',$store_data);
			$this->display();
		}
		
		
	}
	
	
	//删除店铺
	public function del() {
		$Join = D('Join');				//店铺模型表
		$Join->del(array('id'=>$_GET['id'])) ? $this->success('删除成功！') : $this->error('删除失败！');

	}
	
	

	//待审核列表
	public function auditing(){
		$Join = D('Join');
		$list = $Join->seek_auditing_data();
	
		parent::public_file_dir($list,array('store_pic','licence_pic'),'images/');
		
		$this->assign('list',$list);
		$this->display();
    }
    

    //审核通过
    public function adopt () {
    	$id = $this->_get('id');
    	$Join = D('Join');
    	$status = $Join->where(array('id'=>$id))->data(array('status'=>0))->save();
    	if ($status) {
    		
    		//系统消息通知
    		$user_id = $Join->where(array('id'=>$id))->getField('store_uid');
    		$system = array(
				'type'=> 'notify',
				'user_id' => $user_id,
				'content' => date('Y-m-d H:i').'　店铺审核已通过',
			);
			parent::system_message($system);
			
    		$this->success('已通过');
    	} else {
    		$this->error('通过失败');
    	}
    }

    
    //验证提交数据
    private function check_data() {
    	import("@.Tool.Validate");							//验证类
    	//数据验证
    	if (Validate::checkNull($_POST['store_name'])) $this->error('店铺名不得为空');
    	if (Validate::checkNull($_POST['store_phone'])) $this->error('手机号码不得为空');
    //	if (!Validate::checkPhone($_POST['store_phone'])) $this->error('必须为11位的手机号码');
    	if (Validate::checkNull($_POST['address'])) $this->error('地址不得为空');
    	if (Validate::checkNull($_POST['lng'])) $this->error('经度不得为空');
    	if (Validate::checkNull($_POST['lat'])) $this->error('纬度不得为空');
    	if (Validate::checkNull($_POST['classification'])) $this->error('请选择店铺属性');
    	if (Validate::checkNull($_POST['official'])) $this->error('负责人不得为空');
    }
>>>>>>> 875753ed9bff50cc845fe9ec340485710b57dc3a
}