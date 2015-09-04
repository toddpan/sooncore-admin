<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Config extends Api_Controller{
	public function __construct() {
		parent::__construct();
		$this->load->helper('my_publicfun_helper.php');
	}
	
	/**
	 * 获取站点配置项
	 */
	public function getSiteConfig(){
		//get site id
		$site_id = $this->input->get('siteId', true);
		
		//check request param
		if(empty($site_id) OR !is_numeric($site_id)){
			echo response_json(COMMON_PARAM_ERROR,'siteId is required');return;
		}
		
		//get config items from local db
		$this->load->model('uc_site_config_model', 'site_config_model');
		$site_config = $this->site_config_model->getAllSiteConfig($site_id);
		
		//add environment param to header
		header("deployedEnvironment:".$site_config['deployedEnvironment']);
		
		//return json data
		echo response_json(COMMON_SUCCESS,'',$site_config);
		
	}
	
	
	/**
	 * 根据站点id获得公司简称与图标（mfs文件路径）
	 * 
	 * @author xue.bai_2@quanshi.com 2015-06-23
	 */
	public function getCorNameandImg() {
		//get site id
		$site_id = $this->input->get('siteId', true);
		
		//check request param
		if(empty($site_id) OR !is_numeric($site_id)){
			echo response_json(COMMON_PARAM_ERROR,'siteId is required');return;
		}
		
		//get items from local db
		$this->load->model('uc_site_model');
		$res_arr = $this->uc_site_model->getInfoBySiteId($site_id, 'corName,logoUrl');
		if(empty($res_arr)){
			echo response_json(COMMON_PARAM_ERROR,"invalid siteId[{$site_id}]");return;
		}

		$site_info = array(
			'corName' => isset($res_arr['corName']) ? $res_arr['corName'] : '',
			'logoUrl' => (isset($res_arr['logoUrl']) && !empty($res_arr['logoUrl'])) ? LOGO_DOWNLOAD_URL . $res_arr['logoUrl'] : ''
		);
		
		//return json data
		echo response_json(COMMON_SUCCESS, '', $site_info);
	}

	public function getAdminInfo(){
		//检查user_id
		$user_id = $this->input->get('user_id', true);
		if(empty($user_id)){
			echo response_json(COMMON_PARAM_ERROR, 'user_id is required');
			return;
		}

		//检查这个用户是否为系统管理员
		$this->load->model('uc_user_admin_role_model', 'role_model');
		$role_ids = $this->role_model->getRoleIdsByUserId($user_id);
		if(!in_array(SYSTEM_MANAGER, $role_ids)){
			echo response_json(COMMON_PARAM_ERROR, "user[id:{$user_id}] is not a system manager");
		}else{
			$this->load->model('uc_user_model', 'user_model');
			$res = $this->user_model->getUserInfos('siteId,customerCode', array('userID'=>$user_id));
			if(empty($res)){
				echo response_json(COMMON_PARAM_ERROR, "get cutomer info by user info[id:{$user_id}] from local db failed.");
			}else{
				echo response_json(COMMON_SUCCESS, '', $res);
			}
		}
		return;
	}

	public function getSiteApplications(){
		//检查siteId
		$site_id = $this->input->get('siteId', true);
		if(empty($site_id)){
			echo response_json(COMMON_PARAM_ERROR, 'siteId is required');
			return;
		}

		$this->load->model('uc_site_model', 'site_model');
		$site_info = $this->site_model->getInfosBySiteId($site_id);
		if(empty($site_info)){
			echo response_json(COMMON_PARAM_ERROR, 'invalid siteId.');
			return;
		}
		
		//返回站点应用列表
		$this->load->model('uc_application_model', 'app_model');
		$apps = $this->app_model->getApplicationsBySiteId($site_id);

		if(!$apps){
			echo response_json(COMMON_SUCCESS, '', array());
		}else{
			foreach($apps as $k=>$app){
				$apps[$k]['url'] = json_decode($app['url'], true);
			}
			echo response_json(COMMON_SUCCESS, '', $apps);
		}
		return;
	}
}
