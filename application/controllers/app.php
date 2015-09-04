<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @abstract 应用管理类
 * 
 * @author xue.bai_2@quanshi.com 2015-06-23
 */
class App extends Admin_Controller{
	
	/**
	 * 构造方法
	 */
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * 显示应用列表
	 */
	public function app_list(){
		$this->load->model('uc_application_model', 'app');
		
		$app_list_arr = $this->app->get_app_lists($this->p_site_id);
		
		$this->load->view('app/applist.php', array('app_list' => $app_list_arr));
	}
	
	/**
	 * 显示添加或编辑应用页面
	 */
	public function add_or_edit_app() {
		$app_id = $this->input->get('app_id', true);
		
		$app_info = array();
		if(!empty($app_id)){
			$this->load->model('uc_application_model', 'app');
			
			$app_info = $this->app->get_app_info(array('id' => $app_id));
			
		}
		$this->load->view('app/add_or_edit_app.php', array('app_info' => $app_info));
	}
	
	/**
	 * 新建或者修改应用信息
	 */
	public function save_app() {
		$app_info = $this->input->post('app_info', true);
		
		$this->load->model('uc_application_model', 'app');
		$res_flag = $this->app->save_app_info($app_info);
		
		if($res_flag){
			return_json(COMMON_SUCCESS);
		}
		
		return_json(COMMON_FAILURE, 'failed');
	}
	
	public function setLogoDialog(){
		$app_logo = $this->input->get('app_logo', true);
		
		//获取缩放后的原图和logo
		if($app_logo){
			$orig 		 = $this->_getOrigLogo($app_logo);
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
		$this->load->view('public/popup/setapplogo.php', $data);
	}
	
	/**
	 * 获取站点logo原图信息，包括文件名称和扩展
	 */
	private function _getOrigLogo($app_logo){
		$this->load->helper('file');
		$fs = get_filenames(APP_LOGO_UPLOAD_PATH);
		foreach($fs as $f){
			if( 0 === strpos($f, $app_logo)){
				return array('ext'=>end(explode('.', $f)), 'file_name'=>$app_logo, 'full_name'=>$f);
			}
		}
		return false;
	}
	
	/**
	 * logo上传
	 */
	public function logoUpload(){
		$props = array(
				'upload_path'=>APP_LOGO_UPLOAD_PATH,
				'allowed_types'=>LOGO_ALLOW_TYPES,
				'max_size'=>LOGO_MAX_SIZE,
				'file_name'=>'applogo_'.$this->p_customer_code.'_'.$this->p_site_id.'_'.time(),
				'overwrite'=>true,//一个站点只保存一张原图
		);
		$this->load->library('upload', $props);
		if(!$this->upload->do_upload('logo')){
			return_json(10000, json_encode($this->upload->display_errors()));
		}else{
			$d = $this->upload->data();
			//for some reason , here should resize
			$mid_logo = 'applogo_'.$this->p_customer_code.'_'.$this->p_site_id.'_'.time().'_mid'.$d['file_ext'];
			$props = array(
					'image_library'=>'gd2',
					'quality'=>'100%',
					'width'=>LOGO_MID_WIDTH,
					'height'=>LOGO_MID_HEIGHT,
					'maintain_ratio'=>false,
					'master_dim'=>'auto',
					'source_image'=>APP_LOGO_UPLOAD_PATH.$d['file_name'],
					'new_image'=>APP_LOGO_UPLOAD_PATH.$mid_logo,
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
		$app_logo = $this->input->get_post('app_logo',true); 
	
		try{
			//检查参数
			if(($w <= 0) || ($h <= 0)){
				throw new Exception('invalid param');
			}
			//裁剪logo
			if(!$orig = $this->_getOrigLogo($app_logo)){
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
					'source_image'=>APP_LOGO_UPLOAD_PATH.$mid_logo,
					'new_image'=>APP_LOGO_UPLOAD_PATH.$crop_logo,
			);
				
			$this->load->library('image_lib', $props);
			if ( ! $this->image_lib->crop()){
				throw new Exception($this->image_lib->display_errors());
			}
			//缩放
			$props['width'] = LOGO_WIDTH;
			$props['height'] = LOGO_HEIGHT;
			$props['source_image'] = APP_LOGO_UPLOAD_PATH.$crop_logo;
			$props['new_image']    = APP_LOGO_UPLOAD_PATH.$resize_logo;
			$this->image_lib->initialize($props);
			if( !$this->image_lib->resize()){
				throw new Exception($this->image_lib->display_errors());
			}
			$url = $this->_generateUrl($resize_logo);
			
			//返回url
			return_json(0,'',array('src'=>$url));
		}catch(Exception $e){
			log_message('error',$e->getMessage());
			return_json(10000,$e->getMessage());
		}
	}
	
	/**
	 * 生成图片地址
	 * @param str $file_name 图片名称
	 */
	private function _generateUrl($file_name){
		return APP_LOGO_DOWNLOAD_URL.$file_name;
	}
}