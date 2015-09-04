<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @brief ldap服务器类型数组
 */
// $config['_servertype_arr']	= array(1=>'MS_AD',2=>'OPENDIRECTORY',3=>'LOTUS_DIMINO',4=>'OTHERS');
$config['_servertype_arr']	= array(1=>'MS_AD',2=>'OPENDIRECTORY');

/**
 * @brief ldap服务器连接方式数组
 */
$config['_protocol_arr']	= array(0=>'LDAPS',1=>'LDAP');

/**
 * @brief 可选员工标签数组
 */
$config['_tags_arr']		= array(
									'mobileAttribute'	=>'手机',
									'emailAttribute'	=> '邮箱',
									'phoneAttrubute'	=> '工作电话',
									'costAttribute'		=> '成本中心',
									'userIdAttribute'	=> '员工ID'
							);

/**
 * @brief 必选员工标签数组
 */
$config['_must_tags_arr']		= array(
									'lastnameAttribute'		=>'姓氏',
									'firstnameAttribute'	=>'名字',
// 									'genderAttribute'		=>'性别',
									'idAttribute'			=>'ldap用户唯一标识',
									'positionAttribute'		=>'职位',
// 									'mobileAttribute'		=>'手机'
							);



