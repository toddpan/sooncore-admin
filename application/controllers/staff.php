<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @abstract 	Staff Controller，主要负责对员工的列表显示、新加、删除、权限管理等操作。
 * @filesource 	staff.php
 * @author 		zouyan <yan.zou@quanshi.com>
 * @copyright 	Copyright (c) UC
 * @version 	v1.0
 */
class Staff extends Admin_Controller {
	/**
	 * @abstract 构造函数
	 */
	public function __construct() {
		parent::__construct();
		$this->load->helper('my_publicfun');
		$this->load->helper('my_httpcurl');
		$this->load->library('API', '', 'API');
		// 加载员工相关操作的中文提示信息类
		$this->lang->load('staff', 'chinese');
	}

	/**
	 * @abstract 在组织管理页面修改员工信息
	 */
	public function modify_staff_page(){
		// 获取user_id
		$user_id = strtolower($this->input->post('user_id', true));
		log_message('info', __FUNCTION__." input->\n".var_export(array('user_id' => $user_id), true));

		// 验证user_id
		if(!preg_match("/^[\d]+$/", $user_id)){
			form_json_msg('1', '', "The current user'id is wrong");
		}

		// type:1、在组织管理页面添加员工；2、在组织管理页面修改员工；3、在任务列表中点击“立即处理”来新加员工
		$this->add_modify_staff_page(1, $user_id, array('type' => 2, 'type_arr' => array()));
	}

	/**
	 * @abstract 在组织管理页面添加员工
	 */
	public function add_staff_page(){
		// type:1、在组织管理页面添加员工；2、在组织管理页面修改员工；3、在任务列表中点击“立即处理”来新加员工
		$this->add_modify_staff_page(0, 0, array('type' => 1, 'type_arr' => array()));
	}

	 /* @abstract 通过搜索页展示出来的员工详情信息
	 */
	public function search_staff_info_page(){
		// 获取user_id
		$user_id = strtolower($this->input->post('user_id', true));
		$flag = strtolower($this->input->post('flag', true));
		log_message('info', __FUNCTION__." input->\n".var_export(array('user_id' => $user_id), true));
		
		// 验证user_id
		if(!preg_match("/^[\d]+$/", $user_id)){
			form_json_msg('1', '', "The current user'id is wrong");
		}
		// type:1、在组织管理页面添加员工；2、在组织管理页面修改员工；3、在任务列表中点击“立即处理”来新加员工
		$this->add_modify_staff_page(1, $user_id, array('type' => 2, 'type_arr' => array('flag'=>$flag)));
	}

	/**
	 * @abstract 在任务列表中点击“立即处理”来新加员工
	 */
	public function add_staff_taskpage(){
		// 获取任务id
		$task_id = $this->uri->segment(3);

		// 验证任务id
		if(!preg_match("/^[\d]+$/", $task_id)){
			form_json_msg('1', '', 'The operate type is wrong!');
		}

		// 组装数据
		$type_arr = array(
            'task_id' => $task_id
		);

		// type:1、在组织管理页面添加员工；2、在组织管理页面修改员工；3、在任务列表中点击“立即处理”来新加员工
		$this->add_modify_staff_page(0, 0, array('type' => 3, 'type_arr' => $type_arr));
	}

	/**
	 * @abstract 重置密码
	 */
	public function reset_pwd() {
		// 获取user_id
		$user_id = $this->input->post('user_id', true);
		log_message('info', __FUNCTION__ . " input->\n" . var_export(array('user_id' => $user_id), true));

		// 验证user_id
		if(!preg_match("/^[\d]+$/", $user_id)){
			form_json_msg('1', '', "The current user's id is wrong");
		}

		// 加载StaffLib类库
		$this->load->library('StaffLib', '', 'StaffLib');

		// 组装条件数组
		$where_arr = array(
                'org_id'  => $this->p_org_id, 
                'site_id' => $this->p_site_id,                           
		);

		//TODO 等战役接口通了打开
		$re_boolean = true; // $this->StaffLib->reset_pwd($user_id, $where_arr);

		if($re_boolean){
			// 成功
			form_json_msg('0', '', 'Reset password successfully!');
		}else{
			// 失败
			form_json_msg('1', '', 'Reset password failed!');
		}
	}

	/**
	 * @abstract 添加/修改员工信息
	 * @param int 		$is_modify 		0新加1修改
	 * @param int 		$user_id 		当前用户id
	 * @param array 	$page_type_arr 	页面类型数组
	 * 		$page_type_arr = array(
	 * 				'type' 	   => $aaa,	// type:1、在组织管理页面添加员工；2、在组织管理页面修改员工；3、在任务列表中点击“立即处理”来新加员工
	 * 				'type_arr' => $aa   //各类型的其它参数
	 * 		);
	 * @return 输出新加或修改视图
	 */
	public function add_modify_staff_page($is_modify = 0, $user_id = 0, $page_type_arr = array()){
		log_message('info', __FUNCTION__." input->\n".var_export(array('is_modify' => $is_modify, 'user_id' => $user_id, 'page_type_arr' => $page_type_arr), true));

		// 验证是否是添加或修改员工
		if(!preg_match("/^[01]{1}$/", $is_modify)){
			form_json_msg('1', '', 'The operate type is wrong!');
		}

		// 如果是添加员工，则$user_id为0
		if($is_modify == 0){
			$user_id = 0;
		}

		// 验证user_id
		if(!preg_match("/^[\d]+$/", $user_id)){
			form_json_msg('1', '', "The current user's id is wrong!");
		}

		// type:1、在组织管理页面添加员工；2、在组织管理页面修改员工；3、在任务列表中点击“立即处理”来新加员工
		$add_type = arr_unbound_value($page_type_arr, 'type', 2, '');
		// 各类型的其它参数
		$type_arr = arr_unbound_value($page_type_arr, 'type_arr', 1, array());

		// 初始化用户详情信息数组
		$user_auto_arr = array();
		switch ($add_type) {
			case 1:// 1、在组织管理页面添加员工
				break;
			case 2:// 2、在组织管理页面修改员工
				break;
			case 3:// 3、在任务列表中点击“立即处理”来新加员工
				$task_id = arr_unbound_value($type_arr, 'task_id', 2, 0);

				$this->load->model('employee_change_task_model');

				$where_arr = array(
                        'type' => 1,	// 1-add 2-transfer  3-delete
                        'id'   => $task_id
				);

				$task_arr  = $this->employee_change_task_model->get_task_arr($where_arr);
				$task_info = arr_unbound_value($task_arr, 'task_info', 2, '[]');
				$task_arr  = json_decode($task_info,true);

				$display_name      = arr_unbound_value($task_arr, 'display_name', 2, '');		// 新加人姓名
				$mobile            = arr_unbound_value($task_arr, 'mobile', 2, '');				// 手机号
				$current_dept_id   = arr_unbound_value($task_arr, 'current_dept_id', 2, '');	// 当前部门id
				$current_dept_name = arr_unbound_value($task_arr, 'current_dept_name', 2, '');	// 当前部门名称
				$position          = arr_unbound_value($task_arr, 'position', 2, '');			// 职位
				$account_enable    = arr_unbound_value($task_arr, 'account_enable', 2, '');		// 是否是管理员

				$user_auto_arr['lastName']     = $display_name;
				$user_auto_arr['position']     = $position;
				$user_auto_arr['mobileNumber'] = $mobile;
				$user_auto_arr['isopen']       = $account_enable;
				break;
			default:
				break;
		}

		$this->load->library('StaffLib', '', 'StaffLib');

		$in_arr = array(
            'user_id'  => $user_id,			// 用户id，如果没有，则写0
            'tag_type' => 4,				// 标签页面类型
            'site_id'  => $this->p_site_id,	// 当前站点id 
		);
		// 获得当前用户标签及标签值
// 		$user_tag_arr = $this->StaffLib->get_user_tag_arr($in_arr, $user_auto_arr);
		$user_tag_arr = $this->StaffLib->get_user_tag2_arr($in_arr, $user_auto_arr);

		$user_info_arr            = arr_unbound_value($user_tag_arr, 'user_info_arr', 1, array());			// 用户详情信息
// 		$system_must_tag_names    = arr_unbound_value($user_tag_arr, 'system_must_tag_names', 2, '');		// 系统标签名称，多个用,号分隔
		$system_must_tag_arr      = arr_unbound_value($user_tag_arr, 'system_must_tag_arr', 1, array());	// 系统标签及其值数组
// 		$seled_not_must_tag_names = arr_unbound_value($user_tag_arr, 'seled_not_must_tag_names', 2, '');	// 可选标签名称，多个用,号分隔
		$seled_not_must_tag_arr   = arr_unbound_value($user_tag_arr, 'seled_not_must_tag_arr', 1, array());	// 可选标签及其值数组
// 		$user_defined_tag_names   = arr_unbound_value($user_tag_arr, 'user_defined_tag_names', 2, '');		// 自定义标签名称，多个用,号分隔
		$user_defined_tag_arr     = arr_unbound_value($user_tag_arr, 'user_defined_tag_arr', 1, array());	// 自定义标签及其值数组
		// 获得手机号
		$user_mobile = arr_unbound_value($user_info_arr, 'mobileNumber', 2, '');

		if(strpos($user_mobile, '+') !== 0){
			$user_mobile = '+86'.$user_mobile;
		}
		
		// 载入国码资源
		include_once APPPATH . 'libraries/public/Country_code.php';

		$country_code_obj   = new Country_code();
		$country_mobile_arr = $country_code_obj->get_mobile_arr($user_mobile);

		$country_code   = arr_unbound_value($country_mobile_arr, 'code', 2, '+86');
		$country_mobile = arr_unbound_value($country_mobile_arr, 'mobile', 2, '');
		$country_arr    = $country_code_obj->get_country_code($country_code);

		$data['user_id']                  = $user_id;					// 当前用户id
		$data['country_code']             = $country_code;				// 国码
		$data['country_arr'] 			  = $country_arr;				// 国码信息
		$data['country_mobile']           = $country_mobile;			// 手机号
		$data['user_info_arr']            = $user_info_arr ;			// 用户详情信息
// 		$data['system_must_tag_names']    = $system_must_tag_names;		// 系统标签名称，多个用,号分隔
		$data['system_must_tag_arr']      = $system_must_tag_arr;		// 系统标签及其值数组
// 		$data['seled_not_must_tag_names'] = $seled_not_must_tag_names;	// 可选标签名称，多个用,号分隔
		$data['seled_not_must_tag_arr']   = $seled_not_must_tag_arr;	// 可选标签及其值数组
// 		$data['user_defined_tag_names']   = $user_defined_tag_names;	// 自定义标签名称，多个用,号分隔
		$data['user_defined_tag_arr']     = $user_defined_tag_arr;		// 自定义标签及其值数组
		
		$this->load->library('OrganizeLib','','OrganizeLib');

		// 获得客户编码
		$customer_code = $this->p_customer_code;
		//echo $customer_code;

		// 首级及下一级组织数组
		$first_next_org_arr = $this->OrganizeLib->get_first_next_org_arr($customer_code, '1,3,5');

		// 首级及下一级组织json串
		$in_arr = array(
            'is_first' => 1 // 是否第一级0不是1是       
		);
		$org_arr = $this->OrganizeLib->InitzTree_arr($first_next_org_arr, 1, $in_arr);

		$org_json = '[]';
		if(is_array($org_arr)){// 如果是数组
			$org_json = json_encode($org_arr);
		}

		$data['org_list_json']  = $org_json;
	  	
		//add by caohongliang
		$this->load->library('BossLib', '', 'boss');
		$data['account_names'] = $this->boss->getAccountInfo($this->p_customer_code, $this->p_contract_id);
		
		
 		//单个用户展示或者是修改用户信息是需要得到账户名称
		if($user_id > 0){
			$this->load->model('uc_user_model', 'user_model');
	 		$_account_tmp = array_column($data['account_names'], 'name', 'accountId');
	 		$user_account_id = $this->user_model->getAccountIdByUserId($user_id);
	 		$data['account_id'] = $user_account_id;
	 		$data['account_name'] = isset($_account_tmp[$user_account_id]) ? $_account_tmp[$user_account_id] : '';
		}
		
		$data['add_type']       = $add_type;
		$data['page_type_json'] = json_encode($page_type_arr);

		// 获取办公地址并显示在页面上
		$this->load->model('uc_area_model');
		$db_address_arr = array(
			'select' => 'country,area,address',
			'where' => array(
				'customerCode' => $customer_code,
				'siteID' => $this->p_site_id
			)
		);
		$re_addrsee_arr = $this->uc_area_model->operateDB(1, $db_address_arr);
		$address_str = '';
		foreach ($re_addrsee_arr as $address_arr){
			$address_str .= $address_arr;
		}
		//echo $address_str;
		$data['address'] = $address_str;
		

		// 当前组织id
		$user_organizationId = 0;

		if($is_modify == 0){
			// 如果是新加
			if($add_type == 3){
				// 如果是在任务列表中点击“立即处理”来新加员工
				$user_organizationId = $current_dept_id;
			}
			$ns_org_arr = $this->OrganizeLib->get_orgup_arr($user_organizationId, array());	

			// 根据部门串，获得部门串数组
			$data['org_json'] = json_encode($ns_org_arr);

			log_message('info', __FUNCTION__." output->\n".var_export($data, true));
// 			var_dump($data);
			$this->load->view('public/popup/addstaff.php', $data);
		}else{
			// 如果是修改
			$user_organizationId = arr_unbound_value($user_info_arr, 'organizationId', 2, '');
			$ns_org_arr          = $this->OrganizeLib->get_orgup_arr($user_organizationId, array());
			if(!empty($type_arr)){
				$ns_org_arr[0]['flag'] = $type_arr['flag'];
			}
			
			// 判断当前账号是不是管理员账号并且是否被停用
			$condition_arr = array(
					'user_id' 	=> $user_id,
					'state' 	=> 1
			);
			$this->load->model('UC_User_Admin_Role_Model'); // 管理员角色
			$uc_admin_arr = $this->UC_User_Admin_Role_Model->getAdminByUseridAndState($condition_arr);
			$ns_org_arr = empty($uc_admin_arr) ? $ns_org_arr : array_merge($ns_org_arr, array('admin_arr' => $uc_admin_arr));
			log_message('debug', '$uc_admin_arr=' . json_encode($uc_admin_arr));
				
			// 根据部门串，获得部门串数组
			$data['org_json']    = json_encode($ns_org_arr);

			log_message('info', __FUNCTION__." output->\n".var_export($data, true));
			
			$this->setFunctions();
			
			$this->load->view('staff/staffInfoPower.php',$data);
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
		
		$functions['changePassword'] = $this->siteConfig['siteType'] == 0;
		$functions['employeeEdit'] = $this->siteConfig['importType'] != 2;
		
		return $functions;
	}
	
	private function setFunctionsByRole(){
		$functions = array();
		
		$functions['AccountOperation'] = $this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER || $this->p_role_id == EMPPLOYEE_MANAGER || $this->p_role_id == ACCOUNT_MANAGER;
		$functions['changePassword'] = $this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER || $this->p_role_id == EMPPLOYEE_MANAGER || $this->p_role_id == ACCOUNT_MANAGER;
		$functions['employeeAuthority'] = $this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER || $this->p_role_id == EMPPLOYEE_MANAGER || $this->p_role_id == ACCOUNT_MANAGER;
		$functions['employeeEdit'] = $this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER || $this->p_role_id == EMPPLOYEE_MANAGER;

		return $functions;
	}
	
	
	
	
	
	
	
	

	/**
	 * @abstract 保存用户信息
	 */
	public function save_staff(){
		// 获取从表单提交的数据
		$user_id        = $this->input->post('user_id', true);
		$add_type       = $this->input->post('add_type', true);
		$page_type_json = $this->input->post('page_type_json', true);
		$user_json      = $this->input->post('user_json', true);
		log_message('info', __FUNCTION__." input->\n".var_export(array('user_id' => $user_id, 'add_type'=> $add_type, 'page_type_json' => $page_type_json, 'user_json' => $user_json), true));
		
		//////////////////////////////
		//////add by caohongliang
		
		//获取参数
		$tagValue = array();
		if(empty($user_json) OR is_null($tagValue = json_decode($user_json, true))){
			form_json_msg('1', '', 'The parammeter named user_json is null');
		}
		
		//整理参数
		$task_value = array();
		
		$task_value['customer_code'] = $this->p_customer_code;
		$task_value['site_id']		 = $this->p_site_id;
		$task_value['org_id']		 = $this->p_org_id;
		
		$tags_tmp = array_column($tagValue['sys_tag'], 'value', 'umsapifield');
		$tags     = array();
		$tags['lastname'] 		= 	$tags_tmp['lastName'];
		$tags['firstname']		=	'';
		$tags['loginname']  	=   $tags_tmp['loginName'];
		$tags['open']			=	$tags_tmp['isopen'] == 1 ? true : false;
// 		$tags['sex']			=	$tags_tmp['sex'];//1-男 2-女
		$tags['account']		=	$tags_tmp['accountId'];//TODO
		$tags['position']   	=	$tags_tmp['position'];
		$tags['mobile']			=	$tags_tmp['mobileNumber'];
		$tags['email']			=	$tags_tmp['email'];
		$tags['country']		=	$tags_tmp['countryCode'];
		$tags['officeaddress']	=	$tags_tmp['officeaddress'];
		
		$dept_tmp = array_column($tagValue['org_tag'], 'value');
		array_shift($dept_tmp);//将第一个公司组织去掉
		$dept     = array();
		foreach($dept_tmp as $k=>$v){
			$dept['department'.($k+1)] = $v;
		}
		
		$task_value['users'][] = array_merge($tags, $dept);
		
		//将要添加的用户信息添加到任务表里
		$this->load->model('account_upload_task_model', 'upload_task');
		$this->upload_task->saveTask(ACCOUNT_CREATE_UPLOAD, json_encode($task_value));
		
		// 写操作日志
		$this->load->library('LogLib','','LogLib');
		$log_in_arr = $this->p_sys_arr;
		$re_id      = $this->LogLib->set_log(array('5','4'),$log_in_arr);
		
		//返回成功
		form_json_msg('0','', 'Save successfully!');
		
		//////////////////////////////////////// 
		/*
		// 验证user_id
		if(!preg_match("/^[\d]+$/",$user_id)){
			form_json_msg('1', '', "The current user'id is wrong");
		}

		// 判断$user_json是否为空
		if(bn_is_empty($user_json)){
			form_json_msg('1', '', 'The parammeter named user_json is null');
		}
		//echo $user_json;

		// 将$user_json转为数组
		$user_arr = json_decode($user_json, true);
		//print_r($user_arr);

		// 判断数组是否为空
		if(isemptyArray($user_arr)){
			form_json_msg('1', '', 'The parammeter named user_arr is null');
		}

		$page_type_arr = json_decode($page_type_json, true);
		// type:1、在组织管理页面添加员工；2、在组织管理页面修改员工；3、在任务列表中点击“立即处理”来新加员工
		$add_type = empty_to_value($add_type, 1);

		switch ($add_type) {
			case 1:// 1、在组织管理页面添加员工
				$user_source = 2;// 帐号开通来源,多个用“，”号分隔： 1批量导入2组织管理员-单个新加3生态渠道管理员新加4任务新加
				break;
			case 2:// 2、在组织管理页面修改员工
				$user_source = 2;//帐号开通来源,多个用“，”号分隔： 1批量导入2组织管理员-单个新加3生态渠道管理员新加4任务新加
				break;
			case 3:// 3、在任务列表中点击“立即处理”来新加员工
				$user_source = 4;// 帐号开通来源,多个用“，”号分隔： 1批量导入2组织管理员-单个新加3生态渠道管理员新加4任务新加
				break;
			default:
				break;
		}

		// 4 帐号新加页
		$tag_type = 4;

		if($user_id > 0 ){
			// 5 帐号修改页
			$tag_type = 5;
		}

		$this->load->library('StaffLib','','StaffLib');

		$other_arr = array(
            'user_id'  => $user_id,		// 0为新加,具体数字为被修改的userid
            'tag_type' => $tag_type,	// 标签页面类型
		);
		$ns_sys_arr                = $this->p_sys_arr;
		$ns_sys_arr['parentId']    = $this->p_org_id;			// 当前站点的组织机构id
		$ns_sys_arr['user_type']   = $this->p_open_org_type;	// 帐号类型0普通用户1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员[各种管理员新加时，必填]7生态企业普通用户
		$ns_sys_arr['user_source'] = $user_source;				// 帐号开通来源,多个用，号分隔 1批量导入2组织管理员-单个新加3生态渠道管理员新加4任务新加
		$ns_sys_arr['isLDAP']      = $this->p_is_ldap;			// 帐号导入类型[各种管理员新加时，必填]
		$ns_sys_arr['session_id']  = $this->p_session_id; 		// sessionid

		// 调用保存用户方法，对用户信息进行保存,成功true 失败 false 失败的字符串
		$re_boolean = $this->StaffLib->save_user($user_arr, $other_arr, $ns_sys_arr);

		if($re_boolean == true){
			// 日志
			if($tag_type == 5){// 5 帐号修改页
				$this->load->library('LogLib','','LogLib');
				$log_in_arr = $this->p_sys_arr;
				$re_id      = $this->LogLib->set_log(array('5','4'),$log_in_arr);
			}

			$add_type = arr_unbound_value($page_type_arr,'type',2,'');
			$type_arr = arr_unbound_value($page_type_arr,'type_arr',1,array());
			switch ($add_type) {
				case 1:// 1、在组织管理页面添加员工
					break;
				case 2:// 2、在组织管理页面修改员工
					break;
				case 3:// 3、在任务列表中点击“立即处理”来新加员工
					$task_id = arr_unbound_value($type_arr,'task_id',2,0);

					$this->load->model('employee_change_task_model');
					// 改变任务状态
					$modify_data = array(
                        'update_data' => array('status' => 20),
                        'where'       => array(
                            	'site_id' => $this->p_site_id, 
                            	'type'    => 1,		// 1-add 2-transfer  3-delete
                            	'id'      => $task_id,
                            	'status'  => 1,		// 1客户端申请20管理员同意40管理员拒绝                     
					)
					);
					$re_task_arr = $this->employee_change_task_model->operateDB(5,$modify_data);
					break;
				default:
					break;
			}
			form_json_msg('0','', 'Save successfully!');
		}else{
			if($re_boolean == false){
				log_message('info', __FUNCTION__." output->\n".var_export(array('re_boolean' => $re_boolean), true));
				form_json_msg('1','', 'Save failed!');
			}else{
				// $re_boolean是错误字符串
				log_message('info', __FUNCTION__." output->\n".var_export(array('re_boolean' => $re_boolean), true));
				form_json_msg('1','', 'Save failed ' . $re_boolean);
			}
		}
		*/
	}

	/**
	 * setCompanyAdmin
	 * @abstract 生态企业企业信息页面
	 * @details
	 * -# 查看员工的详细信息，并可以对员工信息进行编辑
	 */
	public function staffInfoPage(){
		$this->load->view('staff/staffInfo.php');
	}

	/**
	 * @abstract 企业生态组织结构中的添加员工按钮：
	 * @details
	 * -# 添加员工下拉列表中的批量添加员工
	 *
	 */
	public function batchAddStaffPage() {
		$this->load->view('staff/batchAddStaff.php');
	}

	/**
	 * @abstract 组织管理组织结构中的添加员工按钮：
	 * @details
	 * -# 弹窗提醒是否删除员工
	 *
	 */
	public function deleteStaff() {
		$this->load->view('public/popup/deleteStaff.php');
	}

	/**
	 * @abstract 保存删除帐号
	 */
	public function save_delete_staff(){
		//当前用户id
		$user_id = $this->input->post('user_id', true);
		// 如果当前用户id为空，则为其添加默认值0
		$user_id = empty_to_value($user_id, 0);
		
		// 判断当前用户id是否为空
		if(bn_is_empty($user_id)){
			form_json_msg('1', '', 'Please choose the staff who you want to delete. ');
		}
		
		// 载入员工类库
		$this->load->library('StaffLib', '', 'StaffLib');
		
		$sys_arr = $this->p_sys_arr;

// 		$re_boolean = $this->StaffLib->del_staff($user_id, $sys_arr);
		if($re_boolean){
			form_json_msg('0', '', 'Delete staff successfully!');
		}else{
			form_json_msg('11', '', 'Delete staff failed!');
		}
	}

	/**
	 * @abstract 弹窗显示员工调岗页面
	 * @details
	 * -# 弹窗显示员工调岗页面
	 */
	public function moveStaff(){
		$this->load->view('public/popup/movestaff.php');
	}

	/**
	 * @abstract 新组织，调入员工
	 */
	public function neworg_move_staff(){
		//当前用户id
		$user_id = $this->input->post('user_id' , true);//需要调入的用户信息串{"userid":5810,"orgid":528,"org_name":BOSS组,"org_pid":521}
		$user_id = empty_to_value($user_id,'');
		// echo '$user_id=' . $user_id . '<br/>';
		$org_id = $this->input->post('org_id' , true);//新加的组织id 0
		$org_id = empty_to_value($org_id,'');
		// echo '$org_id=' . $org_id . '<br/>';
		$org_name = $this->input->post('org_name' , true);//新加的组织名称 新建节点2
		// echo '$org_name=' . $org_name . '<br/>';
		$org_code = $this->input->post('org_code' , true);//新加的组织名称-513-964-0
		// echo '$org_code=' . $org_code . '<br/>';
		$org_pid = $this->input->post('org_pid' , true);//新加组织的父组织id 964
		// echo '$org_pid=' . $org_pid . '<br/>';
		//die();
		if( bn_is_empty($user_id) || bn_is_empty($org_id) || bn_is_empty($org_name) || bn_is_empty($org_pid)){//为空
			form_json_msg('1','', '参数有误');//
		}
		if(is_not_json($user_id)){//不是json串
			form_json_msg('2','', '$user_id ' . $user_id . ' not is json');//
		}
		$user_arr = json_decode($user_id,true);

		$this->load->library('StaffLib','','StaffLib');
		$other_arr = array(
            'site_id' => $this->p_site_id,//站点id 
            'obj' => array(
                'sys' => $this->p_sys_arr
		)
		);
		$move_boolean = $this->StaffLib->neworg_get_user($user_arr,$org_id,$other_arr);
		if($move_boolean){//成功
			form_json_msg('0','', '调入员工成功');

		}else{
			form_json_msg('1','', '调入员工失败');
		}
	}

	/**
	 * @abstract 员工调岗保存
	 */
	public function save_move_staff(){
		$search_flag = $this->input->post('search_flag', true);
		$org_id 	= $this->input->post('orgid', true);		// 旧的组织id
		//$orgname 	= $this->input->post('orgname', true);		// 旧的组织名称
		$user_id 	= $this->input->post('user_id', true);		// 当前用户id
		$new_org_id = $this->input->post('neworgid', true);		// 新的组织id
		//$neworgname = $this->input->post('neworgname', true);	// 新的组织名称
		//$org_pid 	= $this->input->post('parent_orgid', true);	// 新的父组织id
		log_message('info', 'Into method save_move_staff');
		// 判断是否为json串
		if(is_not_json($user_id)){
			form_json_msg('2', '', '$user_id ' . $user_id . ' not is json');
		}
		$user_arr = json_decode($user_id, true);

		if($search_flag == 'search'){	//如果是搜索页面过来的调岗需求，则需要逐个对应上组织
			foreach($user_arr as $u_k => $u_v){
				$user_arr[$u_k]['orgid'] 	= $org_id[$u_k]; 	// 用户以前所属的组织id
			}
		}else {
			foreach($user_arr as $u_k => $u_v){
				$user_arr[$u_k]['orgid'] 	= $org_id; 	// 用户以前所属的组织id
				//$user_arr[$u_k]['org_name'] = $orgname; // 用户以前所属的组织名称
			}
		}
		
		$this->load->library('StaffLib','','StaffLib');
		$other_arr = array(
            'site_id' 	=> $this->p_site_id,
            'obj' 		=> array(
                		'sys' => $this->p_sys_arr
				)
		);
		$move_boolean = $this->StaffLib->neworg_get_user($user_arr, $new_org_id, $other_arr);
		if($move_boolean){
			form_json_msg('0', '', '调岗成功');
		}else{
			form_json_msg('1', '', '调岗失败');
		}
	}

	/**
	 * @abstract 员工调岗消息发送
	 * @param array $user_arr 用户数组
	 $user_arr = array(
	 'userid' => $aa,//用户id
	 'user_name' => $aa,//用户姓名
	 'org_name' => $aa,//旧部门名称[可以没有，没有时用其它参数里的]
	 );
	 * @param array $other_arr 其它参数
	 $other_arr = array(
	 'new_org_id' => $aaa,//新的部门id
	 'new_org_name' => $aaa,//新的部门名称
	 'old_org_name' => $aaa,//旧的部门名称//可以为空;如果用户数组有的话，用用户数组的
	 );
	 */
	public function staff_change_org_info($user_arr = array(), $other_arr = array()){
		$this->load->library('Informationlib', '', 'Informationlib');
		
		// 员工部门调动
 		$ns_allold_org_id 	= arr_unbound_value($other_arr, 'org_id', 2, '');		// 旧的部门id
		$ns_new_org_id 		= arr_unbound_value($other_arr, 'new_org_id', 2, '');	// 新的部门id
		$ns_new_org_name 	= arr_unbound_value($other_arr, 'new_org_name', 2, '');	// 新的部门名称
		$ns_allold_org_name = arr_unbound_value($other_arr, 'old_org_name', 2, '');	// 旧部门名称
		
		//根据组织id分别获取就部门和现在部门的管理员信息
		$this->load->model('uc_org_manager_model', 'org_m');
		$old_manager_id = $this->org_m->get_org_manager_userid($ns_allold_org_id, $this->p_site_id);
		$new_manager_id = $this->org_m->get_org_manager_userid($ns_new_org_id, $this->p_site_id);
		
		foreach($user_arr as $u_k => $u_v){
			//echo 1;
			$ns_user_id 		= arr_unbound_value($u_v, 'userid', 2, '');		// 用户id
			$ns_user_name 		= arr_unbound_value($u_v, 'user_name', 2, '');	// 用户姓名
			$ns_old_org_name 	= arr_unbound_value($u_v, 'org_name', 2, '');	// 旧部门名称
			
			if(bn_is_empty($ns_old_org_name)){ //为空,则用其它参数中的
				$ns_old_org_name = $ns_allold_org_name;
			}
			
			//获得用户信息
			if($ns_user_id > 0 ){
				/***************发送消息给个人_start************/
				$info_pre_arr = array(
                    'from_user_id' 	=> $this->p_user_id,	// 消息发送者用户id
                    'from_site_id' 	=> $this->p_site_id,	// 消息发送者站点id
                    'to_user_id' 	=> $ns_user_id,			// $ns_new_org_id,//消息接受者id[是组织时为组织id,是用户时，是用户id]
                    'to_site_id' 	=> $this->p_site_id,	// 消息接受者站点id
                    'is_group' 		=> 0,					// 是否为讨论组聊天1是[是组织] 0 否[是单个用户]   
                    'msg_type' 		=> 1,					// 消息类型  1 - 组织变动
                    'msg_id' 		=> 2,					// 1 - 部门名称变更    2 - 员工部门调动    3 - 职位调整  4 -员工入职 5 - 员工离职   10 - 部门删除  11 - 员工入职确认 12 - 员工离职确认 13 - 员工部门调动确认 14 - 员工入职拒绝消息 15 - 员工离职拒绝消息 16 - 员工部门调动拒绝消息 
				);
				$info_body = array(
                    'operator_id' 	=> $this->p_user_id,	// 操作发起人用户ID
                    'user_id' 		=> $ns_user_id,			// 用户ID
                    'user_name' 	=> $ns_user_name ,		// 用户姓名
                    'dept_id' 		=> $ns_new_org_id,		// 新部门ID
                    'old_dept_name' => $ns_old_org_name,	// 旧部门名称
                    'dept_name' 	=> $ns_new_org_name,	// 新部门名称
                    'desc' 			=> '',					// 消息描述
				);
				
				log_message('info', 'into class ' . json_encode($info_pre_arr) . json_encode($info_body) . '.');
				
				$this->Informationlib->send_info($info_pre_arr,$info_body);
				
				log_message('info', 'send msg orgchange userid = ' . $ns_user_id . '.');
				/***************发送消息给个人_end************/
				
				
				/***************发送消息给原部门管理员_start************/
				if($old_manager_id > 0){
					$old_manager_info_pre_arr = array(
							'from_user_id' 	=> $this->p_user_id,// 消息发送者用户id
							'from_site_id' 	=> $this->p_site_id,// 消息发送者站点id
							'to_user_id' 	=> $old_manager_id,	// $ns_new_org_id,//消息接受者id[是组织时为组织id,是用户时，是用户id]
							'to_site_id' 	=> $this->p_site_id,// 消息接受者站点id
							'is_group' 		=> 0,				// 是否为讨论组聊天1是[是组织] 0 否[是单个用户]
							'msg_type' 		=> 1,				// 消息类型  1 - 组织变动
							'msg_id' 		=> 2,				// 1 - 部门名称变更    2 - 员工部门调动    3 - 职位调整  4 -员工入职 5 - 员工离职   10 - 部门删除  11 - 员工入职确认 12 - 员工离职确认 13 - 员工部门调动确认 14 - 员工入职拒绝消息 15 - 员工离职拒绝消息 16 - 员工部门调动拒绝消息
					);
					$old_manager_info_body = array(
							'operator_id' 	=> $this->p_user_id,// 操作发起人用户ID
							'user_id' 		=> $ns_user_id,		// 用户ID
							'user_name' 	=> $ns_user_name ,	// 用户姓名
							'dept_id' 		=> $ns_new_org_id,	// 新部门ID
							'old_dept_name' => $ns_old_org_name,// 旧部门名称
							'dept_name' 	=> $ns_new_org_name,// 新部门名称
							'desc' 			=> '',				// 消息描述
					);
					
					log_message('info', 'into class ' . json_encode($old_manager_info_pre_arr) . json_encode($old_manager_info_body) . '.');
					
					$this->Informationlib->send_info($old_manager_info_pre_arr,$old_manager_info_body);
					
					log_message('info', 'send msg orgchange to_user_id = ' . $old_manager_id . '.');
				}
				/***************发送消息给原部门管理员_end************/
				
				/***************发送消息给新部门管理员_start************/
				if($new_manager_id > 0){
					$new_manager_info_pre_arr = array(
							'from_user_id' 	=> $this->p_user_id,// 消息发送者用户id
							'from_site_id' 	=> $this->p_site_id,// 消息发送者站点id
							'to_user_id' 	=> $new_manager_id,	// $ns_new_org_id,//消息接受者id[是组织时为组织id,是用户时，是用户id]
							'to_site_id' 	=> $this->p_site_id,// 消息接受者站点id
							'is_group' 		=> 0,				// 是否为讨论组聊天1是[是组织] 0 否[是单个用户]
							'msg_type' 		=> 1,				// 消息类型  1 - 组织变动
							'msg_id' 		=> 2,				// 1 - 部门名称变更    2 - 员工部门调动    3 - 职位调整  4 -员工入职 5 - 员工离职   10 - 部门删除  11 - 员工入职确认 12 - 员工离职确认 13 - 员工部门调动确认 14 - 员工入职拒绝消息 15 - 员工离职拒绝消息 16 - 员工部门调动拒绝消息
					);
					$new_manager_info_body = array(
							'operator_id' 	=> $this->p_user_id,// 操作发起人用户ID
							'user_id' 		=> $ns_user_id,		// 用户ID
							'user_name' 	=> $ns_user_name ,	// 用户姓名
							'dept_id' 		=> $ns_new_org_id,	// 新部门ID
							'old_dept_name' => $ns_old_org_name,// 旧部门名称
							'dept_name' 	=> $ns_new_org_name,// 新部门名称
							'desc' 			=> '',				// 消息描述
					);
						
					log_message('info', 'into class ' . json_encode($new_manager_info_pre_arr) . json_encode($new_manager_info_body) . '.');
						
					$this->Informationlib->send_info($new_manager_info_pre_arr, $new_manager_info_body);
						
					log_message('info', 'send msg orgchange to_user_id = ' . $new_manager_id . '.');
				}
				/***************发送消息给新部门管理员_end************/
			}
		}
	}

	/**
	 * @abstract 生态企业企业信息页面
	 * @details
	 * -# 关闭蜜蜂账号
	 */
	public function closeAccount(){
		$this->load->library('StaffLib','','StaffLib');
		$user_id = $this->uri->segment(3);
		$ns_user_arr = $this->StaffLib->get_user_by_id($user_id);
		$ns_user_name = arr_unbound_value($ns_user_arr,'displayName',2,'');
		$data['user_name'] = $ns_user_name;
		$this->load->view('public/popup/closeAccount.php',$data);
	}

	/**
	 * 关闭单个用户
	 * 
	 */
	public function close_user(){
		//获取参数
		$user_id=$this->input->post('user_id', true);
		
		if(!$user_id){
			form_json_msg(COMMON_PARAM_ERROR,'','invalid user id',array('user_id' => $user_id));//返回信息json格式
		}
		
		//判断用户是否为系统管理员，如果是，则不能关闭
		$this->load->model('uc_user_admin_role_model', 'role_model');
		$role_ids = $this->role_model->getRoleIdsByUserId($user_id);
		if(in_array(SYSTEM_MANAGER,$role_ids)){
			form_json_msg(COMMON_PARAM_ERROR,'','system manager can not be closed',array('user_id' => $user_id));//返回信息json格式
		}
		
		//从ums获取用户所在的组织
		$this->load->library('UmsLib', '', 'ums');
		$org_info = $this->ums->getOrgInfoByUserId($user_id);
		if(count($org_info) <= 0){
			form_json_msg(COMMON_PARAM_ERROR,'','can not get org info from ums',array('user_id' => $user_id));//返回信息json格式
		}
		
		//写任务
		$data = array(
			'customer_code'=>$this->p_customer_code,
			'site_id'	   =>$this->p_site_id,
			'org_id'	   =>$org_info[0]['id'],
			'user_ids'	   =>array($user_id),
		);
		$this->load->model('account_upload_task_model', 'upload_task');
		$this->upload_task->saveTask(ACCOUNT_DISABLE_UPLOAD, json_encode($data));
		
		//返回成功信息
		form_json_msg(COMMON_SUCCESS,'','success',array('user_id' => $user_id));//返回信息json格式
		
		/*
		if(!preg_match('/^[\d]+$/',$user_id)){
			form_json_msg('1','','参数有误！');//返回错误信息json格式
		}

		$type_id=$this->input->post('type_id', true);//类型1组织列表2帐号详情页3成本中心4企业生态人员列表
		$type_id = empty_to_value($type_id,1);
		$site_id = $this->p_site_id;
		$site_id = empty_to_value($site_id,0);//517
		$this->load->library('StaffLib','','StaffLib');
		$in_array = array(
            'user_id' => $user_id,            
            'sys' => $this->p_sys_arr
		);
		//true成功 FALSE 失败
		$re_boolean = $this->StaffLib->open_close_user(array($in_array), 0);
		if($re_boolean){//成功
			//日志
			$this->load->library('LogLib','','LogLib');
			$log_in_arr = $this->p_sys_arr;
			$re_id = $this->LogLib ->set_log(array('5','8'),$log_in_arr);
			form_json_msg('0','','success',array('user_id' => $user_id));//返回信息json格式
		}else{
			form_json_msg('2','','fail',array('user_id' => $user_id));//返回信息json格式
		}
		*/
	}

	/**
	 *  开通单个用户
	 */
	public function open_user(){
		$user_id=$this->input->post('user_id', true);
		
		//获取参数
		$user_id=$this->input->post('user_id', true);
		
		if(!$user_id){
			form_json_msg(COMMON_PARAM_ERROR,'','invalid user id',array('user_id' => $user_id));//返回信息json格式
		}
				
		//从ums获取用户所在的组织
		$this->load->library('UmsLib', '', 'ums');
		$org_info = $this->ums->getOrgInfoByUserId($user_id);
		if(count($org_info) <= 0){
			form_json_msg(COMMON_PARAM_ERROR,'','can not get org info from ums',array('user_id' => $user_id));//返回信息json格式
		}
		
		//写任务
		$data = array(
				'customer_code'=>$this->p_customer_code,
				'site_id'	   =>$this->p_site_id,
				'org_id'	   =>$org_info[0]['id'],
				'user_ids'	   =>array($user_id),
		);
		$this->load->model('account_upload_task_model', 'upload_task');
		$this->upload_task->saveTask(ACCOUNT_ENABLE_UPLOAD, json_encode($data));
		
		//返回成功信息
		form_json_msg(COMMON_SUCCESS,'','success',array('user_id' => $user_id));//返回信息json格式
		
		
		/*
		$user_id = empty_to_value($user_id,0);
		if(!preg_match('/^[\d]+$/',$user_id)){
			form_json_msg('1','','参数有误！');//返回错误信息json格式
		}
		$type_id=$this->input->post('type_id', true);//类型1组织列表2帐号详情页3成本中心4企业生态人员列表
		$type_id = empty_to_value($type_id,1);

		$site_id = $this->p_site_id;
		$site_id = empty_to_value($site_id,0);//517
		$this->load->library('StaffLib','','StaffLib');
		$in_array = array(
            'user_id' => $user_id,            
             'sys' => $this->p_sys_arr
		);

		//true成功 FALSE 失败
		$re_boolean = $this->StaffLib->open_close_user(array($in_array), 1);
		if($re_boolean){//成功
			//日志
			$this->load->library('LogLib','','LogLib');
			$log_in_arr = $this->p_sys_arr;
			$re_id = $this->LogLib ->set_log(array('5','7'),$log_in_arr);
			form_json_msg('0','','开通成功',array('user_id' => $user_id));//返回信息json格式
		}else{
			form_json_msg('2','','开通失败',array('user_id' => $user_id));//返回信息json格式
		}
		
		*/
	}
	
	/**
	 * 获得用户权限
	 */
	public function get_user_power(){
		$user_id = $this->input->post('user_id', true);
		log_message('info', 'Into method get_user_power input----> userid = ' . $user_id);
		
		// 载入UmsLib类库
		$this->load->library('UmsLib', '', 'ums');
		
		// 判断用户在UMS是否存在
		$user_info = $this->ums->getUserById($user_id);
		if($user_info == false){
			return_json(USER_IS_NOT_EXIST, $this->lang->line('userid_is_wrong'), array()); // 该用户不存在
		}
		
		// 载入权限类库
		$this->load->library('RightsLib', '', 'RightsLib');
		
		// 根据userId获取用户个性化权限
		$re_data = $this->RightsLib->get_right_from_user($user_id);
		
		// 如果用户没有个性化权限，则从UMS取出用户所在的部门串，然后根据部门串获取部门权限
		if(isemptyArray($re_data)){
			
			// 获得用户所在部门串
			$org_info = $this->ums->getOrgInfoByUserId($user_id);
			if($org_info == false){
				return_json(ORG_CODE_IS_WRONG, $this->lang->line('org_code_empty'), array()); // 该用户所在组织不正确
			}
			$org_code = isset($re_data['nodeCode']) ? $re_data['nodeCode'] : '';
			
			$re_data = $this->RightsLib->get_right_from_org($org_code);
		}
		
		// 如果部门权限为空，则根据站点Id获得站点的权限
		if(isemptyArray($re_data)){
			$re_data = $this->RightsLib->get_right_from_site($this->p_site_id);
		}
		
		// 取出权限
		$power_arr = isset($re_data['right_arr']) ? $re_data['right_arr'] : array();
		
		// 将获得的权限转换成数组
		$power_arr = $this->RightsLib->recombine_right($power_arr);
		
		// 将数据传递到页面上
		form_json_msg(COMMON_SUCCESS, '', '', array('power' => $power_arr));
	}
	
	
	/**
	 * 保存用户权限
	 */
	public function save_user_power(){
		// 获得用户Id和用户权限串
		//$user_id = 61358507;
		$user_id 	= $this->input->post('user_id', true);
		//$power_json = '{"UC_passDoc":"2","UC_answerStrategy":"1","UC_answerStrategyOverseas":"1","UC_isCall":"1","UC_allowcallOverseas":"1","summit_ParticipantNameRecordAndPlayback":"0","summit_Pcode1InTone":"1","summit_Pcode1OutTone":"1","summit_Pcode2InTone":"1","summit_Pcode2OutTone":"1","summit_ValidationCount":"0","summit_FirstCallerMsg":"1","summit_ConfDnisAccess":"0","summit_ConfQuickStart":"0","tang_stopwhenoneuser":"0","tang_confscale":"200","UC_enableVoip":"1","UC_allowUserVoice":"0","UC_attendeeSurvey":"1"}';
		$power_json = $this->input->post('power_json', true);
		log_message('info', 'Into method get_user_power input----> user_id = ' . $user_id . ' $power_json = '.$power_json);
		
		// 载入UmsLib类库
		$this->load->library('UmsLib', '', 'ums');
		$this->load->library('BossLib', '', 'boss');
		
		// 判断用户是否在UMS中存在
		$user_info = $this->ums->getUserById($user_id);
		if($user_info == false){
			form_json_msg(USER_IS_NOT_EXIST, '',  $this->lang->line('userid_is_wrong'), array()); // 该用户不存在
		}
		
		// 判断用户权限串是否为空
		if(is_empty($power_json)){
			form_json_msg(POWER_JSON_EMPTY, '', $this->lang->line('power_json_empty'), array()); // 权限为空
		}
		
		// 将权限串转换为数组
		$new_power_arr = json_decode($power_json, true);
// 		var_dump($new_power_arr);
// 		die;
		
		// 载入权限类库
		$this->load->library('RightsLib', '', 'RightsLib');
		
		// 验证权限串
		list($flag, $data) = $this->RightsLib->valid_right($new_power_arr);
		if($flag == false){
			form_json_msg(POWER_IS_WRONG, '', $data, array()); // 权限错误
		}else{
			$new_power_arr = $data; // 返回重组后的权限数组
		}
		
		// 获得用户所在部门串
		$org_info = $this->ums->getOrgInfoByUserId($user_id);
		if($org_info == false){
			return_json(ORG_CODE_IS_WRONG, $this->lang->line('org_code_empty'), array()); // 该用户所在组织不正确
		}
		$org_code = isset($re_data['nodeCode']) ? $re_data['nodeCode'] : '';
		
		// 根据userId获取用户旧的个性化权限
		$re_data = $this->RightsLib->get_right_from_user($user_id);
		
		// 如果用户没有个性化权限，根据部门串获取部门旧的权限
		if(isemptyArray($re_data)){	
			$re_data = $this->RightsLib->get_right_from_org($org_code);
		}
		
		// 如果部门权限为空，则根据站点Id获得站点的旧权限
		if(isemptyArray($re_data)){
			$re_data = $this->RightsLib->get_right_from_site($this->p_site_id);
		}
		
		// 取出旧权限和权限获得的位置
		$old_power_arr 		= isset($re_data['right_arr']) ? $re_data['right_arr'] : array();
		$from_where 		= isset($re_data['from_where'])?$re_data['from_where']:POWER_FROM_USER;
		$from_where_data 	= isset($re_data['from_where_data'])?$re_data['from_where_data']:'';
		
		// 对比新旧权限数组，判断权限是否发生变化
		$res_arr 			= $this->RightsLib->compare_rights($old_power_arr, $new_power_arr);
		$is_change 			= isset($res_arr['is_change']) ? $res_arr['is_change'] : POWER_NOT_CHANGE; 	// 权限是否发生变化：0、没有；1、有
		$is_confSet_change = isset($res_arr['is_confSet_change']) ? $res_arr['is_confSet_change'] : CONF_POWER_NOT_CHANGE; // 会议权限是否发生变化：0、没有；1、有
		$new_power_arr 		= isset($res_arr['new_right']) ? $res_arr['new_right'] : array(); // 新的权限数组
		
		// 载入用户个性化权限模型
		$this->load->model('uc_user_config_model');
		$this->load->model('account_upload_task_model');
		
		// 如果是用户的个性化权限发生变化
		if($from_where == POWER_FROM_USER && $is_change == POWER_IS_CHANGE){
			
			// 更新用户的个性化权限
			$where_arr = array(
					'userID' => $user_id
			);
			$update_arr = array(
					'value' 	=> json_encode($new_power_arr),
					'oldValue' 	=> json_encode($old_power_arr)
			);
			$res = $this->uc_user_config_model->update_value($where_arr, $update_arr);
			if($res == false){
				form_json_msg(UPDATE_POWER_FAIL, '', $this->lang->line('update_power_fail'), array()); // 更新权限失败
			}
			
			// 如果会议权限发生变化，则保存线程
			if($is_confSet_change == CONF_POWER_IS_CHANGE){
				$boss_totle_template = $this->boss->getSellingProductTemplates($this->p_contract_id);
				//log_message('info', '$boss_totle_template='.var_export($boss_totle_template,true));
				$new_template_arr = $this->RightsLib->combine_boss_template_data($boss_totle_template, $new_power_arr);
				$update_value = array(
						'customer_code' => $this->p_customer_code,
						'site_id'		=> $this->p_site_id,
						'user_id' 		=> $user_id,
						'sellingProducts' => $new_template_arr
				);
				$this->account_upload_task_model->saveTask(USER_POWER_CHANGE_UPLOAD, json_encode($update_value));
			}
			
		}
		
		// 如果从上级组织或站点获得的组织权限发生变化：1、保存个性化权限；2、向BOSS做变更
		if(($from_where == POWER_FROM_ORG || $from_where == POWER_FROM_PARENT_ORG || $from_where == POWER_FROM_SITE) && $is_change == POWER_IS_CHANGE){
			
			// 保存个性化权限
			$insert_arr = array(
					'userID' 		=> $user_id,
					'value' 		=> json_encode($new_power_arr),
					'createTime' 	=> time()
			);
			$res = $this->uc_user_config_model->save_value($insert_arr);
			if($res == false){
				form_json_msg(UPDATE_POWER_FAIL, '', $this->lang->line('update_power_fail'), array()); // 更新权限失败
			}
				
			// 保存线程
			$boss_totle_template = $this->boss->getSellingProductTemplates($this->p_contract_id, $from_where_data);
			
			$new_template_arr = $this->RightsLib->combine_boss_template_data($boss_totle_template, $new_power_arr);
			$update_value = array(
					'customer_code' => $this->p_customer_code,
					'site_id'		=> $this->p_site_id,
					'user_id' 		=> $user_id,
					'sellingProducts' => $new_template_arr
			);
			$this->account_upload_task_model->saveTask(USER_POWER_CHANGE_UPLOAD, json_encode($update_value));
		}
		
		// 保存权限成功
		form_json_msg(COMMON_SUCCESS, '', $this->lang->line('success'), array()); 
	}

	/**
	 * @abstract 组织管理页面最右侧的标签
	 * @details
	 * -#下拉列表中的员工标签管理
	 */
	public function staffBatchImport() {
		$this->load->view('staff/staffBatchImport.php');
	}

	/**
	 * @abstract 组织管理页面最右侧的标签
	 * -#下拉列表中的批量修改员工
	 */
	public function batchModifyStaff() {
		$this->load->view('staff/batchModifyStaff.php');
	}
	
	/**
	 * @brief 修改用户信息
	 */
	public function updateUserInfo(){
		// 获取从表单提交的数据
		$post_json	= $this->input->post('post_json', true);
		$update_user_info = json_decode($post_json,true);
		log_message('info', __FUNCTION__." input->\n".var_export($update_user_info, true));
		//如果用户修改了账户，则需要在本地uc_user数据表中进行同步更新
		if($update_user_info['accountId'] != null){
			$data = array(
				'userID'		=> $update_user_info['user_id'],
				'accountId'		=> $update_user_info['accountId']
			);
			$this->load->model('uc_user_model', 'user_model');
			$ret = $this->user_model->updateUserAccountId($data);
			$update_message = $ret ? 'update accountID OK' : 'update accountID false';
			unset($update_user_info['accountId']);
			log_message('info', $update_message);
		}
		//如果是其他项进行修改则需调用UMS修改接口
		$update_arr = array();
		foreach ($update_user_info as $k=>$val){
			if($k == 'user_id'){
				$update_arr['id'] = $val;
				continue;
			}
			//如果是国家，则需要转义成国码
			if($k == 'countryCode'){
				include_once APPPATH . 'libraries/public/Country_code.php';
				$country_code_obj   = new Country_code();
				$country_arr    = $country_code_obj->get_country_code();
				foreach ($country_arr as $c_key => $c_val){
					if($val == $c_val['ch_name']){
						$update_arr['countryCode'] = substr($c_val['country_code'], 1);
						break;
					}
				}
			}else {
				$update_arr[$k] = $val;
			}
		}
		$this->load->library('UmsLib', '', 'ums');
		$result = $this->ums->updateUserInfo($update_arr);
		if($result){
			form_json_msg(0,'',$result);
		}else{
			form_json_msg(-1 , '', '');
		}
	}
	
}