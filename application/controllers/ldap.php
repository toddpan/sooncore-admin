<?php
use Ice\Object;
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @class Ldap
 * @brief LDAP Controller，主要负责对LDAP的管理，包含列表、导入、修改、关闭等。
 * @file Ldap.php
 * @author zouyan <yan.zou@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
class Ldap extends Admin_Controller {
	private $_server_info = array();	//LDAP服务器参数
	private $_servertype_arr;
	private $_protocol_arr;
	private $_tags_arr;					//查询得到可选标签的值
// 	private $_ldap_id;
    /**
     * @brief 构造函数：
     *
     */	
    public function __construct() {
        parent::__construct();
        $this->load->helper('my_publicfun');
        $this->load->helper('language');
        $this->lang->load('ldap', 'chinese');
        $this->load->model('uc_user_tags_model','tag');
        $this->load->model('ldap_model','ldap');
        $this->load->library('UmsLib','','ums');
        $this->config->load('ldap_config', TRUE);
        $this->initParams();
    }
    
    /**
     * 初始化参数，对于页面上传过来的参数进行封装
     */
    public function initParams(){
    	$this->_server_info		= $this->input->get_post('server_info' ,true);
    	$this->_servertype_arr	= $this->config->item('_servertype_arr','ldap_config');
    	$this->_protocol_arr	= $this->config->item('_protocol_arr','ldap_config');
    	$this->_tags_arr		= $this->tag->getOptionalTags($this->p_site_id);
    }
	
	/**
     * 显示LDAP配置页面
     */
    public function showLdapPage() {	// 
//         获得参数当前ldap编号,如果没有值,则认为是新加页面
        $ldap_id = intval($this->input->get_post('ldap_id', true));
		if($ldap_id>0){		//$ldap_id不为0表示当前页面属于编辑页面
			$ret = $this->ums->getLdap($ldap_id);	//查找当前ldap_id的ldap组织结构
			if($ret === false){
				log_message('error',"get LDAP server parameter from ums error,the request ldap_id is $ldap_id");
				return ;
			}
			//设置创建LDAP的参数
			$server_info = array(
				'serverType'	=> trim($ret['serverType']),
				'protocol'		=> trim($ret['protocol']),
				'hostname'		=> trim($ret['hostname']),
				'port'			=> trim($ret['port']),
				'admindn' 		=> trim($ret['username']),
				'password' 		=> trim($ret['password']),
				'basedn' 		=> trim($ret['basedn']),
				'orgObjectclasses' 	=> trim($ret['ldapOrgMapping']['objectClass']),
				'orgidproperty' 	=> trim($ret['ldapOrgMapping']['idAttribute']),
				'orgNameProperty' => trim($ret['ldapOrgMapping']['nameAttribute']),
			);
			
			//例外规则
			$rule = $this->ldap->getRuleByLdapId($ldap_id);
			
			$this->assign('ldap_id', $ldap_id);
			$this->assign('rule', $rule);
			$this->assign('select_step1', $server_info);
		}
		
		$this->load->model('uc_user_tags_model', 'tag');
		$tags 		= $this->_tags_arr;	//这里查找选中的可选标签元素，组成数组
		$key_arr	= $this->config->item('_tags_arr','ldap_config');
		$ret_tags   = array();
		if(is_array($tags) && count($tags)>0){	//这里是要组成一个带有下标的可选标签数组
			foreach($tags as $tag){
				if($j = array_search($tag, $key_arr)){
					$ret_tags[$j] = $tag;
				}
			}
		}
		$arr		= $this->ldapInternational();
		$servertype = $arr['servertype'];
		$protocol 	= $arr['protocol'];
		
		$domain		= '@'.$this->p_stie_domain;
		
		$base_url = dirname(BASEPATH);	//根路径
		$this->assign('base_url', $base_url);
		$this->assign('servertype', $servertype);	//第一步中：服务器类型选项
		$this->assign('authtype_name', $protocol);	//第一步中：服务器连接方式
		$this->assign('ldap_relative', $ret_tags);	//第四步中：可选标签的数组变量
		$this->assign('site_domain', $domain);		//第五步中：域名设置
		
        $this->display('ldap/ldap1.tpl');
    }
	
    
	/**
     * 显示LDAP上传成功后的框架页面
     */
    public function ldaplayout(){
        $this->load->view('ldap/ldaplayout.php');
    }
	
	
	/**
	 * 
	 * 获取ldap组织
	 * 
	 */
	public function getLdapOrganization(){
		//获取参数
		$server_info = $this->_server_info;
		list($flag, $msg) = $this->_checkLdapServerInfo($server_info);
		if(!$flag){
			form_json_msg(COMMON_PARAM_ERROR, '', $msg);
		}
		//ldap获取组织
		$ret = $this->ums->getLdapOrgTree($server_info);
		if($ret===false){
			form_json_msg(GET_ORGANIZATION_ERROR, '', lang('get_organization_error'));
		}
		//重组数据
		$ret_data = $this->getTree($server_info, $ret);
		//返回数据
		form_json_msg(COMMON_SUCCESS, '', lang('success'), $ret_data);
	}
	
