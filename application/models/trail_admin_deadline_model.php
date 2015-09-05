<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Trail_Admin_Deadline_Model extends MY_Model{
    const TBL = 'trail_admin_active';
    //构造函数
    public function __construct(){
        //调用父类构造函数，必不可少
        parent::__construct(); 
        $this->set_table('trail_admin_deadline');
    }
    
}