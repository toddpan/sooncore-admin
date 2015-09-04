<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UC_Site_Model extends MY_Model{

    const TBL = 'uc_site';
    //构造函数
    public function __construct(){
        //调用父类构造函数，必不可少
        parent::__construct(); 
        $this->set_table('uc_site');
    }
	
	/**
	 * 更新站点logo
	 * @param int $site_id
	 * @param string $url
	 * @return boolean
	 */
	public function saveLogoUrl($site_id, $url){
		$affect_rows = $this->db->update(self::TBL, array('logoUrl'=>$url), array('siteID'=>$site_id));
		return $affect_rows>0 ? true : false; 
	}
	
	/**
	 * 获取logo
	 * @param int $site_id 站点 id
	 */
	public function getLogoUrl($site_id){
		return $this->db->select('logoUrl')->get_where(self::TBL, array('siteID'=>$site_id))->first_row()->logoUrl;
	}
	
	/**
	 * @brief根据当前当前站点ID查询所有数据;
	 * @param $site_id	当前站点ID
	 * @return array	查询的数据结果集
	 */
	public function getInfosBySiteId($site_id){
		$query = $this->db->get_where(self::TBL, array('siteID'=>$site_id));
		
		if($query->num_rows > 0){
			return $query->row_array();
		}
		return array();
	}
	
	/**
	 * 根据当前site_id查询某个字段.注意与getInfosBySiteId的区别.
	 * @author ge.xie
	 * 
	 * @param string site_id<p>
	 * 站点ID</p>
	 * @param string field<p>
	 * 查询的字段
	 * </p>
	 * @return array 查询的数据结果集
	 */
	public function getInfoBySiteId($site_id, $field) {
		$query = $this->db->select($field)->get_where(self::TBL, array('siteID'=>$site_id));
		
		if($query->num_rows > 0){
			return $query->row_array();
		}
		return array();
	}
	
	/**
	 * 创建站点
	 * @param array $uc_site_info 站点信息
	 */
	public function createSite($uc_site_info){
		$this->db->insert(self::TBL, $uc_site_info);
		return $this->db->affected_rows();
	}
	
	/**
	 * 根据条件更新站点
	 * @param array $where_arr  条件数组
	 * @param array $update_arr 需要更新的数据
	 */
	public function update_value($where_arr, $update_arr){
		$this->db->update(self::TBL, $update_arr, $where_arr);
		//echo $this->db->affected_rows();
	
		//if($this->db->affected_rows() > 0 ){
			return true;
		//}
	
		//return false;
	}
	
	/**
	 * 根据站点id获得公司简称
	 * @param int $site_id
	 * @return array
	 */
	public function getCorNameBySiteId($site_id){
		$query		= $this->db->select('corName')->get_where(self::TBL, array('siteID'=>$site_id));
	
		if($query->num_rows > 0){
			return $query->row_array();
		}
		return array();
	}
	
	/**
	 * 根据条件查询站点信息
	 * 
	 * @param  array  $where_arr 条件数组
	 * 
	 * @return  array
	 */
	public function get_site_info_by_cond($where_arr) {
		$query = $this->db->get_where(self::TBL, $where_arr);
		
		if($query->num_rows() > 0 ){
			return $query->row_array();
		}
		
		return array();
	}
	
// 	/**
// 	 * 按条件修改uc_site和uc_user表中的isLDAP值
// 	 * @param 	int 	$site_id
// 	 * @param 	int 	$isldap
// 	 * @return 	boolean
// 	 */
// 	public function change_ldap($site_id, $isldap) {
// 		try {
// 			$this->db->trans_begin();
			
// 			// 更新uc_site表
// 			$res_site = $this->db->update(self::TBL, array('isLDAP' => $isldap), array('siteID' => $site_id));
// 			if(!$res_site){
// 				throw new Exception('Update data from uc_site failed which siteID='.$site_id);
// 			}
			
// 			// 更新uc_user表中除了系统管理员之外的记录
// 			//$res_user = $this->db->update('uc_user', array('isLDAP' => $isldap), array('siteID' => $site_id));
			
// 			$this->db->trans_commit();
// 			return true;
// 		}catch(Exception $e){
// 			$this->db->trans_rollback();
// 			log_message('error',$e->getMessage());
// 			return false;
// 		}
// 	}
}