<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @abstract 	account Controller，主要是对外接口，负责创建、修改账号。[属于其它不用登陆就可以运行的页面]
 * @filesource 	account.php
 * @author 		jingchaoSun <jingchao.sun@quanshi.com>
 * @copyright 	Copyright (c) UC
 * @version 	v1.0
 */
class Account extends  Run_Controller{
	/**
	 * @abstract 构造函数
	 */
	public function __construct() {
		parent::__construct();
		$this->load->helper('my_publicfun');
		$this->load->helper('my_dgmdate');
		log_message('info', 'into class ' . __CLASS__ . '.');
	}
	
	/**
	 * @abstract 开通消息接口[将消息保存数据库]。BOSS调用站点开通时，调用域分配后，域分配调用此接口
	 * @return int 是否成功保存消息 {'code':1}0成功1失败
	 */
	public function saveOpenThread() {
		// BOSS传递过来的数据（如果是批量导入，数据）
		$value 	= $this->input->post('value', true);
		// 类型：1BOSS客户开通线程；2用户开通线程；3批量修改线程
		$type 	= $this->input->post('type', true);
		log_message('info', __FUNCTION__." input->\n".var_export(array('value' => $value, 'type' => $type), true));
		
		// 判断BOSS传递过来的数据是否为空
		if(bn_is_empty($value)){
			log_message('error', 'The BOSS param named $value is empty.');
			form_json_msg('1', '', 'The BOSS param named $value is empty.', array());
		}
		
		// 将BOSS传递过来的数据转换成数组
		$ns_value_arr = json_decode($value, true);
		
		// 判断转换后的数组是否为空
		if(isemptyArray($ns_value_arr)){
			log_message('error', 'The array named $ns_value_arr is empty.');
			form_json_msg('1', '', 'The array named $ns_value_arr is empty.', array());
		}
		write_test_file( __FUNCTION__ . time() . '.txt', $value);
		
		// 判断类型是否合法
		if(!preg_match('/^[1-9]$/', $type)){
			log_message('error', 'The BOSS post param named $type is wrong.');
			form_json_msg('1', '', 'The BOSS post param named $type is wrong.', array());
		}

		// 载入uc_thread模型
		$this->load->model('UC_Thread_Model');

		//新加入数据,注意，这里只是保存，在运行Thread时，要注意去确认客户编码[customerCode]是否有对应的站点记录
		$data = array(
            'value' 	=>	$value,					// BOSS开通时,传过来的串
            'isvalid' 	=>	1,						// 是否有效：0无效，1有效
            'type' 		=>	$type,					// 类型：1BOSS客户开通线程；2用户开通线程
            'created' 	=> 	dgmdate(time(), 'dt')	// 创建时间
		);

		$insert_thread_arr =  $this->UC_Thread_Model->insert_db($data);
		if(!db_operate_fail($insert_thread_arr)){
			// 成功
			form_json_msg('0', '', '成功', array());
		}else{
			// 失败
			log_message('error', 'insert UC_Thread type is ' . $type . ' fail.');
			form_json_msg('1', '', '保存记录失败', array());
		}
		log_message('info', 'out method ' . __FUNCTION__ . '.');
	}


	/**
	 * @brief 创建管理员
	 * @details
	 * -# 创建UC的管理员角色
	 */
	public function addAdmin(){

	}

	/**
	 * @brief 创建该账号的额外操作
	 * @details
	 * -# 调用UCC接口建立MQ的Exchange及用户对应的聊天数据库
	 * -# 获取用户的开通属性
	 * -# 将开通属性配置到会议服务中去
	 * -# 发送通知，如果有邮件，则发送邮件通知；如果没有邮件，则发送短信通知。
	 * -# 内部发送通知到管理员
	 * @return 0:失败；1：成功
	 */
	public function extendOperation(){

	}

	/**
	 * @brief 增加站点配置
	 * @details
	 * -# 将站点配置增加到数据库表中
	 */
	public function addSite(){

	}

	/**
	 * @brief 停用账号
	 * @details
	 * -# 获取停用的userID
	 * -# 调用会议服务接口，停用该账号
	 * @return 0:失败；1：成功
	 */
	public function closeAccount(){
	}

	/**
	 * @breif 启用账号
	 * @details
	 * -# 获取启用的userID
	 * -# 调用会议服务接口，启用该账号
	 * @return 0:失败；1：成功
	 */
	public function reuseAccount(){
	}

	/**
	 * @brief 校验该站点及用户是否存在
	 * @details
	 * -# 如果存在，则返回0。
	 * -# 如果不存在，则返回1
	 * @return 0：失败；1：成功
	 */
	public function isExistUser(){
	}

	/**
	 * @brief 发送手机短信
	 * @details
	 * -# 发送短信
	 */
	public function sendPhoneMsg(){
		$phone = $_REQUEST['phone'];
	}

	/**
	 * @brief 验证客户输入的数据
	 * @details
	 * -# 获取Session中的验证码
	 * -# 验证客户输入的数据是否为生成随机码
	 * -# 如果验证成功，则调用更新UMS的接口，更新LoginName,pwd
	 * -# 调用BOSS接口开通试用客户
	 * -# 开通成功后，调用addAdmin/addSite/extendOperation 3步操作
	 */
	public function checkCode(){
		$code = $_REQUEST['code'];
	}
}
