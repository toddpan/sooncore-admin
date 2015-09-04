<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 生态企业
 * @file ecology.php
 * @author caohongliang <hongliang.cao@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */

class Ecology_Model extends MY_Model{
	public $error = '';
	public function __construct(){
        parent::__construct(); 
		$this->tbl = array(
			'partake'=>'uc_ecology_partake',//生态企业本方参与人员
			'ecology_manager'=>'uc_manager_ecology',//生态企业-管理员关联表
			'user_role'=>'uc_user_admin_role',//用户-管理员角色关联表，以及记录生态管理员的父子级关系
			'manager_info'=>'uc_user_admin',//管理员详情表
			'user'=>'uc_user',//用户表
			'thread'=>'uc_thread',//进程表
			'site'=>'uc_site',//站点表
			'organization'=>'uc_organization',//组织表
		);
    }
	
	
	/**
	 * 添加生态企业管理员
	 * 1.为该用户添加生态管理员角色
	 * 2.向管理员详情表写入该用户的详细信息
	 * @param int $site_id 站点id
	 * @param int $org_id  用户所属的组织id
	 * @param int $is_ldap 是否为ldap导入
	 * @param int $user_id 用户id
	 * @param int $p_manager_id 父级生态管理员id
	 */
	public function addEcologyManager($site_id, $org_id, $is_ldap, $user_id, $p_manager_id){
		try{
			$this->db->trans_begin();
			//检查此用户是否已经是生态管理员
			$rst_cat = $this->db->get_where($this->tbl['user_role'], array('user_id'=>$user_id, 'role_id'=>ECOLOGY_MANAGER, 'state'=>1));
			if($rst_cat->num_rows()>0){
				throw new Exception('This user is already ecology manager');
			}
			//将这个用户添加为生态管理员
			$insert_data = array('user_id'=>$user_id, 'role_id'=>ECOLOGY_MANAGER, 'state'=>1);
			$rst_dog = $this->db->set('create_time', 'CURRENT_TIMESTAMP', false)->insert($this->tbl['user_role'], $insert_data);
			if(!$rst_dog){
				throw new Exception('insert user role data to database fail');
			}else{
				$cur_id = $this->db->insert_id();
				$parent_node_code = $this->_getEcologyNodeCode($p_manager_id);
				$node_code = $parent_node_code.'-'.$cur_id;
				$parent_id = array_pop(explode('-', $parent_node_code));
				$this->db->update($this->tbl['user_role'],array('node_code'=>$node_code, 'parent_id'=>$parent_id), array('id'=>$cur_id));
			}
			
			//将这个用户的详细信息写入到管理员详情表里
			if(0 == $this->db->get_where($this->tbl['manager_info'], array('userID'=>$user_id))->num_rows()){
				$user_info = $this->db->get_where($this->tbl['user'], array('userID'=>$user_id))->first_row('array');
				if(empty($user_info)) throw new Exception('have no information about user '.$user_id);
				
				$CI = & get_instance();
				$CI->load->library('UmsLib', '', 'ums');
				$user_info_ums = $CI->ums->getUserById($user_id);
				if(empty($user_info) || empty($user_info_ums)){
					throw new Exception('get user info fail');
				}
				$insert_data = array(
					'userID'		=>$user_id,
					'siteID'        =>$site_id,
					'orgID'         =>$org_id,
					'isLDAP'        =>$is_ldap,
					'type'          =>ECOLOGY_MANAGER,
					'billingcode'   =>isset($user_info['billingcode']) ? $user_info['billingcode'] : null,
					'hostpasscode'  =>isset($user_info['hostpasscode']) ? $user_info['hostpasscode'] : null,
					'guestpasscode' =>isset($user_info['guestpasscode']) ? $user_info['guestpasscode'] : null,
					'accountId'     =>isset($user_info['accountId']) ? $user_info['accountId'] : null,
					'display_name'  =>isset($user_info_ums['displayName']) ? $user_info_ums['displayName'] : null,
					'login_name'    =>isset($user_info_ums['loginName']) ? $user_info_ums['loginName'] : null,
					'mobile_number' =>isset($user_info_ums['mobileNumber']) ? $user_info_ums['mobileNumber'] : null
				);
				
				$rst_fish = $this->db->set('createTime','CURRENT_TIMESTAMP',false)->insert($this->tbl['manager_info'], $insert_data);
				if(!$rst_fish) {
                    throw new Exception('insert manager info failed');
                }
			}
			$this->db->trans_commit();
            return array(true,$cur_id);
		}catch(Exception $e){
			$this->db->trans_rollback();
            log_message('error',$e->getMessage());
            return array(false,$e->getMessage());
		}
	}
	
	/**
	 * 获取生态管理员节点的node_code
	 * -只有生态管理员有层级的概念
	 * -生态管理员的父级可能是系统管理员或渠道管理员或生态管理员
	 * @param type $user_id 用户id
	 * @return string
	 */
	private function _getEcologyNodeCode($user_id){
		$rs = $this->db->get_where($this->tbl['user_role'], array('user_id'=>$user_id, 'state'=>1));
		//TODO 如果这个用户既是生态管理员又是渠道管理员，以哪个为准
		if($rs->num_rows()>0){
			return $rs->first_row()->node_code;
		}
		return '';
	}
	
	/**
	 * 为生态管理员添加生态企业
	 * @param int $e_id  生态企业id
	 * @param int $m_id  生态管理员
	 */
	public function addCompanyForManager($site_id, $org_id, $m_id,$e_id){
		$insert_data = array('site_id'=>$site_id, 'org_id'=>$org_id, 'user_id'=>$m_id, 'ecology_id'=>$e_id, 'time'=>time());
		return $this->db->insert($this->tbl['ecology_manager'], $insert_data);
	}
	
	/**
	 * 根据id获取生态管理员信息
	 * @param int $id 生态管理员id
	 * @return mix
	 */
	public function getManagerById($id){
		$this->db->select('a.id,a.user_id,a.role_id,b.isLDAP,b.last_login_time,b.display_name,b.login_name,b.mobile_number');
		$this->db->from($this->tbl['user_role'].' as a');
		$this->db->join($this->tbl['manager_info'].' as b', 'a.user_id=b.userID', 'left');
		$this->db->where(array('a.id'=>$id, 'a.state'=>1));
		$rst = $this->db->get();
		if($rst->num_rows() !== 1){
			log_message('error', 'get illeagel manager info!');
			return false;
		}
		return $rst->first_row('array');
	}
	
	/**
	 * 根据用户id获取管理员信息
	 * @param int $user_id 用户id
	 * @return mix
	 */
	public function getManagerByUid($user_id){
		$this->db->select('a.id,a.user_id,a.role_id,b.isLDAP,b.last_login_time,b.display_name,b.login_name,b.mobile_number');
		$this->db->from($this->tbl['user_role'].' as a');
		$this->db->join($this->tbl['manager_info'].' as b', 'a.user_id=b.userID', 'left');
		$this->db->where(array('a.user_id'=>$user_id, 'a.role_id'=>ECOLOGY_MANAGER, 'a.state'=>1));
		$rst = $this->db->get();
		if($rst->num_rows() !== 1){
			log_message('error', 'get illeagel manager info!');
			return false;
		}
		return $rst->first_row('array');
	}
	
	/**
	 * 获取管理员树的根节点
	 * -如果当前用户为系统管理员，则根节点为系统管理员
	 * -如果当前用户为生态管理员，则根节点为当前生态管理员
	 * -如果当前用户为渠道管理员，则根节点为渠道管理员
	 * -其他角色的用户，则不显示
	 * @param int $site_id 站点id
	 * @param int $org_id  组织id
	 * @param int $user_id 用户id
	 * @return mix
	 */
	public function getRootManager($site_id, $org_id, $user_id){
		$this->db->select('a.id,a.user_id,a.role_id,a.parent_id,b.isLDAP,b.last_login_time,b.display_name,b.login_name,b.mobile_number');
		$this->db->from($this->tbl['user_role']." as a");
		$this->db->join($this->tbl['manager_info']." as b", 'a.user_id=b.userID', 'left');
		//$this->db->where(array('a.parent_id'=>null, 'a.role_id'=>ECOLOGY_MANAGER, 'a.state'=>1, 'b.siteID'=>$site_id, 'b.orgID'=>$org_id));
		$this->db->where(array('a.parent_id'=>null, 'a.user_id'=>$user_id, 'a.state'=>1, 'b.siteID'=>$site_id, 'b.orgID'=>$org_id));
		$rst = $this->db->get();
		if($rst->num_rows() !== 1){
			log_message('error', 'get root manager info failed SQL:'.$this->db->last_query());
			return false;
		}
		return $rst->result_array();
	}
	/**
	 * 根据id获取下一级生态管理员信息
	 * @param int $id
	 * @return mix
	 */
	public function getNextLevelManager($id){
		$this->db->select('a.id,a.user_id,a.role_id,a.parent_id,b.isLDAP,b.last_login_time,b.display_name,b.login_name,b.mobile_number');
		$this->db->from($this->tbl['user_role'].' as a');
		$this->db->join($this->tbl['manager_info'].' as b', 'a.user_id=b.userID', 'left');
		$this->db->where(array('a.parent_id'=>$id, 'a.role_id'=>ECOLOGY_MANAGER, 'a.state'=>1));
		$rst = $this->db->get();
		return $rst->num_rows() > 0 ? $rst->result_array() : array();
	}
	
