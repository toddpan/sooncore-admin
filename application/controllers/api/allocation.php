<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @abstract	Allocation ，负责分配客户路由，将客户平均分配到各个集群中。
 * @filesource	allocation.php
 * @author		jingchaoSun <jingchao.sun@quanshi.com>
 * @copyright	Copyright (c) UC
 * @version		v1.0
 */
class Allocation extends Domain_Controller{
	/**
	 * @abstract 构造函数
	 */
	public function __construct() {
		parent::__construct();
		$this->load->helper('my_publicfun');
		$this->load->helper('my_dgmdate');
		$this->load->library('API', '', 'API');
		log_message('info', 'into class ' . __CLASS__ . '.');
	}

	/**
	 * @abstract 创建客户;平台开通接收接口
	 * @details
	 * -# 1、BOSS向特定的域名（BOSS通过HOST指定）回写UC开通数据
	 * -# 2、调用UC接口时，
	 * -#    首先通过IP访问UC域分配模块，然后此模块根据用户量将分配客户到某个特定的域，
	 * -#    并CLI调用该域的开通接口，
	 * -#    接口调用成功后，将更新该域对应的用户量及增加域分配情况保存到路由表。
	 * @return  失败HTTP/1.1 400 Bad Request；  成功 200
	 */
	public function createAccount() {
		// 获得BOSS post过来的数据
		$BOSS_post_json = api_get_post();
           
		log_message('info', __FUNCTION__." input->\n".var_export(array('BOSS_post_json' => $BOSS_post_json), true));

		// 判断BOSS post过来的数据是否为空
		if(bn_is_empty($BOSS_post_json)){
			$err_msg = 'Get BOSS post json fail.';
			log_message('error', $err_msg);
			echo api_json_msg(-1, array('msg' => $err_msg), 1);
			https(400);
			exit;
		}

		// 将BOSS post过来的数据转换转换成数组
		$BOSS_post_arr = json_decode($BOSS_post_json, true );

		// 判断已转换的数组是否为空
		if(isemptyArray($BOSS_post_arr)){
			$err_msg = 'BOSS post json to array fail.';
			log_message('error', $err_msg);
			echo api_json_msg(-1, array('msg' => $err_msg), 1);
			https(400);
			exit;
		}

		// 获得操作类型
		$boss_type = isset($BOSS_post_arr['type'])?$BOSS_post_arr['type']:'';

		// 判断操作类型是否为空
		if(bn_is_empty($boss_type)){
			$err_msg = 'get post param type not is  create.';
			log_message('error', $err_msg);
			echo api_json_msg(-1, array('msg' => $err_msg), 1);
			https(400);
			exit;
		}

		// 获得客户编码
		$customerCode = isset($BOSS_post_arr['customer']['customerCode'])?$BOSS_post_arr['customer']['customerCode']:'';

		// 判断客户编码是否为空
		if(bn_is_empty($customerCode)){
			$err_msg = 'get post param customerCode is empty.';
			log_message('error', $err_msg);
			echo api_json_msg(-1,array('msg' => $err_msg), 1);
			https(400);
			exit;
		}

		// 获得产品id
		$product_id = UC_PRODUCT_ID;

		// 判断产品id是否为空
		if(bn_is_empty($product_id)){
			$err_msg = 'get post param product_id is empty.';
			log_message('error', $err_msg);
			echo api_json_msg(-1, array('msg' => $err_msg), 1);
			https(400);
			exit;
		}
			
		$siteURL = '';
		$siteURL = isset($BOSS_post_arr['customer']['contract']['resource']['siteURL'])?$BOSS_post_arr['customer']['contract']['resource']['siteURL']:'' ;
		// 判断站点URL是否为空
		if(bn_is_empty($siteURL)){
			$err_msg = ' get post param siteURL is empty.';
			log_message('error', $err_msg);
			echo api_json_msg(-1,array('msg' => $err_msg) , 1);
			https(400);
			exit;
		}
		// 站点属性
		$components_arr = $BOSS_post_arr['customer']['contract']['components'];

		// 看是否有user，如果没有则callback
		$users_arr = isset($BOSS_post_arr['customer']['users'])?$BOSS_post_arr['customer']['users']:'' ;
		// 用户开通类型：0普通用户开通；1管理员开通
		$uc_auth = 0;

		// 注意，合同开通时用户数量分配域，但是帐号开通时，没有用户数量，不用分配域
		// 用户数量
		$site_user_count = 0;

		// 有users节点,则不是合同开通，是用户开通
		if(!isemptyArray($users_arr)){
			foreach($components_arr as $k => $v){
				if(is_array($v)){
					$ns_name 		= isset($v['name'])?$v['name']:'';
					// 转换为小写
					$ns_name_lower 	= strtolower($ns_name);
					if($ns_name_lower == 'uc'){
						$uc_auth 		 = isset($v['property']['auth'])?$v['property']['auth']:0 ;
						//获得当前站点用户数量
						$site_user_count = isset($v['property']['size'])?$v['property']['size']:0;
						break;
					}
				}
			}
		}
		log_message('info', 'get  property auth $uc_auth=' . $uc_auth . '  success.');
			
		// 判断用户数量是否为空
		if(bn_is_empty($site_user_count)){
			$err_msg = ' get post param  uc property size is empty.';
			log_message('error', $err_msg);
			echo api_json_msg(-1, array('msg' => $err_msg), 1);
			https(400);
			exit;
		}

		//获得操作类型
		$open_type 	= 1;	// 1新建账号；2修改账号；3停用账号；4启用账号；5删卡
		$open_class = 1;	// 账号类型：1合同；2帐号

		// 如果有users节点,则不是合同开通，是用户开通
		if(!isemptyArray($users_arr)){
			$open_class = 2;	// 账号类型：1合同；2帐号
		}

		switch (strtolower($boss_type)) {
			case 'create':	// 1新建账号
				$open_type = 1;
				break;
			case 'update':	// 2修改账号
				$open_type = 2;
				break;
			case 'disable':	// 3停用账号
				$open_type = 3;
				break;
			case 'enable':	// 4 启用账号
				$open_type = 4;
				break;
			case 'delete':	// 5删卡
				$open_type = 5;
				break;
			default:
				$open_type = 1;
				break;
		}
		log_message('info', 'get  $open_class=' . $open_class . '  $open_type=' . $open_type . '  success.');

		// 分配域；判断是否需要分配域，并使相同客户编码分配到一个域
		$allot_domain_arr = $this->is_need_allot_domain($customerCode);
		$domain_arr = isset($allot_domain_arr['domain'])?$allot_domain_arr['domain']:array();
		$allot_arr = isset($allot_domain_arr['cluster'])?$allot_domain_arr['cluster']:array();

		// 判断域是否为空数组，如果是，则需要重新分配域
		if(isemptyArray($allot_arr)){
			$allot_arr = $this->allot_domain($site_user_count);// 需要分配域信息数组
		}

		$URL = isset($allot_arr['url'])?$allot_arr['url']:'';// 获得域url

		if(!bn_is_empty($URL)){//url不为空
			log_message('debug', 'param $URL has value.');
			//调用UC线程接口
			//调用分配域的站点的开通消息接口[将消息保存数据库]
			//接口参数
			$data = 'type=1&value=' . $BOSS_post_json;
				
			$uc_thread_arr = $this->API->UCAPI($data, 1, array('url' => $URL ));
			if(api_operate_fail($uc_thread_arr)){// 失败
				$err_msg = 'UCAPI NO 1 is fail.';
				log_message('error', $err_msg);
				echo api_json_msg(-1,array('msg' => $err_msg) , 1);
				https(400);
				exit;
			}else{ // 成功
				log_message('debug', 'UCAPI NO 1 is success.');
			}
		}else{// url为空
			$err_msg = 'param ' . $URL . ' is empty.';
			log_message('error', $err_msg);
			echo api_json_msg(-1,array('msg' => $err_msg) , 1);
			https(400);
			exit;
		}

		if($open_class == 1){// 合同开通时，才保存开通的域 .操作类型1合同2帐号
			//站点id
			$siteID = 0;
				
			//通过站点URL从UMS中精确查询站点
			$uc_site_arr = $this->API->UMS_Special_API('',3,array('url' => $siteURL));
				
			if(api_operate_fail($uc_site_arr)){//失败
				$err_msg = ' usm api rs/sites?url is empty .';
				log_message('error', $err_msg);
				echo api_json_msg(-1,array('msg' => $err_msg) , 1);
				https(400);
				exit;
			}else{ // 成功
				$siteID = isset($uc_site_arr['id'])?$uc_site_arr['id']:'';
			}
				
			// 判断站点id是否为空
			if(bn_is_empty($siteID)){
				$err_msg = 'get post param siteURL is empty .';
				log_message('error', $err_msg);
				echo api_json_msg(-1,array('msg' => $err_msg) , 1);
				https(400);
				exit;
			}

			//TODO 调用Portal的接口
			//接口参参数//注意：Portal的接口中的url : uc_cluster_user_num中的域的url字段+ 客户的url 的前面部分：如：abc.quanshi.com,则拿到abc;
			// TODO 对URL进行解析

			//$str = 'abc.quanshi.com';获处abc
			//preg_match_all("/^(http:\/\/|https:\/\/|ftps:\/\/)?([^\.]+)\.(.+)$/i", $siteURL, $matches);
			//$path_name = isset($matches[2][0])?$matches[2][0]:''; //通过正则，获得abc

			//TODO quanshi.com 改成配置文件
			/*
			 $path_name = str_ireplace('.quanshi.com' , '', $siteURL); //Ge.quanshi.com 要拿到 Ge
			 $web_url = $URL . '/' . $path_name;// 需要跳转到的URL test.quanshi.com/abc
			 $data = array(
			 'siteUrl' => $siteURL . '/' . UC_ENVIRONMENT, //ibm.quanshi.com/devcloud
			 'skipUrl' => $web_url,//uc1.quanshi.com/ibm 域的url
			 'checkCode' => UC_CHECK_CODE
			 	
			 );
			 $Portal_arr = $this->API->PortalAPI(json_encode($data),1);//0：成功；-1：失败
			 if(api_operate_fail($Portal_arr)){//失败
			 log_message('error', '$porter API  fail.');
			 https(400);
			 exit;
			 }else{
			 log_message('debug', '$porter API  success.');
			 }
			 *
			 */

			//保存分配的域
			$save_domain_arr = array(
                'cluster' 			=> $allot_arr,		// 域信息 ，不能为空,肯定有值
                'domain' 			=> $domain_arr,		// 分配的域信息数组 可能为空;新加需要更新cluster数量，更新，则不更新cluster数量
                'site_user_count' 	=> $site_user_count,// 当前站点用户数量
                'siteid' 			=> $siteID,			// 当前站点的siteid
                'siteURL' 			=> $siteURL,		// 当前站点的url
                'customerCode' 		=> $customerCode	// 当前客户编码
			);
			if($this->save_allot_domain($save_domain_arr) == 0){//0失败；非0成功
				$err_msg = 'save_allot_domain fail.';
				log_message('error', $err_msg);
				echo api_json_msg(-1,array('msg' => $err_msg) , 1);
				https(400);
				exit;
			}
		}
		log_message('info', 'out method ' . __FUNCTION__ . '.');
	}

