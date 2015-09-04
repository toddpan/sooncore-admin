<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {
	
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
    
    public function test(){
    	$this->load->library('PortalLib', '', 'portal');
    	$src_url = "gnetis11.quanshi.com/devcloud";
    	$dest_url = "devcloud.quanshi.com/gnetis11";
    	
     	$ret = $this->portal->addRule($src_url, $dest_url);
     	print_r($ret);
    	
//     	$ret = $this->portal->searchRule($src_url);
//     	print_r($ret);
    	
//     	$ret = $this->portal->modifyRule(14,$src_url, 'www.baidu.com');
//     	print_r($ret);
    	
//     	$ret = $this->portal->delRule($src_url);
//     	print_r($ret);

//     	$pattern = '/^([0-9a-zA-Z-_]+)\.quanshi.com\/([0-9a-zA-Z-_]+)$/';
//     	$match = array();
//     	$subject = 'd-d.quanshi.com/aa';
//     	if(preg_match($pattern, $subject, $match)){
//     		echo 'yes';
//     		print_r($match);
//     	}else{
//     		echo 'no';
//     	}

    	
    	
    	
    	
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
    
    /*
    //短信发送test
    public function test(){
    	echo 'start to send message';
    	$this->load->library('UccLib', '', 'ucc');
    	$user_id = 61371839;
    	$content = 'a test';
    	$mobile  = '13601231924';
    	$this->ucc->sendMobileMsg($user_id, $content, $mobile);
    	echo 'done';
    }
    */
    
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */