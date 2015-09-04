<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @abstract 	邮件发送类，主要负责将邮件相关信息保存到数据库中（确保MSS邮件发送进程已开启）。
 * @filesource 	MssLib.php
 * @author 		Bai Xue <xue.bai_2@quanshi.com>  2015-01-29
 * @copyright 	Copyright (c) UC
 * @version		v1.0
 */
class MssLib {
	
	public $CI;
	
	/**
	 * 构造方法
	 */
	public function __construct() {
		$this->CI = & get_instance();
	}
	
	/**
	 * 保存邮件到数据库
	 * 
	 * @param array 	$mail_template_set		邮件模板替换项配置（具体配置详见save_mail()方法中的switch-case语句中的注释）
	 * @param int 		$mail_type				邮件类型：1、管理员账号开通（正式版）；2、一般用户账号开通（正式版）；3、管理员账号开通（试用版）；4、管理员试用资格变更；5、管理员试用期到期；6、重置密码；
	 * 
	 * @return boolean  true/false
	 */
	public function save_mail($mail_template_set = array(), $mail_type = 0){
		log_message('info', 'Into method save_mail, input ---> ' . var_export(array('mail_template_set' => $mail_template_set, 'mail_type' => $mail_type), true));
		
		// 邮件模板配置
		$mail_template_set['blueline'] 		= MAILIMG . 'blueline.jpg'; 	// 全时蜜蜂左边的蓝条
		$mail_template_set['toparrow'] 		= MAILIMG . 'toparrow.jpg'; 	// 上边图片
		$mail_template_set['loginsub'] 		= MAILIMG . 'loginsub.png'; 	// 中间登录条
		$mail_template_set['uploadpc'] 		= MAILIMG . 'uploadpc.png'; 	// 中间更新条
		$mail_template_set['botarrow'] 		= MAILIMG . 'botarrow.jpg'; 	// 下边图片
		$mail_template_set['powerby'] 		= MAILIMG . 'powerby.png';  	// 邮件底部版权
		$mail_template_set['tel'] 			= MAILIMG . 'tel.png'; 	 		// 邮件底部全时电话
		$mail_template_set['codeios'] 		= MAILIMG . 'codeios.jpg'; 		// IOS二维码
		$mail_template_set['codeandroid']	= MAILIMG . 'codeandroid.jpg'; 	// 安卓二维码
		$mail_template_set['apple'] 		= MAILIMG . 'apple.png'; 	 	// 苹果图标
		$mail_template_set['android'] 		= MAILIMG . 'android.png'; 	 	// 邮件底部全时电话
		$mail_template_set['android_ios'] 	= MAILIMG . 'android_ios.jpg'; 	// 移动端下载地址
		$mail_template_set['login_link'] 	= MAIL_LINK; 	 				// 登录链接
		$mail_template_set['download_link'] = MAIL_DOWNLOAD_LINK; 	 		// 下载链接
		
		//var_dump($mail_template_set);
		
		$mail_info_arr 	= array(); 	// 初始化邮件信息数组
		$mail_content 	= ''; 		// 邮件正文
		$mail_title 	= '';		// 邮件标题
		
		// 根据邮件类型设置邮件标题，并获得邮件模板
		switch ($mail_type) {
			case MANAGER_CREATE_MAIL:	
				$mail_title = '【蜜蜂通知】管理员账号开通';
				/*
				 * $mail_template_set = array(
				 * 			'user_name' 	=> '张三',
				 * 			'login_name' 	=> 'san.zhang@quanshi.com',
				 * 			'password' 		=> '111111',
				 * 			'cor_name' 		=> '海尔',  // 该数据需要调用的程序自己获取，先看有没有企业简称，没有则为企业全称
				 * 			'email'			=> 'san.zhang@quanshi.com'  // 收件人邮箱
				 * 		);
				 */
				$mail_content = $this->CI->load->view('mail_templates/manager_create.php', $mail_template_set, true);
				break;
			case USER_CREATE_MAIL: 
				$mail_title = '【蜜蜂通知】用户账号开通';
				/*
				 * $mail_template_set = array(
		 		 * 			'user_name' 	=> '张三',
		 		 * 			'login_name' 	=> 'san.zhang@quanshi.com',
		 		 * 			'password' 		=> '111111',
		 		 * 			'cor_name' 		=> '海尔',  // 该数据需要调用的程序自己获取，先看有没有企业简称，没有则为企业全称
		 		 * 			'email'			=> 'san.zhang@quanshi.com'  // 收件人邮箱
		 		 * 		);
				 */
				$mail_content = $this->CI->load->view('mail_templates/user_create.php', $mail_template_set, true);
				break;
			case MANAGER_CRIPPLEWARE_CREATE_MAIL: 
				$mail_title = '【蜜蜂通知】管理员试用账号开通';
				$mail_content = $this->CI->load->view('mail_templates/manager_crippleware_create.php', $mail_template_set, true);
				break;
			// 以下注释部分暂未对模板内容进行配置
// 			case MANAGER_CRIPPLEWARE_UPDATE_MAIL: 
// 				$mail_title = '【蜜蜂通知】管理员试用资格变更';
// 				$mail_content = $this->CI->load->view('mail_templates/manager_crippleware_update.php', $mail_template_set, true);
// 				break;
// 			case MANAGER_CRIPPLEWARE_DEADLINE_MAIL: 
// 				$mail_title = '【蜜蜂通知】管理员试用期到期';
// 				$mail_content = $this->CI->load->view('mail_templates/manager_crippleware_deadline.php', $mail_template_set, true);
// 				break;
			case RESET_PWD_SUC_MAIL: 
				$mail_title = '【蜜蜂通知】重置密码成功';
				/*
				 * $mail_template_set = array(
				 		* 			'user_name' 	=> '张三',
				 		* 			'password' 		=> '111111',
				 		* 			'cor_name' 		=> '海尔',  // 该数据需要调用的程序自己获取，先看有没有企业简称，没有则为企业全称
				 		* 			'email'			=> 'san.zhang@quanshi.com'  // 收件人邮箱
				 		* 		);
				*/
				$mail_content = $this->CI->load->view('mail_templates/reset_pwd_suc.php', $mail_template_set, true);
				break;
			case MANAGER_SET_MAIL:
				$mail_title = '【蜜蜂通知】管理员身份设置成功';
				/*
				 * $mail_template_set = array(
				 		* 			'user_name' 	=> '张三',
				 		* 			'login_name' 	=> 'san.zhang@quanshi.com',
				 		* 			'password' 		=> '',
				 		* 			'cor_name' 		=> '海尔',  // 该数据需要调用的程序自己获取，先看有没有企业简称，没有则为企业全称
				 		* 			'email'			=> 'san.zhang@quanshi.com'  // 收件人邮箱
				 		* 		);
				*/
				$mail_content = $this->CI->load->view('mail_templates/manager_create.php', $mail_template_set, true);
				break;
			default:
				break;
		}
		
		// 配置邮件信息
		$mail_info_arr['create_time'] 		= date('Y-m-d H:i:s');	// 创建时间
		$mail_info_arr['update_time'] 		= date('Y-m-d H:i:s');	// 更新时间
		$mail_info_arr['postfix_address'] 	= MSS_SERVER;			// 服务器ip
		$mail_info_arr['result_code']		= MSS_SENDING_STATE;	// 邮件待发送状态
		$mail_info_arr['mail_title'] 		= $mail_title;			// 邮件标题
		$mail_info_arr['sender_name'] 		= MSS_SENDER_NAME;		// 发件人
		$mail_info_arr['sender_address'] 	= MSS_SENDER_ADDRESS;	// 发件人邮箱
		$mail_info_arr['mail_content'] 		= $mail_content;		// 邮件正文
		$mail_info_arr['receiver_name'] 	= isset($mail_template_set['user_name']) ? $mail_template_set['user_name'] : '';// 收件人姓名
		$mail_info_arr['receiver_address'] 	= isset($mail_template_set['email']) ? $mail_template_set['email'] : '';		// 收件人邮箱
		
		// 将邮件信息保存到数据库
		$this->CI->load->model('mss_model');
		$result = $this->CI->mss_model->send($mail_info_arr);
		
		log_message('info', 'Out method save_mail, output --->' . var_export(array('result' => $result), true));
		
		return $result;
		
	}
}
