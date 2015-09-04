<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 系统设置
 * @filesource 	system.php
 * @author 		caohongliang <hongliang.cao@quanshi.com>
 * @copyright 	Copyright (c) UC
 * @version 	v1.0
 */
class SystemSet extends Admin_Controller{
	public function __construct() {
		parent::__construct();
		$this->load->helper('my_publicfun');
		$this->load->helper('language');
		$this->lang->load('admin', 'chinese');
		$this->load->library('UmsLib', '', 'ums');
	}
	
	/**
	 * 首页
	 */
	public function index(){
		$this->company();
	}
	
	/**
	 * 企业信息页面
	 */
	public function company(){
		$data = array();
		//获取logo地址
		$this->load->model('uc_site_model', 'site');
		$data['url'] = is_empty($_url = $this->site->getLogoUrl($this->p_site_id)) ? base_url('public/images/bisinessLogo.jpg') : $this->_generateUrl($_url);
		//从boss获取企业信息
		$this->load->library('BossLib', '', 'boss');
		$info = $this->boss->getCustomerInfo($this->p_customer_code);
		if(!empty($info)){
			$data['name']		= isset($info['name']) ? $info['name'] : '';//公司名称
			$data['address']	= isset($info['address']) ? $info['address'] : '';//公司地址
			$data['country']	= isset($info['country']) ? $info['country'] : '';//国家
			$data['city']		= isset($info['city']) ? $info['city'] : '';//城市
			$data['phone']		= isset($info['phone']) ? $info['phone'] : '';//电话
			$data['fax']		= isset($info['fax']) ? $info['fax'] : '';//传真
			$data['website']	= isset($info['website']) ? $info['website'] : '';//公司网站
			
			$data['f_name']		= isset($info['financialContacts']['name']) ? $info['financialContacts']['name'] : '';//财务联系人姓名
			$data['f_email']	= isset($info['financialContacts']['email']) ? $info['financialContacts']['email'] : '';//财务联系人邮箱
			$data['f_mobile']	= isset($info['financialContacts']['mobilePhone']) ? $info['financialContacts']['mobilePhone'] : '';//财务联系人手机
			$data['f_tel']		= isset($info['financialContacts']['telPhone']) ? $info['financialContacts']['telPhone'] : '';//财务联系人电话
			
			$data['m_name']		= isset($info['mainContacts']['name']) ? $info['mainContacts']['name'] : '';//主要联系人姓名
			$data['m_email']	= isset($info['mainContacts']['email']) ? $info['mainContacts']['email'] : '';//主要联系人邮箱
			$data['m_mobile']	= isset($info['mainContacts']['mobilePhone']) ? $info['mainContacts']['mobilePhone'] : '';//主要联系人手机
			$data['m_tel']		= isset($info['mainContacts']['telPhone']) ? $info['mainContacts']['telPhone'] : '';//主要联系人电话
		}
		
		
		$this->load->model('uc_site_model');
		$res_arr = $this->uc_site_model->getCorNameBySiteId($this->p_site_id);
		$corName = isset($res_arr['corName']) ? $res_arr['corName'] : '';	// 公司简称
		$isLdap  = isset($res_arr['isLDAP']) ? $res_arr['isLDAP'] : '';		// 认证方式
		$data['corName'] = $corName;
		$data['isLDAP']  = $isLdap;
		
		//TODO接收提醒邮箱、系统通知
		$data['email'] = 'iwanttoplayagamewithyou@gg.com';//接收提醒邮箱
		$data['system_notice'] = true;//系统通知开关	
		
		$this->load->view('setsystem/cor_info.php',array('data'=>$data));
	}
	
// 	public function get_server_infos(){
// 		// 获得服务器组信息
// 		$this->lang->load('ldap', 'chinese');
// 		$servertypes = array(
// 				lang('servertype0'),lang('servertype1'),lang('servertype2')//,lang('servertype3'),lang('servertype4')
// 		);
// // 		$protocols = array(
// // 				lang('authtype_name0'),lang('authtype_name1'),lang('authtype_name2')
// // 		);

// //		$server_info = array(
// //			'servertypes' => $servertypes,
// //			'protocols' => $protocols
// //		);
// //		var_dump($servertypes);
// 		return $servertypes;
// 	}
	
