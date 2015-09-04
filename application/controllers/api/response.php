<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @abstract 	Response Controller，帐号开通相关接口。[属于其它不用登陆就可以运行的页面]
 * @filesource 	response.php
 * @author 		zouyan <yan.zou@quanshi.com>
 * @copyright 	Copyright (c) UC
 * @version 	v1.0
 */
class Response extends  Run_Controller{
	/**
	 * @abstract 构造函数
	 */
	public function __construct() {
		parent::__construct();
		$this->load->helper('my_publicfun');
		$this->load->helper('my_dgmdate');
		$this->load->library('API','','API');
		log_message('info', 'into class ' . __CLASS__ . '.');
	}
	/**
	 * @abstract 帐号操作异步回调需要做的事
	 * $flow_type
	 * 1新开通流程[全新开启]
	 * 2开启更新UPDATE流程[以前关闭，现在是开启]
	 * 3关闭流程[以前开启，现在半闭]
	 * 4删除流程[以前开启，现在删除]
	 * 5删除流程[以前关闭，但开启过，现在删除]
	 * 
	 * create： 新建账号
	 * update：修改账号
	 * disable：停用账号
	 * enable：启用账号
	 * delete： 删卡
	 */
	public function asynOpen(){
		// 获得类型
		$flow_type = $this->uri->segment(4);
		log_message('info', __FUNCTION__." input->\n".var_export(array('flow_type' => $flow_type), true));
		
		// 判断类型是否为空
		if(bn_is_empty($flow_type)){
			$err_msg = 'Get BOSS $flow_type fail.';
			log_message('error', $err_msg);
			echo api_json_msg(-1, array('msg' => $err_msg), 1);
			https(400);
			die();
		}
		log_message('info', 'get BOSS $flow_type ' . $flow_type . ' success.');
		
		// 获得BOSS post过来的数据
		$BOSS_post_json = api_get_post();
		log_message('info', 'get BOSS $BOSS_post_json= ' . $BOSS_post_json . ' success.');
		write_test_file( __FUNCTION__ . time() . '.txt' ,$BOSS_post_json);
		
		// 判断BOSS post过来的数据是否为空
		if(bn_is_empty($BOSS_post_json)){
			$err_msg = ' get BOSS post json fail.';
			log_message('error', $err_msg);
			echo api_json_msg(-1, array('msg' => $err_msg), 1);
			https(400);
			die();
		}
		log_message('info', 'get BOSS post json success.');
			
		// 将BOSS post过来的数据转化成数组
		$BOSS_post_arr = json_decode($BOSS_post_json, true);
		// 判断转化过来的数组是否为空
		if(isemptyArray($BOSS_post_arr)){
			$err_msg = ' BOSS post json to array fail.';
			log_message('error', $err_msg);
			echo api_json_msg(-1,array('msg' => $err_msg) , 1);
			https(400);
			die();
		}
		log_message('info', ' BOSS post json to array  success.');
		
		// requestId
		$requestId = isset($BOSS_post_arr['requestId'])?$BOSS_post_arr['requestId']:'' ;
		
		// 判断requestId是否为空
		if(bn_is_empty($requestId)){
			$err_msg = ' get post param requestId is empty .';
			log_message('error', $err_msg);
			echo api_json_msg(-1,array('msg' => $err_msg) , 1);
			https(400);
			die();
		}
		log_message('info', ' get post param requestId is ' . $requestId . '.');

		$requestType = isset($BOSS_post_arr['type'])?$BOSS_post_arr['type']:'' ;
		if(bn_is_empty($requestType)){//如果是空
			$err_msg = ' get post param Type is empty .';
			log_message('error', $err_msg);
			echo api_json_msg(-1,array('msg' => $err_msg) , 1);
			https(400);
			die();
		}
		log_message('info', ' get post param $requestType is ' . $requestType . '.');
		
		// 合同id
		$contractId = isset($BOSS_post_arr['contractId'])?$BOSS_post_arr['contractId']:'' ;
		if(bn_is_empty($contractId)){//如果是空
			$err_msg = ' get post param contractId is empty .';
			log_message('error', $err_msg);
			echo api_json_msg(-1,array('msg' => $err_msg) , 1);
			https(400);
			die();
		}
		log_message('info', ' get post param $contractId is ' . $contractId . '.');
		
		//获得成功的用户信息
		$user_ok_arr = isset($BOSS_post_arr['successed'])?$BOSS_post_arr['successed']:array() ;//处理完成的用户列表
		//获得失败的用户信息
		$user_fail_arr = isset($BOSS_post_arr['failed'])?$BOSS_post_arr['failed']:array() ;
		log_message('info', ' get post param successed and failed not is empty.');

		$this->load->model('UC_Request_Model');
		//判断站点表记录是否存在
		$sel_data = array(
            'select' =>'value',
            'where' => array(
                 'requestId' => $requestId                            
		)
		);
		$uc_db_user_arr = array();//uc库里有的用户
		$sel_arr =  $this->UC_Request_Model->operateDB(1,$sel_data);

		if(isemptyArray($sel_arr)){//没有记录
			$err_msg = ' UC_Request_Model not record .';
			log_message('error', $err_msg);
			echo api_json_msg(-1,array('msg' => $err_msg) , 1);
			https(400);
			die();
		}else{
			$ns_uc_db_user_json = isset($sel_arr['value'])?$sel_arr['value']:'';
			log_message('info', ' get UC_Request_Model $ns_uc_db_user_json= ' . $ns_uc_db_user_json . ' success.');
			if(!bn_is_empty($ns_uc_db_user_json)){//不为空
				$uc_db_user_arr = json_decode($ns_uc_db_user_json,true);
			}
		}
		log_message('info', ' get UC_Request_Model success.');
			
		//去掉成功里没有的用户
		foreach($user_ok_arr as $s_k => $s_v){
			$s_id = isset($s_v['id'])?$s_v['id']:'';
			$s_is_exit = 0;//当前记录是否存在0不存在1存在
			if(!bn_is_empty($s_id)){//有数据
				foreach($uc_db_user_arr as $a_k => $a_v){
					$a_id = isset($a_v['id'])?$a_v['id']:'';
					if(!bn_is_empty($a_id)){//有数据
						if($s_id == $a_id){//存在
							$s_is_exit = 1;//当前记录是否存在0不存在1存在
							break;
						}
					}
				}
			}
			if($s_is_exit == 0){//不存在,干掉
				unset($user_ok_arr[$s_k]);
			}
		}
			
		//去掉失败里没有的用户
		foreach($user_fail_arr as $f_k => $f_v){
			$f_id = isset($f_v['id'])?$f_v['id']:'';
			$f_is_exit = 0;//当前记录是否存在0不存在1存在
			if(!bn_is_empty($f_id)){//有数据
				foreach($uc_db_user_arr as $a_k => $a_v){
					$a_id = isset($a_v['id'])?$a_v['id']:'';
					if(!bn_is_empty($a_id)){//有数据
						if($f_id == $a_id){//存在
							$f_is_exit = 1;//当前记录是否存在0不存在1存在
							break;
						}
					}
				}
			}
			if($f_is_exit == 0){//不存在,干掉
				unset($user_fail_arr[$f_k]);
			}
		}

		//默认1新开通流程[全新开启]
		//如果回调失败，callback需要运行的代码
		$fail_run_num = '';//1关闭UMS创建用户产品接口 2会议服务禁用帐号接口[只调预约为2]3关闭uc_user4关闭uc_admin，多个用,号分隔
		switch ($flow_type) {
			case 6:  //TODO 6以前未开启过，现在开启
			case 1: //1新开通流程[全新开启]
				$fail_run_num = '1,2,3,4';
				//$UC_status = 1;
				//$Meet_type = 1;//1 分发账户数据（增加、修改都用此接口）
				break;
			case 7: //TODO 7 开启状态，修改
			case 8: //TODO 8 用户权限修改
			case 9: //TODO 9 部门权限修改
			case 10://10 站点权限修改
			case 11: // 11用户调部门权限变更-最新的是组织权限 ；
			case 12: ////12用户调部门权限变更-最新的是站点权限
				break;
			case 2: //2开启更新UPDATE流程 [以前关闭，现在是开启]
				$fail_run_num = '1,2,3,4';
				//$UC_status = 1;
				// $Meet_type = 1;//1 分发账户数据（增加、修改都用此接口）
				break;
			case 3: //3关闭流程[以前开启，现在半闭]
				$fail_run_num = '';//什么也不做
				// $UC_status = 0;
				// $Meet_type = 2;//2禁用帐号
				break;
			case 4: //4删除流程[以前开启，现在删除]
				$fail_run_num = '';//什么也不做
				//$UC_status = 2;
				//$Meet_type = 2;//2禁用帐号
				break;
			case 5: //5删除流程[以前关闭，但开启过，现在删除]
				$fail_run_num = '';//什么也不做
				//$UC_status = 2;
				//$Meet_type = 0;//0代表不调用
				break;
		}
		//成功需要处理
		$succ_run_num = '';//1入职消息2离职消息3员工权限变更4将user[禁用/删除（开通过）]改为开通5调用ums删除接口6各种管理员信息入库
		switch ($flow_type) {
			case 6:  //TODO 6以前未开启过，现在开启
				$succ_run_num = '1,4,6';
				break;
			case 1: //1新开通流程[全新开启]
				$succ_run_num = '1,6';
				break;
			case 2: //2开启更新UPDATE流程 [以前关闭，现在是开启]
			case 7: //TODO 7 开启状态，修改
			case 9: //TODO 9 部门权限修改
			case 10://10 站点权限修改
			case 11: // 11用户调部门权限变更-最新的是组织权限 ；
			case 12: ////12用户调部门权限变更-最新的是站点权限
				break;
			case 8: //TODO 8 用户权限修改
				//$succ_run_num = '3';
				break;
			case 3: //3关闭流程[以前开启，现在半闭]
				$succ_run_num = '2';
				break;
			case 4: //4删除流程[以前开启，现在删除][删除当前的逻辑就是禁用]
				$succ_run_num = '2,5';
				break;
			case 5: //5删除流程[以前关闭，但开启过，现在删除]
				break;
		}
		//回调接口，失败的不做处理,
		//，成功的才做处理[关闭ums服务、会议服务]
		foreach($user_fail_arr as $f_k => $f_v){
			$user_is_exist_indb = 0;//BOSS返回的用户是否在记录中有0没有1有
			$f_id = isset($f_v['id'])?$f_v['id']:'';
			//获得用户信息
			//$components = array();
			$obj_sys_arr = array();
			foreach($uc_db_user_arr as $k => $v){
				if(is_array($v)){//是数组
					$ns_user_id = isset($v['id'])?$v['id']:0;//用户id
					if($f_id == $ns_user_id){//相等
						$user_is_exist_indb = 1;//BOSS返回的用户是否在记录中有0没有1有
						//$components = isset($v['components'])?$v['components']:array();
						$obj_sys_arr = isset($v['obj']['sys'])?$v['obj']['sys']:array();
						break;
					}
				}
			}
			if($user_is_exist_indb == 1){//BOSS返回的用户是否在记录中有0没有1有
				$customerCode = isset($obj_sys_arr['customerCode'])?$obj_sys_arr['customerCode']:'';
				$siteID = isset($obj_sys_arr['siteID'])?$obj_sys_arr['siteID']:'';
				$accountId = isset($obj_sys_arr['accountId'])?$obj_sys_arr['accountId']:'';
				$siteURL = isset($obj_sys_arr['siteURL'])?$obj_sys_arr['siteURL']:'';
				$contractId = isset($obj_sys_arr['contractId'])?$obj_sys_arr['contractId']:'';
				if ( strstr(',' . $fail_run_num . ',', ',1,')){//1关闭UMS创建用户产品接口
					//调用UMS创建用户产品接口
					$data_ums ='productId=' . UC_PRODUCT_ID . '&userStatus=0&sitesId=' . $siteID . '&userId=' . $f_id . '';
					$ums_arr = $this->API->UMS_Special_API('',4,array('url'=>$data_ums ));
					if(api_operate_fail($ums_arr)){//失败
						$err_msg = 'ums api /rs/users/setUserProduct fail .';
						log_message('error', $err_msg);
						echo api_json_msg(-1,array('msg' => $err_msg) , 1);
						https(400);
						die();
					}else{
						log_message('debug', 'ums apiapi /rs/users/setUserProduct success.');
					}
				}
				if ( strstr(',' . $fail_run_num . ',', ',2,')){//2会议服务禁用帐号接口[只调预约为2]
					//会议服务禁用帐号接口[只调预约为2]
					/*
					 $hy_data = '/2/' . $f_id;
					 $Meet_arr = $this->API->MeetAPI($hy_data,2);
					 if(api_operate_fail($Meet_arr)){//失败
					 $err_msg = ' meet api rs/web/disableUser fail .';
					 log_message('error', $err_msg);
					 echo api_json_msg(-1,array('msg' => $err_msg) , 1);
					 // https(400); //TODO 暂时关闭，以后打开
					 // die(); //TODO 暂时关闭，以后打开
					 }else{//成功
					 log_message('debug', 'meet apiapi rs/web/disableUser success.');
					 }
					 *
					 */
					$meet_type = 3;//1 离职 2 调岗 3 禁用
					$meet_array = array(
                       'appId' => 2,
                       'userIds' => $f_id,//id，多个用;号分隔
                       'type' => $meet_type 
					);
					$meet_data = json_encode($meet_array);
					$Meet_arr = $this->API->MeetAPI($meet_data,3);
					if(api_operate_fail($Meet_arr)){//失败
						$err_msg = 'MeetAPI rs/conference/accountChange ' . $meet_data . json_encode($Meet_arr) . ' fail.';
						log_message('error', $err_msg);
					}else{
						log_message('info', 'MeetAPI rs/conference/accountChange ' . $meet_data . json_encode($Meet_arr) . ' success.');
					}
				}
				if ( strstr(',' . $fail_run_num . ',', ',3,')){//3关闭uc_user
					//修改失败用户状态
					$this->load->model('UC_User_Model');
					$close_user_ok = $this->UC_User_Model->close_user_state($f_id);
					if(!$close_user_ok){//失败
						$err_msg = ' close user ' . $f_id . ' fail .';
						log_message('error', $err_msg);
						echo api_json_msg(-1,array('msg' => $err_msg) , 1);
						https(400);
						die();
					}else{//成功
						log_message('debug', '  close user ' . $f_id . ' success.');
					}
				}
				if ( strstr(',' . $fail_run_num . ',', ',4,')){//4关闭uc_admin
					$this->load->model('UC_User_Admin_Model');
					//修改失败用户状态
					$close_user_admin_ok = $this->UC_User_Admin_Model->update_user_admin_state($f_id,0);
					if(!$close_user_admin_ok){//失败
						$err_msg = ' close user_admin ' . $f_id . ' fail .';
						log_message('error', $err_msg);
						echo api_json_msg(-1,array('msg' => $err_msg) , 1);
						https(400);
						die();
					}else{//成功
						log_message('debug', '  close user_admin ' . $f_id . ' success.');
					}
				}
			}
		}
		$this->load->library('PowerLib','','PowerLib');
		$this->load->library('Informationlib','','Informationlib');
		$this->load->library('StaffLib','','StaffLib');
		$this->load->model('uc_user_model');
		$succ_lxr_arr = array();//帐号开通成功后，需要调用组织机构联系人关系建立 array('org_id'=>array(userid))
		foreach($user_ok_arr as $f_k => $f_v){
			$user_is_exist_indb = 0;//BOSS返回的用户是否在记录中有0没有1有
			$f_id = isset($f_v['id'])?$f_v['id']:'';
			//获得用户信息
			//$components = array();
			$obj_sys_arr = array();
			foreach($uc_db_user_arr as $k => $v){
				if(is_array($v)){//是数组
					$ns_user_id = isset($v['id'])?$v['id']:0;//用户id
					if($f_id == $ns_user_id){//相等
						$user_is_exist_indb = 1;//BOSS返回的用户是否在记录中有0没有1有
						// $components = isset($v['components'])?$v['components']:array();
						$obj_sys_arr = isset($v['obj']['sys'])?$v['obj']['sys']:array();
						switch ($flow_type) {
							case 6:  //TODO 6以前未开启过，现在开启
							case 1: //1新开通流程[全新开启]
							case 2: //2开启更新UPDATE流程 [以前关闭，现在是开启]
								$ns_org_id = isset($v['organizationId'])?$v['organizationId']:0;//用户所在组织id
								log_message('debug', '  $ns_org_id=' . $ns_org_id . ' ');
								$succ_lxr_arr[$ns_org_id][] = $ns_user_id;
								break;
							case 7: //TODO 7 开启状态，修改
							case 9: //TODO 9 部门权限修改
							case 10://10 站点权限修改
							case 11: // 11用户调部门权限变更-最新的是组织权限 ；
							case 12: ////12用户调部门权限变更-最新的是站点权限
							case 8: //TODO 8 用户权限修改
							case 3: //3关闭流程[以前开启，现在半闭]
							case 4: //4删除流程[以前开启，现在删除][删除当前的逻辑就是禁用]
							case 5: //5删除流程[以前关闭，但开启过，现在删除]
								break;
						}
						break;
							
					}
				}
			}

			if($user_is_exist_indb == 1){//BOSS返回的用户是否在记录中有0没有1有
				$customerCode = isset($obj_sys_arr['customerCode'])?$obj_sys_arr['customerCode']:'';
				$siteID = isset($obj_sys_arr['siteID'])?$obj_sys_arr['siteID']:'';
				$accountId = isset($obj_sys_arr['accountId'])?$obj_sys_arr['accountId']:'';
				$siteURL = isset($obj_sys_arr['siteURL'])?$obj_sys_arr['siteURL']:'';
				$contractId = isset($obj_sys_arr['contractId'])?$obj_sys_arr['contractId']:'';
				$operator_id = isset($obj_sys_arr['operator_id'])?$obj_sys_arr['operator_id']:'';

				$user_type = isset($obj_sys_arr['user_type'])?$obj_sys_arr['user_type']:0;//;多个用,号分隔,0普通用户1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员[各种管理员新加时，必填]7生态企业普通用户
				$user_source = isset($obj_sys_arr['user_source'])?$obj_sys_arr['user_source']:0;//帐号开通来源,多个用，号分隔 1批量导入2组织管理员-单个新加3生态渠道管理员新加4任务新加
				$orgID = isset($obj_sys_arr['orgID'])?$obj_sys_arr['orgID']:0;//所属站点，分公司，生态企业组织id[各种管理员新加时，必填]
				$isLDAP = isset($obj_sys_arr['isLDAP'])?$obj_sys_arr['isLDAP']:0;//帐号导入类型[各种管理员新加时，必填]
				if ( strstr(',' . $succ_run_num . ',', ',1,') || strstr(',' . $succ_run_num . ',', ',2,') ){//1入职消息2离职消息
					//获得用户数组信息
					$ns_user_arr = $this->StaffLib->get_user_by_id($f_id);
					$ns_user_name = arr_unbound_value($ns_user_arr,'displayName',2,'');
					$ns_user_position = arr_unbound_value($ns_user_arr,'position',2,'');
					//获得用户所在的组织信息
					$ns_user_org_arr = $this->StaffLib->get_user_org_arr($f_id,1);
					$ns_user_org_id = arr_unbound_value($ns_user_org_arr,'id',2,'');
					$ns_user_org_name = arr_unbound_value($ns_user_org_arr,'name',2,'');

				}
				log_message('debug', '  $user_source=' . $user_source . ' ');
				if ( strstr(',' . $user_source . ',', ',4,')){//4任务新加,发送完成任务消息
					//发送组织消息
					$this->load->library('Informationlib','','Informationlib');
					$msg_arr = array(
                         'user_id' => $f_id,//用户id
					);
					$this->Informationlib->send_ing($sys_arr,array('msg_id' => 11,'msg_arr' => $msg_arr));
				}
				if ( strstr(',' . $succ_run_num . ',', ',1,')){//1入职消息
					//发送消息
					$info_pre_arr = array(
                        'from_user_id' => $operator_id,//消息发送者用户id
                        'from_site_id' => $siteID,//消息发送者站点id
                        'to_user_id' => $ns_user_org_id,//消息接受者id[是组织时为组织id,是用户时，是用户id]
                        'to_site_id' => $siteID,//消息接受者站点id
                        'is_group' => 1,//是否为讨论组聊天1是[是组织] 0 否[是单个用户]   
                        'msg_type' => 1,//消息类型  1 - 组织变动
                        'msg_id' => 4,//1 - 部门名称变更    2 - 员工部门调动    3 - 职位调整  4 -员工入职 5 - 员工离职   10 - 部门删除  11 - 员工入职确认 12 - 员工离职确认 13 - 员工部门调动确认 14 - 员工入职拒绝消息 15 - 员工离职拒绝消息 16 - 员工部门调动拒绝消息
						'content_type' => 2,//消息体类型           1 - mime  2 - 二进制 
					);
					$info_body = array(
                        'operator_id' => $operator_id,//操作发起人用户ID
                        'user_id' => $f_id,//员工用户ID
                        'dept_id' => $ns_user_org_id ,//入职部门ID
                        'user_name' => $ns_user_name,//员工姓名
                        'dept_name' => $ns_user_org_name,//员工部门名称
                        'position' => $ns_user_position,//员工职位名称
                        'desc' => '',//消息描述
					);
					$this->Informationlib->send_info($info_pre_arr,$info_body);
					log_message('info', 'send msg orgchange userid = ' . $ns_user_id . '.');
				}
				if ( strstr(',' . $succ_run_num . ',', ',2,')){//2离职消息
					//发送离职消息
					$del_user_arr = array(
                        'user_id' => $f_id,//删除帐号id
                        'sys' => $obj_sys_arr
					);
					$this->StaffLib->send_del_info(array($del_user_arr));
				}
				if ( strstr(',' . $succ_run_num . ',', ',3,')){//3员工权限变更
					//                    //修改用户权限
					//                    $comp_in_arr = array(
					//                       'type' => 3,//类型1站点权限2部门权限3用户权限
					//                       'id' => $f_id,//保存对应的id 站点是site_id; 部门是部门串-500-501-502-503;用户是用户user_id
					//                       'site_id' => $siteID,//站点id;3用户权限时可以没有值
					//                       'customerCode' => $customerCode,//客户编码;2部门权限3用户权限时可以没有值
					//                    );
					//                    $re_boolean = $this->PowerLib->save_components($comp_in_arr,$components);//保存用户属性
					//                    if($re_boolean){//成功
					//                        log_message('info', ' save_components type =' . $comp_type . ' $ns_user_id =' . $ns_user_id . '$siteID =' . $siteID . '  $customerCode =' . $customerCode . ' success.');
					//                    }else{
					//                        log_message('error', ' save_components type =' . $comp_type . ' $ns_user_id =' . $ns_user_id . '$siteID =' . $siteID . '  $customerCode =' . $customerCode . ' fail.');
					//                        // https(400);
					//                        // die();
					//                    }
				}
				if ( strstr(',' . $succ_run_num . ',', ',4,')){//4将[禁用/删除（开通过）]改为开通

					$update_data = array(
                        'update_data' => array('status' => 1),
                        'where' => array('userID =' => $f_id,'status =' => 2),
					);
					$user_arr = $this->uc_user_model->operateDB(5,$update_data);
					if(!db_operate_fail($user_arr)){//成功
						log_message('debug', ' update uc_user_model ' . json_encode($update_data) . '  is seccuss');
					}else {
						log_message('error', ' update uc_user_model ' . json_encode($update_data) . 'is fail');
					}
				}
				if ( strstr(',' . $succ_run_num . ',', ',5,')){//5调用ums删除接口
					$api_data = '';//参数
					$ums_arr = $this->API->UMS_Special_API($api_data,18,array('url' => $f_id));//8删除用户
					if(api_operate_fail($ums_arr)){//失败
						log_message('error', 'UMS API rs/users/' . $f_id . '/delete  fail.');
					}else{
						log_message('debug', 'UMS API rs/users/' . $f_id . '/delete success.');
					}
				}

				if ( strstr(',' . $succ_run_num . ',', ',6,')){//6各种管理员信息入库
					$user_other_arr = array(
                        'orgID' => $orgID,
                        'isLDAP' => $isLDAP, 
                        'siteID' => $siteID,
					);
					$user_type_arr = explode(',', $user_type);//0普通用户1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员7生态企业普通用户
					foreach($user_type_arr as $ns_admin_k =>$ns_admin_v){
						if($ns_admin_v != 0){
							$ok_arr = array(
                                 'user_id' => $f_id,
                                 'super_admin_id' => $operator_id,
                                 'role_id' => $ns_admin_v,//角色1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员
                                 'state' => 1 ,//0：停用；1：启用
                                 'other' => $user_other_arr
							);
							$re_boolean = $this->StaffLib->add_ecology_onemanger($ok_arr);
							if($re_boolean != true){
								// return false;
							}
						}
					}
				}
			}
		}
		// $succ_lxr_arr = array();//帐号开通成功后，需要调用组织机构联系人关系建立 array('org_id'=>array(userid))
		$this->load->library('OrganizeLib','','OrganizeLib');
		$this->load->library('API','','API');
		log_message('debug', '  $succ_lxr_arr=' . any_to_str($succ_lxr_arr) . ' ');
		foreach($succ_lxr_arr as $u_org => $u_user){
			if($u_org > 0){
				//根据组织id，获得当前组织下的所有人员
				$ns_user_arr = $this->OrganizeLib->get_users_arr_by_orgid($u_org);
				$ns_userid_arr = array();
				foreach($ns_user_arr as $u_arr ){
					$u_id = arr_unbound_value($u_arr,'id',2,0);
					if($u_id > 0){
						$ns_userid_arr[] = $u_id;
					}
				}
				log_message('debug', '  $ns_userid_arr=' . any_to_str($ns_userid_arr) . ' ');
				if(!isemptyArray($ns_userid_arr)){//如果不是空数组
					//$ns_userids_json = json_encode($ns_userid_arr);
					//  $data = 'user_ids=' . $ns_userids_json . '&contact_ids=' . $ns_userids_json  . '&type=1';
					//调用组织机构联系人关系建立接口
					// $this->API->UCCServerAPI($data,20);
				}
			}
		}
		//       if(1>2){
		//        switch ($flow_type) {
		//            case 8: //TODO 8 用户权限修改
		//                //更新权限
		//                foreach ($uc_db_user_arr as $k => $v){
		//                   $comp_type = arr_unbound_value($v,'comp_type',2,'');//用户权限类型1站点权限2部门权限3用户自己的权限
		//                   $comp_org_code = arr_unbound_value($v,'comp_org_code',2,'');//权限组织串[如果是组织权限时]
		//                   $user_id = arr_unbound_value($v,'id',2,''); //用户id
		//                   $components_arr = arr_unbound_value($v,'components',1, array()); //最新的权限串
		//                   $obj_arr = isset($v['obj']['sys'])?$v['obj']['sys']:array();
		//                   $customerCode = isset($obj_arr['customerCode'])?$obj_arr['customerCode']:'';//客户编码
		//                   $siteID = isset($obj_arr['siteID'])?$obj_arr['siteID']:0;//站点id
		//                    if(isemptyArray($components_arr)){
		//                        $ns_user_id = $user_id;
		//                        switch ($comp_type) {
		//                           case 1: //1站点权限
		//                               $ns_user_id = $siteID;
		//                               break;
		//                           case 2: //2部门权限
		//                               $ns_user_id = $comp_org_code;
		//                               break;
		//                           case 2: //3用户自己的权限
		//                               $ns_user_id = $user_id;
		//                               break;
		//                       }
		//                       $comp_in_arr = array(
		//                          'type' => $comp_type,//类型1站点权限2部门权限3用户权限
		//                          'id' => $ns_user_id,//保存对应的id 站点是site_id; 部门是部门串-500-501-502-503;用户是用户user_id
		//                          'site_id' => $siteID,//站点id;3用户权限时可以没有值
		//                          'customerCode' => $customerCode,//客户编码;2部门权限3用户权限时可以没有值
		//                       );
		//                       $re_boolean = $this->PowerLib->save_components($comp_in_arr,$components_arr);//保存用户属性
		//                       if($re_boolean){//成功
		//                           log_message('info', ' save_components type =' . $comp_type . ' $ns_user_id =' . $ns_user_id . '$siteID =' . $siteID . '  $customerCode =' . $customerCode . ' success.');
		//                       }else{
		//                           log_message('error', ' save_components type =' . $comp_type . ' $ns_user_id =' . $ns_user_id . '$siteID =' . $siteID . '  $customerCode =' . $customerCode . ' fail.');
		//                            https(400);
		//                            die();
		//                       }
		//                    }
		//                }
		//                 break;
		//            case 9: //TODO 9 部门权限修改
		//            case 10:// 10站点权限修改
		//
		//        }
		//       }
		log_message('info', ' function  success.');
		//回调成功//TODO 暂时关闭，以后打开
		log_message('info', 'out method ' . __FUNCTION__ . '.');
	}
}
