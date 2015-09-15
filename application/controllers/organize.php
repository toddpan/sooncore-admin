<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @abstract 	Organize Controller，主要负责对组织结构的获取、显示、新加、删除、权限管理等操作。
 * @filesource 	organize.php
 * @author 		zouyan <yan.zou@quanshi.com>
 * @copyright 	Copyright (c) UC
 * @version 	v1.0
 */
class Organize extends Admin_Controller {
	protected $org_power_arr;//组织权限数组,格式-513-521-528 equal 相等-多个,号分隔,in 包含在内的-多个,号分隔,like 包含在外的 -多个,号分隔
	protected $org_spread_arr;//组织默认展开[下一级]数组,格式-513-521-528 equal 相等-多个,号分隔,in 包含在内的-多个,号分隔,like 包含在外的 -多个,号分隔

	/**
	 * @abstract 构造函数
	 */
	public function __construct() {
		parent::__construct();
		$this->load->helper('my_publicfun');
		$this->load->helper('my_dgmdate');
		// 调用分配域的接口
		$this->load->library('API', '', 'API');
		// 加载组织类库
		$this->load->library('OrganizeLib', '', 'OrganizeLib');
		// 加载组织类库
		$this->load->library('UccLib', '', 'ucc');
		// 加载维度UserResourceLib类
		$this->load->library('UserResourceLib', '', 'UserResourceLib');
		// 加载部门相关操作的中文提示信息类
		$this->lang->load('orgnization', 'chinese');
		//$scope_org_vales = $this->UserResourceLib->get_resource_value($this->p_uc_user_resource_arr,1);
	}

	/**
	 * @abstract 组织结构列表页面：
	 * @details
	 * -# load组强结构页面
	 * -# ajax加载最顶级组织结构
	 * 注意:
	 * 在页面js维护一套组织结构
	 * js数组格式:当前组织编号orgid,当前组织父编号pid,当前组织名称orgname,
	 *          组织管理者id[默认都为0，只有当切换到当前组织列表时，实时更新]
	 * 主要目的：
	 *    1、左则切换组织时，实时js更新组织[导航]
	 *    2、取消或设置组织管理者时，实时更新此js组织管理者id
	 *      注意：批量员工调岗和删除员工时，如果选中的员工有组织管理者，时的处理流程
	 *    3、[新加、修改、删除组织、取消或设置组织管理者时注意维护]
	 * -# js组织显示对应的组织[导航]
	 * -# ajax加载对应组织结构员工列表信息[交互已确认没有翻页]
	 */
	public function listOrgPage() {
		$this->load->view('organize/orglist.php');//load组织结构页面
	}

	/**
	 * @abstract 显示Ldap导入成功后的组织结构链接页面
	 *
	 */
	public function ldaporg() {
		$this->load->view('organize/ldaporg.php');
	}

	/**
	 * @abstract  弹框显示确认取消组织管理者页面
	 */
	public function unset_manager() {
		$this->load->view('public/popup/unsetManager.php');
	}

	/**
	 * @abstract 保存取消组织管理者页面
	 */
	public function save_unset_manager(){
		$this->load->library('OrganizeLib','','OrganizeLib');
		//当前组织id
		$org_id = strtolower($this->input->post('orgid' , TRUE));
		$org_id = empty_to_value($org_id,0);
		//父组织id
		$org_pid = $this->input->post('parent_orgid' , TRUE);
		$org_pid = empty_to_value($org_pid,0);
		//当前用户id
		$user_id = strtolower($this->input->post('user_id' , TRUE));
		$user_id = empty_to_value($user_id,0);

		$in_arr = array(
            'org_id' => $org_id,//组织id
            'site_id' => $this->p_site_id,//站点id 
            'user_id' => $user_id,//用户id
            'isset' => 0,//0取消，1设置修改
		);
		$sys_arr = $this->p_sys_arr;
		$operate_boolean = $this->OrganizeLib->modify_manager($in_arr,$sys_arr);
		if($operate_boolean){
			form_json_msg('0','', '取消组织管理者成功');//返回信息json格式
		}else{
			form_json_msg('1','', '取消组织管理者失败');//返回信息json格式
		}
	}

	/**
	 * @abstract 显示Ldap导入成功后的组织结构链接页面
	 */
	public function set_manager() {
		$this->load->view('public/popup/setManager.php');
	}

	/**
	 *@abstract 确定删除组织弹出框
	 */
	public function sure_del_org() {
		$this->load->view('public/popup/sureDelOrg.php');
	}

	/**
	 * @abstract确定删除组织弹出框
	 */
	public function sure_del_org2() {
		$this->load->view('public/popup/sureDelOrg.php');
	}

