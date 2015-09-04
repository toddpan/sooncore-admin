<?php if (!defined('BASEPATH'))	exit('No direct script access allowed');
require_once APPPATH . 'libraries/xmlImport/Parser.php';

/**
 * 转换器.获取type="compare"的 xml，并将获得的用户信息分类转换为数组.
 * 
 * 通过获得的 newXML 文件和上一次同步的 oldXML 进行比较，
 * 根据 <user> 标签的 login_name 属性，判断是否有某个用户，将相应的用户信息保存在对应数组中.
 * 
 * 具体逻辑为
 * oldXML(有) + newXML(有) => update
 * oldXML(有) + newXML(无) => delete 或 disable
 * oldXML(无) + newXML(有) => create
 * 
 * 得到的数组格式为
 * ['create':[], 'update':[], 'delete':[], 'site_id':'', 'org_id':'', 'customer_code':'']或者
 * ['create':[], 'update':[], 'disable':[], 'site_id':'', 'org_id':'', 'customer_code':''].
 * delete 还是 disable 通过传入的参数获得.
 * 
 * @author ge.xie
 *
 */
class CompareConverter {
	
	private $parser;
	private $siteId;		
	private $config;
	
	public function __construct($siteId, $url, $formatUrl) {		

		require APPPATH . 'config/tags2.php';
		
		$this->parser = new Parser($siteId, $url, $formatUrl);
		$this->siteId = $siteId;	
		$this->config = $config;
	}
	
	/**
	 * 静态方法.扫描formats文件夹，获得所有存在的xslt格式.暂未实现递归查找.
	 * 
	 * 例如formats文件夹仅包含a.xsl、b.xsl文件，则返回[a,b]
	 * 
	 * @return array 返回所有格式的数组
	 */
	public static function getAllFormats() {
		return Parser::getAllFormats();
	}
	
	/**
	 * 获取xml，并将获得的用户信息分类转换为数组.
	 *
	 * @return array
	 */
	public function saveClassifyUsers() {
		if (! Parser::$isImportable) {
			
			return false;
		}
		$dir = APPPATH . '../data/xmlImport/backup/';
	
		if (!is_dir($dir)) {
			log_message('warning', 'The ' . $dir . ' is not a directory.');
		}
	
		$newFileName = $dir . $this->siteId . '_' . time() . '.xml';
		$oldFileName = '';
	
		if ($dh = opendir($dir)) {
				
			// 判断写入前是否有 site_id 开头的文件，如果有，记录文件名
			$hasOldFile = false;
			while ($file = readdir($dh)) {
				if (preg_match('/^' . $this->siteId . '/iu', $file)) {
					$hasOldFile = true;
					$oldFileName = $dir . $file;
				}
			}
				
			$newFile = fopen($newFileName, 'w');
			$newDoc = $this->getDocument();
			if (! $newDoc) {
				Parser::$isImportable = 0;
				fclose($newFile);
				unlink($newFileName);
				
				return false;
			}
			fwrite($newFile, $newDoc->saveXML());
			fclose($newFile);

			$classifiedArray = array();
			if ($hasOldFile) {
				if (md5_file($oldFileName) == md5_file($newFileName)) {
					unlink($oldFileName);
					Parser::$isImportable = 2;
					return false;
				}
				
				$classifiedUsers = $this->classifyUserLoginNames($newFileName, $oldFileName);
				if (! $classifiedUsers) {
					
					return false;
				}
				$users = $this->getUsers($classifiedUsers, $newFileName, $oldFileName);
				
				// 将 unlink oldFile 的操作延迟处理，放在Assembler。如果用户处理失败，则不处理oldFile，备份newFile。
				// unlink($oldFileName);
				log_message('info', 'Converter: convert completed. OldFile=' . $oldFileName . ',NewFile=' . $newFileName);
				return $users;
			} else {
				$classifiedUsers = $this->classifyUserLoginNames($newFileName);
				if (! $classifiedUsers) {
					
					return false;
				}
				log_message('info', 'Converter: Users convert completed. NewFile=' . $newFileName);
				return $this->getUsers($classifiedUsers, $newFileName);
			}					
		}
	}
	
