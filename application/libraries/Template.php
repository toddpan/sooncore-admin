<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'third_party/Smarty-3.1.18/libs/Smarty.class.php');


class Template extends Smarty{
	
	public function __construct(){
		
		parent::__construct();
		
		$CI = & get_instance();
		$CI->load->config('smarty');//加载smarty的配置文件
		
		//设置相关的配置项
		$this->setTemplateDir($CI->config->item('template_dir'));
		$this->setCompileDir($CI->config->item('compile_dir'));
		$this->setConfigDir($CI->config->item('config_dir'));
		$this->setCacheDir($CI->config->item('caching'));
		$this->left_delimiter  = $CI->config->item('left_delimiter');
		$this->right_delimiter = $CI->config->item('right_delimiter');
		$this->caching = $CI->config->item('caching');
		$this->cache_lifetime  = $CI->config->item('lefttime');
		
	}
}