	/**
	 * @brief 弹窗显示员工调岗页面
	 * @details
	 * -# 弹窗显示员工调岗页面
	 */
	public function save_set_manager(){
		$this->load->library('OrganizeLib','','OrganizeLib');

		//当前组织id
		$org_id = $this->input->post('orgid' , TRUE);
		$org_id = empty_to_value($org_id,0);
		//父组织id
		$org_pid = $this->input->post('parent_orgid' , TRUE);
		$org_pid = empty_to_value($org_pid,0);
		//当前用户id
		$user_id = $this->input->post('user_id' , TRUE);
		$user_id = empty_to_value($user_id,0);
		$in_arr = array(
            'org_id' => $org_id,//组织id
            'site_id' => $this->p_site_id,//站点id 
            'user_id' => $user_id,//用户id
            'isset' => 1,//0取消，1设置修改
		);
		$sys_arr = $this->p_sys_arr;

		$operate_boolean = $this->OrganizeLib->modify_manager($in_arr,$sys_arr);
		if($operate_boolean){
			//日志
           $this->load->library('LogLib','','LogLib');
           $log_in_arr = $this->p_sys_arr;
           array(
                 'Org_id' => $this->p_org_id ,//组织ID
                 'site_id' => $this->p_site_id ,//站点ID
                 'operate_id' => $this->p_user_id,//操作会员ID
                 'login_name' => $this->p_account ,//操作账号[可以为空，没有，则重新获取]
                 'display_name' => $this->p_display_name,//操作姓名[可以为空，没有，则重新获取]
                 'client_ip' => $this->p_client_ip ,//客户端ip
             );
          $re_id = $this->LogLib ->set_log(array('5','15'),$log_in_arr);
			form_json_msg('0','', '指定为组织管理者成功');//返回信息json格式
		}else{
			form_json_msg('1','', '指定为组织管理者失败');//返回信息json格式
		}
	}

	/**
	 *
	 * @brief 组织结构页面-ajax加载下级组织结构并加载对应视图--?需要接口2.5.1：
	 * @details
	 * -# 前置条件，在页面js会先去判断下级组织是否已经存在，存在则直接js控制显示，不存在才执行此函数获取
	 * -# 获得JS post 过来的 orgPid 需要获得的下级组织结构的父id，默认为0获得最顶级组织结构
	 *    对orgPid进行是否数字验证，正确则获得数据，否则不获得数据
	 * -# 获得父组织结构id下的组织结构并分配到视图
	 * @return null
	 *
	public function OrgList() {
            $this->load->view('organize/organizeStaff.php');
	}
	 */
        
        /**
	 *
	 * @brief 组织结构页面-获得根组织和下一级组织
	 * @details
	 * -# 仅得到根组织和下一级组织数据，下一级的子级为保证页面速度将使用AJAX实现
         */
        public function OrgList(){
            $customer_code = $this->p_customer_code;//客户编码
            $org_id = $this->p_org_id;//组织ID
            $site_id = $this->p_site_id;//站点ID
            //$ret = $this->ucc->getOrgList($user_id, $session_id, $org_id, $customer_code);
            
            //首级及下一级组织数组
            $org_arr = $this->OrganizeLib->get_first_next_org_arr($customer_code);
            if(isemptyArray($org_arr)){
		log_message('error', __FUNCTION__ . " input->\n" . var_export($org_arr, true));
                form_json_msg('1','', '没有得到组织信息，请尝试重新登陆');
            }
            //首级组织下的所有用户
            $first_org_user = $this->OrganizeLib->get_users_list($org_id,$site_id);
            //print_r($org_arr);
            $data['org_json'] = $org_arr;
            
            $data['user_arr'] = $first_org_user;
            
            $this->load->view('organize/organizeStaff.php',$data);
        }




        /**
	 * @abstract 组织结构树
	 */
	public function get_org_tree(){
		//$this->load->library('OrganizeLib','','OrganizeLib');

		//获得客户编码
		$customer_code = $this->p_customer_code;

		//首级及下一级组织数组
		$first_next_org_arr = $this->OrganizeLib->get_first_next_org_arr($customer_code);

		//首级及下一级组织json串
		$in_arr = array(
                    'is_first' => 1 ,//是否第一级0不是1是       
		);
		$org_arr = $this->OrganizeLib->InitzTree_arr($first_next_org_arr ,1,$in_arr);//格式化成目录树结构
		$org_json = '[]';
		if(is_array($org_arr)){//如果是数组
			$org_json = json_encode($org_arr);
		}
			
		form_json_msg('0','', $org_json);
	}

	public function get_first_org_user() {
		$this->load->library('OrganizeLib','','OrganizeLib');
		//获得客户编码
		$customer_code = $this->p_customer_code;//'024014';
		//根据客户编码，获得第一级
		$first_org_data = $this->OrganizeLib->get_first_org_arr($customer_code,2);

		//下一级组织json串
		$in_arr = array(
            'is_first' => 1 ,//是否第一级0不是1是       
		);
		$org_arr = $this->OrganizeLib->InitzTree_arr($first_org_data ,1,$in_arr);
		$org_json = '[]';
		if(is_array($org_arr)){//如果是数组
			$org_json = json_encode($org_arr);
		}
		echo $org_json;
	}

