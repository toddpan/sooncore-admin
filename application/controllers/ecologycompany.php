<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @class Ecologycompany
 * @brief 生态企业 Controller 主要负责对生态企业列表显示、新加、删除、生态管理员[新加、删除]管理等操作。
 * @file Ecologycompany.php
 * @author zouyan <yan.zou@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
class Ecologycompany extends Admin_Controller{
    /**
     * @brief 构造方法
     */
    public function __construct(){
        parent::__construct();
        $this->load->helper('my_publicfun');
        $this->load->helper('my_dgmdate');
        $this->load->helper('language');
        //调用分配域的接口
        $this->load->library('API','','API');
        
    }

    /**
     *
     * @brief 生态企业页面
     * @details
     * -# 载入生态页面
     * @return null
     */
    public function ecologyPage(){
        
        $this->load->library('OrganizeLib','','OrganizeLib');
        //获得客户编码			
        $customer_code = $this->p_customer_code;//'024014';
        
        /**
         * old
         * 首级及下一级组织数组
         * $first_next_org_arr = $this->OrganizeLib->get_first_next_org_arr($customer_code,'2');
         * $org_arr = $this->OrganizeLib->InitzTree_arr($first_next_org_arr ,1,$in_arr);
         */
       
        //2014年10月10日 修改  @Author hao.chen@quanshi.com------------------------
        //1.获取当前站点顶级组织信息
        $this->load->library('UmsLib','','ums');
        $top_org_info	=	$this->ums->getOrganizationBrief($this->p_org_id);
        //2.获得下级
        $sub_org_info	=	$this->ums->getOrganization($this->p_org_id,$this->ums->scope_nextlevel,ORG_ECOLOGY_COMPANY);
        //3.合并子父级
        if($sub_org_info){
        	array_unshift($sub_org_info,$top_org_info);
        }else{
        	$sub_org_info = array($top_org_info);
        }
		//-------------------------------修改end----------------------------------
        //首级及下一级组织json串
        $in_arr = array(
            'is_first' => 1 ,//是否第一级0不是1是       
        );
        $org_arr = $this->OrganizeLib->InitzTree_arr($sub_org_info ,1,$in_arr);
        $org_json = '[]';
        if(is_array($org_arr)){//如果是数组
         $org_json = json_encode($org_arr);
        }
        $data['org_list_json'] = $org_json;
        //print_r($org_list_data); 
        //获得生态企业管理员第一级和第二级数组
        
        $first_manager_arr = $this->get_first_next_ecology_manager_arr();
    
        //首级及下一级组织json串
        $in_arr = array(
            'is_first' => 1 ,//是否第一级0不是1是       
        );
        $mananger_arr = $this->OrganizeLib->InitzTree_arr($first_manager_arr ,1,$in_arr);
        $mananger_json = '[]';
        if(is_array($mananger_arr)){//如果是数组
         	$mananger_json = json_encode($mananger_arr);
        }
        //查询组织成员
        $user_data = array();//当前用户列表 
        $this->assign('org_list_json', $org_json);
        $this->display('ecologycompany/ecologyPage.php');
    }
    
    /**
     *
     * @brief 获得生态企业管理员第一级和第二级：
     * @details
     * @return null echo输出下级组织的json串信息
     *
     */
    public function get_first_next_ecology_manager_arr() {
        $this->load->library('managerLib','','managerLib');
        //获得系统管理员id
        $manager_id = $this->p_max_manager_id;
        $first_next_manager_arr = array();

        //获得系统管理员数组
        $select_field = "userID as id , userID as name, super_admin_id as parentId ";
        $where_arr = array(
            'where' => array(
                //'super_admin_id' => $super_admin_id,//当前管理员id,
                'siteID' => $this->p_site_id,//站点id
                //'userID' => $userID ,//用户id
                //'state' => $state,//0：停用；1：启用
                'role_id'=> $this->p_max_manager_role_id,//角色id
                'orgID' => $this->p_org_id,//企业id        
                //'type' => $type,//1：总公司管理员；2：分公司管理员；3：生态企业管理员；0：其它'
             ),
        );
        $other_arr = array(
            'need_getnext_count' => 1 ,//是否统计这一级管理员数量0不统计1统计
            'need_getnext_manager' => 1 ,//是否列出下一级管理员 1列下级管理员2列下下级管理员，所有的
        ); 

        $re_arr = $this->managerLib->get_local_manager_arr(0,$manager_id,$select_field,$where_arr,$other_arr);

        $re_first_arr = $re_arr ;

       // die();

        //$first_next_manager_arr = array_merge ($first_next_manager_arr,$re_arr);

        //获得下组生态企业管理员
        //$select_field = 'userID,super_admin_id';
        $where_arr = array(
            'where' => array(
                //'super_admin_id' => $super_admin_id,//当前管理员id,
                'siteID' => $this->p_site_id,//站点id
                //'userID' => $userID ,//用户id
                //'state' => $state,//0：停用；1：启用
                'role_id'=> 5,//角色id
                'orgID' => $this->p_org_id,//企业id        
                //'type' => $type,//1：总公司管理员；2：分公司管理员；3：生态企业管理员；0：其它'
             ),
        );
        $other_arr = array(
            'need_getnext_count' => 1 ,//是否统计这一级管理员数量0不统计1统计
            'need_getnext_manager' => 1 ,//是否列出下一级管理员 1列下级管理员2列下下级管理员，所有的
        ); 
        $re_two_arr = $this->managerLib->get_local_manager_arr(2,$manager_id,$select_field,$where_arr,$other_arr);

       if(!isemptyArray($re_first_arr)){//如果不是空数组  
            $re_first_arr['childNodeCount'] = count($re_two_arr);
            $first_next_manager_arr[] = $re_first_arr;
        }
        if(!isemptyArray($re_two_arr)){//如果不是空数组  
            $first_next_manager_arr = array_merge($first_next_manager_arr,$re_two_arr); 
        }

        //$first_next_manager_arr = array_merge ($first_next_manager_arr,$re_arr);
       // print_r($first_next_manager_arr);
        $this->load->library('StaffLib','','StaffLib'); 
        foreach($first_next_manager_arr as $k => $v){
            $user_id = arr_unbound_value($v,'id',2,0);
            if($user_id > 0 ){
                $user_arr = $this-> StaffLib->get_user_by_id($user_id);
                $ns_user_name = arr_unbound_value($user_arr,'displayName',2,'');
                $first_next_manager_arr[$k]['name'] = $ns_user_name;
            }
        }
        return $first_next_manager_arr;
        
    }
    /**
     *
     * @brief 根据post过来的生态企业管理员id，获得下级生态企业管理员信息：
     * @details
     * @return null echo输出下级组织的json串信息
     *
     */
    public function get_next_manager_json(){        
         $this->load->library('managerLib','','managerLib');
         $this->load->library('OrganizeLib','','OrganizeLib');
        $pmanager_id  = strtolower($this->input->post('pmanager_id' , TRUE));//当前组织id
        $pmanager_id = empty_to_value($pmanager_id,0);//517  
        //获得当前下级组织数组
        $select_field = "userID as id , userID as name, super_admin_id as parentId ";
        $where_arr = array(
            'where' => array(
                //'super_admin_id' => $super_admin_id,//当前管理员id,
                'siteID' => $this->p_site_id,//站点id
                //'userID' => $userID ,//用户id
                //'state' => $state,//0：停用；1：启用
                'role_id'=> 5,//角色id
                'orgID' => $this->p_org_id,//企业id        
                //'type' => $type,//1：总公司管理员；2：分公司管理员；3：生态企业管理员；0：其它'
             ),
        );
        $other_arr = array(
            'need_getnext_count' => 1 ,//是否统计这一级管理员数量0不统计1统计
            'need_getnext_manager' => 1 ,//是否列出下一级管理员 1列下级管理员2列下下级管理员，所有的
        ); 
        $manager_list_data = $this->managerLib->get_local_manager_arr(2,$pmanager_id,$select_field,$where_arr,$other_arr);
        $this->load->library('StaffLib','','StaffLib'); 
        foreach($manager_list_data as $k => $v){
            $user_id = arr_unbound_value($v,'id',2,0);
            if($user_id > 0 ){
                $user_arr = $this-> StaffLib->get_user_by_id($user_id);
                $ns_user_name = arr_unbound_value($user_arr,'displayName',2,'');
                $manager_list_data[$k]['name'] = $ns_user_name;
            }
        }
        //$org_list_data = $this->OrganizeLib->get_org_array($org_id,'nextlevel','1,3,5');
        //print_r($org_list_data);
        ///exit;
        $in_arr = array(
            'is_first' => 0 ,//是否第一级0不是1是       
        );
        $manager_arr = $this->OrganizeLib->InitzTree_arr($manager_list_data ,1,$in_arr);
        
        $manager_json = '[]';
        if(is_array($manager_arr)){//如果是数组
            $manager_json = json_encode($manager_arr);   
        }
        echo $manager_json;        
    } 
    /**
     *
     * @brief 根据post过来的组织id，获得下级组织信息：
     * @details
     * @return null echo输出下级组织的json串信息
     *
     */
    public function get_next_OrgList(){
        $this->load->library('OrganizeLib','','OrganizeLib');
        $org_id  = strtolower($this->input->post('org_id' , TRUE));//当前组织id
        $org_id = empty_to_value($org_id,0);//517  

        //获得当前下级组织数组
        $org_list_data = $this->OrganizeLib->get_org_array($org_id,'nextlevel','2');
        //print_r($org_list_data);
        ///exit;
        $in_arr = array(
            'is_first' => 0 ,//是否第一级0不是1是       
        );
        $org_arr = $this->OrganizeLib->InitzTree_arr($org_list_data ,1,$in_arr);
        $org_json = '[]';
        if(is_array($org_arr)){//如果是数组
            $org_json = json_encode($org_arr);   
        }
        echo $org_json;        
    }
    /**
     *
     * @brief 根据帐号id，获得生态企业管理员创新的生态企业
     * @details
     * @param array $in_arr 
            $in_arr = array(
                'user_id' => $aaa,//用户id
                
            );
     * @return array 创新的生态企业数组
     * @return array 生态企业数组[二维]
            array(
                'id' => $id,//id
                'name' => $name,//名称
                'customercode' => $customercode,//客户编码
                'nodeCode' => $nodeCode,//组织串
                'type' => $type ,//类型
                'parentId' => $parentId,//父级id
                'parent_ecology' => $parent_ecology_arr,//上级生态企业[一维数组]
                'ecology_admin' => $ecology_admin_arr,//生态企业管理员[一维数组]
                'admin' => $admin_arr,//生态企业渠道管理员[二维数组]
            );
     */
    public function get_ecology_arr($in_arr = array()){
        $this->load->library('OrganizeLib','','OrganizeLib');
        $re_ecology_arr = array();//数据记录数组        
        if(isemptyArray($in_arr)){//如果是空数组
            return $re_ecology_arr;
        }
        $userID = arr_unbound_value($in_arr,'user_id',2,'');
        
        //根据管理员id,获得管理员所属的生态企业
        $ecology_arr = $this->OrganizeLib->get_ecology_arr_byuserid($in_arr); 
        $re_ecology_arr = $ecology_arr;
        if(!isemptyArray($ecology_arr)){//如果不是空数组
            //获得管理员信息
            $this->load->library('StaffLib','','StaffLib');
            $re_user_arr = $this->StaffLib->get_user_by_id($userID);
            $ns_user_arr = array();
            if(!isemptyArray($re_user_arr)){//如果不是空数组
                $ns_user_arr = array(
                    'user_id' => $userID,//用户id
                    'loginName' => arr_unbound_value($re_user_arr,'loginName',2,''),//登陆名
                    'email' => arr_unbound_value($re_user_arr,'email',2,''),//email
                    'mobileNumber' => arr_unbound_value($re_user_arr,'mobileNumber',2,''),//手机号
                    'displayName' => arr_unbound_value($re_user_arr,'displayName',2,''),//显示姓名
                );
            }
            $this->load->model('uc_user_admin_model');
            $ns_ecology_parentorg_arr = array();
            //加入生态企业管理员信息
            foreach($ecology_arr as $k => $v){
                //获得上级生态企业
                 $parentId = arr_unbound_value($v,'parentId',2,0);//上级生态企业 
                 $orgid = arr_unbound_value($v,'id',2,0);//生态企业，组织id
                 if($parentId > 0){
                     $ns_org_arr =  arr_unbound_value($ns_ecology_parentorg_arr,$parentId,1,array());//上级生态企业 
                     if(isemptyArray($ns_org_arr)){//如果是空数组
                        $ns_org_arr = $this->OrganizeLib->get_org_by_id($parentId);
                        $ns_ecology_parentorg_arr[$parentId] = $ns_org_arr;
                     }
                     $re_ecology_arr[$k]['parent_ecology'] = $ns_org_arr;
                 } 
                 //生态企业管理员
                 $re_ecology_arr[$k]['ecology_admin'] = $ns_user_arr;
                 //生态企业渠道管理员
                $where_arr = array(
                   //'super_admin_id' => $super_admin_id,//当前管理员id,
                   //'userID' => $user_id ,//用户id
                  // 'siteID' => $this->p_site_id,//站点id
                   //'state' => $state,//0：停用；1：启用
                   'role_id'=> 6,//角色id6渠道管理员
                   'orgID' => $orgid,//企业id          
                   //'type' => $type,//1：总公司管理员；2：分公司管理员；3：生态企业管理员；0：其它'
                );
                $admin_arr = $this->uc_user_admin_model->get_next_arr($where_arr);
                $re_ecology_arr[$k]['admin'] = $admin_arr;//二维数组
            }
        }
        return $re_ecology_arr;
    }
    
