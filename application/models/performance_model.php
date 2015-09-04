<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 账号操作相关的model
 * @file account_model.php
 * @author caohongliang <hongliang.cao@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */

class Performance_Model extends CI_Model{
	
	public function __construct(){
		parent::__construct();
		
		$this->load->database(DB_RESOURCE);
		
		$this->tbl = array(
				'p'		=>'uc_performance',//账户户信息表
		);
	}
	
	public function add($one){
		return $this->db->insert($this->tbl['p'],$one);
	}
	
}