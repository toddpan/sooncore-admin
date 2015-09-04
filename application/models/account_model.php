<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 账号操作相关的model
 * @file account_model.php
 * @author caohongliang <hongliang.cao@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */

class Account_Model extends CI_Model{
	
	public function __construct(){
		parent::__construct();
		
		$this->load->database(DB_RESOURCE);
		$this->load->library('UmsLib', '', 'ums');
		
		$this->tbl = array(
				'account'		=>'uc_account',//账户户信息表
				'area'			=>'uc_area',//客户地址表
				'customer'		=>'uc_customer',//客户合同信息表
				'site'			=>'uc_site',//站点信息表
				'site_config'	=>'uc_site_config',//站点配置表
				'org_config'	=>'uc_organization',//组织权限
				'user_config' 	=>'uc_user_config',//用户权限
				'admin'			=>'uc_user_admin',//管理员信息表
				'admin_role'	=>'uc_user_admin_role',//管理员角色关联表
				'user'			=>'uc_user',//用户信息表
				'org_manager'	=>'uc_org_manager',//组织管理者
				'request'		=>'uc_request',//本地向boss发送账号操作请求表
				'user_org_rela' =>'user_org_rela',//同事关系
				'tag'  			=>'uc_user_tags',//标签
				'tag_value'		=>'uc_user_tag_value'//标签值
		);
	}
	
	/**
	 * 合同开通，保存合同信息
	 * @param array $uc
	 */
	public function saveContractInfo($uc,$value){
		try{
			$this->db->trans_begin();
			
			/**
			 * 客户分账信息、客户地址信息，对应uc_account、uc_area两张表
			 * 这部分信息在boss开通合同时暂不添加，boss提供了单独的接口来调用
			*/
			
			//客户地址信息
			$where = array(
				'customerCode'	=>	$uc['customer_code'],
				'siteID'		=>	$uc['site_id'],
			);
			
			$data = array(
				'address'		=>	$uc['address'],
			);
			
			if(!$this->_save($this->tbl['area'], $where, $data)){
				throw new Exception('save area data to db failed');
			}
			
				
			//客户合同信息
			$where = array(
				'siteId'	  =>$uc['site_id'],
				'customerCode'=>$uc['customer_code'],
				'contractId'  =>$uc['contract_id'],
			);
			
			$data = array(
				'name'        =>$uc['customer_name'],
				'value'       =>json_encode($value),
				'createTime'  =>date('Y-m-d H:i:s', time()),
			);
			
			if(!$this->_save($this->tbl['customer'], $where, $data)){
				throw new Exception('save customer contract data to db failed');
			}
			
			//站点信息
			$where = array(
				'siteID'		=>$uc['site_id'],
				'contractId'	=>$uc['contract_id'],
			);
			
			$data = array(
				'domain'		=>$uc['site_url'],
				'companyType'	=>$uc['company_type'],
				'isLDAP'		=>$uc['is_ldap'],
				'customerCode'	=>$uc['customer_code'],
				'createTime'	=>date('Y-m-d H:i:s', time()),
			);
				
			if(!$this->_save($this->tbl['site'], $where, $data)){
				throw new Exception('save site data to db failed');
			}

			//站点配置信息，站点根组织id
			$where = array(
				'site_id'=>$uc['site_id'],
				'key'=>'siteRootOrgId',
			);
			$data = array(
				'site_id'=>$uc['site_id'],
				'key'=>'siteRootOrgId',
				'value'=>$uc['root_id'],
				'create_time'=>date('Y-m-d H:i:s', time()),
			);

			if(!$this->_save($this->tbl['site_config'], $where, $data)){
				throw new Exception('save site data to db failed');
			}
			
			$this->db->trans_commit();
			return array(true, '');
		}catch(Exception $e){
			try{$this->db->trans_rollback();}catch(Exception $e1){}
			
			return array(false, $e->getMessage());
		}
	}
	
