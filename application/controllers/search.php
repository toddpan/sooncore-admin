<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @class Search
 * @brief 组织机构搜索、生态企业搜索
 * @file search.php
 * @author caohongliang <hongliang.cao@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
class Search extends Admin_Controller{
	
	/**
     * @brief 构造方法
     */
    public function __construct(){
        parent::__construct();
        $this->load->helper('my_publicfun');
        $this->load->library('UmsLib','','ums');//ums接口
    }
	
	
	/**
     * 企业员工搜索
     *
     * @author hongliang.cao@quanshi.com
     */
    public function searchOrgAccountPage() {		 
        //获取关键字,如果为空则直接返回
        $keyword = $this->input->get_post('keyword', true);
        $ret_data = array();
        if(empty($keyword)){
            return $this->load->view('staff/searchOrgAccount.php', $data=array('ret_data'=>$ret_data));
        }
        //搜索
		list($flag, $data) = $this->_search($this->p_customer_code, $keyword, 1);
		if(!$flag){
			log_message('error', 'error message:'.$data);
			return $this->load->view('staff/searchOrgAccount.php', $data=array('ret_data'=>$ret_data));
		}
		$ret_users = isset($data['users']) ? $data['users'] : array(); 
        //设置返回值
        foreach($ret_users as $_user){
            $_ret_data = array();
            $_ret_data['userId']    = $_user['id'];
            $_ret_data['displayName']    = $_user['displayName'];
            $_ret_data['loginName']      = $_user['loginName'];
            $_ret_data['mobileNumber']   = $_user['mobileNumber'];
            $_ret_data['lastlogintime']  = !empty($_user['lastlogintime']) ? date("Y/m/d H:i",round($_user['lastlogintime']/1000)) : '未登录';
            $ret_data[] = $_ret_data; 
        }
        $this->load->view('staff/searchOrgAccount.php', $data=array('ret_data'=>$ret_data));
    }
	
	
	/**
     * 生态企业搜索
     * @author hongliang.cao@quanshi.com
     * -#系统管理员可以搜索     1.下级渠道公司名称 2.下级渠道公司员工
     * -#渠道公司管理员可以搜索，1.下级渠道公司名称 2.渠道公司管理员所属的公司员工 3.渠道公司管理的下级渠道公司员工
     * @return html
     */
    public function searchComEcology(){
        //关键字
        $keyword = $this->input->get_post('keyword', true);
        $ret_data =  array('subEcology'=>array(), 'subEcologyMembers'=>array(), 'selfMembers'=>array());
        if(empty($keyword)){
            return $this->load->view('ecologycompany/searchcomEcology.php',array('ret_data'=>$ret_data));
        }
        //根据管理员角色，搜索出不同的结果
        $role_id  =  $this->p_role_id;
 
		//获取下级所有的生态企业和下级生态企业里的员工
		list($flag, $_sub_eco_member)   = $this->_searchSubEcologyMembers($this->p_customer_code, $keyword, array($this->p_org_id));
		if(!$flag){
			log_message('error','error message:'.$_sub_eco_member);
		}
		$ret_data['subEcology']        = $_sub_eco_member['subEcology'];    //下级渠道公司
		$ret_data['subEcologyMembers'] = $_sub_eco_member['subEcologyMembers']; //下级渠道公司里的员工
		
		if($role_id == 6){//渠道管理员用户
            //搜索管理员所属的公司员工
            list($rflag, $data) = $this->_search($this->p_customer_code, $keyword, 1);
            if(!$rflag){
                log_message('error','error message:'.$data);
            }else{
                $ret_data['selfMembers'] = $data['member'];
            }
        }
        $this->load->view('ecologycompany/searchcomEcology.php',array('ret_data'=>$ret_data));
    }
    
