<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * 报告管理
 * 用于财务分析报告、账号分析报告、使用行为报告、生态热点报告
 */
class Report extends Admin_Controller{
   /**
    * @brief 载入报告管理页：
    * @details 
    * -# 
    *
    */
   public function reportManagePage() {
       $this->load->view('report/reportManage.php');
   }

   /**
    * @brief 显示导入之后的报告管理类
    */
   public function financialAnalysisReport() {
            $this->load->view('report/financialAnalysisReport.php');
	}
}