<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class UC_User_Tags_Model extends MY_Model{

    const TBL = 'uc_user_tags';
    //构造函数
    public function __construct(){
        //调用父类构造函数，必不可少
        parent::__construct(); 
        $this->set_table('uc_user_tags');
    }
    /**
     *
     * @brief 根据站点ID,获得当前站点标签信息：
     * @details
     * @param int $site_id  当前站点ID
     * @param int $enable  标签是否可用0不可用1可用，为空则都获取
     * @return array 获得当前站点标签信息
     *
     */
    public function get_tags_by_siteid($site_id = 0,$enable =''){
        //从数据库获得系统可选标签及自定义员工标签信息
        $where_arr = array('site_id =' => $site_id);
        if(!bn_is_empty($enable)){//有数据
            $where_arr['enable'] = $enable;
        }
        $data_tags = array(
            'select' =>'id,site_id,tag_name,tag_scope,tag_type,enable',
            'where' => $where_arr,
        );
        $tag_arr =  $this->UC_User_Tags_Model->operateDB(2,$data_tags);
        if( is_array($tag_arr) ){
            log_message('info', 'get User Tags  success.');
        }else{
            log_message('debug', 'get User Tags  fail');
        }
        return $tag_arr;
    }
    /**
     *
     * @brief 根据站点ID,获得当前站点标签信息：
     * @details
     * @param int $site_id  当前站点ID
     * @return array 获得当前站点标签信息
     *
     */
    public function getTagArrBySiteId($site_id = 0){
        $where = array('site_id' => $site_id);
        $query = $this->db->get_where(self::TBL,$where );
        return $query->result_array();
    }
    /**
     *
     * @brief 根据条件,获得当前站点标签信息：
     * @details
     * @param array $wherearr  条件数组 array('site_id' => $site_id);
     * @return array 获得当前站点标签信息
     *
     */
    public function getTagArr($wherearr){
        //$where = array('site_id' => $site_id);
        $query = $this->db->get_where(self::TBL,$wherearr );
        return $query->result_array();
    }

    /**
     *
     * @brief 根据条件,获得当前站点标签信息数量：
     * @details
     * @param array $wherearr  条件数组 array('site_id' => $site_id);
     * @return int 获得当前站点标签信息数量
     *
     */
    public function getTagCount($wherearr){
        $this->db->select(' id ');                
        if(!empty($wherearr)){
             //$array = array('name !=' => $name, 'id <' => $id, 'date >' => $date);
             $this->db->where($wherearr); 
        }
        $this->db->from(self::TBL);
        $query = $this->db->count_all_results();
        return $query;
    }
    /**
     *
     * @brief 根据站点id,删除不在指定标签id内的标签：
     * @details
     * @param int $site_id 站点id
     * @param array $idarr  不在指定标签id数组 ，如果为空，则删除当前站点所有的标签
     * @return int 0失败1 成功
     *
     */
    public function delTagNotInId($site_id,$idarr){  
        $this->db->where(array('site_id =' => $site_id));
         if(!empty($idarr)){
          $this->db->where_not_in('id',$idarr);
        }
        $query = $this->db->delete(self::TBL); 
        return $query;
    }

    /**
     *
     * @brief 组织管理中设置员工标签
     * @details
     * @param 可选的员工标签插入数据库
     * @return  自定义的员工标签插入数据库
     *
     */
    /*
    public function InsertData($notMustData){

        //使用AR类完成插入操作           
       return  $this->db->insert('uc_user_tags',$notMustData);          

       }
     * */

    public function InsertData($data){

        //使用AR类完成插入操作
         $query =  $this->db->insert(self::TBL,$data);
         return   $query;        

       }

    public function UpdateData($Data,$wherearr = array()){

        //使用AR类完成更新操作

        if(!empty($wherearr)){
             //$array = array('name !=' => $name, 'id <' => $id, 'date >' => $date);
             $this->db->where($wherearr); 
        }
        $query = $this->db->update(self::TBL,$Data);  
        return  $query;        

       }
	
	/**
	 * 获取某个站点的可选标签
	 * @param int $site_id 站点id
	 * @return array
	 */
	public function getOptionalTags($site_id){
		$ret_tags = array();
		$query = $this->db->select('tag_name')->get_where(self::TBL, array('site_id'=>$site_id, 'tag_type'=>1, 'enable'=>1));
		if($query->num_rows()>0){
			foreach($query->result_array() as $row){
				$ret_tags[] = $row['tag_name'];
			}
		}
		return $ret_tags;
	}

}