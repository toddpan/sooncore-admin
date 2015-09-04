<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * boss 接口公用类
 * @file BossLib.php
 * @author caohongliang <hongliang.cao@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
class BossLib{
	 public function __construct() {
        //载入接口公用函数
		include_once APPPATH . 'helpers/my_httpcurl_helper.php';
        $this->apiurl = BOSS_API.'core-service/api' ; //接口地址
    }
	
	/**
	 * 账号create/update/disable/enable/delete接口
	 * @param array $data 向接口发送的数据（详细信息请参照文档）
	 */
	public function account($data){
		$method = 'POST';
        $url  	= $this->apiurl.'/actives/active';
        $param 	= json_encode($data);
        $ret = httpCurl($url, $param, $method);
		
		log_message('info',"operate account from boss url is->".$url." request data is {$param} result is->".var_export($ret, true));
		
		return $ret['code'] == 0 ? true : false;
	}
	
	
	
	/**
	 * 批量创建合同组建属性
	 * -此接口可以创建合同属性、账号属性
	 * @param type $data 向接口发送的数据（详细信息请参照文档）
	 */
	public function batchCreateContractComponentProps($data){
		$method = 'POST';
        $url  = $this->apiurl.'/contractComponentProps/batchCreateContractComponentProps';
        $param = json_encode($data);
        $ret = httpCurl($url, $param, $method);
		
		log_message('info',"Batch create contact component property to boss. url is->".$url." input data is ".var_export($param, true)." result is->".var_export($ret, true));
		
		return $ret['code'] == 0;
	}
	
	/**
	 * 批量修改合同组建属性
	 * -此接口可以修改合同属性、账号属性
	 * @param type $data 向接口发送的数据（详细信息请参照文档）
	 */
	public function batchModifyContractComponentProps($data){
		$method = 'PUT';
        $url  = $this->apiurl.'/contractComponentProps/batchModifyContractComponentProps';
        $param = json_encode($data);
        $ret = httpCurl($url, $param, $method);
		
		log_message('info',"Batch modify contact component property to boss. url is->".$url." input data is ".var_export($param, true)." result is->".var_export($ret, true));
		
		return $ret['code'] == 0;
	}
	
	
	/**
	 * 合同开通操作boss回调
	 * @param string $url 回调地址
	 * @param int    $request_id     boss端请求id
	 * @param int    $contract_id    合同id
	 * @param int    $is_success     是否操作成功
	 * @param string $component_name 组件名称
	 * @return boolean
	 */
	public function contract_callback($url, $request_id, $contract_id, $is_success){
		
		$method = 'POST';
		$data = array(
			'requestId'		=> $request_id,
			'finishedTime'	=> time(),
			'result'		=> $is_success,
			'contractId'	=> $contract_id,
			'componentName'	=> 'UC'
		);
		$ret = httpCurl($url, json_encode($data),$method);
		log_message('info',"Open contract, callback boss. url is-->".$url." request data is ".var_export($data, true)." result is->".var_export($ret, true));
		return $ret['code'] == 0;
	}
	
	/**
	 * 账号开通操作boss回调
	 * @param string $url 回调地址
	 * @param int    $request_id     boss端请求id
	 * @param int    $contract_id    合同id
	 * @param int    $is_success     是否操作成功
	 * @param string $component_name 组件名称
	 * @return boolean
	 */
	//XXX 同步请求boss后，返回值未处理
	public function account_callback($url, $request_id, $contract_id, $is_success, $success_list, $fail_list){

		$method = 'POST';
		$data = array(
				'requestId'		=> $request_id,
				'finishedTime'	=> time(),
				'result'		=> $is_success,
				'contractId'	=> $contract_id,
				'componentName'	=> 'UC',
				'successed'		=> $success_list,
				'failed'		=> $fail_list
		);
		$ret = httpCurl($url, json_encode($data),$method);
		log_message('info',"account, callback boss. url is-->".$url." request data is ".var_export($data, true)." result is->".var_export($ret, true));
		return $ret['code'] == 0;
	}
	
