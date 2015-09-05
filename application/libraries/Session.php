<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @abstract 	替换CI的session类
 * @author 		Bai Xue <xue.bai_2@quanshi.com>
 * @copyright 	Copyright (c) UC
 * @version 	v1.0
 */
class Session {

	var $sess_encrypt_cookie		= FALSE;
	var $sess_expiration			= 7200;
	var $sess_expire_on_close		= FALSE;
	var $sess_match_ip				= FALSE;
	var $sess_match_useragent		= TRUE;
	var $sess_cookie_name			= 'session';
	var $cookie_prefix				= 'uc_';
	var $cookie_path				= '';
	var $cookie_domain				= '';
	var $cookie_secure				= FALSE;
	var $sess_time_to_update		= 300;
	var $encryption_key				= '';
	var $time_reference				= 'time';
	var $userdata					= array();
	var $CI;
	var $now;

	/**
	 * @abstract 构造方法
	 */
	public function __construct() {
		log_message('debug', "Native session Class Initialized");

		// 为超级变量$CI赋值
		$this->CI =& get_instance();
		// 载入公共辅助函数
		$this->CI->load->helper('my_publicfun');

		// 通过config.php文件为session的各个参数赋值
		foreach (array('sess_encrypt_cookie', 'sess_expiration', 'sess_expire_on_close', 'sess_cookie_name', 'cookie_path', 'cookie_domain', 'cookie_secure', 'sess_time_to_update', 'time_reference', 'cookie_prefix', 'encryption_key') as $key){
			$this->$key = $this->CI->config->item($key);
		}

		// 判断加密密钥是否为空
		if ($this->encryption_key == ''){
			show_error('In order to use the Session class you are required to set an encryption key in your config file.');
		}

		// Load the string helper so we can use the strip_slashes() function
		$this->CI->load->helper('string');

		// Do we need encryption? If so, load the encryption class
		if ($this->sess_encrypt_cookie == TRUE){
			$this->CI->load->library('encrypt');
		}

		// Set the "now" time.  Can either be GMT or server time, based on the config prefs.  We use this to set the "last activity" time
		$this->now = $this->_get_time();

		// Set the session length. If the session expiration is set to zero we'll set the expiration two years from now.
		if ($this->sess_expiration == 0){
			$this->sess_expiration = (60*60*24*365*2);
		}

		// Set the cookie name
		$this->sess_cookie_name = $this->cookie_prefix.$this->sess_cookie_name;
		
		// 如果读不到session信息，就创建session
		if(!$this->sess_read()){
			//log_message('info', 'session_id1' . $this->userdata('session_id'));
			$this->sess_create();
		}else{
			//log_message('info', 'session_id2' . $this->userdata('session_id'));
			$this->sess_update();
		}

		log_message('debug', "Native session routines successfully run");

	}

	/**
	 *@abstract 读取session
	 */
	public function sess_read(){
		
		// Fetch the cookie
		$session = $this->CI->input->cookie($this->sess_cookie_name);
		
		log_message('info', 'session='. $session);

		// No cookie?  Goodbye cruel world!...
		if ($session === FALSE){
			log_message('debug', 'A session cookie was not found.');
			return FALSE;
		}

		// Decrypt the cookie data
		if ($this->sess_encrypt_cookie == TRUE){
			$session = $this->CI->encrypt->decode($session);
 		}else{
			// encryption was not used, so we need to check the md5 hash
			$hash	 = substr($session, strlen($session)-32); // get last 32 chars
			$session = substr($session, 0, strlen($session)-32);

			// Does the md5 hash match?  This is to prevent manipulation of session data in userspace
			if ($hash !==  md5($session.$this->encryption_key)){
				log_message('error', 'The session cookie data did not match what was expected. This could be a possible hacking attempt.');
				$this->sess_destroy();
				return FALSE;
			}
		}

		// Unserialize the session array
		$session = $this->_unserialize($session);

		// Is the session data we unserialized an array with the correct format?
		if ( ! is_array($session) OR ! isset($session['session_id']) ){
			log_message('info', '111111');
			$this->sess_destroy();
			return FALSE;
		}

		// Session is valid!
		$this->userdata = $session;
		
		// 从缓存中获取数据
		//$cache_arr = array();
		$cache_arr = cache('get', $session['session_id']);
		//log_message('info', '$cache_arr' . var_export($cache_arr, true));
		if(!$cache_arr){
			//log_message('info', '22222');
			$this->sess_destroy();
			return FALSE;
		}
		$this->userdata = array_merge($this->userdata, $cache_arr);
		
		unset($session);
		unset($cache_arr);

		return TRUE;
	}

