<?php
Class BasedataModel extends Model{
	var $tableName="map_basedata";
	
	protected function _facade($data) {
		/*
		 * TODO 数据验证，增加ctime字段，解析里程K+表达式
		 * @Author:liuqingjie06
		 * @create 2012.4.17
		 * 检查非数据字段 
		 */ 
		
		if(!empty($this->fields)) {
			foreach ($data as $key=>$val){
				//检查有没有K或k开头的数据
				if ($key=='startmileage'||$key=='stopmileage'){
					echo $key.'</br>';
					$data[$key]=$this->_mileagecal($data[$key]);
				}
				if(!in_array($key,$this->fields,true)){
					unset($data[$key]);				
				}elseif(C('DB_FIELDTYPE_CHECK') && is_scalar($val)) {
					// 字段类型检查
					$fieldType = strtolower($this->fields['_type'][$key]);
					if(false !== strpos($fieldType,'int')) {
						$data[$key]   =  intval($val);
					}elseif(false !== strpos($fieldType,'float') || false !== strpos($fieldType,'double')){
						$data[$key]   =  floatval($val);
					}
					
				}
				
				
			}
		}
		
		if($data['starmileage']<$data['starmileage']){
			//添加filter
		}
		$this->_before_write($data);
		$data['ctime']=time();
		return $data;
	}
	
	private function _mileagecal($mileage){
		$mileage=strtolower($mileage);
		$char1 = preg_split('/k/', $mileage, 2, PREG_SPLIT_OFFSET_CAPTURE);
		if ($char1[1][0]==NULL){
			return $mileage;
		}else{
        	$char2=  preg_split('/\+/', $char1[1][0], 2, PREG_SPLIT_OFFSET_CAPTURE);
			return (double)$char1[1][0]*1000+(double)$char2[1][0];
		}
	}
	
	
	
	
}