	public function setLogoDialog(){
		//获取缩放后的原图和logo
		if($orig = $this->_getOrigLogo()){
			$ori_logo    =  $orig['full_name'];//原图
			$mid_logo    =  $orig['file_name'].'_mid.'.$orig['ext'];//中图
			$resize_logo =  $orig['file_name'].'_min.'.$orig['ext'];//剪裁并缩放后的图片
			
			$o_logo = $this->_generateUrl($mid_logo);
			$logo   = $this->_generateUrl($resize_logo);
		}else{
			$o_logo = base_url('public/images/clipLogo.jpg');;
			$logo   = base_url('public/images/bisinessLogo.jpg');;
		}
		
		$data = array('o_logo'=>$o_logo, 'logo'=>$logo);
		$this->load->view('public/popup/setlogo.php', $data);
	}
	
	/**
	 * logo上传
	 */
	public function logoUpload(){
		$props = array(
			'upload_path'=>LOGO_UPLOAD_PATH,
			'allowed_types'=>LOGO_ALLOW_TYPES,
			'max_size'=>LOGO_MAX_SIZE,
			'file_name'=>'logo_'.$this->p_customer_code.'_'.$this->p_site_id,
			'overwrite'=>true,//一个站点只保存一张原图
		);
		$this->load->library('upload', $props);
		if(!$this->upload->do_upload('logo')){
			return_json(10000, json_encode($this->upload->display_errors()));
		}else{
			$d = $this->upload->data();
			//for some reason , here should resize
			$mid_logo = 'logo_'.$this->p_customer_code.'_'.$this->p_site_id.'_mid'.$d['file_ext'];
			$props = array(
					'image_library'=>'gd2',
					'quality'=>'100%',
					'width'=>LOGO_MID_WIDTH,
					'height'=>LOGO_MID_HEIGHT,
					'maintain_ratio'=>false,
					'master_dim'=>'auto',
					'source_image'=>LOGO_UPLOAD_PATH.$d['file_name'],
					'new_image'=>LOGO_UPLOAD_PATH.$mid_logo,
			);
			$this->load->library('image_lib', $props);
			if( !$this->image_lib->resize()){
				return_json(20000, json_encode($this->image_lib->display_errors()));
			}
			
			$url = $this->_generateUrl($mid_logo);
			return_json(0,'success',array('src'=>$url));
		}
	}
	
	/**
	 * logo剪裁
	 */
	public function logoCrop(){
		//获取参数
		$x = intval($this->input->get_post('x',true));
		$y = intval($this->input->get_post('y',true));
		$w = intval($this->input->get_post('w',true));//选取宽度
		$h = intval($this->input->get_post('h',true));//选取高度
		
		try{
			//检查参数
			if(($w <= 0) || ($h <= 0)){
				throw new Exception('invalid param');
			}
			//裁剪logo
			if(!$orig = $this->_getOrigLogo()){
				throw new Exception('Your must upload a image at first!');
			}
			$ori_logo    =  $orig['full_name'];//原图
			$mid_logo    =  $orig['file_name'].'_mid.'.$orig['ext'];//中图
			$crop_logo   =  $orig['file_name'].'_crop.'.$orig['ext'];//剪裁后的图片
			$resize_logo =  $orig['file_name'].'_min.'.$orig['ext'];//剪裁并缩放后的图片
			
			$props = array(
				'image_library'=>'gd2',
				'quality'=>'100%',
				'x_axis'=>$x,
				'y_axis'=>$y,
				'width'=>$w,
				'height'=>$h,
				'maintain_ratio'=>false,
				'source_image'=>LOGO_UPLOAD_PATH.$mid_logo,
				'new_image'=>LOGO_UPLOAD_PATH.$crop_logo,
			);
			
			$this->load->library('image_lib', $props);
			if ( ! $this->image_lib->crop()){
				throw new Exception($this->image_lib->display_errors());
			}
			//缩放
			$props['width'] = LOGO_WIDTH;
			$props['height'] = LOGO_HEIGHT;
			$props['source_image'] = LOGO_UPLOAD_PATH.$crop_logo;
			$props['new_image']    = LOGO_UPLOAD_PATH.$resize_logo;
			$this->image_lib->initialize($props);
			if( !$this->image_lib->resize()){
				throw new Exception($this->image_lib->display_errors());
			}
			$url = $this->_generateUrl($resize_logo);
			//将logo名称存入数据库
			$this->load->model('uc_site_model', 'site');
			if(! $this->site->saveLogoUrl($this->p_site_id, $resize_logo)){
				throw new Exception('save logo url to database fail!');	
			}
			//返回url
			return_json(0,'',array('src'=>$url));
		}catch(Exception $e){
            log_message('error',$e->getMessage());
            return_json(10000,$e->getMessage());
		}
	}
	
