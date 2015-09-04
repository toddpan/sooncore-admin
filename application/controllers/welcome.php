<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends Admin_Controller {
	
	public function __construct(){
		parent::__construct();
	}

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     *	- or -  
     * 		http://example.com/index.php/welcome/index
     *	- or -
     * Since this controller is set as the default controller in 
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function index()
    {
        redirect('login/loginPage');
        echo 'base_url = ' . base_url('') . '<br/>';//http://localhost/UCWebAdmin/
        echo 'site_url = ' . site_url('') . '<br/>';//http://localhost/UCWebAdmin/index.php
        echo 'current_url() = ' . current_url() . '<br/>';
        echo 'uri_string() = ' . uri_string() . '<br/>';
       // echo '自动生成链接anchor()=' . anchor('news/local/123', 'My News', 'title="News title"') . '<br/>';
        $this->load->view('welcome_message');
    }
    
    /*
     * boss 调试
    public function test(){
    	$this->load->library('BossLib', '', 'boss');
    	$this->load->model('account_model');
    	
    	$data = array(
    			'templateUUID' => 'saturday.quanshi.com',
    			'contractId' => 113237,
    			'components' => json_decode($this->account_model->getSitePower(667680), true),
    	);
    	
    	$ret = $this->boss->batchModifyContractComponentProps($data);
    	print_r($ret);
    }
    */
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */