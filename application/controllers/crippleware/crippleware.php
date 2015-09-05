<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @abstract 	试用账号控制器，负责试用账号的相关操作
 * @author 		Bai Xue <xue.bai_2@quanshi.com>
 * @copyright 	Copyright (c) UC
 * @version 	v1.0
 */
class Crippleware extends Web_Controller {
	
	/**
	 * @abstract 构造方法
	 */
	public function __construct() {
		parent::__construct();
		$this->lang->load('crippleware', 'chinese');		
	}
	
	/**
	 * @abstract 显示激活手机号页面
	 */
	public function activation_phone_page() {
		$user_id = $this->input->get('user_id', true); // userId
		log_message('info', 'Into method ' . __FUNCTION__ . " \n input -->" . var_export(array('user_id' => $user_id), true));
		
		// 将userId从base64加密中解密出来
		//$user_id = base64_decode($user_id);
		
		// 验证userId
		$this->load->library('UmsLib', '', 'ums');
		$user_info = $this->ums->getUserById($user_id);
		if($user_info == false){
			log_message('error', 'The user whose user_id is '. $user_id . ' is not exist.');
			$this->assign('error_message', $this->lang->line('user_not_exist'));	// 该用户不存在
			$this->display('crippleware/err_msg.tpl');
		}
		
		// 判断已经发过验证码的次数
		$this->load->model('uc_phone_varification_code_model');
		$count = $this->uc_phone_varification_code_model->count($user_id);
		if($count > 3){
			log_message('error', 'The user whose user_id is '. $user_id . ' is not exist.');
			$this->assign('error_message', $this->lang->line('code_gt_3'));	// 发送验证码已超过三次
			$this->display('crippleware/err_msg.tpl');
			return ;
		}
		
		// 判断手机号是否已经被激活
		$where_arr = array(
			'user_id' 	=> $user_id,
			'state' 	=> CODE_IS_VALID//验证码状态：0、未验证；1、已验证；2、已过期
		);
		$is_act_arr = $this->uc_phone_varification_code_model->getCode($where_arr);
		if(!isemptyArray($is_act_arr)){
			log_message('error', 'The user whose user_id is '. $user_id . ' is not exist.');
			$this->assign('error_message', $this->lang->line('code_is_valid'));	// 该手机号已被验证
			$this->display('crippleware/err_msg.tpl');
		}
		
		// 获得手机号码
		$mobile_number = isset($user_info['mobileNumber']) ? $user_info['mobileNumber'] : '';
		
		// 发送短信
		$this->send_phone_code($user_id, $mobile_number);
		
		// 显示输入手机验证码页面
		$this->assign('user_id', $user_id);
		$this->assign('mobileNumber', $mobile_number);
		$this->display('crippleware/valid_phone.tpl');
	}
	
	/**
	 * @abstract 发送手机短信验证码
	 * @param string $user_id
	 * @param string $mobile_number 手机号码
	 */
	public function send_phone_code($user_id, $mobile_number) {
		log_message('info', 'Into method' . __FUNCTION__ . "\n input -->" . var_export(array('user_id' => $user_id, 'phone' => $mobile_number), true));
	
		$content = '';
		for($i = 0; $i < 6; $i++){
			$content .= rand(0, 9);
		}
	
		// 将手机验证码保存到数据库中
		$this->load->model('uc_phone_varification_code_model');
		$code_info = array(
				'user_id' 			=> $user_id,
				'varification_code' => $content,
				'create_time' 		=> time(),
				'state' 			=> CODE_NOT_VALID // 0、未验证；1、已验证；2、已过期
		);
		$result = $this->uc_phone_varification_code_model->saveCode($code_info);
	
		// 调用战役接口发送短信
		$this->load->library('UccLib', '', 'ucc');
		$res = $this->ucc->sendMobileMsg($user_id, $content, $mobile_number);
	
		log_message('info', 'Out method' . __FUNCTION__ . "\n output -->" . var_export(array('res' => $res), true));
	
		return $res;
		//return true;
	}
	
