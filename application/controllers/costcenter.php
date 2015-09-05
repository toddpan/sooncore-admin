<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @class CostCenter
 * @brief CostCenter Controller，主要负责对成本中心的列表显示、新加、删除、员工[移动、移除]管理等操作。
 * @details
 * @file CostCenter.php
 * @author zouyan <yan.zou@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
class CostCenter extends Admin_Controller {
    /**
     * @brief 构造函数：
     *
     */
    public function __construct() {
        parent::__construct();
        $this->load->helper('my_publicfun');
		$this->lang->load('costcenter', 'chinese');
		$this->lang->load('common', 'chinese');
		$this->load->helper('language');
		
        $this->load->helper('my_dgmdate');
        //调用分配域的接口
        $this->load->library('API','','API');
    }
    /**
     *
     * @brief 根据post过来的组织id，获得当前组织的帐号列表信息[部门管理者在前，其它人员在后]：
     * @details
     * @return null 
     *
     */
    public function get_users_list(){

        $org_id=$this->input->post('org_id', TRUE);
        $org_id = empty_to_value($org_id,518);//517  
        $site_id = $this->p_site_id;
        $site_id = empty_to_value($site_id,0);//517 
        $this->load->library('OrganizeLib','','OrganizeLib'); 
        $user_arr = $this-> OrganizeLib->get_users_list($org_id ,$site_id );
        $data[user_arr] = $user_arr;

        $this->load->view('public/part/usercostcenterlist.php',$data);
    }
    
