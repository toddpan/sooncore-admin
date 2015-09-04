<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * ums 接口公用类
 * @file UmsLib.php
 * @author caohongliang <hongliang.cao@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
class UmsLib{
    
	public $scope_subtree = 'subtree';
	public $scope_samelevel = 'samelevel';
	public $scope_nextlevel = 'nextlevel';//subtree-所有子组织 samelevel-同级组织 nextlevel-下级组织
    public function __construct() {
        //载入接口公用函数
		require_once(APPPATH . 'helpers/my_httpcurl_helper.php');
        $this->apiurl = UMS_API ; //接口地址
        $this->ldapurl = LDAP_API ; //LDAP接口地址
    }
    
    //===================站点相关函数================
    /**
     * 通过站点url,精确查询站点
     * @param string $site_url 站点url
     */
    public function getSiteInfoByUrl($site_url){
    	$method = 'GET';
    	$params = array();
    	$url  	= $this->apiurl.'rs/sites?url='.$site_url;
    	$ret 	= httpCurl($url, $params,$method);
    	log_message('info',"get data from ums url is->".$url." result is->".var_export($ret, true));
    	if($ret['code'] == 0  && $ret['data'] != ''){
    		return json_decode($ret['data'], true);
    	}else{
    		return false;
    	}
    }
    
    /**
     * 通过站点id,精确查询站点
     * @param string $site_id 站点id
     */
    public function getSiteInfoById($site_id){
    	$method = 'GET';
    	$params = array();
    	$url  	= $this->apiurl.'rs/sites/'.$site_id;
    	$ret 	= httpCurl($url, $params,$method);
    	log_message('info',"get data from ums url is->".$url." result is->".var_export($ret, true));
    	if($ret['code'] == 0  && $ret['data'] != ''){
    		return json_decode($ret['data'], true);
    	}else{
    		return false;
    	}
    }
    
    /**
     * 通过客户编码,精确查询站点
     * @param string $customer_code 客户编码
     */
    public function getSiteInfoByCustomercode($customer_code){
    	$method = 'GET';
    	$params = array();
    	$url  	= $this->apiurl.'rs/sites/customercode/'.$customer_code;
    	$ret 	= httpCurl($url, $params,$method);
    	log_message('info',"get data from ums url is->".$url." result is->".var_export($ret, true));
    	if($ret['code'] == 0  && $ret['data'] != ''){
    		return json_decode($ret['data'], true);
    	}else{
    		return false;
    	}
    }
    
    /**
     * 创建站点
     * @param  array  $site_info 站点信息
     */
    public function createSite($site_info){
    	$method = 'POST';
    	$params = json_encode($site_info);
    	$url  	= $this->apiurl.'rs/sites';
    	$header = array("Content-Type: application/json",
           				"Accept: application/json"
                            );
    	$ret 	= httpCurl($url, $params,$method, $header);
    	log_message('info',"Create site ->".$url." result is->".var_export($ret, true));
    	return $ret['code'] == 0 ? $ret['http_info']['http_body']: false;
    }
    
    //===================组织相关接口================
    
    /**
     * 查询组织成员
     * @param int $org_id 组织id
     * @reutrn mix
     */
    public function getOrganizationUsers($org_id, $product_id=UC_PRODUCT_ID){
    	$method = 'GET';
    	$url  	= $this->apiurl.'rs/organizations/'.$org_id.'/users?productID='.$product_id;
    	$params = array();
    	$ret 	= httpCurl($url, $params,$method);
    	log_message('info',"get data from ums url is->".$url." result is->".var_export($ret, true));
    	if($ret['code'] == 0  && $ret['data'] != ''){
    		return json_decode($ret['data'], true);
    	}else{
    		return false;
    	}
    }
    
    /**
     * 查询子组织
     * 
     * @param int    $org_id 组织id
     * @param string $scope  查询范围 subtree-所有子组织 samelevel-同级组织 nextlevel-下级组织
     * @param string $type   类型    1-企业 2-生态企业 3-部门 4-生态企业部门 5-分公司
     * return mix
     */
    public function getOrganization($org_id, $scope='subtree', $type='1,2,3,4,5'){
        $method = 'GET';
        $url  = $this->apiurl.'rs/organizations/'.$org_id;
        $params = array('scope'=>$scope, 'types'=>$type);
        $ret = httpCurl($url, $params,$method);
		log_message('info', "get data from ums url is->{$url}");
		//log_message('info',"get data from ums url is->".$url." result is->".var_export($ret, true));
		if($ret['code'] == 0  && $ret['data'] != ''){
			return json_decode($ret['data'], true);
        }else{
            return false;
        }
    }
    
