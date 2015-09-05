<?php
//各API接口地址配置
define('UCC_API','http://'.UC_DOMAIN_URL.'/uccserver/uccapi/');//UCCServer接口
define('UMS_API','http://10.156.70.28:8081/umsapi/');//UMS接口
define('LDAP_API','http://10.156.70.70:8091/integration/');//UMS平台LDAP接口
define('BOSS_API','http://10.156.70.118:8088/');//BOSS接口
define('MEET_API','http://'.UC_DOMAIN_URL.'/uniform/');//日程和会议服务接口

define('PORTAL_API','http://portal.cc/');//Portal接口
define('PORTAL_CHECKCODE', 'asdfqwerreqqwerd342');//Portal check code

//本地为boss提供的接口地址
define('BOSS_CALLBACK', 'http://'.UC_DOMAIN_URL.'/ucadmin/interface/account/callback');
define('BOSS_ACTIVE', 'http://'.UC_DOMAIN_URL.'/ucadmin/interface/account/active');

//组织消息发送配置
define('UC_ORG_SEDND_IP','192.168.87.127');//组织消息服务器ip
define('UC_ORG_SEDND_TIMEOUT',15);//组织消息超时设置

//ucadmin数据库
define('UC_DB_HOSTNAME','192.168.87.127');//主机
define('UC_DB_USERNAME','dstatusnet');//帐号
define('UC_DB_PASSWORD','lkjijn');//密码
define('UC_DB_DATABASE','statusnet');//数据库

//uc cas(client access service)数据库地址
define('DOMAIN_DB_HOSTNAME','192.168.87.127');//主机
define('DOMAIN_DB_USERNAME','dstatusnet');//帐号
define('DOMAIN_DB_PASSWORD','lkjijn');//密码
define('DOMAIN_DB_DATABASE','statusnet');//数据库

//mss邮件
define('MAILIMG', 'http://'.UC_DOMAIN_URL.'/mailimg/');// 邮件中的图片存放地址
define('MAIL_LINK', 'http://'.UC_DOMAIN_URL.'/ucadmin');// 邮件中登录链接地址
define('MAIL_DOWNLOAD_LINK', 'http://'.UC_DOMAIN_URL.'/uccserver/download-html/download.php');// 邮件中下载PC端的链接地址
define('MSS_SERVER', '192.168.64.6'); // mss服务器ip
define('MSS_SENDER_ADDRESS', 'notify@quanshimeeting.com'); // 邮件发送方的邮箱
define('MSS_SENDER_NAME', '全时蜜蜂'); // 邮件发送方的名称
define('MSS_SENDING_STATE', 9); // 邮件待发送状态
define('MSS_DB_HOSTNAME','192.168.87.127');//主机
define('MSS_DB_USERNAME','dmss');//帐号
define('MSS_DB_PASSWORD','mniiut');//密码
define('MSS_DB_DATABASE','mss');//数据库

//app下载链接地址
define('APP_DOWNLOAD_SHORT_LINK','http://t.cn/RwwCNVN');//A环境
//define('APP_DOWNLOAD_SHORT_LINK','http://t.cn/RAkrYss');//B环境