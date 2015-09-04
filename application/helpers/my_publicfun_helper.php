<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 * @brief 专门判别多维数组是否为空。为空返回true,非空返回false
 * @details 
 * @param array $array 需要判断的数组
 * @return boolean  为空返回true,非空返回false
 *
 */
if ( ! function_exists('isemptyArray'))
{
    function isemptyArray($array){
        if (! is_array($array) ){//不是数组
          return true;
        }
        if(bn_is_empty($array)){//没有值
          return true;
        }
        if (!isset($array)){//不存在
          return true;
        }

       // var_dump($array) ;
        foreach ($array as $value){
          if(is_array($value)){//是数组
              if(count($value)){
                  if(!isemptyArray($value)){
                   return false;
                  }
              }
          }else{//不是数组
              $value=trim($value);
              if(!bn_is_empty($value)){
                 return false;
              }
          }
          //$i++;
       }
       return true;
    }
}
/**
 *
 * @brief 传入变量的值，如果为空，则返回默认值,如果是数组，则数组为空时，并默认值也是数组时，才返回默认数组
 * @param string $in_value 传入值
 * @param string $default_value  为空时的默认值
 * @return string  值
 *
 */
if ( ! function_exists('empty_to_value'))
{
    function empty_to_value($in_value = '', $default_value = ''){
        $re_value = $in_value;
        if(is_array($in_value))//是数组
        {
            if(isemptyArray($in_value)){//空数组            
            {
                if(is_array($default_value))//是数组
                   $re_value = $default_value;
                }
            }                
        }else{
           if(bn_is_empty($in_value)){
               $re_value = $default_value;
           }
        }
        return $re_value;
    }
}
/**
 *
 * @brief 是否为 ""、NULL、FALSE、array()、var $var、未定义; 以及没有任何属性的对象都将被认为是空的
 * 、0、"0" 认为是不为空
 * @details 
 * @param string $record 需要判断的数据
 * @return boolean  为空返回true,非空返回false
 *
 */
if ( ! function_exists('bn_is_empty'))
{
    function bn_is_empty($record){
        if(!isset($record)){//变量不存在
            //echo '变量不存在';
            return TRUE;
        }
        //为空，排除是0的情况
        if(empty($record)){
            if(($record === 0) || ($record === '0')){
               // echo '变量为0';
                return FALSE;
            }else{                
                // echo '变量为空';
                return TRUE;
            }
        }else{
            //echo '变量不为空';
            return FALSE;
        }
    }
}

/**
 *
 * @brief 跳转函数
 * @details 
 * @param int $type 跳转类型
 * @param string $tishitxt 提示文字
 * @param string $go_url 跳转地址
 * @param array $other_arr 其它数组信息
 * @return null
 *
 */
if ( ! function_exists('gotourl'))
{
    function gotourl($type = 1 ,$tishitxt = '' , $go_url = '' , $other_arr = array()){
        log_message('debug', ' ' . __FUNCTION__ . ' $type =' . $type . ' $tishitxt =' . $tishitxt . ' $go_url =' . $go_url . ' $other_arr =' . any_to_str($other_arr) . '.');
        switch ( $type ) { 
            case 1: //仅js提示，并停止继续执行
                echo '<script>alert("' . $tishitxt . '");</script>';
                die();
                break; 
            case 2: //仅js提示，可以继续执行
                echo '<script>alert("' . $tishitxt . '");</script>';
                break; 
            case 3: //js提示，并跳转到指定地址,并停止继续执行
                echo '<script>alert("' . $tishitxt . '");location = "' . $go_url .'";</script>';
                die();
                break; 
            case 4: //直接跳转到指定地址,注意一般不用此功能，直接在程序中用redirect来做跳转
                echo '<script>location = "' . $go_url .'";</script>';
                die();
                break; 
            case 41: //直接跳转到指定地址,注意一般不用此功能，直接在程序中用redirect来做跳转
                echo '<script>parent.location.href= "' . $go_url .'";</script>';
                die();
                break; 
            case 5: //直接输出提示，并停止继续执行
                echo $tishitxt;
                die();
                break; 
            case 6: //直接输出提示，可以继续执行
                echo $tishitxt;
                break; 
            case 7: //返回json串{"error_id":"错误id/name;如果多个，则为值为第一个,如果为空，则不需要获得焦点","prompt_text":"错误提示内容"}
                echo json_encode($other_arr);
                die();
                break;
            case 8: //返回json串,同时还续继执行
                return json_encode($other_arr);
                break;

        }  
    }
}
/**
 *
 * @brief 表单处理返回json错误提示
 * @details 
 * @param int $code 0代表没有错误1代表有错误
 * @param string $error_id 错误id/name;如果多个，则为值为第一个,如果为空，则不需要获得焦点
 * @param string $prompt_text 错误提示内容
 * @param string $othe_arr_value 其它数组值 格式：arr('键值名'=>'数组值'..)
 * @return null
 *
 */
