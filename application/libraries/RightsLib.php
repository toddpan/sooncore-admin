<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @category	RightsLib
 * @abstract	RightsLib 类库，主要负责获取、保存用户、组织和站点的权限。
 * @filesource	RightsLib.php
 * @author		Bai Xue<xue.bai_2@quanshi.com>
 * @copyright	Copyright (c) UC
 * @version		v1.0
 */
class RightsLib {
	
	public $CI;
	
	/**
	 * 构造方法
	 */
	public function __construct() {
		log_message('info', 'Into class RightsLib.');
		
		$this->CI = & get_instance();
	}
	
	/**
	 * 根据userId获得用户自己的权限属性
	 * @param int $user_id
	 */
	public function get_right_from_user($user_id = ''){
		log_message('info', 'Into method get_right_from_user input----> userid = ' . $user_id);
		
		// 载入用户权限模型
		$this->CI->load->model('uc_user_config_model');
		
		// 初始化结果数组
		$re_data = array();
		// 初始化权限获取位置
		$from_where = POWER_FROM_USER; 
		$from_where_data = '';
		
		// 调用模型方法获得用户权限
		$user_rights_arr = $this->CI->uc_user_config_model->get_right_from_user($user_id);
		
		// 如果从数据库中取出的数据不为空，则取出value
		if(!isemptyArray($user_rights_arr)){
			$value_json = isset($user_rights_arr['value'])?$user_rights_arr['value']:'';
			$re_data = array(
					'right_arr' => json_decode($value_json, true),
					'from_where' => $from_where,
					'from_where_data' => $user_id
			);
		}
		
		log_message('info', 'Out method get_right_from_user output ---->' . var_export($re_data, true));
		
		// 返回结果数组
		return $re_data;
	}
	
	/**
	 * 根据组织Id串获得组织的权限属性
	 * @param string $org_code 组织Id串（-11-74-98，最右边是当前组织的Id）
	 */
	public function get_right_from_org($org_code = ''){
		log_message('info', 'Into method get_right_from_org input----> org_code = ' . $org_code);
		
		// 将组织Id串转换数组
		$org_arr = explode('-', $org_code);
		
		// 将组织数组反序
		$org_arr = array_reverse($org_arr);
		
		// 载入组织权限模型
		$this->CI->load->model('uc_organization_model');
		
		// 初始化结果数组
		$re_data = array();
		// 初始化权限获取位置
		$from_where = POWER_FROM_ORG; // 1、用户自己的个性化权限； 2、组织自己的个性化权限；3、上级组织权限；4、站点权限
		$from_where_data = $org_code;
		// 初始化权限获取位置为组织自己的个性化权限
		$self_flag = 0;
		
		// 遍历组织数组，获得组织权限
		foreach ($org_arr as $org_id) {
			$org_rights_arr = $this->CI->uc_organization_model->get_org_right($org_id);
			
			// 如果从数据库中获得的数据不为空，则取出权限，并退出循环
			if(!isemptyArray($org_rights_arr)){
				$value_json = isset($org_rights_arr['value'])?$org_rights_arr['value']:'';
				$re_data = json_decode($value_json, true);
				
				if($self_flag != 0){
					$location = strpos($from_where_data, '-') + 1;
					$from_where_data = substr($from_where_data, $location);
				}
				
				break;
			}
			
			// 如果没有自己的个性化权限，则从上级组织获取
			$self_flag++;
		}
		
		// 如果获取到了权限，当是从上级组织获得的，则将$from_where置为3
		if(!isemptyArray($re_data)){
			$from_where = ($self_flag == 0) ? POWER_FROM_ORG : POWER_FROM_PARENT_ORG;
			
			$re_data = array(
					'right_arr' => $re_data,
					'from_where' => $from_where,
					'from_where_data' => $from_where_data
			);
		}
		
		log_message('info', 'Out method get_right_from_org output ---->' . var_export($re_data, true));
		
		// 返回结果数组
		return $re_data;
	}
	
	/**
	 * 根据站点Id获得站点权限
	 * @param int $site_id
	 */
	public function get_right_from_site($site_id = ''){
		log_message('info', 'Into method get_right_from_site input----> site_id = ' . $site_id);
		
		// 载入站点模型
		$this->CI->load->model('uc_site_model');
		
		// 初始化结果数组
		$re_data = array();
		// 初始化权限获取位置
		$from_where = POWER_FROM_SITE; // 1、用户自己的个性化权限； 2、组织自己的个性化权限；3、上级组织权限；4、站点权限
		$from_where_data = '';
		
		// 调用模型方法获得站点权限
		$site_rights_arr = $this->CI->uc_site_model->getInfosBySiteId($site_id);
		
		// 如果从数据库中获得的数据不为空，则取出权限
		if(!isemptyArray($site_rights_arr)){
			$value_json = isset($site_rights_arr['value'])?$site_rights_arr['value']:'';
			$re_data = array(
					'right_arr' => json_decode($value_json, true),
					'from_where' => $from_where,
					'from_where_data' => $this->CI->p_stie_domain
			);
		}
		
		log_message('info', 'Out method get_right_from_site output ---->' . var_export($re_data, true));
		
		// 返回结果数组
		return $re_data;
	}
	
