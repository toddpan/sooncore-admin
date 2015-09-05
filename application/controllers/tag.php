<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @class Tag
 * @brief Tag Controller，主要负责对员工标签[可选的员工标签、自定义的员工标签]设置、修改操作，自定义的员工标签删除操作。
 * @details
 * @file BulkImport.php
 * @author zouyan <yan.zou@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
class Tag extends Admin_Controller {
    /**
     * @brief 构造函数：
     *
     */
    public function __construct() {
        parent::__construct();
        $this->load->helper('my_publicfun');
        $this->load->helper('my_dgmdate');
        //调用分配域的接口
        $this->load->library('API','','API');
        log_message('info', 'into class ' . __CLASS__ . '.');
    }

    /**
     *
     * @brief 添加员工标签页面、生态企业标签页：
     * @details
     * -# 获得[必选的员工标签]、[可选的员工标签]、[自定义的员工标签]
     * -# 从UC获取当前站点部门层级
     * -# 将员工标签信息分配到设置员工标签视图
     * -# 视图可进行的操作：选择[必选的员工标签]部门层级,勾选[可选的员工标签]、添加设置[自定义员工标签]
     * -# 设置[自定义员工标签]时，js实现验证：名称最长可输入 20 个字，可输入中英文数字，名称不可重复
     * @param int $page_type 标签来源0新加页面，会跳转到批量导入或LDAP导入页面 1修改页面，会跳转到组织页面
     * @return null
     *
     */
    public function addTagPage() {
        log_message('info', 'into method ' . __FUNCTION__ . '.');
        // 员工导入方式0：否（批量导入）；1：是（LDAP导入）；2：全部都可以
        try{
            $is_LDAP = $this->p_is_ldap;
            // 验证是否是0 \1 \ 2
            if(!preg_match('/^[012]$/',$is_LDAP )){
                form_json_msg('1','','非法的导入方式！');//返回错误信息json格式
            }
        }catch(Exception $e){
            log_message('debug', $e->getMessage());
            exit();
        }

        //获得参数,如果没有值,则认为是标签来源0新加页面，会跳转到批量导入或LDAP导入页面 1修改页面，会跳转到组织页面
        $page_type = $this->uri->segment(3);
        try{
            if (bn_is_empty($page_type)){
                $page_type = 0;
            }

            // 验证是否是 0 \1
            if(!preg_match('/^[01]$/',$page_type)){
                form_json_msg('1','','参数错误！');//返回错误信息json格式
            }
        }catch(Exception $e){
            log_message('debug', $e->getMessage());
            exit();
        }

        //载入员工标签资源
        include_once APPPATH . 'libraries/public/Tag_class.php';

        $tag_obj = new Tag_class(0);//标签页显示使用

        try{
            $all_tag_obj = new Tag_class(2);//标签页显示使用
            //所有的系统员工标签名
            $data['system_tag_names'] = $all_tag_obj->get_system_tag_names();

            //获得必选的员工标签
            $data['must_tag_arr'] = $tag_obj->get_must_tag_arr();

            //获得可选的员工标签
            $data['not_must_tag_arr'] = $tag_obj->get_not_must_tag_arr();

            //系统选中的可选员工标签名，多个用，号分隔
            $seled_not_must_tag_arr = '';
            $seled_not_must_tag_names = '';

            //自定义员工标签数组
            $user_defined_tag_arr = array();

            //获得部门层级
            $department_level = 0;
            //是修改功能
            // 站点ID
            $site_id = $this->p_site_id;//1 ;

            $this->load->model('UC_User_Tags_Model');
            //从数据库获得系统可选标签及自定义员工标签信息
            $data_tags = array(  
               'select' =>'id,site_id,tag_name,tag_scope,tag_type,enable',
               'where' => array('site_id' => $site_id),
           );
           $tag_arr =  $this->UC_User_Tags_Model->operateDB(2,$data_tags);

            $tag_obj->resolve_tag_arr($tag_arr);
            //获得当前站点的选中的可选员工标签名，多个用，号分隔
            $seled_not_must_tag_names = $tag_obj->get_seled_not_must_tag_names();
            $seled_not_must_tag_arr =  $tag_obj->get_seled_not_must_tag_arr(); ;
            //自定义员工标签数组
            $user_defined_tag_arr = $tag_obj->get_user_defined_tag_arr();

            //获得部门层级
            //从管理员表获取部门层级
            $this->load->model('UC_Site_Model');
            $data_sel = array(  
                'select' =>'department_level',
                'where' => array('siteID' => $site_id)
                );
            $uc_site_arr = $this->UC_Site_Model->operateDB(1,$data_sel);
            $department_level = isset($uc_site_arr['department_level'])?$uc_site_arr['department_level']:0;
            
            //print_r($data_sel);
            //选中的可选的员工标签
            $data['seled_not_must_tag_arr'] = $seled_not_must_tag_arr ;
            $data['seled_not_must_tag_names'] = $seled_not_must_tag_names;
            
            //获得当前自定义标签
            $data['user_defined_tag_arr'] = $user_defined_tag_arr;
            $data['department_level'] = $department_level ;
            //$page_type 标签类型0新加页面 1修改页面
            $data['page_type'] = $page_type;

            //员工导入方式0：否（批量导入）；1：是（LDAP导入）；2：全部都可以
            $data['is_LDAP'] = $is_LDAP ;

            //加载视图
            $this->load->view('tag/tag_1.php',$data);
        }catch(Exception $e){
            log_message('debug', $e->getMessage());
            exit();
        }
        log_message('info','the view is loaded successfully!');
        log_message('info', 'out method ' . __FUNCTION__ . '.');
    }

