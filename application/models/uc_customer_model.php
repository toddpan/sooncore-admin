<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UC_Customer_Model extends MY_Model{

    const TBL = 'uc_customer';
    //构造函数
    public function __construct(){
        //调用父类构造函数，必不可少
        parent::__construct(); 
        $this->set_table('uc_customer');
    }
    
    /**
     * @abstract 根据条件查询合同Id
     */
    public function getContractid($where_arr){
    	$query = $this->db->get_where(self::TBL, $where_arr);
    	return $query->row_array();
    }
    
    /**
     * 添加客户信息
     * @param array $customer_info 客户信息
     */
    public function add_customer($customer_info){
    	$this->db->insert(self::TBL, $customer_info);
    	return $this->db->affected_rows();
    }
}