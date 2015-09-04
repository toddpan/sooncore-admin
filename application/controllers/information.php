<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @abstract Information Controller，消息控制器，相关管理员对消息的查看。
 * @details
 * -# 消息是指——总管理员对其他管理员（员工管理员、合作伙伴管理员）发布
 * -# 消息都是不需要处理的，只有退出消息管理，消息才视为已读
 * @filesource information.php
 * @author yanzou <yan.zou@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
class Information extends Admin_Controller{
	/**
	 * @abstract 构造函数
	 */
	public function __construct() {
		parent::__construct();
	
	}

	/**
	 * @abstract 消息列表页面
	 * @details
	 * -# 日期说明今天的用时间表示，昨天+时间，
	 *    再往前是日期+时间，例如：5月23号 12:56  2012年3月12号 11:43
	 * -# $isread 状态
	 * -# $keywork 关键字
	 * -# $pageno 当前页数
	 * -# $percount 每页数量
	 * -# 从UC获得当前站点、当前企业、当前管理员的消息信息
	 * -# 将获得的消息分配到视图
	 */
	public function infoPage() {
		$this->load->view('infomation/infolist.php',$data);
	}

	/**
	 * @abstract 消息设置为已读[Ajax执行];
	 * @details
	 * -# $infoId  当前消息
	 * -# 设置为已读状态
	 */
	public function setInfoRead() {
		$this->UC_information_Model->setInfoRead($site_id,$org_id,$infoId);
	}

	public function infoManPage($id) {
		$data['type'] = $id;
		if(bn_is_empty($data['type'])){
			$data['type'] = 0;
		}
			
		//获得任务
		$this->load->model('employee_change_task_model');
		$where_arr = array(
				'site_id' => $this->p_site_id,
				'recipient_user_id' => $this->p_user_id,
				'status' => 1
		);
		$task_sum =  $this->employee_change_task_model->countTask($where_arr);
		$data['task_sum'] = $task_sum;

		//获得消息
		$this->load->model('uc_notice_model');
		$notice_where_arr = array(
				'site_id ' 		=> $this->p_site_id,
        		'to_user_id ' 	=> $this->p_user_id,
        		'isread' 		=> 0
		);
		$notice_sum =  $this->uc_notice_model->countNotice($notice_where_arr);
		$data['notice_sum'] = $notice_sum;

		//获得通知
		$this->load->model('uc_message_model');
		$message_where_arr = array(
				'site_id ' 		=> $this->p_site_id,
				'to_user_id ' 	=> $this->p_user_id,
				'isread' 		=> 0
		);
		$message_sum =  $this->uc_message_model->countMessage($message_where_arr);
		$data['message_sum'] = $message_sum;

		$this->load->view('information/infoManage.php',$data);
	}

	/**
	 * @abstract 员工调岗
	 */
	public function staffTransfer(){//员工调岗
		$task_id = $this->uri->segment(3);
		if(!preg_match('/^[\d]+$/',$task_id)){
			form_json_msg('1','','参数有误！');//返回错误信息json格式
		}
		$this->load->model('uc_task_model');
		$this->load->model('employee_change_task_model');//1任务
		$where_arr = array(
                'site_id' => $this->p_site_id, 
		//'recipient_user_id' => $this->p_user_id,
                'type' => 2,//1-add 2-transfer  3-delete
                'id' => $task_id,
		//'status' => 1,//1客户端申请20管理员同意40管理员拒绝
		);
		$task_arr = $this->employee_change_task_model->get_task_arr($where_arr);
		$task_info = arr_unbound_value($task_arr,'task_info',2,'[]');
		$task_arr = json_decode($task_info,true);
		$data['task_arr'] = $task_arr;
		$this->load->view('public/popup/staffTransfer.php',$data);
	}

