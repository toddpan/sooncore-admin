<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pass_word_attr{
    //用户密码有效期数组，注意天数daynum为0时代表不需要变更
        private $expiry_day_type_arr = array(
        array(
            'id' => 1,
            'title' => '30天',
            'daynum' => 30 ,
            'isdefaultvalue' => 0
        ),
        array(
            'id' => 2,
            'title' => '60天',
            'daynum' => 60 ,
            'isdefaultvalue' => 0
        ),
        array(
            'id' => 3,
            'title' => '90天',
            'daynum' => 90 ,
            'isdefaultvalue' => 1
        ),
        array(
            'id' => 4,
            'title' => '180天',
            'daynum' => 180 ,
            'isdefaultvalue' => 0
        ),
        array(
            'id' => 5,
            'title' => '不需要变更',
            'daynum' => 0 ,
            'isdefaultvalue' => 0
        )

    );
    //密码历史记忆,注意num值为0代表不记忆
    private $history_type_arr = array(
        array(
            'id' => 1,
            'title' => '3次',
            'num' => 3 ,
            'isdefaultvalue' => 0
        ),
        array(
            'id' => 2,
            'title' => '5次',
            'num' => 5 ,
            'isdefaultvalue' => 0
        ),
        array(
            'id' => 3,
            'title' => '10次',
            'num' => 10 ,
            'isdefaultvalue' => 1
        ),
        array(
            'id' => 4,
            'title' => '不记忆',
            'num' => 0 ,
            'isdefaultvalue' => 0
        )    
    );
    //密码复杂性要求 
    private $complexity_type_arr = array(
        array(
            'id' => 1,
            'title' => '8-30位字符（不限制类型）组成',
            'regexptxt' => '/^[\s\S]{8,30}$/' ,
            'isdefaultvalue' => 0
        ),
        array(
            'id' => 2,
            'title' => '8-30位数字与字母组合而成',
            'regexptxt' => '/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{8,30}$/' ,
            'isdefaultvalue' => 1
        ),
        array(
            'id' => 3,
            'title' => '8-30位数字、符号与字母组合而成',
            'regexptxt' => '/^(?![0-9]+$)(?![a-zA-Z]+$)(?![0-9a-zA-Z]+$)[\S]{8,30}$/' ,
            'isdefaultvalue' => 0
        )
    );
   
    //获得用户密码有效期数组
    public function get_expiry_day_type_arr(){       
        return $this->expiry_day_type_arr;
   }
    //获得密码历史记忆
    public function get_history_type_arr(){       
        return $this->history_type_arr;
   }
    //获得密码复杂性要求
    public function get_complexity_type_arr(){       
        return $this->complexity_type_arr;
   }
   

    /**
     *
     * @brief 根据值，获得相应数组，如果编号不存在则返回默认的
     * @details 
     * @param int $type 当前的类型1获得用户密码有效期数组2密码历史记忆3密码复杂性要求
     * @param int $id 当前的编号1获得用户密码有效期数组2密码历史记忆3密码复杂性要求 的值
     * @return array 返回当前编号的信息数组,
     *
     */
   public function get_arr_byid($type = 1,$id = 0){
        $re_data_arr = array();//最终返回的值
        $re_default_arr = array();//默认值
        $re_is_sed = 0;//是否已经选中0没有选中1已经有选中

         switch ($type) {
             case 1: //1获得用户密码有效期数组
                 $data_arr = $this->expiry_day_type_arr;
                 break;
             case 2://2密码历史记忆
                 $data_arr = $this->history_type_arr;
                 break;
             case 3://3密码复杂性要求
                 $data_arr = $this->complexity_type_arr;
                 break;
         }


        foreach($data_arr as $key => $valarr){
            $id_value = isset($valarr['id'])?$valarr['id']:'';
            if ($id == $id_value){
               $re_is_sed = 1;
               $re_data_arr = $valarr; 
               break ; 
            }
            if ($re_is_sed == 0){
               $isdefaultvalue = isset($valarr['isdefaultvalue'])?$valarr['isdefaultvalue']:'';
               if($isdefaultvalue == 1){
                   $re_default_arr = $valarr;
               } 
            }

        }
        
        if ($re_is_sed == 0){
            $re_data_arr = $re_default_arr;
        }
        return $re_data_arr;  
   } 
}


