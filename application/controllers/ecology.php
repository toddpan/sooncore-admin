<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 生态企业
 * @file ecology.php
 * @author caohongliang <hongliang.cao@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
 
class Ecology extends Admin_Controller{
	
	private $_create_ecology_params;	//将页面传递过来的创建生态企业请求数据封装到此变量中
	public  $error = '';  //错误信息
	public function __construct() {
		parent::__construct();
		$this->lang->load('ecology', 'chinese');
		$this->lang->load('common', 'chinese');
		$this->load->helper('my_publicfun');
		$this->load->helper('language');
		$this->load->library('UmsLib','','ums');
		$this->load->library('OrganizeLib','','OrganizeLib');
		$this->load->library('Form_validation','','Form_validation');
	}
	
	public function getLastError(){
		return $this->error;
	}
	
	/**
	 * 获取企业生态列表
	 */
	public function initStqyOrg(){
		$this->load->library('OrganizeLib','','OrganizeLib');
        //获得客户编码			
        $customer_code = $this->p_customer_code;//'024014';
        //首级及下一级组织数组
        $first_next_org_arr = $this->OrganizeLib->get_first_next_org_arr($customer_code,'2');

        //首级及下一级组织json串
        $in_arr = array(
            'is_first' => 1 ,//是否第一级0不是1是       
        );
        $org_arr = $this->OrganizeLib->InitzTree_arr($first_next_org_arr ,1,$in_arr);
        $org_json = '[]';
        if(is_array($org_arr)){//如果是数组
         $org_json = json_encode($org_arr);
        }
        $data['org_list_json'] = $org_json;
        
        $this->load->view('ecologycompany/ecologyPage.php',$data);
	}
	/**
	 * 获取下级管理员列表 ajax
	 */
	public function curNextLevelManagers(){
		//获取参数
		$id = intval($this->input->post('id', true));
		log_message('info', __FUNCTION__." input->\n".var_export(array('id'=>$id),true));
		
		//检查当前管理员id，并获取当前管理员信息，如果id为空表示获取根节点
		$this->load->model('ecology_model', 'ecology');
		if($id == 0){	//表示从管理员页面创建
			$next_managers = $this->ecology->getRootManager($this->p_site_id, $this->p_org_id, $this->p_user_id);
			$sub_managers = $this->ecology->getNextLevelManager($next_managers['0']['id']);
			if($sub_managers){
				$next_managers = array_merge($next_managers,$sub_managers);
			}
			!$next_managers && form_json_msg(0, '', lang('success'));//TODO
		}else{
			$next_managers = $this->ecology->getNextLevelManager($id);
			!$next_managers && form_json_msg(0, '', lang('success'));
		}
		
		//重组返回数据的格式
		$next_managers_format = array();
		foreach($next_managers as $m){
			$tmp = array();
			$tmp['id']   = $m['id'];
			$tmp['pId']  = intval($m['parent_id']);
			$tmp['name'] = $m['display_name'];
			$tmp['nocheck'] = true;
			$tmp['open']    = false;
			$tmp['isParent'] = $this->ecology->hasNextLevel($m['id']);
			$tmp['isaddnext'] = true;
			$tmp['isdel']     = ($id === 0) ? false : true;
			$next_managers_format[] = $tmp;
		}
		
		log_message('info',__FUNCTION__." output->\n".var_export($next_managers_format, true));
		
		form_json_msg(0, '', lang('success'), $next_managers_format);
	}
	
