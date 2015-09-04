<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @abstract 	标签控制器
 * @author 		Bai Xue <xue.bai_2@quanshi.com>
 * @copyright 	Copyright (c) UC
 * @version 	v1.0
 */
class Tag extends Admin_Controller {
    /**
     * @brief 构造函数：
     *
     */
    public function __construct() {
        parent::__construct();
        $this->load->helper('my_publicfun');
        $this->load->helper('my_dgmdate');
        //调用分配域的接口
        $this->load->library('API','','API');
        log_message('info', 'into class ' . __CLASS__ . '.');
    }

    /**
     * 保存标签
     */
    public function addTagPage() {
        log_message('info', 'into method ' . __FUNCTION__ . '.');
        // 员工导入方式0：否（批量导入）；1：是（LDAP导入）；2：全部都可以
        try{
            $is_LDAP = $this->p_is_ldap;
            // 验证是否是0 \1 \ 2
            if(!preg_match('/^[012]$/',$is_LDAP )){
                form_json_msg('1','','非法的导入方式！');//返回错误信息json格式
            }
        }catch(Exception $e){
            log_message('debug', $e->getMessage());
            exit();
        }

        //获得参数,如果没有值,则认为是标签来源0新加页面，会跳转到批量导入或LDAP导入页面 1修改页面，会跳转到组织页面
        $page_type = $this->uri->segment(3);
        try{
            if (bn_is_empty($page_type)){
                $page_type = 0;
            }

            // 验证是否是 0 \1
            if(!preg_match('/^[01]$/',$page_type)){
                form_json_msg('1','','参数错误！');//返回错误信息json格式
            }
        }catch(Exception $e){
            log_message('debug', $e->getMessage());
            exit();
        }

        //载入员工标签资源
        include_once APPPATH . 'libraries/public/Tag_class.php';

        $tag_obj = new Tag_class(0);//标签页显示使用

        try{
            $all_tag_obj = new Tag_class(2);//标签页显示使用
            //所有的系统员工标签名
            $data['system_tag_names'] = $all_tag_obj->get_system_tag_names();

            //获得必选的员工标签
            $data['must_tag_arr'] = $tag_obj->get_must_tag_arr();

            //获得可选的员工标签
            $data['not_must_tag_arr'] = $tag_obj->get_not_must_tag_arr();

            //系统选中的可选员工标签名，多个用，号分隔
            $seled_not_must_tag_arr = '';
            $seled_not_must_tag_names = '';

            //自定义员工标签数组
            $user_defined_tag_arr = array();

            //获得部门层级
            $department_level = 0;
            //是修改功能
            // 站点ID
            $site_id = $this->p_site_id;//1 ;

            $this->load->model('UC_User_Tags_Model');
            //从数据库获得系统可选标签及自定义员工标签信息
            $data_tags = array(  
               'select' =>'id,site_id,tag_name,tag_scope,tag_type,enable',
               'where' => array('site_id' => $site_id),
           );
           $tag_arr =  $this->UC_User_Tags_Model->operateDB(2,$data_tags);

            $tag_obj->resolve_tag_arr($tag_arr);
            //获得当前站点的选中的可选员工标签名，多个用，号分隔
            $seled_not_must_tag_names = $tag_obj->get_seled_not_must_tag_names();
            $seled_not_must_tag_arr =  $tag_obj->get_seled_not_must_tag_arr(); ;
            //自定义员工标签数组
            $user_defined_tag_arr = $tag_obj->get_user_defined_tag_arr();

            //获得部门层级
            //从管理员表获取部门层级
            $this->load->model('UC_Site_Model');
            $data_sel = array(  
                'select' =>'department_level',
                'where' => array('siteID' => $site_id)
                );
            $uc_site_arr = $this->UC_Site_Model->operateDB(1,$data_sel);
            $department_level = isset($uc_site_arr['department_level'])?$uc_site_arr['department_level']:0;
            
            //print_r($data_sel);
            //选中的可选的员工标签
            $data['seled_not_must_tag_arr'] = $seled_not_must_tag_arr ;
            $data['seled_not_must_tag_names'] = $seled_not_must_tag_names;
            
            //获得当前自定义标签
            $data['user_defined_tag_arr'] = $user_defined_tag_arr;
            $data['department_level'] = $department_level ;
            //$page_type 标签类型0新加页面 1修改页面
            $data['page_type'] = $page_type;

            //员工导入方式0：否（批量导入）；1：是（LDAP导入）；2：全部都可以
            $data['is_LDAP'] = $is_LDAP ;

            //加载视图
            $this->load->view('tag/tag_1.php',$data);
        }catch(Exception $e){
            log_message('debug', $e->getMessage());
            exit();
        }
        log_message('info','the view is loaded successfully!');
        log_message('info', 'out method ' . __FUNCTION__ . '.');
    }


    
    
    
    
    
    /**
     *
     * @brief 保存新加/修改员工标签
     * @details
     * -# 获得表单信息
     * -# 校验表单信息：[自定义员工标签]名称最长可输入 20 个字，可输入中英文数字，名称不可重复
     * -# 通过接口保存员工标签设置信息
     * -# 保存当前企业[部门层级]标签层级值
     * -# 获得其他管理员（员工管理员、合作伙伴管理员），手机和邮箱
     * -# 如果获得有其他管理员，则发送消息，发手机和邮箱，同时保存消息，他管理员登陆后台进行相关操作。
     * 来源：总管理员对其他管理员（员工管理员、合作伙伴管理员）
     * 发布；消息分类[新增员工标签]；
     * 内容:总管理员新增员工标签：[员工标签]，请立即更新
     * -# 写日志内容:"添加员工标签名称"
     * @return null
     *
     */
    public function addTag() {
        log_message('info', 'into method ' . __FUNCTION__ . '.');

        $deptLevel = $this->input->post('department_level');
        $necessaryTags = $this->input->post('necessaryTags');
        $optionalTags = $this->input->post('optionalTags');  

        $result = $this->validateData($deptLevel, $necessaryTags, $optionalTags);
        if($result === false){
        	return;
        }
        
        $deptLevel 			= $result['dept_level'];
        $necessaryTags		= $result['necessary'];
        $optionalTags		= $result['optional'];

        if(!$this->updateDeptLevel($deptLevel, $this->p_site_id)){
    		form_json_msg('1', '', '保存失败！');
    		return;        	
        }
     
		if(!$this->deleteNotUseTags($necessaryTags, $optionalTags, $this->p_site_id)){
			form_json_msg('1', '', '保存失败！');
			return;
		}
		
		if(!$this->saveTags($necessaryTags, $this->p_site_id)){
			form_json_msg('1', '', '保存失败！');
			return;
		}

		if(!$this->saveTags($optionalTags, $this->p_site_id)){
			form_json_msg('1', '', '保存失败！');
			return;
		}
		
        $this->save_suffix();
        
        //保存成功
        log_message('info', 'save tags success.');
        log_message('info', 'out method ' . __FUNCTION__ . '.');
    		
        return_json(COMMON_SUCCESS);
    }
    
