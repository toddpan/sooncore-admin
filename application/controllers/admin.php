<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @class Admin
 * @brief Admin Controller，管理员管理，主要是增加管理员、
 * @details  
 * @file Account.php
 * @author jingchaoSun <jingchao.sun@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
class Admin extends Admin_Controller{

    /**
     * @brief获取该账号的列表
     * @details
     * -# 通过SESSION中权限获取该站点ID下的所有的管理账号
     * -# 通过所有的管理员的USERID获取对应的信息（手机号、sooncore平台账号、姓名）等
     */
    public function adminList() {
        $role = $_REQUEST['role'];//代表某种类型的管理员，其中role为0时，则代表所有的用户
    }

    /**
     * @brief 通过部门/地区/成本中心 获取数据
     * @details
     * -# 根据部门获取部门列表，首先获取顶级，点击后可以获取下一级
     * -# 根据地区获取地区配置，调用UMS接口获取
     * -# 根据成本中心获取成本中心的配置
     */
    public function getDataByType(){
        $type = $_REQUEST['type'];// type：1：部门；2：地区；：3：成本中心
    }

    /**
     * @brief 添加用户
     * @detail 
     * -# 校验用户是否为UMS中的用户
     * -# 获取该用户的userID
     * -# 保存用户的管理员权限，并分配组织管理范围
     */
    public function saveAdmin(){
    }

    /**
     * @brief 搜索管理员
     * @details
     * -# 根据名字来搜索用户信息
     */
     public function searchAdmin(){
        /**
         * 为了增加执行效率：
         * 1、获取该站点下的所有管理员的USERID
         * 2、通过userID的list及模糊条件姓名从UMS中查询用户信息
         */
     }
	
}