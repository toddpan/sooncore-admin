<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//        array(
//            'id' => 1,//编号
//            'field' => '',//字段名
//            'title' => '',//名称
//            'type' => 0,//1必选的员工标签2可选的员工标签
//            'values' => array(),//可选值串
//            'defaultvalue' => '',//默认值,多个用逗号分隔
//            'page_type' => '',//，多个用逗号分隔,显示页面类型0标签页,1下载模板页 2判断标签是否重复页 3 生成标签文档 4 帐号新加页 5 帐号修改页6生态管理员添加页面
//        )
class Tag_class {
    private  $system_tag_names = '';//所有的系统员工标签名,多个用，号分隔[部门没有细分]
    private $all_tag_names = '';//所有的员工标签名称，多个用，号分隔 [部门细分为[部门一级。。。]]
    //系统必选员工标签
    private $must_tag_arr = array();//系统必选员工标签
    private $must_tag_department_arr = array();//部门标签信息
    private $must_tag_names = '';//系统必选员工标签名，多个用，号分隔
    private $must_tag_all_names = ''; // 获得系统所有标签名称，多个用，号分隔 
    private $must_tag_department_names = ''; // 获得系统部门标签名称，多个用，号分隔
    
    //系统可选员工标签
    private $not_must_tag_arr = array();//系统可选员工标签
    private $not_must_tag_names = '';//系统可选员工标签名，多个用，号分隔
    
    private $seled_not_must_tag_arr = array();//系统可选员工标签,选中的标签
    private $seled_not_must_tag_names = '';//系统选中的可选员工标签名，多个用，号分隔
    //
    //自定义员工标签
    private $user_defined_tag_arr = array();//自定义标签
    private $user_defined_tag_names = '';//自定义员工标签名，多个用，号分隔
    
   //部门标签,
   private $department_tag_names = '';//根据部门层数，生成部门一级、部门二级、，多个用，号分隔

    
    
