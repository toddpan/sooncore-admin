<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @abstract 	Mixture Controller，主要负责修改系统管理员密码，以及展示帮助中心页面。
 * @filesource 	mixture.php
 * @author 		Bai Xue <xue.bai_2@quanshi.com>
 * @copyright 	Copyright (c) UC
 * @version		v1.0
 */
class Mixture extends Admin_Controller {
	
	/**
	 * 构造方法
	 */
	public function __construct() {
		parent::__construct();
		
		$this->load->language('resetpwd', 'chinese');
		log_message('info', 'Into class Mixture.');
	}
	
	/**
	 * 显示修改个人密码的页面
	 */
	public function resetPwd() {
		log_message('info', 'Into method resetPwd');
		
		// 从数据库中获得当前站点的密码配置
		$current_pwd_set_arr = $this->get_pwd_set_from_db();
		
		// 从密码配置中取出密码复杂度类型（默认是2：8-30位数字与字母组合）
		$pwd_complexity_type = isset($current_pwd_set_arr['complexity_type']) ? $current_pwd_set_arr['complexity_type'] : DEFAULT_PWD_COMPLEXITY_TYPE;
		
		// 创建密码配置对象
		$pwd_obj = $this->create_pwd_obj();
		
		// 根据密码复杂性从密码配置对象中取出对应的复杂性规则
		$current_pwd_arr = array();
		$complexity_arr = $pwd_obj->get_complexity_type_arr();
		foreach ($complexity_arr as $complexity_type){
			if($complexity_type['id'] == $pwd_complexity_type){
				$current_pwd_arr = $complexity_type;
				break;
			}
		}
		
		// 将数据传到页面上
		$data['current_pwd_arr'] = $current_pwd_arr;
		$this->load->view('mixture/resetpwd.php', $data);
	}
	
	/**
	 * 接收、处理并保存修改后的新密码
	 */
	public function saveNewPwd() {
		$oldpwd 		= $this->input->post('oldPwd',true); 	// 旧密码
		$newpwd 		= $this->input->post('newPwd',true); 	// 新密码
		$confirm_newpwd = $this->input->post('repeatPwd',true);	// 确认新密码
		log_message('info', 'Into method saveNewPwd input ---> $oldpwd=' . $oldpwd . ',$newpwd=' . $newpwd . ',$confirm_newpwd=' . $confirm_newpwd);
		
		// 验证表单提交的数据
		$current_num = $this->valid_pwd($oldpwd, $newpwd, $confirm_newpwd);
		
		// 调用UMS接口修改密码
		$this->load->library('UmsLib', '', 'ums');
		$res = $this->ums->resetUserPasswordValidOldPwd($this->p_user_id, $oldpwd, $newpwd);
		if(!$res){
			form_json_msg(RESET_ADMIN_PWD_FAIL, '', $this->lang->line('resetpwd_failed'), array()); // 修改密码失败
		}
		
		// 向密码历史记录中保存新密码
		$this->save_newpwd($newpwd, $current_num);
		
		form_json_msg(COMMON_SUCCESS, '', $this->lang->line('resetpwd_success'), array()); // 修改密码成功
	}
	
	
	/**
	 * 向密码历史记录中保存新密码
	 * @param string $newpwd
	 * @param int 	 $current_num  系统设置的历史密码记忆次数
	 */
	public function save_newpwd($newpwd, $current_num) {
		if($current_num == 0){
			$this->insert_pwd($newpwd);
		}else{
			// 获得密码历史记录
			$pwd_records_arr = $this->get_pwd_records_arr();
				
			// 统计密码历史记录的个数
			$records_num = count($pwd_records_arr);
				
			// 判断密码历史记录的个数是否与设置的记忆次数相等
			if($current_num > $records_num){ // 添加记录
				log_message('info', '11');
				$this->insert_pwd($newpwd);
			}
			if($current_num == $records_num){// 删除最旧的记录，添加新记录
				log_message('info', '22');
				$this->del_records($pwd_records_arr, $current_num, $records_num);
				$this->insert_pwd($newpwd);
			}
			if($current_num < $records_num){// 删除多余的记录，更新最后一条记录
				log_message('info', '33');
				$this->del_records($pwd_records_arr, $current_num, $records_num);
				$this->update_pwd($newpwd, $pwd_records_arr);
			}
		}
	}
	
