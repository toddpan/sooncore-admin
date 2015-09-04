<?php

//各API接口地址配置
define('UCC_API','http://devcloud.quanshi.com/uccserver/uccapi/');//已经修改为系统自动拼写UCCServer接口
//define('UMS_API','http://192.168.17.61:8080/ums/');//UMS接口
define('UMS_API','http://192.168.17.61:8080/ums/');//UMS接口
// define('LDAP_API','http://192.168.12.27:8080/');//UMS平台LDAP接口
define('LDAP_API','http://10.255.0.108:8091/');//UMS平台LDAP接口
define('BOSS_API','http://10.255.0.108:8880/');//BOSS接口//http://192.168.17.57:8880/ 
define('MEET_API','http://devcloud.quanshi.com/uniform/');//日程和会议服务接口//TODO 以后改成ucc.quanshi.com
define('PORTAL_API','http://portal.cc/');//Portal接口// 192.168.12.95

//Portal check code
define('PORTAL_CHECKCODE', 'asdfqwerreqqwerd342');

//系统常量配置
define('UC_DOMAIN_URL','devcloud.quanshi.com');//UC后台指定的域分配处理/其它接口调用域的url  如：开发环境值'devcloud.quanshi.com'

//本地为boss提供的接口地址
define('BOSS_CALLBACK', 'http://'.UC_DOMAIN_URL.'/ucadmin/interface/account/callback');
define('BOSS_ACTIVE', 'http://'.UC_DOMAIN_URL.'/ucadmin/interface/account/active');

//组织消息发送配置
define('UC_ORG_SEDND_IP','192.168.35.155');//组织消息服务器ip
define('UC_ORG_SEDND_TIMEOUT',15);//组织消息超时设置

// 邮件中的图片存放地址
define('MAILIMG', 'http://oncloud.quanshi.com/mailimg/');

// 邮件中登录链接地址
define('MAIL_LINK', 'http://oncloud.quanshi.com/ucadmin');

// 邮件中下载PC端的链接地址
define('MAIL_DOWNLOAD_LINK', 'http://oncloud.quanshi.com/uccserver/download-html/download.php');

// MSS邮件服务器配置
define('MSS_SERVER', '192.168.61.231'); // mss服务器ip
define('MSS_SENDER_ADDRESS', 'UCadmin@lab.quanshievent.cn'); // 邮件发送方的邮箱
define('MSS_SENDER_NAME', '全时蜜蜂'); // 邮件发送方的名称
define('MSS_SENDING_STATE', 9); // 邮件待发送状态

//数据库配置
//uc数据库
//define('UC_DB_HOSTNAME','192.168.35.115');//主机
define('UC_DB_HOSTNAME','117.121.25.135');//主机
define('UC_DB_USERNAME','root');//帐号
define('UC_DB_PASSWORD','quanshi');//密码
define('UC_DB_DATABASE','statusnet');//数据库
//域分配数据库连接
define('DOMAIN_DB_HOSTNAME','192.168.35.115');//主机
define('DOMAIN_DB_USERNAME','root');//帐号
define('DOMAIN_DB_PASSWORD','quanshi');//密码
define('DOMAIN_DB_DATABASE','statusnet');//数据库
//mss邮件数据库连接
define('MSS_DB_HOSTNAME','192.168.35.115');//主机
define('MSS_DB_USERNAME','root');//帐号
define('MSS_DB_PASSWORD','quanshi');//密码
define('MSS_DB_DATABASE','mss');//数据库
//最新邮件数据库连接
define('EMAIL_DB_HOSTNAME','192.168.35.113');//主机
define('EMAIL_DB_USERNAME','root');//帐号
define('EMAIL_DB_PASSWORD','quanshi');//密码
define('EMAIL_DB_DATABASE','webpower');//数据库

//app下载链接地址
define('APP_DOWNLOAD_SHORT_LINK','http://t.cn/RwwCNVN');