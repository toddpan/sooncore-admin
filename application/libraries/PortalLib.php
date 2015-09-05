<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * portal接口公用类
 * @file Portal.php
 * @author caohongliang <hongliang.cao@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
class PortalLib{
	
	public function __construct() {
		//载入接口公用函数
		include_once APPPATH . 'helpers/my_httpcurl_helper.php';
		$this->apiurl = PORTAL_API ; //接口地址
		$this->check_code = PORTAL_CHECKCODE;
	}
	
	public function addSkipRule($site_url, $skip_url){
		$method = 'POST';
		$url    = $this->apiurl.'/portal/rule/addRule';
		$data   = array('siteUrl'=>$site_url, 'skipUrl'=>$skip_url, 'checkCode'=>$this->check_code);
		
		$ret = httpCurl($url, json_encode($data),$method);
		
		log_message('info',"Add url rule to portal url is->".$url." input data is ".var_export($data, true)." result is->".var_export($ret, true));
		
		return $ret['isOk'] == 1 ? array(true, '') : array(false, $ret['msg']);
	}
	
	public function getRule($site_url){
		$method = 'POST';
		$url    = $this->apiurl.'/portal/rule/searchRule';
		$data   = array('siteUrl'=>$site_url);
		
		$ret = httpCurl($url, json_encode($data),$method);
		
		log_message('info',"get url rule from portal url is->".$url." input data is ".var_export($data, true)." result is->".var_export($ret, true));
		
		if($ret['isOk'] == 1 && isset($ret['result'])){
			return json_decode($ret['result'], true);
		}else{
			return false;
		}
		
	}
	
	
}