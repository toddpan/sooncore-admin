<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 账号update上传操作 ，修改组织和用户信息
 * @file AccountUpdateUploadImpl.php
 * @author caohongliang <hongliang.cao@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */

require_once(__DIR__.DIRECTORY_SEPARATOR.'AccountUploadInterface.php');
require_once(APPPATH . 'libraries/chartopinyin.php');
class AccountUpdateUploadImpl extends AccountUploadInterface{
	
	public function __construct(){
		$this->ci = & get_instance();
		$this->ci->load->model('account_upload_task_model', 'upload_task');
		$this->ci->load->model('tags_model');
		$this->ci->load->model('power_model');
		$this->ci->load->model('account_model');
		$this->ci->load->library('UmsLib', '', 'ums');
		$this->ci->load->library('Tags', '', 'tags');
	}
	
	public function process($value){
		//参数校验
		log_message('info', 'start to check param.');
		list($valid, $rst) = $this->checkParamForUpdate($value);
		if(!$valid){
			throw new Exception($rst);
		}else{
			unset($value);
		}
		log_message('info', 'param is valid.');
		
		//获取根组织
		log_message('info', 'start to get root org id.');
		list($flag, $root_id) = $this->getRootId($rst['site_id']);
		if(!$flag){
			throw new Exception($root_id);
		}
		log_message('info', 'get root id success-->'.$root_id);
		
		//获取整个公司里，所有部门id与部门名称路径的映射关系
		log_message('info', 'start to get subtree from ums.');
		list($flag, $id_names_map) = $this->getIdDeptNamesMap($root_id);
		if(!$flag){
			throw new Exception($id_names_map);
		}
		
		//start update
		log_message('info', "now,it's time to update orginfo and userinfo.");
		$fail_msgs = array();
		foreach($rst['users'] as $user){
			//更改组织信息，创建组织，调岗
			//检查组织,三种传递组织信息的方法，通过parent_id、departments list、department1-10
			if(!empty($user['parent_id'])){
				if($user['parent_id'] != $user['_org_id']){
					//调岗之前，先检查parent_id的合法性
					$parent_org_info = $this->ci->ums->getOrganizationById($user['parent_id']);
					if(empty($parent_org_info['nodeCode'])){
						$fail_msgs[] = "invalid parent id[parent_id:{$user['parent_id']}]";
						log_message('error', "invalid parent id[parent_id:{$user['parent_id']}]");
						continue;
					}else{
						$_root_id = array_shift( explode('-',ltrim($parent_org_info['nodeCode']), '-') );
						if($_root_id != $root_id){
							$fail_msgs[] = "the parent_id is not belong to this site[parent_id:{$user['parent_id']}].";
							log_message('error', "the parent_id is not belong to this site[parent_id:{$user['parent_id']}].");
							continue;
						}
					}
					
					//调岗
					log_message('info', "change department for user[id:{$user['id']}].");
					$retChangeDept = $this->changeUserDepartment($user['id'],$user['_org_id'], $user['parent_id']);
					if(!$retChangeDept){
						$fail_msgs[] = "Change department for user[user_id:{$user['id']} failed]";
						log_message("error", "Change department for user[user_id:{$user['id']} failed]");
						continue;
					}
				}
			}else if( !empty($user['departments']) && count($user['departments'])>0 ){
				
				//创建组织，如果有必要的话
				list($flag,$parent_id) = $this->createOrganizationsByNames($user['departments'], $id_names_map, $root_id, $rst['customer_code']);
				if(!$flag){
					$fail_msgs[] = 'create organization by names failed.';
					log_message('error','create organization by names failed.');
					continue;
				}
				
				//调岗
				if($user['_org_id'] != $parent_id){//调岗
					log_message('info', "change department for user[id:{$user['id']}].");
					$retChangeDept = $this->changeUserDepartment($user['id'], $user['_org_id'], $parent_id);
					if(!$retChangeDept){
						$fail_msgs[] = "Change department for user[user_id:{$user['id']} failed]";
						log_message("error", "Change department for user[user_id:{$user['id']} failed]");
						continue;
					}
				}
			}else if(!empty($user['department1'])){
				//将部门参数转为list形式
				$names = array();
				$dept_level = 10;
				for($i=1; $i<=$dept_level; $i++){
					$dept_str = 'department'.$i;
					if(!empty($user[$dept_str])){
						$names[] = $user[$dept_str];
					}else{
						break;
					}
				}
				
				//创建组织，如果有必要的话
				list($flag,$parent_id) = $this->createOrganizationsByNames($names, $id_names_map, $root_id, $rst['customer_code']);
				if(!$flag){
					$fail_msgs[] = 'create organization by names failed.';
					log_message('error','create organization by names failed.');
					continue;
				}
				
				//调岗
				if($user['_org_id'] != $parent_id){//调岗
					log_message('info', "change department for user[id:{$user['id']}].");
					$retChangeDept = $this->changeUserDepartment($user['id'], $user['_org_id'], $parent_id);
					if(!$retChangeDept){
						$fail_msgs[] = "Change department for user[user_id:{$user['id']} failed]";
						log_message("error", "Change department for user[user_id:{$user['id']} failed]");
						continue;
					}
				}
			}else{
				log_message('info', "not change org info for user[user_id:{$user['id']}]");
			}
			
			//更改用户信息
			log_message('info', 'start to update userinfo.');
			$user_info = array();
			$user_info['id'] = $user['id'];
			
			//如果需要修改login_name，则首先需要检查login_name在数据库中是否唯一，除了login_name，其他字段可随意更改
			if(isset($user['loginname'])){
				$uinfo 	  = $this->ci->ums->getUserByLoginName($user['loginname']);
				if( !empty($uinfo['id']) && ($uinfo['id'] != $user['id']) ){
					$fail_msgs[] = "login_name must be unique.a user[id:{$uinfo['id']}] which has the same login_name already exists";
					log_message('error', "login_name must be unique.a user[id:{$uinfo['id']}] which has the same login_name already exists");
					continue;
				}else{
					$user_info['loginName'] = $user['loginname'];
				}
			}
			
			if(isset($user['firstname'])) 		{ $user_info['firstName'] = $user['firstname'];}
			if(isset($user['lastname'])) 		{ $user_info['lastName'] = $user['lastname'];}
			if(isset($user['displayname'])) 	{ $user_info['displayName'] = $user['displayname'];}
			if(isset($user['email'])) 			{ $user_info['email'] = $user['email'];}
			if(isset($user['userstatus'])) 		{ $user_info['userstatus'] = $user['userstatus'];}
			if(isset($user['passtype'])) 		{ $user_info['passType'] = $user['passtype'];}
			if(isset($user['password'])) 		{ $user_info['password'] = $user['password'];}
			if(isset($user['namepinyin'])) 		{ $user_info['namepinyin'] = $user['namepinyin'];}
			if(isset($user['sex'])) 			{ $user_info['sex'] = $user['sex'];}
			if(isset($user['mobile'])) 			{ $user_info['mobileNumber'] = $user['mobile'];}
			if(isset($user['position'])) 		{ $user_info['position'] = $user['position'];}
			if(isset($user['officephone'])) 	{ $user_info['officePhone'] = $user['officephone'];}
			if(isset($user['externalUserName'])){ $user_info['externalUserName'] = $user['externalUserName'];}
			
			$sucess = $this->ci->ums->updateUserInfo($user_info);
			if(!$sucess){
				$fail_msgs[] = "update user info failed.user_id->".$user['id'];
				log_message("error", "update user info failed.user_id->".$user['id']);
				continue;
			}
			log_message('info', 'update userinfo success.');

		}
		
		//保存用户标签信息
		log_message('info', 'start to save custom tag to local db.');
		$this->saveCustomTags($rst['customer_code'], $rst['site_id'], $rst['users']);
		log_message('info', 'save custom tag to local db finished.');

		//check
		if(count($fail_msgs)>0){//如果有失败的user,则此次任务认为失败
			throw new Exception('some user update failed,msg->'.implode('|',$fail_msgs));
		}
	}
	
