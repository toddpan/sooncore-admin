<?php
/**
 * 账户数据上传处理工厂类
 * @file AccountUploadFactory.php
 * @author caohongliang <hongliang.cao@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */

require_once(__DIR__.DIRECTORY_SEPARATOR.'AccountFactory.php');

class AccountUploadFactory extends AccountFactory{
	
	public function __construct() {
		
	}
	
	/**
	 * 根据操作类型，获取操作实例
	 * @param int $type 操作类型
	 * @return object
	 */
	public function get_instance($type){;	
		return $this->_getObject($type);
	}
	
	/**
	 * 根据操作类型，创建操作实例
	 * @param int $type 操作类型
	 * @return object
	 * @throws Exception
	 */
	private function _getObject($type){
		
		$account_upload_path = dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'AccountUpload';
		$process_obj          = NULL;
		
		switch($type){
			case ACCOUNT_CREATE_UPLOAD:
				require_once($account_upload_path.DIRECTORY_SEPARATOR.'AccountCreateUploadImpl.php');
				$process_obj = new AccountCreateUploadImpl;
				break;
			case USER_POWER_CHANGE_UPLOAD:
				require_once($account_upload_path.DIRECTORY_SEPARATOR.'UserPowerChangeUploadImpl.php');
				$process_obj = new UserPowerChangeUploadImpl;
				break;
			case ORG_POWER_CHANGE_UPLOAD:
				require_once($account_upload_path.DIRECTORY_SEPARATOR.'OrgPowerChangeUploadImpl.php');
				$process_obj = new OrgPowerChangeUploadImpl;
				break;
			case SITE_POWER_CHANGE_UPLOAD:
				require_once($account_upload_path.DIRECTORY_SEPARATOR.'SitePowerChangeUploadImpl.php');
				$process_obj = new SitePowerChangeUploadImpl;
				break;
			case ACCOUNT_DISABLE_UPLOAD:
				require_once($account_upload_path.DIRECTORY_SEPARATOR.'AccountDisableUploadImpl.php');
				$process_obj = new AccountDisableUploadImpl;
				break;
			case ACCOUNT_ENABLE_UPLOAD:
				require_once($account_upload_path.DIRECTORY_SEPARATOR.'AccountEnableUploadImpl.php');
				$process_obj = new AccountEnableUploadImpl;
				break;
			case ACCOUNT_DELETE_UPLOAD:
			case ECOLOGY_DELETE_UPLOAD:
			case BATCH_CHANGE_UPLOAD:
			case ECOLOGY_POWER_CHANGE_UPLOAD:		
				throw new Exception('This type of upload task is not supported now.type-->'.$type);
				break;
			default:
				throw new Exception('Invalid upload type-->'.$type);
				break;
		}
		
		return $process_obj;
	}
	
	
	
}
