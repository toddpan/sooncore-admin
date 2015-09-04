<?php
class Ldap_internation{
	private $org_name = '';			//组织管理
	private $ldap_syn_setting = '';	//ldap同步设置
	private $link_ladp = '';			//链接LDAP设置
	private $syn_org = '';				//选择同步的组织
	private $set_staff_infor = '';		//指定员工信息
	private $syn_staff_info = '';	//选择同步的员工信息
	private $set_account_rule = '';	//设置帐号规则
	
	private $server_type_label = '';	//服务器类型
	private $link_type_label = '';		//连接方式
	private $server_addr_label = '';	//LDAP服务器地址
	private $server_port_label = '';	//LDAP服务器端口
	private $server_user_label = '';	//LDAP服务器用户名
	private $server_pass_label = '';	//LDAP服务器密码
	private $base_DN_label = '';		//Base DN
	private $objectclasses_label = ''; //objectclasses
	
	private $wait_message = '';		//设置跳转等待信息
	private $abort = '';				//放弃
	private $prev_step = '';			//上一步
	private $next_step = '';			//下一步
	private $chose_syn_struct = '';	//选择要同步的组织结构
	private $chose_staff_label = '';	//请选择代表员工的标签
	private $chose_ldap_message = '';	//请选择对应的LDAP信息
	private $must_staff_label = '';	//必选的员工标签
	private $tags_label = null;		//同步员工信息标签（数组类型）
	private $set_uc_account = '';	//请设置蜜蜂账号
	private $use_email_label = '';		//使用邮箱作为蜜蜂帐号
	private $use_prefix_label = '';	//指定统一的标签作为帐号前缀
	private $chose_label = '';			//选择标签
	private $assure	= '';			//确定
	private $cancel = '';			//取消
	private $no_rule = '';				//无规则
	private $add_rule = '';			//增加规则
	private $save_set = '';			//保存设置
	
	private $message0 = '';			//请选择
	private $message1 = '';			//同步后，如果在 LDAP 找不到用户信息立即停用并删除
	private $message2 = '';			//请输入不用开通蜜蜂帐号的例外规则
	private $message3 = '';			//您可以写入这样一个规则 OU=labourer
	
	public function __construct(){
		parent::__construct();
	}
	
	public function setAttribute(){
		$this->lang->load('ldap', 'chinese');
		
		$org_name 				= $this->lang->line('org_name');
		$link_ladp 				= $this->lang->line('link_ladp');
		$syn_org 				= $this->lang->line('syn_org');
		$set_staff_infor 		= $this->lang->line('set_staff_infor');
		$syn_staff_info 		= $this->lang->line('syn_staff_info');
		$set_account_rule 		= $this->lang->line('set_account_rule');
		
		$server_type_label 		= $this->lang->line('server_type_label');
		$link_type_label		= $this->lang->line('link_type_label');
		$server_addr_label 		= $this->lang->line('server_addr_label');
		$server_port_label 		= $this->lang->line('server_port_label');
		$server_user_label 		= $this->lang->line('server_user_label');
		$server_pass_label 		= $this->lang->line('server_pass_label');
		$base_DN_label 			= $this->lang->line('base_DN_label');
		$objectclasses_label 	= $this->lang->line('objectclasses_label');
		
		$wait_message 			= $this->lang->line('wait_message');
		$abort 					= $this->lang->line('abort');
		$prev_step 				= $this->lang->line('prev_step');
		$next_step 				= $this->lang->line('next_step');
		$chose_syn_struct 		= $this->lang->line('chose_syn_struct');
		$chose_staff_label 		= $this->lang->line('chose_staff_label');
		$chose_ldap_message 	= $this->lang->line('chose_ldap_message');
		$must_staff_label 		= $this->lang->line('must_staff_label');
		$set_uc_account 		= $this->lang->line('set_uc_account');
		$use_email_label 		= $this->lang->line('use_email_label');
		$use_prefix_label 		= $this->lang->line('use_prefix_label');
		$chose_label 			= $this->lang->line('chose_label');
		$assure 				= $this->lang->line('assure');
		$cancel 				= $this->lang->line('cancel');
		$no_rule 				= $this->lang->line('no_rule');
		$add_rule 				= $this->lang->line('add_rule');
		$org_name = $this->lang->line('org_name');
		$org_name = $this->lang->line('org_name');
		$org_name = $this->lang->line('org_name');
		$org_name = $this->lang->line('org_name');
		$org_name = $this->lang->line('org_name');
		$org_name = $this->lang->line('org_name');
	}
	
	public function getAttribute(){
		
	}
}