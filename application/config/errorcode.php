<?php
/*
UC管理中心后台错误码列表：
-----------------------------/---------
10000 - 10999 常用错误
10000 - 参数错误
10001 - 数据库访问异常
-----------------------------/---------
11000 - 11999 组织模块
11000 - 11099 账号、站点异步开通
11100 - 11199 组织结构
11200 - 11250 ldap
-----------------------------/---------
12000 - 12999 生态模块
-----------------------------/---------

13000 - 13999 报告管理模块
-----------------------------/---------

14000 - 14999 应用管理
-----------------------------/---------

15000 - 15999 安全管理
-----------------------------/---------

16000 - 16999 系统设置
-----------------------------/---------

17000 - 17099 账号登录
-----------------------------/---------
17100 - 17199 管理员管理
-----------------------------/---------
17200 - 17299 消息管理
----------------------------/------------
17300 - 17399 试用账号

18000 - 18999   分公司


19000 - 20000  忘记密码

20100-20199  修改密码


说明：正确码返回统一为0.

*/
define('COMMON_SUCCESS', 0);					//成功
define('COMMON_FAILURE', 1);					//失败
define('COMMON_PARAM_ERROR',10000);				//参数错误
define('COMMON_DATABASE_EXCEPTION',10001);		//数据库访问异常


define('GET_ORGANIZATION_ERROR',11202);		//LDAP获取组织树失败
define('ORGANIZATION_TREE_ERROR',11203);	//LDAP获取组织树格式不正确
define('GET_LABEL_CLASS_ERROR',11204);		//LDAP获取标签类失败
define('GET_ATTRIBUTE_ERROR',11205);		//LDAP获取属性失败
define('GET_LDAP_DATA_ERROR',11206);		//获取LDAP数据失败
define('GET_LDAP_TABLE_ERROR',11207);		//获取LDAP列表失败
define('UPDATE_USER_STATUS_ERROR',11208);	//修改用户状态失败
define('UPDATE_LDAP_ERROR',11209);			//修改LDAP配置失败
define('CREATE_LDAP_ERROR',11210);			//创建LDAP失败
define('LDAP_NAME_NOT_NULL',11210);			//创建LDAP的名称不能为空
define('SYNORG_NOT_NULL',11211);			//同步的组织部门不能为空
define('CLASS_NOT_NULL',11212);				//属性的类不能为空

// 17000 - 17099 账号登录
define('LOGIN_CODE_ERROR', 17001);			// 验证码错误
define('USERNAME_OR_USERPWD_ERROR', 17002);	// 用户名或密码错误
define('LOGIN_FAIL', 17003);				// 登录失败
define('NOT_ADMIN_OR_DISABLLE', 17004);		// 不是管理员或账号被停用
define('ERROR_INFORMATION', 17005);			// 信息错误
define('ERROR_ACCOUNT_ID', 17006);			// 信息错误
define('ERROR_ROLE', 17007);			// 信息错误
define('PWD_EXPIRED', 17008);			// 密码已过期
define('SITEID_NOT_EXIST', 17009); 		// 站点id不存在
define('SITEURL_NOT_EXIST', 17010); 		// 站点url不存在

// 17300 - 17399 试用账号
define('EMAIL_ERROR', 17301);			// email错误
define('USERNAME_ERROR', 17302);		// 姓名错误
define('PHONE_ERROR', 17303);			// 手机号码错误
define('COMPANY_NAME_ERROR', 17304);	// 公司名称错误
define('CODE_ERROR', 17305);		// 验证码错误
define('LOGINNAME_EXIST_ERROR', 17305);		// loginName已存在