if ( ! function_exists('form_json_msg'))
{
    function form_json_msg($code = 1,$error_id = '',$prompt_text = '',$othe_arr_value = ''){
        $err_data = array(
            'code' => $code,
            'error_id' => $error_id,
            'prompt_text' => $prompt_text,  
            'other_msg' => $othe_arr_value,
        );            
        gotourl(7,'','',$err_data); 
    }
} 
/**
 *
 * @brief api处理返回json错误提示
 * @details 
 * @param int $code 0代表没有错误-1代表有错误
 * @param array $re_data 其它数组值 格式：arr('键值名'=>'数组值'..)
 * @param int $is_continue 是否继续 0 输出不继续1返回继续
 * @return null
 *
 */
if ( ! function_exists('api_json_msg'))
{
    function api_json_msg($code = 1,$re_data = array(),$is_continue = 0){
        $err_data = array(
            'code' => $code,
            'data' => $re_data,
        );            
        $ns_msg = gotourl(8,'','',$err_data);
        if($is_continue == 0){
            echo $ns_msg;
            die();
        }else{
            return $ns_msg;
        }
    }
} 
/**
 *
 * @brief 对数据表增、删、改是否失败
 * @details 
 * @param array $oper_arr 操作返回的结果数组
 * @return int TRUE失败 FALSE成功
 *
 */
if ( ! function_exists('db_operate_fail'))
{
    function db_operate_fail($oper_arr){
        $is_fail = TRUE;//0成功1失败
        if(is_array($oper_arr)){
            $is_success = isset($oper_arr['is_success'])?$oper_arr['is_success']:0;//0失败1成功
            if($is_success == 1 || $is_success == '1'){//0失败1成功
               $is_fail = FALSE;//0成功1失败
            }                   
        }
        return $is_fail;
    }
}  
/**
 *
 * @brief 从API接口获得数据是否失败
 * @details 
 * @param array $oper_arr 操作返回的结果数组code 0 成功 非0失败
 * @return Boolean TRUE失败 FALSE成功
 *
 */
if ( ! function_exists('api_operate_fail'))
{
    function api_operate_fail($oper_arr){
        $is_fail = TRUE;//TRUE失败 FALSE成功
        if(is_array($oper_arr)){
            $ns_code = isset($oper_arr['code'])?$oper_arr['code']:-1;
            if($ns_code == 0 || $ns_code == '0'){//成功
               $is_fail = FALSE;//0成功1失败
            }
        }
        return $is_fail;
    }
} 
/**
 *
 * @brief API接口，获得POST过来的数据
 * @details 
 * @return null
 *
 */
if ( ! function_exists('api_get_post'))
{
    function api_get_post(){
        $post_data = file_get_contents("php://input");
        if(bn_is_empty($post_data)){
            $post_data = $GLOBALS['HTTP_RAW_POST_DATA'];
        }
        return $post_data;
    }
}  


/** 
* HTTP Protocol defined status codes 
* HTTP协议状态码,调用函数时候只需要将$num赋予一个下表中的已知值就直接会返回状态了。 
* @param int $num 
* 
*/ 
if ( ! function_exists('https'))
{
    function https($num) { 
        $http = array ( 
            100 => "HTTP/1.1 100 Continue", 
            101 => "HTTP/1.1 101 Switching Protocols", 
            200 => "HTTP/1.1 200 OK", 
            201 => "HTTP/1.1 201 Created", 
            202 => "HTTP/1.1 202 Accepted", 
            203 => "HTTP/1.1 203 Non-Authoritative Information", 
            204 => "HTTP/1.1 204 No Content", 
            205 => "HTTP/1.1 205 Reset Content", 
            206 => "HTTP/1.1 206 Partial Content", 
            300 => "HTTP/1.1 300 Multiple Choices", 
            301 => "HTTP/1.1 301 Moved Permanently", 
            302 => "HTTP/1.1 302 Found", 
            303 => "HTTP/1.1 303 See Other", 
            304 => "HTTP/1.1 304 Not Modified", 
            305 => "HTTP/1.1 305 Use Proxy", 
            307 => "HTTP/1.1 307 Temporary Redirect", 
            400 => "HTTP/1.1 400 Bad Request", 
            401 => "HTTP/1.1 401 Unauthorized", 
            402 => "HTTP/1.1 402 Payment Required", 
            403 => "HTTP/1.1 403 Forbidden", 
            404 => "HTTP/1.1 404 Not Found", 
            405 => "HTTP/1.1 405 Method Not Allowed", 
            406 => "HTTP/1.1 406 Not Acceptable", 
            407 => "HTTP/1.1 407 Proxy Authentication Required", 
            408 => "HTTP/1.1 408 Request Time-out", 
            409 => "HTTP/1.1 409 Conflict", 
            410 => "HTTP/1.1 410 Gone", 
            411 => "HTTP/1.1 411 Length Required", 
            412 => "HTTP/1.1 412 Precondition Failed", 
            413 => "HTTP/1.1 413 Request Entity Too Large", 
            414 => "HTTP/1.1 414 Request-URI Too Large", 
            415 => "HTTP/1.1 415 Unsupported Media Type", 
            416 => "HTTP/1.1 416 Requested range not satisfiable", 
            417 => "HTTP/1.1 417 Expectation Failed", 
            500 => "HTTP/1.1 500 Internal Server Error", 
            501 => "HTTP/1.1 501 Not Implemented", 
            502 => "HTTP/1.1 502 Bad Gateway", 
            503 => "HTTP/1.1 503 Service Unavailable", 
            504 => "HTTP/1.1 504 Gateway Time-out" 
        ); 
        header($http[$num]); 
    }
}
/**
 *
 * @brief 重新整理信息数组,如果源数组没有code,想要返回成功,则需要人工先在数组中加入code => 0
 * @details 
 * @param array $cod_arr 信息数组
 * @return array  整理的信息数组 
 * 
 *
 */
