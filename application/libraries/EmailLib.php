<?php if (!defined('BASEPATH'))	exit('No direct script access allowed');

/**
 * @class EmailLib
 * @brief EmailLib 类库，主要负责对邮件发送操作。
 * @file EmailLib.php
 * @author zouyan <yan.zou@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
class EmailLib{
    /**
     * @brief 发送帐号开通邮件
     * @details 
     * @param array          
     * $in_arr = array(
             'mssuser_arr' => array(
                 'id' => $ns_user_id,//用户id
                 'site_name' => $site_name,//站点名称
                 'user_pwd' => $password,//用户登陆密码 
                 'type' => $type//类型1管理员帐号开通(试用版)2管理员帐号开通(正式版)3一般用户帐号开通(正式版)
             ),
           'mss_user_ids' => '[1,2,3]',
           'domain_url' => ,//站点所在域的url 如:devcloud.quanshi.com
           'siteURL' => $siteURL,//站点url
           'customer_code' => $customer_code,//客户编码
         );
     * @return  boolean/array  false失败 ;TRUE 成功[都成功] ;部分失败，返回失败数组
     */
     public function send_user_email($in_arr){
        return true;
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
         $domain_url = arr_unbound_value($in_arr,'domain_url',2,'');
         $mss_siteURL = arr_unbound_value($in_arr,'siteURL',2,'');
         $mss_customer_code = arr_unbound_value($in_arr,'customer_code',2,'');
         if( bn_is_empty($domain_url) || bn_is_empty($mss_siteURL) || bn_is_empty($mss_customer_code)){//如果是空
              return false;
         }
        $url_arr = array(
            'url' => $domain_url,//需要处理的url
            'pre' => 'http://',//url前部，如果不是则加上，是则不加;如http://、http://www 
            'back' =>'',//url后部，如 /  、目录：/ucadmin/
        );
        $domain_url = url_http($url_arr); // http://baidu.com

        $url_arr = array(
            'url' => $mss_siteURL,//需要处理的url
            'pre' => 'http://',//url前部，如果不是则加上，是则不加;如http://、http://www 
            'back' =>'/',//url后部，如 /  、目录：/ucadmin/ 
        );
        $mss_siteURL = url_http($url_arr);  // http://baidu.com/
         
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
                $ns_ums_api_arr = $CI->API->UMS_Special_API($ns_data,2);//根据用户IDs查询用户
                $ns_ums_user_arr = array();
                if(!api_operate_fail($ns_ums_api_arr)){//成功
                    log_message('debug', 'ums api rs/users/id/in success.'); 
                    $ns_ums_user_arr = arr_unbound_value($ns_ums_api_arr,'data',1,array());
                }else{//失败
                    log_message('debug', 'ums api rs/users/id/in fail.');                     
                }
                if(!isemptyArray($ns_ums_user_arr)){//不是空数组
                    //$emal_send_arr = array();//需要发送邮件的账号信息
                    foreach($mss_user_arr as $m_k => $m_v){                        
                        $m_user_id = arr_unbound_value($m_v,'id',2,0);//用户帐号id;
                        $m_site_name = arr_unbound_value($m_v,'site_name',2,'');//站点名称;
                        $m_user_pwd = arr_unbound_value($m_v,'user_pwd',2,'');//用户密码;  
                        $mss_type = arr_unbound_value($m_v,'type',2,'');//类型1管理员帐号开通(试用版)2管理员帐号开通(正式版)3一般用户帐号开通(正式版)
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
                              // $emal_send_arr[$m_k] = $m_v;//需要发送邮件的账号信息
                              // $emal_send_arr[$m_k]['loginName'] = $loginName;//用户名称
                              // $emal_send_arr[$m_k]['displayName'] = $displayName;//显示名称
                              // $emal_send_arr[$m_k]['email'] = $user_email;//收件人地址;
                              // $emal_send_arr[$m_k]['receiver_name'] = $displayName;//收件人
                              // $emal_send_arr[$m_k]['receiver_address'] = $user_email;//收件人地址'yan.zou@quanshi.com';//
                              // $emal_send_arr[$m_k]['mobileNumber'] = $user_email;//收件人手机号码
                               //调用接口发送邮件
                                $send_api_arr = array(
                                    'send_type' => $mss_type,
                                );
                                switch ($mss_type) {
                                    case 1: //1管理员帐号开通(试用版)
                                        $send_arr = array(
                                            'title' => $m_site_name,//站点名称
                                            'user_name' => $displayName,//用户名称
                                            'auth_user' => $loginName,//帐号
                                            'password' => $m_user_pwd,//密码
                                            'login_url' => $mss_siteURL . 'ucadmin/login/loginPage',//登陆地址
                                            'start_time' => $aaa, //开始时间
                                            'end_time' => $aaa,//结束时间
                                            'amount' => $aa,//
                                            'email' => $user_email,//收件人地址
                                        ); 
                                        break; 
                                    case 2: //2管理员帐号开通(正式版)
                                        $send_arr = array(
                                            'title' => $m_site_name,//站点名称
                                            'user_name' => $displayName,//用户名称
                                            'auth_user' => $loginName,//帐号
                                            'password' => $m_user_pwd,//密码
                                            'login_url' => $mss_siteURL . 'ucadmin/login/loginPage',//登陆地址
                                            'email' => $user_email,//收件人地址
                                        );                                                                             
                                        break; 
                                    case 3: //3一般用户帐号开通(正式版)
                                        $send_arr = array(
                                            'customer_code' => $mss_customer_code,//客户编码
                                            'title' => $m_site_name,//站点名称
                                            'user_name' => $displayName,//用户名称
                                            'auth_user' => $loginName,//帐号
                                            'password' => $m_user_pwd,//密码
                                            'login_url' => $mss_siteURL . 'ucadmin/login/loginPage',//登陆地址
                                            'email' => $user_email,//收件人地址
                                        ); 
                                        break; 

                                }
                                $send_api_arr['send_data'] = $send_arr;   
                                //调用保存邮件发送信息到数据库接口
                                $send_json = json_encode($send_api_arr);
                               // write_test_file( '' . __FUNCTION__ . time() . '.txt' ,$send_json);
                                $data = $send_json ;//urlencode($send_json);
                                $api_url = $domain_url ;
                                $uc_mss_arr = $CI->API->UCAPI($data,7,array('url' => $api_url));
                                if(api_operate_fail($uc_mss_arr)){//失败
                                    //失败了，再发送手机短信息
                                    log_message('error', 'UCAPI NO 7 mss is fail.');
                                    $fail_arr[] = $email_sender_arr;
                                }else{
                                    log_message('debug', 'UCAPI NO 7 mss is success.');                
                                }
                               
                            }else{
                                $re_fail_arr[] = $m_v;//失败
                            }
                            
                        }else{
                            $re_fail_arr[] = $m_v;//失败
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