	/**
	 * 获取生态管理员所管辖的生态企业
	 */
	public function ecologyList(){
		$m_id    = $this->input->get_post('id', true);//管理员id
		$limit   = ($count = $this->input->get_post('count', true)) ? $count : 100;//每页显示条数,默认15条
		$offset  = ($page = $this->input->get_post('page', true)) ? ($page-1)*$limit : 0;//偏移量
		log_message('info', __FUNCTION__." input->\n".var_export(array('id'=>$m_id,'count'=>$count,'page'=>$page), true));
		
		//检查当前管理员
		$this->load->model('ecology_model', 'ecology');
		if(!($m_info = $this->ecology->getManagerById($m_id))){
			show_error(sprintf(lang('error_invalid_param'), $m_id));
		}
		
		//获取生态企业
		$eco_list = array();
		$ecos = $this->ecology->getAllEcologysByMid($m_id, $limit, $offset);
		
		//整理数据
		$this->load->library('UmsLib','','ums');
		foreach($ecos as $e){
			$tmp = array();
			
			//TODO 优化，ums开通批量获取组织信息的接口
			$ums_info =  $this->ums->getOrganizationBrief($e['ecology_id']);
			$tmp['id']				     = element('id',$ums_info,0);
			$tmp['name']				 = element('name',$ums_info,'');
			
			if(!empty($ums_info['parentId'])){
				$ums_info2 = $this->ums->getOrganizationBrief($ums_info['parentId']);
				$tmp['parent_ecology_name'] = element('name',$ums_info2,'');
			}else{
				$tmp['parent_ecology_name'] = '';
			}
			
			$tmp['ecology_manager_name'] = $this->ecology->getEcologyManagerName($e['ecology_id']);//生态管理员姓名
			$tmp['qudao_manager_name']   = $this->ecology->getQudaoManagerName($e['ecology_id']);//渠道管理员姓名
			//TODO 这个等后面的area添加好了加上去
			$tmp['area']				 = '北京(暂定)';
			$eco_list[]					 = $tmp;
		}
		
		$data = array('eco_list'=>$eco_list,'total'=>$this->ecology->getAllEcologysTotalNum($m_id));
		log_message('info',__FUNCTION__." output->\n".var_export($data, true));
		$this->load->view('public/part/ecologycompanylist.php', $data);
	}
	
	
	public function showDelEcologyPage(){
		$this->load->view('ecologycompany/delEcologyCompany.php');
	}
	
	
	/**
	 * @brief 验证第一步用户创建信息是否合法
	 */
	public function valid_eco_1_old(){
		$company_name	 = $this->input->get_post('company_name', true);	//企业名称
		$company_chinese = $this->input->get_post('company_chinese', true);	//中文简称
		$country_area	 = $this->input->get_post('country_area', true);	//国家地区
		$country_code	 = $this->input->get_post('country_code', true);	//国家编码
		$phone_number	 = $this->input->get_post('phone_number', true);	//电话号码
		$area_code		 = $this->input->get_post('area_code', true);		//区号
		$introduce		 = $this->input->get_post('introduce', true);		//公司简介
		$org_id			 = $this->input->get_post('org_id', true);			//上级企业ID
		
		$child_org_arr	 = $this->OrganizeLib->get_org_array($org_id, 'subtree', '2');
		var_dump($child_org_arr);
		
		$this->_create_ecology_params = array(
				'company_name'	 	=> $company_name,
				'company_chinese'	=> $company_chinese,
				'country_area'		=> $country_area,
				'introduce'			=> $introduce,
				'org_id'			=> $org_id,
				array(
						'country_code'	=> $country_code,
						'area_code'		=> $area_code,
						'phone_number'	=> $phone_number
				)
		);
		
	}
	
	/**
	 * 删除生态企业
	 */
	public function delEcology(){
		//获取参数
		$ids = $this->input->get_post('ids', true);
		log_message('info', __FUNCTION__." input->\n".var_export(array('ids'=>$ids),true));
		//参数检查
		if(!is_array($ids) || count($ids)==0){
			//form_json_msg(10000, '', 'param is invalid!');
			form_json_msg(10000, '', sprintf(lang('error_invalid_param'), var_export($ids, true)));
		}
		array_map('intval', $ids);
		
		//异步删除生态企业
		$this->load->model('ecology_model', 'ecology');
		$this->ecology->delEcologyAsyn($ids);
		
		log_message('info',__FUNCTION__." output->success\n");
		form_json_msg(0, '', 'success');
	}
	
	/**
	 * 生态企业权限设置
	 * -$props格式
	 * - array(
	 * -	'power_name'=>'power_value',
	 * -	...
	 * -)
	 */
	/*
	public function setEcologyPower(){
		//获取参数
		$props  = $this->input->get_post('props', true);
		$eco_id = intval($this->input->get_post('id', true));
		
		//检查参数
		list($flag,$msg) = $this->_checkEcologyProps($props);
		if(!$flag || $eco_id <= 0){
			echo response_json(10000, $msg);return;
		}
		
		//获取生态权限组件
		$this->load->model('power_model', 'power'); 
		$value = $this->power->getOrgPower($this->p_site_id, $this->getOrgNodeCodeById($eco_id));
		if(is_empty($value)){
			echo response_json(20000, lang('get_org_components_failed'));return;
		}else{
			$value = json_decode($value, true);
		}
		
		//如果权限没有发生变更，则直接返回
		if(!$this->_isPropsChanged($eco_id, $value, $props)){
			echo response_json(0, lang('props_not_changed'));return;
		}
		
		//权限变更,则更新权限项目
		$this->load->model('power_model', 'power');
		foreach($props as $p_k=>$prop){
			mutiple_array_value($p_k, $prop, &$value);
		}
		$this->power->saveOrgPower($eco_id, $value);
		
		//返回
		echo response_json(0, lang('success'));return;
	}
	*/
	
