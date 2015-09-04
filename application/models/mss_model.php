<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @abstract 	mss邮件模型，主要负责邮件发送功能
 * @author 		Bai Xue<xue.bai_2@quanshi.com>
 * @version		UC 1.0
 */
class Mss_model extends MY_Model {
	
	public $db1;
	
	/**
	 * 构造方法
	 */
	public function __construct(){
		parent::__construct();
		
		// 载入mss数据库
		$this->db1 = $this->load->database('mss', true);
		
		// 配置当前数据库表
		$this->tbl = array(
			'mail' => 'gnet_ss_mail_sending_task',
		);
	}
	
	/**
	 * 发送邮件，这里仅将邮件内容写入mss平台的数据库
	 * @param array $mail_info_arr 邮件内容
	 * $mail_info_arr = array(
	 * 		'receiver_name'		=> '张三',					// 收件人姓名
	 * 		'receiver_address' 	=> 'san.zhang@quanshi.com', // 收件人邮箱
	 * 		'mail_title'		=> '账号开通',				// 邮件标题
	 * 		'mail_content'		=> '' 						// 邮件正文（已替换数据的HTML模板）
	 * 		......
	 * )
	 * @return boolean
	 */
	public function send($mail_info_arr = array()) {
		$this->db1->reconnect();//重连，解决mysql gone away的问题
		$this->db1->insert($this->tbl['mail'], $mail_info_arr);
		
		if($this->db1->insert_id() > 0){
			return true;
		}
		return false;
	}
}