    private $site_id = 0; //当前站点id
    private $tag_arr = array(
         //必选的员工标签
        array(
            'id' => 0,//编号
            'field' => 'last_name',//first_name',//字段名
            'umsapifield' => 'lastName',//umsAPI对应字段名,因为必须要用lastname
            'title' => '姓名',//名称
            'type' => 1,//1必选的员工标签2可选的员工标签
            'regex' => "/^\S{1,50}$/",//'/^[\s\S]{1,50}$/',//50个中英文数字//^[\x80-\xffA-Za-z\d]{1,50}$/
            'values' => array(),//可选值串
            'defaultvalue' => '',//默认值,多个用逗号分隔
            'page_type' => '1,2,3,4,5',//，多个用逗号分隔,显示页面类型0标签页,1下载模板页 2判断标签是否重复页 3 生成标签文档 4 帐号新加页 5 帐号修改页 6生态管理员添加页面
        ), 
       array(
            'id' => 1,//编号
            'field' => 'last_name',//字段名
            'umsapifield' => 'lastName',//umsAPI对应字段名
            'title' => '姓',//名称
            'type' => 1,//1必选的员工标签2可选的员工标签
            'regex' => "/^\S{1,50}$/",//'/^[\s\S]{1,50}$/',
            'values' => array(),//可选值串
            'defaultvalue' => '',//默认值,多个用逗号分隔
            'page_type' => '0,2,6',//，多个用逗号分隔,显示页面类型0标签页,1下载模板页 2判断标签是否重复页 3 生成标签文档 4 帐号新加页 5 帐号修改页 6生态管理员添加页面
        ),
        array(
            'id' => 2,//编号
            'field' => 'first_name',//字段名
            'umsapifield' => 'firstName',//umsAPI对应字段名
            'title' => '名',//名称
            'type' => 1,//1必选的员工标签2可选的员工标签
            'regex' => "/^\S{1,50}$/",//'/^[\s\S]{1,50}$/',
            'values' => array(),//可选值串
            'defaultvalue' => '',//默认值,多个用逗号分隔
            'page_type' => '0,2,6',//，多个用逗号分隔,显示页面类型0标签页,1下载模板页 2判断标签是否重复页 3 生成标签文档 4 帐号新加页 5 帐号修改页 6生态管理员添加页面
        ),
        array(
            'id' => 3,//帐号
            'field' => 'login_name',//字段名
            'umsapifield' => 'loginName',//umsAPI对应字段名
            'title' => '帐号',//名称
            'type' => 1,//1必选的员工标签2可选的员工标签
            'regex' => "/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/",//"/^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/",//'/^[\s\S]{1,100}$/',
            'values' => array(),//可选值串
            'defaultvalue' => '',//默认值,多个用逗号分隔
            'page_type' => '1,2,3,4,5,6',//，多个用逗号分隔,显示页面类型0标签页,1下载模板页 2判断标签是否重复页 3 生成标签文档 4 帐号新加页 5 帐号修改页 6生态管理员添加页面
        ),
        array(
            'id' => 31,//帐户
            'field' => 'accountId',//字段名
            'umsapifield' => 'accountId',//umsAPI对应字段名
            'title' => '帐户',//名称
            'type' => 1,//1必选的员工标签2可选的员工标签
            'regex' => '/^[\d]+$/',
            'values' => array(),//可选值串
            'defaultvalue' => '',//默认值,多个用逗号分隔
            'page_type' => '4,5',//，多个用逗号分隔,显示页面类型0标签页,1下载模板页 2判断标签是否重复页 3 生成标签文档 4 帐号新加页 5 帐号修改页 6生态管理员添加页面
        ),
        array(
            'id' => 4,//开启帐号
            'field' => 'isopen',//字段名
            'umsapifield' => 'isopen',//umsAPI对应字段名
            'title' => '开启帐号',//名称
            'type' => 1,//1必选的员工标签2可选的员工标签
            'regex' => '/^[01]$/',//0或1
            'values' => array(),//可选值串
            'defaultvalue' => '',//默认值,多个用逗号分隔
            'page_type' => '2,3,4,5,6',//，多个用逗号分隔,显示页面类型0标签页,1下载模板页 2判断标签是否重复页 3 生成标签文档 4 帐号新加页 5 帐号修改页 6生态管理员添加页面
        )
        ,

        array(
            'id' => 5,//编号
            'field' => 'sex',//字段名
            'umsapifield' => 'sex',//umsAPI对应字段名
            'title' => '性别',//名称
            'type' => 1,//1必选的员工标签2可选的员工标签
            'regex' => '/^[012]$/',//0未知1男2女
            'values' => array(),//可选值串
            'defaultvalue' => '',//默认值,多个用逗号分隔
            'page_type' => '0,1,2,3,4,5,6',//，多个用逗号分隔,显示页面类型0标签页,1下载模板页 2判断标签是否重复页 3 生成标签文档 4 帐号新加页 5 帐号修改页 6生态管理员添加页面
        ),
        array(
            'id' => 6,//编号
            'field' => 'organization_id',//字段名
            'umsapifield' => 'organization_id',//umsAPI对应字段名
            'title' => '部门',//名称
            'type' => 1,//1必选的员工标签2可选的员工标签
            'regex' => "/^\S{1,50}$/",//'/^[\s\S]{1,100}$/',//部门正则，不在这里写
            'values' => array(
                0 => 0,
                1 => 1,
                2 => 2,
                3 => 3,
                4 => 4,
                5 => 5,
                6 => 6,
                7 => 7,
                8 => 8,
                9 => 9,
                10 => 10
                
            ),//可选值串
            'defaultvalue' => '',//默认值,多个用逗号分隔
            'page_type' => '0,1,2,3',//，多个用逗号分隔,显示页面类型0标签页,1下载模板页 2判断标签是否重复页 3 生成标签文档 4 帐号新加页 5 帐号修改页 6生态管理员添加页面
        ),
        array(
            'id' => 61,//编号 
            'field' => 'organizationId',//字段名
            'umsapifield' => 'organizationId',//umsAPI对应字段名
            'title' => '部门',//名称
            'type' => 1,//1必选的员工标签2可选的员工标签
            'regex' => "/^\S{1,100}$/",//'/^[\s\S]{1,100}$/',//部门正则，不在这里写
            'values' => array(
                0 => 0,
                1 => 1,
                2 => 2,
                3 => 3,
                4 => 4,
                5 => 5,
                6 => 6,
                7 => 7,
                8 => 8,
                9 => 9,
                10 => 10
                
            ),//可选值串
            'defaultvalue' => '',//默认值,多个用逗号分隔
            'page_type' => '0,1,2,3,4,5',//，多个用逗号分隔,显示页面类型0标签页,1下载模板页 2判断标签是否重复页 3 生成标签文档 4 帐号新加页 5 帐号修改页 6生态管理员添加页面
        ),
        array(
            'id' => 7,//编号
            'field' => 'position',//字段名
            'umsapifield' => 'position',//umsAPI对应字段名
            'title' => '职位',//名称
            'type' => 1,//1必选的员工标签2可选的员工标签
            'regex' => "/^\S{1,50}$/",//'/^[\s\S]{1,50}$/',
            'values' => array(),//可选值串
            'defaultvalue' => '',//默认值,多个用逗号分隔
            'page_type' => '0,1,2,3,4,5,6',//，多个用逗号分隔,显示页面类型0标签页,1下载模板页 2判断标签是否重复页 3 生成标签文档 4 帐号新加页 5 帐号修改页 6生态管理员添加页面
        ),
        array(
            'id' => 8,//编号
            'field' => 'mobile_number',//字段名
            'umsapifield' => 'mobileNumber',//umsAPI对应字段名
            'title' => '手机',//名称
            'type' => 1,//1必选的员工标签2可选的员工标签
            'regex' => "/^[+]?[\d]{5,20}$/",//'/^[+]?[\d]{5,20}$/',//按照号段进行匹配///^1[358]{1}[\d]{9}$/
            'values' => array(),//可选值串
            'defaultvalue' => '',//默认值,多个用逗号分隔
            'page_type' => '0,1,2,3,4,5,6',//，多个用逗号分隔,显示页面类型0标签页,1下载模板页 2判断标签是否重复页 3 生成标签文档 4 帐号新加页 5 帐号修改页 6生态管理员添加页面
        ),
        array(
            'id' => 81,//国码
            'field' => 'country_code',//字段名
            'umsapifield' => 'country_code',//umsAPI对应字段名
            'title' => '国码',//名称
            'type' => 1,//1必选的员工标签2可选的员工标签
            'regex' => '/^\+[\d]{1,4}$/',//按照号段进行匹配///^1[358]{1}[\d]{9}$/
            'values' => array(),//可选值串
            'defaultvalue' => '',//默认值,多个用逗号分隔
            'page_type' => '',//，多个用逗号分隔,显示页面类型0标签页,1下载模板页 2判断标签是否重复页 3 生成标签文档 4 帐号新加页 5 帐号修改页 6生态管理员添加页面
        ),
        array(
            'id' => 9,//编号
            'field' => 'country_id',//字段名
            'umsapifield' => 'country_id',//umsAPI对应字段名
            'title' => '国家',//名称
            'type' => 1,//1必选的员工标签2可选的员工标签
            'regex' => "/^\S{1,50}$/",//'/^[\s\S]{1,50}$/',
            'values' => array(),//可选值串
            'defaultvalue' => '',//默认值,多个用逗号分隔
            'page_type' => '0,1,2,3',//，多个用逗号分隔,显示页面类型0标签页,1下载模板页 2判断标签是否重复页 3 生成标签文档 4 帐号新加页 5 帐号修改页 6生态管理员添加页面
        ),
        array(
            'id' => 10,//编号
            'field' => 'office_address',//字段名
            'umsapifield' => 'officeaddress',//umsAPI对应字段名
            'title' => '办公室所在地区',//名称
            'type' => 1,//1必选的员工标签2可选的员工标签
            'regex' => "/^\S{1,50}$/",//'/^[\s\S]{1,200}$/',
            'values' => array(),//可选值串
            'defaultvalue' => '',//默认值,多个用逗号分隔
            'page_type' => '0,1,2,3,4,5,6',//，多个用逗号分隔,显示页面类型0标签页,1下载模板页 2判断标签是否重复页 3 生成标签文档 4 帐号新加页 5 帐号修改页 6生态管理员添加页面
        ),
        //可选的员工标签    

        array(
            'id' => 11,//编号
            'field' => 'email',//字段名
            'umsapifield' => 'email',//umsAPI对应字段名
            'title' => '邮箱',//名称
            'type' => 2,//1必选的员工标签2可选的员工标签
            'regex' => '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/',//
            'values' => array(),//可选值串
            'defaultvalue' => '',//默认值,多个用逗号分隔
            'page_type' => '0,1,2,3,4,6',//，多个用逗号分隔,显示页面类型0标签页,1下载模板页 2判断标签是否重复页 3 生成标签文档 4 帐号新加页 5 帐号修改页 6生态管理员添加页面
        ),
        array(
            'id' => 12,//编号
            'field' => 'office_phone',//字段名
            'umsapifield' => 'officePhone',//umsAPI对应字段名
            'title' => '工作电话',//名称
            'type' => 2,//1必选的员工标签2可选的员工标签
            'regex' => '/^[+]?[\d]{5,20}$/',//
            'values' => array(),//可选值串
            'defaultvalue' => '',//默认值,多个用逗号分隔
            'page_type' => '0,1,2,3,4',//，多个用逗号分隔,显示页面类型0标签页,1下载模板页 2判断标签是否重复页 3 生成标签文档 4 帐号新加页 5 帐号修改页 6生态管理员添加页面
        ),
        array(
            'id' => 13,//编号
            'field' => 'constcenter',//字段名
            'umsapifield' => 'constcenter',//umsAPI对应字段名
            'title' => '成本中心',//名称
            'type' => 2,//1必选的员工标签2可选的员工标签
            'regex' => "/^\S{1,50}$/",//'/^[\s\S]{1,50}$/',
            'values' => array(),//可选值串
            'defaultvalue' => '',//默认值,多个用逗号分隔
            'page_type' => '0,1,2,3',//，多个用逗号分隔,显示页面类型0标签页,1下载模板页 2判断标签是否重复页 3 生成标签文档 4 帐号新加页 5 帐号修改页 6生态管理员添加页面
        ),
        array(
            'id' => 14,//编号
            'field' => 'user_id',//字段名
            'umsapifield' => 'user_id',//umsAPI对应字段名
            'title' => '员工ID',//名称
            'type' => 2,//1必选的员工标签2可选的员工标签
            'regex' => "/^\d+$/",//"/^\S{1,50}$/",//'/^[\s\S]{1,50}$/',
            'values' => array(),//可选值串
            'defaultvalue' => '',//默认值,多个用逗号分隔
            'page_type' => '0,1,2,3',//，多个用逗号分隔,显示页面类型0标签页,1下载模板页 2判断标签是否重复页 3 生成标签文档 4 帐号新加页 5 帐号修改页 6生态管理员添加页面
        )
    );
    /**
     *
     * @brief 构造函数
     * @details 把必填标签与可选标签分别给对应的属性
     * @param int $in_page_type  显示页面类型0标签页,1下载模板页 2判断标签是否重复页 3 生成标签文档 4 帐号新加页 5 帐号修改页 6生态管理员添加页面
     * @return array 
     *
     */
    public function __construct($in_page_type) {
        //载入接口公用函数
        include_once APPPATH . 'helpers/my_httpcurl_helper.php'; 
        $this -> set_tag_arr($in_page_type);
    }

