<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Component_class {  
  private $component_arr = array(//数组
      array(
        'componentId' => 1,//id
        'soleName' => 'tang',//唯一名称
        'componentName' => 'tang',//中文名
        'componentNameEn' => 'tang',//英文名
      ),
      array(
        'componentId' => 2,//id
        'soleName' => 'summit',//唯一名称
        'componentName' => 'summit',//中文名
        'componentNameEn' => 'summit',//英文名
      ),
      array(
        'componentId' => 3,//id
        'soleName' => 'acp',//唯一名称
        'componentName' => 'acp',//中文名
        'componentNameEn' => 'acp',//英文名
      ),
      array(
        'componentId' => 4,//id
        'soleName' => 'UC',//唯一名称
        'componentName' => 'UC',//中文名
        'componentNameEn' => 'UC',//英文名
      ),
      array(
        'componentId' => 5,//id summit+radisys
        'soleName' => 'summitradisys',//唯一名称
        'componentName' => 'radisys',//中文名
        'componentNameEn' => 'radisys',//英文名
      ),
      array(
        'componentId' => 6,//id radisys
        'soleName' => 'radisys',//唯一名称
        'componentName' => 'radisys',//中文名
        'componentNameEn' => 'radisys',//英文名
      )
  );
    public function __construct() {
        //载入接口公用函数
        include_once APPPATH . 'helpers/my_httpcurl_helper.php'; 
    }
    /**
     *
     * @brief 根据中文名称，获得id信息
     * @details 
     * @param string $com_name  中文名称
     * @return int componentId
     *
     */
    public function get_comid($com_name = ''){        
        $re_id = 0;
        foreach ($this->component_arr as $k => $v){
            $soleName = arr_unbound_value($v,'soleName',2,'');
            if(strtolower($soleName) == strtolower($com_name)){
                $re_id = arr_unbound_value($v,'componentId',2,'');
                break;
            }
        }
        return $re_id;
    }
}