	/**
	 * @abstract 保存删除
	 * @details
	 */
	public function save_delstaff(){//员工删除
		$this->load->model('uc_task_model');
		$this->load->model('employee_change_task_model');//1任务
		$task_id = strtolower($this->input->post('task_id' , TRUE));
		if(!preg_match('/^[\d]+$/',$task_id)){
			form_json_msg('1','','参数有误！');//返回错误信息json格式
		}
		//获得任务信息
		$where_arr = array(
                'site_id' => $this->p_site_id, 
		// 'recipient_user_id' => $this->p_user_id,
                'type' => 3,//1-add 2-transfer  3-delete
                'id' => $task_id,
                'status' => 1,//1客户端申请20管理员同意40管理员拒绝
		);
		$task_arr = $this->employee_change_task_model->get_task_arr($where_arr);

		$status = arr_unbound_value($task_arr,'status',2,0);
		if($status == 1){
			$task_info = arr_unbound_value($task_arr,'task_info',2,'[]');
			$task_arr = json_decode($task_info,true);
			$task_title = '删除员工';
			$operator_id = arr_unbound_value($task_arr,'operator_id',2,'');//操作人id
			$operator_name = arr_unbound_value($task_arr,'operator_name',2,'');//操作人姓名
			$delete_user_id = arr_unbound_value($task_arr,'delete_user_id',2,'');//删除的用户id
			$current_dept_id = arr_unbound_value($task_arr,'current_dept_id',2,'');//当前部门id
			$delete_user_name = arr_unbound_value($task_arr,'delete_user_name',2,'');//删除的用户姓名
			$avatar = arr_unbound_value($task_arr,'avatar',2,'');
			$task_des = $operator_name . '申请将离职员工' .  $delete_user_name . '删除 ';
			$task_keyword = empty_to_value($task_keyword,task_des);
			//删除帐号
			$this->load->library('StaffLib','','StaffLib');
			$sys_arr = $this->p_sys_arr;
			$re_boolean = $this->StaffLib->del_staff($delete_user_id,$sys_arr);
			if($re_boolean){
				//改变任务状态
				$modify_data = array(
                    'update_data' =>array('status' => 20),
                    'where' => array(
                        'site_id' => $this->p_site_id, 
				// 'recipient_user_id' => $this->p_user_id,
                        'type' => 3,//1-add 2-transfer  3-delete
                        'id' => $task_id,
                        'status' => 1,//1客户端申请20管理员同意40管理员拒绝                     
				)
				);
				$re_task_arr = $this->employee_change_task_model->operateDB(5,$modify_data);
				if(!db_operate_fail($re_task_arr)){//成功
					//发送组织消息
					$this->load->library('Informationlib','','Informationlib');
					$msg_arr = array(
                         'user_id' => $delete_user_id,//用户id
					);
					$this->Informationlib->send_ing($sys_arr,array('msg_id' => 12,'msg_arr' => $msg_arr));
				}else{
					form_json_msg('5','', '失败');//返回信息json格式
				}
				//  form_json_msg('0','', '成功');//返回信息json格式
			}else{
				form_json_msg('11','', '失败');//返回信息json格式
			}

		}
		form_json_msg('0','','删除成功！');//返回错误信息json格式
	}

	/**
	 * @abstract 删除员工操作
	 */
	public function delStaff(){//删除员工
		$task_id = $this->uri->segment(3);
		if(!preg_match('/^[\d]+$/',$task_id)){
			form_json_msg('1','','参数有误！');//返回错误信息json格式
		}
		$this->load->model('uc_task_model');
		$this->load->model('employee_change_task_model');//1任务
		$where_arr = array(
                'site_id' => $this->p_site_id, 
		//'recipient_user_id' => $this->p_user_id,
                'type' => 3,//1-add 2-transfer  3-delete
                'id' => $task_id,
		//'status' => 1,//1客户端申请20管理员同意40管理员拒绝
		);
		$task_arr = $this->employee_change_task_model->get_task_arr($where_arr);
		$task_info = arr_unbound_value($task_arr,'task_info',2,'[]');
		$task_arr = json_decode($task_info,true);
		$data['task_arr'] = $task_arr;
		$this->load->view('public/popup/delStaff.php',$data);
	}