	/**
	 * 获取父级生态管理员id(也可能是系统管理员或者渠道管理员)
	 * @param int $user_id 
	 * @return int 
	 */
	public function getPreLevelManager($id){
		return $this->db->get_where($this->tbl['user_role'], array('id'=>$id))->first_row()->parent_id;
	}
	
	/**
	 * 获取所有的生态企业，包括当前管理员和子管理员的生态企业
	 * @param int $id uc_admin_user_role中的管理员id
	 */
	public function getAllEcologysByMid($id, $limit, $offset){
		$node_code = $this->db->get_where($this->tbl['user_role'], array('id'=>$id))->first_row()->node_code;
		//TODO sql 需要优化 in
		$sql = "select ecology_id from ".$this->tbl['ecology_manager']." where state=1 and user_id in (select user_id from ".$this->tbl['user_role']." where node_code like '".$node_code."%') limit ".$offset." , ".$limit;
		$rst = $this->db->query($sql);
		return $rst->num_rows()>0 ? $rst->result_array() : array();
	}
	
	/**
	 * 获取所有的生态企业，包括当前管理员和子管理员的生态企业的[总数]
	 * @param int $id uc_admin_user_role中的管理员id
	 */
	public function getAllEcologysTotalNum($id){
		$node_code = $this->db->get_where($this->tbl['user_role'], array('id'=>$id))->first_row()->node_code;
		$sql = "select count(*) as total_num from ".$this->tbl['ecology_manager']." where user_id in (select user_id from ".$this->tbl['user_role']." where node_code like '".$node_code."%')";
		$rst = $this->db->query($sql);
		return $rst->first_row()->total_num;
	}
	
	/**
	 * 获取管理员所管理的生态企业
	 * @param int $user_id 管理员用户id
	 * @param int $limit
	 * @param int $offset
	 * @return array
	 */
	public function getEcologysByUid($user_id, $limit=20, $offset=0){
		$rst = $this->db->get_where($this->tbl['ecology_manager'], array('user_id'=>$user_id, 'state'=>1), $limit, $offset);
		return  $rst->result_array();
	}
	
	/**
	 * 判断该生态管理员是否还有下级管理员
	 * @param int $id id
	 * @return bool
	 */
	public function hasNextLevel($id){
		return $this->db->get_where($this->tbl['user_role'],array('parent_id'=>$id))->num_rows()>0 ? true : false;
	}
	
	/**
	 * 根据生态企业id获取该生态企业的生态管理员信息
	 * @param int $e_id 生态企业id
	 */
	public function getEcologyManagerName($e_id){
		$display_name = '';
		$user_id = $this->db->get_where($this->tbl['ecology_manager'], array('ecology_id'=>$e_id, 'state'=>1))->first_row()->user_id;
		if($user_id){
			$display_name = $this->db->get_where($this->tbl['manager_info'],array('userID'=>$user_id))->first_row()->display_name;
		}
		return $display_name;
	}
	
	/**
	 * 根据生态企业id获取该生态企业的渠道管理员信息
	 * @param int $e_id 生态企业id
	 */
	public function getQudaoManagerName($e_id){
		$this->db->select('a.id,a.user_id,a.role_id,a.parent_id,b.isLDAP,b.last_login_time,b.display_name,b.login_name,b.mobile_number');
		$this->db->from($this->tbl['user_role'].' as a');
		$this->db->join($this->tbl['manager_info'].' as b', 'a.user_id=b.userID', 'left');
		$this->db->where(array('b.orgId'=>$e_id, 'a.role_id'=>CHANNEL_MANAGER, 'a.state'=>1));
		$rst = $this->db->get();
		return $rst->num_rows()>0 ? $rst->first_row()->display_name : '';
	}
	