	/**
	 * 数据库保存，如果存在则更新，不存在则插入
	 * @param string $table 表名
	 * @param array $where  条件
	 * @param array $data   数据
	 * @return boolean
	 */
	private function _save($table, $where, $data){
		$rst = NULL;
		$query = $this->db->get_where($table, $where);
		if($query->num_rows() > 0){
			$rst = $this->db->where($where)->update($table, $data);
		}else{
			$rst = $this->db->insert($table, array_merge($where, $data));
		}
		
		return $rst;
	}
	/**
	 * 保存客户信息
	 * @param array $uc
	 * @param array $user 
	 */
	public function saveUserInfo($uc, $user){
		try{
			$this->db->trans_begin();
			$user_where = array(
				'userID'		=>$user['id'], 
			);
			$user_data = array(
				'siteId'				=>$uc['site_id'],
				'customerCode'		 	=>$uc['customer_code'],
				'status'				=>UC_USER_STATUS_ENABLE,
				'Collect'				=>$uc['collect'],
				'billingcode'			=>$user['billingCode'],
				'hostpasscode'			=>$user['resource']['hostPassword'],
				'guestpasscode'			=>$user['resource']['guestPassword'],
				'accountId'				=>$user['accountId'],
				'isResetPwd'			=>$uc['is_reset_password'],
				'create_time'			=>date('Y-m-d H:i:s'),
				'update_time'			=>date('Y-m-d H:i:s'),
				'expired_time'			=>''
			);
			$query = $this->db->get_where($this->tbl['user'], array('userID'=>$user['id']));
			
			if($query->num_rows()>0){
				$ret = $this->db->where($user_where)->update($this->tbl['user'], $user_data);
			}else{
				$ret = $this->db->insert($this->tbl['user'], array_merge($user_data, $user_where));
			}
			
			if(!$ret) throw new Exception('update or insert user info to local db failed');
			
			$this->db->trans_commit();
			return true;
		}catch(Exception $e){
			try{$this->db->trans_rollback();}catch(Exception $e1){}
			log_message('error', $e->getMessage());
			return false;
		}
	}
	
	/**
	 * 获取合同组件模板
	 * @param string $customer_code
	 * @param int    $contract_id
	 * @param int    $site_id
	 */
	public function getContractComponents($customer_code, $contract_id, $site_id){
		$where = array('customerCode'=>$customer_code, 'contractId'=>$contract_id, 'customerCode'=>$customer_code);
		$query = $this->db->select('value')->get_where($this->tbl['customer'], $where);
	
		if($query->num_rows() > 0){
			$boss_data = json_decode($query->first_row()->value, true);
			return $boss_data['customer']['contract']['components'];
		}
		
		return false;
	}
	
	/**
	 * 开通合同或者开通管理员时，添加站点权限信息
	 *
	 * 首先判断站点信息是否存在，存在则更新，不存在则创建
	 * @param   array 		$uc
	 * @param   string		$value json
	 */
	public function saveSiteInfo($uc, $value){
		$site_where = array('customerCode'=>$uc['customer_code'], 'siteID'=>$uc['site_id'], 'contractId'=>$uc['contract_id']);
		
		if($this->db->get_where($this->tbl['site'], $site_where)->num_rows() > 0){//更新
			$this->db->where($site_where)->update($this->tbl['site'], array('value'=>$value));
		}else{//添加
			$insert_data = array(
					'siteID'		=>	$uc['site_id'],
					'contractId'	=>	$uc['contract_id'],
					'domain'		=>	$uc['site_url'],
					'companyType'	=>	$uc['company_type'],
					'isLDAP'		=>	$uc['is_ldap'],
					'customerCode'	=>	$uc['customer_code'],
					'value'			=>	$value,
					'createTime'	=>date('Y-m-d H:i:s'),
			);
			
			$this->db->insert($this->tbl['site'], $insert_data);
		}
		
		return $this->db->affected_rows() >= 0;//CI框架update后，如果record没有发生变化，则affected row仍然为0
	}
	
