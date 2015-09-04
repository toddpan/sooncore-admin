<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @abstract 	分公司控制器，负责分公司的创建、删除、列表等相关操作
 * @author 		Bai Xue <xue.bai_2@quanshi.com>
 * @copyright 	Copyright (c) UC
 * @version 	v1.0
 */
class Filiale extends Web_Controller {
	
	public $error = ''; // 错误提示信息
	
	/**
	 * 构造方法
	 */
	public function __construct(){
		parent::__construct();
		
		// 载入分公司中文提示语言包
		$this->lang->load('filiale', 'chinese');
		// 载入UMS类库
		$this->load->library('UmsLib', '', 'ums');
	}
	
	/**
	 * 返回错误提示信息
	 * @return string
	 */
	public function getError(){
		return $this->error;
	}
	
	/**
	 * 显示分公司列表
	 */
	public function get_filiale_list() {
		// 获得BOSS传过来的客户编码和OP的id
		//$customerCode 	= $this->input->get_post('customerCode', true);
		$customerCode 	= '007005';
		$op_id 			= $this->input->get_post('op_id', true);
		log_message('info', 'Into method get_filiale_list  input ---> customerCode = ' . $customerCode . ' op_id = ' . $op_id);
		
		// 载入管理员模型
		$this->load->model('uc_user_admin_model');
		
		// 初始化结果数组
		$re_data = array();
		
 		// 根据客户编码从UMS中获得集团公司的信息（通过客户编码查询组织接口）
		$cor_info_json = $this->ums->getOrganizeByCustomerCode($customerCode);
		$cor_info_arr = json_decode($cor_info_json, true);
		
		if(!isemptyArray($cor_info_arr)){
			foreach($cor_info_arr as $cor_info){
				
				// 根据集团公司的Id从UMS获取下级子组织（查询子组织接口）
				$filiale_info_arr = $this->ums->getOrganization($cor_info['id'], $scope = 'subtree', $type = '5');
				if(!isemptyArray($filiale_info_arr)){
					foreach($filiale_info_arr as $filiale_info){
						$temp 					= array();
						$temp['org_id'] 		= $filiale_info['id'];		// 分公司的组织Id
						$temp['filiale_name'] 	= $filiale_info['name'];	// 分公司名称
						$temp['area'] 			= $filiale_info['countryCode'] . ' ' . $filiale_info['areaCode'];	// 分公司所在地
						$temp['parent_name']	= ''; 					// 上级企业名称
						$temp['manager_name'] 	= '';					// 管理员姓名
						
						// 根据分公司id和type在uc_user_admin表中获得管理员姓名
						$where_arr = array(
								'orgID' => $filiale_info['id'],
								'type' 	=> ADMIN_SUB_COMPANY_MANAGER
						);
						$admin_info = $this->uc_user_admin_model->get_admin_info($where_arr);
						if(!empty($admin_info)){
							$temp['manager_name']	= isset($admin_info['display_name'])?$admin_info['display_name']:'';
						}
						
						// 获得上级企业名称
						if($filiale_info['parentId'] == $cor_info['id']){//如果当前分公司是总公司的直接子公司
							$temp['parent_name'] = $cor_info['name'];
						}else{// 当前分公司不是总公司的直接子公司（例如是总公司的孙子公司）
							 $parent_info = $this->ums->getOrganizationById($filiale_info['parentId']);
							 if(!empty($parent_info)){
							 	$temp['parent_name'] = isset($parent_info['name'])?$parent_info['name']:'';;
							 } 
						}
						
						$re_data[] = $temp;
					}
				}
			}
		}		
		
		// 将分公司Id、分公司名称、分公司所在地、上级企业和管理员姓名传递到页面上
		$this->assign('customerCode', $customerCode);
		$this->assign('filiale_info', $re_data);
		$this->display('filiale/filiale_list.tpl');
	}
	
	/**
	 * 显示创建分公司页面
	 */
	public function create_filial_page() {
		// 获得从客户端浏览器端提交的客户编码
		$customerCode = $this->input->get_post('customerCode', true);
		log_message('info', 'Into method create_filiale_page input ---> customerCode = ' . $customerCode);
		
		// 初始化结果数组
		$re_data = array();
		
		// 从UMS获取所有集团、子集团和子公司的信息列表
		$cor_info_json = $this->ums->getOrganizeByCustomerCode($customerCode);
		$cor_info_arr = json_decode($cor_info_json, true);
		
		foreach($cor_info_arr as $cor_info){
			$temp  = array();
			
			// 通过总公司的站点url获取总公司的站点Id
			$site_info = $this->ums->getSiteInfoByUrl($cor_info['siturl']);
			if(!isemptyArray($site_info)){
				$temp['site_id'] = $site_info['id'];
			}
			
			// 总公司的组织Id和公司名称
			$temp['id'] = $cor_info['id'];
			$temp['name'] = $cor_info['name'];
			
			// 将总公司的站点Id、组织Id和公司名称存入结果数组
			$re_data[] = $temp;
			
			// 通过总公司的组织Id获得分公司信息
			$filiale_infos_arr = $this->ums->getOrganization($cor_info['id'], $scope = 'subtree', $type = '5');
			if(!isemptyArray($filiale_infos_arr)){
				foreach($filiale_infos_arr as $filiale_infos){
					// 通过分公司的站点url获取分公司的站点Id
					$filiale_info = $this->ums->getSiteInfoByUrl($filiale_infos['siturl']);
					if(!isemptyArray($site_info)){
						$temp['site_id'] = $filiale_info['id'];
					}
					
					// 分公司的组织Id和公司名称
					$temp['id'] = $filiale_infos['id'];
					$temp['name'] = $filiale_infos['name'];
						
					// 将分公司的站点Id、组织Id和公司名称存入结果数组
					$re_data[] = $temp;
				}
			}
		}
		
		// 获得国家信息
		include_once APPPATH . 'libraries'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'Country_code.php';
		$country_obj = new Country_code();
		$country_name_arr = $country_obj->get_country();
		
		// 将以上列表显示在页面上
		$this->assign('customerCode', $customerCode);
		$this->assign('country_name_arr', $country_name_arr);
		$this->assign('cor_info_arr', $re_data);
		$this->display('filiale/create_filiale.tpl');
	}
	