    private function validateData($deptLevel, $necessaryTags, $optionalTags){
    	log_message('info', 'into method ' . __FUNCTION__ . '.');   
    	
    	//对部门层级进行0-10的验证
    	if(!preg_match('/^([0-9]|10)$/', $deptLevel)){
    		form_json_msg('1', '', '请选择部门层级！');
    		return false;
    	}
    	
    	$validNecessaryTags = $this->validateNecessaryTags($necessaryTags);
    	if($validNecessaryTags === false){
    		return false;
    	}
    	
    	$validOptionalTags = $this->validateOptionalTags($validNecessaryTags, $optionalTags);
    	if($validOptionalTags === false){
    		return false;
    	}
    	
    	return array(
    			'dept_level'		=> (int)$deptLevel,
    			'necessary'			=> $validNecessaryTags,
    			'optional'			=> $validOptionalTags
    	);
    }
    
    private function validateNecessaryTags($necessaryTags){
        log_message('info', 'into method ' . __FUNCTION__ . '.');
        log_message('info', '$optionalTags => ' . $necessaryTags);
        
    	try {
    		$tags = json_decode($necessaryTags , TRUE);
    		 
    	}catch (Exception $e) {
    		form_json_msg('1', '', '保存失败！');
    		 
    		log_message('debug', $e->getMessage());
    		return false;
    	}
    
    	if(!is_array($tags)){
    		return false;
    	}
    	 
    	//获得的是数组
    	$validTags = array();
    	$index = 1;
    	foreach($tags as $key => $val){
    		 
    		if(!is_array($val)){
    			continue;
    		}
    		 
    		$id 				= $val['id'];
    		$tagName 			= $val['tag_name'];
    		$tagCode			= $val['tag_code'];
    		$tagType 			= 0;
    		$enable 			= 1;
    		$clientSearchable 	= 1;
    		$clientVisible		= 1;
    		$clientEditable		= isset($val['client_editable']) ? $val['client_editable'] : 0;
    		$valueMaxLenght		= $val['value_max_length'];
    		$sequence			= $index;
    		
    		$index = $index + 1;
    		 
    		// 验证id为整数
    		if(isset($id) && !preg_match("/\d/", $id)){
    			form_json_msg('1', '', '必选参数有误！');
    			return false;
    		}
    		 
    		//标签名规则验证
    		if(!preg_match('/^[\s\S]{1,50}$/', $tagName)){
    			form_json_msg('1', '', '标签名称参数有误！');
    			return false;
    		}
    		
    		$clientEditable = (int)$clientEditable === 0 ? 0 : 1;

    		if($clientEditable === 0){
    			$valueMaxLenght = 0;
    		}
    		else{
	    		if(!preg_match("/\d/", $valueMaxLenght)){
	    			$valueMaxLenght = 99;
	    		}
	    		else{
	    			$valueMaxLenght = (int)$valueMaxLenght;
	    		}
    		}
    		 
    		$validTags[] = array(
    				'id'					=> $id,
    				'tag_name'				=> $tagName,
    				'tag_code'				=> $tagCode,
    				'tag_type' 				=> $tagType,
    				'enable'				=> $enable,
    				'client_searchable' 	=> $clientSearchable,
    				'client_visible' 		=> $clientVisible,
    				'client_editable' 		=> $clientEditable,
    				'value_max_length'		=> $valueMaxLenght,
    				'sequence'				=> $sequence
    		);
    	}
    
    	return $validTags;
    }
    
