<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 账号(create|update|disable|enable|delete)、合同开通、以及创建管理员的boss接口
 * @file Account.php
 * @author caohongliang <hongliang.cao@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */

class Account extends Api_Controller{
	public function __construct() {
		parent::__construct();
		$this->load->helper('my_publicfun_helper.php');
		$this->load->library('UmsLib', '', 'ums');
		$this->load->model('account_model', 'account');
		$this->config->load('account', true);
	}
	
	/**
	 * 普通账号(create|update|disable|enable|delete)、站点合同开通、管理员开通接口
	 * -获取boss请求数据
	 * -数据格式校验
	 * -根据boss请求数据，判断操作类型
	 * -将数据添加到任务表，等待后台进程处理
	 */
	public function active(){
		
		//获取boss请求数据
		$boss_raw_data = trim(file_get_contents("php://input"));
		log_message('info','Recieve a boss request. The request data is-->'.$boss_raw_data);
		
		//数据格式校验
		list($is_valid, $data) = $this->_checkBossData($boss_raw_data);
		if(!$is_valid){
			log_message('error', $data);
			echo response_json(COMMON_PARAM_ERROR,$data);return;
		}
		
		//根据boss请求数据，判断操作类型
		$type = $this->_getOperateType($data);
		
		//将数据添加到任务表，等待后台进程处理
		$this->load->model('Account_Process_Task_Model', 'task');
		if(!$this->task->saveTask($type, $boss_raw_data)){
			echo response_json(COMMON_DATABASE_EXCEPTION,'Add task to db failed');return;
		}
		
		//返回
		echo response_json(COMMON_SUCCESS,'success');return;
	}
	
	
	/**
	 * 校验boss请求数据
	 * -数据是否为合法的json字符串 
	 * -数据中是否包含了一些必须的信息,这里仅对一些重要的字段进行验证
	 * -验证失败，返回失败信息
	 * @param string $data
	 * @return array
	 */
	private function _checkBossData($json){
		//判断数据是否为空
		if(empty($json)) return array(false, 'The request data is empty!');
		
		//请求数据是否为合法的json格式
		$data = json_decode($json, true);
		if(is_null($data)) return array(false, 'invalid json data');
		
		//boss数据验证
		$required_keys			= array('requestId', 'callback', 'customer', 'type');
		$diff = array_diff($required_keys , array_keys($data));
		if(count($diff) > 0){
			return array(false, 'the keys '.var_export($diff, true).'is required');
		}
		
		//customer键验证
		$required_keys_customer = array('customerCode', 'contract');
		$diff_customer = array_diff($required_keys_customer , array_keys($data['customer']));
		if(count($diff_customer) > 0){
			return array(false, 'the keys '.var_export($diff_customer, true).'is required in customer');
		}
		
		//contract键验证
		$required_keys_contract = array('id', 'components', 'resource');
		$diff_contract = array_diff($required_keys_contract , array_keys($data['customer']['contract']));
		if(count($diff_contract) > 0){
			return array(false, 'the keys '.var_export($diff_contract, true).'is required in contract!');
		}
		
		return array(true,$data);
	}
	