    /**
     *
     * @brief 生态企业详情页
     * @details
     * -# 载入组织与员工页面
     * @return null
     */
    public function info(){
    	$orgid = intval($this->input->get_post('org_id', true));//组织id
    	$this->load->library('UmsLib','','ums');
    	$org_info	=	$this->ums->getOrganizationBrief($orgid);
    	$this->assign('company_name', $org_info['name']);
    	$this->assign('company_english', $org_info['abbreviation']);
    	$this->assign('company_chinese', $org_info['abbreviation']);
    	$this->assign('telephoneNum', $org_info['countryCode'].$org_info['mobileNumber']);
    	$this->assign('country_location', $org_info['areaCode']);
    	$this->assign('company_introduce', $org_info['introduction']);
    	$this->display('public/part/ecologycompanyinfo.tpl');
    }
    /**
     *
     * @brief 生态企业详情页2
     * @details
     * -# 载入组织与员工页面
     * @return null
     */
    public function info2(){        
        $this->load->view('public/part/ecologycompanyinfo2.php');
    }
    /**
     *
     * @brief 生态企业详情页2-企业信息
     * @details
     * -# 载入组织与员工页面
     * @return null
     */
    public function info2_qiye(){   
        
        $org_id = strtolower($this->input->post('org_id' , TRUE));
        $org_id = empty_to_value($org_id,0);
        if(!preg_match('/^[\d]+$/',$org_id)){
            form_json_msg('1','','参数有误！');//返回错误信息json格式
        }
       $this->load->library('OrganizeLib','','OrganizeLib'); 
       $org_arr = $this->OrganizeLib->get_org_by_id($org_id);
       //国码
        $country_code = arr_unbound_value($org_arr,'countryCode',2,'+86');
        include_once APPPATH . 'libraries/public/Country_code.php';
        $country_code_obj = new Country_code();
        //$country_mobile_arr = $country_code_obj->get_mobile_arr($user_mobile);
        //$country_code = arr_unbound_value($country_mobile_arr,'code',2,'+86');
        //$country_mobile = arr_unbound_value($country_mobile_arr,'mobile',2,'');        
        //$user_info_data['mobileNumber'] = $country_mobile;
        $country_arr = $country_code_obj->get_country_code($country_code);
        foreach ($country_code as &$v){
        	$v['is_selected'] = 0;
        	if($v['country_code'] == $org_arr['countryCode']){
        		$v['is_selected'] = 1;
        	}
        }
        $this->load->model('uc_user_admin_model'); 
        //获得生态企业渠道管理员信息
//        $Admin_data = array(  
//           'select' =>'userID',
//           'where' => array(
//               'siteID' => $this->p_site_id,
//               'state' => 1,
//               'role_id' => 6 ,
//               'orgID' => $org_id,//企业id                              
//                )
//       );
//       $sel_user_admin_arr =  $this->uc_user_admin_model->operateDB(1,$Admin_data);  
        $this->load->model('uc_manager_ecology_model'); 
        $Admin_data = array(  
           'select' =>'user_id',
           'where' => array(
              // 'org_id' => $this->p_org_id,
              // 'site_id' => $this->p_site_id,
               'ecology_id' => $org_id,//企业id
			   'state'=>1,
                )
       );

       $sel_user_admin_arr =  $this->uc_manager_ecology_model->operateDB(1,$Admin_data);  

       $manager_id = arr_unbound_value($sel_user_admin_arr,'user_id',2,''); 
       $manager_name = $manager_id;
       if($manager_id > 0){
            $this->load->library('StaffLib','','StaffLib'); 
            $ns_user_arr = $this->StaffLib->get_user_by_id($manager_id);
            $manager_name = arr_unbound_value($ns_user_arr,'displayName',2,'');
       }
       
       $this->assign('org_id', $org_id);
       $this->assign('manager_id', $manager_id);
       $this->assign('manager_name', $manager_name);
       $this->assign('name', $org_arr['name']);
       $this->assign('abbreviation', $org_arr['abbreviation']);
       $this->assign('countryCode', $org_arr['countryCode']);
       $this->assign('country_arr', $country_arr);
       $this->assign('areaCode', $org_arr['areaCode']);
       $this->assign('mobileNumber', $org_arr['mobileNumber']);
       $this->assign('country_location', $org_arr['country_location']);
       $this->assign('introduction', $org_arr['introduction']);
       $this->assign('introduction_textarea', $org_arr['introduction']);
       $this->display('public/part/ecologycompanyinfo2_qiye.tpl');
    }
    /**
     *
     * @brief 生态企业详情页2-企业权限
     * @details
     * -# 载入组织与员工页面
     * @return null
     */
    public function info2_power(){  
        $org_id = strtolower($this->input->post('org_id' , TRUE));
        $org_id = empty_to_value($org_id,0);
        if(!preg_match('/^[\d]+$/',$org_id)){
            form_json_msg('1','','参数有误！');//返回错误信息json格式
        }
        $this->load->library('OrganizeLib','','OrganizeLib'); 
        $org_arr = $this->OrganizeLib->get_org_by_id($org_id);
        $data['org_id'] = $org_id;
        $org_code = arr_unbound_value($org_arr,'nodeCode',2,'');
        $this->load->library('PowerLib','','PowerLib');
        $in_arr = array(
                'userid' => 0,//用户id
                'org_code' => $org_code,//组织id串  -500-501-502-503
                'siteid' => $this->p_site_id//站点id
            );
        $re_array = $this->PowerLib->get_powers_arr(7,$in_arr);
        $power_class_arr = arr_unbound_value($re_array,'power_class',1,array());
        $power_arr = arr_unbound_value($re_array,'power_arr',1,array());
        $data['power_class_arr'] = $power_class_arr;
        $data['power_arr'] = $power_arr;
        $power_class_arr = array(
        		array(
		        		'id' => 1,
		        		'key' => 'allow_network_meeting',
		        		'name' => '允许召开网络会议',//允许召开网络会议
		        		'checked' => 1,
        		),
        		array(
        				'id' =>2,
        				'key' => 'allow_conference_call',
        				'name' => '允许召开电话会议',//允许召开电话会议
        				'checked' => 1,
        		),
        		array(
        				'id' => 3,
        				'key' => 'allow_set_call_forwarding',
        				'name' => '允许设置呼叫转移',//允许设置呼叫转移
        				'checked' => 1,
        		),
        		array(
        				'id' => 4,
        				'key' => 'allow_call',
        				'name' => '允许拨打电话',//允许拨打电话
        				'checked' => 1,
        		),
        );
        $this->assign('permissions_label_name', $power_class_arr);
        $this->display('public/part/ecologycompanyinfo2_power.tpl');
    }
    /**
     *
     * @brief 生态企业详情页2-保存企业权限
     * @details
     * -# 载入组织与员工页面
     * @return null
     */
    public function info2_save_power(){   
        $org_id = strtolower($this->input->post('org_id' , TRUE));
        $org_id = empty_to_value($org_id,0);
        if(!preg_match('/^[\d]+$/',$org_id)){
            form_json_msg('1','','参数有误！');//返回错误信息json格式
        }

        $this->load->library('OrganizeLib','','OrganizeLib'); 
        $org_arr = $this->OrganizeLib->get_org_by_id($org_id);
        $org_code = arr_unbound_value($org_arr,'nodeCode',2,'');

        $power_json=$this->input->post('power_json', TRUE);
        if(bn_is_empty($power_json)){//没有值
            form_json_msg('4','','参数有误！');//返回错误信息json格式
        }

        $power_arr = json_decode($power_json , TRUE ); 
        print_r($power_arr);
        die();
        $this->load->library('PowerLib','','PowerLib');
        $param_array = array(
               'power_type' => 7, //权限类型 1站点属性,2部门属性,3用户属性,4会议属性 
               'customerCode' => $this->p_customer_code,//客户编码
               'org_id' => $this->p_org_id,//站点所在的组织id
               'oper_type' => '1,2,3,4,5',//ums可以获得下级的组织类型，多个用,号分隔types：1:企业 2:生态企业 3:部门 4:生态企业部门 5 分公司 逗号分割的字符串
               'obj' => array(
                    'sys' => $this->p_sys_arr,
//                   array(
//                        'customerCode' => $this->p_customer_code,//客户编码
//                        'siteID' => $this->p_site_id,//站点id 
//                        'site_name' => $this->p_site_name,//站点名称 
//                        'accountId'=>$this->p_account_id,//分帐id ；注意：如果有用户，则是用户自己的
//                        'siteURL' => $this->p_stie_domain,//地址
//                        'contractId' => $this->p_contract_id,//合同id
//                        'operator_id' => $this->p_user_id,//操作发起人用户ID
//                        'client_ip' => $this->p_client_ip,//客户端ip
//                        'server_ip' => $this->p_server_ip,//服务端ip
//                        'oper_account' => $this->p_account,//操作帐号
//                        'oper_display_name' => $this->p_display_name,//操作姓名
//                        'orgID' => $this->p_org_id,//所属站点，分公司，生态企业组织id[各种管理员新加时，必填]
//                    ),
                )
        );
        //$power_arr = array(
         //  'summit_ValidationCount' => 0,
        //);
        $in_arr = array(
              'userid' => 0,//$this->p_user_id,//用户id
              'org_code' => $org_code,//-500-501-502-503',//组织id串  
              'siteid' => $this->p_site_id//站点id
          );
        $re_boolean = $this->PowerLib->save_powers($param_array,$power_arr, $in_arr);
        if($re_boolean){
            form_json_msg('0','','成功！');//返回错误信息json格式
        }else{
            form_json_msg('2','','失败！');//返回错误信息json格式
        }
        
        
    }
    /**
     *
     * @brief 生态企业详情页2-企业员工
     * @details
     * -# 载入组织与员工页面
     * @return null
     */
    public function info2_staff(){ 
        //获得当前的组织id
        $org_id = strtolower($this->input->post('org_id' , TRUE));
        $org_id = empty_to_value($org_id,0);
        if(!preg_match('/^[\d]+$/',$org_id)){
            form_json_msg('1','','参数有误！');//返回错误信息json格式
        }
        //获得当前组织下的所有人员信息
        $this->load->library('OrganizeLib','','OrganizeLib');
        $other_arr = array(
            'not_stop_type' => '1,3,4',//如果组织类型有变动,还可以获得的组织类型[多个用逗号分隔];为空则表代：不受限止
            'parent_type' => '',//父组织类型,可以为空，为空：可以进行获得下一级
        );
        //TODO 需要改为生态企业的
        //$user_arr = $this->OrganizeLib->get_all_user_byorgid($org_id,'1,3,5','',$other_arr);
        $site_id = $this->p_site_id;
        $user_arr = $this->OrganizeLib->get_users_list($org_id ,$site_id );
        foreach ($user_arr as &$user){
        	if(!$user['lastlogintime']){
        		$user['lastlogintime'] = '未登录';
        	}
        }
        $this->assign('user_arr', $user_arr);
        $this->display('public/part/ecologycompanyinfo2_staff.tpl');
    }
    /**
     *
     * @brief 生态企业详情页2-生态员工
     * @details
     * -# 载入组织与员工页面
     * @return null
     */
    public function info2_ecol_staff(){

        //获得当前的组织id
        $org_id = strtolower($this->input->post('org_id' , TRUE));
//        $org_id = empty_to_value($org_id,0);
//        if(!preg_match('/^[\d]+$/',$org_id)){
//            form_json_msg('1','','参数有误！');//返回错误信息json格式
//        }
        //获得本主参与人员
        $this->load->library('EcologyCompanyLib','','EcologyCompanyLib');
        $partake_where_arr = array(
                'org_id =' => $this->p_org_id, // 本方参与人的根据组织id
                'site_id =' => $this->p_site_id,// 本方参与人的站点id
                'ecology_id =' => $org_id,//生态企业id
        );
        $user_arr = $this->EcologyCompanyLib->get_ecology_partake_arr($partake_where_arr);        
        foreach ($user_arr as &$user){
        	if(!$user['lastlogintime']){
        		$user['lastlogintime'] = '未登录';
        	}else{
        		$user['lastlogintime'] = dgmdate(round($user['lastlogintime']/1000), 'dt');
        	}
        }
        $this->assign('user_arr', $user_arr);
        $this->display('public/part/ecologycompanyinfo2_ecol_staff.tpl');
    }
    /**
     *
     * @brief 生态企业页面
     * @details
     * -# 载入组织与员工页面
     * @return null
     */
    public function organizeStaff(){
        
        $this->load->view('ecologycompany/organizeStaff.php');
    }