	/**
	 * @brief 根据客户编码，获得域信息
	 * @details
	 * @param int $customerCode 客户编码
	 * @param int $siteid 站点id[可以不传]
	 * @return  输出以下数组的json串
	 Array
	 (
	 [code] => 0
	 [error_id] =>
	 [prompt_text] => $domain_cluster_arr success
	 [other_msg] => Array
	 (
	 [data] => Array
	 (
	 [cluster] => Array
	 (
	 [id] => 10
	 [clusterID] => 2
	 [ip] => 192.168.35.155
	 [url] => devcloud.quanshi.com
	 [userAmount] => 12000
	 [topLimit] => 250000
	 )

	 [domain] => Array
	 (
	 [id] => 61
	 [siteID] => 666897
	 [URL] => xianUC.quanshi.com
	 [customerCode] => 024014
	 [clusterID] => 2
	 [userAmount] => 0
	 )

	 )

	 )

	 )
	 */
	public function get_cluster() {
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$customerCode = $this->input->post('customer_code', TRUE);//BOSS传递过来的数据;如果是批量导入，数据

		if(bn_is_empty($customerCode)){
			log_message('error', 'post param  $customerCode is empty.');
			form_json_msg('1','','post param  $customerCode is empty',array());//返回错误信息json格式
		}
		log_message('debug', 'post param  $customerCode = ' . $customerCode . '  empty.');
		$siteID = $this->input->post('siteid', TRUE);//BOSS传递过来的数据;如果是批量导入，数据
		log_message('debug', 'post param  $siteID = ' . $siteID . '  empty.');
		$domain_cluster_arr = $this->get_domain_bycustomercode($customerCode,$siteID);
		if(isemptyArray($domain_cluster_arr)){//如果没有数据
			form_json_msg('1','','$domain_cluster_arr isemptyArray',array());//返回错误信息json格式
		}
		$cluster_arr = isset($domain_cluster_arr['cluster'])?$domain_cluster_arr['cluster']:array();
		if(isemptyArray($cluster_arr)){//如果没有分配的域
			form_json_msg('1','','$cluster_arr isemptyArray',array());//返回错误信息json格式
		}
		$domain_arr = isset($domain_cluster_arr['domain'])?$domain_cluster_arr['domain']:array();
		if(isemptyArray($cluster_arr)){//如果没有分配的域
			form_json_msg('1','','$domain_arr isemptyArray ',array());//返回错误信息json格式
		}
		form_json_msg('0','','$domain_cluster_arr success ',array('data' => $domain_cluster_arr));//返回信息json格式
	}
	/**
	 * @brief 根据客户编码或站点id获得分配域
	 * @details
	 * @param int $customerCode 客户编码
	 * @param int $siteid 站点id[可以不传]
	 * @return array
	 * array(
	 *  'cluster' => array(),域信息
	 *  'domain' => array(),分配的域信息数组
	 *
	 */
	public function get_domain_bycustomercode($customerCode = 0,$siteid = 0){
		$re_array = array(
           'cluster' => array(),//域信息
           'domain' => array(),//分配的域信息数组
		);
			
		//载入路由表uc_domain_router
		$this->load->model('UC_Domain_Router_Model');
		$this->load->model('UC_Cluster_User_Num_Model');
		//根据该客户所属的客户编码customerCode，在UC域分配模块中的路由表uc_domain_router 中查询是否有记录
		$where_arr = array('customerCode =' => $customerCode);
		if($siteid > 0){
			$where_arr['siteID'] = $siteid ;
		}
		$data = array(
		//'select' =>'clusterID',
            'where' => $where_arr
		);
		$router_arr = $this->UC_Domain_Router_Model->operateDB(1,$data);
		$is_add_new_domain = 1;//是否重新分配域0没有,1是,默认重新分配
		// $userAmount = 0;//初始化当前域有的用户数
		if(!isemptyArray($router_arr)){//如果有记录则：拿该客户的域URL
			$re_array['domain'] = $router_arr;//域信息
			$clusterID = $router_arr['clusterID'];//通过clusterID,再从uc_cluster_user_num表,拿到url
			if(!bn_is_empty($clusterID)){
				if($clusterID > 0){
					$data = array(
					// 'select' =>'url',
                        'where' => array('clusterID =' => $clusterID),
					);
					$sel_arr = $this->UC_Cluster_User_Num_Model->operateDB(1,$data);
					if(!isemptyArray($sel_arr)){//如果有记录则：拿该客户的域URL
						$re_array['cluster'] = $sel_arr;//分配的域信息数组
						//$URL = $sel_arr['url'];//集群的URL
						//if(!bn_is_empty($URL)){//有记录
						$is_add_new_domain = 0;//是否重新分配域0没有,1是
						// }
					}
				}
			}

			if($is_add_new_domain == 0)//$is_add_new_domain = 0;//是否重新分配域0没有,1是
			log_message('debug', 'get url from UC_Domain_Router  success.');
			else{
				log_message('debug', 'get url from UC_Domain_Router  fail.');
			}
		}
		return $re_array;
	}
	/**
	 * @brief 创建正式客户路由
	 * @details
	 * -# 分配客户所在的集群
	 * -## 规则1：根据开通客户时传递的用户数来选择集群。每个集群的数量不能超过10万（可配置）；
	 * -## 规则2：顺序去选择集群。
	 * -# 调用所在集群的开通接口
	 * -# 更新数据库中本集群的人数，更新路由表。
	 * -# 调用Portal接口，将SiteID，产品ID，集群的URL给Portal接口中。
	 * @return 0:失败；1：成功。
	 */
	public function createCustomer(){

	}

