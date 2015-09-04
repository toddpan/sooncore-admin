<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @abstract 	管理员管理控制器，主要对系统各类管理员进行管理。
 * @filesource 	manager.php
 * @author 		Bai Xue <xue.bai_2@quanshi.com>
 * @copyright 	Copyright (c) UC
 * @version 	v1.0
 */
class Manager extends Admin_Controller{
	
	public $allow_role; // 管理员参数
	
    /**
     * @abstract 构造方法
     */
    public function __construct() {
        parent::__construct();
        // 载入辅助函数
        $this->load->helper('my_httpcurl');
        // 载入管理员管理中文提示信息语言包
        $this->lang->load('admin', 'chinese');
        // 载入公共中文提示信息语言包
        $this->lang->load('common', 'chinese');
        
        // 添加管理员参数
        $this->allow_role = array(3, 4, 5); // 允许的管理员：3-员工管理员，4-账号管理员，5-生态管理员
    }
    
    /**
     * 显示管理员管理页面
     */
     public function listManagerPage() {
         $this->display('manage/manager.tpl');
     }
     
     /**
      * 搜索管理员并显示结果列表
      * @param int $offset 结果偏移量
      */
     public function search($offset = ''){
     	$role_id = $this->input->get_post('role_id', true);  // 角色id
     	$keyword = $this->input->get_post('keyword', true);  // 关键字
     	log_message('info', 'into method' . __FUNCTION__ . "\n input-> " . var_export(array('role_id' => $role_id, 'keyword' => $keyword), true));
     	
     	// 载入管理员角色模型
     	$this->load->model('uc_user_admin_role_model');
     	
     	// 载入分页类
     	$this->load->library('PageLib', '', 'pagelib');
     	
     	// 配置分页信息
     	$config['base_url'] = site_url('manager/search/');
     	$config['total_rows'] = $this->uc_user_admin_role_model->countManagerList($keyword, $role_id, $this->p_site_id);
     	$config['per_page'] = PER_PAGE;
     	$config['anchor_class'] = "";
     	$config['list_div_class'] ='.infor_page';
     	
     	// 初始化分页类
     	$this->pagelib->initialize($config);
     	
     	// 生成分页信息
     	$page_text = $this->pagelib->create_links();
     
     	// 获取管理员列表
     	$limit = $config['per_page'];
     	$rdata = $this->uc_user_admin_role_model->getManagerList($keyword, $role_id, $limit, $offset, $this->p_site_id);
     	
     	if(empty($rdata)){
     		$rdata = '没有搜到相关结果';
     	}
     
     	// 打印log
     	log_message('info', 'out method' . __FUNCTION__ . "\n output-> " . var_export($rdata, true));
     
     	// 将数据传递到页面
     	$this->assign('page_text', $page_text);
     	$this->assign('data', $rdata);
     	$this->display('manage/manager_type.tpl');
     }
     
     /**
      * 弹框显示填写新增管理员信息页面
      */
     public function addManagerPage() {
     	$this->load->view('manage/addManagerDialog.php');
     }
    
    /**
     * 显示单个管理员的个人信息、权限以及管理维度等详细情况的页面
     */
    public function managerInfoPage() {
    	$user_id = $this->input->post('user_id', true); // user_id
    	$id 	 = $this->input->post('id', true); 		// 管理员角色表中的id
    	log_message('info', 'into method ' . __FUNCTION__ . " input -> \n" . var_export(array('user_id' => $user_id), true));
    	
    	// 验证user_id
    	if(!is_numeric($user_id) || $user_id < 1){
    		response_json(COMMON_PARAM_ERROR, $this->lang->line('admin_param_error')); // 参数错误
    	}
    	
    	// 根据user_id获得当前管理员的个人信息
     	$user_info = $this->getAdminInfo($user_id);
     	$system_must_tag_arr = isset($user_info['system_must_tag_arr']) ? $user_info['system_must_tag_arr'] : array(); // 必选标签
     	$other_tag_arr = isset($user_info['other_tag']) ? $user_info['other_tag'] : array(); // 可选标签和自定义标签
    	
     	// 根据user_id获得当前管理员的管理维度
     	$admin_role_arr = $this->getAdminRole($user_id);
     	//var_dump($admin_role_arr);
     	
     	// 获得管理员角色
     	$this->load->model('uc_user_admin_role_model');
     	$role_arr = $this->uc_user_admin_role_model->getAdminRoleById($id);
     	$role_name = isset($role_arr['role']) ? $role_arr['role'] : '';
     	
     	$this->setFunctions();     	
     	
    	// 将数据传递到页面上
    	$this->assign('p_user_id', $this->p_user_id);
    	$this->assign('id', $id);
   		$this->assign('must_tag', $system_must_tag_arr);
   		$this->assign('other_tag_arr', $other_tag_arr);
     	$this->assign('admin_role_arr', $admin_role_arr);
     	$this->assign('role_name', $role_name);
     	
    	$this->display('manage/managerInfo.tpl');
    }
    
