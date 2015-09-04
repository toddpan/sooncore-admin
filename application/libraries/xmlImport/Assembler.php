<?php if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
require_once APPPATH . 'libraries/xmlImport/converter/CompareConverter.php';
require_once APPPATH . 'libraries/xmlImport/Parser.php';

/**
 * 组装器.将获得的json格式的用户信息保存到account_upload_task表.
 *
 * 获取用户信息的规范化的xml，将用户信息、类型、状态等存入uc_upload_task.
 *
 * @author ge.xie
 */
class Assembler {
	private $converter;
	private $siteId;
	private $customerCode;
	private $orgID;
	
	const XML_BATCH_IMPORT_SIZE = 50; 
	
	/**
	 *
	 * @param array $param<p>
	 * 由于要通过 CI 的 library 访问，只能通过数组传参
	 * [ formatUrl=>, url=> ,siteId=> ]</p>
	 */
	public function __construct($param) {
		require_once APPPATH . 'config/uc_config.php';
		
		$this->siteId = $param ['siteId'];
		
		$this->converter = new CompareConverter ( $param ['siteId'], $param ['url'],  $param ['formatUrl'] );
		
		$CI = & get_instance ();
		$CI->load->model ( 'uc_site_model', 'site' );
		$CI->load->library ( 'umsLib', '', 'umsLib' );
		
		$customerCode = $CI->site->getInfoBySiteId ( $this->siteId, 'customerCode' );
		$this->customerCode = $customerCode ['customerCode'];
		
		// 获得站点的org_id。通过从 uc_user_admin 表中查找的方式。
		$CI->load->model ( 'uc_user_admin_model', 'user_admin' );
		$this->orgID = $CI->user_admin->getOrgId( $this->siteId );
		
		// 获得站点的org_id. 优先通过 UMS 接口获得，失败则在配置文件查找。
		if (empty($this->orgID)) {
			$siteInfoBySiteId = $CI->umsLib->getSiteInfoById ( $this->siteId );
			$organizeByCustomerCode = json_decode ( $CI->umsLib->getOrganizeByCustomerCode ( $this->customerCode ), true );
			
			if(count($organizeByCustomerCode) == 1){
				$organize = array_pop($organizeByCustomerCode);
				$this->orgID = $organize ['id'];
			} elseif (count($organizeByCustomerCode) > 1) {
				foreach ( $organizeByCustomerCode as $organize ) {
					$this->orgID = ($organize ['siturl'] == $siteInfoBySiteId ['url']) ? $organize ['id'] : null;
				}
			} else {
				$this->orgID = null;
			}
		}
		
		if (empty($this->orgID)) {
			// ！暂时将 org_id 放在配置文件中
			$domFile = new DOMDocument;
			$domFile->load(APPPATH . 'libraries/xmlImport/org_id_config.xml');
			$xpath = new DOMXPath($domFile);
			$tBody = $domFile->getElementsByTagName('sites')->item(0);
			
			$siteNode = $xpath->query('//site[@site_id=' . $this->siteId .']', $tBody);
			$this->orgID = trim($xpath->query('@org_id', $siteNode->item(0))->item(0)->nodeValue);
		}

	}
	
	/**
	 * 静态方法.扫描formats文件夹，获得所有存在的xslt格式.暂未实现递归查找.
	 *
	 * 例如formats文件夹仅包含a.xsl、b.xsl文件，则返回[a,b]
	 *
	 * @param null
	 * @return array 返回所有格式的数组
	 */
	public static function getAllFormats() {
		return CompareConverter::getAllFormats ();
	}
	