	/**
	 * 验证分公司信息
	 */
	public function valid_filiale(){
		// 获取表单提交的分公司信息
		$parent_site_id = $this->input->post('parent_site_id', true); // 上级企业的siteId
		$parent_org_id	= $this->input->post('parent_org_id', true); // 上级企业的组织Id
		$country 		= $this->input->post('country', true); 		// 国家
		$cor_site_url 	= $this->input->post('cor_site_url', true); // 分公司的域名
		$site_url 		= $this->input->post('site_url', true); 	// 全时给分公司分配的站点url
		$province 		= $this->input->post('province', true); 	// 省份
		$city 			= $this->input->post('city', true); 		// 乡镇市区
		$address 		= $this->input->post('address', true); 		// 详细地址
		$filiale_name 	= $this->input->post('filiale_name', true); // 分公司名称
		$filiale_type 	= $this->input->post('filiale_type', true); // 公司形态
		$manage_type 	= $this->input->post('manage_type', true); 	// 管理方式
		$is_ldap 		= $this->input->post('is_ldap', true); 		// 员工创建方式
		
		$filiale_info = array(
				'parent_site_id' 	=> $parent_site_id,
				'parent_org_id'		=> $parent_org_id,
				'country' 			=> $country,
				'cor_site_url' 		=> $cor_site_url,
				'site_url' 			=> $site_url,
				'province' 			=> $province,
				'city' 				=> $city,
				'address' 			=> $address,
				'filiale_name' 		=> $filiale_name,
				'filiale_type' 		=> $filiale_type,
				'manage_type' 		=> $manage_type,
				'is_ldap' 			=> $is_ldap
		);
		
		$res = $this->valid_filiale_info($filiale_info);
		if($res == false){
			$error = $this->getError();
			form_json_msg($error[0], $error[1], $error[2], array());
		}
		
		form_json_msg(COMMON_SUCCESS, '', $this->lang->line('success'), array());
	}
	
	
	/**
	 * 验证分公司信息
	 * @param array $data
	 */
	public function valid_filiale_info($data){
		// 验证子公司名称:1、不能为空；2、不超过80个汉字
		if(empty($data['filiale_name'])){
			$this->error = array(FILIALENAME_IS_EMPTY, 'filiale_name', $this->lang->line('filiale_name_is_empty')); // 子公司的名称不能为空
			return false;
		}
		if(strlen($data['filiale_name']) > 80){
			$this->error = array(FILIALENAME_LT_EIGHTY, 'filiale_name', $this->lang->line('filiale_name_is_empty')); // 子公司的名称不能超过80个汉字
			return false;
		}
		
		// 验证上级企业的组织Id和站点Id
		$parent_org_info = $this->ums->getOrganizationById($data['parent_org_id']);
		if(empty($parent_org_info)){
			$this->error = array(PARENT_ORG_NOT_EXIST, 'parent_org_id', $this->lang->line('parent_org_not_exist'));// 上级企业不存在
			return false;
		}
		$parent_site_info = $this->ums->getSiteInfoById($data['parent_site_id']);
		if(empty($parent_org_info)){
			$this->error = array(PARENT_ORG_NOT_EXIST, 'parent_site_id', $this->lang->line('parent_org_not_exist'));// 上级企业不存在
			return false;
		}
		
		// 验证分公司的域名
		if(empty($data['cor_site_url'])){
			$this->error = array(E_COMPANY_NOT_NULL,'cor_site_url',$this->lang->line('cor_site_url_not_null'));// 分公司的域名不能为空
			return false;
		}
		
		// 验证全时给分公司分配的站点url
		if(empty($data['site_url'])){
			$this->error = array(SITE_URL_IS_WRONG,'site_url',$this->lang->line('site_url_not_null'));// 全时站点不能为空
			return false;
		}
		
		return true;
	}
	
