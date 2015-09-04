<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 账号操作相关的model
 * @file account_model.php
 * @author caohongliang <hongliang.cao@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */

class UC_Site_Config_Model extends CI_Model{
	
	public function __construct(){
		parent::__construct();
		
		$this->load->database(DB_RESOURCE);
	
		$this->tbl = array(
			'site_config'		=>'uc_site_config',//站点属性配置表
			'site'				=>'uc_site',//站点信息表
		);
	}
	
	public function getAllSiteConfig($site_id){	
		
		$ret 		= array();
		
		$query 		= $this->db->select('key,value')->get_where($this->tbl['site_config'],array('site_id' => $site_id,));
		$configs_1 	= array();
		if($query->num_rows() > 0){
			$configs_1 = array_column($query->result_array(),'value', 'key');
		}
		$ret['deployedEnvironment']			= isset($configs_1['deployedEnvironment'])? $configs_1['deployedEnvironment'] : 'A';
		$ret['accountNotifyEmail'] 			= isset($configs_1['accountNotifyEmail']) ? $configs_1['accountNotifyEmail'] : 1;
		$ret['accountNotifySMS']   			= isset($configs_1['accountNotifySMS'])   ? $configs_1['accountNotifySMS'] 	: 1;
		$ret['meetingNotifyEmail'] 			= isset($configs_1['meetingNotifyEmail']) ? $configs_1['meetingNotifyEmail'] : 1;
		$ret['password_existing_prompt'] 	= isset($configs_1['password_existing_prompt']) ? htmlspecialchars_decode($configs_1['password_existing_prompt']) : '请使用已有的全时产品密码';
		$ret['accountDefaultPassword'] 		= isset($configs_1['accountDefaultPassword']) ? htmlspecialchars_decode($configs_1['accountDefaultPassword']) : '';
		$ret['siteAllowChangePassword'] 	= isset($configs_1['siteAllowChangePassword']) ? $configs_1['siteAllowChangePassword'] : (($this->p_is_ldap == 1) ? 0 : 1);
		
		$query 		= $this->db->select('siteID,customerCode,contractId,isLDAP,domain')->get_where($this->tbl['site'],array('siteID'=>$site_id));
		$configs_2  = array();
		if($query->num_rows() > 0){
			$configs_2 = $query->first_row('array');
		}
		$ret['isLdap'] 		 = isset($configs_2['isLDAP'])  	?  $configs_2['isLDAP'] : '';
		$ret['siteId'] 		 = isset($configs_2['siteId'])  	?  $configs_2['siteId'] : $site_id;
		$ret['customerCode'] = isset($configs_2['customerCode'])?  $configs_2['customerCode'] : '';
		$ret['contractId'] 	 = isset($configs_2['contractId'])  ?  $configs_2['contractId'] : '';
		$ret['siteUrl'] 	 = isset($configs_2['siteUrl'])  	?  $configs_2['siteUrl'] : '';
		
		return $ret;
	}
	
	public function getSiteConfig(){
		
	}
	
	public function setSiteConfig($site_id, $key, $value){
		// TODO
	}
		
	/**
	 * 获得站点的认证方式
	 * @author Bai Xue <xue.bai_2@quanshi.com>
	 */
	public function get_ldap_id($site_id){
		$where_arr = array('site_id' => $site_id, 'category' => 'ACCOUNT_AUTHENTICATION_TYPE', 'key'=> 'LDAP_AUTHENTICATION_ID');
		$query = $this->db->select('value')->get_where($this->tbl['site_config'], $where_arr);
		
		if($query->num_rows() > 0){
			$row = $query->row_array();
			return $row['value'];
		}
		
		return false;
	}
	