//12000 - 12999 生态模块
define('E_COMPANY_NOT_NULL', 12001);		// 生态企业公司名不能为空
define('E_COMPANY_TOO_LONG', 12002);		// 生态企业公司名长度不能大于50个汉字
define('E_COMPANY_CHINESE_NOT_NULL', 12003);		// 生态企业中文名称不能为空
define('E_COMPANY_CHINESE_TOO_LONG', 12004);		// 生态企业中文名称不能大于50个汉字
define('E_COMPANY_EXIST_ERROR', 17005);		// 生态企业公司名称名已存在
define('E_TELEPHONE_ERROR', 17006);		    // 电话号码有误
define('E_COUNTRY_TOO_LONG', 17007);		// 国家地区长度不能大于15个汉字
define('E_INTRODUCTION_TOO_LONG', 17008);		// 公司简介长度不能大于500个汉字

define('E_NAME_NOT_NULL', 120010);		// 姓或名不能为空
define('E_JOB_NOT_NULL', 120011);		// 职务不能为空
define('E_OFFICE_ADDRESS_NOT_NULL', 120012);		// 办公地点不能为空
define('E_CALL_PHONE_ERROR', 120013);		// 手机号码有误

// 17100 - 17199 管理员管理
define('ADMIN_DEL_FAIL', 17101);		// 删除管理员失败
define('ADMIN_ADD_FAIL', 17102);		// 添加管理员失败
define('NOT_RESET_SELF_PWD', 17103);	// 无法重置自己的密码，请修改您的密码
define('RESET_PWD_FAIL', 17104);		// 重置密码失败
define('USER_NOT_EXIST', 17105);		// 用户不存在

//16000 - 16999 系统设置
define('SITE_POWER_NO_CHANGE', 16001);		// 站点权限未做修改
define('SITE_POWER_SAVE_ERROR', 16002);		// 站点权限保存失败
define('SITE_POWER_SYNC_ERROR', 16003);		// 站点权限同步失败


// 18000 - 18999 分公司
define('LOGINNAME_IS_WRONG', 18001); // 管理员账号格式不正确
define('LOGINNAME_IS_ALREADY_EXIST', 18002); // 管理员账号已存在
define('LOGINNAME_IS_NOT_EXIST', 18002); // 管理员账号不存在
define('FILIALENAME_IS_EMPTY', 18003); // 子公司的名称不能为空
define('FILIALENAME_LT_EIGHTY', 18004); // 子公司的名称不能超过80个汉字
define('EMAIL_IS_WRONG', 18005); // 电子邮箱格式不正确
define('PARENT_ORG_NOT_EXIST', 18006); // 上级企业不存在
define('TELEPHONE_IS_WRONG', 18007); // 手机号码不正确
define('SITE_URL_IS_WRONG', 18008); // 全时站点不能为空

define('CREATE_FILIALE_FAIL', 18099); // 创建分公司失败
define('CREATE_MANAGER_FAIL', 18088); // 创建管理员失败
define('UMS_CREATE_SITE_FAIL', 18100); // 在UMS创建站点失败
define('UC_CREATE_SITE_FAIL', 18101); // 在UC创建站点失败
define('UC_GET_CONTRACTID_FAIL', 18102); // 获取合同Id失败
define('UMS_CREATE_ORGANIZATION_FAIL', 18103); // 在UMS创建组织失败
define('UC_CREATE_CUSTOMER_FAIL', 18104); // 在UC创建客户失败
define('UC_CREATE_AREA_FAIL', 18105); // 在UC创建地区失败
define('UMS_CREATE_USER_FAIL', 18106); // 在UMS创建用户失败
define('CREATE_CONTRACT_FAIL', 18107); // 创建分公司模板失败
define('UC_CREATE_USER_FAIL', 18108); // 在UC创建用户失败
define('UC_CREATE_ADMIN_FAIL', 18109); // 在UC创建管理员失败
define('CREATE_ADMIN_FAIL', 18110); // 创建管理员失败
define('CREATE_CLUSTER_FAIL', 18111); // 分配集群失败
define('SAVE_CLUSTER_FAIL', 18112); // 保存集群失败
define('SAVE_PORTAL_FAIL', 18113); // 保存集群失败