    /**
     *
     * @brief 删除生态企业
     * @details
     * -# 调用UC删除生态企业自己及所有下级企业
     * -# 调用UC删除生态企业自己及所有下级管理员
     * -# 调用会议，关闭会议
     * -# 在UC删除生态企业的管理员、删除本企业对应的管理生态的管理员
     * @return null
     */
    public function delEcology(){
        //$this->load->view('ldap_import_Ecologycompany.php');
    }

    /**
     *
     * @brief 生态企业树页面[ajax]
     * @details
     * -# 最上层为当前企业或当前生态企业
     * -# 如果是当前企业，则注意右则企业信息时，只显示企业信息[公司介绍]内容为空
     * -# 如果是生态企业，则注意右则企业信息时，会有[企业信息]、企业权限、企业员工、本方参与人员
     * @return null
     *
     */
    public function ecologyTreePage(){
        // $this->load->view('aa.php');
    }

    /**
     *
     * @brief 创建生态企业
     * @details
     * -# 填写生态企业信息
     * @return null
     *
     */
    public function createEcologyCompany(){
        $this->load->library('OrganizeLib','','OrganizeLib');
        if($this->p_role_id == 1){//系统管理员
             $org_id = $this->uri->segment(3);
        }else{//其它管理员
             $org_id = $this->p_org_id;//$this->uri->segment(3);  
        }
        $org_id = empty_to_value($org_id,$this->p_org_id);
        if(preg_match('/^[\d]+$/',$org_id)){//获得组织名称
            $org_arr = $this-> OrganizeLib->get_org_by_id($org_id);
            $org_name = arr_unbound_value($org_arr,'name',2,'');
        } 
        $data['org_id'] = $org_id;
        $data['org_name'] = $org_name;
        //获得新加管理员时需要的标签
        $this->load->library('StaffLib','','StaffLib');
        $in_arr = array(
            'user_id' => 0,//用户id，没有写0
            'tag_type' => 6,//标签页面类型6生态管理员添加页面
            'site_id' => $this->p_site_id,//当前站点id 
        );
        //获得当前用户标签及[标签值]        
        $user_tag_arr = $this->StaffLib->get_user_tag_arr($in_arr);

        //$country_code = arr_unbound_value($user_tag_arr,'country_code',2,'');//国码
        //$country_arr = arr_unbound_value($user_tag_arr,'country_arr',1,array());//国码信息
        $user_info_arr = arr_unbound_value($user_tag_arr,'user_info_arr',1,array());//用户详情信息
        $system_must_tag_names = arr_unbound_value($user_tag_arr,'system_must_tag_names',2,'');//系统标签名称，多个用,号分隔
        $system_must_tag_arr= arr_unbound_value($user_tag_arr,'system_must_tag_arr',1,array());//系统标签及其值数组
        $seled_not_must_tag_names= arr_unbound_value($user_tag_arr,'seled_not_must_tag_names',2,'');//可选标签名称，多个用,号分隔
        $seled_not_must_tag_arr= arr_unbound_value($user_tag_arr,'seled_not_must_tag_arr',1,array());//可选标签及其值数组
        $user_defined_tag_names= arr_unbound_value($user_tag_arr,'user_defined_tag_names',2,'');//自定义标签名称，多个用,号分隔
        $user_defined_tag_arr= arr_unbound_value($user_tag_arr,'user_defined_tag_arr',1,array());//自定义标签及其值数组
       // print_r($user_info_arr);
        //echo '<br/><br/>';
        //echo json_encode($system_must_tag_arr);
        //echo '<br/><br/>';
       // echo json_encode($seled_not_must_tag_arr);
        //echo '<br/><br/>';
       // echo json_encode($user_defined_tag_arr);
        //获得手机号
        $user_mobile = arr_unbound_value($user_info_arr,'mobileNumber',2,'');
        //载入员工标签资源
        include_once APPPATH . 'libraries/public/Country_code.php';
        $country_code_obj = new Country_code();
        $country_mobile_arr = $country_code_obj->get_mobile_arr($user_mobile);
        $country_code = arr_unbound_value($country_mobile_arr,'code',2,'+86');
        $country_mobile = arr_unbound_value($country_mobile_arr,'mobile',2,'');        
        //$user_info_data['mobileNumber'] = $country_mobile;
        $country_arr = $country_code_obj->get_country_code($country_code);        

        $data['user_id'] = $user_id;//当前用户id
        $data['country_code'] = $country_code;//国码
        $data['country_arr'] = $country_arr;//国码信息 
        $data['country_mobile'] = $country_mobile;//手机号
        $data['user_info_arr'] = $user_info_arr ;//用户详情信息
        $data['system_must_tag_names'] = $system_must_tag_names;//系统标签名称，多个用,号分隔
        $data['system_must_tag_arr'] = $system_must_tag_arr;//系统标签及其值数组
        $data['seled_not_must_tag_names'] = $seled_not_must_tag_names;//可选标签名称，多个用,号分隔
        $data['seled_not_must_tag_arr'] = $seled_not_must_tag_arr;//可选标签及其值数组
        $data['user_defined_tag_names'] = $user_defined_tag_names;//自定义标签名称，多个用,号分隔
        $data['user_defined_tag_arr'] = $user_defined_tag_arr;//自定义标签及其值数组
        //后缀
        $data['url_host'] = $this->p_account_back;
        $this->load->view('ecologycompany/createEcologyCompany1.php',$data);
    }
    /**
     *
     * @brief 验证新加生态企业时的参数是否正确
     * @param array $com_arr 企业数组信息     
        $com_arr = array(
            'company_name' => $company_name,
            //'company_english' => $company_english,
            'company_chinese' => $company_chinese,
            'country_code' => $country_code,
            'area_code' => $area_code,
            'phone_number' => $phone_number,
            'country_area' => $country_area,
            'introduce' => $introduce,
            'org_id' => $org_id,
        );
     * @return null 有错误，则直接输入json串
     */
    public function validate_com($com_arr = array()){
        // 获取企业名称company_name  
        $company_name = arr_unbound_value($com_arr,'company_name',2,'');
        $pattern='/^[\s\S]{1,100}$/';//[\x80-\xffA-Za-z\d]{1,100}
        if(!preg_match($pattern, $company_name)){
            form_json_msg('1','company_name','企业名称格式不正确');//返回正确信息json格式
        }
        
        // 获取英文简称company_english
        //$company_english=arr_unbound_value($com_arr,'company_english',2,'');
        //$pattern='/^[\da-zA-Z]{1,100}$/';
       // if(!preg_match($pattern, $company_english)){
        //    form_json_msg('2','company_english','英文简称格式不正确');//返回正确信息json格式
       // }

        // 中文简称 company_chinese
        $company_chinese = arr_unbound_value($com_arr,'company_chinese',2,'');
        $pattern='/^[\s\S]{1,100}$/';//[\x80-\xff\da-zA-Z\S]{1,100}
        if(!preg_match($pattern, $company_chinese)){
            form_json_msg('3','company_chinese','中文简称格式不正确');//返回正确信息json格式
        }
        
        //联系电话 国码 add_num
        $country_code = arr_unbound_value($com_arr,'country_code',2,''); 
        $pattern='/^\+[\d]{2,4}$/';
        if(!preg_match($pattern, $country_code)){
            form_json_msg('4','add_num','国码格式不正确');//返回正确信息json格式
        }
        
        //联系电话 区号 area_code
        $area_code = arr_unbound_value($com_arr,'area_code',2,'');
        $pattern='/^[\d]{2,6}$/';
        if(!preg_match($pattern, $area_code)){
            form_json_msg('5','area_code','区号格式不正确');//返回正确信息json格式
        }
        
        //联系电话 电话号码 phoneNum_1
        $phone_number = arr_unbound_value($com_arr,'phone_number',2,'');
        $pattern='/^[\d]{2,10}$/';
        if(!preg_match($pattern, $phone_number)){
            form_json_msg('6','phoneNum_1','电话号码格式不正确');//返回正确信息json格式
        }
        
        // 国家地区country_area
        $country_area = arr_unbound_value($com_arr,'country_area',2,'');
        $pattern='/^[\s\S]{2,100}$/';
        if(!preg_match($pattern, $country_area)){
            form_json_msg('7','country_area','国家地区格式不正确');//返回正确信息json格式
        }
        
        // 公司介绍 textarea
        $introduce = arr_unbound_value($com_arr,'introduce',2,'');
        
        $intro_len = strlen($introduce);
        
        $pattern='/^[\s\S]{2,2000}$/';
        if(!preg_match($pattern, $introduce)){
            form_json_msg('8','textarea','公司介绍格式不正确');//返回正确信息json格式
        }
//        if($intro_len < 2 || $intro_len > 2000){
//            form_json_msg('8','textarea','公司介绍格式不正确');//返回正确信息json格式
//        }
        //所属组织id 
        $org_id = arr_unbound_value($com_arr,'org_id',2,''); 
        if(!preg_match('/^[\d]+$/',$org_id)){//获得组织名称
             form_json_msg('9','','组织格式不正确');//返回正确信息json格式
        } 
    }
    /**
     *
     * @brief 验证创建生态企业第一步提交的表单数据是否符合规定
     * @details
     * -# 依次获取并验证表单提交数据
     * -# 企业名称：company_name
     * -#英文简称：company_english
     * -#中文简称:company_chinese
     * -#联系电话：phoneNum
     * -#国家地区：country_area
     * -#公司介绍：textarea
     */
    public function valid_eco_1(){
        // 获取企业名称company_name  
        $company_name = $this->input->post('company_name',true);
        // 获取英文简称company_english
        //$company_english=$this->input->post('company_english',true);
        // 中文简称 company_chinese
        $company_chinese = $this->input->post('company_chinese',true);
        //联系电话 国码 add_num
        $country_code = $this->input->post('country_code',true);        
        //联系电话 区号 area_code
        $area_code = $this->input->post('area_code',true);
        //联系电话 电话号码 phoneNum_1
        $phone_number = $this->input->post('phone_number',true);
        // 国家地区country_area
        $country_area = $this->input->post('country_area',true);
        // 公司介绍textarea
        $introduce = $this->input->post('introduce');
        //所属组织id 
        $org_id = $this->input->post('org_id',true);//新加时，为父级节点id,修改时为当前节点id
        $operate_type = $this->input->post('operate_type',true);//操作类型0信息验证1新加入库
        $com_arr = array(
            'company_name' => $company_name,
            //'company_english' => $company_english,
            'company_chinese' => $company_chinese,
            'country_code' => $country_code,
            'area_code' => $area_code,
            'phone_number' => $phone_number,
            'country_area' => $country_area,
            'introduce' => $introduce,
            'org_id' => $org_id,
        );
        
        $this->validate_com($com_arr);
        if($operate_type == 0){
            form_json_msg('0','','生态企业信息正确');//返回正确信息json格式
        }else{
            $this->load->library('OrganizeLib','','OrganizeLib'); 
            $org_arr = $this-> OrganizeLib->get_org_by_id($org_id);
            $org_pId = arr_unbound_value($org_arr,'parentId',2,null);
            $data = array(
                  'id' => $org_id,
                  'name' => $company_name,//"创想空间北京分公司",
                  'abbreviation' => $company_chinese,
                  'code' => $this->p_customer_code,//"900144",
                  'countryCode' => $country_code,
                  'areaCode' => $area_code,
                  'mobileNumber' => $phone_number,
                  'introduction' => $introduce,
                  //"siturl" => null,
                 // "childOrder" => null,
                  "parentId" => $org_pId,
                  "customercode" => $this->p_customer_code,
                 // "type" => null,
              );
            $re_boolean = $this->save_qiye($data);
            if($re_boolean){//成功
                form_json_msg('0','','修改生态企业信息成功');
            }else{
                form_json_msg('1','','修改生态企业信息失败');
            }
        }
    }
    /**
     *
     * @brief 生态企业详情页2-保存企业信息
     * @details
     * -# 载入组织与员工页面
     * @return boolean 成功true 失败false
     */
    public function save_qiye($data = array()){  
        $ums_arr = $this->API->UMS_Special_API(json_encode($data),10);
         if(api_operate_fail($ums_arr)){//失败
            $err_msg = 'ums api rs/organizations modify org fail.';
            log_message('error', $err_msg); 
            return false;
          }else{
            log_message('debug', 'ums api rs/organizations modify org success.');  
            return true;
          }
    }
    /**
     *
     * @brief 验证创建生态企业权限
     * @details
     */
    public function valid_eco_2(){
        //post 过来的josn串
        $power_json=$this->input->post('power_json', TRUE);
        $power_json = empty_to_value($power_json,'');//
        if(bn_is_empty($power_json)){//为空
            form_json_msg('31','', '参数有误');//
        }
        form_json_msg('0','', '参数无误');//
    }
    /**
     *
     * @brief 生态企业管理员信息
     * @details
     */
    public function valid_eco_3(){
        //post 过来的josn串
        $user_json=$this->input->post('user_json', TRUE);
        $user_json = empty_to_value($user_json,'');//
        if(bn_is_empty($user_json)){//为空
            form_json_msg('31','', '参数有误');//
        }
        form_json_msg('0','', '参数无误');//
    }
      /**
     *
     * @brief 保存生态企业
     * @details
     */
    public function save_ecology_company(){
        //生态企业详情
        $company_information_json = $this->input->post('company_information',true);
        //echo '' . $company_information_json . '<br/>';
        $company_information_arr = json_decode($company_information_json,true);   
        if(isemptyArray($company_information_arr)){//空数组
            form_json_msg('11','', '生态企业详情参数有误');//
        }
        //die();
        //权限
        $company_power_json = $this->input->post('company_power',true);
        //echo '' . $company_power_json . '<br/>';
        if(bn_is_empty($company_power_json)){//没有值
            form_json_msg('41','','权限参数有误！');//返回错误信息json格式
        }
        //生态企业管理员
        $company_adminstrator_json = $this->input->post('company_adminstrator',true);
        //echo '' . $company_adminstrator_json . '<br/>';
        $user_arr = json_decode($company_adminstrator_json,true);//转为数组
        if(isemptyArray($user_arr)){//空数组
            form_json_msg('12','', '生态企业管理员参数有误');//
        }
        //本方参与人员        
        $company_staff_json = $this->input->post('company_staff',true);//[{"userid":39937,"orgid":1031},{"userid":39950,"orgid":1034}]
        //echo '' . $company_staff_json . '<br/>';
        //die();
        $user_json = empty_to_value($company_staff_json,'');//
        if(bn_is_empty($user_json)){//为空
            form_json_msg('1','', '本方参与人员参数有误');//
        }
        $staff_user_arr = json_decode($user_json,true);//转为数组
        if(isemptyArray($staff_user_arr)){//空数组
            form_json_msg('2','', '本方参与人员参数有误');//
        }
        //所属组织id 
        $org_id = $this->input->post('org_id',true);
        
        //echo '' . $org_id . '<br/>';
        //die();
        //保存生态企业             
        $company_name = arr_unbound_value($company_information_arr,'company_name',2,'');       
        $company_english = arr_unbound_value($company_information_arr,'company_english',2,'');     
        $company_chinese = arr_unbound_value($company_information_arr,'company_chinese',2,'');       
        $country_code = arr_unbound_value($company_information_arr,'country_code',2,'');       
        $area_code = arr_unbound_value($company_information_arr,'area_code',2,'');       
        $phone_number = arr_unbound_value($company_information_arr,'phone_number',2,'');       
        $country_area = arr_unbound_value($company_information_arr,'country_area',2,'');       
        $introduce = arr_unbound_value($company_information_arr,'introduce',2,'');  
        $com_arr = array(
            'company_name' => $company_name,
            //'company_english' => $company_english,
            'company_chinese' => $company_chinese,
            'country_code' => $country_code,
            'area_code' => $area_code,
            'phone_number' => $phone_number,
            'country_area' => $country_area,
            'introduce' => $introduce,
            'org_id' => $org_id,
        );
        $company_name = arr_unbound_value($com_arr,'company_name',2,'');;
        $this->validate_com($com_arr);
        //调用ums保存企业信息
        $org_arr = array(
            'name' => $company_name,
            'code' => $this->p_customer_code,
            'abbreviation' => $company_chinese,//公司中文简称
            'countryCode' => $country_code,//国码
            'areaCode' => $area_code,//区号
            'mobileNumber' => $phone_number,//号码
            'introduction' => $introduce,//公司介绍
            //'childOrder' => null,
            'parentId' => $org_id,
            'customercode' => $this->p_customer_code,                        
            'type' => 2//1:企业 2:生态企业 3:部门 4:生态企业部门 5 分公司 逗号分割的字符串
        );
        $uc_org_arr = $this->API->UMS_Special_API(json_encode($org_arr),1,array());
        if(api_operate_fail($uc_org_arr)){//失败
             log_message('error', 'ums api /rs/organizations fail.'); 
             form_json_msg('41','', '保存生态企业失败');//
         }else{
             log_message('debug', 'ums api /rs/organizations success.'); 
        }
        $ns_org_id = arr_unbound_value($uc_org_arr,'org_id',2,0); 
        if($ns_org_id <= 0 ){
            form_json_msg('42','', '保存生态企业失败');//
        }
        //保存生态企业管理员与生态企业关系
        $this->load->model('uc_manager_ecology_model');
         $insert_data = array(  
             'org_id' => $this->p_org_id, 
             'site_id' => $this->p_site_id, 
             'user_id' => $this->p_user_id, 
             'ecology_id' => $ns_org_id,
             'time' => dgmdate(time(), 'dt'),
         );
        $insert_arr =  $this->uc_manager_ecology_model->insert_db($insert_data);
        $requestId = 0;
        if(db_operate_fail($insert_arr)){//失败
            form_json_msg('43','', '保存管理员生态企业信息失败');//
        }
        //echo $ns_org_id;
       // die();
        //获得当前组织信息
       $this->load->library('OrganizeLib','','OrganizeLib'); 
       $re_org_arr = $this->OrganizeLib->get_org_by_id($ns_org_id);
       $org_code = arr_unbound_value($re_org_arr,'nodeCode',2,''); 
        //保存生态企业权限
        $power_json = $company_power_json;//$this->input->post('power_json', TRUE);
        if(bn_is_empty($power_json)){//没有值
            form_json_msg('4','','参数有误！');//返回错误信息json格式
        }
        $power_arr = array();
//        $power_arr = json_decode($power_json , TRUE ); 
        $this->load->library('PowerLib','','PowerLib');
        $sys_arr = $this->p_sys_arr;
        $param_array = array(
               'power_type' => 7, //权限类型 1站点属性,2部门属性,3用户属性,4会议属性 7生态企业权限
               'customerCode' => $this->p_customer_code,//客户编码
               'org_id' => $this->p_org_id,//站点所在的组织id
               'oper_type' => '2,4',// ums可以获得下级的ums可以获得下级的组织类型组织类型，多个用,号分隔types：1:企业 2:生态企业 3:部门 4:生态企业部门 5 分公司 逗号分割的字符串
               'obj' => array(
                    'sys' => $sys_arr,
//                   array(
//                        'customerCode' => $this->p_customer_code,//客户编码
//                        'siteID' => $this->p_site_id,//站点id 
//                        'site_name' => $this->p_site_name,//站点名称 
//                        'accountId'=>$this->p_account_id,//分帐id ；注意：如果有用户，则是用户自己的
//                        'siteURL' => $this->p_stie_domain,//地址
//                        'contractId' => $this->p_contract_id,//合同id
//                        'operator_id' => $this->p_user_id,//操作发起人用户ID
//                        'client_ip' => $this->p_client_ip,//客户端ip
//                        'server_ip' => $this->p_server_ip,//服务端ip
//                        'oper_account' => $this->p_account,//操作帐号
//                        'oper_display_name' => $this->p_display_name,//操作姓名
//                        'orgID' => $this->p_org_id,//所属站点，分公司，生态企业组织id[各种管理员新加时，必填]
//                    ),
                )
        );
        //$power_arr = array(
         //  'summit_ValidationCount' => 0,
        //);
        $in_arr = array(
              'userid' => 0,//$this->p_user_id,//用户id
              'org_code' => $org_code,//-500-501-502-503',//组织id串  
              'siteid' => $this->p_site_id//站点id
          );
        //直接保存用户权限
        $re_boolean = $this->PowerLib->save_powers($param_array,$power_arr, $in_arr);
        if($re_boolean){
           // form_json_msg('0','','成功！');//返回错误信息json格式
        }else{
            //form_json_msg('2','','失败！');//返回错误信息json格式
        }
        //保存生态企业管理员[线程保存用户信息]
        $user_id = 0;
        $tag_type = 6;//6生态管理员添加页面
        $this->load->library('StaffLib','','StaffLib');
        $other_arr = array(
            'user_id'=> $user_id,//0为新加；具体数字为修改的userid
            'tag_type' => $tag_type,//标签页面类型
        );  
        $n_sys_arr = $this->p_sys_arr;
        $n_sys_arr['orgID'] = $ns_org_id;
        $ns_sys_arr = $n_sys_arr;
        $ns_sys_arr['parentId'] = $this->p_org_id;//"513",//当前站点的组织机构id 
        $ns_sys_arr['user_type'] = 6; //帐号类型0普通用户1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员[各种管理员新加时，必填]7生态企业普通用户
        $ns_sys_arr['user_source'] = 3;//帐号开通来源,多个用，号分隔 1批量导入2组织管理员-单个新加3生态渠道管理员新加4任务新加
        $ns_sys_arr['isLDAP'] = $this->p_is_ldap;//帐号导入类型[各种管理员新加时，必填]
        $ns_sys_arr['session_id'] = $this->p_session_id; //sessionid
       // $sys_arr['sys_user_id'] = $this->p_user_id,//登陆的系统管理员id
//        array(
//              'siteID' => $this->p_site_id,//站点id
//              'customerCode' => $this->p_customer_code,//客户编码
//            'parentId' => $this->p_org_id,//"513",//当前站点的组织机构id 
//              "site_name"=>$this->p_site_name, //站点名称
//              "accountId"=> $this->p_account_id, //当前用户分帐id
//              "siteURL"=> $this->p_stie_domain, //站点地址
//              "contractId"=> $this->p_contract_id,//合同id
//              "operator_id" => $this->p_user_id,//操作发起人用户ID
//              'client_ip' => $this->p_client_ip,//客户端ip
//              'server_ip' => $this->p_server_ip,//服务端ip
//              'oper_account' => $this->p_account,//操作帐号
//              'oper_display_name' => $this->p_display_name,//操作姓名
//            'user_type' => 6, //帐号类型0普通用户1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员[各种管理员新加时，必填]7生态企业普通用户
//              'orgID' => $ns_org_id,//$this->p_org_id,//所属站点，分公司，生态企业组织id[各种管理员新加时，必填]
//            'isLDAP' => $this->p_is_ldap,//帐号导入类型[各种管理员新加时，必填]
//            'session_id' => $this->p_session_id, //sessionid
//            //'sys_user_id' => $this->p_user_id,//登陆的系统管理员id
//        );  
        log_message('debug', ' into StaffLib save_user method.'); 
        //调用保存用户方法，对用户信息进行保存,成功true 失败 false 失败的字符串  
        $org_tag_arr = array(
            array(
                'id' => $this->p_org_id,//组织id
                'value' => $this->p_org_name,//组织名称
            ),
            array(
                'id' => $ns_org_id,//组织id
                'value' => $company_name,//组织名称
            ),
        );
        $user_arr['org_tag'] = $org_tag_arr;//

        //file_put_contents(  ' ' . __FUNCTION__. 'aa.txt' ,  json_encode($org_tag_arr));
        //file_put_contents(  ' ' . __FUNCTION__. 'bb.txt' ,  json_encode($user_arr));
        $re_boolean = $this->StaffLib->save_user($user_arr,$other_arr,$ns_sys_arr);
        log_message('debug', ' out StaffLib save_user method.'); 
       // print_r($re_boolean);
       // die();
        if($re_boolean == true){
            //form_json_msg('0','', '保存成功');//
        }else{
            if($re_boolean == false){
                form_json_msg('3','', '保存失败');//
            }else{
                form_json_msg('5','', '保存失败' . $re_boolean);//
            }
        }
        $ecology_arr = array(
            'ecology_id' => $ns_org_id,//生态企业id
            'site_id' => $this->p_site_id,//当前站点id
            'orgid' => $this->p_org_id,//当前参与人所在的分公司/生态企业组织id
            'type' => 1,//类型1全新的新加或修改，会删除其它的，2只是新加或修改，会保存其它的
        );
        //保存本方参与人员{"userid":50703,"orgid":1063}
        $re_boolean = $this->StaffLib->save_partake($ecology_arr,$staff_user_arr);
        if($re_boolean){//成功
            
        }else{//失败
            
        }
        form_json_msg('0','','生态企业保存成功');//返回正确信息json格式
    }
    /**
     * @access public
     * @abstract 新加本方参与人员
     */   
    public function add_partake(){
        $user_json = $this->input->post('user_id' , TRUE);//需要调入的用户信息串{"userid":5810,"orgid":528,"org_name":BOSS组,"org_pid":521}
        if( bn_is_empty($user_json) ){
           form_json_msg('1','','参数有误!');//返回正确信息json格式   
        }
        $ns_org_id =  $this->input->post('org_id' , TRUE);//新加的组织id 0;//生态企业id
        
        $this->load->library('StaffLib','','StaffLib');        
        $ecology_arr = array(
            'ecology_id' => $ns_org_id,//生态企业id
            'site_id' => $this->p_site_id,//当前站点id
            'orgid' => $this->p_org_id,//当前参与人所在的分公司/生态企业组织id
            'type' => 2,//类型1全新的新加或修改，会删除其它的，2只是新加或修改，会保存其它的
        );
        if(bn_is_empty($user_json)){//为空
            form_json_msg('1','', '本方参与人员参数有误');//
        }
        $staff_user_arr = json_decode($user_json,true);//转为数组
        if(isemptyArray($staff_user_arr)){//空数组
            form_json_msg('2','', '本方参与人员参数有误');//
        }
        //保存本方参与人员{"userid":50703,"orgid":1063}
        $re_boolean = $this->StaffLib->save_partake($ecology_arr,$staff_user_arr);
        if($re_boolean){//成功
            
        }else{//失败
            
        }
        form_json_msg('0','','本方参与人员保存成功');//返回正确信息json格式
    }
    /**
     * @access public
     * @abstract 删除本方参与人员
     */   
    public function delete_partake(){
        $this->load->library('StaffLib','','StaffLib'); 
        $ecology_id = $this->input->post('ecology_id' , TRUE);
        if(!preg_match('/^[\d]+$/',$ecology_id)){
            form_json_msg('1','','生态企业id编号有误！');//返回错误信息json格式
        }
        $del_userids = $this->input->post('user_ids' , TRUE);//356065,356073,356074
        if(bn_is_empty($del_userids) ){
            form_json_msg('2','','帐号信息有误！');//返回错误信息json格式
        }
        $del_userid_arr = explode(',',$del_userids);
        $this->StaffLib->del_partake($ecology_id,$del_userid_arr);

        form_json_msg('0','','本方参与人员删除成功');//返回正确信息json格式
    }
    /**
     *
     * @brief 删除生态企业
     * @details
     * 保存到线程
     * 运行线程，获得当前生态企业及其所有下及下下级帐号，做批量关闭[从上而一，一个企业一个企业继承的关闭]
     * 在批量关闭完成时，从最下而上删除生态企业
     * @return null
     *
     */
    public function delete_ecology(){
        $org_id = strtolower($this->input->post('ecology_id' , TRUE));//多个用,号分隔
        //echo $org_id;
        if(!preg_match('/^([\d]+|([\d]+\,)+[\d]+)(\,)?$/',$org_id)){
            form_json_msg('1','','帐号信息有误！');//返回错误信息json格式
        }
       // echo $org_id;
         form_json_msg('0','','生态企业删除成功！');//返回错误信息json格式
        die();
        $org_id_arr = explode(',', $org_id);
        $sys_arr = $this->p_sys_arr;
//        array(
//            'customerCode' => $this->p_customer_code,//客户编码
//            'siteID' => $this->p_site_id,//站点id 
//            'site_name' => $this->p_site_name,//站点名称 
//            'accountId'=>$this->p_account_id,//分帐id ；注意：如果有用户，则是用户自己的
//            'siteURL' => $this->p_stie_domain,//地址
//            'contractId' => $this->p_contract_id,//合同id
//            'operator_id' => $this->p_user_id,//操作发起人用户ID
//            'client_ip' => $this->p_client_ip,//客户端ip
//            'server_ip' => $this->p_server_ip,//服务端ip
//            'oper_account' => $this->p_account,//操作帐号
//            'oper_display_name' => $this->p_display_name,//操作姓名
//            'orgID' => $this->p_org_id,//所属站点，分公司，生态企业组织id[各种管理员新加时，必填]
//        );
        
        if(!isemptyArray($org_id_arr)){//不是空数组
             //保存到线程
            $thread_arr = array(
                'sys' => $sys_arr,  
                'org_arr' => $org_id_arr
            );
            $data = 'type=6&value=' . urlencode(json_encode($thread_arr));                
            $uc_thread_arr = $this->API->UCAPI($data,2,array('url' => base_url('')));
            if(api_operate_fail($uc_thread_arr)){//失败
                log_message('error', 'save thread  ' . json_encode($data) . 'is fail.');
                return false;                
            }
            log_message('debug', 'save thread  ' . json_encode($data) . ' is success.');
        }
        form_json_msg('0','','删除生态企业成功');//返回正确信息json格式 
    }
    /**
     *
     * @brief 更改生态企业管理员
     * @details
     * 如果新的管理员与原管理员是同一个人，不进行操作
     * 如果新管理员，不是生态企业管理员，则创建为生态管理员[父级为，原管理员父级],把生态企业改在新管理员名下
     * .............是............,把生态企业改在新管理员名下
     * 如果老管理员去掉当前生态后，还有生态，则只是移交生态的管理员
     * ......................没有生态，则走管理员删除流程
     * @return null
     *
     */
    public function modify_ecology_admin(){
        $old_user_id = strtolower($this->input->post('old_user_id' , TRUE));//多个用,号分隔
        if(!preg_match('/^[\d]+$/',$old_user_id)){//旧生态管理员
            form_json_msg(COMMON_PARAM_ERROR,'','信息有误！');//返回错误信息json格式
        }
       // echo $old_user_id;
        $new_user_json = strtolower($this->input->post('user_id' , TRUE));//多个用,号分隔{"userid":403483,"user_name":"白雪","orgid":1117,"org_name":"新产品研发组","org_pid":"1114","org_code":"-1099-1111-1114-1117"}
        $new_user_arr = json_decode($new_user_json,true);
        $new_user_id = 0;
        foreach($new_user_arr as $k => $v){
            $new_user_id = arr_unbound_value($v,'userid',2,0);
        }
       // echo $new_user_id;
        //if(!preg_match('/^[\d]+$/',$new_user_id)){//新生态管理员
          //  form_json_msg('1','','信息有误！');//返回错误信息json格式
        //}
        
        $ecology_id = strtolower($this->input->post('ecology_id' , TRUE));//多个用,号分隔
        if(!preg_match('/^[\d]+$/',$ecology_id)){//生态id
            form_json_msg('1','','信息有误！');//返回错误信息json格式
        }
       // echo $ecology_id;
        $this->load->library('OrganizeLib','','OrganizeLib');
        $msg_arr = array(
            'old_user_id' => $old_user_id,
            'new_user_id' => $new_user_id,
            'ecology_id' => $ecology_id,
        );
       // print_r($msg_arr);
        //die();
        $re_boolean = $this-> OrganizeLib -> modify_ecology_manager($msg_arr);
        if($re_boolean){//成功
            form_json_msg('0','','更改生态企业管理员成功！');//返回错误信息json格式
        }else{
            form_json_msg('0','','更改生态企业管理员失败！');//返回错误信息json格式
        }
    }
    
