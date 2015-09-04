<?php if (!defined('BASEPATH'))	exit('No direct script access allowed');

/**
 * @class UserResourceLib
 * @brief UserResourceLib 类库，主要负责对维度操作的方法。
 * @file UserResourceLib.php
 * @author zouyan <yan.zou@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
class UserResourceLib{  
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
     * @brief 根据维度类型，返回对应维度的值
     * @details 
     * @param array $scope_array 维度数组
     * @param int $scope_type 维度类型1：部门；2：地区；：3：成本中心
     * @return string 字符类型,当前维度类型的值
     */
    public function get_resource_value($scope_array = array(),$scope_type = 0){
        $scope_value = '';
        //如果有维度，则从维度拿到组织id到in里面
        if(!isemptyArray($scope_array)){//如果不是空数组
            //type：1：部门；2：地区；：3：成本中心
            $scope_level_1 = arr_unbound_value($scope_array,'scope_level_1',2,0);
            $scope_level_2 = arr_unbound_value($scope_array,'scope_level_2',2,0);
            
            if($scope_level_1 == $scope_type){//是部门
               $scope_level_1_value = arr_unbound_value($scope_array,'scope_level_1_value',2,'');
               if(!bn_is_empty($scope_level_1_value)){//不为空
                   if(!bn_is_empty($scope_value)){//不为空,则加,号分隔符
                       $scope_value .= ',';
                   }
                   $scope_value .= $scope_level_1_value;
               }
            }
            if($scope_level_2 == $scope_type){//是部门
               $scope_level_2_value = arr_unbound_value($scope_array,'scope_level_2_value',2,'');
               if(!bn_is_empty($scope_level_2_value)){//不为空
                   if(!bn_is_empty($scope_value)){//不为空,则加,号分隔符
                       $scope_value .= ',';
                   }
                   $scope_value .= $scope_level_2_value;
               }
            }
        }
        return $scope_value;
    }

}