	/**
	 * 保存管理员角色信息
	 * 
	 * 检查是否存在，存在则更新，不存在则添加
	 * 
	 * @param array $uc
	 * @param array $user
	 */
	public function saveSystemManagerInfo($uc, $user){
		try{
			$this->db->trans_begin();
			
			$role_data = array(
				'user_id'		=> $user['id'],
				'role_id'		=> SYSTEM_MANAGER,
				'parent_id'		=> 0,
				'state'			=> ADMIN_OPEN,//0-停用 1-禁用
				'create_time'	=> date('Y-m-d H:i:s'),
			);
			
			
			$user_data = array(
					'userID'				=> $user['id'],
					'siteID'				=> $user['siteId'],
					'orgID'					=> $user['org_id'],
					'isLDAP'				=> $uc['is_ldap'],
					'billingcode'			=> $user['billingCode'],
					'hostpasscode'			=> $user['resource']['hostPassword'],
					'guestpasscode'			=> $user['resource']['guestPassword'],
					'accountId'				=> $user['accountId'],
					'type'					=> ADMIN_COMPANY_MANAGER,//1：总公司管理员；2：分公司管理员；3：生态企业管理员；0：其它',
					'createTime'			=> date('Y-m-d H:i:s'),
			);
			
			//从ums获取用户详细信息
			$this->load->library('UmsLib', '', 'ums');
			$user_ums_info = $this->ums->getUserById($user['id']);
			if($user_ums_info){
				$user_data['display_name'] = $user_ums_info['displayName'];
				$user_data['login_name'] = $user_ums_info['loginName'];
				$user_data['mobile_number'] = $user_ums_info['mobileNumber'];
			}
			
			if($this->db->get_where($this->tbl['admin_role'], array('user_id'=>$user['id'], 'role_id'=>SYSTEM_MANAGER))->num_rows() > 0){//已存在
				
				//更新管理员角色表
				$this->db->where(array('user_id'=>$user['id'], 'role_id'=>SYSTEM_MANAGER))->update($this->tbl['admin_role'], $role_data);
				if(!$this->db->affected_rows()) throw new Exception('update admin role info failed');
				
				//更新管理员详情表
				$this->db->where(array('userID'=>$user['id']))->update($this->tbl['admin'], $user_data);
				if(!$this->db->affected_rows()) throw new Exception('update admin info failed');
				
				
			}else{
				
				//添加管理员角色信息
				$this->db->insert($this->tbl['admin_role'], $role_data);
				$id = $this->db->insert_id();
				if(!$id) throw new Exception('insert admin role info failed.sql is'.$this->db->last_query());
				
				//更新node_code
				$this->db->where(array('id'=>$id))->update($this->tbl['admin_role'],array('node_code'=>'-'.$id));
				
				//添加管理员详细信息
				$this->db->insert($this->tbl['admin'], $user_data);
				if(!$this->db->affected_rows()) throw new Exception('insert admin info failed');
			}
			
			$this->db->trans_commit();
			return true;
		}catch(Exception $e){
			try{$this->db->trans_rollback();}catch(Exception $e1){}
			log_message('error', $e->getMessage());
			return false;
		}
	}
	
	/**
	 * 保存用户权限信息
	 * @param int 		$user_id  用户id
	 * @param string    $value	     用户权限模板 json
	 */
	public function saveUserConfigInfo($user_id, $value){
		$num_rows = $this->db->get_where($this->tbl['user_config'], array('userID'=>$user_id))->num_rows();
		if($num_rows > 0){
			$this->db->where('userID', $user_id)->update($this->tbl['user_config'], array('value'=>$value));
		}else{
			$this->db->insert($this->tbl['user_config'], array('userID'=>$user_id, 'value'=>$value, 'createTime'=>date('Y-m-d H:i:s')));
		}
		
		return $this->db->affected_rows() > 0 ? true : false;
	}
	
