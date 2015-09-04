<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UC_Account_Model extends MY_Model{

    const TBL = 'uc_account';
    
    /**
     * 构造方法
     */
    public function __construct(){
        parent::__construct(); 
        $this->set_table('uc_account');
    }
    
    /**
     * 根据客户编码查询账户信息
     * @param string $customer_code
     */
    public function get_account($customer_code){
    	$query = $this->db->get_where(self::TBL, array('customercode' => $customer_code));
    	 if($query->num_rows > 0){
    	 	return $query->row_array();
    	 }
    	return array();

    }
}