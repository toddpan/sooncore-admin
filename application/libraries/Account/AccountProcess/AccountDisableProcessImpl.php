<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 账号disable操作
 * @file AccountDisableProcessImpl.php
 * @author caohongliang <hongliang.cao@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */

require_once(__DIR__.DIRECTORY_SEPARATOR.'AccountProcessInterface.php');

class AccountDisableProcessImpl extends AccountProcessInterface{
	
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * 关闭帐号
	 * 
	 * 1.从boss的请求数据中提取变更相关信息
	 * 2.检查验证，如果参数不合法，则报异常，终止任务
	 * 3.设置成员变量，以供全局调用
	 * 4.依次去关闭帐号
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
		
		#依次去关闭用户
		log_message('info', 'start to close account.');
		foreach($uc['users'] as $user){
		
			/**
			 * 如果有一个用户在关闭的过程中遇到问题，则跳过，继续关闭剩下的
			 * 这里加异常处理，防止调用函数将异常抛向最顶层，从而中断任务
			 */
			try{
				list($is_ok, $msg) = $this->_disableAccount($user);
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
		
		#检查是否所有的账号都关闭成功，如果是，则callback成功，否则callback失败，任务记log
		log_message('info', 'start to checkout users which disable failed.');
		if(!empty($failed_list)){
			$this->ci->boss->account_callback($uc['callback'], $uc['request_id'], $uc['contract_id'], BOSS_CALLBACK_FAILED ,$success_list, $failed_list);
			throw new Exception('Encounter a problem when disable user, the msg is-->'.$msg);
		}else{
			return $this->ci->boss->account_callback($uc['callback'], $uc['request_id'], $uc['contract_id'], BOSS_CALLBACK_SUCCESS ,$success_list, $failed_list);
		}
		log_message('info', 'checkouting users finished.');
	}
	
	
	/**
	 * @brief 关闭用户
	 * @detail
	 * 1.关闭用户产品状态
	 * 2.关闭本地用户状态
	 * 3.如果用户是管理员，则删除用户管理员角色
	 * 4.调用ucc接口,解除同事关系
	 * 5.调用会议接口,禁用会议
	 * 6.返回
	 * 
	 * @param array $user
	 * @return array
	 */
	private function _disableAccount($user){
		#关闭用户产品状态
		log_message('info', 'start to close user product...');
		if( ! $this->ci->ums->setUserProduct($this->uc['site_id'], $user['id'], UC_PRODUCT_ID, UMS_USER_STATUS_CLOSE)){
			log_message('error', 'set user product to ums failed.');
		}
		
		#关闭本地用户状态
		log_message('info', 'start to close user status at local...');
		if( ! $this->ci->account->setUserstatus($user['id'], UC_USER_STATUS_DISABLE)){
			log_message('error', 'disable user status at local failed.');
		}
		
		/*
		#如果用户是管理员，则删除用户管理员角色
		log_message('info', 'start to delete user role at local...');
		if( ! $this->ci->account->deleteUserRole($user['id'])){
			log_message('error', 'delete user manager role at local failed');
		}
		*/
		
		
		#调用ucc接口,解除同事关系
		/*
		log_message('info', 'start to delete colleague relationship...');
		$org_info = $this->ci->ums->getOrganizationByUserId($user['id']);//获取当前用户所在的组织
		if(!$this->ci->ucc->deleteColleague($user['id'], $org_info['id'], $org_info['parentId'], 0)){
			return array(false, 'delete colleague to ucc failed.');
		}
		*/
		
		#调用会议接口,禁用会议
		/*
		log_message('info', 'start to close meeting...');
		if( ! $this->ci->meeting->closeMeeting($user['id'],3)){
			return array(false, 'close meeting to uniform failed.');
		}
		*/
		
		#返回
		return array(true, 'success');
	}
	
	
}