	private function _isPropsChanged($eco_id, $components, $props){
		//权限项比对
		foreach($props as $p_k=>$prop){
			$_prop = multiple_array_search($p_k, $components);
			if($_prop != $prop){
				return true;//changed
			}
		}
		
		return false;
	}
	
	
	/**
	 * 检查权限格式
	 * @param string $props json串
	 * @return boolean
	 */
	private function _checkEcologyProps($props){
		$props = json_decode($props, true);
		if(is_null($props) || !is_array($props) || count($props)<=0){
			return array(false,lang('error_param'));
		}
		
		//从配置文件获取生态权限配置模板
		$this->config->load('power_view');
		$eco_cfg = $this->config->item('eco_power');
		$cfg_allow_props  = array();
		$cfg_prop_values  = array();
		foreach($eco_cfg as $e){
			$cfg_allow_props[] = $e['property'];
			$cfg_prop_values[] = $e['values']; 
		}
		
		//检查
		foreach($props as $p_k=>$prop){
			if(!in_array($p_k, $cfg_allow_props || !in_array($prop, $cfg_prop_valus))){
				return array(false, lang('error_invalid_power_items'));
			}
		}
		
		return array(true,'ok');
	}
	
	
	/**
	 * 设置生态管理员
	 */
	public function setEcologyManager(){
		//获取参数
		$user_id = intval($this->input->get_post('user_id', true));
		$eco_id  = intval($this->input->get_post('ecology_id', true));
		
		log_message('info', "the action ".__FUNCTION__." input->".var_export(array('user_id'=>$user_id, 'ecology_id'=>$eco_id),true).' site_id->'.$this->p_site_id.' customer code->'.$this->p_customer_code);
		//检查参数
		if(empty($user_id) || empty($eco_id)){
			return_json(10000, lang('error_param'));
		}
		//只能为下级生态企业设置自己的员工
		if( !$this->_isNextLevelEcology($eco_id) || !$this->_isOurUser($user_id)){
			return_json(20000, lang('set_ecology_manager'));
		}
		//判断该用户是否为生态管理员角色，如果不是则为其添加生态管理员角色，父级管理员为当前系统管理员
		$this->load->model('ecology_model', 'ecology');
		if(!$this->ecology->isEcologyManager($user_id)){//TODO 
			list($is_success, $data) = $this->ecology->addEcologyManager($this->p_site_id, $this->p_org_id, $this->p_is_ldap, $user_id, $this->p_user_id);
			!$is_success && return_json(30000, lang('error_set_ecology_manager'));
		}
		$this->ecology->setEcologyManager($this->p_site_id, $this->p_org_id, $user_id, $eco_id);
		
		return_json(0, lang('success'));
	}
	
	/**
	 * 判断是否当前企业或生态企业的下级
	 * @param int $eco_id 生态id
	 */
	private function _isNextLevelEcology($eco_id){
		$this->load->library('UmsLib', '', 'ums');
		$rs = $this->ums->getOrganization($this->p_org_id, 'nextlevel', '2');
		if(!$rs){
			return false;
		}
		$next_levels = array();
		foreach($rs as $o){
			$next_levels[] = $o['id'];
		}
		return in_array($eco_id, $next_levels) ? true : false;
	}
	
	/**
	 * 添加生态管理员
	 */
	public function addEcologyManager(){
		//获取参数
		$user_ids      = $this->input->get_post('user_id', true);
		$p_id = intval($this->input->get_post('p_id', true));
		log_message('info', "the action ".__FUNCTION__." input->".var_export(array('user_id'=>$user_ids, 'p_id'=>$p_id),true).' site_id->'.$this->p_site_id.' customer code->'.$this->p_customer_code);

		//如果父级管理员参数为0，则默认为系统管理员、渠道管理员或者当前登录的生态管理员，也就是当前用户id。
		$this->load->model('ecology_model', 'ecology');
		$p_manager_id = ($p_id === 0) ? $this->p_user_id : element('user_id', $this->ecology->getManagerById($p_id), 0);
		if(!is_array($user_ids) || empty($user_ids) || empty($p_manager_id)){
			return_json(10000, lang('error_param'));
		}
		$user_ids = array_map('intval', $user_ids);
		
		//检查上级管理员是否为本方用户
		if(!$this->_isOurUser($p_manager_id)){
			return_json(20000, sprintf(lang('error_p_must_be_our_user'), $p_manager_id));
		}
		
		//检查父级用户必须为生态管理员、渠道管理员、或者系统管理员角色
		$this->load->model('uc_user_admin_role_model', 'user_role');
		$allow_roles = array(ECOLOGY_MANAGER, SYSTEM_MANAGER, CHANNEL_MANAGER);
		$p_roles = $this->user_role->getRoleIdsByUserId($p_manager_id);
		if(is_empty(array_intersect($allow_roles,$p_roles))){
			return_json(30000,lang('error_invalid_role'));
		}
		
		//检查要添加的生态管理员
		foreach($user_ids as $user_id){
			//是否为本方员工
			if(!$this->_isOurUser($user_id)){
				return_json(40000, sprintf(lang('error_s_must_be_our_user'), $user_id));
			}
			
			//检查用户是否已经是生态管理员、渠道管理员、系统管理员
			$s_roles = $this->user_role->getRoleIdsByUserId($user_id);
			if(!is_empty(array_intersect($allow_roles,$s_roles))){
				return_json(50000,sprintf(lang('error_already_ecology_manager', $user_id)));
			}
			
			//添加生态管理员
			list($flag, $data) = $this->ecology->addEcologyManager($this->p_site_id, $this->p_org_id, $this->p_is_ldap, $user_id, $p_manager_id);
			if(!$flag){
				return_json(60000,lang('error_add_ecology_manager'));
			}
		}
		
		//返回
		return_json(0,lang('success'),array('id'=>$data));
	}
	
