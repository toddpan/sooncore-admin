<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 账号、合同开通、以及创建管理员操作接口
 * @file AccountProcessInterface.php
 * @author caohongliang <hongliang.cao@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */

abstract class AccountProcessInterface{
	
	public function __construct(){
		$this->ci = & get_instance();
		$this->ci->load->model('account_model', 'account');
		$this->ci->load->model('email_model', 'email');
		$this->ci->load->library('BossLib', '', 'boss');
		$this->ci->load->library('UccLib', '', 'ucc');
		$this->ci->load->library('MeetingLib', '', 'meeting');
		$this->ci->load->library('UmsLib', '', 'ums');
	}
	
	/**
	 * 针对不同的操作类型，进行处理
	 * @param int $value 操作类型
	 */
	abstract public function process($value);
	
	
	/**
	 * 从组建中获取属性
	 * @param array   $components 			所有的组件
	 * @param string  $component_name 		组件名称
	 * @param mix $keys         			要查找的key
	 */
	protected function _search_components($components,$component_name, $keys){
		
		if(!is_array($keys)){
			$keys = array($keys);
		}
		
		//获取组件
		$uc_component = array();
		foreach($components as $component){
			if(strtolower($component['name']) == $component_name){
				$uc_component = $component;
				break;
			}
		}
		
		//从uc组件里获取需要的值
		$ret_array    = array();
		foreach($keys as $key){
			$ret_array[$key] = isset($uc_component['property'][$key]) ? $uc_component['property'][$key] : '';
		}
		
		return $ret_array;
	}
	
