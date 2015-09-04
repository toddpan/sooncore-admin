<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once APPPATH . 'helpers/my_publicfun_helper.php';//公用函数
/**
* 格式化时间
* @param integer $timestamp 时间戳
* @param string $format dt=日期时间 d=日期 t=时间 u=个性化 其他=自定义 
* @param integer $timeoffset 时区值 
* @param string $custom_format 自定义时间格式 
* @return string 
*/ 
if ( ! function_exists('dgmdate'))
{
    function dgmdate($timestamp, $format = 'dt', $timeoffset = '9999', $custom_format = ''){ 
        $timestamp = substr($timestamp,0,10);
        $return = ''; 
        $now = time(); 
        $day_format = 'Y-n-j'; 
        $time_format = 'H:i:s'; 
        $date_format = $day_format . ' ' . $time_format; 
        $offset = 8; //这里默认是东八区，也就是北京时间 
        $lang = array( 
            'before' => '前', 
            'day' => '天', 
            'yday' => '昨天', 
            'byday' => '前天', 
            'hour' => '小时', 
            'half' => '半', 
            'min' => '分钟', 
            'sec' => '秒', 
            'now' => '刚刚'
        );
        $timeoffset = $timeoffset == 9999 ? $offset : $timeoffset; 
        $timestamp += $timeoffset * 3600; 
        switch ( $format ) { 
            case 'dt': 
                $format = $date_format; 
                break; 
            case 'd': 
                $format = $day_format; 
                break; 
            case 't': 
                $format = $time_format; 
                break; 
        } 
        if ( $format == 'u' ) { 
            $todaytimestamp = $now - ($now + $timeoffset * 3600) % 86400 + $timeoffset * 3600; 
            $s = gmdate( bn_is_empty( $custom_format ) ? $date_format : $custom_format, $timestamp ); 
            $time = $now + $timeoffset * 3600 - $timestamp; 
            if ( $timestamp >= $todaytimestamp ) { 
                if ( $time > 3600 ) { 
                    $return = '<span title="' . $s . '">' . intval( $time / 3600 ) . $lang['hour'] . $lang['before'] . '</span>'; 
                } elseif ( $time > 1800 ) { 
                    $return = '<span title="' . $s . '">' . $lang['half'] . $lang['hour'] . $lang['before'] . '</span>'; 
                } elseif ( $time > 60 ) { 
                    $return = '<span title="' . $s . '">' . intval( $time / 60 ) . $lang['min'] . $lang['before'] . '</span>'; 
                } elseif ( $time > 0 ) { 
                    $return = '<span title="' . $s . '">' . $time . $lang['sec'] . $lang['before'] . '</span>'; 
                } elseif ( $time == 0 ) { 
                    $return = '<span title="' . $s . '">' . $lang['now'] . '</span>'; 
                } else { 
                    $return = $s; 
                } 
            } elseif ( ($days = intval( ($todaytimestamp - $timestamp) / 86400 )) >= 0 && $days < 7 ) { 
                if ( $days == 0 ) { 
                    $return = '<span title="' . $s . '">' . $lang['yday'] . gmdate( $time_format, $timestamp ) . '</span>'; 
                } elseif ( $days == 1 ) { 
                    $return = '<span title="' . $s . '">' . $lang['byday'] . gmdate( $time_format, $timestamp ) . '</span>'; 
                } else { 
                    $return = '<span title="' . $s . '">' . ($days + 1) . $lang['day'] . $lang['before'] . '</span>'; 
                } 
            } else { 
                $return = $s; 
            } 
        } else { 
            $return = gmdate( $format, $timestamp ); 
        } 
        return $return; 
    }
}
