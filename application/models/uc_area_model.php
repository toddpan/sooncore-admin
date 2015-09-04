<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UC_Area_Model extends MY_Model{
	const TBL = 'uc_area';

    /**
     * 构造函数
     */
    public function __construct(){
        parent::__construct();
        $this->set_table('uc_area');
    }
    
    /**
     * 根据客户编码和站点Id获得地址信息
     * @param string $customerCode
     * @param int 	 $site_id
     */
    public function get_area($customerCode, $site_id){
    	$this->db->select('area');
    	$this->db->where(array('customerCode' => $customerCode, 'siteID' => $site_id));
    	$query = $this->db->get(self::TBL);
    	if($query->num_rows() > 0){
    		return $query->row_array();
    	}
    	return array();
    }
    
    /**
     * 根据客户编码查询地区信息
     * @param string $customerCode
     */
    public function get_area_by_customercode($customerCode){
    	$this->db->select('country,area,address');
    	$this->db->where(array('customerCode' => $customerCode));
    	$query = $this->db->get(self::TBL);
    	 
    	if($query->num_rows() > 0){
    		return $query->result_array();
    	}
    	return array();
    }
    
    /**
     * 获取详细地址
     * @param string $customer_code
     * @param int    $site_id
     */
    public function get_address($customer_code, $site_id){
    	$this->db->select('address')->where(array('customerCode'=>$customer_code, 'siteID'=>$site_id));
    	$query = $this->db->get(self::TBL);
    	return $query->first_row()->address;
    }
    
    /**
     * 添加地址记录
     * @param array $area_info 地址记录
     */
    public function add_area($area_info){
    	$this->db->insert(self::TBL, $area_info);
    	return $this->db->affected_rows();
    }
    
}