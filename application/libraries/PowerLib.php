<?php if(!defined('BASEPATH'))exit('No direct script access allowed');
/**
 * @category	PowerLib
 * @abstract	PowerLib 类库，主要负责对权限等的操作。
 * @filesource	PowerLib.php
 * @author		zouyan <yan.zou@quanshi.com>   Bai Xue<xue.bai_2@quanshi.com
 * @copyright	Copyright (c) UC
 * @version		v1.0
 */
class PowerLib{
	/**
	 * @abstract 构造函数
	 * @detail   特别说明：$CI不能入在构造函数中，因为当加载model时，在具体方法中使用时不成功，所以在每个方法中单独使用。
	 */
	public function __construct() {
		log_message('info', 'into class ' . __CLASS__);
	}

	/**
	 * @abstract 根据当前用户id,组织id串,站点id，去获得属性数组
	 * @param array $in_arr = array(
	 'userid' => $user_id,//用户id
	 'org_code' => $org_str,//组织id串  -500-501-502-503
	 'siteid' => $siteid//站点id
	 );
	 * @return arrary  array(
	 'from_num' => $from_num,//从哪里获得的 0没有1用户2组织3站点
	 'power_org_code' => ,//如果是组织上拿到的权限,则返回拿到权限的组织串
	 'components' => $components_arr//开通属性
	 );
	 */
	public function get_components($in_arr = array()){
		$CI =&get_instance();
		$CI->load->helper('my_publicfun');
		//$CI->load->helper('my_dgmdate');
		//$CI->load->library('API','','API');
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$user_id = isset($in_arr['userid'])?$in_arr['userid']:0;//用户id
		$org_code = isset($in_arr['org_code'])?$in_arr['org_code']:'';//组织id串 -500-501-502-503
		$siteid = isset($in_arr['siteid'])?$in_arr['siteid']:0;//站点id
		$components_arr = array();//属性
		$power_org_code = '';//如果是组织上拿到的权限,则返回拿到权限的组织串
		$from_num =0 ;//从哪里获得的 0没有1用户2组织3站点
		//判断当前用户是否有属性
		if($user_id > 0){
			$CI->load->model('UC_User_Config_Model');
			//从uc_user_config表获得属性
			//判断站点表记录是否存在
			$sel_data = array(
                     'select' =>'value',
                     'where' => array(
                             'userID' => $user_id                           
			)
			);
			$sel_arr =  $CI->UC_User_Config_Model->operateDB(1,$sel_data);
			if(!isemptyArray($sel_arr)){//不为空
				$ns_components_json = isset($sel_arr['value'])?$sel_arr['value']:'';
				if(!bn_is_empty($ns_components_json)){//不为空
					$ns_components_arr = json_decode($ns_components_json,true);
					if(!isemptyArray($ns_components_arr)){//不为空
						$components_arr = $ns_components_arr;
						$from_num =1 ;//从哪里获得的 1用户2组织3站点
					}

				}
			}
		}

		//如果为空，则需要从部门去获取权限
		if(isemptyArray($components_arr)){
			if(!bn_is_empty($org_code)){//变量不为空值
				$orgid_arr = explode("-", $org_code);// -500-501-502-503
				if(!isemptyArray($orgid_arr)){//数组不为空
					$orgid_ok_arr = array_reverse($orgid_arr) ;//对数组进行倒序
					$ns_org = $org_code;
					$ns_org_over = '';//已经使用过的组织
					foreach($orgid_ok_arr as $k => $v){
						$ns_orgnum = $v;//组织id
						$ns_org = str_ireplace($ns_org_over, '', $org_code);
						if(!bn_is_empty($ns_orgnum)){// 变量不为空值
							if($ns_orgnum > 0){
								$CI->load->model('UC_Organization_Model');
								//从uc_user_config表获得属性
								//判断站点表记录是否存在
								$sel_data = array(
                                         'select' =>'value',
                                         'where' => array(
                                            'siteID' => $siteid,
                                            'org' => $ns_org,
								)
								);
								$sel_arr =  $CI->UC_Organization_Model->operateDB(1,$sel_data);
								if(!isemptyArray($sel_arr)){//不为空
									$ns_components_json = isset($sel_arr['value'])?$sel_arr['value']:'';
									if(!bn_is_empty($ns_components_json)){//不为空
										$ns_components_arr = json_decode($ns_components_json,true);
										if(!isemptyArray($ns_components_arr)){//不为空
											$components_arr = $ns_components_arr;
											$from_num =2 ;//从哪里获得的 1用户2组织3站点
											$power_org_code = $ns_org;//如果是组织上拿到的权限,则返回拿到权限的组织串
											break;
										}

									}
								}

							}

						}
						if(!bn_is_empty($ns_org_over)){//如果不为空，则加上 -
							$ns_org_over = '-' . $ns_org_over;
						}
						$ns_org_over = '-' . $ns_orgnum . $ns_org_over;//已经使用过的组织
					}
				}
					
			}
		}
		//如果为空，则需要从站点去获取权限
		if(isemptyArray($components_arr)){
			if($siteid > 0){
				//修改2014年10月14日 @Author hao.chen@quanshi.com
				$CI->load->model('uc_site_model','site');
				$siteInfo	= $CI->site->getInfosBySiteId($siteid);
				$sel_arr	= $siteInfo[0];
				if(!isemptyArray($sel_arr)){//不为空
					$ns_components_json = isset($sel_arr['value'])?$sel_arr['value']:'';
					if(!bn_is_empty($ns_components_json)){//不为空
						$ns_components_arr = json_decode($ns_components_json,true);
						if(!isemptyArray($ns_components_arr)){//不为空
							$components_arr = $ns_components_arr;
							$from_num =3 ;//从哪里获得的 1用户2组织3站点
						}
					}
				}
			}
		}
		$re_arr = array(
            'from_num' => $from_num,
            'power_org_code' => $power_org_code,//如果是组织上拿到的权限,则返回拿到权限的组织串
            'components' => $components_arr
		);

		return $re_arr;
	}

	/**
	 * @abstract 根据当前用户id,组织id串,站点id，去uc库保存属性数组
	 * @param 	 array		$in_arr
	 * 						$in_arr = array(
	 *	 						'type' 			=> 0,	// 类型1站点权限2部门权限3用户权限
	 *	 						'id' 			=> ,	// 保存对应的id 站点是site_id; 部门是部门串-500-501-502-503;用户是用户user_id
	 *	 						'site_id' 		=> ,	// 站点id;3用户权限时可以没有值
	 *	 						'customerCode' 	=> 		// 客户编码;2部门权限3用户权限时可以没有值
	 *	 					);
	 * @return	 array		$property_arr		需要保存的权限串
	 * @return 	 boolean    成功true 失败false
	 */
	public function save_components($in_arr = array(), $property_arr = array()){
		log_message('info', __FUNCTION__ . " input->\n" . var_export(array('in_arr' => $in_arr, 'property_arr' => $property_arr), true));

		// 判断参数是否为空
		if(isemptyArray($in_arr) || isemptyArray($property_arr)){
			log_message('debug', '$in_arr or $property_arr is empty.');
			return false;
		}

		$CI =&get_instance();
		$CI->load->helper('my_publicfun');

		//获得参数值
		$type 			= arr_unbound_value($in_arr, 'type', 2, '');		// 类型1站点权限2部门权限3用户权限7生态企业权限
		$id 			= arr_unbound_value($in_arr, 'id', 2, '');			// 保存对应的id 站点是site_id; 部门是部门串-500-501-502-503;用户是用户user_id
		$site_id 		= arr_unbound_value($in_arr, 'site_id', 2, '');		// 站点id;3用户权限时可以没有值
		$customerCode 	= arr_unbound_value($in_arr, 'customerCode', 2, '');// 客户编码;2部门权限3用户权限时可以没有值

		if(bn_is_empty($type) || bn_is_empty($id)){// 为空
			log_message('debug', ' $type = ' . $type . ' $id = ' . $id . ' $site_id = ' . $site_id . ' $customerCode = ' . $customerCode . '.');
			return false;
		}

		log_message('info', ' $type = ' . $type . ' $id = ' . $id . ' $site_id = ' . $site_id . ' $customerCode = ' . $customerCode . '.');

		$property_arr  = $this->power_add_id($property_arr);

		switch($type){// 开通类型1站点权限2部门权限3用户权限
			case 1: //1站点权限
				$CI->load->model('uc_site_model');

				// 查看当前用户是否有权限记录[没有则新加,有则更新]
				$select_field 	= 'id';
				$where_arr 		= array(
                    'siteID' 		=> $id,
                    'customerCode'	=> $customerCode
				);
				$modify_arr 	= array(
                    'siteID' 		=> $id,
                    'customerCode' 	=> $customerCode,
                    'value' 		=>  json_encode($property_arr) // 属性json串
				);
				$insert_arr = $modify_arr;
				$insert_arr['createTime'] = dgmdate(time(), 'dt');
				$re_num = $CI-> uc_site_model -> updata_or_insert(2,$select_field,$where_arr,$modify_arr,$insert_arr);

				if($re_num == -2 || $re_num == -4){//失败 -1 更新记录成功 -2更新操作失败 -3新加的记录id 新加记录成功 -4新加操作失败 -5 没有操作
					$err_msg = 'update/insert  uc_site_model fail. $re_num =' . $re_num . ' ';
					log_message('error', $err_msg);
					return false;
				}
				log_message('info', 'update/insert  uc_site_model success. $re_num =' . $re_num . ' .');
				break;
			case 2: //2部门权限
				$CI->load->model('uc_organization_model');

				//查看当前用户是否有权限记录[没有则新加,有则更新]
				$select_field 	= 'id';
				$where_arr 		= array(
                    'siteID' => $site_id,
                    'org' 	 => $id 
				);
				$modify_arr 	= array(
                    'siteID' => $site_id,
                    'org' 	 => $id ,
                    'value'  =>  json_encode($property_arr) // 属性json串
				);
				$insert_arr = $modify_arr;
				$insert_arr['createTime'] = dgmdate(time(), 'dt');
				$re_num = $CI-> uc_organization_model -> updata_or_insert(1,$select_field,$where_arr,$modify_arr,$insert_arr);

				if($re_num == -2 || $re_num == -4){//失败 -1 更新记录成功 -2更新操作失败 -3新加的记录id 新加记录成功 -4新加操作失败 -5 没有操作
					$err_msg = 'update/insert  uc_organization_model fail. $re_num =' . $re_num . ' ';
					log_message('error', $err_msg);
					return false;
				}
				log_message('info', 'update/insert  uc_organization_model success. $re_num =' . $re_num . ' .');
				break;
			case 3: // 3用户权限
				$CI->load->model('UC_User_Config_Model');

				// 查看当前用户是否有权限记录[没有则新加,有则更新]
				$select_field = 'id';
				$where_arr 	  = array(
					'userID'  => $id     
				);
				$modify_arr  = array(
                    'userID' =>$id,	// 该客户的站点ID
                    'value'  =>  json_encode($property_arr)// 属性json串
				);
				$insert_arr = $modify_arr;
				$insert_arr['createTime'] = dgmdate(time(), 'dt');
				$re_num = $CI-> UC_User_Config_Model -> updata_or_insert(1,$select_field,$where_arr,$modify_arr,$insert_arr);

				if($re_num == -2 || $re_num == -4){//失败 -1 更新记录成功 -2更新操作失败 -3新加的记录id 新加记录成功 -4新加操作失败 -5 没有操作
					$err_msg = 'update/insert  UC_User_Config_Model fail. $re_num =' . $re_num . ' ';
					log_message('error', $err_msg);
					return false;
				}
				log_message('info', 'update/insert  UC_User_Config_Model success. $re_num =' . $re_num . ' .');
				break;
			default:
				break;
		}
		return true;
	}