	/**
	 * 验证管理员信息
	 * @param array $data 管理员信息
	 */
	public function valid_manager_info($data){
		// 验证管理员账号：1、格式是否正确；2、在UMS中是否存在
		if(!preg_match("/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/u", $data['login_name'])){
			$this->error = array(LOGINNAME_IS_WRONG, 'login_name', $this->lang->line('loginname_is_wrong')); // 管理员账号格式不正确
			return false;
		}
		$res = $this->ums->getUserByLoginName($data['login_name']);
		if(!empty($res)){
			$this->error = array(LOGINNAME_IS_ALREADY_EXIST, 'login_name', $this->lang->line('loginname_is_already_exist')); // 管理员账号已存在
			return false;
		}
		
		// 验证手机号码
		if(!preg_match("/\d{5,20}/", $data['telephone'])){
			$this->error = array(TELEPHONE_IS_WRONG, 'telephone', $this->lang->line('telephone_is_wrong')); // 手机号码不正确
			return false;
		}
		
		// 验证电子邮箱
		if(!preg_match("/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/u", $data['email'])){
			$this->error = array(EMAIL_IS_WRONG, 'email', $this->lang->line('email_is_wrong')); // 电子邮箱格式不正确
			return false;
		}
		
		return true;
	}
	
	/**
	 * 创建站点、分公司以及管理员
	 */
	public function create_total_info() {
		// 获取表单提交的分公司信息
		$parent_site_id = $this->input->post('parent_site_id', true); // 上级企业的siteId
		$parent_org_id	= $this->input->post('parent_org_id', true); // 上级企业的组织Id
		$country 		= $this->input->post('country', true); 		// 国家
		$cor_site_url 	= $this->input->post('cor_site_url', true); // 分公司的域名
		$site_url 		= $this->input->post('site_url', true); 	// 全时给分公司分配的站点url
		$province 		= $this->input->post('province', true); 	// 省份
		$city 			= $this->input->post('city', true); 		// 乡镇市区
		$address 		= $this->input->post('address', true); 		// 详细地址
		$filiale_name 	= $this->input->post('filiale_name', true); // 分公司名称
		// $filiale_type 	= $this->input->post('filiale_type', true); // 公司形态
		$manage_type 	= $this->input->post('manage_type', true); 	// 管理方式
		$is_ldap 		= $this->input->post('is_ldap', true); 		// 员工创建方式
		
		// 获取表单提交的管理员信息
		$first_name 	= $this->input->post('first_name', true);	// 姓氏
		$last_name 		= $this->input->post('last_name', true);	// 名字
		$display_name	= $this->input->post('display_name', true);	// 姓名
		$country_code 	= $this->input->post('country_code', true);	// 国码
		$telephone 		= $this->input->post('telephone', true);	// 手机号码
		$city_code 		= $this->input->post('city_code', true);	// 区号
		$phone 			= $this->input->post('phone', true);		// 固定电话
		$e_mail 		= $this->input->post('email', true);		// 电子邮箱
		$departmet_name = $this->input->post('departmet_name', true);// 部门名称
		$position 		= $this->input->post('position', true);		// 职位
		$login_name 	= $this->input->post('login_name', true);	// 用户名
		
		// 获得客户编码
		$customerCode = $this->input->get_post('customerCode', true);
		
		// 打印log
		$filiale_info = array(
				'parent_site_id' 	=> $parent_site_id,
				'parent_org_id'		=> $parent_org_id,
				'country' 			=> $country,
				'cor_site_url' 		=> $cor_site_url,
				'site_url' 			=> $site_url,
				'province' 			=> $province,
				'city' 				=> $city,
				'address' 			=> $address,
				'filiale_name' 		=> $filiale_name,
				//'filiale_type' 		=> $filiale_type,
				'manage_type' 		=> $manage_type,
				'is_ldap' 			=> $is_ldap
		);
		$manager_info = array(
				'first_name' 		=> $first_name,
				'last_name' 		=> $last_name,
				'display_name' 		=> $display_name,
				'country_code' 		=> $country_code,
				'telephone' 		=> $telephone,
				'city_code' 		=> $city_code,
				'phone' 			=> $phone,
				'email' 			=> $e_mail,
				'department_name' 	=> $departmet_name,
				'position' 			=> $position,
				'login_name' 		=> $login_name
		);
		$input_data = array(
				'parent_site_id' 	=> $parent_site_id,
				'parent_org_id'		=> $parent_org_id,
				'country' 			=> $country,
				'cor_site_url' 		=> $cor_site_url,
				'site_url' 			=> $site_url,
				'province' 			=> $province,
				'city' 				=> $city,
				'address' 			=> $address,
				'filiale_name' 		=> $filiale_name,
				//'filiale_type' 		=> $filiale_type,
				'manage_type' 		=> $manage_type,
				'is_ldap' 			=> $is_ldap,
				'first_name' 		=> $first_name,
				'last_name' 		=> $last_name,
				'display_name' 		=> $display_name,
				'country_code' 		=> $country_code,
				'telephone' 		=> $telephone,
				'city_code' 		=> $city_code,
				'phone' 			=> $phone,
				'email' 			=> $e_mail,
				'department_name' 	=> $departmet_name,
				'position' 			=> $position,
				'login_name' 		=> $login_name,
				'customerCode' 		=> $customerCode
		);
		log_message('info', 'Into method create_total_info ');

		// 验证分公司信息
		$res = $this->valid_filiale_info($filiale_info);
		if($res == false){
			$error = $this->getError();
			form_json_msg($error[0], $error[1], $error[2], array());// 参数错误
		}
		
		// 验证管理员信息
		$res = $this->valid_manager_info($manager_info);
		if($res == false){
			$error = $this->getError();
			form_json_msg($error[0], $error[1], $error[2], array());
			//form_json_msg(CREATE_MANAGER_FAIL, '', $this->lang->line('create_admin_fail'), array()); // 参数错误
		}
		
		// 创建分公司
		$cor_info = $this->create_coporation($input_data);
		if($cor_info == false){
			$error = $this->getError();
			form_json_msg($error[0], $error[1], $error[2], array());
			//form_json_msg(CREATE_FILIALE_FAIL, '', $this->lang->line('create_filiale_fail'), array()); // 创建分公司失败
		}
		
		// 创建管理员
		$res = $this->create_admin($input_data, $cor_info);
		if($res == false){
			$error = $this->getError();
			form_json_msg($error[0], $error[1], $error[2], array());
			//form_json_msg(CREATE_MANAGER_FAIL, '', $this->lang->line('create_admin_fail'), array()); // 创建管理员失败
		}
		
		// 创建站点、分公司以及管理员成功
		form_json_msg(COMMON_SUCCESS, '', $this->lang->line('success'), array());
	}
	