	/**
	 * 获取客户信息
	 * @param string $customer_code 客户编码
	 */
	public function getCustomerInfo($customer_code){
		$method = 'GET';
        $url  = $this->apiurl.'/customers/getCustomerInfo?customerCode='.$customer_code;
        $params = array();
		$ret = httpCurl($url, $params,$method);
		
		log_message('info',"get customer info from boss. url is->".$url." customer code is ->".$customer_code." result is->".var_export($ret, true));
		
		if($ret['code'] == 0  && $ret['data'] != ''){
			return json_decode($ret['data'], true);
        }else{
            return false;
        }
	}
	
	/**
	 * 查询指定客户下合同对应的所有账户(废弃)
	 * @deprecated
	 * @param string $customer_code 客户编码
	 * @param 合同id  $contract_id   合同id
	 */
	public function getAccountInfo($customer_code, $contract_id){
		$method = 'GET';
		$url  = $this->apiurl.'/accounts/getByCustomerCodeAndContractId/'.$customer_code.'/'.$contract_id;
		$params = array();
		$ret = httpCurl($url, $params,$method);
		
		log_message('info',"get account info from boss. url is->".$url." customer code is ->".$customer_code." result is->".var_export($ret, true));
		
		if($ret['code'] == 0  && $ret['data'] != ''){
			return json_decode($ret['data'], true);
		}else{
			return false;
		}
	}
	
	public function getAccountInfoByCustomerCode($customer_code){
		$method = 'GET';
		$url  = $this->apiurl.'/accounts/getByCustomerCodeForUc/'.$customer_code;
		$params = array();
		$ret = httpCurl($url, $params,$method);
		
		log_message('info',"get account info from boss. url is->".$url." customer code is ->".$customer_code." result is->".var_export($ret, true));
		
		if($ret['code'] == 0  && $ret['data'] != ''){
			return json_decode($ret['data'], true);
		}else{
			return false;
		}
	}
	
	/**
	 * 根据客户id获取客户标签
	 */
	public function getTags($customer_id, $others=array()){
		$method = 'GET';
        $url  = $this->apiurl.'/customerTags/getByCustomerCode/'.$customer_id;
        $params = array(
			'isRequired'=> isset($others['isRequired']) ? $others['isRequired'] : 0, 
			'isEnabled'=> isset($others['isEnabled']) ? $others['isEnabled'] : 1, 
			'isModified'=> isset($others['isModified']) ? $others['isModified'] : 1, 
			'isSubaccountTag'=> isset($others['isSubaccountTag']) ? $others['isSubaccountTag'] : 0, 
		);
		$ret = httpCurl($url, $params,$method);
		log_message('info',"get tags from boss. url is->".$url." request data is ->".var_export($params,true)." result is->".var_export($ret, true));
		if($ret['code'] == 0  && $ret['data'] != ''){
			return json_decode($ret['data'], true);
        }else{
            return false;
        }
	}
	
	/**
	 * 根据标签id查询客户标签基本信息
	 */
	public function getTagInfo($tag_id){
		$method = 'GET';
        $url  = $this->apiurl.'/customerTags/get/'.$tag_id;
        $params = array();
		$ret = httpCurl($url, $params,$method);
		
		log_message('info',"get tags info from boss. url is->".$url." request data is ->".var_export($params,true)." result is->".var_export($ret, true));
		
		if($ret['code'] == 0  && isset($ret['data'])){
			return json_decode($ret['data'], true);
        }else{
            return false;
        }
	}
	
	
	/**
	 * 创建客户标签
	 */
	public function createTag($customer_id, $tag_name, $parent_id=null, $others=array()){
		$method = 'POST';
        $url  = $this->apiurl.'/customerTags';
		$data = array(
			'customerId'=>$customer_id,
			'tagName'=>$tag_name,
			'parentId'=>$parent_id,
			'isRequired'=> isset($others['isRequired']) ? $others['isRequired'] : 0, 
			'isEnabled'=> isset($others['isEnabled']) ? $others['isEnabled'] : 1, 
			'isModified'=> isset($others['isModified']) ? $others['isModified'] : 1, 
			'isSubaccountTag'=> isset($others['isSubaccountTag']) ? $others['isSubaccountTag'] : 0, 
			'tagOptions'=> isset($others['tagOptions']) ? $others['tagOptions'] : '', 
		);
        $ret = httpCurl($url, json_encode($data),$method);
		log_message('info',"create tag to boss. url is->".$url." request data is ".var_export($data, true)." result is->".var_export($ret, true));
		if($ret['code'] == 0){
			return intval($ret['http_info']['http_body']);
        }else{
            return false;
        }
	}
	
