<?php 


class BaseModel extends Model {
	

	//删除方法
	public  function del($condition) {
		return $this->where($condition)->data(array('status'=>-2))->save();
	}

	
	/**
	 * 格式化日期
	 * @param unknown_type $all
	 */
	protected function setTime(&$all) {
		foreach ($all AS $key=>$val) {
			$all[$key]['time'] = date('Y-m-d H:i:s',$val['time']);
		}
	}
	



	
}
?>