    /**
     *
     * @brief 成本中心调入员工页面：
     * @details
     * @return null
     *
     */
    public function addstaff() {     
        $this->load->view('public/popup/costcenteraddstaff.php');
    }
    /**
     *
     * @brief 成本中心删除页面：
     * @details
     * @return null
     *
     */
    public function save_del_costcenter() {    
        $cost_id = $this->input->post('cost_id' , TRUE);
        if(bn_is_empty($cost_id)){//没有数据
            form_json_msg('1','', '参数有误');//返回信息json格式
        }
        $this->load->library('StaffLib','','StaffLib');
        $in_array = array(
            'cost_id' => $cost_id,
            'org_id' =>$this->p_org_id,
            'site_id' => $this->p_site_id             
        );
        //根据成本中心id删除帐号及成本中心数据
        $re_boolean = $this->StaffLib->del_cost($in_array);
        if($re_boolean){//成功
            //日志
            $this->load->library('LogLib','','LogLib');
            $log_in_arr = $this->p_sys_arr;
//            array(
//                  'Org_id' => $this->p_org_id ,//组织ID
//                  'site_id' => $this->p_site_id ,//站点ID
//                  'operate_id' => $this->p_user_id,//操作会员ID
//                  'login_name' => $this->p_account ,//操作账号[可以为空，没有，则重新获取]
//                  'display_name' => $this->p_display_name,//操作姓名[可以为空，没有，则重新获取]
//                  'client_ip' => $this->p_client_ip ,//客户端ip
//              );
           $re_id = $this->LogLib ->set_log(array('5','10'),$log_in_arr); 
            form_json_msg('0','', '删除成本中心成功');//返回信息json格式
        }else{
           form_json_msg('1','', '删除成本中心失败');//返回信息json格式
         }
    }
    /**
     *
     * @brief 成本中心移动员工到新的成本中心页面：
     * @details
     * @return null
     *
     */
    public function move_staff() {     
        $this->load->view('public/popup/costcentermovestaff.php');
    }
    /**
     *
     * @brief 成本中心删除员工页面：
     * @details
     * @return null
     *
     */
    public function del_staff() {     
        $this->load->view('public/popup/costcenterdelstaff.php');
    }
    /**
     *
     * @brief 保存删除成本中心：
     * @details
     * @return null
     *
     */
    public function del_costcenter() {     
        $this->load->view('public/popup/costcenterdel.php');
    }
    /**
     *
     * @brief 成本中心列表页面：
     * @details
     * -# 获得成本中心信息，分配到页面左则成本中心列表
     * -# 获得组织结构，分配到成本中心，右上角[全部组织]列表，用来筛选对应成本中心员工
     * -# 获得当前成本中心员工列表，分配到员工列表
     * @return null
     *
     */
    public function listCostCenterPage() {
        $this->load->model('uc_site_costcenter_model');
        //获得成本中心信息
        $where_arr = array(
            'org_id' => $this->p_org_id,
            'site_id' => $this->p_site_id,
        );
        $cost_center_arr = $this->uc_site_costcenter_model->get_costcenter_list($where_arr);
        $data["cost_center_arr"] = $cost_center_arr;        
        $this->load->view('public/part/costcenter.php',$data);
    }
    /**
     *
     * @brief 获得成本中心用户列表页面：//TODO 
     * @details
     * @return null
     *
     */
    public function get_cost_user_list() {
        $cost_id = $this->input->post('cost_id' , TRUE);
        $org_id = $this->input->post('org_id' , TRUE);
        $user_arr = array();
        if(bn_is_empty($cost_id) || bn_is_empty($org_id)){//有数据
            //TODO:参数有误
        }
        
        $this->load->library('StaffLib','','StaffLib');
		$in_array = array(
            'cost_id' => $cost_id, //当前的成本中心分组过滤条件，0表示"未指定成本中心"分组
            'org_id' =>$org_id, //当前的组织过滤条件，0表示列出成本中心分组下的所有成员
            'site_id' => $this->p_site_id,//站点id
			'p_org_id'=> $this->p_org_id,//最顶层组织的id
        );
        $user_arr = $this->StaffLib->get_cost_users($in_array);
        $data[user_arr] = $user_arr;
        $this->load->view('public/part/usercostcenterlist.php',$data);
    }
    /**
     *
     * @brief 获得成本中心json串：
     * @details
     * @return null
     *
     */
    public function get_cost() {
        $this->load->model('uc_site_costcenter_model');
        $where_arr = array(
            'org_id' => $this->p_org_id,
            'site_id' => $this->p_site_id,
        );
        $cost_center_arr = $this->uc_site_costcenter_model->get_costcenter_list($where_arr);
        if (! is_array($cost_center_arr) ){
            $cost_center_arr = array();
        }
        echo json_encode($cost_center_arr);
    }
    /**
     *
     * @brief 修改或新加成本中心：
     * @details
     * @return null
     *
     */
    public function add_modify_cost() {
        $this->load->model('uc_site_costcenter_model');
        $cost_title = $this->input->post('cost_title' , TRUE);
        $cost_id = $this->input->post('cost_id' , TRUE);
        if(bn_is_empty($cost_title) || bn_is_empty($cost_id)){//没有数据
            form_json_msg('1','', '参数有误');//返回信息json格式
        }
        //获得成本中心信息
        $where_arr = array(
            'cost_pid' => 0,//父成本中心id
            'org_id' => $this->p_org_id,
            'site_id' => $this->p_site_id,
            //'id' => $cost_id,//成本中心id
        );
        $is_modify = 0;//是否修改，0新加1更新
        if($cost_id > 0){
            $is_modify = 1;//是否修改，0新加1更新
        }
        //成功 1或新加记录id 失败0 
        $re_num = $this->uc_site_costcenter_model->save_costcenter_name($where_arr,$cost_title,$cost_id);
        if ($re_num == 0){//不是数组
                if($is_modify == 0){
                    //日志
                    $this->load->library('LogLib','','LogLib');
                    $log_in_arr = $this->p_sys_arr;
//                    array(
//                          'Org_id' => $this->p_org_id ,//组织ID
//                          'site_id' => $this->p_site_id ,//站点ID
//                          'operate_id' => $this->p_user_id,//操作会员ID
//                          'login_name' => $this->p_account ,//操作账号[可以为空，没有，则重新获取]
//                          'display_name' => $this->p_display_name,//操作姓名[可以为空，没有，则重新获取]
//                          'client_ip' => $this->p_client_ip ,//客户端ip
//                      );
                   $re_id = $this->LogLib ->set_log(array('5','9'),$log_in_arr); 
                }
            form_json_msg('1','', '操作失败');//返回信息json格式
        }else{
            form_json_msg('0','','操作成功',array('new_cost_id' => $re_num));//返回错误信息json格式
        }

    }
    /**
     *
     * @brief 批量调入成本中心员工：
     * @details
     * @return null
     *
     */
    public function add_cost_user() {
        $users_arr = json_decode($this->input->post('users' , TRUE),true);
        $cost_id = $this->input->post('cost_id' , TRUE);
		//print_r($users_arr);die;
		if(is_null($users_arr) || bn_is_empty($cost_id)){//有数据
            form_json_msg('1','', '参数有误');//返回信息json格式
        }
		$user_ids_arr = array();
		foreach($users_arr as $user){
			$user_ids_arr[] = $user['userid']; 
		}
		//print_r($user_ids_arr);die;
		
        $this->load->model('uc_costcenter_user_model');
        $re_boolean = $this->uc_costcenter_user_model->save_cost_users($user_ids_arr,$cost_id);
        if($re_boolean){
            form_json_msg('0','', '保存成功');//返回信息json格式
        }else{
            form_json_msg('1','', '保存失败');//返回信息json格式
        }

    }
    /**
     *
     * @brief 批量从一个成本中心员工调入另一个成本中心：
     * @details
     * @return null
     *
     */
    public function move_user_new_cost() {  
        $old_cost_id = $this->input->post('old_cost_id' , TRUE);
        $user_ids = $this->input->post('user_id' , TRUE);
        $cost_id = $this->input->post('cost_id' , TRUE);
        if(bn_is_empty($old_cost_id)  || bn_is_empty($user_ids)  || bn_is_empty($cost_id)){//有数据
            form_json_msg('1','', '参数有误');//返回信息json格式
        }
        $this->load->model('uc_costcenter_user_model');
        $re_boolean = $this->uc_costcenter_user_model->save_cost_users_from_old_cost($old_cost_id,$user_ids,$cost_id);
        if($re_boolean){
            form_json_msg('0','', '调入成功');//返回信息json格式
        }else{
            form_json_msg('1','', '调入失败');//返回信息json格式
        }
    }
    /**
     *
     * @brief 移除成本中心员工：
     * @details
     * @return null
     *
     */
    public function move_cost_user() {

        $this->load->model('uc_costcenter_user_model');
        $user_ids = $this->input->post('user_id' , TRUE);//用值，则删除指定用户，多个用逗号分隔；
        $cost_id = $this->input->post('cost_id' , TRUE);
        
        if(bn_is_empty($user_ids) || bn_is_empty($cost_id)){//有数据
            form_json_msg('1','', '参数有误');//返回信息json格式
        }
        $this->load->model('uc_costcenter_user_model');
        $re_boolean = $this->uc_costcenter_user_model->del_cost_users($user_ids,$cost_id);
        if($re_boolean){
            form_json_msg('0','', '移除成功');//返回信息json格式
        }else{
            form_json_msg('1','', '移除失败');//返回信息json格式
        }
    }
    /**
     *
     * @brief 移除成本中心员工：
     * @details
     * @return null
     *
     */
    public function move_cost() {
        $this->load->model('uc_costcenter_user_model');
        $cost_Id = $this->input->post('cost_Id' , TRUE);
        if(bn_is_empty($cost_Id)){//有数据
            form_json_msg('1','', '参数有误');//返回信息json格式
        }
        $this->load->model('uc_costcenter_user_model');
        $re_boolean = $this->uc_costcenter_user_model->del_cost_users('',$cost_Id);
        if($re_boolean){
            form_json_msg('0','', '移除成功');//返回信息json格式
        }else{
            form_json_msg('1','', '移除失败');//返回信息json格式
        }
    }
    /**
     *
     * @brief 添加成本中心页面[ajax添加]：
     * @details
     * -# 获得要添加的成本中心名称 $newConstName
     * -# 判断名称是否已经存在
     * -# 返回添加状态
     * @return int 成功返回当前成本中心标识，失败返回false,名称已存在返回-1
     *
     */
    public function add_costcenter() {
        
        $this->UC_Site_Costcenter_Model->addCostcenter($data);
    }