	/**
	 * 获取站点logo原图信息，包括文件名称和扩展
	 */
	private function _getOrigLogo(){
		$this->load->helper('file');
		$fn = 'logo_'.$this->p_customer_code.'_'.$this->p_site_id;
		$fs = get_filenames(LOGO_UPLOAD_PATH);
		foreach($fs as $f){
			if( 0 === strpos($f, $fn)){
				return array('ext'=>end(explode('.', $f)), 'file_name'=>$fn, 'full_name'=>$f);
			}
		}
		return false;
	}
	
	/**
	 * 生成图片地址
	 * @param str $file_name 图片名称
	 */
	private function _generateUrl($file_name){
		//$_u = str_replace(FCPATH, '', realpath(LOGO_DOWNLOAD_URL));
		//$_u = str_replace('\\','/',$_u);
		//return base_url(LOGO_DOWNLOAD_URL.$file_name);
		return LOGO_DOWNLOAD_URL.$file_name;
	}
	
	/**
	 * 修改公司简称
	 */
	public function update_cor_name(){
		$cor_name = $this->input->post('corName', true);
		log_message('info', 'Into method update_cor_name input ---> $corName = ' .$cor_name);
		
		$this->load->model('uc_site_model');
		$where_arr = array(
			'siteID' => $this->p_site_id
		);
		$updata_arr = array(
			'corName' => $cor_name
		);
		$res = $this->uc_site_model->update_value($where_arr, $updata_arr);
		
		if(!$res){
			return_json(COMMON_FAILURE, $this->lang->line('fail'), array());
		}
		
		return_json(COMMON_SUCCESS, $this->lang->line('success'), array()); 
	}
	
	/**
	 * 获得认证方式
	 * 获得账号导入方式
	 * @author Bai Xue <xue.bai_2@quanshi.com>
	 */
	public function get_isldap(){
		
		// 从uc_site表获得认证方式
		$this->load->model('uc_site_model');
		$res_arr = $this->uc_site_model->getInfosBySiteId($this->p_site_id);
		$isLdap  = isset($res_arr['isLDAP']) ? $res_arr['isLDAP'] : '';		// 认证方式
		
		// 从uc_site_config表获得账号导入方式
		$this->load->model('uc_site_config_model');
			 
		$importmode = $this->uc_site_config_model->getValue('ACCOUNT_AUTHENTICATION_TYPE', $this->p_site_id, 'DATA_IMPORT_TYPE');
		
		$import_info = array();
		
		// 设置默认值
		if(! $importmode) {
			if($isLdap == IS_LDAP) {
				$importmode = 'ldap';
				$import_arr = array(
						array(
								'site_id' 		=> $this->p_site_id,
								'category' 		=> 'ACCOUNT_AUTHENTICATION_TYPE',
								'key' 			=> 'DATA_IMPORT_TYPE',
								'value' 		=> $importmode,
								'create_time' 	=> date("Y-m-d H:i:s")
						)
				);				
				$this->uc_site_config_model->setValues($import_arr);
			} else {
				$importmode = 'excel';
				$import_arr = array(
						array(
								'site_id' 		=> $this->p_site_id,
								'category' 		=> 'ACCOUNT_AUTHENTICATION_TYPE',
								'key' 			=> 'DATA_IMPORT_TYPE',
								'value' 		=> $importmode,
								'create_time' 	=> date("Y-m-d H:i:s")
						)
				);				
				$this->uc_site_config_model->setValues($import_arr);
			}
		}elseif ($importmode == 'xml') {
			$deleable = $this->uc_site_config_model->getValue('ACCOUNT_AUTHENTICATION_TYPE', $this->p_site_id, 'handle_invalidation_user_type');
			$deleable = empty($deleable) ? 'disable' : $deleable;
			$xmlimporturl = $this->uc_site_config_model->getValue('ACCOUNT_AUTHENTICATION_TYPE', $this->p_site_id, 'xmlurl');
			$xmlimportformat = $this->uc_site_config_model->getValue('ACCOUNT_AUTHENTICATION_TYPE', $this->p_site_id, 'formaturl');			
		} 
		
		$import_info = array(
				'DATA_IMPORT_TYPE'                      => $importmode,
				'handle_invalidation_user_type'         => $deleable,
				'xmlurl'                                => $xmlimporturl,
				'formaturl'                             => $xmlimportformat
		);
				
		// LDAP认证
		// 没有配置过LDAP，从配置文件中获得配置信息
		$this->config->load('ldap_config', true);
		$this->_servertype_arr	= $this->config->item('_servertype_arr','ldap_config');
		$this->_protocol_arr	= $this->config->item('_protocol_arr','ldap_config');
		$tmp_server_info = array(
					'servertype' => ($this->_servertype_arr[1] == 'MS_AD') ? 'Microsoft Active Directory' : $this->_servertype_arr[1],// 服务器类型
					'protocol'	 => $this->_protocol_arr[1],	// 连接方式
					'confName'	 => '', 	// LDAP名字
					'hostname'	 => '', 	// 服务器地址
					'port' 		 => '', 	// 端口号
					'basedn'	 => '', 	// Base DN
					'username'	 => '', 	// 服务器用户名
					'password'	 => '', 	// 密码
					'emailAttribute' => '' 	// mail属性
			);
		if($isLdap == IS_LDAP){
			$res_arr = array();
			$res_arr = $this->get_server_info();
			$server_info = !is_null($res_arr) ? $res_arr['server_info'] : $tmp_server_info;
		}else{
			$server_info = $tmp_server_info;
		}
		
		form_json_msg(COMMON_SUCCESS, '', '', array('isLDAP' => $isLdap, 'server_info' => $server_info, 'import_info' => $import_info));
	}
	
