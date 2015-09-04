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
	const ERROR_MARK			= "-**";//用来记录失败cell的标记
	
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
		$this->load->model('tags_model');
		$allTags = $this->tags_model->getSiteTags($this->p_site_id, true);
		
		$tags = $this->_getTags($allTags, 'cn');
		$this->assign('tags', $tags);
		$this->display('batchimport/index.tpl');
	}
	
	/**
	 * @brief 批量导入
	 * 
	 */
	public function upload(){
		
		$callStartTime = microtime(true);
		
		//默认的语言
		//XXX 暂时为中文
		$lang = 'cn';
		
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
		
		
		//检查表头中是否有未定义的标签或者重复的标签
		$this->load->model('tags_model');
		$allTags = $this->tags_model->getSiteTags($this->p_site_id, false);
		
		$tags 		 = $this->_getTags($allTags, $lang);//所有的标签
		$custom_tags = $this->_getCustomTags($this->p_site_id);
		
		$undefined_tags = array();
		$undefined_tags = array_diff($header_data, $tags);
		if(  count($undefined_tags) > 0 OR count($tags) != count($header_data) ) {//表头是否有不合法或者重复的标签
			$this->_return(self::IMPORT_FAIL_TAGS, array('header_data'=>$header_data, 'undefined_tags'=>$undefined_tags, 'tags'=>$tags));
		}
		
		//获取自定义标签所对应的列index
		$custom_tag_idx = array();
		foreach($custom_tags as $custom_tag){
			$idx = array_search($custom_tag, $header_data);
			if(!($idx === false)){
				$custom_tag_idx[] = $idx; 
			}
		}
		
		//找到header_data对应的key,必选标签为配置文件里的key,可选和自定义为数据库中的id,部门层级为department*
		$header_data_tmp = array();
		$r_tags = array_flip($tags);
		foreach($header_data as $k=>$v){
			$header_data_tmp[$r_tags[$v]] = $v;
		}
		$header_data = $header_data_tmp;
		$tags_keys 	 = array_keys($header_data);
		
		//获取每一列标签所对应标签值的校验规则
		$header_pattern = $this->filterPatternByTagName($allTags, $header_data);
		log_message('info', json_encode($header_pattern));
		
		//从boss获取所有的账户信息
		//$account_info = $this->boss->getAccountInfo($this->p_customer_code, $this->p_contract_id);
		$account_info = $this->boss->getAccountInfoByCustomerCode($this->p_customer_code);
		
		if(empty($account_info)){
			$error_msg = '<span class="errorText01">'.$this->lang->line('upload_get_account_info_failed').'</div>';
			$this->_return(self::IMPORT_FAIL_FORMAT, array('msg'=>$error_msg));
		}
		$account_info = array_column($account_info, 'accountId', 'name');
		
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
			list($success_list, $fail_list) = $this->_checkBodyData($chunk_data, $header_data, $header_pattern, $account_info);
			
			//--将读出的所有行，写入任务，每条任务开通的账户数是可以设置的，推荐20
			if(!empty($success_list)){
				for($i=0,$successTotal=count($success_list); $i<$successTotal; $i += ACCOUNTS_PER_TASK){
					$tag_value_rows = array_slice($success_list, $i, ACCOUNTS_PER_TASK);
					
					foreach($tag_value_rows as $j=>$tag_value_row){
						
						//整理单元行中的自定义标签和系统标签
						$custom_tags_tmp = array();
						$system_tags_tmp = array();
						foreach($tag_value_row as $k=>$tag_value){
							if(in_array($k, $custom_tag_idx)){
								$custom_tags_tmp[$tags_keys[$k]] = trim($tag_value);
							}else{
								$system_tags_tmp[$tags_keys[$k]] = trim($tag_value);
							}
						}
						
						//yes it's time
						$tag_value_rows[$j] = array_merge($system_tags_tmp, array('custom_tags'=>$custom_tags_tmp));
						
					}
					
					//将系统标签的值存入任务表，后台任务最终会将这些值写入ums
					$task_value	= array(
						'customer_code'	=>$this->p_customer_code,
						'site_id'		=>$this->p_site_id,
						'org_id'		=>$this->p_org_id,
						'users'			=>$tag_value_rows,
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
		
		/* $callEndTime = microtime(true);
		$callTime = $callEndTime - $callStartTime;
		
		log_message('info', 'Call time to read Workbook was '.sprintf('%.4f',$callTime)." seconds");
		log_message('info', 'Current memory usage: '.(memory_get_usage(true) / 1024 / 1024)." MB");
		log_message('info', "Peak memory usage: " .(memory_get_peak_usage(true) / 1024 / 1024)." MB"); */
		
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
		$this->load->model('tags_model');
		$allTags = $this->tags_model->getSiteTags($this->p_site_id, false);
		
		$tags = $this->_getTags($allTags, 'cn');
		$tags = array_values($tags);
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
			$validateCol;
			for ($i == 0; $i < count($tags); $i ++) {
				$activeSheet->setCellValueByColumnAndRow(key($tags), $tagRow, current($tags));
				if (current($tags) == '开通帐号') {
					$validateCol = $i;
				} 
				next($tags);
			}
			
			//--对是否开通账号字段写入限定的数据有效性 wrote by ge.xie	
			if (isset($validateCol)) {
				$rows = array("A", "B", "C", "D", "E", "F", "G", "H", "I");	
				for ($i = 2; $i <= BATCH_LIMIT_ROWS; $i++) {
					$activeSheet -> getCell($rows[$validateCol] . $i)
						-> getDataValidation()
						-> setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
						-> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
						-> setAllowBlank(false)
						-> setShowInputMessage(true)
						-> setShowErrorMessage(true)
						-> setShowDropDown(true)
						-> setErrorTitle('输入的值有误.')
						-> setError('只能输入“是”或“否”.')
						-> setFormula1('"是,否"');
				}
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
	
	private function filterPatternByTagName($allTags, $headers){
		$temps = array();
		foreach($allTags['necessary'] as $tag){			
			if($tag['tag_code'] == 'department'){
				$dept_tag_names = $this->tags->getAllDeptTagsName($this->tags_model->getDepartmentLevels($this->p_site_id), $lang='cn');//部门标签
				foreach($dept_tag_names as $deptKey => $deptName){
					$temps[$deptName] = $tag['pattern'];
				}
			}
			else{
				$temps[$tag['tag_name']] = $tag['pattern'];
			}
		}
		
		foreach($allTags['optional'] as $tag){
			if($tag['selected'] !== '0'){
				$temps[$tag['tag_name']] = $tag['pattern'];
			}
		}
		
		$tagNames = array();
		foreach ($headers as $key => $name){
			$tagNames[] = $temps[$name];
		}
		
		return $tagNames;
	}
	
	/**
	 * 获取所有标签
	 * @param bool $show 是否显示隐藏的标签
	 * @return array
	 */
	private function _getTags($allTags, $lang='cn'){				
		$selectedTagNames = array();		
		
		foreach ($allTags['necessary'] as $tag){	
			if($tag['tag_code'] == 'department'){
				$dept_tag_names = $this->tags->getAllDeptTagsName($this->tags_model->getDepartmentLevels($this->p_site_id), $lang='cn');//部门标签
				foreach($dept_tag_names as $deptKey => $deptName){
					$selectedTagNames[$deptKey] = $deptName;
				}
			}
			else {
				$selectedTagNames[$tag['tag_code']] = $tag['tag_name'];
			}
		}
		
		foreach ($allTags['optional'] as $tag){			
			if($tag['selected'] == 1){
				if($tag['tag_type'] == '1'){
					$selectedTagNames[$tag['tag_code']] = $tag['tag_name'];
				}
				else{
					$selectedTagNames[$tag['id']] = $tag['tag_name'];
				}
			}
		}
		
		return $selectedTagNames;
	}
		
	private function _getCustomTags($site_id){
		return $this->tags_model->getCustomTags($site_id); //用户自定义标签
	}
	
	/**
	 * @brief 检查body体数据
	 * 
	 * @param array $users 	用户数据
	 * @param array $header	表头
	 * @return array(boolean, $msg)
	 */
	private function _checkBodyData($rows, $header_data, $header_pattern, $account_info){
		$success 	   		= array();//导入成功的用户
		$fail	 	   		= array();//导入失败的用户
		$pattern_idx   		= array_values($header_pattern);
		$header_key_idx     = array_keys($header_data);
			
		
		foreach($rows as $row){			
			//过滤掉空行
			$not_empty_cells = array_filter($row);
			if( empty($not_empty_cells) ){
				break;
			}
			
			//记录一行里第一个验证失败的cell
			$first_fail_cell    = '';
						
			//验证标签值
			$row_bak = $row;//backup row
			$row = array_map('trim', $row);//过滤掉单元格值的空格
			for($i=0,$num=count($row); $i<$num; $i++){
				//标签值规则校验
				if(!preg_match($pattern_idx[$i], $row[$i])){
					$first_fail_cell = $i;
					$fail_message = '红色提示的内容超长或格式不正确';
					break;
				}
				
				//账户有效性的验证(标签设置中账号标签已去掉--2015-06-10)
				/*
				if($header_key_idx[$i] == 'account' && !empty($row[$i]) && !isset($account_info[$row[$i]])){
					$first_fail_cell = $i;
					$this->_return(self::IMPORT_FAIL_FORMAT, array('msg'=>$row[$i]));
					break;
				}*/
				
				//部门一级，必填项
				if($header_key_idx[$i] == 'department1' && empty($row[$i])){					
					$first_fail_cell = $i;
					$fail_message = '部门一级不能空';
					break;
				}
				
				//执行到这里，说明这个标签值已经通过了校验，对里面的值进行转化
				if($header_key_idx[$i] == 'open') {
					$row[$i] = in_array($row[$i], array('是','开','开启')) ? true : false;
				}
				
				if($header_key_idx[$i] == 'sex') {
					$row[$i]  		= ($row[$i] == '男' ? 1 : ($row[$i] == '女' ? 2 : 0));// 0未设置 1男 2女
				}
				
				if($header_key_idx[$i] == 'account') {
					$row[$i]		= isset($account_info[$row[$i]]) ? $account_info[$row[$i]] : 0;
				}
				
				if($header_key_idx[$i] == 'lastname') {
					
					//$row[$i] .= $row[array_search('firstname', $header_key_idx)];
					
					//到此可以判定lastname字段不为空
					//如果firstname字段为空，则将姓和名分开
					$firstNameKey = array_search('firstname', $header_key_idx);
					if(empty($row[$firstNameKey])){
						$first_name = '';
						$last_name  = '';
						if( ($d=preg_split("/[\s,]+/", $row[$i])) && count($d) > 1 ){//英文名或其他,中间有空格或者逗号
							$last_name = array_pop($d);
							$first_name  = implode(" ",$d);
						}else{//中文名
							list($last_name, $first_name) = splitName($row[$i]);//中文
						}
						$row[$i] 			= $last_name;
						$row[$firstNameKey] = $first_name;
					}
				}
				
			}
			
			if(!($first_fail_cell === '')){
				$row_bak[$first_fail_cell] = array(self::ERROR_MARK.$row_bak[$first_fail_cell], $fail_message);
				$fail[] = $row_bak;
			}else{
				$success[] = $row;
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
			->setCreator("bee team")
			->setSubject("Office 2007 XLSX  Document")
			->setDescription("The fail list when imported accounts")
			->setKeywords("batch import fail list");
			
			//--设置active sheet
			$activeSheet = $objPHPExcel->setActiveSheetIndex(0);
				
			//--写入数据到单元格
			for($i = 0; $i < $rows; $i++){
				
				for($j = 0; $j < $columns; $j++){
					$cellPos = PHPExcel_Cell::stringFromColumnIndex($j).($i+1);
					if((strpos($data[$i][$j][0], self::ERROR_MARK) === 0)){//将首次发生错误的单元格背景标红
						$data[$i][$j][0] = trim($data[$i][$j][0], self::ERROR_MARK);
						$activeSheet->getStyle($cellPos)->applyFromArray(
							array(
								'fill' => array(
									'type' => PHPExcel_Style_Fill::FILL_SOLID,
									'color' => array('rgb' => 'FF0000')
								)
							)
						);
						$activeSheet->setCellValue($cellPos,$data[$i][$j][0]);
						
						$cellPos = PHPExcel_Cell::stringFromColumnIndex($columns).($i+1);
						$activeSheet->getStyle($cellPos)->applyFromArray(
								array(
										'fill' => array(
												'type' => PHPExcel_Style_Fill::FILL_SOLID,
												'color' => array('rgb' => 'FF0000')
										)
								)
						);
						$activeSheet->setCellValue($cellPos, $data[$i][$j][1]);
					}
					else{
						$activeSheet->setCellValue($cellPos,$data[$i][$j]);						
					}
				}
				
				if($i == 0){
					$cellPos = PHPExcel_Cell::stringFromColumnIndex($columns).(1);
					$activeSheet->getStyle($cellPos)->applyFromArray(
							array(
									'fill' => array(
											'type' => PHPExcel_Style_Fill::FILL_SOLID,
											'color' => array('rgb' => 'FF0000')
									)
							)
					);
					$activeSheet->setCellValue($cellPos,'错误信息');
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
