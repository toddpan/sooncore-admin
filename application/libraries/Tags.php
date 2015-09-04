<?php if (!defined('BASEPATH'))	exit('No direct script access allowed');

class Tags  {
	
	const MUST_TAG = 0;
	const OPTIONAL_TAG = 1;
	const DEPARTMENT_TAG = 2;
	const ALL_TAG = 3;
	
	
	public function __construct(){
		$CI = & get_instance();
		$CI->config->load('tags2');
		$this->system_tags = $CI->config->item('system_tags');
		$this->custom_tags = $CI->config->item('custom_tags');
	}
	
	
	/**
	 * 检查标签值
	 * @param mix 	 $tag_value 标签值
	 * @param string $tag_key    标签id(配置文件中每个标签的key)
	 * @return boolean
	 */
	public function checkTagValue($tag_value, $pattern){
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
	 * 获取标签
	 * @param int $tag_type		 		标签类型
	 * @param string $lang              语言
	 * @param bool   $show              是否显示隐藏的标签
	 * @param bool   $includeDept       是否包含部门标签
	 * @return array
	 */
	public function getAllSystemTags($tag_type = self::ALL_TAG, $lang='cn', $show=true, $includeDept=true){
		$must_tags 	   = array();
		$optional_tags = array();

		if(!empty($this->system_tags)){
			foreach($this->system_tags as $k=>$item){
				
				if(!$item['enable']) continue;
				if(!$show && $item['show'] == false) continue;
				if(!$includeDept && ($k == "department")) continue;
				
				foreach($item['alias'] as $alia){
					if($alia['lang'] == $lang){
						$item = array_merge($item, $alia);
						unset($item['alias']);
						break;
					}
				}
				
				if($item['tag_type'] == self::MUST_TAG ){
					$must_tags[$k]     = $item;
				}else if($item['tag_type'] == self::OPTIONAL_TAG ){
					$optional_tags[$k] = $item;
				}
		
			}
			
		}
		
		//返回tag数组
		if($tag_type == self::MUST_TAG){
			return $must_tags;	
		}else if($tag_type == self::OPTIONAL_TAG){
			return $optional_tags;
		}else{
			return array_merge($must_tags, $optional_tags);
		}
	}
	
	
	/**
	 * @brief 获取所有的必选标签
	 */
	public function getAllMustTags($lang='cn', $show=true){
		return $this->getAllSystemTags(self::MUST_TAG, $lang, $show);
	}
	
	/**
	 * @brief 获取所有的必选标签名称
	 */
	public function getAllMustTagsName($lang='cn', $show=true){
		$must_tags_arr = $this->getAllSystemTags(self::MUST_TAG, $lang, $show);
		//return array_column($must_tags_arr, 'name');
		$ret = array();
		foreach($must_tags_arr as $key=>$tag){
			$ret[$key] = $tag['name'];
		}
		return $ret;
	}
	
	public function getAllMustTagsExceptDept($lang='cn', $show=true){
		return $this->getAllSystemTags(self::MUST_TAG, $lang, $show, false);
	}
	
	public function getAllMustTagsNameExceptDept($lang='cn', $show=true){
		$must_tags_arr = $this->getAllSystemTags(self::MUST_TAG, $lang, $show, false);
		//return array_column($must_tags_arr, 'name');
		$ret = array();
		foreach($must_tags_arr as $key=>$tag){
			$ret[$key] = $tag['name'];
		}
		return $ret;
	}
	
	/**
	 * @brief 获取所有的可选标签
	 */
	public function getAllOptionalTags($lang='cn', $show=true){
		return $this->getAllSystemTags(self::OPTIONAL_TAG, $lang, $show);
	}
	
	/**
	 * @brief 获取所有的可选标签名称
	 */
	public function getAllOptionalTagsName($lang='cn', $show=true){
		$optional_tags_arr = $this->getAllSystemTags(self::OPTIONAL_TAG, $lang, $show);
		//return array_column($optional_tags_arr, 'name');
		$ret = array();
		foreach($optional_tags_arr as $key=>$tag){
			$ret[$key] = $tag['name'];
		}
		return $ret;
	}
	
	/**
	 * 获取所有的部门标签
	 * @param string $lang
	 * @param unknown $levels
	 */
	public function getAllDeptTagsName($levels, $lang='cn'){
		return $this->getDepartmentTagsByLevel($levels, $lang);
	}
	
	/**
	 * @brief 根据部门层级获取部门标签
	 * @param int $levels 部门层级
	 * @return array('部门一级', '部门二级',...)
	 */
	public function getDepartmentTagsByLevel($levels, $lang='cn'){
		$dept_names = array();
		if($lang == 'cn'){
			$chinese_numbers	=	array('一','二','三','四','五','六','七','八','九','十');
			$dept_names				=	array();
			for($deptNo = 1; $deptNo <= $levels; $deptNo++){
				$name = '部门'.$chinese_numbers[$deptNo-1].'级';
				$dept_names['department'.$deptNo] = $name;
			}
		}
		
		return $dept_names;
	}
	
	public function getPatternByTagName($name, $lang='cn'){
		$all_must_tags   = $this->getAllMustTagsName($lang);
		$all_option_tags = $this->getAllOptionalTagsName($lang);
		$all_dept_tags   = $this->getDepartmentTagsByLevel(10, $lang);
		
		$key = '';
		if($key = array_search($name, $all_must_tags)){
			return $this->system_tags[$key]['pattern'];
		}else if($key = array_search($name, $all_option_tags)){
			return $this->system_tags[$key]['pattern'];
		}else if(in_array($name, $all_dept_tags)){
			return $this->system_tags['department']['pattern'];
		}else{
			return $this->custom_tags['pattern'];
		}
		
	}
	
	public function getKeyByTagName($name, $lang='cn'){
		$all_must_tags = $this->getAllSystemTags(self::ALL_TAG, $lang);
		$all_dept_tags = $this->getDepartmentTagsByLevel(10, $lang);
		
		foreach($all_must_tags as $k=>$all_must_tag){
			if($all_must_tag['name'] == $name){
				return $k;
			}
		}
		
		$key = array_search($name, $all_dept_tags);
		if($key !== false){
			return $key;
		}
		
		return '';
	}
	
	
}
