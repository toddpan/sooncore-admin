<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Email_Model extends MY_Model{
	
	public function __construct(){
		parent::__construct();
		
		$this->load->database('email');
		
		$this->tbl = array(
			'admin_active'=>'formal_admin_active',
		);
		
	}
	
	/**
	 * 发送邮件，这里仅将数据写入到webpower平台的数据库
	 * 
	 * 
	 * @param array $send_data 要发送的数据
	 * array(
	 * 		'title'=>'账号开通通知', 			//邮件标题
	 *		'user_name'=>'张三',   				//用户名
	 *		'auth_user'=>'李四',   				//?
	 *		'password'=>1234567, 				//密码
	 *		'login_url'=>'www.quanshi.com',		//登录地址
	 *		'email'=>'san.zhang@quanshi.com'	//发送地址
	 * )
	 * 
	 */
	public function send($send_data){
		
		$this->db->insert($this->tbl['admin_active'], $send_data);
		
		return $this->db->insert_id();
	}
	
}
