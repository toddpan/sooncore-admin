<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @abstract	ResetPassword Controller，主要用于当用户忘记密码时找回密码的相关操作。
 * @filesource	resetpassword.php
 * @author 		Bai Xue <xue.bai_2@quanshi.com>
 * @copyright 	Copyright (c) UC
 * @version 	v1.0
 */
class ResetPassword extends Web_Controller {
	public static $num = 1;
	
	/**
	 * 构造方法
	 */
	public function __construct() {
		parent::__construct();
		
		// 载入忘记密码模块中文提示信息语言包
		$this->lang->load('forgetpwd', 'chinese');
	}

	/**
	 * 显示忘记密码页面:提示输入帐号和验证码。
	 */
	public function index() {
		$this->load->view('public/popup/forgetpassword1.php');
	}
	
	/**
	 * 生成图片验证码
	 */
	public function generate_img_code(){
		// 载入captcha辅助函数
		$this->load->helper('captcha');
		
		// 调用辅助函数生成验证码
		$vals = array(
				'word_length' 	=> 4,	// 长度
				'img_width'   	=> '78'	// 宽度
		);
		$code = create_captcha($vals);
		
		// 将验证码保存到session中
		$this->session->set_userdata('img_code', $code);
	}

	/**
	 * 验证用户表单提交的账号和验证码
	 * 
	 * 1、获得用户表单提交的账号和验证码；
	 * 2、验证验证码是否正确；
	 * 3、验证账号是否正确；
	 * 4、调用UMS接口查看账号是否存在
	 * 5、存在，则调用UCCServer接口发送手机短信验证码，保存验证码到数据库，有效期为1分钟
	 */
	public function valid_account() {
		$user_account 	= $this->input->get_post('user_account', true); 	// 账号
		$pwd_code 		= $this->input->get_post('pass_word_code', true); 	// 验证码
		log_message('info', 'Into method accountValidate input---> $user_account=' . $user_account .',$pwd_code=' . $pwd_code);
		
		// 验证验证码是否正确
		if(strtolower($pwd_code) != strtolower($this->session->userdata('img_code'))){
			return_json(IMG_CODE_IS_WRONG, $this->lang->line('img_code_is_wrong'), array()); // 您输入的验证码有误
		}
		
		// 调用UMS接口判断账号是否存在
		$this->load->library('UmsLib', '', 'ums');
		$ums_user_info = $this->ums->getUserByLoginName($user_account);
		if($ums_user_info == false){
			return_json(UMS_LOGINNAME_NOT_EXIST, $this->lang->line('ums_login_name_not_exist'), array()); // 该账号不存在
		}
		
		// 判断产品状态
		$user_id = isset($ums_user_info['id']) ? $ums_user_info['id'] : '';
		$product_info = $this->ums->getUserProduct($user_id, UC_PRODUCT_ID);
		if($product_info == false){
			return_json(UMS_PRODUCT_NOT_EXIST, $this->lang->line('ums_product_not_exist'), array()); // 该账号不存在
		}
		
		// 判断手机号是否为空
		$mobile  = isset($ums_user_info['mobileNumber']) ? $ums_user_info['mobileNumber'] : '';
		if(empty($mobile)){
			return_json(HAVE_NO_PHONE, $this->lang->line('have_no_phone'), array()); // 无法获取到您的手机号，请联系系统管理员
		}
		
		// 判断该用户是否是管理员
		$this->load->model('uc_user_admin_model');
		$this->load->model('uc_user_admin_role_model');
		$admin_where_arr = array(
			'userID' => $user_id
		);
		$re_admin_arr = $this->uc_user_admin_model->getAdminByUseridAndState($admin_where_arr);
		$where_arr = array(
			'user_id' 	=> $user_id,
			'state'		=> ADMIN_OPEN
		);
		$re_admin_role_arr = $this->uc_user_admin_role_model->getAdminByUseridAndState($where_arr);
		$role_id = isset($re_admin_role_arr['role_id']) ? $re_admin_role_arr['role_id'] : '';
		if(empty($re_admin_arr) || is_empty($role_id)){
			return_json(YOU_ARE_NOT_ADMIN, $this->lang->line('you_are_not_admin'), array()); // 您输入的账号不是管理员账号
		}
		
		// 发送手机短信验证码
		$this->send_msg_code($user_id, $mobile);
	}
	