	private function setFunctions(){
		$roleFunctions = $this->setFunctionsByRole();
		$customFunctions = $this->setFunctionsBySite();
		
		$functions = array_merge($customFunctions, $roleFunctions);
		
		foreach ($customFunctions as $key=>$value){
			$functions[$key] = $functions[$key] && $value;
		}
		
		$this->assign('functions', $functions);
	}
	
	private function setFunctionsBySite(){
		$functions = array();
		
		$functions['changePassword'] = $this->siteConfig['siteType'] == 0;
		$functions['employeeEdit'] = $this->siteConfig['importType'] != 2;
		
		return $functions;
	}
	
	private function setFunctionsByRole(){
		$functions = array();
		
		$functions['changePassword'] = $this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER || $this->p_role_id == EMPPLOYEE_MANAGER || $this->p_role_id == ACCOUNT_MANAGER;
		$functions['employeeEdit'] = $this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER || $this->p_role_id == EMPPLOYEE_MANAGER;

		return $functions;
	}
	
	
	
    
    
    
    
    /**
     * 根据user_id获取当前管理员的个人信息
     * @param int $user_id
     */
    public function getAdminInfo($user_id) {
    	// 载入员工类库
    	$this->load->library('StaffLib', '', 'StaffLib');
    	// 载入组织类库
    	$this->load->library('OrganizeLib','','OrganizeLib');
    	// 载入账户模型
    	$this->load->model('uc_account_model');
    	// 载入地址模型
    	$this->load->model('uc_area_model');
    	
    	// -----------------------------------------------------获得当前用户标签及标签值--------------------------------------------- 
    	$in_arr = array(
    			'user_id'  => $user_id,			// 用户id，如果没有，则写0
    			'tag_type' => 4,				// 标签页面类型
    			'site_id'  => $this->p_site_id,	// 当前站点id
    	);
    	
    	$user_auto_arr 			  = array();
    	$user_tag_arr 			  = $this->StaffLib->get_user_tag_arr($in_arr, $user_auto_arr);
     	$user_info_arr            = arr_unbound_value($user_tag_arr, 'user_info_arr', 1, array());			// 用户详情信息
    	$system_must_tag_arr      = arr_unbound_value($user_tag_arr, 'system_must_tag_arr', 1, array());	// 系统标签及其值数组
    	$seled_not_must_tag_arr   = arr_unbound_value($user_tag_arr, 'seled_not_must_tag_arr', 1, array());	// 可选标签及其值数组
    	$user_defined_tag_arr     = arr_unbound_value($user_tag_arr, 'user_defined_tag_arr', 1, array());	// 自定义标签及其值数组
    	$user_mobile = arr_unbound_value($user_info_arr, 'mobileNumber', 2, '');// 手机号
    	
    	// 初始化必选标签结果数组
    	$system_must_tags_arr = array();
    	// 遍历必选标签数组，将其转化为key => value的格式
    	foreach($system_must_tag_arr as $must_tag){
//     		if($must_tag['field'] == 'sex'){
//     			//echo $must_tag['tag_value'];
//     			if($must_tag['tag_value'] == 1){
//     				$tag_value = '男';
//     			}else if($must_tag['tag_value'] == 2){
//     				$tag_value = '女';
//     			}else{
//     				$tag_value = '未设置';
//     			}

//     			$system_must_tags_arr[$must_tag['field']] = $tag_value;
//     		}else{
    			$system_must_tags_arr[$must_tag['field']] = $must_tag['tag_value'];
//    		}
    	}
    	
    	// -----------------------------------------------------获取电话号码和国码---------------------------------------------------
    	// 载入国码资源类
    	include_once APPPATH . 'libraries/public/Country_code.php';
    	// 创建国码资源对象
    	$country_code_obj   = new Country_code();
     	// 获得当前管理员的手机号及其国码信息
     	$country_mobile_arr = $country_code_obj->get_mobile_arr($user_mobile);
//     	// 获得当前管理员对应手机号的国码
     	$country_code   = arr_unbound_value($country_mobile_arr, 'code', 2, '+86');
//     	// 获得当前管理员的手机号
//     	$country_mobile = arr_unbound_value($country_mobile_arr, 'mobile', 2, '');
    	// 获得所有国码信息
   		$country_arr    = $country_code_obj->get_country_code($country_code);
    	
    	$system_must_tags_arr['user_id']                  = $user_id;		// 当前用户id
    	$system_must_tags_arr['country_code']             = "+86";	// 当前国码
    	$system_must_tags_arr['country_arr'] 			  = $country_arr;	// 国码信息
    	$system_must_tags_arr['country_mobile']           = $user_mobile;// 手机号
    	
    	// -----------------------------------------------------获取部门信息--------------------------------------------------------
    	// 客户编码
    	$customer_code = $this->p_customer_code;
    	// 首级及下一级组织数组
    	$first_next_org_arr = $this->OrganizeLib->get_first_next_org_arr($customer_code, '1,3,5');
    	//首级及下一级组织json串
    	$in_arr = array(
    			'is_first' => 1 // 是否第一级0不是1是
    	);
    	$org_arr = $this->OrganizeLib->InitzTree_arr($first_next_org_arr, 1, $in_arr);
    	//print_r($org_arr);
    	$org_json = '[]';
    	if(is_array($org_arr)){// 如果是数组
    		$org_json = json_encode($org_arr);
    	}
    	$system_must_tags_arr['org_list_json']  = $org_json;
    	// 当前组织id
    	$user_organizationId = arr_unbound_value ( $user_info_arr, 'organizationId', 2, '' );
    	$ns_org_arr = $this->OrganizeLib->get_orgup_arr ( $user_organizationId, array () );
    	// 根据部门串，获得部门串数组
    	//$system_must_tags_arr['org_json'] = $ns_org_arr;
    	$dep_id = '';
    	$dep_val = '';
    	foreach($ns_org_arr as $dep) {
    			$dep_id .= $dep['id'].'-';
    			$dep_val .= $dep['value'].'-';
    	}
    	$department_arr = array('id' => rtrim($dep_id, '-'), 'value' => rtrim($dep_val, '-'));
    	$system_must_tags_arr['org_json'] = $department_arr;
    	//print_r($system_must_tags_arr['org_json']);

    	
    	// -----------------------------------------------------获取账户信息--------------------------------------------------------
    	$re_arr = $this->uc_account_model->get_account($customer_code);
    	// 获取account_name
    	$account_name = arr_unbound_value($re_arr, 'account_name', 2, '');
    	$system_must_tags_arr['account_name']   = $account_name;

    	
    	// -----------------------------------------------------获取办公地址--------------------------------------------------------
//     	$site_id = $this->p_site_id;
//     	$re_addrsee_arr = $this->uc_area_model->get_area($customerCode, $site_id);
//     	$address_str = '';
//     	foreach ($re_addrsee_arr as $address_arr){
//     		$address_str .= $address_arr;
//     	}
//     	$system_must_tags_arr['address'] = $address_str;
    	
    	// -----------------------------------------------------组装可选标签和自定义标签--------------------------------------------- 
    	$other_tag_arr = array();
    	if(!isemptyArray($seled_not_must_tag_arr)){
    		//print_r($seled_not_must_tag_arr);
    		foreach ($seled_not_must_tag_arr as $not_must_tag) {
    			$other_tag_arr[] = array(
    				'tag_name' 	=> $not_must_tag['tag_name'],
    				'tag_key' 	=> $not_must_tag['field'],
    				'tag_value' => $not_must_tag['tag_value']
    			);
    		}
    	}
    	if(!isemptyArray($user_defined_tag_arr)){
    		foreach ($user_defined_tag_arr as $user_define_tag) {
    			//print_r($user_defined_tag_arr);
    			$other_tag_arr[] = array(
    				'tag_name' 	=> $user_define_tag['tag_name'],
    				//'tag_key' 	=> $user_define_tag['field'],
    				'tag_value' => $user_define_tag['tag_value']
    			);
    		}
    	}
    	
    	// -----------------------------------------------------组装所有标签信息数组-------------------------------------------------
    	$tag_arr = array(
    		'system_must_tag_arr' => $system_must_tags_arr,
    		'other_tag' => $other_tag_arr
    	);
		
		log_message ( 'info', __FUNCTION__ . " output->\n" . var_export ( $tag_arr, true ) );
		return $tag_arr;
    }
    
    /**
     * 根据用户id获得当前管理员的个人信息
     * 
     * @param int $user_id 用户id
     * 
     * @return array $tag_arr 个人信息数组
     */
    public function get_admin_info($user_id) {
    	log_message('info', 'Into method get_admin_info input --> $user_id = ' . $user_id);
    	
    	// 初始化结果数组：个人信息数组
    	$tag_arr = array();
    	
    	return $tag_arr;
    }
    
    /**
     * 根据user_id获得当前管理员的管理维度
     * @param int $user_id
     */
    public function getAdminRole($user_id) {
    	log_message('info', 'into method' . __FUNCTION__ . "\n input -->" . var_export(array('user_id' => $user_id), true));
    	
    	// 载入管理员管理维度模型
    	$this->load->model('uc_user_resource_model');
    	$this->load->library('UmsLib', '', 'ums');
    	
    	// 调用模型方法进行查询
    	$re_data = $this->uc_user_resource_model->getUserResourceByUserId($user_id);
    	
    	// 组装数据
    	$org_name = '';
    	$new_data = array();
    	if(!isemptyArray($re_data)){
    		foreach($re_data as $admin_role){
    			if($admin_role['scope_level_1'] == 'department'){
    				$org_id_arr = explode(',', $admin_role['scope_level_1_value']);
    				//print_r($org_id_arr);
    				foreach ($org_id_arr as $org_id){
    					//$org_id = get_org_id($org_id);
    					//echo $org_id;
    					$org_info = $this->ums->getOrganizationById($org_id);
    					if(!empty($org_info)){
    						$org_name = $org_info['name'] . ',';
    					}
    				}
    				$admin_role['org_name'] = rtrim($org_name, ',');
    				$org_name = '';
    			}
    		
    			if($admin_role['scope_level_2'] == 'department'){
    				//$admin_role['scope_level_2_value'] = get_org_id($admin_role['scope_level_2_value']);
    				$org_info = $this->ums->getOrganizationById($admin_role['scope_level_2_value']);
    				if(!empty($org_info)){
    					$org_name = $org_info['name'];
    				}
    				$admin_role['org_name'] = $org_name;
    				$org_name = '';
    			}
    		
    			$new_data[] = $admin_role;
    		}
    	}
    	
    	log_message('info', 'out method' . __FUNCTION__ . "\n output -->" . var_export($new_data, true));
    	
    	//print_r($new_data);die;
    	
    	// 返回结果
    	return $new_data;
    }
    