	/**
	 * @abstract 移动部门
	 * @detail
	 * -# 1、获取被移动部门的id、被移动部门旧的父子id串、被移动部门新的父id和被移动部门新的父子id串
	 * -# 2、判断每个节点是不是正确
	 * -# 3、调用UMS接口来移动部门
	 */
	public function move_org(){
		$move_org_id 	= $this->input->post('move_org_id', true);	// 被移动部门的id
		$old_parent_id 	= $this->input->post('old_parent', 	true);	// 被移动部门旧的父id
		$old_brother_id = $this->input->post('old_brother', true);	// 被移动部门旧的兄弟id串
		$newParentId 	= $this->input->post('new_parent', 	true);	// 被移动部门新的父id
		$new_brother_id = $this->input->post('new_brother', true);	// 被移动部门新的兄弟id串
		$input_arr = array(
			'move_org_id' => $move_org_id,
			'old_parent'  => $old_parent_id,
			'old_brother' => $old_brother_id,
			'newParentId' => $newParentId,
			'new_brother' => $new_brother_id
		);
		log_message('info', __FUNCTION__ . " input->\n" . var_export($input_arr, true));
		
		if($this->p_role_id != SYSTEM_MANAGER && $this->p_role_id != ORGANIZASION_MANAGER){
			form_json_msg('1', '', '您没有权限');
		}

		// 验证以上数据是否正确
		if($move_org_id > 0 && !preg_match("/^[\d]+$/", $move_org_id)){
			form_json_msg('1', '', $this->lang->line('move_org_0')); //'The param of $move_org_id is wrong.'
		}
		if($old_parent_id >0 && !preg_match("/^[\d]+$/", $old_parent_id)){
			form_json_msg('1', '', $this->lang->line('move_org_1')); //'The param of $old_parent_id is wrong.'
		}
		if($newParentId >0 && !preg_match("/^[\d]+$/", $newParentId)){
			form_json_msg('1', '', $this->lang->line('move_org_2')); //'The param of $newParentId is wrong.'
		}
		if(!empty($old_brother_id) && !preg_match("/(\d)(,\d)*/", $old_brother_id)){
			form_json_msg('1', '', $this->lang->line('move_org_3')); //'The param of $old_brother_id is wrong.'
		}
		if(!empty($new_brother_id) && !preg_match("/(\d)(,\d)*/", $new_brother_id)){
			form_json_msg('1', '', $this->lang->line('move_org_4')); //'The param of $new_brother_id is wrong.'
		}

		// 组装数据，调用UMS接口
		$formdata_arr = array(
			'id' 					 => $move_org_id,
			'old_parent_child_order' => $old_parent_id . ',' . $old_brother_id,
			'newParentId' 			 => $newParentId,
			'new_parent_child_order' => $newParentId . ',' . $new_brother_id
		);
		$re_arr = $this->API->UMS_Special_API('', 23, array('url' => http_build_query($formdata_arr)));

		// 获取code
		$is_suc = arr_unbound_value($re_arr, 'code', 2, array());

		if($is_suc == 0){
			// 移动部门成功
			
			// TODO 发消息到数据库确定被移动部门及其下面员工的新权限
			
			// TODO 调用战役接口发送系统消息
			
			// 在安全管理的日志管理中记录日志
			$this->load->library('LogLib', '', 'LogLib');
			$log_in_arr = $this->p_sys_arr;
			$this->LogLib ->set_log(array('5', '18'), $log_in_arr);
			
			form_json_msg('0', '', $this->lang->line('move_org_5'), array()); //'move org success'
		}else{
			// 移动部门失败
			form_json_msg('1', '', $this->lang->line('move_org_6'), array()); //'move org fail'
		}
	}

	/**
	 * @brief 根据post过来的组织id，获得下级组织信息及人级组织人员信息：
	 * @return echo输出下级组织的json串信息
	 */
	public function get_next_orguser_list(){
		$this->load->library('OrganizeLib','','OrganizeLib');
		$org_id  = strtolower($this->input->post('org_id' , true));//当前组织id
		$org_id = empty_to_value($org_id,517);//517
		$type  = strtolower($this->input->post('type' , true));//类型1组织调入员工，2成本中心调入员工
		$type = empty_to_value($type,1);//
		//获得当前下级组织及当前组织用户数组
		$org_user_list_data = $this->OrganizeLib->get_org_user_array($org_id,$this->org_tree_type);

		if(isemptyArray($org_user_list_data)){//空数组
			form_json_msg('1','','获得下级组织及员工信息有误',array('user_json' =>'' ));//返回信息json格式
		}
		$userCount = arr_unbound_value($org_user_list_data,'userCount',2,0);
		$childNodeCount = arr_unbound_value($org_user_list_data,'childNodeCount',2,0);
		$ns_user_arr = array();
		if($userCount > 0){ //有用户
			$ns_user_arr = arr_unbound_value($org_user_list_data,'users',1,array());
		}
		$ns_org_arr = array();
		if($childNodeCount > 0){ //有下级组织
			$ns_org_arr = arr_unbound_value($org_user_list_data,'childs',1,array());
		}
		//下一级组织json串
		$in_arr = array(
            'is_first' => 0 ,//是否第一级0不是1是       
		);
		$org_arr = $this->OrganizeLib->InitzTree_arr($ns_org_arr ,1,$in_arr);
		$user_arr = $this-> OrganizeLib->user_to_user_tree($ns_user_arr,$org_id,1);
		$re_arr = array(
            'userCount' => $userCount,//所属用户数量
            'childNodeCount' => $childNodeCount,//所属下级组织数量
            'users' => json_encode($user_arr),//所属用户数组
            'orgs' => json_encode($org_arr),//所属下级组织数组            
		);
		form_json_msg('0','','当前组织所属下级组织及所属帐号信息',$re_arr);//返回信息json格式
	}

	/**
	 * @brief 根据post过来的组织id，获得下级组织信息：
	 * @return echo输出下级组织的json串信息
	 */
	public function get_next_OrgList(){
		$this->load->library('OrganizeLib','','OrganizeLib');
		$org_id  = strtolower($this->input->post('org_id' , true));//当前组织id
		$org_id = empty_to_value($org_id,0);//517
		//获得当前下级组织数组
                $org_list_data = array();
		$org_list_data = $this->OrganizeLib->get_org_array($org_id,'nextlevel','1,3,5');
		//print_r($org_list_data);
		///exit;
		if(is_array($org_list_data)){//如果是数组
			$org_json = json_encode($org_list_data);
		}
		echo $org_json;
	}