	/**
	 * @brief 展示【建立您的全时sooncore平台帐号以及使用者帐号】页面
	 */
	public function showTrialPage(){

	}

	/*
	 * @brief 验证站点和账号是否存在
	 * @details
	 * -# 试用客户从官网来的数据信息有UserID/邮箱/公司名称，系统默认的使用用户数为300。
	 * -# 选择路由，然后到指定的UCC中。（不做路由表的保存）
	 * @return 0:失败；1：成功
	 */
	public function isExistUser(){
		$data = $_REQUEST['userID'];//请求的用户ID
		$data = $_REQUEST['Email'];//请求的邮箱
	}

	/**
	 * @brief 创建试用客户，并开通路由
	 * @details
	 * -# 根据用户数选择路由
	 * -# 调用该集群中开通模块的【开通试用客户】接口
	 * -# 如果返回为成功，则成功信息包括客户编码、URL、站点ID等3个信息
	 * -# 更新本集群的路由表
	 * -# 调用Portal接口，将SiteID，产品ID、集群的URL给Portal接口中
	 */
	public function createTrialCustomer(){
	}

	/**
	 * @brief 选择路由，校验哪个集群的服务器不满
	 * @details
	 * -# 选择路由的规则：
	 * <p>选择第一个集群满足如下条件：</p>
	 * -## (该集群的用户数  + 客户的用户量(300) ) <= 10万 （每个集群的上限，该值存在数据表中） * 80%
	 */
	private function selectRouter($num = 300){

	}

