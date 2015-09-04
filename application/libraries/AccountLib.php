<?php if (!defined('BASEPATH'))	exit('No direct script access allowed');

/**
 * @class AccountLib
 * @brief AccountLib 类库，主要负责对用户开通方法。[属于扫描线程类库]
 * @file AccountLib.php
 * @author zouyan <yan.zou@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
class AccountLib{
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
	 * @brief 符合规则用户批量导入[开通]流程 //按开启用户/关闭用户分组进行操作
	 * @details
	 * @param json $user_json 需要开通的帐号json串
	 *
	 * @return 0:失败；1：成功 2 回调失败
	 */
	public function batch_modify_user($user_json) {
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->helper('my_dgmdate');
		$CI->load->library('API','','API');
		if(bn_is_empty($user_json)){//没有数据
			$err_msg = ' get $user json fail.';
			log_message('error', $err_msg);
			echo api_json_msg(-1,array('msg' => $err_msg) , 1);
			return 0;
			die();
		}
		//数据转换
		$user_arr = json_decode($user_json , TRUE );
		//print_r($user_arr);
		// die();
		if(isemptyArray($user_arr)){//如果是空数组
			$err_msg = ' $user_json json to array fail.';
			log_message('error', $err_msg);
			echo api_json_msg(-1,array('msg' => $err_msg) , 1);
			return 0;
			die();
		}

		//汉字转拼音库
		include_once APPPATH . 'libraries/chartopinyin.php';
		$pinyin_obj = new pinyin();
		//把用户分类为0新建账号[开启]1新建账号[关闭]
		//0新建账号[开启]
		//$open_user_arr = array();
		//1新建账号[关闭]
		//$closed_user_arr = array();
		$modify_user_arr = array();
		$user_state_arr = array();//用户状态数组
		//TODO 用户数组 分踺捡为开启、或关闭的数组
		$CI->load->library('StaffLib','','StaffLib');
		foreach($user_arr as $key => $val){
			if(isemptyArray($val)){//如果是空数组
				continue;
			}
			$is_open = isset($val['isopen'])?$val['isopen']:0;//是否开通0未开通1开通
			$customerCode = isset($val['customerCode'])?$val['customerCode']:0;//客户编码
			$parentId = isset($val['parentId'])?$val['parentId']:0;//当前站点id
			//$accountId = isset($val['accountId'])?$val['accountId']:0;//分帐id
			//$siteID = isset($val['siteID'])?$val['siteID']:0;//站点id
			//echo $is_open . '<br/>';
			//组织部门数组
			$org_tag_arr = isset($val['org_tag'])?$val['org_tag']:array();
			//用户标签
			$user_tag_arr = isset($val['user_tag'])?$val['user_tag']:array();
			//系统标签
			$sys_tag_arr = isset($val['sys_tag'])?$val['sys_tag']:array();
			if( (!is_array($org_tag_arr)) || (!is_array($user_tag_arr)) || (!is_array($sys_tag_arr)) ){//不是数组
				continue;
			}

			//组织值
			$org_value = '';//部门1/部门2/部门3
			foreach($org_tag_arr as $k => $v){
				$ns_org_name = isset($v['value'])?$v['value']:'';
				if(!bn_is_empty($ns_org_name)){//名称不为空
					//是否加/
					if(!bn_is_empty($org_value)){
						$org_value .= '/';
					}
					$org_value .= $ns_org_name;
				}
			}
			//echo $org_value;
			//exit;
			//用户信息单个数组
			$user_arr = array();
			$user_use_tag = array('lastName','loginName','sex','position','mobileNumber','email','officePhone');//当前可以用的字段
			$new_position = '';//新的置位名称
			$new_lastName = '';//姓
			$new_middleName = '';//中间名
			$new_firstName = '';//名
			$new_displayName = '';//显示姓名
			foreach($sys_tag_arr as $k => $v){
				$umsapifield = isset($v['umsapifield'])?$v['umsapifield']:'';//umsAPI字段名
				if(in_array($umsapifield,$user_use_tag)){
					$ns_value = isset($v['value'])?$v['value']:'';//字段值
					if( (!bn_is_empty($umsapifield)) && (!bn_is_empty($ns_value))){//都不为空
						$user_arr[$umsapifield] = $ns_value;
					}
					//登陆名
					if(strtolower($umsapifield) == 'loginname'){
						$ums_user_arr = $CI->StaffLib->get_ums_user_arr($ns_value);
						$ns_ums_id = arr_unbound_value($ums_user_arr,'id',2,'');//用户id
						$ns_ums_position = arr_unbound_value($ums_user_arr,'position',2,'');//用户职位
						$user_state_arr[] = array(//用户状态数组
                            'loginname' => $ns_value,
                            'isopen' => $is_open,//0未开通1开通
                            'id' => $ns_ums_id,//用户id
                            'position' => $ns_ums_position//用户职位                          
						);
					}
					if($umsapifield == 'position'){
						$new_position = $ns_value;//新的置位名称
					}
					if(strtolower($umsapifield) == 'lastname'){
						$new_lastName = $ns_value;//姓
					}
					if(strtolower($umsapifield) == 'middlename'){
						$new_middleName = $ns_value;//中间名
					}
					if(strtolower($umsapifield) == 'firstname'){
						$new_firstName = $ns_value;//名
					}
					if(strtolower($umsapifield) == 'displayname'){
						$new_displayName = $ns_value;//显示姓名
					}

				}
			}
			//手动加上displayName
			//if(strtolower($umsapifield) == 'lastname'){
			$ns_displayName = $new_lastName . $new_middleName . $new_firstName;
			$displayName = empty_to_value($new_displayName, $ns_displayName);
			if(!bn_is_empty($displayName)){//有值
				$user_arr['displayName'] = $displayName;
				$user_arr['namepinyin'] = $pinyin_obj -> utf8_to($displayName,true);;//名称首字母拼音
			}
			//$user_arr['passType'] = 1;//密码规则1 md5加密
			//$user_arr['role'] = 1;//权限
			//$user_arr['userstatus'] = 1;//状态
			// }
			$user_arr['organizationName'] = $org_value;//组织名称串
			$user_arr['obj'] = $user_tag_arr;

			//$user_arr['obj'] = serialize($user_tag_arr);//序列化标签数组
			//用户信息
			//$ns_user_arr = array();
			//$ns_user_arr['name'] = $org_value;//组织名称串
			// $ns_user_arr['customercode'] = $customerCode;//客户编码
			//$ns_user_arr['siteID'] = $siteID;//站点id
			// $ns_user_arr['accountId'] = $accountId;//站点id
			// $ns_user_arr['type'] = 3;
			// $ns_user_arr['parentId'] = $parentId;

			// $ns_user_arr['users'][]= $user_arr;//user信息
			// $ns_user_arr[]= $user_arr;//user信息
			$modify_user_arr[] = $user_arr;
			//echo $is_open;
			//通过登录名查询用户id
			//根据用户ID和产品ID获取用户产品信息
			//            if($is_open == 1)//开启
			//            {
			//                $open_user_arr[] = $ns_user_arr;
			//            }else{//关闭
			//                $closed_user_arr[] = $ns_user_arr;
			//            }
		}
		// print_r($user_state_arr);
		// die();
		// print_r($modify_user_arr);
		// die();
		$ums_user_arr = array();//调用ums接口数据
		if(!isemptyArray($modify_user_arr)){//如果是空数组
			$ums_arr = $CI->API->UMSAPI(json_encode($modify_user_arr),8,array('url' => $customerCode));
			$ums_user_arr = arr_unbound_value($ums_arr,'data',1,array());

		}
		print_r($ums_user_arr);
		die();
		$ums_fail_arr = arr_unbound_value($ums_user_arr,'failed',1,array());
		//print_r($ums_fail_arr);
		$ums_success_arr = arr_unbound_value($ums_user_arr,'success',1,array());
		//print_r($ums_success_arr);
		if(!isemptyArray($ums_fail_arr)){//有失败的,生成失败文件
			$fail_all_arr = array();//失败数组
			$fail_excelhead_arr = array();//excel头文件
			$fail_num = 1;
			foreach($ums_fail_arr as $fail_k => $fail_v){
				$fail_excel_arr = arr_unbound_value($fail_v['obj']['sys'],'excel',1,array());
				if($fail_num == 1 ){//excel头文件
					$fail_excelhead_arr = arr_unbound_value($fail_v['obj']['sys'],'excelhead',1,array());
				}
				if(!isemptyArray($fail_excel_arr)){//有失败的原execel记录
					$fail_all_arr[] = $fail_excel_arr;//失败数组
				}
				$fail_num += 1;
			}

			if(!isemptyArray($fail_all_arr)){//有失败记录,生成失败文件
				$fail_all_arr = array_merge(array($fail_excelhead_arr),$fail_all_arr);
				$CI->load->helper('my_phpexcel');
				$file_arr = array(
                    'file_path' => 'data/file/',//文件路径，相对于站点目录：形式: 文件夹/../文件夹/
                    'file_name' => time(),//文件名称,注意没有文件后缀,如aaaa
				);
				$re_filename = create_excel($fail_all_arr,'07',$file_arr,1);
				//$file_url = $this->web_root_url . $re_filename;
			}
		}
		if(!isemptyArray($ums_success_arr)){//有失败的,生成失败文件
			$CI->load->library('StaffLib','','StaffLib');
			$CI->load->library('OrganizeLib','','OrganizeLib');
			$CI->load->library('Informationlib','','Informationlib');
			$user_open_arr = array();//需要从关闭到开通的帐号数组
			$user_close_arr = array();//需要从开通到关闭的帐号数组
			foreach($ums_success_arr as $succ_k => $succ_v){
				$user_id = arr_unbound_value($succ_v,'id',2,'');
				$loginName = arr_unbound_value($succ_v,'loginName',2,'');
				$position = arr_unbound_value($succ_v,'position',2,'');
				if(!bn_is_empty($user_id)){//有数据
					$displayName = arr_unbound_value($succ_v,'displayName',2,'');
					$sys_arr = arr_unbound_value($succ_v['obj'],'sys',1,array());
					$operator_id = arr_unbound_value($sys_arr,'operator_id',2,'');
					$site_id = arr_unbound_value($sys_arr,'siteID',2,'');
					//部门变动[变更权限及发部门变更消息]
					$oldOrgNodeCode = arr_unbound_value($succ_v,'oldOrgNodeCode',2,'');
					$orgNodeCode = arr_unbound_value($succ_v,'orgNodeCode',2,'');
					$ns_user_org_arr = $this->StaffLib->get_user_org_arr($user_id,1);
					$ns_user_org_id = arr_unbound_value($ns_user_org_arr,'id',2,'');
					$ns_user_org_name = arr_unbound_value($ns_user_org_arr,'name',2,'');
					$ns_user_org_parentId = arr_unbound_value($ns_user_org_arr,'parentId',2,'');
					$ns_user_org_code = arr_unbound_value($ns_user_org_arr,'nodeCode',2,'');
					if(!bn_is_empty($orgNodeCode)){//新部门有数据
						if($oldOrgNodeCode != $orgNodeCode){//有部门变动
							$old_org_arr = explode('-', $oldOrgNodeCode);
							$old_orgid = get_last_part($oldOrgNodeCode, '-');//获得旧部门id
							$new_orgid = get_last_part($orgNodeCode, '-');//获得新部门id

							$old_org_arr = $CI->OrganizeLib->get_org_by_id($old_orgid);
							$old_org_name =  arr_unbound_value($old_org_arr,'name',2,'');

							$new_org_arr = $CI->OrganizeLib->get_org_by_id($new_orgid);
							$new_org_name =  arr_unbound_value($new_org_arr,'name',2,'');
							if( (!bn_is_empty($old_orgid)) &&  (!bn_is_empty($new_orgid)) ){//有数据
								//单个用户部门变动时，权限需要变更的则直接保存线程：
								$msg_arr = array(
                                    'user_id' => $user_id,//用户id
                                    'old_org_code' => $oldOrgNodeCode,//旧组织id串
                                    'org_code' => $orgNodeCode,//新组织id串
                                    'obj' => $sys_arr
								);
								$org_change_boolean = $CI->StaffLib->get_user_power_changetype($msg_arr);
								if($org_change_boolean){//成功
									log_message('info', ' get_user_power_changetype $user_id=' . $user_id . ' success .');
								}else{//失败
									log_message('info', ' get_user_power_changetype $user_id=' . $user_id . ' fail .');
								}
								//发送组织变动消息
								//发送组织消息
								$info_pre_arr = array(
                                    'from_user_id' => $operator_id,//$this->p_user_id,//消息发送者用户id
                                    'from_site_id' => $site_id,//$this->p_site_id,//消息发送者站点id
                                    'to_user_id' => $user_id,//$ns_new_org_id,//消息接受者id[是组织时为组织id,是用户时，是用户id]
                                    'to_site_id' => $site_id,//$this->p_site_id,//消息接受者站点id
                                    'is_group' => 0,//是否为讨论组聊天1是[是组织] 0 否[是单个用户]   
                                    'msg_type' => 1,//消息类型  1 - 组织变动
                                    'msg_id' => 2,//1 - 部门名称变更    2 - 员工部门调动    3 - 职位调整  4 -员工入职 5 - 员工离职   10 - 部门删除  11 - 员工入职确认 12 - 员工离职确认 13 - 员工部门调动确认 14 - 员工入职拒绝消息 15 - 员工离职拒绝消息 16 - 员工部门调动拒绝消息 
								);
								$info_body = array(
                                    'operator_id' => $operator_id,//$this->p_user_id,//操作发起人用户ID
                                    'user_id' => $user_id,//用户ID
                                    'user_name' => $displayName ,//用户姓名
                                    'dept_id' => $new_orgid,//新部门ID
                                    'old_dept_name' => $old_org_name,//旧部门名称
                                    'dept_name' => $new_org_name,//新部门名称
                                    'desc' => '',//消息描述
								);
								log_message('info', 'into class ' . json_encode($info_pre_arr) . json_encode($info_body) . '.');
								$CI->Informationlib->send_info($info_pre_arr,$info_body);
								log_message('info', 'send msg orgchange userid = ' . $ns_user_id . '.');
							}
						}
					}
					//获得当前帐号状态
					$ums_stat = $CI->StaffLib->get_umsproduct_state($user_id);//0关闭1开通2失败
					if($ums_stat == 0 || $ums_stat == 1){
						//当前用户的状态
						$user_new_state = 0 ;
						$user_old_id = '' ;
						$user_old_position = '' ;
						foreach($user_state_arr as $u_s_k => $u_s_v){
							$u_s_loginname = arr_unbound_value($u_s_v,'loginname',2,'');
							if($u_s_loginname == $loginName){
								$user_new_state = arr_unbound_value($u_s_v,'isopen',2,0);
								$user_old_id = arr_unbound_value($u_s_v,'id',2,'');
								$user_old_position = arr_unbound_value($u_s_v,'position',2,'');
								break;
							}
						}
						//职位有变动，发送职位变更消息
						if($user_old_position != $position ){
							$info_pre_arr = array(
                                'from_user_id' => $operator_id,//消息发送者用户id
                                'from_site_id' => $site_id,//消息发送者站点id
                                'to_user_id' => $user_id,//消息接受者id[是组织时为组织id,是用户时，是用户id]
                                'to_site_id' => $site_id,//消息接受者站点id
                                'is_group' => 0,//是否为讨论组聊天1是[是组织] 0 否[是单个用户]   
                                'msg_type' => 1,//消息类型  1 - 组织变动
                                'msg_id' => 3,//1 - 部门名称变更    2 - 员工部门调动    3 - 职位调整  4 -员工入职 5 - 员工离职  6 - 员工权限变更 10 - 部门删除  11 - 员工入职确认 12 - 员工离职确认 13 - 员工部门调动确认 14 - 员工入职拒绝消息 15 - 员工离职拒绝消息 16 - 员工部门调动拒绝消息 
							);
							$info_body = array(
                                'operator_id' => $operator_id,//操作发起人用户ID
                                'user_id' => $user_id,//用户ID
                                'user_name' => $displayName ,//用户姓名
                                'new_position' => $position,//新职位名称
                                'old_position' => $user_old_position,//旧职位名称
                                'dept_name' => $ns_user_org_name,//职位所在部门名称
                                'desc' => '',//消息描述
							);
							log_message('info', 'into class ' . json_encode($info_pre_arr) . json_encode($info_body) . '.');
							$CI->Informationlib->send_info($info_pre_arr,$info_body);
						}
						if($ums_stat != $user_new_state){
							$ns_usersys_arr = arr_unbound_value($succ_v['obj'],'sys',1,array());;
							$ns_open_closeuser_arr = array(
                                'user_id' => $user_id,//当前用户id
                                'sys' => $ns_usersys_arr,//
							);
							if($user_new_state == 1){//开通
								$user_open_arr[] = $ns_open_closeuser_arr;
							}else{//关闭
								$user_close_arr[] = $ns_open_closeuser_arr;
							}
						}
					}
				}

			}
			if(!isemptyArray($user_open_arr)){//批量开通帐号
				$re_operate_boolean = $CI->StaffLib->open_close_user($user_open_arr, 1);
				if($re_operate_boolean){//成功
					log_message('info', 'open users is success.');
				}else{
					log_message('info', 'open users is fail.');
				}
			}
			if(!isemptyArray($user_close_arr)){//批量关闭帐号
				$re_operate_boolean = $CI->StaffLib->open_close_user($user_open_arr, 0);
				if($re_operate_boolean){//成功
					log_message('info', 'close users is success.');
				}else{
					log_message('info', 'close users is fail.');
				}
			}
		}
		//如果不为空[开启]
		log_message('info', 'out method ' . __FUNCTION__ . '.');
		return 1;
	}
	/**
	 *
	 * @brief 符合规则用户批量导入[开通]流程 //按开启用户/关闭用户分组进行操作
	 * @details
	 * @param json $user_json 需要开通的帐号json串
	 *
	 * @return 0:失败；1：成功 2 回调失败
	 */
	public function batchOpenUser($user_json) {
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->helper('my_dgmdate');
		$CI->load->library('API','','API');
		log_message('debug', 'open ' . __FUNCTION__ . '.');
		if(bn_is_empty($user_json)){//没有数据
			$err_msg = ' get $user json fail.';
			log_message('error', $err_msg);
			echo api_json_msg(-1,array('msg' => $err_msg) , 1);
			return 0;
			die();
		}
		log_message('debug', 'get $user json success.');
		//数据转换
		$user_arr = json_decode($user_json , TRUE );

		if(isemptyArray($user_arr)){//如果是空数组
			$err_msg = ' $user_json json to array fail.';
			log_message('error', $err_msg);
			echo api_json_msg(-1,array('msg' => $err_msg) , 1);
			return 0;
			die();
		}
		log_message('debug', '$user_json json to array success.');
		//汉字转拼音库
		include_once APPPATH . 'libraries/chartopinyin.php';
		$pinyin_obj = new pinyin();
		//把用户分类为0新建账号[开启]1新建账号[关闭]
		//0新建账号[开启]
		$open_user_arr = array();
		//1新建账号[关闭]
		$closed_user_arr = array();
		//TODO 用户数组 分踺捡为开启、或关闭的数组
		foreach($user_arr as $key => $val){
			if(isemptyArray($val)){//如果是空数组
				continue;
			}
			$is_open = isset($val['isopen'])?$val['isopen']:0;//是否开通0未开通1开通
			$customerCode = isset($val['customerCode'])?$val['customerCode']:0;//客户编码
			$parentId = isset($val['parentId'])?$val['parentId']:0;//当前站点id
			//$accountId = isset($val['accountId'])?$val['accountId']:0;//分帐id
			//$siteID = isset($val['siteID'])?$val['siteID']:0;//站点id
			//echo $is_open . '<br/>';
			//组织部门数组
			$org_tag_arr = isset($val['org_tag'])?$val['org_tag']:array();
			//用户标签
			$user_tag_arr = isset($val['user_tag'])?$val['user_tag']:array();
			//系统标签
			$sys_tag_arr = isset($val['sys_tag'])?$val['sys_tag']:array();
			if( (!is_array($org_tag_arr)) || (!is_array($user_tag_arr)) || (!is_array($sys_tag_arr)) ){//不是数组
				continue;
			}
			$user_type = arr_unbound_value($user_tag_arr['sys'],'user_type',2,0);//帐号类型0普通用户1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员[各种管理员新加时，必填]7生态企业普通用户
			//组织值
			$org_value = '';//部门1/部门2/部门3
			foreach($org_tag_arr as $k => $v){
				$ns_org_name = isset($v['value'])?$v['value']:'';
				if(!bn_is_empty($ns_org_name)){//名称不为空
					//是否加/
					if(!bn_is_empty($org_value)){
						$org_value .= '/';
					}
					$org_value .= $ns_org_name;
				}
			}
			//echo $org_value;
			//exit;
			//用户信息单个数组
			$user_arr = array();
			$user_use_tag = array('lastName','firstName','loginName','sex','position','mobileNumber','email','officePhone');//当前可以用的字段
			$new_position = '';//新的置位名称
			$new_lastName = '';//姓
			$new_middleName = '';//中间名
			$new_firstName = '';//名
			$new_displayName = '';//显示姓名
			foreach($sys_tag_arr as $k => $v){
				$umsapifield = isset($v['umsapifield'])?$v['umsapifield']:'';//umsAPI字段名
				if(in_array($umsapifield,$user_use_tag)){
					$ns_value = isset($v['value'])?$v['value']:'';//字段值
					if( (!bn_is_empty($umsapifield)) && (!bn_is_empty($ns_value))){//都不为空
						$user_arr[$umsapifield] = $ns_value;
					}
					if($umsapifield == 'position'){
						$new_position = $ns_value;//新的置位名称
					}
					if(strtolower($umsapifield) == 'lastname'){
						$new_lastName = $ns_value;//姓
					}
					if(strtolower($umsapifield) == 'middlename'){
						$new_middleName = $ns_value;//中间名
					}
					if(strtolower($umsapifield) == 'firstname'){
						$new_firstName = $ns_value;//名
					}
					if(strtolower($umsapifield) == 'displayname'){
						$new_displayName = $ns_value;//显示姓名
					}
				}
			}
			//手动加上displayName
			//if(strtolower($umsapifield) == 'lastname'){
			$ns_displayName = $new_lastName . $new_middleName . $new_firstName;
			$displayName = empty_to_value($new_displayName, $ns_displayName);

			if(!bn_is_empty($displayName)){//有值
				$user_arr['displayName'] = $displayName;
				$user_arr['namepinyin'] = $pinyin_obj -> utf8_to($displayName,true);;//名称首字母拼音
				$user_arr['passType'] = 1;//密码规则1 md5加密
				$user_arr['role'] = 1;//权限
				$user_arr['userstatus'] = 1;//状态
			}

			//}

			$user_arr['obj'] = $user_tag_arr;
			//$user_arr['obj'] = serialize($user_tag_arr);//序列化标签数组
			//用户信息
			$ns_user_arr = array();
			$ns_user_arr['name'] = $org_value;//组织名称串
			$ns_user_arr['customercode'] = $customerCode;//客户编码
			//$ns_user_arr['siteID'] = $siteID;//站点id
			// $ns_user_arr['accountId'] = $accountId;//站点id
			//注意公司或分公司时为
			$ns_org_type = 3;//1:企业 2：生态企业 3：部门 4：生态企业部门 5：分公司

			switch ($user_type) {
				case 0://0普通用户
				case 1://1系统管理员
				case 2://2组织管理员
				case 3://3员工管理员
				case 4://4帐号管理员
				case 5://5生态管理员
					$ns_org_type = 3;
					break;
				case 6: //6渠道管理员[各种管理员新加时，必填]
					$ns_org_type = 2;
					break;
				case 7: //7生态企业普通用户
					$ns_org_type = 4;
					break;
				default:
					$ns_org_type = 3;
					break;
			}

			$ns_user_arr['type'] = $ns_org_type;
			$ns_user_arr['parentId'] = $parentId;
			$ns_user_arr['users'][]= $user_arr;//user信息

			//echo $is_open;
			if($is_open == 1)//开启
			{
				$open_user_arr[] = $ns_user_arr;
			}else{//关闭
				$closed_user_arr[] = $ns_user_arr;
			}
		}

		//如果不为空[开启]
		$ns_re_num = 1;//默认成功
		if(!isemptyArray($open_user_arr))
		{
			$ns_re_num = $this->batchOpen($open_user_arr,0);
			log_message('info', 'open user arr batchOpen method .');
		}
		//如果前面成功才执行下面
		if($ns_re_num == 1){
			//如果不为空[关闭]
			$closed_json = array();
			if(!isemptyArray($closed_user_arr))
			{
				$ns_re_num = $this->batchOpen($closed_user_arr,1);
				log_message('info', 'closed user arr batchOpen method .');
			}
		}
		log_message('info', 'out method ' . __FUNCTION__ . '.');
		return $ns_re_num;
	}
	/**
	 *
	 * @brief 符合规则用户批量导入[开通]流程 //按开启用户/关闭用户分组进行操作
	 * @details
	 * @param array $user_arr 需要开通的帐号数组
	 * @param int $operat_type 0新建账号[开启]1新建账号[关闭]
	 * @return int  返回0:失败；1：成功
	 */

	public function batchOpen($user_arr,$operat_type) {
		//        print_r($user_arr);
		//        print_r($operat_type);
		//        die();
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->helper('my_dgmdate');
		$CI->load->library('API','','API');
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		if(isemptyArray($user_arr)){//为空，则需要看没有没相同的
			echo api_json_msg(-1,array('msg' => '$user_arr is empty') , 1);
			return 0;
		}
		//ums返回员工信息列表json串, 组织id串[11-13-15]
		$ums_user_arr = $CI->API->UMSAPI(json_encode($user_arr),1);

		//exit;
		if(!api_operate_fail($ums_user_arr)){//成功
			log_message('debug', ' ums rs/organizations/list success.');
		}else{//失败
			$err_msg = ' ums rs/organizations/list  fail.';
			log_message('error', $err_msg);
			echo  api_json_msg(-1,array('msg' => $err_msg) , 1);
			return 0;
			die();
		}
		//干掉code
		unset($ums_user_arr['code']);
		$user_ok_arr = $ums_user_arr;


		//boss开通用户信息,组织起来传给BOSS做批量开通用
		$bs_users_arr = array();
		//新的user数组信息[用于保存到uc_request表]
		$user_new_arr = array();
		//站点属性
		$site_components_arr = array();
		$CI->load->library('Informationlib','','Informationlib');
		$CI->load->library('StaffLib','','StaffLib');
		$new_org_arr = array();//新的组织机构id数组
		foreach ($user_ok_arr as $key => $value)
		{
			$user_new_arr[$key] = $value;//新的user数组信息
			//用户id
			$user_id = isset($value['id'])?$value['id']:0;
			$org_str = isset($value['organizationId'])?$value['organizationId']:0;
			$org_code = isset($value['orgNodeCode'])?$value['orgNodeCode']:'';//-500-501-502-503
			$user_tag = isset($value['obj'])?$value['obj']:array();//用户自定义标签
			$obj_arr = isset($value['obj']['sys'])?$value['obj']['sys']:array();
			$customerCode = isset($obj_arr['customerCode'])?$obj_arr['customerCode']:'';//客户编码
			$siteID = isset($obj_arr['siteID'])?$obj_arr['siteID']:0;//站点id
			$site_name = isset($obj_arr['site_name'])?$obj_arr['site_name']:'';//站点名称
			$accountId= isset($obj_arr['accountId'])?$obj_arr['accountId']:0;//分帐id
			$siteURL = isset($obj_arr['siteURL'])?$obj_arr['siteURL']:'';//地址
			$contractId = isset($obj_arr['contractId'])?$obj_arr['contractId']:'';//合同id
			$operator_id = isset($obj_arr['operator_id'])?$obj_arr['operator_id']:'';//操作发起人用户ID
			$user_type = isset($obj_arr['user_type'])?$obj_arr['user_type']:'';//帐号类型0普通用户1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员7生态企业普通用户
			$user_source = isset($obj_arr['user_source'])?$obj_arr['user_source']:'';//帐号开通来源,多个用，号分隔 1批量导入2组织管理员-单个新加3生态渠道管理员新加4任务新加
			////$billingcode = '';//不传
			//$hostpasscode = '123456';//不传
			//$guestpasscode = '123456';//不传
			// 2、向UC的USER表加数据，状态标识为未开通
			if(!bn_is_empty($org_code)){//有数据
				$org_code_arr = explode(',',$org_code);
				foreach ($org_code_arr as $ns_org_id){
					if(!bn_is_empty($ns_org_id)){//有数据
						if(!deep_in_array($ns_org_id, $new_org_arr)){//不包含
							$new_org_arr[] = $ns_org_id;
						}
					}
				}
			}
			$CI->load->model('UC_User_Model');
			//1、有记录则更新记录，没记录则新加；
			$where_arr = array(
                'userID' => $user_id
			);
			$modify_arr = array(
                'userID' => $user_id,//该客户的站点ID
                'siteId' => $siteID,//站点id
			//'billingcode' =>'',//
			//'hostpasscode' =>'',//
			// 'guestpasscode' =>'',//
                'accountId' => $accountId,//分帐id
			//'status' =>0,//TODO 如果已存的，也会值为0了，没有调BOSS后回调改状态功能，需要确定一下处理方式。 现在是关状态（0：删除；1：正常用户
			);
			$insert_arr = $modify_arr;
			$insert_arr['status'] = 0 ;
			$insert_arr['update_time'] = dgmdate(time(), 'dt');
			$re_num = $CI-> UC_User_Model -> updata_or_insert(1,'userID',$where_arr,$modify_arr,$insert_arr);

			switch ($re_num) {//-1 更新记录成功 -2更新操作失败 -3新加的记录id 新加记录成功 -4新加操作失败 -5 没有操作
				case -2:
				case -4:
					$err_msg = ' update/insert UC_User_Model ' . json_encode($where_arr). ' fail.';
					log_message('error', $err_msg);
					break;
				default:
					$err_msg = ' update/insert UC_User_Model ' . json_encode($where_arr). ' success.';
					log_message('debug', $err_msg);
					break;
			}
			//TODO 保存自定义标签
			if(is_array($user_tag)){//是数组
				$CI->load->library('TagLib','','TagLib');
				$ns_in_arr = array(
                    'user_id' => $user_id,
				//'session_id' => $session_id,
				//'sys_user_id' => $sys_user_id,//登陆的系统管理员id
				);
				$tag_boolean = $CI->TagLib->save_tags($user_tag,$ns_in_arr);
				// $err_msg = 'save userid= ' . $user_id . ' tags is ';// . var_export($tag_boolean);
				// log_message('debug', $err_msg);
			}
			//对关闭的用户不发送入职消息
			if($operat_type == 1){//$operat_type 0新建账号[开启]1新建账号[关闭]
				//获得用户数组信息
				//                  $ns_user_arr = $CI->StaffLib->get_user_by_id($user_id);
				//                  $ns_user_name = arr_unbound_value($ns_user_arr,'lastName',2,'');
				//                  $ns_user_position = arr_unbound_value($ns_user_arr,'position',2,'');
				//                  //获得用户所在的组织信息
				//                  $ns_user_org_arr = $CI->StaffLib->get_user_org_arr($user_id,1);
				//                  $ns_user_org_id = arr_unbound_value($ns_user_org_arr,'id',2,'');
				//                  $ns_user_org_name = arr_unbound_value($ns_user_org_arr,'name',2,'');
				//发送消息
				//                $info_pre_arr = array(
				//                    'from_user_id' => $operator_id,//消息发送者用户id
				//                    'from_site_id' => $siteID,//消息发送者站点id
				//                    'to_user_id' => $ns_user_org_id,//消息接受者id[是组织时为组织id,是用户时，是用户id]
				//                    'to_site_id' => $siteID,//消息接受者站点id
				//                    'is_group' => 1,//是否为讨论组聊天1是[是组织] 0 否[是单个用户]
				//                    'msg_type' => 1,//消息类型  1 - 组织变动
				//                    'msg_id' => 4,//1 - 部门名称变更    2 - 员工部门调动    3 - 职位调整  4 -员工入职 5 - 员工离职   10 - 部门删除  11 - 员工入职确认 12 - 员工离职确认 13 - 员工部门调动确认 14 - 员工入职拒绝消息 15 - 员工离职拒绝消息 16 - 员工部门调动拒绝消息
				//                );
				//                $info_body = array(
				//                    'operator_id' => $operator_id,//操作发起人用户ID
				//                    'user_id' => $user_id,//员工用户ID
				//                    'dept_id' => $ns_user_org_id ,//入职部门ID
				//                    'user_name' => $ns_user_name,//员工姓名
				//                    'dept_name' => $ns_user_org_name,//员工部门名称
				//                    'position' => $ns_user_position,//员工职位名称
				//                    'desc' => '',//消息描述
				//                );
				//                $CI->Informationlib->send_info($info_pre_arr,$info_body);
				log_message('info', 'send msg orgchange userid = ' . $user_id . '.');
			}
		}
		if(!isemptyArray($new_org_arr)){//如果是空数组
			//组织机构交换机创建(包括聊天和状态)
			//$data = 'session_id=' . $this->p_session_id . '&user_id=' . $this->p_user_id . '&org_id=' . $org_id;
			$data = 'site_id=' . $siteID . '&org_id=' . json_encode($new_org_arr);
			$ucc_msg_arr = $CI->API->UCCServerAPI($data,18);
			if(!api_operate_fail($ucc_msg_arr)){//成功
				log_message('info', '  uccserver api async/orgCreate ' . $data . ' success .');
			}else{//失败
				log_message('info', '  uccserver api async/orgCreate ' . $data . ' fail .');
			}
		}
		if($operat_type == 0)//$operat_type 0新建账号[开启]1新建账号[关闭]
		{
			return $this->get_boss_json_new($user_ok_arr,1,array());//调用boss1新开通流程
		}

		return 1;
		log_message('info', 'out method ' . __FUNCTION__ . '.');
	}
	/**
	 *
	 * @brief 批量/单个修改
	 * @details
	 * @param array $user_arr 需要修改的帐号数组
	 * @param int $operat_type//1以前是已开通，现在是关闭2以前是已开通，现在还是已开通3以前是已关闭，现在还是关闭4以前是已关闭，现在开启
	 * @param int $is_only_chang_state//是否仅是改变状态 1仅改变用户状态[仅改变状态时用] 0不是[批量改变用户信息时用]
	 */

	public function batchModifyUser($user_arr,$is_only_chang_state = 0) {
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$user_open_close_arr = array();//1以前是已开通，现在是关闭,直接走关闭流程
		$user_open_open_arr = array();//2以前是已开通，现在还是已开通,则直接调UMS批量更新接口.更UC的用户表
		$user_close_close_arr = array();//3以前是已关闭，现在还是关闭,则直接调UMS批量更新接口.更UC的用户表
		$user_close_open_used_arr = array();//4以前是已关闭，现在开启,用户以前开启过,UPDATE流程
		$user_close_open_noused_arr = array();//5.........................没有开户过，先调UMS更新,走BOSS新建流程
		$user_open_del_arr = array();//6删除用户，当前用户状态为开启,则先调USM更新,走BOSS删卡流程
		$user_opened_close_del_arr = array();//7删除用户,当前用户关闭状态[以前开启过],则只调UMS更新.走BOSS删卡流程
		$user_notopened_close_del_arr = array();//8删除用户,当前用户关闭状态[一直未开启过],则只调UMS更新.不用走BOSS
		//TODO 用户数组 分踺捡为以上5种类型的数组
		foreach($user_arr as $key => $val){
			$user_state = $val['user_state'];
			switch ($user_state) {
				case 1: //1以前是已开通，现在是关闭,直接走关闭流程
					$user_open_close_arr[] = $val;
					break;
				case 2://2以前是已开通，现在还是已开通,则直接调UMS批量更新接口.更UC的用户表
					$user_open_open_arr[] = $val;
					break;
				case 3://3以前是已关闭，现在还是关闭,则直接调UMS批量更新接口.更UC的用户表
					$user_close_close_arr[] = $val;
					break;
				case 4://4以前是已关闭，现在开启,用户以前开启过,UPDATE流程
					$user_close_open_used_arr[] = $val;
					break;
				case 5://5.........................没有开户过，先调UMS更新,走BOSS新建流程
					$user_close_open_noused_arr[] = $val;
					break;
				case 6://6删除用户，当前用户状态为开启,则先调USM更新,走BOSS删卡流程
					$user_open_del_arr[] = $val;
					break;
				case 7://7删除用户,当前用户关闭状态[以前开启过],则只调UMS更新.走BOSS删卡流程
					$user_opened_close_del_arr[] = $val;
					break;
				case 8://8删除用户,当前用户关闭状态[一直未开启过],则只调UMS更新.不用走BOSS
					$user_notopened_close_del_arr[] = $val;
					break;
			}
		}

		//1以前是已开通，现在是关闭,直接走关闭流程
		if(!isemptyArray($user_open_close_arr))
		{
			$this->batchModify($user_open_close_arr,1,$is_only_chang_state);
		}

		//2以前是已开通，现在还是已开通,则直接调UMS批量更新接口.更UC的用户表
		if(!isemptyArray($user_open_open_arr))
		{
			$this->batchModify($user_open_open_arr,2,$is_only_chang_state);
		}

		//3以前是已关闭，现在还是关闭,则直接调UMS批量更新接口.更UC的用户表
		if(!isemptyArray($user_close_close_arr))
		{
			$this->batchModify($user_close_close_arr,3,$is_only_chang_state);
		}

		//4以前是已关闭，现在开启,用户以前开启过,UPDATE流程
		if(!isemptyArray($user_close_open_used_arr))
		{
			$this->batchModify($user_close_open_used_arr,4,$is_only_chang_state);
		}

		//5.........................没有开户过，先调UMS更新,走BOSS新建流程
		if(!isemptyArray($user_close_open_noused_arr))
		{
			$this->batchModify($user_close_open_noused_arr,5,$is_only_chang_state);
		}

		//6删除用户，当前用户状态为开启,则先调USM更新,走BOSS删卡流程
		if(!isemptyArray($user_open_del_arr))
		{
			$this->batchModify($user_open_del_arr,6,$is_only_chang_state);
		}
		//7删除用户,当前用户关闭状态[以前开启过],则只调UMS更新.走BOSS删卡流程
		if(!isemptyArray($user_opened_close_del_arr))
		{
			$this->batchModify($user_opened_close_del_arr,7,$is_only_chang_state);
		}
		//8删除用户,当前用户关闭状态[一直未开启过],则只调UMS更新.不用走BOSS
		if(!isemptyArray($user_notopened_close_del_arr))
		{
			$this->batchModify($user_notopened_close_del_arr,8,$is_only_chang_state);
		}
		log_message('info', 'out method ' . __FUNCTION__ . '.');
	}

	/**
	 *
	 * @brief 批量修改流程
	 * @details
	 * 注意批量更新时，都需要更新UC的用户表，只是对状态进行操作时，不用更新。但是注意，不能更新其状态字段，此字段的更新放在回调函数中进行
	 * @param array $user_arr 需要修改的帐号数组
	 * @param int $operat_type//
	 *  1以前是已开通，现在是关闭,直接走关闭流程
	 *  2以前是已开通，现在还是已开通,则直接调UMS批量更新接口.更UC的用户表[因为可能更改了用户其它信息，所以它存在]
	 *  3以前是已关闭，现在还是关闭,则直接调UMS批量更新接口.更UC的用户表[因为可能更改了用户其它信息，所以它存在]
	 *  4以前是已关闭，现在开启,用户以前开启过,UPDATE流程
	 *  5.........................没有开户过，先调UMS更新,走BOSS新建流程
	 *  6删除用户，当前用户状态为开启,则先调USM更新,走BOSS删卡流程
	 *  7删除用户,当前用户关闭状态[以前开启过],则只调UMS更新.走BOSS删卡流程
	 *  8删除用户,当前用户关闭状态[一直未开启过],则只调UMS更新.不用走BOSS
	 * @param int $is_only_chang_state//是否仅是改变状态 1仅改变用户状态[仅改变状态时用] 0不是[批量改变用户信息时用]
	 * @param int $open_style 修改方式 0 UC向BOSS修改用户，1BOSS向UC修改站点
	 */

	private function batchModify($user_arr,$operat_type,$is_only_chang_state = 0,$open_style = 0) {
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->helper('my_dgmdate');
		$CI->load->library('API','','API');
		$is_update_uc = 1;//是否更新操作UC用户表0不更新操作1更新操作
		if ($is_only_chang_state == 1){//仅改变状态时用
			$is_update_uc = 0;//是否更新操作UC用户表0不更新操作1更新操作

			//如果状态其实并没有改变，则直接返回
			if ( ($operat_type == 2) || ($operat_type == 3) ){
				return 1;

			}
		}

		//默认$operat_type = 1 ;以前是已开通，现在是关闭,直接走关闭流程
		//BOSS回调函数类型
		$flow_type = 3;//0代表不调用 BOSS回调 1新开通流程[全新开启] 2开启更新UPDATE流程[以前关闭，现在是开启]
		// 3关闭流程[以前开启，现在半闭] 4删除流程[以前开启，现在删除]5删除流程[以前关闭，但开启过，现在删除]

		//TODO 注意还有2update：修改账号 5delete： 删卡 的操作需要与景超确认 boss操作类型
		$boss_type = 3;//类型：0代表不调用BOSS接口 1create： 新建账号 2update：修改账号 3disable：停用账号 4enable：启用账号 5delete： 删卡
		switch ($operat_type) {
			case 1: //以前是已开通，现在是关闭,直接走关闭流程
				$flow_type = 3;//3关闭流程[以前开启，现在半闭]
				$boss_type = 3;//3disable：停用账号
				break;
			case 2: //以前是已开通，现在还是已开通,则直接调UMS批量更新接口.更UC的用户表
				$flow_type = 0;
				$boss_type = 0;//0代表不调用BOSS接口

				break;
			case 3: //以前是已关闭，现在还是关闭,则直接调UMS批量更新接口.更UC的用户表
				$flow_type = 0;
				$boss_type = 0;//0代表不调用BOSS接口
				break;
			case 4: //以前是已关闭，现在开启,用户以前开启过,UPDATE流程
				$flow_type = 2;//2开启更新UPDATE流程[以前关闭，现在是开启]
				$boss_type = 4;//4enable：启用账号
				break;
			case 5: //以前是已关闭，现在开启,用户以前没有开户过，先调UMS更新,走BOSS新建流程
				$flow_type = 1;//1新开通流程[全新开启]
				$boss_type = 1;//create： 新建账号
				break;
			case 6: //删除用户，当前用户状态为开启,则先调USM更新,走BOSS删卡流程
				$flow_type = 4;//4删除流程[以前开启，现在删除]
				$boss_type = 5;//5delete： 删卡
				break;
			case 7: //删除用户,当前用户关闭状态[以前开启过],则只调UMS更新.走BOSS删卡流程
				$flow_type = 5;//5删除流程[以前关闭，但开启过，现在删除]
				$boss_type = 5;//5delete： 删卡
				break;
			case 8: //删除用户,当前用户关闭状态[一直未开启过],则只调UMS更新.不用走BOSS
				$flow_type = 0;//
				$boss_type = 0;//0代表不调用BOSS接口
				break;
		}
		// 判断用户是否已经开通。
		//1、建组织和用户-在ums –你返回用户及组织关系
		$data = '
{
    "orgs": [
        {
            "name": "zhangsan", 
            "customercode": "002896", 
            "type": 3, 
            "users": [
                {
                    "loginName": "zhangsan@quanshi.com", 
                    "firstName": "三", 
                    "lastName": "姓", 
                    "email": "zhangsan@quanshi.com", 
                    "userstatus": 0, 
                    "sex": 1, 
                    "mobileNumber": "15910720259", 
                    "register": 1, 
                    "position": "工程师"
                }
            ]
        }
    ]
}';


		$ums_user_arr = $CI->API->UMSAPI($data,1);

		foreach ($user_ok_arr as $key => $value)
		{
			if($is_update_uc == 1){//是否更新操作UC用户表0不更新操作1更新操作;仅改变用户状态[不用更新] ;[批量改变用户信息时需要更新]
				//TODO 如果是批量更新，更新操作UC用户表信息，不改状态信息。
				$CI->load->model('UC_User_Model');
				$data = array(
                    'where' => 'userID=' . $userID,
                    'update_data' => array(  
                    'userID' =>$value['userID'],//该客户的站点ID
                    'name' =>$value['name'],//姓名
                    'sex' =>$value['sex'],//性别：（M：男；F：女）
                    'position' =>$value['position'],//职位
                    'phone' =>$value['phone'],//手机
                    'email' =>$value['email'],//邮箱
                    'tel' =>$value['tel'],//电话
				// 'status' =>$value['status'],//状态（0：删除；1：正常用户）

				),
				);
				$update_user_arr =  $CI->UC_User_Model->operateDB(5,$data);
			}


			$ucc_type = 0;//0不调用 1 – 员工入职 3  - 员工离职
			switch ($operat_type) {
				case 5: //5以前是已关闭，现在开启,用户以前没有开户过，先调UMS更新,走BOSS新建流程
					$ucc_type = 1;//1 – 员工入职
					break;
				case 6: //6删除用户，当前用户状态为开启,则先调USM更新,走BOSS删卡流程
				case 7: //7删除用户,当前用户关闭状态[以前开启过],则只调UMS更新.走BOSS删卡流程
				case 8: //8删除用户,当前用户关闭状态[一直未开启过],则只调UMS更新.不用走BOSS
					$ucc_type = 3;//3  - 员工离职
					break;

			}
			if($ucc_type > 0){
				//TODO 调战役组织消息接口[员工离职]删除时候调用

				/* 员工离职json数据json={}

				*
				*/
				$api_data_arr = array();
				$data = 'user_id=' . $user_id . '&session_id=' . $session_id . '&type =' . $UCC_TYPE . '&data=' . json_decode($api_data_arr);
				$api_arr = $CI->API->UCCServerAPI($data,5,array('type'=> $ucc_type));
				if(api_operate_fail($api_arr)){//失败
					log_message('error', 'uccapi message_org leave office  fail.');

				}else{
					log_message('debug', 'uccapi message_org leave office success.');
				}


				if($operat_type == 0)//$operat_type 0新建账号[开启]1新建账号[关闭]
				{
					if($open_style == 0){// 开通方式 0 UC向BOSS开通用户，1BOSS向UC开通站点
						//TODO 3、组织用户数组[权限]等 先从用户权限获取，没有再从部门或上级部门权限获得，再没有则从站点权限获取
						$this->getPower(1);
					}
				}
			}

			if($boss_type != 0)//0代表不调用BOSS接口
			{
				if($open_style == 0){// 修改方式 0 UC向BOSS修改用户，1BOSS向UC修改站点
					//3、组织用户数组[权限]等 先从用户权限获取，没有再从部门或上级部门权限获得，再没有则从站点权限获取
					$this->getPower(1);
				}

			}

		}
		if($boss_type != 0)//0代表不调用BOSS接口
		{
			$boss_data = '';
			//$flow_type 1新开通流程[全新开启] 2开启更新UPDATE流程[以前关闭，现在是开启] 3关闭流程[以前开启，现在半闭]
			//BOSS回调地址
			//TODO $customerCode $siteID 还没有给值，获得当前站点域的url
			$data = 'customer_code=' . $customerCode . '&siteid=' . $siteID;
			$ns_cluster_domain_arr = $CI->API->UCAPI($data,6,array('url' => UC_DOMAIN_URL ));
			$domain_cluster_arr = array();
			if(api_operate_fail($ns_cluster_domain_arr)){//失败
				echo api_json_msg(1,array('msg' => ' uc api api/allocation/get_cluster ' . $data . ' fail') , 1);
				return 0;
				die();
			}else{
				$domain_cluster_arr = arr_unbound_value($ns_cluster_domain_arr['other_msg'],'data',1,array());
				log_message('debug', ' uc api api/allocation/get_cluster ' . $data . ' success.');
			}
			//$domain_cluster_arr = $this->get_domain_bycustomercode($customerCode,$siteID);
			$cluster_arr = isset($domain_cluster_arr['cluster'])?$domain_cluster_arr['cluster']:array();
			if(isemptyArray($cluster_arr)){//如果没有分配的域
				echo api_json_msg(1,array('msg' => '$cluster_arr isemptyArray') , 1);
				return 0;
				die();
			}
			$URL = isset($cluster_arr['url'])?$cluster_arr['url']:'';//获得域url
			if(bn_is_empty($URL)){//没有分配域的url
				echo api_json_msg(1,array('msg' => ' cluster url is empty') , 1);
				return 0;
				die();
			}
			$ip = isset($cluster_arr['ip'])?$cluster_arr['ip']:'';//获得域ip
			if(bn_is_empty($ip)){//没有分配域的url
				echo api_json_msg(1,array('msg' => ' cluster ip is empty') , 1);
				return 0;
				die();
			}
			$callback_url = 'http://' . $URL . UC_DOMAIN_DIR . '/api/asynOpen/' . $flow_type;//site_url('api/asynOpen') . '/' . $flow_type;//回调地址
			//$callback_url = 'http://' . BOSS_CALLBACK_IP . UC_DOMAIN_DIR . '/api/asynOpen/' . $flow_type;
			// $callback_url = 'http://' . $ip . UC_DOMAIN_DIR . '/api/asynOpen/' . $flow_type;
			if($open_style == 0){// 修改方式 0 UC向BOSS修改用户，1BOSS向UC修改站点
				//批量开通¬-向boss，同步返回成功,后面会异步调用我们的接口[需要确认boss接口]
				$boss_user_arr = $CI->API->BOSSAPI($boss_data,1);
			}else{//1BOSS向UC开通站点
				//执行回调函数
				$boss_user_arr = $CI->API->BOSS_Special_API($boss_data,1,array('url' => $back_url));
				if(api_operate_fail($boss_user_arr)){//失败
					log_message('error', ' uc callback  fail.');

				}else{
					log_message('debug', 'uc callback success.');
				}
			}
		}
		log_message('info', 'out method ' . __FUNCTION__ . '.');

	}
	/**
	 * @brief 获得权限方法;先从用户权限获取，没有再从部门或上级部门权限获得，再没有则从站点权限获取
	 * @details
	 * @param int 站点id
	 * @param int 组织id
	 * @param int $user_type 当前用户类型1用户2组织3站点
	 * @return array 权限串
	 */

	public function getPower($user_type = 1){


	}


	/**
	 * @brief BOSS开通接口，打日志
	 * @details
	 * @param int $errno 日志错误号
	 * @param string $logtxt 日志内容
	 * @return null
	 */
	public function boss_err($errno,$logtxt)
	{
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		if(bn_is_empty($errno)){//没有数据
			$errno = -1;
		}
		log_message('error', $logtxt);
		echo api_json_msg($errno,array('msg' => $logtxt) , 1);
	}
	/**
	 * @brief BOSS向UC开通站点,同时输出json信息
	 * @details
	 * @param json $thread_value BOSS 传过来的参数
	 * @return 0:失败；1：成功。2回调失败
	 */
	public function boss_open_site($thread_value)
	{

		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->helper('my_dgmdate');
		$CI->load->library('API','','API');
		$CI->load->library('PowerLib','','PowerLib');
		//向UC的管理员表写数据,判断有没有，有了就做修改处理。 userid 和role_id总管理员 、type
		$CI->load->model('UC_User_Admin_Model');
		$CI->load->model('UC_User_Model');
		//所有用户数组
		$users_arr = array();
		//所有没有成功的用户数组
		$all_user_failed_arr = array();
		//所有成功的用户数组
		$callback_user_successed_arr = array();

		//从数据库获得BOSS调用开通站点时的json数据
		$BOSS_post_json = $thread_value;
		if(bn_is_empty($BOSS_post_json)){//没有数据
			$this->boss_err(-1,' get BOSS post json fail.');
			return 0;
		}

		//数据转换
		$BOSS_post_arr = json_decode($BOSS_post_json , TRUE );
		if(isemptyArray($BOSS_post_arr)){//如果是空数组
			$this->boss_err(-1,' BOSS post json to array fail.');
			return 0;
		}

		//获得回调地址
		$callback = arr_unbound_value($BOSS_post_arr,'callback',2,''); //'http://192.168.17.57:8880/activation-engine/ResponseApi/contract';
		if(bn_is_empty($callback)){//如果是空
			$this->boss_err(-1,' get post param callback is empty .');
			return 0;
		}
		log_message('info', 'get callback=' . $callback . '  success.');

		//获得操作类型
		$boss_type = arr_unbound_value($BOSS_post_arr,'type',2,'');
		if(bn_is_empty($boss_type)){//为空
			$this->boss_err(-1,' get post param type not is  empty.');
			return 0;
		}
		log_message('info', 'get  type=' . $boss_type . '  success.');

		//requestId
		$requestId = arr_unbound_value($BOSS_post_arr,'requestId',2,'');
		if(bn_is_empty($requestId)){//如果是空
			$this->boss_err(-1,' get post param requestId is empty .');
			return 0;
		}
		log_message('info', 'get requestId=' . $requestId . '  success.');

		//合同数组
		$contract_arr = arr_unbound_value($BOSS_post_arr['customer'],'contract',1,array());
			
		//合同id
		$contract_id = arr_unbound_value($contract_arr,'id',2,0);
		log_message('info', 'get $contract_id=' . $contract_id . '  success.');

		//看是否有user ，如果没有则callback
		$users_arr = arr_unbound_value($BOSS_post_arr['customer'],'users',1,array());

		//当前属性
		$components_arr = arr_unbound_value($BOSS_post_arr['customer']['contract'],'components',1,array());
		log_message('info', '4============$components_arr'.var_export($components_arr, true).'=================4');
		$uc_auth = 0;//用户开通类型0普通用户开通1管理员开通
		if(!isemptyArray($users_arr)){//有users节点,则不是合同开通，是用户开通
			foreach($components_arr as $k => $v){
				if(is_array($v)){
					$ns_name = isset($v['name'])?$v['name']:'';
					$ns_name_lower = strtolower($ns_name);//转换为小写
					if($ns_name_lower == 'uc'){
						$uc_auth = isset($v['property']['auth'])?$v['property']['auth']:0 ;
						break;
					}
				}
			}
		}
		log_message('info', '3============$components_arr'.var_export($components_arr, true).'=================3');
		log_message('info', 'get  property auth $uc_auth=' . $uc_auth . '  success.');

		$uc_manager_arr = array();//用户管理员数组
		//获得管理员数组内容
		if(!isemptyArray($users_arr)){//有users节点,则不是合同开通，是用户开通
			foreach($users_arr as $u_k => $u_v){
				$u_id = arr_unbound_value($u_v,'id',2,'');
				if(!bn_is_empty($u_id)){//有数据
					$u_auth = '';//用户的auth
					$u_components_arr = arr_unbound_value($u_v,'components',1,array());//用户自已属性
					if(!isemptyArray($u_components_arr)){//有属性值
						foreach($u_components_arr as $u_com_k => $u_com_v){
							$u_com_name = arr_unbound_value($u_com_v,'name',2,'');
							if(strtolower($u_com_name) == 'uc'){
								$u_auth = arr_unbound_value($u_com_v['property'],'auth',2,'');
								break;
							}
						}
					}

					$u_auth = empty_to_value($u_auth, $uc_auth);
					if($u_auth == 1){//0普通用户开通1管理员开通
						$uc_manager_arr[] = $u_id;//用户管理员数组
					}
				}
			}
		}

		//如果没有管理员
		if($uc_auth == 1 && isemptyArray($uc_manager_arr)){
			$uc_auth = 0;
		}

		//if(!isemptyArray($uc_manager_arr)){//不为空
		// $uc_auth == 1;
		// }
		log_message('debug', '$uc_auth=' . $uc_auth . ' $uc_manager_arr=' . json_encode($uc_manager_arr). '.');
		//获得操作类型
		$open_type =1;//1新建账号 2修改账号 3停用账号 4 启用账号 5删卡
		$open_class = 1;//账号类型1合同[站点权限/部门权限修改也可能是这样]2帐号
		if(!isemptyArray($users_arr)){//有users节点,则不是合同开通，是用户开通
			$open_class = 2;//账号类型1合同2帐号
		}
		switch (strtolower($boss_type)) {
			case 'create':// 新建账号
				$open_type = 1;//1新建账号
				break;
			case 'update'://2修改账号
				$open_type = 2;
				break;
			case 'disable':// 3停用账号
				$open_type = 3;
				break;
			case 'enable':// 4 启用账号
				$open_type = 4;
				break;
			case 'delete':// 5删卡
				$open_type = 5;
				break;
			default:
				$open_type = 1;
				break;
		}
		log_message('info', 'get  $open_class=' . $open_class . '  $open_type=' . $open_type . '  success.');

		//获得客户编码
		$customerCode = arr_unbound_value($BOSS_post_arr['customer'],'customerCode',2,'');
		if(bn_is_empty($customerCode)){//如果是空
			$err_msg = ' get post param customerCode is empty .';
			$this->boss_err(-1,$err_msg);
			$boss_err_arr = array(
               'users_arr' => $users_arr,//$users_arr,//所有用户
               'successed_arr' => $callback_user_successed_arr,//$callback_user_successed_arr,//成功用户
               'failed_arr' => $all_user_failed_arr,//$all_user_failed_arr,//失败用户
               'errorCode' => '1',//当前错误:自己处理的流程步骤
               'error_msg' => $err_msg,//当前错误:捕获的异常信息
               'calltype' => $open_class,//回调类型
               'requestId' => $requestId,//回调id
               'result' => -1,//1标示开通成功  -1标示开通失败开通合同时
               'contractId' => $contract_id,//合同id
               'callback' => $callback,//回调地址  
			);
			return $this->boss_err_callback($boss_err_arr);//错误回调
		}
		log_message('info', 'get customer customerCode $customerCode=' . $customerCode . '  success.');

		//产品id
		$product_id = UC_PRODUCT_ID;
		if(bn_is_empty($product_id)){//如果是空
			$err_msg = ' get post param product_id is empty .';
			$this->boss_err(-1,$err_msg);
			$boss_err_arr = array(
               'users_arr' => $users_arr,//$users_arr,//所有用户
               'successed_arr' => $callback_user_successed_arr,//$callback_user_successed_arr,//成功用户
               'failed_arr' => $all_user_failed_arr,//$all_user_failed_arr,//失败用户
               'errorCode' => '2',//当前错误:自己处理的流程步骤
               'error_msg' => $err_msg,//当前错误:捕获的异常信息
               'calltype' => $open_class,//回调类型
               'requestId' => $requestId,//回调id
               'result' => -1,//1标示开通成功  -1标示开通失败开通合同时
               'contractId' => $contract_id,//合同id
               'callback' => $callback,//回调地址  
			);
			return $this->boss_err_callback($boss_err_arr);//错误回调
			//return 0;
		}
		log_message('info', 'get $product_id=' . $product_id . '  success.');

		//获得站点名称
		//$boss_id = isset($BOSS_post_arr['customer']['id'])?$BOSS_post_arr['customer']['id']:'' ;
		//$site_name = arr_unbound_value($BOSS_post_arr['customer'],'name',2,'');
		
		//调用boss接口，获取客户名称
		$CI->load->library('BossLib', '', 'boss');
		$customer_info  = $CI->boss->getCustomerInfo($customerCode);
		$site_name		= isset($customer_info['name']) ? $customer_info['name'] : '';
		
		if(bn_is_empty($site_name)){//如果是空
			$err_msg = ' get post param  customer name is empty .';
			$this->boss_err(-1,$err_msg);
			$boss_err_arr = array(
               'users_arr' => $users_arr,//$users_arr,//所有用户
               'successed_arr' => $callback_user_successed_arr,//$callback_user_successed_arr,//成功用户
               'failed_arr' => $all_user_failed_arr,//$all_user_failed_arr,//失败用户
               'errorCode' => '3',//当前错误:自己处理的流程步骤
               'error_msg' => $err_msg,//当前错误:捕获的异常信息
               'calltype' => $open_class,//回调类型
               'requestId' => $requestId,//回调id
               'result' => -1,//1标示开通成功  -1标示开通失败开通合同时
               'contractId' => $contract_id,//合同id
               'callback' => $callback,//回调地址  
			);
			return $this->boss_err_callback($boss_err_arr);//错误回调
			//return 0;
		}
		log_message('info', 'get customer name $site_name=' . $site_name . '  success.');

		//站点url
		$siteURL = '';
		$siteURL = arr_unbound_value($BOSS_post_arr['customer']['contract']['resource'],'siteURL',2,'');
		if(bn_is_empty($siteURL)){//如果是空
			$err_msg = 'get post customer  contract resource siteURL is empty .';
			$this->boss_err(-1,$err_msg);
			$boss_err_arr = array(
               'users_arr' => $users_arr,//$users_arr,//所有用户
               'successed_arr' => $callback_user_successed_arr,//$callback_user_successed_arr,//成功用户
               'failed_arr' => $all_user_failed_arr,//$all_user_failed_arr,//失败用户
               'errorCode' => '4',//当前错误:自己处理的流程步骤
               'error_msg' => $err_msg,//当前错误:捕获的异常信息
               'calltype' => $open_class,//回调类型
               'requestId' => $requestId,//回调id
               'result' => -1,//1标示开通成功  -1标示开通失败开通合同时
               'contractId' => $contract_id,//合同id
               'callback' => $callback,//回调地址  
			);
			return $this->boss_err_callback($boss_err_arr);//错误回调
			//return 0;
		}
		log_message('info', 'get customer contract resource siteURL  $siteURL=' . $siteURL . '  success.');

		//注意这里可能会出错 站点id 要从ums获得;合同开通时,BOSS给的json没有站点id siteid,所以要从UMS去拿
		$siteId = '';//isset($BOSS_post_arr['customer']['users'][0]['siteId'])?$BOSS_post_arr['customer']['users'][0]['siteId']:'' ;
		//通过站点URL精确查询站点
		$uc_site_arr = $CI->API->UMS_Special_API('',3,array('url' => $siteURL));
		if(api_operate_fail($uc_site_arr)){//失败
			$err_msg = ' usm api rs/sites?url is empty .' ;
			$this->boss_err(-1,$err_msg);
			$boss_err_arr = array(
               'users_arr' => $users_arr,//$users_arr,//所有用户
               'successed_arr' => $callback_user_successed_arr,//$callback_user_successed_arr,//成功用户
               'failed_arr' => $all_user_failed_arr,//$all_user_failed_arr,//失败用户
               'errorCode' => '5',//当前错误:自己处理的流程步骤
               'error_msg' => $err_msg,//当前错误:捕获的异常信息
               'calltype' => $open_class,//回调类型
               'requestId' => $requestId,//回调id
               'result' => -1,//1标示开通成功  -1标示开通失败开通合同时
               'contractId' => $contract_id,//合同id
               'callback' => $callback,//回调地址  
			);
			return $this->boss_err_callback($boss_err_arr);//错误回调
			//return 0;
		}else{
			$siteId = isset($uc_site_arr['id'])?$uc_site_arr['id']:'';;
		}
		if(bn_is_empty($siteId)){//如果是空
			$err_msg = ' get post param $siteId is empty .';
			$this->boss_err(-1,$err_msg);
			$boss_err_arr = array(
               'users_arr' => $users_arr,//$users_arr,//所有用户
               'successed_arr' => $callback_user_successed_arr,//$callback_user_successed_arr,//成功用户
               'failed_arr' => $all_user_failed_arr,//$all_user_failed_arr,//失败用户
               'errorCode' => '6',//当前错误:自己处理的流程步骤
               'error_msg' => $err_msg,//当前错误:捕获的异常信息
               'calltype' => $open_class,//回调类型
               'requestId' => $requestId,//回调id
               'result' => -1,//1标示开通成功  -1标示开通失败开通合同时
               'contractId' => $contract_id,//合同id
               'callback' => $callback,//回调地址  
			);
			return $this->boss_err_callback($boss_err_arr);//错误回调
			//return 0;
		}
		log_message('info', 'get usm api rs/sites?url= ' . $siteURL . ' $siteId=' . $siteId . '  success.');
			
		//站点是否第一次加管理员
		$is_first_manager = 0;//是否第一次加管理员0不是1是
		//根据siteid判断
		$sel_field = 'userID,orgID';
		$where_arr = array(
                'siteID' => $siteId,                           
		);
		$sel_arr = $CI->UC_User_Admin_Model->get_db_arr($where_arr,$sel_field);
		if($uc_auth == 1){//管理员
			if(isemptyArray($sel_arr)){//如果是空数组
				log_message('debug', 'UC_User_Admin_Model siteID = ' .  siteID . '  is empty.');// TODO错误
				$is_first_manager = 1;//是否第一次加管理员0不是1是
			}else{
				$site_orgID = arr_unbound_value($sel_arr,'orgID',2,'');
			}
		}else{
			$site_orgID = arr_unbound_value($sel_arr,'orgID',2,'');
		}

		$CI->load->model('UC_Account_Model');
		if($is_first_manager == 1){//第一次开管理员时($uc_auth ==1 && $open_type == 1)开1合同或开管理员时保存 $open_class == 1 ||
			//获得分帐id信息[可以为空]
			$accounts_arr = isset($BOSS_post_arr['customer']['accounts'])?$BOSS_post_arr['customer']['accounts']:array();
			log_message('info', 'get $accounts_arr =' . json_encode($accounts_arr) . ' .');
			//分帐信息,只要用分帐信息就保存
			//保存分帐信息 uc_account
			if(isemptyArray($accounts_arr)){//如果是空数组
				foreach($users_arr as $u_k => $u_v){
					$u_accountId = arr_unbound_value($u_v,'accountId',2,'');
					if(!bn_is_empty($u_accountId)){//有数据
						if (!deep_in_array($u_accountId, $accounts_arr)) {//不在数组里面
							$accounts_arr[] =  array(
                             'accountId' => $u_accountId,
                             'name' => $site_name,
							);
						}
					}
				}
			}
			//保存分账信息
			if(!isemptyArray($accounts_arr)){//如果不是空数组
				foreach($accounts_arr as $account_k => $account_v){
					if(!isemptyArray($account_v)){//如果不是空数组
						$ns_account_id = arr_unbound_value($account_v,'accountId',2,'');
						$ns_account_name = arr_unbound_value($account_v,'name',2,'');
						if( (!bn_is_empty($ns_account_id)) && (!bn_is_empty($ns_account_name))){//有数据
							//1、有记录则更新记录，没记录则新加；
							$select_field = 'id';
							$where_arr = array(
                                'id' => $ns_account_id,//QSBOSS给的ID user标签里的 
                                'customercode' => $customerCode,//客户编码
                                'org_id' =>0//组织id
							);
							$modify_arr = array(
                                'id' => $ns_account_id,//QSBOSS给的ID
                                'account_name' => $ns_account_name,//分账名称
                                'customercode' => $customerCode,//客户编码
                                'org_id' =>0//组织id
							);
							$insert_arr = $modify_arr;
							//$insert_arr['sss'] = dgmdate(time(), 'dt');
							$re_num = $CI-> UC_Account_Model -> updata_or_insert(1,$select_field,$where_arr,$modify_arr,$insert_arr);
							if($re_num == -2 || $re_num == -4){//失败 -1 更新记录成功 -2更新操作失败 -3新加的记录id 新加记录成功 -4新加操作失败 -5 没有操作
								$err_msg = 'update/insert  UC_Account_Model fail. $re_num =' . $re_num . ' ';
								log_message('error', $err_msg);
							}else{
								log_message('info', 'update/insert  UC_Account_Model success. $re_num =' . $re_num . ' .');
							}
						}
					}
				}
			}
		}

		$CI->load->model('UC_Customer_Model');
		//合同开通回调
		if($open_class == 1){//没有users节点,是合同开通,则执行回调
			if( $open_type == 1 ){//1新建账号2修改  || $open_type == 2
				//判断合同是否已经存在
				//根据siteid判断
				$sel_field = 'id';
				$where_arr = array(
                      'customerCode' => $customerCode,
                      'siteId' => $siteId,//站点id                       
				);
				$sel_arr = $CI->UC_Customer_Model->get_db_arr($where_arr,$sel_field);
				if(!isemptyArray($sel_arr)){//不为空,已经有记录
					$err_msg = '  UC_Customer_Model is exit. $where_arr =' . json_encode($where_arr) . ' ';
					$this->boss_err(-1,$err_msg);
					$boss_err_arr = array(
                       'users_arr' => $users_arr,//$users_arr,//所有用户
                       'successed_arr' => $callback_user_successed_arr,//$callback_user_successed_arr,//成功用户
                       'failed_arr' => $all_user_failed_arr,//$all_user_failed_arr,//失败用户
                       'errorCode' => '7',//当前错误:自己处理的流程步骤
                       'error_msg' => $err_msg,//当前错误:捕获的异常信息
                       'calltype' => $open_class,//回调类型
                       'requestId' => $requestId,//回调id
                       'result' => -1,//1标示开通成功  -1标示开通失败开通合同时
                       'contractId' => $contract_id,//合同id
                       'callback' => $callback,//回调地址  
					);
					return $this->boss_err_callback($boss_err_arr);//错误回调
				}
				//保存合同属性 1、有记录则更新记录，没记录则新加；3、有记录则不操作，没有则新加
				//保存站点客户表uc_customer[有则更新，没有则新加]
				$select_field = 'id';
				$where_arr = array(
                      'customerCode' => $customerCode,
                      'contractId' => $contract_id,
                      'siteId' => $siteId,//站点id
				);
				$modify_arr = array(
                    'customerCode' =>$customerCode,//客户编码
                    'contractId' =>$contract_id,//$requestId,//id 
                    'siteId' => $siteId,//站点id
                    'name' => $site_name,//客户名称
                    'value' => $BOSS_post_json,//BOSS合同开通JSON数据全保存;json_encode($components_arr),//站点权限配置（Json串）
				);
				log_message('info', '2============$components_arr'.var_export(array('BOSS_post_json' =>$BOSS_post_json), true).'=================2');
				$insert_arr = $modify_arr;
				$insert_arr['createTime'] = dgmdate(time(), 'dt');
				$re_num = $CI-> UC_Customer_Model -> updata_or_insert(1,$select_field,$where_arr,$modify_arr,$insert_arr);
				if($re_num == -2 || $re_num == -4){//失败 -1 更新记录成功 -2更新操作失败 -3新加的记录id 新加记录成功 -4新加操作失败 -5 没有操作
					$err_msg = 'update/insert  UC_Customer_Model fail. $re_num =' . $re_num . ' ';
					$this->boss_err(-1,$err_msg);
					$boss_err_arr = array(
                       'users_arr' => $users_arr,//$users_arr,//所有用户
                       'successed_arr' => $callback_user_successed_arr,//$callback_user_successed_arr,//成功用户
                       'failed_arr' => $all_user_failed_arr,//$all_user_failed_arr,//失败用户
                       'errorCode' => '7',//当前错误:自己处理的流程步骤
                       'error_msg' => $err_msg,//当前错误:捕获的异常信息
                       'calltype' => $open_class,//回调类型
                       'requestId' => $requestId,//回调id
                       'result' => -1,//1标示开通成功  -1标示开通失败开通合同时
                       'contractId' => $contract_id,//合同id
                       'callback' => $callback,//回调地址  
					);
					return $this->boss_err_callback($boss_err_arr);//错误回调
				}
				log_message('info', 'update/insert  UC_Customer_Model success. $re_num =' . $re_num . ' .');

			}
			//成功，则调用成功的回调
			$boss_err_arr = array(
              'users_arr' => $users_arr,//$users_arr,//所有用户
              'successed_arr' => $callback_user_successed_arr,//$callback_user_successed_arr,//成功用户
              'failed_arr' => $all_user_failed_arr,//$all_user_failed_arr,//失败用户
              'errorCode' => '9',//当前错误:自己处理的流程步骤
              'error_msg' => '',//当前错误:捕获的异常信息
              'calltype' => $open_class,//回调类型
              'requestId' => $requestId,//回调id
              'result' => 1,//1标示开通成功  -1标示开通失败开通合同时
              'contractId' => $contract_id,//合同id
              'callback' => $callback,//回调地址  
			);
			log_message('info', 'callback   $callback =' . $callback . '  success.');
			return $this->boss_err_callback($boss_err_arr);//回调
		}

		//继续

		$components_arr = $CI->PowerLib->power_add_id($components_arr);
		log_message('info', '1===========$components_arr'.var_export($components_arr, true).'=================1');

		//获得合同属性[只有管理员才需要]
		//合同属性
		$web_customer_arr = array();
		$web_customer_uc_arr = array();//合同uc属性
		$web_customer_uc_property = array();//合同uc属性
		//创建管理员获得合同属性
		if($is_first_manager == 1){//第一次加管理员$uc_auth == 10普通用户开通1管理员开通
			if($open_type == 1){//1新建账号
				$sel_field = 'value';
				$where_arr = array(
                    'siteId' => $siteId,
                    'customerCode' => $customerCode,
                    'contractId' => $contract_id,
				);
				$sel_arr = $CI->UC_Customer_Model->get_db_arr($where_arr,$sel_field);
				if(!isemptyArray($sel_arr)){//有站点合同属性
					$site_boss_json = isset($sel_arr['value'])?$sel_arr['value']:array();
					if(!is_not_json($site_boss_json)){//是json串
						$site_boss_arr = json_decode($site_boss_json,true);
						if(!isemptyArray($site_boss_arr)){//不是空数组
							//当前属性
							$web_customer_arr = arr_unbound_value($site_boss_arr['customer']['contract'],'components',1,array());
							if(isemptyArray($web_customer_arr)){//如果是空数组,兼容以前只保存属性
								$web_customer_arr = $site_boss_arr;
							}
							foreach($web_customer_arr as $k => $v){
								$ns_name = isset($v['name'])?$v['name']:'';
								if(strtolower($ns_name) == 'uc'){//是uc,注意只拿UC属性
									$web_customer_uc_arr = $v;
									$web_customer_uc_property = isset($v['property'])?$v['property']:'';//合同uc属性
									break;
								}
							}
						}
					}
					log_message('info', ' get table UC_Customer_Model [' . json_encode($where_arr). ']   success .');
				}else{//没有站点合同,则报错callback BOSS
					$err_msg = ' get table UC_Customer_Model [' . json_encode($where_arr). ']  is empty .';
					$this->boss_err(-1,$err_msg);
					$boss_err_arr = array(
                         'users_arr' => $users_arr,//$users_arr,//所有用户
                         'successed_arr' => $callback_user_successed_arr,//$callback_user_successed_arr,//成功用户
                         'failed_arr' => $all_user_failed_arr,//$all_user_failed_arr,//失败用户
                         'errorCode' => '11',//当前错误:自己处理的流程步骤
                         'error_msg' => $err_msg,//当前错误:捕获的异常信息
                         'calltype' => $open_class,//回调类型
                         'requestId' => $requestId,//回调id
                         'result' => -1,//1标示开通成功  -1标示开通失败开通合同时
                         'contractId' => $contract_id,//合同id
                         'callback' => $callback,//回调地址  
					);
					return $this->boss_err_callback($boss_err_arr);//错误回调
					// return 0;
				}
			}
		}

		//根据客户编码和站点id获得域ip及域url信息
		$web_cluster_id = '';
		$web_cluster_url = '';
		$CI->load->library('WebLib','','WebLib');
		$uc_cluster_domain_arr = $CI->WebLib-> get_cluster($customerCode,$siteId);
		$web_cluster_id = arr_unbound_value($uc_cluster_domain_arr['cluster'],'ip',2,'');
		$web_cluster_url = arr_unbound_value($uc_cluster_domain_arr['cluster'],'url',2,'');
		if(bn_is_empty($web_cluster_id) || bn_is_empty($web_cluster_url)){
			$err_msg = ' get $web_cluster_id OR  $web_cluster_url is empty $web_cluster_id=' . $web_cluster_id . ' $web_cluster_url=' . $web_cluster_url . '.';
			$this->boss_err(-1,$err_msg);
			$boss_err_arr = array(
                 'users_arr' => $users_arr,//$users_arr,//所有用户
                 'successed_arr' => $callback_user_successed_arr,//$callback_user_successed_arr,//成功用户
                 'failed_arr' => $all_user_failed_arr,//$all_user_failed_arr,//失败用户
                 'errorCode' => '11',//当前错误:自己处理的流程步骤
                 'error_msg' => $err_msg,//当前错误:捕获的异常信息
                 'calltype' => $open_class,//回调类型
                 'requestId' => $requestId,//回调id
                 'result' => -1,//1标示开通成功  -1标示开通失败开通合同时
                 'contractId' => $contract_id,//合同id
                 'callback' => $callback,//回调地址  
			);
			return $this->boss_err_callback($boss_err_arr);//错误回调
		}
		log_message('debug', '  $web_cluster_id=' . $web_cluster_id . ' $web_cluster_url=' . $web_cluster_url . '.');

		//第一个用户的分帐id
		//$user_accountId = isset($users_arr[0]['accountId'])?$users_arr[0]['accountId']:'';
		// log_message('info', 'get first user accountId =' . $user_accountId . ' .');
		//echo $user_accountId;
		//exit;
			
		//管理员属性
		//部门ID
		$departmentID = 0;//默认为0，表示没有部门。
		//管理员类型[BOSS调我们时，传入role 1管理员0普通用户]
		$admin_type = 0;//1：总公司管理员；2：分公司管理员；3：生态企业管理员；0：其它' ,
		//LOGO的URL地址
		//$logoUrl = '';
		//0：单一企业；1：集中管理；2分散管理
		$companyType = 0;
		//导入类型
		//0：否（批量导入）；1：是（LDAP导入）；2：全部都可以' ,
		$isLDAP = 0;
		//部门层级
		$showOrgTree = 0;

		//获得UC信息
		$uc_arr = array();//uc属性
		//$summit_arr = array();//summit属性
		//$tang_arr = array();//tang属性
		$user_collect = '';//summit Collect值
		foreach($components_arr as $k => $v){
			if(is_array($v)){
				$ns_name = arr_unbound_value($v,'name',2,'');
				$ns_name_lower = strtolower($ns_name);//转换为小写
				switch ($ns_name_lower) {
					case 'uc': //是uc的
						//array_merge[注意只能对当前一级的层有作用，第二层需要深入进去处理]如果键名有重复，该键的键值为最后一个键名对应的值（后面的覆盖前面的）。如果数组是数字索引的，则键名会以连续方式重新索引。
						if($is_first_manager == 1){//第一次加入管理员时$uc_auth == 1 0普通用户开通1管理员开通,管理员才需要,用当前管理员的开通属性，去覆盖合同的uc属性
							if($open_type == 1){//1新建账号
								$ns_uc_property_arr = arr_unbound_value($v,'property',1,array());//获得用户属性
								$ns_uc_property_ok_arr= array_merge($web_customer_uc_property,$ns_uc_property_arr);//当前属性去
								$v['property'] = $ns_uc_property_ok_arr;
								$uc_arr = array_merge($web_customer_uc_arr,$v);
								$uc_arr['property']['auth'] = 0;//改变auth的值为普通用户
								$components_arr[$k] = $uc_arr;
								$admin_type = 1;
								//更改当前数组
								//$siteURL = isset($uc_arr['property']['siteurl'])?$uc_arr['property']['siteurl']:'';//站点url
								//$admin_type = isset($uc_arr['property']['auth'])?$uc_arr['property']['auth']:1;
								$companyType = isset($uc_arr['property']['companytype'])?$uc_arr['property']['companytype']:0;
								$isLDAP = isset($uc_arr['property']['isLDAP'])?$uc_arr['property']['isLDAP']:0;
								$showOrgTree = isset($uc_arr['property']['showOrgTree'])?$uc_arr['property']['showOrgTree']:0;
								if($companyType == ''){//如果是空
									$err_msg = ' get post param $companyType is empty .';
									$this->boss_err(-1,$err_msg);
									$boss_err_arr = array(
                                   'users_arr' => $users_arr,//$users_arr,//所有用户
                                   'successed_arr' => $callback_user_successed_arr,//$callback_user_successed_arr,//成功用户
                                   'failed_arr' => $all_user_failed_arr,//$all_user_failed_arr,//失败用户
                                   'errorCode' => '14',//当前错误:自己处理的流程步骤
                                   'error_msg' => $err_msg,//当前错误:捕获的异常信息
                                   'calltype' => $open_class,//回调类型
                                   'requestId' => $requestId,//回调id
                                   'result' => -1,//1标示开通成功  -1标示开通失败开通合同时
                                   'contractId' => $contract_id,//合同id
                                   'callback' => $callback,//回调地址  
									);
									return $this->boss_err_callback($boss_err_arr);//错误回调
									// return 0;
								}
								if($isLDAP == ''){//如果是空
									$err_msg = ' get post param $isLDAP is empty .';
									$this->boss_err(-1,$err_msg);
									$boss_err_arr = array(
                                   'users_arr' => $users_arr,//$users_arr,//所有用户
                                   'successed_arr' => $callback_user_successed_arr,//$callback_user_successed_arr,//成功用户
                                   'failed_arr' => $all_user_failed_arr,//$all_user_failed_arr,//失败用户
                                   'errorCode' => '15',//当前错误:自己处理的流程步骤
                                   'error_msg' => $err_msg,//当前错误:捕获的异常信息
                                   'calltype' => $open_class,//回调类型
                                   'requestId' => $requestId,//回调id
                                   'result' => -1,//1标示开通成功  -1标示开通失败开通合同时
                                   'contractId' => $contract_id,//合同id
                                   'callback' => $callback,//回调地址  
									);
									return $this->boss_err_callback($boss_err_arr);//错误回调
									//return 0;
								}
							}
						}else{
							$uc_arr = $v;
						}
						if(isemptyArray($uc_arr)){//没有uc信息
							$uc_arr = $v;
						}
						break;
					case 'summit'://summit属性
						//$summit_arr = $v;
						$user_collect = arr_unbound_value($v['property'],'Collect',2,'');//summit Collect值
						break;
					case 'tang'://tang属性
						//$tang_arr = $v;
						break;
				}

			}
		}
		log_message('debug', '  $user_collect=' . $user_collect . ' .');

		if(isemptyArray($uc_arr)){//没有uc信息
			$err_msg = ' $uc_arr is empty .';
			log_message('error', $err_msg);
			echo api_json_msg(-1,array('msg' => $err_msg) , 1);
			$boss_err_arr = array(
               'users_arr' => $users_arr,//$users_arr,//所有用户
               'successed_arr' => $callback_user_successed_arr,//$callback_user_successed_arr,//成功用户
               'failed_arr' => $all_user_failed_arr,//$all_user_failed_arr,//失败用户
               'errorCode' => '13',//当前错误:自己处理的流程步骤
               'error_msg' => $err_msg,//当前错误:捕获的异常信息
               'calltype' => $open_class,//回调类型
               'requestId' => $requestId,//回调id
               'result' => -1,//1标示开通成功  -1标示开通失败开通合同时
               'contractId' => $contract_id,//合同id
               'callback' => $callback,//回调地址  
			);
			return $this->boss_err_callback($boss_err_arr);//错误回调
			//return 0;
		}

		if($is_first_manager == 1){//第一次加管理员$uc_auth == 1 && $admin_type == 1 1：总公司管理员 0普通用户开通1管理员开通;只是管理员回调,才需要$site_orgID
			if($open_type == 1){//1新建账号
				//获得当前站点用户数量[只有管理员才会用到]
				$site_user_count = isset($uc_arr['property']['size'])?$uc_arr['property']['size']:'';
				if($site_user_count == ''){//如果是空
					$err_msg = ' get post param site_user_count is empty .';
					$this->boss_err(-1,$err_msg);
					$boss_err_arr = array(
                       'users_arr' => $users_arr,//$users_arr,//所有用户
                       'successed_arr' => $callback_user_successed_arr,//$callback_user_successed_arr,//成功用户
                       'failed_arr' => $all_user_failed_arr,//$all_user_failed_arr,//失败用户
                       'errorCode' => '16',//当前错误:自己处理的流程步骤
                       'error_msg' => $err_msg,//当前错误:捕获的异常信息
                       'calltype' => $open_class,//回调类型
                       'requestId' => $requestId,//回调id
                       'result' => -1,//1标示开通成功  -1标示开通失败开通合同时
                       'contractId' => $contract_id,//合同id
                       'callback' => $callback,//回调地址  
					);
					return $this->boss_err_callback($boss_err_arr);//错误回调
					//return 0;
				}
				$site_orgID = 0;//$product_id;//企业ID[只管理员时需要用]
				//.企业ID 通过UMS接口获取;创建组织,返回新创建的组织id
				$data_ums = array(
                    'name' => $site_name,
                    'code' => $customerCode,
                    'siturl' => $siteURL,
				//'childOrder' => null,
				//'parentId' => null,
                    'customercode' => $customerCode,                        
                    'type' => 1//1:企业 2:生态企业 3:部门 4:生态企业部门 5 分公司 逗号分割的字符串
				);
				$uc_org_arr = $CI->API->UMS_Special_API(json_encode($data_ums),1,array());

				if(api_operate_fail($uc_org_arr)){//失败
					$err_msg = 'ums api /rs/organizations fail.';
					$this->boss_err(-1,$err_msg);
					$boss_err_arr = array(
                       'users_arr' => $users_arr,//$users_arr,//所有用户
                       'successed_arr' => $callback_user_successed_arr,//$callback_user_successed_arr,//成功用户
                       'failed_arr' => $all_user_failed_arr,//$all_user_failed_arr,//失败用户
                       'errorCode' => '17',//当前错误:自己处理的流程步骤
                       'error_msg' => $err_msg,//当前错误:捕获的异常信息
                       'calltype' => $open_class,//回调类型
                       'requestId' => $requestId,//回调id
                       'result' => -1,//1标示开通成功  -1标示开通失败开通合同时
                       'contractId' => $contract_id,//合同id
                       'callback' => $callback,//回调地址  
					);
					return $this->boss_err_callback($boss_err_arr);//错误回调
					// return 0;

				}else{
					log_message('debug', 'ums api /rs/organizations success.');
				}
				$site_orgID = isset($uc_org_arr['org_id'])?$uc_org_arr['org_id']:0;
				log_message('info', 'get $site_orgID=' . $site_orgID . '  success.');
				if($site_orgID == 0){
					$err_msg = ' $site_orgID =' . $site_orgID;
					$this->boss_err(-1,$err_msg);
					$boss_err_arr = array(
                       'users_arr' => $users_arr,//$users_arr,//所有用户
                       'successed_arr' => $callback_user_successed_arr,//$callback_user_successed_arr,//成功用户
                       'failed_arr' => $all_user_failed_arr,//$all_user_failed_arr,//失败用户
                       'errorCode' => '17',//当前错误:自己处理的流程步骤
                       'error_msg' => $err_msg,//当前错误:捕获的异常信息
                       'calltype' => $open_class,//回调类型
                       'requestId' => $requestId,//回调id
                       'result' => -1,//1标示开通成功  -1标示开通失败开通合同时
                       'contractId' => $contract_id,//合同id
                       'callback' => $callback,//回调地址  
					);
					return $this->boss_err_callback($boss_err_arr);//错误回调
					// return 0;
				}

				//更新boss站点模板
				$component_prop_arr = array(
                    'templateUUID' => $siteURL,//可以是站点URL、location、部门名称,站点模板时siteurl；组织权限时组织部门id串
                    'contractId' => $contract_id,//合同id
                    'propType' => 2,//$boss_propType,//属性类型 1=合同属性；2=账号属性（默认值）
                    'components' => $components_arr,//权限
				);
				log_message('debug', ' save BOSS contractComponentProps api  $component_prop_arr = ' . json_encode($components_arr) . '.');
				$re_boolean = $CI->PowerLib->boss_save_power_mb($component_prop_arr);
				if(!$re_boolean){//失败

					$err_msg = ' save BOSS contractComponentProps api  fail.';
					//log_message('error', $err_msg);
					$this->boss_err(-1,$err_msg);
					$boss_err_arr = array(
                       'users_arr' => $users_arr,//$users_arr,//所有用户
                       'successed_arr' => $callback_user_successed_arr,//$callback_user_successed_arr,//成功用户
                       'failed_arr' => $all_user_failed_arr,//$all_user_failed_arr,//失败用户
                       'errorCode' => '171',//当前错误:自己处理的流程步骤
                       'error_msg' => $err_msg,//当前错误:捕获的异常信息
                       'calltype' => $open_class,//回调类型
                       'requestId' => $requestId,//回调id
                       'result' => -1,//1标示开通成功  -1标示开通失败开通合同时
                       'contractId' => $contract_id,//合同id
                       'callback' => $callback,//回调地址  
					);
					return $this->boss_err_callback($boss_err_arr);//错误回调
				}
				log_message('debug', ' save BOSS contractComponentProps api  success.');
				//添加密码设置
				$CI->load->model('UC_PWD_Manage_Model');
				// 1、有记录则更新记录，没记录则新加
				$select_field = 'id';
				$where_arr = array(
                      'org_id' => $site_orgID,//站点组织id
                      'site_id' => $siteId,//该客户的站点ID
				);
				$modify_arr = array(
                    'org_id' => $site_orgID,//站点组织id
                    'site_id' => $siteId,//该客户的站点ID
                    'expiry_day_type' => PWD_EXPIRY_DAY,
                    'history_type' => PWD_HISTORY_TYPE ,
                    'complexity_type' => PWD_COMPLEXITY_TYPE,
				);
				$insert_arr = $modify_arr;
				$insert_arr['create_time'] = dgmdate(time(), 'dt');
				$re_num = $CI-> UC_PWD_Manage_Model -> updata_or_insert(1,$select_field,$where_arr,$modify_arr,$insert_arr);
				if($re_num == -2 || $re_num == -4){//失败 -1 更新记录成功 -2更新操作失败 -3新加的记录id 新加记录成功 -4新加操作失败 -5 没有操作
					$err_msg = 'update/insert  UC_PWD_Manage fail. $re_num =' . $re_num . ' ';
					$this->boss_err(-1,$err_msg);
					$boss_err_arr = array(
                       'users_arr' => $users_arr,//$users_arr,//所有用户
                       'successed_arr' => $callback_user_successed_arr,//$callback_user_successed_arr,//成功用户
                       'failed_arr' => $all_user_failed_arr,//$all_user_failed_arr,//失败用户
                       'errorCode' => '19',//当前错误:自己处理的流程步骤
                       'error_msg' => $err_msg,//当前错误:捕获的异常信息
                       'calltype' => $open_class,//回调类型
                       'requestId' => $requestId,//回调id
                       'result' => -1,//1标示开通成功  -1标示开通失败开通合同时
                       'contractId' => $contract_id,//合同id
                       'callback' => $callback,//回调地址  
					);
					return $this->boss_err_callback($boss_err_arr);//错误回调
				}
				log_message('info', 'update/insert  UC_PWD_Manage success. $re_num =' . $re_num . ' .');
				//添加站点数据,判断有没有，有了就做修改处理。站点id 和 客户编码
				$CI->load->model('UC_Site_Model');
				// 1、有记录则更新记录，没记录则新加
				$select_field = 'id';
				$where_arr = array(
                      'contractId' => $contract_id,//$requestId,//合同id
                      'siteID' => $siteId,
                      'customerCode' => $customerCode    
				);
				$modify_arr = array(
                    'contractId' => $contract_id,//$requestId,//合同id
                    'siteID' =>$siteId,//该客户的站点ID
                    'domain' =>$siteURL,//域名
                    'department_level' => $showOrgTree,//部门层级
                    'companyType' =>$companyType,//0：单一企业；1：集中管理；2分散管理' ,
                    'isLDAP' =>$isLDAP,//0：否（批量导入）；1：是（LDAP导入）；2：全部都可以' ,
                    'customerCode' =>$customerCode,//客户编码
                    'value' => json_encode($components_arr),//站点时保存合同开通属性json_encode($components_arr),//站点权限配置（Json串）
				);
				$insert_arr = $modify_arr;
				$insert_arr['createTime'] = dgmdate(time(), 'dt');
				$re_num = $CI-> UC_Site_Model -> updata_or_insert(1,$select_field,$where_arr,$modify_arr,$insert_arr);
				if($re_num == -2 || $re_num == -4){//失败 -1 更新记录成功 -2更新操作失败 -3新加的记录id 新加记录成功 -4新加操作失败 -5 没有操作
					$err_msg = 'update/insert  UC_Site fail. $re_num =' . $re_num . ' ';
					$this->boss_err(-1,$err_msg);
					$boss_err_arr = array(
                       'users_arr' => $users_arr,//$users_arr,//所有用户
                       'successed_arr' => $callback_user_successed_arr,//$callback_user_successed_arr,//成功用户
                       'failed_arr' => $all_user_failed_arr,//$all_user_failed_arr,//失败用户
                       'errorCode' => '19',//当前错误:自己处理的流程步骤
                       'error_msg' => $err_msg,//当前错误:捕获的异常信息
                       'calltype' => $open_class,//回调类型
                       'requestId' => $requestId,//回调id
                       'result' => -1,//1标示开通成功  -1标示开通失败开通合同时
                       'contractId' => $contract_id,//合同id
                       'callback' => $callback,//回调地址  
					);
					return $this->boss_err_callback($boss_err_arr);//错误回调
				}
				log_message('info', 'update/insert  UC_Site success. $re_num =' . $re_num . ' .');
			}
		}

		//根据组织id，获得组织父id

		$CI->load->library('OrganizeLib','','OrganizeLib');
		$CI->load->library('StaffLib','','StaffLib');

		//用户id数组,供完成后修改状态用
		$user_id_arr = array();
		//给meet用的用户数组
		$meet_user_arr = array();
		//给BOSS回调返回成功的用户
		$callback_user_successed_arr = array();
		//给发送邮件用的用户信息
		$mss_user_arr = array();
		//给发送邮件的账号用户id 格式[1,2,3]
		$mss_user_ids = '';
		//循环遍历用户
		foreach($users_arr as $user_k => $user_v ){
			$ns_meet_user_arr = array();
			$ns_user_id = arr_unbound_value($user_v,'id',2,0);//userid
			if( $ns_user_id <=0 ){//失败,执行下一个
				$err_msg = ' ' . $ns_user_id . ' $ns_user_id fail <=0 .';
				log_message('debug', $err_msg);
				// echo api_json_msg(-1,array('msg' => $err_msg) , 1);
				continue;
			}else{
				log_message('debug', '$ns_user_id = ' . $ns_user_id . '.');
			}
			$user_is_manager = 0;//用户是否为管理员
			if(!isemptyArray($uc_manager_arr)){//不为空
				if(deep_in_array($ns_user_id, $uc_manager_arr)){//是新建的管理员
					$user_is_manager = 1;//用户是否为管理员
				}
			}
			$ns_is_success = 1;//是否操作成功0不成功1成功
			$ns_meet_user_arr['id'] = $ns_user_id;
			$ns_user_siteId = arr_unbound_value($user_v,'siteId',2,0);//站点id
			$ns_meet_user_arr['siteId'] = $ns_user_siteId;
			$ns_user_billingCode = arr_unbound_value($user_v,'billingCode',2,'');
			$ns_meet_user_arr['billingCode'] = $ns_user_billingCode;
			$ns_user_accountId = arr_unbound_value($user_v,'accountId',2,'');
			$ns_user_hostPassword = arr_unbound_value($user_v['resource'],'hostPassword',2,'');
			$ns_meet_user_arr['hostPassword'] = $ns_user_hostPassword;
			$ns_user_guestPassword = arr_unbound_value($user_v['resource'],'guestPassword',2,'');
			$ns_meet_user_arr['guestPassword'] = $ns_user_guestPassword;
			$ns_user_siteURL = arr_unbound_value($user_v['resource'],'siteURL',2,'');
			$ns_meet_user_arr['siteURL'] = $ns_user_siteURL;
			//判断用户是否还有components标签
			$ns_user_components_arr = arr_unbound_value($user_v,'components',1,array());
			$ns_user_has_prop = 0;//标记用户是否有自己的权限0没有1有，有则需要单独保存

			$ns_user_collect = $user_collect ;//summit Collect值
			//如果用户有自己的权限
			if(!isemptyArray($ns_user_components_arr)){
				//揉合权限[原则：用户有的不变，用户没有的，但是站点有的，则加进来]
				$ns_components_arr = $components_arr;
				//array_merge只能对当前一级有作用，所以下一级需要深入一下，如果键名有重复，该键的键值为最后一个键名对应的值（后面的覆盖前面的）。如果数组是数字索引的，则键名会以连续方式重新索引。
				$ns_u_com_i = 0;
				foreach($ns_user_components_arr as $u_k => $u_v){
					//覆盖属性
					$ns_user_components_arr[$u_k]['property'] =array_merge($ns_components_arr[$u_k]['property'],$ns_user_components_arr[$u_k]['property']);
					$ns_user_components_arr[$u_k] = array_merge($ns_components_arr[$u_k],$ns_user_components_arr[$u_k]);
					$ns_name = isset($u_v['name'])?$u_v['name']:'';
					$ns_name_lower = strtolower($ns_name);//转换为小写
					switch ($ns_name_lower) {
						case 'summit'://summit属性
							$ns_user_collect = arr_unbound_value($u_v['property'],'Collect',2,$user_collect);//summit Collect值
							break;
					}
					$ns_u_com_i += 1;
				}
				if($ns_u_com_i >= 3){//至少有3个，则说明有自己的属性
					$ns_user_has_prop = 1;//用户是否有自己的权限0没有1有
				}else{//用站点的属性
					foreach($ns_user_components_arr as $u_k => $u_v){
						//覆盖属性
						$ns_components_arr[$u_k]['property'] =array_merge($ns_components_arr[$u_k]['property'],$ns_user_components_arr[$u_k]['property']);
						$ns_components_arr[$u_k] = array_merge($ns_components_arr[$u_k],$ns_user_components_arr[$u_k]);
					}
					$ns_user_components_arr = $ns_components_arr;
				}
			}else{
				$ns_user_components_arr = $components_arr;
			}
			//获得用户所在的组织id串
			$ns_user_org_arr = $CI->StaffLib->get_user_org_arr($ns_user_id,1);

			$ns_user_org_code = arr_unbound_value($ns_user_org_arr,'nodeCode',2,'');
			log_message('debug', 'userid= ' . $ns_user_id . ' $ns_user_org_code= ' . $ns_user_org_code . '  .');
			//为了使用户有uc的相关属性 ;获得用户权限：用户没有从组织串；组织串没有从站点
			$power_in_arr = array(
                    'userid' => $ns_user_id,//用户id
                    'org_code' => $ns_user_org_code,//组织id串  -500-501-502-503
                    'siteid' => $ns_user_siteId//站点id
			);
			log_message('debug', 'ok $power_in_arr = ' . json_encode($power_in_arr). '.');
			$ns_uc_components_arr = $CI->PowerLib->get_components($power_in_arr);
			$ns_uc_power_components_arr = array();
			if(!isemptyArray($ns_uc_components_arr)){//如果不是空数组
				$from_num = isset($ns_uc_components_arr['from_num'])?$ns_uc_components_arr['from_num']:0;//从哪里获得的 1用户2组织3站点
				$ns_uc_power_components_arr = isset($ns_uc_components_arr['components'])?$ns_uc_components_arr['components']:array();
			}
			log_message('debug', 'ok $ns_uc_power_components_arr = ' . json_encode($ns_uc_power_components_arr). '.');

			if(!isemptyArray($ns_uc_power_components_arr)){
				foreach($ns_uc_power_components_arr as $ns_uc_k => $ns_uc_v){
					$ns_uc_name = arr_unbound_value($ns_uc_v,'name',2,'');
					if( strtolower($ns_uc_name) == 'uc'){
						foreach($ns_user_components_arr as $ns_u_k => $ns_u_v){
							$ns_u_name = arr_unbound_value($ns_u_v,'name',2,'');
							if(strtolower($ns_u_name)== 'uc'){//更新用户权限
								//print_r($ns_uc_v['property']);
								$ns_user_components_arr[$ns_u_k]['property'] = array_merge($ns_uc_power_components_arr[$ns_uc_k]['property'],$ns_user_components_arr[$ns_u_k]['property']);
								$ns_user_components_arr[$ns_u_k] = array_merge($ns_uc_power_components_arr[$ns_uc_k],$ns_user_components_arr[$ns_u_k]);
								break;
							}
						}
						break;
					}
				}
			}
			log_message('debug', 'ok $ns_user_components_arr = ' . json_encode($ns_user_components_arr). '.');
			$user_v['components'] = $ns_user_components_arr;
			$ns_meet_user_arr['components'] = $ns_user_components_arr;
			$meet_user_arr[] = $ns_meet_user_arr;

			if($open_type != 2){// 2修改账号 [只是修改权限]
				//同事关系
				if($open_type == 1 || $open_type == 5 ){//1新建账号 同事关系创建  5删卡
					//判断当前用户是否是管理者
					$CI->load->model('uc_org_manager_model');
					$ns_user_is_manager = $CI->uc_org_manager_model->userid_is_org_manager($ns_user_id,$site_orgID,$ns_user_siteId);
					$is_admin = 0;
					if($ns_user_is_manager){
						$is_admin = 1;
					}
					//获得当前用户的组织信息
					//$org_arr = $CI-> OrganizeLib->get_org_by_id($site_orgID);
					$org_arr = $CI-> StaffLib->get_user_org_arr($ns_user_id,1);
					$ns_user_org_id = arr_unbound_value($org_arr,'id',2,'');//当前用户组织id
					$ns_user_org_pid = arr_unbound_value($org_arr,'parentId',2,'');//父组织id
					log_message('debug', '  $ns_user_org_pid=' . $ns_user_org_pid . ' .');
					if($open_type == 1){//同事关系创建
						//if($is_first_manager == 0){//是否第一次加管理员0不是1是
						$data = 'user_id=' . $ns_user_id . '&org_id=' . $ns_user_org_id . '&parent_id=' . $ns_user_org_pid . '&is_admin=' . $is_admin  ;
						$api_arr = $CI->API->UCCServerAPI($data,9);
						if(api_operate_fail($api_arr)){//失败
							log_message('error', 'uccapi async/createColleague fail.');

						}else{
							log_message('debug', 'uccapi async/createColleague success.');
						}
						// }
					}else{
						//uccserver 接口 10.	同事关系删除
						$data = 'user_id=' . $ns_user_id . '&org_id=' . $ns_user_org_id . '&parent_id=' . $ns_user_org_pid . '&is_admin=' . $is_admin ;
						$api_arr = $CI->API->UCCServerAPI($data,10);
						if(api_operate_fail($api_arr)){//失败
							log_message('error', 'uccapi async/deleteColleague fail.');
						}else{
							log_message('debug', 'uccapi async/deleteColleague success.');
						}
					}

				}
				$ums_user_status = 82;//开启用户产品
				if($open_type == 3 || $open_type == 5){//1新建账号 2修改账号 3停用账号 4 启用账号 5删卡
					$ums_user_status = 0;//关闭用户产品
				}
				//调用UMS创建用户产品接口
				$data_ums ='productId=' . UC_PRODUCT_ID . '&userStatus=' . $ums_user_status . '&sitesId=' . $ns_user_siteId . '&userId=' . $ns_user_id . '';
				$ums_arr = $CI->API->UMS_Special_API('',4,array('url'=>$data_ums ));
				if(api_operate_fail($ums_arr)){//失败
					$err_msg = 'ums api /rs/organizations fail.';
					log_message('error', $err_msg);
					//TODO 最后打开 return 0;
				}else{
					log_message('debug', 'ums api /rs/organizations success.');
				}
				//保存管理员信息
				if(!isemptyArray($uc_manager_arr)){//不为空
					if(deep_in_array($ns_user_id, $uc_manager_arr)){//是新建的管理员$uc_auth == 1 0普通用户开通1管理员开通;只是管理员回调
						if($open_type == 1){//1新建账号
							$uc_admin_state =1;//0：停用；1：启用'
							//if($open_type == 3 || $open_type == 5){//1新建账号 2修改账号 3停用账号 4 启用账号 5删卡
							// $uc_admin_state = 0;//0：停用；1：启用'
							//}
							//判断管理员表记录是否存在
							$role_id = 1 ;
							// 1、有记录则更新记录，没记录则新加
							$select_field = 'userID';
							$where_arr = array(
                                'siteID' => $siteId,
                                'userID' => $ns_user_id,
                                'orgID' =>$site_orgID,//企业ID
							//'role_id' => $role_id
							);
							$modify_arr = array(
                             'userID' =>$ns_user_id,//该客户的站点ID
                             'role_id' =>$role_id,//角色ID，外键' 
                             'siteID' =>$siteId,//
                             'orgID' =>$site_orgID,//企业ID
                             'isLDAP' => $isLDAP,//0：否（批量导入）；1：是（LDAP导入）；2：全部都可以',
                             'billingcode' =>$ns_user_billingCode,//用户记费码
                             'hostpasscode' =>$ns_user_hostPassword,//主持人密码
                             'guestpasscode' =>$ns_user_guestPassword,//参会人密码
                             'accountId' =>$ns_user_accountId,//分帐id
                             'departmentID' => $site_orgID,//$departmentID,//默认为0，表示没有部门。' ,
                             'type' =>$admin_type,//1：总公司管理员；2：分公司管理员；3：生态企业管理员；0：其它' ,
                             'state' => $uc_admin_state,//0：停用；1：启用' 
							);
							$insert_arr = $modify_arr;
							$insert_arr['createTime'] = dgmdate(time(), 'dt');
							$re_num = $CI-> UC_User_Admin_Model -> updata_or_insert(1,$select_field,$where_arr,$modify_arr,$insert_arr);
							if($re_num == -2 || $re_num == -4){//失败 -1 更新记录成功 -2更新操作失败 -3新加的记录id 新加记录成功 -4新加操作失败 -5 没有操作
								$err_msg = 'update/insert  UC_User_Admin fail. $re_num =' . $re_num . ' ';
								log_message('error', $err_msg);
							}
							log_message('info', 'update/insert  UC_User_Admin success. $re_num =' . $re_num . ' .');
							//如果是第一批管理员变量管理员的组织id
							/*
							 if($is_first_manager == 1){//是否第一次加管理员0不是1是
							 //变更用户组织
							 $data_arr =array(
							 "id" => $ns_user_id,
							 "from" => null,
							 "to" => $site_orgID
							 );
							 //调用ums更改用户所在组织
							 $change_org_arr = $CI->API->UMS_Special_API(json_encode($data_arr),13);
							 if(api_operate_fail($change_org_arr)){//失败
							 $err_msg = ' usm api rs/organizations/change_organization fail .';
							 log_message('error', $err_msg);
							 }else{
							 //$change_org_data = arr_unbound_value($change_org_arr,'data',1,array());
							 $err_msg = ' usm api rs/organizations/change_organization success .';
							 log_message('debug', $err_msg);
							 }
							 }
							 *
							 */

						}
						if($open_type == 3 || $open_type == 5){//1新建账号 2修改账号 3停用账号 4 启用账号 5删卡
							//会议禁用帐号接口
							/* $Meet_arr = $CI->API->MeetAPI($ns_user_id,2);
							 if(api_operate_fail($Meet_arr)){//失败
							 $err_msg = 'MeetAPI rs/web/disableUser/3/' . $ns_user_id . ' fail.';
							 $this->boss_err(-1,$err_msg);
							 $ns_is_success = 0;//是否操作成功0不成功1成功
							 }else{
							 log_message('info', 'MeetAPI rs/web/disableUser/3/' . $ns_user_id . ' success.');
							 }
							 *
							 */
							$meet_type = 3;//1 离职 2 调岗 3 禁用
							if($open_type == 5){//5删卡
								$meet_type = 1;//1 离职 2 调岗 3 禁用
							}
							$meet_array = array(
                            'appId' => 2,
                            'userIds' => $ns_user_id,//id，多个用;号分隔
                            'type' => $meet_type 
							);
							$meet_data = json_encode($meet_array);//'appId=2&userId=' . $ns_user_id . '&type=' . $meet_type;
							$Meet_arr = $CI->API->MeetAPI($meet_data,3);
							if(api_operate_fail($Meet_arr)){//失败
								$err_msg = 'MeetAPI rs/conference/accountChange ' . $meet_data . json_encode($Meet_arr) . ' fail.';
								$this->boss_err(-1,$err_msg);
								$ns_is_success = 0;//是否操作成功0不成功1成功
							}else{
								log_message('info', 'MeetAPI rs/conference/accountChange ' . $meet_data . json_encode($Meet_arr) . ' success.');
							}
						}
					}
				}
			}
			$is_reset_pwd = 0;//是否重置密码[新建账号时需要重置密码]
			if($open_type == 1){//1新建账号 2修改账号 3停用账号 4 启用账号 5删卡
				$is_reset_pwd = 1;//是否重置密码
			}
			//用户信息加入uc_user表
			//判断用户表记录是否存在
			// 1、有记录则更新记录，没记录则新加
			$uc_user_status = 1;//（0：未启用（一直未开通过）；1：已开通；2：禁用/删除（开通过））
			if($open_type == 3 || $open_type == 5){//1新建账号 2修改账号 3停用账号 4 启用账号 5删卡
				$uc_user_status = 2;//关闭用户产品
			}
			$select_field = 'userID';
			$where_arr = array(
			//'siteId' => $siteId,
                    'userID' => $ns_user_id     
			);
			$modify_arr = array(
                 'userID' =>$ns_user_id,//该客户的站点ID
                 'siteId' =>$siteId,//
                 'Collect' =>$ns_user_collect,//summit Collect值
                 'billingcode' =>$ns_user_billingCode,//用户记费码
                 'hostpasscode' =>$ns_user_hostPassword,//主持人密码
                 'guestpasscode' =>$ns_user_guestPassword,//参会人密码
                 'accountId' =>$ns_user_accountId,//分帐id
                 'status' => $uc_user_status,//状态（0：删除；1：正常用户）
                 'isResetPwd' => $is_reset_pwd,//是否重置密码
			);
			$insert_arr = $modify_arr;
			$insert_arr['create_time'] = dgmdate(time(), 'dt');
			$modify_arr['update_time'] = dgmdate(time(), 'dt');
			$re_num = $CI-> UC_User_Model -> updata_or_insert(1,$select_field,$where_arr,$modify_arr,$insert_arr);
			if($re_num == -2 || $re_num == -4){//失败 -1 更新记录成功 -2更新操作失败 -3新加的记录id 新加记录成功 -4新加操作失败 -5 没有操作
				$err_msg = 'update/insert  UC_User_Model fail. $re_num =' . $re_num . ' ';
				log_message('error', $err_msg);
			}
			log_message('info', 'update/insert  UC_User_Model success. $re_num =' . $re_num . ' .');
			$user_id_arr[] = $ns_user_id;

			//保存用户属性
			if($ns_user_has_prop == 1){//用户是否有自己的权限0没有1有
				if(!isemptyArray($ns_user_components_arr)){
					$comp_in_arr = array(
                       'type' => 3,//类型1站点权限2部门权限3用户权限
                       'id' => $ns_user_id,//保存对应的id 站点是site_id; 部门是部门串-500-501-502-503;用户是用户user_id
                       'site_id' => $ns_user_siteId,//站点id;3用户权限时可以没有值
                       'customerCode' => $customerCode,//客户编码;2部门权限3用户权限时可以没有值
					);
					$re_boolean = $CI->PowerLib->save_components($comp_in_arr,$ns_user_components_arr);//保存用户属性
					if($re_boolean){//成功
						log_message('info', ' save_components type =3 $ns_user_id =' . $ns_user_id . '$siteID =' . $ns_user_siteId . ' $customerCode =' . $customerCode . ' success.');
					}else{
						log_message('error', ' save_components type =3 $ns_user_id =' . $ns_user_id . '$siteID =' . $ns_user_siteId . '  $customerCode =' . $customerCode . 'fail.');
					}
				}
			}
			$ns_failed_arr = array(
              'id' =>  $ns_user_id, //userid
              'billingCode' => $ns_user_billingCode, 
              'errorCode' => "0", //自己处理的流程步骤
              'error' => '', //处理中捕获的异常信息
              'accountId' => $ns_user_accountId
			);

			//向BOSS回调成功的user信息
			$ns_user_callback_arr =array(
                'id' => $ns_user_id,
                'billingCode' => $ns_user_billingCode,
                'accountId' => $ns_user_accountId,
			);
			if($ns_is_success == 1){//是否操作成功0不成功1成功)
				$callback_user_successed_arr[] = $ns_user_callback_arr;
			}else{//失败
				$all_user_failed_arr[] = $ns_failed_arr;
			}

			//新建时重置密码
			if($is_reset_pwd == 1){//是否重置密码
				//TODO 修改密码
				$password = '111111';//rand_str(array('length' => 8,'type' => '1,3,4,5'));
				$data_ums = $ns_user_id . '/password'  ;
				$ums_arr = $CI->API->UMS_Special_API($password,22,array('url'=>$data_ums ));
				//print_r($ums_arr);
				//exit;
				if(api_operate_fail($ums_arr)){//失败
					$err_msg = 'ums api /rs/users/id/1/password fail.';
					log_message('error', $err_msg);
					//TODO 最后打开 return 0;

				}else{
					log_message('debug', 'ums api /rs/users/id/1/password success.');
				}
				$user_manager_type = 3;
				if($user_is_manager == 1){//用户是否为管理员
					$user_manager_type = 2;
				}
				$ns_mss_user_arr = array(
                   'id' => $ns_user_id,//用户id
                   'site_name' => $site_name,//站点名称
                   'user_pwd' => $password,//用户登陆密码  
                   'type' => $user_manager_type ,//账号类型1管理员帐号开通(试用版) 2管理员帐号开通(正式版)3一般用户帐号开通(正式版)

				);
				$mss_user_arr []= $ns_mss_user_arr;

				if(!bn_is_empty($mss_user_ids)){//不为空，则加,号
					$mss_user_ids .= ',';
				}
				$mss_user_ids .= $ns_user_id;
			}

		}

		if(!bn_is_empty($mss_user_ids)){//不为空，则加[]号
			$mss_user_ids = '[' . $mss_user_ids . ']';
		}
		if($open_type == 1 || $open_type == 2 || $open_type == 4){//1新建账号 2修改账号 3停用账号 4 启用账号 5删卡
			//将数组转换为xml
			if(!isemptyArray($meet_user_arr)){//如果不是空数组
				//$CI->load->library('AccountLib','','AccountLib');
				$Meet_arr =$CI->PowerLib->get_meet_part($meet_user_arr,array('customerCode' =>$customerCode,'site_name'=>$site_name));
				//$hy_xml_data = $CI->PowerLib->get_meet_xml($meet_user_arr,array('customerCode' =>$customerCode,'site_name'=>$site_name));
				//write_test_file( ' hy_xml_data ' . __FUNCTION__ . time() . '$hy_xml_data.txt' ,$hy_xml_data);
				//分发账户数据（增加、修改，启用都用此接口 批量）
				// $Meet_arr = $CI->API->MeetAPI($hy_xml_data,1);
				//if(api_operate_fail($Meet_arr)){//失败
				if($Meet_arr == false){//失败
					$err_msg = 'MeetAPI common acceptData fail.';
					$this->boss_err(-1,$err_msg);
					$boss_err_arr = array(
                   'users_arr' => $users_arr,//$users_arr,//所有用户
                   'successed_arr' => array(),//$callback_user_successed_arr,//$callback_user_successed_arr,//成功用户
                   'failed_arr' => $all_user_failed_arr,//$all_user_failed_arr,//失败用户
                   'errorCode' => '21',//当前错误:自己处理的流程步骤
                   'error_msg' => $err_msg,//当前错误:捕获的异常信息
                   'calltype' => $open_class,//回调类型
                   'requestId' => $requestId,//回调id
                   'result' => -1,//1标示开通成功  -1标示开通失败开通合同时
                   'contractId' => $contract_id,//合同id
                   'callback' => $callback,//回调地址  
					);
					return $this->boss_err_callback($boss_err_arr);//错误回调
					//return 0;
				}else{//成功或部分失败
					write_test_file( ' hy_xml_data_msg ' . __FUNCTION__ . time() . '$hy_xml_data.txt' ,$Meet_arr['msg']);
					log_message('debug', 'MeetAPI common acceptData success.');
					$ns_json_data = isset($Meet_arr['msg'])?$Meet_arr['msg']:'' ;
					if(!bn_is_empty($ns_json_data)){//不为空
						$ns_data_arr = json_decode($ns_json_data , TRUE );
						if(!isemptyArray($ns_data_arr)){//不是空数组
							$ns_value = isset($ns_data_arr[0])?$ns_data_arr[0]:'' ;
							if(!bn_is_empty($ns_value)){//不为空
								$ns_userid_arr = explode(",",$ns_value );
								foreach($ns_userid_arr as  $ns_k => $ns_v){
									$ns_user_id = $ns_v;
									if(!bn_is_empty($ns_user_id)){//不为空
										if($ns_user_id > 0 ){
											//从成功里干掉
											foreach($callback_user_successed_arr as $ns_s_k => $ns_s_v){
												$ns_s_id = isset($ns_s_v['id'])?$ns_s_v['id']:0 ;
												if($ns_user_id == $ns_s_id){//相等,则干掉
													unset($callback_user_successed_arr[$ns_s_k]);
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}

		}

		if($is_first_manager == 1){//第一次加管理员$uc_auth == 1 && $admin_type == 10普通用户开通1管理员开通;只是管理员会调
			if($open_type == 1){//1新建账号
				//数据库分配接口
				$data = 'customer_code=' . $customerCode .'&amount=' . $site_user_count ;
				$ucc_server_arr = $CI->API->UCCServerAPI($data,7);
				if(api_operate_fail($ucc_server_arr)){//失败
					//TODO 以后看有没有必要直接返回return 0;
					$ns_code = isset($ucc_server_arr['code'])?$ucc_server_arr['code']:'';
					if($ns_code != 10602 ){//have already allocation on mq
						//echo api_json_msg(-1,array('msg' => $err_msg) , 1);
						$err_msg = 'uccapi async/dbDispath fail, request data is->'.var_export($data, true);
						log_message('error', $err_msg);
						$this->boss_err(-1,$err_msg);
						$boss_err_arr = array(
                            'users_arr' => $users_arr,//$users_arr,//所有用户
                            'successed_arr' => array(),//$callback_user_successed_arr,//$callback_user_successed_arr,//成功用户
                            'failed_arr' => $all_user_failed_arr,//$all_user_failed_arr,//失败用户
                            'errorCode' => '22',//当前错误:自己处理的流程步骤
                            'error_msg' => $err_msg,//当前错误:捕获的异常信息
                            'calltype' => $open_class,//回调类型
                            'requestId' => $requestId,//回调id
                            'result' => -1,//1标示开通成功  -1标示开通失败开通合同时
                            'contractId' => $contract_id,//合同id
                            'callback' => $callback,//回调地址  
						);
						return $this->boss_err_callback($boss_err_arr);//错误回调
						//return 0;
					}
					$err_msg = 'uccapi async/dbDispath fail this have already allocation .';
					log_message('debug', $err_msg);

				}else{
					log_message('debug', 'uccapi async/dbDispath success.');
				}

				//UCC MQ集群分配接口
				//$data = 'session_id=&user_id=' . $userID . '&site_id=' . $siteId . '& amount=' . $site_user_count ;
				$data = 'site_id=' . $siteId . '&amount=' . $site_user_count ;
				$mqDis_arr = $CI->API->UCCServerAPI($data,6);
				//print_r($mqDis_arr);
				//exit;
				if(api_operate_fail($mqDis_arr)){//失败
					//TODO 以后看有没有必要直接返回return 0;
					$ns_code = isset($mqDis_arr['code'])?$mqDis_arr['code']:'';
					if($ns_code != 10602 ){//have already allocation on mq
						//echo api_json_msg(-1,array('msg' => $err_msg) , 1);
						$err_msg = 'uccapi async mqDispath fail.';
						log_message('error', $err_msg);
						$this->boss_err(-1,$err_msg);
						$boss_err_arr = array(
                            'users_arr' => $users_arr,//$users_arr,//所有用户
                            'successed_arr' => array(),//$callback_user_successed_arr,//$callback_user_successed_arr,//成功用户
                            'failed_arr' => $all_user_failed_arr,//$all_user_failed_arr,//失败用户
                            'errorCode' => '221',//当前错误:自己处理的流程步骤
                            'error_msg' => $err_msg,//当前错误:捕获的异常信息
                            'calltype' => $open_class,//回调类型
                            'requestId' => $requestId,//回调id
                            'result' => -1,//1标示开通成功  -1标示开通失败开通合同时
                            'contractId' => $contract_id,//合同id
                            'callback' => $callback,//回调地址  
						);
						return $this->boss_err_callback($boss_err_arr);//错误回调
					}
					$err_msg = 'uccapi async mqDispath fail. have already allocation';
					log_message('debug', $err_msg);
				}else{
					log_message('debug', 'uccapi async mqDispath success.');
				}
				//调用UCCSSERVER聊天和状态交换机创建接口
				$data = 'site_id=' . $siteId  ;
				$ucc_server_arr = $CI->API->UCCServerAPI($data,8);
				if(api_operate_fail($ucc_server_arr)){//失败
					$err_msg = 'uccapi async siteCreate fail.';
					$this->boss_err(-1,$err_msg);
					$boss_err_arr = array(
                       'users_arr' => $users_arr,//$users_arr,//所有用户
                       'successed_arr' => array(),//$callback_user_successed_arr,//$callback_user_successed_arr,//成功用户
                       'failed_arr' => $all_user_failed_arr,//$all_user_failed_arr,//失败用户
                       'errorCode' => '22',//当前错误:自己处理的流程步骤
                       'error_msg' => $err_msg,//当前错误:捕获的异常信息
                       'calltype' => $open_class,//回调类型
                       'requestId' => $requestId,//回调id
                       'result' => -1,//1标示开通成功  -1标示开通失败开通合同时
                       'contractId' => $contract_id,//合同id
                       'callback' => $callback,//回调地址  
					);
					return $this->boss_err_callback($boss_err_arr);//错误回调
					//return 0;

				}else{
					log_message('debug', 'uccapi async siteCreate success.');
				}
			}
		}

		//TODO 发送开通通知
		log_message('debug', 'send email $mss_user_arr=' . json_encode($mss_user_arr) . '.');
		if(!isemptyArray($mss_user_arr)){//不是空数组
			$mss_type = 0;
			switch ($open_type) {//开通类型1合同回调2管理员回调3批量开通回调 1新建账号 2修改账号 3停用账号 4 启用账号
				case 1:    //1合同回调
					$mss_type = 0;
					break;
				case 2:    //2管理员回调
					$mss_type = 2;
					break;
				case 3:    //3批量开通回调
					$mss_type = 3;
					break;
				default:
					break;
			}
			$CI->load->library('EmailLib','','EmailLib');

			$mss_in_arr = array(
                'mssuser_arr' => $mss_user_arr,
                'mss_user_ids' => $mss_user_ids, 
                'domain_url' => $web_cluster_url,//站点所在域的url 如:devcloud.quanshi.com
                'siteURL' => $siteURL,//站点url                
                'customer_code' => $customerCode,//客户编码
			);
			$mss_fail_arr = true;//$CI->EmailLib->send_user_email($mss_in_arr);
			/*
			 $mss_in_arr = array(
			 'mssuser_arr' => $mss_user_arr,
			 'mss_user_ids' => $mss_user_ids,
			 'type' => 1,//类型1管理员帐号开通(试用版)2管理员帐号开通(正式版)3一般用户帐号开通(正式版)
			 'domain_url' => $web_cluster_url,//站点所在域的url 如:devcloud.quanshi.com
			 'siteURL' => $siteURL,//站点url
			 );
			 $CI->load->library('MssLib','','MssLib');
			 $mss_fail_arr = $CI->MssLib->send_user_email($mss_in_arr);
			 *
			 */

			$mss_fail_user_arr = array();//发送邮件失败的数组
			//false失败 ;TRUE 成功[都成功] ;部分失败，返回失败数组
			if(is_array($mss_fail_arr)){//是数组
				foreach($mss_fail_arr as $ns_f_k => $ns_f_v){
					$mss_fail_user_arr[]= $ns_f_v;//返回失败数组
				}
			}else{
				if(!$fail_arr){//都失败
					$mss_fail_user_arr = $mss_user_arr;
				}
			}
			log_message('debug', 'send email  $mss_fail_user_arr=' . json_encode($mss_fail_user_arr) . '.');

		}
		//TODO 写UC后台消息
		//参数json,回调BOSS接口平台开通响应接收接口
		$boss_err_arr = array(
           'users_arr' => $users_arr,//$users_arr,//所有用户
           'successed_arr' => $callback_user_successed_arr,//$callback_user_successed_arr,//成功用户
           'failed_arr' => $all_user_failed_arr,//$all_user_failed_arr,//失败用户
           'errorCode' => '23',//当前错误:自己处理的流程步骤
           'error_msg' => '',//当前错误:捕获的异常信息
           'calltype' => $open_class,//回调类型
           'requestId' => $requestId,//回调id
           'result' => 1,//1标示开通成功  -1标示开通失败开通合同时
           'contractId' => $contract_id,//合同id
           'callback' => $callback,//回调地址  
		);
		return $this->boss_err_callback($boss_err_arr);//错误回调
	}
	/**
	 * @brief 合同开通失败
	 * @details
	 * @param array $in_data_arr=array(
	 *  'calltype' => //回调类型号1合同回调2管理员回调
	 *  'user_successed_arr' => 2管理员回调时，成功的用户
	 *  'user_failed_arr' => array(
	 "id": 1, //userid
	 "billingCode": "61148010",
	 "errorCode": "1", //自己处理的流程步骤
	 "error": "调用ACM接口失败！acm handle error :Lack Collect", //处理中捕获的异常信息
	 "accountId": 3848

	 * )
	 *  'requestId' => ,//requestid
	 *  'result' => ,//1标示开通成功  -1标示开通失败开通合同时  result  必须要有（1标示开通成功      -1标示开通失败）
	 *  'contractId' => ,//合同号
	 *  'callback' =>,//回调地址
	 * )
	 * @return int 0其它参数错误 1 回调成功 2回调失败。
	 */
	public function contract_fail($in_data_arr){
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->helper('my_dgmdate');
		$CI->load->library('API','','API');
		$calltype = isset($in_data_arr['calltype'])?$in_data_arr['calltype']:1;//1合同回调2管理员/用户回调
		$user_successed_arr = isset($in_data_arr['user_successed_arr'])?$in_data_arr['user_successed_arr']:array();
		$user_failed_arr = isset($in_data_arr['user_failed_arr'])?$in_data_arr['user_failed_arr']:array();//失败用户列表
		$requestId = isset($in_data_arr['requestId'])?$in_data_arr['requestId']:0;
		$result = isset($in_data_arr['result'])?$in_data_arr['result']:-1;
		$contractId = isset($in_data_arr['contractId'])?$in_data_arr['contractId']:0;
		$callback = isset($in_data_arr['callback'])?$in_data_arr['callback']:'';

		//重新整理ok数组，使其下标从0开始，中间没有断层,因为前面unset时，打乱了，所以转为json时，会出现下标情况'0':{}
		$ns_ok_arr = array();
		foreach($user_successed_arr as $n_k => $n_v){
			$ns_ok_arr[] = $n_v;
		}
		$ns_fail_arr = array();
		foreach($user_failed_arr as $n_k => $n_v){
			$ns_fail_arr[] = $n_v;
		}
		if($calltype == 1){//1合同回调2管理员/用户回调
			$data_arr = array(
                'requestId' => $requestId,            
                'finishedTime'=> time(),
                'result' => $result,//1标示开通成功      -1标示开通失败开通合同时  result  必须要有（1标示开通成功      -1标示开通失败）
                'contractId' => $contractId,//合同号
                'componentName' => UC_PRODUCT_CODE
			);
		}else{
			//参数josn,回调BOSS接口平台开通响应接收接口
			$data_arr = array(
               'requestId' => $requestId,
               'finishedTime'=> time(),
               'successed' => $ns_ok_arr,//$user_successed_arr,
               'failed' => $ns_fail_arr,
			//'result' => $result,//可以不给开通账号时  result值可以不给  我是通过里边的User列表来取值的
               'contractId' => $contractId,//合同号
               'componentName' => UC_PRODUCT_CODE
			);
		}
		$call_back_arr = $CI->API->BOSS_Special_API(json_encode($data_arr),1,array('url' => $callback));

		if($result == -1 || $result == '-1'){//-1标示开通失败开通合同时,0其它参数错误
			return 0;
		}
		if(!api_operate_fail($call_back_arr)){//成功
			$err_msg = ' call back ' . $callback . ' success.';
			log_message('debug', $err_msg);
			//echo api_json_msg(1,array('msg' => $err_msg) , 1);
			return 1;
		}else{//回调失败
			$err_msg = ' call back ' . $callback . '  fail.';
			log_message('error', $err_msg);
			//echo api_json_msg(-1,array('msg' => $err_msg) , 1);
			return 2;
		}
	}

	/**
	 *
	 * @brief 当有错误时，重整错误数据，并回调BOSS：
	 * @details
	 * @param array $in_arr  参数数组
	 $in_arr = array(
	 'users_arr' => aaa,//$users_arr,//所有用户
	 'successed_arr' => aaa,//$callback_user_successed_arr,//成功用户
	 'failed_arr' => aaa,//$all_user_failed_arr,//失败用户
	 'errorCode' => aaa,//当前错误:自己处理的流程步骤
	 'error_msg' => aaa,//当前错误:捕获的异常信息
	 'calltype' => aaa,//回调类型
	 'requestId' => aaa,//回调id
	 'result' => ,//1标示开通成功  -1标示开通失败开通合同时
	 'contractId' => aaa,//合同id
	 'callback' => aaa,//回调地址
	 );
	 * @return null
	 *
	 */
	public function boss_err_callback($in_arr = array()){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->helper('my_dgmdate');
		$CI->load->library('API','','API');
		$users_arr = arr_unbound_value($in_arr,'users_arr',2,array());//$users_arr,//所有用户
		$successed_arr = arr_unbound_value($in_arr,'successed_arr',2,array());//$callback_user_successed_arr,//成功用户
		$failed_arr = arr_unbound_value($in_arr,'failed_arr',2,array());//$all_user_failed_arr,//失败用户
		$errorCode = arr_unbound_value($in_arr,'errorCode',2,0);//当前错误:自己处理的流程步骤
		$error_msg = arr_unbound_value($in_arr,'error_msg',2,'error');//当前错误:捕获的异常信息
		$calltype = arr_unbound_value($in_arr,'calltype',2,0);//回调类型
		$requestId = arr_unbound_value($in_arr,'requestId',2,0);//回调id
		$result = arr_unbound_value($in_arr,'result',2,-1);//1标示开通成功  -1标示开通失败开通合同时
		$contractId = arr_unbound_value($in_arr,'contractId',2,'');//合同id
		$callback = arr_unbound_value($in_arr,'callback',2,'');//回调地址
		//因为错误时,有可能所有用户中还有没有加成功的,也没有加入失败的,所以在此重新整理失败用户
		$ns_fail_data_arr = array(
              'all_arr' => $users_arr,//所有用户
              'successed_arr' => $successed_arr,//$callback_user_successed_arr,//成功用户
              'failed_arr' => $failed_arr,//$all_user_failed_arr,//失败用户
              'errorCode' => $errorCode,//'1',//当前错误:自己处理的流程步骤
              'error' => $error_msg,//$err_msg,//当前错误:捕获的异常信息
		);
		$ns_fail_arr = $this->get_fail_arr($ns_fail_data_arr);
		$in_arr = array(
                'calltype' => $calltype,//$open_type,
                'requestId' => $requestId,//$requestId,
                'user_successed_arr' => $successed_arr,//$callback_user_successed_arr,
                'user_failed_arr' => $ns_fail_arr,
                'result' => $result,//'-1',
                'contractId' => $contractId,//$contract_id,
                'callback' => $callback//$callback
		);
		return $this->contract_fail($in_arr);
	}
	/**
	 * @brief 根据所有用户数组、成功数组、失败数组，返回全新的失败数组
	 * @details
	 * @param array $in_data_arr=array(
	 *  'all_arr' => 2管理员回调时，成功的用户
	 *              array(
	 *                 'id =>  1, //userid
	 *                 'billingCode' =>
	 *                 'accountId' =>
	 *    )
	 *  'successed_arr' => 2管理员回调时，成功的用户
	 *              array(
	 *                 'id =>  1, //userid
	 *    )
	 *  'failed_arr' => array(
	 'id =>  1, //userid
	 'billingCode' => "61148010",
	 'errorCode' => "1", //自己处理的流程步骤
	 'error' => "调用ACM接口失败！acm handle error :Lack Collect", //处理中捕获的异常信息
	 'accountId' => 3848

	 *   )
	 *  'errorCode' => //当前错误:自己处理的流程步骤
	 *  'error' =>//当前错误:捕获的异常信息
	 * )
	 * @return array  全新的失败数组
	 */
	public function get_fail_arr($in_data_arr = array()){
		$re_arr = array();
		if(isemptyArray($in_data_arr)){
			return $re_arr;
		}
		//所有用户
		$all_arr = isset($in_data_arr['all_arr'])?$in_data_arr['all_arr']:array();
		//成功用户
		$successed_arr = isset($in_data_arr['successed_arr'])?$in_data_arr['successed_arr']:array();
		//失败用户
		$failed_arr = isset($in_data_arr['failed_arr'])?$in_data_arr['failed_arr']:array();
		//如果所有用户为空，则返回空
		if(isemptyArray($all_arr)){
			return $re_arr;
		}

		$re_arr = $failed_arr;
		//当前错误:自己处理的流程步骤
		$errorCode = isset($in_data_arr['errorCode'])?$in_data_arr['errorCode']:'';
		//当前错误:捕获的异常信息
		$error = isset($in_data_arr['error'])?$in_data_arr['error']:'';
		//循环所有的
		foreach($all_arr as $a_k => $a_v){
			$a_id = isset($a_v['id'])?$a_v['id']:'';//用户id
			if(!bn_is_empty($a_id)){//有数据
				$a_billingCode = isset($a_v['billingCode'])?$a_v['billingCode']:'';//用户billingCode
				$a_accountId = isset($a_v['accountId'])?$a_v['accountId']:'';//用户accountId
				$s_is_in = 0;//是否在成功里,0没有1在
				//判断当前用户id是否在成功里面
				foreach($successed_arr as $s_k => $s_v){
					$s_id = isset($s_v['id'])?$s_v['id']:'';//用户id
					if($a_id == $s_id){
						$s_is_in = 1;//是否在成功里,0没有1在
						break;
					}
				}
				if($s_is_in == 0){//不在成功里面,则放入失败中
					//判断是否已经在失败里面
					foreach($re_arr as $f_k => $f_v){
						$f_id = isset($f_v['id'])?$f_v['id']:'';//用户id
						if($a_id == $f_id){
							$s_is_in = 1;//是否在成功里,0没有1在
							break;
						}
					}
					if($s_is_in == 0){//不在失败里面,则放入失败中
						$re_arr[] = array(
                             'id'=> $a_id, //userid
                             'billingCode'=> $a_billingCode, 
                             'errorCode'=> $errorCode, //自己处理的流程步骤
                             'error' => $error, //处理中捕获的异常信息
                             'accountId' => $a_accountId  
						);
					}
				}
			}
		}
		return $re_arr;
	}

	/**
	 *
	 * @brief 传入帐号信息，组织BOSS操作需要的JSON串,并保存uc_request表，调用boss接口
	 * @details
	 * @param array $user_ok_arr 需要修改的帐号数组 二维数组
	 $user_ok_arr = array(
	 'id' => $aaa,//当前用户id
	 'organizationId' => $aaa,//当前组织id
	 'orgNodeCode' => $aaa,//当前组织串-500-501-502-503
	 'obj' => array(
	 'sys' => array(
	 'customerCode' => $aaa,//客户编码
	 'siteID' => $aaa,//站点id
	 'site_name' => $aaa,//站点名称
	 'accountId'=>$aaa,//分帐id ；注意：如果有用户，则是用户自己的
	 'siteURL' => $aaa,//地址
	 'contractId' => $aaa,//合同id
	 ),
	 )

	 );
	 * @param int $operat_type//1新开通流程[全新开启] 2开启更新UPDATE流程[以前关闭，现在是开启] 3关闭流程[以前开启，现在半闭]
	 * 4删除流程[以前开启，现在删除]5删除流程[以前关闭，但开启过，现在删除] 6以前未开启过，现在开启，7 开启状态，修改
	 * @param array $power_arr [当权限变更时用]站点权限/用户权限，可以没有
	 $power_arr = array(//可以为空[权限变更时用]
	 'user_components' => $aa,//用户权限[用户/组织最新的权限]
	 'site_components' => $aa,//站点权限[站点最新的权限]
	 );
	 * @return int  返回0:失败；1：成功
	 */
	public function get_boss_json($user_ok_arr = array(),$operat_type = '',$power_arr = array())
	{

		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->helper('my_dgmdate');
		$CI->load->library('API','','API');
		if(isemptyArray($user_ok_arr) || bn_is_empty($operat_type)){//如果是空数组
			log_message('debug', '$user_ok_arr isemptyArray or $operat_type is empty.');
			return 1;
		}
		$CI->load->library('PowerLib','','PowerLib');
		$operate_text = '';
		switch ($operat_type) {
			case 1:  //1新开通流程[全新开启]
			case 6:  //6以前未开启过，现在开启
				$operate_text = 'create';//新建账号
				break;
			case 7: // 7 开启状态，修改
			case 8: // 8 用户权限修改
			case 9: // 9 部门权限修改
			case 10: // 10 站点权限修改
				$operate_text = 'update';//修改账号
				break;
			case 3:  //   3关闭流程[以前开启，现在半闭]
				$operate_text = 'disable';//停用账号
				break;
			case 2:  // 2开启更新UPDATE流程[以前关闭，现在是开启]
				$operate_text = 'enable';//启用账号
				break;
			case 4:  //  4删除流程[以前开启，现在删除]
			case 5: //5删除流程[以前关闭，但开启过，现在删除]
				$operate_text = 'delete';//删卡
				break;
			default:
				$operate_text = 'create';//新建账号
				break;
		}
		//boss开通用户信息,组织起来传给BOSS做批量开通用
		$bs_users_arr = array();
		//新的user数组信息[用于保存到uc_request表]
		$user_new_arr = array();
		$site_components_arr = array();//站点属性
		$all_user_components_arr = array();//用户属性
		if(!isemptyArray($power_arr)){//如果不是空数组
			$site_components_arr = arr_unbound_value($power_arr,'site_components',1,array());//站点属性
			$all_user_components_arr = arr_unbound_value($power_arr,'user_components',1,array());//用户属性
		}
		//根据当前站点id，去获得站点属性数组
		if(isemptyArray($site_components_arr)){//如果是空数组
			$in_arr = array(
                'userid' => 0,//用户id,站点属性时写0
                'org_code' => '',//组织id串
                'siteid' =>$siteID,//站点id
			);
			$ns_components_arr = $CI->PowerLib->get_components($in_arr);
			if(!isemptyArray($ns_components_arr)){//不是空数组
				$from_num = isset($ns_components_arr['from_num'])?$ns_components_arr['from_num']:0;//从哪里获得的 1用户2组织3站点
				$ns_site_components_arr = isset($ns_components_arr['components'])?$ns_components_arr['components']:array();
				if(!isemptyArray($ns_site_components_arr)){//如果不是空数组
					$site_components_arr = $ns_site_components_arr;
				}
			}
		}
		foreach ($user_ok_arr as $key => $value)
		{
			$user_new_arr[$key] = $value;//新的user数组信息
			//用户id
			$user_id = isset($value['id'])?$value['id']:0;
			$org_str = isset($value['organizationId'])?$value['organizationId']:0;
			$org_code = isset($value['orgNodeCode'])?$value['orgNodeCode']:'';//-500-501-502-503
			$obj_arr = isset($value['obj']['sys'])?$value['obj']['sys']:array();
			$customerCode = isset($obj_arr['customerCode'])?$obj_arr['customerCode']:'';//客户编码
			$siteID = isset($obj_arr['siteID'])?$obj_arr['siteID']:0;//站点id
			$site_name = isset($obj_arr['site_name'])?$obj_arr['site_name']:'';//站点名称
			$accountId= isset($obj_arr['accountId'])?$obj_arr['accountId']:0;//分帐id
			$siteURL = isset($obj_arr['siteURL'])?$obj_arr['siteURL']:'';//地址
			$contractId = isset($obj_arr['contractId'])?$obj_arr['contractId']:'';//合同id

			//$billingcode = '';//不传
			//$hostpasscode = '123456';//不传
			//$guestpasscode = '123456';//不传

			//获得用户开通属性,根据当前用户id,组织id串,[不用]站点id，去获得当前用户属性数组
			$user_components_arr = array();
			if(isemptyArray($all_user_components_arr)){//如果是空数组
				$in_arr = array(
                    'userid' => $user_id,//用户id
                    'org_code' => $org_code,//组织id串
                    'siteid' =>0,//站点id
				);
				//是否向uc user开通属性表写数据
				//user 属性只看user 和 组织的,不用看站点
				$ns_components_arr = $CI->PowerLib->get_components($in_arr);
				if(!isemptyArray($ns_components_arr)){//如果不是空数组
					$from_num = isset($ns_components_arr['from_num'])?$ns_components_arr['from_num']:0;//从哪里获得的 1用户2组织3站点
					$user_components_arr = isset($ns_components_arr['components'])?$ns_components_arr['components']:array();
					if(!isemptyArray($user_components_arr)){//如果不是空数组
						//给每个user加属性components
						$user_new_arr[$key]['components'] = $user_components_arr;
					}
				}
			}else{//更新用户权限时用传入进来的
				$user_components_arr = $all_user_components_arr;
				$user_new_arr[$key]['components'] = $user_components_arr;
			}
			//如果用户从user和组织没有拿到属性，就用站点的,暂时定为，些时没必要给用户属性了
			if(isemptyArray($user_components_arr)){//如果是空数组,则用站点的
				// $user_new_arr[$key]['components'] = $site_components_arr;//用户自己的属性
			}
			//组织调用BOSS开通用户信息
			//单个用户信息
			$user_resource = array(
                 "siteURL" => $siteURL, //TODO 
			//"hostPassword" => $hostpasscode, //"123456" 不用
			//"guestPassword" => $guestpasscode//"123456" 不用
			);
			$ns_user_arr =array(
                'id' => $user_id, //用户id 2
			// 'billingCode' => $billingcode, //"611480100"
                'resource' => $user_resource, //单个用户信息
                'accountId' => $accountId, //分帐id 38480
                'siteId' => $siteID,//站点id 2
			);
			if(!isemptyArray($user_components_arr)){//不是空数组
				$ns_user_arr['components'] = $user_components_arr;//用户自己的属性
			}
			$bs_users_arr[] = $ns_user_arr;//调用BOSS开通用户需要用

			//TODO 调战役组织消息接口[员工入职]
			/* 1.	员工入职json数据
			 json={‘current_dept_id’:12,  //部门id
			 ‘current_dept_name’:’北京研发中心,
			 ‘display_name’:”王晓奎”, //员工姓名
			 }
			 $api_data_arr = array(
			 'current_dept_id'=> $current_dept_id,//部门id
			 'current_dept_name' => $current_dept_name,//部门名称
			 'display_name' => $display_name//员工姓名
			 );
			 $UCC_TYPE =1 ;//1 员工入职
			 $data = 'user_id=' . $user_id . '&session_id=' . $session_id . '&type =' . $UCC_TYPE . '&data=' . json_decode($api_data_arr);
			 $api_arr = $CI->API->UCCServerAPI($data,5,array('type'=> $UCC_TYPE));
			 if(api_operate_fail($api_arr)){//失败
			 log_message('error', 'uccapi message_org fail.');

			 }else{
			 log_message('debug', 'uccapi message_org success.');
			 }
			 *
			 */


		}
		if(isemptyArray($bs_users_arr)){//如果没有向BOSS保存的用户
			log_message('error', '$bs_users_arr  isemptyArray .');
			return 0;
		}
		//将带权限的use信息保存到uc_request表
		$CI->load->model('UC_Request_Model');
		$insert_data = array(
             'value' => json_encode($user_new_arr),//保存数组（Json串）
		);
		$insert_arr =  $CI->UC_Request_Model->insert_db($insert_data);
		$requestId = 0;
		if(db_operate_fail($insert_arr)){//失败
			log_message('error', 'insert  UC_Request fail.');
			//return 0;
		}else{
			$requestId = isset($insert_arr['insert_id'])?$insert_arr['insert_id']:0;;
			log_message('debug', 'insert  UC_Request success.');
		}
		// echo '$requestId= ' . $requestId . '<br/>';

		//BOSS回调地址
		//获得当前站点域的url
		$data = 'customer_code=' . $customerCode . '&siteid=' . $siteID;
		$ns_cluster_domain_arr = $CI->API->UCAPI($data,6,array('url' => UC_DOMAIN_URL ));
		$domain_cluster_arr = array();
		if(api_operate_fail($ns_cluster_domain_arr)){//失败
			log_message('error', ' uc api api/allocation/get_cluster ' . $data . ' fail.');
			return 0;
		}else{
			$domain_cluster_arr = arr_unbound_value($ns_cluster_domain_arr['other_msg'],'data',1,array());
			log_message('debug', ' uc api api/allocation/get_cluster ' . $data . ' success.');
		}
		//$domain_cluster_arr = $this->get_domain_bycustomercode($customerCode,$siteID);
		$cluster_arr = isset($domain_cluster_arr['cluster'])?$domain_cluster_arr['cluster']:array();
		if(isemptyArray($cluster_arr)){//如果没有分配的域
			log_message('error', ' $cluster_arr isemptyArray.');
			return 0;
		}
		$URL = isset($cluster_arr['url'])?$cluster_arr['url']:'';//获得域url
		if(bn_is_empty($URL)){//没有分配域的url
			log_message('error', 'cluster url is empty.');
			return 0;
		}
		$ip = isset($cluster_arr['ip'])?$cluster_arr['ip']:'';//获得域ip
		if(bn_is_empty($ip)){//没有分配域的url
			log_message('error', 'cluster ip is empty.');
			return 0;
		}
		$callback_url = 'http://' . $URL . UC_DOMAIN_DIR . '/api/response/asynOpen/' .$operat_type ;//1';//site_url('api/response/asynOpen/1');//回调地址1新开通流程[全新开启]
		//$callback_url = 'http://' . BOSS_CALLBACK_IP . UC_DOMAIN_DIR . '/api/response/asynOpen/1';
		//$callback_url = 'http://' . $ip . UC_DOMAIN_DIR . '/api/response/asynOpen/1';

		//echo '$callback_url= ' . $callback_url . '<br/>';

		// $boss_data = '';
		//批量开通¬-向boss，同步返回成功,后面会异步调用我们的接口

		//以前定义
		/*
		 //客户
		 $customer_arr = array(
		 'id' => 1,//客户在QSBOSS数据库中id
		 'name' => "创想空间商务通信服务有限公司",//客户名称
		 'customerCode' => "006325",//客户编码
		 );
		 //产品
		 $product_arr = array(
		 'id' => 1,//各产品在UMS中注册的产品ID
		 'name' => "PC2",//产品名称
		 );
		 //账户
		 $account_arr = array(
		 'id' => 1,//账户在QSBOSS数据库中id
		 'name' => "全时",//账户名称

		 );
		 //站点
		 $site_arr = array(
		 "id" => 1,//客户站点在UMS中注册时的ID
		 "url" => "quanshi.quanshi.com" //站点地址
		 );
		 //平台、应用
		 $components_arr = array(

		 );
		 //用户
		 $users_arr = array(
		 array(
		 "id" => 1,//在UMS注册的用户ID
		 "name" => "测试1",//姓名
		 "billingCode" => "12345",//计费代码
		 "hostPassword"=>"111111"//主持人密码
		 ),
		 );
		 $boss_arr = array(
		 'templateId' => 1,
		 'callback' =>  $callback_url,//回调地址
		 'type' => 'create',//类型
		 'createdTime' => time(),//当前时间戳
		 //'customer' => $customer_arr,//客户
		 //'product' => $product_arr,//产品
		 //'account' => $account_arr,//账户
		 //'site' => $site_arr,//站点
		 // 'components' => $components_arr,//平台、应用
		 // 'users' => $users_arr,//用户
		 );
		 *
		 */
		//资源
		$resource_arr = array(
             'siteURL' => $siteURL,//传过来 "www.google.com"
		);

		//构成要素
		$components_arr = $site_components_arr;//站点属性

		//合同 contract
		$contract_arr = array(
             'id' => $contractId,//TODO 用 uc_site 表的3848
             'resource' => $resource_arr,//资源
             'components' => $components_arr //构成要素
		);
		//用户信息[前面组织]
		/*
		 //单个用户信息
		 $user_resource = array(
		 "siteURL" => "www.quanshi.com", //"www.quanshi.com"
		 "hostPassword" => "123456", //"123456"
		 "guestPassword" => "123456"//"123456"
		 );
		 //用户信息
		 $bs_users_arr = array(
		 array(
		 'id' => 2, //2
		 'billingCode' => "611480100", //"611480100"
		 'resource' => $user_resource, //单个用户信息
		 'accountId' => 38480, //38480
		 'siteId' => 2//2

		 )
		 );
		 *
		 */
		//顾客
		$customer_arr = array(
             'id' => $contractId, //没记不给 1//合同id
             'name' => $site_name,//'北京创想空间软件技术有限公司',//TODO 没记不给 北京创想空间软件技术有限公司
             'customerCode' => $customerCode,//000000                    
             'contract'=>$contract_arr,//合同 contract
             'users' => $bs_users_arr,//用户信息
		);

		$boss_arr = array(
             'requestId' => $requestId,//UC表中的数据保存记录
             'callback' =>  $callback_url,//回调地址
             'type' => $operate_text,//'create',//类型
             'createdTime' => time(),//当前时间戳 
             'customer' => $customer_arr,//顾客
		);
		// print_r($boss_arr);
		// echo json_encode($boss_arr);
		// die();

		//向uc_request保存向boss发送的记录
		$boss_json = json_encode($boss_arr);
		//载入uc_request_model模型
		$CI->load->model('uc_request_model');
		//新加入数据,注意，这里只是保存，在运行Thread是，要注意去确认客户编码[customerCode]一下是否有对应的站点记录
		$data = array(
            'value' => $boss_json,//BOSS开通时,传过来的串
		);
		$insert_db_arr =  $CI->uc_request_model->insert_db($data);
		if(!db_operate_fail($insert_db_arr)){//成功
			log_message('debug', ' insert db uc_request_model  success.');
		}else{//失败
			log_message('error', 'insert db uc_request_model fail.');
		}
		$boss_user_arr = $CI->API->BOSSAPI($boss_json,1);
		if(api_operate_fail($boss_user_arr)){//失败
			log_message('error', ' BOSS batch api  fail.');
			return 0;
		}else{
			log_message('debug', 'BOSS batch api success.');
			return 1;
		}
		return 0;
	}

	/**
	 *
	 * @brief 传入帐号信息，组织BOSS操作需要的JSON串,并保存uc_request表，调用boss接口
	 * @details
	 * @param array
	 *  需要修改的帐号数组 二维数组 [此数组会折分为按不同的权限组织或站点分批调用BOSS接口]
	 $user_ok_arr = array(
	 'id' => $aaa,//当前用户id,
	 'auth' =>;//[可为空]是否管理员 0不是1是 ,有则用传入的，没有从管理员表获取
	 'power_ower' => ;//[可为空]用户权限变更时传值; 站点是站点siteurl; 部门是部门串-500-501-502-503;用户是用户最近的组织串或siteurl；
	 'organizationId' => $aaa,//当前组织id ；站点是为站点的组织id
	 'orgNodeCode' => $aaa,//当前组织串-500-501-502-503 ；站点是为站点的组织id串
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
	 * @param int $operat_type//1新开通流程[全新开启] 2开启更新UPDATE流程[以前关闭，现在是开启] 3关闭流程[以前开启，现在半闭]
	 * 4删除流程[以前开启，现在删除]5删除流程[以前关闭，但开启过，现在删除] 6以前未开启过，现在开启，7 开启状态，修改8 用户权限修改
	 * 9 部门权限修改 10 站点权限修改11用户调部门权限变更-最新的是组织权限 ； 12用户调部门权限变更-最新的是站点权限
	 * @param array $power_arr [当权限变更时用]站点权限/用户权限，可以没有
	 $power_arr = array(//可以为空[权限变更时用]
	 'user_components' => $aa,//用户权限[用户/组织最新的权限] 注:修改用户
	 'site_components' => $aa,//站点权限[站点最新的权限] 没有用了
	 );
	 * @return int  返回0:失败；1：成功
	 */
	public function get_boss_json_new($user_ok_arr = array(),$operat_type = '',$power_arr = array())
	{
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->helper('my_dgmdate');
		$CI->load->library('API','','API');
		log_message('debug', '$user_ok_arr = ' . json_encode($user_ok_arr) . ' $operat_type = ' . $operat_type . '  $power_arr = ' . json_encode($power_arr) . '.');

		if(isemptyArray($user_ok_arr) || bn_is_empty($operat_type)){//如果是空数组
			log_message('debug', '$user_ok_arr isemptyArray or $operat_type is empty.');
			return 1;
		}

		$CI->load->library('PowerLib','','PowerLib');
		$operate_text = '';
		switch ($operat_type) {
			case 1:  //1新开通流程[全新开启]
			case 6:  //6以前未开启过，现在开启
				$operate_text = 'create';//新建账号
				break;
			case 7: // 7 开启状态，修改
			case 8: // 8 用户权限修改[]
			case 9: // 9 部门权限修改
			case 10: //10 站点权限修改
			case 11: // 11用户调部门权限变更-最新的是组织权限 ；
			case 12: ////12用户调部门权限变更-最新的是站点权限
				$operate_text = 'update';//修改账号
				break;
			case 3:  //   3关闭流程[以前开启，现在半闭]
				$operate_text = 'disable';//停用账号
				break;
			case 2:  // 2开启更新UPDATE流程[以前关闭，现在是开启]
				$operate_text = 'enable';//启用账号
				break;
			case 4:  //  4删除流程[以前开启，现在删除] [删除当前的逻辑就是禁用]
				$operate_text = 'disable';//停用账号
				break;
			case 5: //5删除流程[以前关闭，但开启过，现在删除]
				$operate_text = 'delete';//删卡
				break;
			default:
				$operate_text = 'create';//新建账号
				break;
		}

		//boss开通用户信息,组织起来传给BOSS做批量开通用
		$bs_users_arr = array();
		//获得传入的权限属性
		$site_components_arr = array();//站点属性
		$all_user_components_arr = array();//用户/组织属性[用值，能用户进行权限修改]
		if(!isemptyArray($power_arr)){//如果不是空数组
			$site_components_arr = arr_unbound_value($power_arr,'site_components',1,array());//站点属性
			$all_user_components_arr = arr_unbound_value($power_arr,'user_components',1,array());//用户/组织属性
		}

		//站点权限数组
		// $all_site_components_arr = array();
		$CI->load->model('uc_user_model');
		$CI->load->model('uc_user_admin_model');

		//        include_once APPPATH . 'libraries/public/Component_class.php';
		//        $component_obj = new Component_class();
		foreach ($user_ok_arr as $key => $value)
		{
			//$user_new_arr[$key] = $value;//新的user数组信息
			//用户id
			$user_id = isset($value['id'])?$value['id']:0;
			if($user_id > 0){
				$auth = isset($value['auth'])?$value['auth']:'';//TODO 是否管理员 0不是1是 ,有则用传入的，没有从管理员表获取
				$ns_power_ower = isset($value['power_ower'])?$value['power_ower']:'';//用户权限变更时传值; 站点是站点siteurl; 部门是部门串-500-501-502-503;用户是用户最近的组织串或siteurl；
				$org_str = isset($value['organizationId'])?$value['organizationId']:0;
				$org_code = isset($value['orgNodeCode'])?$value['orgNodeCode']:'';//-500-501-502-503
				$obj_arr = isset($value['obj']['sys'])?$value['obj']['sys']:array();
				$customerCode = isset($obj_arr['customerCode'])?$obj_arr['customerCode']:'';//客户编码
				$siteID = isset($obj_arr['siteID'])?$obj_arr['siteID']:0;//站点id
				$site_name = isset($obj_arr['site_name'])?$obj_arr['site_name']:'';//站点名称
				$accountId= isset($obj_arr['accountId'])?$obj_arr['accountId']:0;//分帐id
				$siteURL = isset($obj_arr['siteURL'])?$obj_arr['siteURL']:'';//地址
				$contractId = isset($obj_arr['contractId'])?$obj_arr['contractId']:'';//合同id
				$user_type = isset($obj_arr['user_type'])?$obj_arr['user_type']:'';//帐号类型0普通用户1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员7生态企业普通用户
				$user_source = isset($obj_arr['user_source'])?$obj_arr['user_source']:'';//帐号开通来源,多个用，号分隔 1批量导入2组织管理员-单个新加3生态渠道管理员新加4任务新加

				$ns_user_arr =array();//调用boss时的用户信息
				$user_power_type = 1 ;//当前用户权限类型1站点权限2部门权限3用户自己的权限
				$user_power_key = $siteURL;//权限数组下标，站点权限：$siteURL,组织为：组织权限串；用户为：$siteURL
				//新的user数组信息[用于保存到uc_request表]
				$user_new_arr = $value;
				$auth = 0;

				//获得用户开通属性,根据当前用户id,组织id串,[不用]站点id，去获得当前用户属性数组
				$user_components_arr = array();//
				if(isemptyArray($all_user_components_arr)){//如果是空数组,说明需要去拿用户的属性
					//获得用户当前使用的权限
					$in_arr = array(
                            'userid' => $user_id,//用户id
                            'org_code' => $org_code,//组织id串
                            'siteid' =>$siteID,//站点id
					);
					log_message('info', 'in_get_components');
					$ns_components_arr = $CI->PowerLib->get_components($in_arr);
					log_message('info', 'out_get_components');
					if(!isemptyArray($ns_components_arr)){//如果不是空数组
						$from_num = isset($ns_components_arr['from_num'])?$ns_components_arr['from_num']:0;//从哪里获得的 1用户2组织3站点
						$power_org_code = isset($ns_components_arr['power_org_code'])?$ns_components_arr['power_org_code']:'';//
						$user_components_arr = isset($ns_components_arr['components'])?$ns_components_arr['components']:array();
						//if(!isemptyArray($user_components_arr)){//如果不是空数组
						switch ($from_num) {//0没有1用户2组织3站点
							case 0: //0没有
								break;
							case 1://1用户
								$user_power_type = 3 ;//当前用户权限类型1站点权限2部门权限3用户自己的权限
								//获得组织/站点权限

								$in_arr = array(
                                               'org_code' => $org_code,//组织id串
                                               'siteid' => $siteID,//站点id
                                               'siteurl' => $siteURL,//站点url
								);
								$user_power_key = $CI->PowerLib->get_power_uuid($in_arr);
								// }
								//给每个user加属性components
								//$user_new_arr['components'] = $user_components_arr;
								break;
							case 2://2组织
								$user_power_type = 2 ;//当前用户权限类型1站点权限2部门权限3用户自己的权限
								$user_power_key = $power_org_code;//权限数组下标，站点权限：$siteURL,组织为：组织权限串；用户为：$siteURL
								//$comp_org_code = $power_org_code;//权限组织串[如果是组织权限时]
								break;
							case 3://3站点
								$user_power_type = 1 ;//当前用户权限类型1站点权限2部门权限3用户自己的权限
								$user_power_key = $siteURL;//权限数组下标，站点权限：$siteURL,组织为：组织权限串；用户为：$siteURL
								break;
						}

						// }
					}
				}else{//更新用户权限时用传入进来的
					switch ($operat_type) {
						case 8: //8 用户权限修改[]
							$user_power_type = 3 ;//当前用户权限类型1站点权限2部门权限3用户自己的权限
							break;
						case 9: //9 部门权限修改
						case 11: // 11用户调部门权限变更-最新的是组织权限 ；
							$user_power_type = 2 ;//当前用户权限类型1站点权限2部门权限3用户自己的权限
							break;
						case 10: //10 站点权限修改
						case 12: ////12用户调部门权限变更-最新的是站点权限
							$user_power_type = 1 ;//当前用户权限类型1站点权限2部门权限3用户自己的权限
							break;
					}
					$user_power_key = $ns_power_ower;//$siteURL;//权限数组下标，站点权限：$siteURL,组织为：组织权限串；用户为：$siteURL
					$user_components_arr = $all_user_components_arr;
					//$user_new_arr['components'] = $user_components_arr;
				}
				//整合用户权限,先从用户权限拿，没有再从站点权限拿
				$user_components_arr = $CI->PowerLib->power_add_id($user_components_arr);
				$ns_ok_user_comp_arr = $user_components_arr;
				//if(isemptyArray($ns_ok_user_comp_arr)){//如果是空数组
				//  $ns_ok_user_comp_arr = $ns_site_components_arr;
				// }
				//print_r($ns_site_components_arr);
				// die();
				$ns_cm_comp_arr = array();//用户没有时，默认要带的权限
				//修改auth及
				foreach($ns_ok_user_comp_arr as  $cm_k => $cm_v){
					if(is_array($cm_v)){
						$ns_name = arr_unbound_value($cm_v,'name',2,'');
						$ns_component_id = arr_unbound_value($cm_v,'id',2,'');
						//                            if(bn_is_empty($ns_component_id)){//没有值
						//                                $ns_component_id = $component_obj->get_comid($ns_name);
						//                                $ns_component_id = empty_to_value($ns_component_id,0);
						//                                $ns_ok_user_comp_arr[$cm_k]['id']= $ns_component_id;//改变id 的值
						//                            }
						$ns_name_lower = strtolower($ns_name);//转换为小写
						if($ns_name_lower == 'uc'){
							$ns_ok_user_comp_arr[$cm_k]['property']['auth'] = $auth ;//修改auth
							//$ns_ok_user_comp_arr[$cm_k]['id']= 4;
							$ns_cm_comp_arr['id'] = $ns_component_id;//TODO 在后面在改一下 ;
							$ns_cm_comp_arr['name'] = $ns_name;
							$ns_cm_comp_arr['activationUrl'] = arr_unbound_value($cm_v,'activationUrl',2,'');
							$ns_cm_comp_arr['property']['auth'] = $auth;
							// break;
						}
					}
				}
				$ns_user_property_arr = array();//最后放在用户后面的权限
				if($user_power_type == 3){//当前用户权限类型1站点权限2部门权限3用户自己的权限
					$ns_user_property_arr = $ns_ok_user_comp_arr;  //用户全权限
				}else{
					$ns_user_property_arr[] = $ns_cm_comp_arr;//默认权限
				}
				$user_new_arr['components'] = $ns_user_property_arr;//用户权限
				//组织调用BOSS开通用户信息
				//单个用户信息
				// $user_resource = array(
				//  "siteURL" => $siteURL, //TODO
				//"hostPassword" => $hostpasscode, //"123456" 不用
				//"guestPassword" => $guestpasscode//"123456" 不用
				// );
				//获得当前用户的分帐id
				/*
				$sel_field = 'accountId,siteId';
				$where_arr = array(
                            'userID' => $user_id                           
				);
				$sel_arr = $CI->uc_user_model->get_db_arr($where_arr,$sel_field);
				if(isemptyArray($sel_arr)){//如果是空数组
					log_message('debug', 'uc_user_model userID = ' .  $user_id . '  is empty.');
					//return 0;
				}
				
				$ns_accountId = arr_unbound_value($sel_arr,'accountId',2,$accountId);
				*/
				//获取账户id,这里获取管理员的账户id
				$select = 'accountId,siteID';
				$where = array(
					'siteID'=>$siteID,
					'role_id'=>1
				);
				$sel_arr = $CI->uc_user_admin_model->get_db_arr($where, $select);
				if(isemptyArray($sel_arr)){//如果是空数组
					log_message('debug', 'uc_user_admin_model siteid = ' .  $ns_siteId . '  is empty.');
					//return 0;
				}
				$ns_accountId = arr_unbound_value($sel_arr,'accountId',2,$accountId);
				$ns_siteId = arr_unbound_value($sel_arr,'siteID',2,$siteID);
				log_message('info', 'imrabit');
				
				$ns_user_arr =array(
                        'id' => $user_id, //用户id 2
                        'accountId' => $ns_accountId, //分帐id 38480
                        'siteId' => $ns_siteId,//站点id 2
				// 'billingCode' => $billingcode, //"611480100"
				// 'resource' => $user_resource, //单个用户信息

				);
				// if(!isemptyArray($user_components_arr)){//不是空数组
				// if( $user_power_type == 3){//当前用户权限类型1站点权限2部门权限3用户自己的权限
				if(!isemptyArray($ns_user_property_arr)){//如果不是空数组
					$ns_user_arr['components'] = $ns_user_property_arr;//用户自己的属性
				}
				// }
				//}
				//
				//TODO 调战役组织消息接口[员工入职]
				/* 1.	员工入职json数据
				 json={‘current_dept_id’:12,  //部门id
				 ‘current_dept_name’:’北京研发中心,
				 ‘display_name’:”王晓奎”, //员工姓名
				 }
				 $api_data_arr = array(
				 'current_dept_id'=> $current_dept_id,//部门id
				 'current_dept_name' => $current_dept_name,//部门名称
				 'display_name' => $display_name//员工姓名
				 );
				 $UCC_TYPE =1 ;//1 员工入职
				 $data = 'user_id=' . $user_id . '&session_id=' . $session_id . '&type =' . $UCC_TYPE . '&data=' . json_decode($api_data_arr);
				 $api_arr = $CI->API->UCCServerAPI($data,5,array('type'=> $UCC_TYPE));
				 if(api_operate_fail($api_arr)){//失败
				 log_message('error', 'uccapi message_org fail.');

				 }else{
				 log_message('debug', 'uccapi message_org success.');
				 }
				 *
				 */

				// }else{//没有用户，只是部门权限或站点权限
				//                $ns_user_arr =array();
				//                if(!isemptyArray($all_user_components_arr)){//如果不是空数组,说明是更改组织权限
				//                    $user_power_type = 2 ;//当前用户权限类型1站点权限2部门权限3用户自己的权限
				//                    $user_power_key = $org_code;//权限数组下标，站点权限：$siteURL,组织为：组织权限串；用户为：$siteURL
				//                    $bs_power_arr = $all_user_components_arr;//如果是修改站点或部门权限时的权限
				//                    $user_new_arr['components'] = $all_user_components_arr;
				//                    $comp_org_code = $org_code;//权限组织串[如果是组织权限时]
				//                }else{//站点权限
				//                    $bs_power_arr = $site_components_arr;//如果是修改站点或部门权限时的权限
				//                    $user_new_arr['components'] = $site_components_arr;
				//                }

				// }
				$user_new_arr['comp_type'] = $user_power_type;//用户权限类型1站点权限2部门权限3用户自己的权限
				//$user_new_arr['comp_org_code'] = $comp_org_code;//权限组织串[如果是组织权限时]

				$bs_ns_ok_arr = array(
				// 'bs_power_arr' => $bs_power_arr,//站点权限或部门权限
                    'user_arr' => $ns_user_arr,//调boss的用户信息
                    'user_new' => $user_new_arr,//解析后的用户信息
				);
				$bs_users_arr[$user_power_key][] = $bs_ns_ok_arr;//调用BOSS开通用户需要用
			}
		}


		if(isemptyArray($bs_users_arr)){//如果没有向BOSS保存的用户
			log_message('error', '$bs_users_arr  isemptyArray .');
			return 0;
		}
		//BOSS回调地址
		//获得当前站点域的url
		$data = 'customer_code=' . $customerCode . '&siteid=' . $siteID;
		$ns_cluster_domain_arr = $CI->API->UCAPI($data,6,array('url' => UC_DOMAIN_URL ));

		$domain_cluster_arr = array();
		if(api_operate_fail($ns_cluster_domain_arr)){//失败
			log_message('error', ' uc api api/allocation/get_cluster ' . $data . ' fail.');
			return 0;
		}else{
			$domain_cluster_arr = arr_unbound_value($ns_cluster_domain_arr['other_msg'],'data',1,array());
			log_message('debug', ' uc api api/allocation/get_cluster ' . $data . ' success.');
		}
		//$domain_cluster_arr = $this->get_domain_bycustomercode($customerCode,$siteID);
		$cluster_arr = isset($domain_cluster_arr['cluster'])?$domain_cluster_arr['cluster']:array();
		if(isemptyArray($cluster_arr)){//如果没有分配的域
			log_message('error', ' $cluster_arr isemptyArray.');
			return 0;
		}
		$URL = isset($cluster_arr['url'])?$cluster_arr['url']:'';//获得域url
		if(bn_is_empty($URL)){//没有分配域的url
			log_message('error', 'cluster url is empty.');
			return 0;
		}
		$ip = isset($cluster_arr['ip'])?$cluster_arr['ip']:'';//获得域ip
		if(bn_is_empty($ip)){//没有分配域的url
			log_message('error', 'cluster ip is empty.');
			return 0;
		}
			
		$has_err = 0;//是否有失败记录0没有1有
		$CI->load->model('UC_Request_Model');
		log_message('debug', '  $bs_users_arr=' . any_to_str($bs_users_arr) . ' ');
		foreach($bs_users_arr as $k => $v){//按不同类型，调用boss接口
			$templateUUID = $k;
			//$ok_bs_power_arr = array();//修改站点或部门时的权限数组
			$ok_user_arr = array();//调用boss的user串
			$ok_user_new = array();//处理的数据记录
			foreach($v as $bs_k => $bs_v){
				//$ns_bs_power_arr = arr_unbound_value($bs_v,'bs_power_arr',1,array());
				$ns_user_arr = arr_unbound_value($bs_v,'user_arr',1,array());
				$ns_user_new = arr_unbound_value($bs_v,'user_new',1,array());

				if(!isemptyArray($ns_user_arr)){//不为空
					$ok_user_arr[] = $ns_user_arr;
				}
				if(!isemptyArray($ns_user_new)){//不为空
					$ok_user_new[] = $ns_user_new;
				}
			}

			$ns_user_count = count($ok_user_arr);//用户数量
			$max_count = UC_BOSSAPI_MAX_NUM;//30;//每次最大运行数量
			log_message('debug', '$max_count=' . $max_count . '.');
			$max_count = empty_to_value($max_count,30);
			log_message('debug', '$max_count=' . $max_count . '.');
			$record_no = 1;//记录号
			$ns_bath_user_arr = array();//分批数组
			$ns_bath_dbuser_arr = array();//分批保存到数据库的数组
			foreach($ok_user_arr as $ns_k => $ns_v){
				$ns_bath_user_arr[$ns_k] = $ns_v;
				$ns_user_id = arr_unbound_value($ns_v,'id',2,'');
				if(!bn_is_empty($ns_user_id)){//有数据
					$is_getuser_frmdb = 0;//是否也会在数据库在保存记录中有，0没有1有
					foreach($ok_user_new as $ns_db_k => $ns_db_v){
						$ns_db_userid = arr_unbound_value($ns_db_v,'id',2,'');
						if($ns_user_id == $ns_db_userid){
							$ns_bath_dbuser_arr[$ns_db_k] = $ns_db_v;//分批保存到数据库的数组
							$is_getuser_frmdb = 1;//是否也会在数据库在保存记录中有，0没有1有
							break;
						}
					}
					if($is_getuser_frmdb == 1){//是否也会在数据库在保存记录中有，0没有1有
						if( (($record_no % $max_count) == 0) || ($ns_user_count == $record_no)){
							//将带权限的use信息保存到uc_request表
							$insert_data = array(
                                 'value' => json_encode($ns_bath_dbuser_arr),//保存数组（Json串）
							);
							$insert_arr =  $CI->UC_Request_Model->insert_db($insert_data);
							$requestId = 0;
							if(db_operate_fail($insert_arr)){//失败
								log_message('error', 'insert  UC_Request fail.');
								$has_err = 1;//是否有失败记录0没有1有
							}else{
								$requestId = isset($insert_arr['insert_id'])?$insert_arr['insert_id']:0;;
								log_message('debug', 'insert  UC_Request success.');
							}
							// echo '$requestId= ' . $requestId . '<br/>';
							$callback_url = 'http://' . $URL . UC_DOMAIN_DIR . '/api/response/asynOpen/' .$operat_type ;//1';//site_url('api/response/asynOpen/1');//回调地址1新开通流程[全新开启]

							//合同 contract
							$contract_arr = array(
                                'id' => $contractId,//TODO 用 uc_site 表的3848

							);
							
							//顾客
							$customer_arr = array(
                                'id' => $contractId, //没记不给 1//合同id
                                'customerCode' => $customerCode,//000000                    
                                'contract'=>$contract_arr,//合同 contract
                                'users' => $ns_bath_user_arr,//$ok_user_arr,//用户信息
							);
							
							$boss_arr = array(
                                'requestId' => $requestId,//UC表中的数据保存记录
                                'callback' =>  $callback_url,//回调地址
                                'createdTime' => time(),//当前时间戳 
                                //'templateUUID'=> $templateUUID,//'devcloud.quanshi.com', //////=>如果是部门的话，需要给-12-253-987；如果部门没有配置属性，则给站点的URL
								'templateUUID'=> '',//'devcloud.quanshi.com', //////=>如果是部门的话，需要给-12-253-987；如果部门没有配置属性，则给站点的URL
								'customer' => $customer_arr,//顾客
                                'type' => $operate_text,//'create',//类型
							);
							

							//载入uc_request_model模型
							$CI->load->model('uc_request_model');

							//向uc_request保存向boss发送的记录
							$boss_json = json_encode($boss_arr);
							//新加入数据,注意，这里只是保存，在运行Thread是，要注意去确认客户编码[customerCode]一下是否有对应的站点记录
							$data = array(
                               'value' => $boss_json,//BOSS开通时,传过来的串
							);
							
							$insert_db_arr =  $CI->uc_request_model->insert_db($data);
							if(!db_operate_fail($insert_db_arr)){//成功
								log_message('debug', ' insert db uc_request_model  success.');
							}else{//失败
								$has_err = 1;//是否有失败记录0没有1有
								log_message('error', 'insert db uc_request_model fail.');
							}
							//write_test_file( '' . __FUNCTION__ . time() . '.txt' ,$boss_json);
							log_message('info', 'chl_boss_api '.var_export($boss_json, true));
							$boss_user_arr = $CI->API->BOSSAPI($boss_json,1);
							if(api_operate_fail($boss_user_arr)){//失败
								$has_err = 1;//是否有失败记录0没有1有
								log_message('error', ' BOSS batch api  fail.');
							}else{
								log_message('debug', 'BOSS batch api success.');
							}
							$ns_bath_user_arr = array();//分批数组
							$ns_bath_dbuser_arr = array();//分批保存到数据库的数组
						}
						$record_no += 1;//记录号
					}
				}
			}
		}
		if( $has_err == 0){//是否有失败记录0没有1有
			return 1;
		}else{
			return 0;
		}
	}

	/**
	 *
	 * @brief 根据用户id userid ，获得用户相关信息
	 * @details
	 *  @param int $userid 用户id
	 * @return array $user_arr 数组
	 $user_ok_arr = array(
	 'id' => $aaa,//当前用户id
	 'organizationId' => $aaa,//当前组织id
	 'orgNodeCode' => $aaa,//当前组织串-500-501-502-503
	 'obj' => array(
	 'sys' => array(
	 'customerCode' => $aaa,//客户编码
	 'siteID' => $aaa,//站点id
	 'site_name' => $aaa,//站点名称
	 'accountId'=>$aaa,//分帐id ；注意：如果有用户，则是用户自己的
	 'siteURL' => $aaa,//地址
	 'contractId' => $aaa,//合同id
	 ),
	 )

	 );
	 */
	public function get_user_msg($user_id = ''){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->helper('my_dgmdate');
		$CI->load->library('API','','API');
		$re_user_arr = array();
		if(bn_is_empty($user_id)){//为空，则加,号
			return $re_user_arr;
		}
		//2uc拿相关信息
		$CI->load->model('uc_user_model');
		$sel_field = 'Collect,billingcode,hostpasscode,guestpasscode,siteId,accountId';
		$where_arr = array(
                'userID' => $user_id, 
		//'site_id' => $site_id,
		);
		$sel_arr = $CI->uc_user_model->get_db_arr($where_arr,$sel_field);

		if(isemptyArray($sel_arr)){//如果是空数组
			log_message('error', 'uc_user_model $user_id = ' .  $user_id . '  is empty.');
			return $re_user_arr;
		}
		log_message('debug', 'uc_user_model $user_id = ' . json_encode($sel_arr) . '  is ok.');
		$ns_Collect = arr_unbound_value($sel_arr,'Collect',2,'');
		$ns_billingcode = arr_unbound_value($sel_arr,'billingcode',2,'');
		$ns_hostpasscode = arr_unbound_value($sel_arr,'hostpasscode',2,'');
		$ns_guestpasscode = arr_unbound_value($sel_arr,'guestpasscode',2,'');
		$ns_siteId = arr_unbound_value($sel_arr,'siteId',2,'');
		$ns_accountId = arr_unbound_value($sel_arr,'accountId',2,'');
		if(  bn_is_empty($ns_siteId)   || bn_is_empty($ns_accountId) ){
			log_message('error', '  uc_user_model  ' . json_encode($sel_arr) . 'has empty.');
			return $re_user_arr;
		}

		log_message('dubug', '  uc_user_model  ' . json_encode($sel_arr) . ' is ok.');
		$customerCode = '';//客户编码
		$CI->load->model('uc_account_model');
		$sel_field = 'customercode';
		$where_arr = array(
                'id' => $ns_accountId,                        
		);
		$sel_arr = $CI->uc_account_model->get_db_arr($where_arr,$sel_field);
		if(isemptyArray($sel_arr)){//如果是空数组
			log_message('error', 'uc_account_model $ns_accountId = ' .  $ns_accountId . '  is empty.');
			return $re_user_arr;
		}
		log_message('debug', 'uc_account_model $ns_accountId = ' . $ns_accountId . '  is ok.');
		$customerCode = arr_unbound_value($sel_arr,'customercode',2,'');
		if( bn_is_empty($customerCode)){
			log_message('error', ' $customerCode = ' .  $customerCode . '  is empty.');
			return $re_user_arr;
		}

		log_message('debug', ' $customerCode = ' . $customerCode . '  is ok.');

		$ne_org_arr = array();
		$org_code = '';
		$org_id = '';
		$org_parentId = '';
		$siteURL = $user_id;//5782  ;// 当前用户id
		$get_org_arr = $CI->API->UMS_Special_API('',15,array('url' => $siteURL));
		if(api_operate_fail($get_org_arr)){//失败;可能没有组织机构
			$err_msg = ' usm api rs/organizations/' . $siteURL . ' fail .';
			log_message('error', $err_msg);
			return $re_user_arr;
		}else{
			$ne_org_arr = arr_unbound_value($get_org_arr,'data',1,array());
			$err_msg = ' usm api rs/organizations/' . $siteURL . ' success .';
			log_message('debug', $err_msg);
		}
		if(!isemptyArray($ne_org_arr)){//不是空数组
			$org_code = arr_unbound_value($ne_org_arr[0],'nodeCode',2,'');
			//$customerCode = arr_unbound_value($ne_org_arr[0],'customercode',2,'');
			$org_id = arr_unbound_value($ne_org_arr[0],'id',2,'');
			$org_parentId = arr_unbound_value($ne_org_arr[0],'parentId',2,'');
		}
		if( bn_is_empty($org_id) ){
			log_message('error', '   $org_id= ' . $org_id . '  is empty.');
			return $re_user_arr;
		}
		//根据customercode和站点id从uc_customer拿站点名称
		$CI->load->model('uc_customer_model');
		$sel_field = 'name,contractId';
		$where_arr = array(
                'siteId' => $ns_siteId, 
                'customerCode' => $customerCode,                           
		);
		$sel_arr = $CI->uc_customer_model->get_db_arr($where_arr,$sel_field);
		if(isemptyArray($sel_arr)){//如果是空数组
			log_message('error', 'uc_customer_model $ns_siteId = ' .  $ns_siteId . ' $customerCode = ' .  $customerCode . ' is empty.');
			return $re_user_arr;
		}
		$site_name = arr_unbound_value($sel_arr,'name',2,''); //站点名称
		$contractId = arr_unbound_value($sel_arr,'contractId',2,''); //合同id
		if( bn_is_empty($site_name) || bn_is_empty($contractId)){
			log_message('error', '   $site_name= ' . $site_name . ' or   $contractId= ' . $contractId . ' is empty.');
			return $re_user_arr;
		}
		log_message('debug', '   $site_name= ' . $site_name . '   $contractId= ' . $contractId . 'is ok.');
		//获得站点url
		$CI->load->model('uc_site_model');
		$sel_field = 'domain';
		$where_arr = array(
                'siteID' => $ns_siteId, 
                'customerCode' => $customerCode,                           
		);
		$sel_arr = $CI->uc_site_model->get_db_arr($where_arr,$sel_field);
		if(isemptyArray($sel_arr)){//如果是空数组
			log_message('error', 'uc_site_model $ns_siteId = ' .  $ns_siteId . ' $customerCode = ' .  $customerCode . ' is empty.');
			return $re_user_arr;
		}
		$ns_user_siteURL = arr_unbound_value($sel_arr,'domain',2,''); //站点url
		if( bn_is_empty($ns_user_siteURL)){
			log_message('error', '   $ns_user_siteURL= ' . $ns_user_siteURL . 'is empty.');
			return $re_user_arr;
		}
		log_message('debug', '   $ns_user_siteURL= ' . $ns_user_siteURL . 'is ok.');
		$re_user_arr = array(
            'id' => $user_id,//当前用户id
            'organizationId' => $org_id,//当前组织id
            'orgNodeCode' => $org_code,//当前组织串-500-501-502-503            
            'obj' => array(
                'sys' => array(
                    'customerCode' => $customerCode,//客户编码
                    'siteID' => $ns_siteId,//站点id 
                    'site_name' => $site_name,//站点名称 
                    'accountId'=>$ns_accountId,//分帐id ；注意：如果有用户，则是用户自己的
                    'siteURL' => $ns_user_siteURL,//地址
                    'contractId' => $contractId,//合同id
		),
		)
		);
		return $re_user_arr;
	}
	/**
	 *
	 * @brief 根据线程的数据组织并调用BOSS做相关操作 见意一次只调用一批；只要有一批失败，则都判断为失败
	 * @details
	 *  @param array $thread_user 线程中保存的用户信息
	 /* $user_arr = array(
	 'user_id' => $user_id,
	 'operate_txt' => $operate_txt,
	 'user_operate' => $user_operate,
	 'sys' => array(
	 'customerCode' => $this->p_customer_code,//客户编码
	 'siteID' => $this->p_site_id,//站点id
	 'site_name' => $this->p_site_name,//站点名称
	 'accountId'=>$this->p_account_id,//分帐id ；注意：如果有用户，则是用户自己的
	 'siteURL' => $this->p_stie_domain,//地址
	 'contractId' => $this->p_contract_id,//合同id
	 'operator_id' => $this->p_user_id,//操作发起人用户ID
	 'client_ip' => $this->p_client_ip,//客户端ip
	 'server_ip' => $this->p_server_ip,//服务端ip
	 'oper_account' => $this->p_account,//操作帐号
	 'oper_display_name' => $this->p_display_name,//操作姓名
	 'orgID' => $this->p_org_id,//所属站点，分公司，生态企业组织id[各种管理员新加时，必填]
	 ),

	 );
	 * @return int 0:失败；1：成功。2回调失败 ,
	 */
	public function boss_modify_user($thread_user = array()){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->helper('my_dgmdate');
		$CI->load->library('API','','API');
		//0:失败；1：成功。2回调失败
		//获得数据$thread_value

		if( bn_is_empty($thread_user)){
			return 0;
		}
		//转换为数组
		$thread_user_arr = json_decode($thread_user,true);
		if(isemptyArray($thread_user_arr)){//空数组
			return 0;
		}

		$boss_create1_arr = array();//新建账号
		$boss_create_arr = array();//新建账号
		$boss_update_arr = array();//修改账号
		$boss_disable_arr = array();//停用账号
		$boss_enable_arr = array();//启用账号
		$boss_delete1_arr = array();//删卡
		$boss_delete_arr = array();//删卡
		foreach($thread_user_arr as $k => $v){
			if (is_array($v) ){//是数组
				$user_id = arr_unbound_value($v,'user_id',2,'');
				$operate_txt = arr_unbound_value($v,'operate_txt',2,'');
				$user_operate = arr_unbound_value($v,'user_operate',2,'');
				$sys_arr = arr_unbound_value($v,'sys',1,array());
				log_message('debug', json_encode($v));
				if( (!bn_is_empty($user_id)) &&  (!bn_is_empty($operate_txt)) &&  (!bn_is_empty($user_operate)) ){//不为空

					$user_arr = $this->get_user_msg($user_id);;//获得用户信息

					$user_arr['obj']['sys'] = $sys_arr;
					switch ($user_operate) {
						case 1:  //1新开通流程[全新开启]
							$boss_create1_arr[] = $user_arr;//新建账号
							break;
						case 6:  //6以前未开启过，现在开启
							$boss_create_arr[] = $user_arr;//新建账号
							break;
						case 7: // 7 开启状态，修改
							$boss_update_arr[] = $user_arr;//修改账号
							break;
						case 3:  //   3关闭流程[以前开启，现在半闭]
							$boss_disable_arr[] = $user_arr ;//停用账号
							break;
						case 2:  // 2开启更新UPDATE流程[以前关闭，现在是开启]
							$boss_enable_arr[] = $user_arr ;//启用账号
							break;
						case 4:  //  4删除流程[以前开启，现在删除] [删除当前的逻辑就是禁用]
							$boss_delete1_arr[] = $user_arr;//删卡
							break;
						case 5: //5删除流程[以前关闭，但开启过，现在删除]
							$boss_delete_arr[] = $user_arr;//删卡
							break;
						default:
							$boss_create_arr[] = $user_arr;//新建账号
							break;
					}
				}else{
					log_message('error', json_encode($v) . ' has empty');
				}
			}
		}
		$is_err = 0;//是否失败0没有失败1失败
		if(!isemptyArray($boss_create1_arr)){//不是空数组
			//返回0:失败；1：成功
			$re_state = $this->get_boss_json_new($boss_create1_arr,$user_operate,array());
			if($re_state == 0){//失败败需要打日志
				log_message('error', json_encode($boss_create1_arr) . ' boss api  is fail ');
				$is_err = 1;//是否失败0没有失败1失败
			}
		}
		if(!isemptyArray($boss_create_arr)){//不是空数组
			//返回0:失败；1：成功
			$re_state = $this->get_boss_json_new($boss_create_arr,$user_operate,array());
			if($re_state == 0){//失败败需要打日志
				log_message('error', json_encode($boss_create1_arr) . ' boss api  is fail ');
				$is_err = 1;//是否失败0没有失败1失败
			}
		}
		if(!isemptyArray($boss_update_arr)){//不是空数组
			//返回0:失败；1：成功
			$re_state = $this->get_boss_json_new($boss_update_arr,$user_operate,array());
			if($re_state == 0){//失败败需要打日志
				log_message('error', json_encode($boss_create1_arr) . ' boss api  is fail ');
				$is_err = 1;//是否失败0没有失败1失败
			}
		}
		if(!isemptyArray($boss_disable_arr)){//不是空数组
			//返回0:失败；1：成功
			$re_state = $this->get_boss_json_new($boss_disable_arr,$user_operate,array());
			if($re_state == 0){//失败败需要打日志
				log_message('error', json_encode($boss_create1_arr) . ' boss api  is fail ');
				$is_err = 1;//是否失败0没有失败1失败
			}
		}
		if(!isemptyArray($boss_enable_arr)){//不是空数组
			//返回0:失败；1：成功

			$re_state = $this->get_boss_json_new($boss_enable_arr,$user_operate,array());
			if($re_state == 0){//失败败需要打日志
				log_message('error', json_encode($boss_create1_arr) . ' boss api  is fail ');
				$is_err = 1;//是否失败0没有失败1失败
			}
		}
		if(!isemptyArray($boss_delete1_arr)){//不是空数组
			//返回0:失败；1：成功
			$re_state = $this->get_boss_json_new($boss_delete1_arr,$user_operate,array());
			if($re_state == 0){//失败败需要打日志
				log_message('error', json_encode($boss_create1_arr) . ' boss api  is fail ');
				$is_err = 1;//是否失败0没有失败1失败
			}
		}
		if(!isemptyArray($boss_delete_arr)){//不是空数组
			//返回0:失败；1：成功
			$re_state = $this->get_boss_json_new($boss_delete_arr,$user_operate,array());
			if($re_state == 0){//失败败需要打日志
				log_message('error', json_encode($boss_create1_arr) . ' boss api  is fail ');
				$is_err = 1;//是否失败0没有失败1失败
			}
		}
		if($is_err == 1){//是否失败0没有失败1失败
			return 2;
		}else{
			return 1;
		}
	}
	/**
	 *
	 * @brief 根据线程的数据uc后台权限修改数组并调用BOSS做相关操作
	 * @details
	 *  @param array $thread_user 线程中保存的用户信息
	 /* $power_in_arr = array(
	 'type' => $power_type,//类型1站点权限2部门权限3用户权限4会议属性 5用户调部门权限变更-最新的是组织权限 ； 6用户调部门权限变更-最新的是站点权限7生态企业权限
	 'id' => $ns_id,//保存对应的id 站点是站点orgid; 部门是部门串-500-501-502-503;用户是用户user_id；5、6是用户id串,格式 [1,2,3]
	 'power_ower' => $ns_power_ower,//用户权限变更时传值; 站点是站点siteurl; 部门是部门串-500-501-502-503;用户是用户最近的组织串或siteurl；5：最新调入部门所有的权限组织串；6：当前站点siteurl
	 'components' => $power_components_arr,//最新的权限数组[真必须有值]
	 'oper_type' => $oper_type,// ums可以获得下级的组织类型，多个用,号分隔types：空：不需要[如type=5或6时]；1:企业 2:生态企业 3:部门 4:生态企业部门 5 分公司 逗号分割的字符串,
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
	 * @return int 0:失败；1：成功。2回调失败 ,
	 */
	public function boss_modify_power($thread_user = array()){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$CI =& get_instance();
		$CI->load->helper('my_publicfun');
		$CI->load->helper('my_dgmdate');
		$CI->load->library('API','','API');
		//0:失败；1：成功。2回调失败
		//获得数据$thread_value
		if( bn_is_empty($thread_user)){
			return 0;
		}
		//转换为数组
		$thread_user_arr = json_decode($thread_user,true);
		if(isemptyArray($thread_user_arr)){//空数组
			return 0;
		}
		$type = arr_unbound_value($thread_user_arr,'type',2,'');
		$id = arr_unbound_value($thread_user_arr,'id',2,'');
		$ns_power_ower = arr_unbound_value($thread_user_arr,'power_ower',2,'');//用户权限变更时传值; 站点是站点siteurl; 部门是部门串-500-501-502-503;用户是用户最近的组织串或siteurl；
		$components = arr_unbound_value($thread_user_arr,'components',1,array());
		$oper_type = arr_unbound_value($thread_user_arr,'oper_type',2,'');
		$sys_obj = arr_unbound_value($thread_user_arr,'obj',1,array());
		if(bn_is_empty($type) || bn_is_empty($id)){//为空
			log_message('error', '  $type=' . $type . ' or  $id=' . $id . '   is empey.');
			return 0;
		}
		log_message('debug', '  $type=' . $type . ' or  $id=' . $id . ' .');
		if(isemptyArray($components)){//为空
			return 0;
		}
		//获得用户数组
		$CI->load->library('OrganizeLib','','OrganizeLib');
		$CI->load->library('StaffLib','','StaffLib');
		$user_arr = array();
		$user_components = array();//用户权限
		$site_components = array();//站点权限
		$operat_type = 8;//操作类型8 用户权限修改
		$power_ower = $id;////用户权限变更时传值; 站点是站点siteurl; 部门是部门串-500-501-502-503;用户是用户user_id；
		$other_arr = array(
            'not_stop_type' => '',//如果组织类型有变动,还可以获得的组织类型[多个用逗号分隔];为空则表代：不受限止
            'parent_type' => '',//父组织类型,可以为空，为空：可以进行获得下一级
		);
		switch ($type) {//1站点属性,2部门属性,3用户属性,4会议属性
			case 1: //1站点属性,
				//获得当前站点下及其下级组织的所有人员
				$user_arr = $CI->OrganizeLib->get_all_user_byorgid($id,$oper_type,'1,2',$other_arr);
				//$site_components = $components;//站点权限
				$user_components = $components;
				$operat_type = 10;//操作类型10 站点权限修改
				break;
			case 7:  //7生态企业权限
			case 2:  //2部门属性,
				//获得当前部门及其下下级部门的所有人员
				$ns_org_code = $id;//组织id串
				$ns_org_arr = explode('-',$ns_org_code);
				$last_org_id = 0;
				foreach ($ns_org_arr as  $o_k => $o_v){
					if(!bn_is_empty($o_v)){
						$last_org_id = $o_v;
					}
				}
				$ns_oper_type = '1,3,5';//
				if($type == 7){
					$ns_oper_type = '2,4';//
				}
				$user_arr = $CI->OrganizeLib->get_all_user_byorgid($last_org_id,$ns_oper_type,'1,2',$other_arr);
				$user_components = $components;//用户权限
				$operat_type = 9;//操作类型9部门权限修改
				break;
			case 3: //3用户属性,
				$user_arr[] = $CI->StaffLib->get_user_by_id($id);
				$user_components = $components;//用户权限
				$operat_type = 8;//操作类型8 用户权限修改
				break;
			case 4: //4会议属性
				break;
			case 5: //5用户调部门,权限变更-最新的是组织权限 ；
				//调用ums的根据用户IDs查询用户接口
				$ums_api_arr = $CI->API->UMS_Special_API($id,2);
				if(!api_operate_fail($ums_api_arr)){//成功
					$user_arr = arr_unbound_value($ums_api_arr,'data',1,array());
				}else{//失败
					return 0;
				}
				$user_components = $components;//用户权限
				$operat_type = 11;//操作类型11用户调部门权限变更-最新的是组织权限 ；
				break;
			case 6: //6用户调部门,权限变更-最新的是站点权限
				//调用ums的根据用户IDs查询用户接口
				$ums_api_arr = $CI->API->UMS_Special_API($id,2);
				if(!api_operate_fail($ums_api_arr)){//成功
					$user_arr = arr_unbound_value($ums_api_arr,'data',1,array());
				}else{//失败
					return 0;
				}
				$user_components = $components;//站点权限
				$operat_type = 12;//操作类型12用户调部门权限变更-最新的是站点权限
				break;
			default:
				break;
		}
		if(isemptyArray($user_arr)){//如果是空数组
			log_message('error', '  $user_arr=' . $user_arr . ' is empey.');
			return 1;
		}
		//组织用户数组
		/*
		 $user_ok_arr = array(
		 'id' => $aaa,//当前用户id
		 'organizationId' => $aaa,//当前组织id
		 'orgNodeCode' => $aaa,//当前组织串-500-501-502-503
		 'obj' => array(
		 'sys' => array(
		 'customerCode' => $aaa,//客户编码
		 'siteID' => $aaa,//站点id
		 'site_name' => $aaa,//站点名称
		 'accountId'=>$aaa,//分帐id ；注意：如果有用户，则是用户自己的
		 'siteURL' => $aaa,//地址
		 'contractId' => $aaa,//合同id
		 ),
		 )

		 );
		 *
		 */

		$user_ok_arr = array();//最终的用户信息数组
		foreach($user_arr as $k => $v){
			$user_id = arr_unbound_value($v,'id',2,'');//用户id
			if(!bn_is_empty($user_id)){//有数据
				$organizationId = arr_unbound_value($v,'organizationId',2,'');//组织id
				$orgNodeCode = arr_unbound_value($v,'nodeCode',2,'');//组织id串，权限变更时，可以为空，因为不用去获得组织权限，是直接给的
				$ns_user_arr = array(
                    'id' => $user_id,//当前用户id
                    'power_ower' => $ns_power_ower,//用户权限变更时传值; 站点是站点siteurl; 部门是部门串-500-501-502-503;用户是用户最近的组织串或siteurl；
                    'organizationId' => $organizationId,//当前组织id
                    'orgNodeCode' => $orgNodeCode,//当前组织串-500-501-502-503            
                    'obj' => $sys_obj,
				);
				$user_ok_arr[] = $ns_user_arr;
			}
		}
		$power_arr = array(//可以为空[权限变更时用]
            'user_components' => $user_components,//用户权限[用户/组织最新的权限]
            'site_components' => $site_components,//站点权限[站点最新的权限]
		);
		return $this->get_boss_json_new($user_ok_arr,$operat_type,$power_arr);//调用boss1 update 流程
	}
	/**
	 *
	 * @brief 根据线程的数据uc后台权限修改数组并调用BOSS做相关操作
	 * @details
	 *  @param array $thread_json 线程中保存的用户信息
	 $thread_arr = array(
	 'sys' => $sys_arr(
	 'customerCode' => $this->p_customer_code,//客户编码
	 'siteID' => $this->p_site_id,//站点id
	 'site_name' => $this->p_site_name,//站点名称
	 'accountId'=>$this->p_account_id,//分帐id ；注意：如果有用户，则是用户自己的
	 'siteURL' => $this->p_stie_domain,//地址
	 'contractId' => $this->p_contract_id,//合同id
	 'operator_id' => $this->p_user_id,//操作发起人用户ID
	 'client_ip' => $this->p_client_ip,//客户端ip
	 'server_ip' => $this->p_server_ip,//服务端ip
	 'oper_account' => $this->p_account,//操作帐号
	 'oper_display_name' => $this->p_display_name,//操作姓名
	 'orgID' => $this->p_org_id,//所属站点，分公司，生态企业组织id[各种管理员新加时，必填]
	 )
	 'org_arr' => $org_id_arr
	 array('14215','45683')
	 );
	 * @return int 0:失败；1：成功。2回调失败 ,
	 */
	public function delete_ecology($thread_json = ''){
		/*
		 log_message('info', 'into method ' . __FUNCTION__ . '.');
		 $CI =& get_instance();
		 $CI->load->helper('my_publicfun');
		 $CI->load->helper('my_dgmdate');
		 $CI->load->library('API','','API');
		 //0:失败；1：成功。2回调失败
		 //获得数据$thread_value
		 if( bn_is_empty($thread_json)){
		 return 0;
		 }
		 //转换为数组
		 $thread_arr = json_decode($thread_json,true);
		 if(isemptyArray($thread_arr)){//空数组
		 return 0;
		 }
		 $sys_arr = arr_unbound_value($thread_arr,'sys',1,array());
		 $org_arr = arr_unbound_value($thread_arr,'org_arr',1,array());
		 //遍历当前企业/分公司/生态企业下的所有需要删除的帐号及企业/分公司/生态企业[按组织删除或按公司/分公司/生态企业删除]
		 foreach($org_arr as $ns_org_id){
		 if($ns_org_id > 0){


		 }
		 }
		 */
		$e_id = json_decode($thread_json, true);
		log_message('info',"Delete ecology start,ecology id->{$e_id}");

		//删除该生态企业，调用ums接口
		list($rflag, $all_users) = $this->_delEcologyFromUms($e_id);
		if(!$rflag){
			log_message('error',$all_users);
			return false;
		}

		//boss端删除生态企业相关信息
		list($rflag, $msg) = $this->_delEcologyFromBoss($all_users);
		if(!$rflag){
			log_message('error',$msg);
			return false;
		}

		//本地删除生态企业相关信息
		list($rflag, $msg) = $this->_delEcologyFromLocation($e_id);
		if(!$rflag){
			log_message('error',$msg);
			return false;
		}
		log_message('info', 'Delete ecology finished!');

	}

	/**
	 * boss端删除生态企业相关信息
	 * @param int $e_id 生态企业id
	 */
	private function _delEcologyFromBoss($all_users){
		//异步批量删除该生态企业下的员工,调用boss接口
		$thread_value = array();
		foreach($all_user as $user){
			$tmp = array();
			$tmp['user_id'] = $user['id'];
			$tmp['operate_txt'] = 'delete';
			$tmp['user_operate'] = 4;
			/*
			 $tmp['sys'] = array(
				'customerCode'=,
				'siteID'=,
				'site_name'=,
				'accountId'=,
				'siteURL'=,
				'contractId'=,
				'operator_id'=,
				'client_ip'=,
				'oper_account'=,
				'oper_display_name'=,
				'orgID'=,
				);
				*/
			$thread_value[] = $tmp;
		}
		$thread_created = time();
		$thread_data = array('isvalid'=>1, 'type'=>4, 'value'=>json_encode($thread_value), 'created'=>$thread_created, 'modify'=>$thread_created);
		//将删除任务写入到线程表里
		$CI = & get_instance();
		$CI->load->model('API', '', 'api');
		$CI->api->UCAPI(json_encode($thread_data), 1);

		return array(true, array());
	}

	/**
	 * ums端删除生态企业相关信息
	 * @param int $e_id 生态企业id
	 */
	private function _delEcologyFromUms($e_id){
		$CI =& get_instance();
		$CI->load->library('UmsLib','','ums');

		$cur_eco  = $this->ums->getOrganizationBrief($e_id);//当前生态企业详情
		$sub_eco  = $this->ums->getOrganization($e_id, 'subtree', '2,4');//当前生态企业下面的子生态企业或生态部门
		$ecos      = array_merge($cur_e, $sub_e);
		//批量删除组织,并获取组织下所有的员工账号
		$users = array();
		foreach($ecos as $eco){
			$org_detail = $this->ums->getOrganizationDetail($eco['id']);//获取组织下所有的用户,从ums删除
			if(isset($org_detail['users'])){
				$users[] = $org_detail['users'];
				foreach($org_detail['users'] as $user){
					$this->ums->delUserById($user['id']);
				}
			}
			$rs = $this->ums->delOrganization($eco['id']);//删除组织
		}

		return array(true, $users);
	}

	/**
	 * 本地端删除生态企业相关信息
	 * @param int $e_id 生态企业id
	 */
	private function _delEcologyFromLocation($e_id){
		$CI =& get_instance();
		$CI->load->model('ecology_model', 'ecology');
		$rs = $CI->ecology->delEcology($e_id);
		if(!$rs){
			return array(false, 'delete ecology from location database failed');
		}
		return array(true, '');
	}

}