	/**
	 * @brief 根据post过来的组织id，获得当前组织的帐号列表信息[部门管理者在前，其它人员在后]
	 */
	public function get_users_list(){
		$org_id=$this->input->post('org_id', true);
		$org_id = empty_to_value($org_id,0);//517
		//新的父组织id
		$org_pid = $this->input->post('parent_orgid' , true);
		$org_pid = empty_to_value($org_pid,0);
		write_test_file( '' . __FUNCTION__ . time() . '.txt' ,'$org_id =' .$org_id);
		$site_id = $this->p_site_id;
		$site_id = empty_to_value($site_id,0);//517
		$this->load->library('OrganizeLib','','OrganizeLib');
		$user_arr = $this->OrganizeLib->get_users_list($org_id ,$site_id );
		//print_r($user_arr);
		// echo json_encode($user_arr);
		// die();
		$data['user_arr'] = $user_arr;
		$this->load->view('public/part/userlist.php',$data);
	}

	/**
	 * @brief 根据post过来的组织id，获得当前组织的帐号列表信息[部门管理者在前，其它人员在后]
	 */
	public function get_users_json_by_orgid(){
		$org_id=$this->input->post('org_id', true);
		$org_id = empty_to_value($org_id,0);//517
		$site_id = $this->p_site_id;
		$site_id = empty_to_value($site_id,0);//517
		$this->load->library('OrganizeLib','','OrganizeLib');
		$ns_user_arr = $this-> OrganizeLib->get_users_list($org_id ,$site_id );
		if(is_array($ns_user_arr)){//是数组
			$user_arr = $this-> OrganizeLib->user_to_user_tree($ns_user_arr,$org_id,1);
			form_json_msg('0','','组织所属帐号信息',array('user_json' => json_encode($user_arr)));//返回信息json格式
		}else{//不是数组
			form_json_msg('1','','获得组织所属帐号信息错误',array('user_json' =>'' ));//返回信息json格式
		}
	}


