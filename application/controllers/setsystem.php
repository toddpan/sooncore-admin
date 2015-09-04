<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @abstract 	SetSystem Controller，系统设置控制器，主要对站点应用设置管理
 * @filesource 	setsystem.php
 * @author 		Bai Xue <xue.bai_2@quanshi.com>
 * @copyright 	Copyright (c) UC
 * @version 	v1.0
 */
class SetSystem extends Admin_Controller{
	
    /**
     * 构造方法
     */
    public function __construct() {
        parent::__construct();
        // 加载站点相关操作的中文提示信息语言包
        $this->lang->load('site', 'chinese');
    }
    
    /**
     * 获得站点权限
     * @author xue.bai_2@quanshi.com 2014-11-21
     */
    public function get_sys_power() {
    	log_message('info', 'Into method get_sys_power.');
    	
    	// 载入权限类
    	$this->load->library('RightsLib', '', 'RightsLib');
    	
    	// 从站点获得权限
    	$re_data = $this->RightsLib->get_right_from_site($this->p_site_id);
    	$power_arr = isset($re_data['right_arr']) ? $re_data['right_arr']: '' ;
    	
    	// 重组数组
    	$power_arr = $this->RightsLib->recombine_right($power_arr);
    	
    	// 将数据显示在页面上
    	form_json_msg(COMMON_SUCCESS, '', '', array('power' => $power_arr));
    }
    
    /**
     * 保存站点权限
     * @author xue.bai_2@quanshi.com 2014-11-21
     */
    public function save_sys_power(){
    	$power_json = $this->input->post('power_json', true); // 权限串
    	log_message('info', 'Into method save_sys_power input----> $power_json = '.$power_json);
    	
    	// 判断权限串是否为空
    	if(is_empty($power_json)){
    		form_json_msg(SITE_POWER_JSON_EMPTY, 'power_json', $this->lang->line('power_json_empty'), array()); // 权限串不能为空
    	}
    	
    	// 将权限串转化成权限数组
    	$power_arr = json_decode($power_json, true);
    	
    	// 载入权限类库
    	$this->load->library('RightsLib', '', 'RightsLib');
    	$this->load->library('BossLib', '', 'boss');
    	
    	// 验证表单提交的权限
    	list($flag, $data) = $this->RightsLib->valid_right($power_arr);
    	if($flag == false){
    		form_json_msg(POWER_IS_WRONG, '', $data, array()); // 权限错误
    	}else{
    		$new_power_arr = $data; // 返回重组后的权限数组
    	}
    	
    	// 获得旧权限
    	$re_data = $this->RightsLib->get_right_from_site($this->p_site_id);
    	$old_power_arr = isset($re_data['right_arr']) ? $re_data['right_arr']: array();
    	
    	// 对比新旧权限数组，判断权限是否发生变化
    	$res_arr 			= $this->RightsLib->compare_rights($old_power_arr, $new_power_arr);
    	$is_change 			= isset($res_arr['is_change']) ? $res_arr['is_change'] : POWER_NOT_CHANGE; 	// 权限是否发生变化：0、没有；1、有
    	$is_confSet_change = isset($res_arr['is_confSet_change']) ? $res_arr['is_confSet_change'] : CONF_POWER_NOT_CHANGE; // 会议权限是否发生变化：0、没有；1、有
    	$new_power_arr 		= isset($res_arr['new_right']) ? $res_arr['new_right'] : array(); // 新的权限数组
    	
    	// 如果站点的权限发生变化
    	if($is_change == POWER_IS_CHANGE){
    		
    		// 获得旧的BOSS模板
    		$boss_totle_template = $this->boss->getSellingProductTemplates($this->p_contract_id, $this->p_stie_domain);
    		
    		// 修改模板
    		$new_template_arr = $this->RightsLib->combine_boss_template_data($boss_totle_template, $new_power_arr);
    		$res = $this->RightsLib->update_boss_template($this->p_stie_domain, $new_template_arr);
    		if($res == false){
    			form_json_msg(UPDATE_SITE_POWER_FAIL, '', $this->lang->line('update_power_fail'), array()); // 更新权限失败
    		}
    		
    		// 更新UC站点模板
    		$where_arr = array(
    				'siteID' => $this->p_site_id
    		);
    		$update_arr = array(
    				'value' => json_encode($new_power_arr)
    		);
    		$this->load->model('uc_site_model');
    		$res = $this->uc_site_model->update_value($where_arr, $update_arr);
    		if($res == false){
    			form_json_msg(UPDATE_SITE_POWER_FAIL, '', $this->lang->line('update_power_fail'), array()); // 更新权限失败
    		}
    		
    		// 如果会议权限发生变化，则保存线程
			if($is_confSet_change == CONF_POWER_IS_CHANGE){
    			$update_value = array(
    					'customer_code' => $this->p_customer_code,
    					'site_id' 		=> $this->p_site_id,
    					'org_id'		=> $this->p_org_id
    			);
    			$this->load->model('account_upload_task_model');
    			$this->account_upload_task_model->saveTask(SITE_POWER_CHANGE_UPLOAD, json_encode($update_value));
    		}
    	}
    	form_json_msg(COMMON_SUCCESS, '', $this->lang->line('success'), array()); // 返回保存成功信息
    }
    
	
}