	/**
	 * 根据部门名称串创建组织，返回最后一个组织的id
	 * @param array 	names 部门名称
	 * @param array 	$id_names_map 部门id与部门串映射表
	 * @param int   	$root_id 根组织id
	 * @param string 	$customer_code 客户编码
	 * @return array($flag, $rst);
	 */
	private function createOrganizationsByNames($names,$id_names_map,$root_id,$customer_code){
		if(empty($names)){
			return array(false, 'array is empty'); 
		}
		
		$new_depts = array();
		$parent_id = $root_id;
		do{
			if($_id = array_search(implode('/',$names), $id_names_map)){
				$parent_id = $_id;
				break;
			}else{
				$new_depts[] = array_pop($names);
			}
		}while(count($names)>0);
		
		if(!empty($new_depts)){//开始创建组织
			$new_depts = array_reverse($new_depts);
			foreach($new_depts as $new_dept){
				$org_info = array(
						'name'=>$new_dept,
						'parentId'=>$parent_id,
						'customerCode'=>$customer_code,
						'type'=>ORG_DEPARTMANT,
				);
				$new_dept_id = $this->ci->ums->createOrganization($org_info);
				if(!$new_dept_id){
					return  array(false, 'add new department failed.parentId->'.$parent_id);
					break;
				}else{
					$parent_id = $new_dept_id;
				}
			}
		}	
		return array(true, $parent_id);
	}
	
	
	/**
	 * 获取站点下所有的部门id与部门名称串的映射关系，如array('100'=>'研发部/打闹组');
	 * @param $root_id 根组织id
	 * @return array;
	 */
	private function getIdDeptNamesMap($root_id){
		//获取所有的根组织
		$orgs = $this->ci->ums->getOrganization($root_id, 'subtree', '1,3,5');
		if($orgs === false){
			return array(false, "get subtree from ums failed");
		}
		
		//首先得到组织id与name的映射关系
		$id_name_map = array();
		foreach($orgs as $org){
			$id_name_map[$org['id']] = $org['name'];
		}
		
		//然后得到组织id与name串的映射关系
		$id_names_map = array();
		foreach($orgs as $org){
			//$ids = explode('-',ltrim($org['nodeCode'],'-'.$root_id));//去掉根组织，转为数组
			$ids = explode('-',ltrim($org['nodeCode'],'-'));//转为数组
			array_shift($ids);//去掉根组织
			
			$names = '';
			foreach($ids as $id){
				//一旦发现非法的nodeCode,严肃处理！
				if(!isset($id_name_map[$id])){
					return array(false, 'found invalid node_code.please checkout.the org is-->'.var_export($org)
						.'org id is-->'.$id.' this org id not existed in this site');
				}
				
				$names = $names.$id_name_map[$id].'/';
			}
			$id_names_map[$org['id']] = trim($names, '/');
		}
		
		return array(true, $id_names_map);
	}
	

	
	/**
	 * 部门调岗
	 * @param int $user_id
	 * @param int $from
	 * @param int $to
	 * @return array
	 */
	private function changeUserDepartment($user_id,$from, $to){
		return $this->ci->ums->changeUserOrg($user_id,$from, $to);
	}
	
