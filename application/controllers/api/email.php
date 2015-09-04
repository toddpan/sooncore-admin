<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @abstract 	email Controller，保存邮件表接口。
 * @filesource 	email.php
 * @author 		zouyan <yan.zou@quanshi.com>
 * @copyright 	Copyright (c) UC
 * @version 	v1.0
 */
class email extends  Email_Controller{
    /**
     * @abstract 构造函数
     */
    public function __construct() {
        parent::__construct();
        $this->load->helper('my_publicfun');
        $this->load->helper('my_dgmdate');
        log_message('info', 'into class ' . __CLASS__ . '.');
    }

    /** 
      * @abstract 保存正常的管理员邮件接口[将邮件保存数据库]
      * @param $mss_json 传入要发送邮件的json串
      * @return int 是否成功保存邮件 {'code':1}0成功1失败  
      */
     public function save_email() {
        log_message('info', 'into method ' . __FUNCTION__ . '.');
        
        $data_json =api_get_post();//$this->input->post('mss_json', TRUE); 
        write_test_file( '' . __FUNCTION__ . time() . '.txt' ,$data_json);
        if(bn_is_empty($data_json)){//如果是空
            $err_msg = ' param  mss_json is empty .';
            log_message('error', $err_msg); 
            echo api_json_msg(-1,array('msg' => $err_msg) , 0);
        }        
        $data_arr = json_decode($data_json, TRUE);
        if(isemptyArray($data_arr)){//如果是空数组
            $err_msg = ' param  $data_arr is empty array.';
            log_message('error', $err_msg); 
            echo api_json_msg(-1,array('msg' => $err_msg) , 0);
        }
        //1管理员帐号开通(试用版) 2管理员帐号开通(正式版)3一般用户帐号开通(正式版)
        $send_type = arr_unbound_value($data_arr,'send_type',2,''); 
        //对应要抽入到数据库中的数组
        $send_data = arr_unbound_value($data_arr,'send_data',1,array());
        if(bn_is_empty($send_type)){//如果是空
            $err_msg = ' param  $send_type is empty .';
            log_message('error', $err_msg); 
            echo api_json_msg(-1,array('msg' => $err_msg) , 0);  
        }
        if(isemptyArray($send_data)){//如果是空数组
            $err_msg = ' param  $send_data is empty array.';
            log_message('error', $err_msg); 
            echo api_json_msg(-1,array('msg' => $err_msg) , 0);
        }
        switch ($send_type) {
            case 1://1管理员帐号开通(试用版) 
                $this->load->model('trail_admin_active_model');
                $insert_db_arr =  $this->trail_admin_active_model->insert_db($send_data);                      
               break;
            case 2://2管理员帐号开通(正式版)
                $this->load->model('formal_admin_active_model');
                $insert_db_arr =  $this->formal_admin_active_model->insert_db($send_data);
                 break;
            case 3://3一般用户帐号开通(正式版)
                $this->load->model('formal_user_active_model');
                $insert_db_arr =  $this->formal_user_active_model->insert_db($send_data);                
                break;
            case 4://3重置密码
                $this->load->model('user_passwd_modify_model');
                $insert_db_arr =  $this->user_passwd_modify_model->insert_db($send_data);
                break;
            default:
              break;
        }

        if(!db_operate_fail($insert_db_arr)){//成功
            $err_msg = ' insert into   sme_mail_sending_task_model success.';
            log_message('error', $err_msg); 
            echo api_json_msg(0,array('msg' => $err_msg) , 1);
        }else{//失败                      
            $err_msg = ' insert into   sme_mail_sending_task_model fail.';
            log_message('error', $err_msg); 
            echo api_json_msg(-1,array('msg' => $err_msg) , 1);
        }
        log_message('info', 'out method ' . __FUNCTION__ . '.');  
     } 
}