	/**
	 * @abstract 根据用户信息，单批调用会议服务接口
	 * @param array $meet_user_arr  用户数组 下标id,siteId,billingCode,siteURL,components
	 * @param array $other_arr  其它数组
	 * array(
	 *   'customerCode' => ,//客户编码
	 *  'site_name' => , //->customer->name
	 * )
	 * @return xml xml内容 有错误返回false 成功返回true 如果部分失败，返回失败的id串，逗号分隔
	 */

	public function get_meet_api($meet_user_arr = array(),$other_arr = array()){
		$CI =& get_instance();
		$CI->load->library('API','','API');
		if( isemptyArray($meet_user_arr) || isemptyArray($other_arr) ){//如果是空数组
			return true;
		}
		$hy_xml_data = $this->get_meet_xml($meet_user_arr,$other_arr);

		write_test_file( ' hy_xml_data ' . __FUNCTION__ . time() . '$hy_xml_data.txt' ,$hy_xml_data);
		//分发账户数据（增加、修改，启用都用此接口 批量）
		$Meet_arr = $CI->API->MeetAPI($hy_xml_data,1);
		if(api_operate_fail($Meet_arr)){//失败false
			$err_msg = 'MeetAPI common acceptData fail.';
			return false;
		}else{//成功或部分成功
			log_message('debug', 'MeetAPI common acceptData success.');
			$ns_json_data = isset($Meet_arr['msg'])?$Meet_arr['msg']:'' ;
			if(!bn_is_empty($ns_json_data)){//不为空
				$ns_data_arr = json_decode($ns_json_data , TRUE );
				if(!isemptyArray($ns_data_arr)){//不是空数组
					$ns_value = isset($ns_data_arr[0])?$ns_data_arr[0]:'' ;//多个用,号分隔
					if(!bn_is_empty($ns_value)){//不为空
						return $ns_value;//返回失败的部分
					}
				}
			}
		}
		return true;
	}
	/**
	 * @abstract 根据用户信息，分批调用会议服务xml文件
	 * @details
	 * @param array $meet_user_arr  用户数组 下标id,siteId,billingCode,siteURL,components
	 * @param array $other_arr  其它数组
	 * array(
	 *   'customerCode' => ,//客户编码
	 *  'site_name' => , //->customer->name
	 * )
	 * @return xml xml内容 成功返回true,失败false,部分成功，返回失败的id串，多个,号分隔
	 */

	public function get_meet_part($meet_user_arr = array(),$other_arr = array()){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$ns_meet_user_arr = array();//每次调用的用户数组
		$is_part_succ = 0;//是否有成功的0都没有成功1有成功[可能全成功/部分成功]
		$meet_i = 0;//当前的编码
		$meet_max = 10;//最多多少个用户调用一次
		$fail_id = '';//失败的用户id,多个用逗号分隔
		foreach($meet_user_arr as $u_k => $u_v){
			$ns_meet_user_arr[$u_k] = $u_v;
			$meet_i += 1;
			if($meet_i >= $meet_max){
				$re_meet = $this->get_meet_api($ns_meet_user_arr,$other_arr);
				if($re_meet == false){//失败false
					foreach($ns_meet_user_arr as $ns_k => $ns_v){
						$ns_user_id = isset($ns_v['id'])?$ns_v['id']:0;//userid
						if($ns_user_id > 0){
							if(!bn_is_empty($fail_id)){//不为空失败的用户id,多个用逗号分隔
								$fail_id .= ',';
							}
							$fail_id .= $ns_user_id;
						}
					}
				}else{//成功或部分失败
					if($re_meet == true){//成功
						$is_part_succ = 1;//是否有成功的0都没有成功1有成功[可能全成功/部分成功]
					}else{//部分成功
						if(!bn_is_empty($re_meet)){//不为空
							if(!bn_is_empty($fail_id)){//不为空失败的用户id,多个用逗号分隔
								$fail_id .= ',';
							}
							$fail_id .= $re_meet;
						}
					}
				}
				//初始化变量
				$ns_meet_user_arr = array();//每次调用的用户数组
				$meet_i = 0;
			}
		}
		//对最后的进行处理
		if(!isemptyArray($ns_meet_user_arr)){//如果不是空数组
			$re_meet = $this->get_meet_api($ns_meet_user_arr,$other_arr);
			if($re_meet == false){//失败false
				foreach($ns_meet_user_arr as $ns_k => $ns_v){
					$ns_user_id = isset($ns_v['id'])?$ns_v['id']:0;//userid
					if($ns_user_id > 0){
						if(!bn_is_empty($fail_id)){//不为空失败的用户id,多个用逗号分隔
							$fail_id .= ',';
						}
						$fail_id .= $ns_user_id;
					}
				}
			}else{//成功或部分失败
				if($re_meet == true){//成功
					$is_part_succ = 1;//是否有成功的0都没有成功1有成功[可能全成功/部分成功]
				}else{//部分成功
					if(!bn_is_empty($re_meet)){//不为空
						if(!bn_is_empty($fail_id)){//不为空失败的用户id,多个用逗号分隔
							$fail_id .= ',';
						}
						$fail_id .= $re_meet;
					}
				}
			}
		}
		if(bn_is_empty($fail_id)){//没有数据//失败的用户id,多个用逗号分隔--都成功
			return true;
		}else{//失败或部分失败
			if($is_part_succ == 1){//是否有成功的0都没有成功1有成功[可能全成功/部分成功]--部分失败
				return $fail_id;
			}else{//都失败
				return false;
			}
		}
	}
	/**
	 * @abstract 根据用户信息，获得会议服务xml文件
	 * @details
	 * @param array $meet_user_arr  用户数组 下标id,siteId,billingCode,siteURL,components
	 * @param array $other_arr  其它数组
	 * array(
	 *   'customerCode' => ,//客户编码
	 *  'site_name' => , //->customer->name
	 * )
	 * @return xml xml内容 有错误返回false
	 */

