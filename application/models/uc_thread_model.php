<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UC_Thread_Model extends MY_Model{

    const TBL = 'uc_thread';
    //构造函数
    public function __construct(){
        //调用父类构造函数，必不可少
        parent::__construct(); 
        $this->set_table('uc_thread');
    }
}