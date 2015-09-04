<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 账号create上传操作
 * @file AccountCreateUploadImpl.php
 * @author caohongliang <hongliang.cao@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v2.0
 */


require_once(__DIR__.DIRECTORY_SEPARATOR.'AccountUploadInterface.php');
require_once(__DIR__.DIRECTORY_SEPARATOR.'AccountUpdateUploadImpl.php');
require_once(APPPATH . 'libraries/chartopinyin.php');
class AccountCreateUploadImpl extends AccountUploadInterface{
	
	public function __construct(){		
		$this->ci = & get_instance();
		$this->ci->load->model('account_upload_task_model', 'upload_task');
		$this->ci->load->model('tags_model');
		$this->ci->load->model('power_model');
		$this->ci->load->model('account_model');
		$this->ci->load->library('UmsLib', '', 'ums');
		$this->ci->load->library('BossLib', '', 'boss');
		$this->ci->load->library('Tags', '', 'tags');
	}
	
	
	
	/**
	 * 创建帐号
	 */
	public function process($value){
		//参数校验
		log_message('info', 'check param.');
		list($invalidParam,$rst) = $this->checkParamForCreate($value);
		if( ! $invalidParam){
			throw new Exception($rst);
		}
		log_message('info', 'param is valid.');
		
		//设置变量
		$customerCode 		= $rst['customer_code'];
		$siteId       		= $rst['site_id'];
		$this->deptLevel 	= 10;//部门层级
		unset($value);//clear

		//判断该用户是否已经在ums中存在。区别老用户、新用户
		log_message('info', "There are ".count($rst['users'])." users in this task.");
		$oldUsers = array();
		$newUsers = array();
		foreach($rst['users'] as $user){
			if($user['isExistedUser']){
				$oldUsers[] = $user;
			}else{
				$newUsers[$user['loginname']] = $user;//loginName做为key，后面会用到
			}
		}
		unset($rst);//clear

		//新用户，在ums中添加用户信息、组织信息
		log_message('info', 'start to add new users to ums.');
		if(count($newUsers)>0) {
			log_message('info', 'There are '.count($newUsers).' new Users  to add in this task');
			list($success, $newUsers) = $this->addUserAndOrganization($customerCode, $siteId, $newUsers);//with id
			if(!$success){
				throw new Exception($newUsers);
			}
		}else{
			log_message('info', 'There are no new Users to add in this task');
		}
		
		//老用户，在ums中修改用户信息、变更或添加组织信息
		log_message('info', 'start to update old users.');
		if(count($oldUsers)>0) {
			log_message('info', 'There are '.count($oldUsers).' old Users to update in this task');
			list($success, $oldUsers) = $this->updateUserAndOrganization($customerCode, $siteId, $oldUsers);
			if(!$success){
				throw new Exception($oldUsers);
			}
		}else{
			log_message('info', 'There are no old Users to update in this task');
		}

		//本地保存自定义标签值
		log_message('info', 'start to save custom tag to local db.');
		$allUsers = array_merge($newUsers, $oldUsers);	
		$this->saveCustomTags($customerCode, $siteId, $allUsers);
		log_message('info', 'save custom tag to local db finished.');

		//过滤出需要开通产品的用户
		log_message('info', 'start to check users which need to open uc product.');
		foreach($allUsers as $k=>$user){
			if( isset($user['open'])  && ! $user['open']){//不开通uc产品
				log_message('info', "this user[id:{$user['id']},loginName:{$user['umsUserInfo']['loginname']}] needn't to open product.");
				unset($allUsers[$k]);
				continue;
			}

			$productInfo = $this->ci->ums->getUserProduct($user['id'], UC_PRODUCT_ID);//已开通uc产品的用户
			if(!empty($productInfo)){
				log_message('info', "this user[id:{$user['id']},loginName:{$user['umsUserInfo']['loginname']}] have already  opened uc product.so just skip.");
				unset($allUsers[$k]);
				continue;
			}
			
		}
		log_message('info', "check finished.");

		//为用户开通蜜蜂产品
		if(count($allUsers)>0){
			log_message('info', "there are ".count($allUsers)." users to open uc product.");
			list($success, $msg) = $this->sendBossRequest($customerCode, $siteId, $allUsers);
			if(!$success){
				throw new Exception($msg);
			}
		}else{
			log_message('info', 'there are no users to open uc product.task finish.');
		}

		//返回
		return;
	}
	