    /**
     *
     * @brief 修改成本中心页面[ajax添加]：
     * @details
     * -# 获得要修改的成本中心名称 $newConstName
     * -# 判断名称是否已经存在
     * -# 返回添加状态
     * @return int 失败返回false,名称已存在返回-1
     *
     */
    public function modifyCostCenter() {
        $this->UC_Site_Costcenter_Model->modifyCostcenter($data);
    }
    /**
     *
     * @brief 删除成本中心页面[ajax删除]：
     * @details
     * -# 获得要添加的成本中心标识$delConstCenterId
     * -# 删除成本中心
     * -# 删除成本中心对应的员工
     * -# 返回添加状态
     * @return boolean true 删除成功，false删除失败
     *
     */
    public function delCostCenter() {
        $this->UC_Site_Costcenter_Model->delCostcenter($data);
    }

    /**
     *
     * @brief 成本中心列表页面[ajax获取]：
     * @details
     * -# 获得当前页号$currentpage
     * -# 获得当前成本中心标识 $ConstCenterId
     * -# 获得当前成本中心标识，从UC的当前成本中心当前页员工标识[筛选]
     * -# 如果是选择[未指定成本中心]员工，则先从UC获得所有已指定成本中心员工标识，通过接口获得员工
     * -# 获得的员工标识从UMS获得对应员工信息
     * -# 员工信息分配到视图显示
     * -# 获得列表分页信息，分配到视图
     * @return null
     *
     */
    public function costCenterlist($pageno,$ConstId) {
        //$this->load->view('aaa.php');
        
        
        /*
        #配置分页信息
        
        $config['base_url'] = site_url('admin/goodstype/index/');
        $config['total_rows'] = $this->UC_Costcenter_User_Model->countCostcenterStaff($site_id,$org_id,$cost_Id);
        $config['per_page'] = 2;
        $config['uri_segment'] = 4;

        #自定义分页链接
        $config['first_link'] = '首页';
        $config['last_link'] = '尾页';
        $config['prev_link'] = '上一页';
        $config['next_link'] = '下一页';

        #初始化分页类
        $this->pagination->initialize($config);

        #生成分页信息
        // $data['pageinfo'] = $this->pagination->create_links();
        //获得员工信息
        $this->StaffLib->getStaffByIds($staffIds);
        //获得所有有成本中心的员工标识
        //
        $this->UC_Costcenter_User_Model->getStaffIdsByCostId($site_id,$org_id,$cost_Id);
        //获得[未指定成本中心]员工信息
        $this->StaffLib->getCurrentPageStaffByIds($pageNo,$pageNum,$orgId,$staffIds);
        //$limit = $config['per_page'];

        $data['goodstypes'] = $this->UC_Costcenter_User_Model->getCostcenterStaff($site_id,$org_id,$cost_Id,$limit,$offset);

        $this->load->view('goods_type_list.html',$data);
         * 
         */
        
    }
    /**
     *
     * @brief 移动选中员工到新的成本中心[ajax执行]：
     * @details
     * -# 获得需要移动的员工标识
     * -# 获得当前成本中心标识 $NowConstCenterId
     * -# 获得移动到新的中心标识 $NewConstCenterId
     * -# 执行更新操作
     * -# 返回执行结果
     * @return boolean true 成功，false失败
     *
     */
    public function moveStaffToNewCostCenter() {
        $this->UC_Site_Costcenter_Model->batchMoveStaff($site_id,$org_id,$staff_IdS,$Cost_id);
    }
    /**
     *
     * @brief 移除选中员工到新的成本中心[ajax执行]：
     * @details
     * -# 获得需要移动的员工标识
     * -# 获得当前成本中心标识 $NowConstCenterId
     * -# 执行移除操作
     * -# 返回执行结果
     * @return boolean true 成功，false失败
     *
     */
    public function removeStaff() {
        $this->UC_Site_Costcenter_Model->batchDelStaff($site_id,$org_id,$staff_IdS,$Cost_id);
    }