	public function get_meet_xml($meet_user_arr = array(),$other_arr = array()){
		if(isemptyArray($meet_user_arr)){//是空数组
			return false;
		}
		$CI =&get_instance();
		$CI->load->helper('my_publicfun');
		//$CI->load->helper('my_dgmdate');
		//$CI->load->library('API','','API');
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		//载入数组转换为xml类库
		$CI->load->helper('my_arrtoxml');
		//调会议服务接口[分发账户数据（增加、修改，启用都用此接口 批量）]
		$hy_xml_data = '';
		//1 新增/修改 2 启用
		$hy_xml_data .= array_xml(array('userType'=> 1),'',2);
		$customerCode = isset($other_arr['customerCode'])?$other_arr['customerCode']:'';
		$site_name = isset($other_arr['site_name'])?$other_arr['site_name']:'';
		//循环遍历用户
		foreach($meet_user_arr as $user_k => $user_v ){
			$ns_user_id = isset($user_v['id'])?$user_v['id']:0;//userid
			$ns_user_siteId = isset($user_v['siteId'])?$user_v['siteId']:0;//站点id
			$ns_user_billingCode = isset($user_v['billingCode'])?$user_v['billingCode']:'';
			$ns_user_hostPassword = isset($user_v['hostPassword'])?$user_v['hostPassword']:'';
			$ns_user_guestPassword = isset($user_v['guestPassword'])?$user_v['guestPassword']:'';
			// $ns_user_accountId = isset($user_v['accountId'])?$user_v['accountId']:0;
			// $ns_user_hostPassword = isset($user_v['resource']['hostPassword'])?$user_v['resource']['hostPassword']:'';
			// $ns_user_guestPassword = isset($user_v['resource']['guestPassword'])?$user_v['resource']['guestPassword']:'';
			$ns_user_siteURL = isset($user_v['siteURL'])?$user_v['siteURL']:'';
			if(bn_is_empty($ns_user_siteURL)){//没有值
				$ns_user_siteURL = isset($user_v['resource']['siteURL'])?$user_v['resource']['siteURL']:'';
			}
			//判断用户是否还有components标签
			$components_arr = isset($user_v['components'])?$user_v['components']:array();
			//获得UC信息
			$uc_arr = array();//uc属性
			$summit_arr = array();//summit属性
			$tang_arr = array();//tang属性
			$radisys_arr = array();//radisys属性
			foreach($components_arr as $k => $v){
				if(is_array($v)){
					$ns_name = isset($v['name'])?$v['name']:'';
					$ns_name_lower = strtolower($ns_name);//转换为小写
					switch ($ns_name_lower) {
						case 'uc': //是uc的
							$uc_arr = $v;
							// $siteURL = isset($v['property']['siteurl'])?$v['property']['siteurl']:'';//站点url
							//$admin_type = isset($v['property']['auth'])?$v['property']['auth']:1;
							//$companyType = isset($v['property']['companytype'])?$v['property']['companytype']:0;
							// $isLDAP = isset($v['property']['isLDAP'])?$v['property']['isLDAP']:0;
							//$showOrgTree = isset($v['property']['showOrgTree'])?$v['property']['showOrgTree']:0;
							break;
						case 'summit'://summit属性
							$summit_arr = $v;
							break;
						case 'tang'://tang属性
							$tang_arr = $v;
							break;
						case 'radisys'://radisys属性
							$radisys_arr = $v;
							break;
					}

				}

			}
			$summit_Collect = arr_unbound_value($summit_arr['property'],'Collect',2,'');
			$radisys_BridgeName = arr_unbound_value($radisys_arr['property'],'BridgeName',2,'');
			$BridgeName = $radisys_BridgeName;
			if(!bn_is_empty($summit_Collect)){//不为空，则加,号
				if(!bn_is_empty($BridgeName)){//不为空，则加,号
					$BridgeName .= ',';
				}
				$BridgeName .= $summit_Collect;
			}
			//调用会议接口的用户数据
			$accept_users_arr = array();
			//$summit_pcode1 = isset($summit_arr['property']['Pcode1'])?$summit_arr['property']['Pcode1']:0;
			$accept_users_arr['pcode1'] = $ns_user_hostPassword;//$summit_pcode1;//<pcode1>1</pcode1>
			//$summit_pcode2 = isset($summit_arr['property']['Pcode2'])?$summit_arr['property']['Pcode2']:0;
			$accept_users_arr['pcode2'] = $ns_user_guestPassword;//$summit_pcode2;//<pcode2>1</pcode2>
			// $customerCode = $customerCode ;//'=> '0' ,   //客户编码
			$accept_users_arr['customerCode'] = $customerCode;
			$accept_users_arr['BridgeName'] = $BridgeName;//radisys,summit1
			$customerName =$site_name ;//'=> '0' ,   //?    ->customer->name
			$accept_users_arr['customerName'] = $customerName;

			$newAccount = 0 ;//'=> '0' ,    //?   ->0
			$accept_users_arr['newAccount'] = $newAccount;

			$newAccountForPaid = 0;//'=> '0' , //?    ->同一帐号会议冲突时，新建帐号功能(0)
			$accept_users_arr['newAccountForPaid'] = $newAccountForPaid;

			//$applicationId = isset($tang_arr['property']['applicationID'])?$tang_arr['property']['applicationID']:'PC-MEETING';//'=> 'PC-MEETING' ,  //"tang"  ->PC-MEETING
			//if(bn_is_empty($applicationId)){
			$applicationId = UC_PRODUCT_CODE;//'PC-MEETING';
			//}
			$accept_users_arr['applicationId'] = $applicationId;

			//$summit_ConfDnisAccess = isset($summit_arr['property']['ConfDnisAccess'])?$summit_arr['property']['ConfDnisAccess']:0;
			$summit_ConfDnisAccess = arr_unbound_value($summit_arr['property'],'ConfDnisAccess',2,'');
			$localAccess = 0;//'=> '0' ,   //?    ->是否显示本地接入号(0:否 1:是)contract->components->[summit]->ConfDnisAccess 1-国内本地接入(如果有该值，则置为1，如果没有，则置为0)
			if ( strstr(',' . $summit_ConfDnisAccess . ',', ',1,')){
				$localAccess = 1;
			}

			$localAccess = arr_unbound_value($uc_arr['property'],'incomingLocal',2,$localAccess);//兼容以前summit的，uc有先从uc拿,没有再用以前的方式
			$accept_users_arr['localAccess'] = $localAccess;

			$access400 = 0;//'=> '0' ,   //?    ->是否显示国内400接入号(0:否 1:是)contract->components->[summit]->ConfDnisAccess 2-国内400(如果有该值，则置为1，如果没有，则置为0)
			if ( strstr(',' . $summit_ConfDnisAccess . ',', ',2,')){
				$access400 = 1;
			}
			$access400 = arr_unbound_value($uc_arr['property'],'incoming400',2,$access400);//兼容以前summit的，uc有先从uc拿,没有再用以前的方式
			$accept_users_arr['access400'] = $access400;

			$access800 = 0;//'=> '0' ,  //?    ->是否显示国内800接入号(0:否 1:是)contract->components->[summit]->ConfDnisAccess 3-国内800(如果有该值，则置为1，如果没有，则置为0)
			if ( strstr(',' . $summit_ConfDnisAccess . ',', ',3,')){
				$access800 = 1;
			}
			$access800 = arr_unbound_value($uc_arr['property'],'incoming800',2,$access800);//兼容以前summit的，uc有先从uc拿,没有再用以前的方式
			$accept_users_arr['access800'] = $access800;

			$TaiwanAccess = 0;//'=> '0' , //?   ->是否显示台湾接入号(0:否 1:是)contract->components->[summit]->ConfDnisAccess 暂无，写0
			$TaiwanAccess = arr_unbound_value($uc_arr['property'],'incomingTw',2,$TaiwanAccess);//兼容以前summit的，uc有先从uc拿,没有再用以前的方式
			$accept_users_arr['TaiwanAccess'] = $TaiwanAccess;

			$hongKongAccess = 0;//'=> '0' ,  //?   ->是否显示香港接入号(0:否 1:是)contract->components->[summit]->ConfDnisAccess 7-香港本地接入(如果有该值，则置为1，如果没有，则置为0)
			if ( strstr(',' . $summit_ConfDnisAccess . ',', ',7,')){
				$hongKongAccess = 1;
			}
			$hongKongAccess = arr_unbound_value($uc_arr['property'],'incomingHk',2,$hongKongAccess);//兼容以前summit的，uc有先从uc拿,没有再用以前的方式
			$accept_users_arr['hongKongAccess'] = $hongKongAccess;

			$tollFree = 0;//'=> '0' ,   //?    ->是否显示国际Toll Free接入号(0:否 1:是)contract->components->[summit]->ConfDnisAccess 5-国际TollFree(如果有该值，则置为1，如果没有，则置为0)
			if ( strstr(',' . $summit_ConfDnisAccess . ',', ',5,')){
				$tollFree = 1;
			}
			$tollFree = arr_unbound_value($uc_arr['property'],'incomingInter',2,$tollFree);//兼容以前summit的，uc有先从uc拿,没有再用以前的方式
			$accept_users_arr['tollFree'] = $tollFree;

			$localToll = 0;//'=> '0' ,   //?    ->是否显示国际Local Toll接入号(0:否 1:是) contract->components->[summit]->ConfDnisAccess 4-国际Caller Pay(如果有该值，则置为1，如果没有，则置为0)
			if ( strstr(',' . $summit_ConfDnisAccess . ',', ',4,')){
				$localToll = 1;
			}
			$localToll = arr_unbound_value($uc_arr['property'],'incomingLocalToll',2,$localToll);//兼容以前summit的，uc有先从uc拿,没有再用以前的方式
			$accept_users_arr['localToll'] = $localToll;

			$allowHostCall = 1;//'=> '0' ,   //?  ->是否允许主持人外呼(0:否 1:是) 暂无写1
			$accept_users_arr['allowHostCall'] = $allowHostCall;

			$allowAttendeeCall = 1;//'=> '0' ,  //?  ->是否允许参会人外呼(0:否 1:是)暂无写1
			$accept_users_arr['allowAttendeeCall'] = $allowAttendeeCall;

			//$attendeeSurvey = isset($uc_arr['property']['attendeeSurvey'])?$uc_arr['property']['attendeeSurvey']:0;
			$attendeeSurvey = arr_unbound_value($uc_arr['property'],'attendeeSurvey',2,'');//'=> '0' ,  //?     ->参会人开完会后是否弹出调研页(0:否 1:是)contract->components->[uc]->attendeeSurvey
			$accept_users_arr['attendeeSurvey'] = $attendeeSurvey;

			//$appNewAccount = isset($uc_arr['property']['appNewAccount'])?$uc_arr['property']['appNewAccount']:0;
			$appNewAccount = arr_unbound_value($uc_arr['property'],'appNewAccount',2,'');//'=> '0' ,   //?   ->调研页可显示”申请新账号”功能模块(0:否 1:是) contract->components->[uc]->appNewAccount
			$accept_users_arr['appNewAccount'] = $appNewAccount;

			//$conferenceScale = isset($tang_arr['property']['confscale'])?$tang_arr['property']['confscale']:0;
			$conferenceScale = arr_unbound_value($tang_arr['property'],'confscale',2,'');//'=> '0' , //?   ->会议方数  contract->components->[tang]->confscale
			$accept_users_arr['conferenceScale'] = $conferenceScale;

			//$allowUserVoice = isset($uc_arr['property']['allowUserVoice'])?$uc_arr['property']['allowUserVoice']:0;
			$allowUserVoice = arr_unbound_value($uc_arr['property'],'allowUserVoice',2,'');//'=> '0' ,  //?  ->是否允许用户使用语音(0:否 1:是) contract->components->[uc]->allowUserVoice
			$accept_users_arr['allowUserVoice'] = $allowUserVoice;

			//$enableVoip = isset($uc_arr['property']['enableVoip'])?$uc_arr['property']['enableVoip']:0;
			$enableVoip = arr_unbound_value($uc_arr['property'],'enableVoip',2,'');//'=> '0' ,   //?   ->是否可以使用VOIP contract->components->[uc]->enableVoip
			$accept_users_arr['enableVoip'] = $enableVoip;

			//$pin = isset($tang_arr['property']['pin'])?$tang_arr['property']['pin']:false;
			$pin = arr_unbound_value($tang_arr['property'],'pin',2,'');//'=> '0' ,   //"tang"   是否是固定pin码会议，如果是，则只有已经调用过addParticepant接口的pin码才能入会,true：是；false：否 contract->components->[tang]->pin
			$accept_users_arr['pin'] = $pin;

			//$roles = isset($tang_arr['property']['roles'])?$tang_arr['property']['roles']:'';
			$roles = arr_unbound_value($tang_arr['property'],'roles',2,'');//'=> '0' ,   //"tang"  主持人所拥有的角色列表，其中4表示主持人，5标识主讲人，6标识参会人，多个角色使用逗号分隔	contract->components->[tang]->roles
			$accept_users_arr['roles'] = $roles;

			//$stopwhenoneuser = isset($tang_arr['property']['stopwhenoneuser'])?$tang_arr['property']['stopwhenoneuser']:0;
			$stopwhenoneuser = arr_unbound_value($tang_arr['property'],'stopwhenoneuser',2,'');//'=> '0' ,  //"tang"  只有主持人时是否终止会议(0:否 1:是) contract->components->[tang]->stopwhenoneuser
			$accept_users_arr['stopwhenoneuser'] = $stopwhenoneuser;

			//$time1 = isset($tang_arr['property']['time1'])?$tang_arr['property']['time1']:0;
			$time1 = arr_unbound_value($tang_arr['property'],'time1',2,'');//'=> '0' ,   //"tang"     主持人离线后多长时间结束会议，单位为分钟 contract->components->[tang]->time1
			$accept_users_arr['time1'] = $time1;

			//$time2 = isset($tang_arr['property']['time2'])?$tang_arr['property']['time2']:0;
			$time2 = arr_unbound_value($tang_arr['property'],'time2',2,'');//'=> '0' ,  //"tang"      只有主持人时过多长时间结束会议，单位为分钟 contract->components->[tang]->time2
			$accept_users_arr['time2'] = $time2;

			// $realreserve = isset($tang_arr['property']['realreserve'])?$tang_arr['property']['realreserve']:0;
			$realreserve = arr_unbound_value($tang_arr['property'],'realreserve',2,'');//'=> '0' , //"tang"   是否立即预约电话会议(0:否 1:是)contract->components->[tang]->realreserve
			$accept_users_arr['realreserve'] = $realreserve;

			//$hostInitialStatus = isset($summit_arr['property']['HostStraightToConference'])?$summit_arr['property']['HostStraightToConference']:0;//'=> '0' ,
			$summit_hostInitialStatus = arr_unbound_value($summit_arr['property'],'HostStraightToConference',2,'');////?  ->主持人加入电话会议的初始状态 默认写1 contract->components->[summit]->HostStraightToConference
			$radisys_hostInitialStatus = 1;//arr_unbound_value($radisys_arr['property'],'aaa',2,1);//主持人加入电话会议的初始状态			默认写1
			$hostInitialStatus = empty_to_value($summit_hostInitialStatus,$radisys_hostInitialStatus);
			$accept_users_arr['hostInitialStatus'] = $hostInitialStatus;

			//$hostVoicePrompts = isset($summit_arr['property']['Pcode1InTone'])?$summit_arr['property']['Pcode1InTone']:0;

			$summit_hostVoicePrompts = arr_unbound_value($summit_arr['property'],'Pcode1InTone',2,'');//'=> '0' ,    //?  ->主持人加入电话会议的语音提示加入会议 contract->components->[summit]->Pcode1InTone
			$radisys_hostVoicePrompts = arr_unbound_value($radisys_arr['property'],'InOutTone',2,'');//主持人加入电话会议的语音提示加入会议	contract->components->[radisys]->InOutTone
			$hostVoicePrompts = empty_to_value($summit_hostVoicePrompts,$radisys_hostVoicePrompts);
			$accept_users_arr['hostVoicePrompts'] = $hostVoicePrompts;

			//$hostExitVoicePrompts = isset($summit_arr['property']['Pcode1OutTone'])?$summit_arr['property']['Pcode1OutTone']:0;
			$summit_hostExitVoicePrompts = arr_unbound_value($summit_arr['property'],'Pcode1OutTone',2,'');//'=> '0' ,  //?   ->主持人退出电话会议的语音提示退出会议contract->components->[summit]->Pcode1OutTone
			$radisys_hostExitVoicePrompts = arr_unbound_value($radisys_arr['property'],'InOutTone',2,'');//->>>主持人退出电话会议的语音提示退出会议	contract->components->[radisys]->InOutTone
			$hostExitVoicePrompts = empty_to_value($summit_hostExitVoicePrompts,$radisys_hostExitVoicePrompts);
			$accept_users_arr['hostExitVoicePrompts'] = $hostExitVoicePrompts;

			//$attendeeInitialStatus = isset($summit_arr['property']['Pcode2Mode'])?$summit_arr['property']['Pcode2Mode']:'M';
			$summit_attendeeInitialStatus = arr_unbound_value($summit_arr['property'],'Pcode2Mode',2,'');//'=> '0' ,  //?   ->参与人加入电话会议时的初始状态 contract->components->[summit]->Pcode2Mode
			$radisys_attendeeInitialStatus = arr_unbound_value($radisys_arr['property'],'GuestMode',2,'');//->>>参与人加入电话会议时的初始状态			contract->components->[radisys]->GuestMode
			$attendeeInitialStatus = empty_to_value($summit_attendeeInitialStatus,$radisys_attendeeInitialStatus);
			$accept_users_arr['attendeeInitialStatus'] = $attendeeInitialStatus;

			//$attendeeVoicePrompts = isset($summit_arr['property']['Pcode2InTone'])?$summit_arr['property']['Pcode2InTone']:0;
			$summit_attendeeVoicePrompts = arr_unbound_value($summit_arr['property'],'Pcode2InTone',2,'');//'=> '0' ,  //?    ->参与人加入电话会议的语音提示加入会议 contract->components->[summit]->Pcode2InTone
			$radisys_attendeeVoicePrompts = arr_unbound_value($radisys_arr['property'],'InOutTone',2,'');//->>>参与人加入电话会议的语音提示加入会议	contract->components->[radisys]->InOutTone
			$attendeeVoicePrompts = empty_to_value($summit_attendeeVoicePrompts,$radisys_attendeeVoicePrompts);
			$accept_users_arr['attendeeVoicePrompts'] = $attendeeVoicePrompts;

			//$attendeeExitVoicePrompts = isset($summit_arr['property']['Pcode2OutTone'])?$summit_arr['property']['Pcode2OutTone']:0;
			$summit_attendeeExitVoicePrompts = arr_unbound_value($summit_arr['property'],'Pcode2OutTone',2,'');//'=> '0' ,//?   ->参与人退出电话会议的语音提示退出会议 contract->components->[summit]->Pcode2OutTone
			$radisys_attendeeExitVoicePrompts = arr_unbound_value($radisys_arr['property'],'InOutTone',2,'');//->>>参与人退出电话会议的语音提示退出会议	contract->components->[radisys]->InOutTone
			$attendeeExitVoicePrompts = empty_to_value($summit_attendeeExitVoicePrompts,$radisys_attendeeExitVoicePrompts);
			$accept_users_arr['attendeeExitVoicePrompts'] = $attendeeExitVoicePrompts;

			//$attendeeNoticeOthers = isset($summit_arr['property']['ValidationCount'])?$summit_arr['property']['ValidationCount']:0;
			$summit_attendeeNoticeOthers = arr_unbound_value($summit_arr['property'],'ValidationCount',2,'');//'=> '0' , //?     ->会议参与人加入电话会议时，是否通知其会议中的参与方数	contract->components->[summit]->ValidationCount
			$radisys_attendeeNoticeOthers = 0;//arr_unbound_value($radisys_arr['property'],'ValidationCount',2,'');//会议参与人加入电话会议时，是否通知其会议中的参与方数	默认写0
			$attendeeNoticeOthers = empty_to_value($summit_attendeeNoticeOthers,$radisys_attendeeNoticeOthers);
			$accept_users_arr['attendeeNoticeOthers'] = $attendeeNoticeOthers;

			// $firstHearPrompt = isset($summit_arr['property']['FirstCallerMsg'])?$summit_arr['property']['FirstCallerMsg']:0;//'=> '0' ,
			$summit_firstHearPrompt = arr_unbound_value($summit_arr['property'],'FirstCallerMsg',2,'');//?      ->第一方与会者是否需要听到“您是第一个入会者”的提示	contract->components->[summit]->FirstCallerMsg
			$radisys_arrfirstHearPrompt = 1;//arr_unbound_value($radisys_arr['property'],'aaaa',2,'');//第一方与会者是否需要听到“您是第一个入会者”的提示	默认写1
			$firstHearPrompt = empty_to_value($summit_firstHearPrompt,$radisys_arrfirstHearPrompt);
			$accept_users_arr['firstHearPrompt'] = $firstHearPrompt;

			//$recordingFunction = isset($summit_arr['property']['Taped'])?$summit_arr['property']['Taped']:0;//'=> '0' ,
			$summit_recordingFunction = arr_unbound_value($summit_arr['property'],'Taped',2,'');//?    ->是否开启电话录音功能 contract->components->[summit]->Taped
			$radisys_recordingFunction = arr_unbound_value($radisys_arr['property'],'Taped',2,'');//是否开启电话录音功能	contract->components->[radisys]->Taped
			$recordingFunction = empty_to_value($summit_recordingFunction,$radisys_recordingFunction);
			$accept_users_arr['recordingFunction'] = $recordingFunction;

			//$hostNotStart = isset($summit_arr['property']['ConfQuickStart'])?$summit_arr['property']['ConfQuickStart']:0;//'=> '0' ,
			$summit_hostNotStart = arr_unbound_value($summit_arr['property'],'ConfQuickStart',2,'');//?       ->主持人未启动电话会议，电话会议是否可以开始（只要有两方加入，就开始进行会议,不支持；支持的是，只要有主持人进入，这场会就开始。）	contract->components->[summit]->ConfQuickStart
			$radisys_hostNotStart = 1;//arr_unbound_value($radisys_arr['property'],'aaaaa',2,1);//主持人未启动电话会议，电话会议是否可以开始（只要有两方加入，就开始进行会议,不支持；支持的是，只要有主持人进入，这场会就开始。）	默认写1
			$hostNotStart = empty_to_value($summit_hostNotStart,$radisys_hostNotStart);
			$accept_users_arr['hostNotStart'] = $hostNotStart;

			//$participantNameRecord = isset($summit_arr['property']['ParticipantNameRecordAndPlayback'])?$summit_arr['property']['ParticipantNameRecordAndPlayback']:0;
			$summit_participantNameRecord = arr_unbound_value($summit_arr['property'],'ParticipantNameRecordAndPlayback',2,'');//'=> '0' //? ->是否录制参会人姓名	contract->components->[summit]->ParticipantNameRecordAndPlayback
			$radisys_participantNameRecord = arr_unbound_value($radisys_arr['property'],'PNR',2,'');//是否录制参会人姓名	contract->components->[radisys]->PNR
			$participantNameRecord = empty_to_value($summit_participantNameRecord,$radisys_participantNameRecord);
			$accept_users_arr['participantNameRecord'] = $participantNameRecord;

			$userId = $ns_user_id;//'=> '0' , //用户id    ->userID
			$accept_users_arr['userId'] = $userId;

			$billingCode = $ns_user_billingCode;//'=> '0' ,   //  用户记费码     ->billingCode  user
			$accept_users_arr['billingCode'] = $billingCode;

			$siteId = $ns_user_siteId; //'=> '0' ,   //站点id    ->users->siteId
			$accept_users_arr['siteId'] = $siteId;

			$siteName = $ns_user_siteURL ;//'=> '0' , //?     ->users->resource->siteURL
			$accept_users_arr['siteName'] = $siteName;
			$ns_hy_arr = array();//会议接口数据
			$ns_hy_arr['user'] = $accept_users_arr ;//操作的用户 调用会议接口的用户数据
			$hy_xml_data .= array_xml($ns_hy_arr,'',2);

		}
		//将数组转换为xml
		$hy_xml_data = array_xml(array('userDTO'=> $hy_xml_data),'',2);//userDTO包起来
		$hy_xml_data = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . $hy_xml_data;//加xml头
		return $hy_xml_data;
	}

