<?php if (!defined('BASEPATH'))	exit('No direct script access allowed');

/**
 * 解析器.根据传入的xslt格式，将XML翻译为标准格式的XML.
 * 
 * @author ge.xie
 */
class Parser {
	
	const XML_IMPORT_PATH = '/data/ucadmin_data/xmlimport/';
	private $formatUrl;
	private $url;
	private $siteId;
	static $isImportable = 1; // 标志位，标志导入是否正常 0--错误，1--正常，2--无需导入
	
	/**
	 * 构造方法
	 * 
	 * @param string $siteId<p>
	 * 站点ID</p>
	 * @param string $url<p>
	 * xml文件的源地址</p>
	 * @param string $format<p>
	 * 用来翻译的 xslt 规则</p>
	 */
	public function __construct($siteId, $url, $formatUrl) {

		// 载入接口公用函数
		require_once APPPATH . 'helpers/my_httpcurl_helper.php';
		
		$this->formatUrl = $formatUrl;
		$this->url = $url;
		$this->siteId = $siteId;
	}
	
	/**
	 * 静态方法.扫描formats文件夹，获得所有存在的xslt规则.暂未实现递归查找.
	 * 
	 * 例如formats文件夹仅包含a.xsl、b.xsl文件，则返回[a,b]
	 * 
	 * @param null
	 * @return array 返回所有格式的数组
	 */
	public static function getAllFormats() {
		
		// xsl文件路径
		$dir = APPPATH . '../data/xmlImport/xslt/';
		
		$filesArray = array();
		
		if (is_dir($dir)) {
			if ($dh = opendir($dir)) {
				while ($file = readdir($dh)) {
					// 匹配*.xsl文件，不区分大小写
					if (preg_match('/.xsl$/iu', $file)) {
						$filesArray[] = $file;
					}										
				}

				closedir($dh);
			}
		}
		return $filesArray;
		
	}

	
	/**
	 * 根据传入的xslt规则，将XML翻译为标准格式.
	 * 
	 * 注意：由于需求改为仅通过上传方式提供xslt，故将其他方式停用！！！
	 * 
	 * @return DOMDocument 返回翻译后的DomDocument.
	 */
	public function parseXML() {
		// 加载format定义的DOMDocument
// 		if (preg_match('/^http:[\/\/]|^https:[\/\/]/', $this->formatUrl)) {
// 			$xslDoc = $this->getFormatFromHttp();
// 		} elseif (preg_match('/^(\/[a-zA-Z0-9._]+)*$/', $this->formatUrl)) {
// 			$xslDoc = $this->getFormatFromAbsFile();
// 		} elseif (strtolower($this->formatUrl) == '#upload#') {
			$xslDoc = $this->getFormatFromUploadFile();
// 		} else {
// 			$xslDoc = $this->getFormatFromRelFile();
// 		}
		
		if (! $xslDoc) {
			return false;
		}		
		// 根据$xslDoc生成翻译器
		$xsltParser = new XSLTProcessor;
		$xsltParser->importStyleSheet($xslDoc);

		$xmlDoc = new DOMDocument;
		
		// 通过$url获得返回的xml字符串
		// 注意：由于需求改为仅通过上传方式提供xml，故将其他方式停用！！！
// 		if (preg_match('/^http:[\/\/]|^https:[\/\/]/', $this->url)) {
// 			$xmlStr = $this->getXmlFromHttp($this->url);
// 		} elseif (preg_match('/^(\/[a-zA-Z0-9._-]+)*$/', $this->url)) {
// 			$xmlStr = $this->getXmlFromAbsFile($this->url);
// 		} elseif (strtolower($this->url) == '#upload#') {
			$xmlStr = $this->getXmlFromUploadFile();
// 		} else {
// 			$xmlStr = $this->getXmlFromRelFile($this->url);
// 		}
			
		if (!empty($xmlStr)) {
			$xmlDoc->loadXML($xmlStr);
		} else {
			self::$isImportable = 0;
			log_message('error', ' response xml is empty.');
			return false;
		}

		// 以DOMDocument格式返回翻译后的xml
		return $xsltParser->transformToDoc($xmlDoc);
		
// 		return $xmlDoc;
	}
	
	/**
	 * 从相对路径指向的文件中获取xml
	 * 
	 * @return void|boolean|string
	 */
	private function getXmlFromRelFile($relativePath) {
		
		$xmlDoc = new DOMDocument;
		$dir = APPPATH . '../data/xmlImport/xml/' . $this->siteId . '/' . $relativePath;
		if (!is_dir($dir)) {
			mkdir($dir);
		}
		if ($xmlDoc->load($dir)) {
			return $xmlDoc->saveXML();
		} else {
			self::$isImportable = 0;
			return;
		}
		
	}
	
	/**
	 * 从绝对路径指向的文件中获取xml
	 * 
	 * @return void|boolean|string
	 */
	private function getXmlFromAbsFile($absolutePath) {
		
		$xmlDoc = new DOMDocument;
		if ($xmlDoc->load($absolutePath)) {
			return $xmlDoc->saveXML();
		} else {
			self::$isImportable = 0;
			return;
		}
	}
	
	/**
	 * 通过传入的url获得请求字符串内容.
	 *
	 * @return string or false 如果请求返回码小于300且存在返回值，则将其返回.否则返回false.
	 */
	private function getXmlFromHttp($url) {
		// request类型
		$method = 'GET';
		$ret = Array();

		// 通过helper类的方法获得response信息数组
		$ret = httpCurl($url, null, $method);
		
		// 如果请求返回码小于300且存在返回值，则将其返回。否则返回null
		if($ret['code'] == 0  && isset($ret['data'])){
			return $ret['data'];
		}else{
			self::$isImportable = 0;
			log_message('error', 'HTTP response error, for request xml.');
			return false;
		}
	}
	