define('ILLEGAL_OPERATE_TYPE', 18200); // 非法操作：操作类型不正确
define('SITE_NOT_IN_UMS', 18201); // 非法操作：该站点在UMS不存在
define('SITE_NOT_IN_UC', 18202); // 非法操作：该站点在UC中不存在
define('ORG_NOT_IN_UMS', 18203); // 非法操作：该组织在UMS不存在
define('OLD_ADMIN_NOT_EXIST', 18204); // 非法操作：旧的管理员信息不存在
define('ADMIN_IS_CLOSE', 18205); // 您填写的管理员用户名已停用
define('ADMIN_IS_NOT_IN_SITE', 18206); // 您填写的管理员用户名不属于当前站点
define('UPDATE_ADMIN_FAIL', 18207); // 更新管理员失败

define('DELETE_FILIALE_FAIL', 18301); // 删除分公司失败
define('UC_DELETE_USER_FAIL', 18302); // 保存线程失败
define('GET_UC_USER_INFO_FAIL', 18304); // 非法操作：获取用户列表失败
define('UMS_ORG_INFO_WRONG', 18305); // 非法操作：组织信息不存在
define('UMS_ORG_INFO_SITURL_WRONG', 18306); // 非法操作：组织siturl不存在
define('UMS_SITE_INFO_FAIL', 18307);  // 非法操作：站点不存在

// 11000 - 11999 组织模块
define('ORG_CODE_EMPTY', 11001); // 组织串不能为空
define('POWER_JSON_EMPTY', 11002); // 权限串不能为空
define('POWER_IS_WRONG', 11003); // 权限错误
define('UPDATE_POWER_FAIL', 11004); // 更新权限失败

define('USER_IS_NOT_EXIST', 11101); // 该用户不存在
define('ORG_CODE_IS_WRONG', 11102); // 该用户所在组织不正确

// 16000 - 16999 系统设置
define('SITE_POWER_JSON_EMPTY', 16001); // 权限串不能为空
define('UPDATE_SITE_POWER_FAIL', 16002); // 更新权限失败

// 19000 - 20000  忘记密码
define('IMG_CODE_IS_WRONG', 19001); // 您输入的验证码有误
define('UMS_LOGINNAME_NOT_EXIST', 19002); // 该账号不存在
define('UMS_PRODUCT_NOT_EXIST', 19003); // 该账号不存在
define('MSG_LEN_IS_WRONG', 19004); // 短信验证码不正确
define('MSG_TIME_IS_WRONG', 19005); // 短信验证码已过期
define('MSG_IS_WRONG', 19006); // 短信验证码错误
define('TWO_PWD_NOT_EQUEL', 19008); // 两次输入的密码不相等
define('PWD_ILLIGEL', 19010); // 您输入的密码不合法
define('RESET_USER_PWD_FAIL', 19011); // 修改密码失败
define('YOU_ARE_NOT_ADMIN', 19012); // 您输入的账号不是管理员账号
define('HAVE_NO_PHONE', 19014); // 无法获取到您的手机号，请联系系统管理员

// 20100-20199  修改密码
define('RESET_ADMIN_PWD_FAIL', 20101); // 修改密码失败
define('SAVE_NEW_PWD_FAIL', 20102); // 保存新密码失败
define('OLD_PWD_IS_EMPTY', 20103); // 旧密码不能为空
define('NEW_PWD_IS_EMPTY', 20104); // 新密码不能为空
define('NEW_PWD_IS_EQUEL_OLD_PWD', 20105); // 新旧密码相同
define('CONFIRM_PWD_IS_EMPTY', 20106); // 确认新密码为空
define('NEW_PWD_COMPLIXYTY_WRONG', 20107); // 新密码的复杂性不正确
define('IN_PWD_RECORDS', 20108); // 不能使用历史密码




