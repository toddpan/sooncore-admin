<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @category SensitiveWord
 * @abstract SensitiveWord Controller，敏感词管理控制，主要是管理敏感词新建/删除及对员工豁免管理[列表、添加豁免、取消豁免]
 * @filesource SensitiveWord.php
 * @author yanzou <yan.zou@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
class SensitiveWord extends Admin_Controller{
	/**
	 * @access public
	 * @abstract 构造函数
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('UC_Sensitive_Word_Model');
	}

	/**
	 * @access public
	 * @abstract 敏感词管理页面
	 * @details
	 * -# 从UC获得当前站点、当前企业能使用的敏感词
	 * -# 将获得的敏感词分配到视图
	 * @param $type string 类型：1、第一次加载；2、新建敏感词后加载
	 */
	public function sensitiveWordPage($type = '') {
		$site_id = $this->p_site_id;
		$org_id = $this->p_org_id;
		$data["sensitiveArr"] = $this->UC_Sensitive_Word_Model->getSensitiveWord($site_id,$org_id);
		if($type == 1){
			// 将数据传给页面
			$this->load->view('sensitiveword/sesitivewordlist.php', $data);
		}else if($type == 2){
			$this->load->view('public/part/sesitivewordlist.php', $data);
		}
	}

	/**
	 * @access public
	 * @abstract 显示新加敏感词页面
	 */
	public function addSensitiveWordPage() {
		$this->load->view('public/popup/newsensitiveword.php');
	}

	/**
	 * @access public
	 * @abstract 保存新加敏感词
	 * @details
	 * -# 从表单获取敏感词
	 * -# 逗号分隔敏感词,for循环保存没有的敏感词到库
	 */
	public function saveSensitiveWord() {
		$site_id = $this->p_site_id;
		$org_id = $this->p_org_id;

		// 获取表单提交的敏感词字符串
		$sensitive_str = $this->input->post('word', true);
		//echo $sensitive_str;

		// 设置表单验证标识符0、无错；1、有错
		$is_err = 0;

		// 验证敏感词字符串是否为空
		if(empty($sensitive_str)){
			$is_err = 1;
			form_json_msg('1', '', '请输入敏感词！', array());
			//exit;
		}


		// 将表单提交的字符串中的中文“，”转成英文“,”
		$sensitive_str = str_replace('，', ',', $sensitive_str);
		//echo $sensitive_str;

		// 将表单提交的字符串中的空格去掉
		$sensitive_str = str_replace(array(" ", "\n", "\r", "\r\n", "\t"), '', $sensitive_str);

		// 将字符串转换成数组
		$word_arr = explode(',', $sensitive_str);
		//print_r($word);
		//die();

		if($is_err == 0){
			// 调用模型方法添加表单提交的敏感词
			foreach($word_arr as $value){
				if(!empty($value) && strlen($value) <= 4*4){
					$count = $this->UC_Sensitive_Word_Model->addSensitiveWord($site_id, $org_id, $value);
					if(!$count){
						form_json_msg('1', '', '保存失败！', array());
						//break;
					}
				}
			}
		}
		form_json_msg('0', '', '保存成功！', array());
	}

	/**
	 * @access public
	 * @abstract 显示提醒删除敏感词页面
	 *
	 */
	public function showDelSensitiveWordPage($id) {
		$data['is_check'] = $this->input->post('is_check', true);
		//$id = $this->uri->segment(3);
		//echo $id;
		$data['single_sensitive_word'] = $this->UC_Sensitive_Word_Model->query_current_sensitive_word($id);
		//var_dump($data);
		$this->load->view('public/popup/delsensitiveword.php', $data);
	}

	/**
	 * @access public
	 * @abstract 删除敏感词操作[ajax加载]：
	 * @details
	 * -# 获得JS post 过来的 敏感词 SensitiveId
	 *    SensitiveId进行效验
	 * -# 执行删除操作
	 * @return array
	 *
	 */
	public function delSensitiveWord($SensitiveId) {
		$result = $this->UC_Sensitive_Word_Model->delSensitiveWord($SensitiveId);

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
		$site_id = $this->p_site_id;
		$org_id = $this->p_org_id;

		// 获取表单提交的敏感词
		$word = $this->input->post('word', true);

		// 验证表单提交的敏感词是否为空
		if(!empty($word)){
			$data['sensitiveArr'] = $this->UC_Sensitive_Word_Model->searchSensitiveWord($word, $site_id, $org_id);
		}

		// 将数据加载到页面
		$this->load->view('public/part/sesitivewordlist.php', $data);
	}
}

