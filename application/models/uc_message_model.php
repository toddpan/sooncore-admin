<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class UC_Message_Model extends MY_Model{
	
	// 定义表名称
	const TBL = 'uc_message';
    
    /**
     * 构造函数
     */
    public function __construct(){
        parent::__construct(); 
        $this->set_table('uc_message');
    }
    
    /**
     * 根据条件统计通知数量
     * @param 	array 	$where_arr 条件数组
     * @param 	int 	$limit
     * @param 	int 	$offset
     */
    public function countMessage($where_arr) {
    	$this->db->select('id');
    	$query = $this->db->get_where(self::TBL, $where_arr);
    	
    	if($query->num_rows() > 0){
    		return $query->num_rows();
    	}
    	
    	return 0;
    }
    
    /**
     * 根据条件获得通知列表
     * @param 	array 	$where_arr 条件数组
     * @param 	int 	$limit
     * @param 	int 	$offset
     */
    public function getMessageList($where_arr, $limit, $offset) {
    	$this->db->select('id,title,send_name,content,isread,addtime');
    	$this->db->order_by('isread asc,id desc');
    	$query = $this->db->get_where(self::TBL, $where_arr, $limit, $offset);
    	 
    	if($query->num_rows() > 0){
    		return result_array();
    	}
    	 
    	return array();
    }
}