    /**
     *
     * @brief 保存新加/修改员工标签
     * @details
     * -# 获得表单信息
     * -# 校验表单信息：[自定义员工标签]名称最长可输入 20 个字，可输入中英文数字，名称不可重复
     * -# 通过接口保存员工标签设置信息
     * -# 保存当前企业[部门层级]标签层级值
     * -# 获得其他管理员（员工管理员、合作伙伴管理员），手机和邮箱
     * -# 如果获得有其他管理员，则发送消息，发手机和邮箱，同时保存消息，他管理员登陆后台进行相关操作。
     * 来源：总管理员对其他管理员（员工管理员、合作伙伴管理员）
     * 发布；消息分类[新增员工标签]；
     * 内容:总管理员新增员工标签：[员工标签]，请立即更新
     * -# 写日志内容:"添加员工标签名称"
     * @return null
     *
     */
    public function addTag() {
        log_message('info', 'into method ' . __FUNCTION__ . '.');
        try {
            //从表单获得修改标签类型;如果没有值,则认为是标签来源0新加页面，会跳转到批量导入或LDAP导入页面 1修改页面，会跳转到组织页面
            $page_type = $this->input->post('page_type', TRUE);//
            // 验证类型值为0/1
            if(!preg_match('/^[01]$/',$page_type)){
                form_json_msg('1','','参数有误!');//返回错误信息json格式
            }

            $site_id = $this->p_site_id;//1;
            // 验证是数值
            if(!preg_match('/^[\d]+$/',$site_id)){
                form_json_msg('1','','参数有误!');//返回错误信息json格式
            }


            //获得部门层级
            $department_level = $this->input->post('department_level', TRUE);
            //对部门层级进行0-10的验证
            if(!preg_match('/^([0-9]|10)$/',$department_level)){
                form_json_msg('1','','参数有误！');//返回错误信息json格式
            }
            
            //获得系统可选标签及自定义标签//},{"id":10,"tag_name":"dafsdf","enable":1,"tag_scope":1,"tag_type":2}
            $tag_json = $this->input->post('tag_json', TRUE);
            $tag_arr = json_decode( $tag_json , TRUE );

                //print_r($tag_arr);
        } catch (Exception $e) {
            // print $e->getMessage();
            log_message('debug', $e->getMessage());
            exit();
        }

        //保存标签
        $this->load->helper('my_dgmdate');
        $this->load->model('UC_User_Tags_Model');
        //获得的是数组
        $db_tag_id_arr = array();//数据库中有，且当前也选中的标签
        try {
            if(is_array($tag_arr)){
                $is_err_tag = 0;//标记是否有错误的标签，只要有一个错误都返回，不继续执行,0没有，1有
                //对标签数据进行有效性验证
                $tag_i = 0 ;//新标签下标
                foreach($tag_arr as $key => $val){
                    if(is_array($val)){
                        $id = $val['id'];
                        $tag_name = $val['tag_name'];
                        $enable = $val['enable'];
                        $tag_scope = $val['tag_scope'];
                        $tag_type = $val['tag_type'];

                        // 验证为数值
                        if(!preg_match("/\d/",$id)){
                            form_json_msg('1','','参数有误！');//返回错误信息json格式
                        }
                        //标签名规则验证,如果有错$is_err_tag = 1;
                        if(!preg_match('/^[\s\S]{1,50}$/',$tag_name)){
                            $is_err_tag = 1;
                            form_json_msg('1','','标签名称参数有误！');//返回错误信息json格式
                        }
                        //是否可用，验证0或1
                        if(!preg_match('/^[01]$/',$enable)){
                            form_json_msg('1','','标签是否可用参数有误！');//返回错误信息json格式
                        }
                        //验证0-所有人1-管理员 2-员工填写
                        if(!preg_match('/^[012]$/',$tag_scope)){
                            form_json_msg('1','','标签操作类型参数有误！');//返回错误信息json格式
                        }
                        //验证1-基本标签2-自定义
                        if(!preg_match('/^[12]$/',$tag_type)){
                            form_json_msg('1','','标签类型参数有误！');//返回错误信息json格式
                        }

                        //数据库中有，当前也选中的标签
                        if ($id > 0){
                            $db_tag_id_arr[] = $id ;
                        }
                        //查看标签是否存在
                        //从数据库判断当前标签名是否已经存在
                        $where_arr = array(
                            'id != ' => $id,
                            'site_id = ' => $site_id,
                            'tag_name = ' => $tag_name
                        );
                        $is_tag_num = $this->UC_User_Tags_Model->getTagCount($where_arr);
                        //标签已存在
                        if( $is_tag_num> 0){
                            $is_err_tag == 1;
                        }

                        //$is_err_tag = 1;
                        //如果当前标签有误
                        if($is_err_tag == 1){
                            log_message('info', ' tag message is err.');
                            form_json_msg('1','','标签信息有误，请重新填写',array());//返回错误信息json格式
                        }
                    }
                }
            }
        } catch (Exception $e) {
            // print $e->getMessage();
            log_message('debug', $e->getMessage());
            exit();

        }
        // print_r($db_tag_id_arr);
        //exit;
        //保存部门层级
        //载入UC_Site_Model模型
        try {
            $this->load->model('UC_Site_Model');

            $modify_data = array( 
               'update_data' =>array('department_level' => $department_level),
               'where' => array('siteID' => $site_id)
            );
            $this->UC_Site_Model->operateDB(5,$modify_data);
        } catch (Exception $e) {
            // print $e->getMessage();
            log_message('debug', $e->getMessage());
            exit();
        }
        //保存标签:
        //对数据库中有的且没有选中的进行删除，
        try {
            //获得需要删除的标签
            //TODD 对需要删除的标签进行删除
            
            $del_state = $this->UC_User_Tags_Model->delTagNotInId($site_id,$db_tag_id_arr);
            if($del_state){//删除成功
                //日志
                $this->load->library('LogLib','','LogLib');
                $log_in_arr = $this->p_sys_arr;
//                array(
//                      'Org_id' => $this->p_org_id ,//组织ID
//                      'site_id' => $this->p_site_id ,//站点ID
//                      'operate_id' => $this->p_user_id,//操作会员ID
//                      'login_name' => $this->p_account ,//操作账号[可以为空，没有，则重新获取]
//                      'display_name' => $this->p_display_name,//操作姓名[可以为空，没有，则重新获取]
//                      'client_ip' => $this->p_client_ip ,//客户端ip
//                  );
               $re_id = $this->LogLib ->set_log(array('5','13'),$log_in_arr);  
                log_message('info', 'del tags  success.');
            }else{//删除失败
                log_message('info', 'del tags  fail.');
            }
        } catch (Exception $e) {
            // print $e->getMessage();
            log_message('debug', $e->getMessage());
            exit();
        }
        //对数据库中没有且选中的进行新加操作
        //对数据库中有的且选中的进行更新，
        try {

            if(is_array($tag_arr)){
                $insert_json_arr = array();//新加标签
                foreach($tag_arr as $key => $val){
                    $val['site_id'] = $site_id ;
                    $id = $val['id'];
                    $tag_name = $val['tag_name'];//
                    $enable = $val['enable'];//0-不可用1-可用
                    $tag_scope = $val['tag_scope'];//0-所有人1-管理员 2-员工填写
                    $tag_type = $val['tag_type'];//1-基本标签2-自定义
                    if($id > 0) {//更新
                            $val['modify'] = dgmdate(time(), 'dt') ;
                            $update_where = array(
                                'id = ' => $id,
                                'site_id = ' => $site_id
                            );
                            $update_state = $this->UC_User_Tags_Model->UpdateData($val,$update_where);

                            if($update_state){//成功
                                //日志
                                $this->load->library('LogLib','','LogLib');
                                $log_in_arr = $this->p_sys_arr;
//                                array(
//                                      'Org_id' => $this->p_org_id ,//组织ID
//                                      'site_id' => $this->p_site_id ,//站点ID
//                                      'operate_id' => $this->p_user_id,//操作会员ID
//                                      'login_name' => $this->p_account ,//操作账号[可以为空，没有，则重新获取]
//                                      'display_name' => $this->p_display_name,//操作姓名[可以为空，没有，则重新获取]
//                                      'client_ip' => $this->p_client_ip ,//客户端ip
//                                  );
                               $re_id = $this->LogLib ->set_log(array('5','12'),$log_in_arr);  
                                log_message('info', 'Update tag:' . $tag_name . '  success.');
                            }else{//失败
                                log_message('info', 'Update tag:' . $tag_name . '  fail.');
                            }
                            
                       }else{//插入
                            $val['created'] = dgmdate(time(), 'dt') ;
                            $insert_data = $val;
                            unset($insert_data['id']);
                           $insert_arr =  $this->UC_User_Tags_Model->insert_db($insert_data);

                           $new_tag_id = 0;
                           if(db_operate_fail($insert_arr)){//失败
                               log_message('error', 'insert  UC_User_Tags_Model tag:' . $tag_name . '  fail.'); 
                               $has_err = 1;//是否有失败记录0没有1有
                           }else{
                               
                               $new_tag_id = isset($insert_arr['insert_id'])?$insert_arr['insert_id']:0;;
                               log_message('debug', 'insert  UC_User_Tags_Model tag:' . $tag_name . '  success.'); 
                                //日志
                                $this->load->library('LogLib','','LogLib');
                                $log_in_arr = $this->p_sys_arr;
//                                array(
//                                      'Org_id' => $this->p_org_id ,//组织ID
//                                      'site_id' => $this->p_site_id ,//站点ID
//                                      'operate_id' => $this->p_user_id,//操作会员ID
//                                      'login_name' => $this->p_account ,//操作账号[可以为空，没有，则重新获取]
//                                      'display_name' => $this->p_display_name,//操作姓名[可以为空，没有，则重新获取]
//                                      'client_ip' => $this->p_client_ip ,//客户端ip
//                                  );
                               $re_id = $this->LogLib ->set_log(array('5','11'),$log_in_arr);  
                           }
                           if($new_tag_id > 0){
                                if($tag_scope == 2 && $tag_type == 2){//自定义员工填写标签 员工填写 自定义标签
                                    $ns_insert_json_arr = array(
                                        'tag_id' => $new_tag_id,
                                        'tag_name' => $tag_name,//标签名称
                                        'type' => 1,
                                        'require' => 1,

                                    );
                                    $insert_json_arr[] = $ns_insert_json_arr;
                                }
                           } 
 
                        }
                }
                //如果有新加的自定义标签，则发送消息
                if(!isemptyArray($insert_json_arr)){//不是空数组
                    //接口参参数
                    $data = 'session_id=' . $this->p_session_id . '&user_id=' . $this->p_user_id . '&type=1&data=' . json_encode($insert_json_arr);
                    //调用登陆接口
                    $ucc_msg_arr = $this->API->UCCServerAPI($data,11);
                    if(!api_operate_fail($ucc_msg_arr)){//成功
                        log_message('info', '  uccserver api message/systemAlert ' . $data . ' success .');                       
                    }else{//失败
                       log_message('info', '  uccserver api message/systemAlert ' . $data . ' fail .');
                       //form_json_msg('3','','调用发送信息失败');//返回错误信息json格式
                    }
                }
                
            }
        } catch (Exception $e) {
            // print $e->getMessage();
            log_message('debug', $e->getMessage());
            exit();
        }
        //保存成功
        log_message('info', 'save tags success.');
        log_message('info', 'out method ' . __FUNCTION__ . '.');
        form_json_msg('0','','',array());//返回错误信息json格式
    }
}