	/**
	 * 将树形数组组织成页面需要的树形关系数组
	 * @param array  $server_info	服务器信息
	 * @param array	 $tree_arr		组织树的原始数据
	 */
	public function getTree($server_info, $tree_arr){
		$ret_data = array();
		if(is_array($tree_arr) && count($tree_arr)>0){
			//组织树的根节点是basedn属性的值，由于返回的组织树中根节点值大小写不确定，所以需要从返回值$ret中截取出根节点的值
			$base_start	= stripos($tree_arr[0]['dn'], $server_info['basedn']);
			$strDC		= substr($tree_arr[0]['dn'], $base_start);
			if(!$strDC){
				form_json_msg(ORGANIZATION_TREE_ERROR, '', lang('organization_tree_error'));
			}
			$ret_data[] = array('id'=>$strDC, 'pId'=>'', 'name'=>$strDC.'['.$server_info['hostname'].']', 'nocheck'=>false);	//DC根节点
			foreach( $tree_arr as $v){
				if($v == $strDC){
					continue;
				}
				$tmp = array();
				$segs = explode(',', $v['dn']);
				$tmp['id']		= $v['dn'];	//节点的全称路径，格式如：OU=Domain Controllers,DC=haier,DC=com
				$name			= array_shift($segs);	//节点名称，格式如：OU=Domain Controllers
				$tmp['name']	= empty($v['name']) ? $name : $v['name'];
				$tmp['pId']     = implode(',', $segs);	//节点的父节点，格式如：DC=haier,DC=com
				$tmp['nocheck'] = false;	//表示节点的状态，true表示选中，false表示没有选中
				$ret_data[]     = $tmp;
			}
		}
		return $ret_data;
	}
	
	/**
	 * 获取员工标签类
	 */
	public function getLdapClass(){
		//获取server info
		$server_info = $this->_server_info;
		//获取员工类
		$ret = $this->ums->getLdapClass($server_info);
		if($ret===false){
			form_json_msg(GET_LABEL_CLASS_ERROR, '', lang('get_label_class_error'));
		}
		//返回数据
		form_json_msg(COMMON_SUCCESS, '', lang('success'), $ret);
	}
	
	/**
	 * 获取标签属性
	 */
	public function getLdapAttribute(){
		//获取服务器配置信息和标签
		$server_info   = $this->_server_info;
		$classes       = $this->input->get_post('classes', true);
		//check
		if(is_null($classes) || !is_array($classes) || count($classes)<=0){
			form_json_msg(COMMON_PARAM_ERROR, '', lang('param_error'));
		}
		list($flag, $msg) = $this->_checkLdapServerInfo($server_info);
		if(!$flag){
			form_json_msg(COMMON_PARAM_ERROR, '', lang('param_error'));
		}
		
		$server_info['objectclasses'] = implode(';', $classes);
		//get attributes
		$ret = $this->ums->getClassAttributes($server_info);
		//查询出来的结果格式如：$ret=array('classname.attr1', 'classname.attr2', 'classname1.attr1', ...);
		//需要处理掉成只保留属性的数组
		foreach ($ret as $k => $val){
			$start = strpos($val, '.') + 1;
			$ret[$k] = substr($val, $start);	//截取点号后面的属性值，重新赋值给返回数组
		}

		if($ret===false){
			form_json_msg(GET_ATTRIBUTE_ERROR, '', lang('get_attribute_error'));
		}
		//返回结果
		form_json_msg(COMMON_SUCCESS, '', lang('success'), $ret);
	}
	