	/**
	 * 根据站点url，从ums获取站点id和组织id
	 * @param unknown $site_url
	 */
	protected function _getSiteIdFromUms($site_url){
		$this->ci->load->library('UmsLib', '', 'ums');
		$site_info = $this->ci->ums->getSiteInfoByUrl($site_url);
		return $site_info ? $site_info['id'] : '';
	}
	
	
	/**
	 * 组装会议接口数据，xml格式
	 * @param array $meeting_users 用户信息
	 * @param int   $user_type	   操作类型 1-新增或修改 2-启用
	 * @return mix
	 */
	protected function _getMeetingXMLData($meeting_user, $user_type=1){
		//数据初始化
		$meeting_data = array();
		$meeting_data['userType'] = $user_type;//操作类型 1-新增或修改 2-启用
		$meeting_data['user']	  = array();
	
		//获取组件数据
		//--获取合同模板和管理员员模板合并后的站点模板属性
		$site_value 				= json_decode($this->ci->account->getSitePower($meeting_user['siteId']), true);
		if(is_null($site_value)){
			return array(false, 'Get site power from local db failed');
		}
	
		//--获取uc组件属性
		$uc_prop = array(
				'incomingLocal', 		//是否显示本地接入号(0:否 1:是)
				'incoming400', 			//是否显示国内400接入号(0:否 1:是)
				'incoming800', 			//是否显示国内800接入号(0:否 1:是)
				'incomingTw', 			//是否显示台湾接入号(0:否 1:是)
				'incomingHk', 			//是否显示香港接入号(0:否 1:是)
				'incomingInter', 		//是否显示国际Toll Free接入号(0:否 1:是)
				'attendeeSurvey',		//参会人开完会后是否弹出调研页(0:否 1:是)
				'incomingLocalToll',	//是否显示国际Local Toll接入号(0:否 1:是)
				'appNewAccount',		//调研页可显示”申请新账号”功能模块(0:否 1:是)
				'enableVoip',			//是否可以使用VOIP
				'allowUserVoice'		//是否允许用户使用语音(0:电话和VOIP 1:电话 2:VOIP)
		);
		$uc_comp = $this->_search_components($site_value, 'uc', $uc_prop);
	
		//--获取tang组件属性
		$tang_prop = array(
				'confscale',		//会议方数
				'pin',				//是否是固定pin码会议，如果是，则只有已经调用过addParticepant接口的pin码才能入会,true：是；false：否
				'roles',			//主持人所拥有的角色列表，其中4表示主持人，5标识主讲人，6标识参会人，多个角色使用逗号分隔
				'stopwhenoneuser',	//只有主持人时是否终止会议(0:否 1:是)
				'time1',			//主持人离线后多长时间结束会议，单位为分钟
				'time2',			//只有主持人时过多长时间结束会议，单位为分钟
				'realreserve',		//是否立即预约电话会议(0:否 1:是)
		);
		$tang_comp	   = $this->_search_components($site_value, 'tang', $tang_prop);
	
		//--获取radisys组件属性
		$radisys_prop = array(
				'InOutTone',		//主持人、参会人加入退出会议语音提示
				'GuestMode',		//参与人加入电话会议时的初始状态
				'Taped',			//是否开启电话录音功能
				'PNR',				//是否录制参会人姓名
				'BridgeName',
		);
		$radisys_comp  = $this->_search_components($site_value, 'radisys', $radisys_prop);
	
		//--获取summit组件属性
		$summit_prop	= 	array(
				'ConfDnisAccess',
				'HostStraightToConference',				//主持人加入电话会议的初始状态
				'Pcode1InTone',							//主持人加入电话会议的语音提示加入会议
				'Pcode1OutTone',						//主持人退出电话会议的语音提示退出会议
				'Pcode2Mode',							//参与人加入电话会议时的初始状态
				'Pcode2InTone',							//参与人加入电话会议的语音提示加入会议
				'Pcode2OutTone',						//参与人退出电话会议的语音提示退出会议
				'ValidationCount',						//议参与人加入电话会议时，是否通知其会议中的参与方数
				'FirstCallerMsg',						//第一方与会者是否需要听到“您是第一个入会者”的提示
				'Taped',								//是否开启电话录音功能
				'ParticipantNameRecordAndPlayback',		//是否录制参会人姓名
				'Collect',
				'ConfQuickStart',
		);
		$summit_comp	= 	$this->_search_components($site_value, 'summit', $summit_prop);
	
		//组装数据
		$tmp = array();
		$tmp['applicationId']		=		'UC';
		$tmp['userId'] 				= 		$meeting_user['id'];
		$tmp['billingCode']			=		$meeting_user['billingCode'];
		$tmp['customerCode']		=		$this->uc['customer_code'];
		$tmp['siteId']				=		$meeting_user['siteId'];
		$tmp['siteName']			=       $meeting_user['resource']['siteURL'];
		$tmp['pcode1']				=       $meeting_user['resource']['hostPassword'];
		$tmp['pcode2']				=       $meeting_user['resource']['guestPassword'];
		$tmp['customerName']		=       $this->uc['customer_name'];
		$tmp['newAccount']			=       0; //?
		$tmp['newAccountForPaid']	=       0; //同一帐号会议冲突时，新建帐号功能(0)
	
		/**
		 * 由submit组件的Collect和radisys的bridgeName属性组成
		 * 如果两个平台都有，则参数间以逗号分隔
		 */
		$radisys_bridge = $this->_search_components($site_value, 'radisys', 'BridgeName');
		$summit_bridge  = $this->_search_components($site_value, 'summit', 'Collect');
		$tmp['BridgeName']					= 		trim($radisys_comp['BridgeName'].','.$summit_comp['Collect'], ',');
		$this->uc['collect']				=		$tmp['BridgeName'];
	
		/**
		 * 以下属性
		 * 如果uc组件中有该属性则使用
		 * 如果没有，则去summit组件中去找，如果有则将这个值置为1
		 * 如果uc和summit都没有，则将这个值置为0
		*/
		$summit_ConfDnisAccess 				=	explode(',', $summit_comp['ConfDnisAccess']);
		$tmp['localAccess']					=	$uc_comp['incomingLocal'] 		? 	$uc_comp['incomingLocal'] : ( in_array('1', $summit_ConfDnisAccess) ? 1 : 0 );
		$tmp['access400']					=	$uc_comp['incoming400']   		? 	$uc_comp['incoming400']   : ( in_array('2', $summit_ConfDnisAccess) ? 1 : 0 );
		$tmp['access800']					=	$uc_comp['incoming800'] 		? 	$uc_comp['incoming800']   : ( in_array('3', $summit_ConfDnisAccess) ? 1 : 0 );
		$tmp['TaiwanAccess']				=	$uc_comp['incomingTw'] 			? 	$uc_comp['incomingTw']    : 0;//暂无，直接置0
		$tmp['hongKongAccess']				=	$uc_comp['incomingHk'] 			? 	$uc_comp['incomingHk']    : ( in_array('7', $summit_ConfDnisAccess) ? 1 : 0 );
		$tmp['tollFree']					=	$uc_comp['incomingInter'] 		? 	$uc_comp['incomingInter'] : ( in_array('5', $summit_ConfDnisAccess) ? 1 : 0 );
		$tmp['localToll']					=	$uc_comp['incomingLocalToll'] 	? 	$uc_comp['incomingLocalToll']   : ( in_array('4', $summit_ConfDnisAccess) ? 1 : 0);
	
	
		$tmp['allowHostCall']				=   1;//暂无写1
		$tmp['allowAttendeeCall']			=   1;//暂无写1
		$tmp['attendeeSurvey']				=   $uc_comp['attendeeSurvey'];
		$tmp['appNewAccount']				=   $uc_comp['appNewAccount'];
		$tmp['conferenceScale']				=   $tang_comp['confscale'];
		$tmp['allowUserVoice']				=   $uc_comp['allowUserVoice'];
		$tmp['enableVoip']					=   $uc_comp['enableVoip'];
		$tmp['pin']							=   $tang_comp['pin'];
		$tmp['roles']						=   $tang_comp['roles'];
		$tmp['stopwhenoneuser']				=   $tang_comp['stopwhenoneuser'];
		$tmp['time1']						=   $tang_comp['time1'];
		$tmp['time2']						=   $tang_comp['time2'];
		$tmp['realreserve']					=   $tang_comp['realreserve'];
		$tmp['hostInitialStatus']			=   $summit_comp['HostStraightToConference'] 	? $summit_comp['HostStraightToConference'] : 1;
		$tmp['hostVoicePrompts']			=   $summit_comp['Pcode1InTone'] 				? $summit_comp['Pcode1InTone'] : $radisys_comp['InOutTone'];
		$tmp['hostExitVoicePrompts']		=   $summit_comp['Pcode1OutTone'] 				? $summit_comp['Pcode1OutTone'] : $radisys_comp['InOutTone'];
		$tmp['attendeeInitialStatus']		=   $summit_comp['Pcode2Mode'] 					? $summit_comp['Pcode2Mode'] : $radisys_comp['GuestMode'];
		$tmp['attendeeVoicePrompts']		=   $summit_comp['Pcode2InTone'] 				? $summit_comp['Pcode2InTone'] : $radisys_comp['InOutTone'];
		$tmp['attendeeExitVoicePrompts']	=   $summit_comp['Pcode2OutTone'] 				? $summit_comp['Pcode2OutTone'] : $radisys_comp['InOutTone'];
		$tmp['attendeeNoticeOthers']		=   $summit_comp['ValidationCount'] 			? $summit_comp['ValidationCount'] : 0;
		$tmp['firstHearPrompt']				=   $summit_comp['FirstCallerMsg'] 				? $summit_comp['FirstCallerMsg'] : 1;
		$tmp['recordingFunction']			=   $summit_comp['Taped'] 						? $summit_comp['Taped'] : $radisys_comp['Taped'];
		$tmp['hostNotStart']				=   $summit_comp['ConfQuickStart'] 				? $summit_comp['ConfQuickStart'] : 1;
		$tmp['participantNameRecord']		=   $summit_comp['ParticipantNameRecordAndPlayback'] 		? $summit_comp['ParticipantNameRecordAndPlayback'] : $radisys_comp['PNR'];
	
	
		$meeting_data['user'][] = $tmp;//这里的user最终会转为并列的xml格式
	
		//将数据由数组转为xml格式
		//$this->load->library('Array2XML', '', 'array2xml');
		$xml = Array2XML::createXML('userDTO', $meeting_data);
		return $xml->saveXML();
	}
	
