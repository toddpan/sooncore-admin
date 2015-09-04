<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @abstract 	权限类
 * @filesource 	Privilege.php
 * @author 		Bai Xue <xue.bai_2@quanshi.com> 2014-11-26
 * @copyright 	Copyright (c) UC
 * @version 	v1.0
 */
class Privilege_class {
	
	// 权限数组
	private static $privilige = array(
			'ldap/showLdapPage' 				=> array(SYSTEM_MANAGER, ORGANIZASION_MANAGER), 					// LDAP同步设置
			'batchimport/index' 				=> array(SYSTEM_MANAGER, ORGANIZASION_MANAGER), 					// 批量导入
			//'controller/function' 			=> array(SYSTEM_MANAGER, ORGANIZASION_MANAGER), 					//TODO 批量变更（等该功能做好后在此处添加配置）
			'organize/saveNewOrg' 				=> array(SYSTEM_MANAGER, ORGANIZASION_MANAGER), 					// 新增部门
			'organize/delOrg' 					=> array(SYSTEM_MANAGER, ORGANIZASION_MANAGER), 					// 删除部门
			'organize/move_org' 				=> array(SYSTEM_MANAGER, ORGANIZASION_MANAGER), 					// 调整部门
			'organize/save_org_power' 			=> array(SYSTEM_MANAGER, ORGANIZASION_MANAGER, EMPPLOYEE_MANAGER), 	// 部门权限
			'tag/addTagPage' 					=> array(SYSTEM_MANAGER, ORGANIZASION_MANAGER, EMPPLOYEE_MANAGER), 	// 查看标签权限
			'organize/set_manager' 				=> array(SYSTEM_MANAGER), 											// 指定组织管理员
			'manager/addManagerPage' 			=> array(SYSTEM_MANAGER, ORGANIZASION_MANAGER), 					// 指定员工管理员
			'manager/modifyManager' 			=> array(SYSTEM_MANAGER, ORGANIZASION_MANAGER), 					// 编辑管理员
			'manager/delManagerPage' 			=> array(SYSTEM_MANAGER, ORGANIZASION_MANAGER), 					// 删除管理员
			'tag/addTag' 						=> array(SYSTEM_MANAGER), 											// 创建自定义标签
			//'controller/function' 			=> array(SYSTEM_MANAGER), 											// 修改标签	此权限已通过创建标签限制过
			//'controller/function' 			=> array(SYSTEM_MANAGER), 											// 删除标签	此权限已通过创建标签限制过
			'staff/add_staff_page' 				=> array(SYSTEM_MANAGER, ORGANIZASION_MANAGER, EMPPLOYEE_MANAGER), 	// 增加员工
			'staff/deleteStaff' 				=> array(SYSTEM_MANAGER, ORGANIZASION_MANAGER, EMPPLOYEE_MANAGER), 	// 删除员工
			'staff/moveStaff' 					=> array(SYSTEM_MANAGER, ORGANIZASION_MANAGER, EMPPLOYEE_MANAGER), 	// 调岗员工
			'staff/add_staff_page' 				=> array(SYSTEM_MANAGER, ORGANIZASION_MANAGER, EMPPLOYEE_MANAGER), 	// 编辑员工
			//'controller/function' 			=> array(SYSTEM_MANAGER, ORGANIZASION_MANAGER, EMPPLOYEE_MANAGER, ACCOUNT_MANAGER), //TODO 开通帐号
			//'controller/function'				=> array(SYSTEM_MANAGER, ORGANIZASION_MANAGER, EMPPLOYEE_MANAGER, ACCOUNT_MANAGER), //TODO 关闭帐号
			//'controller/function' 			=> array(SYSTEM_MANAGER, ORGANIZASION_MANAGER, EMPPLOYEE_MANAGER, ACCOUNT_MANAGER), //TODO 变更帐号权限
			//'controller/function' 			=> array(SYSTEM_MANAGER, ORGANIZASION_MANAGER, EMPPLOYEE_MANAGER, ACCOUNT_MANAGER), //TODO 重置密码
			'information/get_task' 				=> array(SYSTEM_MANAGER, ORGANIZASION_MANAGER, EMPPLOYEE_MANAGER, ACCOUNT_MANAGER), // 任务管理
			'information/get_notice' 			=> array(SYSTEM_MANAGER, ORGANIZASION_MANAGER, EMPPLOYEE_MANAGER, ACCOUNT_MANAGER, ECOLOGY_MANAGER), // 消息管理
			'information/get_message' 			=> array(SYSTEM_MANAGER, ORGANIZASION_MANAGER), 					// 通知管理
			'ecologycompany/ecologyPage' 		=> array(SYSTEM_MANAGER, ORGANIZASION_MANAGER, ECOLOGY_MANAGER), 	// 生态企业管理
			//'controller/function' 			=> array(SYSTEM_MANAGER, ORGANIZASION_MANAGER, EMPPLOYEE_MANAGER, ECOLOGY_MANAGER), //TODO 生态管理员管理（等该功能做好后在此处添加配置）
			'password/PWDManagePage' 			=> array(SYSTEM_MANAGER), 											// 密码规则设置
			'sensitiveword/sensitiveWordPage' 	=> array(SYSTEM_MANAGER, ORGANIZASION_MANAGER, EMPPLOYEE_MANAGER), 	// 敏感词管理
			'log/logPage' 						=> array(SYSTEM_MANAGER, ORGANIZASION_MANAGER), 					// 日志管理
			//'controller/function' 			=> array(SYSTEM_MANAGER, ORGANIZASION_MANAGER, EMPPLOYEE_MANAGER), 	// 用户使用查询（等该功能做好后在此处添加配置）
			//'controller/function' 			=> array(SYSTEM_MANAGER, ORGANIZASION_MANAGER), 					// 财务分析报告（等该功能做好后在此处添加配置）
			//'controller/function' 			=> array(SYSTEM_MANAGER, ORGANIZASION_MANAGER, EMPPLOYEE_MANAGER, ACCOUNT_MANAGER), // 账号分析报告（等该功能做好后在此处添加配置）
			//'controller/function' 			=> array(SYSTEM_MANAGER, ORGANIZASION_MANAGER, EMPPLOYEE_MANAGER, ACCOUNT_MANAGER), // 使用行为报告（等该功能做好后在此处添加配置）
			//'controller/function' 			=> array(SYSTEM_MANAGER, ORGANIZASION_MANAGER, ECOLOGY_MANAGER), 	// 生态热点报告（等该功能做好后在此处添加配置）
			//'controller/function' 			=> array(SYSTEM_MANAGER, ORGANIZASION_MANAGER, EMPPLOYEE_MANAGER), 	// 导出报告（等该功能做好后在此处添加配置）
			'systemset/company' 				=> array(SYSTEM_MANAGER), // 企业信息
			'setsystem/save_sys_power' 			=> array(SYSTEM_MANAGER), // 站点应用设置
		);
	
	/**
	 * 构造方法
	 */
	private function __construct() {
		
	}
	
	/**
	 * 通过角色Id、控制器名和方法名判断当前管理员是否具备某权限
	 * @param int 		$role_id		角色Id	
	 * @param string 	$controller		控制器名
	 * @param string 	$function		方法名
	 * @return boolean
	 */
	public static function _isPrivilege($role_id, $controller, $function){
		// 将参数控制器与方法名连接起来作为指针
		$cf = $controller . $function;
		
		// 判断指针在权限数组的键中是否存在
		$res_key = array_keys($cf, self::$privilige, true);
		if($res_key){
			// 存在，则判断是否有权限
			if(!in_array($role_id, self::$privilige[$cf])){
				return false;
			}
		}
		return true;
	}
}