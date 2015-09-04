<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log_class {  
   private $log_arr = array(//类型数组
         array(
            'id' => 1,//id
            'name' => '启用全时工作圈',//
            'isopen' => 1,//是否开启
            'item' => array(
                array(
                    'id' => 1,
                    'isopen' => 1,//是否开启
                    'content' => '',
                )
            ),
        ), 
         array(
            'id' => 2,//id
            'name' => '使用同步',//
            'isopen' => 1,//是否开启
            'item' => array(
                array(
                    'id' => 1,
                    'isopen' => 1,//是否开启
                    'content' => '初始化同步作业',
                ),
                array(
                    'id' => 2,
                    'isopen' => 1,//是否开启
                    'content' => '执行同步作业',
                )
            ),
        ),  
         array(
            'id' => 3,//id
            'name' => '批量导入',//
            'isopen' => 1,//是否开启
            'item' => array(
                array(
                    'id' => 1,
                    'isopen' => 1,//是否开启
                    'content' => '初始化执行导入',
                ),
                array(
                    'id' => 2,
                    'isopen' => 1,//是否开启
                    'content' => '执行导入',
                )
            ),
        ),  
         array(
            'id' => 4,//id
            'name' => '更新版本',//
            'isopen' => 1,//是否开启
            'item' => array(
                array(
                    'id' => 1,
                    'isopen' => 1,//是否开启
                    'content' => '启动更新版本',
                )
            ),
        ),  
         array(
            'id' => 5,//id
            'name' => '组织管理',//
            'isopen' => 1,//是否开启
            'item' => array(
                array(
                    'id' => 1,
                    'isopen' => 1,//是否开启
                    'content' => '添加部门',
                ),
                array(
                    'id' => 2,
                    'isopen' => 1,//是否开启
                    'content' => '删除部门',
                ),
                array(
                    'id' => 3,
                    'isopen' => 1,//是否开启
                    'content' => '修改部门名称',
                ),
                array(
                    'id' => 4,
                    'isopen' => 1,//是否开启
                    'content' => '添加员工（新增）',
                ),
                array(
                    'id' => 5,
                    'isopen' => 1,//是否开启
                    'content' => '删除员工（离职）',
                ),
                array(
                    'id' => 6,
                    'isopen' => 1,//是否开启
                    'content' => '变更员工所属部门（调岗）',
                ),
                array(
                    'id' => 7,
                    'isopen' => 1,//是否开启
                    'content' => '开启员工工作圈帐号',
                ),
                array(
                    'id' => 8,
                    'isopen' => 1,//是否开启
                    'content' => '关闭员工工作圈帐号',
                ),
                array(
                    'id' => 9,
                    'isopen' => 1,//是否开启
                    'content' => '新增 Cost Center',
                ),
                array(
                    'id' => 10,
                    'isopen' => 1,//是否开启
                    'content' => '删除 Cost Center',
                ),
                array(
                    'id' => 11,
                    'isopen' => 1,//是否开启
                    'content' => '添加员工标签名称',
                ),
                array(
                    'id' => 12,
                    'isopen' => 1,//是否开启
                    'content' => '修改员工标签名称',
                ),
                array(
                    'id' => 13,
                    'isopen' => 1,//是否开启
                    'content' => '删除员工标签名称',
                ),
                array(
                    'id' => 14,
                    'isopen' => 1,//是否开启
                    'content' => '重设员工密码',
                ),
                array(
                    'id' => 15,
                    'content' => '指定员工管理员',
                ),
                array(
                    'id' => 16,
                    'isopen' => 1,//是否开启
                    'content' => '变更员工管理员权限',
                ),
                array(
                    'id' => 17,
                    'isopen' => 1,//是否开启
                    'content' => '移除员工管理员',
                ),
                array(
                    'id' => 18,
                    'isopen' => 1,//是否开启
                    'content' => '移动部门',
                ),
            ),
        ),  
         array(
            'id' => 6,//id
            'name' => '应用管理',//
            'isopen' => 1,//是否开启
            'item' => array(
                array(
                    'id' => 1,
                    'isopen' => 1,//是否开启
                    'content' => '指定应用给员工',
                ),
                array(
                    'id' => 2,
                    'isopen' => 1,//是否开启
                    'content' => '移除应用给员工',
                ),
                array(
                    'id' => 3,
                    'isopen' => 1,//是否开启
                    'content' => '添加新的应用',
                ),
                array(
                    'id' => 4,
                    'isopen' => 1,//是否开启
                    'content' => '移除新的应用',
                ),
                array(
                    'id' => 5,
                    'isopen' => 1,//是否开启
                    'content' => '指定合作伙伴权限给部门',
                ),
            ),
        ),  
         array(
            'id' => 7,//id
            'name' => '安全管理',//
            'isopen' => 1,//是否开启
            'item' => array(
                array(
                    'id' => 1,
                    'isopen' => 1,//是否开启
                    'content' => '导入敏感词',
                ),
                array(
                    'id' => 2,
                    'isopen' => 1,//是否开启
                    'content' => '添加敏感词',
                ),
                array(
                    'id' => 3,
                    'isopen' => 1,//是否开启
                    'content' => '删除敏感词',
                ),
                array(
                    'id' => 4,
                    'isopen' => 1,//是否开启
                    'content' => '建立豁免规则',
                ),
                array(
                    'id' => 5,
                    'isopen' => 1,//是否开启
                    'content' => '删除豁免规则',
                ),
                array(
                    'id' => 6,
                    'isopen' => 1,//是否开启
                    'content' => '编辑豁免规则',
                ),
                array(
                    'id' => 7,
                    'isopen' => 1,//是否开启
                    'content' => '变更密码规则',
                ),
            ),
        ),  
         array(
            'id' => 8,//id
            'name' => '报告管理',//
            'isopen' => 1,//是否开启
            'item' => array(
                array(
                    'id' => 1,
                    'isopen' => 1,//是否开启
                    'content' => '下载报告',
                )
            ),
        ),  
         array(
            'id' => 2,//id
            'name' => '设置管理',//
            'isopen' => 1,//是否开启
            'item' => array(
                array(
                    'id' => 1,
                    'isopen' => 1,//是否开启
                    'content' => '变更站点设置',
                )
            ),
        ), 
   		array(
   				'id' => 9,//id
   				'name' => '企业生态',//
   				'isopen' => 1,//是否开启
   				'item' => array(
   						array(
   								'id' => 1,
   								'isopen' => 1,//是否开启
   								'content' => '添加生态企业',
   						),
   						array(
   								'id' => 2,
   								'isopen' => 1,//是否开启
   								'content' => '添加生态企业渠道管理员',
   						),
   						array(
   								'id' => 3,
   								'isopen' => 1,//是否开启
   								'content' => '删除生态企业',
   						)
   				),
   		),
   );
    /**
     *
     * @brief 构造函数
     * @details 
     * @return array 
     *
     */
    public function __construct() {
       // echo 'aaaa';
        //载入接口公用函数
        include_once APPPATH . 'helpers/my_httpcurl_helper.php'; 
    }
    /**
     *
     * @brief 获得所有的日志类型数组
     * @details 
     * @return array 日志类型数组
     *
     */
    public function get_log_arr(){
        return $this->log_arr;
    }
    /**
     *
     * @brief 根据日志类型数组，获得对应的日志类型数组
     * @details 
     * @param array $type_arr = array('1','2')  
     * @param array $in_log_arr  日志类型数组[可以为空]
     * @return array 日志类型数组
     *
     */
    public function get_typ_log_arr($type_arr = array(),$in_log_arr = array()){
         $re_arr = array();
         if(isemptyArray($type_arr)){//如果是空数组
             return $re_arr;
         }
         if(isemptyArray($in_log_arr)){//如果是空数组
            $in_log_arr = $this->log_arr;
         }
         if(isemptyArray($in_log_arr)){//如果是空数组
             return $re_arr;
         } 
        $big_num = arr_unbound_value($type_arr,0,2,'');
        $small_num = arr_unbound_value($type_arr,1,2,'');
        foreach($in_log_arr as $k => $v){
            if(!bn_is_empty($big_num)){
                $big_id = arr_unbound_value($v,'id',2,'');                        
                if($big_id == $big_num){
                    $item_arr = arr_unbound_value($v,'item',1,array());
                    foreach($item_arr as $i_k => $i_v){                       
                       if(!bn_is_empty($small_num)){
                           $sml_id = arr_unbound_value($i_v,'id',2,'');
                           if($small_num == $sml_id){
                               $big_name = arr_unbound_value($v,'name',2,'');
                               $big_isopen = arr_unbound_value($v,'isopen',2,'');
                               $re_arr = $i_v;
                               $re_arr['big_id'] = $big_id;
                               $re_arr['big_name'] = $big_name;
                               $re_arr['big_isopen'] = $big_isopen;
                               break;
                           }
                       }
                    }
                    break;
                }
            }                    
        }
        
        log_message('info', 'xiaoxiaoxiao='.var_export($re_arr, true));
        return $re_arr;
    } 
    
    
}


