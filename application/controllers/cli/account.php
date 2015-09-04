<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @abstract 	account Controller，主要是对外接口，负责创建、修改账号。
 * @details		经测试扫描线程并不能获得系统的一些值，如$_SERVER，所以扫描线程代码要小心
 * @filesource 	account.php
 * @author 		jingchaoSun <jingchao.sun@quanshi.com>
 * @copyright 	Copyright (c) UC
 * @version 	v1.0
 */
class Account extends  Thread_Controller{
	public function __construct() {
		parent::__construct();
		$this->load->helper('my_publicfun');
		$this->load->helper('my_dgmdate');
		$this->load->library('API','','API');
		log_message('info', 'into class ' . __CLASS__ . '.');
	}

	/**
	 * @abstract 扫描线程
	 */
	public function scan_thread() {
		// 类型:1客户开通线程;2用户开通线程
		$open_type = $this->uri->segment(4);
		log_message('info', __FUNCTION__." input->\n".var_export(array('open_type' => $open_type), true));

		do{
			echo "run time " .  dgmdate(time(), 'dt') . PHP_EOL ;
			$this->siteOpenThread($open_type);
			sleep(1);		// 休眠10秒
			echo PHP_EOL;	// 换行
		}while(1==1);
	}

	/**
	 * @abstract BOSS站点开通守户线程
	 * 查找未处理的信息
	 * 添加站点数据
	 * 向UMS写数据
	 * 向UC的user表写数据
	 * 调用UCC入职接口
	 * 向UC的管理员表写数据
	 * 调用UCC的接口(建立MQ和数据库)
	 * 调用UCC的增加联系人的消息接口[没有了，是生态企业模块才会调用]
	 * 调用会议服务接口
	 * 发送开通邮件
	 * 发送内部通知
	 * 回调BOSS接口
	 */
	public function siteOpenThread($open_type = 0 ) {
		log_message('info', __FUNCTION__ . " input->\n" . var_export(array('open_type' => $open_type), true));

		// 如果开通类型为空，则为开通类型设置默认值
		$open_type = empty_to_value($open_type, 0);// 类型:1客户开通线程;2用户开通线程
		//echo 'in param $open_type = ' . $open_type . PHP_EOL;

		// 载入账号类库
		$this->load->library('AccountLib', '', 'AccountLib');
		// 载入线程模型
		$this->load->model('UC_Thread_Model');

		// 去扫描线程表uc_thread
		$where_arr = array('isvalid' => 1);
		if($open_type > 0){
			$where_arr['type'] = $open_type;
		}
		$data = array(
           'select' 	=> 'id,value,type',
           'where' 		=> $where_arr,
           'limit' 		=> array(
               		'limit'  => 1,	//返回的结果数量
               		'offset' => 0	//结果偏移量
		),
           'order_by' 	=> 'id asc'
           );

           $Thread_arr =  $this->UC_Thread_Model->operateDB(2, $data);

           //判断类型是开通站点，还是开通用户
           if(!isemptyArray($Thread_arr)){//如果有记录:则执行
           	$thread_idarr = array();//获得id串
           	
           	foreach($Thread_arr as $k => $v){
           		$thread_idarr[] = $v['id'];
           	}
           	
           	if(!isemptyArray($thread_idarr)){//如果有记录:则执行
           		log_message('info', '$thread_idarr=' . json_encode($thread_idarr));
           		$this->update_thread($thread_idarr, 8);//批量修改状态为8正在运行中。。。
           	}
           	
           	foreach($Thread_arr as $k => $v){
           		$open_state = -1;//未知情况
           		$thread_id = $v['id'];
           		$thread_value = $v['value'];
           		$thread_type = $v['type'];
           		$msg_txt = 'running UC_Thread table id = ' . $thread_id . PHP_EOL;
           		log_message('info', $msg_txt);

           		if($thread_type == 1){//类型1BOSS合同开通、BOSS管理员开通、用户批量开通
           			//0:失败；1：成功。2回调失败
           			$open_state = $this->AccountLib->boss_open_site($thread_value);
           			log_message("debug", "boss open site api reutrn data is ->".var_export($open_state, true));
           		}else if($thread_type == 2){//2UC向BOSS批量开通
           			//0:失败；1：成功
           			$open_state = $this->AccountLib->batchOpenUser($thread_value);
           		}else if($thread_type == 3){//2用户开通/修改线程
           			$open_state = $this->AccountLib->batch_modify_user($thread_value);
           		}else if($thread_type == 4){//单个用户开通/关闭/删除
           			//0:失败；1：成功。2回调失败
           			$open_state = $this->AccountLib->boss_modify_user($thread_value);
           		}else if($thread_type == 5){//用户/部门/站点权限更新
           			//0:失败；1：成功。2回调失败
           			$open_state = $this->AccountLib->boss_modify_power($thread_value);
           		}else if($thread_type == 6){//删除生态企业
           			//0:失败；1：成功。2回调失败
           			$open_state = $this->AccountLib->delete_ecology($thread_value);
           		}
           		log_message('info', '$open_state =' . $open_state);
           		
           		switch ($open_state){
           			case 0: //0:失败
           				//修改当前记录状态2线程运行错误
           				$update_stat = $this->update_thread($thread_id,2);
           				break;
           			case 1: //1：成功
           				//修改当前记录状态2线程运行完成
           				$update_stat = $this->update_thread($thread_id,0);
           				break;
           			case 2: //2回调失败
           				//修改当前记录状态2线程运行错误
           				$update_stat = $this->update_thread($thread_id,3);
           				break;
           			default:
           				//修改状态
           				//修改当前记录状态 是否有效0无效1有效
           				$update_stat = $this->update_thread($thread_id,4);
           				break;
           		}
           	}
           }else{
           	echo 'UC_Thread table not record to run ' .  PHP_EOL;
           }
           log_message('info', 'out method ' . __FUNCTION__ . '.');
	}

	/**
	 * @abstract 	更新线程的状态
	 * @param 		int/array 	$thread_id 		当前线程编号,数组时，为多个线程id数组
	 * @param 		int 		$new_state 		新的状态0无效1有效2线程运行错误3回调失败4不是有效类型8正在运行
	 * @return 		FALSE:失败；TRUE：成功。
	 */
	protected function update_thread($thread_id, $new_state){
		log_message('info', __FUNCTION__ . " input->\n" . var_export(array('thread_id' => $thread_id, 'new_state' => $new_state), true));

		// 判断新的状态是否合法
		if(!preg_match("/^[0-4|8]{1}$/", $new_state)){
			return false;
		}

		// 载入线程模型
		$this->load->model('UC_Thread_Model');

		$update_data = array(
            'update_data' => array('isvalid' => $new_state),
		);

		if(is_array($thread_id)){//是数组
			$update_data['where_in'] = array('id' => $thread_id);
		}else{
			if(!preg_match("/^[\d]+$/", $thread_id )){
				return false;
			}
			$update_data['where'] = array('id' => $thread_id);
		}

		// 更新线程状态
		$thread_arr = $this->UC_Thread_Model->operateDB(5, $update_data);
		if(!db_operate_fail($thread_arr)){//成功
			log_message('debug', ' update UC_Thread ' . $thread_id . ' isvalid value ' . $new_state . ' is seccuss');
			return true;
		}else {// 失败
			log_message('error', ' update UC_Thread ' . $thread_id . ' isvalid value ' . $new_state . ' is fail');
			return false;
		}
		log_message('info', 'out method ' . __FUNCTION__ . '.');
	}
}