	/**
	 * 保存站点认证配置
	 * @param	array $config_arr=array(
	 * 							'site_id' 		=> 111, 		// 必填
	 * 							'category' 		=> ldap, 		// 必填
	 * 							'key' 			=> 'ldap_id', 	// 必填
	 * 							'value' 		=> 2,			// 必填
	 * 							'create_time' 	=> '2015-04-15 16:36:18'
	 * 						)
	 * @author Bai Xue <xue.bai_2@quanshi.com>
	 */
	public function save_config($config_arr){
		log_message('debug', '$value=11114444444');
		try {
			//$this->db->trans_begin();
			
			// 查询当前站点是否有LDAP配置
			$value = $this->get_ldap_id($this->p_site_id);
			
			log_message('debug', '$value='.$value);
			
			// 没有则新建
			if($value === false){
				$this->db->insert($this->tbl['site_config'], $config_arr);
					
				if($this->db->affected_rows() < 1){
					throw new Exception('insert data into uc_site_config failed');
				}
			}else{
				// 有且值发生了变化，则更新
				if($value != $config_arr['value']){
					$where_arr = array(
						'site_id' 	=> $config_arr['site_id'], 		
						'category' 	=> $config_arr['category'], 		
						'key' 		=> $config_arr['key'], 	
					);
					$update_arr = array(
						'value' 	=> $config_arr['value']
					);
					$res = $this->update_value($where_arr, $update_arr);
					if(!$res){
						throw new Exception('update data into uc_site_config failed');
					}
				}
			}
			
			//$this->db->trans_commit();
			return true;
		}catch(Exception $e){
			log_message('error', $e->getMessage());
			return false;
		}
	}
	
	/**
	 * 更新站点认证配置
	 * 
	 * @param array $where_arr = array(
						'site_id' 	=> 453, 		
						'category' 	=> 'ldap', 		
						'key' 		=> 'ldap_id', 	
					);
	 * @param array $update_arr = array(
						'value' 	=> 111
					);
	 * 
	 * @author Bai Xue <xue.bai_2@quanshi.com>
	 * @return boolean
	 */
	public function update_value($where_arr = array(), $update_arr = array()) {
		$this->db->update($this->tbl['site_config'], $update_arr, $where_arr);
		
		if($this->db->affected_rows() < 1){
			return false;
		}
		
		return true;
	}
	
	/**
	 * 获得站点配置
	 * 
	 * @param array $where_arr = array(
						'site_id' 	=> 453, 		
						'category' 	=> 'ldap', 		
						'key' 		=> 'ldap_id', 	
					);
	 * 
	 * @author Bai Xue <xue.bai_2@quanshi.com>
	 * @return array
	 */
	public function get_site_config($where_arr){
		$query = $this->db->get_where($this->tbl['site_config'], $where_arr);
		
		if($query->num_rows() > 0){
			return $query->row_array();
		}
		
		return array();
	}
	
	/**
	 * 根据条件获取所有value值
	 * 
	 * 注意这里的get方法，获得一个值时，返回string，多个值返回array.
	 * 
	 * @author ge.xie
	 *
	 */
	public function getValue($category, $site_id=null, $key=null) {
		$this->db->reconnect();
		$where_arr = array();
		$where_arr['category'] = $category;
		isset($site_id) ? $where_arr['site_id'] = $site_id : null;
		isset($key) ? $where_arr['key'] = $key : null;
	
		$query = $this->db->select('value')->get_where($this->tbl['site_config'], $where_arr);
		
		if ($query->num_rows() == 1) {
			$rows = $query->row_array();
			return $rows['value'];
		}
		
		if ($query->num_rows() > 1){
			$rows = $query->result_array();
			foreach ($rows as $row) {
				$values[] = $row['value'];
			}
			return $values;
		} 
	
		return false;
	}
	
	/**
	 * 根据条件获得所有site值
	 * 
	 * 注意这里的get方法，获得一个值时，返回string，多个值返回array.
	 * 
	 * @author ge.xie
	 */
	public function getSiteId($category, $key=null, $value=null) {
		$this->db->reconnect();
		$where_arr = array();
		$where_arr['category'] = $category;
		isset($value) ? $where_arr['value'] = $value : null;
		isset($key) ? $where_arr['key'] = $key : null;
		
		$query = $this->db->select('site_id')->get_where($this->tbl['site_config'], $where_arr);
		
		if ($query->num_rows() == 1) {
			$rows = $query->row_array();
			return $rows['site_id'];
		}
		
		if ($query->num_rows() > 1){
			$rows = $query->result_array();
			foreach ($rows as $row) {
				$site_ids[] = $row['site_id'];
			}
			return $site_ids;
		} 
		
		return false;
	}
	
