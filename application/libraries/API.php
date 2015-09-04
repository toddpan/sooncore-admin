<?php if (!defined('BASEPATH'))	exit('No direct script access allowed');

/**
 * @class UCCServerAPI
 * @brief UCCServerAPI 类库，主要负责对调用UCCServerAPI接口方法。
 * 注意原则 返回数组中，下标code 0代表接口成功，非0代表失败 ，
 * 如果接口调成功有code 而且不是0 ，那么需要先将code的值放入 ‘codenew’ 下标中，并将原code值改为0
 * @file StaffLib.php
 * @author zouyan <yan.zou@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
class API{
	public function __construct() {
		//载入接口公用函数
		include_once APPPATH . 'helpers/my_httpcurl_helper.php';
		include_once APPPATH . 'helpers/my_publicfun_helper.php';//公用函数
	}

	/**
	 *
	 * @brief UCCServerAPI的所有接口
	 * @details
	 * @param string $data 接口输入参数 格式$data = 'user_account=zouyan@quanshi.com&password=111111&client_type=4&client_info='.json_encode(array('mac'=>'D0-67-E5-2D-6F-C9'));
	 * @param int $api_type UCCServerAPI 类型
	 * @param array $other_arr  其它扩展数组
	 * @return array $redata 获得的信息 code 0 : 成功
	 *
	 */
	public function UCCServerAPI($data = '' , $api_type = 0 , $other_arr = array()) {
		//echo $data . '<br/>';
		$redata = array(); //返回数据
			
		//有类型,可以没有参数
		if ($api_type > 0)
		{
			$apiurl = UCC_API ; //接口地址
			$api_method = 'POST'; //接口方法,默认post
			$post_head = POST_HEAD;//head头
			//根据接口类型，拼接接口地址
			switch ($api_type) {
				case 1: //后台用户登陆 ,返回json 串，code  0 : 成功
					$apiurl .= "user/login";
					break;
				case 2://后台用户退登接口code ,返回json 串，0 : 成功 -1: 失败
					$apiurl .= "user/logout";
					break;
				case 3://验证用户账号  ,返回json 串， code  0 : 成功
					$apiurl .= "user/verify";
					break;
				case 4://找回密码，保存新密码 ,返回json 串， code  0 : 成功
					$apiurl .= "user/setNewPassword";
					break;
				case 5://组织消息,返回json 串，code 0 : 成功 -1: 失败
					$apiurl .= "message/org";
					$type = $other_arr['type'];//类型
					break;
				case 6://MQ集群非配接口,返回json 串，code 0 : 成功
					$apiurl .= "async/mqDispath";
					break;

				case 7://数据库分配接口,返回json 串，code 0 : 成功
					$apiurl .= "async/dbDispath";
					break;
				case 8://聊天和状态交换机创建,返回json 串，code 0 : 成功
					$apiurl .= "async/siteCreate";
					break;
				case 9://同事关系创建,返回json 串，code 0 : 成功
					$apiurl .= "async/createColleague";
					break;
				case 10://同事关系删除,返回json 串，code 0 : 成功
					$apiurl .= "async/deleteColleague";
					break;
				case 11://系统通知消息发送
					$apiurl .= "message/systemAlert";
					break;
				case 12://通过用户名和密码换取集群访问地址
					$apiurl .= "user/lookUp";
					break;
					//case 13;//用户标签更新接口
					//  $apiurl .= "user/tagUpdate";
					//  break;
				case 14://验证用户账号(忘记密码用例)
					$apiurl .= "user/accountVerify";
					break;
				case 15://手机短信验证码验证
					$apiurl .= "user/codeVerify";
					break;
				case 16://验证码获取(仅适用于PC客户端)
					$url = $other_arr['url'];
					$apiurl .= "user/captcha?hashstr=" . $url;
					$api_method = 'get';
					break;
				case 17://手机短信消息发送
					$apiurl .= "async/sendMobileMsg";
					break;
				case 18://组织机构交换机创建(包括聊天和状态)
					$apiurl .= "async/orgCreate";
					break;
				case 19://组织机构交换机删除(包括聊天和状态)
					$apiurl .= "async/orgDelete";
					break;
				case 20://组织机构联系人关系建立
					$apiurl .= "async/contactRela";
					break;
			}
			//echo $apiurl;
			//echo $data;
			//echo '<br/>';
			$api_msg = $api_type . __FUNCTION__ . ' $apiurl=' . $apiurl . '  $data=' . any_to_str($data) . '  $api_method=' . $api_method . '  $post_head=' . $post_head . ' .';
			//echo $api_msg;
			log_message('debug', 'post api ' . $api_msg);
			$json_data = httpCurl($apiurl, $data,$api_method,array($post_head));
			//print_r($json_data);
			$ns_write_data = $json_data;
			if(is_array($ns_write_data)){
				$ns_write_data = json_encode($ns_write_data);
			}
			write_test_file( __FUNCTION__ . $api_type . '-' . time() . '.txt' ,  $apiurl . any_to_str($data) . $api_method . $post_head . any_to_str($ns_write_data));
			// print_r($json_data);
			log_message('debug', any_to_str($ns_write_data));
			$http_code = isset($json_data['http_info']['http_code'])?$json_data['http_info']['http_code']:'' ;
			$http_body = isset($json_data['http_info']['http_body'])?$json_data['http_info']['http_body']:'' ;

			//特别情况
			switch ($api_type) {
				case 16://验证码获取(仅适用于PC客户端)
					$redata = $json_data;
					break;
				case -1:
					break;
				default:
					if($http_body != ''){
						$json_data=$http_body;
						$redata = json_decode($json_data,true);
					}
					break;
			}
		}
		$redata = res_arr($redata);//整理结果数组
		log_message('debug', 'post api ' . $api_type  . __FUNCTION__ . ' $redata =' . any_to_str($redata). ' .');
		return $redata;
	}

	/**
	 *
	 * @brief UMSAPI的所有接口
	 * @details
	 * @param string $data 接口输入参数 json串
	 * @param int $api_type UMSAPI 类型
	 * @param array $other_arr  其它扩展数组
	 * @return array $redata 获得的信息  code 0 : 成功
	 *
	 */
	public function UMSAPI($data = '' , $api_type = 0, $other_arr = array()) {
		//echo $data . '<br/>';
		$redata = array(); //返回数据
			
		//有类型,可以没有参数
		if ($api_type > 0)
		{
			$apiurl = UMS_API ; //接口地址
			$api_method = 'POST'; //接口方法,默认post
			$post_head = POST_HEAD;//head头
			//根据接口类型，拼接接口地址
			switch ($api_type) {
				case 1: //a批量导入组织结构
					$apiurl .= "rs/organizations/list";
					$post_head = POST_HEAD_JSON;//head头
					break;
				case 2://批量更新组织结构
					$apiurl .= "rs/web/disableUser";
					break;
				case 3://根据指定的ldap标识，获得指定ldap信息
					$apiurl .= "rs/web/disableUser";
					break;
				case 4://保存ldap信息
					$apiurl .= "rs/web/disableUser";
					break;
				case 5://个性ldap住息
					$apiurl .= "rs/web/disableUser";
					break;
				case 6://根据ldap信息，获得组织结构
					$apiurl .= "rs/web/disableUser";
					break;
				case 7;//a通过loginName判断用户是否在数据库中存在
				$apiurl .= "rs/users/checkUsers";// /rs/users 或 /rs/users/checkUsers
				$post_head = POST_HEAD_JSON;//head头
				break;
				case 8;//a批量修改用户和组织
				$url = $other_arr['url'];//002896
				$apiurl .= "rs/users/updateUsers/customerCode/" . $url;// http://127.0.0.1:8084/ums/rs/users/updateUsers/customerCode/002896
				$post_head = POST_HEAD_JSON;//head头
				break;
			}
			// echo $apiurl;
			// echo $data;
			// echo $api_method;
			//echo $post_head;
			log_message('debug', 'post api ' . $api_type . __FUNCTION__ . ' $apiurl=' . $apiurl . '  $data=' . any_to_str($data) . '  $api_method=' . $api_method . '  $post_head=' . $post_head . ' .');
			$json_data = httpCurl($apiurl, $data ,$api_method,array($post_head));
			$ns_write_data = $json_data;
			if(is_array($ns_write_data)){
				$ns_write_data = json_encode($ns_write_data);
			}
			write_test_file( __FUNCTION__ . $api_type . '-' . time() . '.txt' ,  $apiurl . any_to_str($data) . $api_method . $post_head . any_to_str($ns_write_data));
			//print_r($json_data);
			// exit;
			log_message('debug', any_to_str($ns_write_data));
			$http_code = isset($json_data['http_info']['http_code'])?$json_data['http_info']['http_code']:'' ;
			$http_body = isset($json_data['http_info']['http_body'])?$json_data['http_info']['http_body']:'' ;

			//特别情况
			switch ($api_type) {
				case -1:
					break;
				case 1:
					if($http_body != ''){
						$json_data=$http_body;
						$redata = json_decode($json_data,true);
						if(is_array($redata)){//是数组
							$redata['code']= 0; //返回数据
						}
					}
					break;
				case 7;//通过loginName判断用户是否在数据库中存在
				case 8;//批量修改用户和组织
				//echo $http_code;
				if($http_code == 201 || $http_code == 200){//成功//data有值，没为不存在的数据
					$redata = array( 'code' => 0, 'data' => $http_body ); //返回数据
				}
				break;
				default:
					if($http_body != ''){
						$json_data=$http_body;
						$redata = json_decode($json_data,true);
					}
					break;
			}
		}
		$redata = res_arr($redata);//整理结果数组
		log_message('debug', 'post api ' . $api_type  . __FUNCTION__ . ' $redata =' . any_to_str($redata). ' .');
		return $redata;
	}
	/**
	 *
	 * @brief UMSAPI的所有接口
	 * @details
	 * @param string $data 接口输入参数 json串
	 * @param int $api_type UMS_Special_API 类型
	 * @param array $other_arr  其它扩展数组
	 * @return array $redata 获得的信息code 0 成功1失败  或 自定义其它情况
	 *
	 */
	public function UMS_Special_API($data = '' , $api_type = 0, $other_arr = array()) {
		//echo $data . '<br/>';
		$redata = array( 'code' => 1 ); //返回数据
		//有类型,可以没有参数
		if ($api_type > 0)
		{
			$apiurl = UMS_API ; //接口地址
			$post_head = POST_HEAD;//head头
			$api_method = 'POST'; //接口方法,默认post
			//根据接口类型，拼接接口地址
			switch ($api_type) {
				case 1://创建组织,返回新创建的组织id
					$apiurl .= "rs/organizations";
					$post_head = POST_HEAD_JSON;
					break;
				case 2://根据用户IDs查询用户
					$apiurl .= "rs/users/id/in";
					$post_head = POST_HEAD_JSON;
					break;
				case 3://通过站点URL精确查询站点
					$url = $other_arr['url'];
					$apiurl .= "rs/sites?url=" . $url;
					$api_method = 'get';
					$post_head = POST_HEAD_JSON;
					break;
				case 4://创建用户产品
					$url = $other_arr['url'];
					$apiurl .= "rs/users/setUserProduct?" . $url;
					$post_head = POST_HEAD_JSON;
					break;
				case 5://修改密码校验老密码
					$url = $other_arr['url'];
					$apiurl .= "rs/users/id/" . $url;
					$post_head = POST_HEAD;
					$api_method = 'PUT';
					break;
				case 6://通过客户编码查询组织
					$url = $other_arr['url'];
					$apiurl .= "rs/organizations?" . $url;
					$post_head = POST_HEAD_JSON;
					$api_method = 'GET';
					break;
				case 7://查询组织成员
					$url = $other_arr['url'];
					$apiurl .= "rs/organizations" . $url;// /2/users ?productID=20 //不加productID的话，就不会验证产品状态
					$post_head = POST_HEAD_JSON;
					$api_method = 'GET';
					break;
				case 8://查询子组织
					$url = $other_arr['url'];
					$apiurl .= "rs/organizations" . $url;// /2/users
					$post_head = POST_HEAD_JSON;
					$api_method = 'get';
					break;
				case 9://创建组织
					$apiurl .= "rs/organizations";// /2/users
					$post_head = POST_HEAD_JSON;
					break;
				case 10://修改组织
					$apiurl .= "rs/organizations";// /2/users
					$api_method = 'put';
					$post_head = POST_HEAD_JSON;
					break;
				case 11://根据用户ID查询用户
					$url = $other_arr['url'];
					$apiurl .= "rs/users/getUserById?" . $url;// userId=1&productID=20 //不加productID的话，就不会验证产品状态
					$post_head = POST_HEAD_JSON;
					$api_method = 'get';
					break;
				case 12://删除组织
					$url = $other_arr['url'];
					$apiurl .= "rs/organizations/" . $url;// 3
					//$post_head = POST_HEAD_JSON;
					$api_method = 'DELETE';
					break;
				case 13://更改用户所在组织
					$apiurl .= "rs/organizations/change_organization" ;
					$post_head = POST_HEAD_JSON;
					$api_method = 'PUT';
					break;
				case 14://查询组织详细信息[返回当前组织信息及其下所属帐号及其下所属组织]
					$url = $other_arr['url'];
					$apiurl .= "rs/organizations/" . $url;//2/details? 可选[多个,号分隔]types：1:企业 2:生态企业 3:部门 4:生态企业部门
					$post_head = POST_HEAD_JSON;
					$api_method = 'get';
					break;
				case 15://根据用户ID查询用户所在组织
					$url = $other_arr['url'];
					$apiurl .= 'rs/users/' . $url . '/organizations';
					$post_head = POST_HEAD_JSON;
					$api_method = 'get';
					break;
				case 16://修改用户
					$apiurl .= 'rs/users/updateUser';  // /rs/users 或 /rs/users/updateUser
					$post_head = POST_HEAD_JSON;
					break;
				case 17://通过Id查寻组织
					$url = $other_arr['url'];
					$apiurl .= 'rs/organizations/' . $url . '/brief';//948/brief';
					$post_head = POST_HEAD_JSON;
					$api_method = 'get';
					break;
				case 18://删除用户
					$url = $other_arr['url'];//rs/users/1/delete
					$apiurl .= "rs/users/" . $url . "/delete";// 1/delete
					$api_method = 'DELETE';
					break;
				case 19://通过loginName批量修改用户信息[注意必须要id 各 loginName ]
					$apiurl .= "rs/users/updateUsers";// /rs/users 或 /rs/users/updateUsers
					$post_head = POST_HEAD_JSON;
					break;
				case 20://根据用户ID和产品ID获取用户产品信息
					$url = $other_arr['url'];
					$apiurl .= "rs/users/getUserProductList?userId=" . $url . "&productId=" . UC_PRODUCT_ID;//
					//$post_head = POST_HEAD_JSON;
					$api_method = 'get';
					break;
				case 21://通过登录名查询用户
					$url = $other_arr['url'];
					$apiurl .= "rs/users/getUserByName?loginName=" . $url;//testuser1@quanshitest.com
					//$post_head = POST_HEAD_JSON;
					$api_method = 'get';
					break;
				case 22:// 重置密码
					$url = $other_arr['url'];
					$apiurl .= "rs/users/id/" . $url;
					$post_head = POST_HEAD_JSON;
					$api_method = 'put';
					break;
				case 23:// 移动部门
					$url = $other_arr['url'];
					$apiurl .= "/rs/organizations/change_parent?" . $url;
					$api_method = 'put';
					break;
			}
			log_message('debug', 'post api ' . $api_type  . __FUNCTION__ . ' $apiurl=' . $apiurl . '  $data=' . any_to_str($data) . '  $api_method=' . $api_method . '  $post_head=' . $post_head . ' .');
			$json_data = httpCurl($apiurl, $data,$api_method,array($post_head));
			//print_r($json_data);
			//die();
			// exit;
			$ns_write_data = $json_data;
			if(is_array($ns_write_data)){
				$ns_write_data = json_encode($ns_write_data);
			}
			write_test_file( __FUNCTION__ . $api_type . '-' . time() . '.txt' ,  $apiurl . any_to_str($data) . $api_method . $post_head . any_to_str($ns_write_data));
			//print_r($json_data);
			// exit;
			log_message('debug', any_to_str($ns_write_data));
			$http_code = isset($json_data['http_info']['http_code'])?$json_data['http_info']['http_code']:'' ;
			//echo $http_code;//200
			$http_body = isset($json_data['http_info']['http_body'])?$json_data['http_info']['http_body']:'' ;
			//echo $json_data['http_info']['http_body'];
			switch ($api_type) {
				case 1://创建组织,返回新创建的组织id,返回 201 Body = 6
					if($http_code == 201 || $http_code == 200){//成功
						$redata = array( 'code' => 0, 'org_id' => $http_body ); //返回数据  org_id 当前组织id
					}
					break;
				case 2://根据用户IDs查询用户
				case 19;//通过loginName批量修改用户信息
				//echo $http_code;
				if($http_code == 201 || $http_code == 200){//成功
					$redata = array( 'code' => 0, 'data' => $http_body ); //返回数据  org_id 当前组织id
				}
				break;
				case 3://通过站点URL精确查询站点
					//if($http_code == 201 || $http_code == 200){//成功
					//$json_data=$http_body;
					// print_r($json_data);

					$redata = json_decode($json_data['data'],true);
					//print_r($redata);
					$redata['code'] = $json_data['code'];
					//}
					break;
				case 4:
				case 16://修改用户
				case 18;//删除用户
				if($http_body != ''){
					$json_data=$http_body;
					//echo $json_data;
					// exit;
					//$redata = json_decode($json_data,true);
					if(strstr(strtolower($json_data), 'true'))//如果返回值有true
					{
						$redata = array( 'code' => 0 ); //返回数据
					}
				}
				break;
				case 5://修改密码
				case 9://创建组织
				case 22:// 重置密码
					//echo $http_code;
					if($http_code == 201 || $http_code == 200){//成功
						$redata = array( 'code' => 0, 'org_id' => $http_body ); //返回数据  org_id 当前组织id
					}
					break;

				case 10://修改组织
				case 13://更改用户所在组织
					//echo $http_code;
					if($http_code == 201 || $http_code == 200){//成功
						$redata = array( 'code' => 0); //返回数据
					}
					break;
				case 7://查询组织成员
				case 8://查询子组织
				case 11://根据用户ID查询用户
				case 12://删除组织
				case 14://查询组织详细信息
				case 15://根据用户ID查询用户所在组织
				case 17://通过Id查寻组织
				case 6://通过客户编码查询组织,数组
				case 20;//根据用户ID和产品ID获取用户产品信息
				case 21;//通过登录名查询用户
					$redata = $json_data;//已经是数组了
					break;
				case 23:// 移动部门
					if($http_code == 204){//成功
						$redata = array( 'code' => 0); //返回数据
					}
					break;
				default:
					if($http_body != ''){
						$json_data=$http_body;
						$redata = json_decode($json_data,true);
					}
					break;
			}

		}
		$redata = res_arr($redata);//整理结果数组
		log_message('debug', 'post api ' . $api_type  . __FUNCTION__ . ' $redata =' . any_to_str($redata). ' .');
		return $redata;
	}
	/**
	 *
	 * @brief BOSSAPI的所有接口
	 * @details
	 * @param string $data 接口输入参数 格式$data = 'user_account=zouyan@quanshi.com&password=111111&client_type=4&client_info='.json_encode(array('mac'=>'D0-67-E5-2D-6F-C9'));
	 * @param int $api_type BOSSAPI 类型
	 * @param array $other_arr  其它扩展数组
	 * @return array $redata 获得的信息
	 *
	 *
	 */
	public function BOSSAPI($data = '' , $api_type = 0, $other_arr = array()) {
		log_message('debug', 'test========================='.$api_type);
		$redata = array(); //返回数据
		//有类型,可以没有参数
		if ($api_type > 0)
		{
			$apiurl = BOSS_API ; //接口地址
			$api_method = 'POST'; //接口方法,默认post
			$post_head = POST_HEAD;//head头
			//根据接口类型，拼接接口地址
			switch ($api_type) {
				case 1: //TODO 向QSBOSS批量开通接收接口
					$apiurl .= "core-service/api/actives/active";
					//$type = $other_arr['type'];//类型
					$post_head = POST_HEAD_JSON;//head头
					break;
				case 2://91.修改账号指定组件下的相关属性值
					$apiurl .= "core-service/api/userComponentProps/modifyUserComponentProps";
					//$type = $other_arr['type'];//类型
					$api_method = 'put';
					$post_head = POST_HEAD_JSON;//head头
					break;
				case 3:// 创建模板属性 QSBOSS保存站点/组织模板 ,json串提交
					$apiurl .= "core-service/api/contractComponentProps/batchCreateContractComponentProps";
					//$apiurl .= "contractComponentProps";
					$post_head = POST_HEAD_JSON;
					break;
				case 4:// 修改模板属性 QSBOSS保存站点/组织模板 ,json串提交
					$apiurl .= "core-service/api/contractComponentProps/batchModifyContractComponentProps";
					//$apiurl .= "contractComponentProps";
					$post_head = POST_HEAD_JSON;
					$api_method = 'put';
					break;
				case 5;//根据contractId、uuId查询合同组件属性列表
				$url = $other_arr['url'];
				$apiurl .= "core-service/api/contractComponentProps/getByContractIdAndUuid?" . $url;//contractId={contractId}&uuid={uuid}
				$api_method = 'get';
				break;
					
			}
			log_message('debug', 'post api ' . __FUNCTION__ . ' $apiurl=' . $apiurl . '  $data=' . any_to_str($data) . '  $api_method=' . $api_method . '  $post_head=' . $post_head . ' .');
			$json_data = httpCurl($apiurl, $data,$api_method,array($post_head));
			$ns_write_data = $json_data;
			if(is_array($ns_write_data)){
				$ns_write_data = json_encode($ns_write_data);
			}
			write_test_file( __FUNCTION__ . $api_type . '-' . time() . '.txt' ,  $apiurl . any_to_str($data) . $api_method . $post_head . any_to_str($ns_write_data));
			log_message('debug', '$ns_write_data'.any_to_str($ns_write_data));
			$http_code = isset($json_data['http_info']['http_code'])?$json_data['http_info']['http_code']:'' ;
			$http_body = isset($json_data['http_info']['http_body'])?$json_data['http_info']['http_body']:'' ;
			if($http_body != ''){
				$json_data=$http_body;
				log_message('debug',$json_data);
				$redata = json_decode($json_data,true);
			}
			log_message('debug',111);
			//特别情况
			switch ($api_type) {
				case -1:
					break;
				case 1: //QSBOSS开通接收接口
				case 2: //91.修改账号指定组件下的相关属性值
				case 3://QSBOSS保存站点/组织模板 ,json串提交
				case 4:// 修改模板属性 QSBOSS保存站点/组织模板 ,json串提交
					if($http_code == 201 || $http_code == 200){//成功
						$redata = array( 'code' => 0, 'msg' => $http_body ); //返回数据  org_id 当前组织id
					}
					break;
				case 5://根据contractId、uuId查询合同组件属性列表
					log_message('debug',222);
				if($http_code == 404){//没有记录
					$redata = array( 'code' => 0, 'msg' => $http_body );
					log_message('debug',333);
				}
				log_message('debug',444);
				if($http_code == 201 || $http_code == 200){//成功
					log_message('debug',555);
					$redata = array( 'code' => 0, 'msg' => $http_body ); //返回数据  org_id 当前组织id  $json_data;//
					log_message('debug',666);
					log_message('debug','$redata'.$redata);
				}
				break;
				default:
					log_message('debug',777);
					if($http_body != ''){
						$json_data=$http_body;
						$redata = json_decode($json_data,true);

					}
					break;
			}
		}
		log_message('debug','$redata'.$redata);
		$redata = res_arr($redata);//整理结果数组
		log_message('debug', 'post api ' . $api_type  . __FUNCTION__ . ' $redata =' . any_to_str($redata). ' .');
		return $redata;
	}
	/**
	 *
	 * @brief BOSS_Special_API
	 * @details
	 * @param string $data 接口输入参数 格式$data = 'user_account=zouyan@quanshi.com&password=111111&client_type=4&client_info='.json_encode(array('mac'=>'D0-67-E5-2D-6F-C9'));
	 * @param int $api_type BOSS_Special_API 类型
	 * @param array $other_arr  其它扩展数组
	 * @return array $redata 获得的信息code 0 成功1失败
	 *
	 *
	 */
	public function BOSS_Special_API($data = '' , $api_type = 0, $other_arr = array()) {
		//echo $data . '<br/>';
		$redata = array( 'code' => 1 ); //返回数据
		//有类型,可以没有参数
		if ($api_type > 0)
		{
			$apiurl = BOSS_API ; //接口地址
			$post_head = POST_HEAD;//head头
			$api_method = 'POST'; //接口方法,默认post
			//根据接口类型，拼接接口地址
			switch ($api_type) {
				case 1://QSBOSS回调址地 ,json串提交
					$apiurl = $other_arr['url'];// ; //接口地址
					$post_head = POST_HEAD_JSON;
					break;
			}
			//echo $apiurl;
			//echo $data;
			//echo $api_method;
			//echo $post_head;
			log_message('debug', 'post api ' . $api_type  . __FUNCTION__ . ' $apiurl=' . $apiurl . '  $data=' . any_to_str($data) . '  $api_method=' . $api_method . '  $post_head=' . $post_head . ' .');
			$json_data = httpCurl($apiurl, $data,$api_method,array($post_head));
			$ns_write_data = $json_data;
			if(is_array($ns_write_data)){
				$ns_write_data = json_encode($ns_write_data);
			}
			write_test_file( __FUNCTION__ . $api_type . '-' . time() . '.txt' ,  $apiurl . any_to_str($data) . $api_method . $post_head . any_to_str($ns_write_data));
			// print_r($json_data);
			// exit;
			log_message('debug', any_to_str($ns_write_data));
			$http_code = isset($json_data['http_info']['http_code'])?$json_data['http_info']['http_code']:'' ;
			$http_body = isset($json_data['http_info']['http_body'])?$json_data['http_info']['http_body']:'' ;
			switch ($api_type) {
				case 1://QSBOSS回调址地
					if($http_code == 201 || $http_code == 200){//成功
						$redata = array( 'code' => 0 ); //返回数据
					}
					break;
				default:
					if($http_body != ''){
						$json_data=$http_body;
						$redata = json_decode($json_data,true);

					}
					break;
			}
		}
		$redata = res_arr($redata);//整理结果数组
		log_message('debug', 'post api ' . $api_type  . __FUNCTION__ . ' $redata =' . any_to_str($redata). ' .');
		return $redata;
	}
	/**
	 *
	 * @brief MeetAPI 的所有日程和会议服务接口
	 * @details
	 * @param string $data 接口输入参数 格式$data = 'user_account=zouyan@quanshi.com&password=111111&client_type=4&client_info='.json_encode(array('mac'=>'D0-67-E5-2D-6F-C9'));
	 * @param int $api_type MeetAPI 类型
	 * @param array $other_arr  其它扩展数组
	 * @return array $redata 获得的信息
	 *
	 */
	public function MeetAPI($data = '' , $api_type = 0, $other_arr = array()) {
		//echo $data . '<br/>';
		$redata = array(); //返回数据
			
		//有类型,可以没有参数
		if ($api_type > 0)
		{
			$apiurl = MEET_API ; //接口地址
			$api_method = 'POST'; //接口方法,默认post
			$post_head = POST_HEAD;//head头
			//根据接口类型，拼接接口地址
			switch ($api_type) {
				case 1: //TODO 分发账户数据（增加、修改都用此接口）
					$apiurl .= "rs/conference/acceptData";
					$post_head = POST_HEAD_XML;
					//$api_method = 'post';
					break;
				case 2://TODO 禁用帐号
					$apiurl .= "rs/web/disableUser/3/" . $data;
					$data ='';
					$api_method = 'get'; //接口方法
					break;
				case 3://账号离职/调岗/禁用
					$apiurl .= "rs/conference/accountChange";
					$post_head = POST_HEAD_JSON;//head头
					break;
			}
			//echo '<br/><br/>';
			//echo $data;
			//echo '<br/><br/>';
			// echo $apiurl;
			// echo $api_method;
			// echo $post_head;
			log_message('debug', 'post api ' . $api_type  . __FUNCTION__ . ' $apiurl=' . $apiurl . '  $data=' . any_to_str($data) . '  $api_method=' . $api_method . '  $post_head=' . $post_head . ' .');
			$json_data = httpCurl($apiurl, $data,$api_method,array($post_head));
			$ns_write_data = $json_data;
			if(is_array($ns_write_data)){
				$ns_write_data = json_encode($ns_write_data);
			}
			write_test_file( __FUNCTION__ . $api_type . '-' . time() . '.txt' ,  $apiurl . any_to_str($data) . $api_method . $post_head . any_to_str($ns_write_data));
			// echo print_r($json_data);
			// exit;
			//exit;
			log_message('debug', any_to_str($ns_write_data));
			$http_code = isset($json_data['http_info']['http_code'])?$json_data['http_info']['http_code']:'' ;
			$http_body = isset($json_data['http_info']['http_body'])?$json_data['http_info']['http_body']:'' ;

			//特别情况
			switch ($api_type) {
				case -1:
					break;
				case 2://禁用帐号
				case 1://分发账户数据（增加、修改都用此接口）
					if($http_code == 200){//成功
						if(!strstr(strtolower($http_body), 'false'))//如果返回值没有false
						{
							$redata = array( 'code' => 0 ,'msg'=> $http_body); //返回数据
						}
					}
					break;
				default:
					if($http_body != ''){
						$json_data=$http_body;
						$redata = json_decode($json_data,true);
					}
					break;
			}
		}
		$redata = res_arr($redata);//整理结果数组
		log_message('debug', 'post api ' . $api_type  . __FUNCTION__ . ' $redata =' . any_to_str($redata). ' .');
		return $redata;
	}

	/**
	 *
	 * @brief UCAPI UC接口
	 * @details
	 * @param string $data 接口输入参数 格式$data = 'user_account=zouyan@quanshi.com&password=111111&client_type=4&client_info='.json_encode(array('mac'=>'D0-67-E5-2D-6F-C9'));
	 * @param int $api_type UCAPI 类型
	 * @param array $other_arr  其它扩展数组
	 * @return array $redata 获得的信息
	 *
	 */
	public function UCAPI($data = '' , $api_type = 0, $other_arr = array()) {
		//echo $data . '<br/>';
		$redata = array(); //返回数据
			
		//有类型,可以没有参数
		if ($api_type > 0)
		{
			$apiurl = $other_arr['url'];// ; //接口地址
			//如果左边没有http://,则加上http://
			if(!bn_is_empty($apiurl)){//有数据
				if(substr(strtolower($apiurl), 0, 7) != 'http://'){
					$apiurl = 'http://' . $apiurl;
				}
			}
			$api_method = 'POST'; //接口方法,默认post
			$post_head = POST_HEAD;//head头
			//根据接口类型，拼接接口地址
			switch ($api_type) {
				case 1: //BOSS调用uc时保存UC线程专用 code 0 成功[1、2功能作用相同，只是拼接的地址不同]
					$apiurl .= UC_DOMAIN_DIR. '/api/account/saveOpenThread';
					break;
				case 2: //批量导入帐号时保存UC线程专用 code 0 成功[1、2功能作用相同，只是拼接的地址不同]
					$apiurl .=  '/api/account/saveOpenThread';
					break;
				case 3: //通地当前域的ip地址，返回当前域的url等信息[del]
					$apiurl .= UC_DOMAIN_DIR. '/api/domain/get_cluster_arr';
					break;
				case 4: //通过当前邮件的url去获得邮件内容信息
					//$api_method = 'get'; //接口方法,默认post
					//$post_head = POST_HEAD_HTML;//head头
					//$apiurl .= UC_DOMAIN_DIR. '/api/mss/get_mss_content';
					break;
				case 5: //mss保存邮件接口
					$post_head = POST_HEAD_JSON;//head头
					$apiurl .= UC_DOMAIN_DIR. '/api/mss/save_mss';
					break;
				case 6: //通过客户编码或加上站点id获得域及其分配信息
					$apiurl .= UC_DOMAIN_DIR. '/api/allocation/get_cluster';
					break;
				case 7: //email保存邮件接口
					$post_head = POST_HEAD_JSON;//head头
					$apiurl .= UC_DOMAIN_DIR. '/api/email/save_email';
					break;
			}
			log_message('debug', 'post api ' . $api_type  . __FUNCTION__ . ' $apiurl=' . $apiurl . '  $data=' . any_to_str($data) . '  $api_method=' . $api_method . '  $post_head=' . $post_head . ' .');
			$json_data = httpCurl($apiurl, $data,$api_method,array($post_head));
			$ns_write_data = $json_data;
			if(is_array($ns_write_data)){
				$ns_write_data = json_encode($ns_write_data);
			}
			write_test_file( __FUNCTION__ . $api_type . '-' . time() . '.txt' ,  $apiurl . any_to_str($data) . $api_method . $post_head . any_to_str($ns_write_data));
			//print_r($json_data);
			log_message('debug', any_to_str($ns_write_data));
			$http_code = isset($json_data['http_info']['http_code'])?$json_data['http_info']['http_code']:'' ;
			$http_body = isset($json_data['http_info']['http_body'])?$json_data['http_info']['http_body']:'' ;

			//特别情况
			switch ($api_type) {
				case 4:
					if($http_code == 200){//成功
						$redata = array( 'code' => 0 ,'msg'=> $http_body); //返回数据
					}
					break;
				case -1:
					break;
				default:
					if($http_body != ''){
						$json_data=$http_body;
						$redata = json_decode($json_data,true);

					}
					break;
			}
		}
		$redata = res_arr($redata);//整理结果数组
		log_message('debug', 'post api ' . $api_type  . __FUNCTION__ . ' $redata =' . any_to_str($redata). ' .');
		return $redata;
	}
	/**
	 *
	 * @brief PortalAPI接口
	 * @details
	 * @param string $data 接口输入参数 格式$data = 'user_account=zouyan@quanshi.com&password=111111&client_type=4&client_info='.json_encode(array('mac'=>'D0-67-E5-2D-6F-C9'));
	 * @param int $api_type PortalAPI 类型
	 * @param array $other_arr  其它扩展数组
	 * @return array $redata 获得的信息
	 *
	 */
	public function PortalAPI($data = '' , $api_type = 0, $other_arr = array()) {
		//echo $data . '<br/>';
		$redata = array(); //返回数据
			
		//有类型,可以没有参数
		if ($api_type > 0)
		{
			$apiurl = PORTAL_API ; //接口地址
			$api_method = 'POST'; //接口方法,默认post
			$post_head = POST_HEAD;//head头
			//根据接口类型，拼接接口地址
			switch ($api_type) {
				case 1: //TODO
					$apiurl .= "portal/rule/addRule";
			}
			log_message('debug', 'post api ' . $api_type  . __FUNCTION__ . ' $apiurl=' . $apiurl . '  $data=' . any_to_str($data) . '  $api_method=' . $api_method . '  $post_head=' . $post_head . ' .');
			$json_data = httpCurl($apiurl, $data,$api_method,array($post_head));
			$ns_write_data = $json_data;
			if(is_array($ns_write_data)){
				$ns_write_data = json_encode($ns_write_data);
			}
			write_test_file( __FUNCTION__ . $api_type . '-' . time() . '.txt' ,  $apiurl . any_to_str($data) . $api_method . $post_head . any_to_str($ns_write_data));
			log_message('debug', any_to_str($ns_write_data));
			$http_code = isset($json_data['http_info']['http_code'])?$json_data['http_info']['http_code']:'' ;
			$http_body = isset($json_data['http_info']['http_body'])?$json_data['http_info']['http_body']:'' ;

			//特别情况
			switch ($api_type) {
				case -1:
					break;
				case 1:
					if($http_body != ''){
						$json_data=$http_body;
						$redata = json_decode($json_data,true);
						if($redata['isOk'] == 1){//成功
							$redata['code'] = 0;
						}
					}
					break;
				default:
					if($http_body != ''){
						$json_data=$http_body;
						$redata = json_decode($json_data,true);
					}
					break;
			}
		}
		$redata = res_arr($redata);//整理结果数组
		log_message('debug', 'post api ' . $api_type  . __FUNCTION__ . ' $redata =' . any_to_str($redata). ' .');
		return $redata;
	}
}
