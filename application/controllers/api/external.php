<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @class external
 * @brief external Controller，对外接口。
 * @details
 * 只作对域相关表的操作，不能调有其它接口
 * @file Response.php
 * @author zouyan <yan.zou@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
include_once APPPATH . 'libraries/ice/php3.4.2/Ice.php';//Ice php包
include_once APPPATH . 'libraries/ice/acm.php';//ice生成的文件
class external extends  Run_Controller{
	/**
	 * @brief 构造函数：
	 *
	 */
	public function __construct() {
		parent::__construct();
		$this->load->helper('my_publicfun');
		$this->load->helper('my_dgmdate');
		$this->load->library('API','','API');
		log_message('info', 'into class ' . __CLASS__ . '.');
	}
	/**
	 *
	 * @brief 设置主提人入会\退会，参数人入会\退会提示音接口[会用到ICE]
	 * @details
	 * @return string 成功失败
	 */
	public function set_meeting_cue() {
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		//1拿数据,验证
		$user_id = $this->input->post('user_id', TRUE);//当前用户id
		$pcode1intone = $this->input->post('pcode1intone', TRUE);//主持人进入会议的提示音：0 无提示音；1 提示蜂音；2语音报名。默认值1
		$pcode1outtone = $this->input->post('pcode1outtone', TRUE);//主持人退出会议的提示音：0 无提示音；1 提示蜂音；2语音报名。默认值1
		$pcode2intone = $this->input->post('pcode2intone', TRUE);//参与人进入会议的提示音：0 无提示音；1 提示蜂音；2语音报名。默认值1
		$pcode2outtone = $this->input->post('pcode2outtone', TRUE);//参与人退出会议的提示音：0 无提示音；1 提示蜂音；2语音报名。默认值1
		log_message('error', 'post $user_id= ' . $user_id . '  $pcode1intone= ' . $pcode1intone . ' $pcode1outtone= ' . $pcode1outtone . ' $pcode2intone= ' . $pcode2intone . ' $pcode2outtone= ' . $pcode2outtone . '  ');
		if( bn_is_empty($user_id) || bn_is_empty($pcode1intone) || bn_is_empty($pcode1outtone) || bn_is_empty($pcode2intone) || bn_is_empty($pcode2outtone)){
			log_message('error', 'post param  is empty.');
			form_json_msg('2','','post param  is empty');//返回错误信息json格式
		}
		if( (!preg_match('/^[1-9][0-9]*$/', $user_id)) || (!preg_match('/^[012]$/', $pcode1intone))  || (!preg_match('/^[012]$/', $pcode1outtone)) || (!preg_match('/^[012]$/', $pcode2intone)) || (!preg_match('/^[012]$/', $pcode2outtone))){
			log_message('error', 'post param  is error.');
			form_json_msg('3','','post param  is error');//返回错误信息json格式
		}
		//2uc拿相关信息
		$this->load->model('uc_user_model');
		$sel_field = 'Collect,billingcode,siteId,accountId';
		$where_arr = array(
                'userID' => $user_id, 
		//'site_id' => $site_id,
		);
		$sel_arr = $this->uc_user_model->get_db_arr($where_arr,$sel_field);
		if(isemptyArray($sel_arr)){//如果是空数组
			log_message('error', 'uc_user_model $user_id = ' .  $user_id . '  is empty.');
			form_json_msg('3','','uc_user_model $user_id = ' .  $user_id . '  is empty.');//返回错误信息json格式
		}
		log_message('debug', 'uc_user_model $user_id = ' . json_encode($sel_arr) . '  is ok.');
		$ns_Collect = arr_unbound_value($sel_arr,'Collect',2,'');
		$ns_billingcode = arr_unbound_value($sel_arr,'billingcode',2,'');
		$ns_siteId = arr_unbound_value($sel_arr,'siteId',2,'');
		$ns_accountId = arr_unbound_value($sel_arr,'accountId',2,'');
		if( bn_is_empty($ns_billingcode) || bn_is_empty($ns_siteId)   || bn_is_empty($ns_accountId) ){
			log_message('error', '   $ns_billingcode= ' . $ns_billingcode . '  $ns_siteId= ' . $ns_siteId . 'is empty.');
			form_json_msg('2','',' param  $ns_billingcode= ' . $ns_billingcode . '  $ns_siteId= ' . $ns_siteId . 'is empty.');//返回错误信息json格式
		}
		log_message('debug', '   $ns_billingcode= ' . $ns_billingcode . '  $ns_siteId= ' . $ns_siteId . '  $ns_accountId= ' . $ns_accountId . '  is ok.');
		$customerCode = '';//客户编码
		$this->load->model('uc_account_model');
		$sel_field = 'customercode';
		$where_arr = array(
                'id' => $ns_accountId,                        
		);
		$sel_arr = $this->uc_account_model->get_db_arr($where_arr,$sel_field);
		if(isemptyArray($sel_arr)){//如果是空数组
			log_message('error', 'uc_account_model $ns_accountId = ' .  $ns_accountId . '  is empty.');
			form_json_msg('3','','uc_account_model $ns_accountId = ' .  $ns_accountId . '  is empty.');//返回错误信息json格式
		}
		log_message('debug', 'uc_account_model $ns_accountId = ' . $ns_accountId . '  is ok.');
		$customerCode = arr_unbound_value($sel_arr,'customercode',2,'');
		if( bn_is_empty($customerCode)){
			log_message('error', ' $customerCode = ' .  $customerCode . '  is empty.');
			form_json_msg('3','',' $customerCode = ' .  $customerCode . '  is empty.');//返回错误信息json格式
		}
		log_message('debug', ' $customerCode = ' . $customerCode . '  is ok.');
		$clientInfo = array(
            'AcmClient.AppName' => ACM_APPNAME,//"UC", 
            'AcmClient.ApiLevel' => ACM_APILEVEL,//"1.0",                           
            'AcmClient.Version' => ACM_VERSION,//"1.0.1"                           
		);

		//$ssid = array();
		$billingCode = $ns_billingcode;//"44440833";
		$bridgeName = $ns_Collect;//"summit2";
		$msgMap	= array(
            'HostInTone'=>$pcode1intone,//'0',
            'HostOutTone'=>$pcode1outtone,//'0',
            'GuestInTone'=> $pcode2intone,//'1',
            'GuestOutTone'=> $pcode2outtone,//'1',
            'PNR' => '0',//先定为0 参会人进入会议时是否需要录制姓名：0不录制，1录制。默认值0；以后：如果只要前面四个参数中有一个是1，则PNR为1，看到时返回错还是继续
		);
		//$responseDetail = array();
		try{
			//3调acm
			$endpoints = ACM_LINK;//"tcp -h 192.168.61.7 -p 9012:tcp -h 192.168.61.7 -p 9012";
			$ic = Ice_initialize();
			$acmServerProxy = $ic->stringToProxy("AcmServer:" . $endpoints);
			$acmServer = acmmodule_AcmServerPrxHelper::uncheckedCast($acmServerProxy);
			$acmServer->register($clientInfo, $ssid);
			if(bn_is_empty($ssid)){//没有数据
				log_message('error', 'uc_user_model $ssid = ' .  $ssid . '  is empty.');
				form_json_msg('3','','uc_user_model $ssid = ' .  $ssid . '  is empty.');//返回错误信息json格式
			}
			log_message('debug', 'uc_user_model $ssid = ' .  $ssid . '  is ok.');
			//print_r($ssid);
			//echo '<br/><br/>';
			$confReserveProxy = $ic->stringToProxy("ConfReserve:" . $endpoints);
			$confReserve = acmmodule_ConfReservePrxHelper::uncheckedCast($confReserveProxy);
			$confReserve->updateConf($ssid, $billingCode, $bridgeName, $msgMap, $responseDetail);
			if(!bn_is_empty($responseDetail)){//$responseDetail有值则为出错了，没有值，则是代表成功
				log_message('error', 'uc_user_model $responseDetail = ' .  $responseDetail . '  is empty.');
				form_json_msg('4','','uc_user_model $responseDetail = ' .  $responseDetail . '  is empty.');//返回错误信息json格式
			}
			log_message('debug', 'uc_user_model $responseDetail = ' .  $responseDetail . '  is ok.');
			//print_r($responseDetail);

		} catch(Exception $ex){
			echo $ex;
		}
		if($ic){
			// Clean up?
			try	{
				$ic->destroy();
			}catch(Exception $ex){
				echo $ex;
			}
		}

		$this->load->library('AccountLib','','AccountLib');
		$this->load->library('PowerLib','','PowerLib');
		//给meet用的用户数组
		$meet_user_arr = array();
		$ns_meet_user_arr = array();
		$org_code = '';//用户所在组织串
		$site_name = '';//站点名称
		$ns_user_siteURL;//站点url
		 
		//调用ums接口获得用户详情
		$this->load->library('StaffLib','','StaffLib');
		//$re_user_arr = $this->StaffLib->get_user_by_id($user_id);
		//if(isemptyArray($re_user_arr)){//是空数组
		// log_message('error', 'ums api get user info $user_id = ' .  $user_id . '  is empty.');
		//form_json_msg('4','','ums api get user info $user_id = ' .  $user_id . '  is empty.');//返回错误信息json格式
		//}
		 
		//获得用户所在的组织
		$ne_org_arr = array();
		$siteURL = $user_id;//5782  ;// 当前用户id
		$get_org_arr = $this->API->UMS_Special_API('',15,array('url' => $siteURL));
		if(api_operate_fail($get_org_arr)){//失败;可能没有组织机构
			$err_msg = ' usm api rs/organizations/' . $siteURL . ' fail .';
			log_message('error', 'usm api rs/organizations/' . $siteURL . ' fail ');
			//form_json_msg('5','','usm api rs/organizations/' . $siteURL . ' fail ');//返回错误信息json格式
		}else{
			$ne_org_arr = arr_unbound_value($get_org_arr,'data',1,array());
			$err_msg = ' usm api rs/organizations/' . $siteURL . ' success .';
			log_message('debug', $err_msg);
		}
		if(!isemptyArray($ne_org_arr)){//不是空数组
			$org_code = arr_unbound_value($ne_org_arr[0],'nodeCode',2,'');
			//$customerCode = arr_unbound_value($ne_org_arr[0],'customercode',2,'');
		}
		//if( bn_is_empty($customerCode)){
		//  log_message('error', '   $customerCode= ' . $customerCode . 'is empty.');
		// form_json_msg('6','','   $customerCode= ' . $customerCode . 'is empty.');//返回错误信息json格式
		// }
		// log_message('debug', '   $customerCode= ' . $customerCode . 'is ok.');

		//根据customercode和站点id从uc_customer拿站点名称
		$this->load->model('uc_customer_model');
		$sel_field = 'name';
		$where_arr = array(
                'siteId' => $ns_siteId, 
                'customerCode' => $customerCode,                           
		);
		$sel_arr = $this->uc_customer_model->get_db_arr($where_arr,$sel_field);
		if(isemptyArray($sel_arr)){//如果是空数组
			log_message('error', 'uc_customer_model $ns_siteId = ' .  $ns_siteId . ' $customerCode = ' .  $customerCode . ' is empty.');
			form_json_msg('3','','uc_customer_model $ns_siteId = ' .  $ns_siteId . ' $customerCode = ' .  $customerCode . ' is empty.');//返回错误信息json格式
		}
		$site_name = arr_unbound_value($sel_arr,'name',2,''); //站点名称
		if( bn_is_empty($site_name)){
			log_message('error', '   $site_name= ' . $site_name . 'is empty.');
			form_json_msg('6','','   $site_name= ' . $site_name . 'is empty.');//返回错误信息json格式
		}
		log_message('debug', '   $site_name= ' . $site_name . 'is ok.');
		//获得站点url
		$this->load->model('uc_site_model');
		$sel_field = 'domain';
		$where_arr = array(
                'siteID' => $ns_siteId, 
                'customerCode' => $customerCode,                           
		);
		$sel_arr = $this->uc_site_model->get_db_arr($where_arr,$sel_field);
		if(isemptyArray($sel_arr)){//如果是空数组
			log_message('error', 'uc_site_model $ns_siteId = ' .  $ns_siteId . ' $customerCode = ' .  $customerCode . ' is empty.');
			form_json_msg('7','','uc_site_model $ns_siteId = ' .  $ns_siteId . ' $customerCode = ' .  $customerCode . ' is empty.');//返回错误信息json格式
		}
		$ns_user_siteURL = arr_unbound_value($sel_arr,'domain',2,''); //站点url
		if( bn_is_empty($ns_user_siteURL)){
			log_message('error', '   $ns_user_siteURL= ' . $ns_user_siteURL . 'is empty.');
			form_json_msg('6','','   $ns_user_siteURL= ' . $ns_user_siteURL . 'is empty.');//返回错误信息json格式
		}
		log_message('debug', '   $ns_user_siteURL= ' . $ns_user_siteURL . 'is ok.');
		$ns_meet_user_arr['id'] = $user_id;
		$ns_meet_user_arr['siteId'] = $ns_siteId;
		$ns_meet_user_arr['billingCode'] = $ns_billingcode;
		$ns_meet_user_arr['siteURL'] = $ns_user_siteURL;//站点url
		 
		//获得用户属性
		//获得用户开通属性,根据当前用户id,组织id串,[不用]站点id，去获得当前用户属性数组
		$user_components_arr = array();
		$in_arr = array(
            'userid' => $user_id,//用户id
            'org_code' => $org_code,//组织id串
            'siteid' =>$ns_siteId,//站点id
		);
		//是否向uc user开通属性表写数据
		//user 属性只看user 和 组织的,不用看站点
		$ns_components_arr = $this->PowerLib->get_components($in_arr);
		$from_num = 3;
		if(!isemptyArray($ns_components_arr)){//如果不是空数组
			$from_num = isset($ns_components_arr['from_num'])?$ns_components_arr['from_num']:0;//从哪里获得的 1用户2组织3站点
			$user_components_arr = isset($ns_components_arr['components'])?$ns_components_arr['components']:array();
			//if(!isemptyArray($user_components_arr)){//如果不是空数组
			//给每个user加属性components
			//$user_new_arr[$key]['components'] = $user_components_arr;
			//}
		}

		//用户属性如果有改动，则保存最新的
		$pop_is_change = 0;//属性是否有变动0没有变动，1有变动
		foreach ($user_components_arr as $k => $v ){
			if(is_array($v)){
				$ns_name = isset($v['name'])?$v['name']:'';
				$ns_name_lower = strtolower($ns_name);//转换为小写
				switch ($ns_name_lower) {
					case 'summit': //是summit的
						$old_pcode1intone = arr_unbound_value($v,'Pcode1InTone',2,'');//主持人进入会议的提示音：0 无提示音；1 提示蜂音；2语音报名。默认值1
						$old_pcode1outtone = arr_unbound_value($v,'Pcode1OutTone',2,'');//主持人退出会议的提示音：0 无提示音；1 提示蜂音；2语音报名。默认值1
						$old_pcode2intone = arr_unbound_value($v,'Pcode2InTone',2,'');//参与人进入会议的提示音：0 无提示音；1 提示蜂音；2语音报名。默认值1
						$old_pcode2outtone = arr_unbound_value($v,'Pcode2OutTone',2,'');//参与人退出会议的提示音：0 无提示音；1 提示蜂音；2语音报名。默认值1
						if( $old_pcode1intone != $pcode1intone || $old_pcode1outtone != $pcode1outtone || $old_pcode2intone != $pcode2intone || $old_pcode2outtone != $pcode2outtone){
							$pop_is_change = 1;//属性是否有变动0没有变动，1有变动
						}
						log_message('debug', ' old  $old_pcode1intone= ' . $old_pcode1intone . ' $old_pcode1outtone= ' . $old_pcode1outtone . ' $old_pcode2intone= ' . $old_pcode2intone . ' $old_pcode2outtone= ' . $old_pcode2outtone . '');
						$user_components_arr['summit']['Pcode1InTone'] = $pcode1intone;
						$user_components_arr['summit']['Pcode1OutTone'] = $pcode1outtone;
						$user_components_arr['summit']['Pcode2InTone'] = $pcode2intone;
						$user_components_arr['summit']['Pcode2OutTone'] = $pcode2outtone;
						break;
				}
			}
		}
		 
		$ns_meet_user_arr['components'] = $user_components_arr;
		$meet_user_arr[] = $ns_meet_user_arr;
		 
		//5boss

		//91
		//name summit
		$boss_arr = array(
            'name' => 'summit',
            'billingCode' => $ns_billingcode,
            'property' => array(
                'Pcode1InTone' => $pcode1intone,
                'Pcode1OutTone' => $pcode1outtone,
                'Pcode2InTone' => $pcode2intone,
                'Pcode2OutTone' => $pcode2outtone,
		)
		);
		$boss_user_arr = $this->API->BOSSAPI(json_encode($boss_arr),2);
		if(api_operate_fail($boss_user_arr)){//失败
			log_message('error', '   BOSS batch api ' . json_encode($boss_arr) . ' fail.');
			form_json_msg('6','','   BOSS batch api ' . json_encode($boss_arr) . ' fail');//返回错误信息json格式
		}
		log_message('DEBUG', '   BOSS batch api ' . json_encode($boss_arr) . ' success.');
		//6通知会议
		$Meet_arr = $this->PowerLib->get_meet_part($meet_user_arr,array('customerCode' =>$customerCode,'site_name'=>$site_name));
		//$hy_xml_data = $this->PowerLib->get_meet_xml($meet_user_arr,array('customerCode' =>$customerCode,'site_name'=>$site_name));
		// write_test_file( ' hy_xml_data ' . __FUNCTION__ . time() . '$hy_xml_data.txt' ,$hy_xml_data);
		//分发账户数据（增加、修改，启用都用此接口 批量）
		//$Meet_arr = $this->API->MeetAPI($hy_xml_data,1);
		//if(api_operate_fail($Meet_arr)){//失败
		if($Meet_arr == false){//失败
			log_message('error', '   get_meet_xml api fail.');
			form_json_msg('9','','   get_meet_xmlapi  fail');//返回错误信息json格式
		}
		log_message('DEBUG', '   get_meet_xml api success.');
		//4修改uc
		if($pop_is_change == 1){//属性是否有变动0没有变动，1有变动
			$this->load->model('uc_user_config_model');
			//有则修改，没有则新加
			//1、有记录则更新记录，没记录则新加；
			$select_field = 'id';
			$where_arr = array(
                 'userID' => $user_id, 
			);
			$modify_arr = array(
                 'userID' => $user_id,
                 'value' => json_encode($user_components_arr),
			);
			$insert_arr = $modify_arr;
			$insert_arr['createTime'] = dgmdate(time(), 'dt');
			$re_num = $this-> uc_user_config_model -> updata_or_insert(1,$select_field,$where_arr,$modify_arr,$insert_arr);
			if($re_num == -2 || $re_num == -4){//失败 -1 更新记录成功 -2更新操作失败 -3新加的记录id 新加记录成功 -4新加操作失败 -5 没有操作
				$err_msg = 'update/insert  uc_user_config_model fail. $re_num =' . $re_num . ' ';
				log_message('error', $err_msg);
				form_json_msg('10','',$err_msg);//返回错误信息json格式
			}
			log_message('info', 'update/insert  uc_user_config_model success. $re_num =' . $re_num . ' .');
		}
		//7返回
		form_json_msg('0','','api is success');//返回错误信息json格式

	}
}