	/**
	 * 删除生态管理员
	 * @param int $user_id 要删除的生态管理员id
	 */
	public function delEcologyManager($user_id){
		try{
			$this->db->trans_begin();
			$id = $this->db->get_where($this->tbl['user_role'],array('user_id'=>$user_id, 'role_id'=>ECOLOGY_MANAGER, 'state'=>1))->first_row()->id;
			//获取该生态管理员所管理的生态企业，如果有，则将这些生态企业绑定在父级生态管理员上
			$ecologys = $this->getEcologysByUid($user_id);
			$p_id = $this->getPreLevelManager($id);
			if(count($ecologys)>0){
				if(empty($p_id)){
					throw new Exception('get parent manager fail');
				}else{
					$p_user_id = element('user_id', $this->ecology->getManagerById($p_id), 0);
				}
				$ecology_ids = array();
				foreach($ecologys as $ecology){
					$ecology_ids[] = $ecology['id'];
				}
				$affect_rows = $this->db->where_in('id', $ecology_ids)->update($this->tbl['ecology_manager'], array('user_id'=>$p_user_id));
				if(!$affect_rows) throw new Exception('bind ecologys to parent manager fail');
			}
			//修改子管理员的父级生态管理员
			$son_managers = $this->db->get_where($this->tbl['user_role'], array('parent_id'=>$id))->result_array();
			if(is_array($son_managers) && count($son_managers)>0){
				foreach($son_managers as $son){
					$tmp = array();
					$tmp['parent_id'] = $p_id;
					$tmp['node_code'] = implode('-', explode('-',$son['node_code'],-2)).'-'.$son['id'];
					$affect_rows1 = $this->db->update($this->tbl['user_role'], $tmp, array('id'=>$son['id']));
					if(!$affect_rows1) throw new Exception('bind nextlevel managers to parent manager fail');
				}
			}
			//删除这个生态管理员
			$affect_rows2 = $this->db->update($this->tbl['user_role'], array('state'=>0), array('id'=>$id));
			if(!$affect_rows2){
				throw new Exception('delete manager fail');
			}
			
			$this->db->trans_commit();
            return true;
		}catch(Exception $e){
			$this->db->trans_rollback();
            log_message('error',$e->getMessage());
            return false;
		}
		
	}
	
	public function delEcology($e_id){
		try{
			$this->db->trans_begin();
			//删除该生态企业与生态管理员的关联关系，本地
			$affect_rows = $this->db->delete($this->tbl['ecology_manager'], array('ecology_id'=>$e_id));
			if($affect_rows == 0){
				throw new Exception('delete data from table '.$this->tbl['ecology_manager'].' fail');
			}
			//删除与该生态企业相关的本方参与人员关联关系，本地
			$affect_rows = $this->db->delete($this->tbl['partake'], array('ecology_id'=>$e_id));
			if($affect_rows == 0){
				throw new Exception('delete data from table '.$this->tbl['partake'].' fail');
			}
			//删除管理员信息，本地
// 			$id = 0;
// 			$ecology_manager_ids = $this->getAllEcologysByMid($id);
// 			$affect_rows = $this->db->where_in('user_id',$ecology_manager_ids)->delete($this->tbl['user_role']);
// 			if($affect_rows == 0){
// 				throw new Exception('delete data from table '.$this->tbl['partake'].' fail');
// 			}
			
			$this->db->trans_commit();
            return true;
		}catch(Exception $e){
			$this->db->trans_rollback();
            log_message('error',$e->getMessage());
            return false;
		}
		
	}
	
	
	/**
	 * 异步删除生态企业
	 * --将要删除的生态企业信息写入uc_thread表
	 * --后台脚本读取表中的记录进行异步删除
	 * @param array $ids 生态企业id
	 */
	public function delEcologyAsyn($ids){
		foreach($ids as $id){
			$insert_data = array(
				'isvalid'=>1,
				'type'=>6,//用来标记生态企业删除
				'created'=>time(),
				'modify'=>time(),
				'value'=>json_encode($id)
			);
			$this->db->insert($this->tbl['thread'],$insert_data);
		}
	}
	
	/**
	 * 判断生态企业属性是否发生了变化
	 * @param int $eco_id  生态企业id
	 * @param array $props 生态企业属性
	 */
	public function isPropsChanged($site_id, $eco_id, $props){
		//TODO 从组织属性表里获取该生态企业的属性
		//TODO 获取站点属性表里的默认属性
		return true;
	}
	
