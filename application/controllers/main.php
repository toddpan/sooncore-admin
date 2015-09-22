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
		//var_dump($this->session->userdata);
                //echo $this->p_display_name;
		$data['msg_sum']  = $msg_sum;
		$data['displayName'] = $user_name;
		$this->load->view('index.php', $data);
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
		
		// 站点Id
		$site_id = $this->p_site_id;
		
		// 统计已开通账号的个数
		$where_arr = array(
			'status' => UC_USER_STATUS_ENABLE,// 已开通
			'siteId' => $site_id
		);
		$is_open_users = $this->uc_user_model->countUser($where_arr);
		
		// 统计未开通账号的个数
		unset($where_arr['status']);
		$in_where_arr = array(
			UC_USER_STATUS_UNUSED,// 未开通
			UC_USER_STATUS_DISABLE // 已关闭
		);
		$not_open_users = $this->uc_user_model->countUser($where_arr, $in_where_arr);
		
		// 统计已启用账号的个数
		$is_used_users = $this->uc_user_model->count_is_used_user();
		
		// 统计未启用账号的个数
		$not_used_user = $this->uc_user_model->count_not_used_user();
		
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