	/**
	 * 判断用户是否为组织管理者
	 * 
	 * @param int $site_id 
	 * @param int $user_id 
	 */
	public function isOrganizationAdmin($user_id){
		return $this->db->get_where($this->tbl['org_manager'], array('user_id'=>$user_id))->num_rows() > 0;
	}
	
	/**
	 * 获取站点权限
	 * @param int $site_id
	 * @return string
	 */
	public function getSitePower($customer_code, $site_id, $contract_id){
		$value = '';
		$rs = $this->db->select('value')->get_where($this->tbl['site'], array('customerCode'=>$customer_code,'siteID'=>$site_id, 'contractId'=>$contract_id));
		if($rs->num_rows()>0){
			$value = $rs->first_row()->value;
		}
		return $value;
	}
	
	/**
	 * 查看本地数据库中是否定义该用户的权限
	 * @param int $user_id
	 * @return boolean
	 */
	public function hasUserPower($user_id){
		$query = $this->db->get_where($this->tbl['user_config'], array('userID'=>$user_id));
		return $query->num_rows() > 0;	
	}
	
	public function hasOrgPower($org_id){
		$query = $this->db->get_where($this->tbl['org_config'], array('orgID'=>$org_id));
		return $query->num_rows() > 0;
	}
	
	/**
	 * 获取账号开通时向boss提交的请求参数
	 * @param array $boss_params
	 */
	public function getRequestInfo($request_id){
		$query = $this->db->get_where($this->tbl['request'], array('requestId'=>$request_id));
		return $query->num_rows() > 0 ? $query->first_row()->value : false;
	}
	
	/**
	 * 保存账号开通时向boss提交的请求参数
	 * @param array $boss_params
	 */
	public function saveRequestInfo($boss_params){
		
		try{
			$this->db->trans_begin();
			
			//插入一条数据，产生requestId
			$this->db->insert($this->tbl['request'], array('createTime'=>date('Y-m-d H:i:s')));
			if( ! $this->db->affected_rows()) throw new Exception('Sorry man!we encounter some problem when insert boss request data');
			
			$request_id = $this->db->insert_id();
			$boss_params['requestId'] = $request_id;
			
			//更新value
			$this->db->where(array('requestId'=>$request_id))->update($this->tbl['request'], array('value'=>json_encode($boss_params)));
			if( ! $this->db->affected_rows()) throw new Exception('Sorry man!we encounter some problem when insert boss request data');
			
				
			$this->db->trans_commit();
			
			return $boss_params;
		}catch(Exception $e){
			try{$this->db->trans_rollback();}catch(Exception $e1){}
				
			return false;
		}
		
	}
	
	/**
	 * 设置用户状态
	 * @param int $user_id
	 * @param int $status 状态码 0-未启用（一直未开通过）；1-已开通；2-禁用/删除（开通过）
	 * @return boolean
	 */
	public function setUserstatus($user_id, $status){
		return $this->db->where('userID', $user_id)->update($this->tbl['user'], array('status'=>$status));
	}
	
	/**
	 * 回滚用户权限
	 * @param int $user_id
	 */
	public function userPowerRollback($user_id){
		/*
		$query = $this->db->get_where($this->tbl['user_config'], array('userID'=>$user_id));
		if($query->num_rows()>0){
			$old_value = $query->first_row()->oldValue;
			return $this->db->where('userID', $user_id)->update($this->tbl['user_config'], array('value'=>$old_value));
		}
		
		return false;
		*/
		$sql = 'update '.$this->tbl['user_config'].' set value=oldValue where userID=?';
		return $this->db->query($sql, array($user_id));
	}
	
