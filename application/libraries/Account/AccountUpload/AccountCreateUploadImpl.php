<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 账号create上传操作
 * @file AccountCreateUploadImpl.php
 * @author caohongliang <hongliang.cao@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */


require_once(__DIR__.DIRECTORY_SEPARATOR.'AccountUploadInterface.php');
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
	 * 
	 * -...
	 */
	public function process($value){
                //$value = '{"customer_code":"000025","site_id":68738,"org_id":"46","users":[{"lastname":"FAFAFA","firstname":"","loginname":"fafafa@sb.com","open":true,"sex":"1","account":"1","position":"AAAACCC","mobile":"+8613355556666","officeaddress":"hubeixianning","country":""}]}';
                
		//整理参数,并做简单的校验
		$customer_code = isset($value['customer_code']) ? $value['customer_code'] : NULL;
		$site_id	   = isset($value['site_id']) ? $value['site_id'] : NULL;
		$org_id	       = isset($value['org_id']) ? $value['org_id'] : NULL;
		$users		   = isset($value['users']) ? $value['users'] : NULL;
		
		$invalid_params = array_filter(array($customer_code, $site_id, $users), 'is_null');
		if(count($invalid_params)>0){
			throw new Exception('Some important data is not found in upload data. please check out.the info is-->'.var_export($invalid_params, true));
		}
		
		//判断用户是否存在，区分用户开通还是关闭
		$create_users   = array(); 
		$disable_users  = array();
		
		//user信息整理
		$login_names = array();
		foreach($users as $k=>$user){
			//--判断用户是否存在
			$exist = $this->_existUser($user['loginname']);
			$open  = $user['open'];
			//--1.如果用户要求开通，但已经存在，则忽略
			//--2.如果用户要求关闭，但不存在，则忽略
			if( ($open && $exist) || (!$open && !$exist) ){
				log_message('info', 'user is already exist-->'.var_export($user, true));
				continue;
			}
			
			//--将需要开通和关闭分别存在不同的数组里
			if($open){
				$create_users[]   = $user;
			}else{
				$disable_users[]  = $user;
			}
		}
		
		//将需要关闭的用户，添加到关闭任务表里。如果此时没有需要创建的用户，则直接返回，任务结束！
		if(count($disable_users) > 0){
			$disable_value = array('customer_code'=>$customer_code, 'site_id'=>$site_id, 'users'=>$disable_users);
			$this->ci->upload_task->saveTask(ACCOUNT_DISABLE_UPLOAD, json_encode($disable_value));
		}
		
		if(count($create_users) == 0){
			return;
		}
		
		//ums创建组织，添加用户
		$ums_params = array();
		$dept_level = $this->ci->tags_model->getDepartmentLevels($site_id);//获取部门层级
		foreach($create_users as $k=>$user){
			
			//--组织部门名称。接口支持部门层级，以'/'分隔。如："产品部/设计组/UI"
			$ums_param_name = '';
			for($i=1;$i<=$dept_level;$i++){
				if(!empty($user['department'.$i])){// 选择部门要手选因为目前没有验证 否则department为空会导致失败 lwbbn
					$ums_param_name 				.= 	trim($user['department'.$i]).'/';
					$create_users[$k]['org_name'] 	 = 	$user['department'.$i];//该用户所属组织名称
				}else{
					break;
				}
			}
			$ums_param_name = trim($ums_param_name, '/ ');
			
			//--组织用户信息，即接口参数中的users字段
			$ums_param_user = array(
				'loginName'		=>	( isset($user['loginname']) 	? $user['loginname'] : '' ),
				'firstName'		=>	( isset($user['firstname']) 	? $user['firstname'] : ''),
				'displayName'   =>  ( isset($user['lastname'])  	? $user['lastname']  : ''),
				'lastName'   	=>	( isset($user['lastname'])  	? $user['lastname']  : ''),
				'email'			=>  ( isset($user['email'])     	? $user['email']     : ''),
				'userstatus'	=>	1,
				'passType'      =>  1,//加密类型,默认为md5
				'namepinyin'    =>  pinyin::utf8_to($user['lastname'], true),
				'sex'			=>  ( isset($user['sex'])     		? $user['sex']     : 0),//0-未设置 1-男 2-女
				'position'  	=>  ( isset($user['position']) 		? $user['position'] : '' ),
				'officePhone'   =>  ( isset($user['officephone']) 	? trim($user['officephone']) : '' ),
			);
			
			//--组合ums请求参数。将同部门下的用户放在一个数组分组下
			if( ! isset($ums_params[$ums_param_name])){
				$ums_params[$ums_param_name] = array(
					'name'=>$ums_param_name,
					'customercode'=>$customer_code,
					'type'=>3, //XXX 1:企业 2：生态企业 3：部门 4：生态企业部门 5：分公司,如果是生态企业开通帐号？
					'parentId'=>$org_id,
					'users'=>array($ums_param_user),
				);
			}else{
				$ums_params[$ums_param_name]['users'][] = $ums_param_user;
			}
			$ums_params[$ums_param_name]['users']['password'] = md5("111111");
			//--分账id。如果没有设置，则使用管理员的分账id
			$account_id = (isset($user['account']) && $user['account']) ? $user['account'] : $this->ci->account_model->getSystemManagerAccountId($site_id);
			if( ! $account_id){
				throw new Exception("get account id failed for user-->{$user['loginname']}");
			}
			$user_accounts[$user['loginname']] = $account_id;
			
		}
		
		$rst_users = array();//ums创建成功的用户
		if( ! ($rst_users = $this->ci->ums->createOrganizations( array_values($ums_params))) ){//--批量创建组织，并向组织下添加用户
			throw new Exception('create orgs to ums failed!');
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
		$boss_request_groups = array();
		$contract_id = $this->ci->account_model->getContractId($customer_code, $site_id);
		
		//从ums里创建成功的数据里提取boss请求参数
		foreach($rst_users as $rst_user){

			//--获取用户所在组织的nodeCode
			$node_code 					= 	isset($rst_user['orgNodeCode'])  ? $rst_user['orgNodeCode'] : '';
			if(empty($node_code)){
				log_message('error', 'not found nodeCode for user ['.$rst_user['loginName'].'].so skip this user!');
				continue;
			}
			
			//--获取用户的templateUUID和components,并根据templateUUID，对boss请求进行分组
			$templateUUID   = '';
			$components 	= $this->ci->power_model->getOrgPower($site_id, $node_code, $templateUUID);
			if(empty($components) OR empty($templateUUID)){
				log_message('error', 'not found components or templateUUId for user ['.$rst_user['loginName'].'].so skip this user!');
				continue;
			}
			
			if( !isset($boss_request_groups[$templateUUID])){
				$boss_request_groups[$templateUUID]['callback'] = BOSS_CALLBACK;
				$boss_request_groups[$templateUUID]['templateUUID'] = $templateUUID;
				$boss_request_groups[$templateUUID]['type'] = 'create';
				
				$boss_request_groups[$templateUUID]['customer']['customerCode']   = $customer_code;
				$boss_request_groups[$templateUUID]['customer']['contract']['id'] = $contract_id;
				$boss_request_groups[$templateUUID]['customer']['customerCode']   = $customer_code;
			}
			
			$tmp_user = array();
			$tmp_user['id'] 		= 	$rst_user['id'];
			$tmp_user['accountId']	=	$user_accounts[$rst_user['loginName']];
			
			//--这里user不再需要传components参数，新接口是sellingProducts。boss端可以根据templateUUID来找到权限项
			//$tmp_user['components'] =   json_decode($components, true);
			
			$boss_request_groups[$templateUUID]['customer']['users'][] = $tmp_user;
		}
		
		//根据tempalteUUID分组向boss请求开通
		foreach($boss_request_groups as $request){			
			//uc_request保存请求记录
			$request_with_id    = $this->ci->account_model->saveRequestInfo($request);
			if( ! $request_with_id){
				throw new Exception('save request to local db failed');
			}
			
			//调用boss开通接口
			log_message('info', 'send a boss requset');
			
			//--切换至新的接口（组合销售品统一开通）
			$boss_rst = $this->ci->boss->combinedAccount($request_with_id);
			//$boss_rst = $this->ci->boss->account($request_with_id);
					
			if(!$boss_rst){
				throw new Exception('send users info to boss failed');
			}
		}
		
		//返回
		return;
		
	}
	
	/**
	 * 判断用户是否存在
	 * @param unknown $login_names
	 */
	public function _existUser($login_name){
		//return $this->ci->ums->searchUserByLoginname($login_names);
		$user_info = $this->ci->ums->getUserByLoginName($login_name);
		if($user_info && $this->ci->ums->getUserProduct($user_info['id'], UC_PRODUCT_ID)){
			return true;
		}
		
		return false;
	}
	
	public function _getTemplateUUID(){
		
	}
	
}