	/**
	 * 保存新密码
	 * @param unknown $newpwd
	 */
	public function insert_pwd($newpwd){
		$this->load->model('password_change_history_model');
		$insert_data = array(
				'user_id' => $this->p_user_id,
				'site_id' => $this->p_site_id,
				'password' => md5($newpwd),
				'create_time' => time()
		);
		$res = $this->password_change_history_model->save_newpwd($insert_data);
			
		if(!$res){
			form_json_msg(SAVE_NEW_PWD_FAIL, '', $this->lang->line('resetpwd_failed'), array()); // 修改密码失败
		}
	}
	
// 	/**
// 	 * 更新密码记录
// 	 * @param string $newpwd
// 	 * @param array $pwd_records_arr
// 	 */
// 	public function update_pwd($newpwd, $pwd_records_arr) {
// 		$current_record = current($pwd_records_arr);
// 		$current_id = $current_record['id'];
		
// 		$this->load->model('password_change_history_model');
// 		$where_arr = array(
// 				'id' => $current_id
// 		);
// 		log_message('info', 'current_id'. $current_id);
// 		$update_data = array(
// 				'password' => md5($newpwd),
// 				'create_time' => time()
// 		);
// 		$res = $this->password_change_history_model->update_newpwd($where_arr, $update_data);
			
// 		if(!$res){
// 			form_json_msg(SAVE_NEW_PWD_FAIL, '', $this->lang->line('resetpwd_failed'), array()); // 修改密码失败
// 		}
// 	}
	
	/**
	 * 删除密码记录
	 * @param array $pwd_records_arr
	 */
	public function del_records($pwd_records_arr, $current_num, $records_num) {
		$this->load->model('password_change_history_model');
		$pwd_records_arr = array_reverse($pwd_records_arr);
		$loop = ($records_num - $current_num) == 0 ? 1 : ($records_num - $current_num);
		for($i = 0; $i < $loop; $i++){
			$current_id = $pwd_records_arr[$i]['id'];
				
			$where_arr = array(
					'id' => $current_id
			);
			$res = $this->password_change_history_model->del_pwd($where_arr);
			if(!$res){
				form_json_msg(SAVE_NEW_PWD_FAIL, '', $this->lang->line('resetpwd_failed'), array()); // 修改密码失败
			}
		}
	}
	
	/**
	 * 从数据库中获得当前站点的密码配置
	 */
	public function get_pwd_set_from_db() {
		$this->load->model('uc_pwd_manage_model');
		$current_pwd_set_arr = $this->uc_pwd_manage_model->get_pwd_manage_arr($this->p_org_id, $this->p_site_id);
		
		return $current_pwd_set_arr;
	}
	
	/**
	 * 创建密码配置对象
	 */
	public function create_pwd_obj() {
		// 加载密码配置类
		include_once APPPATH . 'libraries'. DIRECTORY_SEPARATOR . 'public'. DIRECTORY_SEPARATOR .'Pass_word_attr.php';
		$pwd_obj = new Pass_word_attr();
		
		return $pwd_obj;
	}
	
