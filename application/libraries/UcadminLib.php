<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @abstract 	UCAdmin 内部公用接口类
 * @author 		Bai Xue <xue.bai_2@quanshi.com>
 * @copyright 	Copyright (c) UC
 * @version 	v1.0
 */
class UcadminLib{
	
	public $CI;  
	
	/**
	 * 构造方法
	 */
	public function __construct() {
		$this->CI = & get_instance();
		log_message('info', 'Into class UcadminLib.');
	}
	
	/**
	 * 普通站点和LDAP站点的相互过渡
	 * 注意：从普通站点过渡到LDAP站点，需传递$auth_type=1；从LDAP站点过渡到普通站点，不用传$auth_type。
	 * @param 	int 	$site_id 		站点id
	 * @param 	int 	$auth_type		LDAP认证的协议类型
	 * @return 	boolean true/false
	 */
	public function swicth_isldap($site_id = 0, $auth_type = null){
		log_message('info', 'Into method switch_isldap with $site_id = '.$site_id.', $auth_type = '.$auth_type.'.');

		// 从普通站点过渡到LDAP站点，$isldap=1；从LDAP站点过渡到普通站点，$isldap=0。
		$isldap = (is_null($auth_type)) ? NOT_LDAP : IS_LDAP;
		
		// 调用模型方法修改uc_site和uc_user表中的isLDAP值
		$this->CI->load->model('uc_site_model');
		$result = $this->CI->uc_site_model->update_value(array('siteID' => $site_id), array('isLDAP' => $isldap));
		
		log_message('info', 'Out method switch_isldap with $result = '.$result.'.');
		return $result;
	}
	
	/**
	 * 获得站点”登陆是否使用自定义后缀“配置以及自定义后缀
	 * 
	 * @param int $site_id
	 * 
	 * @return array
	 */
	public function get_suffix($site_id) {
		log_message('info', 'Into method get_suffix input --> $site_id='.$site_id);
		
		// 初始化结果数组
		$result_arr = array();
		
		// 载入站点配置模型
		$this->CI->load->model('uc_site_config_model');
		
		// 获得站点”登陆是否使用自定义后缀“
		$where_arr = array(
			'site_id' 	=> $site_id,
			'category' 	=> 'ACCOUNT_AUTHENTICATION_TYPE',
			'key' 		=> 'use_custom_login_name'
		);
		$use_suffix_arr = $this->CI->uc_site_config_model->get_site_config($where_arr);		
		if(!empty($use_suffix_arr)){
			$use_suffix = isset($use_suffix_arr['value']) ? $use_suffix_arr['value'] : 'NOT_USE_SELF_DEFINED_SUFFIX'; // 登陆是否使用自定义后缀
			
			// 获得自定义后缀
			if($use_suffix == USE_SELF_DEFINED_SUFFIX){
				$cond_arr = array(
						'site_id' 	=> $site_id,
						'category' 	=> 'ACCOUNT_AUTHENTICATION_TYPE',
						'key' 		=> 'custom_login_name_suffix'
				);
				$suffix_arr = $this->CI->uc_site_config_model->get_site_config($cond_arr);
				$suffix = isset($suffix_arr['value']) ? $suffix_arr['value'] : '@'.$this->CI->p_stie_domain; // 自定义后缀
			}
		}
		
		$result_arr = array(
			'use_suffix' 	=> isset($use_suffix) ? $use_suffix : NOT_USE_SELF_DEFINED_SUFFIX, 	// 登陆是否使用自定义后缀
			'suffix' 		=> isset($suffix) ? $suffix : ''									// 自定义后缀
		);
		
		log_message('info', 'Out method get_suffix output --> $result_arr='.json_encode($result_arr));
		return $result_arr;
	}
}
