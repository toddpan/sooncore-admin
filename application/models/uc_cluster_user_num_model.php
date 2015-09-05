<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class UC_Cluster_User_Num_Model extends MY_Model{

    //const TBL = 'uc_cluster_user_num';
    //构造函数
    public function __construct(){
        //调用父类构造函数，必不可少
        parent::__construct(); 
        $this->set_table('uc_cluster_user_num');
    }
}