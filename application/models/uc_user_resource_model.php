<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class UC_User_Resource_Model extends MY_Model{

    const TBL = 'uc_user_resource';

    /**
     * @abstract 构造函数
     */
    public function __construct(){
        parent::__construct(); 
    }
    
    /**
     * @abstract 	根据条件查找当前管理员维度
     * @param		array		$condition		查询条件
     * @author 		Bai Xue <xue.bai_2@quanshi.com>  ---2014/09/18
     */
    public function getUserResource($condition) {
    	$query = $this->db->get_where(self::TBL, $condition);
    	return $query->row_array();
    }
    
    /**
     * @abstract 	根据user_id查找当前管理员的管理维度
     * @param		int		$user_id
     * @return		array	$re_data
     * @author 		Bai Xue <xue.bai_2@quanshi.com>  ---2014/10/10
     */
    public function getUserResourceByUserId($user_id) {
    	log_message('info', 'into method' . __FUNCTION__ . "\n input -->" . var_export(array('user_id' => $user_id), true));
    	 
    	// 初始化结果数组
    	$re_data = array();
    	 
    	// 执行SQL语句
    	$this->db->select('r.id,r.userID,r.scope_level_1,r.scope_level_1_value,r.scope_level_2,r.scope_level_2_value,d.role');
    	$this->db->from(uc_user_admin_role.' as u');
    	$this->db->join('uc_user_resource as r', 'u.id=r.id', 'left');
    	$this->db->join('uc_role_dic as d', 'u.role_id=d.id', 'left');
    	$this->db->where(array('r.userID' => $user_id));
    	$query = $this->db->get();
    
    	// 整理结果
    	if($query->num_rows() > 0){
    		foreach($query->result() as $row){
    			$tmp = array();
    			$tmp['id'] = $row->id;
    			$tmp['role'] = $row->role;
    			$tmp['scope_level_1'] = $row->scope_level_1;
    			$tmp['scope_level_1_value'] = json_decode($row->scope_level_1_value);
    			$tmp['scope_level_2'] = $row->scope_level_2;
    			$tmp['scope_level_2_value'] = $row->scope_level_2_value;
    			$re_data[] = $tmp;
    		}
    	}
    
    	log_message('info', 'out method' . __FUNCTION__ . "\n output -->" . var_export($re_data, true));
    	 
    	// 返回数据
    	return $re_data;
    }
}