    /**
     * @brief 弹窗-提醒添加成本中心员工
     * @details
     * -# 
     */
    public function addCostCenterStaff(){
        $this->load->view('public/popup/addCostCenterStaff.php');
    }
	
	//=============================重写===========================
	
	/**
	 * 获取下级分组列表
	 * -group id 为0时表示获取分组树的根节点
	 */
	public function nextGroups(){
		//获取分组id
		$group_id = intval($this->input->get_post('id',true));
		
		//检查参数
		if(!is_numeric($group_id) or $group_id<0){
			echo response_json(10000, lang('error_param'));return;
		}
		
		//获取当前以及下级分组信息
		$this->load->model('uc_site_costcenter_model','cc');
		$next_groups = $this->cc->getNextLevelGroup($this->p_org_id, $this->p_site_id, $group_id);
		if(empty($next_groups)){
			echo response_json(0,lang('success'));return;
		}
		
		//格式化
		$next_group_format = array();
		foreach($next_groups as $next_group){
			$tmp = array();
			$tmp['id']   = $next_group['id'];
			$tmp['pId']  = $next_group['pid'];
			$tmp['name'] = $next_group['title'];
			$tmp['nocheck'] = true;
			$tmp['open']    = false;
			$tmp['isParent'] = $this->cc->hasNextLevel($this->p_org_id, $this->p_site_id, $next_group['id']);
			$tmp['isaddnext'] = true;
			$tmp['isdel']     = true;
			$next_group_format[] = $tmp;
		}
		//返回
		echo response_json(0, 'success', $next_group_format);return;
	}
	