    /**
     * 通过id查询组织
     * @param int $org_id  组织id
     * @return mixed|boolean
     */
    public function getOrganizationById($org_id){
    	$method = 'GET';
    	$url  = $this->apiurl.'rs/organizations/'.$org_id.'/brief';
    	$params = array();
    	$ret = httpCurl($url, $params, $method);
    	log_message('info',"get data from ums url is->".$url." result is->".var_export($ret, true));
    	if($ret['code'] == 0  && $ret['data'] != ''){
    		return json_decode($ret['data'], true);
    	}else{
    		return false;
    	}
    }
    
	/**
	 * 获取组织详情，这个接口返回了组织下面的子组织和子成员
	 * @param int $org_id 组织id
	 * @param int $type   组织类型 1:企业 2:生态企业 3:部门 4:生态企业部门
	 * @return mix
	 */
	public function getOrganizationDetail($org_id, $type){
		if(!is_numeric($org_id)){
            return false;
        }
		if(!in_array($type, array(1,2,3,4))){
            return false;
        }
		$method = 'GET';
        $url  = $this->apiurl.'rs/organizations/'.$org_id.'/details?types='.$type;
		$ret = httpCurl($url, $params,$method);
		log_message('info',"get data from ums url is->".$url." result is->".var_export($ret, true));
		if($ret['code'] == 0  && $ret['data'] != ''){
			return json_decode($ret['data'], true);
        }else{
            return false;
        }
	}
	
	/**
	 * 获取组织信息
	 * @param int $org_id 组织id
	 * @return mix
	 */
	public function getOrganizationBrief($org_id){
		if(!is_numeric($org_id)){
            return false;
        }
		$method = 'GET';
        $url  = $this->apiurl.'rs/organizations/'.$org_id.'/brief';
		$ret = httpCurl($url, array(),$method);
		
		log_message('info',"get data from ums url is->".$url." result is->".var_export($ret, true));
		
		if($ret['code'] == 0  && $ret['data'] != ''){
			return json_decode($ret['data'], true);
        }else{
            return false;
        }
	}
	
	/**
	 * 删除组织
	 * @param int $org_id 组织id
	 * @return mix
	 */
	public function delOrganization($org_id){
		$method = 'DELETE';
        $url  = $this->apiurl.'rs/organizations/'.$org_id;
		$ret = httpCurl($url, array(),$method);
		
		log_message('info',"get data from ums url is->".$url." result is->".var_export($ret, true));
		
		return $ret['code'] == 0 ? true: false;
	}
	
	/**
	 * 创建一个组织
	 * @param array $org_info
	 * @return mix 成功则返回组织id
	 */
	public function createOrganization($org_info){
		$method = 'POST';
		$url  = $this->apiurl.'rs/organizations';
		$ret = httpCurl($url, json_encode($org_info),$method);
		
		log_message('info',"create organization to ums url is->{$url}. post data is-->".var_export($org_info, true)." result is->".var_export($ret, true));
		
		return $ret['code'] == 0 ? $ret['http_info']['http_body']: false;
	}
	
	/**
	 * 批量创建组织
	 * @param unknown $org_infos
	 * @return array
	 */
	public function createOrganizations($org_infos){
		$method = 'POST';
		$url  = $this->apiurl.'rs/organizations/list';
		$ret = httpCurl($url, json_encode($org_infos),$method);
		
		log_message('info',"create organizations to ums url is->{$url}. post data is-->".var_export($org_infos, true)." result is->".var_export($ret, true));
		
		if($ret['code'] == 0 && isset($ret['http_info']['http_body'])){
			return json_decode($ret['http_info']['http_body'], true);
		}else{
			return false;
		}
	}
	
	/**
	 * 将已有用户添加到组织下
	 * 
	 * @param int $user_id 用户id
	 * @param int $org_id  组织id
	 */
	public function addUserToOrg($user_id, $org_id){
		$method = 'POST';
		$url  	= $this->apiurl.'rs/organizations/'.$org_id.'/users/'.$user_id;
		$params = array();
		$ret = httpCurl($url, json_encode($params),$method);
		
		log_message('info',"add user to organization in ums url is->".$url." result is->".var_export($ret, true));
		
		return $ret['code'] == 0 ? true: false;
	}
	