	/**
	 * 获取操作类型
	 *-如果请求数据中users为空，则表示是站点合同操作，否则为用户操作（用户操作）
	 * @param array $data boss端请求数据
	 * @return int
	 */
	private function _getOperateType($data){
		//获取判断类型所需的数据
		$type		= $data['type'];
		$users		= isset($data['customer']['users']) ? $data['customer']['users'] : NULL;
		$components = $data['customer']['contract']['components'];
		
		//users值为空，则为合同操作
		if(is_null($users) && $type == 'create'){
			return CONTRACT_CREATE_PROCESS;
		}
		
		//账号操作类型
		switch($type){
			case 'create':
				return ACCOUNT_CREATE_PROCESS;
			case 'update':
				return ACCOUNT_UPDATE_PROCESS;
			case 'disable':
				return ACCOUNT_DISABLE_PROCESS;
			case 'enable':
				return ACCOUNT_ENABLE_PROCESS;
			case 'delete':
				return ACCOUNT_DELETE_PROCESS;
		}
		
		
		//未定义的操作类型
		return UNDEFINED_PROCESS_TYPE;
	}
	
	
	/**
	 * @brief boss调用uc同步完成接口
	 * @detail
	 * 1.获取boss请求数据
	 * 2.数据格式校验
	 * 3.如果全部开通成功则不做处理、如果有失败则做相应的回滚
	 * 4.操作成功
	 */
	public function callback(){
		#获取boss请求数据
		$boss_raw_data = trim(file_get_contents("php://input"));
		log_message('info','Recieve a boss request. The request data is-->'.$boss_raw_data);
		
		$boss_data  	= 	json_decode($boss_raw_data, true);
		$request_id 	= 	isset($boss_data['requestId']) ? $boss_data['requestId'] : NULL;
		$success_list	= 	isset($boss_data['successed']) 	? $boss_data['successed'] : NULL;
		$failed_list	= 	isset($boss_data['failed'])	   	? $boss_data['failed'] : NULL;
		$contract_id	=	isset($boss_data['contractId']) ? $boss_data['contractId'] : NULL;
		$type			=	isset($boss_data['type'])	? 	$boss_data['type'] : NULL;
		
		#数据格式校验
		log_message('info', 'start to check request data!');
		$msg	=	'';
		if(empty($success_list) && empty($failed_list)){//成功列表与失败列表不能同时为空
			$msg	=	'successful users and failed users is all empty!';
			log_message('error', $msg);
			return_json(COMMON_PARAM_ERROR, $msg);
		}
		
		$invalid_params = array_filter(array('requestId'=>$request_id, 'contractId'=>$contract_id, 'type'=>$type), 'is_empty');
		if(count($invalid_params) > 0){
			$msg 	=	implode(',', array_keys($invalid_params)).'can not be empty!';
			log_message('error', $msg);
			return_json(COMMON_PARAM_ERROR, $msg);
		}
		
		$types = $this->config->item('types', 'account');
		if(!in_array($type, $types)){
			$msg = 'invalid type:'.$type;
			log_message('error', $msg);
			return_json(COMMON_PARAM_ERROR, $msg);
		}
		log_message('info', 'checking request data finished!');
		
		
		#全部开通成功
		//-全部开通成功则不做处理。到此用户开通成功。如果以后有其他操作，可以加在这里
		if(empty($failed_list)){
			log_message('info', 'all users is successed!');
			return_json(COMMON_SUCCESS, 'success');
		}
		
		#如果有失败则做相应的回滚
		log_message('info', 'part of users is failed.start to rollback...');
		
		//1.获取之前发出开通请求时的历史记录
		log_message('info','get detail user info by requestId from local db.');
		$request_info = $this->account->getRequestInfo($request_id);
		if(!$request_info){
			$msg = 'the request id '.$request_id.'is not found in local db';
			log_message('info', $msg);
			return_json(COMMON_DATABASE_EXCEPTION, $msg);
		}
		$boss_history_data = json_decode($request_info, true);
		$boss_history_users = $boss_history_data['customer']['users'];
		
		//2.根据customerCode和合同id获取站点id
		log_message('info', 'get site id from local db.');
		$customer_code 		= $boss_history_data['customer']['customerCode'];
		$contract_id        = $boss_history_data['customer']['contract']['id'];
		$site_id            = $this->account->getSiteId($customer_code, $contract_id);
		if(!$site_id){
			$msg = 'get site id by customerCode and contractId from local db failed';
			log_message('info', $msg);
			return_json(COMMON_DATABASE_EXCEPTION, $msg);
		}
		
		//3.整理历史记录中的user列表，以user_id做为key,方便下面调用
		log_message('info', 'reconstruct user data.');
		$history_users = array();
		foreach($boss_history_users as $k=>$boss_history_user){
			$boss_history_user['siteId']             = $site_id;
			$history_users[$boss_history_user['id']] = $boss_history_user;
		}
		
		//4.根据账号操作类型，去做相应的回滚
		log_message('info', 'rollback user.');
		list($flag, $msg) = $this->_rollback($failed_list, $history_users, $type);
		if(!$flag){
			log_message('error', $msg);
		}else{
			log_message('info', 'all users rollback success');
		}
		
		#操作成功
		return_json(COMMON_SUCCESS, 'success');
	}
	
	/**
	 * @brief 账号回滚
	 * @detail
	 * 1.账号create、delete、disable、enable只要置本地和ums端的产品状态即可
	 * 2.账号update需要将用户的权限回滚
	 * 
	 * @param array  $failed_list 		//开通失败的用户列表
	 * @param array  $history_users		//之前开通用户时向boss发送的数据
	 * @param string $type
	 * @return array array(boolean, $msg)
	 */
	private function _rollback($failed_list, $history_users, $type){
		#依次去回滚开通失败的用户
		$rollback_failed_user_ids = array();//记录回滚失败的所有用户id
		foreach($failed_list as $user){
			//检查此用户是否在历史记录中
			$old_user = isset($history_users[$user['id']]) ? $history_users[$user['id']] : NULL;
			if(! $old_user){
				$rollback_failed_user_ids[] = $user['id'];
				continue;
			}
		
			//根据类型做相应的处理	
			$ret_local_status = true;
			$ret_local_power  = true;
			$ret_ums          = true;
			switch($type){
				case 'create':
				case 'enable':
					$ret_local_status = $this->account->setUserstatus($user['id'], UC_USER_STATUS_DISABLE);//本地用户状态置为禁用
					$ret_ums   		  = $this->ums->setUserProduct($old_user['siteId'], $user['id'], UC_PRODUCT_ID, UMS_USER_STATUS_CLOSE);//ums端用户产品状态置为关闭
					break;
				case 'delete':
				case 'disable':
					$ret_local_status = $this->account->setUserstatus($user['id'], UC_USER_STATUS_ENABLE);//本地用户状态置为启用
					$ret_ums          = $this->ums->setUserProduct($old_user['siteId'], $user['id'], UC_PRODUCT_ID, UMS_USER_STATUS_OPEN);//ums端用户产品状态置为开通
					break;
				case 'update':
					$ret_local_power  = $this->account->userPowerRollback($user['id']);
					break;
				default:
					break;
			}
			
			//检查本地状态和ums产品状态是否都已设置成功
			if(!$ret_local_status OR !$ret_ums OR !$ret_local_power){
				$rollback_failed_user_ids[] = $user['id'];
			}
		}
		
		#返回结果
		if(empty($rollback_failed_user_ids)){
			return array(true, 'success');
		}
		
		return array(false, 'the users '.var_export($rollback_failed_user_ids, true).' rollback failed');
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}
