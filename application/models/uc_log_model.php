<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UC_Log_Model extends MY_Model{

    const TBL = 'uc_log';
    //构造函数
    public function __construct(){
        //调用父类构造函数，必不可少
        parent::__construct();
        $this->set_table('uc_log'); 
    }

    /**
     *
     * @brief 获得日志： 
     * @details
     * @param int $site_id  当前站点ID
     * @param int $org_id  当前组织ID
     * @return array 日志
     *
     */
      public function getLog($site_id,$org_id){
        $query = $this->db->get(self::TBL_GT);
        return $query->result_array();
      }

    /**
     * 新建日志
     * @param array $insert_arr 日志数组
     */
    public function addLog($insert_arr){
    	$this->db->insert(self::TBL, $insert_arr);
    	
    	if($this->db->affected_rows() > 0){
    		return $this->db->insert_id();
    	}else{
    		return 0;
    	}
    }
    
    /**
     *
     * @brief 删除指的的日志。
     * @details
     * @param int $site_id  当前站点ID
     * @param int $org_id  当前组织ID
     * @param int $LogId  日志豁免标识 LogId
     * @return   是否成功 true 成功 false 失败
     *
     */
    public function delLog($site_id,$org_id,$LogId){
        //使用AR类完成
        return $this->db->delete(self::TBL);
    }
}