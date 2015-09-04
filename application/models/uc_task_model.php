<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UC_Task_Model extends MY_Model{
	
	// 定义表名称
    const TBL = 'uc_task';
    
    /**
     * 构造函数
     */
    public function __construct(){
        parent::__construct(); 
        $this->set_table('uc_task');
    }
    
    
    
}