if ( ! function_exists('re_arr'))
{
    function res_arr($cod_arr){
        $ns_res_arr = $cod_arr;
        if(!is_array($cod_arr)){//不是数组
            log_message('error', 'res_arr function not is array.'); 
            $ns_res_arr = array('code' => -1);                      
        }
        //判断数组是否有code下标的
        if(!isset($ns_res_arr['code'])){//不存在
            $ns_res_arr['code'] = -1;
        }
       return $ns_res_arr; 
    }
}
//正则处理方法
/**
 *
 * @brief 判断值是否不满足正则
 * @details 
 * @param string $preg_reg 正则
 * @param string $preg_value 需要判断的值
 * @return boolean TRUE 不匹配 FALSE匹配
 * 
 *
 */
if ( ! function_exists('preg_match_fail'))
{
    function preg_match_fail($preg_reg,$preg_value){
        if(!preg_match($preg_reg, $preg_value)){//有不匹配的
            return true;
        }else{
            return false;
        }
    }
}

//把测试信息输出到文件
/**
 *
 * @brief 把测试信息输出到文件
 * @details 
 * @param string $filename 生成的文件名
 * @param string $content 生成文件内容
 * @return null
 * 
 *
 */
if ( ! function_exists('write_test_file'))
{
    function write_test_file($filename,$content){
        if(IS_OPEN_TEST == 1){ //是否输出
            if(bn_is_empty($filename)){
                 $filename = time() . '.txt';
             }
             file_put_contents(  $filename ,$content);//重新写入字符串  
        }
    }
}
//把测试信息输出到文件
/**
 *
 * @brief 获得数组指定下标的值[可能是json串,可能是array,可能是字符串]
 * @details 
 * @param array $arr_data 需要获得的数组[注意是一维数组]
 * @param string $unbound_value 数组下标
 * @param int $re_type 返回类型0json串1数组[默认]2字符
 * @param string $default_value 默认值
 * 
 * @return null
 * 
 *
 */
if ( ! function_exists('arr_unbound_value'))
{
    function arr_unbound_value($arr_data,$unbound_value = 0,$re_type = 1,$default_value = ''){
        $re_value = $default_value;   
        switch ($re_type) {
            case 0;//返回类型0json串
                if(is_array($re_value)){//是数组,则转换
                    $re_value = json_encode($re_value);
                }
                if(is_not_json($re_value)){//不是json串,返回空串
                    $re_value = '[]';
                }
                break;
            case 2;//字符
                break;
            default://返回1数组
                if(!is_array($re_value)){//不是数组,则返回空数组
                    $re_value = array(); 
                } 
               break;
         }
        if(!isset($arr_data)){//不存在
            return $re_value; 
        }
        if(!is_array($arr_data)){//不是数组
            return $re_value; 
        }
        $ns_data = isset($arr_data[$unbound_value])?$arr_data[$unbound_value]:$default_value;        
        if(!bn_is_empty($ns_data)){//不为空，则转为数组
            $re_value = $ns_data;
            $ns_is_arr = 0;//是否是数组0 不是1是
            if(is_array($re_value)){//是否组
                $ns_is_arr = 1;
            }            
             switch ($re_type) {
                 case 0;//返回类型0json串
                     if($ns_is_arr == 1)//是数组
                     {
                       $re_value = json_encode($re_value);
                     }
                    if(is_not_json($re_value)){//不是json串,给默认值 
                        $re_value = $default_value;                   
                        
                    }
                    if(is_not_json($re_value)){//不是json串 ,给空json串                                         
                        $re_value = '[]'; 
                    }
                     break;
                 case 2;//2字符
                     break;
                 default://返回1数组
                     if($ns_is_arr == 0)//不是数组
                     {
                         if(!is_not_json($re_value)){//是json串，则转为数组
                             $re_value = json_decode($re_value, true);//转换为数组
                         }
                         
                         if(!is_array($re_value)){//不是数组
                            $re_value = $default_value;//返回默认值
                            if(!is_array($re_value)){//默认值也不是数组,则返回空数组
                                $re_value = array(); 
                            }
                         }
                     }
                    break;
             }
        }
        return $re_value;
    }
}

