<?php


class MapAction extends Action
{
	public function _initialize() {
	
	}

	 private function __getGroupList(){
	 Session::pause();
        global $ts;

        $temp = $ts['my_group_list'];
        $group_list = array();
        foreach($temp as $value){
            if($value['openWeibo']){
                $group_list[] = $value;
            }
        }
        $data['group_list']  = $group_list;

          return $data;
        }


      function index(){
      $data=$this->__getGroupList();
      $this->assign($data);

     $this->display();

    }
        
}
