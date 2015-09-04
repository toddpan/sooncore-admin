<?php if (!defined('BASEPATH')) exit('No direct script access allowed');



/**
 * xml 账号同步导入.导入的任务保存在 upload_task 表中.
 * 
 * @author ge.xie
 *
 * @version: v1.0
 */
class XmlImportToUploadTask extends  Task_Controller {
	
	public function __construct() {
		parent::__construct();
		require_once APPPATH . 'libraries/xmlImport/Assembler.php';
	}
	
	public function importTask() {
		log_message ( 'info', 'XmlImportToUploadTask:running in importTask.' );

		while (true) {
			$this->load->model('uc_site_config_model', 'site_config');			
			$sites = $this->site_config->getSiteId('ACCOUNT_AUTHENTICATION_TYPE', 'DATA_IMPORT_TYPE', 'xml');
			log_message('info', 'It has ' . count($sites) . ' sites to waiting import.....');
			try{
				if (is_array($sites)) {
					foreach ($sites as $site) {				
						if (! $this->run($site)) {
							continue;
						}
						
						sleep(SITE_INTERVAL_TIME);
					}
				} else {
					$this->run($sites);
					sleep(SITE_INTERVAL_TIME);
				}	
												
				log_message('info', 'The import task is sleeping.....');
				sleep(GLOBAL_INTERVAL_TIME);				
				continue;
			} catch(Exception $e){
				log_message('error', $e->getMessage());
			}
		}

	}
	
	/**
	 * 对某个站点完成uploadtask
	 */
	private function run($site) {
		
		$importmode = $this->site_config->getValue('ACCOUNT_AUTHENTICATION_TYPE', $site, 'DATA_IMPORT_TYPE');
		
		if ($importmode == 'xml') {
			$formaturl = $this->site_config->getValue('ACCOUNT_AUTHENTICATION_TYPE', $site, 'formaturl');
			$url    = $this->site_config->getValue('ACCOUNT_AUTHENTICATION_TYPE', $site, 'xmlurl');
// 			log_message('info', 'import site is '.$site.' and formaturl is ' . $formaturl . ' and url is ' .$url);
			log_message('info', 'import site is '. $site);
			$param = array(
					'siteId' => $site,
					'formatUrl' => $formaturl,
					'url'    => $url
					
			);
				
	//		$this->load->library('xmlImport/Assembler', $param, 'assembler');
			$callStartTime = microtime(true);
			
			$assembler = new Assembler($param);
			$result = $assembler->action();
			$assembler->reset();
			
			$callEndTime = microtime(true);
			$callTime = $callEndTime - $callStartTime;
			log_message('info', 'Site ' . $site . ' import data take ' . sprintf('%.4f',$callTime) . ' seconds total.');
			
			if (! $result) {
				
				log_message('info', 'This xml import is failed or not need to import. operated site_id = ' . $site);
				return false;
			} else {
			
				log_message('info', 'This xml import is finished. operated site_id = ' . $site);
				return $result;
			}
		}
	}
}