    /**
     * 保存当前管理员的个人信息
     */
    public function saveAdminInfo(){
    	$user_id        = $this->input->post('user_id', true);
    	$user_json      = $this->input->post('user_json', true);
    	$user_role_id   = $this->input->post('user_role_id', true);
    	log_message('info', __FUNCTION__." input->\n".var_export(array('user_id' => $user_id, 'user_json' => $user_json, 'user_role_id' => $user_role_id), true));
    	
    	// 验证user_id
    	if(!is_numeric($user_id) || $user_id < 1){
    		return_json(COMMON_PARAM_ERROR, $this->lang->line('admin_param_error'), array());
    	}
    	
    	// 判断$user_json是否为空
    	if(is_empty($user_json)){
    		return_json(COMMON_PARAM_ERROR, $this->lang->line('admin_param_error'), array());
    	}
    	
    	// 将$user_json转为数组
    	$user_arr = json_decode($user_json, true);
    	
    	// 判断数组是否为空
    	if(isemptyArray($user_arr)){
    		return_json(COMMON_PARAM_ERROR, $this->lang->line('admin_param_error'), array());
    	}
    	
    	$this->load->library('StaffLib', '', 'StaffLib');
    	
    	$other_arr = array(
    			'user_id'  => $user_id,		// 0为新加,具体数字为被修改的userid
    			'tag_type' => 5,	// 标签页面类型:4 帐号新加页,// 5 帐号修改页
    	);
    	$ns_sys_arr                = $this->p_sys_arr;
    	$ns_sys_arr['parentId']    = $this->p_org_id;	// 当前站点的组织机构id
    	$ns_sys_arr['user_type']   = $user_role_id;		// 帐号类型0普通用户1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员[各种管理员新加时，必填]7生态企业普通用户
    	$ns_sys_arr['isLDAP']      = $this->p_is_ldap;	// 帐号导入类型[各种管理员新加时，必填]
    	$ns_sys_arr['session_id']  = $this->p_session_id; // sessionid
    	
    	// 调用保存用户方法，对用户信息进行保存,成功true 失败 false 失败的字符串
    	$re_boolean = $this->StaffLib->save_user($user_arr, $other_arr, $ns_sys_arr);
    	
    	log_message('info', __FUNCTION__." output->\n".var_export(array('re_boolean' => $re_boolean), true));
    	
    	if($re_boolean == false){
    		return_json(COMMON_FAILURE, $this->lang->line('save_admin_error') . $re_boolean, array());
    	}else{
    		return_json(COMMON_SUCCESS, $this->lang->line('save_admin_suc'), array());
    	}
    }

    /**
     * 弹框提醒删除管理员
     */
    public function delManagerPage() {
        $this->load->view('manage/delManagerPage.php');
    }
    