    /**
     *
     * @brief 添加生态企业管理员
     * @details
     * 
     * @return null
     *
     */
    public function add_ecology_admin(){
        $parent_user_id = strtolower($this->input->post('org_id' , TRUE));//
        if(!preg_match('/^[\d]+$/',$parent_user_id)){//生态管理员
            form_json_msg('1','','信息有误！');//返回错误信息json格式
        } 
        $user_id_json = strtolower($this->input->post('user_id' , TRUE));//[{"userid":296893,"user_name":"南冰","orgid":1094,"org_name":"测试组","org_pid":"1092","org_code":"-886-1091-1092-1094"}]
        //if(!preg_match('/^([\d]+|([\d]+\,)+[\d]+)(\,)?$/',$user_ids)){
          //  form_json_msg('1','','帐号信息有误！');//返回错误信息json格式
        //}
        //$user_id_arr = explode(',', $user_ids);//选中的用户id多个用,号分隔
        $user_ids_arr = json_decode($user_id_json,true);
        $user_id_arr = array();
        foreach($user_ids_arr as $k => $v){
            $user_id_arr[] = arr_unbound_value($v,'userid',2,0);
        }
        $msg_arr = array(
            'parent_user_id' => $parent_user_id,
            'user_id_arr' => $user_id_arr,
            'other' => array(
                'orgID' => $this->p_org_id,
                'isLDAP' => $this->p_is_ldap,
                'siteID' => $this->p_site_id,
            ),
        );
        $this->load->library('OrganizeLib','','OrganizeLib'); 
        $re_boolean = $this-> OrganizeLib -> add_ecology_manager($msg_arr);
        if($re_boolean){//成功
            form_json_msg('0','','新加生态企业管理员成功！');//返回错误信息json格式
        }else{
            form_json_msg('0','','新加生态企业管理员失败！');//返回错误信息json格式
        }
    }


