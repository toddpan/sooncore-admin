<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @abstract 	register类，主要负责用户注册相关的操作
 * @filesource 	register.php
 * @author 		Lwbbn
 * @copyright 	Copyright (c) UC
 * @version 	v1.0
 */
class Register extends Web_Controller {
	
	/**
	 * @abstract 构造方法
	 */
	public function __construct() {
		parent::__construct();
		// 载入httpcurl辅助函数
		$this->load->helper('my_httpcurl');
		// 载入接口类库
		//$this->load->library('API', '', 'API');
		// 载入表单验证类
		$this->load->library('form_validation');
                //载入UMS
                $this->load->library('UmsLib','','ums');
                
	}
        
        public function test($customerCode){
                $CI =& get_instance();
                $CI->load->model('Boss_Account_Model');
                $boss_info = $CI->Boss_Account_Model->get_account($customerCode);
                var_dump($boss_info);
        }
        
	/**
	 * @abstract 显示注册页面
	 */
	public function index(){
		// 加载注册页面
		$this->assign('COMPANY_NAME', COMPANY_NAME);
		$this->assign('COMPANY_COPR', COMPANY_COPR);
		$this->assign('COMPANY_ICP', COMPANY_ICP);
		$this->assign('COMPANY_SERVER_TEL', COMPANY_SERVE_TEL);
                $this->assign('UC_NAME_EN',UC_NAME_EN);
                $step = $this->uri->segment(3) ? $this->uri->segment(3) : 1;
		$this->assign('REGISTER_STEP', $step);
                if($step==2){
                    if(strtolower(filter_input(INPUT_SERVER,REQUEST_METHOD))  == 'post'){
                        $user_email = $this->input->post('user_email');
                        $this->assign('USER_EMAIL',  $user_email);
                    }else{
                        //重定向浏览器 
                        header("Location: ".UC_DOMAIN_DIR."/register/index/1"); 
                        exit;
                    }
                }
		$this->display('register.tpl');
	}
	
        /**
         * 检查用户名是否存在于数据库中
         * 
         */
        public function checkLoginName(){
            $loginName = $this->input->post("loginName");
            $result = $this->ums->getUserByLoginName($loginName);
            if(is_array($result) && count($result)>0){
                return_json(1, "此帐号已存在！");//用户已存在于表中返回1
            }else{
                return_json(0, "恭喜此用户名可用！");//用户不存在于表中返回0
            }
        }

        /**
         * 检查站点是否存在于数据库中
         * 
         */
        public function checkUrl(){
            $siteUrl = $this->input->post("site_url");
            $result = $this->ums->getSiteInfoByUrl($siteUrl);
            if(is_array($result) && isset($result['id'])){
                return_json(1, "此网址已经存在于系统中！如您属于此公司员工请联系您的公司管理员进行注册");//已存在返回1
            }else{
                return_json(0, "欢迎体验我们的产品！");//不存在返回0
            }
        }


        /**
	 * @abstract 调用辅助函数生成验证码（不会生成图片）
	 */
	public function code() {
		// 载入captcha辅助函数
		$this->load->helper('captcha');
		
		// 调用辅助函数生成验证码
		$vals = array(
				'word_length' 	=> 	4,		//长度
				'img_width'   	=> 	'80',	//宽度
				'img_height'   	=> 	'33',	//宽度
                                'font_path'     => './system/fonts/3d.ttf'
		);
		$code = create_captcha($vals);
		
		// 将验证码保存到session中
		$this->session->set_userdata('reg_code', $code);
	}
	
	/**
	 * @abstract 验证表单提交的验证码是否正确
	 */
	public function valid_code($p_code='') {
		if(!$p_code){
                    // 获取表单提交的验证码
                    $reg_code = strtolower($this->input->post('checkCode', true));
                }  else {
                    $reg_code = strtolower($p_code);
                }
		log_message('debug', __FUNCTION__." input->\n" . var_export(array('$reg_code' => $reg_code), true));
		
		// 获取缓存中保存的验证码
		$code = strtolower($this->session->userdata('reg_code'));
		
		// 判断表单提交的验证码与缓存中保存的验证码是否相同
		if($reg_code != $code){
                    // 发送出错信息：验证码错误
                    return_json(1, "验证码错误！");
                }else{
                    //return_json(0, "验证通过！");
                    return true;
                }
	}
	
