<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 开通合同操作
 * @file ContractCreateProcessImpl.php
 * @author caohongliang <hongliang.cao@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */

require_once(__DIR__.DIRECTORY_SEPARATOR.'AccountProcessInterface.php');
require_once(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Common'.DIRECTORY_SEPARATOR.'Cluster.php');

class ContractCreateProcessImpl extends AccountProcessInterface{
	
	public function __construct(){
		$this->ci = & get_instance();
		$this->ci->load->helper('my_publicfun_helper');
		$this->ci->load->library('BossLib', '', 'boss');
	}
	
	/**
	 * 创建合同
	 * 
	 * -如果客户没有分配集群，则为其分配集群
	 * -数据本地入库，包括记录账户信息、客户权限、站点信息、客户地址
	 * -执行成功，回调boss接口
	 */
	public function process($value){
		
		//从boss的请求数据中提取开通相关信息
		$uc 					= 		array();
		
		$uc['components']		=  		isset($value['customer']['contract']['components']) ? $value['customer']['contract']['components'] : array();
		$comp					=       $this->_search_components($uc['components'], 'uc', array('size', 'isLDAP', 'companytype'));
		$uc['user_amount'] 		= 		$comp['size'];
		$uc['is_ldap']			=       $comp['isLDAP'];
		$uc['company_type']		=		$comp['companytype'];
		
		$uc['request_id']		=		isset($value['requestId']) ? $value['requestId'] : '';
		//$uc['customer_id']  	= 		isset($value['customer']['id']) ? $value['customer']['id'] : '';
		$uc['customer_code']  	= 		isset($value['customer']['customerCode']) ? $value['customer']['customerCode'] : '';
		$uc['site_url']       	=       isset($value['customer']['contract']['resource']['siteURL']) ? $value['customer']['contract']['resource']['siteURL'] : '';
		//$uc['customer_name'] 	=       isset($value['customer']['name']) ? $value['customer']['name'] : '';
		$uc['contract_id']      =       isset($value['customer']['contract']['id']) ? $value['customer']['contract']['id'] : '';
		$uc['callback']			=       isset($value['callback']) ? $value['callback'] : '';
		//$uc['address']			=       isset($value['customer']['address']) ? $value['customer']['address'] : '';
		
		$uc['site_id']     		=     	$this->_getSiteIdFromUms($uc['site_url']);//从ums通过站点url获取站点id
		
		//检查参数
		if(count(array_filter($uc, 'is_empty')) > 0){
			$this->ci->boss->contract_callback($uc['callback'], $uc['request_id'], $uc['contract_id'], BOSS_CALLBACK_FAILED);
			throw new Exception('Some important data is not found in boss request data. please check out.the info is-->'.var_export($uc, true));
		}
		
		//调用boss接口，获取客户名称、地址
		$customer_info 			= $this->ci->boss->getCustomerInfo($uc['customer_code']);
		$uc['customer_name']	= isset($customer_info['name'])    ? $customer_info['name'] : NULL;
		$uc['address']			= isset($customer_info['address']) ? $customer_info['address'] : NULL;
		
		//分配集群
		log_message('info', 'start allocate cluster for customer...');
		$cluster 		= 		new Cluster($uc['customer_code'] ,$uc['user_amount']);
		$cluster_info   =       array();
		if(!$cluster->isAllocated($uc['customer_code'], $uc['site_id'])){//判断该客户是否分配过集群
			$cluster_info = $cluster->getCluster();//获取一个集群
			if(!$cluster_info){
				$this->ci->boss->contract_callback($uc['callback'], $uc['request_id'], $uc['contract_id'], BOSS_CALLBACK_FAILED);
				throw new Exception('get cluster failed');
			}
			
			//将获取到的集群与客户的关联关系写入到数据库、更新相应集群中的用户量
			list($ret, $msg) = $cluster->allocate($uc['customer_code'], $uc['site_id'], $uc['site_url'], $uc['user_amount'], $cluster_info['clusterID']);
			if(!$ret){
				$this->ci->boss->contract_callback($uc['callback'], $uc['request_id'], $uc['contract_id'], BOSS_CALLBACK_FAILED);
				throw new Exception($msg);
			}
		}
		
		//将集群分配域写入portal,portal功能暂未上线，等上线后调试这段代码
		/*
		log_message('info', 'start add url skip rule to portal...');
		$this->ci->load->library('PortalLib', '', 'portal');
		if(!$this->ci->portal->getRule($uc['site_url'])){
			list($is_ok, $msg) = $this->ci->portal->addSkipRule($uc['site_url'], $cluster_info['url']);
			if(!$is_ok){
				$this->ci->boss->contract_callback($uc['callback'], $uc['request_id'], $uc['contract_id'], BOSS_CALLBACK_FAILED);
				throw new Exception('Add rule to portal is failed,the error message is-->'.$msg);
			}
		}
		*/
		
		//数据本地入库
		log_message('info', 'store data to local database...');
		$this->ci->load->model('account_model');
		list($is_success, $msg) = $this->ci->account_model->saveContractInfo($uc, $value);
		if(!$is_success){
			$this->ci->boss->contract_callback($uc['callback'], $uc['request_id'], $uc['contract_id'], BOSS_CALLBACK_FAILED);
			throw new Exception($msg);
		}
		
		//成功，回调boss接口
		log_message('info', 'contract open success,callback boss...');
		return $this->ci->boss->contract_callback($uc['callback'], $uc['request_id'], $uc['contract_id'], BOSS_CALLBACK_SUCCESS);
	}
	
	
	
	
	
	
	
}