	/**
	 * 新建用户，并将该用户添加在组织下
	 * 
	 * @param array $user_info 用户信息
	 * @param int   $org_id    组织id
	 */
	public function addNewUserToOrg($user_info,$org_id){
		$method = 'POST';
		$url  = $this->apiurl.'rs/organizations/'.$org_id.'/users';
		$ret = httpCurl($url, json_encode($user_info),$method);
		
		log_message('info',"add new user to organization in ums url is->".$url." param is -->".var_export($user_info,true)." result is->".var_export($ret, true));
		
		if($ret['code'] == 0 && isset($ret['http_info']['http_body'])){
			return json_decode($ret['http_info']['http_body'], true);
		}else{
			return false;
		}
	}
	
	
	/**
	 * 改变用户所在的组织
	 * @param int $user_id     用户id
	 * @param int $from_org_id 源组织id
	 * @param int $to_org_id   目标组织id
	 */
	public function changeUserOrg($user_id, $from_org_id, $to_org_id){
		$method = 'PUT';
		$url  = $this->apiurl.'rs/organizations/change_organization';
		$param = array(
			'id'=>$user_id,
			'from'=>$from_org_id,
			'to'=>$to_org_id
		);
		$ret = httpCurl($url, json_encode($param),$method);
		
		log_message('info',"change user organization in ums url is->".$url." param is -->".var_export($param, true)." result is->".var_export($ret, true));
		
		return $ret['code'] == 0 ? true: false;
	}
	
	/**
	 * 通过客户编码查询组织信息
	 * @param string $customerCode	客户编码
	 * @access xue.bai_2@quanshi.com
	 */
	public function getOrganizeByCustomerCode($customerCode) {
		$method = 'GET';
		$url = $this->apiurl. 'rs/organizations?customer_code=' . $customerCode;
		$params = array();
		$ret = httpCurl($url, json_encode($params),$method);
		log_message('info',"get data from ums url is->".$url." result is->".var_export($ret, true));
		
		return $ret['code'] == 0 ? $ret['http_info']['http_body'] : false;
	}
	
    /**
     * 模糊查询组织或者用户
     * 用户匹配：display_name, login_name, mobile_number
     * 组织匹配：name
     * 
     * @param string $customer_code 客户编码
     * @param string $keyword       搜索关键字
     * @param int    $type          0–两者都需要 1–用户  2–企业生态名称
     * @param array  $org_ids       需要查询的组织id
     * @return mix
     * 
     */
    public function searchOrgOrUser($customer_code, $keyword, $type, $org_ids=array()){
        if(!is_numeric($type) || !in_array($type, array(0,1,2))){
            return false;
        }
        $method = 'POST';
        $url  = $this->apiurl.'rs/users/getByOrgOrUser?customerCode='.$customer_code.'&searchText='.$keyword.'&type='.$type;
		$ret = httpCurl($url, json_encode($org_ids), $method);
		log_message('info',"get data from ums url is->".$url."param is->".var_export($org_ids, true)." result is->".var_export($ret, true));
		if($ret['code'] == 0 && isset($ret['http_info']['http_body'])){
            return json_decode($ret['http_info']['http_body'], true);
        }else{
            return false;
        }
    }
    
    /**
     * 根据用户Id查询用户所在组织信息
     * 
     * 返回array([0] => array([id] => 9 [name] => 北京公司 [code] => 003530……)), 注意和getOrganizationByUserId的区别.
     * 
     * @param unknown $user_id
     */
    public function getOrgInfoByUserId($user_id){
    	$method = 'GET';
    	$url = $this->apiurl. 'rs/users/'.$user_id .'/organizations';
    	$params = array();
    	$ret = httpCurl($url, json_encode($params),$method);
    	log_message('info',"get data from ums url is->".$url." result is->".var_export($ret, true));
    	
    	if($ret['code'] == 0 && isset($ret['http_info']['http_body'])){
    		return json_decode($ret['http_info']['http_body'], true);
    	}else{
    		return false;
    	}
    }
    
    //=========================用户相关接口=====================
    /*
     * 根据用户id获取用户信息
     * @param int $user_id  用户id
     * @return mix
     */
    public function getUserById($user_id){
        $method = 'GET';
        $url  = $this->apiurl.'rs/users/getUserById?userId='.$user_id;
        $ret = httpCurl($url, array(), $method);
        log_message('info',"get data from ums url is->".$url." result is->".var_export($ret, true));
		if($ret['code']== 0 && isset($ret['data'])){
            return json_decode($ret['data'] ,true);
        }else{
            return false;
        }
    }
    