	/**
	 * @abstract 保存员工调岗
	 */
	public function save_staffTransfer(){//保存员工调岗
		$this->load->model('employee_change_task_model');//1任务
		$task_id = strtolower($this->input->post('task_id' , TRUE));
		if(!preg_match('/^[\d]+$/',$task_id)){
			form_json_msg('1','','参数有误！');//返回错误信息json格式
		}
		$name = strtolower($this->input->post('name' , TRUE));
		// echo '$name= ' . $name . '<br>';
		//        if(!preg_match('/^[\d]+$/',$task_id)){
		//            form_json_msg('1','','参数有误！');//返回错误信息json格式
		//        }
		$department = strtolower($this->input->post('department' , TRUE));
		// echo '$department= ' . $department . '<br>';
		//        if(!preg_match('/^[\d]+$/',$task_id)){
		//            form_json_msg('1','','参数有误！');//返回错误信息json格式
		//        }
		$position = strtolower($this->input->post('position' , TRUE));
		// echo '$position= ' . $position . '<br>';
		//        if(!preg_match('/^[\d]+$/',$task_id)){
		//            form_json_msg('1','','参数有误！');//返回错误信息json格式
		//        }
		$ismanage = strtolower($this->input->post('ismanage' , TRUE));
		// echo '$ismanage= ' . $ismanage . '<br>';
		//        if(!preg_match('/^[\d]+$/',$task_id)){
		//            form_json_msg('1','','参数有误！');//返回错误信息json格式
		//        }

		//获得任务信息
		$where_arr = array(
                'site_id' => $this->p_site_id, 
		//'recipient_user_id' => $this->p_user_id,
                'type' => 2,//1-add 2-transfer  3-delete
                'id' => $task_id,
                'status' => 1,//1客户端申请20管理员同意40管理员拒绝
		);
		$task_arr = $this->employee_change_task_model->get_task_arr($where_arr);

		$status = arr_unbound_value($task_arr,'status',2,0);
		if($status == 1){
			$task_info = arr_unbound_value($task_arr,'task_info',2,'[]');
			$task_arr = json_decode($task_info,true);
			$task_title = '调岗员工';
			$operator_id = arr_unbound_value($task_arr,'operator_id',2,'');//操作者id
			$operator_name = arr_unbound_value($task_arr,'operator_name',2,'');//操作者姓名
			$move_user_name = arr_unbound_value($task_arr,'move_user_name',2,'');//移除员工姓名
			$move_user_id = arr_unbound_value($task_arr,'move_user_id',2,'');//移除员工id
			$current_dept_id = arr_unbound_value($task_arr,'current_dept_id',2,'');//当前部门id
			$current_dept_name = arr_unbound_value($task_arr,'current_dept_name',2,'');//当前部门名称
			$target_dept_id = arr_unbound_value($task_arr,'target_dept_id',2,'');//目标部门id
			$target_dept_name = arr_unbound_value($task_arr,'target_dept_name',2,'');//目标部门名称
			$position = arr_unbound_value($task_arr,'position',2,'');//职位
			$task_des = $operator_name . '申请' .  $move_user_name . '调到' . $target_dept_name. ' ';
			$task_keyword = empty_to_value($task_keyword,task_des);
			//调岗员工
			$user_arr = array(
			array(
                    'userid' => $move_user_id,//用户id
                    'user_name' => $move_user_name,//用户名称
                    'orgid' => $current_dept_id,//当前的组织id
                    'org_name' => $current_dept_name,//当前的组织名称
			),
			);
			$this->load->library('StaffLib','','StaffLib');
			$other_arr = array(
                'site_id' => $this->p_site_id,//站点id 
                'obj' => array(
                    'sys' => $this->p_sys_arr,
			)
			);
			$move_boolean = $this->StaffLib->neworg_get_user($user_arr,$target_dept_id,$other_arr);
			if(!$move_boolean){//失败
				form_json_msg('2','','调岗员工失败！');//返回错误信息json格式
			}
			$re_user_arr = $this->StaffLib->get_user_by_id($move_user_id);
			$old_user_displayName = arr_unbound_value($re_user_arr,'displayName',2,'');
			$old_user_displayName = arr_unbound_value($re_user_arr,'position',2,'');
			//如果部门名称有变动，则更新部门名称
			$this->load->library('OrganizeLib','','OrganizeLib');
			$re_org_arr = $this->OrganizeLib->get_org_by_id($target_dept_id);
			$db_org_name = arr_unbound_value($re_org_arr,'name',2,'');
			if($db_org_name != $department){//部门名称变更

			}
			//如果员工信息有更新，更新员工信息
			if($old_user_displayName != $name || $position != $old_user_displayName){

				$update_arr = array();
				$update_arr['id'] = $move_user_id;
				if($display_name != $name){
					//汉字转拼音库
					include_once APPPATH . 'libraries/chartopinyin.php';
					$pinyin_obj = new pinyin();
					$update_arr['namepinyin'] = $pinyin_obj -> utf8_to($name,true);//名称首字母拼音
				}
				$update_arr['displayName'] = $name;
				$update_arr['position'] = $position;
					
				$ums_arr = $this->API->UMS_Special_API(json_encode($update_arr),16);
				if(api_operate_fail($ums_arr)){//失败
					$err_msg = 'ums api rs/users/updateUser fail.';
					log_message('error', $err_msg);
					form_json_msg('3','','修改账号信息失败！');//返回错误信息json格式
				}
				if($position != $old_user_displayName){//置位有变动
					$msg_arr = array(
                            'user_id' => $move_user_id,//用户id
                            'new_displayName' => $name,//用户姓名
                            'new_position' => $position,//新职位名称
                            'old_position' => $old_user_displayName,//旧职位名称
                            'dept_name' => $department,//职位所在部门名称
					);
					$this->StaffLib->position_change_msg($msg_arr);
				}

			}
			//是否为员工管理员，设置员工管理员
			if($ismanage == 1){
				$this->load->library('OrganizeLib','','OrganizeLib');
				$in_arr = array(
                    'org_id' => $target_dept_id,//组织id
                    'site_id' => $this->p_site_id,//站点id 
                    'user_id' => $move_user_id,//用户id
                    'isset' => 1,//0取消，1设置修改
				);
				$sys_arr = $this->p_sys_arr;
				$operate_boolean = $this->OrganizeLib->modify_manager($in_arr,$sys_arr);
				if(!$operate_boolean){
					form_json_msg('4','','设置员工管理员失败！');//返回错误信息json格式
				}
			}
			//更状态及操作人员
			$modify_data = array(
                'update_data' =>array(
                    'status' => 20,
                    'solve_user_id' => $this->p_user_id
			),
                'where' => array(
                    'site_id' => $this->p_site_id, 
			//'recipient_user_id' => $this->p_user_id,
                    'type' => 2,//1-add 2-transfer  3-delete
                    'id' => $task_id,
                    'status' => 1,//1客户端申请20管理员同意40管理员拒绝                     
			)
			);
			$user_task_arr = $this->employee_change_task_model->operateDB(5,$modify_data);
			if(!db_operate_fail($user_task_arr)){//成功
				$sys_arr = $this->p_sys_arr;
				//发送组织消息
				$this->load->library('Informationlib','','Informationlib');
				$msg_arr = array(
                     'user_id' => $move_user_id,//用户id
				);
				$this->Informationlib->send_ing($sys_arr,array('msg_id' => 13,'msg_arr' => $msg_arr));
			}else{
				form_json_msg('3','','调岗员工失败！');//返回错误信息json格式
			}

		}
		form_json_msg('0','','调岗员工成功！');//返回错误信息json格式
	}

