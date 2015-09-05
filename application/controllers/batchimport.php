<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 批量导入账号
 * @file batchimport.php
 * @author caohongliang <hongliang.cao@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */

class BatchImport extends Admin_Controller {
	
	const IMPORT_SUCCESS 		= 0; //成功 
	const IMPORT_FAIL_FORMAT 	= 1; //格式或文件大小错误
	const IMPORT_FAIL_TAGS 		= 2; //模板定义不一致
	const IMPORT_FAIL_CONTENT 	= 3; //文件内容不正确
	
	public function __construct() {
		parent::__construct();
		
		include_once APPPATH . 'libraries/PHPExcel.php';
		$this->lang->load('upload', 'chinese');
		$this->load->library('tags', '', 'tags');
		$this->load->library('BossLib', '', 'boss');
		$this->load->model('tags_model');
		$this->load->model('account_upload_task_model', 'upload_task');
		$this->load->helper('download');
	}
	
	/**
	 * 显示批量导入页面
	 */
	public function index(){
		$tags = $this->_getTags();
		$this->assign('tags', $tags);
		$this->display('batchimport/index.tpl');
	}
	
	/**
	 * @brief 批量导入
	 * 
	 */
	public function upload(){
		
		$callStartTime = microtime(true);
		
		//初始化
		$error_msg 		=  	'';	//错误信息
		$file_name 		=  	'';	//上传文件路径
		$max_column		=  	0 ;	//上传文件列数
		$max_row    	=  	0 ;	//上传文件行数
		$reader     	=  	null;//phpExcel插件reader
		$filter     	=  	null;//phpExcel插件filter
		$header_data	=	null;//表头数据
		$body_data		=   null;//表身数据
		
		//文件格式、大小校验,成功则将文件保存
		list($flag, $msg) = $this->_upload();
		
		if( ! $flag){
			$this->_return(self::IMPORT_FAIL_FORMAT, array('msg'=>$msg));
		}
		$file_name = $msg['full_path'];//文件上传后的路径

		//获取文件信息
		$reader 	= 	PHPExcel_IOFactory::createReaderForFile($file_name);
		$meta 		= 	$reader->listWorksheetInfo($file_name);
		
		//读取第一个sheet
		$sheetOne	=	isset($meta[0]) ? $meta[0] : NULL;//取出第一个sheet
		if(is_null($sheetOne)){
			$error_msg = '<span class="errorText01">'.$this->lang->line('upload_read_file_fail').'</div>';
			$this->_return(self::IMPORT_FAIL_FORMAT, array('msg'=>$error_msg));
		}

		//判断sheet是否为空
		$max_row			=	$sheetOne['totalRows'];
		$max_column 		=   $sheetOne['totalColumns'];
		$max_column_alpha   =   chr(ord("A")+$max_column-1); 
		if($max_row * $max_column == 0){
			$error_msg = '<span class="errorText01">'.$this->lang->line('upload_file_data').'</div>';
			$this->_return(self::IMPORT_FAIL_FORMAT, array('msg'=>$error_msg));
		}else if($max_row == 1){
			$error_msg = '<span class="errorText01">'.$this->lang->line('upload_file_null_body').'</div>';
			$this->_return(self::IMPORT_FAIL_FORMAT, array('msg'=>$error_msg));
		}else if($max_row > (BATCH_LIMIT_ROWS+1)){
			$error_msg = '<span class="errorText01">'.sprintf($this->lang->line('upload_limit_rows'), BATCH_LIMIT_ROWS).'</div>';
			$this->_return(self::IMPORT_FAIL_FORMAT, array('msg'=>$error_msg));
		}
		log_message('info', "max column:{$max_column}--max row:{$max_row}");
		
		$filter = new chunkReadFilter();	
		$header_start_row  = 1;
		$header_chunk_size = 1;
		$filter->setRows($header_start_row,$header_chunk_size);//first row
		$reader->setReadFilter($filter);
		$reader->setReadDataOnly(true);
		$reader->setLoadSheetsOnly($sheetOne['worksheetName']);

		$excel 			=   $reader->load($file_name);
		$data			= 	$excel->getActiveSheet()->rangeToArray('A1:'.$max_column_alpha.'1',null,false,false,false);
		$header_data	=	array_pop($data);
		log_message('info', 'The head data is-->'.var_export($header_data, true));
		
		/*
		//获取所有标签
		$tags = $this->_getTags();
		
		//检查表头是否为空
		if(empty($header_data)){//是否为空
			$error_msg = '<span class="errorText01">'.$this->lang->line('upload_file_null_header').'</div>';
			$this->_return(self::IMPORT_FAIL_FORMAT, array('msg'=>$error_msg));
		}
		*/
		
		//获取所有标签,检查表头中是否有未定义的标签或者重复的标签
		$tags = $this->_getTags();
		$undefined_tags = array();
		$undefined_tags = array_diff($header_data, $tags);
		if(  count($undefined_tags) > 0 OR count($tags) != count($header_data) ) {//表头是否有不合法或者重复的标签
			$this->_return(self::IMPORT_FAIL_TAGS, array('header_data'=>$header_data, 'undefined_tags'=>$undefined_tags, 'tags'=>$tags));
		}
		
		//找到header_data对应的英文名，做为键名。
		//XXX 这段代码临时使用，后面做国际化时，会有改动
		$en_name = '';
		foreach($header_data as $k=>$v){
			if( $en_name = $this->tags->getEnNameByCnName($v)){
				$header_data[$en_name] = $v;
				unset($header_data[$k]);
			}
		}
		
		//从boss获取所有的账户信息
		$account_info = $this->boss->getAccountInfo($this->p_customer_code, $this->p_contract_id);
		if(empty($account_info)){
			$error_msg = '<span class="errorText01">'.$this->lang->line('upload_get_account_info_failed').'</div>';
			$this->_return(self::IMPORT_FAIL_FORMAT, array('msg'=>$error_msg));
		}
		$this->account = array_column($account_info, 'accountId', 'name');

		//$this->account = empty($account_info) ? array() : array_column($account_info, 'accountId', 'name');
		
		//分批读取表身（body）信息，校验数据
		$chunkSize 		  = BATCH_MAX_CHUNKSIZE;  		//每次读入的记录数
		$fail_rows[]  	  = array_values($header_data);   			//导入失败的记录
		$success_count    = 0;							//导入成功的账号数
		$fail_count       = 0;                          //导入失败的账号数
		for ($startRow = 2; $startRow <= $max_row; $startRow += $chunkSize) {
			log_message('info', 'start read row-->'.$startRow);
			try{
				$filter->setRows($startRow,$chunkSize);
				$reader->setReadFilter($filter);
				$excel = $reader->load($file_name);
			}catch(PHPExcel_Reader_Exception $e){
				log_message('error', 'Error loading file: '.$e->getMessage());
				$error_msg = $this->lang->line('upload_read_file_fail');
				return $this->load->view('bulkimport/partsuccess.php', $error_msg);;
			}
			
			//--设定开始单元格位置和结束单元格位置
			$startCell = 'A'.$startRow;//起始单元格
			$endCell   = $max_column_alpha.( ($max_row-$startRow) >= $chunkSize ? $startRow+$chunkSize-1 : $max_row );//终止单元格
			$chunk_data = $excel->getActiveSheet()->rangeToArray($startCell.':'.$endCell,null,false,false,false);
			
			//--检查标签值，返回成功和失败列表
			list($success_list, $fail_list) = $this->_checkBodyData($chunk_data,$header_data);
			
			//--将读出的所有行，写入任务，每条任务开通的账户数是可以设置的，推荐20
			if(!empty($success_list)){
				for($i=0,$successTotal=count($success_list); $i<$successTotal; $i += ACCOUNTS_PER_TASK){
					$task_value	= array(
						'customer_code'	=>$this->p_customer_code,
						'site_id'		=>$this->p_site_id,
						'org_id'		=>$this->p_org_id,
						'users'			=>array_slice($success_list, $i, ACCOUNTS_PER_TASK),
					);
					$this->upload_task->saveTask(ACCOUNT_CREATE_UPLOAD, json_encode($task_value));
				}

				$success_count += count($success_list);
			}
			
			//--将验证失败的行，暂存在数组里，最后会统一写入到文件里
			if(!empty($fail_list)){
				$fail_rows 	     =  array_merge($fail_rows,$fail_list);
				$fail_count   	+= count($fail_list);
			}

			//释放资源
			$excel->disconnectWorksheets();
			unset($excel, $chunk_data, $success_list);
		}
		
		//导入完成。返回信息。
		//--如果全部导入成功，则跳转到组织机构页面
		//--如果有部分失败，则显示失败信息
		
		$callEndTime = microtime(true);
		$callTime = $callEndTime - $callStartTime;
		
		log_message('info', 'Call time to read Workbook was '.sprintf('%.4f',$callTime)." seconds");
		log_message('info', 'Current memory usage: '.(memory_get_usage(true) / 1024 / 1024)." MB");
		log_message('info', "Peak memory usage: " .(memory_get_peak_usage(true) / 1024 / 1024)." MB");
		
		if($fail_count > 0){
			//--将失败数据存入失败文件中
			$file_path 		 =  BATCH_FAIL_PATH.'/'.pathinfo($file_name, PATHINFO_BASENAME);
			$this->_writeExcelFile($fail_rows, $file_path);
			
			//--显示错误页面
			$data = array(
				'success_count'	=>$success_count,
				'fail_count'    =>$fail_count,
				'fail_url'		=>site_url('batchimport/downloadFailFile?file='.pathinfo($file_name, PATHINFO_BASENAME)),
			);
			$this->_return(self::IMPORT_FAIL_CONTENT, $data);
		}else{
			$this->_return(self::IMPORT_SUCCESS, array('url'=>site_url('organize/OrgList')));
		}
		
	}
	
	
	/**
	 * 批量导入失败文件下载
	 */
	public function downloadFailFile(){
		//失败文件名称
		$file_name = $this->input->get_post('file',true);

		//判断文件是否存在
		$path = BATCH_FAIL_PATH.'/'.$file_name;
		if(!file_exists($path) OR empty($file_name)){
			echo $this->lang->line('upload_file_not_exist');
		}else{
			force_download($file_name, file_get_contents($path));
		}
	}
	