    private function validateOptionalTags($validNecessaryTags, $optionalTags){
        log_message('info', 'into method ' . __FUNCTION__ . '.');
        log_message('info', '$optionalTags => ' . $optionalTags); 
        
    	try {
    		$tags = json_decode($optionalTags , TRUE);
    			
    	}catch (Exception $e) {
    		form_json_msg('1', '', '保存失败！');
    	
    		log_message('debug', $e->getMessage());
    		return false;
    	}
    	 
    	if(!is_array($tags)){
    		return false;
    	}
    	
    	//获得的是数组
    	$validTags = array();
    	foreach($tags as $key => $val){
    	
    		if(!is_array($val)){
    			continue;
    		}
    	
    		$id 				= $val['id'];
    		$tagName 			= $val['tag_name'];
    		$tagCode			= $val['tag_code'];
    		$tagType 			= $val['tag_type'];
    		$enable 			= $val['selected'];
    		$clientSearchable 	= $val['client_searchable'];
    		$clientVisible		= $val['client_visible'];
    		$clientEditable		= $val['client_editable'];
    		$valueMaxLenght		= $val['value_max_length'];
    		$sequence			= $val['sequence'];
    	
    		// 验证id为整数
    		if(isset($id) && !preg_match("/\d/", $id)){
    			form_json_msg('1', '', '可选参数有误！');
    			return false;
    		}
    	
    		//标签名规则验证
    		if(!preg_match('/^[\s\S]{1,50}$/', $tagName)){
    			form_json_msg('1', '', '标签名称参数有误！');
    			return false;
    		}
    	
    		//验证1-基本标签2-自定义
    		if(!preg_match('/^[12]$/',$tagType)){
    			form_json_msg('1', '', '标签类型参数有误！');    			 
    			return false;
    		}
    		
    		if(!isset($tagCode)){
    			$tagCode = create_guid();
    		}
    	
    		$enable = (int)$enable === 0 ? 0 : 1;
    		$clientSearchable = (int)$clientSearchable === 0 ? 0 : 1;
    		$clientVisible = (int)$clientVisible === 0 ? 0 : 1;
    		$clientEditable = (int)$clientEditable === 0 ? 0 : 1;
    	
    		if(!preg_match("/\d/", $valueMaxLenght)){
    			$valueMaxLenght = 99;
    		}
    		else{
    			$valueMaxLenght = (int)$valueMaxLenght;
    		}
    		
    		foreach ($validNecessaryTags as $validData){
    			if($validData['tag_name'] == $tagName){
    				form_json_msg('1', '', '标签' . $tagName . '已经存在，请重新填写！');
    				return false;
    			}
    		}
    	
    		foreach ($tags as $validKey => $validData){
    			if($key == $validKey){
    				break;
    			}
    			 
    			if($validData['tag_name'] == $tagName){
    				form_json_msg('1', '', '标签' . $tagName . '已经存在，请重新填写！');
    				return false;
    			}
    		}
    	
    		$validTags[] = array(
    				'id'					=> $id,
    				'tag_name'				=> $tagName,
    				'tag_code'				=> $tagCode,
    				'tag_type' 				=> $tagType,
    				'enable'				=> $enable,
    				'client_searchable' 	=> $clientSearchable,
    				'client_visible' 		=> $clientVisible,
    				'client_editable' 		=> $clientEditable,
    				'value_max_length'		=> $valueMaxLenght,
    				'sequence'				=> $sequence
    		);
    	}
    	 
    	return $validTags;
    }

