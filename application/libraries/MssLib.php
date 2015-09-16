<?php if (!defined('BASEPATH'))	exit('No direct script access allowed');

/**
 * @class MssLib
 * @brief MssLib 类库，主要负责对邮件发送操作。
 * @file MssLib.php
 * @author zouyan <yan.zou@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
class MssLib{
    /**
     *
     * @brief 发送邮件
     * @details 
     * @param 
     * @param array $in_arr  参数数组
     *   array(
     *     'emal_mb_arr' = array(//获得邮件模板需要的参数
     *             'cluster_url' =>  $this->p_cluster_url ,//获得邮件模版的域url
     *             'mb_num' => ,//邮件模板编号1管理员帐号开通(试用版)2管理员帐号开通(正式版)3一般用户帐号开通(正式版)
     *             
     *      )
     *     'emal_param_arr' = array(//邮件模板中需要替换的参数
     *             '' =>  
     *      )
     *     'emal_send_arr' = array(//邮件发送的参数
     *             'receiver_name' =>  收件人 
     *             'receiver_address' => 收件人地址
     *              ...其它需要替换的参数
     *      )
     * )
     * @return boolean/array  false失败 ;TRUE 成功[都成功] ;部分失败，返回失败数组
     *
     */
    public function send_mss($in_arr = array()) {        
        log_message('info', 'into method ' . __FUNCTION__ . '.');
        $CI =& get_instance();
        $CI->load->helper('my_publicfun');
        $CI->load->helper('my_dgmdate'); 
        $CI->load->library('API','','API');

        if(isemptyArray($in_arr)){
            return false;
        }
        $email_mb_arr = arr_unbound_value($in_arr,'emal_mb_arr',1,array());
        $email_param_arr = arr_unbound_value($in_arr,'emal_param_arr',1,array());
        $email_send_arr = arr_unbound_value($in_arr,'emal_send_arr',1,array());
        if(isemptyArray($email_mb_arr) || isemptyArray($email_param_arr)  || isemptyArray($email_send_arr) ){
            return false;
        }
        
        //获得邮件模板信息
        $cluster_url = arr_unbound_value($email_mb_arr,'cluster_url',2,'');
        $mb_num = arr_unbound_value($email_mb_arr,'mb_num',2,''); 
        if(bn_is_empty($cluster_url) || bn_is_empty($mb_num)){//没有数据
            return false;
        }

        $mb_method ='';
        $mb_title = '';//模板标题
        $mb_baoqian_arr = $email_param_arr;//array();//标签
        switch ($mb_num) {
            case 1: //1管理员帐号开通(试用版)
                $mb_method = "admin_open_try";
                $mb_title = '欢迎试用全时sooncore平台';//模板标题
                break;
            case 2: //2管理员帐号开通(正式版)
                $mb_method = "admin_open_official";
                $mb_title = '欢迎试用全时sooncore平台';//模板标题
                break;
            case 3: //3一般用户帐号开通(正式版)
               // $mb_baoqian_arr = array(
                //    'user_name' => 'user_name',//#用户名称#                    
               // );//标签
                $mb_method = "user_open_official";
                $mb_title = '{user_name}，您的sooncore平台帐号已经开通，请立即启用';//模板标题
                break;
        }
        if(bn_is_empty($mb_method)){
            return false;
        }
            
        $mss_mb_content = '';
        $data = '';//type=' . $file_type . '&value=' . urlencode($success_json);
        $api_url = 'http://' . $cluster_url . UC_DOMAIN_DIR . '/mss/' . $mb_method;

        $uc_mss_arr = $CI->API->UCAPI($data,4,array('url' => $api_url));
        if(api_operate_fail($uc_mss_arr)){//失败
            log_message('error', 'UCAPI NO 4 mss is fail.');
            // https(400);
            //exit;
        }else{
            log_message('debug', 'UCAPI NO 4 mss is success.');
            $mss_mb_content = arr_unbound_value($uc_mss_arr,'msg',2,'');
        }
       // echo $mss_mb_content;  
        if(bn_is_empty($mss_mb_content)){
            return false;
        }

        //循环发送邮件
        $fail_arr = array();//发送失败数组
        foreach($email_send_arr as $email_sender_arr){
            $ns_mb_content = $mss_mb_content;            
            $ns_mb_title = $mb_title;
            //替换标签
            foreach($mb_baoqian_arr as $k => $v){
                $ns_value = arr_unbound_value($email_sender_arr,$k,2,'');   
                $ns_mb_content = str_ireplace('{' . $v . '}', $ns_value , $ns_mb_content);
                $ns_mb_title = str_ireplace('{' . $v . '}', $ns_value , $ns_mb_title);
            }

            $receiver_name = arr_unbound_value($email_sender_arr,'receiver_name',2,'');//收件人
            $receiver_address = arr_unbound_value($email_sender_arr,'receiver_address',2,'');//收件人地址
            if(bn_is_empty($ns_mb_title) || bn_is_empty($ns_mb_content)  || bn_is_empty($receiver_name) || bn_is_empty($receiver_address) ){//没有数据
                $fail_arr[] = $email_sender_arr;
                continue;
            }
            $send_arr = array(
                //'id' => aaa,//不写
                'create_time' => dgmdate(time(), 'dt'),// 创建时间
                //'priority' => aaa,// 不写
                'is_handled' => 0,//  0 
                'result_code' => 9,// 9
               // 'update_time' => aaa,// 不写
               // 'postfix_address' => aaa,// 不写
                'sender_name' => UC_SENDER_NAME,//'quanshi.com',//  发件人 quanshi.com
                'receiver_name' => $receiver_name,// 收件人
                'sender_address' => UC_SENDER_ADDRESS,//'quanshi.com',// 发件人邮件地址
                'receiver_address' => $receiver_address,// 收件人邮件地址
                'mail_title' => $ns_mb_title,// 邮件标题
                'mail_content' => $ns_mb_content,// 邮件内容
                //'ss_mail_id' => aaa,//
                //'ss_mail_recipient_id' => aaa,//
                'ss_source' => 0,// 0
               // 'mail_content_ext' => aaa,//
                'type' => 0,// 0 
            );
            //调用保存邮件发送信息到数据库接口
            $send_json = json_encode($send_arr);
            write_test_file( '' . __FUNCTION__ . time() . '.txt' ,$send_json);
            $data = $send_json ;//urlencode($send_json);
            $api_url = 'http://' . $cluster_url ;
            
            $uc_mss_arr = $CI->API->UCAPI($data,5,array('url' => $api_url));
            
            if(api_operate_fail($uc_mss_arr)){//失败
                //失败了，再发送手机短信息 
                
                log_message('error', 'UCAPI NO 5 mss is fail.');
                $fail_arr[] = $email_sender_arr;
            }else{
                log_message('debug', 'UCAPI NO 5 mss is success.');                
            }
        }
        if(isemptyArray($fail_arr)){//空数组
            return true;
        }
        return $fail_arr;

    }
    
    /**
     * @brief 发送邮件
     * @details 
     * @param array          
     * $in_arr = array(
             'mssuser_arr' => array(
                 'id' => $ns_user_id,//用户id
                 'site_name' => $site_name,//站点名称
                 'user_pwd' => $password,//用户登陆密码  
             ),
           'mss_user_ids' => '[1,2,3]',
           'type' => ,//类型1管理员帐号开通(试用版)2管理员帐号开通(正式版)3一般用户帐号开通(正式版)
           'domain_url' => ,//站点所在域的url 如:devcloud.quanshi.com
           'siteURL' => $siteURL,//站点url
         );
     * @return  boolean/array  false失败 ;TRUE 成功[都成功] ;部分失败，返回失败数组
     */
     public function send_user_email($in_arr){
        log_message('info', 'into method ' . __FUNCTION__ . '.');
        $CI =& get_instance();
        $CI->load->helper('my_publicfun');
        $CI->load->helper('my_dgmdate'); 
        $CI->load->library('API','','API');
         if(isemptyArray($in_arr)){//是空数组
             return false;
         }
         $mss_user_arr = arr_unbound_value($in_arr,'mssuser_arr',1,array());
         if(isemptyArray($mss_user_arr)){//不是空数组
             return false;
         }
         $mss_user_ids = arr_unbound_value($in_arr,'mss_user_ids',2,'');//可以为空
         $mss_type = arr_unbound_value($in_arr,'type',2,'');//类型
         $mss_siteURL = arr_unbound_value($in_arr,'siteURL',2,'');//可以为空
         if(bn_is_empty($mss_type) || bn_is_empty($mss_siteURL)){//如果是空
              return false;
         }
         $re_fail_arr = array();//返回失败数组
         if(!isemptyArray($mss_user_arr)){//不是空数组
             //获得账号信息
             $ums_mss_user_arr = array();
             if(bn_is_empty($mss_user_ids)){//空，则重新获得id,格式[1,2,3]
                 $ns_user_ids = '';
                 foreach($mss_user_arr as $ns_k => $ns_v){
                    $ns_user_id = arr_unbound_value($ns_v,'id',2,0);//用户帐号id; 
                    if($ns_user_id > 0 ){
                        if(!bn_is_empty($ns_user_ids)){//不为空，加,号
                            $ns_user_ids .= ',';
                        }
                        $ns_user_ids .= $ns_user_id;
                    }
                 }
                 if(!bn_is_empty($ns_user_ids)){//不为空，加[]号
                     $ns_user_ids = '[' . $ns_user_ids . ']';
                 }
                 $mss_user_ids = $ns_user_ids;
             }
             if(!bn_is_empty($mss_user_ids)){//不为空，则加[]号
                $ns_data = $mss_user_ids;
                $ns_ums_api_arr = $CI->API->UMS_Special_API($ns_data,2);
                $ns_ums_user_arr = array();
                if(!api_operate_fail($ns_ums_api_arr)){//成功
                    log_message('debug', 'ums api rs/users/id/in success.'); 
                    $ns_ums_user_arr = arr_unbound_value($ns_ums_api_arr,'data',1,array());
                }else{//失败
                    log_message('debug', 'ums api rs/users/id/in fail.');                     
                }
                if(!isemptyArray($ns_ums_user_arr)){//不是空数组
                    $emal_send_arr = array();//需要发送邮件的账号信息
                    foreach($mss_user_arr as $m_k => $m_v){                        
                        $m_user_id = arr_unbound_value($m_v,'id',2,0);//用户帐号id;
                        $m_site_name = arr_unbound_value($m_v,'site_name',2,'');//站点名称;
                        $m_user_pwd = arr_unbound_value($m_v,'user_pwd',2,'');//用户密码;   
                        $m_user_arr = array();
                        //获得当前帐号的信息                        
                        foreach($ns_ums_user_arr as $ns_k => $ns_v){
                            $ns_user_id = arr_unbound_value($ns_v,'id',2,0);//用户帐号id;
                            if($ns_user_id == $m_user_id){
                                $m_user_arr = $ns_v;
                                break;
                            }
                        }
                        if(!isemptyArray($m_user_arr)){//不是空数组
                            $loginName = arr_unbound_value($m_user_arr,'loginName',2,'');//用户名称;
                            $displayName = arr_unbound_value($m_user_arr,'displayName',2,'');//显示名称;
                            $user_email = arr_unbound_value($m_user_arr,'email',2,'');//收件人地址;
                            if(!bn_is_empty($user_email)){//如果是空
                               $emal_send_arr[$m_k] = $m_v;//需要发送邮件的账号信息
                               $emal_send_arr[$m_k]['loginName'] = $loginName;//用户名称
                               $emal_send_arr[$m_k]['displayName'] = $displayName;//显示名称
                               $emal_send_arr[$m_k]['email'] = $user_email;//收件人地址;
                               $emal_send_arr[$m_k]['receiver_name'] = $displayName;//收件人
                               $emal_send_arr[$m_k]['receiver_address'] = $user_email;//收件人地址'yan.zou@quanshi.com';//
                               $emal_send_arr[$m_k]['mobileNumber'] = $user_email;//收件人手机号码
                            }else{
                                $re_fail_arr[] = $m_v;//失败
                            }
                            
                        }else{
                            $re_fail_arr[] = $m_v;//失败
                        }
                    }
                    $emal_param_arr = array(//邮件模板中需要替换的参数
                        'site_name' => 'site_name',
                        'user_pwd' => 'user_pwd',
                        'loginName' => 'loginName',
                        'displayName' => 'displayName',
                        'email' => 'email',
                        'receiver_name' => 'receiver_name',
                        'receiver_address' => 'receiver_address',
                        'mobileNumber' => 'mobileNumber',                        
                    );
                    if(!isemptyArray($emal_send_arr)){//不是空数组
                        //批量发送邮件
                        //$CI->load->library('MssLib','','MssLib');
                        $mss_in_arr = array(
                                  'emal_mb_arr' => array(//获得邮件模板需要的参数
                                        'cluster_url' =>  CLUSTER_URL,//$this->p_cluster_url ,//获得邮件模版的域url
                                        'mb_num' => $mss_type,//邮件模板编号1管理员帐号开通(试用版)2管理员帐号开通(正式版)3一般用户帐号开通(正式版)
                                  ),
                                  'emal_param_arr' => $emal_param_arr,//邮件模板中需要替换的参数
                                  'emal_send_arr' => $emal_send_arr,//邮件发送的参数
                              );
                        $fail_arr = $this->send_mss($mss_in_arr);
                        //false失败 ;TRUE 成功[都成功] ;部分失败，返回失败数组
                        if(is_array($fail_arr)){//是数组
                            foreach($fail_arr as $ns_f_k => $ns_f_v){
                               $re_fail_arr[]= $ns_f_v;//返回失败数组 
                            }
                        }else{
                            if(!$fail_arr){//失败
                                foreach($emal_send_arr as $ns_f_k => $ns_f_v){
                                   $re_fail_arr[]= $ns_f_v;//返回失败数组 
                                }
                            }
                        }
                        
                    }
                }
             }
         }  
        if(isemptyArray($re_fail_arr)){//空数组
            return true;
        }
         return $re_fail_arr;//返回失败数组
     }
}