	/**
	 * @abstract 根据权限类型，获得权限的初始信息数组
	 * @param 	int 	$power_type  类型1站点属性,2部门属性,3用户属性,4会议属性5用户调部门权限变更-最新的是组织权限 ；6用户调部门权限变更-最新的是站点权限7生态企业权限
	 * @return 	array 	$power_init_arr
	 *					$power_init_arr = array(
	 *						'power_class' 	=> $aaa,	//类型数组
	 *						'power_arr' 	=> $aaa,	//权限数组
	 *					);
	 */
	public function get_initpower_arr($power_type = 0){
		if($power_type == 7){
			include_once APPPATH . 'libraries/public/Power_ecology_class.php';
			$power_obj = new Power_ecology_class(1);
		}else{
			include_once APPPATH . 'libraries/public/Power_class.php';
			$power_obj = new Power_class($power_type);
		}
		$powers_arr = $power_obj->get_power();
		return $powers_arr;
	}

	/**
	 * @abstract 根据当前用户id、组织id串和站点id，去获得属性数组
	 * @param    int		$power_type		权限类型类型:1站点属性,2部门属性,3用户属性,4会议属性,5用户调部门权限变更-最新的是组织权限 ,6用户调部门权限变更-最新的是站点权限,7生态企业权限
	 * @param    array		$in_arr			获得权限的数组
	 * 						$in_arr = array(
	 *							'userid'   => $user_id, //用户id
	 *							'org_code' => $org_str, //组织id串  -500-501-502-503
	 *							'siteid'   => $siteid   //站点id
	 *						);
	 * @return	 array		$re_arr
	 *						格式如下：
	 *						$re_arr = array(
	 *							'power_class' => $power_class_arr,
	 *							'power_arr' => $power_arr
	 *						);
	 *						例如：
	 *						$re_arr = array(
	 *							'power_class' => array(
	 *								'0' => array(
	 *									'id'        => 1,
	 *									'name'      => IM设置,
	 *              					'enable'    => 1,
	 *             						'whow_type' => 5
	 *								),
	 *								'1' => array(
	 *									'id'        => 2,
	 *           						'name'      => 通话设置,
	 *          						'enable'    => 1,
	 *         							'whow_type' => 5
	 *								),
	 *							),
	 *							'power_arr' => array(
	 *	 							'UC_passDoc' => array(
	 *	 								'enable' 		=> 1,
	 *	 								'name' 			=> 可使用全时sooncore平台 IM 互传文档,
	 *	 								'boss_name' 	=> UC,
	 *	 								'boss_property' => passDoc,
	 *	 								'value_arr' 	=> array(
	 *	 									'1' => 不允许,
	 *	 									'2' => 允许
	 *	 								),
	 *	 								'default_value' => 1,
	 *	 								'regex' 		=> /^[12]+$/,
	 *	 								'whow_type' 	=> 4,
	 *	 								'type' 			=> passDoc,
	 *	 								'class' 		=> 1,
	 *	 								'value' 		=> 1,
	 *	 							),//多个用逗号分开
	 *							)
	 */
	public function get_powers_arr($power_type = 1,$in_arr = array()){
		log_message('info', __FUNCTION__ . " input->\n" . var_export(array('power_type' => $power_type, 'in_arr' => $in_arr), true));

		// 根据权限类型，获取权限的初始信息数组
		$powers_arr 	 = $this->get_initpower_arr($power_type);
		$power_class_arr = arr_unbound_value($powers_arr, 'power_class', 1, array()); 	// IM设置、通话设置、电话会议设置、网络会议设置
		$power_arr 		 = arr_unbound_value($powers_arr, 'power_arr', 1, array());		// 具体的权限数组

		// 初始化权限属性数组，属性只看user和组织的，不用看站点的
		$power_components_arr = array();

		// 根据当前用户id、组织id以及站点id，获得各个平台（如UC、Tang等）的权限属性
		$ns_components_arr 		= $this->get_components($in_arr);
		$from_num 				= isset($ns_components_arr['from_num'])?$ns_components_arr['from_num']:0;			// 从哪里获得的 1用户2组织3站点
		$power_components_arr 	= isset($ns_components_arr['components'])?$ns_components_arr['components']:array();	// 各平台的权限属性

		log_message('info', 'power from  $from_num = ' . $from_num . ';$power_components_arr is ' . $power_components_arr);

		// 遍历初始具体权限数组
		foreach($power_arr as $k => $v){
			$boss_name = arr_unbound_value($v, 'boss_name', 2, '');  // 如UC、Tang等

			// 如果平台名称不为空
			if(!bn_is_empty($boss_name)){
				$boss_property = arr_unbound_value($v, 'boss_property', 2, '');	// 具体的权限名称
				$default_value = arr_unbound_value($v, 'default_value', 2, '');	// 具体权限的默认值

				// 遍历当前用户或组织或站点的权限属性数组
				foreach($power_components_arr as $p_k => $p_v){
					if(is_array($p_v)){
						$ns_name = arr_unbound_value($p_v, 'name', 2, ''); // 如UC、Tang等

						// 判断是否是当前权限
						if(strtolower($boss_name) == strtolower($ns_name)){
							$ns_property_arr 	= arr_unbound_value($p_v, 'property', 1, array()); // 具体权限
							$ns_propeerty_value = arr_unbound_value($ns_property_arr, $boss_property, 2, $default_value); // 具体权限的值

							// 以下代码是BOSS以前的参数中间多了一个空格的，纠正，如果正常了，可以去掉，也可以不去，不影响
							if($boss_property == 'answerStrategyOverseas'){
								if(strtolower($ns_name) == 'uc'){
									$ns_answerStrategyOverseas = arr_unbound_value($ns_property_arr, 'answerStrategy Overseas', 2, '');
									if(!bn_is_empty($ns_answerStrategyOverseas)){
										$ns_propeerty_value = $ns_answerStrategyOverseas;
									}
								}
							}// 纠正结束

							$power_arr[$k]['value'] = $ns_propeerty_value;
							break ;
						}
					}
				}
			}
		}

		$re_arr = array(
            'power_class' 	=> $power_class_arr, 	// IM设置、通话设置、电话会议设置、网络会议设置
            'power_arr' 	=> $power_arr 			// 具体的权限数组
		);
		return $re_arr;
	}