	/**
	 * @brief 根据用户当前组织$org_id，获得当前组织管理者user_id：
	 */
	public function get_manager_user_id(){
		$org_id=$this->input->post('orgid', true);
		$org_id = empty_to_value($org_id,0);
		$site_id = $this->p_site_id;
		$site_id = empty_to_value($site_id,0);//517
		$this->load->model('uc_org_manager_model');
		$org_manager_user_id = $this-> uc_org_manager_model->get_org_manager_userid($org_id,$site_id);
		form_json_msg('0','','是否是有组织管理者',array('manager_user_id' => $org_manager_user_id));//返回信息json格式
	}

        
	/**
	 * @brief 组织结构页面-ajax保存新的组织结构名称--?需要接口2.5.2：
	 * @details
	 * -# 有效规则验证
	 * -# 返回保存状态 0失败 成功返回新保存的组织结构标识
	 * -# 后置条件,保存成功,需要JS把此组织结构加入js维护的页面组织结构
	 */
	public function saveNewOrg() {
		log_message('info', '1111');
		$org_id = strtolower($this->input->post('id' , true));
		$org_pId = strtolower($this->input->post('pId' , true));
		$org_name = strtolower($this->input->post('name' , true));

		$shos_title = '新加';
		$data = array(
                    'name' => $org_name,
                    'code' => $this->p_customer_code,
		//"siturl" => null,
		// "childOrder" => null,
                    "parentId" => $org_pId,
                    "customercode" => $this->p_customer_code,
		//"type" => null,
		);
                
		if($org_id > 0){//修改名称
			log_message('info', '2222');
			$shos_title = '修改';
			//根据组织id获得组织信息
                        if($org_id==$org_pId){
                            $err_msg = '父级部门不能为现在准备修改的部门';
                            log_message('error', $err_msg);
                            form_json_msg('1','',$shos_title . '失败，'.$err_msg);//返回错误信息json格式
                        }
			$org_arr = $this->OrganizeLib->get_org_by_id($org_id);
			$org_old_name = arr_unbound_value($org_arr,'name',2,'');
			$org_pId =  arr_unbound_value($org_arr,'parentId',2,'');
			$data['id'] = $org_id;
			$ums_arr = $this->API->UMS_Special_API(json_encode($data),10);
			if(api_operate_fail($ums_arr)){//失败
				$err_msg = 'ums api rs/organizations modify org fail.';
				log_message('error', $err_msg);
				form_json_msg('1','',$shos_title . '失败',array('org_id' => 0));//返回错误信息json格式
			}else{
				//13.1.1.部门名称变更消息
				$this->load->library('Informationlib','','Informationlib');
				$info_pre_arr = array(
                            'from_user_id' => $this->p_user_id,//消息发送者用户id
                            'from_site_id' => $this->p_site_id,//消息发送者站点id
                            'to_user_id' => $org_id,//消息接受者id[是组织时为组织id,是用户时，是用户id]
                            'to_site_id' => $this->p_site_id,//消息接受者站点id
                            'is_group' => 1,//是否为讨论组聊天1是[是组织] 0 否[是单个用户]   
                            'msg_type' => 1,//消息类型  1 - 组织变动
                            'msg_id' => 1,//1 - 部门名称变更    2 - 员工部门调动    3 - 职位调整  4 -员工入职 5 - 员工离职   10 - 部门删除  11 - 员工入职确认 12 - 员工离职确认 13 - 员工部门调动确认 14 - 员工入职拒绝消息 15 - 员工离职拒绝消息 16 - 员工部门调动拒绝消息 
				);
				$info_body = array(
                            'operator_id' => $this->p_user_id,//操作发起人用户ID
                            'dept_id' => $org_id,//部门ID
                            'old_dept_name' => $org_old_name ,//原部门名称
                            'new_dept_name' => $org_name,//新部门名称
				//'desc' => '',//消息描述
				);
				$this->Informationlib->send_info($info_pre_arr,$info_body);
				//日志
				$this->load->library('LogLib','','LogLib');
				$log_in_arr = $this->p_sys_arr;
				$re_id = $this->LogLib ->set_log(array('5','3'),$log_in_arr);
				form_json_msg('0','',$shos_title . '成功',array('org_id' => $org_id));//返回信息json格式
			}
		}else{//新加
			log_message('info', '3333');
			$new_org_type = 3 ;
			if($this->p_org_type == 2){
				$new_org_type = 4 ;
			}
			$data['type'] = $new_org_type;// 1:企业 2：生态企业 3：部门 4：生态企业部门 5：分公司
			$ums_arr = $this->API->UMS_Special_API(json_encode($data),9);
			if(api_operate_fail($ums_arr)){//失败
				$err_msg = 'UMS API rs/organizations添加组织失败！';
				log_message('error', $err_msg);
				form_json_msg('1','',$shos_title . '失败',array('org_id' => 0));//返回错误信息json格式
			}else{
				log_message('debug', 'UMS API rs/organizations添加组织成功！');
				$org_id = arr_unbound_value($ums_arr,'org_id',2,0);
				//组织机构交换机创建(包括聊天和状态)
				//$data = 'session_id=' . $this->p_session_id . '&user_id=' . $this->p_user_id . '&org_id=' . $org_id;
				$data = 'site_id=' . $this->p_site_id . '&org_id=' . $org_id;
				$ucc_msg_arr = $this->API->UCCServerAPI($data,18);
				if(!api_operate_fail($ucc_msg_arr)){//成功
					log_message('info', '  UCC API async/orgCreate ' . $data . ' 成功 .');
				}else{//失败
					log_message('info', '  UCC API async/orgCreate ' . $data . ' 失败 .');
				}
				//消息
				$ns_org_arr = $this->OrganizeLib->get_org_by_id($org_id);
				$new_nodeCode = arr_unbound_value($ns_org_arr,'nodeCode',2,'');
				$this->load->library('NoticeLib','','NoticeLib');
				$ok_org_code = $this->OrganizeLib->get_orgnamearr_by_orgcode($new_nodeCode);//得到组织名称数组
				$new_org_code = implode('/', $ok_org_code);
				$format_arr = array(
                            'parent_org_names' => $new_org_code,//$this->p_site_name. '/[一级部门]/[二级部门]/[三级部门]',//[公司名称]/[一级部门]/[二级部门]/[三级部门]
                            'org_name' => $org_name,//[四级部门名称]
				);
				$in_notice_arr = array(
                            'org_id' => $this->p_org_id,//组织ID
                            'site_id' => $this->p_site_id,//站点ID
                            'operate_id' => $this->p_user_id,//添加员工编号
				);
				log_message('info', '4444');
                                //////////////////////////////////////////////////////////////////////////////
				//$this->NoticeLib->set_notice(2,array('3','1'),$format_arr,$in_notice_arr);//循环向其他部门发消息通知  很费时间。。。后续把这一步做成后台任务去跑
                                //////////////////////////////////////////////////////////////////////////////
                                log_message('info', '5555');
				//日志
				$this->load->library('LogLib','','LogLib');
				$log_in_arr = $this->p_sys_arr;
				$re_id = $this->LogLib ->set_log(array('5','1'),$log_in_arr);
				form_json_msg('0','',$shos_title . '成功',array('org_id' => $org_id));//返回信息json格式
			}
		}
	}