        //验证邮箱中接收的验证码
        public function validMailCode()
        {
            $code = md5($this->input->post('emailCode'));
            
            $codeTime = $this->input->cookie("EMAIL_CODE_TIME");
            
            $cookieCode = $this->input->cookie("EMAIL_CODE");
            
            if($codeTime && (time()-$codeTime)<5*60 && $cookieCode==$code)
            {
		log_message('debug', __FUNCTION__." input->验证通过");
                $this->input->set_cookie("EMAIL_CODE_TIME",'',-1);//使COOKIE过期
                $this->input->set_cookie("EMAIL_CODE",'',-1);
                return_json(0, '邮箱验证通过');
            }  else {
		log_message('error', __FUNCTION__." input->验证失败");
                return_json(1,'邮箱验证失败，请核实是否输入错误');
            }
        }
        
        //发送邮箱验证码
        public function sendMailCode()
	{
            //验证邮箱
            $to = $this->input->post('email');
            if($this->valid_email($to)==FALSE)
            {
                return_json(1, '邮箱格式不正确！请核对');
            }
            
            //效验图片验证码
            $pic_code = $this->valid_code($this->input->post('code'));
            if($pic_code['code']==1){
                return_json(1, '图片验证码不正确');
            }
            
            $title = '【'.UC_SENDER_NAME.'】给您发送的注册验证码';
            $randCode = rand(100000,999999);
            $content = '您好，感谢您体验【'.UC_NAME_EN.'】的产品。<br />您的验证码为【<font color=red><strong>'.$randCode.'</strong></font>】，有效期为五分钟。';
            $code = $this->sendEmail($to,$title,$content);
            if($code==1)
            {
                //设置COOKIE有效时间为5分钟
                $this->input->set_cookie("EMAIL_CODE_TIME",  time(),60*5);
                
                $this->input->set_cookie("EMAIL_CODE",  md5($randCode),60*5);
            }
            log_message('debug', __FUNCTION__." input->邮件已发送到您的邮箱，内容为：".$content);
            return_json(0, '<font color="#6bd7eb">邮件已发送到您的邮箱，请注意查收</font>',$code);
	}
	
        
        //发送邮件
	private function sendEmail($to,$title,$content)
	{
		$this->load->library('email');
		$this->email->from(UC_SENDER_ADDRESS,UC_SENDER_NAME);            //发送者，签名
		$this->email->to($to);					//接收者
		$this->email->subject($title);			//邮件主题
		$this->email->message($content); 
		//echo $this->email->print_debugger();		//邮件内容
		return $this->email->send();	//调试模式
	}
        
        //验证邮件格式
        public function valid_email($str)
        {
        return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
        }
        
        
        
