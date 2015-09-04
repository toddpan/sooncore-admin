<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * action log
 * @file RequestLog.php
 * @author caohongliang <hongliang.cao@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
 
class RequestLog{
	public function __construct() {
		
	}
	
	public function beforeSystem(){
		//记录log
		log_message('info','>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>');
	}
	
	public function beforeAction(){
		
	}
	
	public function inAction(){
		//记录log
		$CI = & get_instance();
		if( ! $CI->input->is_cli_request()){
			$customer_code = isset($CI->p_customer_code) ? $CI->p_customer_code: null;
			$site_id       = isset($CI->p_site_id)       ? $CI->p_site_id: null;
			$org_id        = isset($CI->p_org_id)        ? $CI->p_org_id: null;
			$logs = array(
					'request_method'=>strtoupper($CI->input->server('REQUEST_METHOD', true)),
					'url'=>current_url(),
					'request_params'=>$CI->input->get_post(null),
					'ip_address' => $CI->input->ip_address(),
					'customer_code'=>$customer_code,
					'site_id'=>$site_id,
					'org_id'=>$org_id,
			);
			foreach($logs as $k=>$log){
				log_message('info',strtoupper($k).' --> '.var_export($log, true));
			}
		}
		
	}
	
	public function afterAction(){
		
	}
	
	public function afterSystem(){
		log_message('info','<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
	}
}