    private function updateDeptLevel($deptLevel, $siteId){
        log_message('info', 'into method ' . __FUNCTION__ . '.');
        log_message('info', '$deptLevel => ' . $deptLevel . ', $siteId => ' . $siteId);
    	
    	try {
    		$this->load->model('UC_Site_Model');
    	
    		$data = array(
    				'update_data' 	=> array('department_level' => $deptLevel),
    				'where' 		=> array('siteID' => $siteId)
    		);
    		
    		$this->UC_Site_Model->operateDB(5, $data);
    		
    	} catch (Exception $e) {
    		log_message('debug', $e->getMessage());    		
    		return false;
    	}
    	
    	return true;
    }
    
    private function deleteNotUseTags($necessaryTags, $optionalTags, $siteId){
        log_message('info', 'into method ' . __FUNCTION__ . '.');
        
    	$retainedIds = array();
    	foreach($necessaryTags as $tag){
    		$id = $tag['id'];
    		
    		if(isset($id)){
    			$retainedIds[] = $id;
    		}
    	}
    	
    	foreach($optionalTags as $tag){
    		$id = $tag['id'];
    		
    		if(isset($id)){
    			$retainedIds[] = $id;
    		}
    	}
    	
    	if(count($retainedIds) == 0){
    		return true;
    	}
    	
    	try {    	
    		$this->load->model('tags_model');    		    		
    		$result = $this->tags_model->deleteNotUseTags($retainedIds, $siteId);
    		
    		if($result){    			
    			//日志
    			$this->load->library('LogLib', '', 'LogLib');    			
    			$re_id = $this->LogLib ->set_log(array('5','13'), $this->p_sys_arr);
    			
    			log_message('info', 'del tags  success.');
    	
    			return true;
    		}else{
    			log_message('info', 'del tags  fail.');
    			return false;    			
    		}
    		
    	} catch (Exception $e) {
    		log_message('debug', $e->getMessage());    		
    		return false;
    	}
    }
    
