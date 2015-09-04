<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @category	Rights_class
 * @abstract	Rights_class 类库，主要负责创建原始权限类。
 * @filesource	Rights_class.php
 * @author		Bai Xue<xue.bai_2@quanshi.com>
 * @copyright	Copyright (c) UC
 * @version		v1.0
 */
class Rights_class {
	private $rights_arr = array(
// 		'UC_passDoc' => array(
// 				'type' 			=> IM_SET, //'IM 设置',
// 				'name' 		=> '可使用全时蜜蜂 IM 互传文档',
// 				'boss_name' 	=> 'UC',
// 				'boss_property' => 'passDoc',
// 				'regex' 		=> "/[12]{1}/",
// 				'default_value' => 1,
// 				'value' 		=> ''//1：不允许2：允许
// 			),
// 		'' => array(
// 				'type' 			=> IM_SET, //'IM 设置',
// 				'name' 		=> '自动将联系过的联系人添加到常用联系人列表',
// 				'boss_name' 	=> 'UC',
// 				'boss_property' => '',
// 				'regex' 		=> "",
// 				'default_value' => '',
// 				'value' 		=> ''
// 		),
		'UC_answerStrategy' => array(
				'type' 			=> CALL_SET, //'通话设置',
				'name' 		=> '允许用户设置接听策略',
				'boss_name' 	=> 'UC',
				'boss_property' => 'answerStrategy',
				'regex' 		=> "/[12]{1}/",
				'default_value' => 1,
				'value' 		=> '' // 1：是2：否
		),
		'UC_answerStrategyOverseas' => array(
				'type' 			=> CALL_SET, //'通话设置',
				'name' 		=> '用户可设定接听策略到海外直线电话',
				'boss_name' 	=> 'UC',
				'boss_property' => 'answerStrategyOverseas',
				'regex' 		=> "/[12]{1}/",
				'default_value' => 1,
				'value' 		=> '' //1：是2：否
		),
		'UC_isCall' => array(
				'type' 			=> CALL_SET, //'通话设置',
				'name' 		=> '允许使用蜜蜂拨打电话',
				'boss_name' 	=> 'UC',
				'boss_property' => 'isCall',
				'regex' 		=> "/[12]{1}/",
				'default_value' => 1,
				'value' 		=> '' //1：是2：否
		),
		'UC_allowcallOverseas' => array(
				'type' 			=> CALL_SET, //'通话设置',
				'name' 			=> '允许拨打海外电话',
				'boss_name' 	=> 'UC',
				'boss_property' => 'allowcallOverseas',
				'regex' 		=> "/[12]{1}/",
				'default_value' => 1,
				'value' 		=> '' //1：是2：否
		),
		'UC_allowUserVoice' => array(
				'type' 			=> CONF_SET, //'会议设置',
				'name' 			=> '允许用户使用语音接入方式',
				'boss_name' 	=> 'UC',
				'boss_property' => 'allowUserVoice',
				'regex' 		=> "/[0-3]{1}/",
				'default_value' => 0,
				'value' 		=> ''//0电话+VoIP；1电话；2VoIP；3VoIP+国内本地接入
		),
		'summit_allowAttendeeCall' => array(
				'type' 			=> CONF_SET, //'会议设置',
				'name' 			=> '允许参会人自我外呼',
				'boss_name' 	=> 'summit',
				'boss_property' => 'allowAttendeeCall',
				'regex' 		=> "/[01]{1}/",
				'default_value' => 1,
				'value' 		=> ''//0是；1否
		),
		'summit_ParticipantNameRecordAndPlayback' => array(
				'type' 			=> CONF_SET, //'会议设置',
				'name' 			=> '所有参会者在加入会议时，是否需要录制姓名',
				'boss_name' 	=> 'summit',
				'boss_property' => 'ParticipantNameRecordAndPlayback',
				'regex' 		=> "/[01]{1}/",
				'default_value' => 1,
				'value' 		=> ''//0是；1否
		),
		'summit_Pcode1InTone' => array(
				'type' 			=> CONF_SET, //'会议设置',
				'name' 			=> '主持人加入会议语音提示',
				'boss_name' 	=> 'summit',
				'boss_property' => 'Pcode2InTone',
				'regex' 		=> "/[0-2]{1}/",
				'default_value' => 1,
				'value' 		=> ''//0 无提示音；1 提示蜂音；2语音报名
		),
		'summit_Pcode1OutTone' => array(
				'type' 			=> CONF_SET, //'会议设置',
				'name' 			=> '主持人退出会议语音提示',
				'boss_name' 	=> 'summit',
				'boss_property' => 'Pcode2OutTone',
				'regex' 		=> "/[0-2]{1}/",
				'default_value' => 1,
				'value' 		=> ''//0 无提示音；1 提示蜂音；2语音报名
		),
		'summit_Pcode2Mode' => array(
				'type' 			=> CONF_SET, //'会议设置',
				'name' 			=> '主持人未入会，参会人入会时的初始状态',
				'boss_name' 	=> 'summit',
				'boss_property' => 'Pcode2Mode',
				'regex' 		=> "/[TM]{1}/",
				'default_value' => 'M',
				'value' 		=> ''//T可听可讲；M静音
		),
		'summit_Pcode2InTone' => array(
				'type' 			=> CONF_SET, //'会议设置',
				'name' 			=> '参会人加入会议语音提示',
				'boss_name' 	=> 'summit',
				'boss_property' => 'Pcode1InTone',
				'regex' 		=> "/[0-2]{1}/",
				'default_value' => 1,
				'value' 		=> ''//0 无提示音；1 提示蜂音；2语音报名
		),
		'summit_Pcode2OutTone' => array(
				'type' 			=> CONF_SET, //'会议设置',
				'name' 			=> '参会人退出会议语音提示',
				'boss_name' 	=> 'summit',
				'boss_property' => 'Pcode1OutTone',
				'regex' 		=> "/[0-2]{1}/",
				'default_value' => 1,
				'value' 		=> ''//0 无提示音；1 提示蜂音；2语音报名
		),
		'summit_ValidationCount' => array(
				'type' 			=> CONF_SET, //'会议设置',
				'name' 			=> '参会人加入会议时，是否通知其会议中的参与方数',
				'boss_name' 	=> 'summit',
				'boss_property' => 'ValidationCount',
				'regex' 		=> "/[01]{1}/",
				'default_value' => 0,
				'value' 		=> '' //0 否；1 是
		),
		'summit_FirstCallerMsg' => array(
				'type' 			=> CONF_SET, //'会议设置',
				'name' 			=> '第一方与会者是否听到“您是第一个入会者”的提示',
				'boss_name' 	=> 'summit',
				'boss_property' => 'FirstCallerMsg',
				'regex' 		=> "/[01]{1}/",
				'default_value' => 0,
				'value' 		=> '' //0 否；1 是
		),
		'tang_time2' => array(
				'type' 			=> CONF_SET, //'会议设置',
				'name' 			=> '主持人离开会议时，何时结束会议',
				'boss_name' 	=> 'tang',
				'boss_property' => 'time2',
				'regex' 		=> "/\d+/",
				'default_value' => 5,
				'value' 		=> ''//整数（默认5分钟）
		),
		'tang_stopwhenoneuser' => array(
				'type' 			=> CONF_SET, //'会议设置',
				'name' 			=> '主持人退出会议时，会议是否自动终止',
				'boss_name' 	=> 'tang',
				'boss_property' => 'stopwhenoneuser',
				'regex' 		=> "/[01]{1}/",
				'default_value' => 1,
				'value' 		=> ''//0 否；1 是
		),
// 		'' => array(
// 				'type' 			=> CONF_SET, //'会议设置',
// 				'name' 			=> '数据会议结束后，是否立即结束电话会议',
// 				'boss_name' 	=> '',
// 				'boss_property' => '',
// 				'regex' 		=> "",
// 				'default_value' => '',
// 				'value' 		=> ''
// 		),
		'tang_5' => array(
				'type' 			=> CONF_SET, //'会议设置',
				'name' 			=> 'VoIP 音频质量',
				'boss_name' 	=> 'tang',
				'boss_property' => '5',
				'regex' 		=> "",
				'default_value' => '11',
				'value' 		=> '' // 11高保真音质；13标准音质
		),
		'tang_confscale' => array(
				'type' 			=> CONF_SET, //'会议设置',
				'name' 			=> '会议最大方数',
				'boss_name' 	=> 'tang',
				'boss_property' => 'confscale',
				'regex' 		=> "/^([2-9])|([1-9]\d{1})|([1-9]\d{2})|([1]\d{3})|(2000)$/",
				'default_value' => 2,
				'value' 		=> ''//最小为2，最大为2000
		),
		'summit_ConfDnisAccess' => array(
				'type' 			=> CONF_SET, //'会议设置',
				'name' 			=> '允许的海外接入号',
				'boss_name' 	=> 'summit',
				'boss_property' => 'ConfDnisAccess',
				'regex' 		=> "",
				'default_value' => 1,
				'value' 		=> '' // 1国内本地接入；2国内400接入；3国内800接入；4国际 local toll 接入；5国际 toll free 接入；7香港 local 接入
		)
	);
	
	public function __construct() {
		log_message('info', 'Into class Rights_class.');
	}
	
	public function get_rights() {
		return $this->rights_arr;
	}
}
