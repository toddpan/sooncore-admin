<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @abstract 	Log Controller，操作日志控制器
 * @author 		yanzou <yan.zou@quanshi.com>
 * @copyright 	Copyright (c) UC
 * @version 	v1.0
 */
class Log extends Admin_Controller{
	
     /**
      * 构造方法
      */
     public function __construct() {
         parent::__construct();
     }
     
    /**
     * 显示起止日期和输入框页面
     */
    public function logPage() {
    	$this->setFunctions();
    	
    	if(!$this->functions['LogManage']){
    		$this->redirectToMainPage();
    	}
    	else{
        	$this->load->view('log/log.php');
    	}
    }

    private function setFunctions(){
    	$roleFunctions = $this->setFunctionsByRole();
    	$customFunctions = $this->setFunctionsBySite();
    
    	$functions = array_merge($customFunctions, $roleFunctions);
    
    	foreach ($customFunctions as $key=>$value){
    		$functions[$key] = $functions[$key] && $value;
    	}
    
    	$this->functions = $functions;
    }
    
    private function setFunctionsBySite(){
    	$functions = array();
        	
    	$functions['PasswordManage'] = $this->siteConfig['siteType'] == 0;
    
    	return $functions;
    }
    
    private function setFunctionsByRole(){
    	$functions = array();
    
    	$functions['PasswordManage'] = $this->p_role_id == SYSTEM_MANAGER;
    	$functions['SensitiveWord'] = $this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER || $this->p_role_id == EMPPLOYEE_MANAGER;
    	$functions['LogManage'] = $this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER;
    	$functions['UserActionManage'] = $this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER || $this->p_role_id == EMPPLOYEE_MANAGER;
    
    	return $functions;
    }
    
    
    
    
    
    /**
     * 显示日志列表页面
     */
    public function loglist() {
       $type 	= 1;	// 类型：1、页面展示；2、导出档案
       $bdate 	= strtolower($this->input->post('bdate', true)); 	// 开始时间
       $edate 	= strtolower($this->input->post('edate', true)); 	// 结束时间
       $keyword = strtolower($this->input->post('keyword', true));	// 关键词
       
       $in_array = array(
            'type' 	=> $type,		// 类型：1、页面展示；2、导出档案
            'bdate' => $bdate,		// 开始时间
            'edate' => $edate,		// 结束时间
            'keyword' => $keyword,	// 关键词
       );
       $this->showfile_list($in_array);
    }
    
    /**
     * 下载日志excel
     */
    public function down_log() {
       $type 	= 2; 	// 类型：1、页面展示；2、导出档案
       $bdate 	= $this->uri->segment(3); // 结束时间
       $edate 	= $this->uri->segment(4); // 结束时间
       $keyword = $this->uri->segment(5); // 关键词
       
       $in_array = array(
            'type' 		=> $type,	// 类型：1、页面展示；2、导出档案
            'bdate' 	=> $bdate,	// 开始时间
            'edate' 	=> $edate,	// 结束时间
            'keyword' 	=> $keyword,// 关键词
       ); 
       $this->showfile_list($in_array);
    }
    
    /**
     * 显示日志列表或者下载日志excel
     */
    public function showfile_list($in_array = array()){
    	$type 		= arr_unbound_value($in_array, 'type', 2, 1);		// 类型：1、页面展示；2、导出档案
    	$bdate 		= arr_unbound_value($in_array, 'bdate', 2, '');		// 开始时间
    	$edate 		= arr_unbound_value($in_array, 'edate', 2, '');		// 结束时间
    	$keyword 	= arr_unbound_value($in_array, 'keyword', 2, '');	// 关键词
    	
        $where_sql = "site_id =" . $this->p_site_id . " and Org_id =" . $this->p_org_id;
        if(!bn_is_empty($bdate)){
            $where_sql .= " and addtime >='" . $bdate . " 00:00:01'";
        }
        if(!bn_is_empty($edate)){
            $where_sql .= " and addtime <='" . $edate . " 23:59:59'";
        }
        if(!bn_is_empty($keyword)){
        	$keyword = ($keyword == "'") ? "‘" : $keyword;
            $where_sql .= " and ( login_name like '%" . $keyword . "%' or display_name like '%" . $keyword . "%')";
        }
        
        $this->load->model('uc_log_model');
        $data_log = array(
            'select' 	=>'log_type_name,log_content,display_name,ip,addtime',
            'where' 	=> $where_sql,
            'order_by' 	=> 'id desc'
        );
        $log_arr = $this->uc_log_model->operateDB(2,$data_log);

        if($type == 1 ){// 类型：1、页面展示；2、导出档案
            $data['log_arr'] = $log_arr;
            $this->load->view('public/part/log_list.php', $data);
        }else{
        	$this->load->helper('my_phpexcel');
        	$file_arr = array();
            $head_arr 		= array('活动名称', '活动说明', '操作人员', 'IP地址', '时间');
            $log_arr 		= array_merge(array($head_arr), $log_arr); 
            $re_filename 	= create_excel($log_arr, '07', $file_arr, 0);
        }
    }
}