	/**
	 * 修改分组名称
	 */
	public function modifyGroup(){
		//获取参数
		$new_title = trim($this->input->get_post('name', true));
		$group_id  = intval($this->input->get_post('id', true));
		
		//检查分组是否存在
		$this->load->model('uc_site_costcenter_model','cc');
		if(!$this->cc->getGroupById($group_id)){
			echo response_json(10000, lang('error_param'));return;
		}
		
		//检查是否重名
		if(!$this->cc->isUniqueName($group_id,$new_title,0)){
			echo response_json(20000,lang('error_group_already_exist'));return;
		}
	
		//boss同步group标签
		$this->load->library('BossLib', '', 'boss');
		$r_boss = $this->boss->modifyTag($group_id, $new_title);
		if(!$r_boss){
			echo response_json(30000,lang('error_boss_sync'));return;
		}
		
		//本地修改
		$r_local = $this->cc->changeGroupName($group_id, $new_title);
		if(!r_local){
			echo response_json(40000,lang('error_change_group_name_local'));return;
		}
		//返回成功
		echo response_json(0, 'success');return;
	}
	
	public function delGroup(){
		//获取参数
		$group_id = intval($this->input->get_post('id', true));
		
		//检查参数
		$this->load->model('uc_site_costcenter_model','cc');
		if(!is_numeric($group_id) || $group_id <= 0 || !$this->cc->getGroupById($group_id)){
			echo response_json(10000,lang('error_param'));return;
		}
		
		//检查分组下是否有员工或分组
		if($this->cc->getGroupMemberIds($group_id, 1)){
			echo response_json(20000, lang('error_have_memebers'));return;
		}
		if($this->cc->getNextLevelGroup($this->p_org_id, $this->p_site_id, $group_id)){
			echo response_json(30000, lang('error_have_groups'));return;
		}

		//从boss删除
		$this->load->library('BossLib', '', 'boss');
		$r_boss = $this->boss->delTag($group_id);
		if(!$r_boss){
			response_json(40000,lang('error_boss_sync'));return;
		}
		
		//本地删除
		if(!$this->cc->delGroup($group_id)){
			echo response_json(50000,lang('error_delete_group_local'));return;
		}
		
		//返回
		echo response_json(0, 'success');return;
	}
	