	/**
	 * 设置用户新的权限
	 * -保存旧的权限项
	 * -设置新的权限项
	 * @param $user_id int 用户id
	 * @param $new_components string 新的权限项
	 */
	public function setUserNewPower($user_id, $new_components){
		$sql = 'update ? set oldValue=value where userID=?';
		$step1 = $this->db->query($sql, array($this->tbl['user_config'], $user_id));
		
		$step2 = false;
		if($step1){
			$step2 = $this->db->where(array('userId'=>$user_id))->update($this->tbl['user_config'], array('value'=>$new_components));
		}
		return $step2;
	}
	
	/**
	 * @brief 判断用户是否有管理员角色,有则删除管理员角色（软删除）
	 * @param int $user_id
	 * @return boolean
	 */
	public function deleteUserRole($user_id){
		//判断用户是否有管理员角色,有则删除管理员角色（软删除）
		$query = $this->db->get_where($this->tbl['admin_role'], array('user_id'=>$user_id, 'state'=>1));
		if($query->num_rows() > 0){
			$ret_admin_role = $this->db->where(array('user_id'=>$user_id, 'state'=>1))->update($this->tbl['admin_role'], array('state'=>0));
			$ret_admin      = $this->db->where(array('userID'=>$user_id))->update($this->tbl['admin'], array('state'=>0));
			return ($ret_admin_role && $ret_admin);
		}
		return true;
	}
	
	public function getSystemManagerAccountId($site_id){
		//获取系统管理员id
		$this->db->select('b.accountId');
		$this->db->from($this->tbl['admin_role'].' as a');
		$this->db->join($this->tbl['admin'].' as b', 'a.user_id=b.userID', 'left');
		$this->db->where(array('a.role_id'=>SYSTEM_MANAGER, 'a.state'=>1, 'b.siteID'=>$site_id));
		$rst = $this->db->get();
		
		return $rst->num_rows() > 0 ? $rst->first_row()->accountId : false;
	}
	
	public function getSystemManagerUserId($site_id){
		//获取系统管理员id
		$this->db->select('b.userID');
		$this->db->from($this->tbl['admin_role'].' as a');
		$this->db->join($this->tbl['admin'].' as b', 'a.user_id=b.userID', 'left');
		$this->db->where(array('a.role_id'=>SYSTEM_MANAGER, 'a.state'=>1, 'b.siteID'=>$site_id));
		$rst = $this->db->get();
	
		return $rst->num_rows() > 0 ? $rst->first_row()->userID : false;
	}
	
	public function getUserAccountId($user_id){
		//获取用户的分账id
		$rst = $this->db->select('accountId')->get_where($this->tbl['user'], array('userID'=>$user_id));
		return $rst->num_rows() > 0 ? $rst->first_row()->accountId : false;
	}
	
	public function getContractId($customer_code, $site_id){
		$query = $this->db->select('contractId')->get_where($this->tbl['customer'], array('siteId'=>$site_id, 'customerCode'=>$customer_code));
		
		return $query->num_rows() > 0 ? $query->first_row()->contractId : false;
	}
	
	//根据site_id获取site_url
	public function getSiteUrl($site_id){
		$query = $this->db->get_where($this->tbl['site'], array('siteID'=>$site_id));
		return $query->num_rows() > 0 ? $query->first_row()->domain : false;
	}
	
	/**
	 * 根据customer code和合同id获取站点id
	 */
	public function getSiteId($customer_code, $contract_id){
		$query = $this->db->get_where($this->tbl['site'], array('customerCode'=>$customer_code, 'contractId'=>$contract_id));
		return $query->num_rows() > 0 ? $query->first_row()->siteID : false;
	}
	
	/*
	 * 保存同事关系缓存数据，以供后面的c++程序去批量执行
	 */
	public function saveColleagueData($data){
		return $this->db->insert($this->tbl['user_org_rela'], $data);
	}
	
	/*
	 * 获取站点配置项(是否发送邮件，是否发送短信)
	 */
	
