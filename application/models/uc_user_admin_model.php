<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UC_User_Admin_Model extends MY_Model
{

    const TBL = 'uc_user_admin';
    //构造函数
    public function __construct()
    {
        //调用父类构造函数，必不可少
        parent::__construct(); 
        $this->set_table('uc_user_admin');
    }
    
    /**
     * @abstract 	根据条件查找当前管理员信息
     * @param		array		$condition		查询条件
     * @author 		Bai Xue <xue.bai_2@quanshi.com>  ---2014/09/18
     */
    public function getAdminByUseridAndState($condition) {
    	$query = $this->db->get_where(self::TBL, $condition);
    	if($query->num_rows() > 0){
    		return $query->row_array();
    	}
    	return array();
    }
    
    /**
     * @brief 禁用/删除用户管理员状态
     * @details
     * @param int $in_user_id 用户id
     * @param int $in_state 状态下0关闭1开启
     * @return boolean  true 成功 false 失败
     */
    public function update_user_admin_state($in_user_id = 0,$in_state = 0){
       //修改失败的user表状态 
       if(bn_is_empty($in_user_id)){//为空
           return FALSE;
       } 
       if( $in_user_id <= 0 ){//不在于0
           return FALSE;
       }
        $insert_user_data = array(  
            'state' => $in_state,//状态（0关闭1开启）
        );
       $modify_data = array(
            'update_data' =>$insert_user_data,
            'where' => array(
                 // 'siteID' => $uc_admin_user_siteID,
                 'userID' => $in_user_id  
            )
       );
        $update_arr =  $this->operateDB(5,$modify_data);
        if(db_operate_fail($update_arr)){//失败
            return FALSE;
        }else{
            return TRUE;
        }
    }
    
    
    
    /**
     *
     * @brief 获得当前管理员信息： 
     * @details
     * @param int $user_id  当前用户ID
     * @return array 管理员信息
     *
     */
      public function getUserById($user_id){
        $where = array('userID' => $user_id);
        $query = $this->db->get_where(self::TBL,$where );           
        return $query->row_array();
      }
    /**
     *
     * @brief 更新最后登陆时间： 
     * @details
     * @param int $user_id  当前用户ID
     * @return array 管理员信息
     *
     */
      public function updateLastLoginTimeById($user_id){
        $this->load->helper('my_dgmdate');
        $data = array(
                'last_login_time' => dgmdate(time(), 'dt')
                    );            
        $this->db->where('userID', $user_id);
        $result = $this->db->update(self::TBL, $data);            
        return $result;
           
      } 
    /**
     *
     * @brief 获得当前管理员信息： 
     * @details
     * @param int $user_id  当前用户ID
     * @return array 管理员信息
     *
     */
      public function getDepartmentLevelById($user_id){
        $where = array('userID' => $user_id);
        $query = $this->db->get_where(self::TBL,$where );           
        return $query->row_array();
      }
    /**
     * @brief根据当前管理员id,获得所属下级管理员数组;
        $where_arr = array(
           'super_admin_id' => $super_admin_id,//当前管理员id,
           'userID' => $userID ,//用户id
           'siteID' => $siteID,//站点id
           'state' => $state,//0：停用；1：启用
           'role_id'=> $role_id,//角色id
           'orgID' => $orgID,//企业id        
           'type' => $type,//1：总公司管理员；2：分公司管理员；3：生态企业管理员；0：其它'
        );
     */
      public function get_next_arr($where_arr){
        $admin_arr = array(
            //'select' =>'id',
            'where' => $where_arr,
        );
        $re_arr =  $this->operateDB(2,$admin_arr);
        $this->load->library('StaffLib','','StaffLib');
        if( is_array($re_arr) ){
            if(!isemptyArray($re_arr)){//如果不是空数组

                foreach($re_arr as $k => $v){
                    $ns_userid = arr_unbound_value($v,'userID',2,0); 
                    $ns_display_name = arr_unbound_value($v,'display_name',2,0);

                    if(bn_is_empty($ns_display_name) || $ns_display_name == 0){//没有值
                        
                        $ns_user_arr = $this->StaffLib->get_user_by_id($ns_userid);

                        $ns_user_name = arr_unbound_value($ns_user_arr,'displayName',2,'');
                        if(!bn_is_empty($ns_user_name)){//有值
                           $re_arr[$k]['display_name'] = $ns_user_name;
                        }
                    }
                }
            }
            log_message('info', 'get uc_user_admin ' . json_encode($admin_arr) . '  success.');
        }else{
            log_message('debug', 'get uc_user_admin ' . json_encode($admin_arr) . ' fail');
        }
        return $re_arr;
      }
      
	/**
	  * @brief根据当前当前站点ID和管理员角色ID查询数据;
	  * @param $site_id	当前站点ID
	  * @param $user_id	当前用户角色ID
	  * @return array	查询的数据结果集
	  */
	public function getInfosBySiteIdAnduserId($site_id, $user_id){
		$info_arr	= array();
		$query		= $this->db->get_where(self::TBL, array('siteID'=>$site_id, 'userID'=>$user_id));
		if($query->num_rows()>0){
			return $query->result_array();
		}else {
			log_message('info', 'get infos from uc_user_admin fail.');
			return null;
		}
	} 
	
	/**
	 * 创建管理员
	 * @param array $user_admin_info 管理员信息
	 */
	public function create_admin($user_admin_info) {
		$this->db->insert(self::TBL, $user_admin_info);
		if($this->db->affected_rows() > 0){
			return $this->db->affected_rows();
		}
		return false;
	}
	
	
	/**
	 * 根据条件查询管理员信息
	 * @param array $where_arr
	 */
	public function get_admin_info($where_arr){
		$this->db->from(self::TBL . ' as a');
		$this->db->join('uc_user_admin_role as r', 'r.user_id=a.userID', 'left');
		$this->db->where($where_arr);
		$this->db->where('r.state', ADMIN_OPEN);
		$query = $this->db->get();
		//$query = $this->db->select('userID')->where_in('role_id', $where_in_arr)->get_where(self::TBL, $where_arr);
		if($query->num_rows() > 0){
			return $query->row_array();
		}
		return array();
	}
	
	/**
	 * 根据条件获得管理员的userId
	 * @param array $where_arr
	 * @param array $where_in_arr
	 */
	public function get_userid($where_arr, $where_in_arr){
		$this->db->select('userID');
		$this->db->from(self::TBL . ' as a');
		$this->db->join('uc_user_admin_role as r', 'r.user_id=a.userID', 'left');
		$this->db->where_in('r.role_id', $where_in_arr);
		$query = $this->db->get();
		//$query = $this->db->select('userID')->where_in('role_id', $where_in_arr)->get_where(self::TBL, $where_arr);
		if($query->num_rows() > 0){
			return $query->result_array();
		}
		return array();
	}
	
	/**
	 * 根据siteId获得系统管理员的账户Id
	 * @param int $site_id  
	 */
	public function get_account_id($site_id){
		$this->db->select('accountId');
		$this->db->from(self::TBL . ' as a');
		$this->db->join('uc_user_admin_role as r', 'r.user_id=a.userID', 'left');
		$this->db->where(array('siteID' => $site_id));
		$query = $this->db->get();
		
		if($query->num_rows()>0){
			return $query->row_array();
		}
		
		return array();
	}
	
	/**
	 * 根据siteId获得根组织org_id
	 * @param int $site_id
	 * @author ge.xie
	 */
	public function getOrgId($site_id) {
		$this->db->reconnect();
		$this->db->select('orgID');
		$this->db->from(self::TBL);
		$this->db->where(array('siteID' => $site_id));
		$query = $this->db->get();

		if ($query->num_rows() == 1) {
			$rows = $query->row_array();
 			return $rows['orgID'];
		} else {
			return null;
		}
	}
}