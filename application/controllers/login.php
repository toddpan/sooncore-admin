<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @abstract 	Login类，主要负责用户登录相关的操作
 * @author 		Bai Xue <xue.bai_2@quanshi.com>
 * @copyright 	Copyright (c) UC
 * @version 	v1.0
 */
class Login extends Web_Controller {
	
	/**
	 * @abstract 构造方法
	 */
	public function __construct() {
		parent::__construct();
		// 载入登录模块中文提示信息语言包
		$this->lang->load('login', 'chinese');
		// 载入httpcurl辅助函数
		$this->load->helper('my_httpcurl');
		// 载入接口类库
		$this->load->library('API', '', 'API');
		// 载入表单验证类
		$this->load->library('form_validation');
	}
	
	/**
	 * @abstract 显示登录页面
	 */
	public function loginPage(){
		//log_message('info', 'session_id' . $this->session->userdata('session_id'));
		// 从缓存中获得登录次数，如果登录次数为空，则为第0次登录
		$login_num = bn_is_empty($this->session->userdata('login_num')) ? 0 : $this->session->userdata('login_num');
		
		//echo $login_num;
	
		$this->assign('login_num', $login_num);
		$this->assign('COMPANY_NAME', COMPANY_NAME);
		$this->assign('COMPANY_COPR', COMPANY_COPR);
		$this->assign('COMPANY_ICP', COMPANY_ICP);
		$this->assign('COMPANY_SERVER_TEL', COMPANY_SERVE_TEL);
		$this->assign('download_link', MAIL_DOWNLOAD_LINK);
		
		// 加载登录页面
		$this->display('login.tpl');
	}
	
	/**
	 * @abstract 调用辅助函数生成验证码（不会生成图片）
	 */
	public function code() {
		// 载入captcha辅助函数
		$this->load->helper('captcha');
		
		// 调用辅助函数生成验证码
		$vals = array(
				'word_length' 	=> 	4,		//长度
				'img_width'   	=> 	'78'	//宽度
		);
		$code = create_captcha($vals);
		
		// 将验证码保存到session中
		$this->session->set_userdata('login_code', $code);
	}
	
	/**
	 * @abstract 验证表单提交的验证码是否正确
	 * @param 	 int		$login_num		登录次数	
	 */
	public function valid_code($login_num = 3) {		
		// 获取表单提交的验证码
		$login_code = strtolower(trim($this->input->post('loginCode', true)));
		log_message('debug', __FUNCTION__." input->\n" . var_export(array('loginCode' => $login_code), true));
		
		// 获取缓存中保存的验证码
		$code = strtolower($this->session->userdata('login_code'));
		
		// 判断表单提交的验证码与缓存中保存的验证码是否相同
		if($login_code != $code){
			// 验证码错误，则登录次数+1
			$login_num += 1;
			
			// 将登录次数保存到session中
			$this->session->set_userdata('login_num', $login_num);
			
			// 发送出错信息：验证码错误
			form_json_msg(LOGIN_CODE_ERROR, 'loginCode', $this->lang->line('login_code_error'), array('login_num' => $login_num));
		}
	}
	