	/**
	 * @abstract 修改消息状态
	 */
	public function update_stak_status(){
		//$this->load->model('uc_task_model');
		$this->load->model('employee_change_task_model');//1任务
		$task_id = strtolower($this->input->post('task_id' , TRUE));
		if(!preg_match('/^[\d]+$/',$task_id)){
			form_json_msg('1','','参数有误！');//返回错误信息json格式
		}
		$status = strtolower($this->input->post('status' , TRUE));
		if(!preg_match('/^[\d]+$/',$status)){
			form_json_msg('2','','参数有误！');//返回错误信息json格式
		}
		$modify_data = array(
            'update_data' =>array('status' => $status),
            'where' => array(
                'site_id' => $this->p_site_id, 
		//'recipient_user_id' => $this->p_user_id,
		//'type' => 3,//1-add 2-transfer  3-delete
                'id' => $task_id,
		//'status' => 1,//1客户端申请20管理员同意40管理员拒绝
		)
		);
		$user_task_arr = $this->employee_change_task_model->operateDB(5,$modify_data);
		if(!db_operate_fail($user_task_arr)){//成功

		}else{
			form_json_msg('5','','修改状态失败！');//返回错误信息json格式
		}
		form_json_msg('0','','修改状态成功！');//返回错误信息json格式
	}

