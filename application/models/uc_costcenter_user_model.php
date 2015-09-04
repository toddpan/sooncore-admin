<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UC_Costcenter_User_Model extends MY_Model{

    const TBL = 'uc_costcenter_user';
    //构造函数
    public function __construct(){
        //调用父类构造函数，必不可少
        parent::__construct(); 
        $this->set_table('uc_costcenter_user');
    }
    /**
     *
     * @brief 成本中心人员与成本中心联合查询，获得当前组织成本中心的所有用户： 
     * @details
     * @param string $cost_ids //成本中心id,多个用逗号分隔,没有则查所有成本中心
     * @param int $re_type //返回类型 1多个逗号分隔[可能为空]，2二维数组
     * @return array 用户id数组
     *
     */
      public function get_all_cost_center_user($cost_ids = '',$re_type = 2){          
          $sql = 'select a.user_id from uc_costcenter_user a ,uc_site_costcenter b where a.cost_id = b.id ';
          if(!bn_is_empty($cost_ids)){//有数据
             $sql .= ' and b.id in(' . $cost_ids . ')';  
          }
          $re_user_arr = $this-> get_db_arr_by_sql($sql,2);   

          if($re_type == 2){//2二维数组
              return $re_user_arr;
          }else{
              $id_txt = '';//
              foreach ($re_user_arr as $u_k =>$u_v_arr){
                  $ns_user_id = arr_unbound_value($u_v_arr,'user_id',2,'');
                  if(!bn_is_empty($ns_user_id)){//有数据
                    if(!bn_is_empty($id_txt)){//有数据
                        $id_txt .= ',';
                    }
                    $id_txt .= $ns_user_id;
                  }
              }
              return $id_txt;
          }
      }
    /**
     *
     * @brief 批量移动员工到新的成本中心： 
     * @details
     * @param string $user_ids  员工ID,多个用逗号分隔
     * @param int $cost_Id  当前成本中心ID
     * @return boolean  成功TRUE 失败false     *
     */
      public function save_cost_users($user_ids_arr,$cost_Id = ''){
          if(bn_is_empty($user_ids_arr) || bn_is_empty($cost_Id)){//没有数据
              return false;
          }

          foreach ($user_ids_arr as $k => $v) {              
              $ns_user_id = $v;              
              if(!bn_is_empty($ns_user_id)){//有数据
                    //有，则不操作，没有则新加
                    $where_arr = array(
                        'user_id' => $ns_user_id,
                        //'cost_id' => $cost_Id,
                        //'user_id' => $user_id,
                           );
                    //$modify_arr = $where_arr;
                    $modify_arr = array(
                        'user_id' => $ns_user_id,
                        'cost_id' => $cost_Id,
                        //'user_id' => $user_id,
                    );
					/*
                    $where_arrarray(
                        'org_id' => $org_id,
                        'site_id' => $site_id,
                        'user_id' => $user_id,
                           ); 
                     * 
                     */
                    $insert_arr = $modify_arr;
                    //$insert_arr['create_time'] = dgmdate(time(), 'dt');
                    $re_num = $this-> updata_or_insert(1,'id',$where_arr,$modify_arr,$insert_arr);
                    /*
                    if($re_num > 0 ){//如果大于0，则返回新加记录id
                        return $re_num;
                    }
                    switch ($re_num) {//-1 更新记录成功 -2更新操作失败 -3新加的记录id 新加记录成功 -4新加操作失败 -5 没有操作
                       case -2:  
                       case -4: 
                           return 0;
                           break;
                       default:
                           return 1;
                           break;
                   }
                     * 
                     */
              }
          }
          return true;          
      } 
    /**
     *
     * @brief 批量从一个成本中心移动员工到新的成本中心： 
     * @details
     * @param int $old_cost_Id  旧成本中心ID
     * @param string $user_ids  员工ID,多个用逗号分隔
     * @param int $cost_Id  当前成本中心ID
     * @return boolean  成功TRUE 失败false     *
     */
      public function save_cost_users_from_old_cost($old_cost_Id='',$user_ids = '',$cost_Id = ''){
          if(bn_is_empty($old_cost_Id) ||  bn_is_empty($user_ids) || bn_is_empty($cost_Id)){//没有数据
              return false;
          }
            $operate_type =2; ////有，则修改，没有则不新加 1、有记录则更新记录，没记录则新加；  2、有记录则更新记录，没有则不新加  3、有记录则不操作，没有则新加  *
            if($old_cost_Id == 0){
              $operate_type =1; //有，则修改，没有则新加                        
            }
         $user_id_arr =  explode(',', $user_ids);
          foreach ($user_id_arr as $k => $v) {              
              $ns_user_id = $v;              
              if(!bn_is_empty($ns_user_id)){//有数据
                    $where_arr = array(
                        'user_id' => $ns_user_id,
                        'cost_id' => $old_cost_Id,
                        //'user_id' => $user_id,
                           );
                    $modify_arr = array(
                        'user_id' => $ns_user_id,
                        'cost_id' => $cost_Id,
                        //'user_id' => $user_id,
                           ); 
                    $insert_arr = $modify_arr;
                    //$insert_arr['create_time'] = dgmdate(time(), 'dt');
                    $re_num = $this-> updata_or_insert($operate_type,'id',$where_arr,$modify_arr,$insert_arr);
                    /*
                    if($re_num > 0 ){//如果大于0，则返回新加记录id
                        return $re_num;
                    }
                    switch ($re_num) {//-1 更新记录成功 -2更新操作失败 -3新加的记录id 新加记录成功 -4新加操作失败 -5 没有操作
                       case -2:  
                       case -4: 
                           return 0;
                           break;
                       default:
                           return 1;
                           break;
                   }
                     * 
                     */
              }
          }
          return true;          
      }
      
    /**
     *
     * @brief 批量移除成本中心员工： 
     * @details
     * @param string $user_ids  员工ID,多个用逗号分隔;可以为空，则删除当前成本中心所有用户
     * @param int $cost_Id  当前成本中心ID
     * @return boolean  成功TRUE 失败false     *
     */
      public function del_cost_users($user_ids = '',$cost_Id = ''){
            if(bn_is_empty($cost_Id)){//没有数据
              return false;
            }
            $where_arr = array(
                'cost_id' => $cost_Id,
                //'site_id' => $site_id,
                //'user_id' => $user_id,
                   );
           $del_arr = array(
               'where' => $where_arr,
              // 'where_in' =>array('user_id' =>explode(',', $user_ids))//array('user_id',array('Frank', 'Todd', 'James'))
               
           );

           if(!bn_is_empty($user_ids)){//有数据
               $del_arr['where_in'] = array('user_id' => explode(',', $user_ids));
            }

           $re_del_arr = $this->operateDB(4,$del_arr); 
           if(db_operate_fail($re_del_arr)){//失败
               return false;
           }else{
               return true;
           }
      }
 
      
    /**
     *
     * @brief 批量移动员工到新的成本中心： 
     * @details
     * @param int $site_id  当前站点ID
     * @param int $org_id  当前组织ID
     * @param string $staff_IdS  员工ID
     * @param string $cost_Id  当前成本中心ID
     * @return array 状态
     *
     */
      public function batchMoveStaff($site_id,$org_id,$staff_IdS,$Cost_id){
          $datearr=array();
          //通过接口获得
          return $datearr;
      }
    /**
     *
     * @brief 批量移除员工： 
     * @details
     * @param int $site_id  当前站点ID
     * @param int $org_id  当前组织ID
     * @param string $staff_IdS  员工ID
     * @param string $cost_Id  当前成本中心ID
     * @return array 状态
     *
     */
      public function batchDelStaff($site_id,$org_id,$staff_IdS,$Cost_id){
          $datearr=array();
          //通过接口获得
          return $datearr;
      }
    /**
     *
     * @brief 获取分页员工数据： 
     * @details
     * @param int $site_id  当前站点ID
     * @param int $org_id  当前组织ID
     * @param string $cost_Id  当前成本中心ID
     * @param int $limit  数量
     * @param int $offset  位置
     * @return array 获得的数据
     *
     */
    public function getCostcenterStaff($site_id,$org_id,$cost_Id,$limit,$offset){
        $query = $this->db->limit($limit,$offset)->get(self::TBL_GT);
        return $query->result_array();
    }
    /**
     *
     * @brief 统计商品类型的总数： 
     * @details
     * @param int $site_id  当前站点ID
     * @param int $org_id  当前组织ID
     * @param string $cost_Id  当前成本中心ID
     * @return array 获得的数据
     *
     */
    public function countCostcenterStaff($site_id,$org_id,$cost_Id){
        return $this->db->count_all(self::TBL_GT);
    }
    /**
     *
     * @brief 获得所有有成本中心的员工标识： 
     * @details
     * @param int $site_id  当前站点ID
     * @param int $org_id  当前组织ID
     * @param int $cost_Id  当前成本中心ID，默认为0获得所有的，有值则获得具体的
     * @return array 获得的数据
     *
     */
    public function getStaffIdsByCostId($site_id,$org_id,$cost_Id){
        return $this->db->count_all(self::TBL_GT);
    }
      
}