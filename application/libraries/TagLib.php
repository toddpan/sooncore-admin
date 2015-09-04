<?php if (!defined('BASEPATH'))	exit('No direct script access allowed');

/**
 * @class TagLib
 * @brief Tag 类库，主要负责对标签接口类的获取等操作。
 * @file TagLib.php
 * @author zouyan <yan.zou@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
class TagLib  {
    
    /**
     *
     * @brief 根据当前站点ID获得员工标签信息--?需要接口2.2.1： 
     * @details 
     * -# 根据当前站点ID，通过接口获得员工标签信息。
     * @param int $site_id  当前站点ID
     * @param int $tag_type  标签类型
     * @return array $datearr 获得员工标签信息
     *
     */
      public function getTags($site_id,$tag_type){
          $datearr=array();
          //通过接口获得
          return $datearr;
      }
    /**
     *
     * @brief 当前站点ID保存员工标签设置信息--?需要接口2.3.2： 
     * @details 
     * @param int $site_id  当前站点ID
     * @param int $tag_type  标签类型
     * @param int $tag_id  标签标识0新加，值为对应修改标签值
     * @param json $tagsData  新加标签设置
     * @return array $datearr 保存是否成功
     *
     */
      public function addTags($site_id,$tag_type,$tag_id,$tagsData){
          $datearr=array();
          //通过接口获得
          return $datearr;
      }
    /**
     *
     * @brief 当前站点ID保存员工标签设置信息--?需要接口2.3.2： 
     * @details 
     * @param array $tag_arr  当前标签数组
            array(//下标可以随便给,
                "N" => array(
                    "tag_name"=> "birthday", //自定义标签名称
                    "tag_id"=>"1", //自定义标签id
                    "suffix"=>"N", //下标
                    "value"=>19840229, //自定义标签值
                    "regex"=>"/^[\\s\\S]{1,100}$/"//正则
                ), 
                "sys" => array(//此数组可以没有
                    "customerCode"=> "024014", //客户编码
                    "siteID"=> "666897", //站点id
                    "site_name"=>"北京奥的斯电梯有限公司", //站点名称
                    "accountId"=> "118093", //当前用户分帐id
                    "siteURL"=> "xianUC.quanshi.com", //站点地址
                    "contractId"=> "111357"//合同id
                )
          )
     * @param array $other_arr  其它数组
     *       array(
     *          'user_id' => ,//当前用户id
     *          'session_id' => ,//sessionid
     *          'sys_user_id' => //登陆的系统管理员id
     *       )
     * @return boolean 保存是否成功 true 成功 false 失败
     *
     */
      public function save_tags($tag_arr = array(),$other_arr = array()){
        log_message('info', 'into method ' . __FUNCTION__ . '.');
        $CI =& get_instance();
        $CI->load->helper('my_publicfun');
        $CI->load->helper('my_dgmdate'); 
        $CI->load->library('API','','API');
        $CI->load->model('uc_user_tag_value_model');  
        $CI->load->model('uc_user_tags_model'); 
        $user_id = arr_unbound_value($other_arr,'user_id',2,'');
        $sys_user_id = arr_unbound_value($other_arr,'sys_user_id',2,'');
        if(!preg_match('/^[\d]+$/',$user_id)){
            return false;
        } 
        $session_id = arr_unbound_value($other_arr,'session_id',2,'');

        //保存
        $tag_id_arr = array(0);
        $new_tag_arr = array();//存放tags的键值对（Json串）tag_id=tagID , tag_value=tagValue,tag_name= '
        $tag_scope_arr = array();
        foreach ($tag_arr as $t_k => $v){
            if(strtolower($t_k)!='sys'){//不是'sys'
                $tag_id = arr_unbound_value($v,'tag_id',2,'');                
                $tag_name = arr_unbound_value($v,'tag_name',2,''); 
                $tag_value = arr_unbound_value($v,'value',2,'');
                $tag_id_arr[] = $tag_id;
                //根据标签id，获得标签填写人
              $tag_scope = arr_unbound_value($tag_scope_arr,$tag_id,2,'');
              if(bn_is_empty($tag_scope)){//没有数据
                $sel_data = array(  
                     'select' =>'tag_scope',
                     'where' => array(
                          'id' => $tag_id                            
                     )
                 );
                 $sel_arr =  $CI->uc_user_tags_model->operateDB(1,$sel_data);
                 $tag_scope = arr_unbound_value($sel_arr,'tag_scope',2,1);
                 $tag_scope_arr[$tag_id] = $tag_scope;
              }
                //1、有记录则更新记录，没记录则新加；
                $where_arr = array(
                    'user_id' => $user_id,
                    'tag_id' => $tag_id,    
                       );
                $modify_arr = array(
                    'user_id' => $user_id,//该客户的站点ID
                    'tag_id' => $tag_id,
                    'tag_name' => $tag_name,
                    'tag_value' => $tag_value,                    
                    'tag_scope' => $tag_scope,//0-所有人1-管理员 2-员工填写
                    'tag_type' => 2,//1-基本标签2-自定义
                       ); 
                $insert_arr = $modify_arr;
                //$insert_arr['status'] = 0 ;
                $insert_arr['created'] = time();//dgmdate(time(), 'dt');
                $re_num = $CI-> uc_user_tag_value_model -> updata_or_insert(1,'id',$where_arr,$modify_arr,$insert_arr);

                switch ($re_num) {//-1 更新记录成功 -2更新操作失败 -3新加的记录id 新加记录成功 -4新加操作失败 -5 没有操作
                   case -2:  
                   case -4: 
                        $err_msg = ' update/insert uc_user_tag_value_model ' . json_encode($where_arr). ' fail.';
                        log_message('error', $err_msg);  
                          break;
                   default:
                        $err_msg = ' update/insert uc_user_tag_value_model ' . json_encode($where_arr). ' success.';
                        log_message('debug', $err_msg);  
                       break;
               }
            }
            
        }
        //删除其它多余的标签
        if(isemptyArray($tag_arr)){//空数组，则删除
            $where_arr = array(
                'user_id' => $user_id
                   );
           $del_arr = array(
               'where' => $where_arr,
               'where_not_in' => array('tag_id'=>$tag_id_arr)
           );
           $re_del_arr = $CI-> uc_user_tag_value_model -> operateDB(4,$del_arr); 
           if(db_operate_fail($re_del_arr)){//失败
              // return false;
           }else{
              // return true;
           } 
           return true;
        }
        //TODO 发送消息
        //接口参参数
//        $data = 'user_id=' . $sys_user_id . '&session_id=' . $session_id . '&target_user_id=' . $user_id . '&data=' . json_encode($new_tag_arr); 
//        //调用用户标签更新接口
//        $api_arr = $CI->API->UCCServerAPI($data,13);
//        if(api_operate_fail($api_arr)){//失败
//            log_message('error', 'uccapi user/tagUpdate fail.' . $data); 
//        }else{
//            log_message('debug', 'uccapi user/tagUpdate success.' . $data); 
//        }     
        
        

      }
    /**
     *
     * @brief 根据用户id获得他的自定定标签及值： 
     * @details
     * @param int $user_id  当前用户id 
     * @return array 返回数组
          array(
                "tag_name"=> $tag_name, //自定义标签名称
                "tag_id"=>$tag_id, //自定义标签id
                "value"=> $tag_value, //自定义标签值
            );
     *
     */
      public function get_tag_arr($user_id = ''){
        log_message('info', 'into method ' . __FUNCTION__ . '.');
        $CI =& get_instance();
        $CI->load->helper('my_publicfun');
        $CI->load->helper('my_dgmdate'); 
        $CI->load->library('API','','API');
        $re_arr = array();
        if(!preg_match('/^[\d]+$/',$user_id)){
            return $re_arr;
        }  
        $CI->load->model('uc_user_tag_value_model');
        
//        $sel_field = 'tag_id,tag_name,tag_value';
//        $where_arr = array(
//            'userID' => $user_id,
//        );
//        $sel_arr = $CI->uc_user_tag_value_model->get_db_arr($where_arr,$sel_field);
        $data_admin = array(
            'select' =>'tag_id,tag_name,tag_value',
            'where' => array('user_id =' => $user_id),
        );
        $admin_arr =  $CI->uc_user_tag_value_model->operateDB(2,$data_admin);
         if(!isemptyArray($admin_arr)){//有用户信息
             $re_arr = $admin_arr;
         }
//         $value = arr_unbound_value($sel_arr,'value',2,'');
//         $tag_arr = array();
//         if(!bn_is_empty($value)){//有值
//             if(!is_not_json($value)){//是json串
//               $tag_arr = json_decode($value, true);
//             }             
//         }
//         foreach($tag_arr as $k => $v){
//            $tag_id = arr_unbound_value($v,'tag_id',2,'');
//            $tag_value = arr_unbound_value($v,'tag_value',2,'');
//            $tag_name = arr_unbound_value($v,'tag_name',2,''); 
//            $re_arr[] = array(
//                    "tag_name"=> $tag_name, //自定义标签名称
//                    "tag_id"=>$tag_id, //自定义标签id
//                    "tag_value"=> $tag_value, //自定义标签值
//            );
//         }
         return $re_arr;
      }
}
