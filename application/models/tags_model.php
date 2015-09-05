<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 标签相关的model
 * @file tag_model.php
 * @author caohongliang <hongliang.cao@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */

class Tags_Model extends CI_Model{
	
	public function __construct(){
		parent::__construct();
	
		$this->load->database(DB_RESOURCE);
	
		$this->tbl = array(
			'tags'			=>'uc_user_tags',
			'tags_value'	=>'uc_user_tag_value',
			'site'			=>'uc_site',
		);
	}
	
	/**
	 * 获取站点客户自定义标签名称
	 * @param int $site_id 站点id
	 * @return array
	 */
	public function getCustomTags($site_id){
		$query = $this->db->select('tag_name')->get_where($this->tbl['tags'], array('site_id'=>$site_id, 'enable'=>1, 'tag_type'=>2));
		return $query->num_rows > 0 ? array_column($query->result_array(), 'tag_name') : array();
	}
	
	/**
	 * 获取站点客户可选标签名称
	 * @param int $site_id
	 * @return  array
	 */
	public function getOptionalTags($site_id){
		$query = $this->db->select('tag_name')->get_where($this->tbl['tags'], array('site_id'=>$site_id, 'enable'=>1, 'tag_type'=>1));
		return $query->num_rows > 0 ? array_column($query->result_array(), 'tag_name') : array();
	}
	
	/**
	 * 获取部门level
	 * @param int $site_id
	 * @return mixed
	 */
	public function getDepartmentLevels($site_id){
		$query = $this->db->select('department_level')->get_where($this->tbl['site'], array('siteID'=>$site_id));
		
		return $query->num_rows()>0 ? $query->first_row()->department_level : false;
	}
	
	
	
	
}