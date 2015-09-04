<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @abstract 	手机短信验证码模型，主要负责对手机验证码的增删改查等操作
 * @author		Bai Xue <xue.bai_2@quanshi.com>
 * @copyright	Copyright (c) UC
 * @version 	v1.0
 */
class uc_phone_varification_code_model extends CI_Model {
	// 定义表名称
	const TBL = 'uc_phone_varification_code';
	
	/**
	 * 构造方法
	 */
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * 保存手机短信验证码
	 * @param array $code_info 需要被保存的短信信息数组
	 */
	public function saveCode($code_info) {
		$this->db->insert(self::TBL, $code_info);
		if($this->db->affected_rows() > 0){
			return true;
		}
		return false;
	}
	
	/**
	 * 根据条件查询验证码信息
	 * @param array $where_arr  条件数组
	 */
	public function getCode($where_arr) {
		$query = $this->db->get_where(self::TBL, $where_arr);
		
		if($query->num_rows() > 0){
			return $query->last_row('array');
		
		}
		
		return array();
	}
	
	/**
	 * 根据条件更新验证码记录
	 * @param array $where_arr 条件数组
	 * @param array $update_data 需要被修改的数据
	 */
	public function updateCode($where_arr, $update_data) {
		$this->db->update(self::TBL, $update_data, $where_arr);
		
		if($this->db->affected_rows() > 0){
			return true;
		}
		
		return false;
	}
	
	/**
	 * 根据userId统计短信验证码的个数
	 * @param int $user_id
	 */
	public function count($user_id){
		$query = $this->db->get_where(self::TBL, array('user_id' => $user_id));
		return $query->num_rows();
	}
	
	/**
	 * 根据userId获得手机验证码
	 * @param unknown $user_id
	 */
	public function getCodeByUserId($user_id){
		$query = $this->db->get_where(self::TBL, array('user_id' => $user_id));
		if($query->num_rows() > 0){
			return $query->row_array();
		}
		return array();
	}
	
	/**
	 * 根据条件删除验证码记录
	 * @param array $where_arr 条件数组
	 */
	public function del_code($where_arr){
		$this->db->delete(self::TBL, $where_arr);
		
		if($this->db->affected_rows() > 0){
			return true;
		}
		return false;
	}
}