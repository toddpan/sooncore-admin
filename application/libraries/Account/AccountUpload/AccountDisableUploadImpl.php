<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 账号disable上传操作
 * @file AccountDisableUploadImpl.php
 * @author caohongliang <hongliang.cao@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */

require_once(__DIR__.DIRECTORY_SEPARATOR.'AccountUploadInterface.php');
class AccountDisableUploadImpl extends AccountUploadInterface{
	
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * 禁用帐号
	 * 
	 * -获取参数
	 * -获取用户所在的组织的nodeCode
	 * -获取用户的templateUUID
	 * -向boss发送请求
	 */
	public function process($value){
		//获取参数
		$required = array('customer_code', 'site_id', 'org_id', 'user_ids');
		list($valid, $msg) = $this->checkParam($value, $required);
		if(!$valid){
			throw new Exception($msg);
		}
		$uc = &$msg;
		$uc['user_ids'] = array_filter(array_map('trim', $uc['user_ids']), 'strlen');//过滤掉空字符串
		
		//获取用户所在的组织的nodeCode
		$org_info = $this->ci->ums->getOrganizationById($uc['org_id']);
		if(!$org_info || !isset($org_info['nodeCode'])){
			throw new Exception("get org {$uc['org_id']} info from ums failed");
		}
		$node_code = $org_info['nodeCode'];
		
		//TODO:搜索路径用户-组织-站点，根据站点，分组发送
		//获取用户的templateUUID
		$templateUUID 	= 	'';
		$components 	= 	$this->ci->power_model->getOrgPower($uc['site_id'], $node_code, $templateUUID);
		if(empty($templateUUID)){
			throw new Exception('error', 'not found templateUUId');
		}	
		
		//向boss发送请求
		//$this->sendBossRequest($uc['customer_code'], $uc['site_id'], $uc['user_ids'], 'disable', $templateUUID);
                
                //关闭用户产品 lwbbn改
		$close_product = $this->ci->ums->setUserProduct($uc['site_id'], $uc['user_ids'][0],UC_PRODUCT_ID, 0);
	}
}
