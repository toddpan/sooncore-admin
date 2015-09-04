<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @detail
 * 1.账号发生create、update、disable、enable、delete操作事，需要将操作信息上传给boss来执行。
 * 2.这个文件是上传任务的model
 * @file AccountUploadTask.php
 * @author caohongliang <hongliang.cao@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */


class Account_Upload_Task_Model extends CI_Model{
	
	public function __construct(){
		parent::__construct(); 

        //载入数据库
        $this->load->database(DB_RESOURCE);
        
        //表
		$this->tbl = array(
			'task'=>'uc_upload_task',
		);
    }
	
    /**
     * 获取一条任务
     * -这里使用了乐观锁，避免多进程处理一条任务的情况
     * @return mix
     */
	public function getTask(){
		
		//获取一条未执行的任务
		$_task = $this->db->select('id,value,status,version,type,create_time,update_time')
			->where(array('status'=>TASK_UNPROCESS))
			->limit(1)
			->get($this->tbl['task']);
		if(!$_task->num_rows()){ return false;}
		
		//乐观锁，更新版本号version
		$task        =  $_task->first_row('array');
		$update_where = array(
			'id'       =>$task['id'],
			'version'  =>$task['version'],
		);
		$update_data =  array(
				'version'     => $task['version']+1,
				'status'  	  => TASK_PROCESSING,
				'update_time' => time(),
		);
		$affect_rows = $this->db->where($update_where)->update($this->tbl['task'], $update_data);
		
		return $affect_rows > 0 ? $task : false;
		
	}
	
	/**
	 * 保存任务
	 * @param int    $type 操作类型
	 * @param string $json 请求数据
	 */
	public function saveTask($type, $json){
		$insert_data = array(
			'value'			=>$json,
			'type'			=>$type,
			'create_time'	=>time(),
			'status'		=>TASK_UNPROCESS,//操作状态，0-未执行 1-执行中 2-执行完成功 3-执行失败
			'version'       =>INIT_VERSION, //乐观锁状态
			'update_time'	=>time()
		);
		return $this->db->insert($this->tbl['task'],$insert_data);
	}
	
	/**
	 * 改变任务状态
	 * @param int $id     任务id
	 * @param int $status 任务状态码
	 */
	public function changeTaskStatus($id, $status, $msg=''){
		return $this->db->where(array('id'=>$id))->update($this->tbl['task'], array('status'=>$status, 'result'=>$msg, 'update_time'=>time()));
	}
}