    /**
     * 根据用户登陆名获取用户信息
     * @param string $loginName 用户登陆名
     * @return mixed|boolean
     */
    public function getUserByLoginName($loginName){
    	$method = 'GET';
    	$url  = $this->apiurl.'rs/users/getUserByName?loginName='.$loginName;
    	$ret = httpCurl($url, array(), $method);
    	log_message('info',"get data from ums url is->".$url." result is->".var_export($ret, true));
    	if($ret['code']== 0 && isset($ret['data'])){
    		return json_decode($ret['data'] ,true);
    	}else{
    		return false;
    	}
    }
	
	/**
	 * 根据用户的ids,获取用户信息
	 * @param array $ids 员工id 
	 */
	public function getUserByIds($ids){
		$method = 'POST';
        $url  = $this->apiurl.'rs/users/id/in';
        $ret = httpCurl($url, json_encode($ids), $method);
        log_message('info',"ums api url --> ".$url."param --> ".var_export($ids, true)." result -->".var_export($ret, true));
		if($ret['code'] == 0 && isset($ret['http_info']['http_body'])){
            return json_decode($ret['http_info']['http_body'] ,true);
        }else{
            return false;
        }
	}
    
	/**
	 * 获取用户产品信息
	 * @param int $user_id
	 * @param int $product_id
	 */
	public function getUserProduct($user_id, $product_id){
		$method = 'GET';
        $url  = $this->apiurl.'rs/users/getUserProductList?userId='.$user_id.'&productId='.$product_id;
        $ret = httpCurl($url, array(), $method);
        log_message('info',"get data from ums url is->".$url." result is->".var_export($ret, true));
		if($ret['code']== 0 && isset($ret['data'])){
            return json_decode($ret['data'] ,true);
        }else{
            return false;
        }
	}
	
	/**
	 * 删除用户
	 * @param int $user_id 用户id
	 */
	public function delUserById($user_id){
		$method = 'DELETE';
        $url  = $this->apiurl.'rs/users/'.$user_id.'/delete';
        $ret = httpCurl($url, array(), $method);
        log_message('info',"get data from ums url is->".$url." result is->".var_export($ret, true));
		return $ret['code']== 0 ? true : false;
	}
	
	/**
	 * 根据用户的id获取用户所在的组织信息
	 * 
	 * 返回array([id] => 9 [name] => 北京公司 [code] => 003530……), 注意和getOrgInfoByUserId的区别.
	 */
	public function getOrganizationByUserId($user_id){
		$method = 'GET';
        $url  = $this->apiurl.'rs/users/'.$user_id.'/organizations';
        $ret = httpCurl($url, array(), $method);
		log_message('info',"get org info by user id from ums. url is->".$url." result is->".var_export($ret, true));
		if($ret['code'] == 0 && isset($ret['data'])){
            $orgs = json_decode($ret['data'], true);
			return array_shift($orgs);
        }else{
            return false;
        }
	}
	
	/**
	 * 可以返回多个组织
	 * @param unknown $user_id
	 * @return unknown|boolean
	 */
	public function getOrganizationsByUserId($user_id){
		$method = 'GET';
		$url  = $this->apiurl.'rs/users/'.$user_id.'/organizations';
		$ret = httpCurl($url, array(), $method);
		log_message('info',"get org info by user id from ums. url is->".$url." result is->".var_export($ret, true));
		if($ret['code'] == 0 && isset($ret['data'])){
			$orgs = json_decode($ret['data'], true);
			return $orgs;
		}else{
			return false;
		}
	}
	
	/**
	 * 分页获取未指定成本中心分组的用户
	 * @param type $exclude_users 已经指定分组的用户
	 * @param type $org_id 组织id
	 * @param type $limit
	 * @param type $offset
	 * @param type $sort_by
	 * @param type $direct
	 */
	public function getUnGroupedCostMemebers($customer_code, $org_id, $exclude_users, $limit=15, $offset=0, $sort_by='id', $direct='asc'){
		$method = 'POST';
        $url  = $this->apiurl.'rs/users/excludeExamplesUsers/customerCode/'.$customer_code.'?orgId='.$org_id.'&limit='.$limit.'&offset='.$offset.'&sort_by='.$sort_by.'&direct='.$direct;
        $ret = httpCurl($url, json_encode($exclude_users), $method);
		log_message('info',"get data from ums url is->".$url." result is->".var_export($ret, true));
		if($ret['code'] == 0 && isset($ret['http_info']['http_body'])){
            return json_decode($ret['http_info']['http_body'], true);
        }else{
            return false;
        }
	}
	
