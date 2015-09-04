<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class UC_Role_Dic_Model extends MY_Model{

    const TBL = 'uc_role_dic';
    //构造函数
    public function __construct(){
        //调用父类构造函数，必不可少
        parent::__construct(); 
        $this->set_table('uc_role_dic');
    }
    /**
     * 
     * 获取权限字典
     * 
     * @param int $role_id 角色id
     * @return array
     * array('role_id'=>role_name,...) 
     */
    public function getRoleDic($role_id=0){
        $ret = array();
        if(!is_int($role_id) || $role_id<0){
            return $ret;
        }
        $where_arr = array();
        if($role_id !== 0){
            $where_arr = array('id'=>$role_id);
        }
        $query = $this->db->select('id, role')->get_where($this->table_name,$where_arr);
        if($query->num_rows()>0){
            foreach($query->result() as $row){
                $ret[$row->id] = $row->role;
            }
        }
        return $ret;
    }
}