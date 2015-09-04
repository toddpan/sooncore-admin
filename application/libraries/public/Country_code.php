<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
$country_code_arr = array(
        'ch_name' => aaa,//国家（中文名称）
        'en_name' => aaa,//国家（英文名称）
        'short_name' => aaa,//国家（简称）
        'country_code' => aaa,//国码        
    );
 * 
 */
class Country_code {
  private $country_arr = array(
         array(
            'ch_name' => '中国',//国家（中文名称）
            'en_name' => 'China',//国家（英文名称）
            'short_name' => 'CN',//国家（简称）
            'country_code' => '+86',//国码 
            'is_selected' => 0,//是否选中
         ),
//         array(
//             'ch_name' => '中国香港',//国家（中文名称）
//             'en_name' => 'Hong Kong',//国家（英文名称）
//             'short_name' => 'HK',//国家（简称）
//             'country_code' => '+852',//国码  
//             'is_selected' => 0,//是否选中      
//          ),
//         array(
//             'ch_name' => '阿尔巴尼亚',//国家（中文名称）
//             'en_name' => 'Afghanistan',//国家（英文名称）
//             'short_name' => 'AL',//国家（简称）
//             'country_code' => '+355',//国码  
//             'is_selected' => 0,//是否选中      
//          ),
//         array(
//             'ch_name' => '阿尔及利亚',//国家（中文名称）
//             'en_name' => 'Algeria',//国家（英文名称）
//             'short_name' => 'DZ',//国家（简称）
//             'country_code' => '+213',//国码  
//             'is_selected' => 0,//是否选中      
//          ),
//         array(
//             'ch_name' => '阿富汗',//国家（中文名称）
//             'en_name' => 'Afghanistan',//国家（英文名称）
//             'short_name' => 'AF',//国家（简称）
//             'country_code' => '+93',//国码  
//             'is_selected' => 0,//是否选中      
//          ),
//         array(
//             'ch_name' => '美属萨摩亚',//国家（中文名称）
//             'en_name' => 'American Samoa',//国家（英文名称）
//             'short_name' => 'AS',//国家（简称）
//             'country_code' => '+1684',//国码  
//             'is_selected' => 0,//是否选中      
//          ),
//         array(
//             'ch_name' => '津巴布韦',//国家（中文名称）
//             'en_name' => 'Zimbabwe',//国家（英文名称）
//             'short_name' => 'ZW',//国家（简称）
//             'country_code' => '+263',//国码  
//             'is_selected' => 0,//是否选中      
//          ),
//         array(
//             'ch_name' => '赞比亚',//国家（中文名称）
//             'en_name' => 'Zambia',//国家（英文名称）
//             'short_name' => 'ZM',//国家（简称）
//             'country_code' => '+260',//国码  
//             'is_selected' => 0,//是否选中      
//          ),
//         array(
//             'ch_name' => '也门',//国家（中文名称）
//             'en_name' => 'Yemen',//国家（英文名称）
//             'short_name' => 'YE',//国家（简称）
//             'country_code' => '+967',//国码  
//             'is_selected' => 0,//是否选中      
//          ),
//         array(
//             'ch_name' => '西撒哈拉',//国家（中文名称）
//             'en_name' => 'Western Sahara',//国家（英文名称）
//             'short_name' => 'EH',//国家（简称）
//             'country_code' => '+212 28',//国码  
//             'is_selected' => 0,//是否选中      
//          ),
//         array(
//             'ch_name' => '约旦河西岸地区',//国家（中文名称）
//             'en_name' => 'West Bank',//国家（英文名称）
//             'short_name' => '',//国家（简称）
//             'country_code' => '+970',//国码  
//             'is_selected' => 0,//是否选中      
//          ),
//         array(
//             'ch_name' => '瓦利斯和富图纳',//国家（中文名称）
//             'en_name' => 'Wallis and Futuna',//国家（英文名称）
//             'short_name' => 'WF',//国家（简称）
//             'country_code' => '+681',//国码  
//             'is_selected' => 0,//是否选中      
//          ),
//         array(
//             'ch_name' => '越南',//国家（中文名称）
//             'en_name' => 'Vietnam',//国家（英文名称）
//             'short_name' => 'VN',//国家（简称）
//             'country_code' => '+84',//国码  
//             'is_selected' => 0,//是否选中      
//          ),
//         array(
//             'ch_name' => '委内瑞拉',//国家（中文名称）
//             'en_name' => 'Venezuela',//国家（英文名称）
//             'short_name' => 'VE',//国家（简称）
//             'country_code' => '+58',//国码  
//             'is_selected' => 0,//是否选中      
//          ),
//         array(
//             'ch_name' => '梵蒂冈',//国家（中文名称）
//             'en_name' => 'Vatican City',//国家（英文名称）
//             'short_name' => 'VA',//国家（简称）
//             'country_code' => '+39',//国码  
//             'is_selected' => 0,//是否选中      
//          ),
//         array(
//             'ch_name' => '瓦努阿图',//国家（中文名称）
//             'en_name' => 'Vanuatu',//国家（英文名称）
//             'short_name' => 'VU',//国家（简称）
//             'country_code' => '+678',//国码  
//             'is_selected' => 0,//是否选中      
//          ),
//         array(
//             'ch_name' => '乌兹别克斯坦',//国家（中文名称）
//             'en_name' => 'Uzbekistan',//国家（英文名称）
//             'short_name' => 'UZ',//国家（简称）
//             'country_code' => '+998',//国码  
//             'is_selected' => 0,//是否选中      
//          ),
//         array(
//             'ch_name' => '美属维京群岛',//国家（中文名称）
//             'en_name' => 'US Virgin Islands',//国家（英文名称）
//             'short_name' => 'VI',//国家（简称）
//             'country_code' => '+1 340',//国码  
//             'is_selected' => 0,//是否选中      
//          ),
//         array(
//             'ch_name' => '乌拉圭',//国家（中文名称）
//             'en_name' => 'Uruguay',//国家（英文名称）
//             'short_name' => 'UY',//国家（简称）
//             'country_code' => '+598',//国码  
//             'is_selected' => 0,//是否选中      
//          ),
//         array(
//             'ch_name' => '美国',//国家（中文名称）
//             'en_name' => 'United States',//国家（英文名称）
//             'short_name' => 'US',//国家（简称）
//             'country_code' => '+1',//国码  
//             'is_selected' => 0,//是否选中      
//          ),
//         array(
//             'ch_name' => '英国',//国家（中文名称）
//             'en_name' => 'United Kingdom',//国家（英文名称）
//             'short_name' => 'GB',//国家（简称）
//             'country_code' => '+44',//国码  
//             'is_selected' => 0,//是否选中      
//          ),
//         array(
//             'ch_name' => '阿联酋',//国家（中文名称）
//             'en_name' => 'United Arab Emirates',//国家（英文名称）
//             'short_name' => 'AE',//国家（简称）
//             'country_code' => '+971',//国码  
//             'is_selected' => 0,//是否选中      
//          ),
//         array(
//             'ch_name' => '乌克兰',//国家（中文名称）
//             'en_name' => 'Ukraine',//国家（英文名称）
//             'short_name' => 'UA',//国家（简称）
//             'country_code' => '+380',//国码  
//             'is_selected' => 0,//是否选中      
//          ),
//         array(
//             'ch_name' => '乌干达',//国家（中文名称）
//             'en_name' => 'Uganda',//国家（英文名称）
//             'short_name' => 'UG',//国家（简称）
//             'country_code' => '+256',//国码  
//             'is_selected' => 0,//是否选中      
//          ),
//         array(
//             'ch_name' => '图瓦卢',//国家（中文名称）
//             'en_name' => 'Tuvalu',//国家（英文名称）
//             'short_name' => 'TV',//国家（简称）
//             'country_code' => '+688',//国码  
//             'is_selected' => 0,//是否选中      
//          ),
//         array(
//             'ch_name' => '英属特克斯和凯科斯群岛',//国家（中文名称）
//             'en_name' => 'Turks and Caicos Islands',//国家（英文名称）
//             'short_name' => 'TC',//国家（简称）
//             'country_code' => '+1 649',//国码  
//             'is_selected' => 0,//是否选中      
//          ),
//         array(
//             'ch_name' => '土耳其',//国家（中文名称）
//             'en_name' => 'Turkey',//国家（英文名称）
//             'short_name' => 'TR',//国家（简称）
//             'country_code' => '+90',//国码  
//             'is_selected' => 0,//是否选中      
//          ),
//         array(
//             'ch_name' => '土库曼斯坦',//国家（中文名称）
//             'en_name' => 'Turkemenistan',//国家（英文名称）
//             'short_name' => 'TM',//国家（简称）
//             'country_code' => '+993',//国码  
//             'is_selected' => 0,//是否选中      
//          ),
//         array(
//             'ch_name' => '突尼斯',//国家（中文名称）
//             'en_name' => 'Tunisia',//国家（英文名称）
//             'short_name' => 'TN',//国家（简称）
//             'country_code' => '+216',//国码  
//             'is_selected' => 0,//是否选中      
//          ),
//         array(
//             'ch_name' => '特立尼达和多巴哥',//国家（中文名称）
//             'en_name' => 'Trinidad and Tobago',//国家（英文名称）
//             'short_name' => 'TT',//国家（简称）
//             'country_code' => '+1 868',//国码  
//             'is_selected' => 0,//是否选中      
//          ),
//         array(
//             'ch_name' => '汤加',//国家（中文名称）
//             'en_name' => 'Tonga',//国家（英文名称）
//             'short_name' => 'TO',//国家（简称）
//             'country_code' => '+676',//国码  
//             'is_selected' => 0,//是否选中      
//          ),
//         array(
//             'ch_name' => '托克劳群岛',//国家（中文名称）
//             'en_name' => 'Tokelau',//国家（英文名称）
//             'short_name' => 'Tk',//国家（简称）
//             'country_code' => '+690',//国码  
//             'is_selected' => 0,//是否选中      
//          ),
//         array(
//             'ch_name' => '多哥',//国家（中文名称）
//             'en_name' => 'Togo',//国家（英文名称）
//             'short_name' => 'TG',//国家（简称）
//             'country_code' => '+228',//国码  
//             'is_selected' => 0,//是否选中      
//          ),
//         array(
//             'ch_name' => '东帝汶',//国家（中文名称）
//             'en_name' => 'Timor-Leste',//国家（英文名称）
//             'short_name' => 'TL',//国家（简称）
//             'country_code' => '+670',//国码  
//             'is_selected' => 0,//是否选中      
//          ),
//         array(
//             'ch_name' => '泰国',//国家（中文名称）
//             'en_name' => 'Afghanistan',//国家（英文名称）
//             'short_name' => 'AF',//国家（简称）
//             'country_code' => '+93',//国码  
//             'is_selected' => 0,//是否选中      
//          )
    );
    public function __construct() {
        //载入接口公用函数
        include_once APPPATH . 'helpers/my_httpcurl_helper.php'; 
    }
    
