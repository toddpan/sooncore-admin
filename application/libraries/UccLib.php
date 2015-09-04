<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * ucc server 接口公用类
 * @file UccLib.php
 * @author caohongliang <hongliang.cao@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */

class UccLib{
	
	public function __construct(){
		//载入接口公用函数
		require_once(APPPATH . 'helpers/my_httpcurl_helper.php');
		$this->apiurl = trim(UCC_API, '/\\') ; //接口地址
	}
	
	/**
	 * 创建mq站点交换机
	 * @param array $site_ids 站点id
	 */
	public function createSiteExchange($site_ids){
		$method = 'POST';
		$url  	= $this->apiurl.'/async/siteCreate';
		if(!is_array($site_ids)){$site_ids = (array)$site_ids;}
		$param 	= http_build_query(array('site_id'=>json_encode($site_ids)));
		$ret = httpCurl($url, $param, $method, array(POST_HEAD));
		log_message('info',"ucc api url --> ".$url." param --> ".var_export($param, true)." result -->".var_export($ret, true));
		
		return $ret['code'] == 0;
	}
	
	/**
	 * 创建组织交换机
	 * @param int   $site_id  站点id
	 * @param array $org_ids  组织id
	 */
	public function createOrgExchange($site_id, $org_ids){
		$method = 'POST';
		$url  	= $this->apiurl.'/async/orgCreate';
		if(!is_array($org_ids)){$org_ids = (array)$org_ids;}
		$param 	= http_build_query(array('site_id'=>$site_id, 'org_id'=>json_encode($org_ids))); 
		$ret = httpCurl($url, $param, $method, array(POST_HEAD));
		log_message('info',"ucc api url --> ".$url." param --> ".var_export($param, true)." result -->".var_export($ret, true));
		
		return $ret['code'] == 0;
	}
	
	/**
	 * 数据库分配
	 * @param string $customer_code 客户编码
	 * @param int    $amount		客户用户总量
	 */
	public function dbDispatch($customer_code, $amount){
		$method = 'POST';
		$url  	= $this->apiurl.'/async/dbDispath';
		$params 	= http_build_query(array('customer_code'=>$customer_code, 'amount'=>$amount));
		$ret = httpCurl($url, $params, $method, array(POST_HEAD));
		log_message('info',"ucc api url --> ".$url." params --> ".var_export($params, true)." result -->".var_export($ret, true));
		
		return $ret['code'] == 0;
	}
	
	/**
	 * 为站点分配mq集群
	 * @param int $site_id 站点id
	 * @param int $amount  站点用户总量
	 * @return boolean
	 */
	public function mqDispatch($site_id, $amount){
		$method = 'POST';
		$url  	= $this->apiurl.'/async/mqDispath';
		$param 	= http_build_query(array('site_id'=>$site_id, 'amount'=>$amount));
		$ret = httpCurl($url, $param, $method, array(POST_HEAD));
		log_message('info',"ucc api url --> ".$url." param --> ".var_export($param, true)." result -->".var_export($ret, true));
		
		return $ret['code'] == 0;
	}
	
	/**
	 * 创建同事关系
	 * @param int $user_id   用户id
	 * @param int $org_id    组织id
	 * @param int $parent_id 父组织id
	 * @param int $is_admin  是否为管理员  0-否 1-是
	 */
	public function createColleague($user_id, $org_id, $parent_id, $is_admin = 0){
		$method = 'POST';
		$url  	= $this->apiurl.'/async/createColleague';
		$param 	= http_build_query(array('user_id'=>$user_id, 'org_id'=>$org_id, 'parent_id'=>(int)$parent_id, 'is_admin'=>$is_admin));
		$ret = httpCurl($url, $param, $method, array(POST_HEAD));
		log_message('info',"ucc api url --> ".$url." param --> ".var_export($param, true)." result -->".var_export($ret, true));
		
		return $ret['code'] == 0;
	}
	
	public function deleteColleague($user_id, $org_id, $parent_id, $is_admin = 0){
		$method = 'POST';
		$url  	= $this->apiurl.'/async/deleteColleague';
		$param 	= http_build_query(array('user_id'=>$user_id, 'org_id'=>$org_id, 'parent_id'=>$parent_id, 'is_admin'=>$is_admin));
		$ret = httpCurl($url, $param, $method, array(POST_HEAD));
		log_message('info',"ucc api url --> ".$url." param --> ".var_export($param, true)." result -->".var_export($ret, true));
		
		return $ret['code'] == 0;
	}
	
	/**
	 * 手机短信发送
	 * @param int    $user_id
	 * @param string $content
	 * @param string $mobile
	 */
	public function sendMobileMsg($user_id, $content, $mobile){
		$method = 'POST';
		$url  	= $this->apiurl.'/async/sendMobileMsg';
		$param 	= http_build_query(array('user_id'=>$user_id, 'content'=>$content, 'mobile'=>$mobile));
		$ret = httpCurl($url, $param, $method, array(POST_HEAD));
		log_message('info',"ucc api url --> ".$url."param --> ".var_export($param, true)." result -->".var_export($ret, true));
		
		return $ret['code'] == 0;
	}
        
        
	/**
	 * 获取组织机构列表
         * @author LongWei
	 * @param int    $user_id 用户id （必选）
	 * @param string $session_id 用户session（必选）
	 * @param string $org_id 组织机构id,填0表示获取根节点信息,大于0表示部门信息（必选）
	 * @param string $customer_code 客户编码(必选)
	 */
	public function getOrgList($user_id, $session_id, $org_id, $customer_code){
		$method = 'GET';
		$url  	= $this->apiurl.'/org/list?user_id='.$user_id.'&session_id='.$session_id. '&org_id='.$org_id.'&customer_code='.$customer_code;
		$param 	= array();
		$ret = httpCurl($url, $param, $method);
		log_message('info',"ucc api url --> ".$url."param --> ".var_export($param, true)." result -->".var_export($ret, true));
		
		return $ret['code'] == 0 ? json_decode($ret['http_info']['http_body'],true) : false;
	}
	
        
        
        
        
// 	/**
// 	 * @abstract 登录接口
// 	 * @author Bai Xue <xue.bai_2@quanshi.com>
// 	 * @param string $useraccount 用户账号
// 	 * @param string $password		密码
// 	 * @param int $client_type	用户类型
// 	 * @param string $client_info 用户信息
// 	 */
// 	public function loginApi($useraccount, $password, $client_type, $client_info){
// 		$method = 'POST';
// 		$url  	= $this->apiurl.'user/login';
// 		$param 	= http_build_query(array('user_account'=>$useraccount, 'password'=>$password, 'client_type'=>$client_type, 'client_info'=>$client_info));
// 		$ret = httpCurl($url, $param, $method, array(POST_HEAD));
// 		log_message('info',"ucc api url --> ".$url."param --> ".var_export($param, true)." result -->".var_export($ret, true));
		
// 		return $ret['code'] == 0;
// 	}
	
}

