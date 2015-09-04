<?php if (!defined('BASEPATH'))	exit('No direct script access allowed');

/**
 * @class LogLib
 * @brief LogLib 类库，主要负责对邮件发送操作。
 * @file LogLib.php
 * @author zouyan <yan.zou@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
class LogLib{
    /**
     * 
     * @brief 构造函数
     * @details 
     * 特别说明：$CI不能入在构造函数中，因为当加载model时，在具体方法中使用时不成功，所以在每个方法中单独使用。
     */
    public function __construct() {      
        log_message('info', 'into class ' . __CLASS__ . '.');
    }
   /**
     *
     * @brief 保存消息： 
     * @details 
     * @param array $type_arr = array('1','2'),具体请查看log_class
     * @param array 
          $in_arr = 
            array(//常用参数数组
                'customerCode' => $this->p_customer_code,//客户编码
                'siteID' => $this->p_site_id,//站点id 
                'site_name' => $this->p_site_name,//站点名称 
                'accountId'=>$this->p_account_id,//分帐id ；注意：如果有用户，则是用户自己的
                'siteURL' => $this->p_stie_domain,//地址
                'contractId' => $this->p_contract_id,//合同id
                'operator_id' => $this->p_user_id,//操作发起人用户ID
                'client_ip' => $this->p_client_ip,//客户端ip
                'server_ip' => $this->p_server_ip,//服务端ip
                'oper_account' => $this->p_account,//操作帐号
                'oper_display_name' => $this->p_display_name,//操作姓名
                'orgID' => $this->p_org_id,//所属站点，分公司，生态企业组织id[各种管理员新加时，必填]
            );
             array(
                'Org_id' => $Org_id ,//组织ID
                'site_id' => $site_id ,//站点ID
                'operate_id' => $operate_id,//操作会员ID
                'login_name' => $login_name ,//操作账号[可以为空，没有，则重新获取]
                'display_name' => $display_name ,//操作姓名[可以为空，没有，则重新获取]
                'client_ip' => $client_ip ,//客户端ip
            );
     * @return int 插入成功的日志id    失败返回0 ，成功返回具体的id
     *
     */
      public function set_log($type_arr = array(),$in_arr = array()){  
        log_message('info', 'into method ' . __FUNCTION__ . '.');
        $CI =& get_instance();
        $CI->load->helper('my_publicfun');
        $CI->load->helper('my_dgmdate'); 
        $CI->load->library('API','','API');
        $re_ok_id = 0;//插入成功的日志id     
        include_once APPPATH . 'libraries/public/Log_class.php';        
        $log_obj = new Log_class();        
        $ok_replaced_arr = $log_obj->get_typ_log_arr($type_arr);  
        //log_message('info', var_export($ok_replaced_arr, true));
        $big_isopen = arr_unbound_value($ok_replaced_arr,'big_isopen',2,'');
        $isopen = arr_unbound_value($ok_replaced_arr,'isopen',2,'');
        log_message('info', '11111');
        if($big_isopen == 1 && $isopen == 1){
            $small_id = arr_unbound_value($ok_replaced_arr,'id',2,'');
            $big_id = arr_unbound_value($ok_replaced_arr,'big_id',2,'');        
            $big_name = arr_unbound_value($ok_replaced_arr,'big_name',2,'');
            $ok_content = arr_unbound_value($ok_replaced_arr,'content',2,'');
            $CI->load->model('uc_log_model'); //

            $Org_id = arr_unbound_value($in_arr,'orgID',2,'');//组织ID
            $site_id = arr_unbound_value($in_arr,'siteID',2,'');//站点ID  
            $operate_id = arr_unbound_value($in_arr,'operate_id',2,'');//操作会员ID
            $login_name = arr_unbound_value($in_arr,'oper_account',2,'');//操作账号[可以为空，没有，则重新获取]
            $display_name = arr_unbound_value($in_arr,'oper_display_name',2,'');//操作姓名[可以为空，没有，则重新获取] 
            $client_ip = arr_unbound_value($in_arr,'client_ip',2,'');//客户端ip
            $customerCode = arr_unbound_value($in_arr,'customerCode',2,'');//客户编码
            
            if(bn_is_empty($login_name) || bn_is_empty($display_name) ){//没有数据,重新获取
                $CI->load->library('StaffLib','','StaffLib');
                $re_user_arr = $CI->StaffLib->get_user_by_id($operate_id);
                $login_name = arr_unbound_value($re_user_arr,'loginName',2,'');//操作账号[可以为空，没有，则重新获取]
                $display_name = isset($re_user_arr['displayName'])?$re_user_arr['displayName']:'';//操作姓名[可以为空，没有，则重新获取] 
                log_message('info', 'xiaoxiao='.var_export($re_user_arr, true));
            }
            if(bn_is_empty($client_ip)){
                $CI->load->library('GetIP','','GetIP'); 
                $client_ip = $CI->GetIP->get_client_ip();
            }
            $log_arr = array(
                'customerCode' => $CI->p_customer_code,//客户编码
                'Org_id' => $CI->p_org_id ,//组织ID
                'site_id' => $CI->p_site_id ,//站点ID
                'user_id' => $CI->p_user_id ,//操作会员ID
                'login_name' => $CI->p_user_id ,//操作账号
                'display_name' => $display_name ,//操作姓名
                'log_type_id' => $big_id ,//日志活动类型ID
                'log_type_name' => $big_name ,//类型名称
                'log_content_id' => $small_id ,//log_content_id
                'log_content' => $ok_content ,//活动内容
                'ip' => $client_ip ,//IP地址
                'addtime' => dgmdate(time(), 'dt') ,//时间戳
            );
            log_message('info', 'log_arr=' . var_export($log_arr, true));
            $insert_data = $log_arr ;
           	$re_ok_id =  $CI->uc_log_model->addLog($insert_data);
        }
        log_message('info', 'xiaoxiao='.$re_ok_id);
        return $re_ok_id;
      }
      
      
}