	/**
	 * 重新发送手机验证码
	 */
	public function re_send_phone_code() {
		$user_id = $this->input->post('user_id', true);
		$mobile_number = $this->input->post('mobileNumber', true);
		log_message('info', 'Into method' . __FUNCTION__ . "\n input -->" . var_export(array('user_id' => $user_id, 'mobileNumber' => $mobile_number), true));
		
		// 验证user_id
		$this->load->library('UmsLib');
		$user_info = $this->UmsLib->getUserById($user_id);
		if($user_info == false){
			log_message('error', 'The user whose user_id is '. $user_id . ' is not exist.');
			$this->assign('error_message', $this->lang->line('user_not_exist'));	// 该用户不存在
			$this->display('crippleware/err_msg.tpl');
		}
		
		// 发送短信验证码
		$this->send_phone_code($user_id, $mobile_number);
	}
	
	/**
	 * @abstract 验证手机验证码
	 */
	public function valid_phone_code() {
		$user_id = $this->input->post('user_id', true);
		$phone_code = $this->input->post('code', true);
		log_message('info', 'Into method' . __FUNCTION__ . "\n input -->" . var_export(array('user_id' => $user_id, 'phone_code' => $phone_code), true));
		
		// 验证userid
		$this->load->library('UmsLib', '', 'ums');
		$user_info = $this->ums->getUserById($user_id);
		if($user_info == false){
			form_json_msg(CODE_ERROR, 'code', $this->lang->line('user_not_exist'), array());// 该用户不存在
		}
		
		// 根据user_id在数据库中查找对应的验证码
		$this->load->model('uc_phone_varification_code_model');
		$code_info = $this->uc_phone_varification_code_model->getCodeByUserId($user_id);
		$code = isset($code_info['varification_code']) ? $code_info['varification_code'] : '';
		$create_time = isset($code_info['create_time']) ? $code_info['create_time'] : '';
		if(isemptyArray($code_info) || is_empty($code) || is_empty($create_time)){
			form_json_msg(CODE_ERROR, 'code', $this->lang->line('code_not_exist'), array());// 验证码不存在
		}
		
		// 验证手机验证码
		if($phone_code != $code){
			form_json_msg(CODE_ERROR, 'code', $this->lang->line('code_is_wrong'), array());// 验证码不正确
		}
		
		// 判断验证码是否有效
		$where_arr = array(
				'user_id' => $user_id
		);
		if($create_time + CODE_EXPIRED_TIME < time()){
			// 已过期，则修改验证码状态
			$update_data = array(
					'state' => CODE_IS_EXPIRED// 0、未验证；1、已验证；2、已过期
			);
			$res = $this->uc_phone_varification_code_model->updateCode($where_arr, $update_data);
			usset($update_data);
			form_json_msg(CODE_ERROR, 'code', $this->lang->line('code_is_expired'), array());// 验证码已过期
		}
		
		// 验证成功，则修改数据库中验证码的状态
		$update_data = array(
			'state' => CODE_IS_VALID// 0、未验证；1、已验证；2、已过期
		);
		$this->uc_phone_varification_code_model->updateCode($where_arr, $update_data);
		
		// 返回成功信息
		form_json_msg(COMMON_SUCCESS, '', $this->lang->line('success'), array('user_id' => $user_id));
	}
	
	/**
	 * @abstract 显示手机号码激活成功页面
	 */
	public function activation_phone_suc_page() {
		$user_id = $this->input->get_post('user_id', true);
		log_message('info', 'Into method ' . __FUNCTION__ . " \n input -->" . var_export(array('user_id' => $user_id), true));
		
		$this->assign('user_id', $user_id);
		$this->display('crippleware/valid_phone_suc.tpl');
	}
	