	/**
	 * 创建站点、分公司
	 */
	public function create_coporation($data) {
		log_message('info', 'Into method create_coporation');
		
		// 载入BOSS类库
		$this->load->library('BossLib', '', 'boss');
		// 载入客户模型
		$this->load->model('uc_customer_model');
		// 载入站点模型
		$this->load->model('uc_site_model');
		// 载入地区模型
		$this->load->model('uc_area_model');		
		
		// 通过全时给分公司分配的站点url在UMS中创建站点
		$ums_site_info = array(
 			'url' => $data['site_url'],
			'aliasUrl' => $data['site_url'],
			'siteStatus' => 1,
			'createTime' => time(),
			'statusModifyTime' => time(),
			'customerCode' => $data['customerCode'],
			'siteType' => UMS_USER_SITE
		);
		$site_id = $this->ums->createSite($ums_site_info);
		if($site_id == false){
			$this->error = array(UMS_CREATE_SITE_FAIL, '', $this->lang->line('ums_create_site_fail'));// 在UMS创建站点失败
			return false;
		}
		
		// 根据客户编码和站点Id（此时为parent_site_id）在uc_customer表中获得合同Id
		$where_arr = array(
			'customerCode' => $data['customerCode'],
			'siteId' => $data['parent_site_id']
		);
		$costomer_info = $this->uc_customer_model->getContractid($where_arr);
		if(empty($costomer_info)){
			$this->error = array(UC_GET_CONTRACTID_FAIL, '', $this->lang->line('uc_get_contractId_fail'));// 获取合同Id失败
			return false;
		}
		$contractId = isset($costomer_info['contractId']) ? $costomer_info['contractId'] : '';
		
		// 根据parent_site_id在uc_site表中获取value
		$parent_site_info = $this->uc_site_model->getInfosBySiteId($data['parent_site_id']);
		$parent_site_value = isset($parent_site_info['value']) ? $parent_site_info['value'] : '';
		
		log_message('info', 'parent_site_value'.$parent_site_value);
		
		// 在uc_site表中创建站点
		$uc_site_info = array(
			'siteID' 		=> $site_id,
			'contractId' 	=> $contractId,
			'domain' 		=> $data['site_url'],
			'companyType' 	=> $data['manage_type'],
			'isLDAP' 		=> $data['is_ldap'],
			'customerCode' 	=> $data['customerCode'],
			'value' 		=> $parent_site_value,
			'createTime' 	=> time()
		);
		$res = $this->uc_site_model->createSite($uc_site_info);
		if($res == false){
			$this->error = array(UC_CREATE_SITE_FAIL, '', $this->lang->line('uc_create_site_fail'));// 在uc创建站点失败
			return false;
		}
		
		// 在UMS创建组织
		$ums_organization_info = array(
			'name' 			=> $data['filiale_name'],
			'siturl' 		=> $data['site_url'],
			'parentId' 		=> $data['parent_org_id'],
			'customercode' 	=> $data['customerCode'],
			'type' 			=> ORG_SUB_COMPONY, // 公司类型：分公司
			'countryCode' 	=> $data['country'],
			'areaCode' 		=> $data['city']
		);
		$org_id = $this->ums->createOrganization($ums_organization_info);
		if($org_id == false){
			$this->error = array(UMS_CREATE_ORGANIZATION_FAIL, '', $this->lang->line('ums_create_organization_fail'));// 在ums创建组织失败
			return false;
		}
		
		// 根据parent_site_id从uc_customer表中获得value
		$where_arr = array(
			'siteId' 		=> $data['parent_site_id'],
			'customerCode' 	=> $data['customerCode']
		);
		$parent_customer_info = $this->uc_customer_model->getContractid($where_arr);
		$parent_customer_value = isset($parent_customer_info['value']) ? $parent_customer_info['value'] : '';
		
		// 向uc_customer表添加记录
		$uc_customer_info = array(
			'siteId' 		=> $site_id,
			'customerCode' 	=> $data['customerCode'],
			'contractId' 	=> $contractId,
			'name' 			=> $data['filiale_name'],
			'value' 		=> $parent_customer_value,
			'createTime' 	=> time()
		);
		$res = $this->uc_customer_model->add_customer($uc_customer_info);
		if($res == false){
			$this->error = array(UC_CREATE_CUSTOMER_FAIL, '', $this->lang->line('uc_create_customer_fail'));// 在uc创建客户失败
			return false;
		}
		
		// 向uc_area表中添加记录
		$uc_area_info = array(
			'customerCode' 	=> $data['customerCode'],
			'siteID' 		=> $site_id,
			'country'	 	=> $data['country'],
			'area' 			=> $data['city'],
			'address' 		=> $data['address']
		);
		$res = $this->uc_area_model->add_area($uc_area_info);
		if($res == false){
			$this->error = array(UC_CREATE_AREA_FAIL, '', $this->lang->line('uc_create_area_fail'));// 在uc创建地址失败
			return false;
		}
		
		// 创建分公司模板 （调BOSS接口）
		$components = array(
			'templateUUID' 	=> $data['site_url'],
			'contractId' 	=> $contractId,
			'components' 	=> json_decode($parent_site_value)
		);
		$res = $this->boss->batchCreateContractComponentProps($components);
		if(!$res){
			$this->error = array(CREATE_CONTRACT_FAIL, '', $this->lang->line('create_contract_fail'));// 创建分公司模板失败
			return false;
		}
		
		// 分配集群
		include_once APPPATH.'libraries'.DIRECTORY_SEPARATOR.'Account'.DIRECTORY_SEPARATOR.'Common'.DIRECTORY_SEPARATOR.'Cluster.php';
		$cluster = new Cluster($data['customerCode'], FILIALE_USER_AMOUNT);
		if(!$cluster->isAllocated($data['customerCode'], $site_id)){//判断该客户是否分配过集群
			$cluster_info = $cluster->getCluster();//获取一个集群
			if(!$cluster_info){
				$this->error = array(CREATE_CLUSTER_FAIL, '', $this->lang->line('create_contract_fail'));// 分配集群失败
				return false;
			}
			
			// 将获取到的集群与客户的关联关系写入到数据库、更新相应集群中的用户量
			list($ret, $msg) = $cluster->allocate($data['customerCode'], $site_id, $data['site_url'], FILIALE_USER_AMOUNT, $cluster_info['clusterID']);
			if(!$ret){
				$this->error = array(SAVE_CLUSTER_FAIL, '', $this->lang->line('save_cluster_fail'));// 保存集群失败
				return false;
			}
		}
		
		// 写入portal
// 		$this->load->library('PortalLib', '', 'portal');
// 		if(!$this->portal->getRule($data['site_url'])){
// 			list($is_ok, $msg) = $this->portal->addSkipRule($data['site_url'], $cluster_info['url']);
// 			if(!$is_ok){
// 				$this->error = array(SAVE_PORTAL_FAIL, '', $this->lang->line('save_portal_fail'));// 写入portal失败
// 				return false;
// 			}
// 		}
		
		return array('site_id' => $site_id, 'org_id' => $org_id);
	}
	
