<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UC_Organization_Model extends MY_Model{

    const TBL = 'uc_organization';
    
    /**
     * 构造方法
     */
    public function __construct(){
        parent::__construct(); 
        $this->set_table('uc_organization');
    }

   /**
    * 根据org_id获得组织权限
    * @param int $org_id
    */
    public function get_org_right($org_id){
        $query = $this->db->get_where(self::TBL, array('orgID' => $org_id));
        
        if($query->num_rows() > 0 ){
        	return $query->row_array();
        }
        return array();
    }
    
    /**
     * 保存组织的个性化权限
     * @param array $insert_arr 组织的个性化权限
     */
    public function save_value($insert_arr){
    	$this->db->insert(self::TBL, $insert_arr);
    	
    	if($this->db->affected_rows() > 0 ){
    		return true;
    	}
    	
    	return false;
    }
    
    /**
     * 根据条件更新组织的个性化权限
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