	public function addGroup(){
		//获取参数
		$group_name = trim($this->input->get_post('name', true));
		$p_group_id = intval($this->input->get_post('id', true));
		
		//检查参数
		if(is_empty($group_name) || $p_group_id < 0){//p_group_id==0时，表示添加根分组
			echo response_json(10000, lang('error_param'));return;
		}
		
		//检查父级分组是否存在
		$this->load->model('uc_site_costcenter_model','cc');
		if($p_group_id !==0 && !$this->cc->getGroupById($p_group_id)){
			echo response_json(20000, lang('error_invalid_group_id'));return;
		}
	
		//检查是否重名
		if(!$this->cc->isUniqueName($p_group_id,$new_title,1)){
			echo response_json(30000,lang('error_group_already_exist'));return;
		}
		
		//boss标签添加，同步
		$this->load->library('BossLib', '', 'boss');
		$tag_id = $this->boss->createTag($this->p_customer_code, $group_name, $p_group_id);
		if(!$tag_id){
			echo response_json(40000,lang('error_boss_sync'));return;
		}
		
		//本地添加
		$this->cc->addGroup($this->p_org_id, $this->p_site_id, $tag_id, $group_name, $p_group_id);
		
		//返回
		echo response_json(0,lang('success'),array('id'=>$tag_id));return;
	}
	
	
	/**
	 * 获取分组的成员
	 * -org_id  为0表示获取全部员工
	 */
	public function members(){
		//获取参数
		$group_id = intval($this->input->get_post('id', true));
		$limit    = ($count = intval($this->input->get_post('count', true))) > 0 ? $count : 15;
		$offset   = ($page = intval($this->input->get_post('page', true))) > 0 ? ($page-1)*$limit : 0;
		$org_id   = intval($this->input->get_post('org_id', true));
		
		//检查参数
		if($group_id<=0 || $org_id<0){
			return $this->load->view('public/part/usercostcenterlist.php',array('members'=>array()));
		}
		
		//获取分组下的成员
		$this->load->model('uc_site_costcenter_model','cc');
		$this->load->library('UmsLib', '', 'ums');
		$m_ids  = $this->cc->getGroupMemberIds($group_id, $limit, $offset, $org_id);
		$m_info = array();
		if(count($m_ids)>0){
			$m_info = $this->ums->getUserByIds($m_ids);//从ums获取用户信息
		}
		
		//整理数据
		$data = array();
		foreach($m_info as $u){
			$tmp = array();
			$tmp['id']   = $u['id'];
			$tmp['name'] = $u['displayName'];
			$tmp['login_name'] = $u['loginName'];
			$tmp['phone'] = $u['mobileNumber'];
			$tmp['last_login'] = date('Y-m-d H:i:s',round($u['lastlogintime']/1000));
			$tmp['is_open'] = $this->_isUserOpened($u['id']);
			$data[] = $tmp;
		}
		
		//返回
		$this->load->view('public/part/usercostcenterlist.php',array('members'=>$data));
	}
	
	/**
	 * 判断用户账号是否开通
	 * @param int $user_id
	 * @return boolean 
	 */
	private function _isUserOpened($user_id){
		$this->load->library('UmsLib', '', 'ums');
		$u = $this->ums->getUserProduct($user_id, UC_PRODUCT_ID);
		$status = isset($u['userProductDTO']['userStatus']) ? $u['userProductDTO']['userStatus'] : 0;
		if( $status == UC_PRODUCT_OPEN_STATUS){
			return true;
		}
		return false;
	}
	
	/**
	 * 添加员工
	 */
	public function addMembers(){
		//获取参数
		$group_id = intval($this->input->get_post('id', true));
		$user_ids = $this->input->get_post('user_ids', true);
		//检查参数
		if(!max(0,$group_id) || !is_array($user_ids) || min($user_ids = array_map('intval', $user_ids))<=0){
			echo response_json(10000,lang('error_param'));return;
		}
		
		//检查分组
		$this->load->model('uc_site_costcenter_model','cc');
		if(!$this->cc->getGroupById($group_id)){
			echo response_json(20000, lang('error_invalid_group_id'));return;
		}
		
		//检查用户是否为当前企业员工
		list($yep, $msg) = $this->_isOurUser($user_ids);
		if(!$yep){
			echo response_json(30000,$msg);return;
		}
		
		//过滤掉已经属于本分组的用户
		foreach($user_ids as $u_k=>$user_id){
			$belong_to_group_ids = $this->cc->getGroupIdsByUserId($user_id);
			if(in_array($group_id, $belong_to_group_ids)){
				unset($user_ids[$u_k]);
			}
		}
		
		//同步到boss,保存到本地
		$this->load->library('BossLib', '', 'boss');
		foreach($user_ids as $user_id){
			$rtn = $this->boss->createUserTag($user_id, $group_id);
			if(!$rtn){
				echo response_json(40000, lang('error_boss_sync'));return;
			}
			$this->cc->addGroupMember($group_id, $user_id);
		}
		
		//返回
		return_json(0, lang('success'));
	}
	
