<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 应用管理
 * 
 * @author xue.bai_2  2015-06-30
 */

class UC_Application_Model extends CI_Model{
	
	private $tbl;
	
	public function __construct() {
		$this->tbl = array(
			'app' => 'uc_application'
		);
	}
	
	/**
	 * 获得应用列表
	 */
	public function get_app_lists($site_id){
		$this->db->select('id,app_title,author,oriented_obj,status,update_time');
		$this->db->where(array('site_id' => $site_id));
		$this->db->from($this->tbl['app']);
		
		$query = $this->db->get();
		
		$re_data = array();
		if($query->num_rows() > 0){
			foreach($query->result() as $row){
					$tmp = array();
					$tmp['id'] = $row->id;
					$tmp['app_title'] = $row->app_title;
					$tmp['author'] = $row->author;
					$tmp['oriented_obj'] = ($row->oriented_obj == 1) ? '全体员工' : '部分员工';
					$tmp['status'] = ($row->status == 1) ? '使用中' : '停用';
					$tmp['update_time'] = date('Y/m/d H:i', $row->update_time);
					$re_data[] = $tmp;
				}
		}
		
		log_message('debug', 'out method get_app_lists with $re_data='. var_export($re_data, true));
		return $re_data;
	}
	
	/**
	 * 获得应用详情
	 */
	public function get_app_info($where_arr){
		$this->db->where($where_arr);
		$this->db->from($this->tbl['app']);
		
		$query = $this->db->get();
		
		$re_data = array();
		if($query->num_rows() > 0){
			$row = $query->row();
			$re_data['id'] = $row->id;
			$re_data['app_title'] = $row->app_title;
			$re_data['app_desc'] = $row->app_desc;
			$re_data['author'] = $row->author;
			$re_data['app_acount'] = $row->app_acount;
			$re_data['url'] = $row->url;
			$re_data['use_agent'] = $row->use_agent;
			//$re_data['relative'] = $row->relative;
			$re_data['oriented_obj'] = $row->oriented_obj;
			$re_data['status'] = $row->status;
			$re_data['app_logo'] = empty($row->app_logo) ? base_url('public/images/bisinessLogo.jpg') : $row->app_logo;
		}
		
		log_message('debug', 'out method get_app_info with $re_data='. var_export($re_data, true));
		return $re_data;
	}
	
	/**
	 * 新建或者修改应用详情
	 */
	public function save_app_info($app_info){
		try {
			 $this->db->trans_begin();
			 
			 $app_id = (isset($app_info['app_id']) && !empty($app_info['app_id'])) ? $app_info['app_id'] : 0;
			 
			 // 方案1
			 $app_info['site_id'] = $this->p_site_id;
			 $app_info['update_time'] = time();
			 $app_info['url'] = json_encode($app_info['url'],true);
			 unset($app_info['app_id']);
			 if($app_id){
			 	$this->db->update($this->tbl['app'], $app_info, array('id' => $app_id));
			 	if($this->db->affected_rows() < 1){
 					throw new Exception('update app failed.');
 				}
			 }else{
			 	$this->db->insert($this->tbl['app'], $app_info);
			 	if($this->db->affected_rows() < 1){
			 		throw new Exception('insert app failed.');
 				}
			 }

// 方案2 
// 			 // 如果是修改应用，则先删除之前的记录
// 			 if($app_id){
// 			 	$this->db->delete($this->tbl['app'], array('id' => $app_id));
			 	
// 			 	if($this->db->affected_rows() < 1){
// 			 		throw new Exception('delete old app failed.');
// 			 	}
// 			 }
			 
// 			 unset($app_info['app_id']);
// 			 $app_info['site_id'] = $this->p_site_id;
// 			 $app_info['update_time'] = time();
// 			 $app_info['url'] = json_encode($app_info['url'],true);
			 
// 			 // 根据站点id和应用标题查询是否有相同的记录，有则更新，没有则创建
// 			 $app_arr = $this->get_app_info(array('site_id' => $this->p_site_id, 'app_title' => $app_info['app_title']));
// 			 if(empty($app_arr)){
// 			 	$this->db->insert($this->tbl['app'], $app_info);
			 	
// 			 	if($this->db->affected_rows() < 1){
// 			 		throw new Exception('insert app failed.');
// 			 	}
// 			 }else{
// 			 	$this->db->update($this->tbl['app'], $app_info, array('id' => $app_arr['id']));
			 	
// 			 	if($this->db->affected_rows() < 1){
// 			 		throw new Exception('update app failed.');
// 			 	}
// 			 }
			 
			 $this->db->trans_commit();
			 return true;
		}catch(Exception $e){
			$this->db->trans_rollback();
			log_message('error',$e->getMessage());
			return false;
		}
	}

	/**
     * 获取应用列表
	*/
	public function getApplicationsBySiteId($site_id){
		$q = $this->db->get_where($this->tbl['app'], array('site_id'=>$site_id));
		return $q->num_rows()>0 ? $q->result_array() : false;
	}
}