    /**
     * 单个或批量删除管理员（注意：系统管理员不能被删除）
     */
    public function delManager() {
        // 获取uc_user_admin_role表中主键id（字符串，逗号分隔）
        $ids = $this->input->post('ids', true);
        log_message('info', 'into method ' . __FUNCTION__ . " input -> \n" . var_export(array('ids' => $ids), true));
        
        // 判断主键id字符串是否为空
        if(is_empty($ids)){
        	response_json(COMMON_PARAM_ERROR, $this->lang->line('admin_param_error')); // 参数错误
        }
        
        // 将主键id字符串转化为数组
        $_ids_arr = explode(',', $ids);
        
        // 载入管理员角色模型
        $this->load->model('uc_user_admin_role_model', 'user_admin_role');
        
        $ids_arr  = array();
        // 遍历主键id数组
        foreach($_ids_arr as $id){
        	// 判断每个主键id是否为数字
            if(!is_numeric($id)){
                return_json(COMMON_PARAM_ERROR, $this->lang->line('admin_param_error')); // 参数错误
            }
            
            // 验证该用户角色是否为系统管理员
            $role_id = $this->user_admin_role->getRoleById($id);
            if($role_id == SYSTEM_MANAGER){
                return_json(ADMIN_DEL_FAIL, $this->lang->line('del_sys_admin_param_error')); // 系统管理员不能被删除
            }else if($role_id == 0){
                return_json(COMMON_PARAM_ERROR, $this->lang->line('admin_param_error')); // 参数错误
            }
            
            $ids_arr[] = intval($id);
        }
        
        // 删除管理员（直接删除记录）
        $ret = $this->user_admin_role->delManagers($ids_arr);
        
        log_message ( 'info', __FUNCTION__ . " output->\n" . var_export ( array('ret' => $ret), true ) );
        
        // 删除失败
        if(!$ret){
            return_json(ADMIN_DEL_FAIL, $this->lang->line('del_admin_error')); 
        }
        // 删除成功
        return_json(COMMON_SUCCESS, $this->lang->line('del_admin_suc')); 
    }
    

    
    /**
     * 新增管理员
     * 从页面获取到的参数数据格式如下：
     * array(
     *      'user_id'=>1000,
     *      'role_id'=>1,
     *      'w1'=>array('key'=>'region','value'=>'r1,r2,r3'),
     *      'w2'=>array('key'=>'department',value=>'d1')
     * )
     */
    public function addManager() {
        // 获取参数（json串格式）
        $manager_info_json = $this->input->post('manager_info');
        //var_dump($manager_info_json);die;
        log_message('info', 'into method' . __FUNCTION__ . "\n input -->" . var_export(array('manager_info' => $manager_info_json), true));
        
        // 将参数json串转换成数组
        $manager_infos = array();
        $manager_info = json_decode($manager_info_json, true);
        //print_r($manager_info);die;
        $loginName = $manager_info['login_name'];
        $user_id = intval($manager_info['user_id']);
        $role_id = intval($manager_info['role_id']);
        log_message('info', var_export($manager_info, true));
        
        // 当是手动输入的用户名时，要根据loginName判断这个用户是否存在
        if(empty($user_id)){
	        $loginName = $manager_info['login_name'];
	        $this->load->library('UmsLib', '', 'ums');
	        $user_info = $this->ums->getUserByLoginName($loginName);
	        if($user_info == false){
	        	form_json_msg(COMMON_PARAM_ERROR, 'user_id', $this->lang->line('username_is_not_exist')); // 该用户不存在
	        }
	        	
	        $user_id = $user_info['id'];
	        $manager_info['user_id'] = $user_id;
        }
        
         $manager_infos[] = $manager_info;
        
        // 参数检查
        list($flag) = $this->checkManagerInfo($manager_infos);
        if(!$flag){
            form_json_msg(COMMON_PARAM_ERROR, '', $this->lang->line('admin_param_error')); // 参数错误
        }
        
        // 载入管理员角色模型
        $this->load->model('uc_user_admin_role_model', 'admin_role');
        
        // 插入数据
        $insert_data_admin_part = array(
            'siteID'        =>$this->p_site_id,
            'orgID'         =>$this->p_org_id,
            'isLDAP'        =>$this->p_is_ldap,
            'super_admin_id'=>($role_id==ECOLOGY_MANAGER ? $this->p_user_id : 0),
            'type'          =>($role_id==ECOLOGY_MANAGER ? 3 : 0),
            'createTime'     =>time()
        );
        list($flag, $user_id, $id) = $this->admin_role->addManager($manager_infos, $insert_data_admin_part);
        
        log_message ( 'info', __FUNCTION__ . " output->\n" . var_export ( array('ret' => $flag), true ) );
        
        if(!$flag){
            form_json_msg(ADMIN_ADD_FAIL, '', $this->lang->line('add_admin_error'), array()); // 添加管理员失败
        }
        form_json_msg(COMMON_SUCCESS, '', $this->lang->line('add_admin_suc'), array('user_id' => $user_id, 'id' => $id));
    }
    
