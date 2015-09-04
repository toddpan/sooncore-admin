<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @abstract 	ExemptUser Controller，豁免员工控制，主要是员工豁免管理[列表、添加豁免、取消豁免]
 * @filesource 	exemptuser.php
 * @author 		Bai Xue <xue.bai_2@quanshi.com>
 * @copyright 	Copyright (c) UC
 * @version 	v1.0
 */
class ExemptUser extends Admin_Controller{
	/**
	 * @abstract 构造函数
	 */
	public function __construct() {
		parent::__construct();
		// 加载公共辅助函数
		$this->load->helper('my_publicfun');
		// 载入豁免员工模型
		$this->load->model('UC_Exempt_User_Model');
		// 加载UMS员工信息类库
		$this->load->library('StaffLib', '', 'StaffLib');
	}

	/**
	 * @abstract 豁免员工列表页面
	 * 注意在获得列表的同时，在页面用js保存列表的员工标识，
	 * 用以在页面维护一套豁免员工标识，在添加及删除时可以不用到从数据库获取
	 * @details
	 * -# 从UC获得当前站点、当前企业能豁免员工列表
	 * -# 根据获取到的员工标识，获得员工详情
	 * -# 根据获得的员工信息，获得需要获取的所在部门
	 * -# 将获得的员工分配到视图
	 */
	public function exemptUserPage() {
		// 当前站点id
		$site_id = $this->p_site_id;
		// 当前组织id
		$org_id = $this->p_org_id;
		
		// 查询条件数组
		$db_arr = array(
			'where_ arr' => array(
					'site_id' => $site_id,
					'Org_id' => $org_id
		)
		);
		// 调用模型方法进行查询，返回二维数组
		$data['exeptusers'] = $this->UC_Exempt_User_Model->operateDB(2, $db_arr);
		
		// 加载视图页面
		$this->load->view('public/part/exemptuserlist.php', $data);
	}

	/**
	 * @abstract 保存新加豁免员工
	 * @details
	 * -# 从表单获取豁免员工
	 * -# for循环保存没有的豁免员工到库
	 */
	public function saveExemptUser() {
		//获取表单提交的豁免员工json串
		$user_id_json = $this->input->post('user_id', true);
		log_message('info', __FUNCTION__." input->\n".var_export(array('user_id' => $user_id_json), true));
		
		$user_id_json = empty_to_value($user_id_json,'');

		// 验证表单数据是否为空
		if(bn_is_empty($user_id_json)){
			form_json_msg('1','', '参数有误');
		}

		if(is_not_json($user_id_json)){
			form_json_msg('1','', 'user_id ' . $user_id_json . ' is not json');
		}

		$user_arr = json_decode($user_id_json,true);
		
		$site_id = $this->p_site_id;
		$org_id = $this->p_org_id;

		foreach($user_arr as $user_data){
			$user_id = $user_data['userid'];

			$where_arr = array('site_id' => $site_id, 'Org_id' => $org_id, 'user_id' => $user_id);
			$modify_arr = array(
                    'site_id' => $site_id, 'Org_id' => $org_id, 'user_id' => $user_id, 'time' => time()
			);
			$insert_arr = $modify_arr;
			$re_num = $this->UC_Exempt_User_Model->updata_or_insert(3, 'site_id, Org_id, user_id, time', $where_arr, $modify_arr, $insert_arr);
			if($re_num == -2 || $re_num == -4){
				form_json_msg('1','', '操作失败');
			}
		}
		form_json_msg('0','', '操作成功');
	}
	
	/**
	 * @abstract 显示提醒是否取消当前员工的豁免权页面
	 * @param int  $user_id  豁免员工id
	 */
	public function remindDelExemptUser($user_id) {
		$data['user_id'] = $user_id;
		$data['user_arr'] = $this->StaffLib->get_user_by_id($user_id);
		$this->load->view('public/popup/delexuser.php', $data);
	}

	/**
	 *
	 * @abstract 删除豁免员工[ajax加载]：
	 * @details
	 * -# 获得JS post 过来的 豁免员工豁免标识 ExempId
	 *    ExempId进行效验
	 * -# 执行删除操作
	 * -# 后置操作：此ajax执行完后，js移除对应的列表记录
	 * @return null
	 *
	 */
	public function delExemptUser(){
		$user_id = $this->input->post('user_id', true);
		$db_arr = array(
			'where' => array('user_id' => $user_id),
		);
		
		$re_arr = $this->UC_Exempt_User_Model->operateDB(4, $db_arr);
		
		if(!db_operate_fail($re_arr)){
			form_json_msg('0','', '删除成功');
		}else{
			form_json_msg('1','', '删除失败');
		}
	}
}