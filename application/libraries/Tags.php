<?php if (!defined('BASEPATH'))	exit('No direct script access allowed');

class Tags  {
	
	const MUST_TAG = 0;
	const OPTIONAL_TAG = 1;
	const DEPARTMENT_TAG = 2;
	const ALL_TAG = 3;
	
	
	public function __construct(){
		$CI = & get_instance();
		$CI->config->load('tags');
		$this->system_tags = $CI->config->item('system_tags');
		$this->custom_tags = $CI->config->item('custom_tags');
	}
	
	
	/**
	 * 检查标签值
	 * @param unknown $cell_value
	 * @param unknown $cell_header
	 * @return boolean
	 */
	public function checkTagValue($tag_value, $tag){
		//根据标签名，获取对应的标签值匹配规则。如果不是系统预定义标签，则使用统一的自定义标签值规则来验证
		$md5_key = md5($tag);
		$pattern = '';
		if(isset($this->system_tags[$md5_key])){
			$pattern = $this->system_tags[$md5_key]['pattern'];
		}else{
			$custom_tag = end($this->custom_tags);
			$pattern = $custom_tag['pattern'];
		}
		
		//log_message('info', $tag.'--'.$tag_value.'--'.$pattern.'--'.preg_match($pattern, $tag_value));
		
		//标签值校验
		return (boolean)preg_match($pattern, $tag_value);
	}
	
	/**
	 * 将标签值里的进行转化。如：性别、是否开启
	 * XXX
	 */
	public function filterTagValue($tag_value, $tag){
		
	}
	
	
	/**
	 * @brief 获取所有系统预定义标签，包括可选和必选
	 * @detail 
	 * 1.必选标签里包含了"部门一级"，因为按照业务逻辑，导入的标签至少应该有一级部门
	 * 2.可选标签里包含了部门二级到部门九级别。这些为可选标签
	 * @param $tag_type 0-必选标签1-可选标签 2-全部
	 * @return array('must_tags'=>array(),'optional_tags'=>array()) 或者 array();
	 */
	public function getAllSystemTags($tag_type = self::ALL_TAG, $dept_levels = 1){
		$must_tags 	   = array();
		$optional_tags = array();
		$dept_tags     = array();

		if(!empty($this->system_tags)){
			foreach($this->system_tags as $k=>$item){
				if($item['tag_type'] == self::MUST_TAG){
					$must_tags[$k]     = $item;
				}else if($item['tag_type'] == self::OPTIONAL_TAG){
					$optional_tags[$k] = $item;
				}else if($item['tag_type'] == self::DEPARTMENT_TAG){
					$dept_names_md5 = array_map('md5', $this->getDepartmentTagsByLevel($dept_levels));
					if(in_array($k, $dept_names_md5)){
						$dept_tags[$k] = $item;
					}
				}
			}
		}
		
		//返回tag数组
		if($tag_type == self::MUST_TAG){
			return $must_tags;	
		}else if($tag_type == self::OPTIONAL_TAG){
			return $optional_tags;
		}else if($tag_type == self::DEPARTMENT_TAG){
			return $dept_tags;
		}else{
			return array_merge($must_tags, $optional_tags, $dept_tags);
		}
	}
	
	
	/**
	 * @brief 获取所有的必选标签
	 */
	public function getAllMustTags(){
		return $this->getAllSystemTags(self::MUST_TAG);
	}
	
	/**
	 * @brief 获取所有的必选标签名称
	 */
	public function getAllMustTagsName(){
		$must_tags_arr = $this->getAllSystemTags(self::MUST_TAG);
		return array_column($must_tags_arr, 'name');
	}
	
	/**
	 * @brief 获取所有的可选标签
	 */
	public function getAllOptionalTags(){
		return $this->getAllSystemTags(self::OPTIONAL_TAG);
	}
	
	/**
	 * @brief 获取所有的可选标签名称
	 */
	public function getAllOptionalTagsName(){
		$optional_tags_arr = $this->getAllSystemTags(self::OPTIONAL_TAG);
		return array_column($optional_tags_arr, 'name');
	}
	
	/**
	 * 获取所有的部门标签
	 */
	public function getAllDeptTags($levels){
		return $this->getAllSystemTags(self::DEPARTMENT_TAG, $levels);
	}
	
	
	/**
	 * 获取所有的部门标签名称
	 */
	public function getAllDeptTagsName($levels){
		$dept_tags_arr = $this->getAllSystemTags(self::DEPARTMENT_TAG, $levels);
		return array_column($dept_tags_arr, 'name');
	}
	
	/**
	 * @brief 根据部门层级获取部门标签
	 * @param int $levels 部门层级
	 * @return array('部门一级', '部门二级',...)
	 */
	public function getDepartmentTagsByLevel($levels){
		$chinese_numbers	=	array('一','二','三','四','五','六','七','八','九','十');
		$dept_names				=	array();
		for($deptNo = 1; $deptNo <= $levels; $deptNo++){
			$dept_names[] = '部门'.$chinese_numbers[$deptNo-1].'级';
		}
		
		return $dept_names;
	}
	
	/**
	 * 根据标签中文名称获取英文名
	 * @param string $name 中文名
	 * @return string
	 */
	public function getEnNameByCnName($name){
		$md5_name = md5($name);
		
		return isset($this->system_tags[$md5_name]) ? $this->system_tags[$md5_name]['en_name'] : false;
	}
	
	
}