	/**
	 * @abstract 保存权限
	 * @details
	 *-# 部门权限修改（难点）
	 *-# 1、	需要给BOSS发送修改模板；[boss给接口]
	 *-# 2、	修改UC部门模板,删除所属用户uc_user_config 记录
	 *-# 3、   修改该部门下的所用的用户权限[开通的用户才修改] ；保存到线程，让线程去做
	 *-# 用户权限变更问题
	 *-# 1、保存到uc_user_config记录
	 *-# 2、入线程，让线程去做[update boss 只调亚利的接口]
	 * @param 	array 	$param_array
	 * 					$param_array = array(
	 *          			'power_type'   => , 	// 权限类型 [1-3、7可用]1站点属性,2部门属性,3用户属性,4会议属性5用户调部门权限变更-最新的是组织权限 ；6用户调部门权限变更-最新的是站点权限 7生态企业权限
	 *          			'customerCode' => 0,	// 客户编码
	 *          			'org_id'       => 0,	// 站点所在的组织id
	 *          			'oper_type'    => 		// ums可以获得下级的组织类型组织类型，多个用“,”号分隔types：1企业,2生态企业,3部门,4生态企业部门,5分公司
	 *							'obj'      => array(
	 *	 								'sys'  => array(
	 *	 									'customerCode' 		=> $aaa,	// 客户编码
	 *	 									'siteID'       		=> $aaa,	// 站点id
	 *	 									'site_name'    		=> $aaa,	// 站点名称
	 *	 									'accountId'    		=> $aaa,	// 分帐id ；注意：如果有用户，则是用户自己的
	 *	 									'siteURL'      		=> $aaa,	// 地址
	 *	 									'contractId'   		=> $aaa,	// 合同id
	 *	 									'operator_id'  		=> $this->p_user_id,		// 操作发起人用户ID
	 *	 									'client_ip'    		=> $this->p_client_ip,		// 客户端ip
	 *	 									'server_ip'         => $this->p_server_ip,		// 服务端ip
	 *	 									'oper_account'      => $this->p_account,		// 操作帐号
	 *	 									'oper_display_name' => $this->p_display_name,	// 操作姓名
	 *	 									'orgID'             => $this->p_org_id,			// 所属站点，分公司，生态企业组织id[各种管理员新加时，必填]
	 *	 								),
	 *							)
	 * 					)
	 * @param 	array 	$power_arr 	权限数组（一维键值串）
	 * 					$power_arr = array(
	 *	 					'summit_ValidationCount' => 0
	 *	 				);
	 * @param 	array 	$in_arr 	获得权限的数组[根据情况，传不同的值]
	 * 					$in_arr = array(
	 *	 					'userid'   => $user_id,	// 用户id
	 *	 					'org_code' => $org_str,	// 组织id串  -500-501-502-503
	 *	 					'siteid'   => $siteid	// 站点id
	 *	 				);
	 * @return 成功true,失败 false,错误则返回错误字符串
	 */
	public function save_powers($param_array = array(), $power_arr = array(), $in_arr = array()){
		log_message('info', __FUNCTION__ . " input->\n" . var_export(array('param_array' => $param_array, 'power_arr' => $power_arr, 'in_arr' => $in_arr), true));

		$CI = &get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->helper('my_dgmdate');
		$CI->load->library('API', '', 'API');

		// 获得权限类型：权限类型 [1-3、7可用]1站点属性,2部门属性,3用户属性,4会议属性5用户调部门权限变更-最新的是组织权限 ；6用户调部门权限变更-最新的是站点权限 7生态企业权限
		$power_type   = arr_unbound_value($param_array, 'power_type', 2,'');
		// 获得客户编码
		$customerCode = arr_unbound_value($param_array, 'customerCode', 2, '');
		// 获得组织id
		$org_id 	  = arr_unbound_value($param_array, 'org_id', 2, '');
		// 获得组织类型：1企业,2生态企业,3部门,4生态企业部门,5分公司
		$oper_type    = arr_unbound_value($param_array, 'oper_type', 2, '');
		$sys_obj 	  = arr_unbound_value($param_array, 'obj', 1, array());

		// 如果权限类型、客户编码和组织id中任意一个为空
		if(bn_is_empty($power_type) || bn_is_empty($customerCode) || bn_is_empty($org_id)) {
			log_message('error', '$power_type=' . $power_type . ';$customerCode=' . $customerCode . ';$org_id=' . $org_id . 'fail.');
			return false;
		}

		log_message('debug', '$power_type=' . $power_type . ';$customerCode=' . $customerCode . ';$org_id=' . $org_id . 'success.');

		// 合同id
		$contract_id = arr_unbound_value($sys_obj['sys'], 'contractId', 2, '');
		// 地址
		$site_url = arr_unbound_value($sys_obj['sys'], 'siteURL', 2, '');

		// 如果合同id或者地址任意一个为空
		if(bn_is_empty($contract_id) || bn_is_empty($site_url) ){
			log_message('error', '$contract_id=' . $contract_id . ';$site_url=' . $site_url . 'fail.');
			return false;
		}

		log_message('debug', '$contract_id=' . $contract_id . ';$site_url=' . $site_url . 'fail.');


		$powers_arr		 = $this->get_initpower_arr($power_type);						// 获得权限库信息
		$power_class_arr = arr_unbound_value($powers_arr, 'power_class', 1, array());	// 权限分类
		$power_lib_arr	 = arr_unbound_value($powers_arr, 'power_arr', 1, array());		// 权限数组
		$userid			 = arr_unbound_value($in_arr, 'userid', 2, '');					// 用户id
		$org_code 		 = arr_unbound_value($in_arr, 'org_code', 2, '');				// 组织id串  -500-501-502-503
		$siteid			 = arr_unbound_value($in_arr, 'siteid', 2, '');					// 站点id

		// 遍历权限库中的权限数组，验证规则并为其赋值
		foreach ($power_lib_arr as $k => $v){
			// 判断当前权限是否为空
			if(!isemptyArray($v)){
				// 当前权限不为空
				$value = arr_unbound_value($v, 'value', 2, '');	// 默认值
				$regex = arr_unbound_value($v, 'regex', 2, '');	// 正则条件

				// 遍历从视图页面提交上来的权限数组
				foreach($power_arr as $p_k => $p_v){
					// 判断是不是当前权限
					if(strtolower($p_k) == strtolower($k)){
						//是当前的权限，则判断是否有正则
						if(!bn_is_empty($regex)){
							// 当前权限有正则，则进行正则匹配
							if(!preg_match($regex, $p_v)){
								// 未匹配上，则返回错误信息
								$err_msg = $p_k . ' value ' . $p_v . ' is not regex' . $regex;
								log_message('debug', $err_msg);
								echo $err_msg;
								return $err_msg;
							}
						}
						// 将视图页面提交的权限值赋给权限库
						$value = $p_v;
						$power_lib_arr[$k]['value'] = $value;
						break;
					}
				}
			}
		}

		// 根据当前用户id、组织id串和站点id获得属性数组
		$ns_components_arr = $this->get_components($in_arr);

		// 判断属性数组是否为空
		if(!isemptyArray($ns_components_arr)){
			// 如果不是空数组
			$from_num 				= isset($ns_components_arr['from_num'])?$ns_components_arr['from_num']:0;  			// 从哪里获得的 ：1用户2组织3站点
			$power_components_arr 	= isset($ns_components_arr['components'])?$ns_components_arr['components']:array(); // 属性串
		}

		// 属性串如果没有id，则给它加上id
		$power_components_arr = $this->power_add_id($power_components_arr);

		log_message('info', 'power from ' . $from_num);

		// 更新权限信息
		$is_uc_power_change   = 0; // 是否有IM 配置、通话配置，权限值变化：0没有，1有
		$is_boss_power_change = 0; // 是否有电话会议配置 、网络会议配置，权限值变化：0没有，1有

		// 遍历当前权限属性串数组，判断权限是否有变化
		foreach($power_components_arr as $k => $v){
			if(is_array($v)){
				$ns_name 		 = arr_unbound_value($v, 'name', 2, '');			// 名称：summit,tang,radisys,UC
				$ns_name_lower 	 = strtolower($ns_name);							// 将名称转为小写字母
				$ns_property_arr = arr_unbound_value($v, 'property', 1, array());	// 属性串数组

				// 遍历当前属性串数组
				foreach($ns_property_arr as $p_k => $p_v){
					$new_suffix  = $ns_name . '_' . $p_k; // 新的下标
					$ns_value	 = arr_unbound_value($power_lib_arr[$new_suffix], 'value', 2, '');	// 从权限库中获得新值
					$ns_is_save	 = 0; // 是否保存新值：0不保存,1保存

					// 如果新值为空，旧值不为空时
					if(bn_is_empty($ns_value) && (!bn_is_empty($p_v))){// 邹燕在这写的旧值是$ns_value，这个逻辑不对，我把$ns_value换成$p_v后就可以保存成功了
						// 保存新值[0不保存,1保存]
						$ns_is_save = 1;
					}

					// 如果新值不为空
					if(!bn_is_empty($ns_value)){
						// 判断新值和旧值是否相等
						if($ns_value != $p_v){
							// 不相等，则保存新值[0不保存,1保存]
							$ns_is_save = 1;
						}
					}

					// 如果有更新值
					if($ns_is_save == 1){
						// 保存更新值
						$power_components_arr[$k]['property'][$p_k] = $ns_value;
						// 获得当前更新值的分类，多个用逗号分隔
						$ns_class = arr_unbound_value($power_lib_arr[$new_suffix], 'class', 2, '');

						// 判断是否有IM设置或者通话设置变化
						if (strstr(',' . $ns_class . ',', ',1,') || strstr(',' . $ns_class . ',', ',2,')){
							// 0没有，1有
							$is_uc_power_change = 1;
						}

						// 判断是否有电话会议设置或者网络会议配置变化
						if (strstr(',' . $ns_class . ',', ',3,') || strstr(',' . $ns_class . ',', ',4,')){
							// 0没有，1有
							$is_boss_power_change = 1;
						}
					}
				}
			}
		}

		log_message('debug', '$is_uc_power_change=' . $is_uc_power_change . ';$is_boss_power_change=' . $is_boss_power_change);

		// 判断属性值是否有变化，有变化则保存新的值
		if(($is_uc_power_change == 1) || ($is_boss_power_change == 1)){
			$ns_id 				= ''; 	// 保存属性时对应的id:站点是site_id,部门是部门串-500-501,用户是user_id
			$ns_power_id 		= ''; 	// 获得需要改为权限的用户的id, 站点是：所属组织id; 部门是部门串-500-501-502-503;用户是用户user_id；5、6是用户id串,格式 [1,2,3]
			$ns_power_ower 		= ''; 	// 站点为siteurl ；组织为 组织id串，用户为：用户最接近的组织串或站点siteurl
			$change_boss_mb 	= 0;	// 是否更新BOSS模板0不更新1更新
			$boss_uuid 			= '';	// 修改站点或组织模板时用；可以是站点URL、location、部门名称,站点模板时siteurl；组织权限时组织部门id串
			// $boss_propType 	= 2;	// 属性类型 1=合同属性；2=账号属性（默认值）
			$power_operate_type = 0;	// 属性操作类型1站点属性,2部门属性,3用户属性
				
			switch ($power_type) {// 1站点属性,2部门属性,3用户属性,4会议属性
				case 1:	// 1站点属性
					$power_operate_type = 1;		// 属性操作类型1站点属性,2部门属性,3用户属性
					$ns_id 				= $siteid;
					$ns_power_id 		= $org_id;	// 站点所在的组织id
					$change_boss_mb 	= 1;		// 是否更新BOSS模板0不更新1更新
					$boss_uuid 			= $site_url;
					$ns_power_ower 		= $site_url;// 站点为siteurl ；组织为 组织id串，用户为：用户最接近的组织串或站点siteurl
					//  $boss_propType 	= 1;		// 属性类型 1=合同属性；2=账号属性（默认值）
					break;
				case 2:	// 2部门属性
					$power_operate_type = 2;		// 属性操作类型1站点属性,2部门属性,3用户属性
					$ns_id 				= $org_code;
					$ns_power_id 		= $org_code;// 组织部门id串
					$change_boss_mb 	= 1;		// 是否更新BOSS模板0不更新1更新
					$boss_uuid 			= $org_code;
					$ns_power_ower 		= $org_code;// 站点为siteurl，组织为 组织id串，用户为：用户最接近的组织串或站点siteurl
					break;
				case 3: // 3用户属性
					$power_operate_type = 3;		// 属性操作类型1站点属性,2部门属性,3用户属性
					$ns_id 				= $userid;
					$ns_power_id 		= $userid;	// 用户id
					$ns_power_arr 		= array(
                       'org_code' 	=> $org_code,	// 组织id串
                       'siteid' 	=> $siteid,		// 站点id
                       'siteurl'	=> $site_url,	// 站点url
					);
					$ns_power_ower 		= $this->get_power_uuid($ns_power_arr); // 站点为siteurl ；组织为 组织id串，用户为：用户最接近的组织串或站点siteurl
					break;
				case 4: //4 会议属性
					$ns_id 				= '';
					break;
				case 5: // 5用户调部门权限变更-最新的是组织权限
					$power_operate_type = 2;		// 属性操作类型1站点属性,2部门属性,3用户属性
					$ns_id 				= '';
					$ns_power_ower 		= $org_code;// 站点为siteurl ；组织为 组织id串，用户为：用户最接近的组织串或站点siteurl
					break;
				case 6: // 6用户调部门权限变更-最新的是站点权限
					$power_operate_type = 2;		// 属性操作类型1站点属性,2部门属性,3用户属性
					$ns_id 				= '';
					$ns_power_ower 		= $site_url;// 站点为siteurl ；组织为 组织id串，用户为：用户最接近的组织串或站点siteurl
					break;
				case 7: // 7生态企业权限
					$power_operate_type = 2;		// 属性操作类型1站点属性,2部门属性,3用户属性
					$ns_id 				= $org_code;
					$ns_power_id 		= $org_code;// 组织部门id串
					$change_boss_mb 	= 1;		// 是否更新BOSS模板0不更新1更新
					$boss_uuid 			= $org_code;
					$ns_power_ower 		= $org_code;// 站点为siteurl ；组织为 组织id串，用户为：用户最接近的组织串或站点siteurl
					break;
				default:
					break;
			}

			// 站点权限和组织权限时，查看模板是否存在，不存在，则先调用BOSS创建模板
			if($change_boss_mb == 1){// 是否更新BOSS模板0不更新1更新
				// 保存boss及UC模板
				$in_param_arr = array(
                    'templateUUID' 		=>	$boss_uuid,				// 可以是站点URL、location、部门名称,站点模板时siteurl；组织权限时组织部门id串
                    'contractId' 		=> $contract_id,			// 合同id
                    'components_arr' 	=> $power_components_arr,	// 权限
                    'power_type' 		=> $power_operate_type,		// uc中权限类型
                    'power_id' 			=> $ns_id,					// uc中对应的保存对应的id 站点是site_id; 部门是部门串-500-501-502-503;用户是用户user_id
                    'site_id' 			=> $siteid,					// 站点id;3用户权限时可以没有值
                    'customerCode' 		=> $customerCode			// 客户编码;2部门权限3用户权限时可以没有值
				);
				$re_boolean = $this->save_power_mb($in_param_arr);
				if(!$re_boolean){// 失败
					return false;
				}
			}else{// 是否保存uc模板
				if($power_type == 1 || $power_type == 2 || $power_type == 3){// 1站点属性,2部门属性3用户属性
					$comp_in_arr = array(
                       'type' 			=> $power_operate_type,	// 类型1站点权限2部门权限3用户权限
                       'id' 			=> $ns_id,				// 保存对应的id 站点是site_id; 部门是部门串-500-501-502-503;用户是用户user_id
                       'site_id' 		=> $siteid,				// 站点id;3用户权限时可以没有值
                       'customerCode' 	=> $customerCode,		// 客户编码;2部门权限3用户权限时可以没有值
					);
					$uc_com_boolean =  $this->save_components($comp_in_arr,$power_components_arr);
					if(!$uc_com_boolean){
						log_message('error', 'save_components  fail.');
					}
					log_message('debug', 'save_components  success.');
				}
			}

			// 保存线程
			if($is_boss_power_change == 1 ){
				$power_in_arr = array(
                   'type' 		=> $power_type,				// 类型1站点权限2部门权限3用户权限4会议属性5用户调部门权限变更-最新的是组织权限 ；6用户调部门权限变更-最新的是站点权限7生态企业权限
                   'id' 		=> $ns_power_id,			// 保存对应的id 站点是：所属组织id; 部门是部门串-500-501-502-503;用户是用户user_id；5、6是用户id串,格式 [1,2,3]
                   'power_ower' => $ns_power_ower,			// 用户权限变更时传值; 站点是站点siteurl; 部门是部门串-500-501-502-503;用户是用户最近的组织串或siteurl；
                   'components' => $power_components_arr,	// 最新的权限数组
                   'oper_type' 	=> $oper_type,				// ums可以获得下级的组织类型组织类型，多个用,号分隔types：空：不需要[如type=5或6时]；1:企业 2:生态企业 3:部门 4:生态企业部门 5 分公司 逗号分割的字符串
                   'obj' 		=> $sys_obj,				// 调用boss接口需要用到的
				);
				$success_json = json_encode($power_in_arr);

				// 接口参数
				$data = 'type=5&value=' . urlencode($success_json);
				$uc_thread_arr = $CI->API->UCAPI($data,2,array('url' => base_url('')));
				if(api_operate_fail($uc_thread_arr)){// 失败
					log_message('error', 'UCAPI NO 1 is fail.');
					return false;
				}else{
					log_message('debug', 'UCAPI NO 1 is success.');
				}
			}
		}else{// 没有变动
			if($power_type == 7){// 7生态企业权限[直接会保存boss 模板及uc模板]
				$CI->load->library('OrganizeLib', '', 'OrganizeLib');

				$re_org_bolean = $CI->OrganizeLib->org_has_power($org_code);

				if(!$re_org_bolean){// 没有权限，则需要新加权限模板
					// 保存boss及UC模板
					$in_param_arr = array(
                        'templateUUID' 		=>$org_code,				// 可以是站点URL、location、部门名称,站点模板时siteurl；组织权限时组织部门id串
                        'contractId' 		=> $contract_id,			// 合同id
                        'components_arr' 	=> $power_components_arr,	// 权限
                        'power_type' 		=> 2,						// uc中权限类型
                        'power_id' 			=> $org_code,				// uc中对应的保存对应的id 站点是site_id; 部门是部门串-500-501-502-503;用户是用户user_id
                        'site_id' 			=> $siteid,					// 站点id;3用户权限时可以没有值
                        'customerCode' 		=> $customerCode			// 客户编码;2部门权限3用户权限时可以没有值
					);
					$re_boolean = $this->save_power_mb($in_param_arr);
					if(!$re_boolean){// 失败
						return false;
					}
				}
			}
		}
		return true;
	}

