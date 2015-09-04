<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 账号enable操作
 * @file AccountEnableProcessImpl.php
 * @author caohongliang <hongliang.cao@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */

require_once(__DIR__.DIRECTORY_SEPARATOR.'AccountProcessInterface.php');

class AccountEnableProcessImpl implements AccountProcessInterface{
	
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * 启用帐号
	 * 
	 * 1.从boss的请求数据中提取变更相关信息
	 * 2.检查验证，如果参数不合法，则报异常，终止任务
	 * 3.设置成员变量，以供全局调用
	 * 4.依次去启用帐号
	 * 5.检查是否所有的账号都关闭成功，如果是，则callback成功，否则callback失败，任务记log
	 * 
	 * @param array $value boss请求数据
	 * 
	 */
	public function process($value){
		#从boss的请求数据中提取变更相关信息
		log_message('info', 'start to get key data from boss request data.');
		$uc = $this->_getDataFromBossRequest($value);
		log_message('info', 'geting key data from boss request data finished');
		
		#检查验证，如果参数不合法，则报异常，终止任务
		log_message('info', 'start to check data.');
		$success_list 				= 		array();//所有开通成功的用户
		$failed_list  				= 		array();//所有开通失败的用户
		if( ! $this->_checkData($uc)){
		foreach($uc['users'] as $user){
			$failed_list[] = array(
				'id'			=>	$user['id'],
				'billingCode'	=>	$user['billingCode'],
				'accountId'		=>	$user['accountId'],
				'error'			=>	'param error'
			);
		}
		
		$this->ci->boss->account_callback($uc['callback'], $uc['request_id'], $uc['contract_id'], BOSS_CALLBACK_FAILED ,$success_list, $failed_list);
			throw new Exception('Some important data is not found in boss request data. please check out.the info is-->'.var_export($uc, true));
		}
		log_message('info', 'checking data finished.');
		
		#设置成员变量，以供全局调用
		log_message('info', 'start to set global variable.');
		$this->is_manager   		= 		($uc['auth'] == AUTH_IS_MANAGER && $uc['type'] == 'create') ? true : false;//是否为管理员
		$this->uc					= 		 $uc;
		log_message('info', 'seting global variable finished.');
		
		#依次去启用用户
		log_message('info', 'start to close account.');
		foreach($uc['users'] as $user){
		
			/**
			* 如果有一个用户在启用的过程中遇到问题，则跳过，继续启用剩下的
			* 这里加异常处理，防止调用函数将异常抛向最顶层，从而中断任务
			*/
			try{
				list($is_ok, $msg) = $this->_enableAccount($user);
			}catch(Exception $e){
				log_message('error', $e->getMessage());
			}
		
		
			if(!$is_ok){
				$failed_list[] = array(
					'id'			=>$user['id'],
					'billingCode'	=>$user['billingCode'],
					'accountId'		=>$user['accountId'],
					'error'			=>$msg
				);
			}else{
				$success_list[] = array(
					'id'			=>$user['id'],
					'billingCode'	=>$user['billingCode'],
					'accountId'		=>$user['accountId']
				);
			}
		}
		log_message('info', 'closing account finished.');
		
		#检查是否所有的账号都启用成功，如果是，则callback成功，否则callback失败，任务记log
		log_message('info', 'start to checkout users which close failed.');
		if(!empty($failed_list)){
			$this->ci->boss->account_callback($uc['callback'], $uc['request_id'], $uc['contract_id'], BOSS_CALLBACK_FAILED ,$success_list, $failed_list);
			throw new Exception('Encounter a problem when create user, the msg is-->'.$msg);
		}else{
			return $this->ci->boss->account_callback($uc['callback'], $uc['request_id'], $uc['contract_id'], BOSS_CALLBACK_SUCCESS ,$success_list, $failed_list);
		}
		log_message('info', 'checkouting users finished.');
	}
	
	/**
	 * 启用帐号
	 * -1.uccserver创建同事关系
	 * -2.uniform启用会议
	 * -3.本地uc_user更新状态
	 * -4.开通用户产品状态
	 * -5.返回
	 * @param array $user
	 */
	private function _enableAccount($user){
		//ucc创建同事关系
		log_message('info', 'start to create colleage relationship for user.');
		$org_info = $this->ci->ums->getOrganizationByUserId($user['id']);//获取当前用户所在的组织
		$is_manager = 0;//是否为管理者 1-是 0-否
		if(!$this->ci->ucc->createColleague($user['id'], $org_info['id'], $org_info['parentId'], $is_manager)){
			return array(false, 'create colleague to ucc failed.');
		}
		log_message('info', 'creating colleage relationship for user finished.');
		
		//uniform启用会议
		log_message('info', 'start to send data to uniform.');
		$user_type = 2;//操作类型 1-新增或修改 2-启用
		$meeting_xml_data = $this->_getMeetingXMLData($user, $user_type);
		if(!$this->ci->meeting->saveMeetingData($meeting_xml_data)){
			return array(false, 'save meeting info to uniform failed.');
		}
		log_message('info', 'sending data to uniform finished.');
		
		//uc_user本地更新状态
		log_message('info', 'start to close user status at local...');
		if( ! $this->ci->account->setUserstatus($user['id'], UC_USER_STATUS_ENABLE)){
			log_message('error', 'disable user status at local failed.');
		}
		
		//开通用户产品状态
		log_message('info', 'start to close user product...');
		if( ! $this->ci->ums->setUserProduct($this->uc['site_id'], $user['id'], UC_PRODUCT_ID, UMS_USER_STATUS_OPEN)){
			log_message('error', 'set user product to ums failed.');
		}
		
		//返回
		return array(true, 'success');
	}
	
	
	
	
}