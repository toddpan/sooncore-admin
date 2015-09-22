<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @abstract 	Login类，主要负责用户登录相关的操作
 * @filesource 	login.php
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
		//从缓存中获得登录次数，如果登录次数为空，则为第0次登录
		$login_num = bn_is_empty($this->session->userdata('login_num')) ? 0 : $this->session->userdata('login_num');
		
		//echo $login_num;
	
		$this->assign('login_num', $login_num);
		$this->assign('COMPANY_NAME', COMPANY_NAME);
		$this->assign('COMPANY_COPR', COMPANY_COPR);
		$this->assign('COMPANY_ICP', COMPANY_ICP);
		$this->assign('COMPANY_SERVER_TEL', COMPANY_SERVE_TEL);
		$this->assign('UC_NAME_EN', UC_NAME_EN);
		
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
				'img_width'   	=> 	'80',	//宽度
				'img_height'   	=> 	'33',	//宽度
                                'font_path'     => './system/fonts/3d.ttf'
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
		// 从参数列表获取登录次数
		$login_num = $login_num;
		
		// 获取表单提交的验证码
		$login_code = strtolower($this->input->post('loginCode', true));
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
	 */
	public function loginin() {
		// 获取表单提交的用户名
		$user_name = $this->input->post('userName', true);
		// 获得表单提交的密码
		$user_pwd  = $this->input->post('userPwd', true);
		log_message('info', __FUNCTION__." input->\n" . var_export(array('userName' => $user_name, 'userPwd' =>$user_pwd), true));
		
		// 从session中获得登录次数
		$login_num = bn_is_empty($this->session->userdata('login_num')) ? 0 : $this->session->userdata('login_num');
		
		// 如果登录次数大于等于三次，则判断验证码是否正确
		if($login_num >= 3){
			$this->valid_code($login_num);
		}
		
		// 验证用户名和密码是否为空
		if(bn_is_empty($user_name) ||  bn_is_empty($user_pwd)){
			$login_num += 1;
			
			// 将登录次数保存到session中
			$this->session->set_userdata('login_num', $login_num);
			
			// 发送出错信息：用户名或密码错误
			form_json_msg(USERNAME_OR_USERPWD_ERROR, '', $this->lang->line('userName_or_userPwd_error'), array('login_num' => $login_num));
		}
		
		// 验证用户名是否正确
		$this->form_validation->set_rules('email', 'userName', 'valid_email');
		if ($this->form_validation->run() == false){
			$login_num += 1;
				
			// 将登录次数保存到session中
			$this->session->set_userdata('login_num', $login_num);
				
			// 发送出错信息：用户名或密码错误
			form_json_msg(USERNAME_OR_USERPWD_ERROR, '', $this->lang->line('userName_or_userPwd_error'), array('login_num' => $login_num));
		}
		
		// 整理参数，调用登录接口
		$data_arr = array(
			'user_account' 	=> $user_name,
			'password' 		=> $user_pwd,
			'client_type' 	=> 4,
			'client_info'	=> json_encode(array('mac'=> MAC_ADDR))// Mac地址
		);
		$ucc_user_arr = $this->API->UCCServerAPI(http_build_query($data_arr), 1);
		log_message('info', '$ucc_user_arr = ' . json_encode($ucc_user_arr));
		$ucc_user_arr['data'] = isset($ucc_user_arr['data'])?$ucc_user_arr['data']:0;
		$ucc_user_arr = arr_unbound_value($ucc_user_arr['data'], '0', 1, array());
		//log_message('info', '$ucc_user_arr = ' . json_encode($ucc_user_arr));
		
		$user_id 		= isset($ucc_user_arr['user_id'])?$ucc_user_arr['user_id']:'';
		$site_id 		= isset($ucc_user_arr['site_id'])?$ucc_user_arr['site_id']:'';
		$ucc_session_id = isset($ucc_user_arr['session_id'])?$ucc_user_arr['session_id']:'';
		$dislay_name 		= isset($ucc_user_arr['profile']['dislay_name'])?$ucc_user_arr['profile']['dislay_name']:$ucc_user_arr['profile']['first_name'];
		//如果上次登录时间为0，表示该账号没有登陆过。赋值给$has_login，0表示该账户曾经登陆过，1表示该账户从来没有登陆过
		$has_login		= $ucc_user_arr['profile']['last_login_time'] == 0 ? 0 : 1 ;
		
		// 判断从UCCServer登录接口返回的数据是否为空
		if(isemptyArray($ucc_user_arr) || bn_is_empty($user_id) || bn_is_empty($site_id) || bn_is_empty($ucc_session_id)){
			$login_num += 1;
			
			// 将登录次数保存到session中
			$this->session->set_userdata('login_num', $login_num);
			
			// 发送出错信息：用户名或密码错误
			form_json_msg(USERNAME_OR_USERPWD_ERROR, '', $this->lang->line('userName_or_userPwd_error'), array('login_num' => $login_num));
		}
		
		// 载入相关模型
		$this->load->model('UC_User_Admin_Role_Model'); // 管理员角色
		$this->load->model('UC_User_Admin_Model'); 		// 管理员
		$this->load->model('UC_Site_Model');			// 站点
		$this->load->model('uc_role_privilege_model');	// 权限
		$this->load->model('uc_user_resource_model');	// 管理员维度
		
		// 判断当前账号是不是管理员账号并且是否被停用
		$condition_arr = array(
				'user_id' 	=> $user_id,
				'state' 	=> 1
		);
		$uc_admin_arr = $this->UC_User_Admin_Role_Model->getAdminByUseridAndState($condition_arr);
		log_message('debug', '$uc_admin_arr=' . json_encode($uc_admin_arr));
		
		$uc_role_id = $uc_admin_arr['role_id']; // 角色id
		$uc_state 	= $uc_admin_arr['state'];	// 状态:0、停用；1、启用
		
		$condition_arr = array(
				'userId' 	=> $user_id,
				//'state' 	=> 1
		);
		$uc_admin_arr = $this->UC_User_Admin_Model->getAdminByUseridAndState($condition_arr);
		log_message('debug', '$uc_admin_arr=' . json_encode($uc_admin_arr));
		$uc_org_id 	= $uc_admin_arr['orgID'];	// 组织id
		$uc_type 	= $uc_admin_arr['type'];	// 类型：1、总公司管理员；2、分公司管理员；3、生态企业管理员；0、其它
		$is_ldap 	= $uc_admin_arr['isLDAP'];
		
		if (isemptyArray($uc_admin_arr) || is_empty($uc_role_id) || is_empty($uc_org_id) || is_empty($uc_type) || $uc_state == 0 || is_empty($is_ldap)){
			$login_num += 1;
			
			// 将登录次数保存到session中
			$this->session->set_userdata('login_num', $login_num);
			
			// 发送出错信息：信息错误:不是管理员或账号被停用
			form_json_msg(NOT_ADMIN_OR_DISABLLE, '', $this->lang->line('error_information'), array('login_num' => $login_num));
		}
		
		// 获得客户编码和域名
		$uc_site_arr = $this->UC_Site_Model->getInfosBySiteId($site_id);
		log_message('debug', '$uc_site_arr=' . json_encode($uc_site_arr));
		$contract_id 	= isset($uc_site_arr['contractId'])?$uc_site_arr['contractId']:'';
		$customerCode 	= isset($uc_site_arr['customerCode'])?$uc_site_arr['customerCode']:'';
		$domain 		= isset($uc_site_arr['domain'])?$uc_site_arr['domain']:'';
		$companyType 	= isset($uc_site_arr['companyType'])?$uc_site_arr['companyType']:'';
		if(isemptyArray($uc_site_arr) || is_empty($customerCode) || is_empty($domain) ||is_empty($companyType) || is_empty($contract_id)){
			$login_num += 1;
			
			// 将登录次数保存到session中
			$this->session->set_userdata('login_num', $login_num);
			
			// 发送出错信息：信息错误
			form_json_msg(ERROR_INFORMATION, '', $this->lang->line('error_information'), array('login_num' => $login_num));
		}
		
		// 获得分账id
		$this->load->model('uc_user_model');
		$account_info = $this->uc_user_model->getUserInfo($user_id);
		$account_id = isset($account_info['accountId']) ? $account_info['accountId'] : '';
		if(empty($account_info) || is_empty($account_id)){
			$login_num += 1;
				
			// 将登录次数保存到session中
			$this->session->set_userdata('login_num', $login_num);
				
			// 发送出错信息：信息错误
			form_json_msg(ERROR_ACCOUNT_ID, '', $this->lang->line('error_information'), array('login_num' => $login_num));
		}
		
		$this->load->library('OrganizeLib','','OrganizeLib');
		$re_org_arr   = $this->OrganizeLib->get_org_by_id($uc_org_id);
		$org_type 	  = arr_unbound_value($re_org_arr,'type',2,'');//根点的组织类型 1:企业 2：生态企业 3：部门 4：生态企业部门 5：分公司
		$org_nodeCode = arr_unbound_value($re_org_arr,'nodeCode',2,'');//根点的组织id串
		
		// 获得用户权限uc_role_privilege
		$condition_arr    = array('role_id' => $uc_role_id);
		$uc_privilege_arr = $this->uc_role_privilege_model->getRolePrivilegeByRole($condition_arr);
		$uc_privilege_ids = array();
		if(is_array($uc_privilege_arr) && count($uc_privilege_arr) > 0){
			foreach($uc_privilege_arr as $v){
				$uc_privilege_ids[] = $v['privilege_id'];
			}
		}
		if(isemptyArray($uc_privilege_ids)){
			$login_num += 1;
			
			// 将登录次数保存到session中
			$this->session->set_userdata('login_num', $login_num);
			
			// 发送出错信息：信息错误
			form_json_msg(ERROR_ROLE, '', $this->lang->line('error_information'), array('login_num' => $login_num));
		}
		
		// 获得管理员维度，允许为空
		$condition_arr = array('userID' => $user_id );
		$uc_user_resource_arr = $this->uc_user_resource_model->getUserResource($condition_arr);
		log_message('debug', '$uc_user_resource_arr = ' . json_encode($uc_user_resource_arr));
		
		// 获得客户端ip地址
		$this->load->library('GetIP','','GetIP');
		$client_ip = $this->GetIP->get_client_ip();
		
		//更新帐号上次登陆时间
		$this->UC_User_Admin_Model->updateLastLoginTimeById($user_id);
		
		// 登录成功，则删除先前的session以及cache，并创建新的session以及cache
		$this->session->sess_destroy();
		$this->session->sess_recreate($ucc_session_id);
		
		// 将相关信息存入缓存中
		$value = array(
				'userid' 				=> $user_id,
                                'dislay_name'                           => $dislay_name,
				'account' 				=> $user_name,
				'admin_type' 			=> $uc_type,
				'site_id' 				=> $site_id,
				'org_id' 				=> $uc_org_id,
				'role_id' 				=> $uc_role_id,
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
// 		$cache_arr = cache('get', $this->p_session_id);
// 		log_message('info', 'cache=' . var_export($cache_arr, true));
		
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
