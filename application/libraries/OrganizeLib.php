<?php if (!defined('BASEPATH'))	exit('No direct script access allowed');

/**
 * @class OrganizeLib
 * @brief Organize 类库，主要负责对UMS组织结构获得、修改、新加方法。
 * @file OrganizeLib.php
 * @author zouyan <yan.zou@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
class OrganizeLib  {

	var $allow_w_k = array('region', 'department', 'costcenter');//管理员管理维度
	/**
	 *
	 * @brief 构造函数
	 * @details
	 * 特别说明：$CI不能入在构造函数中，因为当加载model时，在具体方法中使用时不成功，所以在每个方法中单独使用。
	 */
	public function __construct() {

		//载入接口公用函数
		// include_once APPPATH . 'helpers/my_httpcurl_helper.php';
		// $CI =& get_instance();
		// $CI->load->helper('my_publicfun');
		// $CI->load->helper('my_dgmdate');
		log_message('info', 'into class ' . __CLASS__ . '.');
	}
	/**
	 *
	 * @brief 根据客户编码,获得顶级和下一级组织数组[二维数组]
	 * @details
	 * @param int $customer_code 客户编码 '024014'
	 * @param int $get_type 类型，多个用,号分隔1:企业 2:生态企业 3:部门 4:生态企业部门 5 分公司 逗号分割的字符串
	 * @return array 顶级组织数组[二维数组]
	 *
	 */
	public function get_first_next_org_arr($customer_code = 0,$get_type = '1,3,5'){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->helper('my_dgmdate');
		$CI->load->library('API','','API');
		//获得第一层组织
		//首层和下一层组织数组
		$first_next_org_arr = array();
		//获得第一层组织数组[一维数组]
		$first_org_data = $this->get_first_org_arr($customer_code,0);
		//print_r($first_org_data);
		//exit;
		$first_next_org_arr = $first_org_data;
		//$data['first_org_data'] = $first_org_data;
		//当前第一层组织id
		$first_org_id = arr_unbound_value($first_org_data[0],'id',2,0);
		//获得当前下级组织数组

		$org_list_data = $this->get_org_array($first_org_id,'nextlevel',$get_type);

		if(isemptyArray($org_list_data)){//如果是空数组
			$first_next_org_arr = $first_next_org_arr;
		}else{
			$first_next_org_arr = array_merge ($first_next_org_arr,$org_list_data);
		}

		log_message('info', 'out method ' . __FUNCTION__ . '.');
		return $first_next_org_arr;
	}
	/**
	 *
	 * @brief 根据客户编码,获得顶级组织数组[一维/二维数组]
	 * @details
	 * @param int $customer_code 客户编码 '024014'
	 * @param int $re_type 返回数组类型 0二维1一维
	 * @return array 顶级组织数组[一维/二维数组]
	 *
	 */
	public function get_first_org_arr($customer_code = 0,$re_type = 1){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->helper('my_dgmdate');
		$CI->load->library('API','','API');
		//获得第一层组织
		$first_org_data = array();
		$siteURL = 'customer_code=' . $customer_code;
// 		$siteURL = 'customer_code=' . $customer_code . '&site_id=' . $CI->p_site_id;
		$uc_org_arr = $CI->API->UMS_Special_API('',6,array('url' => $siteURL));
		if(api_operate_fail($uc_org_arr)){//失败
			$err_msg = ' usm api rs/organizations' . $siteURL . ' fail .';
			log_message('error', $err_msg);
		}else{
			$first_org_data = arr_unbound_value($uc_org_arr,'data',1,array());
			if($re_type == 1){//一维数组
				$first_org_data = arr_unbound_value($first_org_data,0,1,array());
			}

			$err_msg = ' usm api rs/organizations' . $siteURL . ' success .';
			log_message('debug', $err_msg);
		}
		log_message('info', 'out method ' . __FUNCTION__ . '.');
		return $first_org_data;
	}
	/**
	 *
	 * @brief 获得下级组织并判断是否还有下级：[二维]
	 * @details
	 * @param int $org_id 当前组织id
	 * @param string $oper_type 类型，多个用,号分隔types：1:企业 2:生态企业 3:部门 4:生态企业部门 5 分公司 逗号分割的字符串
	 * @param string $power_no 是否获取有权限的用户 1排除有权限的组织2排除有权限的用户；多个用,号分隔
	 * @return array 组织数组[二维]
	 *
	 *
	 */
	public function get_org_user_array($org_id = 513,$oper_type = '1,3,5',$power_no = ''){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->helper('my_dgmdate');
		$CI->load->library('API','','API');
		$userCount = 0;
		$childNodeCount = 0;
		$nodeCode = '';
		$ns_user_arr = array();
		$ns_org_arr = array();
		//获得下级组织
		$org_user_list_data = array();
		//查询子组织
		//types：1:企业 2:生态企业 3:部门 4:生态企业部门 5 分公司 逗号分割的字符串 例如：1 或1,2
		//scope可以是：subtree,查询所有子组织samelevel,同级组织nextlevel下级
		$org_user_list_data = $this->get_org_by_orgid($org_id,$oper_type);
		if(isemptyArray($org_user_list_data)){//空数组
			$err_msg = ' get $org_user_list_data $org_id=' . $org_id . ' fail .';
			log_message('error', $err_msg);
		}else{
			$err_msg = ' get $org_user_list_data $org_id=' . $org_id . '  success .';
			log_message('debug', $err_msg);
			//userCount 当前组织下的用户数量,users为当前组织下所有用户
			//childNodeCount 当前组织所属下级组织数量，childs 为当前组织所属下级组织数组
			$userCount = arr_unbound_value($org_user_list_data,'userCount',2,0);
			$childNodeCount = arr_unbound_value($org_user_list_data,'childNodeCount',2,0);
			$nodeCode = arr_unbound_value($org_user_list_data,'nodeCode',2,0);
			if($userCount > 0){ //有用户
				$ns_user_arr = arr_unbound_value($org_user_list_data,'users',1,array());
			}
			if($childNodeCount > 0){ //有下级组织
				$ns_org_arr = arr_unbound_value($org_user_list_data,'childs',1,array());
			}
		}
		//获得组织数组
		$ok_org_arr = array();//最终的组织数组
		//判断当前组织id是否有自己的权限
		if ( strstr(',' . $power_no . ',', ',1,')){//1排除有权限的组织2排除有权限的用户
			foreach($ns_org_arr as $k => $v){
				$new_nodeCode = arr_unbound_value($v,'nodeCode',2,'');
				$org_boolean = $this->org_has_power($new_nodeCode);
				if(!$org_boolean){//组织没有自己权限
					$ok_org_arr[] = $v;
				}
			}
		}else{
			$ok_org_arr = $ns_org_arr;//最终的组织数组
		}
		//获得用户数组
		$ok_user_arr = array();//最终的用户数组
		foreach($ns_user_arr as $k => $v){
			$user_is_need = 1;//是不是需要的用户 0不需要1需要
			if ( strstr(',' . $power_no . ',', ',2,')){//1排除有权限的组织2排除有权限的用户
				$CI->load->library('StaffLib','','StaffLib');
				$new_user_id = arr_unbound_value($v,'id',2,'');
				$user_boolean = $CI->StaffLib->user_has_power($new_user_id);
				if($user_boolean){//用户有自己权限
					$user_is_need = 0;//是不是需要的用户 0不需要1需要
				}
			}
			if($user_is_need == 1){//是不是需要的用户 0不需要1需要
				$v['nodeCode'] = $nodeCode;//加入所属组织id串
				$ok_user_arr[] = $v;
			}

		}
		log_message('info', 'out method ' . __FUNCTION__ . '.');
		$re_arr = array(
            'nodeCode' => $nodeCode,//当前用户所属的组织串
            'organizationId' => $org_id,//当前组织id
            'userCount' => $userCount,//当前组织下的用户数量
            'users' => $ok_user_arr,//当前组织下所有用户
            'childNodeCount' => $childNodeCount,//当前组织所属下级组织数量
            'childs' => $ok_org_arr,//为当前组织所属下级组织数组
		);
		return $re_arr;
	}
	/**
	 *
	 * @brief 获得当前组织及下级组织所有员工信息
	 * @details
	 * @param int $org_id 当前组织id
	 * @param string $oper_type 类型，多个用,号分隔types：1:企业 2:生态企业 3:部门 4:生态企业部门 5 分公司 逗号分割的字符串
	 * @param string $power_no 是否获取有权限的用户 1排除有权限的组织2排除有权限的用户；多个用,号分隔
	 * @param array $other_arr 其它参数
	 $other_arr = array(
	 'not_stop_type' => $aaa,//如果组织类型有变动,还可以获得的组织类型[多个用逗号分隔];为空则表代：不受限止
	 'parent_type' => $aaa,//父组织类型,可以为空，为空：可以进行获得下一级
	 );
	 * @return array 员工数组[二维]
	 *
	 *
	 */
	public function get_all_user_byorgid($org_id = 513,$oper_type = '1,3,5',$power_no = '',$other_arr = array()){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$re_arr = array();
		$ns_org_user_arr = $this->get_org_user_array($org_id,$oper_type,$power_no);
		if(isemptyArray($ns_org_user_arr)){//如果是空数组
			return $re_arr;
		}
		$nodeCode = arr_unbound_value($ns_org_user_arr,'nodeCode',2,0);//当前用户所属的组织串
		$userCount = arr_unbound_value($ns_org_user_arr,'userCount',2,0);//当前组织下的用户数量
		$users_arr = arr_unbound_value($ns_org_user_arr,'users',1,array());//当前组织下所有用户
		$childNodeCount = arr_unbound_value($ns_org_user_arr,'childNodeCount',2,0);//当前组织所属下级组织数量
		$childs_arr = arr_unbound_value($ns_org_user_arr,'childs',1,array());//为当前组织所属下级组织数组
		if(!isemptyArray($users_arr)){//如果不是空数组
			$re_arr = array_merge($re_arr, $users_arr);
		}
		if(!isemptyArray($childs_arr)){//如果不是空数组
			$allow_get_next = 1;//是否允许获得下一级0不允许1允许
			$not_stop_type = arr_unbound_value($other_arr,'not_stop_type',2,'');//如果组织类型有变动,还可以获得的组织类型[多个用逗号分隔];为空则表代：有变动则停止获取
			$parent_type = arr_unbound_value($other_arr,'parent_type',2,'');//父组织类型,可以为空，为空：可以进行获得下一级
			foreach($childs_arr as $k => $v){
				$new_org_id = arr_unbound_value($v,'id',2,0);
				if($new_org_id > 0){
					$new_org_type = arr_unbound_value($v,'type',2,0);// 1:企业 2：生态企业 3：部门 4：生态企业部门 5：分公司
					if(!bn_is_empty($not_stop_type)){//有数据
						if(!bn_is_empty($parent_type)){//有数据
							if($new_org_type != $parent_type){
								if ( !strstr(',' . $not_stop_type . ',', ',' . $new_org_type . ',')){//不在里面
									$allow_get_next = 0;//是否允许获得下一级0不允许1允许
								}
							}
						}
					}
					$new_other_arr = array(
                        'not_stop_type' => $not_stop_type,
                        'parent_type' => $new_org_type,
					);
					if($allow_get_next == 1){
						$new_user_arr = $this->get_all_user_byorgid($new_org_id,$oper_type,$power_no,$new_other_arr);
						if(!isemptyArray($new_user_arr)){//如果不是空数组
							$re_arr = array_merge($re_arr, $new_user_arr);
						}
					}

				}
			}
		}
		return $re_arr;
	}
	/**
	 *
	 * @brief 判断当前组织串，是否有自己的权限
	 * @details
	 * @param string $org_code 当前组织串-500-501-502-503
	 * @return boolean 有权限true,没有权限false
	 *
	 */
	public function org_has_power($org_code = ''){
		$CI =& get_instance();
		if(bn_is_empty($org_code)){//没有数据
			return false;
		}
		$CI->load->model('uc_organization_model');
		$sel_field = 'id';
		$where_arr = array(
                'org' => $org_code                           
		);
		$sel_arr = $CI->uc_organization_model->get_db_arr($where_arr,$sel_field);
		if(isemptyArray($sel_arr)){//如果是空数组
			log_message('debug', 'uc_organization_model org = ' .  $org_code . '  is empty.');
			return false;
		}
		return true;
	}
	/**
	 *
	 * @brief 获得下级组织并判断是否还有下级：[二维]
	 * @details
	 * @param int $org_id 当前组织id
	 * @param string $scope scope可以是：subtree,查询所有子组织samelevel,同级组织nextlevel下级
	 * @param string $oper_type 类型，多个用,号分隔1:企业 2:生态企业 3:部门 4:生态企业部门 5 分公司 逗号分割的字符串
	 * @return array 组织数组[二维]
	 *
	 */
	public function get_org_array($org_id = 513,$scope = 'nextlevel',$oper_type = '1,3,5'){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->helper('my_dgmdate');
		$CI->load->library('API','','API');
		//获得下级组织
		$org_list_data = array();
		//查询子组织
		//types：1:企业 2:生态企业 3:部门 4:生态企业部门 5 分公司 逗号分割的字符串 例如：1 或1,2
		//scope可以是：subtree,查询所有子组织samelevel,同级组织nextlevel下级
		$siteURL = '/' . $org_id . '?scope=' . $scope . '&types=' . $oper_type ;
		$uc_orglist_arr = $CI->API->UMS_Special_API('',8,array('url' => $siteURL));
		if(api_operate_fail($uc_orglist_arr)){//失败
			$err_msg = ' usm api rs/organizations ' . $siteURL . ' fail .';
			log_message('error', $err_msg);
		}else{
			$org_list_data = arr_unbound_value($uc_orglist_arr,'data',1,array());
			$err_msg = ' usm api rs/organizations ' . $siteURL . ' success .';
			log_message('debug', $err_msg);
		}
		log_message('info', 'out method ' . __FUNCTION__ . '.');
		return $org_list_data;
		//print_r($org_list_data);
	}

	/**
	 * 格式化组织串
	 * 
	 * @param 	array 	$org_array 		组织数组
	 * @param 	int 	$oper_type 		类型：1、组织树
	 * @param 	array 	$in_arr 		其它条件
	 * 
	 * @return array 数组
	 */
	public function InitzTree_arr($org_array, $oper_type = 1, $in_arr = array()){
		$CI = & get_instance();
		log_message('info', 'Into method InitzTree_arr.');
		
		// 初始化结果数组
		$re_array = array();
		
		// 是否为第一层级
		$is_first 	= arr_unbound_value($in_arr, 'is_first', 2, 0);
		$org_no 	= 1;
		
		foreach($org_array as $k => $v){
			// 初始化临时结果数组
			$ns_mb_arr = array();
			
			$id 			= arr_unbound_value($v, 'id', 2, 0);			// 组织id
			$name 			= arr_unbound_value($v, 'name', 2, '');			// 组织名称
			$childNodeCount = arr_unbound_value($v, 'childNodeCount', 2, 0);// 子组织数
			$parentId 		= arr_unbound_value($v, 'parentId', 2, 0);		// 父组织id
			$userCount 		= arr_unbound_value($v, 'userCount', 2, 0);		// 用户数量

			$open_boolean 				= false;				// 是否展开
			$nocheck_boolean 			= false;					// 没有复选框
			$chkDisabled_boolean 		= false;				// 是否禁用
			$isrename_boolean 			= true;					// 是否可以修改组织名称
			$isaddnext_boolean 			= true ;				// 是否可以新加下级组织
			$isdel_boolean 				= true;					// 是否可以删除当前组织
			$isDisabled_boolean 		= false;				// 都不能用
			$is_onclick					= true;					// 是否可以点击选中
			
			// 如果是第一级
			if($org_no == 1 && $is_first == 1){
				$open_boolean 			= false;				// 是否展开
				$nocheck_boolean 		= true;					// 没有复选框
				$chkDisabled_boolean 	= true;					// 是否禁用
				$isrename_boolean 		= false;				// 是否可以修改组织名称
				$isaddnext_boolean 		= false;				// 是否可以新加下级组织
				$isdel_boolean 			= false;				// 是否可以删除当前组织
			}
			
			// 各种管理员的权限控制
			if(in_array($CI->p_role_id, array(EMPPLOYEE_MANAGER, ORGANIZASION_MANAGER, ACCOUNT_MANAGER))){
				// 查询当前管理员的组织管理范围
				$CI->load->model('uc_user_resource_model');
				$where_arr = array(
					'id' 		=> $CI->p_admin_role_id,
					'userID' 	=> $CI->p_user_id
				);
				$user_res_arr = $CI->uc_user_resource_model->getUserResource($where_arr);
				
				
				// 第一维度的组织范围
				$wd_1 = isset($user_res_arr['scope_level_1']) ? $user_res_arr['scope_level_1'] : '';
				if($wd_1 == 'department'){
					$wd_1_value = isset($user_res_arr['scope_level_1_value']) ? $user_res_arr['scope_level_1_value'] : '';
					if(!empty($wd_1_value)){
						$self_org_arr = explode(",", $wd_1_value);
						if(!in_array($id, $self_org_arr)){
							$is_onclick 		= false;	// 是否可以点击选中: true可以，false不可以[手动控制]
							$isrename_boolean 	= false;	// 是否可以修改组织名称
						}
					}
				}
				
				// 第二维度的组织范围
				$wd_2 = isset($user_res_arr['scope_level_2']) ? $user_res_arr['scope_level_2'] : '';
				if($wd_1 == 'department'){
					$wd_2_value = isset($user_res_arr['scope_level_2_value']) ? $user_res_arr['scope_level_2_value'] : '';
					if($id == $wd_2_value){
						$is_onclick 		= false;	// 是否可以点击选中: true可以，false不可以[手动控制]
						$isrename_boolean 	= false;	// 是否可以修改组织名称
					}
				}
				
			}
			
			$ns_mb_arr = array(
					'id' 				=> $id,					// 当前组织id
					'pId' 				=> $parentId,			// 父组织id
					'name' 				=> $name,				// 组织名称
					'userCount' 		=> $userCount,			// 用户数量
					'open' 				=> $open_boolean,		// 默认展开不展开下一级： true展开 ，false不展开 [手动控制]
					'nocheck' 			=> $nocheck_boolean,	// 没有复选框 ：true没有 ，false有 [手动控制]
					'chkDisabled' 		=> $chkDisabled_boolean,// 是否禁用 ：true 禁用，false不禁用 [手动控制,]
					'isDisabled' 		=> $isDisabled_boolean,	// 都不能用是否禁用： true禁用，false不禁用 [手动控制]
					'isParent' 			=> $childNodeCount,		// 子组织数（决定是否会有下一级）
					'isrename' 			=> $isrename_boolean,	// 是否可以修改组织名称： true 能，false不能
					'isaddnext' 		=> $isaddnext_boolean,	// 是否可以新加下级组织： true 能，false不能
					'isdel' 			=> $isdel_boolean,		// 是否可以删除当前组织 ：true 能，false不能
					'identity' 			=> 0,					// 0组织，1帐号
					'is_onclick'		=> $is_onclick			// 是否可以点击选中: true可以，false不可以[手动控制]
			);
						
			$re_array[] = $ns_mb_arr;
			$org_no += 1;
		}
		
		log_message('info', 'out method InitzTree_arr.');
		
		return $re_array;
	}
	
	/**
	 *
	 * @brief 格式化用户串,使组织树可以使用：
	 * @details
	 * @param array $in_user_array 组织数组
	 * @param int $org_id 用户所属组织id
	 * @param int $oper_type 类型1组织树
	 * @param array $in_arr 其它条件
	 * @return array 数组
	 *
	 */
	public function user_to_user_tree($in_user_array = array(),$org_id = '',$oper_type = 1){
		$user_arr = array();
		if(is_array($in_user_array)){//是数组
			foreach ($in_user_array as $k =>$v)
			{
				$ns_user_id = arr_unbound_value($v,'id',2,0);
				$ns_user_displayName = arr_unbound_value($v,'displayName',2,'');
				$ns_user_orgNodeCode = arr_unbound_value($v,'orgNodeCode',2,0);
				$ns_user_organizationId = arr_unbound_value($v,'organizationId',2,0);
				$ok_user_arr = array(
                    'id' => $ns_user_id,
                    'name' => $ns_user_displayName,
                    'pId' => $org_id,
                    'orgNodeCode' => $ns_user_orgNodeCode,
                    'organizationId' => $ns_user_organizationId,
                    'identity' => 1,//0组织1帐号
				);
				$user_arr[]= $ok_user_arr;
			}
		}
		return $user_arr;
	}
	/**
	 *
	 * @brief 根据组织id,获得当前组织用户信息
	 * @details
	 * @param int $org_id 组织id
	 * @return array 数组
	 *
	 */
	public function get_users_arr_by_orgid($org_id = 0){
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->helper('my_dgmdate');
		$CI->load->library('API','','API');
		$user_data = array();
		
		//隐藏公司组织下的用户 add by hongliang
		/*
		if($org_id == $CI->p_org_id){
			return $user_data;
		}
		*/
		
		$siteURL = '/' . $org_id . '/users?productID=' . UC_PRODUCT_ID;//不加productID的话，就不会验证产品状态
		$uc_user_arr = $CI->API->UMS_Special_API('',7,array('url' => $siteURL));
		if(api_operate_fail($uc_user_arr)){//失败
			$err_msg = ' usm api rs/sites?' . $siteURL . ' fail .';
			log_message('error', $err_msg);
		}else{
			$user_data = arr_unbound_value($uc_user_arr,'data',1,array());
			$err_msg = ' usm api rs/sites?' . $siteURL . ' success .';
			log_message('debug', $err_msg);
		}
		return $user_data;

	}
	/**
	 *
	 * @brief 根据组织id,获得当前组织用户信息，获得当前组织的帐号列表信息[部门管理者在前，其它人员在后]：
	 * @details
	 * @param int $org_id 组织id
	 * @param int $site_id 站点id
	 * @param int $org_pid 父组织id,平时都为空，只是临时为了触发UCCSERVER的同事接口加的
	 * @return array 数组
	 *
	 */
	public function get_users_list($org_id = 0,$site_id = 0 ,$org_pid = 0){
		log_message('info', 'into method get_users_list.');
		
		$CI = & get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->library('API','','API');
		
		// 获得用户列表
		$user_arr = $this->get_users_arr_by_orgid($org_id);
		
		// 如果用户列表不是空数组
		if(!isemptyArray($user_arr)){
			$CI->load->model('uc_org_manager_model');
			
			// 获得当前组织的管理者id,没有则为0
			$org_manager_userid = $CI-> uc_org_manager_model ->get_org_manager_userid($org_id,$site_id);
			
			// 有组织管理者，将组织管理者提到最前面来
			if($org_manager_userid > 0){
				$ns_user_arr 			= array();	// 临时普通员工数组
				$ns_manager_user_arr 	= array();	// 临时管理者数组
				
				foreach($user_arr as $k => $v){
					$id = arr_unbound_value($v,'id',2,0);	// 用户id
					
					// 是组织管理者
					if($org_manager_userid == $id){
						$v['is_org_manager'] = 1;	// 是否组织管理者：0、不是；1、是
						$ns_manager_user_arr[] = $v;
					}else{
						$v['is_org_manager'] = 0;	// 是否组织管理者：0、不是；1、是
						$ns_user_arr[] = $v;
					}
				}
				
				//把组织管理者放到最前面 ,合并数组
				$user_arr = array_merge($ns_manager_user_arr,$ns_user_arr);

			}else{ // 没有组织管理者
				foreach($user_arr as $k => $v){
					$user_arr[$k]['is_org_manager'] = 0;	// 是否组织管理者：0、不是；1、是
				}
			}
		}
		return $user_arr;
	}

	/**
	 *
	 * @brief 根据组织id删除当前组织：
	 * @details
	 * @param int $org_id 组织id
	 * @return boolean true 成功 false 失败
	 *
	 */
	public function del_org_by_orgid($org_id = 0){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->model('uc_org_manager_model');
		$CI->load->library('API','','API');
		$siteURL = $org_id ;
		$del_org_arr = $CI->API->UMS_Special_API('',12,array('url' => $siteURL));
		if(api_operate_fail($del_org_arr)){//失败
			$err_msg = ' usm api del org rs/organizations/' . $siteURL . ' fail .';
			log_message('error', $err_msg);
			return false;
		}else{
			// $user_info_data = arr_unbound_value($del_org_arr,'data',1,array());
			$err_msg = ' usm api del org rs/organizations/' . $siteURL . ' success .';
			log_message('debug', $err_msg);
			return true;
		}
	}
	/**
	 *
	 * @brief 成本中心员工列表时，根据post过来的组织id，获得当前组织的帐号信息[部门管理者在前，其它人员在后]：
	 * @details
	 * @param array $in_arr 需要传入的参数
	 $in_arr = array(
	 'org_id' => ,//当前组织id
	 'site_id' =>,//当前站点id
	 );
	 * @param array $cost_user_arr 已有的成本中心用户数组，如果不想排除已经有成本中心的用户，则传空数组
	 * @param int $type 1排除已经有成本中心的用户，2只获得在已经有成本中心的用户
	 * @return array 用户信息数组
	 *
	 */
	public function get_costusers_by_orgid($in_arr,$cost_user_arr = array(),$type = 2){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->model('uc_org_manager_model');
		$CI->load->library('API','','API');
		$user_arr = array();
		//$org_id=$this->input->post('org_id', TRUE);
		//$org_id = empty_to_value($org_id,0);//517
		//$site_id = $this->p_site_id;
		//$site_id = empty_to_value($site_id,0);//517
		$org_id = arr_unbound_value($in_arr,'org_id',2,0);
		$site_id = arr_unbound_value($in_arr,'site_id',2,0);
		//$this->load->library('OrganizeLib','','OrganizeLib');
		$ns_user_arr = $this->get_users_list($org_id ,$site_id );
		if(is_array($ns_user_arr)){//是数组
			foreach ($ns_user_arr as $k =>$v)
			{
				$ns_user_id = arr_unbound_value($v,'id',2,0);
				if($type == 1){//1排除已经有成本中心的用户，
					if (!deep_in_array($ns_user_id, $cost_user_arr)) {//不在数组里面
						$user_arr[]= $v;
					}
				}else{//2只获得在已经有成本中心的用户
					if (deep_in_array($ns_user_id, $cost_user_arr)) {//不在数组里面
						$user_arr[]= $v;
					}
				}
			}
		}
		return $user_arr;
	}

	/**
	 *
	 * @brief [此方法有问题不要使用]根据组织id串[多个用逗号分隔],获得组织信息：
	 * @details
	 * @param string $orgids  组织id串[多个用逗号分隔]
	 * @return array 组织数组
	 *
	 */
	public function get_org_by_orgids($orgids = ''){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->helper('my_dgmdate');
		$CI->load->library('API','','API');
		$re_org_arr = array();
		if(!bn_is_empty($orgids)){//有数据
			$orgid_arr = explode(",", $orgids);
			foreach ($orgid_arr as $orgid){
				//echo '$orgid= ' . $orgid .'<br/>';
				$ns_org_arr = $this->get_org_by_orgid($orgid);
				if(!isemptyArray($ns_org_arr)){//如果不是空数组
					$re_org_arr[] = $ns_org_arr;
				}
			}
		}
		return $re_org_arr;
	}
	/**
	 *
	 * @brief 根据组织id,获得组织信息[当前组织的信息及，当前组织所属的下级组织和下级帐号]：
	 * @details
	 * @param int $orgid  组织id
	 * @param string $oper_type 类型，多个用,号分隔types：1:企业 2:生态企业 3:部门 4:生态企业部门 5 分公司 逗号分割的字符串
	 * @return array 组织数组[多维数组]
	 *
	 */
	public function get_org_by_orgid($orgid = '',$oper_type =''){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->helper('my_dgmdate');
		$CI->load->library('API','','API');
		$re_org_arr = array();
		if(!bn_is_empty($orgid)){//有数据
			$ne_org_arr = array();
			$siteURL = $orgid . '/details' ;// 2/details? 可选[多个,号分隔]types：1:企业 2:生态企业 3:部门 4:生态企业部门
			if(!bn_is_empty($oper_type)){//有数据
				$siteURL .= '?types=' . $oper_type;
			}
			$get_org_arr = $CI->API->UMS_Special_API('',14,array('url' => $siteURL));
			if(api_operate_fail($get_org_arr)){//失败
				$err_msg = ' usm api rs/organizations/' . $siteURL . ' fail .';
				log_message('error', $err_msg);
			}else{
				$ne_org_arr = arr_unbound_value($get_org_arr,'data',1,array());
				$err_msg = ' usm api rs/organizations/' . $siteURL . ' success .';
				log_message('debug', $err_msg);
				if(!isemptyArray($ne_org_arr)){//如果不是空数组
					$re_org_arr = $ne_org_arr;
				}
			}
		}
		return $re_org_arr;
	}
	/**
	 *
	 * @brief 根据组织id,获得组织信息：
	 * @details
	 * @param int $orgid  组织id
	 * @return array 组织数组[一维数组]
	 *
	 */
	public function get_org_by_id($orgid = ''){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->helper('my_dgmdate');
		$CI->load->library('API','','API');
		$re_org_arr = array();
		if(!bn_is_empty($orgid)){//有数据
			$ne_org_arr = array();
			//$siteURL = $orgid;
			$get_org_arr = $CI->API->UMS_Special_API('',17,array('url' => $orgid));
			if(api_operate_fail($get_org_arr)){//失败
				log_message('error', 'ums api rs/organizations/' . $orgid . '/brief fail .');
			}else{
				$ne_org_arr = arr_unbound_value($get_org_arr,'data',1,array());
				log_message('debug', 'ums api rs/organizations/' . $orgid . '/brief success .');
				if(!isemptyArray($ne_org_arr)){//如果不是空数组
					$re_org_arr = $ne_org_arr;
				}
			}
		}
		return $re_org_arr;
	}
	/**
	 *
	 * @brief 根据组织id,获得组织层级数组信息：
	 * @details
	 * @param int $orgid  组织id
	 * @return array 组织层级数组[多维数组]
	 *
	 */
	public function get_orgarr_by_id($orgid = ''){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->helper('my_dgmdate');
		$CI->load->library('API','','API');
		$re_org_arr = array();
		if(!bn_is_empty($orgid)){//有数据
			$ne_org_arr = array();
			$siteURL = $orgid;
			$get_org_arr = $CI->API->UMS_Special_API('',17,array('url' => $siteURL));
			if(api_operate_fail($get_org_arr)){//失败
				$err_msg = ' usm api rs/organizations/' . $siteURL . '/brief fail .';
				log_message('error', $err_msg);
			}else{
				$ne_org_arr = arr_unbound_value($get_org_arr,'data',1,array());
				$err_msg = ' usm api rs/organizations/' . $siteURL . '/brief success .';
				log_message('debug', $err_msg);
				if(!isemptyArray($ne_org_arr)){//如果不是空数组
					//$re_org_arr = $ne_org_arr;
					$parentId = arr_unbound_value($ne_org_arr,'parentId',2,'');

					if(!bn_is_empty($parentId)){//有数据
						if($parentId > 0 ){
							$ns_org_arr = $this->get_orgarr_by_id($parentId);
							$re_org_arr = array_merge($re_org_arr,$ns_org_arr);
						}
					}
					$re_org_arr[] = $ne_org_arr;
				}
			}

		}
		return $re_org_arr;
	}
	/**
	 *
	 * @brief 根据组织串，获得组织信息：
	 * @details
	 * @param string $org_code  组织串-500-501-502-503
	 * @return array 组织数组
	 *
	 */
	public function get_orgarr_by_orgcode($org_code = ''){
		$re_org_arr = array();
		if(bn_is_empty($org_code)){//为空
			return $re_org_arr;
		}
		$org_code_arr = explode('-', $org_code);
		if(isemptyArray($org_code_arr)){//如果是空数组
			return $re_org_arr;
		}
		foreach($org_code_arr as $k => $v){
			if(!bn_is_empty($v)){//不为空
				$ns_org_id = $v;
				$ns_org_arr = $this->get_org_by_orgid($ns_org_id,'');
				if(!isemptyArray($ns_org_arr)){//不是空数组
					$ns_p_arr = array();
					foreach($ns_org_arr as $p_k => $p_v){
						if(!is_array($p_v)){//不是数组的
							$ns_p_arr[$p_k] = $p_v;
						}
					}
					$re_org_arr[] = $ns_p_arr;
				}
			}
		}
		return $re_org_arr;
	}
	/**
	 *
	 * @brief 根据组织串，获得组织名称信息：
	 * @details
	 * @param string $org_code  组织串-500-501-502-503
	 * @return array 组织名称数组
	 *
	 */
	public function get_orgnamearr_by_orgcode($org_code = ''){
		$re_org_arr = array();
		if(bn_is_empty($org_code)){//为空
			return $re_org_arr;
		}
		$org_arr = $this->get_orgarr_by_orgcode($org_code);
		foreach($org_arr as $v){
			$org_name = arr_unbound_value($v,'name',2,'');
			$re_org_arr[] = $org_name;
		}
		return $re_org_arr;
	}

	/**
	 *
	 * @brief 根据组织org_id及用户user_id取消或设置组织管理者
	 * @details
	 * @param array $in_arr  参数组数
	 $in_arr = array(
	 'org_id' => 0,//组织id
	 'site_id' => 0,//站点id
	 'user_id' => 0,//用户id
	 'isset' => 0,//0取消，1设置修改
	 );
	 * @param array $sys_arr  参数组数
	 $sys_arr = array(
	 'customerCode' => $aaa,//客户编码
	 'siteID' => $aaa,//站点id
	 'site_name' => $aaa,//站点名称
	 'accountId'=>$aaa,//分帐id ；注意：如果有用户，则是用户自己的
	 'siteURL' => $aaa,//地址
	 'contractId' => $aaa,//合同id
	 'operator_id' => $this->p_user_id,//操作发起人用户ID
	 'client_ip' => $this->p_client_ip,//客户端ip
	 'server_ip' => $this->p_server_ip,//服务端ip
	 'oper_account' => $this->p_account,//操作帐号
	 'oper_display_name' => $this->p_display_name,//操作姓名
	 'orgID' => $this->p_org_id,//所属站点，分公司，生态企业组织id[各种管理员新加时，必填]
	 ),
	 * @return boolean true 成功 false
	 *
	 */
	public function modify_manager($in_arr = array(),$sys_arr = array()){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->helper('my_dgmdate');
		$CI->load->library('API','','API');
		if(isemptyArray($in_arr)){//如果是空数组
			return false;
		}


		$org_id 	= arr_unbound_value($in_arr,'org_id',2,'');
		$site_id 	= arr_unbound_value($in_arr,'site_id',2,'');
		$user_id 	= arr_unbound_value($in_arr,'user_id',2,'');
		$isset 		= arr_unbound_value($in_arr,'isset',2,'');//0取消1设置
		$re_org_arr = $this->get_org_by_id($org_id);
		$org_pid 	= arr_unbound_value($re_org_arr,'parentId',2,'');


		if(bn_is_empty($org_id) || bn_is_empty($site_id) || bn_is_empty($user_id) || bn_is_empty($isset)){//没有数据
			return false;
		}
		$CI->load->model('uc_org_manager_model');
		$where_arr = array(
                'org_id' => $org_id,
                'site_id' => $site_id,
                'user_id' => $user_id,
			);
			$del_arr = array(
               'where' => $where_arr,
			 
			);

		$log_id = 15;//日志编号 17取消15设置
		if($isset == 0){//取消就是删除
			$log_id = 17;//日志编号 17取消15设置
			
			$re_del_arr = $CI-> uc_org_manager_model -> operateDB(4,$del_arr);
			if(db_operate_fail($re_del_arr)){//失败
				return false;
			}else{
				//uccserver 接口 10.	同事关系删除
				$data = 'user_id=' . $user_id . '&org_id=' . $org_id . '&parent_id=' . $org_pid . '&is_admin=1' ;//'user_id=' . $user_id . '&session_id=' . $session_id . '&type =' . $UCC_TYPE . '&data=' . json_decode($api_data_arr);
				$api_arr = $CI->API->UCCServerAPI($data,10);
				if(api_operate_fail($api_arr)){//失败
					log_message('error', 'uccapi async/deleteColleague fail.');

				}else{
					log_message('debug', 'uccapi async/deleteColleague success.');
				}
				return true;
			}
		}elseif($isset == 1){//是设置管理者，调设置管理者接口
			$where_arr = array(
	                'org_id' => $org_id,
	                'site_id' => $site_id,
					'user_id' => $user_id,
			);
			$modify_arr = array(
	                'org_id' => $org_id,
	                'site_id' => $site_id,
	                'user_id' => $user_id,
			);
			$insert_arr = $modify_arr;
			$insert_arr['create_time'] = dgmdate(time(), 'dt');
			$re_num = $CI-> uc_org_manager_model -> updata_or_insert(1, 'id', $where_arr,$modify_arr,$insert_arr);	
			log_message('debug', "update or insert uc org manger results is [$re_num]");		
			if (in_array($re_num, array(-1, -3, -5)) || $re_num > 0){
				//uccserver 接口 9.	同事关系创建
				$data = 'user_id=' . $user_id . '&org_id=' . $org_id . '&parent_id=' . $org_pid . '&is_admin=' .$isset ;//'user_id=' . $user_id . '&session_id=' . $session_id . '&type =' . $UCC_TYPE . '&data=' . json_decode($api_data_arr);
				$api_arr = $CI->API->UCCServerAPI($data,9);
				if(api_operate_fail($api_arr)){//失败
					log_message('error', 'uccapi async/createColleague fail.');
				}else{
					log_message('debug', 'uccapi async/createColleague success.');
				//6员工权限变更消息
// 				$CI->load->library('Informationlib','','Informationlib');
// 				$msg_arr = array(
//                         'user_id' => $user_id,//用户id
//                         'org_id' => $org_id,//组织id
// 				);
// 				$CI->Informationlib->send_ing($sys_arr,array('msg_id' => 6,'msg_arr' => $msg_arr));	
				}				
			}
		}else{
			log_message('error', 'input modify user right params error, error params is ->'.var_export($in_arr, true));
		}

		$where_arr = array(
                'org_id' => $org_id,
                'site_id' => $site_id,
		//'user_id' => $user_id,
		);
		$modify_arr = array(
                'org_id' => $org_id,
                'site_id' => $site_id,
                'user_id' => $user_id,
		);
		$insert_arr = $modify_arr;
		$insert_arr['create_time'] = dgmdate(time(), 'dt');
		$re_num = $CI-> uc_org_manager_model -> updata_or_insert(1,'id',$where_arr,$modify_arr,$insert_arr);
		switch ($re_num) {//-1 更新记录成功 -2更新操作失败 -3新加的记录id 新加记录成功 -4新加操作失败 -5 没有操作
			case -2:
			case -4:
				return false;
				break;
			default:
				//return true;
				//6员工权限变更消息
				$CI->load->library('Informationlib','','Informationlib');
				$msg_arr = array(
                        'user_id' => $user_id,//用户id
                        'org_id' => $org_id,//组织id
				);
				$CI->Informationlib->send_ing($sys_arr,array('msg_id' => 6,'msg_arr' => $msg_arr));
				break;
		}

		 
		$ns_orgID = arr_unbound_value($sys_arr,'orgID',2,'');
		$ns_siteID = arr_unbound_value($sys_arr,'siteID',2,'');
		$ns_operator_id = arr_unbound_value($sys_arr,'operate_id',2,'');
		$ns_oper_account = arr_unbound_value($sys_arr,'oper_account',2,'');
		$ns_oper_display_name = arr_unbound_value($sys_arr,'oper_display_name',2,'');
		$ns_client_ip = arr_unbound_value($sys_arr,'client_ip',2,'');
		//日志
		$CI->load->library('LogLib','','LogLib');
		$log_in_arr =array(
               'Org_id' => $ns_orgID,//$this->p_org_id ,//组织ID
               'site_id' => $ns_siteID,//$this->p_site_id ,//站点ID
               'operate_id' => $ns_operator_id,//$this->p_user_id,//操作会员ID
               'login_name' => $CI->p_account ,//操作账号[可以为空，没有，则重新获取]
               'display_name' => $ns_oper_display_name,//$this->p_display_name,//操作姓名[可以为空，没有，则重新获取]
               'client_ip' => $ns_client_ip,//$this->p_client_ip ,//客户端ip
		);
		log_message('info', 'xiaoxiao_log=' . var_export($log_in_arr, true));
		$re_id = $CI->LogLib->set_log(array('5',$log_id),$log_in_arr);
		return true;
	}
	/**
	 *
	 * @brief 根据管理员信息,获得当前管理员的生态企业数组：
	 * @details
	 * @param array $in_arr
	 $in_arr = array(
	 'user_id' => $aaa,//用户id

	 );
	 * @return array 生态企业数组[二维]
	 array(
	 'id' => $id,//id
	 'name' => $name,//名称
	 'customercode' => $customercode,//客户编码
	 'nodeCode' => $nodeCode,//组织串
	 'type' => $type ,//类型
	 'parentId' => $parentId,//父级id
	 );
	 *
	 */
	public function get_ecology_arr_byuserid($in_arr = array()){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->helper('my_dgmdate');
		$CI->load->library('API','','API');
		$re_ecology_arr = array();//数据记录数组

		if(isemptyArray($in_arr)){//如果是空数组
			return $re_ecology_arr;
		}
		$userID = arr_unbound_value($in_arr,'user_id',2,'');
		//根据管理员id,获得管理员所属的生态企业
		$CI->load->model('uc_manager_ecology_model');
		$where_arr = array(
               'user_id' => $userID ,//用户id
		);
		$manager_ecology_where_arr = array(
		//'select' =>'id',
                'where' => $where_arr,
		);
		$manager_ecology_arr =  $CI->uc_manager_ecology_model->operateDB(2,$manager_ecology_where_arr);

		if( is_array($manager_ecology_arr) ){
			log_message('info', 'get uc_manager_ecology_model ' . json_encode($manager_ecology_where_arr) . '  success.');
		}else{
			log_message('debug', 'get uc_manager_ecology_model ' . json_encode($manager_ecology_where_arr) . ' fail');
		}
		foreach($manager_ecology_arr as $k => $v){
			$ecology_id = arr_unbound_value($v,'ecology_id',2,0);
			$re_org_arr = $this->get_org_by_id($ecology_id);
			if(!isemptyArray($re_org_arr)){//不是空数组
				$id = arr_unbound_value($re_org_arr,'id',2,'');
				$name = arr_unbound_value($re_org_arr,'name',2,'');
				$customercode = arr_unbound_value($re_org_arr,'customercode',2,'');
				$nodeCode = arr_unbound_value($re_org_arr,'nodeCode',2,'');
				$type = arr_unbound_value($re_org_arr,'type',2,'');
				$parentId = arr_unbound_value($re_org_arr,'parentId',2,'');
				$re_ecology_arr[] = array(
                        'id' => $id,//id
                        'name' => $name,//名称
                        'customercode' => $customercode,//客户编码
                        'nodeCode' => $nodeCode,//组织串
                        'type' => $type ,//类型
                        'parentId' => $parentId,//父级id
				);
			}

		}
		return $re_ecology_arr;
	}
	/**
	 *
	 * @brief 根据公司orgid，获得公司信息[自动登陆]
	 * @details
	 * @param int $org_id 公司id
	 * @return array $re_arr
	 $re_arr = array(
	 'ucc_user_arr' => $ucc_user_arr,//uccserver 登陆信息[可以为空]
	 'uc_user_arr' => $uc_user_arr,//user表 信息
	 'uc_admin_arr' => $uc_admin_arr,//管理员表 信息
	 'uc_site_arr' => $uc_site_arr,//站点表 信息
	 'uc_account_arr' => $uc_account_arr,//分帐表 信息
	 'uc_customer_arr' => $uc_customer_arr,//合同表 信息
	 'uc_privilege_arr' => $uc_privilege_arr,//角色权限表 信息
	 'uc_user_resource_arr' => $uc_user_resource_arr,//管理员维度表 信息[可以为空]
	 'uc_cluster_domain_arr' => $uc_cluster_domain_arr,//域分配 数组表 信息
	 );
	 */
	public function get_autologin_array($org_id = ''){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->library('API','','API');
		$CI->load->library('Informationlib','','Informationlib');
		$re_arr = array();
		if(bn_is_empty($org_id)){//没有数据
			return $re_arr;
		}
		$ucc_user_arr = array();//uccserver 登陆信息
		$uc_user_arr = array();//user表 信息
		$uc_admin_arr = array();//管理员表 信息
		$uc_site_arr = array();//站点表 信息
		$uc_account_arr = array();//分帐表 信息
		$uc_customer_arr = array();//合同表 信息
		$uc_privilege_arr = array();//角色权限表 信息
		$uc_user_resource_arr = array();//管理员维度表 信息
		$uc_cluster_domain_arr = array();//域分配 数组表 信息

		$CI->load->model('UC_User_Admin_Model');
		//管理员表 信息
		$get_data = array(
            'where' => array('orgID' => $org_id,'state' => 1),
            'where_in' => array('role_id' => array('1','6'))//1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员

		);
		$uc_admin_arr = $CI->UC_User_Admin_Model->operateDB(1,$get_data);
		if(isemptyArray($uc_admin_arr)){//如果是空数组
			return $re_arr;
		}
		$user_id = arr_unbound_value($uc_admin_arr,'userID',2,'');
		$uc_role_id = arr_unbound_value($uc_admin_arr,'role_id',2,'');
		$uc_site_id = arr_unbound_value($uc_admin_arr,'siteID',2,'');
		$accountId = arr_unbound_value($uc_admin_arr,'accountId',2,'');
		//uccserver 登陆信息

		//user表 信息
		$CI->load->model('UC_User_Model');
		$sel_data = array(
            'where' => array('siteId' => $uc_site_id,'userID' => $user_id,'status' => 1),
		);
		$uc_user_arr = $CI->UC_User_Model->operateDB(1,$sel_data);
		if(isemptyArray($uc_user_arr)){//如果是空数组
			return $re_arr;
		}

		//站点表 信息
		$CI->load->model('UC_Site_Model');
		$sel_data = array(
            'select' => 'id,siteID,contractId,domain,department_level,logoUrl,companyType,isLDAP,customerCode,createTime',
            'where' => array('siteID' => $uc_site_id ),
		);
		$uc_site_arr = $CI->UC_Site_Model->operateDB(1,$sel_data);
		if(isemptyArray($uc_site_arr)){//如果是空数组
			return $re_arr;
		}
		$contractId = arr_unbound_value($uc_site_arr,'contractId',2,'');
		$domain = arr_unbound_value($uc_site_arr,'domain',2,'');
		$customerCode = arr_unbound_value($uc_site_arr,'customerCode',2,'');
		//分帐表 信息
		$CI->load->model('uc_account_model');
		$sel_data = array(
		//'select' => '',
            'where' => array('customercode' => $customerCode ),//array('site_id' => $uc_site_id ),
		);
		$uc_account_arr = $CI->uc_account_model->operateDB(2,$sel_data);
		if(isemptyArray($uc_account_arr)){//如果是空数组
			return $re_arr;
		}
		//合同表 信息
		$CI->load->model('uc_customer_model');
		$sel_data = array(
            'select' => 'id,siteId,customerCode,contractId,name',
            'where' => array('siteId' => $uc_site_id ),
		);
		$uc_customer_arr = $CI->uc_customer_model->operateDB(1,$sel_data);
		if(isemptyArray($uc_customer_arr)){//如果是空数组
			return $re_arr;
		}
		//角色权限表 信息
		$CI->load->model('uc_role_privilege_model');
		$sel_data = array(
            'select' => 'privilege_id',
            'where' => array('role_id' => $uc_role_id ),
		);
		$uc_privilege_arr = $CI->uc_role_privilege_model->operateDB(2,$sel_data);
		if(isemptyArray($uc_privilege_arr)){//如果是空数组
			return $re_arr;
		}
		//管理员维度表 信息
		$CI->load->model('uc_user_resource_model');//管理员维度
		$sel_data = array(
		//'select' => 'scope_level_1,scope_level_1_value,scope_level_2,scope_level_2_value',
            'where' => array('userID' => $user_id ),
		);
		$uc_user_resource_arr = $CI->uc_user_resource_model->operateDB(1,$sel_data);
		//if(isemptyArray($uc_user_resource_arr)){//如果是空数组
		//   return $re_arr;
		// }
		//域分配 数组表 信息
		$CI->load->library('WebLib','','WebLib');
		$uc_cluster_domain_arr = $CI->WebLib-> get_cluster($customerCode,$uc_site_id);
		if(isemptyArray($uc_cluster_domain_arr)){//如果是空数组
			return $re_arr;
		}
		$re_arr = array(
            'ucc_user_arr' => $ucc_user_arr,//uccserver 登陆信息[可以为空]
            'uc_user_arr' => $uc_user_arr,//user表 信息
            'uc_admin_arr' => $uc_admin_arr,//管理员表 信息
            'uc_site_arr' => $uc_site_arr,//站点表 信息
            'uc_account_arr' => $uc_account_arr,//分帐表 信息
            'uc_customer_arr' => $uc_customer_arr,//合同表 信息
            'uc_privilege_arr' => $uc_privilege_arr,//角色权限表 信息
            'uc_user_resource_arr' => $uc_user_resource_arr,//管理员维度表 信息[可以为空]
            'uc_cluster_domain_arr' => $uc_cluster_domain_arr,//域分配 数组表 信息
		);
		return $re_arr;
	}
	/**
	 *
	 * @brief 变更生态企业管理员
	 * @details
	 * 如果新的管理员与原管理员是同一个人，不进行操作
	 * 如果新管理员，不是生态企业管理员，则创建为生态管理员[父级为，原管理员父级],把生态企业改在新管理员名下
	 * .............是............,把生态企业改在新管理员名下
	 * 如果老管理员去掉当前生态后，还有生态，则只是移交生态的管理员
	 * ......................没有生态，则走管理员删除流程
	 * @param array 生态企业管理员变更
	 $msg_arr = array(
	 'old_user_id' => $aaa,
	 'new_user_id' => $aaa,
	 'ecology_id' => $aaa,
	 );
	 * @return false失败；true成功
	 */
	public function modify_ecology_manager($msg_arr = array()){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->helper('my_dgmdate');
		$CI->load->library('API','','API');
		$old_user_id = arr_unbound_value($msg_arr,'old_user_id',2,'');
		$new_user_id = arr_unbound_value($msg_arr,'new_user_id',2,'');
		$ecology_id = arr_unbound_value($msg_arr,'ecology_id',2,'');
		if( bn_is_empty($old_user_id) || bn_is_empty($new_user_id)  || bn_is_empty($ecology_id) ){
			return false;
		}
		if($old_user_id == $new_user_id){
			return true;
		}
		$CI->load->model('uc_user_admin_model');
		$CI->load->model('uc_user_model');
		$CI->load->model('uc_manager_ecology_model');
		$CI->load->library('StaffLib','','StaffLib');
		//获得旧管理员信息
		$sel_field = 'orgID,isLDAP,departmentID,super_admin_id,siteID';
		$where_arr = array(
                'userID' => $old_user_id,     
                'role_id' => 5,//5生态管理员
                'state' => 1,
		);
		$sel_arr = $CI->uc_user_admin_model->get_db_arr($where_arr,$sel_field);
		if(isemptyArray($sel_arr)){//没有数据
			return false;
		}
		$old_orgID = arr_unbound_value($sel_arr,'orgID',2,'');
		$old_isLDAP = arr_unbound_value($sel_arr,'isLDAP',2,'');
		$old_departmentID = arr_unbound_value($sel_arr,'departmentID',2,'');
		$old_super_admin_id = arr_unbound_value($sel_arr,'super_admin_id',2,'');
		$old_siteID = arr_unbound_value($sel_arr,'siteID',2,'');
		//加入生态企业管理员
		$user_other_arr = array(
            'orgID' => $old_orgID,
            'isLDAP' => $old_isLDAP, 
            'siteID' => $old_siteID,
		);
		$ok_arr = array(
            'user_id' => $new_user_id,
            'super_admin_id' => $old_super_admin_id,
            'role_id' => 5,//角色1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员
            'state' => 1 ,//0：停用；1：启用
            'other' => $user_other_arr
		);
		$re_boolean = $CI->StaffLib->add_ecology_onemanger($ok_arr);
		if($re_boolean != true){
			return false;
		}
		//把生态企业改在新管理员名下
		$data = array(
            'where' => 'ecology_id = ' . $ecology_id . ' and user_id = ' . $old_user_id ,
            'update_data' => array(  
                'org_id' => $old_orgID,//所属站点组织id
                'site_id' =>$old_siteID,//站点id
                'user_id' => $new_user_id,//新的生态企业管理员id
		),
		);
		$update_manager_ecology_arr = $CI->uc_manager_ecology_model->operateDB(5,$data);

		//判断老生态管理员是否还有生态企业可以管理
		$sel_field = 'id';
		$where_arr = array(
                'user_id' => $old_user_id,//旧的生态企业管理员id
		);
		$sel_arr = $CI->uc_manager_ecology_model->get_db_arr($where_arr,$sel_field);
		if(isemptyArray($sel_arr)){//没有数据没有生态，则走管理员删除流程
			$ecology_arr = array(
                'manager_id' => $old_user_id,//生态企业管理员id
			);
			$re_boolean = $this-> del_ecology_manager($ecology_arr);
			if($re_boolean){//成功
			}else{
				return false;
			}
		}
		return true;
	}

	/**
	 *
	 * @brief 删除生态企业管理员
	 * @details
	 * @param array $ecology_arr
	 $ecology_arr = array(
	 'manager_id' => $aaa,//生态企业管理员id

	 );
	 * @return boolean true成功 false失败
	 *
	 */
	public function del_ecology_manager($ecology_arr = array()){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->helper('my_dgmdate');
		$CI->load->library('API','','API');
		if(isemptyArray($ecology_arr)){//空数组
			return false;
		}
		$user_id = arr_unbound_value($ecology_arr,'manager_id',2,'');
		if(bn_is_empty($user_id) ){
			return false;
		}
		$CI->load->model('uc_user_admin_model');
		//获得当前生诚管理员的信息;如果没有父生态企业管理员，则移动到系统管理员
		$where_arr = array(
		//'super_admin_id' => $super_admin_id,//当前管理员id,
           'userID' => $user_id ,//用户id
		//'siteID' => $this->p_site_id,//站点id
           'state' => 1,//0：停用；1：启用
           'role_id'=> 5,//角色id 5生态管理员
		// 'orgID' => $orgID,//企业id
		//'type' => $type,//1：总公司管理员；2：分公司管理员；3：生态企业管理员；0：其它'
		);
		$admin_arr = $CI->uc_user_admin_model->get_next_arr($where_arr);
		$CI->load->model('uc_manager_ecology_model');
		if(isemptyArray($admin_arr)){//如果不是空数组;是生态管理员
			foreach($admin_arr as $admin_k => $admin_v){
				$super_admin_id = arr_unbound_value($admin_v,'super_admin_id',2,0);
				$siteID = arr_unbound_value($admin_v,'siteID',2,'');
				$orgID = arr_unbound_value($admin_v,'orgID',2,'');
				//获得系统/渠道/分公司管理员
				$get_data = array(
                    'select' => 'userID',
                    'where' => array('siteID' => $siteID,'orgID' => $orgID,'state' => 1),
                    'where_in' => array('role_id' => array('1','6'))//1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员

				);
				$uc_admin_arr = $CI->uc_user_admin_model->operateDB(1,$get_data);

				$ns_padmin_id = arr_unbound_value($uc_admin_arr,'userID',2,0);
				if($super_admin_id > 0){//有父管理员
					$ns_padmin_id = $super_admin_id;
				}

				//把当前生态管理员的下级生态管理员的父id，修改成自己的父id
				$update_data = array(
                    'update_data' => array('super_admin_id' => $ns_padmin_id),
                    'where' => array('super_admin_id' => $user_id,'state' => 1),
				);
				$user_admin_arr = $CI->uc_user_admin_model->operateDB(5,$update_data);
				if(!db_operate_fail($user_admin_arr)){//成功
					log_message('debug', ' update uc_user_admin_model ' . json_encode($update_data) . '  is seccuss');
				}else {
					log_message('error', ' update uc_user_admin_model ' . json_encode($update_data) . 'is fail');
				}
				//把自己的生态企业，变为父级的生态企业
				$update_data = array(
                    'update_data' => array('user_id' => $ns_padmin_id),
                    'where' => array('user_id' => $user_id),
				);

				$manager_ecology_arr = $CI->uc_manager_ecology_model->operateDB(5,$update_data);
				if(!db_operate_fail($user_admin_arr)){//成功
					log_message('debug', ' update uc_manager_ecology_model ' . json_encode($update_data) . json_encode($manager_ecology_arr) . '  is seccuss');
				}else {
					log_message('error', ' update uc_manager_ecology_model ' . json_encode($update_data) . json_encode($manager_ecology_arr) . 'is fail');
				}
				//删除,修改当前管理员状态为不可用
				$update_data = array(
                    'update_data' => array('state' => 0),
                    'where' => array('userID' => $user_id),
				);
				$user_admin_arr = $CI->uc_user_admin_model->operateDB(5,$update_data);
				if(!db_operate_fail($user_admin_arr)){//成功
					log_message('debug', ' update uc_user_admin_model ' . json_encode($update_data) . '  is seccuss');
				}else {
					log_message('error', ' update uc_user_admin_model ' . json_encode($update_data) . 'is fail');
				}
			}
		}
		return true;
	}
	/**
	 *
	 * @brief 新加生态企业管理员
	 * @details
	 * @param array 生态企业管理员变更
	 $msg_arr = array(
	 'parent_user_id' => $parent_user_id,
	 'user_id_arr' => $user_id_arr,
	 'other' => array(
	 'orgID' => $this->p_org_id,
	 'isLDAP' => $this->p_is_ldap,
	 'siteID' => $this->p_site_id,
	 ),
	 );
	 * @return false失败；true成功
	 */
	public function add_ecology_manager($msg_arr = array()){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->helper('my_dgmdate');
		$CI->load->library('API','','API');
		if(isemptyArray($msg_arr)){//如果是空数组
			return false;
		}
		$parent_user_id = arr_unbound_value($msg_arr,'parent_user_id',2,'');
		$user_id_arr = arr_unbound_value($msg_arr,'user_id_arr',1,array());
		$user_other_arr = arr_unbound_value($msg_arr,'other',1,array());
		if(!preg_match('/^[\d]+$/',$parent_user_id)){//生态管理员
			return false;
		}
		if(isemptyArray($user_id_arr) || isemptyArray($user_other_arr)){//如果是空数组
			return false;
		}
		//         $orgID = arr_unbound_value($user_other_arr,'orgID',2,'');
		//         $isLDAP = arr_unbound_value($user_other_arr,'isLDAP',2,'');
		//         $departmentID = arr_unbound_value($user_other_arr,'departmentID',2,'');
		//         $siteID = arr_unbound_value($user_other_arr,'siteID',2,'');
		$CI->load->model('uc_user_admin_model');
		$CI->load->model('uc_user_model');
		$CI->load->library('StaffLib','','StaffLib');
		foreach($user_id_arr as $user_id){
			$ok_arr = array(
                'user_id' => $user_id,
                'super_admin_id' => $parent_user_id,
                'role_id' => 5,//角色1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员
                'state' => 1 ,//0：停用；1：启用
                'other' => $user_other_arr
			);
			$re_boolean = $CI->StaffLib->add_ecology_onemanger($ok_arr);
			if($re_boolean != true){
				return false;
			}
		}
		return true;
	}

	/**
	 *
	 * @brief 根据域名,获得站点id及组织id
	 * @details
	 * @param string $url_host 域名
	 * @return array 站点id及组织id数组
	 $re_arr = array(
	 'siteID' => $siteID,
	 'orgID' => $orgID,
	 'customerCode' => $customerCode,
	 );
	 */
	public function get_siteid_orgid($url_host = ''){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->helper('my_dgmdate');
		$CI->load->library('API','','API');
		$re_arr = array();
		if(bn_is_empty($url_host)){//没有数据
			return $re_arr;
		}
		$CI->load->model('uc_site_model');
		//获得site_id
		$sel_field = 'siteID,customerCode';
		$where_arr = array(
            'domain' => $url_host,
		);
		$sel_arr = $CI->uc_site_model->get_db_arr($where_arr,$sel_field);
		if(isemptyArray($sel_arr)){//没有用户信息
			return $re_arr;
		}
		$siteID = arr_unbound_value($sel_arr,'siteID',2,'');
		$customerCode = arr_unbound_value($sel_arr,'customerCode',2,'');
		if(bn_is_empty($siteID) || bn_is_empty($customerCode) ){//没有数据
			return $re_arr;
		}
		$CI->load->model('uc_user_admin_model');
		//获得site_id
		$sel_field = 'orgID';
		$where_arr = array(
            'siteID' => $siteID,
            'role_id' => 1,
		);
		$sel_arr = $CI->uc_user_admin_model->get_db_arr($where_arr,$sel_field);
		if(isemptyArray($sel_arr)){//没有用户信息
			return $re_arr;
		}
		$orgID = arr_unbound_value($sel_arr,'orgID',2,'');
		if(bn_is_empty($orgID)  ){//没有数据
			return $re_arr;
		}
		$re_arr = array(
            'siteID' => $siteID,
            'orgID' => $orgID,
            'customerCode' => $customerCode,
		);
		return $re_arr;
	}
	 
	/**
	 *
	 * @brief 根据域名,获得站点id及组织id
	 * @details
	 * @param int $org_id 当前组织id
	 * @return array $other_arr 其它参数数组[以后补充使用]
	 $re_arr = array(
	 );
	 */
	public function get_orgup_arr($org_id = 0,$other_arr = array()){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->helper('my_dgmdate');
		$CI->load->library('API','','API');
		$ns_org_arr = array();
		$user_organizationId = $org_id;
		if(!bn_is_empty($user_organizationId)){//有数据
			if($user_organizationId > 0 ){
				$org_arr = $this->get_orgarr_by_id($user_organizationId);
				foreach($org_arr as $k => $v){
					$ns_org_id = arr_unbound_value($v,'id',2,'');
					$ns_org_name = arr_unbound_value($v,'name',2,'');
					$ns_org_arr[] = array(
                        'id' => $ns_org_id,
                        'value' => $ns_org_name
					);
				}
			}
		}
		return $ns_org_arr;
	}
	 


	/**
	 * 检查添加管理员数据
	 * @param array $data
	 */
	public function checkManagerData($data){
		//check keys
		$allow = array('user_id', 'role_id', 'w1', 'w2');
		foreach($allow as $v){
			if(!isset($data[$v])){
				log_message('error', 'the key '.$v.' is requred');
				//return array(false, 'the key '.$v.' is requred');
				return array(false);
			}
		}
		//check w1
		$allow_w   = array('key', 'value');
		$allow_w_k = $this->allow_w_k;
		if(isset($data['w1']) && is_array($data['w1'])){
			foreach($allow_w as $v1){
				if(!isset($data['w1'][$v1])){
					log_message('error', 'the key '.$v1.' in w1 is requred');
					//return array(false, 'the key '.$v1.' in w1 is requred');
					return array(false);
				}
			}

			if(!in_array($data['w1']['key'], $allow_w_k)){
				log_message('error', 'not allowed key value'.$data['w1']['key']);
				//return array(false, 'not allowed key value'.$data['w1']['key']);
				return array(false);
			}
		}
		//check w2
		if(isset($data['w2']) && is_array($data['w2'])){
			foreach($allow_w as $v2){
				if(!isset($data['w2'][$v2])){
					log_message('error', 'the key '.$v1.' in w2 is requred');
					//return array(false, 'the key '.$v1.' in w2 is requred');
					return array(false);
				}
			}

			if(!in_array($data['w2']['key'], $allow_w_k)){
				log_message('error', 'not allowed key value'.$data['w2']['key']);
				//return array(false, 'not allowed key value'.$data['w1']['key']);
				return array(false);
			}
			//第二维度单选
			if(count(explode(',', $data['w2']['value'])) > 1){
				log_message('error', 'you can only choice one option in w2');
				//return array(false, 'you can only choice one option in w2');
				return array(false);
			}
		}

		//return array(true, 'success');
		return array(true);
	}



	/**
	 * 检查维度
	 * @param array $w 维度
	 * return boolean
	 */
	public function checkW($w){
		//验证参数
		if(!in_array($w['key'], $this->allow_w_k)){
			return false;
		}
		if(!is_string($w['value']) || $w['value'] == ''){
			return false;
		}
		//加载类库
		$CI =& get_instance();
		$CI->load->model('uc_site_costcenter_model', 'costcenter');//成本中心
		//检查维度值是否合法
		if($w['key'] == 'region'){
			$area_arr = explode(',', trim(strtolower($w['value'])));
			if(!isemptyArray($area_arr)){
				return $this->checkRegion($area_arr);
			}
			return true;
		}else if($w['key'] == 'department'){
			$dept_ids = explode(',', trim(strtolower($w['value'])));
			return $this->checkDepartment($CI->p_org_id, $dept_ids);
		}else if($w['key'] == 'costcenter'){
			$cc_ids = explode(',', trim($w['value'], ' ,'));//过滤字符串两边的逗号和空格
			return $this->costcenter->checkCostCenter($CI->p_site_id, $CI->p_org_id, $cc_ids);
		}else{
			return false;
		}
	}
	
	/**
	 * 添加管理员时检查地区信息是否正确
	 * @param array $area_arr
	 * @return boolean
	 */
	public function checkRegion($area_arr){
		$CI =& get_instance();
		$CI->load->model('uc_area_model');
		
		$address_info_arr = $CI->uc_area_model->get_area($CI->p_customer_code, $CI->p_site_id);
		$city_arr = array();
		foreach($address_info_arr as $address_info){
			$city_arr[] = $address_info['area'];
		}
		
		$count = 0;
		foreach($area_arr as $area){
			if(in_array($area, $city_arr)){
				$count++;
			}
		}
		
		if($count == 0){
			return false;
		}
		return true;
	}

	/**
	 * 检查部门
	 * @author hongliang.cao@quanshi.com
	 * @param int   $org_id    组织id
	 * @param array $dept_ids  部门id
	 * @return boolean
	 *
	 */
	public function checkDepartment($org_id, $dept_ids = array()){
		$CI =& get_instance();
		$CI->load->library('UmsLib', '', 'ums');
		$data = $CI->ums->getOrganization($org_id, 'subtree', '3');
		if(empty($data)){
			log_message('error', 'get orgs from ums fail.at line '.__LINE__.'in file'.__FILE__);
			return false;
		}
		$dept_ids_arr        = array();//组织下所有的部门id
		//$dept_node_code_arr = array();//组织下所有部门的node_code
		foreach($data as $dept){
			$dept_ids_arr[]       = $dept['id'];
			//$dept_node_code_arr[] = $dept['node_code'];
		}

		if(count($dept_ids)>0){
			//return count(array_diff($dept_ids, $dept_ids_arr)) > 0 ? true : false; // array_diff()返回在A数组中存在但是在B数组中不存在的值的数组
			return count(array_diff($dept_ids, $dept_ids_arr)) > 0 ? false : true; // array_diff()返回在A数组中存在但是在B数组中不存在的值的数组
		}else{
			//return $dept_node_code_arr;
			return false;
		}
	}

}
