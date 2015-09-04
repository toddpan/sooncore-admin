<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 站点、组织、个人、生态企业权限项
 * -权限项目可能的来源有四个平台uc、tang、submit、radisys
 * -管理中心后台所展示的权限项均来自以上四个平台，只是展示了一部分
 * -这里存一份模板，来决定具体该展示哪些权限项，形式上类似于mysql里的view,所以文件名称里有个'view'
 * @file power_view.php
 * @author caohongliang <hongliang.cao@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */

//站点权限项
$config['site_power'] = array(
	'im_set'		 =>array(
		array(
			'name'     =>'可使用全时蜜蜂IM互传文档',
			'property' =>'passDoc',
			'values'   =>array(1,2),//1-是 2-否 下同
		),
		array(
			'name'     =>'自动将联系过的联系人添加到常用联系人列表',
			'property' =>'',
			'values'   =>array(1,2),
		),
		array(
			'name'     =>'自动将联系过的讨论组添加到讨论组列表',
			'property' =>'',
			'values'   =>array(1,2),
		),
	),
	'call_set'       =>array(
		array(
			'name'     =>'允许用户设置接听策略',
			'property' =>'answerStrategy',
			'values'   =>array(1,2),
		),
		array(
			'name'     =>'用户可设定接听策略到海外直线电话',
			'property' =>'answerStrategyOverseas',
			'values'   =>array(1,2),
		),
		array(
			'name'     =>'允许使用蜜蜂PC客户端拨打电话',
			'property' =>'isCall',
			'values'   =>array(1,2),
		),
		array(
			'name'     =>'允许拨打海外电话',
			'property' =>'allowcallOverseas',
			'values'   =>array(1,2),
		),
	),
	'tel_meeting_set'=>array(
		array(
			'name'     =>'允许召开电话会议',
			'property' =>' : "0"',
			'values'   =>array(1,2),
		),
		array(
			'name'     =>'电话会议自动报名',
			'property' =>'ParticipantNameRecordAndPlayback',
			'values'   =>array(0,1),
		),
		array(
			'name'     =>'外呼屏蔽*1功能',
			'property' =>'',
			'values'   =>array(1,2),
		),
		array(
			'name'     =>'参会人加入会议，告知参会者人数',
			'property' =>'ValidationCount',
			'values'   =>array(0,1),
		),
		array(
			'name'     =>'第一个入会是否需要听到您是第一个到会者讯息',
			'property' =>'FirstCallerMsg',
			'values'   =>array(0,1),
		),
		array(
			'name'     =>'主持人未入会，只要会议有人入会，会议就开始',
			'property' =>'ConfQuickStart',
			'values'   =>array(0,1),
		),
		array(
			'name'     =>'主持人退会，会议是否结束',
			'property' =>'stopwhenoneuser',
			'values'   =>array(0,1),
		),
		array(
			'name'     =>'参会人加入会议语音提示',
			'property' =>'Pcode1InTone',
			'values'   =>array(0,1,2),
		),
		array(
			'name'     =>'参会人退出会议语音提示',
			'property' =>'Pcode1OutTone',
			'values'   =>array(0,1,2),
		),
		array(
			'name'     =>'主持人加入会议语音提示',
			'property' =>'Pcode2InTone',
			'values'   =>array(0,1,2),
		),
		array(
			'name'     =>'主持人退出会议语音提示',
			'property' =>'Pcode2OutTone',
			'values'   =>array(0,1,2),
		),
	),
	'net_meeting_set'=>array(
		array(
			'name'     =>'允许召开网络会议',
			'property' =>'enableVoip',
			'values'   =>array(1,2),
		),
		array(
			'name'     =>'允许使用硬件视频',
			'property' =>'',
			'values'   =>array(1,2),
		),
		array(
			'name'     =>'会议允许最大方数',
			'property' =>'confscale',
			'values'   =>array(1,2),
		),
		array(
			'name'     =>'允许用户邀请站点外用户加入会议',
			'property' =>'',
			'values'   =>array(1,2),
		),
		array(
			'name'     =>'会议结束显示会后营销页面',
			'property' =>'attendeeSurvey',
			'values'   =>array(1,2),
		),
		array(
			'name'     =>'允许参会人共享文档',
			'property' =>'',
			'values'   =>array(1,2),
		),
		array(
			'name'     =>'允许参会人批注',
			'property' =>'',
			'values'   =>array(1,2),
		),
		array(
			'name'     =>'允许参会人保存共享数据',
			'property' =>'',
			'values'   =>array(1,2),
		),
		array(
			'name'     =>'允许参会人切换共享的文档／白板',
			'property' =>'',
			'values'   =>array(1,2),
		),
		array(
			'name'     =>'用户使用 PC 客户端开会，默认语音接入方式',
			'property' =>'allowUserVoice',
			'values'   =>array(0,1,2),
		),
	),
);
//组织权限项
$config['org_power'] = $config['site_power'];
//个人权限项
$config['user_power'] = $config['site_power'];
//生态权限项
$config['eco_power'] = array(
	array(
			'name'     =>'允许召开网络会议',
			'property' =>'enableVoip',
			'values'   =>array(1,2),
	),
	array(
			'name'     =>'允许召开电话会议',
			'property' =>'',
			'values'   =>array(1,2),
	),
	array(
			'name'     =>'允许会中外呼',
			'property' =>'',
			'values'   =>array(1,2),
	),
	array(
			'name'     =>'允许设置呼叫转移 ',
			'property' =>'',
			'values'   =>array(1,2),
	),
	array(
			'name'     =>'允许拨打电话 ',
			'property' =>'',
			'values'   =>array(1,2),
	),
);