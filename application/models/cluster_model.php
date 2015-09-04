<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 集群
 * @file cluster_model.php
 * @author caohongliang <hongliang.cao@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */

class Cluster_Model extends MY_Model{

	public function __construct(){
        parent::__construct(); 
		$this->db = $this->load->database('domain', true);
		$this->tbl = array(
			'cluster'=>'uc_cluster_user_num',//集群表
			'customer_cluster'=>'uc_domain_router',//客户与集群关联表
		);
    }

    public function getCustomerCluster($customer_code, $site_id = null){
    	$this->db->reconnect();
    	$where = array('customerCode'=>$customer_code);
    	if(!is_null($site_id)) $where['siteID'] = $site_id;
    	
    	$ret = $this->db->get_where($this->tbl['customer_cluster'], $where);

    	return $ret->num_rows()>0 ? $ret->first_row('array') : false;
    }
    
    public function saveCustomerCluster($customer_code, $site_id, $site_url, $user_amount, $cluster_id){
    	$this->db->reconnect();
    	try{
    		$this->db->trans_begin();
    		
    		//更新集群中的总人数
    		$x = $this->db->get_where($this->tbl['cluster'], array('clusterID'=>$cluster_id))->first_row();
    		if(!$x) throw new Exception('get cluster info from db failed.the cluster id is-->'.$cluster_id);
    		$x->userAmount += $user_amount;
    		$affect_rows = $this->db->update($this->tbl['cluster'], array('userAmount'=>$x->userAmount), array('clusterID'=>$cluster_id));
    		if(!$affect_rows) throw new Exception('insert cluster data to db failed');
    		
    		//为客户添加集群
    		$insert_data = array(
    				'siteID'=> $site_id,
    				'URL'   => $site_url,
    				'customerCode'=>$customer_code,
    				'clusterID'=>$cluster_id,
    				'userAmount'=>$user_amount
    		);
    		$affect_rows_customer = $this->db->insert($this->tbl['customer_cluster'],$insert_data);		
    		if(!$affect_rows_customer) throw new Exception('insert customer cluster data to db failed');
    		
    		$this->db->trans_commit();
    		return array(true, '');
    	}catch(Exception $e){
    		try{$this->db->trans_rollback();}catch(Exception $e1){}
    		return array(false, $e->getMessage());
    	}
    	
    }
    
    public function getClusters(){
    	$this->db->reconnect();
    	$ret = $this->db->get_where($this->tbl['cluster']);
    	return $ret->num_rows()>0 ? $ret->result_array() : false;
    }
    
    public function getClusterByEnvironmentName($name){
    	$this->db->reconnect();
    	$ret = $this->db->get_where($this->tbl['cluster'],array('EnvironmentName'=>$name));
    	return $ret->num_rows()>0 ? $ret->first_row('array') : false;
    }
    
    public function getClusterByDomain($url){
    	$this->db->reconnect();
    	$ret = $this->db->get_where($this->tbl['cluster'],array('url'=>$url));
    	return $ret->num_rows()>0 ? $ret->first_row('array') : false;
    }
    
}