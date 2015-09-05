<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @class Chat
 * @brief Chat Controller，聊天记录控制器，相关聊天记录列表、详情
 * @details  
 * @file Chat.php
 * @author yanzou <yan.zou@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
class Chat extends Admin_Controller{
    /**
     * @brief 构造函数： 
     * 
     */
    public function __construct() {
        parent::__construct();     
       //加载类
       $this->load->library('ChatLib','','ChatLib');
    } 
   /**
    * @brief聊天记录列表页面
    * @details
    * -# 获得当前页码
    * -# 获得筛选启始时间
    * -# 获得筛选结束时间
    * -# 获得关键字:姓名、账号、手机号
    * -# 根据以上信息，获得当前页聊天记录信息
    * -# 将获得的信息分配到视图
    */
   public function listChatPage() {
      //$this->load->view('aaa.php',$data);
   }
   /**
    * @brief聊天记录查看详情页面
    * @details
    * -# 获得当前页码
    * -# 聊天记录标识
    * -# 根据以上信息，获得当前页聊天记录信息
    * -# 将获得的信息分配到视图
    */
   public function showChatPage() {
      $this->load->view('public/popup/chatdetail.php');        
   }
}