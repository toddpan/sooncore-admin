<?php if (!defined('BASEPATH'))	exit('No direct script access allowed');

/**
 * @class ManagerLib
 * @brief ManagerLib 类库，主要负责对UMS员工信息的获得、修改、新加方法。
 * @file ManagerLib.php
 * @author zouyan <yan.zou@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
class ManagerLib{
    /**
     * 
     * @brief 构造函数
     * @details 
     * 特别说明：$CI不能入在构造函数中，因为当加载model时，在具体方法中使用时不成功，所以在每个方法中单独使用。
     */
    public function __construct() {
        
        //载入接口公用函数
       // include_once APPPATH . 'helpers/my_httpcurl_helper.php';
       // $CI =& get_instance();
       // $CI->load->helper('my_publicfun');
       // $CI->load->helper('my_dgmdate');        
        log_message('info', 'into class ' . __CLASS__ . '.');
    }
   /**
     *
     * @brief 根据管理员id，获得管理员下级管理员信息： 
     * @details 
     * @param string $select_field  管理员查询字段
     * @param array $where_arr  管理员查询条件数组 operateDB 方法的条件形式
        $where_arr = array(
            'where' => array(
                'super_admin_id' => $super_admin_id,//当前管理员id,
                'siteID' => $this->p_site_id,//站点id
                //'userID' => $userID ,//用户id
                //'state' => $state,//0：停用；1：启用
                //'role_id'=> $role_id,//角色id
               // 'orgID' => $orgID,//企业id        
                //'type' => $type,//1：总公司管理员；2：分公司管理员；3：生态企业管理员；0：其它'
             ),
        );
     * @param array $re_type_arr 返回类型 1总数量，2列表信息 array(1,2);
     * @return array  
        $re_arr = array(
            'sumnum' => $aaa,//数量
            'db_arr' => $aaa,//返回的数组列表[二维]
        );
     *
     */
    public function get_managermsg_arr($select_field = '',$where_arr = array(),$re_type_arr = array()){
        log_message('info', 'into method ' . __FUNCTION__ . '.');
        $CI =& get_instance();
        $CI->load->helper('my_publicfun');
        $CI->load->helper('my_dgmdate'); 
        $CI->load->library('API','','API');
        $CI->load->model('uc_user_admin_model'); 

        $in_where_arr = array(
            //'field' => $select_field,//需要返回的字段;选择的字段数组或'title, content, date'，多个用逗号分隔，默认为 *
            'sumfield' => 'userID',//计算总数量时用到的字段;此处见意只选择一个主键，提高效率
            'sum_where_arr' => $where_arr,//求数量的条件 operateDB 方法的条件形式
            'list_where_arr' => array(),//求列表的条件 operateDB 方法的条件形式[注意如果列表件与求数量条件相同，则为空数组],不为空，列表就有这个
        ); 
        if(!bn_is_empty($select_field)){//有数据
            $in_where_arr['field'] = $select_field;
        }        
        $re_manager_arr =  $CI->uc_user_admin_model->get_db_sumlistarr($re_type_arr,$in_where_arr);//operateDB(2,$data_task);
        return $re_manager_arr;        
    }
     /**
     *
     * @brief 根据管理员id,获得下级管理员及数量信息： 
     * @details 
     * @param int $manager_id  管理员id
     * @param string $select_field  管理员查询字段
     * @param array $where_arr  管理员查询其它条件数组 operateDB 方法的条件形式
        $where_arr = array(
            'where' => array(
                'super_admin_id' => $super_admin_id,//当前管理员id,
                'siteID' => $this->p_site_id,//站点id
                //'userID' => $userID ,//用户id
                //'state' => $state,//0：停用；1：启用
                //'role_id'=> $role_id,//角色id
               // 'orgID' => $orgID,//企业id        
                //'type' => $type,//1：总公司管理员；2：分公司管理员；3：生态企业管理员；0：其它'
             ),
        );
     * @param array $other_arr  
        $other_arr = array(
            'need_getnext_count' => $aa ,//是否统计这一级管理员数量0不统计1统计
            'need_getnext_manager' => $aa ,//是否列出下一级管理员 1列下级管理员2列下下级管理员，所有的
        );            
     * @return array  管理员信息组[一维]
        $re_arr = array(
            'count_num' => 0,//数量            
            'manager_arr' => array(),//管理员数组  下标 childNodeCount 下级数量
        );
     */
     public function get_next_manager_arr($manager_id = '',$select_field = '',$where_arr = array(),$other_arr = array()){
        log_message('info', 'into method ' . __FUNCTION__ . '.');
        $re_arr = array(
            'count_num' => 0,//数量            
            'manager_arr' => array(),//管理员数组
        );
         if(isemptyArray($other_arr)){//如果是空数组
             return $re_arr;
         }
        if(!preg_match('/^[\d]+$/',$manager_id)){
           return $re_arr;
        }
        
        if(bn_is_empty($manager_id)){//没有数据
           return $re_arr;
        }
        $re_type_arr = array(2);//1总数量，2列表信息 array(1,2);
        $need_getnext_count = arr_unbound_value($other_arr,'need_getnext_count',2,0);
        $need_getnext_manager = arr_unbound_value($other_arr,'need_getnext_manager',2,1);

        //获得下级管理员信息、及数组
       $ns_where_arr = $where_arr;
       $ns_where_arr['where']['super_admin_id'] = $manager_id;
       $re_admin_arr = $this->get_managermsg_arr($select_field,$ns_where_arr,$re_type_arr);
       //$total_num = arr_unbound_value($re_admin_arr,'sumnum',2,'');
       $re_db_arr = arr_unbound_value($re_admin_arr,'db_arr',1,array());
        $new_manager_arr = $re_db_arr;
        $ns_count_num = 0;//当前数量
        if($need_getnext_count == 1){
            $ns_count_num = count($re_db_arr);   
        } 
        if($need_getnext_manager == 2 || $need_getnext_count == 1){//需要拿数量或都要获得下下..级
            foreach($new_manager_arr as $k => $v){

                $ns_manager_id = arr_unbound_value($v,'userID',2,'');
                if(bn_is_empty($ns_manager_id)){//没有数据                   
                     $ns_manager_id = arr_unbound_value($v,'id',2,'');   
                }
                if($ns_manager_id > 0){
                    $ns_manager_arr = $this->get_next_manager_arr($ns_manager_id,$select_field,$where_arr,$other_arr);
                    
                    $ns_total_num = arr_unbound_value($ns_manager_arr,'count_num',2,0);
                    $ns_re_db_arr = arr_unbound_value($ns_manager_arr,'manager_arr',1,array());
                    if($need_getnext_count == 1){//需要下一级数量
                        $new_manager_arr[$k]['childNodeCount'] = $ns_total_num;                           
                    }
                    if($need_getnext_manager == 2 ){//2列下下级管理员，只要有都列 
                        $new_manager_arr[$k]['childs'] = $ns_re_db_arr;
                    }
                }
            } 
        }
        $re_arr['count_num'] = $ns_count_num;
        $re_arr['manager_arr'] = $new_manager_arr;
        return $re_arr;
    }
     /**
     *
     * @brief 根据管理员id,获得当前管理员及下级管理员信息： 
     * @details 
     * @param int $type  0 获得当前管理员数组，1当前及下级管理员数组2获得下级管理员数组
     * @param int $manager_id  管理员id
     * @param string $select_field  管理员查询字段
     * @param array $where_arr  管理员查询条件数组
        $where_arr = array(
            'where' => array(
                'super_admin_id' => $super_admin_id,//当前管理员id,
                'siteID' => $this->p_site_id,//站点id
                //'userID' => $userID ,//用户id
                //'state' => $state,//0：停用；1：启用
                //'role_id'=> $role_id,//角色id
               // 'orgID' => $orgID,//企业id        
                //'type' => $type,//1：总公司管理员；2：分公司管理员；3：生态企业管理员；0：其它'
             ),
        );
     * @param array $other_arr  
        $other_arr = array(
            'need_getnext_count' => $aa ,//是否统计这一级管理员数量0不统计1统计
            'need_getnext_manager' => $aa ,//是否列出下一级管理员 1列下级管理员2列下下级管理员，所有的
        ); 
     * @return array  管理员信息组0 获得当前管理员数组[一维]1当前及下级管理员数组[一维]2获得下级管理员数组[二维]
     *
     */
     public function get_local_manager_arr($type = '',$manager_id = '',$select_field = '',$where_arr = array(),$other_arr = array()){
        $CI =& get_instance();
        $CI->load->helper('my_publicfun');
        $CI->load->helper('my_dgmdate'); 
        $CI->load->library('API','','API');
        $CI->load->model('uc_user_admin_model');
        $re_arr = array();
        if(bn_is_empty($type) || bn_is_empty($manager_id)){//没有数据
           return $re_arr;
        }
        $uc_manager_arr = array();
        if($type == 0 || $type == 1){//  0 获得当前管理员数组，1当前及下级管理员数组2获得下级管理员数组
            //获得当前管理员信息
             $get_data = $where_arr;
             $get_data['where']['userID'] = $manager_id;
             if(!bn_is_empty($select_field)){//有数据
                 $get_data['select'] = $select_field;
             }
            $uc_manager_arr = $CI->uc_user_admin_model->operateDB(1,$get_data);
             if(isemptyArray($uc_manager_arr)){//如果是空数组  
                 return $re_arr;
             }
        }
        
        $need_getnext_count = arr_unbound_value($other_arr,'need_getnext_count',2,0);
        $need_getnext_manager = arr_unbound_value($other_arr,'need_getnext_manager',2,1);
        
        $ns_other_arr = $other_arr;
        if($type == 0){//  0 获得当前管理员数组，1当前及下级管理员数组2获得下级管理员数组
            if($need_getnext_count == 0 ){
                $ns_other_arr = array();
            }
        }
        
        
        $ns_total_num = 0;
        $ns_next_manager_arr = array();
        
        if(!isemptyArray($ns_other_arr)){//如果不是空数组 
            $ns_next_manager_arr = $this->get_next_manager_arr($manager_id,$select_field,$where_arr,$ns_other_arr);        
        }
        
        
        $ns_total_num = arr_unbound_value($ns_next_manager_arr,'count_num',2,0);
        $ns_re_db_arr = arr_unbound_value($ns_next_manager_arr,'manager_arr',1,array());
      
        
        if($type == 0 || $type == 1 ){//  0 获得当前管理员数组，1当前及下级管理员数组2获得下级管理员数组
             if(isemptyArray($uc_manager_arr)){//如果是空数组  
                 return $re_arr;
             }

            $re_arr = $uc_manager_arr;            
            if($need_getnext_count == 1){
                $re_arr['childNodeCount'] = $ns_total_num;            
            }
            if($type == 1 ){
                $re_arr['childs'] = $ns_re_db_arr;
            }
        }else{
            $re_arr = $ns_re_db_arr;
        } 
        
        return $re_arr;
    }
}