	/**
	 * @abstract 显示搜索消息的结果
	 */
	public function searchInfo() {
		$this->load->view('information/searchInfo');
	}

	/**
	 * @abstract 通知详情
	 */
	public function message_info() {
		$message_id = $this->uri->segment(2);
		if(!preg_match('/^[\d]+$/',$message_id)){
			echo '参数有误';
			die();
		}
		$this->load->model('uc_message_model');
		//判断站点表记录是否存在
		$sel_data = array(
            'select' =>'title,send_name,content,addtime,isread,url_content',
            'where' => array(
                'id' => $message_id,
                'site_id' => $this->p_site_id, 
                'to_user_id' => $this->p_user_id                          
		)
		);
		$uc_db_user_arr = array();//uc库里有的用户
		$sel_arr =  $this->uc_message_model->operateDB(1,$sel_data);
		if(isemptyArray($sel_arr)){//没有记录
			echo '参数有误';
			die();
		}
		$isread = arr_unbound_value($sel_arr,'isread',2,'');
		if($isread == 0){//未读改为已读
			$modify_data = array(
                'update_data' =>array('isread' => 1),
                'where' => array(
                    'id' => $message_id,                         
			)
			);
			$user_task_arr = $this->uc_message_model->operateDB(5,$modify_data);
			if(!db_operate_fail($user_task_arr)){//成功

			}else{

			}
		}
		$data['msg_arr'] = $sel_arr;
		$this->load->view('information/messageinfo.php',$data);
	}

