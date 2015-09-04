<?php if (!defined('BASEPATH'))	exit('No direct script access allowed');

/**
 * @class GetIP
 * @brief GetIP 获得客户端或服务器端ip信息
 * @file GetIP.php
 * @author zouyan <yan.zou@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
class GetIP{ 
    /**
     * @brief 获得客户端ip地址
     * @details
     * @return string 客户端ip地址
     *  
     */
     function clientIP_old(){     
      $cIP = getenv('REMOTE_ADDR');   //$_SERVER['REMOTE_ADDR'];//客户端IP，有可能是用户的IP，也有可能是代理的IP。
      $cIP1 = getenv('HTTP_X_FORWARDED_FOR'); $_SERVER["HTTP_X_FORWARDED_FOR"] ; //用户是在哪个IP使用的代理，可能存在，可以伪造。 
      $cIP2 = getenv('HTTP_CLIENT_IP');   //代理端的IP，可能存在，可伪造。
      $cIP1 ? $cIP = $cIP1 : null;   
      $cIP2 ? $cIP = $cIP2 : null;   
      return $cIP;   
     }   
    function clientIP(){
        if(getenv("HTTP_CLIENT_IP")&&strcasecmp(getenv("HTTP_CLIENT_IP"),"unknown"))
             $ip = getenv("HTTP_CLIENT_IP");
        else if(getenv("HTTP_X_FORWARDED_FOR")&&strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if(getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
            $ip = getenv("REMOTE_ADDR");
        else if (isset($_SERVER['REMOTE_ADDR'])&& $_SERVER['REMOTE_ADDR']&&strcasecmp($_SERVER['REMOTE_ADDR'],"unknown"))
             $ip = $_SERVER['REMOTE_ADDR'];
        else
             $ip = "unknown";
        return($ip);
    }

    /**
     * @brief 获得服务端ip地址
     * @details
     * @return string 服务端ip地址
     *  
     */
     function serverIP(){  
       return gethostbyname($_SERVER["SERVER_NAME"]); //这个变量无论在服务器端还是客户端均能正确显示  
     }  
    /**
     * 获取客户端IP地址
     * @return string
     */ 
    function get_client_ip() { 
        if(getenv('HTTP_CLIENT_IP')){ 
            $client_ip = getenv('HTTP_CLIENT_IP'); 
        } elseif(getenv('HTTP_X_FORWARDED_FOR')) { 
            $client_ip = getenv('HTTP_X_FORWARDED_FOR'); 
        } elseif(getenv('REMOTE_ADDR')) {
            $client_ip = getenv('REMOTE_ADDR'); 
        } else {
            $client_ip = $_SERVER['REMOTE_ADDR'];
        } 
        return $client_ip; 
    }   
    /**
    * 获取服务器端IP地址
     * @return string
     */ 
    function get_server_ip() { 
        if (isset($_SERVER)) { 
            if($_SERVER['SERVER_ADDR']) {
                $server_ip = $_SERVER['SERVER_ADDR']; 
            } else { 
                $server_ip = $_SERVER['LOCAL_ADDR']; 
            } 
        } else { 
            $server_ip = getenv('SERVER_ADDR');
        } 
        return $server_ip; 
    }

}   
  