<?php
//各API接口地址配置
define('UCC_API','http://'.UC_DOMAIN_URL.'/uccserver/uccapi/');//ucc接口地址
define('UMS_API','http://192.168.39.20:8081/umsforuc/');//UMS接口地址
define('LDAP_API','http://192.168.39.20:8091/');//UMS平台LDAP接口
define('BOSS_API','http://10.155.0.102:8880/');//BOSS接口地址
define('MEET_API','http://'.UC_DOMAIN_URL.'/uniform/');//日程和会议服务接口地址

define('PORTAL_API','http://portal.cc/');//Portal接口// 192.168.12.95
define('PORTAL_CHECKCODE', 'asdfqwerreqqwerd342');//Portal check code

//本地为boss提供的接口地址
define('BOSS_CALLBACK', 'http://'.UC_DOMAIN_URL.'/ucadmin/interface/account/callback');
define('BOSS_ACTIVE', 'http://'.UC_DOMAIN_URL.'/ucadmin/interface/account/active');

//组织消息发送配置
define('UC_ORG_SEDND_IP',UC_DOMAIN_URL);//组织消息服务器ip
define('UC_ORG_SEDND_TIMEOUT',15);//组织消息超时设置

//ucadmin数据库
//--A套
define('UC_DB_HOSTNAME','10.255.0.229');//主机
define('UC_DB_USERNAME','root');//帐号
define('UC_DB_PASSWORD','quanshi');//密码
define('UC_DB_DATABASE','statusnet');//数据库
//--B套
/*
define('UC_DB_HOSTNAME','10.255.0.198');//主机
define('UC_DB_USERNAME','root');//帐号
define('UC_DB_PASSWORD','quanshi');//密码
define('UC_DB_DATABASE','statusnet');//数据库
*/

//ucc server数据库地址
//--A套
define('UCCSERVER_DB_HOSTNAME','10.255.0.229');//主机
define('UCCSERVER_DB_USERNAME','root');//帐号
define('UCCSERVER_DB_PASSWORD','quanshi');//密码
define('UCCSERVER_DB_DATABASE','statusnet');//数据库
//--B套
/*
define('UCCSERVER_DB_HOSTNAME','10.255.0.198');//主机
define('UCCSERVER_DB_USERNAME','root');//帐号
define('UCCSERVER_DB_PASSWORD','quanshi');//密码
define('UCCSERVER_DB_DATABASE','statusnet');//数据库
*/

//uc cas(client access service)数据库地址
define('DOMAIN_DB_HOSTNAME','10.255.0.229');//主机
define('DOMAIN_DB_USERNAME','root');//帐号
define('DOMAIN_DB_PASSWORD','quanshi');//密码
define('DOMAIN_DB_DATABASE','cas');//数据库

//mss邮件
define('MAILIMG', 'http://'.UC_DOMAIN_URL.'/mailimg/');// 邮件中的图片存放地址
define('MAIL_LINK', 'http://'.UC_DOMAIN_URL.'/ucadmin');// 邮件中登录链接地址
define('MAIL_DOWNLOAD_LINK', 'http://'.UC_DOMAIN_URL.'/uccserver/download-html/download.php');// 邮件中下载PC端的链接地址
define('MSS_SERVER', '10.255.0.231'); // mss服务器ip
define('MSS_SENDER_ADDRESS', 'UCadmin@lab.quanshievent.cn'); // 邮件发送方的邮箱
define('MSS_SENDER_NAME', '蜜蜂'); // 邮件发送方的名称
define('MSS_SENDING_STATE', 9); // 邮件待发送状态
define('MSS_DB_HOSTNAME','192.168.39.60');//主机
define('MSS_DB_USERNAME','root');//帐号
define('MSS_DB_PASSWORD','111111');//密码
define('MSS_DB_DATABASE','mss_uc');//数据库

//app下载链接地址
define('APP_DOWNLOAD_SHORT_LINK','http://t.cn/RwwikV8');