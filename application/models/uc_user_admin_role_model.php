<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UC_User_Admin_Role_Model extends MY_Model
{

    const TBL = 'uc_user_admin_role';
    //构造函数
    public function __construct()
    {
        //调用父类构造函数，必不可少
        parent::__construct(); 
        $this->set_table('uc_user_admin_role');
    }
    
    /**
     * @abstract 	根据条件查找当前管理员信息
     * @param		array		$condition		查询条件
     * @author 		Bai Xue <xue.bai_2@quanshi.com>  ---2014/09/18
     */
    public function getAdminByUseridAndState($condition) {
    	$query = $this->db->get_where(self::TBL, $condition);
    	if($query->num_rows()){
    		return $query->row_array();
    	}
    	return array();
    }
    
    /** 
     * 添加管理员
     * @param array $data 用户传过来经过验证的数据
     * @param array $insert_data_admin_part 部分需要插入到uc_user_admin表里的数据
     * return boolean
     */
    public function addManager($data, $insert_data_admin_part){ 
        try{
            $this->db->trans_begin();
            foreach($data as $admin_infos){
            	$user_id = $admin_infos['user_id'];
            	log_message('info', 'apple' . $user_id);
            	$role_id = $admin_infos['role_id'];
            	$w1      = isset($admin_infos['w1']) ? $admin_infos['w1'] : array('key'=>'','value'=>'');
            	$w2      = isset($admin_infos['w2']) ? $admin_infos['w2'] : array('key'=>'','value'=>'');
            	
            	// 判断当前被插入的管理员信息是否在uc_user_admin表中存在，存在则不做处理，不存在则插入
            	$query = $this->db->get_where('uc_user_admin', array('userID' => $user_id));
            	if($query->num_rows() < 1){
            	
            		//插入管理员信息表uc_user_admin数据
            		//从ums获取用户信息
            		$this->load->library('UmsLib','','ums');
            		$user_info = $this->ums->getUserById($user_id);
            	
            		//从uc_user表里获取用户的账号信息
            		$qy = $this->db->get_where('uc_user', array('userID' => $user_id));
            		$user_info_other = $qy->row_array();
            		if(empty($user_info) || empty($user_info_other)){
            			throw new Exception('get user info data failed');
            		}
            		 
            		$insert_data_admin_part1 = array(
            				'userId' 		=>$user_id,
            				'billingcode'   =>$user_info_other['billingcode'],
            				'hostpasscode'  =>$user_info_other['hostpasscode'],
            				'guestpasscode' =>$user_info_other['guestpasscode'],
            				'accountId'     =>$user_info_other['accountId'],
            				'display_name'  =>$user_info['lastName'],
            				'login_name'    =>$user_info['loginName'],
            				'mobile_number' =>$user_info['mobileNumber']
            		);
            		$ret_admin = $this->db->insert('uc_user_admin', array_merge($insert_data_admin_part, $insert_data_admin_part1));
            		if(!$ret_admin) {
            			throw new Exception('insert data into uc_user_admin failed');
            		}
            	}
            	
            	// 判断当前被插入的管理员角色在uc_user_admin_role表中是否存在，存在则删除uc_user_resource表中对应的记录，不存在则插入
            	$query = $this->db->get_where(self::TBL, array('user_id' => $user_id, 'role_id' => $role_id, 'state' => 1));
            	if($query->num_rows() < 1){ // 不存在
            		// 插入角色表uc_admin_user_role数据
            		$insert_data_role = array('user_id'=>$user_id, 'role_id'=>$role_id, 'state'=>1, 'create_time'=>time());
            		$ret_role = $this->db->insert(self::TBL, $insert_data_role);
            		
            		if(!$ret_role){
            			throw new Exception('insert data into uc_user_admin_role failed'); 
            		}
            		$id = $this->db->insert_id();
            	}else{ // 存在
            		$admin_info = $query->row_array();
            		$id = $admin_info['id'];
            		
            		$this->db->delete('uc_user_resource', array('id' => $id));
            	}
            	
            	//插入管理员维度表uc_user_resource数据。生态管理员没有维度
            	$insert_data_resource = array(
            			'id'				   => $id,	
            			'userID'               => $user_id,
            			'scope_level_1'        => $w1['key'],
            			'scope_level_1_value'  => $w1['value'],
            			'scope_level_2'        => $w2['key'],
            			'scope_level_2_value'  => $w2['value']
            	);
            	$ret_resource = $this->db->insert('uc_user_resource', $insert_data_resource);
            	if(!$ret_resource) {
            		throw new Exception('insert data into uc_admin_resource failed');
            	}
            }
            $this->db->trans_commit();
            return true;
        }catch(Exception $e){
            $this->db->trans_rollback();
            log_message('error',$e->getMessage());
            return false;
        }
    }
    
    /**
     * 编辑管理员信息
     * @param array $manager_infos 要修改数据的管理员
     * @return boolean
     */
    public function modifyManager($manager_infos){
        try{
            $this->db->trans_begin();
            foreach($manager_infos as $data){
                $id      = $data['id'];
                $user_id = $data['user_id'];
                $role_id = $data['role_id'];
                $w1      = isset($data['w1']) ? $data['w1'] : array('key'=>'','value'=>'');
                $w2      = isset($data['w2']) ? $data['w2'] : array('key'=>'','value'=>'');
                
                //修改表uc_user_admin_role
                $update_data_role = array('role_id'=>$role_id, 'state'=>1);
                $ret_role         = $this->db->update(self::TBL, $update_data_role, array('id'=>$id));
                if(!$ret_role){ 
                    throw new Exception('update data into uc_admin_user_role failed');
                }
                
                
                //修改表uc_user_resource,生态管理员没有维度限制
                if( $role_id != ECOLOGY_MANAGER ){
                	// 先删除记录
                	$ret = $this->db->delete('uc_user_resource', array('userID' => $user_id));
                	if(!$ret){
                		throw new Exception('insert data into uc_admin_resource failed');
                	}
                	
                	echo $id;
                	
                	// 后添加记录
                    $update_data_resource = array(
                        'id' 					=>	$id,
                    	'userID' 				=>	$user_id,
                        'scope_level_1'        	=>	$w1['key'],
                        'scope_level_1_value'  	=>	$w1['value'],
                        'scope_level_2'        	=>	$w2['key'],
                        'scope_level_2_value'  	=>	$w2['value']
                    );
                    $ret_resource = $this->db->insert('uc_user_resource', $update_data_resource);
                    if(!$ret_resource) {
                        throw new Exception('insert data into uc_admin_resource failed');
                    }
                }
            }
            $this->db->trans_commit();
            return true;
        }catch(Exception $e){
            $this->db->trans_rollback();
            log_message('error',$e->getMessage());
            return false;
        }
    }
    
    /**
     * 获取管理员列表
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getManagerList($keyword, $role_id, $limit, $offset='', $site_id){
        // 初始化返回值
        $ret_data = array();
        
        $this->db->select('u.display_name,u.login_name,u.mobile_number,unix_timestamp(u.last_login_time) as last_login_time,r.user_id,r.id,d.role');
        $this->db->from(self::TBL.' as r');
        $this->db->join('uc_user_admin as u', 'r.user_id=u.userID', 'left');
        $this->db->join('uc_role_dic as d', 'r.role_id=d.id', 'left');
        $this->db->where(array('r.state'=>1, 'u.siteID' => $site_id));
        if($role_id != 0){
        	$this->db->where(array('r.role_id' => $role_id));
        }
// SELECT `u`.`display_name`, `u`.`login_name`, `u`.`mobile_number`, unix_timestamp(u.last_login_time) as last_login_time, `r`.`user_id`, `r`.`id`, `d`.`role` 
// FROM (`uc_user_admin_role` as r) 
// LEFT JOIN `uc_user_admin` as u ON `r`.`user_id`=`u`.`userID` 
// LEFT JOIN `uc_role_dic` as d ON `r`.`role_id`=`d`.`id` 
// WHERE `r`.`state` = 1 AND `u`.`siteID` = 667562 AND `r`.`role_id` = '3' 
// LIMIT 2, 100
        	
        if(!empty($keyword)){
        	$this->db->where("(u.display_name LIKE '%". $keyword . "%' or u.login_name LIKE '%" . $keyword . "%' or u.mobile_number LIKE '%". $keyword . "%')");
        	//$this->db->or_like(array('u.display_name'=>$keyword, 'u.login_name'=>$keyword, 'u.mobile_number'=>$keyword));
        }
        $this->db->limit($limit, $offset);
        
        $query = $this->db->get();
        if($query->num_rows()>0){
            foreach($query->result() as $row){
                $tmp = array();
                $tmp['id'] = $row->id;
                $tmp['user_id'] = $row->user_id;
                $tmp['role'] = $row->role;
                $tmp['display_name'] = $row->display_name;
                $tmp['login_name'] = $row->login_name;
                $tmp['mobile_number'] = $row->mobile_number;
                $tmp['last_login_time'] = empty($row->last_login_time) ? $this->lang->line('not_lognin') : date('Y/m/d H:i', $row->last_login_time);
                $ret_data[] = $tmp;
            }
        }
        log_message('debug',__FUNCTION__.",data:".var_export($ret_data, true));
        return $ret_data;
    }
    
    /**
     * 统计管理员列表总数
     * @author xue.bai_2@quanshi.com
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function countManagerList($keyword, $role_id, $site_id){
    	$this->db->select('u.display_name,u.login_name,u.mobile_number,unix_timestamp(u.last_login_time) as last_login_time,r.user_id,r.role_id,r.id,d.role');
        $this->db->from(self::TBL.' as r');
        $this->db->join('uc_user_admin as u', 'r.user_id=u.userID', 'left');
        $this->db->join('uc_role_dic as d', 'r.role_id=d.id', 'left');
     	$this->db->where(array('r.state'=>1, 'u.siteID' => $site_id));
        if($role_id != 0){
        	$this->db->where(array('r.role_id' => $role_id));
        }
        if(!empty($keyword)){
        	$this->db->where("(u.display_name LIKE '%". $keyword . "%' or u.login_name LIKE '%" . $keyword . "%' or u.mobile_number LIKE '%". $keyword . "%')");
        	//$this->db->or_like(array('u.display_name'=>$keyword, 'u.login_name'=>$keyword, 'u.mobile_number'=>$keyword));
        }
    
    	$query = $this->db->get();
    	if($query->num_rows() == 0){
    		return 0;
    	}
    	return $query->num_rows();
    }
    
    /**
     * 改变管理员状态（删除、恢复）
     * 
     * @author hongliang.cao@quanshi.com
     * @param int $id    id
     * @param int $state 0-删除 1-正常
     * @return boolean
     * 
     */
    public function changeManagerState($id, $state){
        if(!empty($id) && $id>0 && in_array($state, array(0,1))){
            return (boolean)$this->db->update(self::TBL, array('state'=>$state), "id = $id");
        }
        return false;
    }
    
    /**
     * 批量修改管理员状态
     * @param array $ids  需要改变状态的管理员的id
     * @param type $state 0-删除 1-正常
     */
    public function changeManagersState($ids, $state){
        if(is_array($ids) && count($ids)>0 && in_array($state,array(0,1))){
            $where_str = ' id in ('.implode(','.$ids).')';
            return (boolean)$this->db->where($where_str)->update(self::TBL, array('state'=>$state));
        }
        return false;
    }
    
    /**
     * 批量或单个删除管理员
     * @param 		array 		$ids 		uc_user_admin_role表中的id组成的数组
     */
    public function delManagers($ids){
        try{
            $this->db->trans_begin();
            foreach($ids as $id){
               
                // 删除表uc_user_admin_role里的记录，即修改管理员状态
                $ret_role = $this->db->where(array('id' => $id))->update(self::TBL, array('state' => 0));
                if(!$ret_role){
                    throw new Exception('del data from  uc_user_admin_role table failed');
                }
                
                // 删除表uc_user_resource里的记录
                $ret_resource = $this->db->delete('uc_user_resource', array('id'=>$id));
            }
            $this->db->trans_commit();
            return true;
        }catch(Exception $e){
            $this->db->trans_rollback();
            log_message('error',$e->getMessage());
            return false;
        }
    }
    
    /**
     * 获取用户角色信息,根据id
     * 
     * @author hongliang.cao@quanshi.com
     * @param  int $id
     * @return int 角色id
     */
    public function getRoleById($id){
        if(empty($id) || $id < 1){
            return 0;
        }
        $query = $this->db->get_where(self::TBL, array('id' => $id));
        if($query->num_rows() > 0){
            return $query->first_row()->role_id; 
        }
        return 0;
    }
    
    /**
     * 根据用户id,获取用户角色
     * @author hongliang.cao@quanshi.com
     * @param int $user_id
     * @return array
     * 
     */
    public function getRoleIdsByUserId($user_id){
        $ret = array();
        if(empty($user_id) || $user_id<0){
            return $ret;
        }
        $query = $this->db->select('role_id')->get_where(self::TBL, array('user_id'=>$user_id, 'state'=>1));
        if($query->num_rows()>0){
            foreach($query->result_array() as $row){
                $ret[] = $row['role_id'];
            }
        }
        return $ret;
    }
    
    /**
     * 根据角色id获取管理员信息
     * @param int $id 角色id
     * @return mix 
     */
    public function getRoleInfoById($id){
        $query = $this->db->get_where(self::TBL, array('id'=>$id));
        if($query->num_rows()>0){
            return $query->row_array();
        }
        return false;
    }
    
    /**
     * 根据id获得所在记录的角色名称
     * @param int $id
     */
    public function getAdminRoleById($id) {
    	$query = $this->db->query('select d.role from uc_user_admin_role as r,uc_role_dic as d where d.id=r.role_id and r.id=' . $id);
    	
    	if($query->num_rows > 0){
    		return $query->row_array();
    	}
    	
    	return array();
    }
    
    /**
     * 保存系统管理员信息
     * @param array $user_admin_role_arr 系统管理员信息
     */
    public function saveManager($user_admin_role_arr) {
    	$this->db->insert(self::TBL, $user_admin_role_arr);
    	if($this->db->affected_rows() > 0){
    		return $this->db->insert_id();
    	}
    	return false;
    }
    
    /**
     * 根据org_id获得管理员信息
     * @param int $org_id
     */
    public function get_admin_by_org_id($org_id){
    	$this->db->select('r.id,r.user_id');
    	$this->db->from(self::TBL.' as r');
    	$this->db->join('uc_user_admin as u', 'r.user_id=u.userID', 'left');
    	$this->db->where(array('r.role_id' => ADMIN_SUB_COMPANY_MANAGER, 'r.state' => ADMIN_OPEN, 'orgID' => $org_id));
    	$query = $this->db->get();
    	if($query->num_rows() > 0 ){
    		return $query->row_array();
    	}
    	return array();
    }
    
    /**
     * 根据Id更新管理员
     * @param array $update_admin_data 要更新的数据
     * @param array $where_arr  条件
     */
    public function update_admin_info($update_admin_data, $where_arr){
    	$this->db->where($where_arr)->update(self::TBL, $update_admin_data);
    	
    	if($this->db->affected_rows() > 0){
    		return true;
    	}
    	return false;
    }
   
}