	/**
	 *
	 * @abstract 关闭任务
	 * @return null
	 */
	public function close_task(){
		$task_id = strtolower($this->input->post('task_id' , TRUE));
		if(!preg_match('/^[\d]+$/',$task_id)){
			form_json_msg('1','','参数有误！');//返回错误信息json格式
		}
		$this->load->model('uc_task_model');
		$this->load->model('employee_change_task_model');//1任务
		$modify_data = array(
            'update_data' =>array('status' => 40),//40管理员拒绝
            'where' => array(
                'id' => $task_id,  
                'status' => 1,
		)
		);

		$user_task_arr = $this->employee_change_task_model->operateDB(5,$modify_data);

		if(!db_operate_fail($user_task_arr)){//成功
			//发送关闭消息
			$sel_field = 'type,task_info';
			$where_arr = array(
                    'id' => $task_id,                      
			);
			$sel_arr = $this->employee_change_task_model->get_db_arr($where_arr,$sel_field);
			if(!isemptyArray($sel_arr)){//如果不是空数组
				$sys_arr = $this->p_sys_arr;
				$task_type = arr_unbound_value($sel_arr,'type',2,'');
				$task_info_json = arr_unbound_value($sel_arr,'task_info',2,'[]');
				$task_arr = json_decode($task_info_json,true);
				switch ($task_type){
					case 1://1-add
						$task_title = '新增员工';
						$operator_id = arr_unbound_value($task_arr,'operator_id',2,'');//操作人员
						$operator_name = arr_unbound_value($task_arr,'operator_name',2,'');//操作人
						$display_name = arr_unbound_value($task_arr,'display_name',2,'');//新加人姓名
						$mobile = arr_unbound_value($task_arr,'mobile',2,'');//手机号
						$current_dept_id = arr_unbound_value($task_arr,'current_dept_id',2,'');//当前部门id
						$current_dept_name = arr_unbound_value($task_arr,'current_dept_name',2,'');//当前部门名称
						$position = arr_unbound_value($task_arr,'position',2,'');//职位
						$account_enable = arr_unbound_value($task_arr,'account_enable',2,'');//是否是管理员
						$task_des = $operator_name . '申请新增员工' . $display_name . ' ';
						$task_keyword = empty_to_value($task_keyword,task_des);
						//发送组织消息
						$this->load->library('Informationlib','','Informationlib');
						$msg_arr = array(
                                     'org_id' => $current_dept_id,//当前组织id
                                     'user_name' => $display_name,//入职员工的姓名
						);
						$this->Informationlib->send_ing($sys_arr,array('msg_id' => 14,'msg_arr' => $msg_arr));
						break;
					case 2://2-transfer
						$task_title = '调岗员工';
						$operator_id = arr_unbound_value($task_arr,'operator_id',2,'');//操作者id
						$operator_name = arr_unbound_value($task_arr,'operator_name',2,'');//操作者姓名
						$move_user_name = arr_unbound_value($task_arr,'move_user_name',2,'');//移除员工姓名
						$move_user_id = arr_unbound_value($task_arr,'move_user_id',2,'');//移除员工id
						$current_dept_id = arr_unbound_value($task_arr,'current_dept_id',2,'');//当前部门id
						$current_dept_name = arr_unbound_value($task_arr,'current_dept_name',2,'');//当前部门名称
						$target_dept_id = arr_unbound_value($task_arr,'target_dept_id',2,'');//目标部门id
						$target_dept_name = arr_unbound_value($task_arr,'target_dept_name',2,'');//目标部门名称
						$position = arr_unbound_value($task_arr,'position',2,'');//职位
						$task_des = $operator_name . '申请' .  $move_user_name . '调到' . $target_dept_name. ' ';
						$task_keyword = empty_to_value($task_keyword,task_des);
						//发送组织消息
						$this->load->library('Informationlib','','Informationlib');
						$msg_arr = array(
                                     'user_id' => $move_user_id,//用户id
						);
						$this->Informationlib->send_ing($sys_arr,array('msg_id' => 15,'msg_arr' => $msg_arr));
						break;
					case 3://3-delete
						$task_title = '删除员工';
						$operator_id = arr_unbound_value($task_arr,'operator_id',2,'');//操作人id
						$operator_name = arr_unbound_value($task_arr,'operator_name',2,'');//操作人姓名
						$delete_user_id = arr_unbound_value($task_arr,'delete_user_id',2,'');//删除的用户id
						$current_dept_id = arr_unbound_value($task_arr,'current_dept_id',2,'');//当前部门id
						$delete_user_name = arr_unbound_value($task_arr,'delete_user_name',2,'');//删除的用户姓名
						$avatar = arr_unbound_value($task_arr,'avatar',2,'');
						$task_des = $operator_name . '申请将离职员工' .  $delete_user_name . '删除 ';
						$task_keyword = empty_to_value($task_keyword,task_des);
						//发送组织消息
						$this->load->library('Informationlib','','Informationlib');
						$msg_arr = array(
                                     'user_id' => $delete_user_id,//用户id
						);
						$this->Informationlib->send_ing($sys_arr,array('msg_id' => 16,'msg_arr' => $msg_arr));
						break;
					default:
						break;
				}
			}

			log_message('debug', ' update employee_change_task_model ' . json_encode($update_data) . '  is seccuss');
			form_json_msg('0','','关闭任务成功！');//返回错误信息json格式
		}else {
			log_message('error', ' update employee_change_task_model ' . json_encode($update_data) . 'is fail');
			form_json_msg('2','','关闭任务失败！');//返回错误信息json格式
		}
	}
	