    /**
     *
     * @brief 弹窗_提醒_移除生态员工
     * @details
     * -# 设置本方参与的用户
     * @return null
     *
     */
    public function ecology_partake_move(){
        $this->load->view('public/popup/ecologypartakemove.php');
    }

    /**
     *
     * @brief 弹窗_添加生态合作员
     * @details
     * -# 设置本方参与的用户
     * @return null
     *
     */
    public function ecology_partake_add(){
        $this->load->view('public/popup/ecologypartakeadd.php');
    }
    /**
     *
     * @brief 创建生态企业
     * @details
     * -# 放弃填写生态企业信息，返回到企业生态
     * @return null
     *
     */
    public function quitCreateEcologyCompany(){
        $data['org_id'] = 1111;
        $this->load->view('ecologycompany/ecologyPage.php',$data);

    }

    /**
     *
     * @brief 创建生态企业
     * @details
     * -# 设置生态企业权限
     * @return null
     *
     */
    public function setEcologyCompany(){
        $this->load->view('ecologycompany/createEcologyCompany2.php');

    }

    /**
     *
     * @brief 创建生态企业
     * @details
     * -# 放弃设置生态企业权限，返回到主页
     * @return null
     *
     */
    public function quitSetEcologyCompany(){
        $this->load->view('main.php');
    }