	//create任务参数检验
	public function checkParamForCreate($value){
		if(empty($value['customer_code'])){
			return array(false, 'customer_code is required!');
		}
		
		if(empty($value['site_id'])){
			return array(false, 'site_id is required!');
		}

		if(!$this->isvalidCustomerCodeAndSiteId($value['customer_code'], $value['site_id'])){
			return array(false, "invalid customerCode[{$value['customer_code']}] or siteId[{$value['site_id']}]");
		}

		list($success, $rootId) = $this->getRootId($value['site_id']);//获取站点根组织id
		if(!$success){
			return array(false, 'get root id failed.');
		}else{
			$this->rootId = $rootId;
			log_message('info', 'get root id->'.$rootId);
		}

		if( empty($value['users']) OR !is_array($value['users']) OR count($value['users'])==0 ){
			return array(false, 'the param users is required.or users should not be empty!');
		}

		$invalidParamMsgs = array();
		foreach($value['users'] as $k=>$user){
			
			//根据用户信息，查看用户是否已经存在于ums中
			$isExistedUser = false;
			$umsUserInfo   = array();
			if( ! empty($user['id']) ){
				$umsUserInfo = $this->ci->ums->getUserById($user['id']);
				if(!$umsUserInfo){
					$invalidParamMsgs[] = "userId:[{$user['id']}] not exists in ums.";
					continue;
				}else{
					$isExistedUser = true;
				}
				
			}else{
				if( ! empty($user['loginname']) ){
					$email 	= !empty($user['email']) ? $user['email'] : '';
					$mobile = !empty($user['mobile']) ? $user['mobile'] : '';
					
					$umsUserInfo = $this->existUser($user['loginname'], $email, $mobile);

					if($umsUserInfo){
						$value['users'][$k]['id'] = $umsUserInfo['id'];
						$isExistedUser = true;
					}else{
						$isExistedUser = false;
					}
				}else{
					$invalidParamMsgs[] = "loginname is required.->".var_export($user,true);
					continue;
				}
			}

			$value['users'][$k]['isExistedUser'] = $isExistedUser;
			$value['users'][$k]['umsUserInfo']   = $umsUserInfo;
			
			//如果用户已经存在，则判断用户是否属于本站点。
			if($isExistedUser){
				$loginName = isset($user['loginname']) ? $user['loginname'] : $user['umsUserInfo']['loginName'];
				$orgs 		= $this->ci->ums->getOrganizationsByUserId($umsUserInfo['id']);//获取用户上级组织
				if(is_array($orgs) && count($orgs)>1){
					log_message('info', "user[id:{$user['umsUserInfo']['id']},loginName:{$loginName}] belong to mutiple organization,just skip.");
					continue;
				}else if(is_array($orgs) && (count($orgs) == 1) && !$this->isValidOrgId($orgs[0]['id'])){
					$invalidParamMsgs[] = "user[id:{$umsUserInfo['id']},loginName:{$umsUserInfo['loginName']}] already exist. and it does not belong to this site,you can't do anything.";
					continue;
				}
				$value['users'][$k]['oldParentOrganizations'] = $orgs;
			}
			

			//如果提供了parent_id，则必须是本站的
			if( isset($user['parent_id']) && ! $this->isValidOrgId($user['parent_id']) ){
				$invalidParamMsgs[] = "invalid parent_id for user->".var_export($user, true);
				continue;
			}

			//如果是新用户，则必须包含组织信息
			if( ! $isExistedUser ){
				if(empty($user['parent_id']) 
					&& empty($user['department1']) 
					&& empty($user['departments'][0])
				){
					$invalidParamMsgs[] = "department info is required for new user.->".var_export($user);
					continue;
				}
			}
		}

		//检查用户信息是否有误
		if(count($invalidParamMsgs)>0){
			return array(false, json_encode($invalidParamMsgs));
		}

		return array(true, $value);	
	}