	/**
	 * @brief 发送手机短信
	 * @details
	 * -# 随机生成客户的短信验证码（仅包含6位随机数字即可）
	 * -# 发送短信
	 * -# 将该短信验证码保留到Session中，等待客户验证
	 */
	public function sendPhoneMsg(){

	}
	/**
	 * @brief 是否需要重新分配域
	 * @details
	 * @param int $customerCode 客户编码
	 * @return array
	 * array(
	 *  'cluster' => array(),域信息
	 *  'domain' => array(),分配的域信息数组
	 *
	 */
	public function is_need_allot_domain($customerCode){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$re_array = array(
           'cluster' => array(),//域信息
           'domain' => array(),//分配的域信息数组
		);
		//载入路由表uc_domain_router
		$this->load->model('UC_Domain_Router_Model');
		$this->load->model('UC_Cluster_User_Num_Model');
		//根据该客户所属的客户编码customerCode，在UC域分配模块中的路由表uc_domain_router 中查询是否有记录
		$data = array(
		//'select' =>'clusterID',
            'where' => array('customerCode =' => $customerCode)
		);
		$router_arr = $this->UC_Domain_Router_Model->operateDB(1,$data);
		$is_add_new_domain = 1;//是否重新分配域0没有,1是,默认重新分配
		// $userAmount = 0;//初始化当前域有的用户数
		if(!isemptyArray($router_arr)){//如果有记录则：拿该客户的域URL
			$re_array['domain'] = $router_arr;//域信息
			$clusterID = $router_arr['clusterID'];//通过clusterID,再从uc_cluster_user_num表,拿到url
			if(!bn_is_empty($clusterID)){
				if($clusterID > 0){
					$data = array(
					// 'select' =>'url',
                        'where' => array('clusterID =' => $clusterID),
					);
					$sel_arr = $this->UC_Cluster_User_Num_Model->operateDB(1,$data);
					if(!isemptyArray($sel_arr)){//如果有记录则：拿该客户的域URL
						$re_array['cluster'] = $sel_arr;//分配的域信息数组
						//$URL = $sel_arr['url'];//集群的URL
						//if(!bn_is_empty($URL)){//有记录
						$is_add_new_domain = 0;//是否重新分配域0没有,1是
						// }
					}
				}
			}

			if($is_add_new_domain == 0)//$is_add_new_domain = 0;//是否重新分配域0没有,1是
			log_message('debug', 'get url from UC_Domain_Router  success.');
			else{
				log_message('debug', 'get url from UC_Domain_Router  fail.');
			}
		}
		log_message('info', 'out method ' . __FUNCTION__ . '.');
		return $re_array;
	}

