<?php if (!defined('BASEPATH'))	exit('No direct script access allowed');

/**
 * @class StaffLib
 * @brief StaffLib 类库，主要负责对UMS员工信息的获得、修改、新加方法。
 * @file StaffLib.php
 * @author zouyan <yan.zou@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
class StaffLib{
	/**
	 *
	 * @brief 构造函数
	 * @details
	 * 特别说明：$CI不能入在构造函数中，因为当加载model时，在具体方法中使用时不成功，所以在每个方法中单独使用。
	 */
	public function __construct() {
		log_message('info', 'into class ' . __CLASS__ . '.');
	}
	/**
	 *
	 * @brief 根据帐号id，获得帐号所在的组织信息：
	 * @details
	 * @param int $user_id  帐号id
	 * @param int $arr_type  返回的数组类型1一维2二维
	 * @return array 帐号所在的组织信息数组
	 *
	 */
	public function get_user_org_arr($user_id = 0,$arr_type = 2){
		log_message('info', __FUNCTION__ . " input->\n" . var_export(array('user_id' => $user_id, 'arr_type' => $arr_type), true));
		$CI =& get_instance();
		//$CI->load->helper('my_publicfun');
		$CI->load->helper('my_dgmdate');
		$CI->load->library('API','','API');
		$ne_org_arr = array();
		$siteURL = $user_id;//5782  ;// 当前用户id
		$get_org_arr = $CI->API->UMS_Special_API('',15,array('url' => $siteURL));
		if(api_operate_fail($get_org_arr)){//失败
			$err_msg = ' usm api rs/organizations/' . $siteURL . ' fail .';
			log_message('error', $err_msg);
		}else{
			$ne_org_arr = arr_unbound_value($get_org_arr,'data',1,array());
			$err_msg = ' usm api rs/organizations/' . $siteURL . ' success .';
			log_message('debug', $err_msg);
			if($arr_type == 1){//一维数组
				$ne_org_arr = twoarr_to_onearr($ne_org_arr);
			}
		}
		log_message('debug', '$ne_org_arr= ' . json_encode($ne_org_arr));
		return $ne_org_arr;
	}
	/**
	 *
	 * @brief 根据帐号id，获得帐号详情：
	 * @details
	 * @param int $user_id  帐号id
	 * @return array 帐号详情数组
	 *
	 */
	public function get_user_by_id($user_id = 0){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->helper('my_dgmdate');
		$CI->load->library('API','','API');
		//调用接口获得用户详情
		$user_info_data = array();
		if(!preg_match('/^[\d]+$/',$user_id)){
			log_message('info', '11111 = '.$user_id);
			return $user_info_data;
		}
		

		$siteURL = 'userId=' . $user_id . '&productID=' . UC_PRODUCT_ID ;////不加productID的话，就不会验证产品状态

		$uc_user_arr = $CI->API->UMS_Special_API('',11,array('url' => $siteURL));

		if(api_operate_fail($uc_user_arr)){//失败
			$err_msg = ' usm api rs/users/getUserById?' . $siteURL . ' fail .';
			log_message('info', $err_msg);
		}else{
			$user_info_data = arr_unbound_value($uc_user_arr,'data',1,array());
			$err_msg = ' usm api rs/users/getUserById?' . $siteURL . ' success .';
			log_message('info', $err_msg);
		}
		
		log_message('info', '$user_info_data= ' . json_encode($user_info_data) . '.');
		return $user_info_data;
	}

	/**
	 * @abstract 	对单个员工进行重置密码操作
	 * @param 		string 		$user_id  		需要重置密码的员工
	 * @param 		array 		$where_arr  	获得密码规则条件
	 *	 						$where_arr = array(
	 *	 							'org_id'  => $org_id,
	 *	 							'site_id' => $site_id,
	 *	 						);
	 * @return 		booblean  	成功true 失败false
	 *
	 */
	public function reset_pwd($user_id = '',$where_arr = array()){
		log_message('info', __FUNCTION__ . " input->\n" . var_export(array('user_id' => $user_id, 'where_arr' => $where_arr), true));

		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->helper('my_dgmdate');
		$CI->load->library('API', '', 'API');

		// 判断用户id是否为空
		if(bn_is_empty($user_id)){
			return false;
		}

		// 判断用户名是否符合正则表达式要求
		if(!preg_match("/^[\d]+$/", $user_id)){
			return false;
		}

		//获得系统密码复杂性1、8-30位，不限制类型2、8-30位数字与字母组合3、8-30位数字、符号与字母组合
		$CI->load->model('uc_pwd_manage_model');
		$sel_field = 'complexity_type';
		$sel_arr = $CI->uc_pwd_manage_model->get_db_arr($where_arr, $sel_field);
		$password_complexity = arr_unbound_value($sel_arr, 'complexity_type', 2, 1);

		return $this->reset_password($user_id, $password_complexity);
	}

	/**
	 * @abstract 	根据用户及站点密码规则，重置密码，并发送邮件或短信
	 * @param 		string 		$user_id  				需要重置密码的员工
	 * @param 		string 		$password_complexity  	密码复杂性
	 * @return 		booblean  	成功true 失败false
	 *
	 */
	public function reset_password($user_id = '', $password_complexity = 0){
		log_message('info', __FUNCTION__ . " input->\n" . var_export(array('user_id' => $user_id, 'password_complexity' => $password_complexity), true));

		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->helper('my_dgmdate');
		$CI->load->library('API', '', 'API');

		// 判断用户id是否为空
		if(bn_is_empty($user_id)){
			return false;
		}

		// 判断用户id是否符合正则表达式要求
		if(!preg_match('/^[\d]+$/', $user_id)){
			return false;
		}

		// 按密码规则随机生成密码
		$ns_type = '1,2,5';
		switch ($password_complexity) {
			case 2:// 8-30位数字与字母组合
				$ns_type = '1,2';
				break;
			default:
				break;
		}
		$in_arr = array(
            'length' => 8,			// 长度
            'type'	 =>	$ns_type,	// 包含类型:多个用","号分隔， 1数字、2大小写字母、3大写字母、4小写字母、5特殊字符
		);
		$ns_new_pwd = rand_str($in_arr);

		//修改用户密码
		$password = $ns_new_pwd;
		$data_ums = $user_id . '/password'  ;
		$ums_arr = $CI->API->UMS_Special_API($password, 22, array('url'=>$data_ums ));
		if(api_operate_fail($ums_arr)){//失败
			log_message('error', 'ums api /rs/users/id/' . $user_id . '/password fail.');
			return false;
		}else{
			log_message('debug', 'ums api /rs/users/id/' . $user_id . '/password success.');
		}

		//修改用户登陆修改密码
		$CI->load->model('uc_user_model');

		$modify_data = array(
           'update_data' =>array('isResetPwd' => 1),
           'where' => array('userID' => $user_id)
		);
		$CI->uc_user_model->operateDB(5, $modify_data);

		//根据用户id获得用户信息
		$ns_user_arr = $this->get_user_by_id($user_id);
		if(isemptyArray($ns_user_arr)){//如果是空数组
			return false;
		}
		$mobileNumber = arr_unbound_value($ns_user_arr,'mobileNumber',2,'');//手机号
		$email = arr_unbound_value($ns_user_arr,'email',2,'');//email
		//邮件或短信发送新密码
		return true;
	}
	/**
	 *
	 * @brief 新建组织，从别的组织调入员工：
	 * @details
	 *
	 * @param array $user_id_arr 需要调入的用户id {"userid":5810,"user_name":"sss","orgid":528,"org_name":BOSS组}//,"org_pid":521
	 * @param int $org_id 新加的组织id
	 *  @param array $other_arr 其它参数
	 $other_arr = array(
	 'site_id' => $aaa,//站点id
	 'obj' => array(
	 'sys' => array(
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
	 )
	 );
	 * @return boolean true 成功 false 失败
	 *
	 */
	public function neworg_get_user($user_id_arr = array(),$org_id = '',$other_arr = array()){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->library('API','','API');
		$CI->load->library('OrganizeLib','','OrganizeLib');
		if(bn_is_empty($org_id)){//没有数据
			log_message('debug', '  $org_id is empty .');
			return false;
		}
		log_message('debug', '  $org_id=' . $org_id . ' .');
		if(isemptyArray($user_id_arr) ||  isemptyArray($other_arr)){//空数组
			log_message('debug', '  $user_id_arr or $other_arr is empty .');
			return false;
		}
		log_message('debug', '  $user_id_arr=' . json_encode($user_id_arr) . ' $other_arr=' . json_encode($other_arr) . ' .');

		$site_id = arr_unbound_value($other_arr,'site_id',2,'');//新加的组织id
		$sys_arr = arr_unbound_value($other_arr['obj'],'sys',1,array());
		$sys_obj = arr_unbound_value($other_arr,'obj',1,array());
		$operator_id = arr_unbound_value($sys_obj['sys'],'operator_id',2,'');
		$siteURL =  arr_unbound_value($sys_obj['sys'],'siteURL',2,'');//站点siteurl
		if(bn_is_empty($site_id) || bn_is_empty($siteURL)){//没有数据
			log_message('debug', '  $site_id or $siteURL is empty .');
			return false;
		}
		log_message('debug', '  $site_id=' . $site_id . ' $siteURL=' . $siteURL . ' .');

		//根据组织id，获得组织信息
		$CI->load->library('OrganizeLib','','OrganizeLib');
		$new_org_arr = $CI-> OrganizeLib->get_org_by_id($org_id);
		$org_name = arr_unbound_value($new_org_arr,'name',2,'');//新加的组织名称
		$org_pid = arr_unbound_value($new_org_arr,'parentId',2,'');//新加组织的父组织id
		$org_code = arr_unbound_value($new_org_arr,'nodeCode',2,'');//新加组织的组织id串
		$CI->load->library('PowerLib','','PowerLib');
		$CI->load->model('uc_org_manager_model');
		//员工调岗（需要注意部门权限）（如果新调入的部门或者老的部门中有权限，需要和现有的部门权限保持一致，走账号的修改流程）【只要员工自己有权限，不处理】[流程]
		//调ums变更组织
		//[上面条件]保存线程，走线程update流程
		//获得新组织id的权限，并判断此权限是组织权限[用户没有自己权限时需要直接更新权限]还是站点权限[看旧组织权限情况]
		//$org_str 组织串
		//user 属性只看user 和 组织的,不用看站点
		$power_in_arr = array(
             'userid' => 0,//用户id
             'org_code' => $org_code,//新的组织id串  -500-501-502-503
             'siteid' => 0//站点id
		);
		$neworg_power_components_arr = array();
		$power_org_code = '';//当前组织权限组织串
		$ns_components_arr = $CI->PowerLib->get_components($power_in_arr);
		if(!isemptyArray($ns_components_arr)){//如果不是空数组
			$from_num = isset($ns_components_arr['from_num'])?$ns_components_arr['from_num']:0;//从哪里获得的 0没有1用户2组织3站点
			$power_org_code = isset($ns_components_arr['power_org_code'])?$ns_components_arr['power_org_code']:'';//
			$neworg_power_components_arr = isset($ns_components_arr['components'])?$ns_components_arr['components']:array();
		}
		$has_neworg_power = 0;//是否有新组织权限0没有1有
		if(!isemptyArray($neworg_power_components_arr)){//如果不是空数组
			$has_neworg_power = 1;//是否有新组织权限0没有1有
		}

		$not_update_pwoer_arr = array();//不需要更新权限，形式：array(125,158,258)
		$update_org_pwoer_arr = array();//需要更新到当前组织权限，形式：array(125,158,258)
		$update_org_site_arr = array();//需要更新到当前站点权限，形式：array(125,158,258)
		foreach($user_id_arr as $k => $v){//遍历调入的用户数组
			$user_id = arr_unbound_value($v,'userid',2,'');//用户id
			//根据用户id，判断用户是否是组织管理者
			// $is_manager = arr_unbound_value($v,'is_manager',2,'');//是否组织管理者0不是1是
			$ns_ok_sys_obj = $sys_obj;
			//            //获得用户类型
			//            $user_type_arr = array(
			//                'userid' => $user_id,//用户id
			//                'adminstate' => 1,//0：停用；1：启用
			//            );
			//            $user_type = $this->get_usertype_byuserid($user_type_arr);
			//            $ns_ok_sys_obj['sys']['user_type'] = $user_type;
			$old_org_id = arr_unbound_value($v,'orgid',2,'');//旧组织id
			if($org_id != $old_org_id){//新旧组织不一样，才是调岗

				$is_manager_boolean = $CI->uc_org_manager_model->user_is_org_manager($user_id);
				$is_manager = 0;
				if($is_manager_boolean){
					$is_manager = 1;
				}
				//调ums变更组织
				$data_arr =array(
                    "id" => $user_id,
                    "from" => $old_org_id,
                    "to" => $org_id
				);
				log_message('info', '$data_arr='.var_export($data_arr, true));
				$is_change_success = 1;//变更组织是否成功0没有成功1成功
				//调用ums更改用户所在组织
				$change_org_arr = $CI->API->UMS_Special_API(json_encode($data_arr),13);
				if(api_operate_fail($change_org_arr)){//失败
					$err_msg = ' usm api rs/organizations/change_organization $user_id=' . $user_id . ' fail .';
					log_message('error', $err_msg);
					$is_change_success = 0;//变更组织是否成功0没有成功1成功
					//return false;
				}else{
					//$change_org_data = arr_unbound_value($change_org_arr,'data',1,array());
					$err_msg = ' usm api rs/organizations/change_organization $user_id=' . $user_id . '  success .';
					log_message('debug', $err_msg);
					// return true;
				}

				if($is_change_success == 1){//变更组织是否成功0没有成功1成功
					//如果是旧组织管理者,新旧组织不一样时，删除旧组织管理者
					//if($org_id != $old_org_id){
					// if($is_manager == 1){
					//设置成组织管理者
					//                                $in_arr = array(
					//                                    'org_id' => $org_id,//组织id
					//                                    'site_id' => $site_id,//站点id
					//                                    'user_id' => $user_id,//用户id
					//                                    'isset' => 1,//0取消，1设置修改
					//                                );
					//                              $sys_arr = $sys_arr;
					//                              $new_org_arr = $CI-> OrganizeLib->modify_manager($in_arr,$sys_arr);
					//                            $where_arr = array(
					//                                'user_id' => $user_id,
					//                            );
					//                            $modify_arr = array(
					//                                'org_id' => $aaa,
					//                                'site_id' => $aaa,
					//                                'user_id' => $user_id,
					//                            );
					//                           $CI->uc_org_manager_model->set_manager($where_arr,$modify_arr);
					// }else{
					//如果是组织管理者，则取消组织管理者
					if($is_manager == 1){
						$in_arr = array(
                                    'org_id' => $old_org_id,//组织id
                                    'site_id' => $site_id,//站点id 
                                    'user_id' => $user_id,//用户id
                                    'isset' => 0,//0取消，1设置修改
						);
						// $sys_arr = $sys_arr;
					//}
					$operate_boolean = $CI->OrganizeLib->modify_manager($in_arr,$sys_arr);
					// $CI->uc_org_manager_model->del_org_manager($user_id);
					//}

					 }


					//判断用户是否有自己的权限
					$user_boolean = $this->user_has_power($user_id);
					if($user_boolean){//用户有自己权限
						$not_update_pwoer_arr[] = $user_id;//不需要更新权限
					}else{
						if($has_neworg_power == 1){//是否有新组织权限0没有1有
							$update_org_pwoer_arr[] = $user_id;//需要更新到当前组织权限
						}else{//是否为站点权限[如果旧组织有权限，则需要修改用户权限为站点权限，如果旧组织也是站点权限，则不用更新]
							//获得旧组织权限
							//旧的组织
							$ns_old_org_arr = arr_unbound_value($old_orgid_arr,$old_org_id,1,array());
							if(isemptyArray($ns_old_org_arr)){//如果是空数组
								$ns_old_org_arr = $CI-> OrganizeLib->get_org_by_id($old_org_id);
								$old_orgid_arr[$old_org_id] = $ns_old_org_arr;
							}
							$old_org_code = arr_unbound_value($ns_old_org_arr,'nodeCode',2,'');//旧组织的组织id串

							//先从数组中获取
							$ns_oldorg_power_components_arr = arr_unbound_value($old_orgid_power_arr,$old_org_id,1,array());//旧组织权限数组
							if(isemptyArray($ns_oldorg_power_components_arr)){//如果是空数组,则再去获取
								$power_in_arr = array(
                                     'userid' => 0,//用户id
                                     'org_code' => $old_org_code,//组织id串  -500-501-502-503
                                     'siteid' => 0//站点id
								);
								$ns_oldorg_components_arr = $CI->PowerLib->get_components($power_in_arr);
								if(!isemptyArray($ns_oldorg_components_arr)){//如果不是空数组
									//$ns_oldorg_from_num = isset($ns_oldorg_components_arr['from_num'])?$ns_oldorg_components_arr['from_num']:0;//从哪里获得的 0没有1用户2组织3站点
									//$ns_oldorg_power_org_code = isset($ns_oldorg_components_arr['power_org_code'])?$ns_oldorg_components_arr['power_org_code']:'';//
									$ns_oldorg_power_components_arr = isset($ns_oldorg_components_arr['components'])?$ns_oldorg_components_arr['components']:array();
									$old_orgid_power_arr[$old_org_id] = $ns_oldorg_power_components_arr;
								}

							}
							if(!isemptyArray($ns_oldorg_power_components_arr)){//如果不是空数组,有旧部门权限,更新
								$update_org_site_arr[] = $user_id;//需要更新到当前站点权限
							}else{//是站点权限，不用更新
								$not_update_pwoer_arr[] = $user_id;//不需要更新权限
							}
						}
					}

				}
				//发送组织消息
				$CI->load->library('Informationlib','','Informationlib');
				$msg_arr = array(
                    'user_id' => $user_id,//用户id
                    'new_org_id' => $org_id,//新组织id
                    'old_org_id' => $old_org_id,//旧组织id
				);
				$CI->Informationlib->send_ing($sys_arr,array('msg_id' => 2,'msg_arr' => $msg_arr));
			}
		}
		//日志
		$CI->load->library('LogLib','','LogLib');
		$log_in_arr = $sys_arr;
		$re_id = $CI->LogLib ->set_log(array('5','6'),$log_in_arr);
		//分别对不对权限修改用户进行操作
		//不需要更新权限,不进行操作了
		if(!isemptyArray($not_update_pwoer_arr)){

		}
		//需要更新到当前组织权限
		if(!isemptyArray($update_org_pwoer_arr)){
			//保存线程，走线程update流程
			// 保存线程
			$power_in_arr = array(
               'type' => 5,//类型1站点权限2部门权限3用户权限4会议属性5用户调部门权限变更-最新的是组织权限 ；6用户调部门权限变更-最新的是站点权限
               'id' => '[' . implode(',', $update_org_pwoer_arr) . ']',//保存对应的id 站点是：所属组织id; 部门是部门串-500-501-502-503;用户是用户user_id；5、6是用户id串,格式 [1,2,3]
               'power_ower' => $power_org_code,//用户权限变更时传值; 站点是站点siteurl; 部门是部门串-500-501-502-503;用户是用户最近的组织串或siteurl；5：最新调入部门所有的权限组织串；6：当前站点siteurl
               'components' => $neworg_power_components_arr,//最新的权限数组               
               'oper_type' => '',// 组织类型，多个用,号分隔types：空：不需要[如type=5或6时]；1:企业 2:生态企业 3:部门 4:生态企业部门 5 分公司 逗号分割的字符串
               'obj' => $ns_ok_sys_obj,//调用boss接口需要用到的
			);
			$success_json = json_encode($power_in_arr);
			//接口参数
			$data = 'type=5&value=' . urlencode($success_json);
			$uc_thread_arr = $CI->API->UCAPI($data,2,array('url' => base_url('')));
			if(api_operate_fail($uc_thread_arr)){//失败
				log_message('error', 'UCAPI NO 1 is fail.');
			}else{

				log_message('debug', 'UCAPI NO 1 is success.');
			}
		}
		//需要更新到当前站点权限
		if(!isemptyArray($update_org_site_arr)){
			//获得站点权限
			$power_in_arr = array(
                 'userid' => 0,//用户id
                 'org_code' => '',//组织id串  -500-501-502-503
                 'siteid' => $site_id//站点id
			);
			$site_components_arr = array();
			$ns_components_arr = $CI->PowerLib->get_components($power_in_arr);
			if(!isemptyArray($ns_components_arr)){//如果不是空数组
				$from_num = isset($ns_components_arr['from_num'])?$ns_components_arr['from_num']:0;//从哪里获得的 0没有1用户2组织3站点
				$site_components_arr = isset($ns_components_arr['components'])?$ns_components_arr['components']:array();
			}
			//保存线程，走线程update流程
			$power_in_arr = array(
               'type' => 6,//类型1站点权限2部门权限3用户权限4会议属性5用户调部门权限变更-最新的是组织权限 ；6用户调部门权限变更-最新的是站点权限
               'id' => '[' . implode(',', $update_org_site_arr) . ']',//保存对应的id 站点是：所属组织id; 部门是部门串-500-501-502-503;用户是用户user_id；5、6是用户id串,格式 [1,2,3]
               'power_ower' => $siteURL,//用户权限变更时传值; 站点是站点siteurl; 部门是部门串-500-501-502-503;用户是用户最近的组织串或siteurl；5：最新调入部门所有的权限组织串；6：当前站点siteurl
               'components' => $site_components_arr,//最新的权限数组
               'oper_type' => '',// 组织类型，多个用,号分隔types：空：不需要[如type=5或6时]；1:企业 2:生态企业 3:部门 4:生态企业部门 5 分公司 逗号分割的字符串
               'obj' => $ns_ok_sys_obj,//调用boss接口需要用到的
			);
			$success_json = json_encode($power_in_arr);
			//接口参数
			$data = 'type=5&value=' . urlencode($success_json);
			$uc_thread_arr = $CI->API->UCAPI($data,2,array('url' => base_url('')));
			if(api_operate_fail($uc_thread_arr)){//失败
				log_message('error', 'UCAPI NO 1 is fail.');
			}else{
				log_message('debug', 'UCAPI NO 1 is success.');
			}
		}
		return true;
	}
	/**
	 *
	 * @brief 单个用户部门变动时，权限需要变更的则直接保存线程：
	 * @details
	 * @param array $msg_arr
	 $msg_arr = array(
	 'user_id' => $user_id,//用户id
	 'old_org_code' => $old_org_id,//旧组织id串
	 'org_code' => $org_id,//新组织id串
	 'obj' => array(
	 'sys' => array(
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
	 )
	 );
	 * @return boolean true 成功 (0不需要更新,1更新为站点权限，其它权限组织id串);false 失败
	 *
	 */
	public function get_user_power_changetype($msg_arr = array()){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->library('API','','API');
		$user_id = arr_unbound_value($msg_arr,'user_id',2,'');
		$old_org_code = arr_unbound_value($msg_arr,'old_org_code',2,'');//旧组织id串
		$sys_obj = arr_unbound_value($msg_arr,'obj',2,'');//
		//        //获得当前用户类型
		//        $user_type_arr = array(
		//            'userid' => $user_id,//用户id
		//            'adminstate' => 1,//0：停用；1：启用
		//        );
		//        $user_type = $this->get_usertype_byuserid($user_type_arr);
		//        $sys_obj['sys']['user_type'] = $user_type;

		$site_id = arr_unbound_value($sys_obj['sys'],'siteID',2,'');//
		$siteURL = arr_unbound_value($sys_obj['sys'],'siteURL',2,'');//
		$org_code = arr_unbound_value($msg_arr,'org_code',2,'');;//新组织id串
		$org_id = get_last_part($org_code, '-');
		$old_org_id = get_last_part($old_org_code, '-');
		$CI->load->library('OrganizeLib','','OrganizeLib');
		$CI->load->library('PowerLib','','PowerLib');
		$CI->load->model('uc_org_manager_model');
		//如果是旧组织管理者,新旧组织不一样时，删除旧组织管理者
		if($org_id != $old_org_id){
			$CI->uc_org_manager_model->del_org_manager($user_id);
		}
		$power_type = 0;//0不更新权限;1新组织权限2站点权限
		//判断用户是否有自己的权限
		$user_boolean = $this->user_has_power($user_id);
		if($user_boolean){//用户有自己权限
			//$not_update_pwoer_arr[] = $user_id;//不需要更新权限
			$power_type = 0;//0不更新权限;1新组织权限2站点权限
		}else{
			$power_in_arr = array(
                 'userid' => 0,//用户id
                 'org_code' => $org_code,//新的组织id串  -500-501-502-503
                 'siteid' => 0//站点id
			);
			$neworg_power_components_arr = array();
			$power_org_code = '';//当前组织权限组织串
			$ns_components_arr = $CI->PowerLib->get_components($power_in_arr);
			if(!isemptyArray($ns_components_arr)){//如果不是空数组
				$from_num = isset($ns_components_arr['from_num'])?$ns_components_arr['from_num']:0;//从哪里获得的 0没有1用户2组织3站点
				$power_org_code = isset($ns_components_arr['power_org_code'])?$ns_components_arr['power_org_code']:'';//
				$neworg_power_components_arr = isset($ns_components_arr['components'])?$ns_components_arr['components']:array();
			}
			if(!isemptyArray($neworg_power_components_arr)){//如果不是空数组
				$power_type = 1;//0不更新权限;1新组织权限2站点权限
			}else{//是否为站点权限[如果旧组织有权限，则需要修改用户权限为站点权限，如果旧组织也是站点权限，则不用更新]
				//获得旧组织权限
				//先从数组中获取
				//$ns_oldorg_power_components_arr = arr_unbound_value($old_orgid_power_arr,$old_org_id,1,array());//旧组织权限数组
				//if(isemptyArray($ns_oldorg_power_components_arr)){//如果是空数组,则再去获取
				$power_in_arr = array(
                         'userid' => 0,//用户id
                         'org_code' => $old_org_code,//组织id串  -500-501-502-503
                         'siteid' => 0//站点id
				);
				$ns_oldorg_components_arr = $CI->PowerLib->get_components($power_in_arr);
				if(!isemptyArray($ns_oldorg_components_arr)){//如果不是空数组
					//$ns_oldorg_from_num = isset($ns_oldorg_components_arr['from_num'])?$ns_oldorg_components_arr['from_num']:0;//从哪里获得的 0没有1用户2组织3站点
					//$ns_oldorg_power_org_code = isset($ns_oldorg_components_arr['power_org_code'])?$ns_oldorg_components_arr['power_org_code']:'';//
					$ns_oldorg_power_components_arr = isset($ns_oldorg_components_arr['components'])?$ns_oldorg_components_arr['components']:array();
					$old_orgid_power_arr[$old_org_id] = $ns_oldorg_power_components_arr;
				}

				//}
				if(!isemptyArray($ns_oldorg_power_components_arr)){//如果不是空数组,有旧部门权限,更新
					$power_type = 2;//0不更新权限;1新组织权限2站点权限
				}else{//是站点权限，不用更新
					$power_type = 0;//0不更新权限;1新组织权限2站点权限
				}
			}
		}
		switch ($power_type) {//0不更新权限;1新组织权限2站点权限
			case 0://0不更新权限;
				return true;
				break;
			case 1:    //1新组织权限
				//保存线程，走线程update流程
				// 保存线程
				$power_in_arr = array(
                   'type' => 5,//类型1站点权限2部门权限3用户权限4会议属性5用户调部门权限变更-最新的是组织权限 ；6用户调部门权限变更-最新的是站点权限
                   'id' => '[' . $user_id . ']',//保存对应的id 站点是：所属组织id; 部门是部门串-500-501-502-503;用户是用户user_id；5、6是用户id串,格式 [1,2,3]
                   'power_ower' => $power_org_code,//用户权限变更时传值; 站点是站点siteurl; 部门是部门串-500-501-502-503;用户是用户最近的组织串或siteurl；5：最新调入部门所有的权限组织串；6：当前站点siteurl
                   'components' => $neworg_power_components_arr,//最新的权限数组               
                   'oper_type' => '',// 组织类型，多个用,号分隔types：空：不需要[如type=5或6时]；1:企业 2:生态企业 3:部门 4:生态企业部门 5 分公司 逗号分割的字符串
                   'obj' => $sys_obj,//调用boss接口需要用到的
				);
				$success_json = json_encode($power_in_arr);
				//接口参数
				$data = 'type=5&value=' . urlencode($success_json);
				$uc_thread_arr = $CI->API->UCAPI($data,2,array('url' => base_url('')));
				if(api_operate_fail($uc_thread_arr)){//失败
					log_message('error', 'UCAPI NO 1 is fail.');
					return false;
				}else{
					log_message('debug', 'UCAPI NO 1 is success.');
					return true;
				}

				break;
			case 2:    //2站点权限
				//获得站点权限
				$power_in_arr = array(
                     'userid' => 0,//用户id
                     'org_code' => '',//组织id串  -500-501-502-503
                     'siteid' => $site_id//站点id
				);
				$site_components_arr = array();
				$ns_components_arr = $CI->PowerLib->get_components($power_in_arr);
				if(!isemptyArray($ns_components_arr)){//如果不是空数组
					$from_num = isset($ns_components_arr['from_num'])?$ns_components_arr['from_num']:0;//从哪里获得的 0没有1用户2组织3站点
					$site_components_arr = isset($ns_components_arr['components'])?$ns_components_arr['components']:array();
				}
				//保存线程，走线程update流程
				$power_in_arr = array(
                   'type' => 6,//类型1站点权限2部门权限3用户权限4会议属性5用户调部门权限变更-最新的是组织权限 ；6用户调部门权限变更-最新的是站点权限
                   'id' => '[' . $user_id . ']',//保存对应的id 站点是：所属组织id; 部门是部门串-500-501-502-503;用户是用户user_id；5、6是用户id串,格式 [1,2,3]
                   'power_ower' => $siteURL,//用户权限变更时传值; 站点是站点siteurl; 部门是部门串-500-501-502-503;用户是用户最近的组织串或siteurl；5：最新调入部门所有的权限组织串；6：当前站点siteurl
                   'components' => $site_components_arr,//最新的权限数组
                   'oper_type' => '',// 组织类型，多个用,号分隔types：空：不需要[如type=5或6时]；1:企业 2:生态企业 3:部门 4:生态企业部门 5 分公司 逗号分割的字符串
                   'obj' => $sys_obj,//调用boss接口需要用到的
				);
				$success_json = json_encode($power_in_arr);
				//接口参数
				$data = 'type=5&value=' . urlencode($success_json);
				$uc_thread_arr = $CI->API->UCAPI($data,2,array('url' => base_url('')));
				if(api_operate_fail($uc_thread_arr)){//失败
					log_message('error', 'UCAPI NO 1 is fail.');
					return false;
				}else{
					log_message('debug', 'UCAPI NO 1 is success.');
					return true;
				}
				break;
		}
		return true;

	}
	/**
	 *
	 * @brief 从当前帐号组织id移动帐号id到新组织id：
	 * @details
	 * @param string $user_ids 用户id,多个用,号分隔
	 * @param int $org_id 当前组织id
	 * @param int $new_org_id 移动到的新组织id
	 * @return boolean true 成功 false 失败
	 *
	 */
	public function change_org($user_ids = '',$org_id = 0,$new_org_id = 0 ){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->library('API','','API');
		if(bn_is_empty($user_ids)){//为空
			return true;
		}
		$ns_user_arr = explode(",", $user_ids);
		//$CI->load->library('Informationlib','','Informationlib');
		foreach ($ns_user_arr as $v){
			$ns_user_id = $v;
			$data_arr =array(
                "id" => $ns_user_id,
                "from" => $org_id,
                "to" => $new_org_id
			);
			//调用ums更改用户所在组织
			$change_org_arr = $CI->API->UMS_Special_API(json_encode($data_arr),13);
			if(api_operate_fail($change_org_arr)){//失败
				$err_msg = ' usm api rs/organizations/change_organization fail .';
				log_message('error', $err_msg);
				return false;
			}else{
				//$change_org_data = arr_unbound_value($change_org_arr,'data',1,array());
				$err_msg = ' usm api rs/organizations/change_organization success .';
				log_message('debug', $err_msg);
				//return true;
			}
		}
		return true;

	}
	/**
	 *
	 * @brief 根据成本中心id及组织id获得帐号信息：
	 * @details
	 * @param array $in_array  传入数组信息
	 $in_array = array(
	 'cost_id' => ,
	 'org_id' =>,
	 'site_id' =>
	 );
	 * @return array  返回用户数组   *
	 */
	public function get_cost_users($in_array = array()){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->library('API','','API');
		$cost_id = arr_unbound_value($in_array,'cost_id',2,'');
		$org_id = arr_unbound_value($in_array,'org_id',2,'');
		$site_id = arr_unbound_value($in_array,'site_id',2,'');
		$p_org_id = arr_unbound_value($in_array, 'p_org_id',2,'');
		$user_data = array();
		if(bn_is_empty($cost_id) || bn_is_empty($org_id)  || bn_is_empty($site_id)){//有数据
			log_message('debug', '参数错误');
		}else{
			$CI->load->library('OrganizeLib','','OrganizeLib');
			if($cost_id == 0){//成本中心为0，获取"未指定成本中心"分组里的员工
				//组织下的所有用户
				$other_arr = array(
                    'not_stop_type' => '',//如果组织类型有变动,还可以获得的组织类型[多个用逗号分隔];为空则表代：不受限止
                    'parent_type' => '',//父组织类型,可以为空，为空：可以进行获得下一级
				);
				//$user_data = $CI-> OrganizeLib->get_all_user_byorgid($p_org_id,'1,3,5','',$other_arr);
				//测试：518
				$user_data = $CI-> OrganizeLib->get_all_user_byorgid(518,'1,3,5','',$other_arr);

				//所有已经分配到成本中心分组的用户
				$CI->load->model('uc_costcenter_user_model');
				$other_cost_users = $CI->uc_costcenter_user_model->get_all_cost_center_user('',2);//成本中心里所有已分组的员工
				$other_cost_userIds = array();
				foreach($other_cost_users as $_user_id){
					$other_cost_userIds[] = $_user_id['user_id'];
				}

				//过滤
				if($org_id > 0){//如果有组织过滤
					//获得属于当前组织并且没有分配到其他分组里的成本中心人员
					foreach($user_data as $k=>$_user){
						if( in_array($_user['id'],$other_cost_userIds) || $_user['organizationId']!=$org_id ){
							unset($user_data[$k]);
						}
					}
				}else{//没有组织过滤
					//获得没有分配到其他分组里的成本中心人员
					foreach($user_data as $k=>$_user){
						if(in_array($_user['id'],$other_cost_userIds)){
							unset($user_data[$k]);
						}
					}
				}
			}else{//指定成本中心分组的用户
				//获得当前成本中心用户id数组
				$CI->load->model('uc_costcenter_user_model');
				//多个用,号分隔
				$ns_userid_str = $CI->uc_costcenter_user_model->get_all_cost_center_user($cost_id,1);
				//如果有用户
				if(!bn_is_empty($ns_userid_str)){//如果不是空
					if($org_id > 0){//如果有组织过滤
						$in_arr = array(
                            'org_id' => $org_id,//当前组织id
                            'site_id' => $site_id,//当前站点id
						);
						$user_data = $CI-> OrganizeLib-> get_costusers_by_orgid($in_arr,explode(',',$ns_userid_str),2);
					}else{//没有组织过滤
						//调用ums的根据用户IDs查询用户接口
						$in_users ='[' .  $ns_userid_str . ']';
						$ums_api_arr = $CI->API->UMS_Special_API($in_users,2);
						if(!api_operate_fail($ums_api_arr)){//成功
							$user_data = arr_unbound_value($ums_api_arr,'data',1,array());
						}else{//失败
							log_message('error', 'ums api fail');
						}
					}
				}
			}
		}
		return $user_data;
	}
	/**
	 *
	 * @brief 根据成本中心id及组织id站点id，删除成本中心：
	 * @details
	 * @param array $in_array  传入数组信息
	 $in_array = array(
	 'cost_id' => ,
	 'org_id' =>,
	 'site_id' =>
	 );
	 * @return boolean TRUE成功 FALSE 失败
	 *
	 */
	public function del_cost($in_array = array()){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->library('API','','API');
		$cost_id = arr_unbound_value($in_array,'cost_id',2,'');
		$org_id = arr_unbound_value($in_array,'org_id',2,'');
		$site_id = arr_unbound_value($in_array,'site_id',2,'');
		if(bn_is_empty($cost_id) || bn_is_empty($org_id)  || bn_is_empty($site_id)){//有数据
			return false;
		}
		if($cost_id>0){
			//删除成本中心人员记录
			$CI->load->model('uc_costcenter_user_model');
			$where_arr = array(
                'cost_id' => $cost_id,
			);
			$del_arr = array(
               'where' => $where_arr,

			);
			$re_del_arr = $CI-> uc_costcenter_user_model -> operateDB(4,$del_arr);
			if(db_operate_fail($re_del_arr)){//失败
				return false;
			}else{
				//删除成本中心
				$CI->load->model('uc_site_costcenter_model');
				$where_arr = array(
                    'org_id' => $org_id,
                    'site_id' => $site_id,
                    'id' => $cost_id,
				);
				$del_arr = array(
                   'where' => $where_arr,

				);
				$re_del_arr = $CI-> uc_site_costcenter_model -> operateDB(4,$del_arr);
				if(db_operate_fail($re_del_arr)){//失败
					return false;
				}else{
					return true;
				}
			}
		}else{
			return false;
		}

			
			
	}

	/**
	 * @abstract 	开通/关闭单个用户：
	 * @param 		array 		$in_array 		传入数组信息[二维数组，可以批量操作]
	 *	 						$in_array = array(
	 *	 							array(
	 *	 								'user_id' => ,//当前用户id
	 *	 								'sys' => array(
	 *	 								'customerCode' 		=> $this->p_customer_code,	// 客户编码
	 *	 								'siteID' 			=> $this->p_site_id,		// 站点id
	 *	 								'site_name' 		=> $this->p_site_name,		// 站点名称
	 *	 								'accountId'			=>$this->p_account_id,		// 分帐id ；注意：如果有用户，则是用户自己的
	 *	 								'siteURL' 			=> $this->p_stie_domain,	// 地址
	 *	 								'contractId' 		=> $this->p_contract_id,	// 合同id
	 *	 								'operator_id' 		=> $this->p_user_id,		// 操作发起人用户ID
	 *	 								'client_ip' 		=> $this->p_client_ip,		// 客户端ip
	 *	 								'server_ip' 		=> $this->p_server_ip,		// 服务端ip
	 *	 								'oper_account' 		=> $this->p_account,		// 操作帐号
	 *	 								'oper_display_name' => $this->p_display_name,	// 操作姓名
	 *	 								'orgID' 			=> $this->p_org_id,			// 所属站点，分公司，生态企业组织id[各种管理员新加时，必填]
	 *	 							),
	 *	 						)
	 * @param 		int 		$operate_type 操作类型 0关闭1开通2删除
	 * @return 		boolean 	true成功 false失败
	 *
	 */
	public function open_close_user($in_array = array(), $operate_type = 0){
		log_message('info', __FUNCTION__." input->\n".var_export(array($in_array, 'operate_type' => $operate_type), true));

		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->library('API','','API');

		// 判断参数数组是否为空
		if(isemptyArray($in_array)){
			// 为空
			return false;
		}

		// 载入uc_user模型
		$CI->load->model('uc_user_model');

		$user_arrs = array();
		foreach($in_array as $u_k => $u_arr){
			$user_id = arr_unbound_value($u_arr, 'user_id', 2, '');
			$sys_arr = arr_unbound_value($u_arr, 'sys', 1, array());

			// 判断当前用户id是否为空
			if(bn_is_empty($user_id)){
				// 为空
				continue;
			}

			// 从uc_user表获得用户状态
			$sel_field = 'status';
			$where_arr = array(
                  'userID' => $user_id,
			);
			$sel_arr = $CI->uc_user_model->get_db_arr($where_arr, $sel_field);

			// 判断是否有用户信息
			if(isemptyArray($sel_arr)){
				// 没有
				continue;
			}

			// 用户状态
			$status = arr_unbound_value($sel_arr, 'status', 2, '');

			// 判断用户状态是否为空
			if(bn_is_empty($status)){
				continue;
			}

			$is_operate   = 0; // 是否需要操作0不需要1需要
			$is_del_ums   = 0; // 是否调用ums删除0不调用1调用
			$operate_txt  = '';
			$user_operate = 1; // 1新开通流程[全新开启]2开启更新UPDATE流程[以前关闭，现在是开启]3关闭流程[以前开启，现在半闭]4删除流程[以前开启，现在删除]5删除流程[以前关闭，但开启过，现在删除] 6以前未开启过，现在开启，7 开启状态，修改
			switch ($operate_type){// 0关闭1开通2删除
				case 0: // 0关闭
					if( ($status == 1)){// 开通状态,才进行操作 （0：未启用（一直未开通过）；1：已开通；3：禁用（开通过）；2:删除（开通过））
						$is_operate   = 1; // 是否需要操作0不需要1需要
						$operate_txt  = 'disable';
						$user_operate = 3; //3 关闭流程[以前开启，现在半闭]
					}
					break;
				case 2: // 2删除
					$is_del_ums = 0;//是否调用ums删除0不调用1调用
					switch ($status) {//（0：未启用（一直未开通过）；1：已开通；3：禁用（开通过）；2:删除（开通过）
						case 0://（0：未启用（一直未开通过）
							$is_del_ums = 1;//是否调用ums删除0不调用1调用
							break;
						case 1: //1：已开通；
							$is_operate = 1;//是否需要操作0不需要1需要
							$operate_txt = 'delete';
							$user_operate = 4;//4删除流程[以前开启，现在删除]
							break;
						case 2://2：禁用/删除（开通过;这里说的是禁用，只是删除及发送离职消息
							$is_del_ums = 1;//是否调用ums删除0不调用1调用
							break;
						default:
							break;
					}
					if($is_del_ums == 1){//是否调用ums删除0不调用1调用
						$api_data = '';//参数
						$ums_arr = $CI->API->UMS_Special_API($api_data, 18, array('url' => $user_id));//8删除用户
						if(api_operate_fail($ums_arr)){//失败
							log_message('error', 'UMS API rs/users/' . $user_id . '/delete  fail.');
						}else{
							log_message('debug', 'UMS API rs/users/' . $user_id . '/delete success.');
						}
					}

					//删除管理者，及对user、useradmin表进行操作
					$re_boolean = $this->close_user($user_id);
					if($re_boolean){//成功
						log_message('debug', ' save close_user success  $user_id =' . $user_id . ' .');
					}else{
						log_message('error', ' save close_user  $user_id =' . $user_id . ' .');
					}
					break;
				case 1://1开通
					//echo 1;
					$is_operate = 1;//是否需要操作0不需要1需要
					$operate_txt = 'create';
					$user_operate = 6;//6以前未开启过，现在开启，
					if( $status == 2 ){
						$operate_txt = 'enable';
						$user_operate = 2;//2开启更新UPDATE流程[以前关闭，现在是开启]
					}
					break;
				default:
					break;
			}
			if($is_operate == 1){
				//保存线程
				$user_arr = array(
                            'user_id' => $user_id,
                            'operate_txt' => $operate_txt,
                            'user_operate' => $user_operate,
                            'sys' => $sys_arr,
				);
				$user_arrs[$u_k] = $user_arr;
			}
		}
		if(!isemptyArray($user_arrs)){//不是空数组
			$data = 'type=4&value=' . urlencode(json_encode($user_arrs));
			$uc_thread_arr = $CI->API->UCAPI($data, 2, array('url' => base_url('')));
			if(api_operate_fail($uc_thread_arr)){//失败
				log_message('error', 'save thread  ' . json_encode($user_arrs) . 'is fail.');
				return false;
			}
			log_message('debug', 'save thread  ' . json_encode($user_arrs) . ' is success.');
		}
		return true;
	}

	/**
	 *
	 * @brief 发送离职消息
	 * @details
	 * @param array $in_arr 二维数组
	 $in_arr =array(
	 array(
	 'user_id' => $aaa,//删除帐号id
	 'sys' => $sys_arr
	 ),
	 )
	 * @return null
	 *
	 */
	public function send_del_info($in_arr = array()){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->library('API','','API');
		$CI->load->library('Informationlib','','Informationlib');
		foreach ($in_arr as $ns_user_arr){
			$ns_user_id = arr_unbound_value($ns_user_arr,'user_id',2,'');
			$ns_sys_arr = arr_unbound_value($ns_user_arr,'sys',1,array());
			$operator_id = arr_unbound_value($ns_sys_arr,'operator_id',2,'');
			$site_id = arr_unbound_value($ns_sys_arr,'siteID',2,'');
			$re_org_arr = $this->get_user_org_arr($ns_user_id,1);
			$org_id = arr_unbound_value($re_org_arr,'id',2,'');
			$org_name = arr_unbound_value($re_org_arr,'name',2,'');
			$CI->load->model('uc_org_manager_model');
			$org_manager_user_id = $CI-> uc_org_manager_model->get_org_manager_userid($org_id,$site_id);
			if($org_manager_user_id > 0){//不为空,有管理者则发送
				$re_user_arr = $this->get_user_by_id($ns_user_id);
				$ns_user_name = arr_unbound_value($re_user_arr,'displayName',2,'');
				//发送离职消息
				$info_pre_arr = array(
                    'from_user_id' => $operator_id,//$this->p_user_id,//消息发送者用户id
                    'from_site_id' => $site_id,//$this->p_site_id,//消息发送者站点id
                    'to_user_id' => $org_manager_user_id,//消息接受者id[是组织时为组织id,是用户时，是用户id]
                    'to_site_id' => $site_id,//$this->p_site_id,//消息接受者站点id
                    'is_group' => 0,//是否为讨论组聊天1是[是组织] 0 否[是单个用户]   
                    'msg_type' => 1,//消息类型  1 - 组织变动
                    'msg_id' => 5,//1 - 部门名称变更    2 - 员工部门调动    3 - 职位调整  4 -员工入职 5 - 员工离职   10 - 部门删除  11 - 员工入职确认 12 - 员工离职确认 13 - 员工部门调动确认 14 - 员工入职拒绝消息 15 - 员工离职拒绝消息 16 - 员工部门调动拒绝消息 
				);
				$info_body = array(
                    'operator_id' => $operator_id,//$this->p_user_id,//操作发起人用户ID
                    'user_id' => $ns_user_id,//员工用户ID
                    'user_name' => $ns_user_name ,//员工姓名
                    'dept_name' => $org_name,//员工部门名称
                    'desc' => '',//消息描述
				);
				log_message('info', 'send msg orgchange $info_pre_arr = ' . json_encode($info_pre_arr) . ' $info_body = ' . json_encode($info_body) . '.');
				$CI->Informationlib->send_info($info_pre_arr, $info_body);
				log_message('info', 'send msg orgchange userid = ' . $ns_user_id . '.');
			}
		}
	}
	/**
	 *
	 * @brief 删除/关闭时，删除组织管理者记录，修改管理员表/用户表状态：
	 * @details
	 * @param int $user_id  当前用户id
	 * @return boolean TRUE成功 FALSE 失败
	 *
	 */
	public function close_user($user_id = 0){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->library('API','','API');
		$CI->load->model('uc_org_manager_model');
		$CI->load->model('uc_user_model');
		$CI->load->model('uc_user_admin_model');
		//关闭/删除用户
		$re_boolean = false;
		$get_boolean = $CI->uc_user_model->close_user_state($user_id);
		if($get_boolean){
			$re_boolean = true;
			log_message('debug', 'close_user_state $user_id =' . $user_id . ' success.');
			$CI->uc_user_admin_model->update_user_admin_state($user_id,0);
			$CI->uc_org_manager_model->del_org_manager($user_id);
		}else{
			$re_boolean = false;
			log_message('debug', 'uclose_user_state $user_id =' . $user_id . ' fail.');
		}
		return $re_boolean;
	}


	/**
	 *
	 * @brief 根据相关信息，获得当前用户的标签相关信息
	 * @details
	 * @param array $in_arr
	 $in_arr = array(
	 'user_id' => $aa,//用户id，没有写0，且不会取相关标签的值
	 'tag_type' => $aa,//标签页面类型
	 'site_id' => $aa,//当前站点id
	 );
	 * @param array $user_auto_arr 默认的用户帐号信息
	 * @return array
	 $re_arr = array(
	 'user_info_arr' => $user_info_data,//用户详情信息
	 'system_must_tag_names' => $system_must_tag_names,//系统标签名称，多个用,号分隔
	 'system_must_tag_arr' => $system_must_tag_arr,//系统标签及其值数组
	 'seled_not_must_tag_names' => $seled_not_must_tag_names,//可选标签名称，多个用,号分隔
	 'seled_not_must_tag_arr' => $seled_not_must_tag_arr ,//可选标签及其值数组
	 'user_defined_tag_names' => $user_defined_tag_names,//自定义标签名称，多个用,号分隔
	 'user_defined_tag_arr' => $user_defined_tag_arr ,//自定义标签及其值数组
	 );
	 *
	 */
	public function get_user_tag_arr($in_arr = array(),$user_auto_arr = array()){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->library('API','','API');

		$user_id = arr_unbound_value($in_arr,'user_id',2,0);
		$tag_type = arr_unbound_value($in_arr,'tag_type',2,4);
		$site_id = arr_unbound_value($in_arr,'site_id',2,0);

		$re_arr = array();
		//载入员工标签资源
		include_once APPPATH . 'libraries/public/Tag_class.php';
		//获得当前用户信息数组
		$user_info_data = array();
		$user_info_data = array_merge($user_info_data, $user_auto_arr);
		if($user_id > 0){
			$user_info_data = $this->get_user_by_id($user_id);
			//print_r($user_info_data);
			
			// 获得当前用户所在部门的信息
			$CI->load->library('UmsLib', '', 'ums');
			$org_info = $CI->ums->getOrgInfoByUserId($user_id);
			//print_r($org_info);
// 			foreach($org_info as $org_value){
				$user_info_data['organizationId'] = isset($org_info[0]['id']) ? $org_info[0]['id'] : '';
				$user_info_data['organizationName'] = isset($org_info[0]['name']) ? $org_info[0]['name'] : '';
				$user_info_data['orgNodeCode'] = isset($org_info[0]['nodeCode']) ? $org_info[0]['nodeCode'] : '';
// 			}
			
		}
// 		echo "<br>";
// 		print_r($user_info_data);
// 		 die;


		$tag_obj = new Tag_class($tag_type);//新加员工页显示使用
		//系统标签,多个用逗号分隔
		$system_must_tag_names = $tag_obj->get_system_tag_names();

		//系统标签数组
		$system_must_tag_arr = $tag_obj->get_must_tag_arr();

		//print_r($system_must_tag_arr);
		//获得系统员工标签值
		foreach($system_must_tag_arr as $k => $v){
			$tag_umsapifield = arr_unbound_value($v,'umsapifield',2,'');//标签字段
			$tag_value = '';
			if($tag_umsapifield == 'lastName'){
				$tag_value = isset($user_info_data['displayName']) ? $user_info_data['displayName'] : '';
			}else{
				foreach ($user_info_data as $v_f =>$v_v){
					if($tag_umsapifield == $v_f){
						$tag_value = $v_v;
						break;
					}
				}
			}
			
			$system_must_tag_arr[$k]['tag_value'] = $tag_value;
		}
		//print_r($system_must_tag_arr);
		//从数据库获得系统可选标签及自定义员工标签信息
		$CI->load->model('UC_User_Tags_Model');
		$tag_arr = $CI->UC_User_Tags_Model->get_tags_by_siteid($site_id,1);
		$tag_obj->resolve_tag_arr($tag_arr);

		//获得当前站点的选中的可选员工标签名，多个用，号分隔
		$seled_not_must_tag_names = $tag_obj->get_seled_not_must_tag_names();
		$seled_not_must_tag_arr =  $tag_obj->get_seled_not_must_tag_arr();

		//获得系统可选员工标签值
		foreach($seled_not_must_tag_arr as $k => $v){
			$tag_umsapifield = arr_unbound_value($v,'umsapifield',2,'');//标签字段
			$tag_value = '';
			foreach ($user_info_data as $v_f =>$v_v){
				if($tag_umsapifield == $v_f){
					$tag_value = $v_v;
					break;
				}
			}
			$seled_not_must_tag_arr[$k]['tag_value'] = $tag_value;
		}
		//自定义员工标签数组
		$user_defined_tag_names = $tag_obj->get_user_defined_tag_names();
		$user_defined_tag_arr = $tag_obj->get_user_defined_tag_arr();

		//自定义员工标签值
		//获得当前员工签值
		$user_tag_value_arr = array();
		if($user_id > 0){
			$CI->load->library('TagLib','','TagLib');
			$user_tag_value_arr = $CI->TagLib->get_tag_arr($user_id);
		}
		//获得自定义员工标签值
		foreach($user_defined_tag_arr as $k => $v){
			$tag_id = arr_unbound_value($v,'id',2,'');//tag标签
			$tag_value = '';
			foreach ($user_tag_value_arr as $v_k =>$v_v){
				$u_tag_id = arr_unbound_value($v_v,'tag_id',2,'');
				$u_tag_value = arr_unbound_value($v_v,'tag_value',2,'');
				$u_tag_name = arr_unbound_value($v_v,'tag_name',2,'');
				if($tag_id == $u_tag_id){
					$tag_value = $u_tag_value;
					break;
				}
			}
			$user_defined_tag_arr[$k]['tag_value'] = $tag_value;
		}
		$re_arr = array(
            'user_info_arr' => $user_info_data,//用户详情信息
            'system_must_tag_names' => $system_must_tag_names,//系统标签名称，多个用,号分隔
            'system_must_tag_arr' => $system_must_tag_arr,//系统标签及其值数组
            'seled_not_must_tag_names' => $seled_not_must_tag_names,//可选标签名称，多个用,号分隔
            'seled_not_must_tag_arr' => $seled_not_must_tag_arr ,//可选标签及其值数组
            'user_defined_tag_names' => $user_defined_tag_names,//自定义标签名称，多个用,号分隔
            'user_defined_tag_arr' => $user_defined_tag_arr ,//自定义标签及其值数组
		);
		return $re_arr;
	}

	/**
	 *
	 * @brief 保存用户信息
	 * @details
	 * @param array $in_arr 用户标签信息数组
	 Array
	 (
	 [sys_tag] => Array
	 (
	 [0] => Array
	 (
	 [name] => 姓名
	 [value] => 邹燕
	 [umsapifield] => lastName
	 )

	 )

	 [user_tag] => Array
	 (
	 [0] => Array
	 (
	 [tag_name] => birthday
	 [value] => 222
	 [tag_id] => 1
	 )
	 )

	 [org_tag] => Array//注意：需要从公司开始，第一层会自动去掉
	 (
	 [0] => Array
	 (
	 [id] => 513
	 [value] => 北京奥的斯电梯有限公司
	 )

	 [1] => Array
	 (
	 [id] => 879
	 [value] => 测试
	 )
	 )

	 )
	 * @param array $other_arr 其它系统标签信息数组
	 $other_arr = array(
	 'user_id'=> $user_id,//0为新加；具体数字为修改的userid
	 'tag_type' => $tag_type,//标签页面类型
	 );
	 * @param array $sys_arr 其它系统标签信息数组
	 $sys_arr = array(
	 'siteID' => $this->p_site_id,//站点id
	 'customerCode' => $this->p_customer_code,//客户编码
	 'parentId' => $this->p_org_id,//"513",//当前站点的组织机构id
	 "site_name"=>$this->p_site_name, //站点名称
	 "accountId"=> $this->p_account_id, //当前用户分帐id[注意如果用户有，则需要用用户自己的]
	 "siteURL"=> $this->p_stie_domain, //站点地址
	 "contractId"=> $this->p_contract_id//合同id
	 "operator_id" => $this->p_user_id//操作发起人用户ID
	 'user_type' => 'user_type' //帐号类型0普通用户1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员[各种管理员新加时，必填]7生态企业普通用户
	 'orgID' => $this->p_org_id,//所属站点，分公司，生态企业组织id[各种管理员新加时，必填]
	 'isLDAP' => $this->p_is_ldap,//帐号导入类型[各种管理员新加时，必填]
	 'session_id' => $this->p_session_id //sessionid
	 //'sys_user_id' => $sys_user_id,//登陆的系统管理员id  [去掉]
	 );
	 * @return boolean 成功true 失败 false 失败的字符串
	 *
	 */
	public function save_user($in_arr = array(), $other_arr = array(), $sys_arr = array()) {
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->library('API','','API');
		if(isemptyArray($in_arr) || isemptyArray($other_arr) || isemptyArray($sys_arr)){//空数组
			return false;
		}
		$user_id = arr_unbound_value($other_arr,'user_id',2,'');//0为新加；具体数字为修改的userid
		$tag_type = arr_unbound_value($other_arr,'tag_type',2,'');//标签页面类型

		$site_id = arr_unbound_value($sys_arr,'siteID',2,'');//站点id
		$customerCode = arr_unbound_value($sys_arr,'customerCode',2,'');//客户编码
		$parentId = arr_unbound_value($sys_arr,'parentId',2,'');//当前站点的组织机构id
		$site_name = arr_unbound_value($sys_arr,'site_name',2,'');//站点名称
		$accountId = arr_unbound_value($sys_arr,'accountId',2,'');//当前用户分帐id
		$siteURL = arr_unbound_value($sys_arr,'siteURL',2,'');//站点地址
		$contractId = arr_unbound_value($sys_arr,'contractId',2,'');//合同id
		$operator_id = arr_unbound_value($sys_arr,'operator_id',2,'');//操作发起人用户ID
		$user_type = arr_unbound_value($sys_arr,'user_type',2,0);//帐号类型0普通用户1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员7生态企业普通用户
		$session_id = arr_unbound_value($sys_arr,'session_id',2,'');//session_id
		$sys_user_id = $operator_id;//arr_unbound_value($sys_arr,'sys_user_id',2,'');//登陆的系统管理员id
		if( bn_is_empty($user_id)   || bn_is_empty($site_id) || bn_is_empty($tag_type) || bn_is_empty($customerCode)){//没有值
			log_message('debug', ' param is empty.');
			return false;
		}

		if( bn_is_empty($parentId)  || bn_is_empty($site_name)  || bn_is_empty($accountId) || bn_is_empty($siteURL) || bn_is_empty($contractId)){//没有值
			log_message('debug', ' param is empty.');
			return false;
		}
		//
		//将数组转换为这样的数组
		/*
		 $user_arr = array(
		 'customerCode' => "024014", //客户编码
		 'parentId' => "513",//当前站点的组织机构id
		 'isopen' => 1,//是否开通
		 'org_tag' => array(//下标可以随便给,
		 "E" => array(
		 "name" => "部门一级", //组织层级中文名
		 "suffix" => "E", //下标，可不要
		 "value" => "abc", //组织值
		 "regex" => "/^[\\s\\S]{1,100}$/"//当前值正则
		 )
		 ),
		 'user_tag' => array(//下标可以随便给,
		 "N" => array(
		 "tag_name"=> "birthday", //自定义标签名称
		 "tag_id"=>"1", //自定义标签id
		 "suffix"=>"N", //下标
		 "value"=>19840229, //自定义标签值
		 "regex"=>"/^[\\s\\S]{1,100}$/"//正则
		 ),
		 "sys" => array(
		 "customerCode"=> "024014", //客户编码
		 "siteID"=> "666897", //站点id
		 "site_name"=>"北京奥的斯电梯有限公司", //站点名称
		 "accountId"=> "118093", //当前用户分帐id
		 "siteURL"=> "xianUC.quanshi.com", //站点地址
		 "contractId"=> "111357"//合同id
		 )
		 ),
		 'sys_tag'=> array(//下标可以随便给,
		 "A" => array(
		 "name" => "姓名", //系统标签名称
		 "suffix" => "A",  //系统标签下标
		 "value" => "开发测试",  //系统标签值
		 "regex" => "/^[\\x80-\\xffA-Za-z\\d]{1,50}$/",  //系统标签正则
		 "field"=> "last_name",  //系统标签字段
		 "umsapifield" => "lastName" //系统标签ums字段
		 )
		 )
		 );
		 *
		 */
		//获得当前用户信息数组
		$user_info_data = array();
		$is_modify = 0;//是否是修改0新加1修改
		if($user_id > 0){
			$user_info_data = $this->get_user_by_id($user_id);
		}
		log_message('debug', ' get_user_by_id user_id=' . $user_id . '.');
		$isopen = 0;//开通/关闭 0关闭1开通
		$productStatus = arr_unbound_value($user_info_data,'productStatus',2,0);
		$organizationId = arr_unbound_value($user_info_data,'organizationId',2,0);//所在部门id
		$old_position = arr_unbound_value($user_info_data,'position',2,'');//置位
		//$old_position = arr_unbound_value($user_info_data,'position',2,'');//置位
		if($productStatus == 82 ){
			$isopen = 1;//开通/关闭 0关闭1开通
		}
		//汉字转拼音库
		include_once APPPATH . 'libraries/chartopinyin.php';
		$pinyin_obj = new pinyin();

		$sys_tag = arr_unbound_value($in_arr,'sys_tag',1,array());//系统标签{"name":"\u59d3\u540d","value":"\u90b9\u71d5","umsapifield":"lastName"}
		$user_tag = arr_unbound_value($in_arr,'user_tag',1,array());//自定义标签{"tag_name":"birthday","value":"birthday","tag_id":"1"}
		$org_tag  = arr_unbound_value($in_arr,'org_tag',1,array());//组织标签 {"id":"879","value":"值"}
		// echo json_encode($sys_tag);
		$in_tag_arr = array(
            'user_id' => 0,//用户id，没有写0
            'tag_type' => $tag_type,//4,//标签页面类型
            'site_id' => $site_id,//当前站点id 
		);
		//获得当前用户标签及[标签值]
		$user_tag_arr = $this->get_user_tag_arr($in_tag_arr);
		log_message('debug', ' get_user_tag_arr is success.');
		$user_info_arr = arr_unbound_value($user_tag_arr,'user_info_arr',1,array());//用户详情信息
		$system_must_tag_names = arr_unbound_value($user_tag_arr,'system_must_tag_names',2,'');//系统标签名称，多个用,号分隔
		$system_must_tag_arr= arr_unbound_value($user_tag_arr,'system_must_tag_arr',1,array());//系统标签及其值数组
		$seled_not_must_tag_names= arr_unbound_value($user_tag_arr,'seled_not_must_tag_names',2,'');//可选标签名称，多个用,号分隔
		$seled_not_must_tag_arr= arr_unbound_value($user_tag_arr,'seled_not_must_tag_arr',1,array());//可选标签及其值数组
		$user_defined_tag_names= arr_unbound_value($user_tag_arr,'user_defined_tag_names',2,'');//自定义标签名称，多个用,号分隔
		$user_defined_tag_arr= arr_unbound_value($user_tag_arr,'user_defined_tag_arr',1,array());//自定义标签及其值数组
		$user_sys_tag_arr = array_merge($system_must_tag_arr,$seled_not_must_tag_arr);

		//组结成功的数组
		$ok_user_tag = array();//用户标签信息
		$ok_sys_tag = array();//系统标签信息
		$ok_user_sys = array();//用户数组里的sys标签
		$ok_org_tag = array();//组织信息

		//组织转换组织机构
		$num = 1;
		foreach($org_tag as $k => $v){
			if($num > 1){//第一级是公司不要
				$id = arr_unbound_value($v,'id',2,'');
				$value = arr_unbound_value($v,'value',2,'');
				$ns_org_arr = array(
                    'name' => '部门' . num_to_upper($num -1 ) . '级', //组织层级中文名'
                    "value" => $value, //组织值
				);
				$ok_org_tag[] = $ns_org_arr;
			}
			$num += 1;
		}
		log_message('debug', ' org_arr to org_txt is success.');


		//用户自定义信息转换{"tag_name":"birthday","value":"birthday","tag_id":"1"}
		foreach($user_tag as $k => $v){
			$tag_name = arr_unbound_value($v,'tag_name',2,'');//标签名称
			$value = arr_unbound_value($v,'value',2,'');//标签值
			$tag_id = arr_unbound_value($v,'tag_id',2,'');//标签id

			$ns_user_tag_arr =array(
                    "tag_name"=> $tag_name, //自定义标签名称
                    "tag_id"=> $tag_id, //自定义标签id
			//"suffix"=>"N", //下标
                    "value"=>$value, //自定义标签值
			// "regex"=>"/^[\\s\\S]{1,100}$/"//正则
			);
			$ok_user_tag[] = $ns_user_tag_arr;
		}
		$ok_user_tag['sys'] = $sys_arr;

		log_message('debug', ' user_tag is success.');
		//系统标签[含必选/可选]{"name":"\u59d3\u540d","value":"\u90b9\u71d5","umsapifield":"lastName"}
		$update_arr = array();
		$update_arr['id'] = $user_id;
		$new_position = '';//新的置位名称
		$new_lastName = '';//姓
		$new_middleName = '';//中间名
		$new_firstName = '';//名
		$new_displayName = '';//显示姓名
		foreach ($sys_tag as $k => $v){
			$name = arr_unbound_value($v,'name',2,'');//标签名称
			$value = arr_unbound_value($v,'value',2,'');//标签值
			$umsapifield = arr_unbound_value($v,'umsapifield',2,'');//标签字段
			if($umsapifield == 'loginName'){//loginName 帐号
				$loginName = $value;
				switch($value){
					case '是':
					case '开通':
					case '开':
						$value=1;
						break;
					case '否':
					case '关闭':
					case '关':
						$value=0;
						break;
				}
			}
			if($umsapifield == 'sex'){//sex 性别
				switch($value){
					case '男':
						$value=1;
						break;
					case '女':
						$value=2;
						break;
				}
			}
			//根据系统标签，判断当前的标签值是否正确 {"id":0,"field":"last_name","umsapifield":"lastName","title":"aa","type":1,"regex":"\/^[02]{1,50}$\/","values":[],"defaultvalue":"","page_type":"1,2,3,4,5","tag_value":""}
			foreach($user_sys_tag_arr as $tg_k => $tg_v){
				$tg_umsapifield = arr_unbound_value($tg_v,'umsapifield',2,'');//标签字段
				if($umsapifield == $tg_umsapifield){
					$tg_regex = arr_unbound_value($tg_v,'regex',2,'');//标签正则
					if(!bn_is_empty($tg_regex)){//正则有值
						if(!preg_match($tg_regex, $value)){//不满足条件
							return $umsapifield . ' value is not in regex ' . $tg_regex ;
						}
					}
					break;
				}
			}
			if(strtolower($umsapifield) == 'isopen'){//开启帐号
				$isopen = $value;//开通/关闭 0关闭1开通
			}
			$ns_sys_tag_arr = array(
                'name' => $name, //系统标签名称
			//'suffix' => "A",  //系统标签下标
                'value' => $value,  //系统标签值
			// 'regex' => "/^[\\x80-\\xffA-Za-z\\d]{1,50}$/",  //系统标签正则
			// 'field'=> "last_name",  //系统标签字段
                'umsapifield' => $umsapifield //系统标签ums字段
			);

			$ok_sys_tag[] = $ns_sys_tag_arr;
			$update_arr[$umsapifield] = $value;

			if($umsapifield == 'position'){
				$new_position = $value;//新的置位名称
			}
			if(strtolower($umsapifield) == 'lastname'){
				$new_lastName = $value;//姓
			}
			if(strtolower($umsapifield) == 'middlename'){
				$new_middleName = $value;//中间名
			}
			if(strtolower($umsapifield) == 'firstname'){
				$new_firstName = $value;//名
			}
			if(strtolower($umsapifield) == 'displayname'){
				$new_displayName = $value;//显示姓名
			}
		}
		if($user_id > 0){//修改用户
			//手动加上displayName
			$ns_displayName = $new_lastName . $new_middleName . $new_firstName;
			$displayName = empty_to_value($new_displayName, $ns_displayName);
			//if(strtolower($umsapifield) == 'lastname'){
			if(!bn_is_empty($displayName)){//有值
				$update_arr['displayName'] = $displayName;
				$update_arr['namepinyin'] = $pinyin_obj -> utf8_to($displayName,true);//名称首字母拼音
			}

			//$update_arr['passType'] = 1;//密码规则1 md5加密
			//}
		}
		log_message('debug', ' sys_tag is success.');
		$ok_user_arr = array(
            'customerCode' => $customerCode,//"024014", //客户编码
            'parentId' => $parentId,//当前站点的组织机构id 
            'isopen' => $isopen,//是否开通
            'org_tag' => $ok_org_tag, 
            'user_tag' => $ok_user_tag, 
            'sys_tag'=> $ok_sys_tag,   
		);
		if($user_id > 0){//修改用户
			//调用修改用户接口
			$ums_arr = $CI->API->UMS_Special_API(json_encode($update_arr),16);
			if(api_operate_fail($ums_arr)){//失败
				$err_msg = 'ums api rs/users/updateUser fail.';
				log_message('error', $err_msg);
				return false;//失败
				//TODO 最后打开 return 0;
			}else{
				$new_user_info_data = $this->get_user_by_id($user_id);
				$new_displayName = arr_unbound_value($new_user_info_data,'displayName',2,'');
				$new_organizationId = arr_unbound_value($new_user_info_data,'organizationId',2,'');
				$new_organizationName = arr_unbound_value($new_user_info_data,'organizationName',2,'');
				if($new_position != $old_position){//置位有变动
					$msg_arr = array(
                            'user_id' => $user_id,//用户id
                            'new_displayName' => $new_displayName,//用户姓名
                            'new_position' => $new_position,//新职位名称
                            'old_position' => $old_position,//旧职位名称
                            'dept_name' => $new_organizationName,//职位所在部门名称
					);
					$this->position_change_msg($msg_arr);
				}
				log_message('debug', 'ums api rs/users/updateUser success.');

			}
			//TODO 如果部门有变化，则调用调部门方法

			//TODO 保存用户自定义标签
			//保存自定义标签
			//if(isemptyArray($ok_user_tag)){//不是空数组

			// }
			if(is_array($ok_user_tag)){//是数组
				$CI->load->library('TagLib','','TagLib');
				$ns_in_arr = array(
                        'user_id' => $user_id,
                        'session_id' => $session_id,
                        'sys_user_id' => $sys_user_id,//登陆的系统管理员id
				);
				$tag_boolean = $CI->TagLib->save_tags($ok_user_tag,$ns_in_arr);
				// $err_msg = 'save userid= ' . $user_id . ' tags is ';// . var_export($tag_boolean);
				// log_message('debug', $err_msg);
			}
			return true;//成功

		}else{//新加用户
			//保存到线程
			if(isemptyArray($ok_user_arr)){//空数组
				return false;
			}
			$users_arr = array($ok_user_arr);
			
			// ----------------判断当前账号信息是否在UMS中存在-----------@author xue.bai_2@quanshi.com
			$CI->load->helper('my_publicfun');
			$CI->load->library('API', '', 'API');
			
			$ums_data_arr = array();
			$ums_data_arr[] = array('loginName' => $loginName);
			
			$ums_api_arr = $CI->API->UMSAPI(json_encode($ums_data_arr), 7);//返回不存在的数组			
			$umsuser_notexist_arr = arr_unbound_value($ums_api_arr,'data',1,array());
			
			if(!isemptyArray($umsuser_notexist_arr)){// 不为空，则不存在
				// --------------不存在，则保存到线程---------------@author xue.bai_2@quanshi.com
				
				$success_json = json_encode($users_arr);
				$data = 'type=2&value=' . urlencode($success_json);
				$uc_thread_arr = $CI->API->UCAPI($data,2,array('url' => base_url('')));
				if(api_operate_fail($uc_thread_arr)){//失败
					log_message('error', 'UCAPI NO 1 is fail.');
					return false;
				}else{
					log_message('debug', 'UCAPI NO 1 is success.');
					return true;
				}
			}
		}
		return true;
	}
	/**
	 *
	 * @brief 判断当前用户，是否有自己的权限
	 * @details
	 * @param int $user_id 当前用户id
	 * @return boolean 有权限true,没有权限false
	 *
	 */
	public function user_has_power($user_id = ''){
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->library('API','','API');
		if(bn_is_empty($user_id)){//没有数据
			return false;
		}
		$CI->load->model('uc_user_config_model');
		$sel_field = 'id';
		$where_arr = array(
                'userID' => $user_id                           
		);
		$sel_arr = $CI->uc_user_config_model->get_db_arr($where_arr,$sel_field);
		if(isemptyArray($sel_arr)){//如果是空数组
			log_message('debug', 'uc_user_config_model userID = ' .  $user_id . '  is empty.');
			return false;
		}
		return true;
	}
	/**
	 *
	 * @brief 根据loginName,判断用户是否存在，返回不存在的数据，如果都存在返回空数组
	 * @details
	 * @param array $user_arr 用户帐号数组[二维]
	 $user_arr = array(
	 array('loginName' => 'qing.wang2@gnetis.quanshi.com'),
	 //array('loginName' => 'qing.wang2aaaaaa@gnetis.quanshi.com'),
	 );
	 * @return array 返回不存在的用户数组,如果都存在返回空数组
	 *
	 */
	public function users_not_exist($user_arr = array()){
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->library('API','','API');
		$re_arr = array();
		if(isemptyArray($user_arr)){//如果是空数组
			return $re_arr;
		}
		log_message('debug', __FUNCTION__ . ' params $user_arr = ' . json_encode($user_arr). '.');
		$ums_user_arr = $CI->API->UMSAPI(json_encode($user_arr),7);
		if(!api_operate_fail($ums_user_arr)){//成功
			log_message('debug', ' ums rs/organizations/list success.');
			$re_arr = arr_unbound_value($ums_user_arr,'session_id',1,array());
		}else{//失败
			log_message('debug', ' ums rs/organizations/list fail.');
		}
		return $re_arr;
	}
	/**
	 *
	 * @brief 根据帐号id，获得帐号在ums中的状态
	 * @details
	 * @param int $user_id 用户id
	 * @return array 0关闭1开通2失败
	 *
	 */
	public function get_umsproduct_state($user_id = ''){
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->library('API','','API');
		//$user_id = '39915'  ;
		$ums_arr = $CI->API->UMS_Special_API('',20,array('url'=>$user_id ));
		if(api_operate_fail($ums_arr)){//失败
			$err_msg = ' usm api rs/users/getUserProductList?userId=' . $user_id . '&productId=' .UC_PRODUCT_ID . ' fail .';
			log_message('error', $err_msg);
			return 2;
		}else{
			$ums_data_json = arr_unbound_value($ums_arr,'data',2,'');
			$ums_data_arr = json_decode($ums_data_json,true);
			$ums_user_state = arr_unbound_value($ums_data_arr['userProductDTO'],'userStatus',2,'');//0关闭82开通

			//$change_org_data = arr_unbound_value($change_org_arr,'data',1,array());
			$err_msg = ' usm api rs/users/getUserProductList?userId=' . $user_id . '&productId=' .UC_PRODUCT_ID . ' success .';
			log_message('debug', $err_msg);
			if($ums_user_state == 82){
				return 1;
			}else{
				return 2;
			}

		}
	}
	/**
	 *
	 * @brief 通过登录名查询用户
	 * @details
	 * @param string $user_loginName 用户帐号登录名
	 * @return array 0关闭1开通2失败
	 *
	 */
	public function get_ums_user_arr($user_loginName = ''){
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->library('API','','API');
		$ums_data_arr = array();
		$ums_arr = $CI->API->UMS_Special_API('',21,array('url' => $user_loginName));
		if(api_operate_fail($ums_arr)){//失败
			$err_msg = ' usm api rs/users/getUserProductList?userId=' . $user_id . '&productId=' .UC_PRODUCT_ID . ' fail .';
			log_message('error', $err_msg);
			return 2;
		}else{
			$ums_data_json = arr_unbound_value($ums_arr,'data',2,'');
			$ums_data_arr = json_decode($ums_data_json,true);
		}
		return $ums_data_arr;
	}
	/**
	 *
	 * @brief 保存或修改本方参与人员
	 * @details
	 * @param array $ecology_arr 生态企业信息数组
	 $ecology_arr = array(
	 'ecology_id' => $aa,//生态企业id
	 'site_id' => $aa,//当前站点id
	 'orgid' => $aaa当前参与人所在的分公司/生态企业组织id
	 'type' => $aa,//类型1全新的新加或修改，会删除其它的，2只是新加或修改，会保存其它的
	 );
	 * @param array $staff_user_arr 本方参与人员信息数组[可以是空数组:没有本方参与人员]
	 $staff_user_arr = array(
	 'userid' => $userid,//参与人用户id
	 //'orgid' => $orgid,//参与人所在的站点组织id ;去除

	 );
	 * @return boolean true成功 false失败
	 *
	 */
	public function save_partake($ecology_arr = array() ,$staff_user_arr = array()){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->helper('my_dgmdate');
		$CI->load->library('API','','API');
		if(isemptyArray($ecology_arr)){//如果是空数组
			return false;
		}
		$ecology_id = arr_unbound_value($ecology_arr,'ecology_id',2,'');
		$site_id = arr_unbound_value($ecology_arr,'site_id',2,'');
		$site_orgid = arr_unbound_value($ecology_arr,'orgid',2,'');
		$type = arr_unbound_value($ecology_arr,'type',2,'');
		if(bn_is_empty($ecology_id) || bn_is_empty($site_id) || bn_is_empty($type) ){
			return false;
		}

		$CI->load->model('uc_ecology_partake_model');
		$notdel_userid_arr = array();

		foreach($staff_user_arr as $u_k => $u_v){
			$ns_user_id = arr_unbound_value($u_v,'userid',2,'');
			$ns_user_orgid = $site_orgid;//arr_unbound_value($u_v,'orgid',2,'');
			$notdel_userid_arr[] = $ns_user_id;
			$select_field = 'id';
			$where_arr = array(
                'user_id' => $ns_user_id,
                'ecology_id' => $ecology_id,         
			);
			$modify_arr = array(
                'org_id' => $ns_user_orgid,
                'site_id' => $site_id,
                'user_id' => $ns_user_id,
                'ecology_id' => $ecology_id,                
			);
			$insert_arr = $modify_arr;
			$insert_arr['time'] = dgmdate(time(), 'dt');
			$re_num = $CI->uc_ecology_partake_model->updata_or_insert(1,$select_field,$where_arr,$modify_arr,$insert_arr);
			if($re_num == -2 || $re_num == -4){//失败 -1 更新记录成功 -2更新操作失败 -3新加的记录id 新加记录成功 -4新加操作失败 -5 没有操作
				$err_msg = 'update/insert  uc_ecology_partake_model fail. $re_num =' . $re_num . ' ';
				log_message('error', $err_msg);
				log_message('error', ' updata_or_insert uc_ecology_partake_model $select_field=' . json_encode($select_field) . '$where_arr=' . json_encode($where_arr) . '$modify_arr=' . json_encode($modify_arr) . '$insert_arr=' . json_encode($insert_arr) . 'fail');
				//return false;
			}else{
				log_message('debug', ' updata_or_insert uc_ecology_partake_model $select_field=' . json_encode($select_field) . '$where_arr=' . json_encode($where_arr) . '$modify_arr=' . json_encode($modify_arr) . '$insert_arr=' . json_encode($insert_arr) . 'success');
			}
		}
		//删除其它的记录
		if($type == 1){//类型1全新的新加或修改，会删除其它的，2只是新加或修改，会保存其它的
			$where_arr = array(
                  'ecology_id' => $ecology_id,
			);
			$del_arr = array(
               'where' => $where_arr, 
			);
			if(!isemptyArray($notdel_userid_arr)){
				$del_arr['where_not_in'] = array('user_id' => $notdel_userid_arr);
			}
			$re_del_arr = $CI-> uc_ecology_partake_model -> operateDB(4,$del_arr);
			if(db_operate_fail($re_del_arr)){//失败
				//return false;
				log_message('error', ' delete uc_ecology_partake_model ' . json_encode($del_arr) . 'fail');
			}else{
				// return true;
				log_message('debug', ' delete uc_ecology_partake_model ' . json_encode($del_arr) . 'success');
			}
		}
		return true;
	}
	/**
	 *
	 * @brief 删除本方参与人员
	 * @details
	 * @param int $ecology_id 生态企业id
	 * @param array $del_userid_arr 要删除的生态企业id数组
	 * @return boolean true成功 false失败
	 *
	 */
	public function del_partake($ecology_id = 0,$del_userid_arr = array() ){
		log_message('debug', 'into method ' . __FUNCTION__ . '.');
		log_message('debug', '$ecology_id ' . any_to_str($ecology_id) . '.');
		log_message('debug', '$del_userid_arr ' . any_to_str($del_userid_arr) . '.');
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->helper('my_dgmdate');
		$CI->load->library('API','','API');
		$CI->load->model('uc_ecology_partake_model');
		if(isemptyArray($del_userid_arr)){//如果是空数组
			return false;
		}
		$where_arr = array(
              'ecology_id' => $ecology_id,
		);
		$del_arr = array(
               'where' => $where_arr, 
		);
		if(!isemptyArray($del_userid_arr)){
			$del_arr['where_in'] = array('user_id' => $del_userid_arr);
		}
		$re_del_arr = $CI-> uc_ecology_partake_model -> operateDB(4,$del_arr);
		if(db_operate_fail($re_del_arr)){//失败
			return false;
			log_message('error', ' delete uc_ecology_partake_model ' . json_encode($del_arr) . 'fail');
		}else{

			log_message('debug', ' delete uc_ecology_partake_model ' . json_encode($del_arr) . 'success');
			return true;
		}
	}
	/**
	 *
	 * @brief 单个新加生态企业管理员或其它管理员
	 * @details
	 * @param array 生态企业管理员变更
	 $msg_arr = array(
	 'user_id' => $user_id,
	 'super_admin_id' => $super_admin_id,
	 'role_id' => $role_id,//角色1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员
	 'state' => $state ,//0：停用；1：启用
	 'other' => array(
	 'orgID' => $this->p_org_id,
	 'isLDAP' => $this->p_is_ldap,
	 'siteID' => $this->p_site_id,
	 ),
	 );
	 * @return false失败；true成功
	 */
	public function add_ecology_onemanger($msg_arr = array()){
		log_message('debug', 'into method ' . __FUNCTION__ . '.');
		log_message('debug', '$msg_arr ' . any_to_str($msg_arr) . '.');
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->helper('my_dgmdate');
		$CI->load->library('API','','API');

		if(isemptyArray($msg_arr)){//如果是空数组
			return false;
		}

		$new_user_id = arr_unbound_value($msg_arr,'user_id',2,'');
		$old_super_admin_id = arr_unbound_value($msg_arr,'super_admin_id',2,'');
		$role_id = arr_unbound_value($msg_arr,'role_id',2,'');
		$state = arr_unbound_value($msg_arr,'state',2,'');
		$user_other_arr = arr_unbound_value($msg_arr,'other',1,array());

		if(!preg_match('/^[\d]+$/',$new_user_id)){//生态管理员
			return false;
		}

		if(!preg_match('/^[\d]+$/',$old_super_admin_id)){//生态管理员
			return false;
		}
		if(!preg_match('/^[01]$/',$state)){//状态
			return false;
		}
		if(!preg_match('/^[123456789]$/',$role_id)){//角色
			return false;
		}
		if(isemptyArray($user_other_arr)){//如果是空数组
			return false;
		}

		$old_orgID = arr_unbound_value($user_other_arr,'orgID',2,'');
		$old_isLDAP = arr_unbound_value($user_other_arr,'isLDAP',2,'');
		$old_siteID = arr_unbound_value($sel_arr,'siteID',2,'');
			
		$CI->load->model('uc_user_admin_model');
		$CI->load->model('uc_user_model');
		//获得用户信息
		$sel_field = 'siteId,billingcode,hostpasscode,guestpasscode,accountId';
		$where_arr = array(
                'userID' => $new_user_id, 
		);
		$sel_arr = $CI->uc_user_model->get_db_arr($where_arr,$sel_field);

		if(isemptyArray($sel_arr)){//没有数据
			return false;
		}
		$new_siteId = arr_unbound_value($sel_arr,'siteId',2,'');
		$new_billingcode = arr_unbound_value($sel_arr,'billingcode',2,'');
		$new_hostpasscode = arr_unbound_value($sel_arr,'hostpasscode',2,'');
		$new_guestpasscode = arr_unbound_value($sel_arr,'guestpasscode',2,'');
		$new_accountId = arr_unbound_value($sel_arr,'accountId',2,'');
		//有则更新，没有则新加
		$where_arr = array(
            'userID' => $new_user_id,     
            'role_id' => $role_id,//5,//5生态管理员
		);
		$admin_type = 0;//1：总公司管理员；2：分公司管理员；3：生态企业管理员；4渠道管理员0：其它',
		switch ($role_id) {
			case 1://1系统管理员
				$admin_type = 1;
				break;
			case 2://2组织管理员
				$admin_type = 2;
				break;
			case 3://3员工管理员
				$admin_type = 0;
				break;
			case 4://4帐号管理员
				$admin_type = 0;
				break;
			case 5://5生态管理员
				$admin_type = 3;
				break;
			case 6://6渠道管理员
				$admin_type = 4;
				break;
			default:
				break;
		}
		//获得用户所在的组织
		$ns_user_org_arr = $this->get_user_org_arr($new_user_id,1);
		$ns_user_org_id = arr_unbound_value($ns_user_org_arr,'id',2,'');
		$modify_arr = array(
            'userID' => $new_user_id,//管理员userid
            'role_id' => $role_id,//5,//5生态管理员 角色id
            'siteID' => $new_siteId,//管理员所属的站点id
            'orgID' => $old_orgID,//管理员所属的组织id[系统管理员为站点所属的组织id]
            'isLDAP' => $old_isLDAP,//0：否（批量导入）；1：是（LDAP导入）；2：全部都可以',
            'billingcode' => $new_billingcode,//用户记费码
            'hostpasscode' => $new_hostpasscode,//主持人密码
            'guestpasscode' => $new_guestpasscode,//参会人密码
            'accountId' => $new_accountId,//分帐id
            'departmentID' => $ns_user_org_id,//部门id
            'super_admin_id' => $old_super_admin_id,//父管理员id[生态管理员时]
            'type' => $admin_type,//0,//1：总公司管理员；2：分公司管理员；3：生态企业管理员；4渠道管理员0：其它',
            'state' => $state,//1,//0：停用；1：启用
		//'last_login_time' => dgmdate(time(), 'dt'),//最后登陆时间
		//'createTime' => dgmdate(time(), 'dt'),//创建时间
		);
		$insert_arr = $modify_arr;
		$insert_arr['createTime'] = dgmdate(time(), 'dt');
		$re_num = $CI-> uc_user_admin_model -> updata_or_insert(1,'userID',$where_arr,$modify_arr,$insert_arr);

		switch ($re_num) {//-1 更新记录成功 -2更新操作失败 -3新加的记录id 新加记录成功 -4新加操作失败 -5 没有操作
			case -2:
			case -4:
				return false;
			default:
		}
		return true;
	}
	/**
	 *
	 * @brief 根据用户id；获得用户类型
	 * @details
	 * @param array 用户信息
	 $user_arr = array(
	 'userid' => $aaa,//用户id
	 'adminstate' => $aaa,//0：停用；1：启用
	 );
	 * @return string 用户类型，多个用,号分隔;帐号类型0普通用户1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员7生态企业普通用户
	 */
	public function get_usertype_byuserid($user_arr = array()){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->helper('my_dgmdate');
		$CI->load->library('API','','API');
		$re_usertype = 0;
		if(isemptyArray($user_arr)){//如果是空数组
			return $re_usertype;
		}
		$userid = arr_unbound_value($user_arr,'userid',2,'');
		$adminstate = arr_unbound_value($user_arr,'adminstate',2,'');
		if( bn_is_empty($userid) || bn_is_empty($adminstate) ){
			return $re_usertype;
		}
		//从数据库中获得用户信息
		$CI->load->model('UC_User_Admin_Model');
		$data_admin = array(
            'select' =>'role_id',
            'where' => array('userID =' => $userid, 'state =' => $adminstate),
		);
		$admin_arr =  $CI->UC_User_Admin_Model->operateDB(2,$data_admin);
		if( is_array($admin_arr) ){
			log_message('info', 'get UC_User_Admin_Model array  success.');

		}else{
			log_message('debug', 'get UC_User_Admin_Model array fail');
			return $re_usertype;
		}
		if(isemptyArray($admin_arr)){//不为空
			return $re_usertype;
		}
		$ns_ok_type = '';
		foreach ($admin_arr as  $k => $v){
			$ns_type = arr_unbound_value($v,'role_id',2,'');
			if(!bn_is_empty($ns_type) ){
				if(!bn_is_empty($ns_ok_type) ){
					$ns_ok_type .= ',';
				}
				$ns_ok_type .= $ns_type;
			}
		}
		if(!bn_is_empty($ns_ok_type) ){
			$re_usertype = $ns_ok_type;
		}
		return $re_usertype;
	}

	/**
	 * @abstract 根据用户信息删除帐号
	 * @param 	 string 	$user_id 		需要删除的帐号id
	 * @param 	 array 		$sys_arr 		系统参数
	 *	 					$sys_arr = array(
	 *	 						'customerCode' 		=> $this->p_customer_code,	// 客户编码
	 *	 						'siteID' 			=> $this->p_site_id,		// 站点id
	 *	 						'site_name' 		=> $this->p_site_name,		// 站点名称
	 *	 						'accountId'			=>$this->p_account_id,		// 分帐id ；注意：如果有用户，则是用户自己的
	 *	 						'siteURL' 			=> $this->p_stie_domain,	// 地址
	 *	 						'contractId' 		=> $this->p_contract_id,	// 合同id
	 *	 						'operator_id' 		=> $this->p_user_id,		// 操作发起人用户ID
	 *	 						'client_ip' 		=> $this->p_client_ip,		// 客户端ip
	 *	 						'server_ip' 		=> $this->p_server_ip,		// 服务端ip
	 *	 						'oper_account' 		=> $this->p_account,		// 操作帐号
	 *	 						'oper_display_name' => $this->p_display_name,	// 操作姓名
	 *	 						'orgID' 			=> $this->p_org_id,			// 所属站点，分公司，生态企业组织id[各种管理员新加时，必填]
	 *	 					);
	 * @return 	 boolean 	true 成功 false失败
	 *
	 */
	public function del_staff($user_id = '',$sys_arr = array()){
		log_message('info', __FUNCTION__." input->\n".var_export(array('user_id' => $user_id, $sys_arr), true));

		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->helper('my_dgmdate');
		$CI->load->library('API', '', 'API');

		// 判断当前用户id是否为空
		if(bn_is_empty($user_id)){
			// 为空
			return true;
		}

		// 将用户id转化成数组
		$user_arr = explode(',',$user_id);

		// 判断用户id数组是否为空
		if(isemptyArray($user_arr)){
			// 为空
			return true;
		}

		// 将每个有效的用户进行重组
		$ok_user_arr = array();
		foreach ($user_arr as $ns_user_id){
			if($ns_user_id > 0){
				$in_array = array(
                    'user_id' => $ns_user_id,            
                    'sys' => $sys_arr,
				);
				$ok_user_arr[] = $in_array;
			}
		}

		if(!isemptyArray($ok_user_arr)){
			log_message('debug', 'save delete thread   $ok_user_arr = ' . json_encode($ok_user_arr) . '.');

			$re_boolean = $this->open_close_user($ok_user_arr , 2);
			if($re_boolean){
				// 成功：true
				log_message('debug', 'save delete thread success  $ns_user_id = ' . $ns_user_id . '.');
			}else{
				// 失败：false
				log_message('error', 'save delete thread fail  $ns_user_id = ' . $ns_user_id . '.');
			}
		}

		// 载入日志类库
		$CI->load->library('LogLib', '', 'LogLib');
		$log_in_arr = $sys_arr;
		// 写日志
		$re_id = $CI->LogLib->set_log(array('5', '5'), $log_in_arr);
		return true;
	}

	/**
	 * @brief 发送置位变动消息
	 * @details
	 * @param array $msg_arr 参数
	 $msg_arr = array(
	 'user_id' => $user_id,//用户id
	 'new_displayName' => $new_displayName,//用户姓名
	 'new_position' => $new_position,//新职位名称
	 'old_position' => $old_position,//旧职位名称
	 'dept_name' => $new_organizationName,//职位所在部门名称
	 );
	 * @return null
	 *
	 */
	public function position_change_msg($msg_arr = array()){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->helper('my_dgmdate');
		$CI->load->library('API','','API');
		$CI->load->library('Informationlib','','Informationlib');
		$CI->Informationlib->send_ing($sys_arr,array('msg_id' => 3,'msg_arr' => $msg_arr));
	}
}
