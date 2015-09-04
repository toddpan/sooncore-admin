<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Formal_User_Active_Model extends MY_Model{
    const TBL = 'formal_user_active';
    //构造函数
    public function __construct(){
        //调用父类构造函数，必不可少
        parent::__construct(); 
        $this->set_table('formal_user_active');
    }
    
}