	/**
	 * @brief 全新分配域
	 * @details
	 * @param int $site_user_count 当前站点的用户量
	 * @return array 域信息数组 ，没有分配成功，则返回空数组
	 *
	 */
	public function allot_domain($site_user_count){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$re_arr = array();
		//载入路由表uc_domain_router
		$this->load->model('UC_Domain_Router_Model');
		//$this->load->model('UC_Cluster_User_Num_Model');
		//如果没有记录，根据当前站点的用户数量来通过域集群uc_cluster_user_num去分配一个域：
		//分配域的规则:该集群现有的用户总量+当前站点的用户数量<该集群的上限阀值 最接近的一个
		$data = array(
		//'select' =>'clusterID,id,url,ip,userAmount',
            'where' => '(userAmount +  ' . $site_user_count . ') <= (topLimit * ' . DOMAIN_USER_NUM_RATE . ')',//常量为比率如:0.8写到配值文件
            'order_by' => 'id asc'
            );
            $cluster_user_arr = $this->UC_Cluster_User_Num_Model->operateDB(1,$data);
            if(!isemptyArray($cluster_user_arr)){//分配成功
            	$re_arr = $cluster_user_arr;
            }else{//没有域可分配 error;会选择数量是最少的一个来分配,用min()的来做，打一个最高级别的fail
            	log_message('error', 'allocation UC_Cluster_User_Num  fail.');
            	//获得最少的一个
            	$data_min = array(
            	//'select' =>'clusterID,id,url,ip',
                'select_min' => array(
            	array(
                        'field' => 'userAmount',//字段名
                        'alias' => 'userAmount_num'//别名
            	)
            	),
                'where' => '(userAmount +  ' . $site_user_count . ') <= topLimit'
                );
                $cluster_min_arr =  $this->UC_Cluster_User_Num_Model->operateDB(1,$data_min);
                if(!isemptyArray($cluster_min_arr)){//分配成功
                	$re_arr = $cluster_min_arr;
                	log_message('debug', 'allocation UC_Cluster_User_Num min(userAmount)  success.');
                }else{
                	log_message('error', 'allocation UC_Cluster_User_Num min(userAmount)  fail.');
                	// https(400);
                	// exit;
                }
            }
            log_message('info', 'out method ' . __FUNCTION__ . '.');
            return $re_arr;
	}

