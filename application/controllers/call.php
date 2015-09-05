<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @class Call
 * @brief Call Controller，通话记录控制器，相关员工通话记录列表
 * @details  
 * @file Call.php
 * @author yanzou <yan.zou@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
class Call extends Admin_Controller {
     /**
      * @brief 构造函数： 
      * 
      */
     public function __construct() {
         parent::__construct();     
        //加载类
        $this->load->library('CallLib','','CallLib');
     } 
    /**
     * @brief通话记录列表页面
     * @details
     * -# 获得当前页码
     * -# 获得筛选启始时间
     * -# 获得筛选结束时间
     * -# 获得关键字:姓名、账号、手机号
     * -# 根据以上信息，获得当前页通话记录信息
     * -# 将获得的信息分配到视图
     */
    public function listCallPage() {        
       //$this->load->view('aaa.php',$data);        
    }
	
}