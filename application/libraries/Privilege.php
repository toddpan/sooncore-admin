<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 系统权限布置
 * @detail
 * 用户的每一个操作都被检查，检查当前用户的角色
 * 根据角色权限表，来判断当前用户是否有权限进行此项操作
 * 如果没有权限则停止操作，返回信息
 * @file Privilge.php
 * @author caohongliang <hongliang.cao@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
class Privilege{
	
	private $request_key;
	private $privilege_ids;
	private $request_key_map ;
	public function __construct($request_key, $privilege_ids){
		$this->request_key     = strtolower($request_key);
		$this->privilege_ids   = $privilege_ids;
		//请求"控制器|方法"和权限id映射关系
		//*表示全部
		$this->request_key_map = array(
			'ldap|*'							=>101,    //LDAP同步设置
			'bulkimport|*'						=>102,    //批量导入
			'staff|batchmodifystaff'			=>103,    //批量变更
			'organize|saveneworg'				=>104,    //新增部门
			'organize|delorg'					=>105,    //删除部门
			''									=>106,    //调整部门
			'organize|power'					=>107,    //部门权限 TODO
			'tag_xxx'							=>108,    //查看标签权限TODO
			'organize|set_manager'				=>201,    //指定组织管理员
			'xxx'								=>202,    //指定员工管理员
			'xxx'								=>203,    //编辑管理员
			'xxx'								=>204,    //删除管理员
			'xxx'								=>301,    //创建自定义标签
			'xxx'								=>302,    //修改标签
			'xxx'								=>303,    //删除标签
			'staff|add_staff_page'				=>304,    //增加员工
			'staff|deletestaff'					=>305,    //删除员工
			'staff|movestaff'					=>306,    //调岗员工
			'staff|save_staff'					=>307,    //编辑员工
			'staff|open_user'					=>401,    //开通账号
			'staff|closeaccount'				=>402,    //关闭账号
			'staff|close_user'					=>402,    //关闭账号
			'staff|save_user_power'				=>403,    //变更账号权限
			'password|showtemppwd'				=>404,    //重置密码
			'staff|reset_pwd'					=>404,    //重置密码
			'task|*'							=>501,    //任务管理
			'information|*'						=>502,    //消息管理
			'notice|*'							=>503,    //通知管理
			'ecologycompany|ecologypage'		=>601,    //生态企业管理
			'ecologycompany|ecologycompanylist' =>602,    //生态管理员管理
			'password|pwdmanagepage'			=>701,    //密码规则设置
			'sensitiveword|sensitivewordpage'	=>702,    //敏感词管理
			'log|logpage'						=>703,    //日志管理
			'useraction|useractionpage'			=>704,    //用户使用查询
			'801'								=>801,    //财务分析报告
			'802'								=>802,    //帐号分析报告
			'803'								=>803,    //使用行为报告
			'804'								=>804,    //生态热点报告
			'805' =>805,    //导出报告
			'setsystem|setsystempage' =>901,    //企业信息
			'setsystem|get_sys_power' =>902     //站点应用设置
		); 
		log_message('info', 'Privilege check! the request key is->'.$this->request_key.' current role privilege ids is->'.var_export($this->privilege_ids, true));
	}
	
	/**
	 * 用户权限判断
	 * @return boolean
	 */
	public function hasPrivilege(){
		list($controller,$action) = explode('|', $this->request_key);
		$p_id = 0;
		if(isset( $this->request_key_map[$controller.'|*'] )){
			$p_id = $this->request_key_map[$controller.'|*'];
		}
		if(isset($this->request_key_map[$this->request_key])){
			$p_id  =  $this->request_key_map[$this->request_key];
		}
		
		if($p_id>0){
			if(in_array($p_id, $this->privilege_ids)){//判断是否在“权限控制”范围之内
				return true;
			}else{
				return false;
			}
		}
		return true;
	}
	
	
}
