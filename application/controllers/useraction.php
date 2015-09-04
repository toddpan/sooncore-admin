<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @class UserAction
 * @brief UserAction Controller，通知控制器，相关管理员对通知的查看
 * @details
 * -# 第一次展示聊天记录
 * -# 后面切换时，如果切换到的页面已经展开，则一定要判断，条件是否有变化，有则需要重新加载，
 *    没有，则不用重新加载，只是显示。
 * @file UserAction.php
 * @author yanzou <yan.zou@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
class UserAction extends Admin_Controller{
    /**
     * @brief 构造函数：
     *
     */
    public function __construct() {
        parent::__construct();
    }

    public function userActionPage(){
        $this->load->view('useraction/useractionlist.php');
    }
}