	/**
	 * 功能库的入口方法.传入format和url.
	 *
	 * @return null
	 */
	public function action() {
		log_message ( 'info', 'Assembler:running in action.' );
		
		if (Parser::$isImportable == 0 || Parser::$isImportable == 2) {
			$this->finish();
			return false;
		}
		
		$usersArr = $this->converter->saveClassifyUsers ();
		log_message ( 'info', 'Assembler:data is ready to insert task.' );
		
		if (! $usersArr) {
			$this->finish();
			return false;
		}
		
		foreach ( $usersArr as $type => $users ) {
			
			if (empty ( $users )) {
				continue;
			}
			
			$CI = & get_instance ();
			$CI->load->model ( 'account_upload_task_model', 'upload_task' );
			
			if ($type == 'create') {				
				log_message('info', 'Assembler: There are ' . count($users) . ' users prepaered create.');
				$users = array_chunk($users, self::XML_BATCH_IMPORT_SIZE);
				
				foreach ( $users as $k=>$inner_users) {										
					$usersValue = array (
							'customer_code' => $this->customerCode,
							'site_id' => $this->siteId,
							'org_id' => $this->orgID,
							'users' => $inner_users
					);
					$CI->upload_task->saveTask ( ACCOUNT_CREATE_UPLOAD, json_encode ( $usersValue ) );
// 					$CI->upload_task->saveTask ( 80, json_encode ( $usersValue ) );

					log_message('info', 'Assembler: ' . self::XML_BATCH_IMPORT_SIZE .' users create completed.' . 'site_id = ' . $this->siteId . ' batch_num=' . $k);
				} 
				
				log_message('info', 'Assembler: All users create completed. site_id = ' . $this->siteId);
			} elseif ($type == 'update') {
				log_message('info', 'Assembler: There are ' . count($users) . ' users prepaered update.');
				$users = array_chunk($users, self::XML_BATCH_IMPORT_SIZE);

				foreach ($users as $k=>$inner_users) {
					$usersValue = array (
							'customer_code' => $this->customerCode,
							'site_id' => $this->siteId,
							'org_id' => $this->orgID,
							'users' => $inner_users
					);
					$CI->upload_task->saveTask ( ACCOUNT_UPDATE_UPLOAD, json_encode ( $usersValue ) );
// 					$CI->upload_task->saveTask ( 90, json_encode ( $usersValue ) );
					
					log_message('info', 'Assembler: ' . self::XML_BATCH_IMPORT_SIZE .' users update completed.' . 'site_id = ' . $this->siteId . ' batch_num=' . $k);
				}

				log_message('info', 'Assembler: All users upload completed. site_id = ' . $this->siteId);
			} elseif ($type == 'disable') {
				log_message('info', 'Assembler: There are ' . count($users) . ' users prepaered disable.');
				// 注意！！！ disable 操作,此处算法
				// $users 保存的是user_id数组，类似$users=[11, 33, 42, 456]的数组
				// 根据$users通过遍历得到所有用户的org_id,类似$org_ids=[aa, bb, aa, cc]的数组
				// 将两个数组合并，最终生成类似 [aa=>[11, 42], bb=>[33], cc=>[456]]的数组
				$usersValue = array(
					'customer_code' => $this->customerCode,
					'site_id' => $this->siteId,
					'user_ids' => $users
				);
				
// 				foreach ( $users as $user_id ) {
// 					$userOrg = $CI->umsLib->getOrganizationByUserId ( $user_id );
// 					if (! $userOrg) {
// 						log_message('error', 'Users disable failed at site_id=' . $this->siteId . ' user_id=' . $user_id);						
// 					}
// 					$org_ids[] = $userOrg['id'];			
// 				}
				
// 				foreach (array_combine($users, $org_ids) as $user=>$org) {
// 					$array_res[$org][] = $user;
// 				}

// 				foreach ($array_res as $org_id=>$org_users) {
					
// 					$users_res[] = array('org_id'=>$org_id, 'user_ids'=>$org_users);
// 				}
				
				// 多个组织批量导入
// 				$usersValue['users'] = $users_res;				
// 				$CI->upload_task->saveTask ( ACCOUNT_DISABLE_UPLOAD, json_encode ( $usersValue ) );

				// 单个组织导入
// 				foreach ($users_res as $u) {
// 					$usersValue['org_id']   = $u['org_id'];
// 					$usersValue['user_ids'] = $u['user_ids'];
// 					$CI->upload_task->saveTask ( ACCOUNT_DISABLE_UPLOAD, json_encode ( $usersValue ) );
// 					log_message('info', 'Assembler: ' . count($u['user_ids']) . ' users disable completed' . ' org_id=' . $u['org_id'] . ' and site_id = ' . $this->siteId);
// 				}
				
				// 新的disable格式
				$CI->upload_task->saveTask ( ACCOUNT_DISABLE_UPLOAD, json_encode ( $usersValue ) );
				log_message('info', 'Assembler: All users disable completed. site_id = ' . $this->siteId);
			} elseif ($type == 'delete') {
				log_message('info', 'Assembler: There are ' . count($users) . ' users prepaered delete.');
				$usersValue = array (
						'customer_code' => $this->customerCode,
						'site_id' => $this->siteId,
// 						'user_ids' => $usersJson 
						'user_ids' => $users
				);
				$CI->upload_task->saveTask ( ACCOUNT_DELETE_UPLOAD, json_encode ( $usersValue ) );
				log_message('info', 'Assembler: All users delete completed. site_id = ' . $this->siteId);
			}
		
		}	
		$this->finish();
		return true;
	}

	// 重置 import 状态。在每次调用 action 之后调用。
	public function reset() {
		Parser::$isImportable = 1;
	}
	
	// 根据导入成功与否， 判断backup缓存文件如何保存
	private function finish() {
		
		if (Parser::$isImportable == 2) {
			return;
		}
		
		$dir = APPPATH . '../data/xmlImport/backup/';		
		if (!is_dir($dir)) {
			log_message('warning', 'The ' . $dir . ' is not a directory.');
		}
			
		if ($dh = opendir($dir)) {
			$fileNames = array();
			while ($file = readdir($dh)) {
				if (preg_match('/^' . $this->siteId . '/iu', $file)) {
					$fileNames[] = $file;
				}					
			}

			array_multisort($fileNames, SORT_DESC, SORT_STRING);
			
			if (Parser::$isImportable == 1) {
				for($i=0; $i < count($fileNames); $i ++) {
					if ($i != 0) {
						unlink($dir . $fileNames[$i]);
					}
				}
				
			} 
			if (Parser::$isImportable == 0) {
				for($i=0; $i < count($fileNames); $i ++) {
					if ($i == 0) {
						rename($dir . $fileNames[0], $dir . 'bak_' . $fileNames[0]);
					} 
					
					if ($i > 1) {
						unlink($dir . $fileNames[$i]);
					}
				}
			}
		}
		
	}
}
	
