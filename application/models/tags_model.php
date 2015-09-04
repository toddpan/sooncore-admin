<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 标签相关的model
 * @file tag_model.php
 * @author caohongliang <hongliang.cao@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */

class Tags_Model extends CI_Model{
	const valueMaxLength = '0';	
	
	public function __construct(){
		parent::__construct();
	
		$this->load->database(DB_RESOURCE);
	
		$this->tbl = array(
			'tags'			=>'uc_user_tags',
			'tags_value'	=>'uc_user_tag_value',
			'site'			=>'uc_site',
		);
	}
	
	/**
	 * 获取站点客户自定义标签名称
	 * @param int $site_id 站点id
	 * @return array
	 */
	public function getCustomTags($site_id){
		$query = $this->db->select('tag_name,id')->get_where($this->tbl['tags'], array('site_id'=>$site_id, 'enable'=>1, 'tag_type'=>2));
		return $query->num_rows() > 0 ? array_column($query->result_array(), 'tag_name','id') : array();
	}

	public function getCustomerTagsInfo($site_id){
		$query = $this->db->get_where($this->tbl['tags'], array('site_id'=>$site_id, 'enable'=>1, 'tag_type'=>2));
		return $query->num_rows() > 0 ? $query->result_array() : array();
	}
	
	/**
	 * 获取站点客户可选标签名称
	 * @param int $site_id
	 * @return  array
	 */
	public function getOptionalTags($site_id){
		$query = $this->db->select('id,tag_name')->get_where($this->tbl['tags'], array('site_id'=>$site_id, 'enable'=>1, 'tag_type'=>1));
		return $query->num_rows > 0 ? array_column($query->result_array(), 'tag_name', 'id') : array();
	}
	

	/**
	 * 不使用可选标签或自定义标签
	 */
	public function disableOptionalTag($ids){
		if(count($ids) > 0){
			$this->db->set('enable', '0');
			$this->db->where_in('id', $ids);
			
			$this->db-update($this->tbl['tags']);
		}
	}
	
	
	/**
	 * 获取站点标签名称
	 * @param int $site_id
	 * @return  array
	 */
	public function getTagsBySiteId($site_id){		
		$this->db->select('id, tag_name, tag_code, tag_type, enable, client_searchable, client_visible, client_editable, value_max_length, sequence');
		$query = $this->db->order_by("sequence")->get_where($this->tbl['tags'], array('site_id'=>$site_id));
		
		return $query->num_rows > 0 ? $query->result_array() : array();
	}
	
	/**
	 * 获取部门level
	 * @param int $site_id
	 * @return mixed
	 */
	public function getDepartmentLevels($site_id){
		$query = $this->db->select('department_level')->get_where($this->tbl['site'], array('siteID'=>$site_id));
		
		return $query->num_rows()>0 ? $query->first_row()->department_level : false;
	}
	
	/**
	 * 删除自定义标签
	 * @param array $retainedIds
	 * @param int $siteId
	 */
	public function deleteNotUseTags($retainedIds, $siteId){
		$this->db->where(array('site_id = ' => $siteId));
		
		if(!empty($retainedIds)){
			$this->db->where_not_in('id', $retainedIds);
		}
		
		return $this->db->delete($this->tbl['tags']);		
	}
	
	
	
