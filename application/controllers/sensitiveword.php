<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @abstract 	用户活动控制器
 * @author 		Bai Xue <xue.bai_2@quanshi.com>
 * @copyright 	Copyright (c) UC
 * @version 	v1.0
 */
class SensitiveWord extends Admin_Controller{
	
	/**
	 * 构造方法
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('uc_sensitive_word_model');
	}

	/**
	 * 显示敏感词页面
	 * 
	 * @param $type string 类型：1、第一次加载；2、新建敏感词后加载
	 */
	public function sensitiveWordPage($type = '') {
		$this->setFunctions();
		
		if(!$this->functions['SensitiveWord']){
    		$this->redirectToMainPage();
		}
		else{
			$data["sensitiveArr"] = $this->uc_sensitive_word_model->getSensitiveWord($this->p_site_id, $this->p_org_id);
			
			if($type == 1){
				$this->load->view('sensitiveword/sesitivewordlist.php', $data);
			}else if($type == 2){
				$this->load->view('public/part/sesitivewordlist.php', $data);
			}
		}
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
			
		$functions['PasswordManage'] = $this->siteConfig['siteType'] == 0;
	
		return $functions;
	}
	
	private function setFunctionsByRole(){
		$functions = array();
	
		$functions['PasswordManage'] = $this->p_role_id == SYSTEM_MANAGER;
		$functions['SensitiveWord'] = $this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER || $this->p_role_id == EMPPLOYEE_MANAGER;
		$functions['LogManage'] = $this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER;
		$functions['UserActionManage'] = $this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER || $this->p_role_id == EMPPLOYEE_MANAGER;
	
		return $functions;
	}
	
	
	
	
	

	/**
	 * 显示新建敏感词页面
	 */
	public function addSensitiveWordPage() {
		$this->load->view('public/popup/newsensitiveword.php');
	}

	/**
	 * 保存新加敏感词
	 */
	public function saveSensitiveWord() {
		// 获取表单提交的敏感词字符串
		$sensitive_str = $this->input->post('word', true);

		// 验证敏感词字符串是否为空
		if(empty($sensitive_str)){
			form_json_msg('1', '', '请输入敏感词！', array());
		}

		// 将表单提交的字符串中的中文“，”转成英文“,”
		$sensitive_str = str_replace('，', ',', $sensitive_str);

		// 将表单提交的字符串中的空格去掉
		$sensitive_str = str_replace(array(" ", "\n", "\r", "\r\n", "\t"), '', $sensitive_str);

		// 将字符串转换成数组
		$word_arr = explode(',', $sensitive_str);

		// 调用模型方法添加表单提交的敏感词
		foreach($word_arr as $value){
			if(!empty($value) && strlen($value) <= 4*4){
				$count = $this->uc_sensitive_word_model->addSensitiveWord($this->p_site_id, $this->p_org_id, $value);
				if(!$count){
					form_json_msg('1', '', '保存失败！', array());
				}
			}
		}
		
		form_json_msg('0', '', '保存成功！', array());
	}

	/**
	 * 显示提醒删除敏感词页面
	 */
	public function showDelSensitiveWordPage($id) {
		$data['is_check'] = $this->input->post('is_check', true);
		$data['single_sensitive_word'] = $this->uc_sensitive_word_model->query_current_sensitive_word($id);
		$this->load->view('public/popup/delsensitiveword.php', $data);
	}

	/**
	 * 
	 * 删除敏感词操作
	 */
	public function delSensitiveWord($SensitiveId) {
		$result = $this->uc_sensitive_word_model->delSensitiveWord($SensitiveId);

		if($result['is_success'] == 1){
			form_json_msg('0', '', '删除成功！', array());
		}else if($result['is_success'] == 0){
			form_json_msg('1', '', '删除失败！', array());
		}
	}

	/**
	 * @access public
	 * @abstract 查询敏感词
	 * @return array 若该敏感词存在，则返回该敏感词所在的数组，否则，则返回空数组
	 */
	public function searchSensitiveWord() {
		// 获取表单提交的敏感词
		$word = $this->input->post('word', true);

		// 验证表单提交的敏感词是否为空
		if(!empty($word)){
			$data['sensitiveArr'] = $this->uc_sensitive_word_model->searchSensitiveWord($word, $this->p_site_id, $this->p_org_id);
		}

		// 将数据加载到页面
		$this->load->view('public/part/sesitivewordlist.php', $data);
	}
}