	/**
	 * 为生态企业设置管理员
	 * @param int $user_id 用户id
	 * @param int $eco_id  生态企业id
	 */
	public function setEcologyManager($site_id, $org_id, $user_id, $eco_id,$channel_id=NUll){
		try{
			$this->db->trans_begin();
			//获取该生态企业的当前管理员，如果有则解除关系
			$rs = $this->db->get_where($this->tbl['ecology_manager'],array('ecology_id'=>$eco_id, 'state'=>1));
			if($rs->num_rows()>0){
				$rs2 = $this->db->update($this->tbl['ecology_manager'], array('state'=>0), array('state'=>1, 'ecology_id'=>$eco_id));
				if(!$rs2) throw new Exception('operating failed when disable the relationship between manager and ecoloyg');
			}
			//将该用户设置为该生态企业的生态管理员
			$insert_data = array(
				'org_id'=>$org_id,
				'site_id'=>$site_id,
				'user_id'=>$user_id,
				'ecology_id'=>$eco_id,
				'channel_id'=>$channel_id,
				'state'=>1,
			);
			$rs3 = $this->db->set('time', 'CURRENT_TIMESTAMP', false)->insert($this->tbl['ecology_manager'], $insert_data);
			if(!$rs3) throw new Exception('set ecology manager failed');
			$this->db->trans_commit();
            return true;
		}catch(Exception $e){
			$this->db->trans_rollback();
            log_message('error',$e->getMessage());
            return false;
		}
	}
	
	/**
	 * 判断此用户是否为生态管理员角色
	 * @param int $user_id
	 */
	public function isEcologyManager($user_id){
		$rs = $this->db->get_where($this->tbl['user_role'], array('role_id'=>ECOLOGY_MANAGER, 'user_id'=>$user_id, 'state'=>1));
		return $rs->num_rows()>0 ? true : false;
	}
	
	/**
	 * 创建生态组织和管理以后创建本地用户关系
	 * @param int   $org_id  生态组织ID
	 * @param int   $admin_user_id 管理员用户ID
	 * @param array $users 本方参与用户
	 */
	public function CreatLocalEcologyInfo($org_id,$admin_user_id,$users){
		try{
			//获取组织信息
			$CI = & get_instance();
			$CI->load->library('UmsLib', '', 'ums');
			$org_info = $CI->ums->getOrganizationBrief($org_id);
			if(empty($org_info)){
				log_message('error', __FUNCTION__." input->\n".var_export($org_id,true).' site_id->'.$this->p_site_id.' customer code->'.$this->p_customer_code);
				return false;
			}
			//获取用户信息
			$user_info = $CI->ums->getUserById($admin_user_id);
			if(empty($user_info)){
				log_message('error', __FUNCTION__." input->\n".var_export($admin_user_id,true).' site_id->'.$this->p_site_id.' customer code->'.$this->p_customer_code);
				return false;
			}
			$this->db->trans_begin();
			//获取站点信息
			$site_info = $this->db->get_where($this->tbl['site'], array('siteID'=>$this->p_site_id))->first_row('array');
			if(empty($site_info)){
				throw new Exception('get site info fail');
			}
			
			//保存组织权限
			 $insert_organization_data = array(
				'siteID'			=>		$this->p_site_id,//站点ID
				'orgID'				=>		$org_id,
				'org'				=>		$org_info['nodeCode'],
				'value'				=>		$site_info['value'],
			);
			$organization_res = $this->db->set('createTime', 'CURRENT_TIMESTAMP', false)->insert($this->tbl['organization'], $insert_organization_data);
			if(!$organization_res) throw new Exception('insert organization data failed');
			//查询该生态企业组织父级
			$this->db->select('a.userID,a.orgID,a.siteId,a.role_id,b.id,b.user_id,b.role_id,b.parent_id,b.node_code,b.state');
			$this->db->from($this->tbl['user_role'].' as b');
			$this->db->join($this->tbl['manager_info'].' as a', 'b.user_id=a.userID', 'left');
			$this->db->where(array('a.orgID'=>$org_info['parentId'], 'a.state'=>ADMIN_OPEN));
			$rst = $this->db->get()->result_array();
			//增加管理员角色角色
			$insert_user_admin_role_data = array(
				'user_id' 		=> $admin_user_id,//用户id
				'role_id' 		=> CHANNEL_MANAGER,
				'parent_id' 	=> $rst[0]['id'],//父级管理员id
				'node_code' 	=> '',//父级节点
				'state' 		=> ADMIN_OPEN,//0：停用；1：启用
			);
			$user_admin_role_res = $this->db->set('create_time', 'CURRENT_TIMESTAMP', false)->insert($this->tbl['user_role'], $insert_user_admin_role_data);
			if(!$user_admin_role_res) throw new Exception('insert user_admin_role data failed');
			//获取新增的ID
			$id = $this->db->insert_id();
			$update_res = $this->db->where(array('id'=>$id))->update($this->tbl['user_role'],array('node_code'=>$rst['0']['node_code'].'-'.$id));
			if(!$update_res) throw new Exception('update user_admin_role data failed');
			//增加管理员
			$insert_user_admin_data = array(
					'userID'			=>		$admin_user_id,
					'siteID'			=>		$this->p_site_id,//管理员所属的站点id
					'role_id'			=>		CHANNEL_MANAGER,//管理员所属的站点id
					'orgID'				=>		$org_id,//管理员所属的组织id[系统管理员为站点所属的组织id]
					'isLDAP'			=>		$this->p_is_ldap,//0：否（批量导入）；1：是（LDAP导入）；2：全部都可以',
					'accountId'			=>		$this->p_account_id,
					'type'				=>		ADMIN_OTHERS,//1：总公司管理员；2：分公司管理员；3：生态企业管理员；0：其它',
					'state'				=>		ADMIN_OPEN,//0：停用；1：启用
					'last_login_time'	=>		dgmdate(round($user_info['lastUpdateTime']/1000), 'dt'),//最后登陆时间
					'createTime'		=>		dgmdate(round($user_info['lastUpdateTime']/1000), 'dt'),//创建时间
					'display_name'		=>		$user_info['displayName'],//用户名
					'login_name'		=>		$user_info['loginName'],//登录名
					'mobile_number'		=>		$user_info['mobileNumber'],//手机号
			);
			$user_admin_res = $this->db->insert($this->tbl['manager_info'], $insert_user_admin_data);
			if(!$user_admin_res) throw new Exception('insert user_admin data failed');
			
			//添加本方参与人员
			$users_data = array();//本方参与人员数据
			if(is_array($users)){
				foreach ($users as $user){
					$temp_users_data = array(
						'org_id'		=>		$this->p_org_id,//本方参与人的根据组织id
						'site_id'		=>		$this->p_site_id,//站点ID
						'ecology_id'	=>		$org_id, //所参与的生态企业ID
						'user_id'		=>		$user['userid'],
						'time'			=>		dgmdate(time(), 'dt'),//最后登陆时间
					);
					$users_data[] = $temp_users_data;
				}
			}
			$users_insert_res = $this->db->insert_batch($this->tbl['partake'], $users_data);
			if(!$users_insert_res) throw new Exception('insert ecology_partake data failed');
			
			$eanager_res = $this->setEcologyManager($this->p_site_id, $this->p_org_id, $this->p_user_id, $org_id,$admin_user_id);
			if(!$eanager_res) throw new Exception('setEcologyManager data failed');
			$this->db->trans_commit();
			return $org_info;
		}catch(Exception $e){
			$this->db->trans_rollback();
            log_message('error',$e->getMessage());
            return false;
		}
	}
	
