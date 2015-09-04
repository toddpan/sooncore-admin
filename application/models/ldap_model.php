<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 账号操作相关的model
 * @file account_model.php
 * @author yuanxinlong <xinlong.yuan@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
class LDAP_Model extends MY_Model{

	const TBL = 'uc_ldap_config';
	public function __construct(){
		parent::__construct();
		$this->set_table('uc_ldap_config');
	}
	
	
	/**
	 * @brief 往数据库中插入ldap规则信息
	 * @param array data 要插入的信息
	 * @return array 获得当前ldap的规则信息
	 */
	public function insertLdapConfig($data){
		$query = $this->db->insert(self::TBL,$data );
		$id = $this->db->insert_id();
		if(!$id) throw new Exception('insert data into uc_ldap_config failed. sql is'.$this->db->last_query());
		return $id ? true : false;
	}
	
	
	/**
	 * @brief 根据ldap_ids
	 * @param int $ldap_id  ldap的id
	 * @return array 获得当前ldap的规则信息
	 */
	public function hasLdapIds($ldap_ids){
		$this->db->where_in('ldap_id', $ldap_ids);
		$ret = $this->db->get(self::TBL);
		return !empty($ret) ? true : false;
	}
	
	/**
	 * @brief 根据ldap_id
	 * @param int $ldap_id  ldap的id
	 * @return array 获得当前ldap的规则信息
	 */
	public function getRuleByLdapId($ldap_id = 0){
		$where	 = array('ldap_id' => $ldap_id);
		$query	 = $this->db->select('rule')->get_where(self::TBL,$where );
		$results = $query->result_array();
		$ret	 = array();
		foreach ($results as $r){
			$ret[] = $r['rule'];
		}
		return $ret;
	}
	
	
	/**
	 * @brief 删除ldap规则信息
	 * @param array $ldap_ids  要删除的ldap的id的数组
	 * @return array 获得当前ldap的规则信息
	 */
	public function deleteLdapConfig($ldap_ids){
		$this->db->where_in('ldap_id', $ldap_ids);
		$ret = $this->db->delete(self::TBL);
		if(!$ret) throw new Exception('detlete into uc_ldap_config failed. sql is'.$this->db->last_query());
		return $ret ? true : false;
	}

	
	/**
	 * @brief 修改ldap规则信息
	 * @param array $data  要修改的ldap的信息
	 * @return array 获得当前ldap的规则信息
	 */
	public function updateLdapConfig($data){
		$this->db->where('ldap_id', $data['ldap_id']);
		$res = $this->db->update(self::TBL,$data);
		if(!$res) throw new Exception('insert data into uc_ldap_config failed. sql is'.$this->db->last_query());
		return $res ? true : false;
	}
	
}

