<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 账号(create|update|disable|enable|delete)、权限变更操作的数据上传
 * @file AccountUploadTask.php
 * @author caohongliang <hongliang.cao@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */

class AccountUploadTask extends  Task_Controller{
	
	public function __construct() {
		parent::__construct();
	
		$this->load->model('account_upload_task_model','upload_task');
		
		$this->load->library('Account/Factory/AccountUploadFactory', '', 'factory');
	}
	
	
	/**
	 * 扫描数据库,从数据库里获取一条执行任务执行
	 * 根据操作类型，进行普通账号（开通、关闭、启用、禁用、删除）、权限变更的数据上传
	 * 
	 * -从数据库里获取一件任务，如果没有任务，则休眠，然后继续获取
	 * -根据获取到的任务类型，去工厂里创建实例，调用实例的接口方法，进行相应的操作
	 * @throws Exception
	 */
	public function scan() {
		
		log_message('info', 'Account uploaed thread start...');
		
		while(true){
			try{
				//从数据库里获取一条数据
				$task = $this->upload_task->getTask();
				if(!$task) {
					log_message('debug', 'Not found upload task to run. Let me have a rest');
					sleep(THREAD_SLEEP_TIME);
					continue;
				}
				
				//记log
				$id	   = isset($task['id'])    ? $task['id']   : 0;
				log_message('info','Get a task from DB. the task id is -->'.$id);
				
				//获取任务类型以及数据
				$type  = isset($task['type'])  ? $task['type'] : 0;
				$value = isset($task['value']) ? json_decode($task['value'], true) : NULL;
				if(is_null($value)) {
					throw new Exception('Invalid data format-->'.var_export($value, true));
				}
				
				//从工厂里获取相应的实例去执行
				$account_inst = $this->factory->get_instance($type);
				$account_inst->process($value);
				
				//任务执行完成，更新状态
				$this->upload_task->changeTaskStatus($id, TASK_PROCESSED, 'success');
				
			}catch(Exception $e){
				log_message('error', $e->getMessage());
				$this->upload_task->changeTaskStatus($id, TASK_PROCESS_FAILED, $e->getMessage());//任务执行失败，更改状态
			}
		}
	}
	
}