	/**
	 * 调用UCCServer接口发送手机短信验证码，并将验证码保存的UC数据库中
	 * @param int 		$user_id
	 * @param string 	$mobile
	 */
	public function send_msg_code($user_id, $mobile){
		log_message('info', 'Into method send_msg_code input---> $user_id=' . $user_id .',$mobile=' . $mobile);
		
		// 获取24小时内验证码发送次数
		$this->load->model('uc_phone_varification_code_model');
		$today_num = $this->uc_phone_varification_code_model->get_today_num($user_id);
		
		// 判断24小时内验证码发送次数是否小于五次，是，则发送验证码，否，则删除今天之前的验证码
		if($today_num < TODAY_CODE_SEND_NUM){
			
			// 生成短信验证码（6位数字）
			$msg_code = '';
			for($i = 0; $i < 6; $i++){
				$msg_code .= rand(0, 9);
			}
			
			// 调用UCCServer接口发送手机短信验证码
			$this->load->library('UccLib', '', 'ucc');
			$this->lang->load('msg_tpl', 'chinese');
			$msg_tpl = $this->lang->line('msg_tpl_forget_pwd');
			$content = sprintf($msg_tpl, $msg_code);;
			$this->ucc->sendMobileMsg($user_id, $content, $mobile);
			
			// 将验证码保存到数据库中
			$code_info = array(
					'user_id' 			=> $user_id,
					'varification_code' => $msg_code,
					'create_time' 		=> time(),
					'state' 			=> CODE_NOT_VALID // 未验证
			);
			$this->uc_phone_varification_code_model->saveCode($code_info);
			
			return_json(COMMON_SUCCESS, $this->lang->line('success'), array('user_id' => $user_id, 'mobile' => $mobile));
			
		}else{
			$this->uc_phone_varification_code_model->del_code($user_id);
			return_json(COMMON_FAILURE, sprintf($this->lang->line('most_msg_num'), TODAY_CODE_SEND_NUM), array()); // 您今天最多只能发送5条短信验证码
		}
	}

	/**
	 * 显示输入短信验证码页面
	 * 
	 * 1、输入验证码
	 * 2、可重新发送验证码
	 */
	public function input_msgcode_page($user_id, $mobile) {
		$mobile_str = substr_replace($mobile, ' **** ', 3, -4);
		
		$data['user_id'] = $user_id;
		$data['mobile'] = $mobile;
		$data['mobile_str'] = $mobile_str;
		$this->load->view('public/popup/forgetpassword2.php', $data);
	}
	
	/**
	 * 验证短信验证码
	 */
	public function valid_msgcode() {
		$user_id 	= $this->input->get_post('user_id', true);
		$msg_code 	= $this->input->get_post('msg_code', true); // 短信验证码
		log_message('info', 'Into method valid_msgcode input---> $user_id=' . $user_id . ',$msg_code=' . $msg_code);
		
		// 验证表单提交的短信验证码的长度
		if(strlen($msg_code) != 6){
			return_json(MSG_LEN_IS_WRONG, $this->lang->line('msg_is_wrong'), array()); // 短信验证码不正确
		}
		
		// 根据userId和手机号从数据库中查出符合条件的短信验证码
		$this->load->model('uc_phone_varification_code_model');
		$where_arr = array(
			'user_id' => $user_id,
			'state' => CODE_NOT_VALID // 未验证
		);
		$msg_info = $this->uc_phone_varification_code_model->getCode($where_arr);
		
		// 先比较短信验证码的有效期
		$create_time = isset($msg_info['create_time']) ? $msg_info['create_time'] : '';
		if((time() - 60 > $create_time)){
			return_json(MSG_TIME_IS_WRONG, $this->lang->line('msg_time_is_wrong'), array()); // 短信验证码已过期
		}
		
		// 再比较短信验证码内容
		$msg = isset($msg_info['varification_code']) ? $msg_info['varification_code'] : '';
		if($msg != $msg_code){
			return_json(MSG_IS_WRONG, $this->lang->line('msg_is_wrong'), array()); // 短信验证码错误
		}
		
		return_json(COMMON_SUCCESS, $this->lang->line('success'), array());
	}
	
	/**
	 * 显示输入新密码页面
	 */
	public function inut_new_pwd_page($user_id) {
		log_message('info', 'Into method inut_new_pwd_page input---> $user_id=' . $user_id);
		
		// 获得密码复杂性
		$current_pwd_arr = $this->get_complexity($user_id);
		
		$data['user_id'] = $user_id;
		$data['complexity_arr'] = $current_pwd_arr;
		$this->load->view('public/popup/forgetpassword3.php', $data);
	}
	