	/**
	 * 获得原始权限数组
	 */
	public function get_initright_arr() {
		include_once APPPATH . 'libraries'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'Rights_class.php';
	
		$rights_obj = new Rights_class();
	
		return $rights_obj->get_rights();
	}
	
	/**
	 * 为原始权限数组赋值，并将赋值后的权限数组转化成页面识别的数组格式
	 * @param array $right_arr 从用户或组织或站点表获得的权限数组
	 */
	public function recombine_right($right_arr = array()){
		log_message('info', 'Into method recombine_right input----> $right_arr = ' . var_export($right_arr, true));
		
		// 初始化原始权限数组
		$initial_rights = $this->get_initright_arr();
		
		// 初始化结果数组
		$re_data = array();
		
		// 遍历原始权限数组，使用从用户或组织或站点表获得的权限数组为原始权限数组赋值
		foreach ($initial_rights as $initial_key => $initial_value){
			
			$boss_name 		= $initial_value['boss_name'];		// 原始权限数组的boss_name，如UC、tang、radisys等
			$boss_property 	= $initial_value['boss_property'];	// 原始权限数组的boss_property
			
			// 遍历从用户或组织或站点获取到的权限数组
			foreach ($right_arr as $right_value){
				
// 				if($initial_key == 'summit_ConfDnisAccess' && $right_value['name'] == 'UC'){
// 					if($right_value['property']['incomingLocal'] == 1){
// 						$initial_value['value'] .= '1,';
// 					}
					
// 					if($right_value['property']['incoming400'] == 1){
// 						$initial_value['value'] .= '2,';
// 					}
					
// 					if($right_value['property']['incoming800'] == 1){
// 						$initial_value['value'] .= '3,';
// 					}
					
// 					if($right_value['property']['incomingLocalToll'] == 1){
// 						$initial_value['value'] .= '4,';
// 					}
					
// 					if($right_value['property']['incomingInter'] == 1){
// 						$initial_value['value'] .= '5,';
// 					}
					
// 					if($right_value['property']['incomingHk'] == 1){
// 						$initial_value['value'] .= '7,';
// 					}
					
// 					break;
					
// 				}else if($initial_key == 'summit_ParticipantNameRecordAndPlayback' && $right_value['name'] == 'radisys'){
// 					// 是否录制参会人姓名
// 					$initial_value['value'] = $right_value['property']['PNR'];
// 					break;
					
// 				}else if(($initial_key == 'summit_Pcode2InTone' || $initial_key == 'summit_Pcode2OutTone' || $initial_key == 'summit_Pcode1InTone' || $initial_key == 'summit_Pcode1OutTone') && $right_value['name'] == 'radisys'){
// 					// 主持人/参会人入会/退会提示音设置
// 					$initial_value['value'] = $right_value['property']['InOutTone'];
// 					break;
// 				}else{
					// 如果是当前的boss_name和当前boss_property，则为原始权限数组赋值
					if(($boss_name == $right_value['name']) && isset($right_value['property'][$boss_property])){
						$initial_value['value'] = $right_value['property'][$boss_property];
						break;
					}
//				}
				
				 
			}
			
			// 将赋值后的结果赋给结果数组
			$temp = array();
			$temp['default_value'] 	= $initial_value['default_value'];
			$temp['value'] 			= $initial_value['value'];
			$re_data[$initial_key] = $temp;
		}
		
		log_message('info', 'Out method recombine_right output ---->' . var_export($re_data, true));
		
		// 返回赋值后的结果数组
		return $re_data;
	}
	
	/**
	 * 验证表单提交的权限是否符合正则规定
	 * @param array $right_arr  从表单提交的权限
	 */
	public function valid_right($right_arr = array()){
		log_message('info', 'Into method valid_right input----> $right_arr = ' . var_export($right_arr, true));
		
		// 初始化原始权限数组
		$initial_rights = $this->get_initright_arr();
		
		// 遍历原始权限数组，用原始权限数组中的正则匹配表单提交的权限值
		foreach ($initial_rights as $initial_key => $initial_value){
			
			// 遍历表单提交的权限数组
			foreach ($right_arr as $right_key => $right_value){
				
				// 如果是当前权限，则用正则匹配
				if($initial_key == $right_key){
					
					// 匹配失败，则返回出错信息
					if(!empty($initial_value['regex']) && !preg_match($initial_value['regex'], $right_value)){
							log_message('info', 'regx=' . $initial_value['regex'] . 'value=' . $right_value);
							$err_msg = $initial_value['name'].'is wrong.';
							return array(false, $err_msg);
					}
					
					// 其他情况（无正则或匹配成功），都将表单提交的权限值赋给原始权限数组，然后break，匹配下一条
					$initial_value['value'] = $right_value;
					break;
				}
			}
			
			// 将结果赋给结果数组
			$re_data[$initial_key] = $initial_value;
		}
		
		log_message('info', 'Out method valid_right output ---->' . var_export(array(true, $re_data), true));
		
		// 返回结果数组
		return array(true, $re_data);
	}
	
