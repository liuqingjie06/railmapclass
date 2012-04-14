<?php
/**
 * @uses Action
 * @package Action::map
 * @version 1.0
 * @copyright 2012-2015 Railmap
 * @author liuqingjie
 */

class BaseAction extends Action
{
	protected $gid;
	/**
	 * @access public
	 * @return void
	 */
	protected $isadmin;
	protected $ismember;
	protected $config;
	protected $groupinfo;
	protected $siteTitle;
	protected $is_invited;
	
	protected  $_datatype=array(
			'车站'=>array('站名'=>'stationname',
					'股道数'=>'tracknum',
					'站名缩写'=>'namehead',
					'中心里程'=>'cetermileage',
					'车站编号'=>'lid',
					'路局编号'=>'Admin_id',
					'线路编号'=>'Line_id'),
			'曲线'=>array('长度'=>'length',
					'切线长'=>'tangent',
					'半径'=>'radius',
					'缓和曲线长'=>'gentlecurve',
					'起点里程'=>'startmileage',
					'终点里程'=>'endmileage',
					'上下行'=>'side',
					'编号'=>'lid',
					'路局'=>'Admin_id',
					'线路'=>'line_id'),
			'坡道'=>array('长度'=>'length',
					'坡度'=>'slope',
					'起点里程'=>'startmileage',
					'终点里程'=>'endmileage',
					'上下行'=>'side',
					'编号'=>'lid',
					'路局编号'=>'Admin_id',
					'线路编号'=>'Line_id'),
			'隧道'=>array('隧道名'=> 'tunnelname',
					'隧道类型'=>'tunneltype',
					'中心里程'=>'mileage',
					'隧道编号'=>'lid',
					'路局编号'=>'Admin_id',
					'线路编号'=>'Line_id'),
			'桥梁'=>array(
					'桥梁名称'=>'bridgename',
					'类型'=>'bridgetype',
					'里程'=>'mileage',
					'编号'=>'lid',
					'路局编号'=>'Admin_id',
					'线路编号'=>'Line_id'));
	protected  $_tablename=array(
			'车站'=>'ts_map_station',
			'曲线'=>'ts_map_curve',
			'坡道'=>'ts_map_slope',
			'隧道'=>'ts_map_tunnel',
			'桥梁'=>'ts_map_bridge',);

	protected  function __formatMap($map_type, $data) {
		/*
		 * TODO 对数据进行分解重组
		*/
		foreach($data['data'] as $k => $v) {
			//判断数据的类型
			if ($map_type == 'basemap') {
				unset($data['data'][$k]);
				$data['data'][$k]	=  array(
						'file_id'		=>	$v['id'],
						'filename'		=>	$v['name'],
						'filesize'		=>	$v['filesize'],
						'ctime' 		=>  $v['ctime'],
						'note'			=>  $v['note'],
				);
			}/*else if ($from_app == 'measurement') {
			unset($data['data'][$k]);
			$v['data'] = unserialize($v['data']);
			$data['data'][$k]	=  array(
					'comment_id'	=> $v['id'],
					'type'			=> $v['type'],
					'content'		=> $v['comment'],
					'uid'			=> $v['uid'],
					'to_uid'		=> $v['to_uid'],
					'url'			=> $v['data']['url'],
					'ctime'			=> $v['cTime'],
			);
			}else if ($from_app == 'repairment') {
			unset($data['data'][$k]);
			$v['data'] = unserialize($v['data']);
			$data['data'][$k]	=  array(
					'comment_id'	=> $v['id'],
					'type'			=> $v['type'],
					'content'		=> $v['comment'],
					'uid'			=> $v['uid'],
					'to_uid'		=> $v['to_uid'],
					'url'			=> $v['data']['url'],
					'ctime'			=> $v['cTime'],
			);
			}*/
		}
		return $data;
	}
	protected function _initialize()
	{
		
	}
	
	protected function _getSearchMap($fields) {
		// 为使搜索条件在分页时也有效，将搜索条件记录到SESSION中
		if ( !empty($_POST) ) {
			$_SESSION['admin_search_attach'] = serialize($_POST);
		}else if ( isset($_GET[C('VAR_PAGE')]) ) {
			$_POST = unserialize($_SESSION['admin_search_attach']);
		}else {
			unset($_SESSION['admin_search_attach']);
		}
		
		// 组装查询条件
		$map	= array();
		foreach ($fields as $k => $v) {
			foreach ($v as $field) {
				if ( isset($_POST[$field]) && $_POST[$field] != '' ) {
					if($k == 'in') {
						$map[$field] = array($k, explode(',', $_POST[$field]));
					}else {
						$map[$field] = array($k, $_POST[$field]);					
					}
				}
			}
		}
		
		return $map;
	}
}


