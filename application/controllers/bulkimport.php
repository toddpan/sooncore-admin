<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @category BulkImport
 * @abstract BulkImport Controller，主要负责对批量导入的管理，包含csv模板下载、csv模板上传、csv模板解析等。
 * @details
 * -# 系统初始化时：1、系统管理员在设置完员工标签，如果系统是批量导入，则点[完成]按钮时进行批量导入。2、渠道管理员、组织管理员通过首页[批量导入]按钮进行批量导入
 * -# 系统完成初始化后：批量导入功能在组织、员工列表页面右上角[导航处]下拉有[批量导入]按钮，进行批量导入操作。
 * @filesource BulkImport.php
 * @author zouyan <yan.zou@quanshi.com>  Bai Xue <xue.bai_2@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
class BulkImport extends Admin_Controller {
	/**
	 * @abstract 构造函数
	 */
	public function __construct() {
		parent::__construct();
		log_message('info', 'into class ' . __CLASS__ . '.');
		$this->load->helper('my_publicfun');
		$this->load->helper('my_httpcurl');
		$this->load->library('API','','API');
		$this->lang->load('upload', 'chinese');
	}

	/**
	 * @abstract 批量导入页面：
	 * @details
	 * -# 获得员工标签信息--?需要接口2.2.1
	 * -# 将员工标签信息分配到批量导入视图
	 * -# 视图可进行的操作：通过[下载模板]按钮下载CSV模板文件、通过[上传文档]按钮上传CSV模板文件
	 */
	public function showBulkImportPage() {
		log_message('info', 'into method ' . __FUNCTION__ . '.');

		// 站点ID
		$site_id = $this->p_site_id;

		try {
			// 从管理员表获取部门层级
			$this->load->model('UC_Site_Model');
			$data_sel = array(
                'select' =>'department_level',
                'where' => array('siteID' => $site_id)
			);
			$uc_site_arr = $this->UC_Site_Model->operateDB(1,$data_sel);
			if(!isemptyArray($uc_site_arr)){
				$department_level = isset($uc_site_arr['department_level'])?$uc_site_arr['department_level']:0;
				log_message('debug', 'get department level ' . $department_level . '  success.');
			}else{
				log_message('error', 'get department level fail');
			}
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			exit();
		}

		$this->load->model('UC_User_Tags_Model');

		try {
			//从数据库获得系统可选标签及自定义员工标签信息
			$tag_arr =  $this->UC_User_Tags_Model->get_tags_by_siteid($site_id,1);
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			exit();
		}

		//载入员工标签资源
		include_once APPPATH . 'libraries/public/Tag_class.php';

		try {
			$tag_obj = new Tag_class(1);//1下载模板页
			//获得当前系统有的标签系统
			$tag_obj->resolve_tag($tag_arr , $department_level);
			$sys_all_tag_names = $tag_obj->get_all_tag_names();
			log_message('info', 'get all tag names success.');
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			exit();
		}

		//转换为数组
		try {
			$sys_all_tag_names_arr = explode(",", $sys_all_tag_names);
			if( is_array($sys_all_tag_names_arr) ){
				log_message('info', 'explode all tag names to arr success.');
			}else{
				log_message('debug', 'explode all tag names to arr fail');
			}
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			exit();
		}
		$data['all_tag_names_arr'] =  $sys_all_tag_names_arr;
		$this->load->view('bulkimport/bulkimport0.php',$data);
		log_message('info', 'out method ' . __FUNCTION__ . '.');
	}

	/**
	 * @abstract 下载excel2007模板文件
	 * @details
	 * -# 从页面获得员工标签信息
	 * -# 将员工标签信息生成excel文档流
	 */
	public function downExcelFile() {
		log_message('info', 'into method ' . __FUNCTION__ . '.');

		$site_id = $this->p_site_id;

		//从管理员表获取部门层级
		try {
			$this->load->model('UC_Site_Model');
			$data_sel = array(
                'select' =>'department_level',
                'where' => array('siteID' => $site_id)
			);
			$uc_site_arr = $this->UC_Site_Model->operateDB(1,$data_sel);
			if( is_array($uc_site_arr) ){
				log_message('info', 'get department level array  success.');
			}else{
				log_message('debug', 'get department level array fail');
			}
			$department_level = isset($uc_site_arr['department_level'])?$uc_site_arr['department_level']:0;
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			exit();
		}

		try {
			$this->load->model('UC_User_Tags_Model');
			//从数据库获得系统可选标签及自定义员工标签信息
			$data_tags = array(
                'select' =>'id,site_id,tag_name,tag_scope,tag_type,enable',
                'where' => array('site_id =' => $site_id, 'enable =' => 1),
			);
			$tag_arr =  $this->UC_User_Tags_Model->operateDB(2,$data_tags);
			if( is_array($tag_arr) ){
				log_message('info', 'get User Tags array  success.');
			}else{
				log_message('debug', 'get User Tags array fail');
			}
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			exit();
		}

		//载入员工标签资源
		try {
			include_once APPPATH . 'libraries/public/Tag_class.php';

			$tag_obj = new Tag_class(3);//3 生成标签文档

			//获得当前系统有的标签系统
			$tag_obj->resolve_tag($tag_arr , $department_level);
			$sys_all_tag_names = $tag_obj->get_all_tag_names();

			//转换为数组
			$sys_all_tag_names_arr = explode(",", $sys_all_tag_names);

			$tag_arr = $sys_all_tag_names_arr;
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			exit();
		}

		try {
			//生成excel文件
			$this->load->helper('my_phpexcel');

			$file_arr = array(
			// 'file_path' => 'data/file/',//文件路径，相对于站点目录：形式: 文件夹/../文件夹/
			// 'file_name' => '',//文件名称,注意没有文件后缀,如aaaa
			);
			$re_filename = create_excel(array($tag_arr),'07',$file_arr,0);
			//$file_url = $this->web_root_url . $re_filename;
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			exit();
		}
		log_message('info', 'out method ' . __FUNCTION__ . '.');
	}