	/**
	 * 保存用户自定义标签
	 * @param int 	$user_id
	 * @param array $customer_tags  array( '100'=>'你好', '200'=>'世界'); 100和200为tag_id
	 */
	public function saveCustomTags($user_id, $custom_tags){
		//根据tagId获取tag基本信息
		$tag_infos = array();
		foreach($custom_tags as $tag_id=>$tag_value){
			$rst = $this->db->get_where($this->tbl['tags'], array('id'=>$tag_id));
			if($rst->num_rows()>0){
				$tag_infos[$tag_id] = $rst->first_row('array');
			}else{
				unset($custom_tags[$tag_id]);
			}
		}
		
		//tag value插入或者修改
		foreach($tag_infos as $tag_id=>$tag_info){
			$where = array('tag_id'=>$tag_id, 'user_id'=>$user_id);
			$rst2 = $this->db->select('tag_name')->get_where($this->tbl['tags_value'], $where);
			if($rst2->num_rows() > 0){//修改
				$this->db->where($where)->update($this->tbl['tags_value'], array('tag_value'=>$custom_tags[$tag_id], 'modified'=>time()*1000));
			}else{//插入
				$insert_data = array(
					'user_id'	=>	$user_id,
					'tag_id' 	=>	$tag_id,
					'tag_name'	=>	$tag_info['tag_name'],
					'tag_value'	=>	$custom_tags[$tag_id],
					'tag_type'	=>	$tag_info['tag_type'],
					'tag_scope'	=>	$tag_info['tag_scope'],
					'created'	=>	time()*1000,
					'modified'	=>	time()*1000,
					'tag_flag'	=>	1,
				);
				$this->db->insert($this->tbl['tags_value'], $insert_data);
			}
		}
		
		return true;
	}
	
	
	
	
	
	
	
	public function getSiteTags($siteId, $pageUI = true){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		 
		$systemTags = $this->getSystemTagInfo($pageUI);
		 
		$tags = $this->getTagsBySiteId($siteId);
		 
		return array(
				"necessary" => $this->mergeNecessaryTags($systemTags, $tags),
				"optional" => $this->mergeOptionalTags($systemTags, $tags)
		);
	}
	
	private function mergeNecessaryTags($systemTags, $tags){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
	
		$necessaryTags = array();
	
		foreach($systemTags as $systemTag){
			if($systemTag['tag_type'] == 0 && $systemTag['enable'] == 1){				
				$temp = array(
						'js_pattern'	=> $systemTag['js_pattern'],
						'pattern'		=> $systemTag['pattern'],
						'umsapifield'	=> $systemTag['umsapifield'],
						'tag_name'		=> $systemTag['tag_name'],
						'tag_code'		=> $systemTag['id']
				);
				
				if(isset($systemTag['openEdit']) && $systemTag['openEdit'] === true){
					$temp['client_editable'] = '0';
					$temp['value_max_length'] = self::valueMaxLength;
				}
				
				foreach($tags as $tag){
					if($tag['tag_code'] == $temp['tag_code'] || $tag['tag_name'] == $temp['tag_name']){
						
						$temp['id'] = $tag['id'];
							
						if(isset($systemTag['openEdit']) && $systemTag['openEdit'] === true){
							$temp['client_editable'] = $tag['client_editable'];
							$temp['value_max_length'] = $tag['value_max_length'];
							
							if($temp['client_editable'] !== '0'){
								$temp['pattern'] = '/^.{0,' . $temp['value_max_length'] . '}$/u';
								$temp['js_pattern'] = '/^\S{0,' . $temp['value_max_length'] . '}$/';
							}
							
							break;
						}		
					}
				}
				
				$necessaryTags[] = $temp;
			}
		}
	
		return $necessaryTags;
	}
	
	private function getSystemTagInfo($pageUI){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
	
		$this->config->load('tags2');
		$system_tags = $this->config->item('system_tags');
	
		$tag_info = array();
		foreach($system_tags as $id=>$system_tag){
			if($system_tag['enable'] && (!$pageUI || ($pageUI && $system_tag['show']))){				
				$tmp = array();
	
				$tmp['id']              =  	$id;
				$tmp['js_pattern']      =  	$system_tag['js_pattern'];
				$tmp['pattern']      =  	$system_tag['pattern'];
				$tmp['tag_type']        =  	$system_tag['tag_type'];
				$tmp['umsapifield']     =  	$system_tag['umsapifield'];
				$tmp['enable']          =  	$system_tag['enable'];
				$tmp['openEdit']        = 	$system_tag['openEdit'];
	
				foreach($system_tag['alias'] as $alia){
					if($alia['lang'] == 'cn'){
						$tmp['tag_name'] = $alia['name'];
					}
				}
	
				$tag_info[] = $tmp;
			}
		}
	
		return $tag_info;
	}

