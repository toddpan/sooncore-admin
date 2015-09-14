<?php
//环境配置  |生产环境on ;开发环境 dev;测试环境 test
define('UC_ENVIRONMENT','dev');
//base url,区分AB环境，如果是A环境：devyt.xiezuoyun.cn 如果是B环境 devcloudb.quanshi.com
define('UC_DOMAIN_URL','devyt.xiezuoyun.cn');

//api调用head配置
define('POST_HEAD','Content-type: application/x-www-form-urlencoded;charset:UTF-8');//接口post head
define('POST_HEAD_JSON','Content-type: application/json');//接口post head json
define('POST_HEAD_XML','Content-type: text/xml');//接口post head xml数据
define('POST_HEAD_HTML','text/html; charset=utf-8');//接口post head HTML数据

//系统常量配置
//define('BOSS_CALLBACK_IP','192.168.35.155');//集群中指定的唯一做域分配处理的ip  地址[当前uc站点ip];注意最后没有/
//define('BOSS_CALLBACK_URL','devyt.xiezuoyun.cn');//集群中指定的唯一做域分配处理的url  地址[当前uc站点url];注意最后没有/
define('UC_DOMAIN_DIR','/sooncore-admin');//uc域接口目录,相对于域表中的url,如果'/ucadmin' ，如果没有目录，则为空 ，注意后台没有/
//define('MAILIMG', '/mailimg/'); // 邮件中的图片存放地址
define('IS_OPEN_TEST',0);//是否开通测试输出文件0不开启1开启
define('IS_OPEN_DEV',0);//是否开启正式环境功能，当前开发或测试时暂时还不能开启0不开启1开启
//define('CFG_TEMPLETS_STYLE','default');
//define('USER_DEFAULT_PASSWORD','');//用户初始密码
define('UC_BOSSAPI_MAX_NUM',20);//UC每次向boss批量调接口时，每次最大发送数据量
define('DOMAIN_USER_NUM_RATE',0.95);//域该站点的用户量的限值率
define('UC_PRODUCT_ID',20);//UC产品编号
define('PC3_PRODUCT_ID',60000);//PC3.0产品编号
define('UC_PRODUCT_OPEN_STATUS',82);//uc产品开通状态值
define('UC_PRODUCT_CODE','UC');//UC产品,会议接口applicationId 值也是此值
define('UC_CHECK_CODE','asdfqwerreqqwerd342');//检验码，由各应用从portal处申请，用于进行检验身份

//系统邮件信息配置
define('UC_SENDER_NAME','快合科技');//邮件sender_name 发件人
define('UC_SENDER_ADDRESS','service@sooncore.com');//发件人邮件地址sender_address
define('UC_NAME_EN','sooncore'); //系统英文名称
define('UC_NAME_CN','快合科技'); //系统中文名称

//Acm配置
define('ACM_LINK',"tcp -h 192.168.61.7 -p 9012:tcp -h 192.168.61.7 -p 9012");
define('ACM_APPNAME','UC');
define('ACM_APILEVEL','1.0');
define('ACM_VERSION','1.0.1');

// 今天允许发送的短信总数
define('TODAY_CODE_SEND_NUM',5);

// 密码设置规则
define('EXPIRY_THIRTY_TYPE', 1); // 1、用户密码有效期30天
define('EXPIRY_SIXTY_TYPE', 2); // 2、用户密码有效期60天
define('EXPIRY_NINTY_TYPE', 3); // 3、用户密码有效期90天
define('EXPIRY_ONE_AND_ENGHITY_TYPE', 4); // 4、用户密码有效期180天
define('EXPIRY_NOT_LIMIT_TYPE', 5); // 5、用户密码有效期:不需要变更
define('DEFAULT_PWD_EXPIRY_DAY', 3); // 默认用户密码有效期：90天
define('HISTORY_TYPE_THREE', 1); // 1、密码历史记忆次数：3次
define('HISTORY_TYPE_FIVE', 2); // 2、密码历史记忆次数：5次
define('HISTORY_TYPE_TEN',3); // 3、密码历史记忆次数：10次
define('HISTORY_TYPE_NO', 4); // 4、密码历史记忆次数：不记忆
define('DEFAULT_PWD_HISTORY_TYPE', 1);// 默认密码历史记忆次数：3次
define('COMPLEXITY_TYPE_ONE', 1); // 1、密码复杂性：8-30位，不限制类型
define('COMPLEXITY_TYPE_TWO', 2); // 2、密码复杂性：8-30位数字与字母组合
define('COMPLEXITY_TYPE_THREE', 3); // 3、密码复杂性：8-30位数字、符号与字母组合
define('DEFAULT_PWD_COMPLEXITY_TYPE', 2);// 默认密码复杂性：8-30位数字与字母组合