	/**
	 * @abstract 上传的CSV\exe文档，并做相关条件判断，异步解析上传文件数据到数据库;
	 * 如果有失败记录，则提供[下载失败列表]按钮，用户点击，下载失败记录文档
	 * 注意exe需要支持07、10、13
	 * @details
	 * -# 判断文档格式是不是CSV格式，不是则提示：您提供的档案格式不正确，请选择 CSV 格式的文档
	 * -# 判断文档大小是不是小于10M，不是则提示：您只能上传小于10M的文档
	 * -# 获得文档内容，进行以下判断，同时统计能成功导入数量和不能成功导入数量:
	 * -# 根据用户所在的企业（生态企业）从UCCServer获得员工标签信息
	 * -# 判断文档标签是否有改动，有改动则提示：导入的文件与定义的模板不一致,[不继续解析上传文档]
	 * -# 判断模板信息标签是否都填写完整，不完整则提示：模板信息标签未填写完整,[此条记录会组织到失败页面，供用户下载]
	 * -# 判断手机或邮箱格式是否正确，手机或邮箱格式不正确则提示：如：手机位数不对，邮箱格式不正确,[此条记录会组织到失败页面，供用户下载]
	 * -# 判断是否有手机或邮箱信息相同的员工，有则提示：手机或邮箱信息相同,[此条记录会组织到失败页面，供用户下载]
	 * -# 异步调用解析文档页保存员工信息
	 * -# 保存员工信息成功数量，失败数量
	 * -# 如果都成功，则展示都成功页面;如果有失败数量，则根据不同的失败原因，展示不同的失败内容。
	 * -# 写日志[初始化执行导入]、[执行导入]
	 */
	public function upCSVFile() {
		// 操作类型：0、批量导入；1、批量修改
		$operate_type = $this->uri->segment(3);
		// 获得要上传的文件
		$filename = $this->input->get('filename', true);// 2014_07_24 (2).xlsx
		log_message('info', __FUNCTION__." input->\n".var_export(array('operate_type' => $operate_type, 'file_name' => $filename), true));

		// 初始化错误提示信息
		$err_message = '';
		// 初始化错误类型：0.无错；1.文件类型和大小错误；2.文件模板与系统模板不一致；3.文件内容不正确
		$err_type = 0;

		// 为操作类型设置默认值
		$operate_type = empty_to_value($operate_type, 0);

		$data_arr['operate_type'] = $operate_type;

		if(!preg_match('/^[01]$/', $operate_type)){
			$err_type = 1;
			$data_arr['err_type'] = $err_type;
			$data_arr['err_msg'] = $this->lang->line('upload_operate_illegal');// 非法操作
			log_message('error', __FUNCTION__." output->\n".var_export($data_arr, true));
			$this->load->view('bulkimport/partsuccess.php', $data_arr);
			return;
		}

		// 对文件进行有效性验证  扩展名(xls xlsx csv)
		$suffix = get_file_extension($filename);
		if($suffix != 'xls' && $suffix != 'xlsx' && $suffix != 'csv'){
			$err_message = $this->lang->line('upload_file_suffix_error');//'您提供的档案格式不正确，请选择 CSV、XLS、XLSX格式的文档！';
			$err_type = 1;
			$data_arr['err_msg'] = $err_message;
			$data_arr['err_type'] = $err_type;
			log_message('info', __FUNCTION__." output->\n".var_export($data_arr, true));
			$this->load->view('bulkimport/partsuccess.php', $data_arr);
			return;
		}

		$filePath = BULKIMPORT_FILE_PATH . $filename;

		// 文件大小（字节）
		$file_size = filesize($filePath);
		// 上传文件大小的限制
		if( $file_size > FILE_MAX_SIZE){
			$err_message = $this->lang->line('upload_file_size_max');//'您只能上传小于10M的文档';
			$err_type = 1;
			$data_arr['err_msg'] = $err_message;
			$data_arr['err_type'] = $err_type;
			log_message('info',__FUNCTION__." output->\n".var_export($data_arr, true));
			$this->load->view('bulkimport/partsuccess.php', $data_arr);
			return;
		}

		// 加载Excel辅助函数
		$this->load->helper('my_phpexcel');

		// 获得解析的文档
		$excel_arr = getExcelContent($filePath);

		if(isemptyArray($excel_arr)){
			$err_message = $this->lang->line('upload_file_data');//'您上传的文档没有记录';
			$err_type = 1;
			$data_arr['err_msg'] = $err_message;
			$data_arr['err_type'] = $err_type;
			log_message('info',__FUNCTION__." output->\n".var_export($data_arr, true));
			$this->load->view('bulkimport/partsuccess.php', $data_arr);
			return;
		}

		// 获取表头数据
		$excel_header_arr = $excel_arr[1];

		if(isemptyArray($excel_header_arr)){
			$err_message = $this->lang->line('upload_file_null_header');//'表头有误，请重新上传！';
			$err_type = 1;
			$data_arr['err_msg'] = $err_message;
			$data_arr['err_type'] = $err_type;
			log_message('info',__FUNCTION__." output->\n".var_export($data_arr, true));
			$this->load->view('bulkimport/partsuccess.php', $data_arr);
			return;
		}

		// 以下内容为获得当前系统标签
		// 站点ID
		$site_id = $this->p_site_id;

		// 从管理员表获取部门层级
		$this->load->model('UC_Site_Model');

		$data_sel = array(
            'select' =>'department_level',
            'where' => array('siteID' => $site_id)
		);
		$uc_site_arr = $this->UC_Site_Model->operateDB(1, $data_sel);
		$department_level = isset($uc_site_arr['department_level'])?$uc_site_arr['department_level']:0;


		$this->load->model('UC_User_Tags_Model');

		// 从数据库获得可用的系统可选标签及自定义员工标签信息
		$data_tags = array(
            'select' =>'id,site_id,tag_name,tag_scope,tag_type,enable',
            'where' => array('site_id =' => $site_id, 'enable =' => 1)
		);
		$tag_arr =  $this->UC_User_Tags_Model->operateDB(2, $data_tags);

		// 自定义标签[]Array ( [0] => Array ( [tag_id] => 103 [tag_name] => bbbb ) [1] => Array ( [tag_id] => 109 [tag_name] => ccdd ) )
		$user_tags_arr = array();
		foreach($tag_arr as $k => $v){
			$ns_tag_type = isset($v['tag_type'])?$v['tag_type']:0;
			// 标签类型1-基本标签2-自定义
			if($ns_tag_type == 2){
				// 标签id
				$ns_tag_id = isset($v['id'])?$v['id']:0;
				// 标签名称
				$ns_tag_name = isset($v['tag_name'])?$v['tag_name']:'';
				$ns_tag_arr = array();
				$ns_tag_arr['tag_id'] = $ns_tag_id;
				$ns_tag_arr['tag_name'] = $ns_tag_name;
				$user_tags_arr[] = $ns_tag_arr;
			}
		}

		// 载入员工标签资源
		include_once APPPATH . 'libraries/public/Tag_class.php';

		// 3生成标签文档
		$tag_obj = new Tag_class(3);
		// 获得当前系统有的标签
		$tag_obj->resolve_tag($tag_arr, $department_level);

		// 总标签,当前站点所有的标签,多个用逗号分隔
		$sys_all_tag_names = $tag_obj->get_all_tag_names();

		// 当前站点所有的标签数组
		//Array ( [0] => 姓名 [1] => 帐号 [2] => 开启帐号 [3] => 性别 [4] => 部门一级 [5] => 部门二级 [9] => 手机 [10] => 国家[11] => 办公室所在地区 [12]=>邮箱
		$sys_all_tag_arr = array();
		if(!bn_is_empty($sys_all_tag_names)){
			$sys_all_tag_arr = explode(",", $sys_all_tag_names );
		}

		// 自定义标签,多个用逗号分隔
		$user_defined_tag_names = $tag_obj->get_user_defined_tag_names();

		// 获取Tag_class中的所有系统标签[可选和必选]
		$all_tag_arr = $tag_obj->get_tag();

		// 获取部门标签,多个用逗号分隔
		$department_tags = $tag_obj->get_department_tag_names();

		// 部门标签名,转换为数组 Array ( [0] => 部门一级 [1] => 部门二级 [2] => 部门三级 [3] => 部门四级 )
		$department_tag_name_arr = array();
		if(!bn_is_empty($department_tags)){
			$department_tag_name_arr=explode(",", $department_tags);
		}

		// 对标签头进行比对
		// 正确标头信息数组[都有的]$head_success_arr
		$head_success_arr = array();
		// 错误标头信息数组[上传文件有;系统没有]$head_fail_arr
		$head_fail_arr = array();
		// 错误标头信息数组[系统有;上传文件没有]
		$head_sys_fail_arr = array();
		// 重复标头信息数组[上传标签中]
		$head_repeat_arr = array();

		// 记录遍历的标签，多个用逗号分隔
		$head_notrepeat_tags = '';
		// 遍历上传文件的表头,只要有一个不匹配，则标签不对
		foreach ($excel_header_arr as $header_v ){
			// 遍历系统总标签数组，比对
			if(in_array($header_v, $sys_all_tag_arr)){
				$head_success_arr[]=$header_v;
			}else{
				$head_fail_arr[]=$header_v;
			}

			// 判断标签是否有重复
			// 判断标签名是否已经存在
			if ( !strstr(',' . $head_notrepeat_tags . ',', ',' . $header_v . ',')){
				// 不存在
				if(!bn_is_empty($head_notrepeat_tags)){
					$head_notrepeat_tags .= ',';
				}
				$head_notrepeat_tags .= $header_v;
			}else{
				// 重复
				$head_repeat_arr[] = $header_v;
			}
		}

		// 错误标头信息数组[系统有;上传文件没有]
		$head_sys_fail_arr = array_diff($sys_all_tag_arr, $head_success_arr);

		if(!isemptyArray($head_fail_arr) || !isemptyArray($head_repeat_arr)){
			// 如果不是空数组： '上传文件有不是系统有的标签！';
			$err_message = $this->lang->line('upload_file_header_error');//上传文件有不是系统有的标签！;
			$err_type = 2;
			$data_arr['err_msg'] = $err_message;
			$data_arr['err_type'] = $err_type;
			$data_arr['system_tag_head'] = $sys_all_tag_arr;
			$data_arr['excel_head'] = $excel_header_arr;
			log_message('info',__FUNCTION__." output->\n".var_export($data_arr, true));
			$this->load->view('bulkimport/partsuccess.php', $data_arr);
			return;
		}

		// 对应表头下标
		// 部门标签在文档中下标数组[部门标签顺序很重要-一定要安系统生成的部门标签顺序来做]
		$department_suffix_arr=array();
		// 自定义标签下标数组
		$user_suffix_arr = array();
		// 系统标签下标数组
		$sys_suffix_arr = array();
		// 没有在[部门、自定义、系统]标签中的标签
		$no_suffix_arr = array();

		// 开启帐号及性别这样的标签
		$change_tag_arr = array('1' => '开启帐号', '2' => '性别');
		// 开启帐号及性别这样的字段下标数组
		$change_suffix_arr = array();
		// 开启帐号数组
		$isopen_tag_arr = array('1' => '开启帐号');
		// 开启帐号下标
		$isopen_suffix = '0';
		// 帐号数组
		$loginName_tag_arr = array('1' => '帐号');
		// 帐号下标
		$loginName_suffix = '0';
		// 正则部分
		// 标头相同下标的正则数组
		$head_regex = array();
		// 部门标签正则部门标签，其规则为100个中英文数字
		$department_regex = '/^[\s\S]{1,100}$/';
		// 自定义标签正则自定义标签，其规则为300个中英文数字符号
		$user_defined_regex = '/^[\s\S]{1,300}$/';

		// 标签模板
		// 部门标签信息数组模板[二维]
		$department_name_arr = array();
		// 自定义标签数组模板[二维]
		$user_define_tag_arr = array();
		// 系统标签数组模板[二维]
		$sys_tag_arr = array();
		foreach ($excel_header_arr as $k2=>$header_v){
			// 1为已获取规则，0为未获取规则
			$had_regex=0;
			// 部门标签
			if ( strstr(',' . $department_tags . ',', ',' . $header_v . ',')){
				// 如果是部门标签，其规则为100个中英文数字
				$head_regex[$k2]=$department_regex;
				$department_suffix_arr[$k2]=$k2;
				$had_regex=1;
				foreach($department_tag_name_arr as $n_k => $n_v){
					if($n_v == $header_v){
						// 值相等
						$department_name_arr[$k2]['name'] = $n_v;// 标签名
						$department_name_arr[$k2]['suffix'] = $k2;// 下标
						$department_name_arr[$k2]['value'] = '';// 值
						$department_name_arr[$k2]['regex'] = $department_regex;// 正则
						break;
					}
				}
				// 自定义标签
			}else if( strstr(',' . $user_defined_tag_names . ',', ',' . $header_v . ',')){
				// 如果是自定义标签，其规则为300个中英文数字符号
				$head_regex[$k2] = $user_defined_regex ;
				$user_suffix_arr[$k2] = $k2;
				$had_regex=1;
				// 更新自定义标签
				foreach($user_tags_arr as $ut_v){
					$tag_name = isset($ut_v['tag_name'])?$ut_v['tag_name']:'';
					if($tag_name == $header_v){// 标签相等
						$tag_id = isset($ut_v['tag_id'])?$ut_v['tag_id']:0;
						$user_define_tag_arr[$k2]['tag_name'] = $tag_name;// 标签名
						$user_define_tag_arr[$k2]['tag_id'] = $tag_id;// 标签id
						$user_define_tag_arr[$k2]['suffix'] = $k2;// 下标
						$user_define_tag_arr[$k2]['value'] = '';// 值
						$user_define_tag_arr[$k2]['regex'] = $user_defined_regex;// 正则
						break;
					}
				}
				//其它系统标签
			}else{
				foreach ($all_tag_arr as $value){
					$tag_title = isset($value['title'])?$value['title']:'';
					if($header_v==$tag_title){
						$tag_regex = isset($value['regex'])?$value['regex']:'';
						$tag_field = isset($value['field'])?$value['field']:'';
						$tag_usmapifield = isset($value['umsapifield'])?$value['umsapifield']:'';

						$head_regex[$k2]=$tag_regex;
						$sys_suffix_arr[$k2] = $k2;
						$had_regex=1;

						$sys_tag_arr[$k2]['name'] = $tag_title;// 名称
						$sys_tag_arr[$k2]['suffix'] = $k2;// 下标
						$sys_tag_arr[$k2]['value'] = '';// 值
						$sys_tag_arr[$k2]['regex'] = $tag_regex;// 正则
						$sys_tag_arr[$k2]['field'] = $tag_field;// 字段
						$sys_tag_arr[$k2]['umsapifield'] = $tag_usmapifield;// umsAPI字段
						break;
					}
				}
			}

			if($had_regex==0){
				$head_regex[$k2]= '';
				$no_suffix_arr[$k2]=$k2 ;
			}

			// 判断$change_tag_arr和$excel_header_arr的元素是否相同
			if(in_array($header_v, $change_tag_arr)){
				// 如果相同，则把$excel_header_arr的下标保存到$change_suffix_arr
				$change_suffix_arr[$k2]=$k2;
			}
			//print_r($change_suffix_arr);

			// 是否是开启帐号标签
			if(in_array($header_v, $isopen_tag_arr)){
				// 启帐号下标
				$isopen_suffix = $k2;
			}
			// 是否是帐号标签
			if(in_array($header_v, $loginName_tag_arr)){
				// 帐号下标
				$loginName_suffix = $k2;
			}
		}

		// 没有在[部门、自定义、系统]标签中的标签
		if(!isemptyArray($no_suffix_arr)){
			$err_message = $this->lang->line('upload_file_header_error');//上传文件有不是系统有的标签！;
			$err_type = 2;
			$data_arr['err_msg'] = $err_message;
			$data_arr['err_type'] = $err_type;
			$data_arr['system_tag_head'] = $sys_all_tag_arr;
			$data_arr['excel_head'] = $excel_header_arr;
			log_message('info',__FUNCTION__." output->\n".var_export($data_arr, true));
			$this->load->view('bulkimport/partsuccess.php', $data_arr);
			return;
		}

		// 部门标签在文档中下标数组[部门标签顺序很重要-一定要安系统生成的部门标签顺序来做]
		$department_suffix_ok_arr = array();

		// 部门标签信息数组模板[系统顺序]
		$department_name_ok_arr = array();

		// 系统部门标签中没有的标签
		$not_sys_tag_arr = array();
		foreach($department_tag_name_arr as $n_k => $n_v){
			$nd_is_exit = 0;// 系统部门标签是否存在0不存在1存在
			foreach($department_name_arr as $dn_k => $dn_v){
				$dn_name = isset($dn_v['name'])?$dn_v['name']:'';
				if($n_v == $dn_name){
					$nd_suffix = $dn_k;
					$department_suffix_ok_arr[$nd_suffix] = $nd_suffix;
					$department_name_ok_arr[$dn_k]= $dn_v ;
					$nd_is_exit = 1;// 系统部门标签是否存在0不存在1存在
					break;
				}
			}
			if($nd_is_exit == 0 ){// 0不存在
				$not_sys_tag_arr[] = $n_v;// 系统中有，但是没有解析出来的标签
			}
		}

		if(!isemptyArray($not_sys_tag_arr)){
			//echo '系统中有，但是上传文件中没有的部门标签！';
			$err_message = $this->lang->line('upload_file_header_error');//'导入的文件与定义的模板不一致。';
			$err_type = 2;
			$data_arr['err_msg'] = $err_message;
			$data_arr['err_type'] = $err_type;
			$data_arr['system_tag_head'] = $sys_all_tag_arr;
			$data_arr['excel_head'] = $excel_header_arr;
			$this->load->view('bulkimport/partsuccess.php', $data_arr);
			return;
		}

		// 载入国码类库
		include_once APPPATH . 'libraries/public/Country_code.php';
		// new国码对象
		$county_code_obj = new Country_code();

		// 进行上传文件中的数据进行规则验证
		$ns_excel_success_arr = array();// 临时成功的数组
		// 初始化保存成功记录的数组
		$excel_success_arr=array();
		// 初始化保存失败记录的数组
		$excel_fail_arr=array();

		$customerCode = $this->p_customer_code; // 客户编码,用于线程时,判断是哪个唯一站点
		$parentId = $this->p_org_id;// 当前站点的组织机构id

		// 删除excel的表头
		unset($excel_arr[1]);
		//print_r($excel_arr);

		// 自定义标签下面加一个系统其它的参数
		$sys_other_arr = $this->p_sys_arr;

		//帐号类型0普通用户1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员[各种管理员新加时，必填]7生态企业普通用户
		$sys_other_arr['user_type'] = $this->p_open_org_type;
		//帐号开通来源,多个用，号分隔 1批量导入2组织管理员-单个新加3生态渠道管理员新加4任务新加
		$sys_other_arr['user_source'] = 1;
		//帐号导入类型[各种管理员新加时，必填]
		$sys_other_arr['isLDAP'] = $this->p_is_ldap;

		$login_name_arr = array();//记录解析了的帐号,不重名
		$error_row = array(); // 每条记录出错的字段的下标

		// 按行遍历excel的除表头外的所有数据
		foreach ($excel_arr as $row => $value1){
			//如果是空数组，则执行下一条
			if(isemptyArray($value1)){//如果是空数组
				continue;
			}
			$loginName = '';//ums loginname
			//临时模板
			//是否开启帐号
			$ns_isopen =0 ;
			//部门标签
			$ns_department_mb_arr = $department_name_ok_arr;
			//自定义标签
			$ns_user_mb_arr = $user_define_tag_arr;
			//系统标签
			$ns_sys_mb_arr = $sys_tag_arr;

			$is_err = 0;//当前记录是否有错，0没有错误1有错
			// 保存excel中部门层级的数组
			$department_arr = array();

			// 自定义标签
			if($is_err == 0){
				foreach($ns_user_mb_arr as $ns_key => $ns_v){
					$suffix = isset($ns_v['suffix'])?$ns_v['suffix']:'';//下标
					$regex = isset($ns_v['regex'])?$ns_v['regex']:'';//正则
					$ns_value = isset($value1[$suffix])?$value1[$suffix]:'';
					if(!bn_is_empty($regex)){//有正则
						if(!preg_match($regex, $ns_value)){//有不匹配的
							//当前行有一个数据未匹配上时
							$is_err = 1;
							$error_row[$row][$suffix] = '';// 自定义标签不正确
							break;
						}
					}
					//保存值
					$ns_user_mb_arr[$ns_key]['value'] = $ns_value;
				}
			}
			//系统标签
			if($is_err == 0){
				//$aa_value = "";
				foreach($ns_sys_mb_arr as $ns_key => $ns_v){
					$suffix = isset($ns_v['suffix'])?$ns_v['suffix']:'';//下标
					$filed = isset($ns_v['field'])?$ns_v['field']:'';
					$regex = isset($ns_v['regex'])?$ns_v['regex']:'';//正则
					$ns_value = isset($value1[$suffix])?$value1[$suffix]:'';
					$ns_ok_value = $ns_value;
					// 判断$change_tag_arr和$excel_header_arr的元素是否相同
					if(in_array($suffix,$change_suffix_arr)){
						switch($ns_value){
							case '是':
							case '开通':
							case '开':
								$ns_ok_value=1;
								break;
							case '否':
							case '关闭':
							case '关':
								$ns_ok_value=0;
								break;
							case '男':
								$ns_ok_value=1;
								break;
							case '女':
								$ns_ok_value=2;
								break;
						}
					}

					if($ns_ok_value !== $ns_value){//一定要用不恒等,值变化了,更新原数组值
						$ns_value = $ns_ok_value;
					}



					//如果是手机号码，则对其前面的国码进行验证
					if($filed == 'mobile_number'){
						$re_arr = $county_code_obj->get_mobile_arr($ns_value);
						if(empty($re_arr['mobile'])){
							$error_row[$row][$suffix] = '';
							$is_err = 1;
							break;
						}
					}else{
						// 如果是其他系统标签
						if(!bn_is_empty($regex)){//有正则
							if(!preg_match($regex,$ns_value)){//有不匹配的
								// 当前行有一个数据未匹配上时
								$error_row[$row][$suffix] = '';
								$is_err = 1;
								break;
							}
						}
					}

					//如果是帐号，则去判断帐号是否存在
					if($is_err == 0){
						if($loginName_suffix == $suffix){//是帐号标签
							$loginName = $ns_value;//ums loginname

							if(deep_in_array($loginName, $login_name_arr)){//在里面，代名重帐号了
								$error_row[$row][$suffix] = '';
								$is_err = 1;
								//}
							}//else{
							//								$login_back_txt = get_last_part($loginName,'@');
							//								// XXX 可能会有问题，需要确认(已确认，不限制后缀了)
							//								if($login_back_txt == $this->p_stie_domain){//后缀是当前域
							//									$login_name_arr[] = $ns_value;//记录解析了的帐号,不重名
							//								}else{
							//									$error_row[$row][$suffix] = '';
							//									$is_err = 1;
							//								}
							//}//end of else
						}
					}

					//保存值
					//开启帐号下标
					if($isopen_suffix == $suffix){
						switch($ns_value){
							case '是':
							case '开通':
							case '开':
								$ns_value = 1;
								break;
							case '否':
							case '关闭':
							case '关':
								$ns_value= 0 ;
								break;
						}
						$ns_isopen = $ns_value;
					}else{//非开启帐号
						$ns_sys_mb_arr[$ns_key]['value'] = $ns_value;
					}
				}
			}

			//部门标签[注意部门不在此进行有效性验证]
			if($is_err == 0){
				foreach($ns_department_mb_arr as $ns_key => $ns_v){
					$suffix = isset($ns_v['suffix'])?$ns_v['suffix']:'';//下标
					//$regex = isset($ns_v['regex'])?$ns_v['regex']:'';//正则
					//保存值
					$ns_value = isset($value1[$suffix])?$value1[$suffix]:'';
					// 如果当前数据的列下表在$department_suffix_arr数组中，则将当前数据存入$department_arr中
					$ns_department_mb_arr[$ns_key]['value'] = $ns_value;
					// 将标识符置1，表示当前数据已存入数组$department_arr中
					$department_arr[$suffix]=$ns_value;
				}
			}

			//当前记录没有错误，才会验证部门标签
			if($is_err == 0){
				//部门标签数组,验证[] ,用函数实现
				$is_err = $this->department_is_err($department_arr, $department_regex);
			}

			//最后才做一个定性的结论
			$ns_sys_other_arr  = $sys_other_arr;
			$ns_sys_other_arr['excel'] = $value1;
			$ns_sys_other_arr['excelhead'] = $excel_header_arr;
			$ns_user_mb_arr['sys'] = $ns_sys_other_arr;//自定义标签下面加一个系统其它的参数

			if($is_err == 0){
				$ns_success_arr = array(
                    'customerCode' => $customerCode,
                    'parentId'=> $parentId,
                    'isopen' => $ns_isopen,
                    'org_tag' => $ns_department_mb_arr,
                    'user_tag' => $ns_user_mb_arr,
                    'sys_tag' => $ns_sys_mb_arr
				);
				$ns_excel_success_arr[] = array(
                    'loginName' => $loginName,//判断是否存在的loginname
                    'old_excel_arr' => $value1,//excel原始数组
                    'ok_excel_arr' => $ns_success_arr,//需要保存到线程中的数组
				);//临时成功的数组
			}else if($is_err == 1){
				$error_row[$row][$suffix] = '';
				$excel_fail_arr[] = $value1;
			}
			$error_row[$row] = array_unique($error_row[$row]);
		}

		// 如果失败记录数组不为空
		if(!bn_is_empty($error_row)){//  Array ( [2] => Array ( [B] => ) [3] => Array ( [G] => ) )
			// 判断失败记录具体字段对应excel表头中的哪一个
			foreach ($error_row as $err_value){
				foreach($err_value as $err_k => $err_v){
					if(isset($excel_header_arr[$err_k])){
						if(!bn_is_empty($err_message)){
							// 有数据
							$err_message .= '<br/>';
						}
						$err_message .= $excel_header_arr[$err_k].$this->lang->line('upload_fail_wrong_value');//'不正确';
					}
				}
			}
		}

		//批量判断帐号是否存在
		if(!isemptyArray($ns_excel_success_arr)){//如果不是空数组
			$ums_user_arr = array();//用来判断帐号在ums是否存在的数组
			$fail_user_arr = array();
			$succ_user_arr = array();//成功的数组
			$noinums_loginname_arr = array();//不在ums中的loginname数组
			foreach($ns_excel_success_arr as $ns_k => $ns_v){
				$ns_loginName = arr_unbound_value($ns_v,'loginName',2,'');
				$old_excel_arr = arr_unbound_value($ns_v,'old_excel_arr',1,array());
				$ok_excel_arr = arr_unbound_value($ns_v,'ok_excel_arr',1,array());
				if(!bn_is_empty($ns_loginName)){//有值
					$ums_user_arr [] = array(
                        'loginName' => $ns_loginName,
					);
					$succ_user_arr[] = $ns_v;//成功的数组
				}else{
					$fail_user_arr[] = $old_excel_arr;
				}
			}
			if(!isemptyArray($fail_user_arr)){//将失败的加入失败数组
				$excel_fail_arr = array_merge($excel_fail_arr,$fail_user_arr);
			}
			if(!isemptyArray($ums_user_arr)){//如果不是空数组
				log_message('info',__FUNCTION__." input->\n".var_export($ums_user_arr, true));
				$ums_api_arr = $this->API->UMSAPI(json_encode($ums_user_arr),7);//返回不存在的数组
				log_message('info',__FUNCTION__." output->\n".var_export($ums_api_arr, true));

				$umsuser_notexist_arr = arr_unbound_value($ums_api_arr,'data',1,array());

				if(!isemptyArray($umsuser_notexist_arr)){//如果不是空数组
					//对不存在/存在的数组再进行操作
					foreach($umsuser_notexist_arr as $f_u_k => $f_u_v){
						$ums_user_loginname = arr_unbound_value($f_u_v,'loginName',2,'');
						if(!bn_is_empty($ums_user_loginname)){//有值,去掉成功的，并加入到失败中
							$noinums_loginname_arr[] = $ums_user_loginname;
						}
					}
				}
			}

			//最终确定成功数组
			$ns_fail_user_arr = array();//临时失败数组
			foreach($succ_user_arr as $s_k => $s_v){
				$ns_loginName = arr_unbound_value($s_v,'loginName',2,'');
				$old_excel_arr = arr_unbound_value($s_v,'old_excel_arr',1,array());
				$ok_excel_arr = arr_unbound_value($s_v,'ok_excel_arr',1,array());
				if(deep_in_array($ns_loginName, $noinums_loginname_arr)){//不在ums记录里面
					//新加[可操作],修改[不可操作]
					if($operate_type == 0 ){//0新加
						$excel_success_arr[] = $ok_excel_arr;
					}else{//1更新
						$ns_fail_user_arr[] = $old_excel_arr;
					}
				}else{//在ums记录里面
					//新加[不可操作],修改[可操作]
					if($operate_type == 0 ){//0新加
						$ns_fail_user_arr[] = $old_excel_arr;
					}else{//1更新
						$excel_success_arr[] = $ok_excel_arr;
					}
				}
			}
			//有记录
			if(!isemptyArray($ns_fail_user_arr)){//如果不是空数组
				if(!bn_is_empty($err_message)){
					// 有数据
					$err_message .= '<br/>';
				}
				if($operate_type == 0){// 0新加
					$err_message .= $this->lang->line('upload_fail_account_repeat');//'账号重复';
				}else{// 1修改
					$err_message .= $this->lang->line('upload_fail_account_not_exist');//账号不存在';
				}
				$excel_fail_arr = array_merge($excel_fail_arr, $ns_fail_user_arr);
			}
		}

		//$user_arr = $this->p_uc_user_arr;
		//$userID = arr_unbound_value($user_arr,'userID',2,'');
		$userID = $this->p_user_id;

		//将错误记录生成错误excel文档
		if(!isemptyArray($excel_fail_arr)){//如果不是空数组
			$excel_fail_arr = array_merge(array($excel_header_arr),$excel_fail_arr);
			//print_r($excel_fail_arr);
			$this->load->helper('my_phpexcel');
			$file_arr = array(
                'file_path' => FAIL_FILE_DIR, // '/data/failfile/'
                'file_name' => $userID.'_'.time(),//文件名称,注意没有文件后缀,如aaaa
			);
			$re_filename = create_excel($excel_fail_arr,'07',$file_arr,1);// ./data/file/356036_1406183496.xlsx

			// 将excel文档保存在数据库表中
			$this->load->model('UC_Message_Model');

			$org_id = $this->p_org_id;
			$addtime = date('Y-m-d H:i:s', strtotime('now'));

			$insert_arr = array(
				'org_id' => $org_id,
				'site_id' => $site_id,
				'type' => 1,
				'content' => $this->lang->line('upload_fail_message_content'), //"尊敬的用户您好，您上传的文档中包含不符合要求的数据，请点此下载失败列表哦"
				'isread' => 0,
				'title' => $this->lang->line('upload_fail_message_title'), // "上传文档失败"
				'from_user_id' => $userID,
				'send_name' => COMPANY_MSG_SEND_NAME, //'全时'
				'addtime' => $addtime,
				'to_user_id' => $userID,
				'url_content' => strstr($re_filename, $file_arr['file_name']),// // 356036_1406194990.xlsx
				'param' => ''
				);

				$this->UC_Message_Model->updata_or_insert(3,'',$insert_arr,$insert_arr,$insert_arr);
		}

		//统计成败个数
		$suc_count = count($excel_success_arr);
		unset($excel_fail_arr[0]);
		$fail_count = count($excel_fail_arr);

		log_message('debug', ' $suc_count = ' . $suc_count . ' $fail_count = ' . $fail_count . '.');
		log_message('debug', '$fail ' . json_encode($excel_fail_arr) . '.');

		//write_test_file( __FUNCTION__ . time() . '.txt' ,$success_json);
		//调用uc保存线程
		//调用UC线程接口
		//调用分配域的站点的开通消息接口[将消息保存数据库]
		//接口参数
		//类型2批量开通3批量修改
		if($operate_type == 0 ){//0新加
			$file_type = 2;
		}else{//1修改
			$file_type = 3;
		}
		$this->load->library('API','','API');
		$max_count = UC_BOSSAPI_MAX_NUM;//每次最大运行数量
		$max_count = empty_to_value($max_count,30);
		$record_no = 1;//记录号
		$ns_indb_arr = array();//批量入库的数组
		//分批入库
		foreach($excel_success_arr as $ns_k => $ns_v){
			$ns_indb_arr[$ns_k] = $ns_v;
			if( (($record_no % $max_count) == 0) || ($suc_count == $record_no)){
				$success_json = json_encode($ns_indb_arr);
				log_message('debug', '$excel_success_arr ' . $success_json . '.');
				$data = 'type=' . $file_type . '&value=' . urlencode($success_json);
				$uc_thread_arr = $this->API->UCAPI($data,2,array('url' => base_url('')));
				if(api_operate_fail($uc_thread_arr)){//失败
					log_message('error', 'UCAPI NO 1 is fail.');
				}else{
					log_message('debug', 'UCAPI NO 1 is success.');
				}
				$ns_indb_arr = array();//批量入库的数组
			}
			$record_no += 1;//记录号
		}
		if($suc_count > 0){
			//日志
			$this->load->library('LogLib','','LogLib');
			$log_in_arr = $this->p_sys_arr;
			$re_id = $this->LogLib ->set_log(array('3','1'),$log_in_arr);

			//日志
			$this->load->library('LogLib','','LogLib');
			$log_in_arr = $this->p_sys_arr;
			$re_id = $this->LogLib ->set_log(array('3','2'),$log_in_arr);
		}
		$data_arr['success_count'] = $suc_count;
		$data_arr['fail_count'] = $fail_count;
		log_message('debug', ' $suc_count = ' . $suc_count . ' $fail_count = ' . $fail_count . '.');
		if($fail_count > 0){
			$err_type = 3;
			$data_arr['fail_file'] = strstr($re_filename, $file_arr['file_name']);// 356036_1406194990.xlsx
			$data_arr['err_msg'] = $err_message;
		}
		$data_arr['err_type'] = $err_type;
		log_message('info',__FUNCTION__." output->\n".var_export($data_arr, true));
		$this->load->view('bulkimport/partsuccess.php', $data_arr);
	}