    /**
     * 显示确认邮箱页面
     */
    public function show_confirm_email_page() {
    	$user_id = $this->input->get_post('user_id', true);	// 用户id
    	$id 	 = $this->input->get_post('id', true);		// 管理员id
    	log_message('info', 'Into method show_confirm_email_page input -> user_id = ' . $user_id);
    	
    	// 调用UMS接口获取当前管理员的Email地址
    	$this->load->library('UmsLib', '', 'ums');
    	$user_info 	= $this->ums->getUserById($user_id);
    	$email 		= isset($user_info['email']) ? $user_info['email'] : '';
    	
    	// 加载视图页面并传递数据
    	$this->assign('user_id', $user_id);
    	$this->assign('id', $id);
    	$this->assign('email', $email);
    	$this->display('manage/confirm_email.tpl');
    }
    
    /**
     * 发送指定管理员的通知邮件
     */
    public function send_mail(){
    	$user_id = $this->input->post('user_id', true);
    	$email 	 = $this->input->post('email', true);
    	log_message('info', 'Into method send_mail input -> user_id = ' . $user_id .',email = ' . $email);
    	
    	// 调用UMS接口获得用户相关信息
    	$this->load->library('UmsLib', '', 'ums');
    	$user_info 	= $this->ums->getUserById($user_id);
    	$user_name 	= isset($user_info['displayName']) ? $user_info['displayName'] : '';
    	$loginName 	= isset($user_info['loginName']) ? $user_info['loginName'] : '';
    	
    	//TODO 获得公司简称
    	$cor_name = '全时';
    	
    	// 套用模板发送通知邮件
    	$this->load->library('MssLib', '', 'mss');
    	$mail_template_set = array(
    			'user_name' 	=> $user_name, 	// 姓名
    			'login_name' 	=> $loginName, 	// 用户名
    			'password' 		=> '',  		// 密码
    			'cor_name' 		=> $cor_name,  	// 公司简称
    			'email' 		=> $email, 		// 收件人邮箱
    	);
    	$mail_type = MANAGER_SET_MAIL;
    	$result = $this->mss->save_mail($mail_template_set, $mail_type);
    	log_message('info', 'the result of send_mail is ' . $result);
    }
    
     /**
      * 修改管理员维度
      */
    public function modifyManager(){
        // 获取参数
        $manager_infos_json = $this->input->get_post('manager_infos', true);
        log_message('info', 'into methodmodifyManager' . "\n input -->manager_info' =>". $manager_infos_json);
        
        // 将参数json串转换成数组
        $manager_infos      = json_decode($manager_infos_json, true);
        
        // 检查参数
        if(is_null($manager_infos)){
            response_json(COMMON_PARAM_ERROR, $this->lang->line('admin_param_error')); // 参数错误
        }
        $this->load->model('uc_user_admin_role_model', 'admin_role');
        
        foreach($manager_infos as $manager_info){
            if( !isset($manager_info['id']) || !is_numeric($manager_info['id'])){
                response_json(COMMON_PARAM_ERROR, $this->lang->line('admin_param_error')); // 参数错误
            }
            //检查该id是否关联于user_id
            $ret= $this->admin_role->getRoleInfoById($manager_info['id']);
            if ( !$ret || $ret['user_id']!= $manager_info['user_id'] ){
                response_json(COMMON_PARAM_ERROR, $this->lang->line('admin_param_error')); // 参数错误
            }
        }
        list($flag) = $this->checkManagerInfo($manager_infos);
        if(!$flag){
            form_json_msg(COMMON_PARAM_ERROR, '', $this->lang->line('admin_param_error')); 
        }
        
        //修改管理员信息
        $ret_modify = $this->admin_role->modifyManager($manager_infos);
        
        log_message ( 'info', __FUNCTION__ . " output->\n" . var_export(array('ret_modify' => $ret_modify), true));
        
        if(!$ret_modify){
            form_json_msg(COMMON_FAILURE, '', $this->lang->line('save_admin_error')); // 添加管理员失败
        }
        form_json_msg(COMMON_SUCCESS, '', $this->lang->line('save_admin_suc'));
    }
    