	/**
	 * 保存认证方式
	 * 保存账号导入方式
	 */
	public function save_ldap() {
		log_message('info', 'Into method save_ldap');
		$is_ldap = $this->input->get_post('is_ldap', true);
		
		$import_info = $this->input->get_post('import_info', true);
		$importmode = $import_info['DATA_IMPORT_TYPE'];
		
		// 保存信息到uc_site_config
		$this->load->model('uc_site_config_model');
		
		$import_arr = array();
		
		try {	
			if ($importmode == 'xml') {
				$import_arr = array(
						array(
								'site_id' 		=> $this->p_site_id,
								'category' 		=> 'ACCOUNT_AUTHENTICATION_TYPE',
								'key' 			=> 'DATA_IMPORT_TYPE',
								'value' 		=> $importmode,
								'create_time' 	=> date("Y-m-d H:i:s")
						),
						array(
								'site_id' 		=> $this->p_site_id,
								'category' 		=> 'ACCOUNT_AUTHENTICATION_TYPE',
								'key' 			=> 'xmlurl',
								'value' 		=> $import_info['xmlurl'],
								'create_time' 	=> date("Y-m-d H:i:s")
						),
						array(
								'site_id' 		=> $this->p_site_id,
								'category' 		=> 'ACCOUNT_AUTHENTICATION_TYPE',
								'key' 			=> 'formaturl',
								'value' 		=> $import_info['formaturl'],
								'create_time' 	=> date("Y-m-d H:i:s")
						),
						array(
								'site_id' 		=> $this->p_site_id,
								'category' 		=> 'ACCOUNT_AUTHENTICATION_TYPE',
								'key' 			=> 'handle_invalidation_user_type',
								'value' 		=> $import_info['handle_invalidation_user_type'],
								'create_time' 	=> date("Y-m-d H:i:s")
						)
				);
			}
			
			if ($importmode == 'ldap') {
				$import_arr = array(
						array(
								'site_id' 		=> $this->p_site_id,
								'category' 		=> 'ACCOUNT_AUTHENTICATION_TYPE',
								'key' 			=> 'DATA_IMPORT_TYPE',
								'value' 		=> 'ldap',
								'create_time' 	=> date("Y-m-d H:i:s")
						)
				);
			}
			
			if ($importmode == 'excel') {
				$import_arr = array(
						array(
								'site_id' 		=> $this->p_site_id,
								'category' 		=> 'ACCOUNT_AUTHENTICATION_TYPE',
								'key' 			=> 'DATA_IMPORT_TYPE',
								'value' 		=> 'excel',
								'create_time' 	=> date("Y-m-d H:i:s")
						)
				);
			}
			
			$result = $this->uc_site_config_model->setValues($import_arr);
			
			if(!$result){
				throw new Exception('Save import data from uc_site_config faild.');
			}
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			return_json(COMMON_FAILURE, $this->lang->line('falied'), array());
		}	

		$auth_type = null;
		
		try{
			if($is_ldap == 1){
				$auth_type = 1; // 从普通站点过渡到LDAP站点，需传递$auth_type=1；
				
				// 获得表单提交的LDAP认证参数
				$server_info = $this->input->get_post('server_info', true);
				
				$server_type = ($server_info['servertype'] == 'Microsoft Active Directory') ? 'MS_AD' : $server_info['servertype'];
				
				$ldap_param = array(
					'protocol'				=> $server_info['protocol'], 	// 连接方式
					'confName'				=> $server_info['confName'], 	// LDAP名字
					'serverType'			=> $server_type, 				// 服务器类型
					'customerCode'			=> $this->p_customer_code,		// 客户编码
					'hostname'				=> $server_info['hostname'], 	// 服务器地址
					'authenticationMethod'	=> 'SIMPLE',					// 认证方式
					'port' 					=> $server_info['port'], 		// 端口号
					'basedn'				=> $server_info['basedn'], 		// Base DN
					'username'				=> $server_info['admindn'], 	// 服务器用户名
					'password'				=> $server_info['password'],	// 密码
					'siteLdapConfig'		=> array(
								'siteId'					=> $this->p_site_id,
								'umsOrgId'					=> $this->p_org_id, 
								'enableUserSync'			=> 0,
								'enableOrgSync'				=> 0,
								'enableActivation'			=> 0,
								'enableDeactivation'		=> 0,
								'enableUpdate'				=> 0,
								'enableDeactivationFilter'	=> 0
					),
					'ldapUserMapping' 		=>array(
								'idAttribute' 				=> $server_info['idAttribute'],
								'emailAttribute' 			=> $server_info['emailAttribute']
					)
						
				);
				
				// 判断当前站点是否设置过LDAP
				$res_arr= $this->get_server_info();
				
				$res_server_info = !is_null($res_arr['server_info']) ? $res_arr['server_info'] : array();
				$ldap_id = isset($res_arr['id']) ? $res_arr['id'] : array();
				
				if(empty($res_server_info) || !$ldap_id){
					// 调用UMS的创建LDAP接口（只认证不同步）
					$ldap_id = $this->ums->createLdap($ldap_param);
					if(!$ldap_id){
						throw new Exception('Create Auth LDAP from UMS faled.');
					}
				}else{
					// 修改
					$ldap_param['id'] = $ldap_id;
					$res_edit = $this->ums->editLdap($ldap_param);
					if(!$res_edit){
						throw new Exception('Update Auth LDAP from UMS faled.');
					}
				}
				
				$ldap_arr = array(
					'site_id' 		=> $this->p_site_id,
					'category' 		=> 'ACCOUNT_AUTHENTICATION_TYPE',
					'key' 			=> 'LDAP_AUTHENTICATION_ID',
					'value' 		=> $ldap_id,
					'create_time' 	=> date("Y-m-d H:i:s")
				);
				$result = $this->uc_site_config_model->save_config($ldap_arr);
				if(!$result){
					throw new Exception('Save isLDAP from uc_site_config faild.');
				}
			}
			
			// 在uc_site表更新is_ldap字段
			$this->load->library('UcadminLib', '', 'ucadmin');
			$res = $this->ucadmin->swicth_isldap($this->p_site_id, $auth_type);
			if(!$res){
				throw new Exception('Update isLDAP from uc_site faild.');
			}
			
			// 为全局is_ldap重新赋值
			$this->p_is_ldap = $ldap_id;
			
			return_json(COMMON_SUCCESS, $this->lang->line('success'), array());
		}catch(Exception $e){
			log_message('error', $e->getMessage());
			return_json(COMMON_FAILURE, $this->lang->line('falied'), array());
		}
		
		form_json_msg(COMMON_SUCCESS);
	}
	
