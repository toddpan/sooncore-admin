<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 系统设置
 * @filesource 	system.php
 * @author 		caohongliang <hongliang.cao@quanshi.com>
 * @copyright 	Copyright (c) UC
 * @version 	v1.0
 */
class SystemSet extends Admin_Controller{
	public function __construct() {
		parent::__construct();
		$this->load->helper('my_publicfun');
		$this->load->helper('language');
		$this->lang->load('admin', 'chinese');
	}
	
	/**
	 * 首页
	 */
	public function index(){
		$this->company();
	}
	
	/**
	 * 企业信息页面
	 */
	public function company(){
		$data = array();
		//获取logo地址
		$this->load->model('uc_site_model', 'site');
		$data['url'] = is_empty($_url = $this->site->getLogoUrl($this->p_site_id)) ? base_url('public/images/bisinessLogo.jpg') : $this->_generateUrl($_url);
		//从boss获取企业信息
		$this->load->library('BossLib', '', 'boss');
		$info = $this->boss->getCustomerInfo($this->p_customer_code);
		if(!empty($info)){
			$data['name']		= isset($info['name']) ? $info['name'] : '';//公司名称
			$data['address']	= isset($info['address']) ? $info['address'] : '';//公司地址
			$data['country']	= isset($info['country']) ? $info['country'] : '';//国家
			$data['city']		= isset($info['city']) ? $info['city'] : '';//城市
			$data['phone']		= isset($info['phone']) ? $info['phone'] : '';//电话
			$data['fax']		= isset($info['fax']) ? $info['fax'] : '';//传真
			$data['website']	= isset($info['website']) ? $info['website'] : '';//公司网站
			
			$data['f_name']		= isset($info['financialContacts']['name']) ? $info['financialContacts']['name'] : '';//财务联系人姓名
			$data['f_email']	= isset($info['financialContacts']['email']) ? $info['financialContacts']['email'] : '';//财务联系人邮箱
			$data['f_mobile']	= isset($info['financialContacts']['mobilePhone']) ? $info['financialContacts']['mobilePhone'] : '';//财务联系人手机
			$data['f_tel']		= isset($info['financialContacts']['telPhone']) ? $info['financialContacts']['telPhone'] : '';//财务联系人电话
			
			$data['m_name']		= isset($info['mainContacts']['name']) ? $info['mainContacts']['name'] : '';//主要联系人姓名
			$data['m_email']	= isset($info['mainContacts']['email']) ? $info['mainContacts']['email'] : '';//主要联系人邮箱
			$data['m_mobile']	= isset($info['mainContacts']['mobilePhone']) ? $info['mainContacts']['mobilePhone'] : '';//主要联系人手机
			$data['m_tel']		= isset($info['mainContacts']['telPhone']) ? $info['mainContacts']['telPhone'] : '';//主要联系人电话
		}
		//TODO接收提醒邮箱、系统通知
		$data['email'] = 'iwanttoplayagamewithyou@gg.com';//接收提醒邮箱
		$data['system_notice'] = true;//系统通知开关

		$this->load->view('setsystem/cor_info.php',array('data'=>$data));
	}
	
	public function setLogoDialog(){
		//获取缩放后的原图和logo
		if($orig = $this->_getOrigLogo()){
			$ori_logo    =  $orig['full_name'];//原图
			$mid_logo    =  $orig['file_name'].'_mid.'.$orig['ext'];//中图
			$resize_logo =  $orig['file_name'].'_min.'.$orig['ext'];//剪裁并缩放后的图片
			
			$o_logo = $this->_generateUrl($mid_logo);
			$logo   = $this->_generateUrl($resize_logo);
		}else{
			$o_logo = base_url('public/images/clipLogo.jpg');;
			$logo   = base_url('public/images/bisinessLogo.jpg');;
		}
		
		$data = array('o_logo'=>$o_logo, 'logo'=>$logo);
		$this->load->view('public/popup/setlogo.php', $data);
	}
	