/**
 *
 * @brief 判断数据不是JSON格式
 * @details 
 * @param string $str 需要判断的字符
 * @return boolean true 不是json格式,false 是json格式
 * 
 *
 */
if ( ! function_exists('is_not_json'))
{
    //判断数据不是JSON格式:
    function is_not_json($str){ 
        return is_null(json_decode($str));
    }
}
/**
 *
 * @brief 获得当前运行页面的url信息数组
 * @details 
 * @return array url信息数组
 * 
 *
 */
if ( ! function_exists('get_url'))
{
     function get_url(){
        $re_url_arr = array();
        #测试网址:     http://localhost/ucadmin/test/get_url?id=5 http://localhost/blog/testurl.php?id=5
        //获取域名或主机地址 
        $http_host = isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:'';//localhost
        //echo '$http_host= ' . $http_host . '<br/>';
        $re_url_arr ['http_host'] = $http_host ;
        //获取网页地址 
        $php_self = isset($_SERVER['PHP_SELF'])?$_SERVER['PHP_SELF']:'';// /ucadmin/index.php  /blog/testurl.php
        //echo '$php_self= ' . $php_self . '<br/>';
        $re_url_arr ['php_self'] = $php_self ;

        //获取网址参数 
        $query_string = isset($_SERVER['QUERY_STRING'])?$_SERVER['QUERY_STRING']:'';// id=5
        //echo '$query_string= ' . $query_string . '<br/>';
        $re_url_arr ['query_string'] = $query_string ;

        //获取用户代理 
        $http_referer = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'';// 空
       // echo '$http_referer= ' . $http_referer . '<br/>';
        $re_url_arr ['http_referer'] = $http_referer ;

        $request_uri = isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:''; // /ucadmin/test/get_url?id=5
        //echo '$request_uri= ' . $request_uri . '<br/>';
        $re_url_arr ['request_uri'] = $request_uri ;

        $server_name = isset($_SERVER['SERVER_NAME'])?$_SERVER['SERVER_NAME']:'';//  localhost 
        //echo '$server_name= ' . $server_name . '<br/>';           
        $re_url_arr ['server_name'] = $server_name ;

        $server_port = isset($_SERVER['SERVER_PORT'])?$_SERVER['SERVER_PORT']:'';// 80 
        //echo '$server_port= ' . $server_port . '<br/>';           
        $re_url_arr ['server_port'] = $server_port ;

        //获取完整的url[一般用这个]
        $http_rul = 'http://' . $http_host . $request_uri; // http://localhost/ucadmin/test/get_url?id=5  http://localhost/ucadmin/test/abcdefg
        //echo '$http_rul= ' . $http_rul . '<br/>';           
        $re_url_arr ['http_rul'] = $http_rul ;

        $http_url_full = 'http://' . $http_host . $php_self . '?' . $query_string;//http://localhost/ucadmin/index.php?id=5 http://localhost/blog/testurl.php?id=5
        //echo '$http_url_full= ' . $http_url_full . '<br/>';           
        $re_url_arr ['http_url_full'] = $http_url_full ;


        //包含端口号的完整url
        $http_url_port = 'http://' . $server_name . ':' . $server_port . $request_uri; //http://localhost:80/ucadmin/test/get_url?id=5 http://localhost:80/blog/testurl.php?id=5
        //echo '$http_url_port= ' . $http_url_port . '<br/>';           
        $re_url_arr ['http_url_port'] = $http_url_port ;


        //只取路径            
        $url_path = dirname($http_rul);// http://localhost/ucadmin/test http://localhost/blog
        //echo '$url_path= ' . $url_path . '<br/>';           
        $re_url_arr ['url_path'] = $url_path ;
        return $re_url_arr; 
    }  
}

/**
 *
 * @brief 生成随机字串
 * @details 
 * @param array $in_arr 条件数组
 * $in_arr = array(
 *          'length' => //长度
 *          'type'=>'1,2,5'//包含类型多个用,号分隔 1数字2大小写字母3大写字母4小写字母5特殊字符
 * )
 * @return string 生成的字串
 * 
 *
 */
