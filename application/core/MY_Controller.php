<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @abstract 总控制器
 * @author 		Bai Xue <xue.bai_2@quanshi.com>
 * @copyright 	Copyright (c) UC
 * @version 	v1.0
 */
class UC_Controller extends CI_Controller {
	
	// 系统变量
	public $web_url_arr;	// 当前运行页面的url信息
	public $web_root_url;	// 站点根url，如http://localhost/ucadmin/
	public $p_url_host;		// 当前域的host，如localhost或test.quanshi.com
	public $session_id;		// UCCServer分配的session_id
	public $p_account;		// 账号
	public $p_display_name; // 姓名
	public $p_site_id;		// 站点id
	public $p_stie_domain;	// 站点域名domain
	public $p_company_type; // 企业类型：0、单一企业；1、集中管理；2、分散管理
	public $p_org_id;		// 组织id
	public $p_user_id;		// 管理员id，即userId
	public $p_account_id; 	// 分账id，即accountId
	public $p_type;			// 管理员类型：1、总公司管理员；2、分公司管理员；3、生态企业管理员；0、其它
	public $p_role_id;		// 角色Id
	public $p_admin_role_id;// uc_user_admin_role表中的主键
	public $p_super_admin_id; // 总管理者Id
	public $p_client_ip;	// 客户端ip
	public $p_org_type;		// 组织类型
	public $p_org_nodeCode; // 根节点的组织Id串
	public $p_customer_code;// 客户编码
	public $p_contract_id;	// 合同Id
	public $p_session_id;
	public $p_is_ldap;
	public $p_privilege_ids;// 权限数组
	public $p_has_login;	// 验证是否已经登陆过
	public $p_sys_arr;		// 常用参数数组
	
	/**
	 * @abstract 构造方法
	 */
	public function __construct() {
		parent::__construct();
		
		// 载入公共方法辅助函数
		$this->load->helper('my_publicfun');
		
		$this->web_url_arr 	= get_url();	// 当前运行页面的url信息
		$this->web_root_url = base_url();	// 站点根url，如http://localhost/ucadmin/
		$this->p_url_host 	= arr_unbound_value($this->web_url_arr, 'http_host', 2, ''); // 当前域的host，如localhost或test.quanshi.com
	
		//为的页面分配base_url
		$this->assign('tag_base_url', site_url(''));
	}
	
	/**
	 * @abstract 初始化系统变量
	 */
	public function uc_init_variable() {
		// 初始化各个系统变量
		$this->p_account 		= $this->session->userdata('account');		// 账号
		$this->p_site_id 		= $this->session->userdata('site_id');		// 站点id
		$this->p_stie_domain 	= $this->session->userdata('domain');		// 站点域名domain
		$this->p_company_type 	= $this->session->userdata('companyType');	// 企业类型：0、单一企业；1、集中管理；2、分散管理
		$this->p_org_id 		= $this->session->userdata('org_id');		// 组织id
		$this->p_user_id 		= $this->session->userdata('userid');		// 管理员id，即userId
		$this->p_account_id 	= $this->session->userdata('account_id');	// 分账id，即accountId
		$this->p_type 			= $this->session->userdata('admin_type');	// 管理员类型：1、总公司管理员；2、分公司管理员；3、生态企业管理员；0、其它
		$this->p_role_id 		= $this->session->userdata('role_id');		// 角色Id
		$this->p_admin_role_id  = $this->session->userdata('admin_role_id');// uc_user_admin_role表的主键
		//$this->p_super_admin_id = $this->session->userdata('account');	// 总管理者Id
		$this->p_client_ip 		= $this->session->userdata('client_ip');	// 客户端ip
		$this->p_org_type 		= $this->session->userdata('org_type');		// 组织类型
		$this->p_org_nodeCode 	= $this->session->userdata('nodeCode');		// 根节点的组织Id串
		$this->p_customer_code 	= $this->session->userdata('customerCode');	// 客户编码
		$this->p_contract_id 	= $this->session->userdata('contract_id');	// 合同Id
		$this->p_session_id 	= $this->session->userdata('session_id');
		$this->p_is_ldap 		= $this->session->userdata('is_ldap');
		$this->p_privilege_ids  = $this->session->userdata('uc_privilege_ids');
		$this->p_has_login  = $this->session->userdata('has_login');
		
// 		log_message('info', 'session_id=' . $this->p_session_id);
// 		$cache_arr = cache('get', $this->p_session_id);
// 		log_message('info', 'cache=' . var_export($cache_arr, true));
		
		if(is_empty($this->p_account) || is_empty($this->p_site_id) || is_empty($this->p_stie_domain) || is_empty($this->p_company_type) || is_empty($this->p_org_id)){
			log_message('info', 'the session is out of date1.'.$this->p_account);
			log_message('info', 'the session is out of date1.'.$this->p_site_id);
			log_message('info', 'the session is out of date1.'.$this->p_stie_domain);
			log_message('info', 'the session is out of date1.'.$this->p_company_type);
			log_message('info', 'the session is out of date1.'.$this->p_org_id);
			gotourl(41,'',site_url('login/loginPage'),array());
		}
		
		if(is_empty($this->p_user_id) || is_empty($this->p_type) || is_empty($this->p_role_id) || is_empty($this->p_client_ip)){
			log_message('info', 'the session is out of date2.');
			gotourl(41,'',site_url('login/loginPage'),array());
		}
		
		if(is_empty($this->p_org_nodeCode) || is_empty($this->p_customer_code) || is_empty($this->p_contract_id) || is_empty($this->p_is_ldap) || is_empty($this->p_account_id)){
			log_message('info', 'the session is out of date3.');
			gotourl(41,'',site_url('login/loginPage'),array());
		}
		
// 		if(isemptyArray($this->p_privilege_ids)){
// 			log_message('info', 'the session is out of date3.');
// 			gotourl(41,'',site_url('login/loginPage'),array());
// 		}
		
// 		if(is_empty($this->p_has_login)){
// 			log_message('info', 'the session is out of date4.');
// 			gotourl(41,'',site_url('login/loginPage'),array());
// 		}
		
		$this->p_sys_arr 		= array(// 常用参数数组
				'customerCode' 		=> $this->p_customer_code,	// 客户编码
				'siteID' 			=> $this->p_site_id,		// 站点id
				'accountId'			=> $this->p_account_id,		// 分账id，即accountId，注意：如果有用户，则是用户自己的
				'siteURL' 			=> $this->p_stie_domain,	// 站点域名domain
				'contractId' 		=> $this->p_contract_id,	// 合同id
				'operate_id' 		=> $this->p_user_id,		// 操作发起人用户ID
				'client_ip' 		=> $this->p_client_ip,		// 客户端ip
				'oper_account' 		=> $this->p_account,		// 操作帐号
				'oper_display_name' => $this->p_display_name,	// 操作姓名
				'orgID' 			=> $this->p_org_id,			// 组织id
		);
				
		$this->initGlobalVariables();		
	}
	
