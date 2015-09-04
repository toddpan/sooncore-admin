<?php

class Xmlimport extends Task_Controller {
	
	private $siteInfo = array();
 	private $UPLOAD_PATH = '/data/ucadmin_data/xmlimport/';
// 	private $UPLOAD_PATH = '../data/xmlImport/xml';
	
	public function __construct()
	{
		parent::__construct();
		parent::uc_init_variable();
// 		$this->UPLOAD_PATH = APPPATH . 'controllers/_test_xml_import/uploads/';
	}
	
	/**
	 * xml上传通用接口
	 */
	public function upload() {		
		if (! $this->isValidUser()) {
			echo response_json(array('result'=>"The loginname is invalid."));
			return;
		}
				
		$this->fileupload();
	}
	
	/**
	 * 提供上传功能页面，以便客户测试使用
	 */
	public function uploadpage() {
		$this->load->view('xmlimport/uploadform.php');
	}
	
	/**
	 * 提供管理中心设置页面上传功能，以便用户上传使用
	 */
	public function uploaduc() {
		if (! $this->isLoginable()) {
			echo response_json(array('result'=>"The loginname is not loginin."));
			return;
		} 
		
		$this->fileupload();
	}
	
	/**
	 * 根据loginname和password判断用户是否为合法用户
	 * @return boolean
	 */
	private function isValidUser() {	
		$user_name = trim($this->input->post('loginname', true)); 	// 用户名
		$user_pwd  = trim($this->input->post('password', true));	// 密码
		
		if (is_empty($user_name) || is_empty($user_pwd)) {
			log_message('info', 'The auto_upload_xml request has empty loginname or password.');
			return false;
		}
		
		// 根据login_name和password确定用户是否是合法用户
		$this->load->library('API', '', 'API');
		$data_arr = array(
				'user_account' 	=> $user_name,
				'password' 		=> $user_pwd,
				'client_type' 	=> 4,
				'client_info'	=> json_encode(array('mac'=> MAC_ADDR))// Mac地址
		);
		$ucc_user_arr = $this->API->UCCServerAPI(http_build_query($data_arr), 1);
		// 判断从UCCServer登录接口返回的数据是否为空
		$ucc_user_arr['data'] 	= isset($ucc_user_arr['data'])?$ucc_user_arr['data']:0;
		$ucc_user_arr 			= arr_unbound_value($ucc_user_arr['data'], '0', 1, array());
		$user_id 				= isset($ucc_user_arr['user_id'])?$ucc_user_arr['user_id']:'';// 组织id
		if(isemptyArray($ucc_user_arr) || bn_is_empty($user_id)) {
			log_message('info', 'The auto_upload_xml request has error loginname or password.');
			return false;
		}
		
		// 判断当前账号是不是管理员账号并且是否被停用
		$this->load->model('UC_User_Admin_Role_Model'); // 管理员角色
		$condition_arr = array(
				'user_id' 	=> $user_id,
				'state' 	=> 1
		);
		$uc_admin_arr = $this->UC_User_Admin_Role_Model->getAdminByUseridAndState($condition_arr);
		$uc_role_id 		= isset($uc_admin_arr['role_id']) ? $uc_admin_arr['role_id'] : ''; 	// 角色id
		$uc_state 			= isset($uc_admin_arr['state']) ? $uc_admin_arr['state'] : '';		// 状态:0、停用；1、启用
		
		// 根据login_name获得site_id以及根组织org_id
		$this->load->model('uc_user_admin_model');
		$user_info_arr = $this->uc_user_admin_model->getAdminByUseridAndState(array('userID' => $user_id));
		$site_id 	= isset($user_info_arr['siteID']) ? $user_info_arr['siteID'] : 0;
		$uc_org_id 	= isset($user_info_arr['orgID']) ? $user_info_arr['orgID'] : '';// 组织id
		$uc_type 	= isset($user_info_arr['type']) ? $user_info_arr['type'] : '';	// 类型：1、总公司管理员；2、分公司管理员；3、生态企业管理员；0、其它
		
		if (isemptyArray($uc_admin_arr)||is_empty($uc_role_id)||is_empty($uc_org_id)||is_empty($uc_type)||is_empty($uc_state)){
			log_message('info', 'The auto_upload_xml request is not the administrator user.');
			return false;
		}
		
		$this->siteInfo['user_id'] = $user_id;
		$this->siteInfo['site_id'] = $site_id;
		$this->siteInfo['org_id']  = $uc_org_id;
		
		return true;
	}
	
	/**
	 * 判断是否已经正常登录
	 * @return boolean
	 */
	private function isLoginable() {
		if(is_empty($this->p_account) || 
		   is_empty($this->p_site_id) ||
		   is_empty($this->p_stie_domain) || 
		   is_empty($this->p_company_type) || 
		   is_empty($this->p_org_id) ||
		   is_empty($this->p_user_id) || 
		   is_empty($this->p_type) || 
		   is_empty($this->p_role_id) || 
		   is_empty($this->p_client_ip) || 
		   is_empty($this->p_org_type) ||
		   is_empty($this->p_org_nodeCode) || 
		   is_empty($this->p_customer_code) || 
		   is_empty($this->p_contract_id) || 
		   is_empty($this->p_is_ldap) ||
		   is_empty($this->p_account_id)) {
			return false;   	
		} else {
			$this->siteInfo['site_id'] = $this->p_site_id;
			return true;
		}		
	}
	
	/**
	 * 使用CI文件上传插件上传xml文件的代码
	 */
	private function fileupload() {
		$config['allowed_types'] = 'xml|xsl';
		$config['max_size'] = '8388608';
		$config['file_type'] = 'text/xml';
		$config['is_image'] = false;
		
		$file_type = $this->input->post('type', true); //文件类型，xml还是xsl
		
		if (! is_empty($this->siteInfo['site_id'])) {
				
			if ($file_type == 'xml') {
		
				$config['file_name'] = $this->siteInfo['site_id'] . '_' . time() . '.xml';
				$upload_path = $this->UPLOAD_PATH . 'xml/upload/' . $this->siteInfo['site_id'] . '/';
		
			} elseif ($file_type == 'xsl') {
		
				$config['file_name'] = $this->siteInfo['site_id'] . '_' . time() . '.xsl';
				$upload_path = $this->UPLOAD_PATH . 'xslt/upload/' . $this->siteInfo['site_id'] . '/';
			} else {
				echo response_json(array('result'=>"Upload has not specify the import file type."));
				return;
			}
				
		} else {
			echo response_json(array('result'=>"Upload has error because could not get site_id."));
			return;
		}
		
		if (! is_dir($upload_path)) {
			mkdir($upload_path);
		}
		$config['upload_path'] = $upload_path;
		
		$this->load->library('upload', $config);
		if ( ! $this->upload->do_upload())
		{
			$result = array('result' => $this->upload->display_errors());
			echo response_json($result);
			return;
		}
		else
		{
			$result = array('result' => $this->upload->data());
			echo response_json(array(
					'result'      => true,
					'client_name' => $result['client_name'],
					'file_type'   => $result['file_type']
			));
			return;
		}
	}
}