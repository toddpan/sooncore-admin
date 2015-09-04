<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * test
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
class Test extends Admin_Controller {
	public function __construct() {
		parent::__construct();
	}

	//短信测试
	public function msgTest(){
		log_message('info', 'start to send message to ');
			
		$this->lang->load('msg_tpl', 'chinese');
		$msg_tpl = $this->lang->line('msg_tpl_account_create');
		$content = sprintf($msg_tpl, "zhangsan@quanshi.com", "11111111", APP_DOWNLOAD_SHORT_LINK);
		
		$this->load->library("UccLib", '', 'ucc');
		$this->ucc->sendMobileMsg(61473626, $content, "15129209164");
		log_message('info', 'sending message to user finished.');
	}
	
	//邮件测试
	public function emailTest(){
		log_message('info', 'start to send email');
		$template_var = array(
				'user_name'=>"张三",
				'login_name'=>"zhangsan@quanshi.com",
				'email'=>"hongliang.cao@quanshi.com",
				'password'=>"11111111",
				'cor_name'=>'全时',
		);
		$this->load->library('MssLib', '', 'mss');
		$this->mss->save_mail($template_var, USER_CREATE_MAIL);//MANAGER_CREATE_MAIL
		$this->mss->save_mail($template_var, MANAGER_CREATE_MAIL);
		log_message('info', 'sending email to user finished.');
	}
	
	//ums接口测试
	public function umsTest(){
		$this->load->library('UmsLib', '', 'ums');
		
		/*
		//获取用户从上级组织
		$this->ums->getOrganizationsByUserId(61500252);
		*/
		/*
		//批量修改用户和组织
		$customerCode = '003530';
		$user = array(
			'loginName'=>'goodnight629@gnetis11.quanshi.com',
			//'id'=>61500252,
			'organizationName'=>'accountopentest',
			//'obj'=>'hello'
		);
		$info = array($user);
		$this->ums->updateUserAndOrganization($customerCode, $info);
		
		*/
		/*
		$org_infos = array (
			0 =>array (
				'name' => '齐天大圣/孙悟空',
				'customercode' => '003530',
				'type' => 3,
				'parentId' => '9',
				
		  ),
		);
		
		$this->ums->createOrganizations($org_infos);
		*/
		/*
		$userInfo = array(
			'loginName' => 'sunday_2323@quanshi.com',
			//'firstName' => '',
			'displayName' => '星期天',
			//'lastName' => ' ',
			'email' => 'hongliang.cao45454545@quanshi.com',
		);
		$this->ums->addNewUserToOrg($userInfo, 9);
		*/
		//修改用户
		
		$userInfo = array(
			'id'=>61656268,
			'loginName' => 'sunday_2323@quanshi.com',
			//'firstName' => '',
			'displayName' => '星期天2',
			'organizationId'=>10,
			//'lastName' => ' ',
			'email' => 'hongliang.cao45454545@quanshi.com',
		);
		$this->ums->updateUserInfo($userInfo);
		
		/*
		//验证用户信息是否存在
		$loginName = "hongliang.cao@quanshi.com";
		$email	   = "";
		$phone     = "";
		
		$ret = $this->ums->checkUserVerified($email, $phone);
		print_r($ret);
		*/
		
		//ums用户更新接口
		/*
		$userInfo = array(
			'id'=>61500252,
			'email'=>'hongliang.cao3@quanshi.com',
			'mailVerified' => true,
		);
		$this->ums->updateUserInfo($userInfo);
		*/
		echo 'ok';
	}
	
	public function test_save_ldap(){
	// 在uc_site表更新is_ldap字段
			$this->load->library('UcadminLib', '', 'ucadmin');
			$res = $this->ucadmin->swicth_isldap($this->p_site_id, $auth_type);
			if(!$res){
				echo 'Update isLDAP from uc_site faild.';
			}
	}
}