	/**
	 * 设置用户产品
	 * @param int $site_id
	 * @param int $user_id
	 * @param int $uc_product_id  //用户产品编号，uc为20
	 * @param int $user_status	  //用户状态  ，82为开通
	 * @return mixed
	 */
	public function setUserProduct($site_id, $user_id, $uc_product_id, $user_status){
		$method = 'POST';
		$url  = $this->apiurl.'rs/users/setUserProduct?productId=%s&userStatus=%s&sitesId=%s&userId=%s';
		$url  = sprintf($url, $uc_product_id, $user_status, $site_id, $user_id);
		$ret = httpCurl($url, '', $method);
		log_message('info',"set user product to ums url is->".$url." result is->".var_export($ret, true));
		return $ret['code'] == 0;
	}
	
	/**
	 * 直接修改用户密码
	 * @param unknown $user_id
	 * @param unknown $password
	 * @return boolean
	 */
	public function resetUserPassword($user_id, $password){
		$method = 'PUT';
		$url  = $this->apiurl.'rs/users/id/'.$user_id.'/password';
		$param = $password;
		$ret = httpCurl($url, $param, $method);
		log_message('info',"reset password to ums url is->".$url." param is ".var_export($param, true)." result is->".var_export($ret, true));
		return $ret['code'] == 0;
	}
	
	/**
	 * 修改密码校验老密码
	 * @param unknown $user_id
	 * @param unknown $oldpwd
	 * @param unknown $new_pwd
	 */
	public function resetUserPasswordValidOldPwd($user_id, $oldpwd, $newpwd){
		$method = 'PUT';
		$header = array(POST_HEAD);
		$url  = $this->apiurl.'rs/users/id/'.$user_id.'/change_password'; 
		$param = http_build_query(array('oldPassword' => $oldpwd, 'newPassword' => $newpwd)); //oldPassword=111111&newPassword=222222
		$ret = httpCurl($url, $param, $method, $header);
		log_message('info',"reset password to ums url is->".$url." param is ".var_export($param, true)." result is->".var_export($ret, true));
		return $ret['code'] == 0 ? $ret['http_info']['http_body'] : false;
	}
	
	/**
	 * 通过loginName判断用户在数据库中是否存在
	 * @param array $login_name
	 * @param array $others_info
	 * @return boolean
	 * 
	 * array(
	 * 		array('loginName'=>"aa@bb.com"),
	 * 		array('loginName'=>"bb@qq.com"),
	 * 		...
	 * )
	 * 
	 */
	public function searchUserByLoginname($login_names, $others_info=array()){
		$method = 'POST';
		$url  = $this->apiurl.'rs/users/checkUsers';
		$param = $login_names;
		//$param = array_combine(array_fill(0, count($login_names), 'loginName'),$login_names);
		$ret = httpCurl($url, json_encode($param), $method);
		log_message('info',"check user to ums url is->{$url} param is-->".var_export($param, true)." result is->".var_export($ret, true));
		return $ret['code'] == 0 ? $ret['http_info']['http_body'] : false;
	}
	
	/**
	 * 创建用户
	 * @param string $login_name
	 * @param array  $others_info
	 */
	public function createUser($login_name, $others_info=array()){
		$method = 'POST';
		$url  = $this->apiurl.'rs/users';
		$others_info['loginName'] = $login_name;
		log_message('info', var_export($others_info, true));
		$ret = httpCurl($url, json_encode($others_info), $method);
		log_message('info',"create user to ums url is->".$url." result is->".var_export($ret, true));
		return $ret['code'] == 0 ? $ret['http_info']['http_body'] : false;
	}
	
	/**
	 * 修改用户
	 * @param string $login_name
	 * @param array  $others_info
	 */
	public function updateUser($login_name, $others_info=array()){
		$method = 'POST';
		$url  = $this->apiurl.'rs/users'; // /rs/users/updateUser或/rs/users
		//$param = array_push($others_info, array('loginName'=>$login_name));
		$others_info['loginName'] = $login_name;
		//var_dump($others_info);
		$ret = httpCurl($url, json_encode($others_info), $method);
		log_message('info',"update user to ums url is->".$url.", param is->". json_encode($others_info) ." result is->".var_export($ret, true));
		return $ret['code'] == 0 ? $ret['http_info']['http_body'] : false;
	}