if ( ! function_exists('rand_str'))
{
    //生成随机字串
    function rand_str($in_arr){
        $number = '023456789';//数组
        $big_character = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';//大写字母
        $small_character = 'abcdefghijklmnopqrstuvwxyz';//小写字母
        $special_character = '!"#$%&\'()*+,-./:;<=>?@[\]^_`{|}~';//特殊字符 
        
        $re_word = '';
        $length = arr_unbound_value($in_arr,'length',2,8);
        $type = arr_unbound_value($in_arr,'type',2,2);
        $type_arr = explode(",", $type);//类型转换为数组
        $type_num = count($type_arr);
        if( $type_num > 1){//大于1随机调换调用位置
            $arr_unbound = '';//数组下标
            for ($i = 0; $i < $type_num; $i++) {
                $arr_unbound .=  $i;
            }
            $new_arr = array();
            $ns_len = strlen($arr_unbound);
            $kk = 1;
            while( ($ns_len > 0) && ($kk <= $type_num)){//大于0                 
                $new_unbound = $arr_unbound[ mt_rand(0,$ns_len -1) ];//获得下标
                $new_arr[] = $type_arr[$new_unbound];//当前下标的值
                $arr_unbound = str_ireplace($new_unbound, '', $arr_unbound);//去掉下标记录
                $ns_len = strlen($arr_unbound);//获得下标长度
                $kk += 1;//防进入死循环
            };
            $type_arr = $new_arr;         
        }
        
        $pre_num =  floor($length/$type_num);//平均数取整
        $ok_num = 0 ;//已经随机好的数量
        foreach($type_arr as $k => $v){   
            $max_num = $pre_num;//最大数量
            if($k == ($type_num -1)){//最后一个
               $max_num =  $length - $ok_num;
            }
            switch ($v){//
                case 1: //1数字
                   $re_word .= generate_username( $max_num , $number);
                   break;
                case 2:  //2大小写字母  
                   $re_word .= generate_username( $max_num , $big_character . $small_character);             
                   break;
                case 3:   // 3大写字母 
                   $re_word .= generate_username( $max_num , $big_character);                 
                   break;
                case 4: //4小写字母 
                   $re_word .= generate_username( $max_num , $small_character);                       
                   break;
                case 5:  //5特殊字符  
                   $re_word .= generate_username( $max_num , $special_character);                    
                   break;
                default:
                   $re_word .= generate_username( $max_num ,$number . $big_character . $small_character . $special_character);
                   break;
            }
            $ok_num += $max_num ;//已经随机好的数量
        }
        
        //$number = '023456789';
        //$big_character = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        //$small_character = 'abcdefghijklmnopqrstuvwxyz';
        //$special_character = '!@#$%^&*()-_ []{}<>~`+=,.;:/?|';
        //$randpwd = create_password($length);
        return $re_word;
    }
}
/**
 *
 * @brief 随机生成密码
 * @details 
 * @param int $pw_length 随机生成的长度
 * @return string 生成的用密码
 * 
 *
 */
if ( ! function_exists('create_password'))
{
//自动为用户随机生成用户名(长度6-13)
    function create_password($pw_length = 4){ 
        $randpwd = ''; 
        for ($i = 0; $i < $pw_length; $i++){
            $randpwd .= chr(mt_rand(33, 126)); 
        } 
        return $randpwd; 
    } 
}
/**
 *
 * @brief 随机生成用户名
 * @details 
 * @param int $length 随机生成的长度
 * @return string 生成的用户名
 * 
 *
 */
