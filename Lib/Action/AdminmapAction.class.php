<?php
/**
 +------------------------------------------------------------------------------
 * AdminmapAction 管理员地图操作类
 +------------------------------------------------------------------------------
 * @category   Railmap
 * @package  Railmap
 * @subpackage  Core
 * @author   liuqingjie <email liuqingjie06@yahoo.com.cn>
 * @version  1.0 2012.4
 +------------------------------------------------------------------------------
 */
require_once(VENDOR_PATH.'/vendor/PHPExcel.php');

class AdminmapAction extends BaseAction {

	protected $_host_type;
	protected $dir;
	private   $__excelReader;
	
	public function _initialize(){
		/*
		 * TODO 页面初始化
	 	 * @author liuqingjie06@yahoo.com.cn
		 * @create 2012.4.10 
		 */
		//parent::_initialize();
	
		$this->dir = D('Mapdir');
		$this->assign('current','dir');
	}
	
	private function _readsheet($sheetName){
		/*
		 * TODO 读取表格名称 sheet name
		 * @author liuqingjie06@yahoo.com.cn
		 * @create 2012.4.10
		 */
		//建立读写规则
		$this->__excelReader=new PHPExcel();

		
	}
	
	
	public function index()
	{
		/*
		 * TODO 查看地图数据
		 * @author liuqingjie06@yahoo.com.cn
		 * @create 2012.4.13 
		 */
		$this->display();
	}
	

	
	//上传线路基础数据，由管理员上传。	
	public function uploaddata(){
		/*
		 * TODO 列出数据文件目录，上传新的数据文件，只支持.xls和.xlsx类型的数据，选择数据文件生成新数据
		 * 
		 * @author liuqingjie06@yahoo.com.cn
		 * @create 2012.4.13 
		 */

		if(isset($_POST['uploadsubmit']) || $_GET['ajax'] == 1) {
			//如果没有上传文件！输出错误
			if ($_FILES['uploadfile']['size'] <= 0) {
				if ($_GET['ajax'] == 1) {
					exit(json_encode(array('status'=>0, 'info'=>'请选择上传文件')));
				}
				$this->error('请选择上传文件');
			}
			//设置上传参数上传参数
			$upload['max_size']   = 2*1024*1024;//$this->config['simpleFileSize']*1024*1024;
			$upload['allow_exts']=array('xls','xlsx','CSV','html');
			//上传类型名为basemap_data的附件
			$info = X('Xattach')->upload('map_file',$upload);
			if($info['status']){  //上传成功
				list($uploadFileInfo) = $info['info'];
			
				$attchement['name'] = $uploadFileInfo['name'];
				$attchement['note'] = !empty($_POST['note']) ? t($_POST['note']) : '';
				$attchement['filesize'] = $uploadFileInfo['size'];
				$attchement['filetype'] = $uploadFileInfo['extension'];
				$attchement['fileurl'] = $uploadFileInfo['savepath'] . $uploadFileInfo['savename'];
				$attchement['ctime'] = time();
				$attchement['attachId'] = $uploadFileInfo['id'];
				if ($_GET['ajax'] == 1) {
					$attchement['is_del'] = 1; // 异步上传的文件默认为删除状态，等异步信息保存时候再设定为非删除
				}
				

				$result =$this->dir->add($attchement);

				//如果上传成功提示下一步
				
				$this->assign('filename',$attchement['name']);
				$this->assign('id',$attchement['attachId']);
			
			}else{
				if ($_GET['ajax'] == 1) {
					exit(json_encode($info));
				}

				$this->error($info['info']);
			}					
		}
		
		//列出数据文件的信息
		$limit = 20; //每页显示数据数
		
		$data = $this->dir->order('id')->findPage($limit);
		$this->assign($this->__formatMap('basemap', $data));
		
		$this->assign('upload',$upload);
		
		$this->display();
		
	}
	
	public function readrule(){
		/*
		 * TODO 建立数据读取规则 
		 * @author liuqingjie06@yahoo.com.cn
		 * @create 2012.4.14 20:49
		 */

		
		$filedir=$this->dir->where("id=".$_POST['fileid'])->getField('fileurl');
		$datatype=array();
		foreach($this->_datatype as $key=>$value){
			$datatype[]=$key;
		}
		$data['datatypename']=$datatype;
		$this->assign($data);

		$dir= SITE_DATA_PATH.'\\uploads\\'.$filedir;
		$dir=str_replace("/", "\\", $dir);
		$this->assign('datatype',$this->_datatype);
		$this->assign('filedir',$dir);
		
		$this->display();
		
		

	}
	
	public function preview(){
		/*
		 * TODO 在页面中列出表的结构，匹配数据库与excel表格
		 */
		//样表显示功能 使用PHPExcel实现  类库在addon/vendor 中
		$sheetname=$_POST['sheetname'];
		$filename=$_POST['filedir'];
		error_reporting(E_ALL);
		date_default_timezone_set('Europe/London');		
		//echo date('H:i:s')."Create new PHPExcel object\n";		
		$objPHPExcel=new PHPExcel();
		//echo "Load from file</br>";
		
		//echo $filename.$sheetname;
		$objReader=PHPExcel_IOFactory::createReaderForFile($filename);
		$objReader->setLoadSheetsOnly(array($sheetname));	
		$objPHPExcel=$objReader->load($filename);
		for ($i=0;$i<15;$i++){
		for ($j=0;$j<15;$j++){
		$tabledata[$i][$j] =$objPHPExcel->getActiveSheet()->getCell(chr($i+65).(string)$j)->getCalculatedValue();
		}
		}
		$this->assign('tabledata',$tabledata);
		//数据读取规则
		//$contentlist=$this->_datatype[$_POST['type']];  //读入数据表内容		
		//$this->assign('contentlist',$contentlist);
		//数据库表名		

		$this->display();
		
	}
	public function showdata(){
		/*
		 * TODO 读写规则建立完成之后，将数据填写到数据库中，并显示结果和入库信息
		 * @author liuqingjie06@yahoo.com.cn
		 * @create 2012.4.14 20:49
		 */
		$datalist=array();
		foreach ($this->_datatype[$_POST['datatype']] as $key=>$value){
			$datalist[$key]=strtoupper($value);
		}
		
		$mapdata=M($this->_tablename[$_POST['datatype']]);		
		
		$mapdata->add();
		$this->assign('datalist',$datalist);
		$this->display();
	}
}
	
?>