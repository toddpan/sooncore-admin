<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @class mss
 * @brief mss Controller，保存邮件表接口。
 * @details  
 * 
 * @file mss.php
 * @author zouyan <yan.zou@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
class mss extends  Mss_Controller{
    /**
     * @brief 构造函数：
     *
     */
    public function __construct() {
        parent::__construct();
        $this->load->helper('my_publicfun');
        $this->load->helper('my_dgmdate');
        log_message('info', 'into class ' . __CLASS__ . '.');
    }
    /**
      * 
      * @brief 保存邮件接口[将邮件保存数据库]      *  
      * @details 
      * @param $mss_json 传入要发送邮件的json串
      * @return int 是否成功保存邮件 {'code':1}0成功1失败  
      */
     public function save_mss() {
        log_message('info', 'into method ' . __FUNCTION__ . '.');
        $this->load->model('sme_mail_sending_task_model');
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
        $insert_db_arr =  $this->sme_mail_sending_task_model->insert_db($data_arr);
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