	/**
	 * 检查ldap所有的配置信息是否合法
	 */
	public function checkAllLdapParams(){
		//获取参数
		$server_info		= $this->_server_info;  //服务器参数
		$org_info			= $this->input->get_post('org_info', true);     //要同步的部门
		$classes			= $this->input->get_post('classes', true);//选中的属性，即同步的员工信息
		$property_info		= $this->input->get_post('property_info', true);//选中的属性，即同步的员工信息
		$filter_rule		= $this->input->get_post('filter_rule', true);  //过滤规则
		$email_value		= $this->input->get_post('email_value', true);  //使用邮箱做为sooncore平台标签时，对应的标签
		$is_auto_del		= $this->input->get_post('is_auto_del', true);  //同步后，如果在 LDAP 找不到用户信息，是否立即停用并删除 0-否 1-是
// 		$objectclasses		= $this->input->get_post('objectclasses', true);    //组织objectclasses
// 		$idAttribute		= $this->input->get_post('idAttribute', true);    //组织id
// 		$nameAttribute		= $this->input->get_post('nameAttribute', true);    //组织名称
		//检查参数
		$this->_checkAllLdapParams($server_info, $org_info, $classes, $property_info, $filter_rule, $email_value, $is_auto_del);	
		form_json_msg(COMMON_SUCCESS, '', lang('success'));
	}
	/**
	 * 添加ldap
	 */
	public function createLdap(){
		//获取参数
		$server_info		= $this->_server_info;  //服务器参数
		$org_info			= $this->input->get_post('org_info', true);     //要同步的部门
		$property_info		= $this->input->get_post('property_info', true);//选中的属性，即同步的员工信息
		$classes			= $this->input->get_post('classes', true);
		$filter_rule		= $this->input->get_post('filter_rule', true);  //过滤规则
		$email_next			= $this->input->get_post('email_next', true);    //使用统一的标签做为账号前缀时，对应的标签
		$email_value		= $this->input->get_post('email_value', true);  //使用邮箱做为sooncore平台标签时，对应的标签
		$is_auto_del		= $this->input->get_post('is_auto_del', true);  //同步后，如果在 LDAP 找不到用户信息，是否立即停用并删除 0-否 1-是
		$ldap_name			= $this->input->get_post('ldap_name', true);    //ldap名称
		$objectclasses		= empty($server_info['orgObjectclasses']) ? 'organizationalUnit' : $server_info['orgObjectclasses'];    //组织objectclasses
		$idAttribute		= $server_info['orgidproperty'];    //组织id
		$nameAttribute		= $server_info['orgNameProperty'];    //组织名称
		if(!is_string($ldap_name) || $ldap_name == ''){
			form_json_msg(LDAP_NAME_NOT_NULL, '', lang('ldap_name_not_null'));
		}
		//检查参数
		$this->_checkAllLdapParams($server_info, $org_info, $classes, $property_info, $filter_rule, $email_value, $is_auto_del);	
		//创建LDAP组织映射关系参数
		$ldap_org_mapping = array(
			'objectClass'		=> $objectclasses,
			'idAttribute'		=> $idAttribute,
			'nameAttribute'		=> $nameAttribute,
			'organizations'		=> $org_info
		);
		//创建LDAP用户映射关系参数
		$otherAttributes = array();
		$tags_arr = $this->config->item('_tags_arr', 'ldap_config');
		foreach ($tags_arr as $k => $tag){	//如果用户选择了除邮箱以外的其他可选标签，则需要拼接成字符串赋值给‘otherAttributes’属性
			if($k != 'emailAttribute' && isset($property_info[$k])){
				$otherAttributes[] = $tag . '=' .$property_info[$k];
				unset($property_info[$k]);
			}
		}
		$ldap_user = array(
			'objectClass'			=> implode(';', $classes),
			'loginNameAttribute'	=> $email_value,
			'otherAttributes'		=> implode(';', $otherAttributes),
			'customEmailDomain'		=> ''	// FIXME 这个值表示邮箱的域，目前可以给成空值，以后有可能有变化
		);
		!empty($email_next) ? $ldap_user['customLoginNameSuffix'] = $email_next : ''; 
		$ldap_user_mapping	= array_merge($property_info, $ldap_user);
		//创建LDAP站点映射关系参数
		$ldap_site_mapping = array(
			'siteId'					=> $this->p_site_id,
			'umsOrgId'					=> $this->p_org_id,
			'enableUserSync'			=> 1,	//是否开通用户同步 0否，1是，以下几个数字同样
			'enableOrgSync'				=> 1,	//是否开通组织同步
			'enableActivation'			=> 1,   //是否开通自动开通
			'enableDeactivation'		=> intval($is_auto_del),//是否开通账号自动停用
			'enableDeactivationFilter'	=> 1,   //是否使用停用规则停用账号
			'enableUpdate'				=> 1    //是否使用同步更新
		);
		//创建LDAP查询映射关系参数
		$org_arr	= explode(';', substr($org_info, 0, strlen($org_info) - 1));
		//由此开始拼接验证规则的的代码
		$class_count = count($classes); 
		foreach ($classes as $k => $val){
			$obj_filter	.= "(objectClass={$val})";
		}
		if(!empty($filter_rule)){	//用户填写了过滤规则
			$filter_arr				= explode(';', $filter_rule);
			$ldap_search_mapping	= array();
			$filter0 				= "(|";
			$filter1 				= "(&";
			foreach ($filter_arr as $f => $filter){
				$filter0 .= "($filter)";
				$filter1 .= "(!($filter))";
			}
			$cnt = 2;
			if($class_count == 1){	//用户只选择了一个类
				$rule0 = '(&'.$obj_filter.$filter0.'))';	//拼装验证过滤规则字符串1，如：(&(objectClass=user)(|(CN=fengjuan35)(CN=fengjuan36)))
				$rule1 = '(&'.$obj_filter.$filter1.'))';	//拼装验证过滤规则字符串2，如：(&(objectClass=user)(&(!(CN=fengjuan35))(!(CN=fengjuan35))))
			}elseif($class_count > 1){	//选择了多个类
				$rule0 = '(&(|'.$obj_filter.')'.$filter0.'))';	//拼装验证过滤规则字符串1，如：(&(|(objectClass=user)(objectClass=securityPrincipal))(|(CN=fengjuan35)(CN=fengjuan36)))
				$rule1 = '(&(|'.$obj_filter.')'.$filter1.'))';	//拼装验证过滤规则字符串2，如：(&(|(objectClass=user)(objectClass=securityPrincipal))(&(!(CN=fengjuan35))(!(CN=fengjuan35))))
			}
		}else{	//用户没有填写过滤规则
			if($class_count == 1){	//用户只选择了一个类
				$rule0 = $rule1 = 'objectClass='.$classes[0];	//构造字符串，如：objectClass=xxxx
			}elseif($class_count > 1){	//选择了多个类
				$rule0 = $rule1 = '(|' . $obj_filter.')';	//构造字符串，如：(|(objectClass=xxxx)(objectClass=ss))
			}
			$cnt = 1;
		}
		$index = 0;
		for ($i = 0; $i < $cnt; $i++){
			$rule = 'rule'.$i;	//拼接'rule0'和'rule1'字符串
			$enableAutoSync = empty($filter_rule) ? 1 : $i;	//如果没有过滤规则 ，则同步的组织开通属性为1，否则每同步的组织会有两种属性
			foreach ($org_arr as $org_key => $org_val){
				$ldap_search_mapping[$index++] = array(
					'searchBase'	 => $org_val,		//表示查询位置，默认是basedn
					'searchFilter'	 => $$rule,
					'templateId'	 => $this->p_stie_domain,
					'accountId'		 => $this->p_account_id,
					'contractId'	 => $this->p_contract_id,
					'bossType'		 => 2,	//1表示MIS，2表示QSBOSS
					'searchScope'	 => 1,	//查同级0，查下面一级1，查所有子节点2
					'action'		 => 1,	//1创建，2删除
					'enableAutoSync' => $enableAutoSync	//1开通并同步，0开通不同步
				);
			}
		}
		
		//设置创建LDAP的参数
		$servertype_arr = $this->_servertype_arr;
		$protocol_arr   = $this->_protocol_arr;
		$ldap_param = array(
			'protocol'				=> $protocol_arr[$server_info['authtype']],
			'confName'				=> $ldap_name,
			'serverType'			=> $servertype_arr[$server_info['servertype']],
			'customerCode'			=> $this->p_customer_code,
			'hostname'				=> $server_info['hostname'],
			'authenticationMethod'	=> 'SIMPLE',
			'port' 					=> $server_info['port'],
			'basedn'				=> $server_info['basedn'],
			'username'				=> $server_info['admindn'],
			'password'				=> $server_info['adminpassword'],
			'ldapOrgMapping'		=> $ldap_org_mapping,
			'ldapUserMapping'		=> $ldap_user_mapping,
			'siteLdapConfig'		=> $ldap_site_mapping,
			'ldapSearch'			=> $ldap_search_mapping
		);
		log_message('info', __FUNCTION__.' customerCode->'.$this->p_customer_code.' site_id->'.$this->p_site_id.' input params->'.var_export($ldap_param, true));
		$ret = $this->ums->createLdap($ldap_param);
		if($ret===false){
			form_json_msg(CREATE_LDAP_ERROR, '', lang('create_ldap_fail'));
		}
		//如果创建成功，需要在本地保存一份用户设置的过滤规则
		if(isset($filter_arr) && $ret){	//如果用户填写了过滤规则并且创建成功
			foreach ($filter_arr as $f){
				$flag = $this->ldap->insertLdapConfig(array('ldap_id' => $ret, 'rule' => $f));
				if($flag == false){
					log_message('error',"insert info into uc_ldap_config error,the request ldap_id is $ret");
				}
			}
		}
		log_message('info', __FUNCTION__.' customerCode->'.$this->p_customer_code.' site_id->'.$this->p_site_id.' input params->'.var_export($ldap_param, true));
		form_json_msg(COMMON_SUCCESS, '', lang('success'));
	}
	
