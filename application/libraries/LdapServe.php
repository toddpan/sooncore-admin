<?php if (!defined('BASEPATH'))	exit('No direct script access allowed');

/**
 * @class LdaplibServe
 * @brief LDAP 服务器类库，主要负责对LDAP的链接，获得组织及员工方法。
 * @file LdaplibServe.php
 * @author zouyan <yan.zou@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
class LdapServe  {
    private $hostName; //服务器地址 
    private $userDn; //用户名 
    private $pwd; //密码 
    private $port; //端口号 
    private $link; //返回的连接对象 
    public  $state; //返回的一个公共的连接状态 
    /**
     *
     * @brief LDAP库类。
     * @details
     * -# 连接LDAP服务器超时提示：操作超时，请稍后再试；
     * -# 连接LDAP服务器地址错误提示：请填写正确信息；
     * -# 连接LDAP服务器用户名提示：用户或密码错误
     * @return null
     *
     */
    //------------ 定义联接类的构造函数 ------------------ 
    function __construct($params) 
    { 

        $this->hostName=$params['hostName']; 
        $this->userDn=$params['userDn']; 
        $this->pwd=$params['pwd']; 
        $this->port=$params['port']; 
        $this->link=$params['link'];
        $this->state=0; 
        
    } 
    //--------------- end conStruct ------------------- 

    //-------------- 定义link方法 连接并BIND数据库 ---------------- 
    function open() 
    { 
        /////////////////////*********加上阻止错误********/////////////////////////////// 
        $this->link = ldap_connect($this->hostName); 
        ldap_set_option($this->link, LDAP_OPT_PROTOCOL_VERSION, 3); 
        if ($this->link && ldap_bind($this->link,$this->userDn,$this->pwd)) { 
            $ldapbind = ldap_bind($this->link,$this->userDn,$this->pwd); 
            $this->state=1; 
            return $this->link; 
            ldap_unbind($this->link); //关闭数据库 
            ldap_close($this->link); //关闭连接 
        } else { 
            return $this->link; 
            //echo "抱歉，无法连上 LDAP 服务器"; 
            exit; 
        } 
    } 
    //--------------end open()-------------------------- 



}