	/**
	 * 参数校验
	 * @param array $value
	 * @return array
	 */
	private function checkParamForUpdate($value){
		//参数检验-必填项
		if(empty($value['customer_code'])){
			return array(false, 'customer_code is required!');
		}
		
		if(empty($value['site_id'])){
			return array(false, 'site_id is required!');
		}
		
		if(empty($value['users']) OR !is_array($value['users'])){
			return array(false, 'users is required.or users should not be empty!');
		}		
		
		//用户信息校验
		$e_message = '';
		foreach($value['users'] as $k=>$user){
			
			if(!is_array($user)){
				$e_message = "invalid json format!";
				break;
			}
			
			//用户id为必填项
			if(empty($user['id'])){
				$e_message = 'User id is required.error param:'.var_export($user, true);
				break;
			}
				
			//通过本地数据库，校验用户是否属于当前站点,如果不属于，则中断任务
			if( !$this->isBelongToSite($value['customer_code'], $value['site_id'], $user['id']) ){
				$e_message = "user(user_id:{$user['id']}) is not belong to this site.or has not opened bee product.customer_code:{$value['customer_code']}.site_id:{$value['site_id']}";
				break;
			}
			
			//获取用户的父组织信息
			$org_info = $this->ci->ums->getOrganizationsByUserId($user['id']);
			if(!$org_info OR count($org_info)>1){
				$e_message = "Get organization info from ums failed, or this user belong to mutiple organizations.[user_id:{$user['id']}]";
				break;
			}else{
				$value['users'][$k]['_org_id'] 			= 	$org_info[0]['id'];
				$value['users'][$k]['_org_node_code'] 	= 	$org_info[0]['nodeCode'];
			}
		}
		
		if($e_message !== ''){
			return array(false, $e_message);
		}
		
		return array(true, $value);
	}
}