	/**
	 * 判断用户是否属于本站点
	 * @param mix $user_ids 
	 */
	private function _isOurUser($user_ids){
		if(!is_array($user_ids)){
			$user_ids = array($user_ids);
		}
		$this->load->library('UmsLib', '', 'ums');
		foreach($user_ids as $user_id){
			$u_org = $this->ums->getOrganizationByUserId($user_id);
			if(!isset($u_org['customercode']) or $u_org['customercode'] != $this->p_customer_code){
				return array(false, sprintf(lang('error_user_not_belong_customer'), $user_id));
			}
		}
		return array(true,'success');
	}
	
	/**
	 * 从分组删除员工
	 */
	public function delMembers(){
		//获取参数
		$group_id = intval($this->input->get_post('id', true));
		$user_ids = $this->input->get_post('user_ids', true);
		
		//检查参数
		if(!max(0,$group_id) || !is_array($user_ids) || min($user_ids = array_map('intval', $user_ids))<=0){
			echo response_json(10000,lang('error_param'));return;
		}
		
		//检查分组是否合法
		$this->load->model('uc_site_costcenter_model','cc');
		if(!$this->cc->getGroupById($group_id)){
			echo response_json(20000,lang('error_invalid_group_id'));return;
		}
		
		//检查用户是否属于当前客户
		list($yep, $msg) = $this->_isOurUser($user_ids);
		if(!$yep){
			echo response_json(30000,$msg);return;
		}
		
		//检查用户是否属于原分组
		foreach($user_ids as $u_k=>$user_id){
			$belong_to_group_ids = $this->cc->getGroupIdsByUserId($user_id);
			if(!in_array($group_id, $belong_to_group_ids)){
				echo response_json(40000, sprintf(lang('error_user_not_belong_to_group'), $user_id));return;
			}
		}

		//同步boss,本地从分组里删除员工
		$this->load->library('BossLib', '', 'boss');
		foreach($user_ids as $user_id){
			$del_success = $this->boss->delUserTag($user_id, $group_id);
			if(!$del_success){
				echo response_json(50000, lang('error_boss_sync'));return;
			}
			$this->cc->delGroupMember($group_id,$user_id);
		}
		
		//返回
		return_json(0, lang('success'));
	}
	
	/**
	 * 分组间移动员工
	 * -如果是从'未指定成本中心分组'移动，则from_group_id为0
	 */
	public function moveMembers(){
		//获取参数
		$from_group_id = intval($this->input->get_post('from_id', true));
		$to_group_id   = intval($this->input->get_post('to_id', true));
		$user_ids      = $this->input->get_post('user_ids', true);
		
		//检查参数
		if($from_group_id < 0 || $to_group_id <= 0 || !is_array($user_ids) || min($user_ids = array_map('intval', $user_ids)) <= 0){
			echo response_json(10000,lang('error_param'));return;
		}
		
		//检查分组是否合法
		$this->load->model('uc_site_costcenter_model','cc');
		if( ($from_group_id != 0 && !$this->cc->getGroupById($from_group_id)) || !$this->cc->getGroupById($to_group_id) ){
			echo response_json(20000,lang('error_invalid_group_id'));return;
		}
		
		//检查用户是否属于当前客户
		list($yep, $msg) = $this->_isOurUser($user_ids);
		if(!$yep){
			echo response_json(30000,$msg);return;
		}
		
		//检查用户是否属于原分组
		if($from_group_id != 0){
			foreach($user_ids as $u_k=>$user_id){
				$belong_to_group_ids = $this->cc->getGroupIdsByUserId($user_id);
				if(!in_array($from_group_id, $belong_to_group_ids)){
					echo response_json(40000, sprintf(lang('error_user_not_belong_to_group'), $user_id));return;
				}
			}
		}
		//过滤掉已经属于目标分组的用户
		foreach($user_ids as $u_k=>$user_id){
			$belong_to_group_ids = $this->cc->getGroupIdsByUserId($user_id);
			if(in_array($to_group_id, $belong_to_group_ids)){
				unset($user_ids[$u_k]);
			}
		}
		
		//boss同步，本地移动
		foreach($user_ids as $user_id){
			list($is_ok, $msg) = $this->_moveUserFromBoss($user_id, $from_group_id, $to_group_id);
			if(!$is_ok){
				echo response_json(50000, $msg);return;
			}
			list($is_ok_too, $msg) = $this->_moveUserFromLocal($user_id, $from_group_id, $to_group_id);
			if(!$is_ok_too){
				echo response_json(60000, $msg);return;
			}
		}
		//返回
		echo response_json(0, lang('success'));return;
	}
	
