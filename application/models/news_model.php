<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News_model extends CI_Model{

    const TBL = 'news';
    //构造函数
    public function __construct(){
        //调用父类构造函数，必不可少
        parent::__construct();
        //手动载入数据库操作类
        $this->load->database();
    }

    /**
     * @access public
     * @param $data array
     * @return bool 成功返回true，失败返回false
     */
    public function add_news($data){
        //使用AR类完成插入操作
        return $this->db->insert(self::TBL,$data);
    }

    /**
     * @access public
     * @return array 查询的结果
     */
    public function list_news(){
        $query = $this->db->get(self::TBL);
        return $query->result_array();
    }
}