    /**
     *
     * @brief 创建生态企业
     * @details
     * -# 设置该企业管理员
     * @return null
     *
     */
    public function setCompanyAdmin(){
        $this->load->view('ecologycompany/createEcologyCompany3.php');

    }

    /**
     *
     * @brief 创建生态企业
     * @details
     * -# 设置本方参与的用户
     * @return null
     *
     */
    public function setSelfJoinUser(){
        $this->load->view('ecologycompany/createEcologyCompany4.php');
    }

    /**
     * setCompanyAdmin
     * @brief 生态企业企业信息页面
     * @details
     * -# 从UMS获得当前生态企业信息
     * -# 获得的全态企业信息分配到视图
     * @return null
     */
    public function ecologyInfoPage(){
        $this->load->view('ecologycompany/ecologyInfoPage.php');
    }

    /**
     * setCompanyAdmin
     * @brief 生态企业企业信息页面
     * @details
     * -# 查看员工的详细信息，并可以对员工信息进行编辑
     * -#
     * @return null
     */
    public function staffInfoPage(){
        $this->load->view('staff/staffInfo.php');
    }

    /**
     * setCompanyAdmin
     * @brief 生态企业企业信息页面
     * @details
     * -# 查看员工的职能详细信息，并可以对员工职能进行保存和还原设置
     * @return null
     */
    public function staffFunctionPage(){

        $this->load->view('staff/staffFunction.php');
    }



