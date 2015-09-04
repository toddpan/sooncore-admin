<?php

/**
 * $Id$
 * $LastChangedBy$
 * $LastChangedDate$
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 将数组转换为 XML 格式
 *
 * @author Dream <dream@shanjing-inc.com>
 */
if ( ! function_exists('array_xml')) {
    //定义函数
    function array_xml($array,$roottxt = 'root', $level = 0) {
        //构造 XML 文件内容
        $return = '';
        if ($level == 0) {
            $return = '<?xml version="1.0" encoding="utf-8" ?><' . $roottxt . '>';
        }
        foreach ( $array as $key => $item ) {
            if (! is_array ( $item )) {
                $return .= "<{$key}>{$item}</{$key}>";
            } else {
                $return .= "<{$key}>";
                $return .= array_xml ( $item, $roottxt, $level + 1 );
                $return .= "</{$key}>";
            }
        }
        if ($level == 0) {
            $return .= '</' . $roottxt . '>';
        }

        //返回数据
        return $return;
    }
}


/**
 * API 响应
 * 
 * @author Dream <dream@shanjing-inc.com>
 */
if ( ! function_exists('response')) {
    //定义函数
    function response($array, $type = 'xml', $error_code = '') {
        switch ($type) {
            case 'xml':
                header("Content-Type:text/xml");
                if ($error_code) $array = $array + array('error_code' => $error_code);
                echo array_xml($array);
                break;
            case 'json':
                if ($error_code) $array = $array + array('error_code' => $error_code);
                echo json_encode($array);
                break;
            default:
                die('Response Type Is Wrong!');
        }
    }
}
/* End of file response_helper.php */
/* Location: ./application/helpers/response_helper.php */