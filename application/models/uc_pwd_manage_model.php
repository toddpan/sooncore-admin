<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @abstract UC_PWD_Manage Model，密码管理模型，主要用于用户进行密码管理变更设置。
 * @filesource uc_pwd_manage_model.php
 * @author Bai Xue <xue.bai_2@quanshi.com>
 * @copyright Copyright (c) UC
 * @version v1.0
 */
class UC_PWD_Manage_Model extends MY_Model{
	
	const TBL = 'uc_pwd_manage';
	
	/**
	 * @access public
	 * @abstract 构造方法
	 */
	public function __construct() {
		// 继承父类构造方法
		parent::__construct();
		// 定义数据库表名称
		$this->set_table('uc_pwd_manage');
	}
	
	/**
	 * @access public
	 * @abstract 根据org_id和站点id来select密码管理设置的数据
	 * @param $org_id int 企业id
	 * @param $site_id int 站点id
	 */
	public function get_pwd_manage_arr($org_id, $site_id) {
		// 组装条件
		$condition['org_id'] = $org_id;
		$condition['site_id'] = $site_id;
		 
		// 执行查询
		$query = $this->db->get_where(self::TBL, $condition);
		
		
		if($query->num_rows() > 0){
			
			return $query->row_array();
		}

		// 返回查询结果数组
		return array();
	}
	
	/**
	 * @access public
	 * @abstract 获得当前系统密码信息数组
	 * @param array 如
	 *     array(
	 *          'org_id' => ,//企业id
	 *          'site_id' => ,//站点id
	 * )
	 * @return array 当前系统密码规则
	 * array(
	 *   'password_complexity' => //密码复杂性要求1、8-30位，不限制类型2、8-30位数字与字母组合3、8-30位数字、符号与字母组合
	 *   'pwd_reg' => //密码复杂性正则
	 *   'history_type' =>  //密码历史记忆1、3次2、5次3、10次4、不记忆默认是3次
	 *   'pwd_history_num' => //密码历史记忆次数 0不限
	 *   'expiry_day_type' => //用户密码有效期1、30天2、60天3、90天4、180天5、不需要变更默认90天
	 *   'expiry_day' => //用户密码有效期天数期 0不限
	 *
	 * )
	 */
	public function get_pwd_arr($in_arr = array()) {
		$sel_field = 'expiry_day_type, history_type, complexity_type';
		$where_arr = $in_arr;
		$sel_arr = $this->get_db_arr($where_arr,$sel_field);
		//密码复杂性要求1、8-30位，不限制类型2、8-30位数字与字母组合3、8-30位数字、符号与字母组合
		$password_complexity = arr_unbound_value($sel_arr,'complexity_type',2,DEFAULT_PWD_COMPLEXITY_TYPE);
		$pwd_reg = '';//正则
		include_once APPPATH . 'libraries/public/Pass_word_attr.php';
		$pwd_obj = new Pass_word_attr();
		$re_pwd_arr = $pwd_obj->get_arr_byid(3,$password_complexity);
		$pwd_reg = arr_unbound_value($re_pwd_arr,'regexptxt',2,'/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{8,30}$/');//正则
		$re_arr['complexity_type'] = $password_complexity;
		$re_arr['pwd_reg'] = $pwd_reg;
		//密码历史记忆1、3次2、5次3、10次4、不记忆默认是3次
		$history_type = arr_unbound_value($sel_arr,'history_type',2,DEFAULT_PWD_HISTORY_TYPE);
		$pwd_history_num = 3;//次数
		switch ($history_type){
			case 1: //1、3次
				$pwd_history_num = 3;//次数
				break;
			case 2: //2、5次
				$pwd_history_num = 5;//次数
				break;
			case 3: //3、10次
				$pwd_history_num = 10;//次数
				break; //4、不记忆默认是3次
			case 4:
				$pwd_history_num = 0;//次数
				break;
			default://
				break;
		}
		$re_arr['history_type'] = $history_type;
		$re_arr['pwd_history_num'] = $pwd_history_num;
		//用户密码有效期1、30天2、60天3、90天4、180天5、不需要变更默认90天
		$expiry_day_type = arr_unbound_value($sel_arr,'expiry_day_type',2,DEFAULT_PWD_EXPIRY_DAY);
		$expiry_day = 90;//天数
		switch ($expiry_day_type){
			case 1: //1、30天
				$expiry_day = 30;//天数
				break;
			case 2: //2、60天
				$expiry_day = 60;//天数
				break;
			case 3: //3、90天
				$expiry_day = 90;//天数
				break;
			case 4: //4、180天
				$expiry_day = 180;//天数
				break;
			case 5://5、不需要变更
				$expiry_day = 0;//天数
				break;
			default://
				break;
		}
		$re_arr['expiry_day_type'] = $expiry_day_type;
		$re_arr['expiry_day'] = $expiry_day;
		return $re_arr;
	}

	/**
	 * @access public
	 * @abstract 保存更新后的密码管理设置
	 * @param $data array 需要被修改的数据
	 * @param $condition 执行更改的条件
	 */
	public function modifyPWDManage($data, $condition) {
		 
		// 执行更新
		$this->db->update(self::TBL, $data, $condition);
		// 返回执行结果
		return $this->db->affected_rows();
	}
}