	/**
	 * 根据 $newFileName，$oldFileName，对用户帐号进行对比，并将账号分类放置在[create=>[], delete=>[], update=>[]]
	 */
	private function classifyUserLoginNames($newFileName, $oldFileName = null) {
		if (! Parser::$isImportable) {
			return false;
		}
		$CI = & get_instance();
		$CI->load->model('uc_site_config_model', 'site_config');
		
		$isDelete = $CI->site_config->getValue('ACCOUNT_AUTHENTICATION_TYPE', $this->siteId, 'deleable'); // delete or disable
		
// 		$newUsersLoginNameValues = $this->getUserParamValues($newFileName, 'loginname');
		$newUsersValues = $this->getUserParamValues($newFileName);
		$oldUsersValues = isset($oldFileName) ? 
			$this->getUserParamValues($oldFileName) : array();
			
		// diff(old, new) => delete/disable
		// diff(new, old) => create
		// diff(new, diff(new, old)) => update		
		// 对update进行特别操作。对于没有改变的用户信息，从update去除。
		$createUsersValues = array_diff_key($newUsersValues, $oldUsersValues);
		$deleableUsersValues = array_diff_key($oldUsersValues, $newUsersValues);
		$updateUsersValues = array_diff_key($newUsersValues, $createUsersValues);

		if (isset($oldFileName)) {

			foreach ($updateUsersValues as $key=>$updateUsersValue) {
				$hasChanged = count(array_diff($oldUsersValues[$key], $updateUsersValue)) != 0 
						   || count(array_diff($updateUsersValue, $oldUsersValues[$key])) != 0;
				if (! $hasChanged) {
					unset($updateUsersValues[$key]);
				}
			}
		}
		
		log_message('info', 'Converter: All users has been classifid to various types.');

		return array(
				'create'=>$createUsersValues,
				'update'=>$updateUsersValues,
				(empty($isDelete) ? 'disable' : 'delete') => $deleableUsersValues
		);
	}
	
	/**
	 * 获得标准xml文件中指定param值组成的数组.
	 * 
	 * paramName为某个属性值(如email)时,返回['email1', 'email2', 'email3', 'email4'……]
	 * paramName为 null 时，返回['loginname1'=>['loginname'=>'abc', 'email'=>'123@gmail'……], 'loginname2'=>[]……]
	 */
	private function getUserParamValues($fileName, $paramName = null) {
		if (! Parser::$isImportable) {
			return false;
		}

		$domFile = new DOMDocument;
		$domFile->load($fileName);
		
		if ($paramName != null) {
			// xml的xpath对象
			$xpath = new DOMXPath($domFile);	
			// xml的根节点
			$tbody = $domFile->getElementsByTagName('users')->item(0);
			
			// 选取所有<param name="[ $paramName ]" value="***">的所有param元素
			$usersParams = $xpath->query("//param[@name='" . $paramName . "']", $tbody);
			
			$usersParamValues = array();
			
			// 遍历$usersParams，获得其中所有 param 的 value 值保存在 $usersParamValue 数组
			for ($i = 0; $i < $usersParams->length; $i ++) {
				$userParam = $usersParams->item($i);
				$value = trim($xpath->query('@value', $userParam)->item(0)->nodeValue);
				
				$usersParamValues[] = $value;
			}
		} else {
			$simpleXml = simplexml_import_dom($domFile);
			
			$usersParamValues = array();

			$counter = 0; // 定义计数器
			foreach ($simpleXml->users->user as $user) {
				$userParamValues = array();
				foreach ($user->param as $param) {	
					$userParamValues[(string)$param['name']] = (string)$param['value'];
					if ($param ['name'] == 'loginname') {
						$loginname = strtolower((string)$param['value']);
					}					
				}
												
				if (isset($usersParamValues[$loginname])) {
					$counter ++;
					log_message('info', 'Converter: The loginname ' . $loginname . ' has duplicates.');
				} 
				
				$usersParamValues[$loginname] = $userParamValues;
			}
			log_message('error', 'Converter: There are ' . $counter . ' users are duplicate.');
		}
		
		return $usersParamValues;
	}

	/**
	 * @deprecated
	 * 根据标准xml文件和用户loginname获得用户所有信息
	 *
	 */
	private function getUserParams($fileName, $loginName) {
		if (! Parser::$isImportable) {
			return false;
		}
		$domFile = new DOMDocument;
		$domFile->load($fileName);
		
		// xml的xpath对象
		$xpath = new DOMXPath($domFile);
		// xml的根节点
		$tbody = $domFile->getElementsByTagName('users')->item(0);
		
		// 选取value=loginName对应的param元素。
		$userLogin = $xpath->query("//param[@value='" . $loginName . "']", $tbody);
		
		$userParams = $xpath->query("parent::*/child::*", $userLogin->item(0));
		
		$userParamValues = array();
		
		for ($i = 0; $i < $userParams->length; $i ++) {
			$userParam = $userParams->item($i);
			$name = trim($xpath->query('@name', $userParam)->item(0)->nodeValue);
			$value = trim($xpath->query('@value', $userParam)->item(0)->nodeValue);
			
			$userParamValues[$name] = $value;
		}
		
		return $userParamValues;
	}
	
