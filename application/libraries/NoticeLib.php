<?php if (!defined('BASEPATH'))	exit('No direct script access allowed');

/**
 * @class NoticeLib
 * @brief NoticeLib 类库，主要负责对邮件发送操作。
 * @file NoticeLib.php
 * @author zouyan <yan.zou@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
class NoticeLib{
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
     * @brief 保存消息： 
     * @details 
     * @param int  $type  1任务 2消息 3通知
     * @param array $classid_arr  类型id数组[逐层] array('7','1')
     * @param array $format_arr 格式数组[可以为空]；具体的看notice_class类
     * @param array $in_arr
           $type  1任务
            $in_arr = array(
                'customer_code' => $customer_code,//customer_code
                'org_id' => $org_id,//组织ID
                'site_id' => $site_id,//站点ID
                'operate_id' => $operate_id,//添加员工编号
            );
          $type   2消息 3通知
            $in_arr = array(
                'org_id' => $org_id,//组织ID
                'site_id' => $site_id,//站点ID
                'operate_id' => $operate_id,//添加员工编号
            ); 
    * 
     * @return array 插入成功的数组 空数组：失败
     *
     */
      public function set_notice($type = 0,$classid_arr = array(),$format_arr = array(),$in_arr = array()){  
        log_message('info', 'into method ' . __FUNCTION__ . '.');
        $CI =& get_instance();
        $CI->load->helper('my_publicfun');
        $CI->load->helper('my_dgmdate'); 
        $CI->load->library('API','','API');
        $re_ok_arr = array();//插入成功的数组
        switch ($type) {
            case 1://1任务 
//                $in_arr = array(
//                    'customer_code' => $customer_code,//customer_code
//                    'org_id' => $org_id,//组织ID
//                    'site_id' => $site_id,//站点ID
//                    'operate_id' => $operate_id,//添加员工编号
//                );
                $customer_code = arr_unbound_value($in_arr,'customer_code',2,'');//customer_code
                $org_id = arr_unbound_value($in_arr,'org_id',2,'');//组织ID
                $site_id = arr_unbound_value($in_arr,'site_id',2,'');//站点ID 
                $operate_id = arr_unbound_value($in_arr,'operate_id',2,'');//添加员工编号
                break;
            case 2: //2消息  
            case 3: //3通知  
//                $in_arr = array(
//                    'org_id' => $org_id,//组织ID
//                    'site_id' => $site_id,//站点ID
//                    'operate_id' => $operate_id,//添加员工编号
//                ); 
               $org_id = arr_unbound_value($in_arr,'org_id',2,'');//组织ID
               $site_id = arr_unbound_value($in_arr,'site_id',2,'');//站点ID
               $operate_id = arr_unbound_value($in_arr,'operate_id',2,'');//添加员工编号
               break;
            default:
               break;
        }
        include_once APPPATH . 'libraries/public/Notice_class.php';        
        $notice_obj = new Notice_class(array($type));        
        $ok_replaced_arr = $notice_obj->replaced_arr($classid_arr,$format_arr);
        $big_isopen = arr_unbound_value($ok_replaced_arr,'big_isopen',2,'');
        $isopen = arr_unbound_value($ok_replaced_arr,'isopen',2,'');
        if($big_isopen == 1 && $isopen == 1){
            $small_id = arr_unbound_value($ok_replaced_arr,'id',2,'');
            $big_id = arr_unbound_value($ok_replaced_arr,'big_id',2,'');        
            $big_name = arr_unbound_value($ok_replaced_arr,'big_name',2,'');
            $name = arr_unbound_value($ok_replaced_arr,'name',2,'');
            $ok_content = arr_unbound_value($ok_replaced_arr,'ok_content',2,'');
            $from_admin_idarr = arr_unbound_value($ok_replaced_arr,'from_admin_id',1,array());
            $to_admin_idarr = arr_unbound_value($ok_replaced_arr,'to_admin_id',1,array());
            if(isemptyArray($to_admin_idarr)){//如果不是空数组,如果没有，则发给系统管理员
                $to_admin_idarr = array(1);
            }
            //如果没有系统管理员，则加入系统管理员
            if(!deep_in_array(1, $to_admin_idarr)){
                $to_admin_idarr[] = 1;
            }
            if(!isemptyArray($to_admin_idarr)){//如果不是空数组
            	
                $CI->load->model('uc_notice_model'); //2消息  
                $CI->load->model('uc_message_model');//3通知
                $CI->load->model('uc_task_model'); 
                $CI->load->model('employee_change_task_model');//1任务
                $CI->load->model('uc_user_admin_model'); 
                //根据管理员类型id,获得管理员id信息
//                 $data_admin = array(  
//                    'select' =>'userID',
//                    'where' => array('state' => 1,'siteID' => $site_id , 'orgID' => $org_id ),
//                    'where_in' => array('role_id'=> $to_admin_idarr),
//                );
//                $admin_arr =  $CI->uc_user_admin_model->operateDB(2,$data_admin);
				$where_arr = array('state' => 1,'siteID' => $site_id , 'orgID' => $org_id );
				$where_in_arr = $to_admin_idarr;
				$admin_arr = $CI->uc_user_admin_model->get_userid($where_arr, $where_in_arr);
				log_message('info', var_export($admin_arr, true));
               if(!isemptyArray($admin_arr)){//如果不是空数组
                    foreach($admin_arr as $to_admin_arr){
                        $to_admin_id = arr_unbound_value($to_admin_arr,'userID',2,0);
                        //log_message('info', '6666');
                        if($to_admin_id > 0){
                            switch ($type) {
                                case 1://1任务 
                                    $task_arr = array(
                                        'customer_code' => $customer_code,//customer_code
                                        'org_id' => $org_id,//组织ID
                                        'applicant_user_id' => $operate_id,//添加员工编号
                                        //'recipient_user_id' => $to_admin_id,//消息接收者
                                        'site_id' => $site_id,//站点ID
                                        //'type' => $aaa,//1-add 2-transfer 3-delete
                                        //'status' => $aaa,//1客户端申请20管理员同意40管理员拒绝
                                        'task_info' => $ok_content,//内容
                                        'created' => dgmdate(time(), 'dt'),//添加时间戳
                                        //'modify' => dgmdate(time(), 'dt'),//修改时间戳
                                    );
                                    $insert_data = $task_arr;//保存数组（Json串）
                                     $insert_arr =  $CI->employee_change_task_model->insert_db($insert_data);
                                    break;
                                case 2: //2消息  
                                  $notice_arr = array(
                                      'org_id' => $org_id ,//组织ID
                                      'site_id' => $site_id,//站点ID
                                      'title' => $name,//名称
                                      'content' => $ok_content,//内容
                                      'isread' => 0,//是否已读0未读1已读
                                      'from_user_id' => $operate_id,//添加员工编号
                                      'addtime' => dgmdate(time(), 'dt'),//添加时间戳
                                      'to_user_id' => $to_admin_id,//消息接收者
                                  );
                                    $insert_data = $notice_arr ;//保存数组（Json串）
                                     $insert_arr =  $CI->uc_notice_model->insert_db($insert_data);
                                     log_message('info', '66666');
                                   break;
                                case 3: //3通知  
                                  $message_arr = array(
                                      'org_id' => $org_id ,//组织ID
                                      'site_id' => $site_id,//站点ID
                                      //'type' => $aaa,//1：名字的URL\n2：部门的URL\nURL在PHP中定义
                                      'content' => $ok_content,//被标记后的消息内容，不包含标记过的。
                                      'isread' => 0,//是否已读0未读1已读
                                      'from_user_id' => $operate_id,//添加员工编号
                                      'send_name' => COMPANY_MSG_SEND_NAME,//发送人
                                      'title' =>  $name,//标题
                                      'addtime' => dgmdate(time(), 'dt'),//添加时间戳
                                      'to_user_id' => $to_admin_id,//消息接收者
                                      //'url_content' => $aaa,//被标记的url的文字
                                      //'param' => $aaa,//参数
                                  );
                                    $insert_data = $message_arr;//保存数组（Json串）
                                     $insert_arr =  $CI->uc_message_model->insert_db($insert_data);
                                   break;
                                default:
                                   break;
                            } 
                            $requestId = 0;
                            if(db_operate_fail($insert_arr)){//失败
                                log_message('error', 'insert   fail.'); 

                            }else{
                                $requestId = isset($insert_arr['insert_id'])?$insert_arr['insert_id']:0;;
                                log_message('debug', 'insert   success.'); 
                            }
                            if($requestId > 0 ){
                                $re_ok_arr[] = $requestId;//插入成功的数组
                            }

                        }
                    }
               }

            }
        }
        return $re_ok_arr;
      }
      
}
