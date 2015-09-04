<?php if (!defined('BASEPATH'))	exit('No direct script access allowed');
require_once APPPATH . 'libraries/xmlImport/Parser.php';

/**
 * 转换器.获取xml，并将获得的用户信息分类转换为数组.
 * 
 * @author ge.xie
 * @deprecated
 */
class GeneralConverter {
	
	private $parser;
	private $config;
	
	// 通过__set()对站点信息进行添加
	// 临时添加
	private $type;
	private $site_id;
	private $customer_code;
	private $org_id;	
	
	public function __construct($format, $url) {			
		require_once APPPATH . 'config/tags2.php';

		$this->config = $config;				
		$this->parser = new Parser($format, $url);
			
	}
	
	/**
	 * 设置type、site_id、customer_code、org_id
	 * @param string $name
	 * @param string $value
	 */
	public function __set($name, $value) {
		$this->$name = $value;
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
	 * 从指定的地址获取xml文件，通过指定的xslt翻译，获得用户信息数组.
	 *
	 * 数据的合法性通过config/tags2.php定义的正则表达式判断.
	 * 
	 * @return array 返回用户信息数组.
	 * 返回的数组由tpye操作类型,valid用户数组和invalid用户数组组成
	 */
	public function getUsers() {
		require_once APPPATH . 'helpers/my_publicfun_helper.php';
		
		// 从tags2.php的$config中，提取各字段的pattern值，生成patterns_arr数组.
		$patterns = array_column($this->config['system_tags'], 'pattern');
		$keys = array_keys($this->config['system_tags']);
		$patterns_arr = array_combine($keys, $patterns);
		
		// 添加自定义字段的pattern到$patterns_arr数组.
		// attention！custom_tags的匹配定义与其他字段不在数组同一级
		$patterns_arr['custom_tags'] = $this->config['custom_tags']['pattern']; 
		
		$domDoc = $this->getDocument();
		
		// 使用patterns_arr对$docDoc中的用户进行遍历验证，返回结果数组
		$users_arr = $this->matchUsersParams($domDoc, $patterns_arr);
		return $users_arr;	
	}
	
	/**
	 * 
	 * @return DOMDocument
	 */
	private function getDocument() {

		$domDoc = $this->parser->parseXML();
		
		// schema检查
		// 此处为弱检查. 仅检查元素标签的有效性. 内容的有效性通过$this->matchUsersParams()进行检查.
		if ($domDoc->schemaValidate(APPPATH . 'libraries/xmlImport/standard-user.xsd')) {
			return $domDoc;
		} else {
			log_message('error', 'the xml is invalid, checked by schema.');
			return null;
		}
		return $domDoc;
	}
	
	/**
	 * 对xslt翻译的xml进行验证.
	 * 
	 * @param DOMDocument $domDoc<p>
	 * 经过xslt翻译过的xml DOMDocument</p>
	 * @param array $patterns_arr<p>
	 * 匹配规则组成的数组</p>
	 * @return array 返回valid用户数组和invalid用户数组,type等组成的数组
	 */
	private function matchUsersParams($domDoc, $patterns_arr) {
		
		$validUsers_arr   = array(); // 信息完整用户
		$invalidUsers_arr = array(); // 其他用户
		
		// xml的xpath对象
		$xpath = new DOMXPath($domDoc);
		
		// xml的根节点
		$tbody = $domDoc->getElementsByTagName('enterprise')->item(0);
		
		// type,customer_code,site_id,org_id
// 		$type          = $xpath->query('@type', $tbody)->item(0)->nodeValue;
// 		$customer_code = $xpath->query('customer_code', $tbody)->item(0)->nodeValue;
// 		$site_id       = $xpath->query('site_id', $tbody)->item(0)->nodeValue;
// 		$org_id        = $xpath->query('org_id', $tbody)->item(0)->nodeValue;
		// 临时添加
		$type          = $this->type;
		$customer_code = $this->customer_code;
		$site_id       = $this->site_id;
		$org_id        = $this->org_id;
		
		switch ($type) {
			case 'create':
				// 所有user节点
				$userElements = $xpath->query('users/user', $tbody);
				
				// 遍历<user>
				for ($i = 0; $i < $userElements->length; $i ++) {
					// 第i个user下所有param节点
					$paramElements = $xpath->query('param', $userElements->item($i));
				
					// 用户信息数组,根据下面的匹配判断添加到$validUsers_arr还是$invalidUsers_arr
					$user_arr = array();

					// 标志位
					$isValid = true;
					
					// 遍历<param>
					for ($j = 0; $j < $paramElements->length; $j ++) {
				
						$paramElement = $paramElements->item($j);
				
						$name  = $xpath->query('@name', $paramElement)->item(0)->nodeValue;
						$value = trim($xpath->query('@value', $paramElement)->item(0)->nodeValue);
						
						$user_arr[$name] = $value;
						
						// 对所有字段合法性进行验证
						if (array_key_exists($name, $patterns_arr)) {
							// 验证必选(除department)和可选字段内容
							if (!preg_match($patterns_arr[$name], $value)) {
								$isValid = false;
							}
						} else {
							// 验证各级department
							if (preg_match('/department([1-9]?|10)$/u', $name)) {
								if (!preg_match($patterns_arr['department'], $value)) {
									$isValid = false;
								}
							} 
							
							// 验证自定义字段内容
							if (!preg_match($patterns_arr['custom_tags'], $value)) {
								$isValid = false;
							}				
						}
					}
					
					// 有效用户信息保存在$validUsers_arr，无效保存在$invalidUsers_arr
					$isValid = true ? $validUsers_arr[] = $user_arr : $invalidUsers_arr[] = $user_arr;
				}		
				break;
			case 'update':
				break;
			case 'delete':
				break;
			case 'enable':
				break;
			case 'disable':
				break;
			default:
				log_message('error', 'Converter:Operate tpye is invalid.');
		}
				
		return array('type'             => $type, 
					 'customer_code'    => $customer_code,
					 'site_id'          => $site_id,
					 'org_id'           => $org_id,
					 'validUsers_arr'   => $validUsers_arr, 
					 'invalidUsers_arr' => $invalidUsers_arr				
		);		
	}
}