	/**
	 * 根据$classifiedUsers将用户信息进行分类
	 */
	private function getUsers($classifiedUsers, $newFileName, $oldFileName=null) {
		if (! Parser::$isImportable) {
			return false;
		}		
		$users = array();
		
		$callStartTime = microtime(true);
		
		$users['create'] = $this->matchUsersParams($classifiedUsers, 'create');
		
		// delete/disable从旧文件添加
		// create/update从新文件添加
		if (isset($oldFileName)) {
			if (array_key_exists('delete', $classifiedUsers)) {
				$users['delete'] = $this->matchUsersParams($classifiedUsers, 'delete');
			} elseif (array_key_exists('disable', $classifiedUsers)) {
				$users['disable'] = $this->matchUsersParams($classifiedUsers, 'disable');
			}
		}
		
		$users['update'] = $this->matchUsersParams($classifiedUsers, 'update');
		
		log_message('info', 'Converter: All users has been checked and organized.');
		return $users;
	}
	
	/**
	 * 从$classifiedUsers获取数据，进行校验和保存
	 *
	 * 数据的合法性通过config/tags2.php定义的正则表达式判断.
	 * 
	 * @return array 返回用户信息数组.
	 * 返回的数组由tpye操作类型,valid用户数组和invalid用户数组组成.
	 * 其中create, update, delete, disable 按照 update_task 表的 value 字段要求进行封装
	 */
	private function matchUsersParams($classifiedUsers, $type) {
			
		if (! Parser::$isImportable) {
			return false;
		}
		
// 		require_once APPPATH . 'helpers/my_publicfun_helper.php';
		
		$validUsers   = array(); // 信息完整用户
		$invalidUsers = array(); // 其他用户
		
		// 从tags2.php的$config中，提取各字段的pattern值，生成patterns_arr数组.
// 		$patterns = array_column($this->config['system_tags'], 'pattern');
// 		$keys = array_keys($this->config['system_tags']);
// 		$patterns_arr = array_combine($keys, $patterns);
		
		// 添加自定义字段的pattern到$patterns_arr数组.
		// attention！custom_tags的匹配定义与其他字段不在数组同一级
// 		$patterns_arr['custom_tags'] = $this->config['custom_tags']['pattern'];
	
		// type操作的用户，从相应的File里添加信息
		foreach ($classifiedUsers[$type] as $userLoginName=>$user) {			
			// 通过调用 umsLib 的 getUserByLoginName 方法获得user_id
			$CI = & get_instance();
			$CI->load->library('umsLib', '','umsLib');
			
			foreach ($user as $name=>$value) {				
				// 标志位
				$isValid = true;
				
				// 对所有字段合法性进行验证
				if ($name == 'loginname') {
					// 验证必选(除department)和可选字段内容
					if (!preg_match('/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9_-]+\.[a-zA-Z0-9-.]+$/', $value)) {
						$isValid = false;
						break;
					}
				} else {
					// 验证各级department
					if (preg_match('/^department([1-9]?|10)$/', $name)) {
// 						if (!preg_match($patterns_arr['department'], $value)) {						
						if (empty($value)) {
							$isValid = false;
							break;
						}
					} 
				}
			}
				
			// 在此处按照 update_task 表的 value 字段要求进行封装
			if ($type == 'update') {
				
				unset($user['account']);
				$temp_user = $CI->umsLib->getUserByLoginName($user['loginname']);
				
				if ($temp_user) {
					$user['id'] = $temp_user['id'];
				} else {
					$user['id'] = 0;
				}
				
				// 不存在 user_id 的，直接丢弃
				if (! empty($user['id'])) {
					$isValid ? $validUsers[] = $user : $invalidUsers[] = $user;
				}				
				
			} elseif ($type == 'delete' || $type == 'disable') {
								
				$temp_user = $CI->umsLib->getUserByLoginName($user['loginname']);
				unset($user);
				if ($temp_user) {
					$user['id'] = $temp_user['id'];
				} else {
					$user['id'] = 0;
				}
				// 不存在 user_id 的，直接丢弃
				if (! empty($user['id'])) {
					$isValid ? $validUsers[] = $user['id'] : $invalidUsers[] = $user['id'];
				}
				
			} elseif ($type == 'create') {
				// displayname对create操作无效
				unset($user['displayname']);
				// 有效用户信息保存在$validUsers_arr，无效保存在$invalidUsers_arr				
				$isValid ? $validUsers[] = $user : $invalidUsers[] = $user;
			}			
		}
		
		log_message('error', 'Converter: ' . $type . ' type users all has been checked and organized. ' . count($invalidUsers) . ' users was invalid.');

		return $validUsers;
	}
	
	/**
	 *
	 * @return DOMDocument
	 */
	private function getDocument() {
	
		if (! Parser::$isImportable) {
			return false;
		}
		
		$domDoc = $this->parser->parseXML();
		
		if (! $domDoc) {
			Parser::$isImportable = 0;
			return false;
		}
		
		// schema检查
		// 此处为弱检查. 仅检查元素标签的有效性. 内容的有效性通过$this->matchUsersParams()进行检查.
		if ($domDoc->schemaValidate(APPPATH . 'libraries/xmlImport/standard-user.xsd')) {
			return $domDoc;
		} else {
			log_message('error', 'the xml is invalid, checked by schema.');
		}
		Parser::$isImportable = 0;
		return false;
	}
}
