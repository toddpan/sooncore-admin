<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * meeting 接口公用类
 * @file MeetingLib.php
 * @author caohongliang <hongliang.cao@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */

class MeetingLib{
	
	public function __construct(){
		//载入接口公用函数
		require_once(APPPATH . 'helpers/my_httpcurl_helper.php');
		$this->apiurl = trim(MEET_API, '/\\') ; //接口地址
	}
	
	/**
	 * 分发账户数据（增加、修改，启用都用此接口批量）
	 */
	public function saveMeetingData($xmlData){
		$method = 'POST';
		$url  	= $this->apiurl.'/rs/conference/acceptData';
		$ret = httpCurl($url, $xmlData, $method, array(POST_HEAD_XML));
		log_message('info',"uniform api url --> ".$url."param --> ".$xmlData." result -->".var_export($ret, true));
		
		return $ret['code'] == 0;
	}
	
	/**
	 * 账号离职/调岗/禁用
	 * 
	 * @param string $user_ids 用户id,以逗号分隔
	 * @param int    $type     操作类型 1-离职 2-调岗 3-禁用
	 * @param int    $app_id   
	 * 
	 * @return boolean
	 */
	public function closeMeeting($user_ids, $type, $app_id=19){
		$method = 'POST';
		$url  	= $this->apiurl.'/rs/conference/accountChange';
		$param 	= array(
			'appId'=>$app_id,
			'userIds'=>$user_ids,
			'type'=>$type
		);
		$ret = httpCurl($url, json_encode($param), $method);
		log_message('info',"uniform api url --> ".$url."param --> ".var_export($param, true)." result -->".var_export($ret, true));
		
		return $ret['code'] == 0;
	}
	
	
}