	private function _moveUserFromBoss($user_id, $from_group_id, $to_group_id){
		$this->load->library('BossLib', '', 'boss');
		//boss里删除用户与原分组的关联关系
		if($from_group_id != 0){
			$is_success = $this->boss->delUserTag($user_id, $from_group_id);
			if(!$is_success) return array(false, lang('error_boss_sync'));
		}
		//boss里添加用户与新分组的关联关系
		$is_success_too = $this->boss->createUserTag($user_id, $to_group_id);
		if(!$is_success_too) return array(false, lang('error_boss_sync'));
		
		return array(true, 'ok');
	}
	
	private function _moveUserFromLocal($user_id, $from_group_id, $to_group_id){
		$this->load->model('uc_site_costcenter_model','cc');
		//本地删除用户与原分组的关联关系
		if($from_group_id != 0){
			$aff_rows = $this->cc->delGroupMember($from_group_id, $user_id);
			if($aff_rows<=0) return array(false, lang('error_local_db'));
		}
		//本地添加用户与新分组的关联关系
		$another_aff_rows = $this->cc->addGroupMember($to_group_id, $user_id);
		if($another_aff_rows<=0) return array(false, lang('error_local_db'));
		
		return array(true, 'ok');
	}
	
	/**
	 * 获取未指定成本中心分组的成员
	 */
	public function unGroupedMembers(){
		//获取参数
		$limit    = ($count = intval($this->input->get_post('count', true))) > 0 ? $count : 15;
		$offset   = ($page = intval($this->input->get_post('page', true))) > 0 ? ($page-1)*$limit : 0;
		$org_id   = intval($this->input->get_post('org_id', true));
		
		//从本地数据库获取已经指定成本中心分组的所有用户
		$data = array();
		$this->load->model('uc_site_costcenter_model','cc');
		$exclude_users = $this->cc->getAllGroupedUsers($this->p_site_id, $this->p_org_id);
		
		//从ums获取用户信息
		$this->load->library('UmsLib', '', 'ums');
		$rs = $this->ums->getUnGroupedCostMemebers($this->p_customer_code, $org_id,$exclude_users, $limit, $offset);
		if(!$rs){
			return $this->load->view('public/part/usercostcenterlist.php',array('members'=>$data));
		}
		
		//整理数据
		$user_info = isset($rs['result']) ? $rs['result'] : array();
		$page_info = isset($rs['page']) ? $rs['page'] : array();
		foreach($user_info as $u){
			$tmp = array();
			$tmp['id']   = $u['id'];
			$tmp['name'] = $u['displayName'];
			$tmp['login_name'] = $u['loginName'];
			$tmp['phone'] = $u['mobileNumber'];
			$tmp['last_login'] = date('Y-m-d H:i:s',round($u['lastlogintime']/1000));
			$tmp['is_open'] = $this->_isUserOpened($u['id']);
			$data[] = $tmp;
		}
		
		//返回
		$this->load->view('public/part/usercostcenterlist.php',array('members'=>$data));
		
	}
	/**
	 * 获取当前站点下的成本中心分组列表
	 */
	public function groups(){
		$this->load->model('uc_site_costcenter_model','cc');
		$groups = $this->cc->getGroups($this->p_site_id, $this->p_org_id);
	}
	
	
	public function test(){
		//$this->load->library('BossLib', '', 'boss');
		//$r = $this->boss->createTag($this->p_customer_code,'just another test2',0);
		//$r = $this->boss->modifyTag(14, ' another apple');
		//var_dump($r);
		//$r = $this->boss->getTagInfo(15);
		//print_r($r);
		//$r = $this->boss->getTags('8502');
		//var_dump($r);
		//$r = $this->boss->delTag(16);
		//var_dump($r);
		//$r = $this->boss->delUserTag($this->p_user_id);
		//var_dump($r);
		
		//$this->output->set_output(json_format(10000,'success'));
		//return_json(20000,'llll');
		$this->load->library('UmsLib', '', 'ums');
		$rs = $this->ums->getUnGroupedCostMemebers($this->p_customer_code, 0,array(356045));
		echo '<pre>';
		print_r($rs);
		echo '</pre>';
	}

}
