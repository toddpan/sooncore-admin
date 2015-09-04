<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once APPPATH . 'helpers/my_publicfun_helper.php';//公用函数
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter CAPTCHA Helper
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/xml_helper.html
 */

// ------------------------------------------------------------------------

/**
 * Create CAPTCHA
 *
 * @access	public
 * @param	array	array of data for the CAPTCHA
 * @param	string	path to create the image in
 * @param	string	URL to the CAPTCHA image folder
 * @param	string	server path to font
 * @return	string
 */
if ( ! function_exists('httpCurl'))
{
   function httpCurl($url, $body, $method='GET', $header=array(), $xml_format=false, $timeout=10000)
    {
    	$ci = curl_init();
        /* Curl settings */
        //curl_setopt($ci, CURLOPT_VERBOSE, TRUE);
        curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ci, CURLOPT_USERAGENT, 'Ucc Server Api v1.0');
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ci, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
        //set http request body
        if (strtolower($method) == 'post' && !$xml_format) {
            $data = $body;//json_encode($body);
        }elseif (strtolower($method) == 'post' && $xml_format) {
            $data = $body;
        }else{
             $data = is_array($body) ? http_build_query($body) : $body;
        }
        //set http request header  
        if (bn_is_empty($header)){
           $header =  array("Content-Type: application/json",
                        "Content-Length: " . strlen($data)
                            );
        }else{
            $header[] = "Content-Length: " . strlen($data);
        }
//        $header = array("Content-Type: application/xml",
//                        "Content-Length: " . strlen($data)
//                        );
        //set http request method and request body
        switch (strtoupper($method))
        {
            case 'POST':
                curl_setopt($ci, CURLOPT_HEADER, true);
                curl_setopt($ci, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ci, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ci, CURLOPT_HTTPHEADER, $header);
                break;
            case 'GET':
                $url = (is_array($body) && count($body) > 0) ? sprintf("%s?%s", $url, http_build_query($body)) : $url;
                break;
            case 'PUT':
                curl_setopt($ci, CURLOPT_HEADER, true);
                curl_setopt($ci, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ci, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ci, CURLOPT_HTTPHEADER, $header);
                break;
            case 'DELETE':
                curl_setopt($ci, CURLOPT_HEADER, true);
                curl_setopt($ci, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($ci, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ci, CURLOPT_HTTPHEADER, $header);
                break;
            default :
                $url = (is_array($body) && count($body) > 0) ? sprintf("%s?%s", $url, http_build_query($body)) : $url;
        }
        //exec curl
        curl_setopt($ci, CURLOPT_URL, $url);
        //common_log(LOG_DEBUG, "\r\n \r\n curl url is [$url], request method is [$method],header is ".var_export($header, true)."requeat data is [".var_export($data,true)."]");
        //curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE );
        //return
        try {
        	//--性能测试，接口调用起始时间
        	if(OPEN_PERFORMANCE_TEST){
        		$callStartTime = microtime(true);
        	}
        	//--
        	
            $response = curl_exec($ci);
            
            //--性能测试，接口调用结束时间
            $callEndTime = microtime(true);
            $callTime = $callEndTime - $callStartTime;
            if(OPEN_PERFORMANCE_TEST && ($callTime >= PERFORMANCE_INTERNAL)){
				$CI = & get_instance();
				$CI->load->model('performance_model');
				
				$platform = 'others';
				if(preg_match('/core-service/u',$url)){
					$platform = 'boss';	
				}
				
				if(preg_match('/ums/u',$url)){
					$platform = 'ums';
				}
				
				if(preg_match('/uccapi/u',$url)){
					$platform = 'ucc';
				}
				
				if(preg_match('/uniform/u',$url)){
					$platform = 'uniform';
				}
				
				$one = array(
					'callurl'   =>$url,
					'platform'	=>$platform,
					'calltime'  =>round($callTime, 2),
					'createtime'=>date('Y-m-d H:i:s'),
				);
				$CI->performance_model->add($one);
				
            }
            //--
            
            if (!$response){
                $error_code = curl_errno($ci);
                $error_msg = curl_error($ci);
               // common_log(LOG_DEBUG, "\r\n\r\n get ums exception: error code is -> [$error_code], error_msg is [$error_msg]");
                curl_close($ci);
                $error_code = ($error_code == 0) ? -2:$error_code;
                return array('code' => $error_code, 'msg' => $error_msg);
            } else {
                $info = curl_getinfo($ci);
                curl_close($ci);
                //log_message('info', '$response =' . $response);
                //@list($header, $body) = explode("\r\n\r\n", $response, 2);
				if(count(explode("\r\n\r\n", $response))>1){
					@list($header, $body) = explode("\r\n\r\n", $response, 2);
				}else{
					$body = $response;
				}
				
               // common_log(LOG_DEBUG, "\r\n\r\n get ums response header value is [".var_export($header, true)."], \r\n body value is [".  var_export($body, true)."]");
                if ($info['http_code'] > 300){
                    $result = array('code'      => -1, 
                                    'http_info' => array('http_code' => $info['http_code'], 'http_body' => $body), 
                                    'msg'       => "http response code > 300, http code value is [".$info['http_code']."]"
                                   );
                }else{
                    $result = array('code'  => 0, 
                                    'data'         => $response, 
                                    'http_info'    => array('http_code' => $info['http_code'], 'http_body' => $body)
                                    );
                    
                }
                //common_log(LOG_DEBUG, "\r\n\r\n get data from ums results-> [".var_export($result, true)."]");
                return $result;
            }
        } catch (Exception $e) {
                $error_code = $e->getCode();
                $error_msg = $e->getMessage();
               // common_log(LOG_DEBUG, "\r\n\r\n get ums exception: error code is -> [$error_code], error_msg is [$error_msg]");
                curl_close($ci);
                $error_code = ($error_code == 0) ? -1:$error_code;
                return array('code' => $error_code, 'msg' => $error_msg);
        }
    }
}

// ------------------------------------------------------------------------

/* End of file captcha_helper.php */
/* Location: ./system/heleprs/captcha_helper.php */