	/**
	 * @abstract 显示设置管理员密码页面
	 */
	public function set_crippleware_page() {
		$user_id = $this->input->get_post('user_id');
		log_message('info', 'Into method ' . __FUNCTION__ . " \n input -->" . var_export(array('user_id' => $user_id), true));
		
		// 验证user_id
		$this->load->library('UmsLib', '', 'ums');
		$user_info = $this->ums->getUserById($user_id);
		if($user_info == false){
			log_message('error', 'The user whose user_id is '. $user_id . ' is not exist.');
			$this->assign('error_message', $this->lang->line('user_not_exist'));	// 该用户不存在
			$this->display('crippleware/err_msg.tpl');
		}
		
		$loginName = isset($user_info['loginName']) ? $user_info['loginName'] : '';				// 用户名
		$mobileNumber = isset($user_info['mobileNumber']) ? $user_info['mobileNumber'] : ''; 	// 手机号码
		
		// 分配企业域名
		$site_url = $mobileNumber . '.quanshi.com/uc';
		
		// 判断该站点是否在UMS中存在
		$site_info_arr = $this->ums->getSiteInfoByUrl($site_url);
		if($site_info_arr == false){
			log_message('error', 'The site whose site_url is '. $site_url . ' is already exist.');
			$this->assign('error_message', $this->lang->line('site_is_exist'));	// 该站点已存在
			$this->display('crippleware/err_msg.tpl');
			return ;
		}
		
		// 向UMS创建站点
		$site_info = array(
			'url' 			=> $site_url,	// 站点url
			'aliasUrl' 		=> $site_url,  	// 站点域名
			'siteStatus' 	=> 1,
			'createTime' 	=> time(),
			'customerCode' 	=> TEST_CUSTOMER_CODE,
			'siteType' 		=> UMS_SITE_TYPE_TEST_OR_CORP // 0是用户site，1是公用site，2是测试site或运营公司site
		);
		$site_id = $this->ums->createSite($site_info);
		if(!$site_id){
			// 站点创建失败
			log_message('error', 'Create site which site_url is '. $site_url . ' failed');
			$this->assign('error_message', $this->lang->line('create_site_failed'));	// 创建站点失败
			$this->display('crippleware/err_msg.tpl');
			return ;
		}
		
		// 判断该站点在本地是否存在
		$this->load->model('uc_site_model');
		$uc_site = $this->uc_site_model->getInfosBySiteId($site_id);
		if(isemptyArray($uc_site)){
			log_message('error', 'The site whose site_url is '. $site_url . ' is already exist.');
			$this->assign('error_message', $this->lang->line('site_is_exist'));	// 该站点已存在
			$this->display('crippleware/err_msg.tpl');
			return ;
		}
		
		// 从试用账号属性表中获得站点属性
		$this->load->model('uc_crippleware_site_value_model');
		$type = CRIPPLEWATE_RADYSIS; // 1、radysis  2、radysis+summit
		$value_arr = $this->uc_crippleware_site_value_model->get_value($type);
		$value = isset($value_arr['value'])?$value_arr['value']:'';
		
		// 在本地创建站点
		$uc_site_info = array(
			'siteID' 			=> $site_id,
			'contractId' 		=> TEST_CONTRACT_ID,
			'domain' 			=> $site_url,
			'department_level' 	=> 1,
			'logoUrl' 			=> '',
			'companyType' 		=> COR_TYPE_FOCUS,
			'isLDAP' 			=> NOT_LDAP,
			'customerCode' 		=> TEST_CUSTOMER_CODE,
			'value' 			=> $value,
			'createTime' 		=> time()
		);
		$re_site = $this->uc_site_model->createSite($uc_site_info);
		if($re_site < 1){
			log_message('error', 'Create site which site_id is '. $site_id . ' failed');
			$this->assign('error_message', $this->lang->line('create_site_failed'));	// 创建站点失败
			$this->display('crippleware/err_msg.tpl');
			return ;
		}	
		
		// 在本地创建用户
		$uc_user = array(
				'userID' 		=> $user_id,
				'siteId' 		=> $site_id,
				'customerCode'  => TEST_CUSTOMER_CODE,
				'accountId' 	=> TEST_ACCOUNT_ID,
				'status' 		=> UC_USER_STATUS_ENABLE,// 用户状态：0、未激活；1、已激活；2、已关闭
				'create_time' 	=> time(),
				'expired_time' 	=> time() + CRIPPLEWARE_EXPIRED_TIME 	// 有效期为10天
		);
		$this->load->model('uc_user_model');
		$res = $this->uc_user_model->createUser($uc_user_data);
		if(!$res){
			log_message('error', 'Create local user whose user_id is '. $user_id . ' failed'); // 本地创建用户失败
			$this->assign('error_message', $this->lang->line('create_site_failed'));
			$this->display('crippleware/err_msg.tpl');
		}
		
		// 将账号显示在页面上
		$this->assign('loginName', $loginName);
		$this->assign('site_url', $site_url);
		$this->display('crippleware/set_pwd.tpl');
	}
	
