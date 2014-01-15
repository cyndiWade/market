<<<<<<< HEAD
<?php

//城市表模型
class CityModel extends BaseModel {
	
	
	//读取城市表缓存数据
	public function get_city_cache() {
		$cache_data = F('city_cache');			//读取缓存
		
		if (empty($cache_data)) {	//缓存文件不存在时
			$all_city = $this->field('id,name')->select();		//读取城市表数据
			F('city_cache',$all_city);				//生成缓存文件
			return $all_city;
		} else {								//缓存文件存在直接读取缓存
			return $cache_data;				
		}
		
	}
	

	
	
}

?>
=======
<?php

//城市表模型
class CityModel extends BaseModel {
	
	
	//读取城市表缓存数据
	public function get_city_cache() {
		$cache_data = F('city_cache');			//读取缓存
		
		if (empty($cache_data)) {	//缓存文件不存在时
			$all_city = $this->field('id,name')->select();		//读取城市表数据
			F('city_cache',$all_city);				//生成缓存文件
			return $all_city;
		} else {								//缓存文件存在直接读取缓存
			return $cache_data;				
		}
		
	}
	

	
	
}

?>
>>>>>>> 875753ed9bff50cc845fe9ec340485710b57dc3a
