<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Power_ecology_class {
  private $power_type ;//1生态企业属性
  private $power_class_order ;//当前类型分类排序串
  private $power_order ;//当前权限排序串
  private $power_ok_class_arr ;// 当前类型权限数组  
  private $power_ok_arr ;// 当前类型下的权限数组
  
  private $power_order_arr = array(//排序数组
      'class_order' => array(//类型排序数组,没在值里的，会排在后面
          '1' => '1',//键为权限用在的类型,1生态企业属性 ,值为类型id,多个,号分隔，前面的在前
          '2' => '',
          '3' => '',
          '4' => '',
          
      ),
      'power_order' => array(//同class_order
          '1' => '',//键为权限用在的类型,1生态企业属性 ,值为boss_name_boss_property,多个,号分隔，前面的在前
          '2' => '',
          '3' => '',
          '4' => '',
          )
  );
  
  private $power_class_arr = array(
      array(
          'id' => 1,//类型编号
          'name' => '生态企业权限',//类型名称
          'enable' => 1,//是否可用0不可用1可用
          'whow_type' => array(
                '1' => 5,//类型编号,键为页面类型,值为显示类型0输入框1下接框2单选框3复选框[多个]4复选框[单个]5直接显示文本6用value_arr作为复选框列,不选为空7[名称]值单选
                '2' => 5,
                '3' => 5,
                '4' => 5,
            ),//权限用在的类型,多个用,号分隔,1生态企业属性 
          
      ),

  );  
  private $power_arr = array(
      
         array(
            'enable' => 1,//是否可用0不可用1可用
            'name' => '可使用全时sooncore平台 IM 互传文档',//说明 passDoc=>1：不允许2：允许  UC_passDoc
            'boss_name' => 'UC',//boss权限所属名称
            'boss_property' => 'passDoc',//权限在BOSS中的属性名称[注意大小写]
            'value_arr' => array(
                '1' => '不允许',
                '2' => '允许'
            ),//值串
            'default_value' => 1,//默认值
            'regex' => '/^[12]+$/',//50个中英文数字//^[\x80-\xffA-Za-z\d]{1,50}$/
            'whow_type' => 4,//显示类型0输入框1下接框2单选框3复选框[多个]4复选框[单个]5直接显示文本6用value_arr作为复选框列,不选为空7[名称]值单选
            'type' => array(
                '1' => '',//类型编号,值为当前编号下的名称;如果为空则用boss_property
                '2' => '',
                '3' => '',
                '4' => '',
                
            ),//权限用在的类型,多个用,号分隔,1生态企业属性
            'class' => '1',//权限分类，多个用,号分隔
            'value' => '',//值,多个用,号分隔
         ), 
         array(
            'enable' => 0,//是否可用0不可用1可用
            'name' => '自动将联系过的联系人添加到常用联系人列表',//说明
            'boss_name' => 'UC',//boss权限所属名称
            'boss_property' => '?',//权限在BOSS中的属性名称[注意大小写]
            'value_arr' => array(
                '0' => 'aaa',
                '1' => 'aa',
                '2' => 'aa'
            ),//值串
            'default_value' => 1,//默认值
            'regex' => '/^[012]+$/',//50个中英文数字//^[\x80-\xffA-Za-z\d]{1,50}$/
            'whow_type' => 4,//显示类型0输入框1下接框2单选框3复选框[多个]4复选框[单个]5直接显示文本6用value_arr作为复选框列,不选为空7[名称]值单选
            'type' => array(
                '1' => '',//类型编号,值为当前编号下的名称;如果为空则用boss_property
                '2' => '',
                '3' => '',
                '4' => '',
            ),//权限用在的类型,多个用,号分隔,1生态企业属性
            'class' => '1',//权限分类，多个用,号分隔
            'value' => '',//值,多个用,号分隔
         )
    );
  
    public function __construct($power_type = 1){
        //载入接口公用函数
        include_once APPPATH . 'helpers/my_httpcurl_helper.php'; 
        $this->power_type = $power_type ;
        $this->power_init();//根据权限类型，获得权限分类及权限的排序串
    }
    /**
     *
     * @brief 获得权限的信息
     * @details 
     * @return array 返回标签数组 注意原来的数组 'type' 下标,由数组转换为真实的属性txt
     *
     */
    public function get_power(){
        $re_array = array(
            'power_class' =>$this->power_ok_class_arr,
            'power_arr' => $this->power_ok_arr
        );
        return $re_array;
    }
    /**
     *
     * @brief 根据权限类型，获得权限分类及权限的排序串
     * @details 
     * @return array 返回标签数组 注意原来的数组 'type' 下标,由数组转换为真实的属性txt
     *
     */
    public function power_init(){
        $class_order_arr = arr_unbound_value($this->power_order_arr,'class_order',1,array());
        $power_order_arr = arr_unbound_value($this->power_order_arr,'power_order',1,array());
        $this->power_class_order = arr_unbound_value($class_order_arr,$this->power_type,2,'');//当前类型分类排序串
        $this->power_order = arr_unbound_value($power_order_arr,$this->power_type,2,'');//当前权限排序串
        //按顺序组织分类
        $this->power_ok_class_arr = $this->get_power_class_arr();
        //按顺序组织类型
        $this->power_ok_arr = $this->get_power_arr();
    }
    /**
     *
     * @brief 获得当前类型的权限数组[按顺序]
     * @details 
     * @return array 返回标签数组 注意原来的数组 'whow_type' 下标,由数组转换为真实的属性txt
     *
     */
    public function get_power_class_arr(){
       $re_arr = array();        
       $pre_arr = array();//在顺序串里的数组
       $back_arr = array();//没在顺序串里的数组
       $order_arr = explode(',', $this->power_class_order);
       //把顺序值改为键，值改为空
       foreach ($order_arr as $k => $v){
           $pre_arr[$v] = '';
       }
       foreach ($this->power_class_arr as $k => $v){
           if(!isemptyArray($v)){//不为空
              $is_enable = arr_unbound_value($v,'enable',2,0);
              if($is_enable == 1){//可使用
                $type_arr = arr_unbound_value($v,'whow_type',1,array());             
                if(is_array($type_arr)){//是数组
                $class_id = arr_unbound_value($v,'id',2,'');
                $new_suffix = $class_id ;
                foreach($type_arr as $t_k => $t_v){                     
                   if($t_k == $this->power_type){
                        $ns_power_type = $t_v;
                        $v['whow_type'] = $ns_power_type;
                        //下标是否在下标数组里                          
                        if(deep_in_array($new_suffix, $order_arr)){//在里面
                          $pre_arr[$new_suffix] = $v;
                        }else{//不在里面
                          $back_arr[$new_suffix] = $v;
                        }
                        break;
                   } 
                }
              }
              }
           }
       }       
       //去掉空值
       foreach($pre_arr as $k => $v){
           if(isemptyArray($v)){//空，则去除
               unset($pre_arr[$k]);
           }
       }
       $re_arr = array_merge($pre_arr,$back_arr);
       return $re_arr;
    }
    /**
     *
     * @brief 获得当前类型的权限数组[按顺序]
     * @details 
     * @return array 返回标签数组 注意原来的数组 'type' 下标,由数组转换为真实的属性txt
     *
     */
    public function get_power_arr(){
       $re_arr = array(); 
       $pre_arr = array();//在顺序串里的数组
       $back_arr = array();//没在顺序串里的数组
       $order_arr = explode(',', $this->power_order);
       //把顺序值改为键，值改为空
       foreach ($order_arr as $k => $v){
           $pre_arr[$v] = '';
       }
       foreach ($this->power_arr as $k => $v){
           if(!isemptyArray($v)){//不为空
              $is_enable = arr_unbound_value($v,'enable',2,0);
              if($is_enable == 1){//可使用
                $type_arr = arr_unbound_value($v,'type',1,array());             
                if(is_array($type_arr)){//是数组                  
                  $boss_name = arr_unbound_value($v,'boss_name',2,'');
                  $boss_property = arr_unbound_value($v,'boss_property',2,'');
                  $new_suffix = $boss_name . '_' . $boss_property;
                  foreach($type_arr as $t_k => $t_v){                     
                     if($t_k == $this->power_type){
                          $ns_power_type = $t_v;
                          if(bn_is_empty($ns_power_type)){//为空
                              $ns_power_type = $boss_property;
                          }
                          $v['type'] = $ns_power_type;
                          //下标是否在下标数组里                          
                          if(deep_in_array($new_suffix, $order_arr)){//在里面
                            $pre_arr[$new_suffix] = $v;
                          }else{//不在里面
                            $back_arr[$new_suffix] = $v;
                          }
                          break;
                     } 
                  }
              }
              }
           }
       }
       //去掉空值
       foreach($pre_arr as $k => $v){
           if(isemptyArray($v)){//空，则去除
               unset($pre_arr[$k]);
           }
       }
       $re_arr = array_merge($pre_arr,$back_arr);
       return $re_arr;
    } 
}


