<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @class log
 * @brief log Controller，日志控制，主要是员工豁免管理[列表、添加豁免、取消豁免]
 * @details  
 * @file log.php
 * @author yanzou <yan.zou@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
class Log extends Admin_Controller{
     /**
      * @brief 构造函数： 
      * 
      */
     public function __construct() {
         parent::__construct();     
         $this->load->library('form_validation');
         #UC_log_Model，载入之后，可以使用$this->UC_log_Model来操作
         //$this->load->model('UC_log_Model');
      
        //加载Staff类
        $this->load->library('StaffLib','','StaffLib');
     }
    /**
     * @brief日志列表页面;
     * @details
     * -# $databegin 开始日期 日期筛选只能选择最近三个月的日期，超过三个月置灰不可选择
     * -# $dataend 结束日期
     * -# $keywork 关键字
     * -# $isExport 是否导出档案
     * -# 从UC获得当前站点、当前企业能日志列表
     * -# 根据获取到的员工标识，获得操作人员名称
     * -# 将获得的日志分配到视图
     */
    public function logPage() {

        $this->load->view('log/log.php');
    }
    /**
     * @brief日志列表页面;
     * @details
     * -# $databegin 开始日期 日期筛选只能选择最近三个月的日期，超过三个月置灰不可选择
     * -# $dataend 结束日期
     * -# $keywork 关键字
     * -# $isExport 是否导出档案
     * -# 从UC获得当前站点、当前企业能日志列表
     * -# 根据获取到的员工标识，获得操作人员名称
     * -# 将获得的日志分配到视图
     */
    public function loglist() {
        //根据关键字获得员工标识
        //$this->StaffLib->
                
        //$data["LogArr"]=$this->UC_log_Model->getlog($site_id,$org_id);
        //导出档案
       //$type = strtolower($this->input->post('type' , TRUE));//类型1页面展示2导出档案
       $type =1;// empty_to_value($type,1);
       $bdate = strtolower($this->input->post('bdate' , TRUE));//开始时间
       $edate = strtolower($this->input->post('edate' , TRUE));//结束时间
       
       $keyword = strtolower($this->input->post('keyword' , TRUE));//关键词
        $in_array = array(
            'type' => $type,//类型1页面展示2导出档案
            'bdate' => $bdate,//开始时间
            'edate' => $edate,//结束时间
            'keyword' => $keyword,//关键词
        );
        $this->showfile_list($in_array);

    }
    public function down_log() {
        //根据关键字获得员工标识
        //$this->StaffLib->
                
        //$data["LogArr"]=$this->UC_log_Model->getlog($site_id,$org_id);
        //导出档案
       //$type = strtolower($this->input->get('type' , TRUE));//类型1页面展示2导出档案
       $type = 2;//$this->uri->segment(3); 
       $type = empty_to_value($type,2);
       $bdate = strtolower($this->input->get('bdate' , TRUE));//开始时间
       $bdate = $this->uri->segment(3); 
      // $edate = strtolower($this->input->get('edate' , TRUE));//结束时间
       $edate = $this->uri->segment(4); 
       //$keyword = strtolower($this->input->get('keyword' , TRUE));//关键词
       $keyword = $this->uri->segment(5); 
       
     
        $in_array = array(
            'type' => $type,//类型1页面展示2导出档案
            'bdate' => $bdate,//开始时间
            'edate' => $edate,//结束时间
            'keyword' => $keyword,//关键词
        ); 
        $this->showfile_list($in_array);

    }
    /**
     * 
     * @brief html显示或生态导入excel文档
     * @details 
     * @param array in_array 
        $in_array = array(
            'type' => $type,//类型1页面展示2导出档案
            'bdate' => $bdate,//开始时间
            'edate' => $edate,//结束时间
            'keyword' => $keyword,//关键词
        );
     * @return 0:失败；1：成功 2 回调失败
     */
    public function showfile_list($in_array = array()){
          $this->load->model('uc_log_model');
          $type = arr_unbound_value($in_array,'type',2,'1');//类型1页面展示2导出档案
          $bdate = arr_unbound_value($in_array,'bdate',2,'');//开始时间
          $edate = arr_unbound_value($in_array,'edate',2,'');//结束时间
          $keyword = arr_unbound_value($in_array,'keyword',2,'');//关键词
        //从数据库获得系统可选标签及自定义员工标签信息
        $where_sql = "site_id =" . $this->p_site_id . " and Org_id =" . $this->p_org_id;
        if(!bn_is_empty($bdate)){//有数据
            $where_sql .= " and addtime >='" . $bdate . " 00:00:01'";
        }
        if(!bn_is_empty($edate)){//有数据
            $where_sql .= " and addtime <='" . $edate . " 23:59:59'";
        }
        if(!bn_is_empty($keyword)){//有数据
            $where_sql .= " and ( login_name like '%" . $keyword . "%' or display_name like '%" . $keyword . "%')";
        } 
        $data_log = array(
            'select' =>'log_type_name,log_content,display_name,ip,addtime',
            'where' => $where_sql,
//          'where' => array(
//                   'Org_id' => $this->p_org_id, 
//                   'site_id' => $this->p_site_id,
//                ),
            'order_by' => 'id desc'
            
        );
        //die();
        $log_arr =  $this->uc_log_model->operateDB(2,$data_log);
        //print_r($log_arr);

        if($type == 1 ){//1页面展示2导出档案
            $data['log_arr'] = $log_arr;
            $this->load->view('public/part/log_list.php',$data);
        }else{//2导出档案
            //生成excel文件
            $this->load->helper('my_phpexcel');
            //export_excel(array($tag_arr),'07');
             $file_arr = array(
                // 'file_path' => 'data/file/',//文件路径，相对于站点目录：形式: 文件夹/../文件夹/
                // 'file_name' => '',//文件名称,注意没有文件后缀,如aaaa
             );
             $head_arr = array('活动名称','活动说明','操作人员','IP地址','时间');
             $log_arr = array_merge(array($head_arr),$log_arr); 
             $re_filename = create_excel($log_arr,'07',$file_arr,0);
            
        }
    }
}