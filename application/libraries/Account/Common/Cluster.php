<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 集群分配
 * @file Cluster.php
 * @author caohongliang <hongliang.cao@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */


class Cluster{
	
	protected $user_amount;
	protected $customer_code;
	protected $ci;
	
	/**
	 * 初始化
	 * @param string $customer_code  客户编码
	 * @param int    $user_amount    员工数量
	 */
	public function __construct($customer_code, $user_amount){
		$this->user_amount   = $user_amount;
		$this->customer_code = $customer_code;
		
		$this->ci = & get_instance();
		$this->ci->load->model('cluster_model', 'cluster');
	}
	
	/**
	 * 集群分配算法
	 * 
	 * - 一个客户的所有员工必须分配到一个集群里
	 * - 当一个集群里的员工数量达到了安全上限，开始往下一个集群里分配客户的员工
	 * - 如果一个客户可以分配到多个非空的集群里，则选择人数较少的那个集群
	 */
	public function getCluster($user_amount = NULL){
		
		$user_amount = is_null($user_amount) ? $this->user_amount : $user_amount;
		
		$ret_cluster = NULL;
		
		try{
			//获取所有的集群
			$this->clusters = $this->_getClusters();
			
			//将所有的集群按照剩余容量从小到大排序
			usort($this->clusters, array($this, '_sort_clusters'));
			
			//获得所有的集群中剩余容量最大的集群
			$max_amount_cluster = array_pop($this->clusters);
			
			//从所有的集群中获取第一个非空的集群，因为上面已经排过序，所以这里获取到的是非空的集群中容量最大的集群.
			$max_amount_used_cluster = $this->_getFirstUsedCluster($this->clusters);
			
			//这种算法也考虑到了以下几种情况：
			//1.所有的集群都为空。选出容量最大的一个空集群。
			//2.所有的集群包含空集群和非空集群。如果非空的集群,可以满足当前客户的需求，则使用非空的集群。
			//	反之使用所有集群中剩余容量最大的一个。
			//3.所有的集群都已被占用。选出剩余容量最大的一个非空集群
			//4.所有的集群添加当前客户后，都会超出安全上限。选出超出上限最少的一个集群。
			if($max_amount_used_cluster && !$this->_exceed_limit($max_amount_used_cluster, $this->user_amount)){
				$ret_cluster = $max_amount_used_cluster;
			}else{
				$ret_cluster = $max_amount_cluster;
			}
			
			//如果最终返回的集群用户量超出了安全上限，则记log预警
			if($this->_exceed_limit($ret_cluster, $this->user_amount)){
				throw new Exception('Fatal error:A cluster have been exceeded safe limit amount.The cluster id is-->'.$ret_cluster['clusterID']);
			}
			
		}catch(Exception $e){
			log_message('error', $e->getMessage());
		}
		
		//返回最终分配到的集群
		return $ret_cluster;
		
	}
	
	
	/**
	 * 判断客户是否已经分配过集群
	 * @param  string $customer_code 客户编码
	 * @return mix 
	 */
	public function isAllocated($customer_code = NULL, $site_id = NULL){
		$customer_code = is_null($customer_code) ? $this->customer_code : $customer_code;
		return $this->ci->cluster->getCustomerCluster($customer_code, $site_id);
	}
	
	
	public function allocate($customer_code, $site_id, $site_url, $user_amount, $cluster_id){
		return $this->ci->cluster->saveCustomerCluster($customer_code, $site_id, $site_url, $user_amount, $cluster_id);
	}
	
	
	
	/**
	 * 获取所有的集群
	 * @throws Exception
	 * @return array
	 */
	public function _getClusters(){
		if(!$clusters = $this->ci->cluster->getClusters() ){
			throw new Exception('Not found cluster in db. please check out!');
		}
		return $clusters;
	}
	
	/**
	 * 集群排序
	 * -排序规则是根据集群剩余容量的大小
	 * @param array $a  集群a
	 * @param array $b  集群b
	 * @return int
	 */
	public function  _sort_clusters($a, $b){
		return ($a['topLimit']*SAFE_RATIO - $a['userAmount']) - ($b['topLimit']*SAFE_RATIO - $b['userAmount']);
	}
	
	
	/**
	 * 遍历集群列表，获取第一个非空的集群
	 * @param   array $clusters 集群列表
	 * @return  mix
	 */
	public function _getFirstUsedCluster($clusters){
		foreach($clusters as $cluster){
			if($cluster['userAmount'] > 0){
				return $cluster;
			}
		}
		
		return false;
	}
	
	/**
	 * 判断集群添加了一定数量的用户后，是否超过了安全上限
	 * @param array $cluster       集群
	 * @param int   $user_amount   用户数量
	 * @return boolean
	 */
	public function _exceed_limit($cluster, $user_amount){
		return ($cluster['userAmount'] + $user_amount - ceil($cluster['topLimit']*SAFE_RATIO) ) > 0;
	}
	
	
	
	
	
	
	
	
	
}