	/**
	 * 删除生态组织
	 * @param int $org_id  生态组织ID
	 * @param int $user_id 执行操作的用户ID
	 * @return int|bool    删除的组织ID
	 */
	public function deleteEcology($org_id){
		log_message('info', __FUNCTION__." input->\n".'org_id->'.$org_id.' site_id->'.$this->p_site_id.' customer code->'.$this->p_customer_code);
		//UMS删除
		$CI = & get_instance();
		$CI->load->library('UmsLib', '', 'ums');
		$res = $CI->ums->delOrganization($org_id);
		if(!$res){
			log_message('error', __FUNCTION__." input->\n".var_export($res,true).' site_id->'.$this->p_site_id.' customer code->'.$this->p_customer_code);
			return false;
		}
		//删除本地信息
		$res_d = $this->delEcology($org_id);
		if(!$res_d){
			log_message('error', __FUNCTION__." input->\n".var_export($res,true).' site_id->'.$this->p_site_id.' customer code->'.$this->p_customer_code);
			return false;
		}
		log_message('info', __FUNCTION__.'->deleteEcology:'." input->\n".var_export($org_id,true).' site_id->'.$this->p_site_id.' customer code->'.$this->p_customer_code);
		return true;
	}
	
	/**
	 * 获取错误提示
	 */
	public function getLastError(){
		return $this->error;
	}
	
	/**
	 * 获取组织管理员ID
	 * @param int $parent_org_array
	 */
	function getParentOrgAdminID($parent_org_array){
		return $this->db->where_in('orgID',$parent_org_array)->get($this->tbl['manager_info'])->result_array();
	}
}