	/**
	 * 修改ldap配置
	 */
	public function updateLdap(){
		//根据ldap的id，获取当前id的ldap信息
		//查找当前id的ldap信息
		$ldap_id = $this->input->get_post('ldap_id', true);     //要同步的部门
		$res = $this->ums->getLdap($ldap_id);
		//获取参数
		$server_info		= $this->_server_info;  //服务器参数
		$org_info			= $this->input->get_post('org_info', true);     //要同步的部门
		$property_info		= $this->input->get_post('property_info', true);//选中的属性，即同步的员工信息
		$classes			= $this->input->get_post('classes', true);
		$filter_rule		= $this->input->get_post('filter_rule', true);  //过滤规则
		$email_next			= $this->input->get_post('email_next', true);    //使用统一的标签做为账号前缀时，对应的标签
		$email_value		= $this->input->get_post('email_value', true);  //使用邮箱做为sooncore平台标签时，对应的标签
		$is_auto_del		= $this->input->get_post('is_auto_del', true);  //同步后，如果在 LDAP 找不到用户信息，是否立即停用并删除 0-否 1-是
		$ldap_name			= $this->input->get_post('ldap_name', true);    //ldap名称
		$objectclasses		= empty($server_info['orgObjectclasses']) ? 'organizationalUnit' : $server_info['orgObjectclasses'];    //组织objectclasses
		$idAttribute		= $server_info['orgidproperty'];    //组织id
		$nameAttribute		= $server_info['orgNameProperty'];    //组织名称
		if(!is_string($ldap_name) || $ldap_name == ''){
			form_json_msg(LDAP_NAME_NOT_NULL, '', lang('ldap_name_not_null'));
		}
		//检查参数
		$this->_checkAllLdapParams($server_info, $org_info, $classes, $property_info, $filter_rule, $email_value, $is_auto_del);	
		//创建LDAP组织映射关系参数
		$ldap_org_mapping = array(
			'ldapConfId'		=> $res['ldapOrgMapping']['ldapConfId'],
			'objectClass'		=> $objectclasses,
			'idAttribute'		=> $idAttribute,
			'nameAttribute'		=> $nameAttribute,
			'organizations'		=> $org_info
		);
		//创建LDAP用户映射关系参数
		$otherAttributes = array();
		$tags_arr = $this->config->item('_tags_arr', 'ldap_config');
		foreach ($tags_arr as $k => $tag){	//如果用户选择了除邮箱以外的其他可选标签，则需要拼接成字符串赋值给‘otherAttributes’属性
			if($k != 'emailAttribute' && isset($property_info[$k])){
				$otherAttributes[] = $tag . '=' .$property_info[$k];
				unset($property_info[$k]);
			}
		}
		$ldap_user = array(
			'ldapConfId'			=> $res['ldapUserMapping']['ldapConfId'],
			'objectClass'			=> implode(';', $classes),
			'loginNameAttribute'	=> $email_value,
			'otherAttributes'		=> implode(';', $otherAttributes),
			'customEmailDomain'		=> ''	// FIXME 这个值表示邮箱的域，目前可以给成空值，以后有可能有变化
		);
		!empty($email_next) ? $ldap_user['customLoginNameSuffix'] = $email_next : ''; 
		$ldap_user_mapping	= array_merge($property_info, $ldap_user);
		//创建LDAP站点映射关系参数
		$ldap_site_mapping = array(
			'siteId'					=> $this->p_site_id,
			'umsOrgId'					=> $this->p_org_id,
			'enableUserSync'			=> 1,	//0否，1是，以下几个数字同样
			'enableOrgSync'				=> 1,
			'enableActivation'			=> 1,
			'enableDeactivation'		=> intval($is_auto_del),
			'enableDeactivationFilter'	=> 1,
			'enableUpdate'				=> 1
		);
		//创建LDAP查询映射关系参数
		$org_arr	= explode(';', substr($org_info, 0, strlen($org_info) - 1));
		//由此开始拼接验证规则的的代码
		$class_count = count($classes); 
		foreach ($classes as $k => $val){
			$obj_filter	.= "(objectClass={$val})";
		}
		if(!empty($filter_rule)){	//用户填写了过滤规则
			$filter_arr				= explode(';', $filter_rule);
			$ldap_search_mapping	= array();
			$filter0 				= "(|";
			$filter1 				= "(&";
			foreach ($filter_arr as $f => $filter){
				$filter0 .= "($filter)";
				$filter1 .= "(!($filter))";
			}
			$cnt = 2;
			if($class_count == 1){	//用户只选择了一个类
				$rule0 = '(&'.$obj_filter.$filter0.'))';	//拼装验证过滤规则字符串1，如：(&(objectClass=user)(|(CN=fengjuan35)(CN=fengjuan36)))
				$rule1 = '(&'.$obj_filter.$filter1.'))';	//拼装验证过滤规则字符串2，如：(&(objectClass=user)(&(!(CN=fengjuan35))(!(CN=fengjuan35))))
			}elseif($class_count > 1){	//选择了多个类
				$rule0 = '(&(|'.$obj_filter.')'.$filter0.'))';	//拼装验证过滤规则字符串1，如：(&(|(objectClass=user)(objectClass=securityPrincipal))(|(CN=fengjuan35)(CN=fengjuan36)))
				$rule1 = '(&(|'.$obj_filter.')'.$filter1.'))';	//拼装验证过滤规则字符串2，如：(&(|(objectClass=user)(objectClass=securityPrincipal))(&(!(CN=fengjuan35))(!(CN=fengjuan35))))
			}
		}else{	//用户没有填写过滤规则
			if($class_count == 1){	//用户只选择了一个类
				$rule0 = $rule1 = 'objectClass='.$classes[0];	//构造字符串，如：objectClass=xxxx
			}elseif($class_count > 1){	//选择了多个类
				$rule0 = $rule1 = '(|' . $obj_filter.')';	//构造字符串，如：(|(objectClass=xxxx)(objectClass=ss))
			}
			$cnt = 1;
		}
		$index = 0;
		for ($i = 0; $i < $cnt; $i++){
			$rule = 'rule'.$i;	//拼接'rule0'和'rule1'字符串
			$enableAutoSync = empty($filter_rule) ? 1 : $i;	//如果没有过滤规则 ，则同步的组织开通属性为1，否则每同步的组织会有两种属性
			foreach ($org_arr as $org_key => $org_val){
				$ldap_search_mapping[$index++] = array(
					'searchBase'	 => $org_val,		//表示查询位置，默认是basedn
					'searchFilter'	 => $$rule,
					'templateId'	 => $this->p_stie_domain,
					'accountId'		 => $this->p_account_id,
					'contractId'	 => $this->p_contract_id,
					'bossType'		 => 2,	//1表示MIS，2表示QSBOSS
					'searchScope'	 => 1,	//查同级0，查下面一级1，查所有子节点2
					'action'		 => 1,	//1创建，2删除
					'enableAutoSync' => $enableAutoSync	//1开通并同步，0开通不同步
				);
			}
		}
		
		//设置创建LDAP的参数
		$servertype_arr = $this->_servertype_arr;
		$protocol_arr   = $this->_protocol_arr;
		$ldap_param = array(
			'id'					=> $res['id'],
			'protocol'				=> $protocol_arr[$server_info['authtype']],
			'confName'				=> $ldap_name,
			'serverType'			=> $servertype_arr[$server_info['servertype']],
			'customerCode'			=> $this->p_customer_code,
			'hostname'				=> $server_info['hostname'],
			'authenticationMethod'	=> 'SIMPLE',
			'port' 					=> $server_info['port'],
			'basedn'				=> $server_info['basedn'],
			'username'				=> $server_info['admindn'],
			'password'				=> $server_info['adminpassword'],
			'ldapOrgMapping'		=> $ldap_org_mapping,
			'ldapUserMapping'		=> $ldap_user_mapping,
			'siteLdapConfig'		=> $ldap_site_mapping,
			'ldapSearch'			=> $ldap_search_mapping
		);
		//修改ldap
		$ret = $this->ums->editLdap($ldap_param);
		if(!$ret){
			form_json_msg(UPDATE_LDAP_ERROR, '', lang('update_ldap_fail'));
		}
		//如果修改正确，则需要将修改的过滤规则update
		if(isset($filter_arr) && $ret){	//如果用户填写了过滤规则并且修改成功
			
			//删除过滤规则 本地中删除相对应的规则数据信息
			$this->ldap->deleteLdapConfig($ldap_id);
			foreach ($filter_arr as $f){
				$flag = $this->ldap->insertLdapConfig(array('ldap_id' => $ret, 'rule' => $f));
				if($flag == false){
					log_message('error',"update info uc_ldap_config error,the request ldap_id is ".$ldap_id);
				}
			}
		}
		form_json_msg(COMMON_SUCCESS, '', lang('update_ldap_success'));
	}
	
