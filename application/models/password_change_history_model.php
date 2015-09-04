<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Password_Change_History_Model extends CI_Model {
	
	const TBL = 'password_change_history';
	
	/**
	 * 构造方法
	 */
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * 根据条件获得密码历史记录
	 * @param array $condition  条件数组
	 */
	public function get_pwd_records($condition){
		$query = $this->db->order_by('id', 'desc')->get_where(self::TBL, $condition);
		
		if($query->num_rows() > 0 ){
			return $query->result_array();
		}
		
		return array();
	}
	
	/**
	 * 添加密码记录
	 * @param array $insert_data  
	 */
	public function save_newpwd($insert_data) {
		$this->db->insert(self::TBL, $insert_data);
		
		if($this->db->affected_rows() > 0){
			return true;
		}
		
		return false;
	}
	
	
	/**
	 * 更新密码记录
	 * @param array $where_arr  条件数组
	 * @param array $uplade_data  被更新的数据
	 */
	public function update_newpwd($where_arr, $update_data){
		$this->db->update(self::TBL, $update_data, $where_arr);
		
		if($this->db->affected_rows() > 0){
			return true;
		}
		
		return false;
	}
	
	/**
	 * 删除密码记录
	 * @param array $where_arr
	 */
	public function del_pwd($where_arr) {
		$this->db->delete(self::TBL, $where_arr);
		
		if($this->db->affected_rows() > 0){
			return true;
		}
		
		return false;
	}
}