	public function showDelEcologyManagerPage(){
		 $this->load->view('manage/remindDelEcoAdmin.php');
	}
	/**
	 * 删除生态管理员
	 */
	public function delEcologymanager(){
		//获取参数
		$id = intval($this->input->get_post('id', true));
		$this->load->model('ecology_model', 'ecology');
		$del_user_id = ($id>0) ? element('user_id', $this->ecology->getManagerById($id), 0) : 0 ;
		log_message('info', __FUNCTION__." input->\n".var_export(array('id'=>$id),true).' site_id->'.$this->p_site_id.' customer code->'.$this->p_customer_code);
		//检查参数格式
		if(!$del_user_id){
			return_json(10000, lang('error_param'));
		}
		//检查是否为系统管理员，渠道管理员，或当前登录的生态管理员，如果是，则不能被删除
		$this->load->model('uc_user_admin_role_model', 'user_role');
		$deny_roles = array(SYSTEM_MANAGER, CHANNEL_MANAGER);
		$m_roles = $this->user_role->getRoleIdsByUserId($del_user_id);
		if(!is_empty(array_intersect($deny_roles,$m_roles)) || $del_user_id == $this->p_user_id){
			return_json(30000,lang('error_can_not_delete_manager'));
		}
		//删除生态管理员
		$rs = $this->ecology->delEcologyManager($del_user_id);
		if(!$rs){
			return_json(40000,lang('error_delete_ecology_manager_fail'));
		}
		log_message('info', "output->success\n");
		return_json(0, lang('success'));
	}
	
	/**
	 * 添加本方参与人员
	 * -本方参与人员必须是当前企业或者生态企业里的员工
	 */
	public function addPartake(){
		//获取参数
		$eco_id = intval($this->input->get_post('id', true));
		$user_ids = $this->input->get_post('user_ids', true);
		
		//检查参数
		if($eco_id<=0 OR !is_array($user_ids) OR !min(0, $user_ids = array_map('intval',$user_ids))){
			echo response_json(10000, lang('error_param'));return;
		}
		
		//检查生态企业是否存在，并且为生态企业
		if(!$this->_isExistOrg($eco_id, $type)){
			echo response_json(20000, lang('error_param'));return;
		}
		
		//检查用户是否属于本公司、过滤掉已经
		/*
		foreach($user_ids as $user_id){
			if(!$this->_isOurUser($user_id)){
				echo response_json(20000, sprintf(lang('error_s_must_be_our_user'), $user_id));return;
			}
		}
		*/
	}
	
	
	/**
	 * 判断是否为本公司的员工
	 * @param int $user_id 用户id
	 */
	private function _isOurUser($user_id){
		$this->load->library('UmsLib', '', 'ums');
		
		//TODO 注意：这里遇到个很奇怪的问题:管理员在ums中查不到与组织的关联关系，所以这里做特殊判断说明，后面可能会去掉这段代码
		if($user_id == $this->p_user_id) return true;
		
		$rs = $this->ums->getOrganizationByUserId($user_id);
		if(!$rs){
			return false;
		}
		$code = isset($rs['customercode']) ? $rs['customercode'] : 0;
		return ($code == $this->p_customer_code);
	}
	
	
	/****************************************************************************
	* @Date 2014年9月26日
	* @author hao.chen@quanshi.com
	****************************************************************************/
	
	/**
	 * @brief 用户点击“创建生态企业”按钮，展示生态企业的创建页面
	 */
	