	/**
	 * @abstract 保存BOSS及UC权限模板：
	 * @param array $in_param_arr 参数数组
	 $in_param_arr = array(
	 'templateUUID' =>$aaa,//可以是站点URL、location、部门名称,站点模板时siteurl；组织权限时组织部门id串
	 'contractId' => $aaa,//合同id
	 'components_arr' => $aaa,//权限
	 'power_type' => $aaa,//uc中权限类型
	 'power_id' => $aaa,//uc中对应的保存对应的id 站点是site_id; 部门是部门串-500-501-502-503;用户是用户user_id
	 'site_id' => $aaa,//站点id;3用户权限时可以没有值
	 'customerCode' => $aaa//客户编码;2部门权限3用户权限时可以没有值

	 );
	 * @return boolean true成功 false 失败
	 *
	 */
	public function save_power_mb($in_param_arr = array()){
		$templateUUID = arr_unbound_value($in_param_arr,'templateUUID',2,'');
		$contractId = arr_unbound_value($in_param_arr,'contractId',2,'');
		$components_arr = arr_unbound_value($in_param_arr,'components_arr',1,array());
		$power_type = arr_unbound_value($in_param_arr,'power_type',2,'');
		$power_id = arr_unbound_value($in_param_arr,'power_id',2,'');
		$site_id = arr_unbound_value($in_param_arr,'site_id',2,'');
		$customerCode = arr_unbound_value($in_param_arr,'customerCode',2,'');

		//更新BOSS模板
		$component_prop_arr = array(
                'templateUUID' => $templateUUID,//$boss_uuid,//可以是站点URL、location、部门名称,站点模板时siteurl；组织权限时组织部门id串
                'contractId' => $contractId,//$contract_id,//合同id
                'propType' => 2,//$boss_propType,//属性类型 1=合同属性；2=账号属性（默认值）
                'components' => $components_arr,//$power_components_arr,//权限
		);
		log_message('debug', ' save BOSS contractComponentProps api  $component_prop_arr = ' . json_encode($component_prop_arr) . '.');
		
		
		$re_boolean = $this->boss_save_power_mb($component_prop_arr);
		//TODO 此处同步BOSS有问题
		//$re_boolean = 1;
		if(!$re_boolean){//失败
			log_message('error', ' save BOSS contractComponentProps api  fail.');
			return false;
		}
		log_message('debug', ' save BOSS contractComponentProps api  success.');
		//更新uc模板
		$comp_in_arr = array(
               'type' => $power_type,//类型1站点权限2部门权限3用户权限
               'id' => $power_id,//$ns_id,//保存对应的id 站点是site_id; 部门是部门串-500-501-502-503;用户是用户user_id
               'site_id' => $site_id,//$siteid,//站点id;3用户权限时可以没有值
               'customerCode' => $customerCode,//$customerCode,//客户编码;2部门权限3用户权限时可以没有值
		);
		$uc_com_boolean =  $this->save_components($comp_in_arr,$components_arr);
		if(!$uc_com_boolean){
			log_message('error', ' save_components  fail.');
			return false;
		}
		log_message('debug', ' save_components  success.');
		return true;
	}