	/**
	 * 通过上传的文件获取xml的内容
	 *
	 * @return void|boolean|string
	 */
	private function getXmlFromUploadFile() {
		$absolutePath = self::XML_IMPORT_PATH . 'xml/upload/' . $this->siteId . '/';
		if (! is_dir($absolutePath)) {
			self::$isImportable = 0;
			log_message('error', 'Upload folder not exist, for request xml.');
			return false;
		}
		
		if ($dh = opendir($absolutePath)) {
			
			$w_fileNames = array(); // files wait for import
			$a_fileNames = array(); // already import
			while ($file = readdir($dh)) {
				if (preg_match('/^[0-9]/iu', $file)) {
					$w_fileNames[] = $file;
				}
				
				if (preg_match('/^(bak)/iu', $file)) {
					$a_fileNames[] = $file;
				}
			}
			
			if (count($a_fileNames) >= 5) {
				array_multisort($a_fileNames, SORT_DESC, SORT_STRING); 
				unlink($absolutePath . array_pop($a_fileNames));
			}
			
			array_multisort($w_fileNames, SORT_DESC, SORT_STRING); 
			$w_fileName = array_pop($w_fileNames);			
	
			$xmlDoc = new DOMDocument;			
			if (is_file($absolutePath . $w_fileName)) {
				if ($xmlDoc->load($absolutePath . $w_fileName)) {
					rename($absolutePath . $w_fileName, $absolutePath . 'bak_' . $w_fileName);// 加载后将文件改名
					return $xmlDoc->saveXML();
				} else {
					self::$isImportable = 0;
					return;
				}
			} else {
				$a_fileName = $a_fileNames[0];
				if ($xmlDoc->load($absolutePath . $a_fileName)) {
					log_message('info', 'Parser : The current import xml name is ' . $a_fileName);
					return $xmlDoc->saveXML();
				} else {
					self::$isImportable = 0;
					return;
				}
			}			
		}		
	}
	
// 	private function getXmlFromFtp() {		
//  		$xmlurl = $CI->site_config->getValue('ACCOUNT_AUTHENTICATION_TYPE', $this->siteId, 'xmlurl');		
//  		$conn = ftp_connect("$xmlurl");
		
// 		$conn = ftp_connect("localhost");
		
// 		ftp_login($conn,"ge.xie","1q2w3e4r5t");
		
// 		$files = ftp_nlist($conn,"dir1");
		
// 		foreach ($files as $file) {
			
// 		}
// 	}	
	
	/** 
	 * 通过本地文件获得需要的 xslt 的 DOMDocument
	 * 
	 * @return DOMDocument
	 */
	private function getFormatFromRelFile() {
		
		$xmlDoc = new DOMDocument;
		$dir = APPPATH . '../data/xmlImport/xslt/' . $this->formatUrl;
		if (! is_dir($dir)) {
			mkdir($dir);
		}
		if ($xmlDoc->load($dir)) {
			return $xmlDoc;
		} else {
			self::$isImportable = false;
			return false;
		}
		
	}
	
	/**
	 * 通过本地文件的绝对路径获得需要的xslt 的 DOMDocument
	 * 
	 * @return DOMDocument
	 */
	private function getFormatFromAbsFile() {
		
		$xmlDoc = new DOMDocument;
		if ($xmlDoc->load($this->formatUrl)) {
			return $xmlDoc;
		} else {
			self::$isImportable = 0;
			return false;
		}		
		
	}
	
	/**
	 * 通过uc_site_config表里的获得需要的 xslt 的 DOMDocument
	 *
	 * @return DOMDocument
	 */
	private function getFormatFromHttp() {
		
		$xslDoc = new DOMDocument;
				
		$format = $this->getXmlFromHttp($this->formatUrl);
	 	if (! $format) {
			return false;
		}	
		$xslDoc->loadXML($format);		
		return $xslDoc;
	}

	/**
	 * 通过上传后的文件获得xslt 的 DOMDocument
	 * 
	 * @return void|boolean|DOMDocument
	 */
	private function getFormatFromUploadFile() {
		$absolutePath = self::XML_IMPORT_PATH . 'xslt/upload/' . $this->siteId . '/';
		if (! is_dir($absolutePath)) {
			self::$isImportable = 0;
			log_message('error', 'Upload folder not exist, for request xslt.');
			return false;
		}
		if ($dh = opendir($absolutePath)) {
			
			$r_fileNames = array(); // files in xslt folder
			while ($file = readdir($dh)) {
				
				if (preg_match('/^[0-9]/iu', $file)) {
					$r_fileNames[] = $file;
				}
			}

			if (count($r_fileNames) >= 5) {
				array_multisort($r_fileNames, SORT_DESC, SORT_STRING);
				unlink($absolutePath . array_pop($r_fileNames));
			}
			
			$r_fileName = array_pop($r_fileNames);
		
			$xmlDoc = new DOMDocument;
			if (is_file($absolutePath . $r_fileName)) {
				if ($xmlDoc->load($absolutePath . $r_fileName)) {
					log_message('info', 'Parser : The current import xslt name is ' . $r_fileName);
					return $xmlDoc;
				} else {
					self::$isImportable = 0;
					return;
				}
			} else {
				self::$isImportable = 0;
				return;
			}			
		} 
	}
}
