<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 账号enable上传操作
 * @file AccountEnableUploadImpl.php
 * @author caohongliang <hongliang.cao@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */

require_once(__DIR__.DIRECTORY_SEPARATOR.'AccountUploadInterface.php');
class AccountEnableUploadImpl extends AccountUploadInterface{
	
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * 启用帐号
	 * 
	 * -获取参数
	 * -获取用户所在的组织的nodeCode
	 * -获取用户的templateUUID
	 * -向boss发送请求
	 */
	public function process($value){
		//参数校验
		list($valid, $rst) = $this->checkParamForEnableAndDisable($value);
		if(!$valid){
			throw new Exception($rst);
		}
		
		//获取这个站点下个性化过的所有组织、个人
		$configed_user  		   = $this->ci->account_model->getConfigedUser($rst['customer_code'],$rst['site_id']);
		$configed_organization_ids = $this->ci->account_model->getConfigedOrganizationIds($rst['customer_code'],$rst['site_id']);
		
		//获取站点url,这是站点下所有用户的默认的属性模板id
		$site_url				   = $this->ci->account_model->getSiteUrl($rst['site_id']);
		if(empty($site_url)){
			throw new Exception("can not get site url by custom_code:{$rst['customer_code']} and site_id:{$rst['site_id']}");
		}
		
		//确定每个用户的权限模板
		foreach($rst['users'] as $k=>$user){
			//查看此用户的所有上级组织里，是否有个性化过权限的组织，有则使用该组织的node_code做为template_id
			if(count($configed_organization_ids) > 0){
				$_ids = explode('-', trim($user['_org_node_code'], '-'));
				while( count($_ids) > 0 ){
					$_id = array_pop($_ids);
					if(in_array($_id, $configed_organization_ids)){
						$t = '';
						if(count($_ids)>0){
							$t = '-'.implode($_ids).'-'.$_id;
						}else{
							$t = '-'.$_id;
						}
						$rst['users'][$k]['template_id'] = $t;
						break;
					}
				}
			}
		
			//如果以上没有找到templte_id，则使用站点url做为权限模板id
			if(!isset($rst['users'][$k]['template_id'])){
				$rst['users'][$k]['template_id'] = $site_url;
			}
				
			//查看此用户是否个性化过权限，有则需要在请求里将权限属性加上，模板id还是他的组织nodeCode或者site_url
			if(isset($configed_user[$user['id']])){
				$rst['users'][$k]['sellingProducts'] = $configed_user[$user['id']];
			}
		}
		
		//根据template_id将用户分组
		$template_groups = array();
		foreach($rst['users'] as $user){
			$template_groups[$user['template_id']][] = $user;
		}
		
		//向boss分组发送请求
		$failed_request_ids = array();
		foreach($template_groups as $templateUUID=>$users){
			//挑出个性化过的，单独发送
			foreach($users as $k=>$user){
				if(!empty($user['sellingProducts'])){
					list($flag, $request_id) = $this->sendBossRequest($rst['customer_code'], $rst['site_id'], array_column($users, 'id'), 'enable', $templateUUID, $user['sellingProducts']);
					if(! $flag){
						$failed_request_ids[] = $request_id;
					}
					unset($users[$k]);
				}
			}
				
			//剩下的一起发送
			if(count($users) > 0){
				list($flag, $request_id) = $this->sendBossRequest($rst['customer_code'], $rst['site_id'], array_column($users, 'id'), 'enable', $templateUUID);
				if(! $flag){
					$failed_request_ids[] = $request_id;
				}
			}
		}
		
		//result里记录发送失败的
		if(count($failed_request_ids) > 0){
			throw new Exception("some request send to boss is failed in this task.the request id is-->".var_export($failed_request_ids, true));
		}
	}
	
	
}