	/**
	 * 从boss的请求数据中提取需要的数据
	 * 
	 * @param array $value boss请求数据
	 * @return array
	 */
	protected function _getDataFromBossRequest($value){
		$uc							=   	array();
		$uc['callback']				= 		isset($value['callback']) ? $value['callback'] : NULL;
		$uc['request_id']			= 		isset($value['requestId']) ? $value['requestId'] : NULL;
		$uc['type']					= 		isset($value['type'])	? $value['type'] : NULL;
		//$uc['customer_id']			=		isset($value['customer']['id']) ? $value['customer']['id'] : NULL;
		$uc['customer_code']		= 		isset($value['customer']['customerCode']) ? $value['customer']['customerCode'] : NULL;
		$uc['users']				= 		( isset($value['customer']['users']) && is_array($value['customer']['users']) ) ? $value['customer']['users'] : NULL;
		$uc['contract_id']			=		isset($value['customer']['contract']['id']) ? $value['customer']['contract']['id'] : NULL;
		
		//从uc组件里获取信息
		$uc['components']			=		isset($value['customer']['contract']['components']) ? $value['customer']['contract']['components'] : NULL;
		$comp						=       $this->_search_components($uc['components'], 'uc', array('auth'));
		$uc['auth']					=		$comp['auth'];			//0-普通用户 1-管理员
		
		//使用站点url，从ums获取站点id
		$uc['site_url']				= 		isset($value['customer']['contract']['resource']['siteURL']) ? $value['customer']['contract']['resource']['siteURL'] : NULL;
		$uc['site_id']     			=     	$this->_getSiteIdFromUms($uc['site_url']);//从ums通过站点url获取站点id
		
		//调用boss接口，获取客户名称
		$customer_info = $this->ci->boss->getCustomerInfo($uc['customer_code']);
		$uc['customer_name']		=		isset($customer_info['name']) ? $customer_info['name'] : NULL;
		
		return $uc;
	}
	
	/**
	 * 检查数据，这里只检查数据是否为空
	 * @param array $data
	 * @return boolean
	 */
	protected function _checkData($data){
		return !(count(array_filter($data, 'is_empty')) > 0) ;
	}
	
	protected function _generate_password($length = 8) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
		$password = substr(str_shuffle( $chars ), 0, $length);
		return $password;
	}
}