	private function mergeOptionalTags($systemTags, $tags){
		log_message('info', 'into method ' . __FUNCTION__ . '.');
		$defaultOptionalPattern = $this->config->item('custom_tags');
	
		$optionalTags = array();
		foreach ($tags as $tag){
			$tagType = $tag['tag_type'];
			$tagCode = $tag['tag_code'];
	
			if($tagType == 1){
				$tagName = $tag['tag_name'];
					
				foreach ($systemTags as $systemTag){
	
					if($systemTag['id'] == $tagCode || $systemTag['tag_name'] == $tagName){
							
						$temp = array(
								'id' 				=> $tag['id'],
								'tag_name' 			=> $systemTag['tag_name'],
								'js_pattern'		=> $systemTag['js_pattern'],
								'pattern'			=> $systemTag['pattern'],
								'umsapifield'		=> $systemTag['umsapifield'],
								'tag_code'			=> $systemTag['id'],
								'tag_type'			=> $tagType,
								'selected'			=> $tag['enable'],
								'client_searchable'	=> $tag['client_searchable'],
								'client_visible'	=> $tag['client_visible'],
								'client_editable'	=> $tag['client_editable'],
								'value_max_length'	=> $tag['value_max_length']
						);
				
						if($temp['client_visible'] !== '0' && $temp['client_editable'] !== '0'){
							$temp['pattern'] = '/^.{0,' . $temp['value_max_length'] . '}$/u';
							$temp['js_pattern'] = '/^\S{0,' . $temp['value_max_length'] . '}$/';					
						}
						
						$optionalTags[] = $temp;
					}
				}
			}
			else if($tagType == 2){
				$temp = array(
						'id' 				=> $tag['id'],
						'tag_name' 			=> $tag['tag_name'],
						'js_pattern'		=> $defaultOptionalPattern['js_pattern'],
						'pattern'			=> $defaultOptionalPattern['pattern'],
						'tag_code'			=> $tagCode,
						'tag_type'			=> $tagType,
						'selected'			=> $tag['enable'],
						'client_searchable'	=> $tag['client_searchable'],
						'client_visible'	=> $tag['client_visible'],
						'client_editable'	=> $tag['client_editable'],
						'value_max_length'	=> $tag['value_max_length']
				);
				
				if($temp['client_visible'] !== '0' && $temp['client_editable'] !== '0'){
					$temp['pattern'] = '/^.{0,' . $temp['value_max_length'] . '}$/u';
					$temp['js_pattern'] = '/^\S{0,' . $temp['value_max_length'] . '}$/';					
				}
						
				$optionalTags[] = $temp;
			}
		}
	
		foreach($systemTags as $systemTag){
	
			$tagType = $systemTag['tag_type'];
	
			if($tagType == 1){
				$tagName = $systemTag['tag_name'];
				$tagCode = $systemTag['id'];
	
				$match = false;
	
				foreach($optionalTags as $optionalTag){
					if($optionalTag['tag_type'] == 1 && ($optionalTag['tag_code'] == $tagCode || $optionalTag['tag_name'] == $tagName)){
						$match = true;
						break;
					}
				}
	
				if(!$match){
					$optionalTags[] = array(
							'tag_name' 			=> $tagName,
							'tag_code'			=> $tagCode,
							'js_pattern'		=> $systemTag['js_pattern'],
							'pattern'			=> $systemTag['pattern'],
							'tag_type'			=> '1',
							'selected'			=> '0',
							'client_searchable'	=> '0',
							'client_visible'	=> '0',
							'client_editable'	=> '0',
							'value_max_length'	=> self::valueMaxLength
					);
				}
			}
		}
			
		return $optionalTags;
	}
}