    /**
     *
     * @brief 获得系统所有标签数组
     * @details 
     * @return array 返回标签数组
     *
     */
    public function get_tag(){
        return $this->tag_arr;
    }  

    /**
     *
     * @brief 根据类型，获得系统标签数组
     * @details 
     * 可以获得 系统必选员工标签数组 $this->must_tag_arr
     * 部门标签信息 $this -> must_tag_department_arr 
     * 可以获得 系统可选员工标签数组 $this->not_must_tag_arr
     * 所有的系统员工标签名,多个用，号分隔  $this->system_tag_names 
     * 所有的系统必选员工标签名,多个用，号分隔$this->must_tag_names
     * 所有的系统可选员工标签名,多个用，号分隔$this->not_must_tag_names
     * @param int $in_page_type  显示页面类型0标签页,1下载模板页 2判断标签是否重复页 3 生成标签文档
     * @return null
     *
     */
    private function set_tag_arr($in_page_type)
    {
        
        $data_arr = $this->tag_arr;
        
        $re_must_arr = array();//必选员工标签
        $re_not_must_arr = array();//可选员工标签
        foreach($data_arr as  $valarr){
            //'type' //1必选的员工标签2可选的员工标签
            $tag_type = isset($valarr['type'])?$valarr['type']:0;
            $title = isset($valarr['title'])?$valarr['title']:'';//标签名称
            $page_type = isset($valarr['page_type'])?$valarr['page_type']:'-1';
            if (strstr(',' . $page_type . ',' ,',' . $in_page_type . ',')){
                //TODO 需要把名称中的，号转义一下成其它码
                $title = trim($title);
                if(!bn_is_empty($title)){
                    if($tag_type == 1){//必选的员工标签
                       if( $title == '部门'){
                          $this -> must_tag_department_arr = $valarr;//部门标签信息
                       }
                       //判断标签名是否已经存在
                        if ( !strstr(',' . $this->must_tag_names . ',', ',' . $title . ',')){
                            //不存在
                            if(!bn_is_empty($this->must_tag_names)){
                                $this->must_tag_names .= ',';
                            }
                            $this->must_tag_names .= $title;
                             $re_must_arr[] = $valarr;
                        }          


                    }else{//可选的员工标签
                       //判断标签名是否已经存在
                        if ( !strstr(',' . $this->not_must_tag_names . ',', ',' . $title . ',')){
                            //不存在
                            if(!bn_is_empty($this->not_must_tag_names)){
                                $this->not_must_tag_names .= ',';
                            }
                            $this->not_must_tag_names .= $title;
                            $re_not_must_arr[] = $valarr; 
                        } 

                    }
                }
            }
            
        }
  
        //所有的系统员工标签名,多个用，号分隔
        $this->system_tag_names =  $this->must_tag_names;            
        if(!bn_is_empty($this->not_must_tag_names)){
            if(!bn_is_empty($this->system_tag_names)){
                $this->system_tag_names .= ',';
            }
            $this->system_tag_names .= $this->not_must_tag_names;
        }   
        
        //给必选员工标签赋值
        $this->must_tag_arr = $re_must_arr;
        //给可选员工标签赋值
        $this->not_must_tag_arr = $re_not_must_arr;
    }
     /**
     *
     * @brief 部门标签信息 $this -> must_tag_department_arr
     * @details 
     * @return array 返回标签数组
     *
     */
    public function get_must_tag_department_arr(){
        return $this->must_tag_department_arr;
    }    
 