	/**
	 * 批量修改用户和组织
	 *
	*/
	public function updateUserAndOrganization($customer_code,$info){
		$method = 'POST';
		$url  = $this->apiurl.'rs/users/updateUsers/customerCode/'.$customer_code;
		$ret = httpCurl($url, json_encode($info), $method);
		
		log_message('info',"batch update user and organization to ums url is->".$url.", param is->". json_encode($info) ." result is->".var_export($ret, true));
		
		if($ret['code'] == 0 && isset($ret['http_info']['http_body'])){
            return json_decode($ret['http_info']['http_body'], true);
        }else{
            return false;
        }
	}

	/**
	 * 修改用户
	 * @param string $login_name
	 * @param array  $others_info
	 */
	public function updateUserInfo($user_info=array()){
		$method = 'POST';
		$url  = $this->apiurl.'rs/users/updateUser'; // /rs/users/updateUser或/rs/users
		$ret = httpCurl($url, json_encode($user_info), $method);
		log_message('info',"update user to ums url is->".$url.", param is->". json_encode($user_info) ." result is->".var_export($ret, true));
		return $ret['code'] == 0 ? $ret['http_info']['http_body'] : false;
	}

	/**
	 * 查询邮箱和手机是否认证过
	*/
	public function checkUserVerified($email, $mobileNumber){
		$method = 'POST';
		$url  = $this->apiurl.'rs/users/checkVerified';
		$param = array(
			'email'	=> $email,
			'mobile'=> array(
				'phoneNumber'=>$mobileNumber,
				'phoneTypeName'=>3,//电话类型
			)
		);
		$ret = httpCurl($url, json_encode($param), $method);
		log_message('info',"check user verified from ums url is->".$url.", param is->". json_encode($param) ." result is->".var_export($ret, true));

		if($ret['code'] == 0 && isset($ret['http_info']['http_body'])){
            return json_decode($ret['http_info']['http_body'], true);
        }else{
            return false;
        }
	}

	/**
	 * 认证手机和邮箱
	 * 
	 * 
	*/
	public function verifyPhoneAndMail($user_id, $email='' ,$phone=''){
		$method = 'POST';
		$url  = $this->apiurl.'rs/users/verifyPhoneAndMail';
		$param = array(
			'id'=>$user_id,
			'email'	=> $email,
			'mobile'=> array(
				'phoneNumber'=>$mobileNumber,
				'phoneTypeName'=>3,//电话类型
			),
			'isForceUpdate'=>false,
		);
		$ret = httpCurl($url, json_encode($param), $method);
		log_message('info',"verified  user email and phone to ums url is->".$url.", param is->". json_encode($param) ." result is->".var_export($ret, true));

		if($ret['code'] == 0 && isset($ret['http_info']['http_body'])){
            return json_decode($ret['http_info']['http_body'], true);
        }else{
            return false;
        }
	}
	
    //=====================LDAP接口==================
     /**
     * 创建ldap配置同时建立ldap站点关联关系
     * @param int   $site_id 站点id
     * @param array $server_info   ldap服务器配置信息
     * @param array $property_info 标签属性对应关系
     * @param array $others        其他
     * @return mix
     */
    public function createSiteLdap($site_id, $server_info, $property_info ,$others){
		$method = 'POST';	
        $url = sprintf('%s%s', $this->ldapurl, sprintf('rs/sites/%d/ldap', $site_id));
		//ums可选标签如果没有，就不能创建，这里做一下修补
		if(isset($property_info['costcenterproperty'])) {unset($property_info['costcenterproperty']);}
		if(isset($property_info['positionproperty'])) {unset($property_info['positionproperty']);}
		if(isset($property_info['accountproperty'])) {unset($property_info['accountproperty']);}
		if(isset($property_info['locationproperty'])) {unset($property_info['locationproperty']);}
		
		if(!isset($property_info['emailproperty'])){ $property_info['emailproperty']='';}
		if(!isset($property_info['phoneproperty'])){ $property_info['phoneproperty']='';}
		if(!isset($property_info['uidproperty'])){ $property_info['uidproperty']='';}
		
		$param = array_merge($server_info, $property_info, $others);
		$ret = httpCurl( $url, json_encode($param), $method, array('Content-Type: application/json') );
		log_message('info',"get data from ums url is->".$url." param is->".var_export($param, true)." result is->".var_export($ret, true));
		if($ret['code']== 0 && isset($ret['http_info']['http_body'])){
			return $ret['http_info']['http_body'];//返回ldap id
		}else{
			return false;
		}
    }
    
    
    