	/**
	 * 获取ldap列表
	 */
	public function getLdapList(){
		$ret = $this->ums->getLdapList($this->p_site_id);
		if(!$ret){
			form_json_msg(GET_LDAP_TABLE_ERROR, '', lang('get_ldap_table_error'));
		}
		//整理数据
		$ret_data = array();
		if(is_array($ret) && count($ret)>0){
			foreach($ret as $item){
				//是否同步用户与组织
				$flag = $item['siteLdapConfig']['enableOrgSync'] == 1 && $item['siteLdapConfig']['enableUserSync'] == 1;
				$tmp = array();
				$tmp['ldap_id']				= $item['id'];
				$tmp['ldap_name']			= $item['confName'];
				$tmp['create_time']			= empty($item['createTime']) ? '' : date('Y/m/d H:i', round($item['createTime']/1000));
				$tmp['last_update_time']	= empty($item['updateTime']) ? '' : date('Y/m/d H:i', round($item['updateTime']/1000));
				$tmp['last_sync_date']		= empty($item['lastSyncTime']) ? '' : date('Y/m/d H:i', round($item['lastSyncTime']/1000));
				$tmp['is_auto_sync']	    = $flag ? '关闭同步':'开启同步' ;//是否自动同步组织 1-是 0-否
				$ret_data[]                 = $tmp;
			}
		}
		$this->assign('ldap_list', $ret_data);
		$this->display('ldap/ldaplist.tpl');
	}
	