	/**
	 * @abstract 属性串,如果没有id,则加上id
	 * @param 	 array 		$components_arr 	权限
	 * @return 	 array 		有id的不操作，没有id 加上
	 */
	public function power_add_id($components_arr){
		log_message('debug',  ' old $components_arr =' . json_encode($components_arr) . '.');

		// 确定是radisys,还是summit+radisys
		$has_radisys = 0; 	// 0没有radisys1有radisys
		$has_summit  = 0;	// 0没有summit有summit
		foreach($components_arr as $k => $v){
			if(is_array($v)){
				$ns_name = arr_unbound_value($v, 'name', 2, '');
				switch (strtolower($ns_name)) {
					case 'summit':
						$has_summit = 1;// 0没有summit有summit
						break;
					case 'radisys':
						$has_radisys = 1;// 0没有radisys1有radisys
						break;
					default:
						break;
				}
			}
		}
		include_once APPPATH . 'libraries/public/Component_class.php';
		$component_obj = new Component_class();

		foreach($components_arr as $k => $v){
			if(is_array($v)){
				$ns_name = arr_unbound_value($v, 'name', 2, '');
				//如果没有id，则把id加上
				$ns_component_id = arr_unbound_value($v, 'id', 2, '');

				if(bn_is_empty($ns_component_id)){// 没有值
					// 如果是radisys，则需要去判断是哪个radisys
					if(strtolower($ns_name) == 'radisys'){
						if($has_summit == 1){ // 0没有summit有summit
							$ns_name = 'summitradisys';
						}
					}
					$ns_component_id 		 = $component_obj->get_comid($ns_name);
					$ns_component_id 		 = empty_to_value($ns_component_id,0);
					$components_arr[$k]['id']= $ns_component_id; // 改变id 的值
				}
			}
		}
		log_message('debug',  ' new $components_arr =' . json_encode($components_arr) . '.');
		// 把uc属性排到最后来处理
		$components_arr = $this->power_change_order(array('uc'), $components_arr);
		return $components_arr;
	}

