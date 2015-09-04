<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 生态企业
 * @file ecology.php
 * @author caohongliang <hongliang.cao@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */

class Power_Model extends MY_Model{
	public function __construct(){
        parent::__construct(); 
		$this->tbl = array(
			'site'=>'uc_site',//站点权限
			'org'=>'uc_organization',//组织权限
			'user'=>'uc_user_config',//用户权限
			'customer'=>'uc_customer',
		);
    }
	
	/**
	 * 获取组织权限
	 * -如果当前组织没有设定'特有'的权限配置，则从当前组织的上一级去获取
	 * -如果到了组织的根级都没有获取到，则使用站点的权限配置
	 * @param int    $site_id
	 * @param string $node_code 组织node关联串
	 * @param boolean $is_org_components 是否为组织权限
	 * @return string json串
	 */
	public function getOrgPower($site_id, $node_code, &$templateUUID = null){
		//权限配置，json串
		$value = '';
		
		//获取组织权限配置
		$org_ids = array_reverse(explode('-', trim($node_code, '-')));
		foreach($org_ids as $k=>$org_id){
			$rs = $this->db->select('value')->get_where($this->tbl['org'], array('orgID'=>$org_id));
			if($rs->num_rows()>0){
				$value = $rs->first_row()->value;
				$templateUUID = '-'.implode('-', array_reverse(array_slice($org_ids, $k)));//将数组变成字符串使用横线连接
				break;
			}
		}
		
		//组织内未获取到权限配置，则从站点权限配置里取
		if(is_empty($value)){
			$value = $this->getSitePower($site_id, $templateUUID);
		}
		
		return $value;
	}
	
	/**
	 * 获取用户权限
	 * -查看该用户是否设置了'特有'的权限配置
	 * -如果没有，则去组织里拿
	 * -如果组织里没有拿到，则使用站点权限配置
	 * @param int    $user_id
	 * @param string $node_code 用户上级组织的node code
	 * @return string
	 */
	public function getUserPower($user_id, $node_code){
		$value = '';
		$rs = $this->db->select('value')->get_where($this->tbl['user'], array('userId'=>$user_id));
		if($rs->num_rows()>0){
			$value = $rs->first_row()->value;
		}else{
			$value = $this->getOrgPower($user_id, $node_code);
		}
		return $value;
	}
	
	/**
	 * 获取站点权限
	 * @param int $site_id
	 * @return string
	 */
	public function getSitePower($site_id, &$templateUUID=NULL){
		$value = '';
		$rs = $this->db->select('value,domain')->get_where($this->tbl['site'], array('siteId'=>$site_id));
		if($rs->num_rows()>0){
			$value = $rs->first_row()->value;
			$templateUUID = $rs->first_row()->domain;
		}
		return $value;
	}
	
	public function saveOrgPower($org_id, $value){
		return $this->db->insert($this->tbl['org'], array('value'=>$value), array('orgId'=>$org_id));
	}
	
	public function saveUserPower($user_id, $value){
		return $this->db->insert($this->tbl['user'], array('value'=>$value), array('userId'=>$user_id));
	}
	
	public function saveSitePower($site_id, $value){
		$this->db->where('siteID', $site_id);
		return $this->db->update($this->tbl['site'],array('value'=>$value));
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
}