	/**
	 * @abstract 接收页面的信息
	 */
	public function regSave() {
                $data = $this->input->post();
                
		// 获取表单提交的用户名
		$user_name = $data['login_name'];
                
		// 获得表单提交的密码
		$user_pwd  = $data['user_cfPwd'];
                
		log_message('info', __FUNCTION__." input->\n" . var_export($data, true));
		
		// 验证用户名和密码是否为空
		if(bn_is_empty($user_name) ||  bn_is_empty($user_pwd)){
			// 发送出错信息：用户名或密码不能为空
			form_json_msg(USERNAME_OR_USERPWD_ERROR, '', '用户名或密码不能为空');
		}
                
                $loginName = $data['login_name'];
                $customercode = strtoupper(substr(md5($loginName),8,16));//生成客户编码 通过将email进行md5后取16位（未用PHP自行生成的16位因为会乱码）并转为大写
                
                
                //==========================创建站点开始===========================
                //创建站点需要的数据
                $sites_info = array(
                    "url" => $data['site_url'],
                    "aliasUrl" => $data['site_url'],
                    "siteStatus" => 1,
                    "createTime" => time(),
                    "statusModifyTime" => time(),
                    "customerCode" => $customercode,
                    "siteType" => 0  // 0是用户site，1是公用site，2是测试site或运营公司site
                );

                //创建站点 得到站点ID
                $siteId = $this->ums->createSite($sites_info);
                
                // 验证$siteId 判断是否为空或返回的字符是否为数字
		if(bn_is_empty($siteId) || preg_match('/^\d+$/i',$siteId) != true){
			//form_json_msg(COMMON_FAILURE, '', '创建站点失败',$siteId);
		}
                log_message('debug',__FUNCTION__.'---'.__LINE__.'---input->\n'.var_export($siteId,true));
                //==========================创建站点结束===========================
		
                $contractId = time();
                //==========================在uc_site创建站点开始===========================
                // 载入站点模型
		$this->load->model('uc_site_model');
                // 在uc_site表中创建站点
		$uc_site_info = array(
			'siteID' => $siteId,
			'contractId' => $contractId,//$contractId,//合同ID
			'domain' => $data['site_url'],
			'companyType' 	=> COR_TYPE_FOCUS,
			'isLDAP' => NOT_LDAP,
			'customerCode' 	=> $customercode,
			'value' => '',
                        'createTime' => time()
		);
		$result = $this->uc_site_model->createSite($uc_site_info);
		if($result == false){
			form_json_msg(UC_CREATE_SITE_FAIL, '', '在UC创建站点失败',$result);
		}
                log_message('debug',__FUNCTION__.'---'.__LINE__.'---input->\n'.var_export($result,true));
                
                
                //==========================在uc_site创建站点结束===========================
                
                
                //==========================创建组织开始===========================
                //创建组织需要的数据
                $org_info = array(
                    "name" => $data['company_name'], //组织机构名称
                    "siturl" => $data['site_url'],   //组织机构网站即站点网址
                    "customercode" => $customercode,  //客户编码
                    "type" => ORG_COMPANY,    //企业
                    "countryCode" => "86",  //国家编码
                    "areaCode" => $data['area_code'],  //区号
                    "mobileNumber" => $data['mobile_number'],  //电话
                    "code" => $customercode    //组织编码
                );

                //通过UMS创建组织 得到组织ID
                $orgId = $this->ums->createOrganization($org_info);
                
                // 验证$orgId 判断是否为空或返回的字符是否为数字
		if(bn_is_empty($orgId) || preg_match('/^\d+$/i',$orgId) != true){
			form_json_msg(UMS_CREATE_ORGANIZATION_FAIL, '', '创建组织失败',$orgId);
		}
                log_message('debug',__FUNCTION__.'---'.__LINE__.'---input->\n'.var_export($orgId,true));
                
                //==========================创建组织结束===========================
                
                
                //==========================向uc_customer表添加记录开始===========================
                // 载入客户模型
		$this->load->model('uc_customer_model');
		$uc_customer_info = array(
			'siteId' => $siteId,
			'customerCode' => $customercode,
			'contractId' => $contractId,
			'name' => $data['company_name'],
			'value' => ''
		);
		$result = $this->uc_customer_model->add_customer($uc_customer_info);
		if($result == false){
                    form_json_msg(UC_CREATE_CUSTOMER_FAIL, '', '在uc创建客户失败',$result);// 在uc创建客户失败
		}
                log_message('debug',__FUNCTION__.'---'.__LINE__.'---input->\n'.var_export($result,true));
                //==========================向uc_customer表添加记录结束===========================
                
                
                //==========================向uc_area表中添加记录开始===========================
		// 载入地区模型
		$this->load->model('uc_area_model');	
		$uc_area_info = array(
			'customerCode' 	=> $customercode,
			'siteID' 		=> $siteId,
			'country'	 	=> '中国',//国家
			'area' 			=> '',//地区
			'address' 		=> ''//地址
		);
		$result = $this->uc_area_model->add_area($uc_area_info);
		if($result == false){
                    form_json_msg(UC_CREATE_AREA_FAIL, '', '在uc创建地址失败',$result);// 在uc创建地址失败
		}
                log_message('debug',__FUNCTION__.'---'.__LINE__.'---input->\n'.var_export($result,true));
                
                //==========================向uc_area表中添加记录结束===========================
                
                //==========================向UC_Account表中添加记录开始===========================
                $this->load->model('UC_Account_Model');
                //1、有记录则更新记录，没记录则新加；
                $select_field = 'id';
                $where_arr = array(
                    'id' => $contractId,//合同ID
                    'customercode' => $customercode,//客户编码
                    'org_id' => $orgId//组织id
                );
                $modify_arr = array(
                    'id' => $contractId,//合同ID
                    'account_name' => $data['login_name'],//分账名称
                    'customercode' => $customercode,//客户编码
                    'org_id' => $orgId, //组织id
                    'site_id' => $siteId //组织id
                );
                $insert_arr = $modify_arr;
                $re_num = $this-> UC_Account_Model -> updata_or_insert(1,$select_field,$where_arr,$modify_arr,$insert_arr);
                if($re_num == -2 || $re_num == -4){//失败 -1 更新记录成功 -2更新操作失败 -3新加的记录id 新加记录成功 -4新加操作失败 -5 没有操作
                        $err_msg = 'update/insert  UC_Account_Model fail. $re_num =' . $re_num . ' ';
                        log_message('error', $err_msg);
                }else{
                        log_message('info', 'update/insert  UC_Account_Model success. $re_num =' . $re_num . ' .');
                }
                
                log_message('debug',__FUNCTION__.'---'.__LINE__.'---input->\n'.var_export($re_num,true));
                
                //==========================向UC_Account表中添加记录结束===========================
                
                //==========================向UMS创建用户开始===========================
                // 向UMS创建用户
		$others_info = array(
			'firstName' 		=> $data['display_name'],//$data['first_name'],
			'lastName' 		=> '',//$data['last_name'],
                        'password'              => md5($data['user_pwd']),
                        'passType'              => 1, //密码类型0=明文，1=MD5,2=crypt,3=ssha,4=MD5*3
			'email' 		=> $data['user_email'],
			'userstatus' 		=> UMS_USER_IS_OPEN,
			'sex' 			=> NOT_SET, 	//未设置
			'mobileNumber' 		=> '',//$data['telephone'],
			'organizationId' 	=> $orgId,
			'register' 		=> true,
			'registertime' 		=> time(),
			'position' 		=> $data['position'],
			'displayName' 		=> $data['display_name'],
			'lastUpdateTime' 	=> time(),
			'officePhone' 		=> $data['mobile_number'],
			'createTime' 		=> time()
			
		);
		$user_id = $this->ums->createUser($data['login_name'], $others_info);
		if($user_id == false){
                    form_json_msg(UMS_CREATE_USER_FAIL, '', '在UMS创建用户失败',$user_id);// 在UMS创建用户失败
		}
                log_message('debug',__FUNCTION__.'---'.__LINE__.'---input->\n'.var_export($user_id,true));
                
                //==========================向UMS创建用户结束===========================
		
                //==========================uc创建user开始===========================
		// uc创建user
		$this->load->model('UC_User_Model');
		$uc_user_info = array(
			'userID' 		=> $user_id,
			'siteId' 		=> $siteId,
			'customerCode'          => $customercode,
			'accountId'             => $contractId,
			'status' 		=> UC_USER_STATUS_ENABLE,
			'create_time'           => time(),
			'update_time'           => time()	
		);
		$res = $this->UC_User_Model->createUser($uc_user_info);
		if(!$res){
			form_json_msg(UC_CREATE_USER_FAIL, '', '在UC创建用户失败',$res);// 在UC创建用户失败
		}
                log_message('debug',__FUNCTION__.'---'.__LINE__.'---input->\n'.var_export($res,true));
                
                //==========================uc创建user结束===========================
                
                
                //==========================向UC_User_Admin表中添加记录开始===========================
                
                // 向UC创建管理员（type：总公司管理员，role不用管）
		$this->load->model('UC_User_Admin_Model');
		$user_admin_info = array(
			'userID' 		=> $user_id,
			'siteID' 		=> $siteId,
			'orgID' 		=> $orgId,
			'isLDAP' 		=> NOT_LDAP,
			'type' 			=> ADMIN_COMPANY_MANAGER,
			'createTime' 	=> time(),
			'display_name' 	=> $data['display_name'],
			'login_name' 	=> $data['login_name'],
			'mobile_number' => ''
		);
		$result = $this->UC_User_Admin_Model->create_admin($user_admin_info);
		if(!$result){
                    form_json_msg(UC_CREATE_ADMIN_FAIL, '', '在UC创建管理员失败',$result);// 在UC创建管理员失败
		}
                log_message('debug',__FUNCTION__.'---'.__LINE__.'---input->\n'.var_export($result,true));
                
                //==========================向UC_User_Admin表中添加记录结束===========================
                
                
                //==========================向uc_user_admin_role表中添加记录开始===========================
                
                $this->load->model('Uc_User_Admin_Role_Model');
		$admin_role_info = array(
			'user_id' => $user_id,
			'role_id' => SYSTEM_MANAGER,//角色
			'state' => ADMIN_OPEN,//状态
                        'create_time' => '2015-08-29 23:38:22'
		);
		$result = $this->Uc_User_Admin_Role_Model->saveManager($admin_role_info);
		if(!$result){
                    form_json_msg(UC_CREATE_ADMIN_FAIL, '', '在UC创建管理员失败',$result);// 在UC创建管理员失败
		}
                log_message('debug',__FUNCTION__.'---'.__LINE__.'---input->\n'.var_export($result,true));
                
                //==========================向uc_user_admin_role表中添加记录结束===========================
                
                
                
                //==========================分配集群开始===========================
                // 分配集群
		include_once APPPATH.'libraries'.DIRECTORY_SEPARATOR.'Account'.DIRECTORY_SEPARATOR.'Common'.DIRECTORY_SEPARATOR.'Cluster.php';
		$cluster = new Cluster($customercode, FILIALE_USER_AMOUNT);
		if(!$cluster->isAllocated($customercode, $siteId)){//判断该客户是否分配过集群
			$cluster_info = $cluster->getCluster();//获取一个集群
			if(!$cluster_info){
                            form_json_msg(CREATE_CLUSTER_FAIL, '', '分配集群失败');// 分配集群失败
                        }
                        log_message('debug',__FUNCTION__.'---'.__LINE__.'---input->\n'.var_export($cluster_info,true));
                
			
			// 将获取到的集群与客户的关联关系写入到数据库、更新相应集群中的用户量
			list($ret, $msg) = $cluster->allocate($customercode, $siteId, $data['site_url'], FILIALE_USER_AMOUNT, $cluster_info['clusterID']);
			if(!$ret){
				form_json_msg(SAVE_CLUSTER_FAIL, '', '保存集群失败');// 保存集群失败
                        }
                        log_message('debug',__FUNCTION__.'---'.__LINE__.'---input->\n'.var_export($ret,true));
		}
                //==========================分配集群结束===========================
                
                //=========================设置组织根部门管理员=====================
                //把注册用户（管理员）加入根组织
                $result = $this->ums->addUserToOrg($user_id, $orgId);//将已有用户添加到组织下
                if($result==false){
                    log_message('error', __FUNCTION__.'---'.__LINE__.'将员工'.$data['login_name'].'添加到组织失败');
                    form_json_msg(UC_CREATE_ADMIN_FAIL, '', '将'.$data['login_name'].'添加到组织失败',$result);
                }
                //把用户设置为根组织部门管理员
                $this->load->library('OrganizeLib','','OrganizeLib');
                
		$in_arr = array(
                    'org_id' => $orgId,//组织id
                    'site_id' => $siteId,//站点id 
                    'user_id' => $user_id,//用户id
                    'isset' => 1,//0取消，1设置修改
		);
		$sys_arr = $this->p_sys_arr;

		$operate_boolean = $this->OrganizeLib->modify_manager($in_arr,$sys_arr);
                if($operate_boolean == false){
                    log_message('info', __FUNCTION__.'---'.__LINE__.'将员工设置为组织根部门管理员失败');
		}
                //=======================设置组织根部门管理员结束=====================
                
                //==========================调用UMS开通产品开始===========================
                
                
                //通过UMS开通产品
                $result = $this->ums->setUserProduct($siteId, $user_id, UC_PRODUCT_ID, UC_PRODUCT_OPEN_STATUS);
                
                // 验证$orgId 判断是否为空或返回的字符是否为数字
		if(bn_is_empty($result) || preg_match('/^\d+$/i',$result) != true){
			form_json_msg(1, '', '开通产品失败',$result);
		}
                log_message('debug',__FUNCTION__.'---'.__LINE__.'---input->\n'.var_export($result,true));
                //==========================调用UMS开通产品结束===========================
                
		$this->load->library('UccLib', '', 'ucc');
                #向ucc创建站点交换机
		log_message('info', __FUNCTION__.'---'.__LINE__.'---input->\n start to create site exchange.');
		if( ! $this->ucc->createSiteExchange($siteId)){
			return array(false, 'create site exchange in ucc failed');
		}
		log_message('info', __FUNCTION__.'---'.__LINE__.'---input->\n creating site exchange finished.');
		
		#向ucc分配mq集群
		log_message('info', 'start to dispatch mq cluster.');
		if( ! $this->ucc->mqDispatch($siteId, FILIALE_USER_AMOUNT)){
			return array(false, 'dispatch mq cluster in ucc failed');
		}
		log_message('info', __FUNCTION__.'---'.__LINE__.'---input->\n dispatching mq cluster finished.');
		
                return_json(0, '用户创建成功',$user_id);
                
	}
}
