<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Boss_Account_Model extends MY_Model{

    const TBL = 'boss_account';
    
    /**
     * 构造方法
     */
    public function __construct(){
        parent::__construct(); 
        $this->set_table('boss_account');
    }
    
    /**
     * 创建账户
     * @param array $account_info 要写入表的数组
     * $account_info = array(
                'customercode' => md5($loginName),
                'account_name' => $loginName
            );
     */
    public function create_account($account_info) {
		$this->db->insert(self::TBL, $account_info);
		if($this->db->affected_rows() > 0){
			return $this->db->affected_rows();
		}
		return false;
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
    
    
    /**
     * 根据客户名称查询账户信息
     * @param string $account_name
     */
    public function get_account_name($account_name){
    	$query = $this->db->get_where(self::TBL, array('account_name' => $account_name));
    	 if($query->num_rows > 0){
    	 	return $query->row_array();
    	 }
    	return array();

    }
}