    /**
     * 创建ldap配置
     * @param array $ldap_param   ldap服务器配置信息
     * @return mix
     */
    public function createLdap($ldap_param){
		$method = 'POST';	
        $url = sprintf('%s%s', $this->ldapurl, 'rs/ldap');
		$ret = httpCurl( $url, json_encode($ldap_param), $method, array('Content-Type: application/json') );
		log_message('info',"get data from ums url is->".$url." param is->".var_export($ldap_param, true)." result is->".var_export($ret, true));
		if($ret['code']== 0 && isset($ret['http_info']['http_body'])){
			return $ret['http_info']['http_body'];//返回ldap id
		}else{
			return false;
		}
    }
    
    /**
     * 将站点和ldap进行关联
     * 
     * @param int $site_id   站点id
     * @param int $ldap_id   ldap id
     * return boolean
     */
    public function relateSiteLdap($site_id, $ldap_id){
		$method = 'POST';	
        $url = sprintf( '%s%s', $this->ldapurl, sprintf('rs/sites/%d/ldap/%d/add', $site_id, $ldap_id) );
		$param = array();
        $ret = httpCurl($url, json_encode($param), $method);
		log_message('info',"get data from ums url is->".$url." param is->".var_export($param, true)." result is->".var_export($ret, true));
		if( $ret['code']== 0 && isset($ret['http_info']['http_body']) ){
			return $ret['http_info']['http_body'];
		}else{
			return false;
		}
    }
    
    /**
     * 修改ldap配置或者禁用ldap同步 
     * @param int      $ldap_id       ldap id
     * @param array    $server_info   ldap服务器配置信息
     * @param array    $property_info 标签属性对应关系
     * @param array    $others        其他
     * return boolean
     */
    public function editLdap($ldap_param){
		$method = 'PUT';
		$url	= sprintf('%s%s', $this->ldapurl, 'rs/ldap');
		$ret	= httpCurl($url, json_encode($ldap_param), $method, array('Content-Type: application/json'));
		log_message('info',"get data from ums url is->".$url." param is->".var_export($ldap_param, true)." result is->".var_export($ret, true));
		if( $ret['code']== 0 && isset($ret['http_info']['http_body']) ){
			$ret_data =  json_decode($ret['http_info']['http_body'], true);
			if(isset($ret_data) && $ret_data > 0){
				return true; 
			}else{
				$msg = isset($ret_data['message']) ? $ret_data['message'] : '';
				log_message('error', 'edit ldap error ,error message->'.$msg.'request url->'.$url.' result->'.var_export($ret, true));
				return false;
			}
		}
		return false;
    }
    
    /**
     * 查询指定站点的指定ldap配置信息
     * @param int $ldap_id    ldap id
     * @return mix	返回查询到ldap的列表信息
     */
    public function getLdap($ldap_id){
        $method = 'GET';
        $url = sprintf('%s%s', $this->ldapurl, sprintf('rs/ldap/%d',$ldap_id));
        $ret = httpCurl($url, json_encode(array()), $method);
		log_message('info',"get data from ums url is->".$url." result is->".var_export($ret, true));
		if($ret['code']== 0 && isset($ret['data'])){
            return json_decode($ret['data'] ,true);
        }else{
            return false;
        }
    }
    
    /**
     * 查询站点的ldap列表
     * @param int $site_id 站点id
     * @return mix
     */
    public function getLdapList($site_id){
        $method = 'GET';
        $url = sprintf('%s%s', $this->ldapurl, sprintf('rs/ldap/siteId/%d', $site_id));
		$ret = httpCurl($url, json_encode(array()), $method);
		log_message('info',"get data from ums url is->".$url." result is->".var_export($ret, true));
		if($ret['code']== 0 && isset($ret['data'])){
            return json_decode($ret['data'] ,true);
        }else{
            return false;
        }
    }
    