//公司信息配置
define('COMPANY_NAME','快合科技通信服务有限公司');//公司名称
define('COMPANY_COPR','©Copyright 2014-2015');//版权
define('COMPANY_ICP','京ICP备15000001号');//站点备案号
define('COMPANY_SERVE_TEL','400-899-9999');//24小时服务热线
//正则配置
define('USER_REGEX','/^[\S]{8,30}$/');//帐号正则表达式
//系统角色
define('SYSTEM_MANAGER', 1);          //系统管理员
define('ORGANIZASION_MANAGER', 2);    //组织管理员
define('EMPPLOYEE_MANAGER', 3);       //员工管理员
define('ACCOUNT_MANAGER', 4);         //账号管理员
define('ECOLOGY_MANAGER', 5);         //生态管理员
define('CHANNEL_MANAGER', 6);         //渠道管理员


// 批量上传失败文件保存路径
//define('FAIL_FILE_DIR', '/usr/local/webroot/ucadmin/data/failfile/');
define('FAIL_FILE_DIR', './data/failfile/');
// 批量上传文件大小限制  10M
define('FILE_MAX_SIZE', 1024*1024*10);
// 批量上传文件保存路径
//define('BULKIMPORT_FILE_PATH', '/usr/local/webroot/ucadmin/public/jQueryFileUpload/server/php/files/');
define('BULKIMPORT_FILE_PATH', './public/jQueryFileUpload/server/php/files/');

//====logo====
define('LOGO_UPLOAD_PATH','./data/logo/');
//define('LOGO_UPLOAD_PATH','/usr/local/webroot/ucadmin/data/logo/');
define('LOGO_WIDTH',110);
define('LOGO_HEIGHT',110);
define('LOGO_MID_WIDTH',300);
define('LOGO_MID_HEIGHT',205);
define('LOGO_MAX_SIZE',5000);//kb
define('LOGO_ALLOW_TYPES','jpg|png');

//====batch import new==
define('BATCH_UPLOAD_PATH', './data/batchimport/uploadfiles');
define('BATCH_FAIL_PATH', './data/batchimport/failfiles');
define('BATCH_TEMPLATE_PATH', './data/batchimport/templatefiles');
define('BATCH_MAX_SIZE', 10000);//kb
define('BATCH_LIMIT_ROWS', 10000);//单个文档可上传最大帐号数
define('BATCH_MAX_CHUNKSIZE', 2000);//每次读取excel文件取出的行数
define('ACCOUNTS_PER_TASK', 1000);//每个任务开通的帐号数
define('BATCH_ALLOW_TYPES', 'xls|xlsx|csv');
define('EXCEL_VERSION', 'Excel2007');

//账号(create|update|disable|enable|delete)、合同开通、权限变更上传操作类型
define('ACCOUNT_CREATE_UPLOAD', 11);		//帐号开通上传任务
define('ACCOUNT_DELETE_UPLOAD', 21);		//帐号删除上传任务
define('ECOLOGY_DELETE_UPLOAD', 22);		//删除生态企业
define('ACCOUNT_DISABLE_UPLOAD', 31);		//帐号禁用上传任务
define('ACCOUNT_ENABLE_UPLOAD', 41);		//帐号启用上传任务
define('USER_POWER_CHANGE_UPLOAD', 51);		//单用户权限变更上传任务
define('ORG_POWER_CHANGE_UPLOAD', 52);		//组织权限变更上传任务
define('SITE_POWER_CHANGE_UPLOAD', 53);		//站点权限变更上传任务
define('BATCH_CHANGE_UPLOAD',54);			//批量用户变更上传任务，包括用户信息和权限
define('ECOLOGY_POWER_CHANGE_UPLOAD', 55);  //生态企业权限变更上传任务
define('ACCOUNT_UPDATE_UPLOAD', 56);  		//用户个人信息或组织信息变更

