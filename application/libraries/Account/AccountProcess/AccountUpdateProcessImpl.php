<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 账号update操作
 * @file AccountUpdateProcess.php
 * @author caohongliang <hongliang.cao@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */

require_once(__DIR__.DIRECTORY_SEPARATOR.'AccountProcessInterface.php');
require_once(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Common'.DIRECTORY_SEPARATOR.'Array2XML.php');

class AccountUpdateProcessImpl extends AccountProcessInterface{
	
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * @brief 更新帐号
	 * 
	 * @detail 
	 * 场景1：管理员在大道后台更改了用户的权限配置，且该权限需要去boss做同步，此时用户的权限发生了变化
	 * 场景2： 用户被调到了其他部门，此时用户的权限和同事关系都发生了变化
	 * 
	 * 1.从boss的请求数据中提取开通相关信息
	 * 2.检查验证，如果参数不合法，则报异常，终止任务
	 * 3.设置成员变量，以供全局调用
	 * 4.依次去更新用户
	 * 5.检查是否所有的账号都更新成功，如果是，则callback成功，否则callback失败，任务记log
	 * 
	 * @param array $value boss请求数据
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
					'id'			=>$user['id'],
					'billingCode'	=>$user['billingCode'],
					'accountId'		=>$user['accountId'],
					'error'			=>'param error'
			);
		}
			
		$this->ci->boss->account_callback($uc['callback'], $uc['request_id'], $uc['contract_id'], BOSS_CALLBACK_FAILED ,$success_list, $failed_list);
			throw new Exception('Some important data is not found in boss request data. please check out.the info is-->'.var_export($uc, true));
		}
		log_message('info', 'checking data finished.');
		
		#设置成员变量，以供全局调用
		log_message('info', 'start to set global variable.');
		$this->is_manager   		= 		($uc['auth'] == AUTH_IS_MANAGER && $uc['type'] == 'update') ? true : false;//是否为管理员
		$this->uc					= 		 $uc;
		log_message('info', 'seting global variable finished.');
		
		#检查用户，如果用户ums中只有用户信息，但未开通过uc产品，则将用户写入到帐号create task里
		/*
		log_message('info', 'check users,if need save to create process.');
		$users_create = array();
		foreach($uc['users'] as $k=>$user){
			$user_product = $this->ci->ums->getUserProduct($user['id'], UC_PRODUCT_ID);
			if(!$user_product){
				$users_create[] = $user;
				unset($uc['users'][$k]);
			}
		}
		
		if(count($users_create)>0){
			log_message('info', "found {count($users_create)} users,need to save to create process");
			$value_create = $value;
			$value_create['customer']['users'] = $users_create;
			$value_create['type'] = 'create';
			//将数据添加到任务表，等待后台进程处理
			$this->ci->load->model('account_process_task_model', 'task');
			if(!$this->task->saveTask('create', json_encode($value_create))){
				throw new Exception('save users to create process task failed.');
			}
		}
		log_message('info', 'not found users,need save to create process.');
		*/
		
		
		#依次去更新用户
		log_message('info', 'start to update account.');
		foreach($uc['users'] as $user){
				
			/**
			 * 如果有一个用户在更新的过程中遇到问题，则跳过，继续更新剩下的
			 * 这里加异常处理，防止调用函数将异常抛向最顶层，从而中断任务
			 */
			try{
				list($is_ok, $msg) = $this->_updateAccount($user);
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
		log_message('info', 'updating account finished.');
		
		#检查是否所有的账号都更新成功，如果是，则callback成功，否则callback失败，任务记log
		log_message('info', 'start to checkout users which update failed.');
		if(!empty($failed_list)){
			$this->ci->boss->account_callback($uc['callback'], $uc['request_id'], $uc['contract_id'], BOSS_CALLBACK_FAILED ,$success_list, $failed_list);
			throw new Exception('Encounter a problem when update user, the msg is-->'.$msg);
		}else{
			return $this->ci->boss->account_callback($uc['callback'], $uc['request_id'], $uc['contract_id'], BOSS_CALLBACK_SUCCESS ,$success_list, $failed_list);
		}
		log_message('info', 'checkouting users finished.');
		
	}
	
	/**
	 * @brief 更新用户
	 * 
	 * @detail
	 * 1.更新同事关系
	 * 2.向uniform开通会议
	 * @param array $user
	 */
	private function _updateAccount($user){
		#uc站点迁移 TODO
		
		#向uniform开通会议
		#-uniform接口数据xml格式，这里需要将数据准备好后，由array转xml
		#-这个接口主要向uniform传送一些和会议相关的参数
		log_message('info', 'start to send data to uniform.');
		$meeting_xml_data = $this->_getMeetingXMLData($user);
		if(!$this->ci->meeting->saveMeetingData($meeting_xml_data)){
			return array(false, 'save meeting info to uniform failed.');
		}
		log_message('info', 'sending data to uniform finished.');
		
		//处理完成,此用户更新成功
		return array(true, '');
	}
	
}