	/**
	 * 判断用户已存在
	 * @loginName 登录名
	 * @email     邮箱
	 * @mobile    手机
	 * @return 用户信息
	 *
	*/
	public function existUser($loginName, $email='', $mobile=''){
		$userInfoByLoginName = $this->ci->ums->getUserByLoginName($loginName);
		if(empty($userInfoByLoginName)){
			
			$userInfo = $this->ci->ums->checkUserVerified($email, $mobile);
			
			if( empty($userInfo['email']) ){
				if(empty($userInfo['mobile'])){
					return false;
				}else{
					$userId = $userInfo['mobile']['userId'];
					return $this->ci->ums->getUserById($userId);
				}

			}else{
				$userId = $userInfo['email']['userId'];
				return $this->ci->ums->getUserById($userId);
			}

		}else{
			return $userInfoByLoginName;
		}
	}

	/*
     * 添加用户和组织信息
	*/
	public function addUserAndOrganization($customerCode, $siteId, $users){
		
		//ums创建组织，添加用户
		$umsParams 		= array();//ums批量创建组织用户参数
		
		foreach($users as $loginName=>$user){
			$userInfo = array();
			
			$fullName = NULL;//全名，用来生成拼音缩写和默认的展示名称
			if(isset($user['firstname']) OR isset($user['lastName'])){
				$firstName = isset($user['firstname']) ? $user['firstname'] : '';
				$lastName  = isset($user['lastname'])  ? $user['lastname']  : '';  
				$fullName  = $lastName.$firstName;
			}


			if(!empty($user['displayname'])) 	{ //展示名称
				$userInfo['displayName'] = $user['displayname'];
			}else{
				if(!is_null($fullName)){
					$userInfo['displayName'] = $fullName;
				}
			}

			if(isset($user['namepinyin'])) 		{ //拼音缩写
				$userInfo['namepinyin'] = $user['namepinyin'];
			}else{
				if(!is_null($fullName)){
					$userInfo['namepinyin'] = pinyin::utf8_to($fullName, true);
				}
			}

			if(isset($user['loginname'])) 		{ $userInfo['loginName'] = $user['loginname'];}			
			if(isset($user['firstname'])) 		{ $userInfo['firstName'] = $user['firstname'];}
			if(isset($user['lastname']))  		{ $userInfo['lastname']  = $user['lastname'];}
			if(isset($user['email'])) 			{ $userInfo['email'] = $user['email'];}
			if(isset($user['password'])) 		{ $userInfo['password'] = $user['password'];}
			if(isset($user['mobile'])) 			{ $userInfo['mobileNumber'] = $user['mobile'];}
			if(isset($user['position'])) 		{ $userInfo['position'] = $user['position'];}
			if(isset($user['officephone'])) 	{ $userInfo['officePhone'] = $user['officephone'];}
			if(isset($user['externalUserName'])){ $userInfo['externalUserName'] = $user['externalUserName'];}
			//TODO 国家、办公地址
			
			$userInfo['userstatus'] 	=  isset($user['userstatus']) 	? $user['userstatus'] : 1;
			$userInfo['passtype']   	=  isset($user['passtype'])   	? $user['passtype']        : 1;
			$userInfo['sex']   	 		=  isset($user['sex'])   		? $user['sex']        : 0;
			$userInfo['mailVerified']  	=  isset($user['email']);
			$userInfo['phoneVerified'] 	=  isset($user['mobile']);
			
			//创建组织，将用户添加到组织下
			if(!empty($user['parent_id'])){
				$userId = $this->ci->ums->addNewUserToOrg($userInfo, $user['parent_id']);
				if(!$userId){
					return array(false, 'add new user to ums failed.->loginName:'.$user['loginname']);
				}else{
					$users[$loginName]['id'] = $userId;
				}
			}else if(!empty($user['departments']) OR !empty($user['department1'])){
				//组织部门名称。接口支持部门层级，以'/'分隔。如："产品部/设计组/UI"
				$deptNames = '';
				if(!empty($user['departments'])){
					$deptNames = implode('/',$user['departments']);
				}else{
					for($i=1;$i<=$this->deptLevel;$i++){
						if(!empty($user['department'.$i])){
							$deptNames 	.= 	trim($user['department'.$i]).'/';
						}else{
							break;
						}
					}
				}
				$deptNames = trim($deptNames, '/ ');

				//组合ums请求参数。将同部门下的用户放在一个数组分组下
				if( ! isset($umsParams[$deptNames]) ){
					$umsParams[$deptNames] = array(
						'name'=>$deptNames,
						'customercode'=>$customerCode,
						'type'=>3, // 1:企业 2：生态企业 3：部门 4：生态企业部门 5：分公司,如果是生态企业开通帐号？
						'parentId'=>$this->rootId,
						'users'=>array($userInfo),
					);
				}else{
					$umsParams[$deptNames]['users'][] = $userInfo;
				}
			}
	
		}

		//批量创建组织和用户
		if(!empty($umsParams)){
			$rstUsers = array();//ums创建成功的用户
			if( ! ($rstUsers = $this->ci->ums->createOrganizations( array_values($umsParams))) ){//--批量创建组织，并向组织下添加新用户
				return array(false, 'create orgs to ums failed!');
			}
			foreach($rstUsers as $rstUser){
				$rstLoginName = $rstUser['loginName'];
				if(isset($users[$rstLoginName])){
					$users[$rstLoginName]['id'] = $rstUser['id'];
					//$users[$rstLoginName]['orgNodeCode'] = $rstUser['orgNodeCode'];
				}
			}
		}
		

		return array(true, $users);
	}

	