	/**
	 * @abstract 接收并保存管理员密码
	 */
	public function save_manage_pwd() {
		$user_id = $this->input->get_post('user_id', true);
		$password = $this->input->get_post('password', true);
		$confirm_password = $this->input->get_post('confirm_password', true);
		log_message('info', 'Into method ' . __FUNCTION__ . " \n input -->" . var_export(array('user_id' => $user_id, 'possword' => $password, 'confirm_password' => $confirm_password), true));
		
		// 验证密码：1、两次输入的密码相等;2、密码验证规则？
		if($password != $confirm_password){
			form_json_msg(COMMON_FAILURE, '', $this->lang->line('password_not_equel'), array());
		}
		
		// 根据user_id获取loginName
		$this->load->library('UmsLib', '' , 'ums');
		$user_info = $this->ums->getUserById($user_id);
		if($user_info == false){
			form_json_msg(COMMON_FAILURE, '', $this->lang->line('user_not_exist'), array());
		}
		
		// 获得用户名
		$loginName = isset($user_info['loginName']) ? $user_info['loginName'] : '';
		if(is_empty($user_info['loginName'])) {
			form_json_msg(COMMON_FAILURE, '', $this->lang->line('user_not_exist'), array());
		}
		
		// 保存密码
		$updateData = array(
				'password' => md5($password)
		);
		$res = $this->ums->updateUser($loginName, $updateData);
		if($res == false){
			form_json_msg(COMMON_FAILURE, '', $this->lang->line('save_password_failed'), array());
		}
		
		// 创建管理员权限
		$this->load->model('uc_user_admin_role_model');
		$user_admin_role_arr = array(
			'user_id' 		=> $user_id,
			'role_id' 		=> SYSTEM_MANAGER,
			'state' 		=> ADMIN_OPEN,
			'create_time' 	=> time()
		);
		$role_id = $this->uc_user_admin_role_model->saveManager($user_admin_role_arr); 
		if($role_id == false){
			form_json_msg(COMMON_FAILURE, '', $this->lang->line('create_admin_failed'), array()); // 创建管理员失败
		}
		$this->load->model('uc_user_admin_model');
		$user_admin_arr = array(
			'userID' 			=> $user_id,
			'role_id' 			=> $role_id,
			'siteID' 			=> $site_id,
			'isLDAP' 			=> NOT_LDAP,
			'accountId' 		=> TEST_ACCOUNT_ID,
			'type' 				=> ADMIN_COMPANY_MANAGER,
			'state' 			=> ADMIN_OPEN,
			'last_login_time' 	=> isset($user_info['lastUpdateTime']) ? $user_info['lastUpdateTime']: '',
			'createTime' 		=> time(),
			'display_name' 		=> isset($user_info['lastName']) ? $user_info['lastName'] : '',
			'login_name' 		=> $loginName,
			'mobile_number' 	=> isset($user_info['mobileNumber']) ? $user_info['mobileNumber']: ''
		);
		$result = $this->uc_user_admin_model->create_admin($user_admin_info);
		if($result == false){
			form_json_msg(COMMON_FAILURE, '', $this->lang->line('create_admin_failed'), array()); // 创建管理员失败
		}
		
		// 根据customer_code获得站点id
		$site_info = $this->ums->getSiteInfoBCustomercode(TEST_CUSTOMER_CODE);
		if($site_info == false){
			form_json_msg(COMMON_FAILURE, '', $this->lang->line('site_not_exist'), array()); // 该站点不存在
		}
		$site_id = isset($site_info['id']) ? $site_info['id'] : '';
		if(empty($site_id)){
			form_json_msg(COMMON_FAILURE, '', $this->lang->line('site_not_exist'), array()); // 该站点不存在
		}
		
		// 根据customercode和站点Id获得站点名字
		$this->load->model('uc_customer_model');
		$where_arr = array(
			'customerCode' => $data['customerCode'],
			'siteId' => $site_id
		);
		$contract_info = $this->uc_customer_model->getContractid($where_arr);
		$site_name = isset($contract_info['name'])?$contract_info['name']:'';
		
		// 将账号信息保存到线程表做开通
		$user_info_arr = array(
			'lastname' 		=> $user_info['displayName'],
			'firstname' 	=> $user_info['firstName'],
			'loginname' 	=> $loginName,
			'open' 			=> true,
			'sex' 			=> sex,
			'account' 		=> TEST_ACCOUNT_ID,		// 账户Id
			'position' 		=> $user_info['position'],
			'mobile' 		=> $user_info['telephone'],
			'officeaddress' => '',
			'country' 		=> '',
			'department1' 	=> $site_name,
			'auth'			=> 1
		);
		$account_info = array(
			'customer_code' => TEST_CUSTOMER_CODE,
			'site_id' 		=> $site_id,
			'org_id' 		=> $site_id,
			'user_info' 	=> $user_info_arr
		);
		$this->load->model('account_upload_task_model');
		$res = $this->account_upload_task_model->saveTask(ACCOUNT_CREATE_UPLOAD, json_encode($account_info));
		if($res == false){
			form_json_msg(COMMON_FAILURE, '', $this->lang->line('fail'), array()); // 保存线程失败
		}
		
		form_json_msg(COMMON_SUCCESS, '', $this->lang->line('success'), array('user_id' => $user_id));
	}
	
