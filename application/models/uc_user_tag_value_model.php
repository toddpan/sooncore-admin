<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class UC_User_Tag_Value_Model extends MY_Model{

    const TBL = 'uc_user_tag_value';

    //构造函数
    public function __construct(){
        //调用父类构造函数，必不可少
        parent::__construct(); 
        $this->set_table('uc_user_tag_value');
    }
}