	/**
	 * 显示创建管理员页面
	 */
	public function show_create_manager_page(){
		// 获得国码：如+86等
		include_once APPPATH . 'libraries'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'Country_code.php';
		$country_obj = new Country_code();
		$country_code_arr = $country_obj->getCountryCode();
		var_dump($country_code_arr);
		
		// 将数据传到页面上
		$this->assign('country_code_arr', $country_code_arr);
		$this->display('filiale/create_manager.tpl');
	}
	
	/**
	 * 创建管理员
	 */
	public function create_admin($data, $cor_info) {
		log_message('info', 'Into method create_admin');
		
		// 向UMS创建用户
		$others_info = array(
			'firstName' 		=> $data['first_name'],
			'lastName' 			=> $data['last_name'],
			'email' 			=> $data['email'],
			'userstatus' 		=> UMS_USER_NOT_OPEN,
			'sex' 				=> NOT_SET, 	//未设置
			'mobileNumber' 		=> $data['telephone'],
			'organizationId' 	=> $cor_info['org_id'],
			'register' 			=> true,
			'registertime' 		=> time(),
			'position' 			=> $data['position'],
			'displayName' 		=> $data['display_name'],
			'lastUpdateTime' 	=> time(),
			'officePhone' 		=> $data['phone'],
			'createTime' 		=> time()
			
		);
		$user_id = $this->ums->createUser($data['login_name'], $others_info);
		if($user_id == false){
			$this->error = array(UMS_CREATE_USER_FAIL, '', $this->lang->line('ums_create_user_fail'));// 在UMS创建用户失败
			return false;
		}
		
		// 根据上级企业的站点Id获取上级系统管理员的账户Id
		$this->load->model('uc_user_admin_model');
		$parent_admin_info = $this->uc_user_admin_model->get_account_id($data['parent_site_id']);
		$account_id = isset($parent_admin_info['accountId'])? $parent_admin_info['accountId'] : '';
		
		// uc创建user
		$this->load->model('uc_user_model');
		$uc_user_info = array(
			'userID' 		=> $user_id,
			'siteId' 		=> $cor_info['site_id'],
			'customerCode' 	=> $data['customerCode'],
			'accountId' 	=> $account_id,
			'status' 		=> UC_USER_STATUS_UNUSED,
			'create_time' 	=> time(),
			'update_time' 	=> time()	
		);
		$res = $this->uc_user_model->createUser($uc_user_info);
		if(!$res){
			$this->error = array(UC_CREATE_USER_FAIL, '', $this->lang->line('uc_create_user_fail'));// 在UC创建用户失败
			return false;
		}
		
		// 向UC创建管理员（type：分公司管理员，role不用管）
		$this->load->model('uc_user_admin_model');
		$user_admin_info = array(
			'userID' 		=> $user_id,
			'siteID' 		=> $cor_info['site_id'],
			'orgID' 		=> $cor_info['org_id'],
			'isLDAP' 		=> $data['is_ldap'],
			'type' 			=> ADMIN_SUB_COMPANY_MANAGER,
			'createTime' 	=> time(),
			'display_name' 	=> $data['display_name'],
			'login_name' 	=> $data['login_name'],
			'mobile_number' => $data['telephone']
		);
		$res = $this->uc_user_admin_model->create_admin($user_admin_info);
		if(!$res){
			$this->error = array(UC_CREATE_ADMIN_FAIL, '', $this->lang->line('uc_create_admin_fail'));// 在UC创建管理员失败
			return false;
		}
		
		$this->load->model('uc_user_admin_role_model');
		$admin_role_info = array(
			'user_id' => $user_id,
			'state' => ADMIN_OPEN,
			'create_time' => time()
		);
		$res = $this->uc_user_admin_role_model->saveManager($admin_role_info);
		if(!$res){
			$this->error = array(UC_CREATE_ADMIN_FAIL, '', $this->lang->line('uc_create_admin_fail'));// 在UC创建管理员失败
			return false;
		}
		
		// 将账号信息保存到线程表做开通
		$user_info = array(
			'lastname' 		=> $data['display_name'],
			'firstname' 	=> $data['first_name'],
			'loginname' 	=> $data['login_name'],
			'open' 			=> true,
			'sex' 			=> NOT_SET,
			'account' 		=> $account_id,	
			'position' 		=> $data['position'],
			'mobile' 		=> $data['telephone'],
			'officeaddress' => '',
			'country' 		=> '',
			'department1' 	=> $data['filiale_name'],
			'auth'			=> 1
		);
		$account_info = array(
				'customer_code' => $data['customerCode'],
				'site_id' 		=> $cor_info['site_id'],
				'org_id' 		=> $cor_info['org_id'],
				'user_info' 	=> $user_info
		);
		$this->load->model('account_upload_task_model');
		$res = $this->account_upload_task_model->saveTask(ACCOUNT_CREATE_UPLOAD, json_encode($account_info));
		if($res == false){
			$this->error = array(UC_CREATE_ADMIN_FAIL, '', $this->lang->line('save_process_fail')); // 保存线程失败
			return false; 
		}
		
		return true;
	}
	