	/**
	 * 获取同步的组织树信息
	 */
	public function getOrgInfo(){
		//获取参数
		$ldap_id = intval($this->input->get_post('ldap_id', true));
		if($ldap_id <= 0){
			form_json_msg(COMMON_PARAM_ERROR, '', lang('param_error'));
			break;
		}
		//从ums获取ldap数据
		$res = $this->ums->getLdap($ldap_id);
		if($res === false){
			form_json_msg(GET_LDAP_DATA_ERROR, '', lang('get_ldap_data_error'));
			break;
		}
		//Ldap服务器配置参数
		$server_info = array();
		
		$server_info['hostname']			=	isset($res['hostname']) ? $res['hostname'] : ''; 
		$server_info['port']				=	isset($res['port']) ? $res['port'] : ''; 
		$server_info['admindn']				=	isset($res['username']) ? $res['username'] : ''; 
		$server_info['basedn']				=	isset($res['basedn']) ? $res['basedn'] : ''; 
		$server_info['adminpassword']		=	isset($res['password']) ? $res['password'] : ''; 
		$server_info['authtype']			=	isset($res['protocol']) ? $res['protocol'] : ''; 
		$server_info['servertype']			=	isset($res['serverType']) ? $res['serverType'] : ''; 
		//导入的组织列表
		$ldap_tree	= array();
		$tree_str   = $res['ldapOrgMapping']['organizations'];	//同步组织的字符串，由于拼接时最后一位有';'字符，因此变数组时应去掉这位
    	//需要将查找到的返回的同步组织数组，组织成树形数组
    	if(!empty($tree_str)){
    		$tree_arr   = explode(';', substr($tree_str, 0, strlen($tree_str) - 1));
    		$base_start	= stripos($tree_arr[0], $server_info['basedn']);
    		$tree_base	= substr($tree_arr[0], $base_start);	//获得根节点
    		foreach ($tree_arr as $k => $val){	//对于数组的每一条记录都需要递归式的去截取字符串，并将获得到的字符串追加$tree_arr中
    			while ($tree_base != $val){
    				$pos = strpos($val, ',') + 1;
    				$val = substr($val, $pos);	//每次以逗号截取字符串获得当前字符串的父串
    				if(!in_array($val, $tree_arr)){	//如果当前截出来的字符串没有在$tree_arr数组中，则要追加到数组中
    					array_push($tree_arr, $val);
    				}
    			}
    		}
    	}
		$ldap_tree = $this->getTree($server_info, $tree_arr);	//想要同步的组织组织成树的结构
		if(is_null($ldap_tree)){
			form_json_msg(ORGANIZATION_TREE_ERROR, '', lang('organization_tree_error'));
			break;
		}
		form_json_msg(COMMON_SUCCESS, '', $ldap_tree);
	}
	/**
	 * @brief 根据ldapid展示ldap的详细信息
	 */
	public function showLdapInfoPage(){
		//获取参数
		$ldap_id = intval($this->input->get_post('ldap_id', true));
		if($ldap_id <= 0){
			form_json_msg(COMMON_PARAM_ERROR, '', lang('param_error'));
			break;
		}
		//从ums获取ldap数据
		$res = $this->ums->getLdap($ldap_id);
		if($res === false){
			form_json_msg(GET_LDAP_DATA_ERROR, '', lang('get_ldap_data_error'));
			break;
		}
		//Ldap服务器配置参数
		$server_info = array();
		
		$server_info['hostname']			=	isset($res['hostname']) ? $res['hostname'] : ''; 
		$server_info['port']				=	isset($res['port']) ? $res['port'] : ''; 
		$server_info['admindn']				=	isset($res['username']) ? $res['username'] : ''; 
		$server_info['basedn']				=	isset($res['basedn']) ? $res['basedn'] : ''; 
		$server_info['adminpassword']		=	isset($res['password']) ? $res['password'] : ''; 
		$server_info['authtype']			=	isset($res['protocol']) ? $res['protocol'] : ''; 
		$server_info['servertype']			=	isset($res['serverType']) ? $res['serverType'] : ''; 
		//导入的组织列表
		$ldap_tree	= array();
		$tree_str   = $res['ldapOrgMapping']['organizations'];	//同步组织的字符串，由于拼接时最后一位有';'字符，因此变数组时应去掉这位
    	//需要将查找到的返回的同步组织数组，组织成树形数组
    	if(!empty($tree_str)){
    		$tree_arr   = explode(';', substr($tree_str, 0, strlen($tree_str) - 1));
    		$base_start	= stripos($tree_arr[0], $server_info['basedn']);
    		$tree_base	= substr($tree_arr[0], $base_start);	//获得根节点
    		foreach ($tree_arr as $k => $val){	//对于数组的每一条记录都需要递归式的去截取字符串，并将获得到的字符串追加$tree_arr中
    			while ($tree_base != $val){
    				$pos = strpos($val, ',') + 1;
    				$val = substr($val, $pos);	//每次以逗号截取字符串获得当前字符串的父串
    				if(!in_array($val, $tree_arr)){	//如果当前截出来的字符串没有在$tree_arr数组中，则要追加到数组中
    					array_push($tree_arr, $val);
    				}
    			}
    		}
    	}
		$ldap_tree = $this->getTree($server_info, $tree_arr);	//想要同步的组织组织成树的结构
		//选择的员工标签
		$classes  = explode(';',$res['ldapUserMapping']['objectClass']);
		//ldap id
		$ldap_id = $res['id'];
		//统一前缀账号
		$loginNameAttribute 	= $res['ldapUserMapping']['loginNameAttribute'];
		$customLoginNameSuffix	= $res['ldapUserMapping']['customLoginNameSuffix'];
		$account 				= $loginNameAttribute.$customLoginNameSuffix;
		//不开通全时sooncore平台的例外规则
		$rule_arr = $this->ldap->getRuleByLdapId($ldap_id);
		if(empty($rule_arr)){
			$rule_arr = array('this ldap has not filter_rule');
		}
		
		$this->assign('ldap_name', $res['confName']);
		$this->assign('ldap_id', $ldap_id);
		$this->assign('server_info', $server_info);
		$this->assign('classes', $classes);
		$this->assign('ldap_tree', $ldap_tree);
		$this->assign('account', $account);
		$this->assign('filter_rule', $rule_arr);
		
		$this->display('ldap/ldapdetail.tpl');
	}
	
