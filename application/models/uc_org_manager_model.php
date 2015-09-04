<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class UC_Org_Manager_Model extends MY_Model{

    //const TBL = 'uc_org_manager';
    //构造函数
    public function __construct(){
        //调用父类构造函数，必不可少
        parent::__construct(); 
        $this->set_table('uc_org_manager');
    }
    /**
     *
     * @brief 根据组织id站点id,获得当前组织管理者,没有则返回0
     * @details
     * @param int $org_id 组织id
     * @param int $site_id 站点id
     * @return int 当前组织管理者id,没有则返回0
     *
     */
    public function get_org_manager_userid($org_id = 0,$site_id = 0){
        log_message('info', 'into method ' . __FUNCTION__ . '.');
        //获得当前组织管理者信息
        $sel_field = 'user_id';
        $where_arr = array(
                'org_id' => $org_id, 
                'site_id' => $site_id,                           
            );
       $sel_arr = $this->get_db_arr($where_arr,$sel_field);       
       $org_user_id = arr_unbound_value($sel_arr,'user_id',2,0);       
        log_message('info', 'out method ' . __FUNCTION__ . '.');
        return $org_user_id;
            
    }  
    /**
     *
     * @brief 根据组织id,获得当前组织管理者,没有则返回0
     * @details
     * @param int $org_id 组织id
     * @param int $site_id 站点id
     * @return int 当前组织管理者id,没有则返回0
     *
     */
    public function get_org_manager_byorgid($org_id = 0){
        log_message('info', 'into method ' . __FUNCTION__ . '.');
        //获得当前组织管理者信息
        $sel_field = 'user_id';
        $where_arr = array(
                'org_id' => $org_id,                            
            );
       $sel_arr = $this->get_db_arr($where_arr,$sel_field);       
       $org_user_id = arr_unbound_value($sel_arr,'user_id',2,0);       
        log_message('info', 'out method ' . __FUNCTION__ . '.');
        return $org_user_id;
            
    } 
    /**
     *
     * @brief 根据组织id站点id用户id,返回当前用户是否是组织管理者
     * @details
     * @param int $user_id 帐号id
     * @param int $org_id 组织id
     * @param int $site_id 站点id
     * @return int 是true 不是 false
     *
     */
    public function userid_is_org_manager($user_id = 0,$org_id = 0,$site_id = 0){
       log_message('info', 'into method ' . __FUNCTION__ . '.');
        $re_boolean = false;
        //获得当前组织管理者信息
        $sel_field = 'id';
        $where_arr = array(
                'org_id' => $org_id, 
                'site_id' => $site_id, 
                'user_id' => $user_id, 
            );
       $sel_arr = $this->get_db_arr($where_arr,$sel_field);  
       $manager_id = arr_unbound_value($sel_arr,'id',2,0);  
       if($manager_id > 0){
           $re_boolean = true;
       }
        log_message('info', 'out method ' . __FUNCTION__ . '.');
        return $re_boolean;
    }
    /**
     *
     * @brief 根据用户id,返回当前用户是否是组织管理者
     * @details
     * @param int $user_id 帐号id
     * @return int 是true 不是 false
     *
     */
    public function user_is_org_manager($user_id = 0){
       log_message('info', 'into method ' . __FUNCTION__ . '.');
        $re_boolean = false;
        //获得当前组织管理者信息
        $sel_field = 'id';
        $where_arr = array(
                'user_id' => $user_id, 
            );
       $sel_arr = $this->get_db_arr($where_arr,$sel_field);  
       $manager_id = arr_unbound_value($sel_arr,'id',2,0);  
       if($manager_id > 0){
           $re_boolean = true;
       }
        log_message('info', 'out method ' . __FUNCTION__ . '.');
        return $re_boolean;
    }
    /**
     *
     * @brief 根据用户id,如果是组织管理者，进行删除操作
     * @details
     * @param int $user_id 帐号id
     * @return int 是true 不是 false
     *
     */
    public function del_org_manager($user_id = 0){
        log_message('info', 'into method ' . __FUNCTION__ . '.');
        $re_boolean = false;
        //获得当前组织管理者信息
        $where_arr = array(
            'user_id' => $user_id
               );
       $del_arr = array(
           'where' => $where_arr,

       );
       $re_del_arr = $this -> operateDB(4,$del_arr); 
       if(db_operate_fail($re_del_arr)){//失败
           $re_boolean = false;
       }else{
           $re_boolean = true;
       } 
        log_message('info', 'out method ' . __FUNCTION__ . '.');
        return $re_boolean;
    } 
    /**
     *
     * @brief 根据用户信息设置成组织管理者，有进行修改操作，没有进行新加操作
     * @details
     * @param array $where_arr 条件数组
        $where_arr = array(
            'user_id' => $aa,        
               );
     * @param array $modify_arr 更新数组
        $modify_arr = array(
            'org_id' => $aaa,
            'site_id' => $aaa,
            'user_id' => $aa,              
        );
     * @return int 是true 不是 false
     *
     */
    public function set_manager($where_arr = array(),$modify_arr = array()){
        log_message('debug', 'into method ' . __FUNCTION__ . '.');
        $select_field = 'id';
        $insert_arr = $modify_arr;
        $insert_arr['create_time'] = dgmdate(time(), 'dt');
        $re_num = $CI->uc_ecology_partake_model->updata_or_insert(1,$select_field,$where_arr,$modify_arr,$insert_arr);
        if($re_num == -2 || $re_num == -4){//失败 -1 更新记录成功 -2更新操作失败 -3新加的记录id 新加记录成功 -4新加操作失败 -5 没有操作
             $err_msg = 'update/insert  uc_ecology_partake_model fail. $re_num =' . $re_num . ' ';
             log_message('error', $err_msg); 
             log_message('error', ' updata_or_insert uc_ecology_partake_model $select_field=' . json_encode($select_field) . '$where_arr=' . json_encode($where_arr) . '$modify_arr=' . json_encode($modify_arr) . '$insert_arr=' . json_encode($insert_arr) . 'fail'); 
             return false;              
        }else{
            log_message('debug', ' updata_or_insert uc_ecology_partake_model $select_field=' . json_encode($select_field) . '$where_arr=' . json_encode($where_arr) . '$modify_arr=' . json_encode($modify_arr) . '$insert_arr=' . json_encode($insert_arr) . 'success'); 
            return true;
        } 
        log_message('debug', 'out method ' . __FUNCTION__ . '.');

    } 
}