	/**
	 * 修改客户标签
	 */
	public function modifyTag($tag_id, $tag_name, $others=array()){
		$method = 'PUT';
        $url  = $this->apiurl.'/customerTags';
		$data = array(
			'customerTagId'=>$tag_id,
			'tagName'=>$tag_name,
		);
		$data = array_merge($data, $others);
        $ret = httpCurl($url, json_encode($data),$method);
		log_message('info',"modify tag to boss. url is->".$url." request data is ".var_export($data, true)." result is->".var_export($ret, true));
		if($ret['code'] == 0){
			return true;
        }else{
            return false;
        }
	}
	
	
	/**
	 * 删除客户标签
	 */
	public function delTag($tag_id){
		$method = 'DELETE';
        $url  = $this->apiurl.'/customerTags/removeById/'.$tag_id;
		$data = array();
        $ret = httpCurl($url, json_encode($data),$method);
		log_message('info',"delete tag to boss. url is->".$url." request data is ".var_export($data, true)." result is->".var_export($ret, true));
		if($ret['code'] == 0){
			return true;
        }else{
            return false;
        }
	}
	
	/**
	 * 创建账号标签实例，标签与用户的关联关系
	 */
	public function createUserTag($user_id, $tag_id, $tag_value='nothing'){
		$method = 'POST';
        $url  = $this->apiurl.'/userTagInstances';
		$data = array(
			'userId'=>$user_id,
			'customerTagId'=>$tag_id,
			'tagValue'=>$tag_value,
		);
        $ret = httpCurl($url, json_encode($data),$method);
		log_message('info',"create user tag to boss. url is->".$url." request data is ".var_export($data, true)." result is->".var_export($ret, true));
		if($ret['code'] == 0){
			return intval($ret['http_info']['http_body']);
        }else{
            return false;
        }
	}
	
	/**
	 * 删除指定账号的标签实例，这里的删除，是全部删除.即：解除用户与标签的关联关系
	 */
	public function delUserTag($user_id,$tag_id = null){
		$method = 'DELETE';
		if(is_null($tag_id)){
			 $url  = $this->apiurl.'/userTagInstances/removeByUserId/'.$user_id;
		}else{
			 $url  = $this->apiurl.'/userTagInstances/removeByUserId/'.$user_id.'?customerTagId='.$tag_id;
		}
		$data = array();
        $ret = httpCurl($url, json_encode($data),$method);
		log_message('info',"delete user tag to boss. url is->".$url." request data is ".var_export($data, true)." result is->".var_export($ret, true));
		if($ret['code'] == 0){
			return true;
        }else{
            return false;
        }
	}
	
	/**
	 * 获取用户的标签列表
	 */
	public function getUserTag($user_id){
		$method = 'GET';
        $url  = $this->apiurl.'/userTagInstances/getByUserId/'.$user_id;
        $params = array();
		$ret = httpCurl($url, $params,$method);
		log_message('info',"get user tag from boss. url is->".$url." request data is ->".var_export($params,true)." result is->".var_export($ret, true));
		if($ret['code'] == 0  && $ret['data'] != ''){
			return json_decode($ret['data'], true);
        }else{
            return false;
        }
	}
	

	/**********以下是boss增加了多销售品开通后，新增或修改（1个）的接口************/
	
	/**
	 * 组合销售品账号create/update/disable/enable/delete接口
	 * @param array $data 向接口发送的数据（详细信息请参照文档）
	 * @return boolean
	 */
	public function combinedAccount($data){
		$method = 'POST';
		$url  	= $this->apiurl.'/actives/combinedActive';
		
		//cms需要添加的两个可选参数 time:2014-12-12
		$data['createdTime'] = isset($data['createdTime']) ? $data['createdTime'] : time();
		$data['creator'] 	 = isset($data['creator']) 	   ? $data['creator'] 	  : "UC管理中心";
		//----
		
		$param 	= json_encode($data);
		$ret = httpCurl($url, $param, $method);
	
		log_message('info',"operate account from boss url is->".$url." request data is {$param} result is->".var_export($ret, true));
	
		return $ret['code'] == 0 ? true : false;
	}
	