	public function showEcology(){
		$orgid			 = intval($this->input->get_post('orgid', true));//上级组织id
		$sup_depart_name = '';
		if(empty($orgid)){	//未获取到上级组织ID 表示从管理员页面创建
			$orgid = $this->p_org_id;
		}
		if($orgid){//通过“生态企业”组织类表创建
			$org_infos		 = $this->ums->getOrganizationBrief($orgid);	//通过组织id得到该管理员所在的组织信息
			$sup_depart_name = $org_infos['name'];
		}
		//获取所有国家编码数组
		$this->load->library('public/Country_code', '' , 'country');
		$contry_code_arr 	= $this->country->getCountryCode();
		//获取生态企业默认权限
		$permissions = $this->_getDefaultPermissions();
		
		//创建管理员的后缀
		$mail_suffix = '@'.$this->p_account_back;
		$telephone = DEFAULT_TELEPHONE_PRE;
		$this->assign('org_id', $orgid);	//要创建组织的上级企业的名称
		$this->assign('sup_depart_name', $sup_depart_name);	//要创建组织的上级企业的名称
		$this->assign('telephone',$telephone);	//默认的国家编码
		$this->assign('country_code', $contry_code_arr);	//所有国家编码数组
		$this->assign('permissions_label_name', $permissions);	//生态企业默认权限
		$this->assign('mail_suffix', $mail_suffix);	//创建管理员的后缀
		$this->display('ecologycompany/createEcologyCompany1.tpl');
	}
	
	/**
	 * @brief 获取创建的生态企业默认的权限
	 * @return array
	 */
	private function _getDefaultPermissions(){
		//TODO 此处先暂时使用键值对数组返回 后期确定之后可放入配置文件或通过其他接口获得
		return array(
			'allow_network_meeting'		=> lang('allow_network_meeting'),//允许召开网络会议
			'allow_conference_call'		=> lang('allow_conference_call'),//允许召开电话会议
			'allow_set_call_forwarding'	=> lang('allow_set_call_forwarding'),//允许设置呼叫转移
			'allow_call'				=> lang('allow_call')//允许拨打电话
		);
	}

	/**
	 * @brief 验证第一步用户创建信息是否合法
	 */
	
	public function valid_eco_1(){
		$data['parentId'] 		= $this->input->get_post('orgid', true) ? $this->input->get_post('orgid', true) : $this->p_org_id;	//上级企业ID
		$data['name']			= $this->input->get_post('create_company_name', true);	//企业名称
		$data['abbreviation'] 	= $this->input->get_post('create_company_chinese', true);	//中文简称
		$data['country_area'] 	= $this->input->get_post('create_country_area', true);	//国家地区
		$data['mobileNumber'] 	= $this->input->get_post('create_phone_number', true);	//电话号码
		$data['introduction'] 	= $this->input->get_post('textarea', true);	//公司简介
		$data['area_code'] 		= $this->input->get_post('create_area_code', true);	//区号
		log_message('info', __FUNCTION__." input->\n".var_export($data,true).' site_id->'.$this->p_site_id.' customer code->'.$this->p_customer_code);
		$res = $this->_validCompanyInfo($data);//验证创建是公司信息
		if($res){
			form_json_msg('0','', lang('success'));
		}else{
			$error = $this->getLastError();
			form_json_msg($error[0],$error[1],$error[2],array(''));
		}
	}
	/**
	 * @brief验证公司信息
	 */
	private function _validCompanyInfo($data){
		//验证生态企业名称
		if(empty($data['name'])){
			$this->error = array(E_COMPANY_NOT_NULL,'create_company_name',lang('e_company_not_null'));//生态企业公司名不能为空
			return false;
		}
		if($this->Form_validation->count_string_len($data['name']) > 100){
			$this->error = array(E_COMPANY_TOO_LONG,'create_company_name',lang('e_company_too_long'));//生态企业公司名长度不能大于50个汉字
			return false;
		}
		
		//生态企业中文名称
		if(empty($data['abbreviation'])){
			$this->error = array(E_COMPANY_CHINESE_NOT_NULL,'create_company_chinese',lang('e_company_chinese_not_null'));//生态企业中文名称不能为空
			return false;
		}
		if($this->Form_validation->count_string_len($data['abbreviation']) > 100){
			$this->error = array(E_COMPANY_CHINESE_TOO_LONG,'create_company_chinese',lang('e_company_chinese_too_long'));//生态企业中文名称不能大于50个汉字
			return false;
		}
		
		//验证电话号码
		if(!is_tel_number($data['mobileNumber'])){
			$this->error = array(E_TELEPHONE_ERROR,'create_phoneNum_1',lang('e_telephone_error'));//电话号码有误
			return false;
		}
		
		//国家地区
		if($this->Form_validation->count_string_len($data['country_area']) > 30){
			$this->error = array(E_COUNTRY_TOO_LONG,'create_country_area',lang('e_country_too_long'));//国家地区长度不能大于15个汉字
			return false;
		}
		//简介
		if($this->Form_validation->count_string_len($data['introduction']) >1000){
			$this->error = array(E_INTRODUCTION_TOO_LONG,'textarea',lang('e_introduction_too_long'));//公司简介长度不能大于500个汉字
			return false;
		}
		//查询企业生态名称是否重复
		$nextLevelOrgList = $this->ums->getOrganization($data['parentId'],$this->ums->scope_nextlevel,ORG_ECOLOGY_COMPANY);
		$flag = false;//兄弟节点是否重名 默认未重名
		if(is_array($nextLevelOrgList)){
			foreach ($nextLevelOrgList as $org){
				if($data['name'] == $org['name']){
					$flag = true;
					break;
				}
			}
		}
		
		if($flag){
			$this->error = array(E_COMPANY_EXIST_ERROR,'create_company_name',lang('e_company_exist_error'));//生态名称重复
			return false;
		}
		return true;
	}
	