	/**
	 * 获得密码复杂性
	 * @param int $user_id
	 * @return array 密码复杂性数组
	 */
	private function get_complexity($user_id) {
		log_message('info', 'Into method get_complexity input---> $user_id=' . $user_id);
		
		$this->load->model('uc_user_admin_model');
		$condition = array(
			'userID' => $user_id
		);
		$admin_info_arr = $this->uc_user_admin_model->getAdminByUseridAndState($condition);
		$site_id = isset($admin_info_arr['siteID']) ? $admin_info_arr['siteID'] : 0;
		$org_id  = isset($admin_info_arr['orgID']) ? $admin_info_arr['orgID'] : 0;
		
		// 根据站点id和组织id获得密码复杂度
		$this->load->model('uc_pwd_manage_model');
		$complexity_arr = $this->uc_pwd_manage_model->get_pwd_manage_arr($org_id, $site_id);
		$complexity_type = isset($complexity_arr['complexity_type']) ? $complexity_arr['complexity_type'] : 1;
		
		include_once APPPATH . 'libraries' . DIRECTORY_SEPARATOR .'public'. DIRECTORY_SEPARATOR .'Pass_word_attr.php';
		$password_obj = new Pass_word_attr();
		// 获取密码复杂性数组
		$password_arr = $password_obj->get_complexity_type_arr();
		
		// 获得当前密码复杂性数组
		$current_pwd_arr = array();
		foreach($password_arr as $password){
			if($complexity_type == $password['id']){
				$current_pwd_arr = $password;
				break;
			}
		}
		log_message('info', 'Out method get_complexity input---> $current_pwd_arr=' . var_export($current_pwd_arr, true));
		return $current_pwd_arr;
	}
	
	/**
	 * 重置密码
	 */
	public function reset_pwd() {
		$user_id 	 = $this->input->get_post('user_id', true);
		$pwd 		 = $this->input->get_post('pwd', true);
		$confirm_pwd = $this->input->get_post('confirm_pwd', true);
		log_message('info', 'Into method reset_pwd input---> $user_id=' . $user_id . ',$pwd=' . $pwd .',$confirm_pwd=' . $confirm_pwd);
		
		// 判断两次输入的密码是否相同
		if($pwd != $confirm_pwd){
			return_json(TWO_PWD_NOT_EQUEL, $this->lang->line('two_pwd_not_equel'), array()); // 两次输入的密码不相等
		}
		
		// 获得密码复杂性
		$current_pwd_arr = $this->get_complexity($user_id);
		$regex = isset($current_pwd_arr['regexptxt']) ? $current_pwd_arr['regexptxt'] : ''; // 正则表达式
		
		if(!empty($regex) && !preg_match($regex, $pwd)){
			return_json(PWD_ILLIGEL, $this->lang->line('pwd_illigel'), array()); // 您输入的密码不合法
		}
		
		// 调用UMS接口修改密码
		$this->load->library('UmsLib', '', 'ums');
		$res = $this->ums->resetUserPassword($user_id, $pwd);
		if(!$res){
			return_json(RESET_USER_PWD_FAIL, $this->lang->line('reset_pwd_fail'), array()); // 修改密码失败
		}
		
		// 向客户端发消息
		$this->load->model('uc_user_model');
		$select_str = 'siteId';
		$where_arr = array(
			'userID' => $user_id
		);
		$user_info = $this->uc_user_model->getUserInfos($select_str, $where_arr);
		$site_id = isset($user_info['siteId']) ? $user_info['siteId'] : 0;
		$this->load->library('Informationlib','','Informationlib');
		$info_pre_arr = array(
			'from_user_id' 	=> $user_id,
			'from_site_id' 	=> $site_id,
			'to_user_id' 	=> $user_id,
			'to_site_id' 	=> $site_id,
			'is_group' 		=> 0,
			'msg_type' 		=> 0,
			'msg_id' 		=> 17,
			'type' 			=> 0
		);
		$info_body = array(
			'pwd' => md5($pwd)
		);
		$this->Informationlib->send_info($info_pre_arr, $info_body);
		
		return_json(COMMON_SUCCESS, $this->lang->line('success'), array());
	}
	
	/**
	 * 显示重置密码成功页面
	 */
	public function reset_pwd_suc_page() {
		$this->load->view('public/popup/forgetpassword4.php');
	}
}
