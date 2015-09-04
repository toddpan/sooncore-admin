<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UC_Exempt_User_Model extends MY_Model{

    // const TBL = 'Exempt_User';
    //构造函数
    public function __construct(){
        //调用父类构造函数，必不可少
        parent::__construct(); 
        $this->set_table('uc_exempt_user');
    }

    /**
     *
     * @brief 获得豁免员工： 
     * @details
     * @param int $site_id  当前站点ID
     * @param int $org_id  当前组织ID
     * @return array 豁免员工
     *
     */
      public function getExemptUser($site_id,$org_id){
        $query = $this->db->get(self::TBL_GT);
        return $query->result_array();
      }
    /**
     *
     * @brief 豁免员工是否存在：
     * @details
     * @param int $site_id  当前站点ID
     * @param int $org_id  当前组织ID
     * @param int $userid  员工ID
     * @return   是否存在 true 存在 false 不存在
     *
     */
    public function exemptUserIsExist($site_id,$org_id,$userid){
        //使用AR类完成
        return $this->db->get(self::TBL);
    }
    /**
     *
     * @brief 新加豁免员工： 
     * @details
     * @param array $data  当前豁免员工
     * @return array 获得的数据
     *
     */
    public function addExemptUser($data){
        //使用AR类完成插入操作
        return $this->db->insert(self::TBL,$data);
    }
    
    /**
     *
     * @brief 删除指的的豁免员工。
     * @details
     * @param int $site_id  当前站点ID
     * @param int $org_id  当前组织ID
     * @param int $ExempId  豁免员工豁免标识 ExempId
     * @return   是否成功 true 成功 false 失败
     *
     */
    public function delExemptUser($site_id,$org_id,$ExempId){
        //使用AR类完成
        return $this->db->delete(self::TBL);
    }
}