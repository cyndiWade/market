<?php

//文件数据模型表
class FileModel extends BaseModel {
	
	
	//指定的图片地址
	public function get_store_pic($ids) {
		return  $this->field('id,file_address')->where(array('id'=>array('in',$ids),'type'=>1,'status'=>0))->select();
	}
	

	
	
}

?>
