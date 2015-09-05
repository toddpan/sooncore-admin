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
	 * @brief根据当前当前站点ID查询数据;
	 * @param $site_id	当前站点ID
	 * @return array	查询的数据结果集
	 */
	public function getInfosBySiteId($site_id){
		$query		= $this->db->get_where(self::TBL, array('siteID'=>$site_id));
		
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
	
		if($this->db->affected_rows() > 0 ){
			return true;
		}
	
		return false;
	}
}