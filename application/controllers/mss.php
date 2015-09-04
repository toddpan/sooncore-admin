<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @class Mss
 * @brief Mss Controller，邮件管理，主要是增加管理员、帐号时，读取邮件信息[属于其它不用登陆就可以运行的页面]
 * @details  
 * @file Account.php
 * @author jingchaoSun <jingchao.sun@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
class Mss extends Run_Controller{
    /**
     * @brief 构造函数：
     *
     */
    protected $p_mss_img_url ;
    public function __construct() {
        parent::__construct();
        log_message('info', 'into class ' . __CLASS__ . '.');
        $this->load->helper('my_publicfun');
        $this->p_mss_img_url = 'http://' . $this->p_cluster_url . UC_DOMAIN_DIR . '/public/mss/' ;
    }
    /**
     * 
     * @brief 02管理员帐号开通(试用版).html 
     * @return null
     * -#
     */
    
    public function admin_open_try(){
       $data['imgpreurl'] = $this->p_mss_img_url;

       $this->load->view('mss/admin_open_try.php',$data);
    }	
    /**
     * 
     * @brief 05管理员帐号开通(正式版)
     * @return null
     * -#
     */
    
    public function admin_open_official(){
       $data['imgpreurl'] = $this->p_mss_img_url;
       $this->load->view('mss/admin_open_official.php',$data);
    }
    /**
     * 
     * @brief 06一般用户帐号开通(正式版)
     * @return null
     * -#
     */
    
    public function user_open_official(){
       $data['imgpreurl'] = $this->p_mss_img_url;

       $this->load->view('mss/user_open_official.php',$data);
    }
}