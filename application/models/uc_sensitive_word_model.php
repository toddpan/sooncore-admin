<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @category UC_Sensitive_Word_Model
 * @abstract UC_Sensitive_Word Model，主要负责对敏感词的增删改查操作
 * @filesource UC_Sensitive_Word_Model.php
 * @author yanzou <yan.zou@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */

class UC_Sensitive_Word_Model extends MY_Model{
	/**
	 * @access public
	 * @abstract 构造函数
	 */
	public function __construct(){
		//调用父类构造函数，必不可少
		parent::__construct();
		// 定义表名称
		$this->set_table('uc_sensitive_word');
	}

	/**
	 * @access public
	 * @abstract 获得当前站点当前企业的能使用的敏感词：
	 * @details
	 * @param int $pageno  当前页号
	 * @param int $limit  当前显示数量
	 * @param string $keyword  搜索关键字
	 * @param int $site_id  当前站点ID
	 * @param int $org_id  当前组织ID
	 * @return array 能使用的敏感词
	 *
	 */
	public function getSensitiveWord($site_id,$org_id){
		$db_arr = array(
			'where' => '(type = 1 or (type = 2 and site_id = ' . $site_id . ' and Org_id = ' . $org_id . ' ))',//array('site_id' => $site_id, 'Org_id' => $org_id),
			'order_by' => 'type asc,id desc'
			);

			return $this->operateDB(2, $db_arr);
	}

	/**
	 *
	 * @abstract 新加前站点当前企业的敏感词：
	 * @details
	 * @param int $site_id  当前企业站点id
	 * @param int $org_id  当前企业id
	 * @param string $data  当前企业的敏感词
	 * @return boolean true/id 成功 false失败
	 *
	 */
	public function addSensitiveWord($site_id, $org_id, $word){
		//$select = array('site_id', 'Org_id', 'Word');
		$where_arr = array('site_id' => $site_id, 'Org_id' => $org_id, 'Word' => $word);
		$modify_arr = array(
                    'type' => 2, 'site_id' => $site_id, 'Org_id' => $org_id, 'Word' => $word, 'time' =>date("Y-m-d H:i:s",time())
		);
		$insert_arr = $modify_arr;
		$re_num = $this->updata_or_insert(3, 'type, site_id, Org_id, Word, time', $where_arr, $modify_arr, $insert_arr);
		//echo $re_num;
		if($re_num > 0 ){//如果大于0，则返回新加记录id
			return $re_num;
		}
		switch ($re_num) {//-1 更新记录成功 -2更新操作失败 -3新加的记录id 新加记录成功 -4新加操作失败 -5 没有操作
			case -2:
			case -4:
				return false;
				break;
			default:
				return true;
				break;
		}
	}

	/**
	 * @access public
	 * @abstract 查询单条记录
	 * @param int $id  当前敏感词记录的id
	 */
	public function query_current_sensitive_word($id) {
		$db_arr = array(
			'where' => array('id' => $id)
		);
		return $this->operateDB(1, $db_arr);
	}

	/**
	 *
	 * @brief 敏感词是否存在： 注意需要同时查找当前企业自定义的及系统内置的。
	 * @details
	 * @param int $site_id  当前站点ID
	 * @param int $org_id  当前组织ID
	 * @param array $word  敏感词
	 * @return   是否存在 true 存在 false 不存在
	 *
	 */
	public function sensitiveWordIsExist($site_id,$org_id,$word){
		//使用AR类完成
		return $this->db->get(self::TBL);
	}

	/**
	 *
	 * @brief 删除指的的敏感词。注意企业只能删除自己的，不能删除系统内置的
	 * @details
	 * @param int $SensitiveId  敏感词ID
	 * @return   array  array('is_success'=> 0失败1成功,'affect_num'=>‘影响的行数’)
	 *
	 */
	public function delSensitiveWord($SensitiveId){
		$db_arr = array(
			'where' => array('id' => $SensitiveId)
		);
		return $this->operateDB(4, $db_arr);
	}
	
	/**
	 * @access public 
	 * @abstract 根据敏感词进行查询
	 * @param $word string 敏感词
	 */
	public function searchSensitiveWord($word, $site_id, $org_id) {
		$db_arr = array(
			'where' => array('Word' => $word, 'site_id' => $site_id, 'Org_id' => $org_id)
		);
		return $this->operateDB(2, $db_arr);
	}
}

