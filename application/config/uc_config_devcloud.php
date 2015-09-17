<?php
//各API接口地址配置
define('UCC_API','http://devyt.xiezuoyun.cn/uccserver/uccapi/');// UCCServer接口
define('UMS_API','http://'.UC_DOMAIN_URL.':8081/umsforuc/');//UMS接口
define('LDAP_API','http://117.121.26.154:8091/');//UMS平台LDAP接口
define('BOSS_API','http://127.0.0.1:8880/');//BOSS接口//http://192.168.17.57:8880/ 
define('MEET_API','http://'.UC_DOMAIN_URL.'/uniform/');//日程和会议服务接口//TODO 以后改成ucc.quanshi.com

define('PORTAL_API','http://portal.quanshi.com/');//Portal接口// 192.168.12.95
define('PORTAL_CHECKCODE', 'test');//Portal check code

//本地为boss提供的接口地址
define('BOSS_CALLBACK', 'http://'.UC_DOMAIN_URL.'/sooncore-admin/interface/account/callback');
define('BOSS_ACTIVE', 'http://'.UC_DOMAIN_URL.'/sooncore-admin/interface/account/active');

//组织消息发送配置
define('UC_ORG_SEDND_IP','192.168.35.155');//组织消息服务器ip
define('UC_ORG_SEDND_TIMEOUT',15);//组织消息超时设置

//ucadmin数据库
define('UC_DB_HOSTNAME',UC_DOMAIN_URL);//主机
define('UC_DB_USERNAME','root');//帐号
define('UC_DB_PASSWORD','quanshi');//密码
define('UC_DB_DATABASE','statusnet');//数据库

//uc cas(client access service)数据库地址
define('DOMAIN_DB_HOSTNAME',UC_DOMAIN_URL);//主机
define('DOMAIN_DB_USERNAME','root');//帐号
define('DOMAIN_DB_PASSWORD','quanshi');//密码
define('DOMAIN_DB_DATABASE','statusnet');//数据库

//mss邮件
define('MAILIMG', 'http://'.UC_DOMAIN_URL.'/mailimg/'); //邮件中的图片存放地址
define('MAIL_LINK', 'http://'.UC_DOMAIN_URL.'/sooncore-admin');// 邮件中登录链接地址
define('MAIL_DOWNLOAD_LINK', 'http://'.UC_DOMAIN_URL.'/uccserver/download-html/download.php');// 邮件中下载PC端的链接地址
define('MSS_SERVER', '192.168.61.231'); // mss服务器ip
define('MSS_SENDER_ADDRESS', 'UCadmin@lab.quanshievent.cn'); // 邮件发送方的邮箱
define('MSS_SENDER_NAME', '蜜蜂'); // 邮件发送方的名称
define('MSS_SENDING_STATE', 9); // 邮件待发送状态
define('MSS_DB_HOSTNAME','192.168.35.113');//主机
define('MSS_DB_USERNAME','UCadmin@lab.quanshievent.cn');//帐号
define('MSS_DB_PASSWORD','UCadmin ');//密码
define('MSS_DB_DATABASE','mss');//数据库

//app下载链接地址
define('APP_DOWNLOAD_SHORT_LINK','http://t.cn/RwwikV8');