	/**
	 * 模板下载
	 */
	public function downloadTemplate(){
		//设置响应头
		$file_name = 'template_'.$this->p_customer_code.'_'.$this->p_site_id.'_'.$this->p_user_id.'.xlsx';
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment; filename=\"$file_name\"");
		header('Cache-Control: max-age=0');
		
		//获取标签
		$tags = $this->_getTags();
		
		//生成excel文件，内存
		try{
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->getProperties()
			->setCreator("quanshi")
			->setSubject("Office 2007 XLSX  Document")
			->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
			->setKeywords("office 2007 openxml php");
				
			//--设置active sheet
			$activeSheet = $objPHPExcel->setActiveSheetIndex(0);
		
			//--写入数据到单元格
			$tagRow = 1;
			foreach($tags as $col=>$tag){
				$activeSheet->setCellValueByColumnAndRow($col, $tagRow, $tag);
			}
		
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, EXCEL_VERSION);
			$objWriter->save('php://output');
		}catch(PHPExcel_Writer_Exception $e){
			log_message('error', 'Error writinging file: '.$e->getMessage());
		}
		
	}
	
	
	/**
	 * 返回导入结果页面
	 * @param int $type 错误类型 0-成功 1-格式或文件大小错误;2-模板定义不一致;3-文件内容不正确
	 * @return string
	 */
	private function _return($type, $data){
		//记录log
		log_message('error', var_export($data, true));
	
		//分配变量
		$data['operate_type'] = 0;//0-批量导入 1-批量修改
		$this->assign($data);
	
		//显示错误提示
		switch($type){
			case self::IMPORT_SUCCESS:			//成功
				return_json(COMMON_SUCCESS, '', $data);
				break;
			case self::IMPORT_FAIL_FORMAT:		//格式或文件大小错误
				$output = $this->fetch('batchimport/file_error.tpl');
				return_json(COMMON_FAILURE, '', array('html'=>$output));
				break;
			case self::IMPORT_FAIL_TAGS:		//模板定义不一致
				$output = $this->fetch('batchimport/tag_error.tpl');
				return_json(COMMON_FAILURE, '', array('html'=>$output));
				break;
			case self::IMPORT_FAIL_CONTENT:		//文件内容不正确
				$output = $this->fetch('batchimport/content_error.tpl');
				return_json(COMMON_FAILURE, '', array('html'=>$output));
				break;
	
			default:
				return;
				break;
		}
	
	}
	
	
	private function _upload(){
		$props = array(
				'upload_path'=>BATCH_UPLOAD_PATH,
				'allowed_types'=>BATCH_ALLOW_TYPES,
				'max_size'=>BATCH_MAX_SIZE,
				'file_name'=>'batch_'.$this->p_customer_code.'_'.$this->p_site_id.'_'.$this->p_user_id.'_'.date('YmdHis'),
				'overwrite'=>true,
		);
		$this->load->library('upload', $props);
		if( ! $this->upload->do_upload('batchfile')){
			return array(false, $this->upload->display_errors('<span class="errorText01">', '</span>'));
		}else{
			return array(true, $this->upload->data());
		}
	}
	
	/**
	 * 获取所有标签
	 * @return array
	 */
	private function _getTags(){
		
		//获取该站点的标签
		$must_tag_names         =   $this->tags->getAllMustTagsName();//必选标签
		$dept_tag_names 		=   $this->tags->getAllDeptTagsName($this->tags_model->getDepartmentLevels($this->p_site_id));//部门标签
		$custom_tag_names		=	$this->tags_model->getCustomTags($this->p_site_id); //用户自定义标签
		$optional_tag_names 	=	$this->tags_model->getOptionalTags($this->p_site_id); //可选标签
		
		return array_merge($must_tag_names, $dept_tag_names, $optional_tag_names, $custom_tag_names);
	}
	
	/**
	 * @brief 检查body体数据
	 * 
	 * @param array $users 	用户数据
	 * @param array $header	表头
	 * @return array(boolean, $msg)
	 */
	private function _checkBodyData($rows,$header){
		$success = array();//导入成功的用户
		$fail	 = array();//导入失败的用户
		
		foreach($rows as $row){
			
			//验证标签值
			$row = array_map('trim', $row);//过滤掉单元格值的空格
			$row = array_combine(array_keys($header), $row);//XXX重组数据 bad code..
			$rst = array_map(array($this->tags, 'checkTagValue'), $row, $header);//检查每行数据里的每个单元格里的值
			
			//查找验证失败的标签值
			$invalid_cells = array();
			foreach($rst as $index=>$cell){
				if( ! $cell){
					$invalid_cells[$header[$index]] = $row[$index];
				}
			}
			
			//判断单行所有标签值是否全部验证通过。验证通过，则存放在成功列表里
			if(count($invalid_cells) > 0){
				log_message('error', 'invalid tag values-->'.var_export($invalid_cells, true));
				$fail[] 	= array_values($row);
			}else{
				//--XXX 对性别、是否开启进行转化。将账户名称转为账户id。 bad code...
				$row['open'] 		= in_array($row['open'], array('是','开','开启')) ? true : false;
				$row['sex']  		= ($row['sex'] == '男' ? 1 : ($row['sex'] == '女' ? 2 : 0));// 0未设置 1男 2女
				$row['account']		= isset($this->account[$row['account']]) ? $this->account[$row['account']] : 0;
				$row['lastname']    .= 	$row['firstname'];//将姓和名合并
				
				$success[] 	= $row;
			}
		}
		
		return array($success, $fail);
	}
	

	/**
	 * 将数据写入excel文件
	 * @param array   $data 数据
	 * @param string  $path 路径
	 * @return boolean
	 */	
	private function _writeExcelFile($data , $path){
		$rows    = count($data);
		$columns = count($data[0]);
	
		try{
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->getProperties()
			->setCreator("quanshi")
			->setSubject("Office 2007 XLSX  Document")
			->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
			->setKeywords("office 2007 openxml php");
			
			//--设置active sheet
			$activeSheet = $objPHPExcel->setActiveSheetIndex(0);
				
			//--写入数据到单元格
			for($i = 0; $i < $rows; $i++){
				
				for($j = 0; $j < $columns; $j++){
					
					$activeSheet->setCellValueByColumnAndRow($j, $i+1, $data[$i][$j]);
					
					
				}
					
			}
	
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, EXCEL_VERSION);
			$objWriter->save($path);
		}catch(PHPExcel_Reader_Exception $e){
			log_message('error', 'Error writinging file: '.$e->getMessage());
			return false;
		}
		
		return true;
	}
	
	
}
