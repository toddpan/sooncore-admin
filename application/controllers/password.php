<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @abstract 	PassWord Controller，密码管理控制器，主要用于用户进行密码管理变更设置。
 * @filesource 	password.php
 * @author 		yanzou <yan.zou@quanshi.com>
 * @copyright 	Copyright (c) UC
 * @version 	v1.0
 */
class PassWord extends Admin_Controller{
	/**
	 * @abstract 构造函数
	 */
	public function __construct() {
		// 继承父类构造函数
		parent::__construct();
		// 载入公共辅助函数
		$this->load->helper('my_publicfun');
		$this->load->helper('my_httpcurl');
		// 载入API
		$this->load->library('API','','API');
		// 载入用户密码管理模型
		$this->load->model('uc_pwd_manage_model');
	}

	/**
	 * @abstract 显示密码变更设置管理页面
	 * @details
	 * -# 从UC获得当前站点、当前企业密码管理设置
	 * -# 将获得的密码管理设置分配到视图
	 */
	public function PWDManagePage() {
		// 定义组织id
		$org_id = $this->p_org_id;
		// 定义站点id
		$site_id = $this->p_site_id;

		// 根据当前组织id和站点id获得当前的密码规则
		$in_arr = array(
                     'org_id' => $org_id,
                     'site_id' => $site_id,
		);
		$data['pwdArr'] = $this->uc_pwd_manage_model->get_pwd_arr($in_arr) ;

		// 将数据分配到页面
		$this->load->view('password/pwdmanagelist.php', $data);
	}

	/**
	 * @abstract 新加/修改密码管理
	 * @details
	 * -# 从表单获取密码管理设置
	 * -# 将密码设置保存到库
	 */
	public function modifyPWDManage() {
		// 获取表单提交的数据
		$expiry_day_type = $this->input->post('expiry_day_type', true);
		$history_type = $this->input->post('history_type', true);
		$complexity_type = $this->input->post('complexity_type', true);
		log_message('info', __FUNCTION__." input->\n".var_export(array('expiry_day_type' => $expiry_day_type, 'history_type' => $history_type, 'complexity_type' => $complexity_type), true));

		// 设置表单验证标识符：0、无错；1、出错
		$is_err = 0;

		// 对表单提交的数据进行验证
		if(!preg_match("/[1-5]{1}/", $expiry_day_type)){
			$is_err = 1;
			form_json_msg('1','','非法的用户密码有效期');
		}
		if(!preg_match("/[1-4]{1}/", $history_type)){
			$is_err = 1;
			form_json_msg('1','','非法的密码有效记忆');
		}
		if(!preg_match("/[1-3]{1}/", $complexity_type)){
			$is_err = 1;
			form_json_msg('1','','非法的密码复杂性');
		}

		if($is_err == 0){
			$this->load->model('UC_PWD_Manage_Model');
			
			$org_id = $this->p_org_id;
			$site_id = $this->p_site_id;
			
			//根据当前组织id和站点id获得当前的密码规则
			$in_arr = array(
                 'org_id' => $org_id,//企业id
                 'site_id' => $site_id,//站点id
			);
			$pwd_arr = $this-> UC_PWD_Manage_Model ->get_pwd_arr($in_arr) ;
			// 获得当前密码复杂性
			$old_complexity_type = arr_unbound_value($pwd_arr,'complexity_type',2,'');

			//执行修改操作，没有记录则新加，有记录则修改
			$select_field = 'id';
			$where_arr = array(
                     'org_id' => $org_id,//企业id
                     'site_id' => $site_id,//站点id
			);
			$modify_arr = array(
                     'org_id' => $org_id,//企业id
                     'site_id' => $site_id,//站点id
                     'expiry_day_type' => $expiry_day_type,//用户密码有效期1、30天2、60天3、90天4、180天5、不需要变更默认90天
                     'history_type' => $history_type,//密码历史记忆1、3次2、5次3、10次4、不记忆默认是3次
                     'complexity_type' => $complexity_type,//密码复杂性要求1、8-30位，不限制类型2、8-30位数字与字母组合3、8-30位数字、符号与字母组合

			);
			$insert_arr = $modify_arr;
			$insert_arr['create_time'] = dgmdate(time(), 'dt');
			$re_num = $this->UC_PWD_Manage_Model->updata_or_insert(1,$select_field,$where_arr,$modify_arr,$insert_arr);
			if($re_num == -2 || $re_num == -4){//失败 -1 更新记录成功 -2更新操作失败 -3新加的记录id 新加记录成功 -4新加操作失败 -5 没有操作
				$err_msg = 'update/insert  uc_user_config_model fail. $re_num =' . $re_num . ' ';
				log_message('error', $err_msg);
				form_json_msg('10','',$err_msg);//返回错误信息json格式
			}
		}

		//TODO 调用战役消息接口
		//如果修改了密码规则之后，密码的复杂度升高，则调用uccserver 系统通知消息发送
		if($old_complexity_type < $complexity_type){
			//接口参参数
			$json_arr = array(
                    'type' => $complexity_type
			);
			$data = 'session_id=' . $this->p_session_id . '&user_id=' . $this->p_user_id . '&type=2&data=' . json_encode($json_arr);

			//调用发送消息接口
			$ucc_msg_arr = $this->API->UCCServerAPI($data,11);
			//print_r($ucc_msg_arr);
			if(!api_operate_fail($ucc_msg_arr)){//成功
				log_message('info', '  uccserver api message/systemAlert ' . $data . ' success .');
			}else{//失败
				log_message('info', '  uccserver api message/systemAlert ' . $data . ' fail .');
				form_json_msg('3','','调用发送信息失败');
			}
		}
		form_json_msg('0','','密码规则修改成功');
	}


	/**
	 * @abstract 显示提醒重置个人密码的页面
	 */
	public function showTempPWD(){
		$this->load->view('password/resetpwd.php');
	}
}