	/**
	 * 显示变更管理员的页面
	 */
	public function show_update_admin_page(){
		// 获得国码：如+86等
		include_once APPPATH . 'libraries'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'Country_code.php';
		$country_obj = new Country_code();
		$country_code_arr = $country_obj->getCountryCode();
		var_dump($country_code_arr);
		
		// 将数据传到页面上
		$this->assign('country_code_arr', $country_code_arr);
		$this->display('filiale/change_manager.tpl');
	}
	
	/**
	 * 变更管理员
	 * 
	 * 1、从已有用户中选一个做管理员
	 *	    （1）判断从表单获取的管理员账号是不是存在？是不是属于当前站点？
	 *	    （2）分别在uc_user_admin和uc_user_admin_role表中修改管理员信息
	 *
	 *	2、重新创建一个管理员，接下来流程和创建管理员一样
	 *	   （1）在UMS创建用户信息
	 *	   （2）在UC创建用户信息
	 *	   （3）分别在uc_user_admin和uc_user_admin_role表中保存管理员信息
	 *	   （4）禁用先前管理员
	 */
	public function update_admin() {
		// 获得表单提交的数据
		$org_id 			= $this->input->post('org_id', true); 		// 组织Id
		$create_type 		= $this->input->post('create_type', true); 	// 创建类型：1、从已有用户中选一个；2、重新创建
		$manager_info_json 	= $this->input->post('manager_info', true); // 管理员信息:客户编码也保存进来
		
		// 将管理员信息转化为数组
		$$manager_info = json_decode($manager_info_json, true);
		
		// 判断创建类型:1、从已有用户中选一个；2、重新创建
		if($create_type != UPDATE_SUB_ADMIN_CREATE && $create_type != UPDATE_SUB_ADMIN_CHOOSE){
			form_json_msg(ILLEGAL_OPERATE_TYPE, '', $this->lang->line('illegal_operate'), array());// 非法操作：操作类型不正确
		}
		
		// 判断组织在UMS是否存在
		$ums_org_info = $this->ums->getOrganizationById($org_id);
		if($ums_org_info == false){
			form_json_msg(ORG_NOT_IN_UMS, '', $this->lang->line('illegal_operate'), array());// 非法操作:该组织在UMS不存在
		}
		// 获得站点url
		$siturl = isset($ums_org_info['siturl'])?$ums_org_info['siturl']:'';
		
		// 通过站点url获得站点Id
		$ums_site_info = $this->ums->getSiteInfoByUrl($siturl);
		if($ums_site_info == false){
			form_json_msg(SITE_NOT_IN_UMS, '', $this->lang->line('illegal_operate'), array());// 非法操作:该站点在UMS不存在
		}
		$site_id = isset($ums_site_info['id'])?$ums_site_info['id']:'';
		
		// 判断站点在UC中是否存在：存在，则获取isLdap值，不存在，则报错
		$this->load->model('uc_site_model');
		$uc_site_info = $this->uc_site_model->getInfosBySiteId($site_id);
		if(empty($uc_site_info)){
			form_json_msg(SITE_NOT_IN_UC, '', $this->lang->line('illegal_operate'), array());// 非法操作:该站点在UC中不存在
		}
		$is_ldap = isset($uc_site_info['isLDAP'])?$uc_site_info['isLDAP']:'';
		
		// 根据org_id、系统管理员角色和state从uc_user_admin和uc_user_admin_role表中获得当前组织的管理员信息（Id）
		$this->load->model('uc_user_admin_role_model');
		$admin_info = $this->uc_user_admin_role_model->get_admin_by_org_id($org_id);
		if(empty($admin_info)){
			form_json_msg(OLD_ADMIN_NOT_EXIST, '', $this->lang->line('illegal_operate'), array());// 非法操作：旧的管理员信息不存在
		}
		$old_admin_id 		= isset($admin_info['id'])?$admin_info['id']:'';
		$old_admin_user_id 	= isset($admin_info['user_id'])?$admin_info['user_id']:'';
		
		// 根据不同的创建类型更新管理员
		if($create_type == UPDATE_SUB_ADMIN_CHOOSE){// 创建类型：1、从已有用户中选一个；
			// 从表单提交的管理员信息中取出用户名
			$login_name = isset($manager_info['login_name'])?$manager_info['login_name']:'';
			
			// 判断表单提交的用户名是否为空
			if(is_empty($login_name)){
				form_json_msg(LOGINNAME_IS_WRONG, '', $this->lang->line('loginname_is_wrong'), array());// 管理员的用户名不能为空
			}
			
			// 判断表单提交的用户名在UMS中是否存在
			$ums_user_info = $this->ums->getUserByLoginName($login_name);
			if(empty($user_info)){
				form_json_msg(LOGINNAME_IS_NOT_EXIST, '', $this->lang->line('loginname_is_not_exist'), array());// 您填写的管理员用户名不存在
			}
			
			// 判断表单提交的用户名是否已停用
			$user_state = $ums_user_info['userstatus'];
			if($user_state == UMS_USER_STATUS_CLOSE){
				form_json_msg(ADMIN_IS_CLOSE, '', $this->lang->line('loginname_is_close'), array());// 您填写的管理员用户名已停用
			}
			
			// 判断表单提交的用户名是否属于当前站点
			$user_id = $ums_user_info['id'];
			$this->load->model('uc_user_model');
			$uc_user_info = $this->uc_user_model->getUserInfo($user_id);
			$uc_site_id = isset($uc_user_info['siteId'])?$uc_user_info['siteId']:'';
			if($uc_site_id != $site_id){
				form_json_msg(ADMIN_IS_NOT_IN_SITE, '', $this->lang->line('loginname_is_not_in_site'), array());// 您填写的管理员用户名不属于当前站点
			}
			
			// 更新当前站点的管理员（给uc_user_admin表加一条记录，在uc_user_admin_role表根据Id修改记录）
			$this->load->model('uc_user_admin_model');
			$insert_admin_info = array(
				'userID' 			=> $user_id,
				'siteID' 			=> $site_id,
				'orgID' 			=> $org_id,
				'isLDAP' 			=> $is_ldap,
				'billingcode' 		=> isset($uc_user_info['billingcode'])?$uc_user_info['billingcode']:'',
				'hostpasscode' 		=> isset($uc_user_info['hostpasscode'])?$uc_user_info['hostpasscode']:'',
				'guestpasscode' 	=> isset($uc_user_info['guestpasscode'])?$uc_user_info['guestpasscode']:'',
				'accountId' 		=> isset($uc_user_info['accountId'])?$uc_user_info['accountId']:'',
				'departmentID' 		=> $org_id,
				'type' 				=> ADMIN_SUB_COMPANY_MANAGER,
				'state' 			=> ADMIN_OPEN,
				'last_login_time' 	=> isset($ums_user_info['lastlogintime'])?$ums_user_info['lastlogintime']:'',
				'createTime' 		=> isset($uc_user_info['create_time'])?$uc_user_info['create_time']:'',
				'display_name' 		=> isset($ums_user_info['displayName'])?$ums_user_info['displayName']:'',
				'login_name' 		=> $login_name,
				'mobile_number' 	=> isset($ums_user_info['mobileNumber'])?$ums_user_info['mobileNumber']:''
			);
			$res = $this->uc_user_admin_model->create_admin($insert_admin_info);
			if($res == false){
				form_json_msg(UPDATE_ADMIN_FAIL, '', $this->lang->line('update_admin_fail'), array());// 更新管理员失败
			}
			$update_admin_data = array(
				'user_id' => $user_id
			);
			$where_arr = array(
				'id' => $old_admin_id
			);
			$res = $this->uc_user_admin_role_model->update_admin_info($update_admin_data, $where_arr);
			if($res == false){
				form_json_msg(UPDATE_ADMIN_FAIL, '', $this->lang->line('update_admin_fail'), array());// 更新管理员失败
			}
		}else if($create_type == UPDATE_SUB_ADMIN_CREATE){// 创建类型：2、重新创建
			// 验证表单提交的管理员信息
			$res = $this->valid_manager_info($manager_info);
			if($res == false){
				$error = $this->getError();
				form_json_msg($error[0], $error[1], $error[2], array()); // 参数错误
			}
			
			// 将is_ldap保存到管理员信息中，以便创建管理员
			$manager_info['is_ladap'] = $is_ldap;
			
			// 重新创建管理员
			$res = $this->create_admin($manager_info, array('site_id' => $site_id, 'org_id' => $org_id));
			if($res == false){
				$error = $this->getError();
				form_json_msg($error[0], $error[1], $error[2], array()); // 创建管理员失败
			}
			
			// 禁用旧的管理员
			$update_admin_data = array(
					'state' => ADMIN_CLOSE
			);
			$where_arr = array(
				'id' => $old_admin_id,
				'user_id' => $old_admin_user_id
			);
			$res = $this->uc_user_admin_role_model->update_admin_info($update_admin_data, $where_arr);
			if($res == false){
				form_json_msg(UPDATE_ADMIN_FAIL, '', $this->lang->line('update_admin_fail'), array());// 更新管理员失败
			}
		}
		
		form_json_msg(COMMON_SUCCESS, '', $this->lang->line('success'), array());// 更新管理员成功
	}
	