	/**
	 * logo上传
	 */
	public function logoUpload(){
		$props = array(
			'upload_path'=>LOGO_UPLOAD_PATH,
			'allowed_types'=>LOGO_ALLOW_TYPES,
			'max_size'=>LOGO_MAX_SIZE,
			'file_name'=>'logo_'.$this->p_customer_code.'_'.$this->p_site_id,
			'overwrite'=>true,//一个站点只保存一张原图
		);
		$this->load->library('upload', $props);
		if(!$this->upload->do_upload('logo')){
			return_json(10000, json_encode($this->upload->display_errors()));
		}else{
			$d = $this->upload->data();
			//for some reason , here should resize
			$mid_logo = 'logo_'.$this->p_customer_code.'_'.$this->p_site_id.'_mid'.$d['file_ext'];
			$props = array(
					'image_library'=>'gd2',
					'quality'=>'100%',
					'width'=>LOGO_MID_WIDTH,
					'height'=>LOGO_MID_HEIGHT,
					'maintain_ratio'=>false,
					'master_dim'=>'auto',
					'source_image'=>LOGO_UPLOAD_PATH.$d['file_name'],
					'new_image'=>LOGO_UPLOAD_PATH.$mid_logo,
			);
			$this->load->library('image_lib', $props);
			if( !$this->image_lib->resize()){
				return_json(20000, json_encode($this->image_lib->display_errors()));
			}
			
			$url = $this->_generateUrl($mid_logo);
			return_json(0,'success',array('src'=>$url));
		}
	}
	
	/**
	 * logo剪裁
	 */
	public function logoCrop(){
		//获取参数
		$x = intval($this->input->get_post('x',true));
		$y = intval($this->input->get_post('y',true));
		$w = intval($this->input->get_post('w',true));//选取宽度
		$h = intval($this->input->get_post('h',true));//选取高度
		
		try{
			//检查参数
			if(($w <= 0) || ($h <= 0)){
				throw new Exception('invalid param');
			}
			//裁剪logo
			if(!$orig = $this->_getOrigLogo()){
				throw new Exception('Your must upload a image at first!');
			}
			$ori_logo    =  $orig['full_name'];//原图
			$mid_logo    =  $orig['file_name'].'_mid.'.$orig['ext'];//中图
			$crop_logo   =  $orig['file_name'].'_crop.'.$orig['ext'];//剪裁后的图片
			$resize_logo =  $orig['file_name'].'_min.'.$orig['ext'];//剪裁并缩放后的图片
			
			$props = array(
				'image_library'=>'gd2',
				'quality'=>'100%',
				'x_axis'=>$x,
				'y_axis'=>$y,
				'width'=>$w,
				'height'=>$h,
				'maintain_ratio'=>false,
				'source_image'=>LOGO_UPLOAD_PATH.$mid_logo,
				'new_image'=>LOGO_UPLOAD_PATH.$crop_logo,
			);
			
			$this->load->library('image_lib', $props);
			if ( ! $this->image_lib->crop()){
				throw new Exception($this->image_lib->display_errors());
			}
			//缩放
			$props['width'] = LOGO_WIDTH;
			$props['height'] = LOGO_HEIGHT;
			$props['source_image'] = LOGO_UPLOAD_PATH.$crop_logo;
			$props['new_image']    = LOGO_UPLOAD_PATH.$resize_logo;
			$this->image_lib->initialize($props);
			if( !$this->image_lib->resize()){
				throw new Exception($this->image_lib->display_errors());
			}
			$url = $this->_generateUrl($resize_logo);
			//将logo名称存入数据库
			$this->load->model('uc_site_model', 'site');
			if(! $this->site->saveLogoUrl($this->p_site_id, $resize_logo)){
				throw new Exception('save logo url to database fail!');	
			}
			//返回url
			return_json(0,'',array('src'=>$url));
		}catch(Exception $e){
            log_message('error',$e->getMessage());
            return_json(10000,$e->getMessage());
		}
	}
	
	/**
	 * 获取站点logo原图信息，包括文件名称和扩展
	 */
	private function _getOrigLogo(){
		$this->load->helper('file');
		$fn = 'logo_'.$this->p_customer_code.'_'.$this->p_site_id;
		$fs = get_filenames(LOGO_UPLOAD_PATH);
		foreach($fs as $f){
			if( 0 === strpos($f, $fn)){
				return array('ext'=>end(explode('.', $f)), 'file_name'=>$fn, 'full_name'=>$f);
			}
		}
		return false;
	}
	
	/**
	 * 生成图片地址
	 * @param str $file_name 图片名称
	 */
	private function _generateUrl($file_name){
		$_u = str_replace(FCPATH, '', realpath(LOGO_UPLOAD_PATH));
		$_u = str_replace('\\','/',$_u);
		return base_url($_u.'/'.$file_name);
	}
}