	/**
	 * @brief 根据组织id,删除当前组织：
	 * @details
	 *  判断是否有1下级组织，2是否自己有员工3成功删除4删除失败
	 */
	public function delOrg() {
		$this->load->library('OrganizeLib','','OrganizeLib');
		$org_id = strtolower($this->input->post('id' , true));
		$org_id = empty_to_value($org_id,0);
		$is_sure_del = strtolower($this->input->post('is_sure_del' , true));
		$is_sure_del = empty_to_value($is_sure_del,0);  //0去判断可以不可以删除，1满足条件就可以真的删除

		//判断有没有下级组织
		//获得当前下级组织数组
		$org_list_data = $this->OrganizeLib->get_org_array($org_id,'nextlevel','1,3,5');
		if(!isemptyArray($org_list_data)){//如果不是空数组
			form_json_msg('0','', '当前组织还有下级组织',array('state' => 1));//返回信息json格式
		}
		//是否自己有员工
		$site_id = $this->p_site_id;
		$site_id = empty_to_value($site_id,0);//517
		$user_arr = $this-> OrganizeLib->get_users_list($org_id ,$site_id );
		if(!isemptyArray($user_arr)){//如果不是空数组
			form_json_msg('0','', '当前组织还有员工',array('state' => 2));//返回信息json格式
		}

		if($is_sure_del == 1){//0去判断可以不可以删除，1满足条件就可以真的删除
			//根据组织id获得组织信息
			$org_arr = $this-> OrganizeLib->get_org_by_id($org_id);
			$org_name = arr_unbound_value($org_arr,'name',2,'');
			$org_parentId = arr_unbound_value($org_arr,'parentId',2,'');
			//父组织id获得组织信息
			$org_parent_arr = $this-> OrganizeLib->get_org_by_id($org_parentId);
			$org_parent_name = arr_unbound_value($org_parent_arr,'name',2,'');
			//print_r($org_parent_name);
			// die();
			//组织机构交换机删除(包括聊天和状态)
			$data = 'session_id=' . $this->p_session_id . '&user_id=' . $this->p_user_id . '&org_id=' . $org_id;
			$ucc_msg_arr = $this->API->UCCServerAPI($data,19);
			if(!api_operate_fail($ucc_msg_arr)){//成功
				log_message('info', '  uccserver api async/orgCreate ' . $data . ' success .');
			}else{//失败
				log_message('info', '  uccserver api async/orgCreate ' . $data . ' fail .');
			}
			//可以删除，删除组织
			$del_boolean = $this-> OrganizeLib->del_org_by_orgid($org_id);
			if($del_boolean){//删除成功
				if(!bn_is_empty($org_parentId)){//不为空，有上级组织
					//获得上级组织部门管理者
					$this->load->model('uc_org_manager_model');
					$org_manager_user_id = $this-> uc_org_manager_model->get_org_manager_userid($org_parentId,$this->p_site_id);
					if($org_manager_user_id > 0){
						//13.1.1.部门名称变更消息
						$this->load->library('Informationlib','','Informationlib');
						$info_pre_arr = array(
                                'from_user_id' => $this->p_user_id,//消息发送者用户id
                                'from_site_id' => $this->p_site_id,//消息发送者站点id
                                'to_user_id' => $org_manager_user_id,//消息接受者id[是组织时为组织id,是用户时，是用户id]
                                'to_site_id' => $this->p_site_id,//消息接受者站点id
                                'is_group' => 0,//是否为讨论组聊天1是[是组织] 0 否[是单个用户]   
                                'msg_type' => 1,//消息类型  1 - 组织变动
                                'msg_id' => 10,//1 - 部门名称变更    2 - 员工部门调动    3 - 职位调整  4 -员工入职 5 - 员工离职   10 - 部门删除  11 - 员工入职确认 12 - 员工离职确认 13 - 员工部门调动确认 14 - 员工入职拒绝消息 15 - 员工离职拒绝消息 16 - 员工部门调动拒绝消息 
						);

						$info_body = array(
                                'operator_id' => $this->p_user_id,//操作发起人用户ID
                                'dept_id' => $org_id,//删除的部门ID
                                'dept_name' => $org_name ,//删除的部门名称
                                'parent_dept_name' => $org_parent_name,//所在父级部门名称
                                'desc' => '',//消息描述
						);
						log_message('info', 'into class ' . json_encode($info_pre_arr) . json_encode($info_body) . '.');
						$this->Informationlib->send_info($info_pre_arr,$info_body);
					}
				}
				//日志
				$this->load->library('LogLib','','LogLib');
				$log_in_arr = $this->p_sys_arr;
				$re_id = $this->LogLib ->set_log(array('5','2'),$log_in_arr);
				form_json_msg('0','', '删除组织成功',array('state' => 3));//返回信息json格式}
			}else{//删除失败
				form_json_msg('2','', '删除组织失败',array('state' => 4));//返回信息json格式}
			}
		}else{
			form_json_msg('0','', '当前组织可以进行删除',array('state' => 5));//返回信息json格式
		}
	}

// 	/**
// 	 * @brief 组织结构页面-如果部门权限设置有变动，则如果当前部门没有权限记录则新加，有则修改[ajax加载]--?需要接口2.5.4：
// 	 * @details
// 	 * -# 从表单获得当前部门获权限      *
// 	 * -# 对表单数据进行验证
// 	 * -# 如果当前部门没有权限记录则新加，有则修改，先向UC保存，再向BOSS保存
// 	 * @return 保存状态
// 	 */
// 	public function saveOrgPower() {
// 		// $this->form_validation->set_rules('orgId','组织结构编号','required');
// 		$orgId=$this->input->post('orgId', true);
// 		// $data['title'] = $_POST['title'];
// 		//$data['add_time'] = time();
// 		#调用news_model的方法即可
// 		if ($this->UC_Organization_Model->saveOrgPower($data)){
// 			echo "添加成功";
// 		}else{
// 			echo "添加失败";
// 		}
// 		//向BOSS保存
// 		$state = $this->OrganizeLib->saveOrgPower($data,$orgId);
// 	}

	/**
	 * @abstract 弹窗-添加组织结构
	 */
	public function addOrg(){
		$this->load->view('public/popup/addOrg.php');
	}

	public function manInfoPage(){
		$this->load->view('organize/managerInfo.php');
	}

	public function staInfoPowPage(){
		$this->load->view('organize/staffInfoPower.php');
	}
        
        public function add_org_page(){
		$this->load->view('public/popup/addOrgPage.php');
	}

        public function edit_org_page(){
		$this->load->view('public/popup/editOrgPage.php');
	}


