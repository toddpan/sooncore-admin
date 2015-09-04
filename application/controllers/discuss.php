<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @class Discuss
 * @brief Discuss Controller，讨论组记录控制器，列表、详情
 * @details  
 * @file Discuss.php
 * @author yanzou <yan.zou@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
class Discuss extends Admin_Controller {
     /**
      * @brief 构造函数： 
      * 
      */
     public function __construct() {
         parent::__construct();     
        //加载类
        $this->load->library('DiscussLib','','DiscussLib');
     } 
    /**
     * @brief讨论组记录列表页面
     * @details
     * -# 获得当前页码
     * -# 获得筛选启始时间
     * -# 获得筛选结束时间
     * -# 获得关键字:姓名、账号、手机号
     * -# 根据以上信息，获得当前页讨论组记录信息
     * -# 将获得的信息分配到视图
     */
    public function listDiscussPage() {
       //$this->load->view('aaa.php',$data);        
    }
    /**
     * @brief讨论组记录查看详情页面
     * @details
     * -# 获得当前页码
     * -# 讨论组记录标识
     * -# 根据以上信息，获得当前页讨论组记录信息
     * -# 将获得的信息分配到视图
     */
    public function showDiscussPage() {
       //$this->load->view('aaa.php',$data);        
    }
}