	/**
	 * @abstract 上传文档失败后，下载失败列表
	 */
	public function downloadFailFile() {
		// 载入下载辅助函数
		$this->load->helper('download');

		// 获取文件名称
		$file_name = $this->uri->segment(3);

		// 组装文件下载URL
		$target = FAIL_FILE_DIR.$file_name;// ./data/file/356036_1406183496.xlsx

		// 读文件内容
		$data = file_get_contents($target);
		$name = $file_name;

		// 下载文件
		force_download($name, $data);
	}

	/**
	 * @abstract 判断部门层级的值是否正确
	 * @param $ns_department_tag__arr array  该数组存储的是单条记录中部门层级的值
	 * @details
	 * -# 当部门一级为空时，下面的部门层级必须都为空
	 * -# 当部门一级不为空时，下面的部门层级可以为空也可以不为空
	 * -# 即当当前部门层级不为空时，它的上层部门必须不为空
	 * @return int 0正确1有错
	 */
	public function department_is_err($ns_department_tag__arr, $rule){
		$is_err=0;// 错误标识符，0为无错，1为有错
		$has_null_value=0;// 空值标识符，0为无空值，1为有空值
		$level_num = 0;//层级
		foreach($ns_department_tag__arr as $k => $v){
			$level_num += 1;//层级
			if(!bn_is_empty($v)){//值不为空
				if($has_null_value == 1){//前面已经有空值
					$is_err=1;// 错误标识符，0为无错，1为有错
					break;
				}
				//对值进行有效性验证
				// 判断当前部门是否符合规则：100个中英文数字
				if(!preg_match($rule,$v)){
					$is_err=1;
					break;
				}
			}else{//值为空值
				$has_null_value = 1;// 空值标识符，0为无空值，1为有空值
				//如果是第一级,并值为空，没报错
				if($level_num == 1){
					$is_err=1;// 错误标识符，0为无错，1为有错
					break;
				}
			}
		}
		return $is_err;
	}
}

