<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Employee_Change_Task_Model extends MY_Model{
	
	// 定义表名称 
	const TBL = 'employee_change_task';
	
	/**
	 * 构造函数
	 */
	public function __construct(){
		//调用父类构造函数，必不可少
		parent::__construct();
		$this->set_table('employee_change_task');
	}
	
	/**
	 * 根据条件统计任务的数量
	 * @param int 	$site_id	站点id
	 * @param array $where_arr
	 */
	public function countTask($where_arr){
		$query = $this->db->select('id')->get_where(self::TBL, $where_arr);
		
		if($query->num_rows() > 0){
    		return $query->num_rows();
    	}
    	
    	return 0;
	}
	
	/**
	 * 根据条件获得任务列表
	 * @param array $where_arr
	 * @param int 	$limit
	 * @param int 	$offset
	 */
	public function getTaskList($where_arr, $limit, $offset){
		$this->db->select('id,keyword,type,status,task_info,created');
		$query = $this->db->order_by('type asc,id desc')->get_where(self::TBL, $where_arr, $limit, $offset);
		
		if($query->num_rows() > 0){
			return $query->result_array();
		}
		
		return array();
	}

	/**
	 * @abstract 获得任务详情
	 * @param array $where_arr  查询条件数组
	 *   $where_arr = array(
	 *          'site_id' => $this->p_site_id,
	 *          'recipient_user_id' => $this->p_user_id,
	 *          'type' => $type,//1-add 2-transfer  3-delete
	 *          'id' => $task_id
	 *       );
	 * @return array
	 */
	public function get_task_arr($where_arr = array()){
		//根据id，获得任务内容
		$sel_field = 'id,task_info,status';
		$sel_arr = $this->get_db_arr($where_arr, $sel_field);
		return $sel_arr;
	}
}