	/**
	 * 验证表单提交的密码
	 * @param 	string 	$oldpwd 			旧密码
	 * @param 	string 	$newpwd 			新密码
	 * @param 	string 	$confirm_newpwd 	确认新密码
	 */
	private function valid_pwd($oldpwd, $newpwd, $confirm_newpwd) {
		log_message('info', 'Into method valid_pwd input ---> $oldpwd=' . $oldpwd . ',$newpwd=' . $newpwd . ',$confirm_newpwd=' . $confirm_newpwd);
		
		// 验证旧密码是否为空
		if(is_empty($oldpwd)){
			form_json_msg(OLD_PWD_IS_EMPTY, 'oldPwd', $this->lang->line('resetpwd_empty_oldpwd'), array());
		}
		
		// 验证新密码是否为空
		if(is_empty($newpwd)){
			form_json_msg(NEW_PWD_IS_EMPTY, 'newPwd', $this->lang->line('resetpwd_empty_newpwd'), array());
		}
		
		// 验证新密码是否与旧密码相同
		if($newpwd == $oldpwd){
			form_json_msg(NEW_PWD_IS_EQUEL_OLD_PWD, 'newPwd', $this->lang->line('resetpwd_newpwd_equal_oldpwd'), array());
		}
		
		// 验证确认新密码是否为空
		if(is_empty($confirm_newpwd)){
			form_json_msg(CONFIRM_PWD_IS_EMPTY, 'repeatPwd', $this->lang->line('resetpwd_empty_confirm_newpwd'), array());
		}
		
		// 验证确认新密码是否与新密码相同
		if($newpwd != $confirm_newpwd){
			form_json_msg(TWO_PWD_NOT_EQUEL, 'repeatPwd', $this->lang->line('resetpwd_newpwd_not_equal_confirm_newpwd'), array());
		}
		
		// 从数据库中获得当前站点的密码配置
		$current_pwd_set_arr = $this->get_pwd_set_from_db();
		
		// 从当前站点的密码配置中取出密码复杂度类型（默认是2：8-30位数字与字母组合）、密码有效期（默认是3：90天）、密码历史记忆次数（默认是1：3次）
		$pwd_complexity_type = isset($current_pwd_set_arr['complexity_type']) ? $current_pwd_set_arr['complexity_type'] : DEFAULT_PWD_COMPLEXITY_TYPE;
		$history_type = isset($current_pwd_set_arr['history_type']) ? $current_pwd_set_arr['history_type'] : DEFAULT_PWD_HISTORY_TYPE;
		
		// 创建密码配置对象
		$pwd_obj = $this->create_pwd_obj();
		
		// 验证新密码的复杂性
		$this->valid_pwd_complexity($pwd_obj, $newpwd, $pwd_complexity_type);
		
		// 获取历史密码记录
		$pwd_records_arr = $this->get_pwd_records_arr();
		
		$current_num = 0;
		log_message('info', 'current_num1' . var_export($pwd_records_arr, true));
		if(!isemptyArray($pwd_records_arr)){
			
			// 验证新密码是否属于历史密码的范畴
			$current_num = $this->valid_pwd_history($pwd_obj, $pwd_records_arr, $newpwd, $history_type);
		}
		
		return $current_num; // 返回密码记忆次数
	}
	
	/**
	 * 验证新密码的复杂性
	 */
	public function valid_pwd_complexity($pwd_obj, $newpwd, $pwd_complexity_type) {
		$complexity_arr = $pwd_obj->get_complexity_type_arr();
		foreach ($complexity_arr as $complexity_type){
			if($complexity_type['id'] == $pwd_complexity_type){
				$pwd_complexity_reg = $complexity_type['regexptxt'];
				break;
			}
		}
		if(!preg_match($pwd_complexity_reg, $newpwd)){
			form_json_msg(NEW_PWD_COMPLIXYTY_WRONG, 'newPwd', $this->lang->line('resetpwd_wrong_new_complexity'), array());
		}
	}
	
	/**
	 * 获取历史密码记录
	 */
	public function get_pwd_records_arr() {
		$this->load->model('password_change_history_model');
		
		$condition = array(
			'user_id' => $this->p_user_id,
			'site_id' => $this->p_site_id
		);
		log_message('info', 'condition' . var_export($condition, true));
		$pwd_records_arr= $this->password_change_history_model->get_pwd_records($condition);
		
		return $pwd_records_arr;
	}
	
	/**
	 * 验证新密码是否属于历史密码的范畴
	 */
	public function valid_pwd_history($pwd_obj, $pwd_records_arr, $newpwd, $history_type) {
		$current_num = 0;
		$records_num = count($pwd_records_arr); // 密码历史记录总数
		
		$history_sets_arr = $pwd_obj->get_history_type_arr();
		foreach ($history_sets_arr as $history_sets) {
			if($history_sets['id'] == $history_type){
				$current_num = $history_sets['num'];
				break;
			}
		}
		log_message('info', 'current_num' . var_export($pwd_records_arr, true));
		if($current_num != 0){
			log_message('info', 'current_num' . var_export($pwd_records_arr, true));
			$totle_num = ($current_num > $records_num) ? $records_num : $current_num;
			
			for($i = 0; $i < $totle_num; $i++){
				if($pwd_records_arr[$i]['password'] == md5($newpwd)){
					form_json_msg(IN_PWD_RECORDS, '', '您不能使用过去'. $current_num .'次使用过的密码', array()); 
				}	
			}
		}
		
		return $current_num; // 返回密码记忆次数
	}
	
	/**
	 * 显示修改密码成功页面
	 */
	public function resetpwdsuc() {
		$this->load->view('public/popup/resetpwdsuc.php');
	}
	
	/**
	 * 显示帮助中心页面
	 */
	public function showHelpCenter(){
		$this->load->view('mixture/helpcenter.php');
	}
}

