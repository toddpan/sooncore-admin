<?php

class UC_Crippleware_Site_Value_Model extends CI_Model {
	
	const TBL = 'uc_crippleware_value_model';
	
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * 根据类型获得试用账号站点属性
	 * @param int $type
	 */
	public function get_value($type){
		$query = $this->db->get_where(self::TBL, array('type' => $type));
		
		if($query->num_rows > 0){
			return $query->row_array();
		}
		
		return array();
	}
}