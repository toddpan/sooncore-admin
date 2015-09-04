<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UC_User_Config_Model extends MY_Model {

    const TBL = 'uc_user_config';
    
    /**
     * 构造方法
     */
    public function __construct(){
        parent::__construct(); 
        $this->set_table('uc_user_config');
    }

   /**
    * 根据用户Id获得用户权限
    * @param unknown $user_id
    */
    public function get_right_from_user($user_id){
         $query = $this->db->get_where(self::TBL, array('userID' => $user_id));
        
        if($query->num_rows() > 0 ){
        	return $query->row_array();
        }
        return array();
    }
    
    /**
     * 保存用户个性化权限
     * @param array $insert_arr 用户的个性化权限信息
     * @return boolean
     */
    public function save_value($insert_arr){
    	$this->db->insert(self::TBL, $insert_arr);
    	
    	if($this->db->affected_rows() > 0 ){
    		return true;
    	}
    	
    	return false;
    }
    
    /**
     * 根据条件更新用户的个性化权限
     * @param array $where_arr  条件数组
     * @param array $update_arr 需要更新的数据
     */
    public function update_value($where_arr, $update_arr){
    	$this->db->update(self::TBL, $update_arr, $where_arr);
    	 
    	if($this->db->affected_rows() > 0 ){
    		return true;
    	}
    
    	return false;
    }
}