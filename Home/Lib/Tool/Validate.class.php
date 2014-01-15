<?php

class Validate { //表单验证
		
	//是否为空
	static public function checkNull ($_data) {
		return trim($_data) == '';
	}
			
	//数字验证
	static public function checkNum ($_data) {
		return is_numeric($_data);
	}

	//账号验证
	static public function checkAccount($string) {								
		$check1 = preg_match("/^[A-Za-z][\w]{3,64}$/", $string);						//普通账号验证
		$check2 = preg_match("/^([\w\.\-]+)\@([\w]+)\.(com|cn)$/u", $string);	//邮箱验证

		if ($check1 || $check2) {
			return true;
		} else {
			return false;
		}		
	}
	
	//电话号码验证
	static public function checkPhone($string) {
		return preg_match("/^1[358]\d{9}$/", $string);
	}
	
	
	//长度是否合法
	static public function checkLength ($_data,$_length,$_flag) {
		if ($_flag == 'min') { //允许最小字符
			if (mb_strlen(trim($_data),'utf-8') <  $_length) return true;//字符串长度小于
			return false;
		} else if ($_flag == 'max') {//允许最大字符
			if (mb_strlen(trim($_data),'utf-8') >  $_length) return true;//字符串长度大于
			return false;
		} else if ($_flag == 'equals') {
			if (mb_strlen(trim($_data),'utf-8') != $_length) return true;
			return false;
		} else {//字符传值出错

		}
		
	}
			
	//二个提交的数据是否一致
	static public function checkEquals ($_data,$_otherdate) {
		if (trim($_data) != trim($_otherdate)) return true;//如果2个表单数据不同
		return false;
	}
			
	static public function checkemail ($_data) {
		$_zc = '/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/';
		if (!preg_match($_zc,$_data)) return true;
		return false;
	}
			
		

	
}




?>