	/**
	 * @abstract 首次创建session
	 */
	public function sess_create(){
		if(session_id() == ''){
			session_start();
		}
		
		$this->userdata = array(
			'session_id' => session_id()
		);

		// Write the session data
		$this->sess_write();
		
		// 创建缓存
		$this->cache_create();
	}
	
	/**
	 * @abstract 登录成功后创建session
	 * @param  string  $session_id  登录成功后UCCServer分配的sessionid
	 */
	public function sess_recreate($session_id) {
		$this->userdata = array(
			'session_id' => $session_id
		);
		
		// Write the session data
		$this->sess_write();
		
		// 创建缓存
		$this->cache_create();
	}

	/**
	 * @abstract Write the session data
	 * @return	void
	 */
	public function sess_write(){
		// 由于我们不采用将session保存入数据库的方案，因此我们只需更新cookie
		$this->_set_cookie();
	}

	/**
	 * @abstract 更新session
	 */
	public function sess_update() {
		//$cache_arr = array();
		// 从缓存中拿出session上次活动的时间
		$cache_arr = cache('get', $this->userdata('session_id'));
		if($cache_arr == false || is_empty($cache_arr['last_activity'])){
			return;
		}
		
		// We only update the session every thirty minutes by default
		if (($cache_arr['last_activity'] + $this->sess_time_to_update) < $this->now) {
			return;
		}
		
		$cache_arr['last_activity'] = $this->now;
		
		// 更新缓存
		cache('set', $this->userdata('session_id'), $cache_arr);
	}

	/**
	 * @abstract 销毁session
	 */
	public function sess_destroy(){
		// 清除缓存
		cache('rm', $this->userdata('session_id'));
		
		// 清除session
		setcookie(
			$this->sess_cookie_name,
			addslashes(serialize(array())),
			($this->now - 31500000),
			$this->cookie_path,
			$this->cookie_domain,
			$this->cookie_secure
		);
		
		// Kill session data
		$this->userdata = array();
		//unset($this->userdata);
	}

	/**
	 * @abstract Fetch a specific item from the session array
	 * @param	string  $item
	 * @return	string
	 */
	public function userdata($item){
		// 判断是不是sessionid
		if ($item == 'session_id') {
			return isset($this->userdata ['session_id']) ? $this->userdata ['session_id'] : '';
		} else {
			// 从cache中取出所有数据
			$cache_arr = cache ( 'get', $this->userdata['session_id'] );
			
			return (! isset ( $cache_arr [$item] )) ? '' : $cache_arr [$item];
		}
		//return (isset($this->userdata[$item]))?$this->userdata[$item] : '';
	}

	/**
	 * @abstract Add or change data in the "userdata" array
	 * @param	mixed     $newdata
	 * @param	string    $newval
	 * @return	void
	 */
	public function set_userdata($newdata = array(), $newval = ''){
		if (is_string($newdata)){
			$newdata = array($newdata => $newval);
		}

		if (count($newdata) > 0){
			foreach ($newdata as $key => $val){
				$this->userdata[$key] = $val;
			}
		}

		// 更新memcache
		$this->cache_update();
	}

	/**
	 * @abstract Delete a session variable from the "userdata" array
	 * @param	mix   $newdata
	 * @return	void
	 */
	public function unset_userdata($newdata = array()){
		if (is_string($newdata)){
			$newdata = array($newdata => '');
		}

		if (count($newdata) > 0){
			foreach ($newdata as $key => $val){
				unset($this->userdata[$key]);
			}
		}

		// 从缓存中删除数据
		$this->cache_delete();
	}
	
	/**
	 * @abstract 创建缓存
	 */
	public function cache_create() {
		// 创建时间
		$cache_arr['last_activity'] = $this->now;
		
		// 创建缓存
		cache('set', $this->userdata('session_id'), $cache_arr);
	}
	