	/*
	 * @brief 根据contractId、uuid、sellProdId查询和同属性列表
	 * @detail
	 * -此接口在老接口的基础上增加了销售品ID参数，修改了返回值的格式
	 * -此接口与下面的getSellingProductTemplates接口功能基本相同
	 * -只是返回的数据里少了productId
	 *
	 * @param int $contract_id  合同ID
	 * @param int $uuid		    模板ID
	 * @param int $sell_prod_id 销售品ID
	 * @return mix
	 */
	public function getContractComponentProps($contract_id, $uuid, $sell_prod_id=''){
		$method = 'GET';
		$url  = $this->apiurl.'/contractComponentProps/getByContractIdAndUuid?contractId='.$contract_id.'&uuid='.$uuid.'&sellProdId='.$sell_prod_id;
		$ret = httpCurl($url, '',$method);
	
		log_message('info',"get contract component info from boss. url is->".$url." result is->".var_export($ret, true));
	
		if($ret['code'] == 0){
			return json_decode($ret['http_info']['http_body'], true);
		}else{
			return false;
		}
	}
	
	/**
	 * @brief 获取当前合同下所有销售品的权限模板
	 * @detail
	 * -此接口返回的每个销售品里包含了每个销售品所对应的产品id,
	 * -所以，可以通过此接口建立销售品id与产品id的对应关系
	 * -如果$uuid为空，则默认$uuid=$contract_id
	 * 
	 * @param int $contract_id 合同id
	 * @return mix
	 */
	public function getSellingProductTemplates($contract_id, $uuid='', $sellProdId=''){
		$method = 'GET';
		$url  = $this->apiurl.'/contractComponentProps/getContractTemplateProps/'.$contract_id.'?uuid='.$uuid.'&sellProdId='.$sellProdId;
		$ret = httpCurl($url, '',$method);
	
		log_message('info',"get selling product info from boss. url is->".$url." result is->".var_export($ret, true));
	
		if($ret['code'] == 0){
			return json_decode($ret['http_info']['http_body'], true);
		}else{
			return false;
		}
	}
	
	/**
	 * @brief 获取产品id与销售品id对应关系
	 * @param int $contract_id
	 * @return array
	 */
	public function getProdIdAndSellProdIdMap($contract_id){
		$ret = $this->getSellingProductTemplates($contract_id);
		
		if(!$ret){
			return false;
		}
		
		return array_column($ret, 'id', 'productId');
	}
	
	/**
	 * 组合销售品批量创建合同组建属性，新的接口
	 * -此接口可以创建合同属性、账号属性
	 * @param type $data 向接口发送的数据（详细信息请参照文档）
	 * @return boolean
	 */
	public function combinedBatchCreateContractComponentProps($data){
		$method = 'POST';
		$url  = $this->apiurl.'/contractComponentProps/combinedBatchCreateContractComponentProps';
		$param = json_encode($data);
		$ret = httpCurl($url, $param, $method);
	
		log_message('info',"Batch create selling  product contact component property to boss. url is->".$url." input data is ".var_export($param, true)." result is->".var_export($ret, true));
	
		return $ret['code'] == 0;
	}
	
	/**
	 * 组合销售品批量修改合同组建属性，新的接口
	 * -此接口可以修改合同属性、账号属性
	 * @param type $data 向接口发送的数据（详细信息请参照文档）
	 * @return boolean
	 */
	public function combinedBatchModifyContractComponentProps($data){
		$method = 'PUT';
		$url  = $this->apiurl.'/contractComponentProps/combinedBatchModifyContractComponentProps';
		$param = json_encode($data);
		$ret = httpCurl($url, $param, $method);
	
		log_message('info',"Batch modify selling product contact component property to boss. url is->".$url." input data is ".var_export($param, true)." result is->".var_export($ret, true));
	
		return $ret['code'] == 0;
	}
	
	/**********以上是boss增加了多销售品开通后，新增或修改（1个）的接口************/

	
}