	/**
	 * @brief 验证第二步权限
	 */
	public function valid_eco_2(){
		$permissions = $this->input->get_post('power_json', true);
		$permissions_arr_key = array_keys($this->_getDefaultPermissions());//获取权限表单name即为权限数组对应的键
		foreach ($permissions_arr_key as $value){
			$data[$value] = $permissions[$value];	//公司简介
		}
		log_message('info', __FUNCTION__." input->\n".var_export($data,true).' site_id->'.$this->p_site_id.' customer code->'.$this->p_customer_code);
		$res = $this->_validPermissions($data);//验证权限信息
		if($res){
			form_json_msg(COMMON_SUCCESS,'', lang('success'));
		}else{
			$error = $this->getLastError();
			form_json_msg($error[0],$error[1],$error[2],array(''));
		}
	}
	
	/**
	 * @brief验证权限
	 */
	private function _validPermissions($data){
		//TODO
		return true;
	}
	
	
	/**
	 * @brief 验证第三步验证管理员信息
	 */
	public function valid_eco_3(){
		$admin_post_data = json_decode($this->input->get_post('user_json', true),true);
		$creat_admin_date = array();
		$creat_admin_date['firstName'] 		= 		$admin_post_data['sys_tag']['firstname'];
		$creat_admin_date['lastName'] 		= 		$admin_post_data['sys_tag']['lastname'];
		$creat_admin_date['position'] 		= 		$admin_post_data['sys_tag']['status'];
		$creat_admin_date['mobileNumber'] 	= 		$admin_post_data['sys_tag']['telephone_number'];//手机
		$creat_admin_date['loginName'] 		= 		$admin_post_data['sys_tag']['usercount'].'@'.$this->p_account_back;
		$creat_admin_date['sex'] 			= 		$admin_post_data['sys_tag']['sex'];
		$creat_admin_date['location'] 		= 		$admin_post_data['sys_tag']['location'];//工作地点
		$creat_admin_date['email'] 			= 		$admin_post_data['sys_tag']['email'];//邮箱
		log_message('info', __FUNCTION__." input->\n".var_export($creat_admin_date,true).' site_id->'.$this->p_site_id.' customer code->'.$this->p_customer_code);
		$res = $this->_validAdminInfo($creat_admin_date);//验证管理员信息
		if($res){
			form_json_msg(COMMON_SUCCESS,'', lang('success'));
		}else{
			$error = $this->getLastError();
			form_json_msg($error[0],$error[1],$error[2],array(''));
		}
	}
	
	/**
	 * @brief验证管理员信息
	 */
	private function _validAdminInfo($data){
		//姓和名
		if(empty($data['lastName']) || empty($data['firstName'])){
			$this->error = array(E_NAME_NOT_NULL,'firstname',lang('e_name_not_null'));//姓或名不能为空
			return false;
		}
		
		//职位
		if(empty($data['position'])){
			$this->error = array(E_JOB_NOT_NULL,'status',lang('e_job_not_null'));//职位不能为空
			return false;
		}
		
		//手机
		if(!is_numeric($data['mobileNumber']) && is_momobile_number($data['mobileNumber'])){
			$this->error = array(E_CALL_PHONE_ERROR,'telephone_number',lang('e_call_phone_error'));//手机号码有误
			return false;
		}
		
		//验证邮箱
		if(!$this->Form_validation->valid_email($data['email'])){
			$this->error = array(E_EMALL_ERROR,'email',lang('e_emall_error'));//姓或名不能为空
			return false;
		}
		
		//验证登陆名
		if(empty($data['loginName'])){
			$this->error = array(E_LOGIN_NAME_NOT_NULL,'usercount',lang('e_login_name_not_null'));//不能为空
			return false;
		}
		if($this->ums->getUserByLoginName($data['loginName'])){
			$this->error = array(E_LOGIN_NAME_EXIST_ERROR,'usercount',lang('e_login_name_exist_error'));//不能为空
			return false;
		}
		
		
		return true;
	}
	
	