     /**
     *
     * @brief 获得系统必选标签数组
     * @details 
     * @return array 返回标签数组
     *
     */
    public function get_must_tag_arr(){
        return $this->must_tag_arr;
    }
    /**
     *
     * @brief 获得所有的系统必选员工标签名,多个用，号分隔
     * @details 
     * @return string 
     *
     */
    public function get_must_tag_names(){
        return $this->must_tag_names;
    }
    /**
     *
     * @brief 获得系统可选标签数组
     * @details 
     * @return array 返回标签数组
     *
     */
    public function get_not_must_tag_arr(){
        return $this->not_must_tag_arr;
    }
    /**
     *
     * @brief 所有的系统可选员工标签名,多个用，号分隔
     * @details 
     * @return string
     *
     */
    public function get_not_must_tag_names(){
        return $this->not_must_tag_names;
    }
    
    /**
     *
     * @brief 所有的系统员工标签名,多个用，号分隔
     * @details 
     * @return string
     *
     */
    public function get_system_tag_names(){
        return $this->system_tag_names;
    }   
  
    /**
     *
     * @brief 解析处理获得当前站点可以用的可选标签和自定义标签数组
     * @details 
     * 系统可选员工标签,选中的标签数组 $seled_not_must_tag_arr
     * 系统选中的可选员工标签名，多个用，号分隔$seled_not_must_tag_names
     * 自定义标签数组$user_defined_tag_arr
     * 自定义员工标签名，多个用，号分隔  $user_defined_tag_names
     * @param array $tag_arr 处理获得当前站点可以用的可选标签和自定义标签数组
     * @return null
     *
     */
    public function resolve_tag_arr($tag_arr)
    {
         if (is_array($tag_arr)){//是数组值
            foreach($tag_arr as $key => $value){ 
                if(is_array($value)){
                   // $id = isset($value['id'])?$value['id']:0;//标签id
                    $tag_type = isset($value['tag_type'])?$value['tag_type']:0;//1-基本标签2-自定义
                    $tag_name = isset($value['tag_name'])?$value['tag_name']:0;//标签名称
                    $enable = isset($value['enable'])?$value['enable']:0;//0-不可用1-可用
                   // if($enable == 1){
                        
                   
                    //TODO 需要把名称中的，号转义一下成其它码
                    $tag_name = trim($tag_name);
                     if($tag_type == 2)//自定义标签
                    {
                        //判断标签名是否已经存在
                         if ( !strstr(',' . $this->user_defined_tag_names . ',', ',' . $tag_name . ',')){
                             //不存在
                             if(!bn_is_empty($this->user_defined_tag_names)){
                                 $this->user_defined_tag_names .= ',';
                             }
                             $this->user_defined_tag_names .= $tag_name;
                              $this->user_defined_tag_arr[] = $value;
                         }
                        
                    }elseif($tag_type == 1)//系统可选标签
                    {
                        if($enable == 1){

                            //判断可选标签是否真的存在并可用
                            if ( strstr(',' . $this->not_must_tag_names . ',', ',' . $tag_name . ',')){
                                //判断标签名是否已经存在
                                 if ( !strstr(',' . $this->seled_not_must_tag_names . ',', ',' . $tag_name . ',')){
                                     //不存在
                                     if(!bn_is_empty($this->seled_not_must_tag_names)){
                                         $this->seled_not_must_tag_names .= ',';
                                     }
                                     $this->seled_not_must_tag_names .= $tag_name;
                                     //获得可选标签的其它信息
                                     foreach($this->not_must_tag_arr as $t_k => $t_v){
                                         $ns_t_name = arr_unbound_value($t_v,'title',2,'');
                                         if($ns_t_name == $tag_name){//是当前的标签
                                             $value = array_merge($t_v,$value); 
                                             $this->seled_not_must_tag_arr[] = $value;
                                             break;
                                         }
                                     }
                                     
                                 } 

                            }
                        }
                    }
                //}
               }

            }
         }
    }
   /**
     *
     * @brief 系统可选员工标签,选中的标签数组
     * @details 
     * @return array
     *
     */
    public function get_seled_not_must_tag_arr(){
        return $this->seled_not_must_tag_arr;
    } 
    /**
     *
     * @brief 系统选中的可选员工标签名，多个用，号分隔
     * @details 
     * @return string
     *
     */
    public function get_seled_not_must_tag_names(){
        return $this->seled_not_must_tag_names;
    } 
    /**
     *
     * @brief 自定义标签数组
     * @details 
     * @return string
     *
     */
    public function get_user_defined_tag_arr(){
        return $this->user_defined_tag_arr;
    } 
    /**
     *
     * @brief 自定义员工标签名，多个用，号分隔 
     * @details 
     * @return string
     *
     */
    public function get_user_defined_tag_names(){
        return $this->user_defined_tag_names;
    } 
    