	/**
	 * 对比新旧权限数组，返回变化情况
	 * @param array $old_right_arr 旧的权限数组
	 * @param array $new_right_arr 新的权限数组
	 */
	public function compare_rights($old_right_arr, $new_right_arr){
		log_message('info', 'Into method compare_rights input----> ' . var_export(array($old_right_arr, $new_right_arr), true));
		
		$re_data 			= array();					// 初始化结果数组
		$is_change 			= POWER_NOT_CHANGE;			// 权限是否发生变化：0、没有；1、有
		$is_confSet_change = CONF_POWER_NOT_CHANGE; 	// 会议权限是否发生变化：0、没有；1、有
		
		// 取出旧的接入号设置
		$old_conf_acess = $this->recombine_right($old_right_arr);
		$old_conf_acess_arr = isset($old_conf_acess['summit_ConfDnisAccess'])?$old_conf_acess['summit_ConfDnisAccess']:'';
		$old_conf_acess_str = isset($old_conf_acess_arr['value'])?$old_conf_acess_arr['value']:'';
		$old_conf_acess_value_arr = explode(',', $old_conf_acess_str);
		
		// 遍历新旧权限数组，判断新权限与旧权限是否相同，不同则将新权限的值赋给旧权限
		foreach ($old_right_arr as $old_right_key => $old_right_value){
			foreach ($new_right_arr as $new_right_key => $new_right_value){
				
				// 新权限的boss_property
				$boss_property = $new_right_value['boss_property'];
				
				if($boss_property == 'ConfDnisAccess' && $old_right_value['name'] == 'UC'){// 接入号设置
					$conf_acess_arr = explode(',', $new_right_value['value']);
					$diff_conf_arr = array_diff($old_conf_acess_value_arr, $conf_acess_arr);
					
					// 未被选中
					foreach($diff_conf_arr as $diff_conf){
						switch ($diff_conf){
							case 1:
								$old_right_value['property']['incomingLocal'] = 0;
								break;
							case 2:
								$old_right_value['property']['incoming400'] = 0;
								break;
							case 3:
								$old_right_value['property']['incoming800'] = 0;
								break;
							case 4:
								$old_right_value['property']['incomingLocalToll'] = 0;
								break;
							case 5:
								$old_right_value['property']['incomingInter'] = 0;
								break;
							case 7:
								$old_right_value['property']['incomingHk'] = 0;
								break;
							default:
								break;
						}
					}
					
					// 被选中
					foreach($conf_acess_arr as $conf_acess_value){
						
						switch ($conf_acess_value){
							case 1:
								$old_right_value['property']['incomingLocal'] = 1;
								break;
							case 2:
								$old_right_value['property']['incoming400'] = 1;
								break;
							case 3:
								$old_right_value['property']['incoming800'] = 1;
								break;
							case 4:
								$old_right_value['property']['incomingLocalToll'] = 1;
								break;
							case 5:
								$old_right_value['property']['incomingInter'] = 1;
								break;
							case 7:
								$old_right_value['property']['incomingHk'] = 1;
								break;
							default:
								break;
						}
					}
				}
				
				// 如果是当前权限，则进行判断
				if($new_right_value['boss_name'] == $old_right_value['name']){
					
					// 如果发生变化，则将新的值赋给旧的权限
					if($new_right_value['value'] != $old_right_value['property'][$boss_property]){
						
						$is_change = POWER_IS_CHANGE;// 权限是否发生变化：0、没有；1、有
						$old_right_value['property'][$boss_property] = $new_right_value['value'];
						
						
						// 判断会议权限是否发生变化
						if($new_right_value['type'] == CONF_SET){ 
							$is_confSet_change = CONF_POWER_IS_CHANGE; // 会议权限是否发生变化：0、没有；1、有
						}
					}
				}
			}
			
			// 返回重新赋值后的旧权限，此时旧权限已经变成新权限啦，哈哈哈
			$re_data[$old_right_key] = $old_right_value;
		}
		
		// 组装结果数组
		$re_data = array(
			'is_change' 			=> $is_change,
			'is_confSet_change' 	=> $is_confSet_change,
			'new_right' 			=> $re_data
		);
		
		log_message('info', 'Out method compare_rights output ---->' . var_export($re_data, true));
		
		return $re_data;
	}
	
