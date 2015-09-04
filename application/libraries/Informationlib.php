<?php if (!defined('BASEPATH'))	exit('No direct script access allowed');

/**
 * @class Informationlib
 * @brief Informationlib 类库，主要负责对外发送消息。
 * @file Informationlib.php
 * @author zouyan <yan.zou@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
include_once APPPATH . 'libraries/sdk/UcOrgMessage.php';
class Informationlib  {
    /**
     * 
     * @brief 构造函数
     * @details 
     * 特别说明：$CI不能入在构造函数中，因为当加载model时，在具体方法中使用时不成功，所以在每个方法中单独使用。
     */
    public function __construct() {
        
        //载入接口公用函数
       // include_once APPPATH . 'helpers/my_httpcurl_helper.php';
       // $CI =& get_instance();
       // $CI->load->helper('my_publicfun');
       // $CI->load->helper('my_dgmdate');        
        log_message('info', 'into class ' . __CLASS__ . '.');
    }  
    /**
     *
     * @brief 发送消息
     * @details
     * @param array $info_pre_arr 消息前信息
        $info_pre_arr = array(
            'from_user_id' => $aa,//消息发送者用户id
            'from_site_id' => $aa,//消息发送者站点id
            'to_user_id' => $aa,//消息接受者id[是组织时为组织id,是用户时，是用户id]
            'to_site_id' => $aa,//消息接受者站点id
            'is_group' => $aa,//是否为讨论组聊天1是[是组织] 0 否[是单个用户]   
            'msg_type' => $aa,//消息类型  1 - 组织变动
            'msg_id' => $aa,//1 - 部门名称变更    2 - 员工部门调动    3 - 职位调整  4 -员工入职 5 - 员工离职   10 - 部门删除  11 - 员工入职确认 12 - 员工离职确认 13 - 员工部门调动确认 14 - 员工入职拒绝消息 15 - 员工离职拒绝消息 16 - 员工部门调动拒绝消息 
        );
     * @param array $info_body 消息体
     * @return array 顶级组织数组[二维数组]
     *
     */
    public function send_info($info_pre_arr = array(),$info_body = array()){

        //13.1.1.部门名称变更消息
        log_message('debug', '$info_pre_arr=' . json_encode($info_pre_arr) . ' $info_body=' . json_encode($info_body) . '' );
        
        $CI = &get_instance();
		$CI->load->helper('my_publicfun');
        
        $uc_org_msg_obj = new UcOrgMessage();
        $from_user_id = arr_unbound_value($info_pre_arr,'from_user_id',2,''); //消息发送者用户id
        $from_site_id = arr_unbound_value($info_pre_arr,'from_site_id',2,'');//消息发送者站点id
        $to_user_id = arr_unbound_value($info_pre_arr,'to_user_id',2,'');//$this->p_user_id;//消息接受者id[是组织时为组织id,是用户时，是用户id]
        $to_site_id = arr_unbound_value($info_pre_arr,'to_site_id',2,'');//消息接受者站点id
        $is_group= arr_unbound_value($info_pre_arr,'is_group',2,'');//是否为讨论组聊天     1 - 是[是组织]    0 - 否[是单个用户]   
        $msg_type = arr_unbound_value($info_pre_arr,'msg_type',2,'');//消息类型  1 - 组织变动
        $msg_id = arr_unbound_value($info_pre_arr,'msg_id',2,'');//1 - 部门名称变更    2 - 员工部门调动    3 - 职位调整  4 -员工入职 5 - 员工离职   10 - 部门删除  11 - 员工入职确认 12 - 员工离职确认 13 - 员工部门调动确认 14 - 员工入职拒绝消息 15 - 员工离职拒绝消息 16 - 员工部门调动拒绝消息 
        $content_type = arr_unbound_value($info_pre_arr,'content_type',2,''); //消息体类型           1 - mime  2 - 二进制
        // 1 - 部门名称变更  
        /*$body = array(//消息体               发送每类消息对应的消息内容按照消息协议定义的字段赋值
            'operator_id' => $this->p_user_id,//操作发起人用户ID
            'dept_id' => $org_id,//部门ID
            'old_dept_name' => $org_name . 'old',//原部门名称
            'new_dept_name' => $org_name,//新部门名称
            //'desc' => '',//消息描述
        ); 
         * 
         */ 
         // 2 - 员工部门调动    
         // 3 - 职位调整  
         // 4 -员工入职 
         // 5 - 员工离职   
         // 10 - 部门删除  
         // 11 - 员工入职确认 
         // 12 - 员工离职确认 
         // 13 - 员工部门调动确认 
         // 14 - 员工入职拒绝消息 
         // 15 - 员工离职拒绝消息 
         // 16 - 员工部门调动拒绝消息                    
        $body = $info_body;//消息体         
        $re_msg = $uc_org_msg_obj->orgMsgSend($from_user_id, $from_site_id, $to_user_id, $to_site_id, $msg_type, $msg_id, $body, $is_group, $content_type);
//        print_r($re_msg);
//        die();
        log_message('info', '$msg_type ' . $msg_type . var_export($re_msg, true)); 
       
    }
    /**
     *
     * @brief 发送消息
     * @details
     * @param array 
            $sys_arr = array(
                 'customerCode' => $aaa,//客户编码
                 'siteID' => $aaa,//站点id 
                 'site_name' => $aaa,//站点名称 
                 'accountId'=>$aaa,//分帐id ；注意：如果有用户，则是用户自己的
                 'siteURL' => $aaa,//地址
                 'contractId' => $aaa,//合同id
                 'operator_id' => $this->p_user_id,//操作发起人用户ID
                 'client_ip' => $this->p_client_ip,//客户端ip
                 'server_ip' => $this->p_server_ip,//服务端ip
                 'oper_account' => $this->p_account,//操作帐号
                 'oper_display_name' => $this->p_display_name,//操作姓名
                 'orgID' => $this->p_org_id,//所属站点，分公司，生态企业组织id[各种管理员新加时，必填]
             ),
     * @param array $info_arry 
        $info_arry = array(
            'msg_id' => $msg_id,//消息类型2 - 员工部门调动    3 - 职位调整  4 -员工入职 5 - 员工离职   10 - 部门删除  11 - 员工入职确认 12 - 员工离职确认 13 - 员工部门调动确认 14 - 员工入职拒绝消息 15 - 员工离职拒绝消息 16 - 员工部门调动拒绝消息 
            'msg_arr' => $msg_arr,//不同消息内空数组
        );
     * @return boolean 成功true 失败false
     *
     */
    public function send_ing($sys_arr = array(),$info_arry = array()){
        log_message('info', 'into method ' . __FUNCTION__ . '.');
        log_message('info', ' $sys_arr =' . any_to_str($sys_arr). ' .');
        log_message('info', ' $info_arry =' . any_to_str($info_arry). ' .');
        $CI =& get_instance();
        $CI->load->helper('my_publicfun');
        $CI->load->helper('my_dgmdate'); 
        $CI->load->library('API','','API'); 

        if(isemptyArray($sys_arr) ||  isemptyArray($info_arry)){//空数组
            return false;
        }
        $msg_id = arr_unbound_value($info_arry,'msg_id',2,'');
        $msg_arr = arr_unbound_value($info_arry,'msg_arr',1,array());
         if(isemptyArray($msg_arr)){
             return false; 
         }
        $fun_customerCode = arr_unbound_value($sys_arr,'customerCode',2,'');//客户编码
        $fun_siteID = arr_unbound_value($sys_arr,'siteID',2,'');//站点id 
        $fun_site_name = arr_unbound_value($sys_arr,'site_name',2,'');//站点名称 
        $fun_accountId = arr_unbound_value($sys_arr,'accountId',2,'');//分帐id ；注意：如果有用户，则是用户自己的
        $fun_siteURL = arr_unbound_value($sys_arr,'siteURL',2,'');//地址
        $fun_contractId = arr_unbound_value($sys_arr,'contractId',2,'');//合同id
        $fun_operator_id = arr_unbound_value($sys_arr,'operator_id',2,'');//操作发起人用户ID
        $fun_client_ip = arr_unbound_value($sys_arr,'client_ip',2,'');//客户端ip
        $fun_server_ip = arr_unbound_value($sys_arr,'server_ip',2,'');//服务端ip
        $fun_oper_account = arr_unbound_value($sys_arr,'oper_account',2,'');//操作帐号
        $fun_oper_display_name = arr_unbound_value($sys_arr,'oper_display_name',2,'');//操作姓名
        $fun_orgID = arr_unbound_value($sys_arr,'orgID',2,'');//所属站点，分公司，生态企业组织id[各种管理员新加时，必填]
        
        switch ($msg_id) {
            case 1://1 - 部门名称变更   

                break;
            case 2:   // 2 - 员工部门调动   
//                $msg_arr = array(
//                    'user_id' => $aaa,//用户id
//                    'new_org_id' => $aa,//新组织id
//                    'old_org_id' => $aa,//旧组织id
//                );

                $ns_user_id = arr_unbound_value($msg_arr,'user_id',2,'');                
                $ns_new_org_id = arr_unbound_value($msg_arr,'new_org_id',2,'');    
                $ns_old_org_id = arr_unbound_value($msg_arr,'old_org_id',2,'');     
                if(!preg_match('/^[\d]+$/',$ns_user_id)){
                    return false;
                } 
                $CI->load->library('StaffLib','','StaffLib');
                $CI->load->library('OrganizeLib','','OrganizeLib'); 
                
                $re_user_arr = $CI->StaffLib->get_user_by_id($ns_user_id);   
                if(isemptyArray($re_user_arr)){//如果是空数组
                    return false;
                }
                $ns_user_name = arr_unbound_value($re_user_arr,'displayName',2,'');
                
                $new_org_arr = $CI->OrganizeLib->get_org_by_id($ns_new_org_id); 
                if(isemptyArray($new_org_arr)){//如果是空数组
                    return false;
                }
                $ns_new_org_name = arr_unbound_value($new_org_arr,'name',2,'');
                
                $old_org_arr = $CI->OrganizeLib->get_org_by_id($ns_old_org_id);
                if(isemptyArray($old_org_arr)){//如果是空数组
                    return false;
                }
                $ns_old_org_name = arr_unbound_value($old_org_arr,'name',2,'');
                
                $info_pre_arr = array(
                    'from_user_id' => $fun_operator_id,//$this->p_user_id,//消息发送者用户id
                    'from_site_id' => $fun_siteID,//$this->p_site_id,//消息发送者站点id
                    'to_user_id' => $ns_user_id,//$ns_new_org_id,//消息接受者id[是组织时为组织id,是用户时，是用户id]
                    'to_site_id' => $fun_siteID,//$this->p_site_id,//消息接受者站点id
                    'is_group' => 0,//是否为讨论组聊天1是[是组织] 0 否[是单个用户]   
                    'msg_type' => 1,//消息类型  1 - 组织变动
                    'msg_id' => 2,//1 - 部门名称变更    2 - 员工部门调动    3 - 职位调整  4 -员工入职 5 - 员工离职   10 - 部门删除  11 - 员工入职确认 12 - 员工离职确认 13 - 员工部门调动确认 14 - 员工入职拒绝消息 15 - 员工离职拒绝消息 16 - 员工部门调动拒绝消息 
                );                     
                $info_body = array(
                    'operator_id' => $fun_operator_id,//$this->p_user_id,//操作发起人用户ID
                    'user_id' => $ns_user_id,//用户ID
                    'user_name' => $ns_user_name ,//用户姓名
                    'dept_id' => $ns_new_org_id,//新部门ID
                    'old_dept_name' => $ns_old_org_name,//旧部门名称
                    'dept_name' => $ns_new_org_name,//新部门名称
                    'desc' => '',//消息描述
                );  
                log_message('info', 'into class ' . json_encode($info_pre_arr) . json_encode($info_body) . '.');
                $this->send_info($info_pre_arr,$info_body);
               break;
            case 3:    //3 - 职位调整 
//                $msg_arr = array(
//                    'user_id' => $aaa,//用户id
//                    'new_displayName' => $aa,//用户姓名
//                    'new_position' => $aa,//新职位名称
//                    'old_position' => $aa,//旧职位名称
//                    'dept_name' => $aa,//职位所在部门名称
//                );

                //发送6员工权限变更消息
                $user_id = arr_unbound_value($msg_arr,'user_id',2,'');
                
                $new_displayName = arr_unbound_value($msg_arr,'new_displayName',2,'');                
                $new_position = arr_unbound_value($msg_arr,'new_position',2,'');              
                $old_position = arr_unbound_value($msg_arr,'old_position',2,'');              
                $new_organizationName = arr_unbound_value($msg_arr,'dept_name',2,'');                 
                if(!preg_match('/^[\d]+$/',$user_id)){
                    return false;
                }  
                
                $info_pre_arr = array(
                    'from_user_id' => $fun_operator_id,//$sys_user_id,//消息发送者用户id
                    'from_site_id' => $fun_siteID,//$site_id,//消息发送者站点id
                    'to_user_id' => $user_id,//消息接受者id[是组织时为组织id,是用户时，是用户id]
                    'to_site_id' => $fun_siteID,//$site_id,//消息接受者站点id
                    'is_group' => 0,//是否为讨论组聊天1是[是组织] 0 否[是单个用户]   
                    'msg_type' => 1,//消息类型  1 - 组织变动
                    'msg_id' => 3,//1 - 部门名称变更    2 - 员工部门调动    3 - 职位调整  4 -员工入职 5 - 员工离职  6 - 员工权限变更 10 - 部门删除  11 - 员工入职确认 12 - 员工离职确认 13 - 员工部门调动确认 14 - 员工入职拒绝消息 15 - 员工离职拒绝消息 16 - 员工部门调动拒绝消息 
                );                     
                $info_body = array(
                    'operator_id' => $fun_operator_id,//$sys_user_id,//操作发起人用户ID
                    'user_id' => $user_id,//用户ID
                    'user_name' => $new_displayName ,//用户姓名
                    'new_position' => $new_position,//新职位名称
                    'old_position' => $old_position,//旧职位名称
                    'dept_name' => $new_organizationName,//职位所在部门名称
                    'desc' => '',//消息描述
                );  
                $this->send_info($info_pre_arr,$info_body);
               break;
            case 4:      // 4 -员工入职                        
               break;
            case 5:  //5 - 员工离职                             
               break;
            case 6:  //6员工权限变更消息
//                $msg_arr = array(
//                    'user_id' => $aaa,//用户id
//                    'org_id' => $org_id,//组织id
//                );

                //发送6员工权限变更消息
                $user_id = arr_unbound_value($msg_arr,'user_id',2,'');
                $org_id = arr_unbound_value($msg_arr,'org_id',2,'');
//                if(bn_is_empty($user_id) || bn_is_empty($org_id)){//没有数据
//                    return false;
//                }
                if( (!preg_match('/^[\d]+$/',$user_id)) || (!preg_match('/^[\d]+$/',$org_id)) ){
                    return false;
                }
                
                $CI->load->library('OrganizeLib','','OrganizeLib');
                $CI->load->library('StaffLib','','StaffLib'); 
                
                $user_arr = $CI-> StaffLib->get_user_by_id($user_id);
                $ns_user_name = arr_unbound_value($user_arr,'displayName',2,'');
                
                $org_arr = $CI-> OrganizeLib->get_org_by_id($org_id);
                $org_name = arr_unbound_value($org_arr,'name',2,'');
                $info_pre_arr = array(
                    'from_user_id' => $fun_operator_id,//$this->p_user_id,//消息发送者用户id
                    'from_site_id' => $fun_siteID,//$this->p_site_id,//消息发送者站点id
                    'to_user_id' => $org_id,//消息接受者id[是组织时为组织id,是用户时，是用户id]
                    'to_site_id' => $fun_siteID,//$this->p_site_id,//消息接受者站点id
                    'is_group' => 1,//是否为讨论组聊天1是[是组织] 0 否[是单个用户]   
                    'msg_type' => 1,//消息类型  1 - 组织变动
                    'msg_id' => 6,//1 - 部门名称变更    2 - 员工部门调动    3 - 职位调整  4 -员工入职 5 - 员工离职  6 - 员工权限变更 10 - 部门删除  11 - 员工入职确认 12 - 员工离职确认 13 - 员工部门调动确认 14 - 员工入职拒绝消息 15 - 员工离职拒绝消息 16 - 员工部门调动拒绝消息 
                );                     
                $info_body = array(
                    'operator_id' => $fun_operator_id,//$this->p_user_id,//操作发起人用户ID
                    'user_id' => $user_id,//员工用户ID
                    'user_name' => $ns_user_name ,//员工姓名
                    'dept_name' => $org_name,//员工部门名称
                    'dept_id' => $org_id,//用户权限变更部门ID
                    'desc' => '',//消息描述
                ); 
                $this->send_info($info_pre_arr,$info_body);
               break;
            case 7:                             
               break;
            case 8:                             
               break;
            case 9:                             
               break;
            case 10:   // 10 - 部门删除  
               break;
            case 11:    //11 - 员工入职确认   系统管理员完成操作，部门管理者接收消息，用户当事人不需要接收任何消息
//                $msg_arr = array(
//                    'user_id' => $aaa,//用户id
//                );

                //发送6员工权限变更消息
                $user_id = arr_unbound_value($msg_arr,'user_id',2,'');
                if(!preg_match('/^[\d]+$/',$user_id)){
                     return false;
                }
                
                $CI->load->library('OrganizeLib','','OrganizeLib');
                $CI->load->library('StaffLib','','StaffLib'); 
                
                $user_arr = $CI-> StaffLib->get_user_by_id($user_id);
                $ns_user_name = arr_unbound_value($user_arr,'displayName',2,'');
                $org_id = arr_unbound_value($user_arr,'organizationId',2,'');
                $org_name = arr_unbound_value($user_arr,'organizationName',2,'');
                //获得当前站点的组织管理者
                $CI->load->model('uc_org_manager_model'); 
                $ns_org_manager_id = $CI-> uc_org_manager_model ->get_org_manager_byorgid($org_id);
                if($ns_org_manager_id > 0 ){
                   
                    $info_pre_arr = array(
                        'from_user_id' => $fun_operator_id,//$this->p_user_id,//消息发送者用户id
                        'from_site_id' => $fun_siteID,//$this->p_site_id,//消息发送者站点id
                        'to_user_id' => $ns_org_manager_id,//消息接受者id[是组织时为组织id,是用户时，是用户id]
                        'to_site_id' => $fun_siteID,//$this->p_site_id,//消息接受者站点id
                        'is_group' => 0,//是否为讨论组聊天1是[是组织] 0 否[是单个用户]   
                        'msg_type' => 1,//消息类型  1 - 组织变动
                        'msg_id' => 11,//1 - 部门名称变更    2 - 员工部门调动    3 - 职位调整  4 -员工入职 5 - 员工离职  6 - 员工权限变更 10 - 部门删除  11 - 员工入职确认 12 - 员工离职确认 13 - 员工部门调动确认 14 - 员工入职拒绝消息 15 - 员工离职拒绝消息 16 - 员工部门调动拒绝消息 
                    );                     
                    $info_body = array(
                        'operator_id' => $fun_operator_id,//$this->p_user_id,//操作发起人用户ID
                        'dept_id' => $org_id,//入职部门ID
                        'user_name' => $ns_user_name ,//员工姓名
                        'dept_name' => $org_name,//员工部门名称
                        'user_id' => $user_id,//入职员工ID
                        'desc' => '',//消息描述
                    ); 
                    $this->send_info($info_pre_arr,$info_body);
                }
               break;
            case 12:   //12 - 员工离职确认 系统管理员完成操作，部门管理者接收消息，用户当事人不需要接收任何消息。
//                $msg_arr = array(
//                    'user_id' => $aaa,//用户id
//                );

                //发送6员工权限变更消息
                $user_id = arr_unbound_value($msg_arr,'user_id',2,'');
                if(!preg_match('/^[\d]+$/',$user_id)){
                     return false;
                }
                
                $CI->load->library('OrganizeLib','','OrganizeLib');
                $CI->load->library('StaffLib','','StaffLib'); 
                
                $user_arr = $CI-> StaffLib->get_user_by_id($user_id);
                $ns_user_name = arr_unbound_value($user_arr,'displayName',2,'');
                $org_id = arr_unbound_value($user_arr,'organizationId',2,'');
                $org_name = arr_unbound_value($user_arr,'organizationName',2,'');
                //获得当前站点的组织管理者
                $CI->load->model('uc_org_manager_model'); 
                $ns_org_manager_id = $CI-> uc_org_manager_model ->get_org_manager_byorgid($org_id);
                if($ns_org_manager_id > 0 ){
                   
                    $info_pre_arr = array(
                        'from_user_id' => $fun_operator_id,//$this->p_user_id,//消息发送者用户id
                        'from_site_id' => $fun_siteID,//$this->p_site_id,//消息发送者站点id
                        'to_user_id' => $ns_org_manager_id,//消息接受者id[是组织时为组织id,是用户时，是用户id]
                        'to_site_id' => $fun_siteID,//$this->p_site_id,//消息接受者站点id
                        'is_group' => 0,//是否为讨论组聊天1是[是组织] 0 否[是单个用户]   
                        'msg_type' => 1,//消息类型  1 - 组织变动
                        'msg_id' => 12,//1 - 部门名称变更    2 - 员工部门调动    3 - 职位调整  4 -员工入职 5 - 员工离职  6 - 员工权限变更 10 - 部门删除  11 - 员工入职确认 12 - 员工离职确认 13 - 员工部门调动确认 14 - 员工入职拒绝消息 15 - 员工离职拒绝消息 16 - 员工部门调动拒绝消息 
                    );                     
                    $info_body = array(
                        'operator_id' => $fun_operator_id,//$this->p_user_id,//操作发起人用户ID
                        'user_id' => $user_id,//员工用户ID
                        'dept_id' => $org_id,//入职部门ID
                        'user_name' => $ns_user_name ,//员工姓名
                        'dept_name' => $org_name,//员工部门名称
                        'desc' => '',//消息描述
                    ); 
                    $this->send_info($info_pre_arr,$info_body);
                }
               break;
            case 13:  // 13 - 员工部门调动确认 系统管理员完成操作，部门管理者接收消息，用户当事人收到调岗完成消息     
//                $msg_arr = array(
//                    'user_id' => $aaa,//用户id
//                );

                //发送6员工权限变更消息
                $user_id = arr_unbound_value($msg_arr,'user_id',2,'');
                if(!preg_match('/^[\d]+$/',$user_id)){
                     return false;
                }
                
                $CI->load->library('OrganizeLib','','OrganizeLib');
                $CI->load->library('StaffLib','','StaffLib'); 
                
                $user_arr = $CI-> StaffLib->get_user_by_id($user_id);
                $ns_user_name = arr_unbound_value($user_arr,'displayName',2,'');
                $org_id = arr_unbound_value($user_arr,'organizationId',2,'');
                $org_name = arr_unbound_value($user_arr,'organizationName',2,'');
                //获得当前站点的组织管理者
               // $CI->load->model('uc_org_manager_model'); 
                //$ns_org_manager_id = $CI-> uc_org_manager_model ->get_org_manager_byorgid($org_id);
                //if($ns_org_manager_id > 0 ){
                   
                    $info_pre_arr = array(
                        'from_user_id' => $fun_operator_id,//$this->p_user_id,//消息发送者用户id
                        'from_site_id' => $fun_siteID,//$this->p_site_id,//消息发送者站点id
                        'to_user_id' => $org_id,//消息接受者id[是组织时为组织id,是用户时，是用户id]
                        'to_site_id' => $fun_siteID,//$this->p_site_id,//消息接受者站点id
                        'is_group' => 1,//是否为讨论组聊天1是[是组织] 0 否[是单个用户]   
                        'msg_type' => 1,//消息类型  1 - 组织变动
                        'msg_id' => 13,//1 - 部门名称变更    2 - 员工部门调动    3 - 职位调整  4 -员工入职 5 - 员工离职  6 - 员工权限变更 10 - 部门删除  11 - 员工入职确认 12 - 员工离职确认 13 - 员工部门调动确认 14 - 员工入职拒绝消息 15 - 员工离职拒绝消息 16 - 员工部门调动拒绝消息 
                    );                     
                    $info_body = array(
                        'operator_id' => $fun_operator_id,//$this->p_user_id,//操作发起人用户ID
                        'user_id' => $user_id,//员工用户ID
                        //'dept_id' => $org_id,//入职部门ID
                        'user_name' => $ns_user_name ,//员工姓名
                        'dept_name' => $org_name,//员工部门名称
                        'desc' => '',//消息描述
                    ); 
                    $this->send_info($info_pre_arr,$info_body);
               // }
               break;
            case 14:   //14 - 员工入职拒绝消息 系统管理员完成操作，部门管理者接收消息，用户当事人不需要接收任何消息。
//                $msg_arr = array(
//                    'org_id' => $aaa,//当前组织id
//                    'user_name' => $aaa,//入职员工的姓名
//                );

                //发送6员工权限变更消息
                $org_id = arr_unbound_value($msg_arr,'org_id',2,'');
                if(!preg_match('/^[\d]+$/',$org_id)){
                     return false;
                }
                $ns_user_name = arr_unbound_value($msg_arr,'user_name',2,'');
                
                $CI->load->library('OrganizeLib','','OrganizeLib');
                $CI->load->library('StaffLib','','StaffLib'); 
                
                //$user_arr = $CI-> StaffLib->get_user_by_id($user_id);
                //$ns_user_name = arr_unbound_value($user_arr,'displayName',2,'');
                 
                $re_arr = $CI->OrganizeLib->get_org_by_id($org_id);
                //$org_id = arr_unbound_value($re_arr,'organizationId',2,'');
                $org_name = arr_unbound_value($re_arr,'name',2,'');
                //获得当前站点的组织管理者
                $CI->load->model('uc_org_manager_model'); 
                $ns_org_manager_id = $CI-> uc_org_manager_model ->get_org_manager_byorgid($org_id);
                if($ns_org_manager_id > 0 ){
                   
                    $info_pre_arr = array(
                        'from_user_id' => $fun_operator_id,//$this->p_user_id,//消息发送者用户id
                        'from_site_id' => $fun_siteID,//$this->p_site_id,//消息发送者站点id
                        'to_user_id' => $ns_org_manager_id,//消息接受者id[是组织时为组织id,是用户时，是用户id]
                        'to_site_id' => $fun_siteID,//$this->p_site_id,//消息接受者站点id
                        'is_group' => 0,//是否为讨论组聊天1是[是组织] 0 否[是单个用户]   
                        'msg_type' => 1,//消息类型  1 - 组织变动
                        'msg_id' => 14,//1 - 部门名称变更    2 - 员工部门调动    3 - 职位调整  4 -员工入职 5 - 员工离职  6 - 员工权限变更 10 - 部门删除  11 - 员工入职确认 12 - 员工离职确认 13 - 员工部门调动确认 14 - 员工入职拒绝消息 15 - 员工离职拒绝消息 16 - 员工部门调动拒绝消息 
                    );                     
                    $info_body = array(
                        'operator_id' => $fun_operator_id,//$this->p_user_id,//操作发起人用户ID
                        //'user_id' => $user_id,//入职员工ID
                        'dept_id' => $org_id,//入职部门ID
                        'user_name' => $ns_user_name ,//员工姓名
                        'dept_name' => $org_name,//员工部门名称
                        'desc' => '',//消息描述
                    ); 
                    $this->send_info($info_pre_arr,$info_body);
                }
               break;
            case 15: // 15 - 员工离职拒绝消息  系统管理员完成操作，部门管理者接收消息，用户当事人不需要接收任何消息。 
//                $msg_arr = array(
//                    'user_id' => $aaa,//用户id
//                );

                //发送6员工权限变更消息
                $user_id = arr_unbound_value($msg_arr,'user_id',2,'');
                if(!preg_match('/^[\d]+$/',$user_id)){
                     return false;
                }
                
                $CI->load->library('OrganizeLib','','OrganizeLib');
                $CI->load->library('StaffLib','','StaffLib'); 
                
                $user_arr = $CI-> StaffLib->get_user_by_id($user_id);
                $ns_user_name = arr_unbound_value($user_arr,'displayName',2,'');
                $org_id = arr_unbound_value($user_arr,'organizationId',2,'');
                $org_name = arr_unbound_value($user_arr,'organizationName',2,'');
                //获得当前站点的组织管理者
                $CI->load->model('uc_org_manager_model'); 
                $ns_org_manager_id = $CI-> uc_org_manager_model ->get_org_manager_byorgid($org_id);
                if($ns_org_manager_id > 0 ){
                   
                    $info_pre_arr = array(
                        'from_user_id' => $fun_operator_id,//$this->p_user_id,//消息发送者用户id
                        'from_site_id' => $fun_siteID,//$this->p_site_id,//消息发送者站点id
                        'to_user_id' => $ns_org_manager_id,//消息接受者id[是组织时为组织id,是用户时，是用户id]
                        'to_site_id' => $fun_siteID,//$this->p_site_id,//消息接受者站点id
                        'is_group' => 0,//是否为讨论组聊天1是[是组织] 0 否[是单个用户]   
                        'msg_type' => 1,//消息类型  1 - 组织变动
                        'msg_id' => 15,//1 - 部门名称变更    2 - 员工部门调动    3 - 职位调整  4 -员工入职 5 - 员工离职  6 - 员工权限变更 10 - 部门删除  11 - 员工入职确认 12 - 员工离职确认 13 - 员工部门调动确认 14 - 员工入职拒绝消息 15 - 员工离职拒绝消息 16 - 员工部门调动拒绝消息 
                    );                     
                    $info_body = array(
                        'operator_id' => $fun_operator_id,//$this->p_user_id,//操作发起人用户ID
                        'user_id' => $user_id,//员工用户ID
                        'dept_id' => $org_id,//入职部门ID
                        'user_name' => $ns_user_name ,//员工姓名
                        'dept_name' => $org_name,//员工部门名称
                        'desc' => '',//消息描述
                    ); 
                    $this->send_info($info_pre_arr,$info_body);
                }
               break;
            case 16:   //16 - 员工部门调动拒绝消息     系统管理员完成操作，部门管理者接收消息，用户当事人收到调岗完成消息  
//                $msg_arr = array(
//                    'user_id' => $aaa,//用户id
//                );

                //发送6员工权限变更消息
                $user_id = arr_unbound_value($msg_arr,'user_id',2,'');
                if(!preg_match('/^[\d]+$/',$user_id)){
                     return false;
                }
                
                $CI->load->library('OrganizeLib','','OrganizeLib');
                $CI->load->library('StaffLib','','StaffLib'); 
                
                $user_arr = $CI-> StaffLib->get_user_by_id($user_id);
                $ns_user_name = arr_unbound_value($user_arr,'displayName',2,'');
                $org_id = arr_unbound_value($user_arr,'organizationId',2,'');
                $org_name = arr_unbound_value($user_arr,'organizationName',2,'');
                //获得当前站点的组织管理者
               // $CI->load->model('uc_org_manager_model'); 
                //$ns_org_manager_id = $CI-> uc_org_manager_model ->get_org_manager_byorgid($org_id);
                //if($ns_org_manager_id > 0 ){
                   
                    $info_pre_arr = array(
                        'from_user_id' => $fun_operator_id,//$this->p_user_id,//消息发送者用户id
                        'from_site_id' => $fun_siteID,//$this->p_site_id,//消息发送者站点id
                        'to_user_id' => $org_id,//消息接受者id[是组织时为组织id,是用户时，是用户id]
                        'to_site_id' => $fun_siteID,//$this->p_site_id,//消息接受者站点id
                        'is_group' => 1,//是否为讨论组聊天1是[是组织] 0 否[是单个用户]   
                        'msg_type' => 1,//消息类型  1 - 组织变动
                        'msg_id' => 16,//1 - 部门名称变更    2 - 员工部门调动    3 - 职位调整  4 -员工入职 5 - 员工离职  6 - 员工权限变更 10 - 部门删除  11 - 员工入职确认 12 - 员工离职确认 13 - 员工部门调动确认 14 - 员工入职拒绝消息 15 - 员工离职拒绝消息 16 - 员工部门调动拒绝消息 
                    );                     
                    $info_body = array(
                        'operator_id' => $fun_operator_id,//$this->p_user_id,//操作发起人用户ID
                        'user_id' => $user_id,//员工用户ID
                        //'dept_id' => $user_id,//入职部门ID
                        'user_name' => $ns_user_name ,//员工姓名
                        'dept_name' => $org_name,//员工部门名称
                        'desc' => '',//消息描述
                    ); 
                    $this->send_info($info_pre_arr,$info_body);
               // }
               break;
            default:
               break;
        }
        return true;
    }
    
}