//账号(create|update|disable|enable|delete)、合同开通操作类型
define('ACCOUNT_CREATE_PROCESS', 11);
define('ACCOUNT_DELETE_PROCESS', 12);
define('ACCOUNT_DISABLE_PROCESS', 13);
define('ACCOUNT_ENABLE_PROCESS', 14);
define('ACCOUNT_UPDATE_PROCESS', 15);
define('CONTRACT_CREATE_PROCESS', 21);

define('UNDEFINED_PROCESS_TYPE', 0);

//任务处理状态
define('TASK_UNPROCESS',0); //未执行
define('TASK_PROCESSING',1);//执行中
define('TASK_PROCESSED',2);//执行完成
define('TASK_PROCESS_FAILED',3);//执行失败

//乐观锁初始版本号
define('INIT_VERSION', 0);

//开通用户为管理员OR普通用户
define('AUTH_IS_MANAGER', 1);
define('AUTH_IS_USER', 0);

//回调boss，是否执行成功
define('BOSS_CALLBACK_SUCCESS',1);//操作成功，回调boss
define('BOSS_CALLBACK_FAILED',-1);//操作失败，回调boss

//管理员类型
define('ADMIN_COMPANY_MANAGER', 1);//总公司管理员
define('ADMIN_SUB_COMPANY_MANAGER', 2);//分公司管理员
define('ADMIN_ECOLOGY_MANAGER', 3);//生态管理员
define('ADMIN_OTHERS', 0);//其他

// 性别
define('NOT_SET', 0); // 未设置
define('BOY', 1);	// 男
define('GIRL', 2);	// 女

//管理员状态
define('ADMIN_OPEN', 1); //启用
define('ADMIN_CLOSE', 0); //禁用

//uc_user表中用户的状态
define('UC_USER_STATUS_UNUSED', 0);//未启用（一直未开通过）
define('UC_USER_STATUS_ENABLE', 1);//已开通
define('UC_USER_STATUS_DISABLE', 2);//禁用/删除（已开通）

//组织类型
define('ORG_COMPANY',1);//企业
define('ORG_ECOLOGY_COMPANY',2);//生态企业
define('ORG_DEPARTMANT',3);//部门
define('ORG_ECOLOGY_DEPARTMENT',4);//生态企业部门
define('ORG_SUB_COMPONY',5);//分公司

//用户产品状态
define('UMS_USER_STATUS_OPEN', 82);
define('UMS_USER_STATUS_CLOSE', 0);

//后台进程休眠时间,1秒
define('THREAD_SLEEP_TIME', 1);

//集群中用户安全上限比率
define('SAFE_RATIO', 0.8);

// Mac地址
define('MAC_ADDR', 'D0-67-E5-2D-6F-C9');

// UMS站点类型
define('UMS_USER_SITE', 0); // 用户站点
define('UMS_PUBLIC_SITE', 1); // 公用站点
define('UMS_TEST_OR_OPREATING_SITE', 2); // 测试site或运营公司site

// =====================试用账号=========================
// UMS账号状态
define('UMS_USER_NOT_OPEN', 0);	// 未开通
define('UMS_USER_IS_OPEN', 1);	// 已开通
define('UMS_USER_IS_CLOSE', 2);	// 已关闭
define('UMS_USER_IS_DELETE', 3);// 已删除

// 手机短信验证码状态：0、未验证；1、已验证；2、已过期
define('CODE_NOT_VALID', 0);
define('CODE_IS_VALID', 1);
define('CODE_IS_EXPIRED', 2);

// 验证码有效期：半小时
define('CODE_EXPIRED_TIME', 1800);