    /**
     * setCompanyAdmin
     * @brief 生态企业企业信息页面
     * @details
     * -# 增加生态企业合作员工
     * @return null
     */
    public function addEcologyStaffWindow(){
        $this->load->view('ecologycompany/addEcologyStaff.php');
    }

    /**
     *
     * @brief 生态企业企业信息保存
     * @details
     * -# 从表单获得生态企业信息
     * -# 对表单信息进行验证
     * -# 向UMS保存生态企业信息
     * @return null
     */
    public function saveEcologyInfo(){

    }

    /**
     *
     * @brief 生态企业企业权限页面
     * @details
     * -# 从BOSS获得生态企业权限
     * -# 将权限分配到视图
     * @return null
     */
    public function ecologyPowerPage(){
        //$this->load->view('ldap_import_Ecologycompany.php');
    }

    /**
     *
     * @brief 生态企业企业权限保存
     * @details
     * -# 获得企业权限
     * -# 保存企业权限到BOSS
     * @return null
     */
    public function saveEcologyPower(){
        //$this->load->view('ldap_import_Ecologycompany.php');
    }

    /**
     *
     * @brief 生态企业企业员工页面 [只有创建生态的上级管理员才能看到此员工员面，而生态企业自己是在自己的组织管理看员工信息]
     * @details
     * -# 通过UMS获得生态企业所有的员工信息
     * -# 将获得的员工信息分配到视图
     * -# 注意：如果生态企业关闭了，他的生态企业的员工，则生态企业自己不能再开启此员工
     * -# 注意：员工开通时，一定还需要把员工权限给BOSS
     * @return null
     */
    public function ecologyStaffPage(){
        //$this->load->view('ldap_import_Ecologycompany.php');
    }

