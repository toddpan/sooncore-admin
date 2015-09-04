<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 单用户权限变更上传
 * @file UserPowerChangeUploadImpl.php
 * @author caohongliang <hongliang.cao@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */

require_once(__DIR__.DIRECTORY_SEPARATOR.'AccountUploadInterface.php');
class UserPowerChangeUploadImpl extends AccountUploadInterface{
	
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * 单用户权限变更
	 * 
	 * -获取参数
	 * -参数校验
	 * -组织Boss请求数据
	 * -向Boss发送请求
	 */
	public function process($value){
		//参数校验
		$required = array('customer_code', 'site_id', 'user_id', 'sellingProducts');
		list($valid, $msg) = $this->checkParam($value, $required);
		if(!$valid){
			throw new Exception($msg);
		}
		$uc = &$msg;
		
		//发送Boss请求
		return $this->sendBossRequest($uc['customer_code'], $uc['site_id'], array($uc['user_id']), 'update', NULL, $uc['sellingProducts']);

		/*
		//获取参数
		$customer_code 		= isset($value['customer_code']) ? $value['customer_code'] : NULL;
		$site_id 			= isset($value['site_id']) ? $value['site_id'] : NULL;
		$user_id 			= isset($value['user_id']) ? $value['user_id'] : NULL;
		//$components 		= isset($value['components']) ? $value['components'] : NULL;
		$sellingProducts    = isset($value['sellingProducts']) ? $value['sellingProducts'] : NULL;
		
		//参数校验
		$required = array($customer_code, $site_id, $user_id, $sellingProducts);
		$invalid_params = array_filter($required, 'is_null');
		if(count($invalid_params)>0){
			throw new Exception('Some important data is not found in upload data. please check out.the info is-->'.var_export($invalid_params, true));
		}
		
		//组织boss请求数据
		$boss_request = array();
		
		//--获取用户的templateUUID
		//--这里因为是帐号update操作，所以会以user的components为准，templateUUID不重要，合法即可
		//--这里使用站点url
		$templateUUID   = $this->ci->account_model->getSiteUrl($site_id);
		if(!$templateUUID) {throw new Exception('Get site url from local db failed');}
		$boss_request['templateUUID'] 				= $templateUUID;
		
		$boss_request['callback']     				= BOSS_CALLBACK;
		$boss_request['type']		  				= 'update';
		$boss_request['customer']['customerCode'] 	= $customer_code;
		
		//--从本地数据库里获取合同id
		$contract_id = $this->ci->account_model->getContractId($customer_code, $site_id);
		if(!$contract_id){throw new Exception('Get contract id from local db failed.');}
		$boss_request['customer']['contract']['id'] = $contract_id;
		
		$user = array();
		$user['id'] 		= $user_id;
		//--获取用户分账id
		$account_id = $this->ci->account_model->getUserAccountId($user_id);
		if(!$account_id) {throw new Exception('Get account_id from local db failed');}
		$user['accountId'] 	= $account_id;
		
		$user['sellingProducts'] = $sellingProducts;
		//$user['components'] = $components;
		
		
		$boss_request['customer']['users'][] = $user; 
		//向boss发送请求
		//--本地记录request请求
		$request_with_id    = $this->ci->account_model->saveRequestInfo($boss_request);
		if( ! $request_with_id){
			throw new Exception('save request to local db failed');
		}
		//--调用boss开通接口
		log_message('info', 'send a boss requset');
		
		//--切换至新的接口（组合销售品统一开通）
		$boss_rst = $this->ci->boss->combinedAccount($request_with_id);
		//$boss_rst = $this->ci->boss->account($request_with_id);
		
		if(!$boss_rst){
			throw new Exception('send users info to boss failed');
		}
		
		return;
		*/
	}
}