    private function saveTags($tags, $siteId){
        log_message('info', 'into method ' . __FUNCTION__ . '.');
        
		$this->load->helper('my_dgmdate');
		$this->load->model('UC_User_Tags_Model');
		$this->load->library('LogLib', '', 'LogLib');
    	
		$tagsMessage = array();
		foreach($tags as $key => $val){
			$id = $val['id'];
			$tagName = $val['tag_name'];
			$tagType = $val['tag_type'];
			
			$val['site_id'] = $siteId;
			$val['tag_scope'] = 1;
			 
			if(isset($id)) {
				//更新
				$val['modify'] = dgmdate(time(), 'dt') ;
		
				$update_state = $this->UC_User_Tags_Model->UpdateData($val, array(
						'id = ' => $id,
						'site_id = ' => $siteId
				));
		
				if($update_state){
					$this->LogLib ->set_log(array('5','12'), $this->p_sys_arr);			//日志
					log_message('info', 'Update tag:' . $tagName . '  success.');
				}else{
					log_message('info', 'Update tag:' . $tagName . '  fail.');
					return false;
				}
		
			}else{
				//插入
				unset($val['id']);
				$val['created'] = dgmdate(time(), 'dt') ;
		
				$insert_arr =  $this->UC_User_Tags_Model->insert_db($val);
		
				$id = 0;
				if(db_operate_fail($insert_arr)){
					log_message('error', 'insert  UC_User_Tags_Model tag:' . $tagName . '  fail.');
					return false;
				}else{
						
					$id = isset($insert_arr['insert_id'])?$insert_arr['insert_id']:0;;
					log_message('debug', 'insert  UC_User_Tags_Model tag:' . $tagName . '  success.');
		
					$this->LogLib ->set_log(array('5','11'), $this->p_sys_arr);    //日志
				}
		
				if($id > 0 && $tagType == 2){
					$tagsMessage[] = array(
							'tag_id' 	=> $id,
							'tag_name' 	=> $tagName,
							'type' 		=> 1,
							'require' 	=> 1
					);
				}
			}
		}
		

		try{
		//如果有新加的自定义标签，则发送消息
		if(!isemptyArray($tagsMessage)){
			//接口参参数
			$data = 'session_id=' . $this->p_session_id . '&user_id=' . $this->p_user_id . '&type=1&data=' . json_encode($tagsMessage);
		
			//调用登陆接口
			$ucc_msg_arr = $this->API->UCCServerAPI($data, 11);
			if(!api_operate_fail($ucc_msg_arr)){//成功
				log_message('info', '  uccserver api message/systemAlert ' . $data . ' success .');
			}else{
				log_message('info', '  uccserver api message/systemAlert ' . $data . ' fail .');
			}
		}
		}catch (Exception $e){
    		log_message('error', $e->getMessage());
    	}
    	
    	return true;
    }
    
    /**
     * 保存”登陆是否使用自定义后缀“
     *
     * @author Bai Xue <xue.bai_2@quanshi.com>
     */
    private function save_suffix() {
    	$use_suffix = $this->input->get_post('use_suffix', true); 	// 登陆是否使用自定义后缀
    	$suffix 	= $this->input->get_post('suffix', true); 		// 自定义后缀
    	log_message('info', 'Into method save_suffix input -->$suffix'.$suffix);
    
    	try{
    		// 判断表单提交的数据是否合法
    		if(!in_array($use_suffix, array(NOT_USE_SELF_DEFINED_SUFFIX, USE_SELF_DEFINED_SUFFIX))){
    			throw new Exception('Illegal  input --> $suffix='.$suffix);
    		}
    			
    		// 获得站点的自定义后缀配置
    		$this->load->library('UcadminLib', '', 'ucadmin');
    		$old_suffix_set_arr = $this->ucadmin->get_suffix($this->p_site_id);
    		$old_use_suffix 	= isset($old_suffix_set_arr['use_suffix']) ? $old_suffix_set_arr['use_suffix'] : '';
    		$old_suffix 		= isset($old_suffix_set_arr['suffix']) ? $old_suffix_set_arr['suffix'] : '';
    			
    		// 载入站点配置模型
    		$this->load->model('uc_site_config_model');
    			
    		// 如果”登陆是否使用自定义后缀“发生变化
    		if($use_suffix != $old_use_suffix){
    
    			if($use_suffix == NOT_USE_SELF_DEFINED_SUFFIX){
    				// 删除原来的自定义后缀名
    				$this->uc_site_config_model->delete_vaule('ACCOUNT_AUTHENTICATION_TYPE', $this->p_site_id, 'custom_login_name_suffix');
    			}else{
    				// 新建自定义后缀名
    				$this->uc_site_config_model->insertValue('ACCOUNT_AUTHENTICATION_TYPE', $this->p_site_id, 'custom_login_name_suffix', $suffix);
    			}
    
    			// 在uc_site_config表更新：登陆是否使用自定义后缀
    			$this->uc_site_config_model->setVaule('ACCOUNT_AUTHENTICATION_TYPE', $this->p_site_id, 'use_custom_login_name', $use_suffix);
    		}else{
    
    			if($old_suffix != $suffix){
    				// 在uc_site_config表更新自定义后缀
    				$this->uc_site_config_model->setVaule('ACCOUNT_AUTHENTICATION_TYPE', $this->p_site_id, 'custom_login_name_suffix', $suffix);
    			}
    		}
    			
    		return_json(COMMON_SUCCESS);
    			
    	}catch (Exception $e){
    		log_message('error', $e->getMessage());
    		return_json(COMMON_FAILURE, $this->lang->line('falied'), array());
    	}
    }

    
    
    
    
    

