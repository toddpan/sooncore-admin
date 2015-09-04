<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @class domain
 * @brief domain Controller，域分配表接口。
 * @details  
 * 只作对域相关表的操作，不能调有其它接口
 * @file Response.php
 * @author zouyan <yan.zou@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
class domain extends  Cluster_Controller{
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
      * @brief 根据当前域的ip地址，获得当前域的url
      * @details 
      * @param string $ip 需要查询当前域的ip
      * @return array 当前域的信息数组
      */
     public function get_cluster_arr() {
         log_message('info', 'into method ' . __FUNCTION__ . '.');
         $ip = $this->input->post('ip', TRUE);//BOSS传递过来的数据;如果是批量导入，数据
         if(bn_is_empty($ip)){                
             log_message('error', 'post param  $ip is empty.'); 
             form_json_msg('1','','post param  $ip is empty',array());//返回错误信息json格式
        }
        $this->load->model('uc_cluster_user_num_model');        
        $sel_field = 'clusterID,ip,url';
        $where_arr = array(
                'ip' => $ip,                       
            );
       $sel_arr = $this->uc_cluster_user_num_model->get_db_arr($where_arr,$sel_field);
       $sel_arr['code'] = 0;
       echo json_encode($sel_arr);       
     }
}
