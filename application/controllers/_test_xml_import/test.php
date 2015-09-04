<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Test extends Admin_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->library('xmlImport/parser', '', 'parser');
		
		$this->load->library('umsLib', '','umsLib');
		
		$this->load->library('siteConfLib', '', 'siteConfLib');
		
		$this->load->model('uc_site_config_model', 'site_config');
	}
	
	public function testXML() {	
	
		$data['formats'] = Parser::getAllFormats();
		
// 		$data['types'] = array('create', 'update', 'delete', 'disable');
		$this->load->view('_test_xml_import/testXML.php', $data);
	}
	
	public function xmlResult() {
		
		$url = $this->input->get_post('url');
		$formatUrl = $this->input->get_post('format');				
		$site_id = $this->input->get_post('site_id');
		
		$this->load->library('xmlImport/assembler',
				array('format'=>$format, 'url'=>$url, 'siteId'=>$site_id),
				'assembler');

		$data['result'] = $this->assembler->action();
		
		$this->load->view('_test_xml_import/xmlResult.php', $data);
	}
	
	public function testUmsLib() {
		$data['site_info'] = $this->umsLib->getSiteInfoById('60473');
		print_r($this->umsLib->getOrganizationByUserId($this->p_user_id));
// 		print_r($this->umsLib->getOrgInfoByUserId($this->p_user_id));
		$this->load->view('_test_xml_import/testUms.php', $data);
		
	}
	
	public function testSiteConfLib() {
// 		$data['site_info'] = $this->siteConfLib->getImportType($this->p_site_id);
		$data['site_info'] = $this->site_config->getSiteId('ACCOUNT_AUTHENTICATION_TYPE', 'DATA_IMPORT_TYPE', 'xml');
		$this->load->view('_test_xml_import/testSite.php', $data);
	}
	
}