	/**
	 * @abstract 显示试用账号申请成功页面
	 */
	public function apply_crippleware_suc() {
		$user_id = $this->input->get_post('user_id', true);
		log_message('info', 'Into method ' . __FUNCTION__ . " \n input -->" . var_export(array('user_id' => $user_id), true));
		
		// 根据user_id获取邮箱
		$this->load->library('UmsLib', '', 'ums');
		$user_info = $this->ums->getUserById($user_id);
		if($user_info == false){
			log_message('error', 'The user whose user_id is '. $user_id . ' is not exist in ums.');
			$this->assign('error_message', $this->lang->line('user_not_exist'));	// 该用户不存在
			$this->display('crippleware/err_msg.tpl');
		}
		$loginName = isset($user_info['loginName']) ? $user_info['loginName'] : '';	// 用户名
		
		// 根据customer_code获得站点id
		$customer_code = TEST_CUSTOMER_CODE;
		$site_info = $this->ums->getSiteInfoBCustomercode($customer_code);
		if($site_info == false){
			form_json_msg(COMMON_FAILURE, '', $this->lang->line('site_not_exist'), array()); // 该站点不存在
		}
		$site_url = isset($site_info['url']) ? $site_info['url'] : '';
		if(empty($site_url)){
			form_json_msg(COMMON_FAILURE, '', $this->lang->line('site_not_exist'), array()); // 该站点不存在
		}
		
		
		// 显示成功页面
		$this->assign('site_irl', $site_url);
		$this->assign('loginName', $loginName);
		$this->display('crippleware/get_crippleware_suc.tpl');
	}
}