	public function showSavePage(){
		$this->load->view('public/popup/ldap6');
	}
	/**
	 * 显示删除页面
	 */
	public function showDeleteLdapPage(){
		$this->load->view('ldap/ldapdelete.php');
	} 
	
	/**
	 * 显示删除页面
	 */
	public function showDeleteLdapPage2(){
		$this->load->view('ldap/ldapdelete2.php');
	} 
		
	/**
	 * 删除ldap配置
	 */
	public function deleteLdap(){
		//获取参数
		$ldap_ids = $this->input->get_post('ldap_ids', true);
		if(is_null($ldap_ids)){
			form_json_msg(COMMON_PARAM_ERROR, '', lang('param_error'));
		}
		//删除
		$this->load->library('UmsLib', '', 'ums');
		if(count($ldap_ids) > 1){	//多个id，批量删除
			$delOK = $this->ums->deleteLdaps($ldap_ids);
		}else if(count($ldap_ids) == 1){	//删除单个
			$delOK = $this->ums->deleteLdap($this->p_site_id, $ldap_ids[0]);
		}else{
			form_json_msg(COMMON_PARAM_ERROR, '', lang('param_error'));
		}
		if($delOK){	//如果删除成功，则需要从本地中删除相对应的规则数据信息
			$this->ldap->deleteLdapConfig($ldap_ids);
		}
		log_message('info', __FUNCTION__.' customerCode->'.$this->p_customer_code.' site_id->'.$this->p_site_id.' input params->'.var_export($ldap_ids, true));
		form_json_msg(COMMON_SUCCESS, '', lang('delete_success'));
	}
	
	public function showCloseLdapPage(){
		$this->load->view('ldap/ldapclose.php');
	}
	