    /**
     * @brief 获取国家编码的集合
     * @return	array 返回所有定义的国家的国家编码
     */
    public function getCountryCode(){
    	$code_arr = array();
    	foreach ($this->country_arr as $code){
    		$code_arr[] = $code['country_code'];
    	}
    	return $code_arr;
    }
    
    /**
     *
     * @brief 获得所有的国家码
     * @details 
     * @param string $country_code 当前国码
     * @return array 有选中的国家码
     *
     */
    public function get_country_code($country_code = ''){        
        $re_arr = array();
        foreach($this->country_arr as $k => $v){
            $ns_country_code = arr_unbound_value($v,'country_code',2,'');
            $is_selected = 0;
            if($ns_country_code == $country_code){//没有数据
                $is_selected = 1;
            }
            $v['is_selected'] = $is_selected;
            $re_arr[] = $v;
        }
        return $re_arr;
    } 
    /**
     *
     * @brief 根据手机号信息，获得手机国家码，及手机部分信息
     * @details 
     * @param string $mobile 手机
     * @return array
        $re_arr = array(
            'code' => '+85',//默认为中国
            'mobile' => '',//默认手机号
        );
     *
     */
    public function get_mobile_arr($mobile = ''){        
        $re_arr = array(
            'code' => '+86',//默认为中国
            'mobile' => '',//默认手机号
        );
        if(bn_is_empty($mobile)){//没有数据
            return $re_arr;
        }
        $mobile_fist_pre = substr_cn($mobile,1,'');        
        if($mobile_fist_pre == '+'){//前面第一个是+号 
            foreach($this->country_arr as $k => $v){
                
                $ns_country_code = arr_unbound_value($v,'country_code',2,'');
               
                $code_len = strlen($ns_country_code);
                $mobile_pre = substr_cn($mobile,$code_len,'');
                if($ns_country_code == $mobile_pre){
                    $re_arr['code'] = $ns_country_code;
                    $re_arr['mobile'] = substr($mobile,$code_len);
                    break;
                }
            }
        }  
        return $re_arr;
    }
    
    /**
     * 获得所有国家名称
     */
    public function get_country(){
    	$country_name_arr = array();
    	
    	foreach($this->country_arr as $k => $v){
    		$country_name_arr[] = $v['ch_name'];
    	}
    	
    	return $country_name_arr;
    }
    
}