if ( ! function_exists('generate_username'))
{
    function generate_username( $length = 6 , $chars = '') { 
        // 密码字符集，可任意添加你需要的字符 
        if(bn_is_empty($chars)){//为空
          $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_ []{}<>~`+=,.;:/?|'; 
        }
        $password = ''; 
        for ( $i = 0; $i < $length; $i++ ) 
        { 
            // 这里提供两种字符获取方式 
            // 第一种是使用substr 截取$chars中的任意一位字符； 
            // 第二种是取字符数组$chars 的任意元素 
            // $password .= substr($chars, mt_rand(0, strlen($chars) - 1), 1); 
            $password .= $chars[ mt_rand(0, strlen($chars) - 1) ]; 
        } 
        return $password; 
    } 
    // 调用该函数 
    //$userId = 'user'.generate_username(6); 
    //$pwd = create_password(9);
}

/**
 *
 * @brief 判断一个值是否在数组里，可以是多维数组
 * @details 
 * @param string $value 需要判断的值
 * @param array $array 需要判断的数组
 * @return string true在，false 不在
 * 
 *
 */
if ( ! function_exists('deep_in_array'))
{
    function deep_in_array($value, $array) { 
        foreach($array as $item){//遍历数组
            if(!is_array($item)){//不是数组 
                if ($item == $value){//相等
                    return true;
                }else{//不等，继续
                    continue; 
                }
            }
            if(in_array($value,$item)){//在里面
                return true; 
            }else if(deep_in_array($value, $item)) {//递归在里面，返回true
                return true; 
            }         
        } 
        return false; 
    }
}
/**
 *
 * @brief 是否有权限
 * @details  
 * @param string $power_num 当前权限编号
 * @param array/string $in_msg 当前权限数组或权限json串
 * @return boolean 有true 没有false
 * 
 *
 */
if ( ! function_exists('has_power'))
{
//自动为用户随机生成用户名(长度6-13)
    function has_power($power_num = 0,$in_msg = array()){        
        if(!is_array($in_msg)){//不是数组
            if(!is_not_json($in_msg)){//是json串
               $in_msg = json_decode($in_msg, true);
            }
        }
        if(!is_array($in_msg)){//还不是数组
            return false;
        }
        return deep_in_array($power_num,$in_msg);//返回ture /false        
    } 
}
/**
 *
 * @brief 是否有权限
 * @details  
 * @param string $num 当前的小写数字
 * @return string 转换后的大写数字
 * 
 *
 */
if ( ! function_exists('num_to_upper'))
{
//自动为用户随机生成用户名(长度6-13) 
     function num_to_upper($num){
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
}
/*
------------------------------------------------------
 * 
参数：
$str_cut    需要截断的字符串
$length     允许字符串显示的最大长度

程序功能：截取全角和半角（汉字和英文）混合的字符串以避免乱码
------------------------------------------------------
*/
/**
 *
 * @brief 截取字符串
 * @details  
 * @param string $str 需要截断的字符串
 * @param int $mylen 允许字符串显示的最大长度
 * @param string $suffix 后缀，可以为空
 * @return string 转换后的大写数字
 * 
 *
 */
if ( ! function_exists('substr_cn'))
{
    function substr_cn($str = '',$mylen = 0,$suffix = ''){                                                                                                                                        
        
        $content='';        
        $count=0;
        if(bn_is_empty($str)){//没有数据
            return $content;
        }
        $len=strlen($str);
        if($len <= $mylen){//小于或等需要截取的长度
            return $str;
        }
        for($i=0;$i<$len;$i++){
           if(ord(substr($str,$i,1))>127){
            $content.=substr($str,$i,3);//注意utf8是3，gb2312是2
            //echo 'a$i=' . $i . '<br/>';
            $i++;
            $i++;//注意utf8是3，gb2312是2
           }else{
             //echo 'b$i=' . $i . '<br/>';
             $content.=substr($str,$i,1);
           }
           if(++$count==$mylen){
                break;
           }
        }
        //if(!bn_is_empty($suffix)){//有后缀
            $content .= $suffix;
        //}
        return $content;
    }    
}
/**
 *
 * @brief 如果给的是二维数组，且只有一个二维度，则返回一维数组，否则还是返回原数组
 * @details  
 * @param array $old_arr 原数组
 * @return array 返回处理后的一维或二维数组
 * 
 *
 */
if ( ! function_exists('twoarr_to_onearr'))
{
    function twoarr_to_onearr($old_arr = array()){
        $re_arr = array();
        //如果只有一个二维数组，则转为一维数组
        $ns_arr = array();//新的一维数组
        if(!isemptyArray($old_arr)){//非空数组
            $arr_no = 0;
            foreach($old_arr as $k => $v){
                if (is_array($v) ){//是数组
                    $ns_arr = $v;
                }
                $arr_no += 1;
                if($arr_no > 1){//大于1,退出
                    break;
                }
            }
            if($arr_no == 1){//只有一个维度
                $re_arr = $ns_arr;
            }else{
                $re_arr = $old_arr;
            }
        }
        return $re_arr;
    }
}
/**
 *
 * @brief 根据文件名称获得文件后缀名
 * @param $filename string 文件名称
 * @return string 文件的后缀名
 * 
 *
 */
if ( ! function_exists('get_file_extension'))
{

     function get_file_extension($filename){
        // 从文件名中最后一个“.”的位置开始获取，加1表示从后缀名第一个字母开始
        $location = strripos($filename, '.') + 1;
        // 获得后缀名
        $suffix=substr($filename, $location);
        return $suffix;
    }
}
/**
 *
 * @brief 根据文件名称获得文件后缀名
 * @param $file_path string 文件全名及路径 /文件夹/文件名
 * @return string 文件的名称
 * 
 *
 */
if ( ! function_exists('get_file_name'))
{

     function get_file_name($file_path){
        // 从文件名中最后一个“.”的位置开始获取，加1表示从后缀名第一个字母开始
        $location = strripos($file_path, '/') + 1;
        // 获得文件的名称
        $suffix=substr($file_path, $location);
        return $suffix;
    }
}
/**
 *
 * @brief 获得分隔符最后面的部分
 * @param  string  字符
 * @return string $in_str 原内容
 * @return string $split_str 分隔符
 * 
 *
 */
if ( ! function_exists('get_last_part'))
{

     function get_last_part($in_str = '', $split_str = ''){
        // 从文件名中最后一个“.”的位置开始获取，加1表示从后缀名第一个字母开始
        if(bn_is_empty($in_str)){
            return '';
        }
        
        $location = strripos($in_str, $split_str);
        if($location == false){//没有找到
            return $in_str;
        }
        $location += strlen($split_str);
        // 获得文件的名称
        $suffix=substr($in_str, $location);
        return $suffix;
    }
}

/**
 *
 * @brief 规范url，为指定的式
 * @details 
 * @param array $url_arr  url信息数组
        $url_arr = array(
            'url' => $aa,//需要处理的url
            'pre' => $aa,//url前部，如果不是则加上，是则不加;如http://、http://www 
            'back' =>$aaa,//url后部，如 /  、目录：/ucadmin/
        );
 * @return string url
 * 
 *
 */
if ( ! function_exists('url_http'))
{
    function url_http($url_arr = array()){ 
        $url = isset($url_arr['url'])?$url_arr['url']:'';
        $pre = isset($url_arr['pre'])?$url_arr['pre']:'';
        $back = isset($url_arr['back'])?$url_arr['back']:'';
        if(!bn_is_empty($pre)){//有数据
            $pre_len = strlen($pre);
            $ns_pre_txt = substr($url,0,$pre_len);//取倒数5个字符
            if($ns_pre_txt != $pre){
                $url = $pre . $url;
            }
            
        }
        if(!bn_is_empty($back)){//有数据
            $back_len = strlen($back);
            $ns_back_txt = substr($url,-$back_len);//取倒数n个字符            
            if($ns_back_txt != $back){
                $url .= $back;
            }
        }
        return $url;
    } 
}
/**
 *
 * @brief 传入未知数据，返回字串，数组转换为json串
 * @details 
 * @param string $in_str  传入未知数据
 * @return string 返回字任串
 * 
 *
 */
if ( ! function_exists('any_to_str'))
{
    function any_to_str($in_str = ''){ 
        $ns_re_str = $in_str;
        if (is_array($ns_re_str) ){//是数组
            $ns_re_str = json_encode($ns_re_str);
        }
        return $ns_re_str;
    } 
}

/**
 * @brief 判断字符串或数值是否为空，0或'0'不为空
 * @param string $str  字符串
 * @return boolean
 */

if ( ! function_exists('is_empty'))
{
	function is_empty($str){
		if(empty($str) && $str !== 0 && $str !== '0'){
			return true;
		}
		return false;
	}
}

/**
 * 功能同empty,解决原生empty不能直接传表达式的问题
 */
if ( ! function_exists('empty_alias'))
{
	function empty_alias($var){
		return empty($var);
	}
}

/**
 * @brief 从数组里获取某个元素，如果这个元素为空，则返回默认值
 * @param	string $item
 * @param	array  $array
 * @param	mixed  $default
 * @return	mixed	depends on what the array contains
 */

if ( ! function_exists('element'))
{
	function element($item, $array, $default = FALSE){
		if ( ! isset($array[$item]) OR $array[$item] == ""){
			return $default;
		}
		return $array[$item];
	}
}

if ( ! function_exists('return_json'))
{
	function return_json($code=0, $message='',$data=array()){
		$res_data = json_encode(array('code'=>$code, 'msg'=>$message, 'data'=>$data));
		
		$error_level = $code == 0 ? 'info' : 'error';
		log_message($error_level, ' OUTPUT_JSON--> '.$res_data);
		
		echo $res_data;exit;
	}
}

if ( ! function_exists('response_json'))
{
	function response_json($code=0, $message='',$data=array()){
		$res_data = json_encode(array('code'=>$code, 'msg'=>$message, 'data'=>$data));
		
		$error_level = $code == 0 ? 'info' : 'error';
		log_message($error_level, ' OUTPUT_JSON--> '.$res_data);
		
		return $res_data;
	}
}


/**
 * 浏览器友好的变量输出
 * @param mixed $var 变量
 * @param boolean $echo 是否输出 默认为True 如果为false 则返回输出字符串
 * @param string $label 标签 默认为空
 * @param boolean $strict 是否严谨 默认为true
 * @return void|string
 */
if( ! function_exists('dump'))
{
	function dump($var, $echo=true, $label=null, $strict=true) {
		$label = ($label === null) ? '' : rtrim($label) . ' ';
		if (!$strict) {
			if (ini_get('html_errors')) {
				$output = print_r($var, true);
				$output = "<pre style='font-family:\"YaHei Consolas Hybrid\",\"微软雅黑\";'>" . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
			} else {
				$output = $label . print_r($var, true);
			}
		} else {
			ob_start();
			var_dump($var);
			$output = ob_get_clean();
			if (!extension_loaded('xdebug')) {
				$output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
				$output = "<pre style='font-family:\"YaHei Consolas Hybrid\",\"微软雅黑\";'>" . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
			}
		}
		if ($echo) {
			echo($output);
			return null;
		}else
			return $output;
	}
}

/**
 * 取一个二维数组中的每个数组的固定的键知道的值来形成一个新的一维数组
 * @param $pArray 一个二维数组
 * @param $pKey 数组的键的名称
 * @return 返回新的一维数组
 */
if( ! function_exists('getSubByKey'))
{
	function getSubByKey($pArray, $pKey="", $pCondition=""){
		$result = array();
		if(is_array($pArray)){
			foreach($pArray as $temp_array){
				if(is_object($temp_array)){
					$temp_array = (array) $temp_array;
				}
				if((""!=$pCondition && $temp_array[$pCondition[0]]==$pCondition[1]) || ""==$pCondition) {
					$result[] = (""==$pKey) ? $temp_array : isset($temp_array[$pKey]) ? $temp_array[$pKey] : "";
				}
			}
			return $result;
		}else{
			return false;
		}
	}
}
/**
 *  验证电话号码 不能超过20位最少4位
 */
if( ! function_exists('is_tel_number')){
	function is_tel_number($str){
		return preg_match("/^[0-9]{4,20}$/", $str) !== 0;
		
	}
}


/**
 *  验证手机号码 不能超过6-11位
 */
if( ! function_exists('is_momobile_number')){
	function is_momobile_number($str){
		return preg_match("/^[0-9]{6,11}$/", $str) !== 0;

	}
}

/**
 * 从组织串中取出当前部门的Id
 */
if(! function_exists('get_org_id')){
	function get_org_id($org_code){
		$location = strrpos($org_code, '-')+1; // 获取组织串字符串中最后一个“-”出现的位置，再加1则为org_id开始的位置
		$org_id = substr($org_code, $location); // 获取组织字符串中从$location位置取到结尾的子字符串
		
		return $org_id;
	}
}
	

/**
 * php5.5支持的array_column实现
 */
if( ! function_exists('array_column')){
	function array_column(array $input, $columnKey, $indexKey = null){
		$array = array();
		foreach ($input as $value) {
			if ( ! isset($value[$columnKey])) {
				trigger_error("Key \"$columnKey\" does not exist in array");
				return false;
			}
	
			if (is_null($indexKey)) {
				$array[] = $value[$columnKey];
			} else {
				if ( ! isset($value[$indexKey])) {
					trigger_error("Key \"$indexKey\" does not exist in array");
					return false;
				}
				if ( ! is_scalar($value[$indexKey])) {
					trigger_error("Key \"$indexKey\" does not contain scalar value");
					return false;
				}
				$array[$value[$indexKey]] = $value[$columnKey];
			}
		}
	
		return $array;
	}
}

/**
 * 把全名拆分为姓氏和名字 
 * @param string $fullname 全名 
 * @return array 一维数组,元素一是姓,元素二为名 
 */
if(! function_exists('splitName')){
	function splitName($fullname){
		$hyphenated = array('欧阳','太史','端木','上官','司马','东方','独孤','南宫','万俟','闻人','夏侯','诸葛','尉迟','公羊','赫连','澹台','皇甫', '宗政','濮阳','公冶','太叔','申屠','公孙','慕容','仲孙','钟离','长孙','宇文','城池','司徒','鲜于','司空','汝嫣','闾丘','子车','亓官', '司寇','巫马','公西','颛孙','壤驷','公良','漆雕','乐正','宰父','谷梁','拓跋','夹谷','轩辕','令狐','段干','百里','呼延','东郭','南门', '羊舌','微生','公户','公玉','公仪','梁丘','公仲','公上','公门','公山','公坚','左丘','公伯','西门','公祖','第五','公乘','贯丘','公皙', '南荣','东里','东宫','仲长','子书','子桑','即墨','达奚','褚师');
		$vLength = mb_strlen($fullname, 'utf-8');
		$lastname = '';
		$firstname = '';//前为姓,后为名
		if($vLength > 2){
			$preTwoWords = mb_substr($fullname, 0, 2, 'utf-8');//取命名的前两个字,看是否在复姓库中
			if(in_array($preTwoWords, $hyphenated)){
				$lastname = $preTwoWords;
				$firstname = mb_substr($fullname, 2, 10, 'utf-8');
			}else{
				$lastname = mb_substr($fullname, 0, 1, 'utf-8');
				$firstname = mb_substr($fullname, 1, 10, 'utf-8');
			}
		}else if($vLength == 2){//全名只有两个字时,以前一个为姓,后一下为名
			$lastname = mb_substr($fullname ,0, 1, 'utf-8');
			$firstname = mb_substr($fullname, 1, 10, 'utf-8');
		}else{
			$lastname = $fullname;
		}
		return array($lastname, $firstname);
	}
}


if(!function_exists('create_guid')){
	function create_guid() {
		$charid = strtoupper(md5(uniqid(mt_rand(), true)));
		$hyphen = chr(45);// "-"
		$uuid = substr($charid, 0, 8).$hyphen
		.substr($charid, 8, 4).$hyphen
		.substr($charid,12, 4).$hyphen
		.substr($charid,16, 4).$hyphen
		.substr($charid,20,12);
		
		return $uuid;
	}
}