	/**
	 * 删除分公司
	 * 1、调用宏亮接口删除分公司下的所有用户
	 * 2、删除分公司组织
	 */
	public function del_filiale(){
		$org_id  = $this->input->post('org_id', true);
		log_message('info', 'Into method del_filiale input -> $org_id = ' . $org_id);
		
		// 调UMS接口：通过组织Id查询组织信息，获得组织siturl
		$ums_org_info 	= $this->ums->getOrganizationById($org_id);
		$siturl 		= isset($ums_org_info['siturl']) ? $ums_org_info['siturl'] : '';
		if($ums_org_info == false || empty($siturl)){
			form_json_msg(UMS_ORG_INFO_WRONG, '', $this->lang->line('illegal_operate'), array()); // 非法操作：组织信息不存在
		}
		
		// 调UMS接口：通过组织siturl查询站点信息，获得站点Id
		$ums_site_info 	= $this->ums->getSiteInfoByUrl($siturl);
		$site_id 		= isset($ums_site_info['id']) ? $ums_site_info['id'] : '';
		if($ums_site_info == false || empty($site_id)){
			form_json_msg(UMS_SITE_INFO_FAIL, '', $this->lang->line('illegal_operate'), array()); // 非法操作：站点不存在
		}
		
		// 通过站点Id在uc_user表中获得当前站点下的用户Id
		$this->load->model('uc_user_model');
		$uc_user_ids = $this->uc_user_model->get_userids_by_siteid($site_id);
		if(empty($uc_user_ids)){
			form_json_msg(GET_UC_USER_INFO_FAIL, '', $this->lang->line('illegal_operate'), array()); // 非法操作：获取用户列表失败
		}
		
		//TODO 将当前站点下的所有用户保存到线程表中，以待删除
		$this->load->model('account_upload_task_model');
		$res = $this->account_upload_task_model->saveTask($type, $json);
		if($res == false){
			form_json_msg(DELETE_FILIALE_FAIL, '', $this->lang->line('save_process_fail'), array()); // 保存线程失败
		}
		
		// 根据组织Id删除当前组织
		$res = $this->ums->delOrganization($org_id);
		if($res == false){
			form_json_msg(DELETE_FILIALE_FAIL, '', $this->lang->line('delete_filiale_fail'), array()); // 删除分公司失败
		}
		
		form_json_msg(COMMON_SUCCESS, '', $this->lang->line('success'), array());// 删除分公司成功
	}
}