    /**
     * @abstract 管理员管理模块：新建管理员时，输入管理员账号或姓名，Ajax显示员工列表
     * @author Bai Xue <xue.bai_2@quanshi.com>
     */
    public function searchManager(){
    	//获取关键字,如果为空则直接返回
    	$keyword = $this->input->get_post('keyword', true);
    	$ret_data = array();
    	if(is_empty($keyword)){
    		//return $this->load->view('manage/addManagerDialog.php', $data=array('ret_data'=>$ret_data));
    		return_json(0, '', array('ret_data'=>$ret_data));
    	}
    	//搜索
    	list($flag, $data) = $this->_search($this->p_customer_code, $keyword, 1);
    	if(!$flag){
    		log_message('error', 'error message:'.$data);
    		//return $this->load->view('manage/addManagerDialog.php', $data=array('ret_data'=>$ret_data));
    		return_json(0, '', array('ret_data'=>$ret_data));
    	}
    	$ret_users = isset($data['users']) ? $data['users'] : array();
    	//设置返回值
    	foreach($ret_users as $_user){
    		$_ret_data = array();
    		$_ret_data['displayName']    = $_user['displayName'];
    		$_ret_data['loginName']      = $_user['loginName'];
			$_ret_data['user_id']      = $_user['id'];
    		$ret_data[] = $_ret_data;
    	}

    	//$this->load->view('manage/addManagerDialog.php', $data=array('ret_data'=>$ret_data));
    	return_json(0, '', array('ret_data'=>$ret_data));
    }
	
	
	/**
	 * 搜素员工、生态企业
	 * 
	 * @author hongliang.cao@quanshi.com
	 * @param string $customer_code 客户编码 
	 * @param string $keyword       搜索关键字
	 * @param int    $type          搜索类型 0-全部 1-员工 2-生态企业名称
	 * @param array  $org_ids       组织id(企业or生态企业)
	 * @return array
	 */
    private function _search($customer_code, $keyword, $type, $org_ids=array()){
        //设置返回
        $ret_arr = array();
        //加载库
        $this->load->library('UmsLib','','ums');
        //参数验证
        if(empty($keyword) || empty($customer_code) || !in_array($type,array(0,1,2))){
            return array(false,'param error!');
        }
        // XXX 之后改为由大数据搜索
        //调用ums接口搜索
        $ums_arr = $this->ums->searchOrgOrUser($customer_code, $keyword, $type, $org_ids);
		if($ums_arr === false){
            return array(false,'get search result form ums fail!');
        }
        return array(true, $ums_arr);
    }  
   
      
	/**
	 * 搜索下级生态企业以及下级生态企业里所有的员工
	 * 
	 * @author hongliang.cao@quanshi.com
	 * @param    string    $customer_code 客户编码
	 * @param    string    $keyword     搜索关键字
	 * @param    int       $org_ids      组织id
	 */
    private function _searchSubEcologyMembers($customer_code, $keyword, $org_ids=array()){
        //设置返回
        $ret_arr = array();
        //加载库
        $this->load->library('UmsLib','','ums');
        //验证参数
        if(empty($customer_code) || empty($keyword)){
            return array(false, 'param error!');
        }
        //搜索下级生态企业名称
        //list($flag, $rdata) = $this->_search($customer_code, $keyword, 2, $org_ids);
        list($flag, $rdata) = $this->_search($customer_code, $keyword, 2);
		if(!$flag){
            return array(false, $rdata);
        }
        $ret_arr['subEcology'] = $rdata['orgs'];
        //获取下级生态企业id，搜索下级生态企业员工
		$this->load->library('UmsLib', '', 'ums');
		$_subEcologys = $this->ums->getOrganization($this->p_org_id,'nextlevel',2);
        $sub_ecology_ids = array();
        if(!empty($_subEcologys) && is_array($_subEcologys)){
            foreach($_subEcologys as $_subEcology){
                $sub_ecology_ids[] = $_subEcology['id'];
            } 
        }
        list($aflag, $ardata) = $this->_search($customer_code, $keyword, 1, $sub_ecology_ids);
		if(!$aflag){
            return array(false, $ardata);
        }
        $ret_arr['subEcologyMembers'] = $ardata['users']; 
        
        return array(true, $ret_arr);
    }
	
	public function test(){
		$customer_code = $this->p_customer_code;
		echo $customer_code;
		$keyword       = '生态';
		$type          = 0;
		$org_ids       = array();
		$rs = $this->_search($customer_code, $keyword, $type, $org_ids);
		echo $this->p_org_id;
		print_r($rs);
	}
}