<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 账号create操作
 * @file AccountCreateProcessImpl.php
 * @author caohongliang <hongliang.cao@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */

require_once(__DIR__.DIRECTORY_SEPARATOR.'AccountProcessInterface.php');
require_once(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Common'.DIRECTORY_SEPARATOR.'Array2XML.php');

class AccountCreateProcessImpl extends AccountProcessInterface{
	
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * @brief 创建帐号
	 * @detail
	 * 1.从boss的请求数据中提取开通相关信息
	 * 2.检查验证，如果参数不合法，则报异常，终止任务
	 * 3.设置成员变量，以供全局调用
	 * 4.依次去开通用户
	 * 5.检查是否所有的账号都开通成功，如果是，则callback成功，否则callback失败，任务记log
	 * 
	 * @param array $value boss端请求数据
	 * @return boolean|throw exception
	 */
	public function process($value){
		#从boss的请求数据中提取开通相关信息
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
		$this->is_manager   		= 		($uc['auth'] == AUTH_IS_MANAGER && $uc['type'] == 'create') ? true : false;//是否为管理员
		$this->uc					= 		 $uc;
		log_message('info', 'seting global variable finished.');
		
		#依次去开通用户
		log_message('info', 'start to open account.');
		foreach($uc['users'] as $user){
			
			/**
			 * 如果有一个用户在开通的过程中遇到问题，则跳过，继续开通剩下的
			 * 这里加异常处理，防止调用函数将异常抛向最顶层，从而中断任务
			 */
			try{
				list($is_ok, $msg) = $this->_openAccount($user);
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
		log_message('info', 'opening account finished.');
		
		#检查是否所有的账号都开通成功，如果是，则callback成功，否则callback失败，任务记log
		log_message('info', 'start to checkout users which open failed.');
		if(!empty($failed_list)){
			$this->ci->boss->account_callback($uc['callback'], $uc['request_id'], $uc['contract_id'], BOSS_CALLBACK_FAILED ,$success_list, $failed_list);
			throw new Exception('some user is open failed., the fail list is-->'.var_export($failed_list, true));
		}else{
			$this->ci->boss->account_callback($uc['callback'], $uc['request_id'], $uc['contract_id'], BOSS_CALLBACK_SUCCESS ,$success_list, $failed_list);
			log_message('info', 'wow...all user is successed opened.please wait for boss system callback,then you can use these accounts with happy!');
			return;
		}
		log_message('info', 'checkouting users finished.');
	
	}
	
	/**
	 * @brief 开通用户
	 * @detail
	 * 1.如果是管理员，则执行管理员开通的一些操作
	 * 2.向ucc创建组织交换机
	 * 3.向ucc创建同事关系
	 * 4.向uniform开通会议
	 * 5.向ums开通用户产品
	 * 6.重置密码
	 * 7.本地保存用户信息
	 * 8.发送开通邮件
	 * 9.开通
	 * 
	 * @param array $user 
	 * @return array array(boolean, $msg)
	 */
	private function _openAccount($user){
		#如果是系统管理员，则执行管理员开通的一些操作
		log_message('info', 'start to do some operation for manager(if it is).');
		if($this->is_manager){
			list($is_ok, $msg) = $this->_openManager($user);
			if(!$is_ok){
				return array(false, $msg);
			}
		}
		log_message('info', 'doing some operation for manager(if it is) finished.');
		
		#向ucc创建组织交换机
		#-ucc会检查组织交换机是否已经创建,如果已经创建，则不再创建
		log_message('info','start to create org exchange for user.');
		$org_info = $this->ci->ums->getOrganizationByUserId($user['id']);//获取当前用户所在的组织
		if($org_info && ! $this->ci->ucc->createOrgExchange($this->uc['site_id'], $org_info['id'])){
			return array(false, 'create org exchage for user '.$user['id'].' failed');
		}
		log_message('info','creating org exchange for user finished.');
		
		#向ucc创建同事关系
		#-如果创建的用户是管理员，则为组织管理者，否则不是（是否为组织管理者 0-不是 1-是）。
		log_message('info', 'start to create colleage relationship for user.');
		if(!$this->ci->ucc->createColleague($user['id'], $org_info['id'], $org_info['parentId'], ($this->is_manager ? 1 : 0))){
			return array(false, 'create colleague to ucc failed.');
		}
		log_message('info', 'creating colleage relationship for user finished.');
		
		
		#向uniform开通会议
		#-uniform接口数据xml格式，这里需要将数据准备好后，由array转xml
		#-这个接口主要向uniform传送一些和会议相关的参数 
		log_message('info', 'start to send data to uniform.');
		$meeting_xml_data = $this->_getMeetingXMLData($user);
		if(!$this->ci->meeting->saveMeetingData($meeting_xml_data)){
			return array(false, 'save meeting info to uniform failed.');
		}
		log_message('info', 'sending data to uniform finished.');

		#向ums开通用户产品
		#-程序执行到这里时，用户的信息已经存储在ums，这里只是将用户的产品状态置为开通状态
		#-如果是创建管理员，用户的信息是boss主动调ums创建
		#-如果是普通用户，用户的信息是在管理中心向boss发送用户开通请求之前，向ums创建好的。
		log_message('info', 'start to set user product status.');
		if(!$this->ci->ums->setUserProduct($this->uc['site_id'], $user['id'], UC_PRODUCT_ID, UMS_USER_STATUS_OPEN)){
			return array(false, 'set user product to ums failed.');
		}
		log_message('info', 'seting user product status finished.');
		
		#重置密码
		log_message('info', 'start to reset password for user.');
		//$password = $this->_generate_password(8);//生成随机8位密码
		$password = '111111';//XXX 暂时写死为六个1
		if(!$this->ci->ums->resetUserPassword($user['id'], $password)){
			return array(false, 'reset user password failed.');
		}
		
		//XXX
		$this->uc['is_reset_password'] = 1;
		log_message('info', 'reseting password for user finished.');
		
		#本地保存用户信息
		#-在本地保存 用户信息
		log_message('info', 'start to save user info at local.');
		if(!$this->ci->account->saveUserInfo($this->uc, $user)){
			return array(false, 'save info at local failed.');
		}
		log_message('info', 'saving user info at local finished.');
		
		#TODO 发送开通邮件 这块代码需要调试
		log_message('info', 'start to send email to user.');
		/*
		$send_data = array(
				'title'=>'账号开通通知',
				'user_name'=>'张三',
				'auth_user'=>'李四',
				'password'=>$password,
				'login_url'=>'www.quanshi.com',
				'email'=>'san.zhang@quanshi.com'
		);
		if( ! $this->ci->email->send($send_data) ){//如果邮件发送失败，则发短信
			$this->ci->ucc->sendMobileMsg($user['id'],'aaaa','13601231924');
		}
		log_message('info', 'sending email to user finished.');
		*/
		
		#成功返回
		return array(true, '');
	}
	
	/**
	 * @brief 开通管理员所特有的操作
	 * @detail
	 * 1.如果管理员还没有添加到组织下，则创建一个组织，并将管理员添加到这个组织下，组织名称为客户名称。
	 * 2.合并合同组件与管理员组件
	 * 3.向ucc分配数据库
	 * 4.向ucc创建站点交换机
	 * 5.向ucc分配mq集群
	 * 6.本地添加管理员信息
	 * 7.操作成功，返回
	 * @param  array $user 
	 * @return array array(boolean, $msg)
	 */
	private function _openManager($user){
		
		#如果管理员还没有添加到组织下，则创建一个组织，并将管理员添加到这个组织下，组织名称为客户名称。
		log_message('info','start to create a organization for the manager.');
		$admin_org_info = $this->ci->ums->getOrganizationByUserId($user['id']);
		if(!$admin_org_info){		
			//创建一个企业类型的组织
			$org_info  = array(
				'name'			=>$this->uc['customer_name'],//企业名称
				'siturl'		=>$this->uc['site_url'],//组织机构编码
				'customercode'	=>$this->uc['customer_code'],//客户编码
				'type'			=>ORG_COMPANY,	//组织类型 1-企业 2-生态企业 3-部门 4-生态企业部门 5-分公司
				'code'			=>$this->uc['customer_code'],//客户编码
				'childOrder'	=>null,
				'parentId'		=>null,
				'introduction'  =>''
			);
			$org_rtn = $this->ci->ums->createOrganization($org_info);
			if(!$org_rtn){
				return array(false, 'create organization to ums failed');
			}

			//将管理员添加在该组织下
			$user_rtn = $this->ci->ums->addUserToOrg($user['id'],$org_rtn);
			if(!$user_rtn){
				return array(false, 'add manager to new organization in ums failed');
			}
			
			$user['org_id']	= $org_rtn;
		}else{
			$user['org_id'] = $admin_org_info['id'];
		}
		log_message('info', 'creating a organization for the manager finished');
		
		#合并合同组件与管理员组件
		log_message('info', 'start to merge contract components and manager components.');
		
		//1.从本地数据库里取之前开通合同时，boss传过来的组件数据,并取出uc组件的所有属性
		$contractComponents = $this->ci->account->getContractComponents($this->uc['customer_code'], $this->uc['contract_id'], $this->uc['site_id']);
		if(!$contractComponents){
			return array(false, 'get contract components from local db failed');
		}
		$uc_component_contract = array();
		foreach($contractComponents as $x){
			if(0 == strcasecmp($x['name'], 'uc')){
				$uc_component_contract = $x;
				break;
			}
		}
		
		//2.与管理员组件合并
		foreach($this->uc['components'] as $k=>$v){
			if(0 == strcasecmp($v['name'], 'uc')){
				$this->uc['components'][$k]['property'] 		= 	array_merge($uc_component_contract['property'], $v['property']);
				$this->uc['components'][$k]['property']['auth'] = 	0;//将auth置为0
				break;
			}
		}
		
		//3.boss端创建或更新合同模板，创建或更新templateUUID为siteurl的权限模板
		
		/*====老的模板更新方法
		$comp_data = array();
		$comp_data['templateUUID'] = $this->uc['site_url'];//站点url
		$comp_data['contractId']   = $this->uc['contract_id'];
		$comp_data['components']   = $this->uc['components'];
		
		$is_created = $this->ci->boss->getContractComponentProps($this->uc['contract_id'], $this->uc['site_url']);
		if( ! $is_created){//添加
			if(! $this->ci->boss->batchCreateContractComponentProps($comp_data)){
				return array(false, 'create contract components to boss failed');
			}
		}else{//更新
			if(! $this->ci->boss->batchModifyContractComponentProps($comp_data)){
				return array(false, 'update contract components to boss failed');
			}
		}
		*/
		
		/**
		 * ===新的模板更新方法
		 * 引入PC3.0后，查询、修改、创建三个接口参数有变化
		 * 获取PC3.0以及其他销售品属性，在这之前boss端已经创建好一个templateUUID为合同ID的模板
		 * 我们通过这个接口获取除UC之外其他（目前只有PC3.0）销售品的components
		 * 至于UC的components,我们仍然使用上面合同模板和管理员模板合并后的
		 *
		*/
		//--获取其他销售品的components
		$sellProds = $this->ci->boss->getSellingProductTemplates($this->uc['contract_id']);
		if(!$sellProds){
			return array(false, 'get selling products from boss by uuid='.$this->uc['contract_id'].' failed');
		}
		
		//--组装sellingProducts参数
		$sellingProducts = array();
		foreach($sellProds as $sellProd){
			$tmp_prod = array();
			if($sellProd['productId'] == UC_PRODUCT_ID){
				$tmp_prod['id'] = $sellProd['id'];
				$tmp_prod['components'] = $this->uc['components'];
			}else{
				$tmp_prod['id'] 		= $sellProd['id'];
				$tmp_prod['components']	= $sellProd['components'];
			}
			$sellingProducts[] = $tmp_prod;
		}
		
		//--组装其他请求
		$comp_data = array();
		$comp_data['templateUUID'] 		= $this->uc['site_url'];//站点url
		$comp_data['contractId']   		= $this->uc['contract_id'];
		$comp_data['sellingProducts']	= $sellingProducts;
		
		//--检查是否存在这个模板，如果存在则修改，不存在则创建
		$is_created = $this->ci->boss->getContractComponentProps($this->uc['contract_id'], $this->uc['site_url']);
		if( ! $is_created){//添加
			if(! $this->ci->boss->combinedBatchCreateContractComponentProps($comp_data)){
				return array(false, 'create contract components to boss failed');
			}
		}else{//更新
			if(! $this->ci->boss->combinedBatchModifyContractComponentProps($comp_data)){
				return array(false, 'update contract components to boss failed');
			}
		}
		
		//4.本地更新站点模板,如果站点模板不存在，则创建
		$comp							=       $this->_search_components($this->uc['components'], 'uc', array('size', 'isLDAP', 'companytype'));
		$this->uc['user_amount'] 		= 		$comp['size'];
		$this->uc['is_ldap']			=       $comp['isLDAP'];
		$this->uc['company_type']		=		$comp['companytype'];
		if( ! $this->ci->account->saveSiteInfo($this->uc, json_encode($this->uc['components']))){
			return array(false, 'save merged contract components  to local db failed');
		}
		log_message('info', 'merging contract components and manager components finished');
		
		#向ucc分配数据库--->此功能废弃
		/*
		log_message('info', 'start to dispatch database for site.');
		if( ! $this->ci->ucc->dbDispatch($this->uc['customer_code'], $this->uc['user_amount'])){
			return array(false, 'dispatch db in ucc failed');
		}
		log_message('info', 'dispatching database for site finished.');
		*/
		
		#向ucc创建站点交换机
		log_message('info', 'start to create site exchange.');
		if( ! $this->ci->ucc->createSiteExchange($this->uc['site_id'])){
			return array(false, 'create site exchange in ucc failed');
		}
		log_message('info', 'creating site exchange finished.');
		
		#向ucc分配mq集群
		log_message('info', 'start to dispatch mq cluster.');
		if( ! $this->ci->ucc->mqDispatch($this->uc['site_id'], $this->uc['user_amount'])){
			return array(false, 'dispatch mq cluster in ucc failed');
		}
		log_message('info', 'dispatching mq cluster finished.');
				
		#本地添加管理员信息
		log_message('info', 'start to add manager info at local.');
		if( ! $this->ci->account->saveSystemManagerInfo($this->uc, $user)){
			return array(false, 'add|update system manager at local failed');
		}
		log_message('info', 'adding manager info at local finished.');

		#操作成功，返回
		return array(true, 'success');
	}
	
	
	
	
}