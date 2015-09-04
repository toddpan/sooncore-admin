<?php
//LDAP服务器参数
$lang['servertype0'] 	= '请选择服务类型';
$lang['servertype1'] 	= 'Microsoft Active Directory';//MS_AD
$lang['servertype2'] 	= 'OpenDirectory';//OPENDIRECTORY
$lang['servertype3'] 	= 'Lotus Dimino';//LOTUS_DIMINO
$lang['servertype4'] 	= '其他';//OTHERS

//LDAP连接方式
$lang['authtype_name0'] = '请选择连接方式';
$lang['authtype_name1'] = 'LDAP';
$lang['authtype_name2'] = 'LDAPS';

//网页展示中文信息
$lang['html_title']				= '云企管理中心';
$lang['org_name']				= '组织管理';
$lang['ldap_syn_setting']		= 'ldap同步设置';
$lang['link_ladp']				= '连接LDAP服务器';
$lang['syn_org']				= '选择同步的组织';
$lang['set_staff_infor']		= '指定员工信息';
$lang['syn_staff_info']			= '选择同步的员工信息';
$lang['set_account_rule']		= '设置帐号规则';

$lang['server_type_label']		= '服务器类型';
$lang['link_type_label']		= '连接方式';
$lang['server_addr_label']		= 'LDAP服务器地址';
$lang['server_port_label']		= 'LDAP服务器端口';
$lang['server_user_label']		= 'LDAP服务器用户名';
$lang['server_pass_label']		= 'LDAP服务器密码';
$lang['base_DN_label']			= 'Base DN';
$lang['objectclasses_label']	= '参数验证';
$lang['idAttribute_label']		= '组织ID';
$lang['nameAttribute_label']	= '组织名称';

$lang['wait_message']			= '验证设置中，请稍候...';
$lang['abort']					= '放弃';
$lang['prev_step']				= '上一步';
$lang['next_step']				= '下一步';
$lang['chose_syn_struct']		= '选择要同步的组织结构';
$lang['chose_staff_label']		= '请选择代表员工的标签';
$lang['chose_ldap_message']		= '请选择对应的LDAP信息';
$lang['must_staff_label']		= '必选的员工标签';
$lang['set_uc_account']			= '请设置云企账号';
$lang['use_email_label']		= '使用邮箱作为云企帐号';
$lang['use_prefix_label']		= '指定统一的标签作为帐号前缀';
$lang['chose_label']			= '选择标签';
$lang['assure']					= '确定';
$lang['cancel']					= '取消';
$lang['no_rule']				= '无规则';
$lang['add_rule']				= '增加规则';
$lang['save_set']				= '保存设置';

$lang['message0']				= '请选择';
$lang['message1']				= '同步后，如果在 LDAP 找不到用户信息立即停用并删除';
$lang['message2']				= '请输入不用开通云企帐号的例外规则';
$lang['message3']				= '您可以写入这样一个规则 OU=labourer';

//员工标签信息
$lang['name']			= '姓名';
$lang['first_name']		= '名';
$lang['last_name']		= '姓';
$lang['sex']			= '性别';
$lang['account_id']		= '账号';
$lang['account']		= '账户';
$lang['organization']	= '部门';
$lang['position']		= '职位';
$lang['mobile']			= '手机';
$lang['email']			= '邮箱';
$lang['phone']			= '办公电话';
$lang['user_id']		= '员工ID';
$lang['constcenter']	= '成本中心';
$lang['country_code']	= '国码';
$lang['country']		= '国家';
$lang['officeaddress']	= '办公地址';

//错误信息国际化
$lang['success']					= '成功';
$lang['param_error']				= '参数错误';
$lang['get_organization_error']		= '获取组织失败';
$lang['organization_tree_error']	= '组织树信息格式不合法！';
$lang['get_label_class_error']		= '获取标签类失败！';
$lang['get_attribute_error']		= '获取属性失败！';
$lang['get_ldap_data_error']		= '获取LDAP信息失败！';
$lang['get_ldap_table_error']		= '获取ldap列表失败！';
$lang['update_ldap_success']		= '修改ldap配置成功！';
$lang['update_ldap_fail']			= '修改ldap配置失败！';
$lang['create_ldap_fail']			= '创建ldap配置失败！';
$lang['ldap_name_not_null']			= '创建的ldap名称不能为空！';
$lang['delete_success']				= '删除成功！';
$lang['update_user_status_error']	= '修改用户状态失败！';
$lang['staff_attribute_error']		= '员工属性错误';
$lang['undetermined']				= '待定。。。。';

//参数验证错误
$lang['server_addr_error']		= '服务器地址不合法';
$lang['server_port_error']		= '端口号不合法';
$lang['server_authtype_error']	= '链接类型不合法';
$lang['server_type_error']		= 'ldap服务器类型不合法';
$lang['basedn_not_null']		= '根域不能为空';
$lang['admindn_not_null']		= '管理员DN不能为空';
$lang['password_not_null']		= '管理员密码不能为空';
$lang['objectclasses_not_null']	= '验证参数不能为空';
$lang['synorg_not_null']		= '同步的组织不能为空';
$lang['class_not_null']			= '属性的类为分号分隔的字符串且不能为空';