	/**
	 * @abstract 对属性串，按指定的序号得新排序
	 * @param 	 array 		$order_arr 		新的序号数组;值全部小写[没有在里面的，排在前面]
	 *						$order_arr = array(
	 *							'uc'
	 *						);
	 * @param 	 array 		$components_arr 权限
	 * @return 	 array 		重新排好序的数组
	 */
	public function power_change_order($order_arr, $components_arr){
		$re_components 			= array();
		$in_components_arr 		= array();	// 在里面的数组
		$notin_components_arr 	= array();	// 不在里面的数组
		
		//不在序号的值选排在最前面
		foreach($components_arr as $k => $v){
			if(is_array($v)){
				$ns_name 		= arr_unbound_value($v, 'name', 2, '');
				$ns_low_name 	= strtolower($ns_name);
				if(deep_in_array($ns_low_name, $order_arr)){// 在里面
					$in_components_arr[$ns_low_name] = $v;	// 在里面的数组
				}else{
					$notin_components_arr[] = $v;// 在里面的数组
				}
			}
		}

		if(!isemptyArray($in_components_arr)){// 如果不是空数组
			//对在里面的重新进行排序
			foreach($order_arr as $in_v){
				$new_components_arr = arr_unbound_value($in_components_arr, $in_v, 1, array());
				if(!isemptyArray($new_components_arr)){// 如果不是空数组
					$notin_components_arr[] = $new_components_arr;
				}
			}
		}
		
		$re_components = $notin_components_arr;
		return $re_components;
	}

	/**
	 *
	 * @abstract 向BOSS保存站点/组织模板
	 *  @param array $component_prop_arr 调用接口的参数
	 $component_prop_arr = array(
	 'uuid' => $boss_uuid,//可以是站点URL、location、部门名称,站点模板时siteurl；组织权限时组织部门id串
	 'contractId' => $contract_id,//合同id
	 'components' => $power_components_arr,//权限
	 );
	 * @return boolean true 成功 false失败
	 */
	public function boss_save_power_mb($component_prop_arr){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		log_message('debug', __FUNCTION__ . ' $component_prop_arr=' . json_encode($component_prop_arr) . '.');
		//write_test_file( '' . __FUNCTION__ . time() . '.txt' ,  json_encode($component_prop_arr));
		$CI =& get_instance();
		$CI->load->library('API','','API');
		if(isemptyArray($component_prop_arr)){//如果是空数组
			return false;
		}
		$templateUUID = arr_unbound_value($component_prop_arr,'templateUUID',2,'');//可以是站点URL、location、部门名称,站点模板时siteurl；组织权限时组织部门id串
		$contractId = arr_unbound_value($component_prop_arr,'contractId',2,'');//合同id
		$components = arr_unbound_value($component_prop_arr,'components',1,array());//权限
		$components = $this->power_add_id($components);
		$component_prop_arr['components'] = $components;
		if( bn_is_empty($templateUUID) || bn_is_empty($contractId) ){//名称不为空
			return false;
		}
		if(isemptyArray($components)){//如果是空数组
			return false;
		}

		$boss_json = json_encode($component_prop_arr);
		log_message('debug', 'boss_mb_save $boss_json=' . $boss_json . '.');
		$boss_type = 2;//1创建模板属性 2修改模板属性
		//判断模板是否存在
		$data_boss ="contractId={$contractId}&uuid=".trim($templateUUID);
		log_message('debug', "prepari to invoke boss api.....");
		$boss_has_arr = $CI->API->BOSSAPI('',5,array('url'=>$data_boss ));
		log_message('debug', "invoke boss api sucess.....");
		if(api_operate_fail($boss_has_arr)){//失败
			$err_msg = 'boss api core-service/api/contractComponentProps/getByContractIdAndUuid? fail.';
			log_message('error', $err_msg);
			return false;
		}else{
			echo json_encode($boss_has_arr);
			$props_body = arr_unbound_value($boss_has_arr,'data',2,'');
			if(bn_is_empty($props_body)){//没有数据
				$boss_type = 1;//1创建模板属性 2修改模板属性
			}
			log_message('debug', 'contractComponentProps/getByContractIdAndUuid?' . $data_boss);
		}
		if($boss_type == 1 ){
			$boss_arr = $CI->API->BOSSAPI($boss_json,3);//创建模板属性
			if(api_operate_fail($boss_arr)){//失败

				log_message('error', ' BOSS core-service/api/contractComponentProps/batchCreateContractComponentProps api  fail.');
				return false;
			}else{
				log_message('debug', 'BOSS core-service/api/contractComponentProps/batchCreateContractComponentProps api success.');
				return true;
			}
		}else{
			$boss_arr = $CI->API->BOSSAPI($boss_json,4);//修改模板属性
			if(api_operate_fail($boss_arr)){//失败
				log_message('error', ' BOSS core-service/api/contractComponentProps/batchModifyContractComponentProps api  fail.');
				return false;
			}else{
				log_message('debug', 'BOSS core-service/api/contractComponentProps/batchModifyContractComponentProps api success.');
				return true;
			}
			
		}
		return true;
	}

	/**
	 *
	 * @abstract 获得当前用户的权限情况，获得站点siteurl/组织串
	 *  @param array $in_arr 调用接口的参数
	 $in_arr = array(
	 'org_code' => $org_code,//组织id串
	 'siteid' =>$siteID,//站点id
	 'siteurl' =>,//站点url
	 );
	 * @return boolean true 成功 false失败
	 */
	public function get_power_uuid($in_arr = array()){
		$org_code = arr_unbound_value($in_arr,'org_code',2,'');
		$siteID = arr_unbound_value($in_arr,'siteid',2,'');
		$siteurl = arr_unbound_value($in_arr,'siteurl',2,'');
		$user_power_key = $siteurl;
		//获得组织/站点权限
		$power_arr = array(
            'userid' => 0,//用户id,站点属性时写0
            'org_code' => $org_code,//组织id串
            'siteid' =>$siteID,//站点id
		);

		$ns_siteorg_components_arr = $this->get_components($power_arr);
		if(!isemptyArray($ns_siteorg_components_arr)){//不是空数组
			$ns_siteorg_from_num = isset($ns_siteorg_components_arr['from_num'])?$ns_siteorg_components_arr['from_num']:0;//从哪里获得的 1用户2组织3站点
			$ns_siteorg_code = isset($ns_siteorg_components_arr['power_org_code'])?$ns_siteorg_components_arr['power_org_code']:'';//
			$ns_siteorg_com_arr = isset($ns_siteorg_components_arr['components'])?$ns_siteorg_components_arr['components']:array();
			// if(!isemptyArray($ns_site_components_arr)){//如果不是空数组
			//$all_site_components_arr[$siteID] = $ns_site_components_arr;

			// }
		}
		if($ns_siteorg_from_num == 2){//组织权限
			$user_power_key = $ns_siteorg_code;
		}else{//站点权限
			$user_power_key = $siteurl;
		}
		return $user_power_key;
	}
}
