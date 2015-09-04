<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 载入权限类
include_once APPPATH . 'libraries'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'Privilege_class.php';

/**
 * @abstract 	权限验证类 
 * @filesource 	ValidPrivilege.php
 * @author 		Bai Xue <xue.bai_2@quanshi.com> 2014-11-26
 * @copyright 	Copyright (c) UC
 * @version 	v1.0
 */
class ValidPrivilege {
	
	private $CI;
	
	/**
	 * 构造方法
	 */
	public function __construct() {
		$this->CI = & get_instance();
	}
	
	/**
	 * 权限验证
	 */
	public function inAction() {
		// 获得角色Id
		$role_id = isset($this->CI->p_role_id) ? $this->CI->p_role_id : '';
		
		// 如果角色Id不为空，即当有session时，进行权限验证
		if(!empty($role_id)){
			$res = Privilege_class::_isPrivilege($role_id, $this->uri->slash_segment(1), $this->uri->segment(2));
			
			if($res == false){
				// TODO 没有权限，则进行限制:弹框提示无权限或者显示无权限提示信息页面
			}
		}
	}
	
	
}