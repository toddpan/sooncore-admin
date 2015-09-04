<?php if (!defined('BASEPATH'))	exit('No direct script access allowed');

/**
 * @class WebLib
 * @brief WebLib 类库，主要负责对站点公用方法。
 * @file WebLib.php
 * @author zouyan <yan.zou@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
class WebLib{  
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
      * @brief 管理员登陆后，根据客户编码和站点id，获得当前站点域的信息
      * @details 
      * @param string $customer_code 客户编码
      * @param string $site_id 站点id
      * @return array 域的数组
      */

    public function get_cluster($customer_code = '',$site_id = ''){     
        log_message('info', 'into method ' . __FUNCTION__ . '.');
        $CI =& get_instance();
        $CI->load->helper('my_publicfun');
        $CI->load->helper('my_dgmdate'); 
        $CI->load->library('API','','API');
        //先从session获得，没有再调用接口
        //$ucc_cluster = $_SESSION['ucc_cluster']; 
       // if(bn_is_empty($ucc_cluster)){//没有数据
             $CI->load->library('API','','API');
             $data = 'customer_code=' . $customer_code . '&siteid=' . $site_id;
             $ns_cluster_domain_arr = $CI->API->UCAPI($data,6,array('url' => UC_DOMAIN_URL ));
             $domain_cluster_arr = array();
             if(api_operate_fail($ns_cluster_domain_arr)){//失败
                 log_message('error', 'uc api api/allocation/get_cluster ' . $data . ' fail.');
             }else{                 
                 $domain_cluster_arr = arr_unbound_value($ns_cluster_domain_arr['other_msg'],'data',1,array());
                // $_SESSION['ucc_cluster'] = $domain_cluster_arr;
                 log_message('debug', ' uc api api/allocation/get_cluster ' . $data . ' success.'); 
             }
        //}

        //define('CLUSTER_URL',$ucc_cluster_url );//cluster_url;//分配的域url CLUSTER_URL
        return $domain_cluster_arr;
        
    }
}
