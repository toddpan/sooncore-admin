<?php if (!defined('BASEPATH'))	exit('No direct script access allowed');

/**
 * @class TestLib
 * @brief TestLib 类库，测试用。
 * @file TestLib.php
 * @author zouyan <yan.zou@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
class TestLib{
    public function aaa(){
        $CI =& get_instance();
        $CI->load->helper('my_publicfun');
        $CI->load->helper('my_dgmdate'); 
        $CI->load->library('API','','API');
        echo CLUSTER_URL;
        echo '<br/>';
        echo rand_str(array('length' => 8,'type' => '1,3,4,5'));
        
        
        $CI->load->library('MssLib','','MssLib');
        $ns_mss_user_arr = array(
            'id' => 5643,//用户id
            'site_name' => '北京奥的斯电梯有限公司',//站点名称
            'user_pwd' => '111111',//用户登陆密码                 
        );
        $mss_user_ids = '[5643]';
        $mss_user_arr = array();
        $mss_user_arr[] = $ns_mss_user_arr;
        $mss_in_arr = array(
            'mssuser_arr' => $mss_user_arr,
            'mss_user_ids' => $mss_user_ids,
            'type' => 2,//类型1管理员帐号开通(试用版)2管理员帐号开通(正式版)3一般用户帐号开通(正式版)
         );
         $mss_fail_arr = $CI->MssLib->send_user_email($mss_in_arr);
         print_r($mss_fail_arr);
         $mss_fail_user_arr = array();//发送邮件失败的数组
        //false失败 ;TRUE 成功[都成功] ;部分失败，返回失败数组
        if(is_array($mss_fail_arr)){//是数组
            foreach($mss_fail_arr as $ns_f_k => $ns_f_v){
               $mss_fail_user_arr[]= $ns_f_v;//返回失败数组 
            }
        }else{
            if(!$fail_arr){//都失败
                $mss_fail_user_arr = $mss_user_arr;
            }
        }
    }
   
}