	/**
	 * @abstract 更新缓存
	 */
	public function cache_update() {
		// 从cache中取出所有数据
		$cache_arr = cache('get', $this->userdata('session_id'));
		
		if($cache_arr == false){
			$cache_arr = array();
		}
		
		log_message('info', 'cache=' . var_export($cache_arr, true));
		
		// 遍历userdata数组
		foreach($this->userdata as $user_key => $user_value){
			if($user_key != 'session_id'){ // 如果不是session_id
				$cache_arr[$user_key] = $user_value;
			}
		}
		
		// 创建时间
		$cache_arr['last_activity'] = $this->now;
		
		// 创建新缓存
		cache('set', $this->userdata('session_id'), $cache_arr);
	}
	
	/**
	 * @abstract 从缓存中删除部分数据
	 */
		public function cache_delete() {
			// 从缓存中读取数据
			$cache_arr = cache('get', $this->userdata('session_id'));
			
			if($cache_arr == false){
				$cache_arr = array();
			}
	
			// 获得缓存数组中的key
			$cache_keys = array_keys($cache_arr);
	
			// 获得当前userdata数组中的key
			$userdata_keys = array_keys($this->userdata);
	
			// 两个数组求差集
			$not_userdata_keys = array_diff($cache_keys, $userdata_keys);
	
			// 删除缓存中已经不存在的值
			foreach ($not_userdata_keys as $key){
				unset($cache_arr[$key]);
			}
	
			// 添加时间
			$cache_arr['last_activity'] = $this->now;
	
			// 重新设置缓存
			cache('set', $this->userdata('session_id'), $cache_arr);
	
		}
	
	/**
	 * @abstract Write the session cookie
	 * @param   mix  $cookie_data
	 * @return	void
	 */
	public function _set_cookie($cookie_data = NULL){
		if (is_null($cookie_data)){
			$cookie_data = $this->userdata;
		}

		// Serialize the userdata for the cookie
		$cookie_data = $this->_serialize($cookie_data);

		if ($this->sess_encrypt_cookie == TRUE){
			$cookie_data = $this->CI->encrypt->encode($cookie_data);
		}else{
			// if encryption is not used, we provide an md5 hash to prevent userside tampering
			$cookie_data = $cookie_data.md5($cookie_data.$this->encryption_key);
		}

		$expire = ($this->sess_expire_on_close === TRUE) ? 0 : $this->sess_expiration + time();

		// Set the cookie
		setcookie(
			$this->sess_cookie_name,
			$cookie_data,
			$expire,
			$this->cookie_path,
			$this->cookie_domain,
			$this->cookie_secure
		);
	}

	/**
	 * @abstract Get the "now" time
	 */
	public function _get_time(){
		if (strtolower($this->time_reference) == 'gmt'){
			$now = time();
			$time = mktime(gmdate("H", $now), gmdate("i", $now), gmdate("s", $now), gmdate("m", $now), gmdate("d", $now), gmdate("Y", $now));
		}else{
			$time = time();
		}

		return $time;
	}

	/**
	 * @abstract Serialize an array
	 * This function first converts any slashes found in the array to a temporary
	 * marker, so when it gets unserialized the slashes will be preserved
	 * @param	array
	 * @return	string
	 */
	public function _serialize($data){
		if (is_array($data)){
			foreach ($data as $key => $val){
				if (is_string($val)){
					$data[$key] = str_replace('\\', '{{slash}}', $val);
				}
			}
		}else{
			if (is_string($data)){
				$data = str_replace('\\', '{{slash}}', $data);
			}
		}

		return serialize($data);
	}

	/**
	 * @abstract Unserialize
	 * This function unserializes a data string, then converts any
	 * temporary slash markers back to actual slashes
	 * @param	array
	 * @return	string
	 */
	public function _unserialize($data){
		$data = @unserialize(strip_slashes($data));

		if (is_array($data)){
			foreach ($data as $key => $val){
				if (is_string($val)){
					$data[$key] = str_replace('{{slash}}', '\\', $val);     
				}
			}

			return $data;
		}

		return (is_string($data)) ? str_replace('{{slash}}', '\\', $data) : $data;
	}
}