    /**
     *
     * @brief 生态企业上级参与人页面
     * @details
     * -# 获得生态企业的本方参与人员
     * -# 从UC获得本方参与人标识
     * -# 根据本方参与人标识获得本方参与人员工信息
     * -# 将获得的员工信息分配到视图
     * -# 注意：员工开通时，一定还需要把员工权限给BOSS
     * @return null
     */
    public function ecologyActorPage(){
        //$this->load->view('ldap_import_Ecologycompany.php');
    }

    /**
     *
     * @brief 生态企业新加页面
     * @details
     * -# 填写生态企业信息
     * -# 设置生态企业权限
     * -# 设置该企业管理员
     * -# 设置本方参与的用户
     * -# js效验[各块切换时效验各自己的块]
     * -# 提交保存
     * @return null
     */
    public function addEcologyPage(){
        //$this->load->view('ldap_import_Ecologycompany.php');
    }

    /**
     *
     * @brief 保存新增生态企业
     * @details
     * -# 表单获得生态企业信息、生态企业权限、企业管理员、本方参与的用户
     * -# php效验
     * -# 保存生态企业信息到UMS，并返回生态企业ID
     * -# 保存生态企业权限到BOSS。
     * -# 保存生成企业员管理员到BOSS。同时保存到UC
     * -# 保存本方参与的用户到UC
     * @return null
     */
    public function saveEcology(){
        //$this->load->view('ldap_import_Ecologycompany.php');
    }

    /**
     *
     * @brief 生态企业管理员页面
     * @details
     * -# 加载生态企业管理员
     * -# 加载生态企业[注意包括他的下级管理员管理的生态企业]
     * -# 可以点一级一级展开，如果是系统管理员，则最上级为第一级生态管理员，如果是生态管理员，则是自己为第一级，点击再展开下级。
     * -# 注意：删除生态企业管理员时，生态企业管理员有生态企业时，不能删除，需要把他管理的生态企业管理员变更
     *         如果删除，他的下级生态企业管理员级数上移。
     * @return null
     */
    public function ecologyManagerPage(){
        //1生态企业管理员更改[只能选择一个]2多选[生态企业添加管理员]3调入员工4添加豁免员工
        $type_id = $this->uri->segment(3); 
        $type_id = empty_to_value($type_id,3);
        $data['type_id'] = $type_id;
        $this->load->view('ecologycompany/ecologyManagerPage.php',$data);
    }

    /**
     *
     * @brief 生态企业管理员树页面
     * @details
     * -# 根据当前企业生态管理员
     * -# 管理员按树级展，默认第一级，点击展开。
     * @return null
     */
    public function ecologyManagerTreePage(){
        //$this->load->view('ldap_import_Ecologycompany.php');
    }

    /**
     *
     * @brief 生态企业列表页面
     * @details
     * -# 根据左则点击的管理员标识
     * -# 注意包括他的下级管理员管理的生态企业
     * @return null
     */
    public function ecologylistPage(){
        //$this->load->view('ldap_import_Ecologycompany.php');
    }

    /**
     *
     * @brief 生态企业管理员新加页面
     * @details
     * -# 系统管理员可看到所有的生态企业管理员[一级级展开]
     * -# 生态企业管理员，可以看到自己下面的生态管理员
     * @return null
     */
    public function addEcologyManagerPage(){
        //$this->load->view('ldap_import_Ecologycompany.php');
    }

    /**
     *
     * @brief 保存新加生态企业管理员
     * @details
     * -# 在UC保存生态企业管理员
     * @return null
     */
    public function saveEcologyManagerPage(){
        //$this->load->view('ldap_import_Ecologycompany.php');
    }

    /**
     *
     * @brief 删除生态企业管理员
     * @details
     * -# 在UC删除生态企业管理员
     * -# 注意：删除生态企业管理员时，生态企业管理员有生态企业时，不能删除，需要把他管理的生态企业管理员变更
     *         如果删除，他的下级生态企业管理员级数上移。
     * @return null
     */
    public function delEcologyManager(){
        //$this->load->view('ldap_import_Ecologycompany.php');
    }

    /**
     *
     * @brief 生态企业标签页面
     * @details
     * -# 系统管理员，可以为生态企业，设置统一标签给生态企业使用
     * @return null
     */
    public function ecologyTagPage(){
        //$this->load->view('ldap_import_Ecologycompany.php');
    }

    /**
     *
     * @brief 保存生态企业标签页面
     * @details
     * -# 保存生态企业标签
     * @return null
     */
    public function saveEcologyTag(){
        //$this->load->view('ldap_import_Ecologycompany.php');
    }

    /**
     *
     * @brief 点击搜索后勾选后点击按钮删除生态企业弹窗提醒
     * @details
     * -# 删除生态企业
     * @return null
     */
    public function deleteEcologyCompany(){
        $this->load->view('ecologycompany/delEcologyCompany.php');
    }

    /**
     *
     * @brief 企业生态初始页
     * @details
     * -# 指定标签
     * @return null
     */
    public function appointPage(){
        $this->load->view('ecologycompany/appointPage.php');
    }
    /**
     *
     * @brief 企业生态列表
     * @details
     * -# 指定标签
     * @return null
     */
    public function ecologycompanylist(){
        $org_id=$this->input->post('org_id', TRUE);
       // echo $org_id;
        //die();
        if(!preg_match('/^[\d]+$/',$org_id)){
           form_json_msg('1','','参数有误！');//返回错误信息json格式
        }
        $in_arr = array(
            'user_id' => $org_id,//用户id
        );
        $ecologycompany_arr = $this->get_ecology_arr($in_arr);
        //echo json_encode($ecologycompany_arr);
        $data['ecology_arr'] = $ecologycompany_arr;
        $this->load->view('public/part/ecologycompanylist.php',$data);
    }
    

}