    /**
     * 标签管理
     *@author hongliang.cao
     *@date   2014.12.30
    */
    public function manageTag(){
        log_message('info', 'into method ' . __FUNCTION__ . '.');
        
		//从数据库中获取可选标签和自定义标签
		$this->load->model('tags_model');
		$tags = $this->tags_model->getSiteTags($this->p_site_id);
		$deptLevel = $this->tags_model->getDepartmentLevels($this->p_site_id);
		$ldapSetting = $this->getLDAPSetting();
		        
        $this->load->view('tag/tag_new.php', array(
            'necessary_tags'     	=>  $tags['necessary'],
            'optional_tags' 		=>  $tags['optional'],
        	'department_level' 		=>  isset($deptLevel) ? $deptLevel : '-1',
        	'is_LDAP'				=>	$ldapSetting['is_LDAP'],
        	'self_defined_suffix'	=>  $ldapSetting['self_defined_suffix']
        ));
    }
    
    private function getLDAPSetting(){
        log_message('info', 'into method ' . __FUNCTION__ . '.');
        
    	// LDAP站点登录名规则
    	$this->load->library('UcadminLib', '', 'ucadmin');
    	$this->load->model('uc_site_config_model');
    	
    	$self_defined_suffix = $this->ucadmin->get_suffix($this->p_site_id);
    	$data_import_mode = $this->uc_site_config_model->getValue('ACCOUNT_AUTHENTICATION_TYPE', $this->p_site_id, 'DATA_IMPORT_TYPE');
    	
    	return array(
    			"is_LDAP" => $this->p_is_ldap,
    			'self_defined_suffix' => $self_defined_suffix
    	);
    }
    
    
    
    
    
    
    
    
    
    /**
     * 标签设置
     *@author hongliang.cao
     *@date   2014.12.30
    */
    public function setTag(){
        //获取设置项
        //--1.必选标签里的"部门"
        //--2.可选标签，已选项目
        //--3.自定义标签
        $tags           =   $this->input->post('tags', true);
        if(empty($tags) || is_null($tags = json_decode($tags, true))){
            return_json(COMMON_FAILURE);
        }

        $dept           =   $tags['dept'];    //部门标签   
        $optional_tags  =   $tags['optional_tags']; //可选标签，已选标签
        $customer_tags  =   $tags['customer_tags']; //自定义标签

        //从配置文件里获取系统必选标签、可选标签、以及自定义标签pattern
        $this->config->load('tags2');
        $system_tags    = $this->config->item('system_tags');
        $customer_tags  = $this->config->item('customer_tags');
        $customer_tags_pattern = $customer_tags['pattern'];

        //--1.部门层级标签校验即数据整理
        //--校验部门层级
        $dept_config = $system_tags[$dept['name']];
        if(!preg_match($dept_config['pattern'], $dept['value'])){
            return_json(COMMON_FAILURE);
        }

        //--可选标签校验
        foreach($optional_tags as $optional_tag){
            $selected_tag_name = $optional_tag['name'];
            
        }
        
        
        //--自定义标签校验

        //标签设置信息入库。
        //成功
    }

    /**
     * 删除自定义标签
     *@author hongliang.cao
     *@date   2014.12.30
    */
    public function delCustomerTag(){
        //获取参数
        $tag_id = intval($this->input->get_post('tag_id', true));

        //删除tag以及tag中的value
        $this->load->model('uc_user_tags_model', 'tag_model');
        $this->tag_model->delTag($this->p_site_id, $tag_id);

        //返回
        
    }



}