	/**
	 * @brief ldap同步的开启与关闭方法
	 */
	public function changeLdapStatus(){
		$ldap_id	= $this->input->get_post('ldap_id', true);
		$status_str = $this->input->get_post('status' ,true);
		if(!in_array($status_str, array('close','open'))){
			form_json_msg(COMMON_PARAM_ERROR, '', lang('param_error'));
		} 
		$status_code = ($status_str == 'open' ? 1 : 0);	//如果当前状态是关闭则需要将状态值修改为1表示开启
		$ldap_param	 = $this->ums->getLdap($ldap_id);	//根据id获取ldap组织信息
		$ldap_param['siteLdapConfig']['enableOrgSync'] = $status_code;	//修改ldap组织同步设置
		$ldap_param['siteLdapConfig']['enableUserSync'] = $status_code;	//修改ldap用户同步设置
		
		$ret = $this->ums->editLdap($ldap_param);
		if(!$ret){
			form_json_msg(UPDATE_USER_STATUS_ERROR, '', lang('update_user_status_error'));
		}
		
		$this->ldap->updateLdapConfig();
		form_json_msg(COMMON_SUCCESS, '', lang('success'));
	}
	
	/**
	 * 检查ldap服务器配置信息
	 * @param $server_info ldap服务器配置信息
	 * return array('code'=>0, 'msg'=>'aaa');
	 * 
	 */
	private function _checkLdapServerInfo($server_info){
		//check keys
		$allow_keys = array('hostname', 'port', 'basedn', 'admindn', 'adminpassword', 'authtype', 'servertype');
		foreach($allow_keys as $allow_key){
			$param = $server_info[$allow_key];
			if(!isset($param)){
				return array(false, 'the key '.$allow_key.' is required!');
			}
		}
		//check values
		$this->load->library('form_validation','','validation');
		if( !$this->validation->valid_ip($server_info['hostname'] ,'ipv4') ){
			return array(false, lang('server_addr_error'));
		}
		if( !$this->validation->is_natural_no_zero($server_info['port']) ){
			return array(false, lang('server_port_error'));
		}
		if(!in_array($server_info['authtype'], array(0, 1))){ //0-标准ldap 1-ldaps
			return array(false, lang('server_authtype_error'));
		}
		if(!in_array($server_info['servertype'], array(1, 2, 3, 4))){ //Ldap服务器类型1-MS AD 2-OpenDirectory 3-Lotus Dimino 4-其他
			return array(false, lang('server_type_error'));
		}
		
		return array(true, '');
	}
	
	/**
	 * 检查用户标签
	 * @param array $property_info  用户选定的标签
	 * @return array
	 */
	private function _checkLdapPropertyInfo($property_info){
		if(!is_array($property_info) || count($property_info)<=0){
			return array(false, lang('staff_attribute_error'));
		}
		//获取可选标签
		$tags_map = $this->config->item('_tags_arr', 'ldap_config');
		$this->load->model('uc_user_tags_model', 'tag');
		$_tags	= $this->_tags_arr;//可选标签
		$tags   = array();
		foreach($_tags as $tag){
			if($j = array_search($tag, $tags_map)){
				$tags[] = $j;
			}
		}
		
		$allow_key	= array();
		$must_key	= $this->config->item('_must_tags_arr', 'ldap_config');
		foreach ($must_key as $k=>$val){
			$allow_key[] = $k;
		}
		$allow_key = array_merge($tags, $allow_key);
		foreach($allow_key as $k){
			if( !isset($property_info[$k]) || trim($property_info[$k]) == '' ){
				return array(false, 'the key '.$k.' is required');
			}
		}
		return array(true, '');
	}
	
	/**
	 * 
	 * @param  array $server_info
	 * @param  array $org_info
	 * @param  array $property_info
	 * @param  string $filter_rule
	 * @param  string $tag_value
	 * @param  string $email_value
	 * @param  string $is_auto_del
	 */
	private function _checkAllLdapParams($server_info, $org_info, $classes, $property_info, $filter_rule, $email_value, $is_auto_del){
		list($f_server_info, $msg) = $this->_checkLdapServerInfo($server_info);
		if(!$f_server_info){
			form_json_msg(COMMON_PARAM_ERROR, '', $msg);
		}
		list($f_property_info, $msg2) = $this->_checkLdapPropertyInfo($property_info);
		if(!$f_property_info){
			form_json_msg(COMMON_PARAM_ERROR, '', $msg2);
		}
		
		if(!is_string($org_info) || trim($org_info) == ''){
			form_json_msg(SYNORG_NOT_NULL, '', lang('synorg_not_null'));
		}
		if(!is_array($classes) || count($classes) <= 0){
			form_json_msg(CLASS_NOT_NULL, '', lang('class_not_null'));
		}
		
		if(!is_string($filter_rule) || !is_string($email_value) || !in_array($is_auto_del, array(0,1))){
			form_json_msg(COMMON_PARAM_ERROR, '', lang('param_error'));
		}
		if($email_value == ''){
			form_json_msg(COMMON_PARAM_ERROR, '', lang('param_error'));
		}

	}
	
	/**
	 * 检查ldap id是否合法
	 * @param int $ldap_id 
	 */
	public function _checkLdapId($ldap_id){
		$ret = $this->ums->getLdap($ldap_id);
		return (boolean)$ret;
	}

	/**
	 * 国际化字符串数组获取
	 * @return array 含有国际化字符的数组 
	 */
	public function ldapInternational(){
		//LDAP服务器配置信息
		$arr = array();
		$servertype = array(
				lang('servertype0'),lang('servertype1'),lang('servertype2'),lang('servertype3'),lang('servertype4')
		);
		$protocol = array(
				lang('authtype_name0'),lang('authtype_name1'),lang('authtype_name2')
		);
		$arr['servertype']	= $servertype;
		$arr['protocol']	= $protocol;
		return $arr;
	}
		
}
