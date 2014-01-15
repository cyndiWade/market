<?php

/**
 *	即时通讯借口
 */
class ApiOpenFireAction extends ApiBaseAction {
	
	//系统配置
	private $host;					//主机地址
	private $port;					//发送端口
	private $user;					//用户
	private $password;			//密码
	private $resource;			//资源

	
	//私有配置
	private $object;				//连接对象
	private $from;					//发送人
	private $to;						//接收人
	private $content;				//发送内容
	
	//需要验证的模块
	protected $aVerify = array(
		
	);
	
	/***
	 * 构造方法
	 */
	public function __construct() {
		parent::__construct();
		import('@.Vendor.XMPPHP.XMPP','','.php');//XMPP通讯库
		
		$this->host = C('OPEN_FIRE.host');			//主机地址
		$this->port = C('OPEN_FIRE.port');				//服务器端口
		$this->resource = 'xmpphp';							//资源---建议不要修改
		$this->prefix = C('OPEN_FIRE.prefix');		//项目前缀
		
	}

	
	/**
	 * 发送控制
	 * @param array $parameter
	 * 
	 //传送参数
	  $content = array(
			'from'=>'from',			发送者
			'to'=>'to',						收件人
			'content'=>'你好!',		内容
			'xml'=>'<xml>'				源XML文件
		);
	 */
	public function send_action (Array $parameter) {
		//发送人 = 项目前缀 + 发送者用户名
		$this->from = $this->prefix.$parameter['from'];		
		
		//发送人密码 = 发送人
		$this->pass = $this->from;			
						
		//接收人 = 项目前缀 + 接收者用户名 + @ + 服务器主机地址
		$this->to = $this->prefix.$parameter['to'].'@'.$this->host;		
			
		//发送内容
		$this->content = $parameter['content'];

		//执行发送
		empty($parameter['xml']) ?  $this->_message() : $this->_xml($parameter['xml']);

	}
	
	
	/**
	 * 发送消息给服务器
	 * @param String $_from		发送人
	 * @param String $_to			发送目标人
	 * @param String $content	发送内容
	 */
	private function _message() {	
		
		$this->object = new XMPPHP_XMPP($this->host ,$this->port,$this->from,$this->pass,$this->resource);
		
		try {
			//建立连接
			$this->object->connect();
			$this->object->processUntil('session_start');
			$this->object->presence();
			
			//消息主体
			$this->object->message($this->to,$this->content);		//目标接收人JID以及内容
			
			$this->object->disconnect();
		} catch(XMPPHP_Exception $e) {
			die($e->getMessage());
		}
	}
	
	
	/**
	 * 发送源XML目标人
	 * @param  $xml
	 */
	private function _xml ($xml) {
		
		$this->object = new XMPPHP_XMPP($this->host ,$this->port,$this->from,$this->pass,$this->resource);
		
		try {
			//建立连接
			$this->object->connect();
			$this->object->processUntil('session_start');
			$this->object->presence();
				
			/**
			 <message from="yangyang@112.124.52.81/xmpphp" to="json@112.124.52.81" type="chat">
			 <body>
			 你好!2013-09-14 16:54:59</body>
			 </message>
			 */
			$this->object->send($xml);
				
			$this->object->disconnect();
		} catch(XMPPHP_Exception $e) {
			die($e->getMessage());
		}
	
	}

	
	
	//发送接口
	public function send_message() {
		$content = array(
			'from'=>'from',
			'to'=>'to',
			'content'=>'你好!',
		//	'xml'=>'<message from="notice_from@112.124.52.81/xmpphp" to="notice_to@112.124.52.81" type="chat"><body>你好!2013-09-14 16:54:59</body></message>'
		);
		$this->send_action($content);//执行发送
		//$this->_message();
	}


	
}

?>