// UMS站点类型:0是用户site，1是公用site，2是测试site或运营公司site
define('UMS_SITE_TYPE_USER', 0);
define('UMS_SITE_TYPE_PUBLIC', 1);
define('UMS_SITE_TYPE_TEST_OR_CORP', 2);

// UC公司类型:0：单一企业；1：集中管理；2分散管理
define('COR_TYPE_SIGLE', 0);
define('COR_TYPE_FOCUS', 1);
define('COR_TYPE_DISPERSE', 2);

// 是否批量导入:0：否（批量导入）；1：是（LDAP导入）；2：全部都可以',
define('NOT_LDAP', 0);
define('IS_LDAP', 1);
define('BOTH_LDAP', 2);

// 是否批量导入:0：否（批量导入）；1：是（LDAP导入）
define('LDAP_SITE', 1);
define('NOT_LDAP_SITE', 0);

// 试用账号有效期：10天
define('CRIPPLEWARE_EXPIRED_TIME', 864000);

define('TEST_CUSTOMER_CODE', '11011'); // 客户编码
define('TEST_ACCOUNT_ID', '11022'); // 账户Id
define('TEST_CONTRACT_ID', 1111); // 合同Id

// 试用账号属性类型
define('CRIPPLEWATE_RADYSIS', 1); // radysis
define('CRIPPLEWATE_RADYSIS_AND_SUMMIT', 2); // radysis+summit
// =====================试用账号=========================

// 分页类：每页显示的条目数
define('PER_PAGE', 50);

// 分公司用户数量
define('FILIALE_USER_AMOUNT', 2000);

// 变更分公司管理员的方式：1、从已有用户中选一个；2、重新创建
define('UPDATE_SUB_ADMIN_CHOOSE', 1);
define('UPDATE_SUB_ADMIN_CREATE', 2);

// 权限获取位置：1、用户自己的个性化权限； 2、组织自己的个性化权限；3、上级组织权限；4、站点权限
define('POWER_FROM_USER', 1); // 用户的个性化权限
define('POWER_FROM_ORG', 2);  // 组织的个性化权限
define('POWER_FROM_PARENT_ORG', 3); // 从上级组织获得的个性化权限
define('POWER_FROM_SITE', 4); // 从站点获得的权限

// 权限是否有变化：0、没有；1、有
define('POWER_NOT_CHANGE', 0);
define('POWER_IS_CHANGE', 1);

// 会议属性是否有变化：0、没有；1、有
define('CONF_POWER_NOT_CHANGE', 0);
define('CONF_POWER_IS_CHANGE', 1);

// 权限类型：1、IM设置；2、通话设置；3、会议设置
define('IM_SET', 1);
define('CALL_SET', 2);
define('CONF_SET', 3);

//开启接口调用性能测试
define('OPEN_PERFORMANCE_TEST',true);
define('PERFORMANCE_INTERNAL',0);

//邮件类型
define('MANAGER_CREATE_MAIL', 1); // 管理员账号开通（正式版）
define('USER_CREATE_MAIL', 2); // 普通账号开通（正式版）
define('MANAGER_CRIPPLEWARE_CREATE_MAIL', 3); // 管理员试用账号开通
define('MANAGER_CRIPPLEWARE_UPDATE_MAIL', 4); // 管理员试用资格变更
define('MANAGER_CRIPPLEWARE_DEADLINE_MAIL', 5); // 管理员试用期到期
define('RESET_PWD_SUC_MAIL', 6); // 修改密码成功
define('MANAGER_SET_MAIL', 7); // 非管理员账号开通（正式版）

// xml导入进程休眠时间
define('SITE_INTERVAL_TIME', 1); // 站点导入间隔
define('GLOBAL_INTERVAL_TIME', 180); // 所有站点导入间隔

// 配置LDAP站点登录名规则
define('NOT_USE_SELF_DEFINED_SUFFIX', 0); 	// 登陆不使用自定义后缀
define('USE_SELF_DEFINED_SUFFIX', 1); 		// 登陆使用自定义后缀

