<<<<<<< HEAD
<?php

//店铺表数据模型
class JoinModel extends BaseModel {
	
	
	public function get_store_info ($id) {
		 
		$data = 
		$this->table('lh_join AS j')
		->join('lh_file AS f ON j.store_pic=f.id')
		->field('j.store_name,j.trade,j.estate,j.draw,j.lng,j.lat,j.address,j.store_phone,j.city,j.start_business,j.classification,j.official,j.introduce,f.file_address')
		->where(array('j.status'=>0,'j.id'=>$id))
		->limit('1')
		->select();
		return $data;
	}
	
	
	//获取待审核数据
	public function seek_auditing_data () {
		$data = $this->query("
			SELECT 
						j.id,
						j.store_name,
						j.address,
						j.store_phone,
						j.time,
						j.status,
						(SELECT file_address FROM lh_file AS f WHERE f.id=j.store_pic LIMIT 1) AS store_pic,
						(SELECT file_address FROM lh_file AS f WHERE f.id=j.licence_pic LIMIT 1) AS licence_pic
			FROM
						lh_join AS j
		WHERE		
						status = '1'
		");
		parent::setTime($data);

		return $data;
	}
	
	
}

?>
=======
<?php

//店铺表数据模型
class JoinModel extends BaseModel {
	
	
	public function get_store_info ($id) {
		 
		$data = 
		$this->table('lh_join AS j')
		->join('lh_file AS f ON j.store_pic=f.id')
		->field('j.store_name,j.trade,j.estate,j.draw,j.lng,j.lat,j.address,j.store_phone,j.city,j.start_business,j.classification,j.official,j.introduce,f.file_address')
		->where(array('j.status'=>0,'j.id'=>$id))
		->limit('1')
		->select();
		return $data;
	}
	
	
	//获取待审核数据
	public function seek_auditing_data () {
		$data = $this->query("
			SELECT 
						j.id,
						j.store_name,
						j.address,
						j.store_phone,
						j.time,
						j.status,
						(SELECT file_address FROM lh_file AS f WHERE f.id=j.store_pic LIMIT 1) AS store_pic,
						(SELECT file_address FROM lh_file AS f WHERE f.id=j.licence_pic LIMIT 1) AS licence_pic
			FROM
						lh_join AS j
		WHERE		
						status = '1'
		");
		parent::setTime($data);

		return $data;
	}
	
	
}

?>
>>>>>>> 875753ed9bff50cc845fe9ec340485710b57dc3a