	/**
	 * insert
	 *
	 * @author ge.xie
	 *
	 * @param string $category
	 * @param string $site_id
	 * @param string $key
	 * @param string $value
	 */
	public function insertValue($category, $site_id, $key, $value) {
		$this->db->reconnect();
		$insert_arr = array(
				'category' => $category,
				'site_id' => $site_id,
				'key' => $key,
				'value' => $value,
				'create_time' => date("Y-m-d H:i:s")
		);
	
		$this->db->insert($this->tbl['site_config'], $insert_arr);
	}
	
	/**
	 * insert batch
	 *
	 * @author ge.xie
	 *
	 * @param array $values
	 */
	public function insertValues($values) {
		$this->db->reconnect();
		return $this->db->insert_batch($this->tbl['site_config'], $values);
	}
	
	/**
	 * 先delete再insert，对value进行赋值
	 * 
	 * @author ge.xie
	 */
	public function setVaule($category, $site_id, $key, $value) {
		$this->db->reconnect();
		$this->db->delete($this->tbl['site_config'], array('category'=>$category,'site_id'=>$site_id,'key'=>$key));
		
		$this->insertValue($category, $site_id, $key, $value);
	}
	
	/**
	 * 对于多条数据通过先delete再insert,对value进行赋值
	 * 
	 * @author
	 * 
	 * @param array 赋值的数组 $values[[$category, $site_id, $key, $value],[$category, $site_id, $key, $value]……]
	 */
	public function setValues($values) {
		$this->db->reconnect();
		foreach ($values as $value) {
			unset($value['id']);
			unset($value['value']);
			unset($value['create_time']);
			$this->db->delete($this->tbl['site_config'], $value);		
		}
		$this->insertValues($values);
		return true;
	}
	
	/**
	 * 删除记录
	 * 
	 * @author Bai Xue <xue.bai_2@quanshi.com>
	 */
	public function delete_vaule($category, $site_id, $key) {
		$this->db->delete($this->tbl['site_config'], array('category'=>$category,'site_id'=>$site_id,'key'=>$key));
	}
	
	/**
	 * 保存站点通知设置
	 */
	public function save_inform_set($config_arr){
		try {
			//$this->db->trans_begin();
				
			// 查询当前站点是否有LDAP配置
			$value = $this->get_inform_config($this->p_site_id, $config_arr['category'], $config_arr['key']);
				
			log_message('debug', '$value='.$value);
				
			// 没有则新建
			if($value === false){
				$config_arr['create_time'] = date("Y-m-d H:i:s");
				$this->db->insert($this->tbl['site_config'], $config_arr);
					
				if($this->db->affected_rows() < 1){
					throw new Exception('insert data into uc_site_config failed');
				}
			}else{
				// 有且值发生了变化，则更新
				if($value != $config_arr['value']){
					$where_arr = array(
							'site_id' 	=> $config_arr['site_id'],
							'category' 	=> $config_arr['category'],
							'key' 		=> $config_arr['key']
					);
					$update_arr = array(
							'value' 	=> $config_arr['value']
					);
					$res = $this->update_value($where_arr, $update_arr);
					if(!$res){
						throw new Exception('update data into uc_site_config failed');
					}
				}
			}
				
			//$this->db->trans_commit();
			return true;
		}catch(Exception $e){
			log_message('error', $e->getMessage());
			return false;
		}
	}
	
	public function get_inform_config($site_id, $category, $key){
		$where_arr = array('site_id' => $site_id, 'category' => $category, 'key'=> $key);
		$query = $this->db->select('value')->get_where($this->tbl['site_config'], $where_arr);
		
		if($query->num_rows() > 0){
			$row = $query->row_array();
			return $row['value'];
		}
		
		return false;
	}
	
	
	
	/**
	 * 根据 site_id 获得账号导入方式
	 *
	 * @author ge.xie
	 * @param string $site_id
	 * @return number
	 */
	public function getImportType($site_id) {
		$importMode = $this->getValue('ACCOUNT_AUTHENTICATION_TYPE', $site_id, 'DATA_IMPORT_TYPE');
				
		switch ($importMode) {
			case 'xml':
				$formatType = 1;
				break;
			case 'ldap':
				$formatType = 2;
				break;
			default:
				$formatType = 0;
				break;
		}
		
		return $formatType;
	}
}