	public function getSiteConfigs($site_id){
		$ret = array(
			'accountNotifyEmail'			=>1,//1-发送 0-不发送
			'accountNotifySMS'				=>1,//1-发送 0-不发送
			'password_existing_prompt' 		=> '',
			'accountDefaultPassword'		=> '',
			'isLDAP'						=>0,//0-非ldap类型  其他-ldap类型
		);
		
		//邮件、短信发送开关
		$query = $this->db->select('key,value')->get_where($this->tbl['site_config'],array('site_id'=>$site_id));
		if($query->num_rows() > 0){
			$_keys = array_keys($ret);
			foreach($query->result_array() as $item){
				if(in_array($item['key'], $_keys)){
					$ret[$item['key']] = $item['value'];
				}
			}
		}
		
		//站点类型（是否为ldap）
		$query2 = $this->db->select('isLDAP')->get_where($this->tbl['site'],array('siteID'=>$site_id));
		if($query2->num_rows() > 0){
			$ret['isLDAP'] = $query2->first_row()->isLDAP;
		}
		
		return $ret;	
	}
	
	public function isBelongToSite($customer_code, $site_id, $user_id){
		$query = $this->db->get_where($this->tbl['user'], array('customerCode'=>$customer_code, 'siteId'=>$site_id, 'userID'=>$user_id));
		return $query->num_rows() > 0;
	}
	
	public function getConfigedUser($customer_code, $site_id){
		$rst = array();
		$query = $this->db->select('userID,value')->get_where($this->tbl['user_config'], array('siteID'=>$site_id, 'customerCode'=>$customer_code));
		if($query->num_rows()>0){
			foreach($query->result_array() as $item){
				$rst[$item['userID']] = $item['value'];
			}
		}
		return $rst;
	}
	
	public function getConfigedOrganizationIds($customer_code, $site_id){
		$rst = array();
		$query = $this->db->select('orgID')->get_where($this->tbl['org_config'], array('siteID'=>$site_id, 'customerCode'=>$customer_code));
		if($query->num_rows()>0){
			foreach($query->result_array() as $item){
				$rst[] = $item['orgID'];
			}
		}
		return $rst;
	}

	public function saveCustomTags($tags){
		foreach($tags as $tag){
			$this->saveCustomTag($tag);
		}
		return true;
	}

	//保存自定义标签，有则修改，无则添加
	public function saveCustomTag($tag){
		$q = $this->db->get_where($this->tbl['tag_value'], array('user_id'=>$tag['user_id'],'tag_id'=>$tag['tag_id']));
		if($q->num_rows()>0){
			$tag['modified'] = time();
			return $this->db->where(array('id'=>$q->first_row()->id))->update($this->tbl['tag_value'],$tag);
		}else{
			$tag['created']  = time();
			$tag['modified'] = time();
			return $this->db->insert($this->tbl['tag_value'],$tag);
		}
	}

	//获取站点下所有的标签
	public function getSiteCustomTags($site_id){
		$q = $this->db->get_where($this->tbl['tag'], array('site_id'=>$site_id));
		$ret = array();
		if($q->num_rows()>0){
			foreach($q->result_array() as $item){
				$ret[$item['tag_code']] = $item;
			}
		}

		return  $ret;
	}


	public function getRootOrgIdFromUserAdminTable($user_id){
		$q = $this->db->select('orgID')->get_where($this->tbl['admin'], array('userID'=>$user_id));
		if($q->num_rows()>0){
			return $q->first_row()->orgID;
		}
		return false;
	}

	public function getRootOrgIdFromSiteConfigTable($site_id){
		$q = $this->db->select('value')->get_where($this->tbl['site_config'], array('site_id'=>$site_id, 'key'=>'siteRootOrgId'));
		if($q->num_rows()>0){
			return $q->first_row()->value;
		}
		return false;
	}
	
}