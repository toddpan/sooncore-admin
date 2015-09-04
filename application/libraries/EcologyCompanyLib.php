<?php if (!defined('BASEPATH'))	exit('No direct script access allowed');

/**
 * @class EcologyCompanyLib
 * @brief EcologyCompanyLib 类库，主要负责对生态企业相关操作。
 * @file EcologyCompanyLib.php
 * @author zouyan <yan.zou@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
class EcologyCompanyLib{
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
     * @brief 获得生态的本方参与人： 
     * @details 
     * @param array $partake_where_arr  管理员查询条件数组 operateDB 方法的条件形式
        $partake_where_arr = array(
                'org_id =' => $this->p_org_id, // 本方参与人的根据组织id
                'site_id =' => $this->p_site_id,// 本方参与人的站点id
                'ecology_id =' => $org_id,//生态企业id
        );
     * @return array 本主参与人帐号信息 
     *
     */
    public function get_ecology_partake_arr($partake_where_arr = array()){
        log_message('info', 'into method ' . __FUNCTION__ . '.');
        $CI =& get_instance();
        $CI->load->helper('my_publicfun');
        $CI->load->helper('my_dgmdate'); 
        $CI->load->library('API','','API');
        $CI->load->model('uc_ecology_partake_model');

        $data_ecology = array(
            'select' =>'user_id',
            'where' => $partake_where_arr,
//            array(
//                'org_id =' => $this->p_org_id, // 本方参与人的根据组织id
//                'site_id =' => $this->p_site_id,// 本方参与人的站点id
//                'ecology_id =' => $org_id,//生态企业id
//             ),
        );
        $ecology_arr = $CI->uc_ecology_partake_model->operateDB(2,$data_ecology);
        $ecology_idarr = array();
        foreach ($ecology_arr as $k => $v){
            $ns_user_id = arr_unbound_value($v,'user_id',2,'');
            if(!bn_is_empty($ns_user_id)){//有数据
                $ecology_idarr[] = $ns_user_id;
            }
        }
        $user_arr = array();
        if(!isemptyArray($ecology_idarr)){//如果不是空数组
            $ecology_ids = json_encode($ecology_idarr);
            if(!bn_is_empty($ecology_ids)){//有数据
                $ums_api_arr = $CI->API->UMS_Special_API($ecology_ids,2);
                if(!api_operate_fail($ums_api_arr)){//成功
                    $user_arr = arr_unbound_value($ums_api_arr,'data',1,array());
                }else{//失败
                    log_message('error', 'ums api fail'); 
                }
            }
        }
        return $user_arr;
    }
}