	//修改用户和组织信息
	public function updateUserAndOrganization($customerCode, $siteId, $users){
		
		foreach($users as $k=>$user){
			//ums信息
			if(    !isset($user['umsUserInfo']['mailVerified'])
				OR !isset($user['umsUserInfo']['phoneVerified'])
				OR empty($user['umsUserInfo']['id'])
			){
				continue;
			}

			$mailVerified 	= $user['umsUserInfo']['mailVerified'];
			$phoneVerified 	= $user['umsUserInfo']['phoneVerified'];
			$userId   		= $user['umsUserInfo']['id'];

			$msg = "user info update:userid[{$userId}].";
			if($mailVerified)   $msg .= "email[{$user['umsUserInfo']['email']}] is verified.";
			if($phoneVerified)  $msg .= "mobile[{$user['umsUserInfo']['mobileNumber']}] is verified.";
			log_message('info', $msg);
			
			//更新用户信息，如果用户原来的手机或者邮箱认证过，则不更新相应的字段
			$userInfo = array();
			$userInfo['id']  = $userId;
			if(isset($user['email']) && $mailVerified) 			{ $userInfo['email'] = $user['email'];}
			if(isset($user['mobile']) && $phoneVerified) 		{ $userInfo['mobileNumber'] = $user['mobile'];}

			if(isset($user['loginname'])) 		{ $userInfo['loginName'] = $user['loginname'];}
			if(isset($user['firstname'])) 		{ $userInfo['firstName'] = $user['firstname'];}
			if(isset($user['lastname'])) 		{ $userInfo['lastName'] = $user['lastname'];}
			if(isset($user['displayname'])) 	{ $userInfo['displayName'] = $user['displayname'];}
			if(isset($user['email'])) 			{ $userInfo['email'] = $user['email'];}
			if(isset($user['userstatus'])) 		{ $userInfo['userstatus'] = $user['userstatus'];}
			if(isset($user['passtype'])) 		{ $userInfo['passType'] = $user['passtype'];}
			if(isset($user['password'])) 		{ $userInfo['password'] = $user['password'];}
			if(isset($user['namepinyin'])) 		{ $userInfo['namepinyin'] = $user['namepinyin'];}
			if(isset($user['sex'])) 			{ $userInfo['sex'] = $user['sex'];}
			if(isset($user['position'])) 		{ $userInfo['position'] = $user['position'];}
			if(isset($user['officephone'])) 	{ $userInfo['officePhone'] = $user['officephone'];}
			if(isset($user['externalUserName'])){ $userInfo['externalUserName'] = $user['externalUserName'];}
			//TODO 国家、地区

			//更新用户信息
			$rst = $this->ci->ums->updateUserInfo($userInfo);
			if(!$rst){
				log_message("error", "update user info failed.user_id->".$user['id']);
				continue;
			}
			
			//更新用户部门信息，添加部门，调岗
			$loginName = isset($user['loginname']) ? $user['loginname'] : $user['umsUserInfo']['loginName'];
			if(!empty($user['parent_id'])){
				//获取用户的父组织
				$orgs = $user['oldParentOrganizations'];
				if(empty($orgs)){
					$this->ci->ums->addUserToOrg($user['umsUserInfo']['id'], $user['parent_id']);
				}else if(count($orgs)==1 && $orgs[0]['id'] != $user['parent_id']){
					$this->ci->ums->changeUserOrg($userId, $orgs[0]['id'], $user['parent_id']);
				}
				
			}else if(!empty($user['departments']) OR !empty($user['department1'])){
				//根据部门名称序列，创建组织。
				$deptNames = '';
				if(!empty($user['departments'])){
					$deptNames = implode('/',$user['departments']);
				}else{
					for($i=1;$i<=$this->deptLevel;$i++){
						if(!empty($user['department'.$i])){
							$deptNames 	.= 	trim($user['department'.$i]).'/';
						}else{
							break;
						}
					}
				}
				$deptNames = trim($deptNames, '/ ');

				$umsParams = array(
					'name'=>$deptNames,
					'customercode'=>$customerCode,
					'type'=>3, // 1:企业 2：生态企业 3：部门 4：生态企业部门 5：分公司,如果是生态企业开通帐号？
					'parentId'=>$this->rootId,
				);
				$this->ci->ums->createOrganizations(array($umsParams));//批量创建组织

				//用户解除与原来组织的绑定关系，绑定在新的组织下
				$info = array(
					array('loginName'=>$loginName,'organizationName' => $deptNames,),
				);
				$rst = $this->ci->ums->updateUserAndOrganization($customerCode, $info);
				if(!$rst OR count($rst['failed'])>0){
					log_message('info', "update user[loginName:{$loginName}] and organization failed.");
					continue;
				}
			}
					
		}
		
		return array(true, $users);
	}