    private function num_to_bignum($num){
        $re_num = '';
        switch ($num) {
          case 1: 
              $re_num = '一';
              break;
          case 2: 
              $re_num = '二';
              break;
          case 3: 
              $re_num = '三';
              break;
          case 4: 
              $re_num = '四';
              break;
          case 5: 
              $re_num = '五';
              break;
          case 6: 
              $re_num = '六';
              break;
          case 7: 
              $re_num = '七';
              break;
          case 8: 
              $re_num = '八';
              break;
          case 9: 
              $re_num = '九';
              break;
          case 10: 
              $re_num = '十';
              break;
          case 11: 
              $re_num = '十一';
              break;
          case 12: 
              $re_num = '十二';
              break;
          case 13: 
              $re_num = '十三';
              break;
          case 14: 
              $re_num = '十四';
              break;
          case 15: 
              $re_num = '十五';
              break;
          case 16: 
              $re_num = '十六';
              break;
          case 17: 
              $re_num = '十七';
              break;
          case 18: 
              $re_num = '十八';
              break;
          case 19: 
              $re_num = '十九';
              break;
          case 20: 
              $re_num = '二十';
              break;
      }
      return $re_num;
        
    }


    /**
     *
     * @brief 按部门层级，获得系统必填标签名称
     * @details 
     * 获得系统所有标签名称，多个用，号分隔 $this-> $must_tag_all_names
     * 获得系统部门标签名称，多个用，号分隔 $this->must_tag_department_names
     * 根据部门层数，生成部门一级、部门二级、，多个用，号分隔$this->department_tag_names = '';
     * @param int $department_level 部门层级
     * @return null 
     *
     */   
    private function get_must_tag_names_by_department($department_level = 0){
        $ns_must_tag_names = '';
        $ns_must_tag_department_names = '';
        $ns_department_tag_names = '';//根据部门层数，生成部门一级、部门二级、，多个用，号分隔
        //循环系统必填员工标签
        foreach($this->must_tag_arr as $value_arr)
        {
           $field = isset($value_arr['field'])?$value_arr['field']:'';//字段名
           $title = isset($value_arr['title'])?$value_arr['title']:'';//名称
           if(!bn_is_empty($title)){ 
                if ($title == '部门') //是部门标签
                {

                    for ($i = 1; $i <= $department_level; $i++) {
                        $ns_title = '部门' . $this->num_to_bignum($i)  . '级';
                       // 获得系统部门标签名称
                         if ( !strstr(',' . $ns_must_tag_department_names . ',', ',' . $ns_title . ',')){
                             //不存在
                             if(!bn_is_empty($ns_must_tag_department_names)){
                                 $ns_must_tag_department_names .= ',';
                             }
                             $ns_must_tag_department_names .= $ns_title;  
                             
                             //部门层级
                             if(!bn_is_empty($ns_department_tag_names)){
                                 $ns_department_tag_names .= ',';
                             }
                             $ns_department_tag_names .= $ns_title;  
                         }
                        //判断标签名是否已经存在
                         if ( !strstr(',' . $ns_must_tag_names . ',', ',' . $ns_title . ',')){
                             //不存在
                             if(!bn_is_empty($ns_must_tag_names)){
                                 $ns_must_tag_names .= ',';
                             }
                             $ns_must_tag_names .= $ns_title;                   
                         }
                    }

                     
                }else{//不是部门标签
                    //判断标签名是否已经存在
                     if ( !strstr(',' . $ns_must_tag_names . ',', ',' . $title . ',')){
                         //不存在
                         if(!bn_is_empty($ns_must_tag_names)){
                             $ns_must_tag_names .= ',';
                         }
                         $ns_must_tag_names .= $title;                   
                     } 
                }

           }
        }
        $this->must_tag_all_names = $ns_must_tag_names;
        $this->must_tag_department_names = $ns_must_tag_department_names;
        $this->department_tag_names = $ns_department_tag_names;
    } 