	/**
	 * @brief 向数据库更新分配域
	 * @details
	 * @param array $in_arr 传入参数
	 * array(
	 *  'cluster' => array(),域信息 ，不能为空,肯定有值
	 *  'domain' => array(),分配的域信息数组 可能为空;新加需要更新域分配表cluster数量，更新，则不更新域分配表cluster数量
	 *  'site_user_count' => $site_user_count,//当前站点用户数量
	 *  'siteid' =>,//当前站点的siteid
	 *  'siteURL' => //当前站点的url
	 *  'customerCode' => //当前客户编码
	 * @return int 0失败 非0成功
	 */
	public function save_allot_domain($in_arr){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		//载入路由表uc_domain_router
		$this->load->model('UC_Domain_Router_Model');
		$this->load->model('UC_Cluster_User_Num_Model');
		$allot_arr = isset($in_arr['cluster'])?$in_arr['cluster']:array();
		if(isemptyArray($allot_arr)){//如果是空数组,返回错误
			return 0;
		}
		$site_user_count = isset($in_arr['site_user_count'])?$in_arr['site_user_count']:'';//当前站点用户数量
		$siteid = isset($in_arr['siteid'])?$in_arr['siteid']:0;//当前站点的siteid
		$siteURL = isset($in_arr['siteURL'])?$in_arr['siteURL']:'';//当前站点的url
		$customerCode = isset($in_arr['customerCode'])?$in_arr['customerCode']:'';//当前客户编码
		$userAmount = isset($allot_arr['userAmount'])?$allot_arr['userAmount']:0;//当前分配域的用户数量
		$domain_id = isset($allot_arr['id'])?$allot_arr['id']:0;
		$uc_clusterID = isset($allot_arr['clusterID'])?$allot_arr['clusterID']:0;//域的id
			
		$domain_arr = isset($in_arr['domain'])?$in_arr['domain']:array();//当前分配的配信息
		if(!isemptyArray($domain_arr)){//不为空，则需要看没有没相同的
			$data = array(
			//'select' =>'clusterID',
                'where' => array('customerCode =' => $customerCode,'siteID =' => $siteid)
			);
			$router_arr = $this->UC_Domain_Router_Model->operateDB(1,$data);
			if(!isemptyArray($router_arr)){//不为空，则需要看没有没相同的
				$domain_arr = $router_arr;
			}
		}
		$domain_siteID = isset($domain_arr['siteID'])?$domain_arr['siteID']:'';//当前分配的域的siteId
		$domain_customerCode = isset($domain_arr['customerCode'])?$domain_arr['customerCode']:'';//当前分配的域的customerCode

		$domain_operate = 1;//对域信息表的操作类型1新加2更新
		if(isemptyArray($domain_arr)){//如果是空数组
			$domain_operate = 1;
		}else{//如果不需要重新分配域,需要siteid + customercode 判断一下，是否存在,存在没不用新加域
			if( ($siteid == $domain_siteID) && ($domain_customerCode == $customerCode)){//相同站点，则更新
				$domain_operate = 2;
			}else{//不同站点，则新加
				$domain_operate = 1;
			}
		}
			
		//更新域表对应的用户量,
		$cluster_data = array(
            'update_data' => array(
            'userAmount' => $userAmount + $site_user_count
		),
            'where' => array('id =' => $domain_id),
		);

		//增加域分配情况保存到路由表
		$domain_data = array(
              'siteID' =>$siteid,//该客户的站点ID
              'URL' =>$siteURL,//$URL,//该客户的域名
              'customerCode' =>$customerCode,//该客户所属的客户编码
              'clusterID' =>$uc_clusterID,//该客户所有的集群ID
              'userAmount' =>$site_user_count//该站点的用户量
		);

		//运行事务 TO
		$this->db->trans_start();
		if($domain_operate == 1){
			//更新域表
			$update_cluster_user_arr = $this->UC_Cluster_User_Num_Model->operateDB(5,$cluster_data);
			//增加域分配情况保存到路由表
			$insert_router_arr = $this->UC_Domain_Router_Model->insert_db($domain_data);
		}else{//更新,不用更新域的数量
			$Domain_data = array(
                'update_data' => $domain_data,
                'where' => array('siteID =' => $siteid,'customerCode =' => $customerCode),
			);
			$insert_router_arr = $this->UC_Domain_Router_Model->operateDB(5,$Domain_data);
		}
		$this->db->trans_complete();
		if ($this->db->trans_status() == FALSE)
		{
			// 生成一条错误信息... 或者使用 log_message() 函数来记录你的错误信息
			log_message('error', 'update table UC_Cluster_User_Num  field userAmount and inster UC_Domain_Router  fail.');
			return 0;
			//https(400);
			//exit;
		}else{
			if($domain_operate == 1){
				if(!db_operate_fail($update_cluster_user_arr)){//成功
					log_message('debug', ' update_cluster_user success.');
				}else{//失败
					log_message('error', ' update_cluster_user fail.');
					return 0;
				}
			}
			if(!db_operate_fail($insert_router_arr)){//成功
				log_message('debug', ' insert_router success.');
			}else{//失败
				log_message('error', ' insert_router fail.');
				return 0;
			}
		}
		log_message('info', 'out method ' . __FUNCTION__ . '.');
		return 1;
	}

	/**
	 * @brief 创建用户;批量开通接口[BOSS开通后调用]
	 * @details
	 * @return  失败HTTP/1.1 400 Bad Request；1：成功 200
	 */
	public function createUserBatch() {
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		//获得BOSS post过来的数据
		$BOSS_post_json = api_get_post();
		write_test_file( __FUNCTION__ . time() . '.txt' ,$BOSS_post_json);
		if(bn_is_empty($BOSS_post_json)){//没有数据
			$err_msg = ' get BOSS post json fail.';
			log_message('error', $err_msg);
			https(400);
			exit;
		}
		$this->load->library('AccountLib','','AccountLib');
		$this->AccountLib->boss_open_site($BOSS_post_json);
		log_message('info', 'out method ' . __FUNCTION__ . '.');
	}


}