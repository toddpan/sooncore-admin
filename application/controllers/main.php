<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @abstract 首页 Controller 后台首页
 * @filesource main.php
 * @author zouyan <yan.zou@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
class Main extends Admin_Controller{
	/**
	 * @abstract 构造方法
	 */
	public function __construct() {
		// 继承父类构造函数
		parent::__construct();
	}

	/**
	 * @abstract 首页方法
	 */
	public function index(){
		log_message('info', 'Into method index');
		
		$this->setFunctions();
		
		// 获得公司简称
		$this->load->model('uc_site_model');
		$cor_info = $this->uc_site_model->getCorNameBySiteId($this->p_site_id);
		$cor_name = isset($cor_info['corName']) ? $cor_info['corName'] : '全时';
		
		// 获得管理员信息
		$this->load->library('StaffLib', '', 'StaffLib');
		$re_user_arr = $this->StaffLib->get_user_by_id($this->p_user_id);
		$user_name = arr_unbound_value($re_user_arr,'displayName',2,'');

		// 任务
		$this->load->model('employee_change_task_model');
		$task_where_arr = array(
				'site_id' 			=> $this->p_site_id,
				'recipient_user_id' => $this->p_user_id,
				'status'  			=> 1
		);
		$task_sum =  $this->employee_change_task_model->countTask($task_where_arr);
		
		// 获得消息
        $this->load->model('uc_notice_model');
        $notice_where_arr = array(
				'site_id ' 		=> $this->p_site_id,
        		'to_user_id ' 	=> $this->p_user_id,
        		'isread' 		=> 0
		);
        $notice_sum =  $this->uc_notice_model->countNotice($notice_where_arr);
		
		// 获得通知
		$this->load->model('uc_message_model');
		$message_where_arr = array(
				'site_id ' 		=> $this->p_site_id,
				'to_user_id ' 	=> $this->p_user_id,
				'isread' 		=> 0
		);
		$message_sum =  $this->uc_message_model->countMessage($message_where_arr);
		$msg_sum = $task_sum + $message_sum + $notice_sum;
		
		$data['corName'] 	= $cor_name;
		$data['msg_sum'] 	 = $msg_sum;
		$data['displayName'] = $user_name;
		$this->load->view('index.php', $data);
	}

	private function setFunctions(){
		$roleFunctions = $this->setFunctionsByRole();
		$customFunctions = $this->setFunctionsBySite();
	
		$functions = array_merge($customFunctions, $roleFunctions);
	
		foreach ($customFunctions as $key=>$value){
			$functions[$key] = $functions[$key] && $value;
		}
	
		$this->functions = $functions;
	}
	
	private function setFunctionsBySite(){
		$functions = array();
		 
		$functions['ChangePassword'] = $this->siteConfig['siteType'] == 0;
	
		return $functions;
	}
	
	private function setFunctionsByRole(){
		$functions = array();
	
		$functions['ManagerManage'] = $this->p_role_id == SYSTEM_MANAGER;
		$functions['OrgManage'] = $this->p_role_id != ECOLOGY_MANAGER;
		$functions['EcologyCompany'] = $this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER || $this->p_role_id == ECOLOGY_MANAGER || $this->p_role_id == CHANNEL_MANAGER;
		$functions['SecurityManage'] = $this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER;
		$functions['SystemSetting'] = $this->p_role_id == SYSTEM_MANAGER;
	
		return $functions;
	}
	
	
	
	
	/**
	 * @abstract 将消息和通知列表显示到首页页面上
	 */
	public function mainPage(){
		log_message('info', 'Into method mainPage.');
		
		// 获得通知
		$this->load->model('uc_message_model');
		$where_arr = array(
				'site_id' 		=> $this->p_site_id,
				'to_user_id' 	=> $this->p_user_id
		);
		$message_arr = $this->uc_message_model->getMessageList($where_arr, 5, 0);
		$data['message'] = $message_arr;
		if(isemptyArray($data['message'])){
			$data['message'] = '暂无通知';
		}

		// 获得消息
		$this->load->model('uc_notice_model');
		$notice_arr = $this->uc_notice_model->getNoticeList($where_arr, 5, 0);
		$data['notice'] = $notice_arr;
		if(isemptyArray($data['notice'])){
			$data['notice'] = '暂无消息';
		}

		// 载入视图页面并显示数据
		$this->load->view('main.php', $data);
	}

	/**
	 * @abstract 显示帮助中心页面
	 */
	public function help(){
		$this->load->view('help.php');
	}
	
	/**
	 * @abstract 统计账号开通情况
	 */
	public function countUser() {	
		// 载入用户模型
		$this->load->model('uc_user_model');
		
		// 统计开通和未开通的账号
		$is_open_users = 0;
		$not_open_users = 0;
		log_message('info', 'start kaitong.');
		$res_open_arr = $this->uc_user_model->count_open_user();
		log_message('info', 'keep kaitong.');
		foreach ($res_open_arr as $open_and_not_open_user_arr){
			if($open_and_not_open_user_arr['st'] == UC_USER_STATUS_ENABLE){
				$is_open_users += $open_and_not_open_user_arr['num']; // 已开通
			}
			if($open_and_not_open_user_arr['st'] == UC_USER_STATUS_UNUSED){
				$not_open_users += $open_and_not_open_user_arr['num']; // 未开通
			}
		}
		log_message('info', 'end kaitong.');
		
		// 统计启用和未启用的账号
		$is_used_users = 0;
		$not_used_user = 0;
		log_message('info', 'start qiyong.');
		$res_user_arr = $this->uc_user_model->count_use_user();
		$is_used_users += isset($res_user_arr['logined_user']) ? $res_user_arr['logined_user'] : 0;
		$not_used_user += (isset($res_user_arr['all_user']) ? $res_user_arr['all_user'] : 0) - $is_used_users;
		
		log_message('info', 'keep qiyong.');
// 		foreach ($res_user_arr as $use_and_not_user_arr){
// 			if($use_and_not_user_arr['st'] == 0){
// 				$not_used_user += $use_and_not_user_arr['num'];
// 			}
// 			if($use_and_not_user_arr['st'] == 1){
// 				$is_used_users += $use_and_not_user_arr['num'];
// 			}
// 		}
		log_message('info', 'end qiyong.');
		
		// 组装数据
		$data = array(
			'is_open_users'  => $is_open_users,
			'not_open_users' => $not_open_users,
			'is_used_users'  => $is_used_users,
			'not_used_user'  => $not_used_user
		);
		
		// 将数据传递到页面
		return_json(COMMON_SUCCESS, '', $data);
	}
	
}