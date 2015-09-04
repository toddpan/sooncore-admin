<?php
/**
 * 普通账号(create|update|disable|enable|delete)、合同开通、以及创建管理员处理工厂类
 * @file AccountProcessFactory.php
 * @author caohongliang <hongliang.cao@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */

require_once(__DIR__.DIRECTORY_SEPARATOR.'AccountFactory.php');

class AccountProcessFactory extends AccountFactory{

	public function __construct() {
		
	}

	/**
	 * 根据类型获取操作实例
	 * @param int $type 操作类型
	 * @return object
	 */
	public function get_instance($type){
		return $this->_getObject($type);
	}

	/**
	 * 根据操作类型，创建操作实例
	 * @param int $type 操作类型
	 * @return object
	 * @throws Exception
	 */
	private function _getObject($type){

		$account_process_path = dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'AccountProcess';
		$process_obj          = NULL;

		switch($type){
			case ACCOUNT_CREATE_PROCESS:
				require_once($account_process_path.DIRECTORY_SEPARATOR.'AccountCreateProcessImpl.php');
				$process_obj = new AccountCreateProcessImpl;
				break;
			case CONTRACT_CREATE_PROCESS:
				require_once($account_process_path.DIRECTORY_SEPARATOR.'ContractCreateProcessImpl.php');
				$process_obj = new ContractCreateProcessImpl;
				break;
			case ACCOUNT_UPDATE_PROCESS:
				require_once($account_process_path.DIRECTORY_SEPARATOR.'AccountUpdateProcessImpl.php');
				$process_obj = new AccountUpdateProcessImpl;
				break;
			case ACCOUNT_DISABLE_PROCESS:
				require_once($account_process_path.DIRECTORY_SEPARATOR.'AccountDisableProcessImpl.php');
				$process_obj = new AccountDisableProcessImpl;
				break;
			case ACCOUNT_ENABLE_PROCESS:
				require_once($account_process_path.DIRECTORY_SEPARATOR.'AccountEnableProcessImpl.php');
				$process_obj = new AccountEnableProcessImpl;
				break;
			case ACCOUNT_DELETE_PROCESS:
				throw new Exception('This type of process task is not supported now.type-->'.$type);
				break;
			default:
				throw new Exception('Invalid process type-->'.$type);
				break;
		}

		return $process_obj;
	}



}