	/**
	 * 组装新的BOSS模板数据
	 * @param string $boss_template_json  旧的BOSS模板数据
	 * @param string $new_power_json	新的模板属性
	 * @return string  重新组装后的BOSS模板数据
	 */
	public function combine_boss_template_data($boss_template_arr, $new_power_arr) {
		// 从新的模板属性中按名称（UC、tang、PC3.0等）取出各自属性property
		$pc_power_arr = array_column($new_power_arr, 'property', 'name');
		
		// 初始化新的BOSS模板数据
		$new_template_arr = array();
		
		// 遍历旧的BOSS模板数据，将新的模板属性值赋给它，使其成为新的BOSS模板数据
		foreach ($boss_template_arr as $boss_template){
			
			// 如果是PC3.0，则将对应的新的模板属性值赋给它
			if($boss_template['productId'] == PC3_PRODUCT_ID){
				
				// 取出PC3.0的组件
				$components = $boss_template['components'];
				// 取出PC3.0组件中的属性property
				$property = array_column($components, 'property', 'name');
				// 遍历PC3.0的属性property数组，将对应的新的模板属性赋给它
				foreach ($property as $pro_key => $pro_value){
					if(array_key_exists($pro_key, $pc_power_arr)){
						$pro_value = $pc_power_arr[$pro_key];
					}
				}
			}
			
			// 如果是UC，则将新的模板属性值赋给它
			if($boss_template['productId'] == UC_PRODUCT_ID){
				
				// 取出UC的组件
				$components = $boss_template['components'];
				// 取出UC组件中的属性property
				$property = array_column($components, 'property', 'name');
				
				// 将summit属性赋给radisys
				$uc_power_arr = $this->set_uc_radisys_property($pc_power_arr);
				
				// 遍历UC的属性property数组，将对应的新的模板属性赋给它
				foreach ($property as $pro_key => $pro_value){
					if(array_key_exists($pro_key, $uc_power_arr)){
						$pro_value = $uc_power_arr[$pro_key];
					}
				}
			}
			
			// 将当前行赋给新的BOSS模板
			unset($boss_template['productId']);
			$new_template_arr[] = $boss_template;
		}
		
		log_message('info', 'dongfangbubai='.var_export($new_template_arr,true));
		
		// 返回新的BOSS模板数据
		return $new_template_arr;
	}
	
	/**
	 * 将summit的属性转换成radisys的属性
	 * @param array $uc_property_arr  UC的属性数组
	 */
	public function set_uc_radisys_property($uc_property_arr) {
		if(array_key_exists('summit', $uc_property_arr)){
			
			// 是否录制参会人姓名
			if(isset($uc_property_arr['summit']['ParticipantNameRecordAndPlayback'])){
				$uc_property_arr['radisys']['PNR'] = $uc_property_arr['summit']['ParticipantNameRecordAndPlayback'];
			}
			
			// 主持人/参会人加入/退出会议时语音提示
			if(isset($uc_property_arr['summit']['Pcode2InTone'])){
				$uc_property_arr['radisys']['InOutTone'] = $uc_property_arr['summit']['Pcode1InTone']; // 默认使用主持人入会的配置
			}
			
		}
		
		unset($uc_property_arr['summit']);
		
		log_message('info', 'linghuchong='.var_export($uc_property_arr, true));
		
		return $uc_property_arr;
	}
	
	/**
	 * 更新BOSS模板
	 * @param string $uuid  
	 * @param array $sellingProducts BOSS模板数据
	 * @return boolean
	 */
	public function update_boss_template($uuid, $sellingProducts){
		$this->CI->load->library('BossLib', '', 'boss');
		$data = array(
				'templateUUID' => $uuid,
				'contractId' => $this->CI->p_contract_id,
				'sellingProducts' => $sellingProducts
		);

		// 调用BOSS接口更新模板
		$res = $this->CI->boss->combinedBatchModifyContractComponentProps($data);
		 
		return $res;
	}
	
	/**
	 * 创建BOSS模板
	 * @param string $uuid  
	 * @param array $sellingProducts BOSS模板数据
	 */
	public function create_boss_template($uuid, $sellingProducts){
		$this->CI->load->library('BossLib', '', 'boss');
		$data = array(
				'templateUUID' => $uuid,
				'contractId' => $this->CI->p_contract_id,
				'sellingProducts' => $sellingProducts
		);
		
		// 调用BOSS接口更新模板
		$res = $this->CI->boss->combinedBatchCreateContractComponentProps($data);
			
		return $res;
	}
	
}