	/**
	 * @access public
	 * @abstract 显示弹窗-调入员工页面
	 */
	public function foldStaff() {
		$this->load->view('public/popup/foldStaff.php');
	}

// 	/**
// 	 * @abstract 获得组织权限
// 	 */
// 	public function get_org_power() {
// 		// 获得部门id串
// 		$org_code = $this->input->post('org_code', true);

// 		// 判断部门id串是否为空
// 		if(bn_is_empty($org_code)){
// 			form_json_msg('1', '', 'The parameter of org_code is null');
// 		}

// 		// 载入权限类库
// 		$this->load->library('PowerLib', '', 'PowerLib');

// 		// 将当前用户id、部门id以及站点id组装成数组
// 		$in_arr = array(
// 			'userid' 	=> 0,					// 用户id
// 			'org_code' 	=> $org_code,			// 组织id串  -500-501-502-503
// 			'siteid' 	=> $this->p_site_id		// 站点id
// 		);

// 		// 根据上面组装的数组获得部门权限
// 		$re_array 			= $this->PowerLib->get_powers_arr(2, $in_arr);
// 		$power_class_arr 	= arr_unbound_value($re_array, 'power_class', 1, array()); 	// IM设置、通话设置、电话会议设置、网络会议设置
// 		$power_arr 			= arr_unbound_value($re_array, 'power_arr', 1, array()); 	// 具体的权限数组
// 		$data['power_arr']  = $power_arr;
		
// 		var_dump($data['power_arr']);

// 		$this->load->view('public/part/power.php', $data);
// 	}

// 	/**
// 	 * @access public
// 	 * @abstract 保存组织权限
// 	 */
// 	public function save_org_power() {
// 		// 获得表单提交的组织id串
// 		$org_code = $this->input->post('org_code', true);
// 		// 获得表单提交的组织权限json串
// 		$power_json = $this->input->post('power_json', true);

// 		// 判断组织id串是否为空
// 		if(bn_is_empty($org_code)){
// 			form_json_msg('1', '', 'The parameter of org_code is null');
// 		}

// 		// 判断组织权限json串是否为空
// 		if(bn_is_empty($power_json)){
// 			form_json_msg('1', '', 'The parameter of power_json is null');
// 		}

// 		// 将组织权限json串转化成数组
// 		$power_arr = json_decode($power_json , true );

// 		// 载入权限类库
// 		$this->load->library('PowerLib', '', 'PowerLib');

// 		// 组装参数数组
// 		$param_array = array(
// 			'power_type' 	=> 2, 						// 权限类型 1站点属性,2部门属性,3用户属性,4会议属性 
// 			'customerCode' 	=> $this->p_customer_code,	// 客户编码
// 			'org_id' 		=> $this->p_org_id,			// 站点所在的组织id
// 			'oper_type' 	=> '1,2,3,4,5',				// ums可以获得下级的组织类型，多个用,号分隔types：1:企业 2:生态企业 3:部门 4:生态企业部门 5 分公司 逗号分割的字符串
// 			'obj' 			=> array(
// 								'sys' => $this->p_sys_arr
// 		)
// 		);
// 		$in_arr = array(
//                   'userid' 	 => 0,					// 用户id
//                   'org_code' => $org_code,			// 组织id串  
//                   'siteid' 	 => $this->p_site_id	// 站点id
// 		);
// 		// 保存组织权限
// 		$re_boolean = $this->PowerLib->save_powers($param_array, $power_arr, $in_arr);

// 		if($re_boolean){
// 			form_json_msg('0', '', 'Save organize power successfully');
// 		}else{
// 			form_json_msg('1', '', 'Save organize power failed');
// 		}
// 	}
	
	/**
	 * 获得组织的权限
	 * @author xue.bai_2@quanshi.com 2014-11-19
	 */
	public function get_org_power(){
		$org_code = $this->input->post('org_code', true); // 组织Id串（-11-23-74）
		log_message('info', 'Into method get_org_power input----> $org_code = '.$org_code);
		
		// 载入权限类库
		$this->load->library('RightsLib', '', 'RightsLib');
		
		// 从组织或上级组织获得权限
		$rights_arr = $this->RightsLib->get_right_from_org($org_code);
		
		// 如果从组织中没有获得权限，则从站点获得权限
		if(isemptyArray($rights_arr)){
			$rights_arr = $this->RightsLib->get_right_from_site($this->p_site_id);
		}
		
		// 从权限数组取出属性
		$power_arr = isset($rights_arr['right_arr'])?$rights_arr['right_arr']:array();
		
		// 将权限转换成规定格式的数组
		$power_arr = $this->RightsLib->recombine_right($power_arr);
		
		// 将数据传到页面上
		$data['power_arr'] = $power_arr;
		$this->load->view('public/part/power.php', $data);
	}
	