	//创建生态企业
	public function creatEcologyCompany(){
		//接收创建企业生态权限
		$company_post_data = json_decode($this->input->get_post('company_information', true),true);
		$creat_company_date = array(
			'name'				=>		$company_post_data['create_company_name'],//创建名称
			'code'				=>		$this->p_customer_code,
			'parentId'			=>		$company_post_data['org_id'],//上级企业ID
			'customercode'		=>		$this->p_customer_code,
			'type'				=>		ORG_ECOLOGY_COMPANY,//类型 1：企业 2：生态企业 3：部门 4：生态企业部门
			'abbreviation' 		=>		$company_post_data['create_company_chinese'],//中文简称
			'countryCode'		=>		$company_post_data['create_country_code'],//电话区域
			'areaCode'			=>		$company_post_data['create_area_code'],//电话区号
			'mobileNumber'		=>		$company_post_data['create_phone_number'],//电话号码
			'introduction'		=>		$company_post_data['textarea'],//简介
		);
		log_message('info', __FUNCTION__.'->creat_company_date:'." input->\n".var_export($creat_company_date,true).' site_id->'.$this->p_site_id.' customer code->'.$this->p_customer_code);
		
		//TODO 接收权限数据
		
		//接收管理员信息
		$admin_post_data = json_decode($this->input->get_post('company_adminstrator', true),true);
		$creat_admin_date = array();
		$creat_admin_date['loginName'] 		= 		$admin_post_data['sys_tag']['usercount'].'@'.$this->p_account_back;
		$creat_admin_date['firstName'] 		= 		$admin_post_data['sys_tag']['firstname'];
		$creat_admin_date['lastName'] 		= 		$admin_post_data['sys_tag']['lastname'];
		$creat_admin_date['email'] 			= 		$admin_post_data['sys_tag']['email'];//邮箱
		$creat_admin_date['sex'] 			= 		$admin_post_data['sys_tag']['sex'];
		$creat_admin_date['mobileNumber'] 	= 		$admin_post_data['sys_tag']['telephone_number'];//手机
		$creat_admin_date['position'] 		= 		$admin_post_data['sys_tag']['status'];
		$creat_admin_date['displayName'] 	= 		$admin_post_data['sys_tag']['firstname'].$admin_post_data['sys_tag']['lastname'];
		$creat_admin_date['externalUserAddr']= 		$admin_post_data['sys_tag']['location'];//工作地点
		
		log_message('info', __FUNCTION__.'->creat_company_date:'." input->\n".var_export($admin_post_data,true).' site_id->'.$this->p_site_id.' customer code->'.$this->p_customer_code);
		
		//接收本方参与用户
		$company_staff_data = json_decode($this->input->get_post('company_staff', true),true);
		log_message('info', __FUNCTION__.'->company_staff_data:'." input->\n".var_export($company_staff_data,true).' site_id->'.$this->p_site_id.' customer code->'.$this->p_customer_code);
		
		//如果创建的组织信息和管理员信息有误
		if(!$this->_validCompanyInfo($creat_company_date) || !$this->_validAdminInfo($creat_admin_date)){
			$error = $this->getLastError();
			log_message('error', __FUNCTION__." input->\n".var_export($creat_company_date,true).var_export($creat_admin_date,true).' site_id->'.$this->p_site_id.' customer code->'.$this->p_customer_code);
			form_json_msg($error[0],$error[1],$error[2],array(''));
		}
		/**
		 * 创建流程
		 * 1.创建组织
		 * 2.创建用户并添加为创建组织的成员
		 * 3.本地	uc_organization 增加记录 权限为站点权限
		 * 4.开通账号[宏亮]uc_process_task
		 * 5.增加角色
		 *	 |--uc_user_admin
		 *	 |--uc_user_admin_role
		 * 6.本方参与人员 uc_ecology_partake
		 */
		//1.创建组织 调USM创建生态企业组织
		$res_org = $this->ums->createOrganization($creat_company_date);
		if(!$res_org){//创建失败
			log_message('error', __FUNCTION__.'createOrganization error'." input->\n".var_export($creat_company_date,true).' site_id->'.$this->p_site_id.' customer code->'.$this->p_customer_code);
			form_json_msg(E_ORGANIZATION_CREAT_FAILURE,'',lang('e_organization_creat_failure'),array(''));
		}
		//2.创建用户并添加为创建组织的成员 调USM添加用户并添加为组织成员
		$res_user 	= $this->ums->addNewUserToOrg($creat_admin_date,$res_org);
		if(!$res_user){//添加失败
			log_message('error', __FUNCTION__.'createUser'." input->\n".var_export($creat_admin_date,true).' site_id->'.$this->p_site_id.' customer code->'.$this->p_customer_code);
			form_json_msg(E_ORGANIZATION_CREAT_FAILURE,'',lang('e_organization_creat_failure'),array(''));
		}
		//3、5、6 创建本地信息
		$this->load->model('ecology_model', 'ecology');
		$creat_res = $this->ecology->CreatLocalEcologyInfo($res_org,$res_user,$company_staff_data);
		//if($creat_res){//开通账号
			//$this->load->model('account_upload_model', 'upload');
			//$this->upload->saveTask(ACCOUNT_CREATE_UPLOAD,json_encode(array('user_id'=>$res_user,'org_id'=>$res_org)));
		//}
		
		$return_data = array(
			'id'  => $creat_res['id'],
			'pId' => $creat_res['parentId'],
			'name' => $creat_res['name'],
			'userCount' => '0',
			'open' => 'false',
			'nocheck' => 'true',
			'chkDisabled' => 'true',
			'isDisabled' => 'false',
			'isParent' => 'false',
			'isrename' => 'false',
			'isaddnext' => 'false',
			'isaddnext' => 'false',
			'isdel' => 'true',
			'identity' => '0',
		);
		//记录系统日志
		$this->load->library('LogLib','','LogLib');
		$log_in_arr = $this->p_sys_arr;
		$re_id = $this->LogLib ->set_log(array('9','1'),$log_in_arr);
		$re_id = $this->LogLib ->set_log(array('9','2'),$log_in_arr);
		form_json_msg(COMMON_SUCCESS,'',lang('success'),$return_data);
	}
	