	public function get_server_info() {
		$server_info = array();
		
		// 从uc_site_config表获得ldap_id
		$this->load->model('uc_site_config_model');
		$ldap_id = $this->uc_site_config_model->get_ldap_id($this->p_site_id);
			
		// 已经配置过LDAP,调用UMS接口获得LDAP配置信息
		if($ldap_id > 0){
			$res_ums = $this->ums->getLdap($ldap_id);
			if($res_ums === false){
				return null;
			}
			$server_type = ($res_ums['serverType'] == 'MS_AD') ? 'Microsoft Active Directory' : $res_ums['serverType'];
			$server_info = array(
					'servertype' 		=> $server_type,			// 服务器类型
					'protocol'	 		=> $res_ums['protocol'],	// 连接方式
					'confName'	 		=> $res_ums['confName'], 	// LDAP名字
					'hostname'	 		=> $res_ums['hostname'], 	// 服务器地址
					'port' 		 		=> $res_ums['port'], 		// 端口号
					'basedn'	 		=> $res_ums['basedn'], 		// Base DN
					'username'	 		=> $res_ums['username'], 	// 服务器用户名
					'password'	 		=> $res_ums['password'], 	// 密码
					'idAttribute'		=> $res_ums['ldapUserMapping']['idAttribute'], 		// 用户唯一性标识
					'emailAttribute' 	=> $res_ums['ldapUserMapping']['emailAttribute']  	// mail属性
			);
			$res_arr = array(
					'id' => $ldap_id,
					'server_info' => $server_info
			);
		}
		return $res_arr;
	}
	
