<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 组织权限变更
 * @file OrgPowerChangeUploadImpl.php
 * @author caohongliang <hongliang.cao@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */

require_once(__DIR__.DIRECTORY_SEPARATOR.'AccountUploadInterface.php');
class OrgPowerChangeUploadImpl extends AccountUploadInterface{
	
	public function __construct(){
		parent::__construct();
	}
	
	
	/**
	 * @brief组织权限变更
	 * 
	 * @detail
	 * -参数校验
	 * -遍历该组织下所有的组织以及用户(过滤掉已经自定义了权限的组织或者用户)
	 * -组织boss数据
	 * -分批发送请求
	 */
	public function process($value){
		//参数校验
		$required = array('customer_code', 'site_id', 'org_id');
		$optional = array('sellingProducts');
		list($valid, $msg) = $this->checkParam($value, $required, $optional);
		if(!$valid){
			throw new Exception($msg);
		}
		$uc = &$msg;
		
		//获取当前组织的nodeCode,作为templateUUID
		$org_info = $this->ci->ums->getOrganizationById($uc['org_id']);
		if(!$org_info || !isset($org_info['nodeCode'])){
			throw new Exception("get org {$uc['org_id']} info from ums failed");
		}
		$nodeCode = $org_info['nodeCode'];
		
		//组织权限变更
		return $this->powerChange($uc['customer_code'], $uc['site_id'], $uc['org_id'],$nodeCode);
	}
	
	
}

