<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class UC_Role_Privilege_Model extends MY_Model{

    const TBL = 'uc_role_privilege';
    
    //构造函数
    public function __construct(){
        parent::__construct(); 
    }
    
    /**
     * @abstract 	根据条件查找当前权限信息
     * @param		array		$condition		查询条件
     * @author 		Bai Xue <xue.bai_2@quanshi.com>  ---2014/09/18
     */
    public function getRolePrivilegeByRole($condition) {
    	$query = $this->db->get_where(self::TBL, $condition);
    	if($query->num_rows() > 0){
    		return $query->result_array();
    	}
    	return array();
    }
}