	/**
	 * smarty模板为页面分配变量
	 * @param unknown $key
	 * @param unknown $val
	 */
	public function assign($key,$val = null) {
		return $this->template->assign($key,$val);
		
	}
	
	//smarty模板显示页面
	public function display($html) {
		return $this->template->display($html);
	}
	
	//smarty模板，获取解析后的页面内容
	public function fetch($html){
		return $this->template->fetch($html);
	}
	
	

	/*
	 * 定义全局变量初始化函数
	 */
	private function initGlobalVariables(){
		$this->setSiteConfig();
	}
	
	/*
	 * 设置站点配置信息
	 */
	private function setSiteConfig(){
		$info = $this->session->userdata('siteConfig');
		
		if(empty($info)){
			$info = array();
			
			$info['siteType'] = $this->p_is_ldap != 0 ? 1 : 0;
			
			$this->load->model('uc_site_config_model', 'site_config');
			$info['importType'] = $this->site_config->getImportType($this->p_site_id);
			
			$this->session->set_userdata('siteConfig', $info);
		}
		
		$this->siteConfig = $info;
	}
	
	protected function redirectToMainPage(){
		echo ('<script type="text/javascript">window.location = "main/index";</script>');
	}
}


/**
 * @abstract前台父控制器：uc后台不用登陆就可以运行页面的控制器，如如登陆页面、找回密码页面
 */
class Web_Controller extends UC_Controller{
	public function __construct(){
		parent::__construct();
		define('DB_RESOURCE','default');//当前控制器连接的数据库
	}

}


/**
 *@abstract 其它控制器：其它不用登陆就可以运行的页面
 */
class Run_Controller extends UC_Controller{
	public function __construct(){
		parent::__construct();
		define('DB_RESOURCE','default');//当前控制器连接的数据库
	}

}


/**
 * @abstract 后台父控制器：UC后台只有管理员登陆，才可以进行操作页面的控制器，如组织管理、标签设置等
 */
class Admin_Controller extends UC_Controller{

	public function __construct(){
		parent::__construct();
		define('DB_RESOURCE','default');//当前控制器连接的数据库
		//$this->load->library('session');
		
		
		// 权限验证以及系统变量初始化
		$this->uc_init_variable();
	}
}


/**
 * @abstract 后台线程扫描控制器[自动扫描];经测试扫描线程并不能获得系统的一些值，如$_SERVER，所以扫描线程代码要小心
 */
class Thread_Controller extends UC_Controller{
	
	public function __construct(){
		parent::__construct();
		define('DB_RESOURCE','default');//当前控制器连接的数据库
		ini_set("max_execution_time", "0"); //数值 0 表示没有执行时间的限制
		set_time_limit(0);//如果设置为零，没有时间方面的限制
	}
}


/**
 * 域分配父控制器[只给域分配专用]
 */
class Domain_Controller extends UC_Controller{
	
	public function __construct(){
		parent::__construct();
		define('DB_RESOURCE','domain');//当前控制器连接的数据库
		ini_set("max_execution_time", "1800"); //数值 0 表示没有执行时间的限制
		set_time_limit(0);//如果设置为零，没有时间方面的限制
	}
}


/**
 * @abstract mss邮件保存父控制器[只给域分配专用]
 */
class Mss_Controller extends UC_Controller{
	
	public function __construct(){
		parent::__construct();
		define('DB_RESOURCE','mss');//当前控制器连接的数据库
		ini_set("max_execution_time", "0"); //数值 0 表示没有执行时间的限制
		set_time_limit(0);//如果设置为零，没有时间方面的限制
	}
}


/**
 * @abstract 邮件保存父控制器[只给域分配专用]
 */
class Email_Controller extends UC_Controller{
	public function __construct(){
		parent::__construct();
		define('DB_RESOURCE', 'email');	// 当前控制器连接的数据库
		ini_set("max_execution_time", "0"); // 数值 0 表示没有执行时间的限制
		set_time_limit(0);	// 如果设置为零，没有时间方面的限制
	}

}


class Task_Controller extends UC_Controller{
	public function __construct(){
		parent::__construct();
		define('DB_RESOURCE','default');// 当前控制器连接的数据库
		@set_time_limit(0);
	}
}


class Api_Controller extends CI_Controller{
	public function __construct(){
		parent::__construct();
		define('DB_RESOURCE','default');//当前控制器连接的数据库

	}
}