    /**
     * 检查管理员信息，管理员添加和修改的公用方法
     * @param array $manager_info 客户端返回的管理员角色信息
     */
    protected function checkManagerInfo($manager_info_arr){
    	log_message('info', 'into method' . __FUNCTION__ . "\n input -->" . var_export(array('manager_info' => $manager_info_arr), true));
    	
    	// 载入组织类库
        $this->load->library('OrganizeLib', '', 'OrganizeLib');
        // 载入用户模型
        $this->load->model('uc_user_model', 'user');
        
        // 遍历管理员角色信息数组
        foreach($manager_info_arr as $manager_info){
        	//log_message('info', '11100');
            // 为了统一数据格式，如果role_id为生态管理员，则为其加w1、w2两个空维度
            if( $manager_info['role_id'] == ECOLOGY_MANAGER ){
            	$manager_info['w1'] = array('key'=>'','value'=>'');
            	$manager_info['w2'] = array('key'=>'','value'=>'');
            }
            
            // 参数格式检查
            list($allow) = $this->OrganizeLib->checkManagerData($manager_info);
            if(!$allow){
                return array(false);
            }
            $user_id = intval($manager_info['user_id']);
            $role_id = intval($manager_info['role_id']);
            $w1      = $manager_info['w1'];
            $w2      = $manager_info['w2'];
            
            //检查user_id是否合法
//             $user_info = $this->user->getUserInfo($user_id);
//             if(((isset($user_info['siteId']) ?$user_info['siteId'] : '') != $this->p_site_id) || ((isset($user_info['customerCode']) ? $user_info['customerCode'] : '') != $this->p_customer_code)){
//             	log_message('error', 'the param 1'.$user_id.' is not illegal!');
//             	return array(false);
//             }
            //检查角色role_id是否合法
            if(!in_array($role_id, $this->allow_role)){
            	log_message('error', 'the param 2'.$role_id.' is not illegal!');
                return array(false);
            }

            //检查维度值是否正确,如果是生态管理员，则不检查
//             if( ($manager_info['role_id'] != ECOLOGY_MANAGER) && (!$this->OrganizeLib->checkW($w1) || !$this->OrganizeLib->checkW($w2)) ){
//             	log_message('error', 'the param w1 or w2 is not illegal!');
//                 return array(false);
//             }
        }

         return array(true);
    }
    
    /**
     * Ajax获取成本中心分组信息
     */
    public function getCostCenter(){
        $this->load->model('uc_site_costcenter_model', 'cost_center');
        $ret_data = $this->cost_center->getGroups($this->p_site_id, $this->p_org_id);
        return_json(COMMON_SUCCESS, '', array('ret_data'=>$ret_data));
    }
    
    /**
     * Ajax获取地区列表
     */
    public function getRegion(){
    	$this->load->model('uc_area_model');
    	$address_info_arr = $this->uc_area_model->get_area_by_customercode($this->p_customer_code);
		$city_arr = array();
		$city = '';
		if(!isemptyArray($address_info_arr)){
			$count = count($address_info_arr);
			foreach($address_info_arr as $address_info){
				$city = isset($address_info['area']) ? $address_info['area'] : '';
				if(!empty($city)){
					$city_arr[] = $city;
				}
			}
		}
		
// 		if(isemptyArray($city_arr)){
// 			$city_arr[] = 0;
// 		}
		return_json(COMMON_SUCCESS, '', array('ret_data'=>$city_arr));
    }
       

    
    public function get_is_ldap() {
    	return_json(COMMON_SUCCESS, '', array('is_ldap'=>$this->p_is_ldap));
    }
    
    /**
     * 弹窗提醒是否确认重置密码
     * @param int $user_id
     * @param string $user_name
     */
    public function alert_reset_pwd($user_id){
    	// 根据userId找到用户姓名
    	$this->load->library('UmsLib', '', 'ums');
    	$user_info = $this->ums->getUserById($user_id);
    	$user_name = isset($user_info['displayName']) ? $user_info['displayName'] : '';
    	
    	$this->assign('user_id', $user_id);
    	$this->assign('user_name', $user_name);
    	$this->display('manage/alert_reset_pwd.tpl');
    }
    