	/**
	 * @abstract 验证登录
	 * 
	 * 1、获取用户表单提交的数据；
	 * 2、判断登录次数，校验验证码；
	 * 3、通过login_name在ucuser_admin表获得user_id和站点id；
	 * 4、通过站点id获得站点url和isLDAP；
	 * 5、根据isLDAP调用UCCServer不同的登录接口；
	 * 6、如果是ldap站点：
	 * 	6.1、根据根据user_id 到uc_user_admin_role table 取role_id_list；
	 * 	6.2、如果role_id_list中有系统管理员，则调用ucc server 提供的新的登录接口；
	 * 	6.3、反之，则调用ucc server原有的登录接口，传递用户名，密码， siteUrl；
	 * 7、如果是批量导入站点：调用ucc server原有的登录接口，传递用户名，密码；
	 * 8、如果是其他站点：做其他处理；
	 * 9、获得其他必须参数。
	 */
	public function loginin() {
		$user_name = trim($this->input->post('userName', true)); 	// 用户名
		$user_pwd  = trim($this->input->post('userPwd', true));	// 密码
		log_message('info', 'Into method loginin input --> $user_name='.$user_name.', $user_pwd='.$user_pwd);
		
		// 从session中获得登录次数，并判断验证码是否正确
		$login_num = bn_is_empty($this->session->userdata('login_num')) ? 0 : $this->session->userdata('login_num');
		if($login_num >= 3){
			$this->valid_code($login_num);
		}
		
		// 验证用户名和密码是否为空
		if(bn_is_empty($user_name) ||  bn_is_empty($user_pwd)){
			++$login_num;
			$this->session->set_userdata('login_num', $login_num);
			form_json_msg(USERNAME_OR_USERPWD_ERROR, '', $this->lang->line('userName_or_userPwd_error'), array('login_num' => $login_num));
		}
		
// 		if($is_ldap == 1){ // LDAP站点
// 			if($uc_role_id == 1){ //TODO 系统管理员，调用新的UCC接口
				
// 			}else{ // 其他管理员
// 				$data_arr = array(
// 					'user_account' 	=> $user_name,
// 					'password' 		=> $user_pwd,
// 					'site_url'		=> $site_url,
// 					'client_type' 	=> 4,
// 					'client_info'	=> json_encode(array('mac'=> MAC_ADDR))// Mac地址
// 				);
// 				$ucc_user_arr = $this->API->UCCServerAPI(http_build_query($data_arr), 1);
// 			}
// 		}else if($is_ldap == 0 || $is_ldap == 2){ // 批量导入
// 			$data_arr = array(
// 					'user_account' 	=> $user_name,
// 					'password' 		=> $user_pwd,
// 					'client_type' 	=> 4,
// 					'client_info'	=> json_encode(array('mac'=> MAC_ADDR))// Mac地址
// 			);
// 			$ucc_user_arr = $this->API->UCCServerAPI(http_build_query($data_arr), 1);
// 		}else{
// 			// 其他类型站点：oAuth
// 		}

// 		if($is_ldap == IS_LDAP && $uc_role_id != SYSTEM_MANAGER){ // LDAP站点的非系统管理员
// 			$data_arr = array(
// 				'user_account' 	=> $user_name,
// 				'password' 		=> $user_pwd,
// 				'site_url'		=> $site_url,
// 				'client_type' 	=> 4,
// 				'client_info'	=> json_encode(array('mac'=> MAC_ADDR))// Mac地址
// 			);
// 		}else{
// 			// 其他类型站点的管理员
			$data_arr = array(
					'user_account' 	=> $user_name,
					'password' 		=> $user_pwd,
					'client_type' 	=> 4,
					'client_info'	=> json_encode(array('mac'=> MAC_ADDR))// Mac地址
			);
// 		}
		
		
		$ucc_user_arr = $this->API->UCCServerAPI(http_build_query($data_arr), 1);
		log_message('info', '$ucc_user_arr = ' . json_encode($ucc_user_arr));
		
		// 判断从UCCServer登录接口返回的数据是否为空
		$ucc_user_arr['data'] 	= isset($ucc_user_arr['data'])?$ucc_user_arr['data']:0;
		$ucc_user_arr 			= arr_unbound_value($ucc_user_arr['data'], '0', 1, array());
		$user_id 				= isset($ucc_user_arr['user_id'])?$ucc_user_arr['user_id']:'';
		$ucc_session_id 		= isset($ucc_user_arr['session_id'])?$ucc_user_arr['session_id']:'';
		// 如果上次登录时间为0，表示该账号没有登陆过。赋值给$has_login，0表示该账户曾经登陆过，1表示该账户从来没有登陆过
		$has_login		= (isset($ucc_user_arr['profile']['last_login_time']) ? $ucc_user_arr['profile']['last_login_time'] : 0) == 0 ? 0 : 1;
		if(isemptyArray($ucc_user_arr) || bn_is_empty($user_id) || bn_is_empty($ucc_session_id)){
			++$login_num;
			$this->session->set_userdata('login_num', $login_num);
			form_json_msg(USERNAME_OR_USERPWD_ERROR, '', $this->lang->line('userName_or_userPwd_error'), array('login_num' => $login_num));
		}
		
		// 根据login_name获得site_id
		$this->load->model('uc_user_admin_model');
		$user_info_arr = $this->uc_user_admin_model->getAdminByUseridAndState(array('userID' => $user_id));
		log_message('info', '$user_info_arr=' . json_encode($user_info_arr));
		$site_id 	= isset($user_info_arr['siteID']) ? $user_info_arr['siteID'] : 0;
		$uc_org_id 	= isset($user_info_arr['orgID']) ? $user_info_arr['orgID'] : '';// 组织id
		$uc_type 	= isset($user_info_arr['type']) ? $user_info_arr['type'] : '';	// 类型：1、总公司管理员；2、分公司管理员；3、生态企业管理员；0、其它
		//$is_ldap 	= isset($user_info_arr['isLDAP']) ? $user_info_arr['isLDAP'] : '';
		if(!$site_id){
			form_json_msg(SITEID_NOT_EXIST, '', $this->lang->line('userName_or_userPwd_error'), array('login_num' => $login_num));
		}
		
		// 判断当前账号是不是管理员账号并且是否被停用
		$this->load->model('UC_User_Admin_Role_Model'); // 管理员角色
		$condition_arr = array(
				'user_id' 	=> $user_id,
				'state' 	=> 1
		);
		$uc_admin_arr = $this->UC_User_Admin_Role_Model->getAdminByUseridAndState($condition_arr);
		log_message('debug', '$uc_admin_arr=' . json_encode($uc_admin_arr));
		$uc_role_id 		= isset($uc_admin_arr['role_id']) ? $uc_admin_arr['role_id'] : ''; 	// 角色id
		$uc_state 			= isset($uc_admin_arr['state']) ? $uc_admin_arr['state'] : '';		// 状态:0、停用；1、启用
		$uc_admin_role_id 	= isset($uc_admin_arr['id']) ? $uc_admin_arr['id'] : 0; 			// uc_user_admin_role表中的主键
		if (isemptyArray($uc_admin_arr)||is_empty($uc_role_id)||is_empty($uc_org_id)||is_empty($uc_type)||is_empty($uc_state)){
			++$login_num;
			$this->session->set_userdata('login_num', $login_num);
			form_json_msg(NOT_ADMIN_OR_DISABLLE, '', $this->lang->line('not_admin'), array('login_num' => $login_num));
		}
		
		// 根据site_id获得site_url
		$this->load->model('uc_site_model');
		$site_info = $this->uc_site_model->getInfosBySiteId($site_id);
		log_message('debug', '$site_info=' . json_encode($site_info));
		$site_url 		= isset($site_info['domain']) ? $site_info['domain'] : '';
		$is_ldap 		= isset($site_info['isLDAP']) ? $site_info['isLDAP'] : '';
		$contract_id 	= isset($site_info['contractId'])?$site_info['contractId']:'';
		$customerCode 	= isset($site_info['customerCode'])?$site_info['customerCode']:'';
		$domain 		= isset($site_info['domain'])?$site_info['domain']:'';
		$companyType 	= isset($site_info['companyType'])?$site_info['companyType']:'';
		if(!$site_url){
			form_json_msg(SITEURL_NOT_EXIST, '', $this->lang->line('userName_or_userPwd_error'), array('login_num' => $login_num));
		}
		
		// 判断密码是否已过期
		$this->load->model('password_change_history_model');
		$condition = array(
				'user_id' => $user_id,
				'site_id' => $site_id,
				'password' => md5($user_pwd)
		);
		$password_arr = $this->password_change_history_model->get_pwd_records($condition);
		$pwd_create_time = isset($password_arr[0]['create_time']) ? $password_arr[0]['create_time'] : '';

		if(!empty($pwd_create_time)){
			$this->load->model('uc_pwd_manage_model');
			$pwd_set_arr = $this->uc_pwd_manage_model->get_pwd_manage_arr($uc_org_id, $site_id);

			include_once APPPATH . 'libraries'. DIRECTORY_SEPARATOR . 'public'. DIRECTORY_SEPARATOR .'Pass_word_attr.php';
			$pwd_obj = new Pass_word_attr();
			$expiry_day_type = isset($pwd_set_arr['expiry_day_type']) ? $pwd_set_arr['expiry_day_type'] : DEFAULT_PWD_EXPIRY_DAY;
			$expiry_day_set = $pwd_obj->get_arr_byid(1, $expiry_day_type);

			if($expiry_day_set['id'] != EXPIRY_NOT_LIMIT_TYPE){
				$day =  ceil((time() - $pwd_create_time)/(24*3600));
				
				if($day > $expiry_day_set['daynum']){
					// 密码已过期
					++$login_num;
					form_json_msg(PWD_EXPIRED, '', $this->lang->line('pwd_expired'), array('login_num' => $login_num));
				}
			}
		}
		
		// 获得分账id:在ldap和生态企业用
		$this->load->model('uc_user_model');
		$account_info = $this->uc_user_model->getUserInfo($user_id);
		$account_id = isset($account_info['accountId']) ? $account_info['accountId'] : '';
		if(empty($account_info) || is_empty($account_id)){
			++$login_num;
			$this->session->set_userdata('login_num', $login_num);
			form_json_msg(ERROR_ACCOUNT_ID, '', $this->lang->line('error_information'), array('login_num' => $login_num));
		}
		
		// 获得组织id串
		$this->load->library('OrganizeLib','','OrganizeLib');
		$re_org_arr   = $this->OrganizeLib->get_org_by_id($uc_org_id);
		$org_type 	  = arr_unbound_value($re_org_arr,'type',2,'');		//根点的组织类型 1:企业 2：生态企业 3：部门 4：生态企业部门 5：分公司
		$org_nodeCode = arr_unbound_value($re_org_arr,'nodeCode',2,'');	//根点的组织id串
		
		// 获得用户权限uc_role_privilege
		$this->load->model('uc_role_privilege_model');
		$condition_arr    = array('role_id' => $uc_role_id);
		$uc_privilege_arr = $this->uc_role_privilege_model->getRolePrivilegeByRole($condition_arr);
		$uc_privilege_ids = array();
		if(is_array($uc_privilege_arr) && count($uc_privilege_arr) > 0){
			foreach($uc_privilege_arr as $v){
				$uc_privilege_ids[] = isset($v['privilege_id']) ? $v['privilege_id'] : '';
			}
		}
		
		// 获得管理员维度，允许为空
		$this->load->model('uc_user_resource_model');
		$condition_arr = array('userID' => $user_id );
		$uc_user_resource_arr = $this->uc_user_resource_model->getUserResource($condition_arr);
		log_message('debug', '$uc_user_resource_arr = ' . json_encode($uc_user_resource_arr));
		
		// 获得客户端ip地址
		$this->load->library('GetIP','','GetIP');
		$client_ip = $this->GetIP->get_client_ip();
		
		// 更新帐号上次登陆时间
		$this->uc_user_admin_model->updateLastLoginTimeById($user_id);
		
		// 登录成功，则删除先前的session以及cache，并创建新的session以及cache
		$this->session->sess_destroy();
		$this->session->sess_recreate($ucc_session_id);
		
		// 将相关信息存入缓存中
		$value = array(
				'userid' 				=> $user_id,
				'account' 				=> $user_name,
				'admin_type' 			=> $uc_type,
				'site_id' 				=> $site_id,
				'org_id' 				=> $uc_org_id,
				'role_id' 				=> $uc_role_id,
				'admin_role_id'			=> $uc_admin_role_id,
				'customerCode' 			=> $customerCode,
				'account_id' 			=> $account_id,
				'domain' 				=> $domain,
				'org_type' 				=> $org_type,
				'nodeCode' 				=> $org_nodeCode,
				'client_ip' 			=> $client_ip,
				'companyType' 			=> $companyType,
				'contract_id' 			=> $contract_id,
				'uc_privilege_ids' 		=> $uc_privilege_ids,
				'uc_user_resource_arr' 	=> $uc_user_resource_arr,
				'is_ldap' 				=> $is_ldap,
				'has_login' 			=> $has_login,
				'last_activity' 		=> time()
		);
		$this->session->set_userdata($value);
 		log_message('info', 'session_id=' . $this->session->userdata('session_id'));
		
		// 发送成功信息 
		form_json_msg(COMMON_SUCCESS, '', $this->lang->line('login_success'), array('login_num' => 0));
	}
	
	/**
	 * @abstract 退出登录
	 */
	public function logout() {	
		// 获得session_id和userid
		$session_id = $this->session->userdata('session_id');
		$user_id 	= $this->session->userdata('user_id');
		
		// 组装数据，调用UCCServer的退出登录接口
		$data_arr = array(
				'session_id' 	=> $session_id,
				'user_id' 		=> $user_id,
		);
		$ucc_user_arr = $this->API->UCCServerAPI(http_build_query($data_arr), 2);
		
		// 判断从退出登录接口返回的数据是否正确， 不正确则打log，并退出程序
		$ucc_login_code = isset($ucc_login_out_arr['code'])?$ucc_login_out_arr['code']:'';
		if($ucc_login_code != 0){
			log_message('error', 'logout fail----$ucc_login_out_arr = ' . var_export($ucc_login_out_arr, true));
			die;
		}
		
		// 销毁session
		$this->session->sess_destroy();
		
		//跳转到登陆页
		redirect('login/loginPage');
	}
}