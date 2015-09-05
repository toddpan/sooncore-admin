<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sme_Mail_Sending_Task_Model extends MY_Model{

    const TBL = 'sme_mail_sending_task';
    //构造函数
    public function __construct(){
        //调用父类构造函数，必不可少
        parent::__construct();
        $this->set_table('sme_mail_sending_task'); 
    }
    
}