	/**
	 * @brief 点击勾选后点击按钮删除生态企业弹窗提醒
	 * @details
	 * -# 删除生态企业
	 * @return null
	 */
	public function deleteEcologyCompany(){
		$this->load->view('ecologycompany/delEcologyCompany.php');
	}
	
	/**
	 *
	 * @brief 删除生态企业
	 * @details
	 * @return null
	 *
	 */
	public function deleteEcology(){
		$org_id = $this->input->post('ecology_id' , TRUE);
		if(!preg_match('/^[\d]+$/',$org_id)){
			form_json_msg(COMMON_PARAM_ERROR,'',lang('error_param'));//参数错误
		}
		$org_info = $this->ums->getOrganizationBrief($org_id);
		log_message('info', __FUNCTION__.'ums->getOrganizationBrief:'." input->\n".var_export($org_info,true).' site_id->'.$this->p_site_id.' customer code->'.$this->p_customer_code);
		if(!$org_info){
			form_json_msg(COMMON_FAILURE,'',lang('e_get_info_error'));//组织数据获取失败或已被删除
			return false;
		}
		if(!$org_info['parentId']){
			form_json_msg(COMMON_FAILURE,'',lang('top_organizations_can_not_be_deleted'));//已经是最上层的公司组织 不能进行删除
			return false;
		}
		$parent_org = explode('-', $org_info['nodeCode']);//获取自己和自己父级组织
		array_shift($parent_org);//去除第一个空值 -1000-1001 => array('','1000','1001')
		$this->load->model('ecology_model', 'ecology');
		//获取父级组织所有管理员userID
		$admin_data_array = $this->ecology->getParentOrgAdminID($parent_org);
		log_message('info', __FUNCTION__.'admin_data_array:'." input->\n".var_export($admin_data_array,true).' site_id->'.$this->p_site_id.' customer code->'.$this->p_customer_code);
		$admin_user_array = getSubByKey($admin_data_array,'userID');
		if(!in_array($this->p_user_id,$admin_user_array)){//如果是上级或本级管理员则拥有权限，反之亦然。
			form_json_msg(COMMON_FAILURE,'',lang('e_permission_denied'));//没有权限
			return false;
		}
		$res = true;//执行结果标示
		$del_orgs = array();//删除的组织id
		//如果不是叶子节点
		if($org_info['childNodeCount']){
			$sub_org_info	=	$this->ums->getOrganization($org_id,$this->ums->scope_subtree,ORG_ECOLOGY_COMPANY);
			$sub_orgs 		= 	getSubByKey($sub_org_info,'id');
			//此处获取的IDs是高->低 删除需反转进行低->高进行
			$sub_orgs = array_reverse($sub_orgs);
			foreach ($sub_orgs as $k=>$v){
				$sub_res = $this->ecology->deleteEcology($v);
				if(!$sub_res){
					//$sub_res = false;
					log_message('error', __FUNCTION__." input->\n".$org_id.' site_id->'.$this->p_site_id.' customer code->'.$this->p_customer_code.'delete error org_id is'.$v);
				}else{
					$del_orgs[] = $v;//加入
				}
			}
		}
		$res = $this->ecology->deleteEcology($org_id);
		$del_orgs[] = $org_id;//加入
		if($res){
			//记录系统日志
			$this->load->library('LogLib','','LogLib');
			$log_in_arr = $this->p_sys_arr;
			$re_id = $this->LogLib ->set_log(array('9','3'),$log_in_arr);
			//TODO 宏亮线程 $del_orgs
			
			form_json_msg(COMMON_SUCCESS,'', lang('success'));//删除成功
		}else{
			log_message('error', __FUNCTION__." input->\n".$org_id.' site_id->'.$this->p_site_id.' customer code->'.$this->p_customer_code);
			form_json_msg(COMMON_FAILURE,'', lang('failure'));//删除失败
		}
	}
}