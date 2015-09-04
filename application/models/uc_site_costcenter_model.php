<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UC_Site_Costcenter_Model extends MY_Model{

    const TBL = 'uc_site_costcenter';
    //构造函数
    public function __construct(){
        //调用父类构造函数，必不可少
        parent::__construct(); 
        $this->set_table('uc_site_costcenter');
		$this->tbl = array(
			'group'=>'uc_site_costcenter',
			'group_user'=>'uc_costcenter_user',
			'user'=>'uc_user',
		);
    }
      /**
     *
     * @brief 获得成本中心列表： 
     * @details 
     * @param array $where_arr  传入数组
            $where_arr = array(
                'org_id' => 1,
                'site_id' => 1
            );
     * 
     * @return array 获得信息
     *
     */
      public function get_costcenter_list($where_arr = array()){  
            $data = array(  
               //'select' =>'*id,value,type*',
               'where' => $where_arr,//'isvalid = 1',
           );
           $costcenter_arr =  $this->operateDB(2,$data);
           if (! is_array($costcenter_arr) ){//不是数组
               $costcenter_arr = array();
           }
          return $costcenter_arr;
      }  
      /**
     *
     * @brief 存在则更新操作，不存在则新加： 
     * @details 
     * @param array $in_where_arr  传入数组
            $where_arr = array(
                'cost_pid' => //父成本中心id
                'org_id' => 1,
                'site_id' => 1,
                'cost_id' => $cost_id,//成本中心id                
            );
     * @param string $cost_title  成本中心名称
     * @param int $cost_id  成本中心id
     * @return boolean 成功 1或新加记录id 失败0 
     *
     */
      public function save_costcenter_name($in_where_arr = array(),$cost_title = '',$cost_id){  
            $where_arr = $in_where_arr;
            $where_arr['id'] = $cost_id;
               /*    
               array(
                'org_id' => $org_id,
                'site_id' => $site_id,
                //'user_id' => $user_id,
                   );
                * 
                */
            $modify_arr = $in_where_arr;
            $modify_arr['cost_title'] = $cost_title;
               /*     
                array(
                'org_id' => $org_id,
                'site_id' => $site_id,
                'user_id' => $user_id,
                   ); 
                * 
                */
            $insert_arr = $modify_arr;
            $insert_arr['time'] = dgmdate(time(), 'dt');
            $re_num = $this-> updata_or_insert(1,'id',$where_arr,$modify_arr,$insert_arr);
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
      }
    /**
     *
     * @brief 删除成本中心： 
     * @details 
     * @param array $where_arr  传入数组
            $where_arr = array(
                'org_id' => 1,
                'site_id' => 1
                'cost_id' => 
            );
     * @return boolean  成功TRUE 失败 FALSE
     *
     */
      public function del_cost_center($where_arr = array()){
           $del_arr = array(
               'where' => $where_arr,
               
           );
           $re_del_arr = $this -> operateDB(4,$del_arr); 
           if(db_operate_fail($re_del_arr)){//失败
               return false;
           }else{
               return true;
           }
      }
      
    /**
     *
     * @brief 根据当前站点ID、组织id获得成本中心： 
     * @details 
     * -# 根据当前站点ID，通过接口获得成本中心。
     * @param int $site_id  当前站点ID
     * @param int $org_id  当前组织ID
     * @param int $Cost_pid  当前成本中心父ID
     * @return array 获得信息
     *
     */
      public function getCostcenteraa($site_id,$org_id,$Cost_pid){
          $datearr=array();
          //通过接口获得
          return $datearr;
      }
    /**
     *
     * @brief 新加成本中心： 
     * @details 
     * @param arr $data  新加成本中心信息
     * @return array 状态
     *
     */
      public function addCostcenter($data){
          $datearr=array();
          //通过接口获得
          return $datearr;
      }
    /**
     *
     * @brief 修改成本中心： 
     * @details 
     * @param arr $data  修改成本中心信息
     * @return array 状态
     *
     */
      public function modifyCostcenter($data){
          $datearr=array();
          //通过接口获得
          return $datearr;
      }
      
    /**
     * 检查成本中心分组
     * 
     * @author hongliang.cao@quanshi.com
     * @param int $site_id 站点id
     * @param int $org_id  组织id
     * @return mix
     * 
     */
    public function checkCostCenter($site_id, $org_id, $cc_ids = array()){
        //获取数据
        $ret_data = array();
        $query = $this->db->select('id,cost_title')->get_where('uc_site_costcenter', array('site_id'=>$site_id, 'org_id'=>$org_id));
        if($query->num_rows() > 0){
            $ret_data = $query->result_array();
        }
        if(is_array($cc_ids) && count($cc_ids)>0){//检查成本中心id
            $cost_ids = array(); 
            foreach($ret_data as $cost){
                $cost_ids[] = $cost['id'];
            }
            if(count(array_diff($cc_ids, $cost_ids))>0){
                return false;
            }else{
                return true;
            }
        }else{
            return false;
        }
    }
	
	//======================================重写==============================
	/**
	 * 获取下级分组
	 * @param type $group_id 分组id
	 */
	public function getNextLevelGroup($org_id, $site_id, $group_id){
		$where   = array('org_id'=>$org_id,'site_id'=>$site_id,'cost_pid'=>$group_id);
		$r       = $this->db->select('id,cost_pid pid,cost_title title')->from($this->tbl['group'])->where($where)->get();
		return $r->num_rows()>0 ? $r->result_array() : array();
	}
	
	/**
	 * 判断当前分组是否有子组
	 * @param type $group_id 
	 */
	public function hasNextLevel($org_id, $site_id, $group_id){
		return $this->db->where(array('org_id'=>$org_id, 'site_id'=>$site_id, 'cost_pid'=>$group_id))->count_all_results($this->tbl['group'])>0 ? true : false;
	}
	
	/**
	 * 获取分组信息
	 * @param type $group_id
	 */
	public function getGroupById($group_id){
		$re = $this->db->select('id,cost_pid pid,cost_title title')->from($this->tbl['group'])
			->where(array('id'=>$group_id))->get();
		return $re->num_rows()>0 ? $re->result_array() : false;
	}
	
	public function getGroupByTitle($org_id, $site_id, $title){
		$re = $this->db->select('id,cost_pid pid,cost_title title')->from($this->tbl['group'])
			->where(array('cost_title'=>$title, 'org_id'=>$org_id, 'site_id'=>$site_id))->get();
		return $re->num_rows()>0 ? $re->result_array() : false;
	}
	
	public function getGroupIdsByUserId($user_id){
		$ret = $this->db->get_where($this->tbl['group_user'], array('user_id'=>$user_id));
		$group_ids = array();
		if($ret->num_rows()>0){
			foreach($ret->result() as $row){
				$group_ids[] = $row->cost_id;
			}
		}
		return $group_ids;
	}
	
	public function getUserIdsByGroupId($group_id){
		$user_ids = array();
		$rs = $this->db->select('user_id')->get_where($this->tbl['group_user'], array('cost_id'=>$group_id));
		if($rs->num_rows()>0){
			foreach($rs->result() as $row){
				$user_ids[] = $row->user_id;
			}
		}
		return $user_ids;
	}
	
	public function changeGroupName($group_id, $title){
		$affect_rows = $this->db->update($this->tbl['group'], array('cost_title'=>$title), array('id'=>$group_id));
		return $affect_rows>0 ? true : false;
	}
	
	/**
	 * 获取分组下的成员id
	 * @param int $group_id
	 */
	/*
	public function getGroupMemberIds($group_id, $limit=15, $offset=0){
		$rs = $this->db->select('user_id')->get_where($this->tbl['group_user'], array('cost_id'=>$group_id));
		$ids = array();
		if(($num_rows = $rs->num_rows())>0){
			foreach($rs->result() as $row){
				$ids[] = $row->user_id;
			}
		}
		return empty($ids) ? false : $ids;
	}
	*/
	/**
	 * 删除分组
	 * @param int $group_id 分组id
	 * @return int 影响行数
	 */
	public function delGroup($group_id){
		return $this->db->delete($this->tbl['group'],array('id'=>$group_id));
	}
	
	/**
	 * 添加分组
	 * @param int    $org_id      组织id   
	 * @param int    $site_id     站点id
	 * @param int    $id      分组id 
	 * @param string $group_name  分组名称
	 * @param int $p_group_id     父组id
	 */
	public function addGroup($org_id, $site_id, $id, $group_name, $p_group_id){
		$insert_data = array(
			'id'=>$id,
			'org_id'=>$org_id,
			'site_id'=>$site_id,
			'cost_pid'=>$p_group_id,
			'cost_title'=>$group_name,
			'time'=>'CURRENT_TIMESTAMP',
		);
		return $this->db->insert($this->tbl['group'], $insert_data);
	}
	
	/**
	 * 获取成本中心分组下的员工
	 * @param int $org_id
	 * @param int $group_id
	 * @param int $limit
	 * @param int $offset
	 */
	public function getGroupMemberIds($group_id, $limit=15, $offset=0,$org_id=0){
		$ids = array();
		$where = $org_id == 0 ? array('a.cost_id'=>$group_id) : array('a.cost_id'=>$group_id, 'b.org_id'=>$org_id);
		$rs = $this->db->select('a.user_id id')->from($this->tbl['group_user'].' as a')
			->join($this->tbl['group'].' as b', 'a.cost_id=b.id', 'left')->where($where)
			->limit($limit, $offset)->get();
		if($rs->num_rows() > 0){
			foreach($rs->result() as $row){
				$ids[] = $row->id;
			}
		}
		
		return $ids;
	}
	
	/**
	 * 判断分组是否重名
	 * -规则：和同级不能重名，和父级不能重名
	 * @param type $group_id
	 * @param type $new_title
	 * @param string $type 0-修改 1-添加
	 * @return boolean
	 */
	public function isUniqueName($group_id,$new_title,$type){
		//获取父id
		$parent_id = $type == 0 ? $this->db->get_where($this->tbl['group'], array('id'=>$group_id))->first_row()->cost_pid : $group_id;
		if($parent_id > 0){
			$where_parent  = array('id'=>$parent_id,'cost_title'=>$new_title);
			$where_siblings = array('cost_pid'=>$parent_id,'cost_title'=>$new_title);
			$count = $this->db->where($where_parent)->or_where($where_siblings)->get($this->tbl['group'])->count_all_results();
			return $count > 0 ? false : true;
		}
// 		return false;
		return true;
	}
	
	/**
	 * 成本中心分组下添加多个用户
	 * @param int   $group_id
	 * @param array $user_ids
	 */
	public function addGroupMembers($group_id, $user_ids){
		$insert_data = array();
		foreach($user_ids as $user_id){
			$tmp = array();
			$tmp['cost_id'] = $group_id;
			$tmp['user_id'] = $user_id;
			$insert_data[] = $tmp;
		}
		
		return $this->db->insert_batch($this->tbl['group_user'], $insert_batch);
	}
	
	/**
	 * 成本中心分组下添加用户
	 * @param int   $group_id
	 * @param int   $user_id
	 */
	public function addGroupMember($group_id, $user_id){		
		return $this->db->insert($this->tbl['group_user'], array('cost_id'=>$group_id, 'user_id'=>$user_id));
	}
	
	
	/**
	 * 成本中心分组下删除多个用户
	 * @param int $group_id
	 * @param array $user_ids
	 */
	public function delGroupMembers($group_id, $user_ids){
		return $this->db->where_in('user_id',$user_ids)->where(array('cost_id'=>$group_id))->delete($this->tbl['group_user']);
	}
	
	/**
	 * 成本中心分组下删除用户
	 * @param int $group_id
	 * @param int $user_id
	 */
	public function delGroupMember($group_id, $user_id){
		return $this->db->where(array('cost_id'=>$group_id, 'user_id'=>$user_id))->delete($this->tbl['group_user']);
	}
	
	/**
	 * 获取全部已分组用户的id
	 * @param int $site_id
	 * @param int $org_id 
	 * @return array
	 */
	public function getAllGroupedUsers($site_id, $org_id){
		$where = array('site_id'=>$site_id, 'org_id'=>$org_id);
		$user_ids = array();
		$rs = $this->db->select('id')->get_where($this->tbl['group'], $where);
		if($rs->num_rows()>0){
			foreach($rs->result() as $row){
				$_user_ids = $this->getUserIdsByGroupId($row->id);
				$user_ids = array_merge($user_ids, $_user_ids);
			}
		}
		return $user_ids;
	}
	
	public function getGroups($site_id, $org_id){
		$where = array('site_id'=>$site_id, 'org_id'=>$org_id);
		$rs = $this->db->select('id, cost_title')->get_where($this->tbl['group'], $where);
		return $rs->num_rows>0 ? $rs->result_array() : array();
	}
}