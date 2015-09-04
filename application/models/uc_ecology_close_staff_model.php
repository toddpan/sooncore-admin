<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class UC_Ecology_Close_Staff_Model extends MY_Model{

    //const TBL = 'uc_ecology_close_staff';
    //构造函数
    public function __construct(){
        //调用父类构造函数，必不可少
        parent::__construct(); 
        $this->set_table('uc_ecology_close_staff');
    }
}