	/**
	 * 保存组织的权限
	 * @author xue.bai_2@quanshi.com 2014-11-20
	 */
	public function save_org_power(){
		$org_code 	= $this->input->post('org_code', true); 	// 组织Id串（-11-23-74）
		$power_json = $this->input->post('power_json', true); 	// 权限串
		log_message('info', 'Into method save_org_power input----> $org_code = '.$org_code.' $power_json = '.$power_json);
		
		// 判断组织Id串和权限串是否为空
		if(is_empty($org_code)){
			form_json_msg(ORG_CODE_EMPTY, 'org_code', $this->lang->line('org_code_empty'), array()); // 组织串不能为空
		}
		if(is_empty($power_json)){
			form_json_msg(POWER_JSON_EMPTY, 'power_json', $this->lang->line('power_json_empty'), array()); // 权限串不能为空
		}
		
		// 将权限串转化成权限数组
		$power_arr = json_decode($power_json, true);
		
		log_message('info', var_export($power_arr, true));
		
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
		
		// 从组织或上级组织获得旧的权限数组
		$old_right_arr = $this->RightsLib->get_right_from_org($org_code);
		
		// 如果从组织中没有获得旧的权限，则从站点获得旧的权限数组
		if(isemptyArray($old_right_arr)){
			$old_right_arr = $this->RightsLib->get_right_from_site($this->p_site_id);
		}
		
		// 从旧的权限数组取出属性和获取位置
		$old_power_arr 	= isset($old_right_arr['right_arr'])?$old_right_arr['right_arr']:array();
		$from_where 	= isset($old_right_arr['from_where'])?$old_right_arr['from_where']:POWER_FROM_ORG;
		log_message('info', 'from'.$from_where);
		$from_where_data= isset($old_right_arr['from_where_data'])?$old_right_arr['from_where_data']:$org_code;
		
		// 对比新旧权限数组，判断权限是否发生变化
		$res_arr 			= $this->RightsLib->compare_rights($old_power_arr, $new_power_arr);
		$is_change 			= isset($res_arr['is_change']) ? $res_arr['is_change'] : POWER_NOT_CHANGE; 	// 权限是否发生变化：0、没有；1、有
		$is_confSet_change  = isset($res_arr['is_confSet_change']) ? $res_arr['is_confSet_change'] : CONF_POWER_NOT_CHANGE; // 会议权限是否发生变化：0、没有；1、有
		$new_power_arr 		= isset($res_arr['new_right']) ? $res_arr['new_right'] : array(); // 新的权限数组
		
		// 载入相关模型
		$this->load->model('uc_organization_model');
		$this->load->model('account_upload_task_model');
		
		// 组织的个性化权限发生变化：1、更新个性化权限；2、如果有电话会议变化，则向BOSS做变更
		if($from_where == POWER_FROM_ORG && $is_change == POWER_IS_CHANGE){ 
			
			// 更新BOSS模板
			$boss_totle_template = $this->boss->getSellingProductTemplates($this->p_contract_id, $from_where_data);
			$new_template_arr = $this->RightsLib->combine_boss_template_data($boss_totle_template, $new_power_arr);
			$res = $this->RightsLib->update_boss_template($from_where_data, $new_template_arr);
			if($res == false){
				form_json_msg(UPDATE_SITE_POWER_FAIL, '', $this->lang->line('update_power_fail'), array()); // 更新权限失败
			}
			
			// 更新个性化权限
			$where_arr = array(
				'siteID' => $this->p_site_id,
				'orgID'  => get_org_id($org_code)
			);
			$update_arr = array(
				'value' => json_encode($new_power_arr)
			);
			$res = $this->uc_organization_model->update_value($where_arr, $update_arr);
			if($res == false){
				form_json_msg(UPDATE_POWER_FAIL, '', $this->lang->line('update_power_fail'), array()); // 更新权限失败
			}
			
			// 如果网络会议权限发生变化，则保存线程
			if($is_confSet_change == CONF_POWER_IS_CHANGE){
				$update_value = array(
						'customer_code' => $this->p_customer_code,
						'site_id' 		=> $this->p_site_id,
						'org_id' 		=> get_org_id($org_code)
				);
				$this->account_upload_task_model->saveTask(ORG_POWER_CHANGE_UPLOAD, json_encode($update_value)); // TODO 问题：如果变更权限失败怎么办？
			}
		}
		
		// 从上级组织或站点获得的组织权限发生变化：1、保存个性化权限；2、向BOSS做变更
		if(($from_where == POWER_FROM_PARENT_ORG || $from_where == POWER_FROM_SITE) && $is_change == POWER_IS_CHANGE){ 
			// 创建模板
			$boss_totle_template = $this->boss->getSellingProductTemplates($this->p_contract_id, $from_where_data);
			$new_template_arr = $this->RightsLib->combine_boss_template_data($boss_totle_template, $new_power_arr);
			$has_template = $this->boss->getSellingProductTemplates($this->p_contract_id, $org_code); // 是否有权限
			if($has_template == false){
				$res = $this->RightsLib->create_boss_template($org_code, $new_template_arr);
				if($res == false){
					form_json_msg(UPDATE_POWER_FAIL, '', $this->lang->line('update_power_fail'), array()); // 更新权限失败
				}
			}else{
				$res = $this->RightsLib->update_boss_template($org_code, $new_template_arr);
				if($res == false){
					form_json_msg(UPDATE_SITE_POWER_FAIL, '', $this->lang->line('update_power_fail'), array()); // 更新权限失败
				}
			}
			
			// 保存个性化权限
			$insert_arr = array(
				'siteID' 		=> $this->p_site_id,
				'orgID'  		=> get_org_id($org_code),
				'value' 		=> json_encode($new_power_arr),
				'createTime' 	=> time()
			);
			$res = $this->uc_organization_model->save_value($insert_arr);
			if($res == false){
				form_json_msg(UPDATE_POWER_FAIL, '', $this->lang->line('update_power_fail'), array()); // 更新权限失败
			}
			
			// 保存线程
			$update_value = array(
				'customer_code' => $this->p_customer_code,
				'site_id' 		=> $this->p_site_id,
				'org_id' 		=> get_org_id($org_code)
			);
			$this->account_upload_task_model->saveTask(ORG_POWER_CHANGE_UPLOAD, json_encode($update_value));
		}
		
		form_json_msg(COMMON_SUCCESS, '', $this->lang->line('success'), array()); // 返回保存成功信息
	}
	
}