    /**
     * 查询ldap组织树
     * 
     * @param array   $server_info    ldap服务器配置信息
     * @return string
     */
    public function getLdapOrgTree($server_info){
        $method = 'POST';
        $url = sprintf('%s%s', $this->ldapurl, 'rs/sites/ldap/organization');
        $ret = httpCurl($url, json_encode($server_info), $method);
		log_message('info',"get data from ums url is->".$url." param is->".var_export($server_info, true)." result is->".var_export($ret, true));
		if( $ret['code']== 0 && isset($ret['http_info']['http_body']) ){
			return json_decode($ret['http_info']['http_body'], true);
		}else{
			log_message('error', 'get organization tree from UMS failed，the request url is: '.$url.'. the request params is: '.  var_export($server_info, true));
			return false;
		}
    }
    
    /**
     * 查询类
     * @param array   $server_info    ldap服务器配置信息
     * @return string
     */
    public function getLdapClass($server_info){
        $method = 'POST';
        $url = sprintf('%s%s', $this->ldapurl, 'rs/sites/ldap/classes');
        $ret = httpCurl($url, json_encode($server_info), $method);
        log_message('info',"get data from ums url is->".$url." param is->".var_export($server_info, true)." result is->".var_export($ret, true));
		if( $ret['code']== 0 && isset($ret['http_info']['http_body']) ){
			return json_decode($ret['http_info']['http_body'], true);
		}else{
			return false;
		}
    }
    
    /**
     * 查询类的属性
     * 
     * @param string    $hostname           主机ip地址
     * @param int       $port               端口号
     * @param string    $basedn             根域
     * @param string    $admindn            管理员DN
     * @param string    $adminpassword      管理员密码
     * @param int       $servertype         服务器ldap类型 1-MS AD 2-OpenDirectory 3-Lotus Dimino 4-其他
     * @param string    $objectclasses      选择的objectclasses
     * @return mix
     */
    public function getClassAttributes($server_info){
		$method = 'POST';
        $url = sprintf('%s%s', $this->ldapurl,'rs/sites/ldap/classes/attributes');
		$ret = httpCurl($url, json_encode($server_info), $method);
		log_message('info',"get data from ums url is->".$url." param is->".var_export($server_info, true)." result is->".var_export($ret, true));
		if( $ret['code']== 0 && isset($ret['http_info']['http_body']) ){
			return json_decode($ret['http_info']['http_body'], true);
		}else{
			return false;
		}
    }
    
   

  
    
    /**
     * 删除ldap配置
     * @param    int       $site_id  站点id
     * @param    int       $ldap_id 要删除的ldap
     * @return boolean
     */
    public function deleteLdap($site_id, $ldap_id){
		$method = 'DELETE';
        $url = sprintf('%s%s', $this->ldapurl, sprintf('rs/sites/%d/ldap/%d', $site_id, $ldap_id));
		$param = array();
        $ret = httpCurl($url, json_encode($param), $method);
		log_message('info',"get data from ums url is->".$url." param is->".var_export($param, true)." result is->".var_export($ret, true));
		if( $ret['code']== 0 && isset($ret['http_info']['http_body']) ){
            $ret_data =  json_decode($ret['http_info']['http_body'], true);
			if(isset($ret_data['flag']) && $ret_data['flag']==0){
				return true; 
			}else{
				$msg = isset($ret_data['message']) ? $ret_data['message'] : '';
				log_message('error', 'delete site ldap error，error message: '.$msg);
				return false;
			}
    	}
		return false;
    }
    
    /**
     * 批量删除ldap配置
     * @param    int       $site_id  站点id
     * @param    array     $ldap_ids 要删除的ldap
     * @return boolean
     */
    public function deleteLdaps($ldap_ids){
		$method = 'POST';
        $url = sprintf('%s%s', $this->ldapurl, 'rs/sites/ldaps');
        $ret = httpCurl($url, json_encode($ldap_ids), $method);
        log_message('info',"get data from ums url is->".$url." param is->".var_export($ldap_ids, true)." result is->".var_export($ret, true));
		if( $ret['code']== 0 && isset($ret['http_info']['http_body']) ){
            $ret_data =  json_decode($ret['http_info']['http_body'], true);
			if(isset($ret_data['flag']) && $ret_data['flag']==0){
				return true; 
			}else{
				$msg = isset($ret_data['message']) ? $ret_data['message'] : '';
				log_message('error', 'delete site ldap error，error message: '.$msg);
				return false;
			}
    	}
        return false;
    }
	
}