    /**
     * 重置密码
     */
    public function reset_pwd() {
    	$user_id = $this->input->post('user_id', true);
    	log_message('info', 'Into method reset_pwd input ----> user_id=' . $user_id );
    	
    	// 调用UMS接口判断该用户是否存在
    	$this->load->library('UmsLib', '', 'ums');
    	$ums_user_info = $this->ums->getUserById($user_id);
    	if(empty($ums_user_info)){
    		return_json(USER_NOT_EXIST, $this->lang->line('user_not_exist'), array()); // 用户不存在
    	}
    	
    	// 调用UMS接口重置用户密码
    	$password = '11111111'; //TODO 暂时将密码重置为8个1，将来根据需求而定。
    	$res = $this->ums->resetUserPassword($user_id, $password);
		if(!$res){
			return_json(RESET_PWD_FAIL, $this->lang->line('reset_pwd_fail'), array()); // 重置密码失败
		}
		
		$email 		= isset($ums_user_info['email']) ? $ums_user_info['email'] : '';
		$phone 		= isset($ums_user_info['mobileNumber']) ? $ums_user_info['mobileNumber'] : '';
		$user_name	= isset($ums_user_info['displayName']) ? $ums_user_info['displayName'] : '';
		
		$this->load->model('uc_site_config_model');
		$site_config = $this->uc_site_config_model->getAllSiteConfig($this->p_site_id);
		
		$accountNotifyEmail = isset($site_config['accountNotifyEmail']) ? $site_config['accountNotifyEmail'] : 0;
		$accountNotifySMS = isset($site_config['accountNotifySMS']) ? $site_config['accountNotifySMS'] : 0;
		
		if($accountNotifyEmail == 1){
			if(empty($email)){
				log_message('error', '$user_id='.$user_id.' does not have email.');
			}else{
				// 根据user_id获得site_id
				$this->load->model('uc_user_model');
				$user_info = $this->uc_user_model->getUserInfo($user_id);
				$site_id = isset($user_info['siteId']) ? $user_info['siteId'] : '';
				
				// 根据站点id获得企业简称
				$this->load->model('uc_site_model');
				$site_info = $this->uc_site_model->getInfosBySiteId($site_id);
				$cor_name = isset($site_info['corName']) ? $site_info['corName'] : '';
				if(empty($cor_name)){
					$this->load->model('uc_customer_model');
					$where_arr = array(
							'siteId' => $site_id
					);
					$customer_info = $this->uc_customer_model->getContractid($where_arr);
					$cor_name = isset($customer_info['name']) ? $customer_info['name'] : '';
				}
				
				$this->load->library('MssLib', '', 'mss');
				$mail_template_set = array(
						'user_name' 	=> $user_name,
						'password' 		=> $password,
						'cor_name' 		=> $cor_name,  	// 企业简称
						'email'			=> $email  		// 收件人邮箱
				);
				$this->mss->save_mail($mail_template_set, RESET_PWD_SUC_MAIL);
			}
		}
		
		if($accountNotifySMS == 1){
			// 载入短信模板
			$this->lang->load('msg_tpl', 'chinese');
	
			// 判断手机号是否为空
			if(empty($phone)){
				log_message('error', '$user_id='.$user_id.' does not have phone.');
			}else{
				// 调用UCCServer接口发短信
				$this->load->library('UccLib', '', 'ucc');
				$content = sprintf($this->lang->line('msg_tpl_reset_pwd'), $password);
				$this->ucc->sendMobileMsg($user_id, $content, $phone);
			}
		}
		
// 		if(empty($email)){ // 发短信
// 			// 载入短信模板
// 			$this->lang->load('msg_tpl', 'chinese');
			
// 			// 判断手机号是否为空
// 			if(empty($phone)){
// 				log_message('error', '$user_id='.$user_id.' does not have phone.');
// 			}else{
// 				// 调用UCCServer接口发短信
// 				$this->load->library('UccLib', '', 'ucc');
// 				$content = sprintf($this->lang->line('msg_tpl_reset_pwd'), $password);
// 				$this->ucc->sendMobileMsg($user_id, $content, $phone);
// 			}
			
// 		}else{ // 发邮件
			
// 			// 根据user_id获得site_id
// 			$this->load->model('uc_user_model');
// 			$user_info = $this->uc_user_model->getUserInfo($user_id);
// 			$site_id = isset($user_info['siteId']) ? $user_info['siteId'] : '';
			
// 			// 根据站点id获得企业简称
// 			$this->load->model('uc_site_model');
// 			$site_info = $this->uc_site_model->getInfosBySiteId($site_id);
// 			$cor_name = isset($site_info['corName']) ? $site_info['corName'] : '';
// 			if(empty($cor_name)){
// 				$this->load->model('uc_customer_model');
// 				$where_arr = array(
// 					'siteId' => $site_id
// 				);
// 				$customer_info = $this->uc_customer_model->getContractid($where_arr);
// 				$cor_name = isset($customer_info['name']) ? $customer_info['name'] : '';
// 			}
			
// 			$this->load->library('MssLib', '', 'mss');
// 			$mail_template_set = array(
// 						'user_name' 	=> $user_name,
// 						'password' 		=> $password,
// 						'cor_name' 		=> $cor_name,  	// 企业简称
// 						'email'			=> $email  		// 收件人邮箱
// 			);
// 			$this->mss->save_mail($mail_template_set, RESET_PWD_SUC_MAIL);
// 		}
		
		return_json(COMMON_SUCCESS, $this->lang->line('success'), array('p_user_id' => $this->p_user_id));	// 重置密码成功
    }
    
    /**
     * 显示重置密码成功页面
     */
    public function reset_pwd_suc() {
    	$this->assign('site_url', site_url('login/logout'));
    	$this->display('manage/resetpwdsuc.tpl');
    }
}