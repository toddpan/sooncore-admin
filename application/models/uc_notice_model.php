<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UC_Notice_Model extends MY_Model{

    const TBL = 'uc_notice';
    
    /**
     * 构造函数
     */
    public function __construct(){
        parent::__construct(); 
        $this->set_table('uc_notice');
    }
    
    /**
     * 根据条件统计消息的数量
     * @param array $where_arr 条件数组
     */
    public function countNotice($where_arr) {
    	$this->db->select('id');
    	$query = $this->db->get_where(self::TBL, $where_arr);
    	
    	if($query->num_rows() > 0){
    		return $query->num_rows();
    	}
    	
    	return 0;
    }
    
    /**
     * 根据条件获得消息列表
     * @param 	array 	$where_arr 条件数组
     * @param 	int 	$limit
     * @param 	int 	$offset
     */
    public function getNoticeList($where_arr, $limit, $offset) {
    	$this->db->select('id,content,isread,addtime');
    	$this->db->order_by('isread asc,id desc');
    	$query = $this->db->get_where(self::TBL, $where_arr, $limit, $offset);

    	if($query->num_rows() > 0){
    		return $query->result_array();
    	}
    	 
    	return array();
    }
}