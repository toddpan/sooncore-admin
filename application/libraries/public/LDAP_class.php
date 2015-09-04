<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//        array(
//            'id' => 1,//编号
//            'field' => '',//字段名
//            'title' => '',//名称
//            'input_type' => 0,//0输入框1密码框2单选框3复选框4下拉框[单选]
//            'regex' => '',//正则表达式
//            'values' => array(),//可选值串
//            'defaultvalue' => '',//默认值,多个用逗号分隔
//            'page_type' => '',//，多个用逗号分隔,显示页面类型
//        )
class LDAP_class {
    private $LDAP_arr = array(
        array(//服务器类型
            'id' => 0,//编号
            'field' => 'serve_type',//字段名
            'title' => '服务器类型',//名称
            'input_type' => 4,//0输入框1密码框2单选框3复选框4下拉框[单选]
            'regex' => '/^[1234]$/',//
            'values' => array(
                '1' => 'Microsoft Active Directory',
                '2' => 'OpenDirectory',
                '3' => 'Lotus Dimino',
                '4' => '其他'
            ),//可选值串
            'defaultvalue' => '',//默认值,多个用逗号分隔
            'page_type' => '',//，多个用逗号分隔,显示页面类型
        ),
        array(//连接方式
            'id' => 1,//编号
            'field' => 'link_type',//字段名
            'title' => '连接方式',//名称
            'input_type' => 1,//
            'regex' => '/^[12]$/',//
            'values' => array(
                '1'=>'标准LDAP',
                '2'=>'LDAP + SSL',
            ),//可选值串
            'defaultvalue' => '',//默认值,多个用逗号分隔
            'page_type' => '',//，多个用逗号分隔,显示页面类型
        ),
        array(//LDAP服务器地址
            'id' => 2,//编号
            'field' => 'ldp_address',//字段名
            'title' => 'LDAP服务器地址',//名称
            'input_type' => 1,//
            'regex' => '/^((2[0-4]\d|25[0-5]|[01]?\d\d?)\.){3}(2[0-4]\d|25[0-5]|[01]?\d\d?)$/',//
            'values' => array(),//可选值串
            'defaultvalue' => '',//默认值,多个用逗号分隔
            'page_type' => '',//，多个用逗号分隔,显示页面类型
        ),
        array(//LDAP服务器端口
            'id' => 3,//编号
            'field' => 'ldp_port',//字段名
            'title' => 'LDAP服务器端口',//名称
            'input_type' => 1,//
            'regex' => '/^[\d]+$/',//
            'values' => array(),//可选值串
            'defaultvalue' => '',//默认值,多个用逗号分隔
            'page_type' => '',//，多个用逗号分隔,显示页面类型
        ),
        array(//LDAP服务器用户名
            'id' => 4,//编号
            'field' => 'ldp_username',//字段名
            'title' => 'LDAP服务器用户名',//名称
            'input_type' => 1,//
            'regex' => '/^[\s\S]{1,100}$/',//
            'values' => array(),//可选值串
            'defaultvalue' => '',//默认值,多个用逗号分隔
            'page_type' => '',//，多个用逗号分隔,显示页面类型
        ),
        array(//LDAP服务器密码
            'id' => 5,//编号
            'field' => 'ldp_password',//字段名
            'title' => 'LDAP服务器密码',//名称
            'input_type' => 1,//
            'regex' => '/^[\s\S]{1,100}$/',//
            'values' => array(),//可选值串
            'defaultvalue' => '',//默认值,多个用逗号分隔
            'page_type' => '',//，多个用逗号分隔,显示页面类型
        ),
        array(//Base DN
            'id' => 6,//编号
            'field' => 'ldp_dn',//字段名
            'title' => 'Base DN',//名称
            'input_type' => 1,//
            'regex' => '/^[\s\S]{1,100}$/',//
            'values' => array(),//可选值串
            'defaultvalue' => '',//默认值,多个用逗号分隔
            'page_type' => '',//，多个用逗号分隔,显示页面类型
        )
        

    );

      public function __construct() {
  

    }
    
    /**
     *
     * @brief 获得系统所有ldap数组
     * @details 
     * @return array 返回标签数组
     *
     */
    public function get_ldap_arr(){
        return $this->LDAP_arr;
        
    }
    
    
}


