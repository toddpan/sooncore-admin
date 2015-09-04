<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class UC_User_Model extends MY_Model{

    const TBL = 'uc_user';
    //构造函数
    public function __construct(){
        //调用父类构造函数，必不可少
        parent::__construct(); 
        $this->set_table('uc_user');
    }
    
    /**
     * 创建用户
     * @param  array()  $user_info 用户信息
     */
    public function createUser($user_info) {
    	$this->db->insert(self::TBL, $user_info);
    	if($this->db->affected_rows() > 0){
    		return true;
    	}
    	
    	return false;
    }
    
    /**
     * 根据站点Id获得用户Id信息
     * @param int $site_id
     */
    public function get_userids_by_siteid($site_id){
    	$query = $this->db->select('userID')->get_where(self::TBL, array('siteId' => $site_id));
    	
    	if($query->num_rows() > 0){
    		return $query->result_array();
    	}
    	
    	return array();
    }
    
    
    /**
     * @brief 禁用/删除用户状态
     * @details
     * @param int $in_user_id 用户id
     * @return boolean  true 成功 false 失败
     */
    public function close_user_state($in_user_id = 0){
       //修改失败的user表状态 
       if(bn_is_empty($in_user_id)){//为空
           return FALSE;
       } 
       if( $in_user_id <= 0 ){//不在于0
           return FALSE;
       }
       //查看原来的状态
        $sel_data = array(  
           'select' =>'status',
           'where' => array(
               'userID' => $in_user_id                        
                )
       );
       $sel_user_arr = $this->operateDB(1,$sel_data);
        if(isemptyArray($sel_user_arr)){//不存在
            return FALSE;
        }
        $ns_user_status = isset($sel_user_arr['status'])?$sel_user_arr['status']:0;
        $ns_user_new_status = 0 ;
        switch ($ns_user_status) {//0：未启用（一直未开通过）；1：已开通；2：禁用/删除（开通过）
            case 0: //0：未启用（一直未开通过）
                $ns_user_new_status = 0 ;
                break;
            case 1://1：已开通
                $ns_user_new_status = 2 ;
                break; 
            case 2://2：禁用/删除（开通过）
                $ns_user_new_status = 2 ;
                break; 
        }
        
        $insert_data = array(  
            'status' => $ns_user_new_status,//状态（0：删除；1：正常用户）
        );
       $modify_data = array(
            'update_data' =>$insert_data,
            'where' => array(
                 // 'siteID' => $uc_admin_user_siteID,
                 'userID' => $in_user_id  
            )
       );
        $update_user_arr =  $this->operateDB(5,$modify_data);
        if(db_operate_fail($update_user_arr)){//失败
            return FALSE;
        }else{
            return TRUE;
        }
    }
    /**
     * @brief 禁用/删除用户管理员状态
     * @details
     * @param int $in_user_id 用户id
     * @param int $in_status 状态下0关闭1开启
     * @return boolean  true 成功 false 失败
     */
    public function update_user_state($in_user_id = 0,$in_status = 0){
       //修改失败的user表状态 
       if(bn_is_empty($in_user_id)){//为空
           return FALSE;
       } 
       if( $in_user_id <= 0 ){//不在于0
           return FALSE;
       }
        $insert_user_data = array(  
            'status' => $in_status,//状态（0关闭1开启）
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
     * 获取用户信息
     * @author hongliang.cao@quanshi.com
     * @param int $user_id
     * return array
     */
    public function getUserInfo($user_id){
        $query = $this->db->get_where(self::TBL, array('userID'=>$user_id, 'status' => UC_USER_STATUS_ENABLE));
        if($query->num_rows() > 0){
            return $query->row_array();
        }
        return array(); 
    }
    
    /**
     * 根据条件统计账号个数
     * @param array $where_arr  	where条件
     * @param array $in_where_arr 	账号的status范围数组
     */
    public function countUser($where_arr, $in_where_arr = array()) {
    	$this->db->where($where_arr);
    	if(!empty($in_where_arr)){
    		$this->db->where_in('status', $in_where_arr);
    	}
    	$query = $this->db->get(self::TBL);
    	return $query->num_rows();
    }
    
    /**
     * 统计已启用的账号
     */
    public function count_is_used_user(){
    	$query = $this->db->query('SELECT userID FROM uc_user WHERE siteId = ' . $this->p_site_id . ' and userId IN (SELECT DISTINCT user_id FROM user_client)');
    	return $query->num_rows();
    }
    
    /**
     * 统计未启用的账号
     */
    public function count_not_used_user(){
    	$query = $this->db->query('SELECT userID FROM uc_user WHERE siteId = ' . $this->p_site_id . ' and userId NOT IN (SELECT DISTINCT user_id FROM user_client)');
    	return $query->num_rows();
    }
    
    public function getAccountIdByUserId($user_id){
    	$query = $this->db->get_where('uc_user', array('userID'=>$user_id));
    	return $query->num_rows() > 0 ? $query->first_row()->accountId : 0;
    }
   
}