    /**
     *
     * @brief 根据当前系统部门层级及选用的系统可选标签及自定义员工标签，确定可用的标签
     * @details 
     * 系统可选员工标签,选中的标签数组 $seled_not_must_tag_arr
     * 系统选中的可选员工标签名，多个用，号分隔$seled_not_must_tag_names
     * 自定义标签数组$user_defined_tag_arr
     * 自定义员工标签名，多个用，号分隔  $user_defined_tag_names
     * 
     * 所有的员工标签名称，多个用，号分隔 [部门细分为[部门一级。。。]] $this-> all_tag_names
     * 获得系统所有标签名称，多个用，号分隔 $this-> must_tag_all_names
     * 获得系统部门标签名称，多个用，号分隔 $this->must_tag_department_names
     * @param int $department_level 当前系统部门层级
     * @param array $tag_arr 从数据库获得系统可选标签及自定义员工标签信息
     * @return null 
     *
     */
    public function  resolve_tag($tag_arr , $department_level = 0)
    {
        $this->resolve_tag_arr($tag_arr);
        $this->get_must_tag_names_by_department($department_level);
        $ns_all_tag_names = '';//所有的员工标签名称，多个用，号分隔 
        //加入系统必选的员工标签
        if ( !strstr(',' . $ns_all_tag_names . ',', ',' . $this->must_tag_all_names . ',')){
            //不存在
            if(!bn_is_empty($ns_all_tag_names)){
                $ns_all_tag_names .= ',';
            }
            $ns_all_tag_names .= $this->must_tag_all_names;                   
        } 
        //加入系统可选的员工标签
        if ( !strstr(',' . $ns_all_tag_names . ',', ',' . $this->seled_not_must_tag_names . ',')){
            //不存在
            if(!bn_is_empty($ns_all_tag_names)){
                $ns_all_tag_names .= ',';
            }
            $ns_all_tag_names .= $this->seled_not_must_tag_names;                   
        } 
        //加入用户自定义的员工标签
        if ( !strstr(',' . $ns_all_tag_names . ',', ',' . $this->user_defined_tag_names . ',')){
            //不存在
            if(!bn_is_empty($ns_all_tag_names)){
                $ns_all_tag_names .= ',';
            }
            $ns_all_tag_names .= $this->user_defined_tag_names;                   
        } 
        $this-> all_tag_names = $ns_all_tag_names;
    }
    
   /**
     *
     * @brief 获得所有的员工标签名称，多个用，号分隔 [部门细分为[部门一级。。。]]
     * @details 
     * @return string
     *
     */
    public function get_all_tag_names(){
        return $this->all_tag_names;
    } 
    /**
     *
     * @brief 获得系统所有标签名称，多个用，号分隔
     * @details 
     * @return string
     *
     */
    public function get_must_tag_all_names(){
        return $this->must_tag_all_names;
    }
    /**
     *
     * @brief 获得系统部门标签名称，多个用，号分隔
     * @details 
     * @return string
     *
     */
    public function get_must_tag_department_names(){
        return $this->must_tag_department_names;
    } 
    /**
     *
     * @brief 获得根据部门层数，生成部门一级、部门二级、，多个用，号分隔
     * @details 
     * @return string
     *
     */
    public function get_department_tag_names(){
        return $this->department_tag_names;
    } 
  
}