	/**
	 * 至此，ums中已经创建好了组织和用户信息。由于boss参数templateUUID的缘故，
	 * 这里需要根据templateUUID来分组请求boss开通。
	 * 
	 * 1.boss中的参数templateUUID是与用户的权限组建的获取位置相关的
	 * 2.站点X下一次有组织A、B、C。组织ABC呈父子级关系
	 * 3.现在开通组织C下的一个用户甲，如果甲的权限组件是从C组织里获取的，则templateUUID等于C的node_code
	 * 4.如果组织C中没有设置权限组件，甲的权限组件是从组织B里获取的，则templateUUID等于B的node_code
	 * 5.依此类推，如果最终ABC都没有设置权限项，则用户甲的templateUUID是站点url
	 */
	public function sendBossRequest($customerCode, $siteId, $allUsers){
		$boss_request_groups = array();
		$contract_id = $this->ci->account_model->getContractId($customerCode, $siteId);
		
		
		//获取这个站点下个性化过的所有组织、个人
		$configed_user  		   = $this->ci->account_model->getConfigedUser($customerCode,$siteId);
		$configed_organization_ids = $this->ci->account_model->getConfigedOrganizationIds($customerCode, $siteId);
		
		//获取站点url,这是站点下所有用户的默认的属性模板id
		$site_url				   = $this->ci->account_model->getSiteUrl($siteId);
		if(empty($site_url)){
			return array(false, "can not get site url by custom_code:{$customerCode} and site_id:{$siteId}");
		}

		//获取系统管理员的账户id
		$accountId = $this->ci->account_model->getSystemManagerAccountId($siteId);
		if( ! $accountId){
			return array(false, "get system manager accountId from local db failed");
		}

		foreach($allUsers as $k=>$user){
			//查看此用户的所有上级组织里，是否有个性化过权限的组织，有则使用该组织的node_code做为template_id
			$templateUUID = $site_url;//默认是站点url
			
			$orgInfo = $this->ci->ums->getOrganizationByUserId($user['id']);
			if(count($configed_organization_ids) > 0 && !empty($orgInfo)){
				$_ids = explode('-', trim($orgInfo['nodeCode'], '-'));
				while( count($_ids) > 0 ){
					$_id = array_pop($_ids);
					if(in_array($_id, $configed_organization_ids)){
						$t = '';
						if(count($_ids)>0){
							$t = '-'.implode($_ids).'-'.$_id;
						}else{
							$t = '-'.$_id;
						}
						$templateUUID = $t;
						break;
					}
				}
			}
			
			

			//账户id。如果没有设置，则使用管理员的账户id
			if(empty($user['account'])){
				$allUsers[$k]['account'] = $accountId;
			}
			


			if( !isset($boss_request_groups[$templateUUID])){
				$boss_request_groups[$templateUUID]['callback'] = BOSS_CALLBACK;
				$boss_request_groups[$templateUUID]['templateUUID'] = $templateUUID;
				$boss_request_groups[$templateUUID]['type'] = 'create';
				
				$boss_request_groups[$templateUUID]['customer']['customerCode']   = $customerCode;
				$boss_request_groups[$templateUUID]['customer']['contract']['id'] = $contract_id;
				$boss_request_groups[$templateUUID]['customer']['customerCode']   = $customerCode;
			}
			
			$tmp_user = array();
			$tmp_user['id'] 		= 	$user['id'];
			$tmp_user['accountId']	=	empty($user['account']) ? $accountId : $user['account'];
			
			
			//查看此用户是否个性化过权限，有则需要在请求里将权限属性加上，模板id还是他的组织nodeCode或者site_url
			if(isset($configed_user[$user['id']])){
				$tmp_user['sellingProducts'] = $configed_user[$user['id']];
			}
			
			$boss_request_groups[$templateUUID]['customer']['users'][] = $tmp_user;
		}
		
		//根据tempalteUUID分组向boss请求开通
		foreach($boss_request_groups as $request){			
			//--uc_request保存请求记录
			$request_with_id    = $this->ci->account_model->saveRequestInfo($request);
			if( ! $request_with_id){
				return array(false, 'save request to local db failed');
			}
			
			//--调用boss开通接口
			log_message('info', 'start to send a request to boss,the request id is-->'.$request_with_id['requestId']);
			
			//--切换至新的接口（组合销售品统一开通）
			$boss_rst = $this->ci->boss->combinedAccount($request_with_id);
					
			if(!$boss_rst){
				return array(false, 'send users info to boss failed,the request id is-->'.$request_with_id['requestId']);
			}
		}
	}

	//判断用户的父组织id是否合法
	public function isValidOrgId($orgId){
		if( ! isset($this->rootId)){
			return false;
		}

		$orgInfo = $this->ci->ums->getOrganizationById($orgId);
		if(! $orgInfo){
			return false;
		}


		$_rootId = array_shift( explode('-',ltrim($orgInfo['nodeCode'],'-')) );
		if($_rootId != $this->rootId){
			return false;
		}

		return true;
	}
	
}
