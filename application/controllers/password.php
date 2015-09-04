<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @abstract 	PassWord Controller，密码管理控制器，主要用于用户进行密码管理变更设置。
 * @filesource 	password.php
 * @author 		Bai Xue <xue.bai_2@quanshi.com>
 * @copyright 	Copyright (c) UC
 * @version 	v1.0
 */
class PassWord extends Admin_Controller{
	
	/**
	 * 构造函数
	 */
	public function __construct() {
		parent::__construct();

		$this->load->library('API','','API');
		$this->load->model('uc_pwd_manage_model');
	}

	/**
	 * 显示密码变更设置管理页面
	 */
	public function PWDManagePage() {		
		$this->setFunctions();
		
		if(!$this->functions['PasswordManage']){
			echo ('<script type="text/javascript">loadCont("log/logPage");</script>');
		}
		else{
			// 根据当前组织id和站点id获得当前的密码规则
			$in_arr = array(
					'org_id' 	=> $this->p_org_id,
					'site_id' 	=> $this->p_site_id,
			);
			$data['pwdArr'] = $this->uc_pwd_manage_model->get_pwd_arr($in_arr) ;			
			
			$this->load->view('password/pwdmanagelist.php', $data);
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
	 * 新加/修改密码管理
	 */
	public function modifyPWDManage() {
		$expiry_day_type 	= $this->input->post('expiry_day_type', true); 	// 密码过期时间设置
		$history_type 		= $this->input->post('history_type', true);		// 密码历史记忆次数设置
		$complexity_type 	= $this->input->post('complexity_type', true); 	// 密码复杂度设置
		log_message('info', 'Into method modifyPWDManage input --->' . var_export(array('expiry_day_type' => $expiry_day_type, 'history_type' => $history_type, 'complexity_type' => $complexity_type), true));

		$this->load->model('UC_PWD_Manage_Model');
		$in_arr = array(
				'org_id' 	=> $this->p_org_id,
                'site_id' 	=> $this->p_site_id,
		);
		$pwd_arr = $this-> UC_PWD_Manage_Model ->get_pwd_arr($in_arr) ;
		$old_complexity_type = arr_unbound_value($pwd_arr,'complexity_type',2,'');

		//执行修改操作，没有记录则新加，有记录则修改
		$select_field = 'id';
		$where_arr = array(
				'org_id' 	=> $this->p_org_id,
				'site_id' 	=> $this->p_site_id,
			);
		$modify_arr = array(
				'org_id' 			=> $this->p_org_id,
				'site_id' 			=> $this->p_site_id,
				'expiry_day_type' 	=> $expiry_day_type,// 用户密码有效期：1、30天；2、60天；3、90天；4、180天；5、不需要变更。默认90天
				'history_type' 		=> $history_type,	// 密码历史记忆：1、3次；2、5次；3、10次；4、不记忆。默认是3次
				'complexity_type' 	=> $complexity_type,// 密码复杂性要求：1、8-30位，不限制类型；2、8-30位数字与字母组合；3、8-30位数字、符号与字母组合
		);
		$insert_arr = $modify_arr;
		$insert_arr['create_time'] = dgmdate(time(), 'dt');
		$re_num = $this->UC_PWD_Manage_Model->updata_or_insert(1,$select_field,$where_arr,$modify_arr,$insert_arr);
		
		if($re_num == -2 || $re_num == -4){ //失败 -1 更新记录成功 -2更新操作失败 -3新加的记录id 新加记录成功 -4新加操作失败 -5 没有操作
			$err_msg = 'update/insert  uc_user_config_model fail. $re_num =' . $re_num . ' ';
			log_message('error', $err_msg);
			form_json_msg('10','',$err_msg);
		}

		//如果修改了密码规则之后，密码的复杂度变化，则调用uccserver 系统通知消息发送
		if($old_complexity_type != $complexity_type){
			//接口参参数
			$json_arr = array(
                    'type' => $complexity_type
			);
			$data = 'session_id=' . $this->p_session_id . '&user_id=' . $this->p_user_id . '&type=2&data=' . json_encode($json_arr);

			//调用发送消息接口
			$ucc_msg_arr = $this->API->UCCServerAPI($data,11);
			if(!api_operate_fail($ucc_msg_arr)){ //成功
				log_message('info', '  uccserver api message/systemAlert ' . $data . ' success .');
			}else{ //失败
				log_message('info', '  uccserver api message/systemAlert ' . $data . ' fail .');
				//form_json_msg('3', '', '调用发送信息失败');
			}
		}
		form_json_msg('0', '', '密码规则修改成功');
	}
	
	/**
	 * 密码复杂度由低变高时，提醒当前管理员修改自己的密码
	 */
	public function alert_reset_pwd(){
		$this->load->view('public/popup/alertresetpwd.php');
	}

	/**
	 * 显示提醒重置个人密码的页面
	 */
	public function showTempPWD(){
		$this->load->view('password/resetpwd.php');
	}
	
	/**
	 * 重置密码，临时
	 * @author hongliang.cao@quanshi.com 
	 */
	public function resetPasswordTmpPage(){
		$this->load->view("password/resetpwdtmp.php");
	}
	
	/**
	 * 重置密码ajax，临时
	 * @author hongliang.cao@quanshi.com
	 */
	public function resetPasswordTmp(){
		$resetType 		= intval($this->input->post('resetType', true));//重置类型 2-根据组织id  1-根据login_name
		$loginNameStr   = trim($this->input->post('loginName', true), ', ');
		$orgId     = intval($this->input->post('orgId', true));
		$password  = trim($this->input->post('password', true));
		
		//验证数据格式
		if(!in_array($resetType, array(1,2))){
			return_json(-1,'invalid param');
		}
		
		
		$this->load->library('umsLib', '', 'ums');
		$userIds = array();
		if($resetType == 1){//根据login_name重置密码
			//验证
			if(empty($loginNameStr) || empty($password)){
				return_json(-1,'登录名或密码不能为空！');
			}
			
			//判断所有用户是否属于当前站点
			$loginNameArr = explode(',', $loginNameStr);
			$invalidLoginNameArr = array();
			if(!empty($loginNameArr)){
				foreach($loginNameArr as $loginName){
					$userInfo = $this->ums->getUserByLoginName($loginName);
					if(empty($userInfo)){
						$invalidLoginNameArr[] = $loginName;
					}else{
						$userIds[] = $userInfo['id'];
					}
				}
			}
			
			if(!empty($invalidLoginNameArr)){
				return_json(-1,'发现无效的登录名：-->'.var_export($invalidLoginNameArr, true));
			}	
		}
		
		if($resetType == 2){//根据组织ID重置密码
			//验证
			if(empty($orgId) || empty($password)){
				return_json(-1,'组织ID或密码不能为空！');
			}
			
			//获取所有组织的id
			$orgIds = array();
			$orgs = $this->ums->getOrganization($orgId, 'subtree', '1,3');
			if(!empty($orgs)){
				$orgIds = array_column($orgs, 'id');
			}
			$orgIds[] = $orgId;
			
			foreach($orgIds as $oid){
				$orgInfo = $this->ums->getOrganizationUsers($oid);
				if(!empty($orgInfo)){
					$userIds = array_merge($userIds, array_column($orgInfo, 'id'));
				}
			}
		}
		
		//重置密码
		log_message('info',"start to reset password, user id is-->".var_export($userIds, true));
		foreach($userIds as $userId){
			$this->ums->resetUserPassword($userId, $password);
		}
			
		//返回成功
		return_json(0, 'success');
	}
	
	/**
	 * 重置用户信息，临时
	 */
	
	public function resetUserInfoTmp(){
		$userId = $this->input->post('userId');
		$loginName = $this->input->post('loginName');
		$phone = $this->input->post('phone');
		$email = $this->input->post('email');
		$firstName = $this->input->post('firstName');
		$lastName  = $this->input->post('lastName');
		
		if(empty($loginName)){
			return_json(-1, "登录名不能为空");
		}
		
		$this->load->library('umsLib', '', 'ums');
		$userInfo = $this->ums->getUserByLoginName($loginName);
		
		if(!empty($email)){
			$userInfo['email'] = $email;
		}
		
		if(!empty($phone)){
			$userInfo['mobileNumber'] = $phone;
		}
		
		if(!empty($firstName) || !empty($lastName)){
			$userInfo['firstName'] = $firstName; 
			$userInfo['lastName'] = $lastName;
			$userInfo['displayName'] = $lastName.$firstName;
		}
		
		
		$this->ums->updateUser($loginName, $userInfo);
		return_json(0, "success");
	}
	
}