	/**
	 * 获得任务列表
	 * @param string $offset
	 */
	public function get_task($offset = '') {
		$type = $this->input->get_post('type', true); //类型：0任务、1消息、2通知
		$is_read = $this->input->get_post('is_read', true); // 是否已读：0未读、1已读
		$type 		= (!is_empty($type) && is_numeric($type)) ? $type : 0;
		
		// 载入分页类
		$this->load->library('PageLib');
		
		// 配置分页信息
		$config['base_url'] 		= site_url('information/get_task/');
		$config['per_page'] 		= PER_PAGE;
		$config['list_div_class'] 	= '.infor_page';
		$limit 						= $config['per_page'];
		
		// 载入任务模型
		$this->load->model('employee_change_task_model');
		
		// 组织查询条件
		$where_arr = array(
				'site_id' 			=> $this->p_site_id,
				'recipient_user_id' => $this->p_user_id,
		);
		
		if(!is_empty($is_read)){
			if($is_read == 0){
				$where_arr['status'] = 1;
			}else if($is_read == 1){
				$where_arr['status'] = "IN(20,40)";
			}
		}
		
		// 配置分页信息：任务总数
		$config['total_rows'] = $this->employee_change_task_model->countTask($where_arr);
		
		// 初始化分页类
		$this->pagelib->initialize($config);
		
		// 生成分页信息
		$page_text = $this->pagelib->create_links();
		
		// 获取任务列表
		$data['task_arr'] = $this->employee_change_task_model->getTaskList($where_arr, $limit, $offset);
		
		// 将数据传递到页面
		$data['page_text'] = $page_text;
		$this->load->view('public/part/task_list.php', $data);
	}
	
	/**
	 * 获得消息列表
	 * @param string $offset
	 */
	public function get_notice($offset = '') {
		$type = $this->input->get_post('type', true); //类型：0任务、1消息、2通知
		$is_read = $this->input->get_post('is_read', true); // 是否已读：0未读、1已读
		$type = (!is_empty($type) && is_numeric($type)) ? $type : 1;
		
		
		// 载入分页类
		$this->load->library('PageLib');
		
		// 配置分页信息
		$config['base_url'] 		= site_url('information/get_notice/');
		$config['per_page'] 		= PER_PAGE;
		$config['list_div_class'] 	= '.infor_page';
		$limit 						= $config['per_page'];
		
		// 载入消息模型
		$this->load->model('uc_notice_model');
		
		// 组织查询条件
		$where_arr = array(
				'site_id' 		=> $this->p_site_id,
				'to_user_id' 	=> $this->p_user_id,
		);
		
		if(!is_empty($is_read)){
			$where_arr['isread'] = $is_read;
		}
		
		// 配置分页信息：消息总数
		$config['total_rows'] = $this->uc_notice_model->countNotice($where_arr);
		
		// 初始化分页类
		$this->pagelib->initialize($config);
		
		// 生成分页信息
		$page_text = $this->pagelib->create_links();
		
		// 获取消息列表
		$data['notice_arr'] = $this->uc_notice_model->getNoticeList($where_arr, $limit, $offset);
		
		// 将数据传递到页面
		$data['page_text'] = $page_text;
		$this->load->view('public/part/notice_list.php', $data);
	}
	
	/**
	 * 获得通知列表
	 * @param string $offset
	 */
	public function get_message($offset = '') {
		$type = $this->input->get_post('type', true); //类型：0任务、1消息、2通知
		$is_read = $this->input->get_post('is_read', true); // 是否已读：0未读、1已读
		
		$type = (!is_empty($type) && is_numeric($type)) ? $type : 0;
		
		// 载入分页类
		$this->load->library('PageLib');
		
		// 配置分页信息
		$config['base_url'] 		= site_url('information/get_message/');
		$config['per_page'] 		= PER_PAGE;
		$config['list_div_class'] 	= '.infor_page';
		$limit 						= $config['per_page'];
		
		//  载入通知模型
		$this->load->model('uc_message_model');
		
		// 组织查询条件
		$where_arr = array(
				'site_id' 		=> $this->p_site_id,
				'to_user_id' 	=> $this->p_user_id,
		);
		
		if(!is_empty($is_read)){
			$where_arr['isread'] = $is_read;
		}
		
		// 配置分页信息：通知总数
		$config['total_rows'] = $this->uc_message_model->countMessage($where_arr);
		
		// 初始化分页类
		$this->pagelib->initialize($config);
		
		// 生成分页信息
		$page_text = $this->pagelib->create_links();
		
		// 获取通知列表
		$data['message_arr'] = $this->uc_message_model->getMessageList($where_arr, $limit, $offset);
		
		// 将数据传递到页面
		$data['page_text'] = $page_text;
		$this->load->view('public/part/message_list.php', $data);
	}
}