	/**
	 * 获得通知设置
	 */
	public function get_notice_set(){
		log_message('info', 'Into method get_notice_set.');
		
		$this->load->model('uc_site_config_model');
		$inform_set_arr = $this->uc_site_config_model->getAllSiteConfig($this->p_site_id);
		
		return_json(COMMON_SUCCESS, 'success', $inform_set_arr);
	}
	
	public function save_notice_set(){
		$accountNotifyEmail = $this->input->get_post('accountNotifyEmail', true);
		$accountNotifySMS 	= $this->input->get_post('accountNotifySMS', true);
		$meetingNotifyEmail = $this->input->get_post('meetingNotifyEmail', true);
		$passwordNotifyWord = $this->input->get_post('passwordNotifyWord');
		$accountDefaultPassword = $this->input->get_post('accountDefaultPassword');
		$siteAllowChangePassword = $this->input->get_post('siteAllowChangePassword', true);
		log_message('info', 'Into method save_notice_set.');
		
		$this->load->model('uc_site_config_model');
		$config_arr_1 = array(
			'site_id' 		=> $this->p_site_id,
			'category' 		=> 'inform_set', 		
			'key' 			=> 'accountNotifyEmail', 	
			'value' 		=> $accountNotifyEmail	
		);
		$this->uc_site_config_model->save_inform_set($config_arr_1);
		
		$config_arr_2 = array(
				'site_id' 		=> $this->p_site_id,
				'category' 		=> 'inform_set',
				'key' 			=> 'accountNotifySMS',
				'value' 		=> $accountNotifySMS
		);
		$this->uc_site_config_model->save_inform_set($config_arr_2);
		
		$config_arr_3 = array(
				'site_id' 		=> $this->p_site_id,
				'category' 		=> 'inform_set',
				'key' 			=> 'meetingNotifyEmail',
				'value' 		=> $meetingNotifyEmail
		);
		$this->uc_site_config_model->save_inform_set($config_arr_3);
		
		$config_arr_4 = array(
				'site_id' 		=> $this->p_site_id,
				'category' 		=> 'inform_set',
				'key' 			=> 'password_existing_prompt',
				'value' 		=> htmlspecialchars($passwordNotifyWord)
		);
		$this->uc_site_config_model->save_inform_set($config_arr_4);
		
		$config_arr_5 = array(
				'site_id' 		=> $this->p_site_id,
				'category' 		=> 'inform_set',
				'key' 			=> 'siteAllowChangePassword',
				'value' 		=> $siteAllowChangePassword
		);
		$this->uc_site_config_model->save_inform_set($config_arr_5);
		
		$config_arr_6 = array(
				'site_id' 		=> $this->p_site_id,
				'category' 		=> 'inform_set',
				'key' 			=> 'accountDefaultPassword',
				'value' 		=> htmlspecialchars($accountDefaultPassword)
		);
		$this->uc_site_config_model->save_inform_set($config_arr_6);
		
		return_json(COMMON_SUCCESS);
	}
}

