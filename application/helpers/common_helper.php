<?php
/**
 * 公共函数库
 *
 * @author Bai Xue<xue.bai_2@quanshi.com>
 *
 */


/**
 * 数据缓存
 *
 * 该函数可选择以Memcache缓存还是以文件写入形式缓存。
 * 如果开启Memcache,在config.php下修改$config['memcached']['enable']=TRUE;
 * 否则缓存信息将以文本形式存储在application/cache文件夹下。
 * 文件命名格式：$key+md5(ip_address+user_aguent);
 * 如果能用Memcache就尽量用，不要使用文本，否则会产生一堆的垃圾文件
 * 注意，该函数缓存的数据只针对某个用户而非类似缓存新闻列表等全局调用的。
 * 如需使用全局的缓存，则使用memory()方法替代
 *
 * @param string $cmd get|set|rm
 * @param string $key
 * @param mixed $value
 * @param int $expire
 * @return mixed
 *
 */
function cache($cmd, $key, $value=null, $expire=3600) {
	static $ways = NULL;
	$CI =& get_instance();
	if( is_null($ways) ){
		$CI->load->driver('cache');
		$ways = 'memcached';
	}
	switch ($cmd) {
		case 'get':
			return $CI->cache->$ways->get($key);
			break;
		case 'set':
			return $CI->cache->$ways->save($key,$value,$expire);
			break;
		case 'rm':
			return $CI->cache->$ways->delete($key);
			break;
		case 'clean':
			$CI->cache->$ways->clean();// 刷新输出缓存，即将当前为止程序的所有输出发送到用户的浏览器
			break;
		default:
			break;
	}
}


//function ssdbcache($cmd,$key,$value=null,$expire=600){
//	$CI =& get_instance();
//	if(! $CI->config->get('ssdb_enable') ) return true;
//	$CI->load->driver('cache');
//	switch ($cmd) {
//		case 'get':
//			return $CI->cache->ssdb->get($key);
//			break;
//		case 'set':
//			return $CI->cache->ssdb->save($key,$value,$expire);
//			break;
//		case 'rm':
//			return $CI->cache->ssdb->delete($key);
//			break;
//		case 'clean':
//			$CI->cache->ssdb->clean();
//			break;
//	}
//}

/**
 * 文件缓存机制
 * 用于保存不易还原的重要数据，不受外部环境的影响。
 *
 */
// function fcache($cmd,$key,$value=null,$expire=600){
// 	static $ways = NULL;
// 	$CI =& get_instance();
// 	$CI->load->driver('cache');
// 	$ways = 'file';
// 	switch ($cmd) {
// 		case 'get':
// 			return $CI->cache->$ways->get($key);
// 			break;
// 		case 'set':
// 			return $CI->cache->$ways->save($key,$value,$expire);
// 			break;
// 		case 'rm':
// 			$CI->cache->$ways->delete($key);
// 			break;
// 		case 'clean':
// 			$CI->cache->$ways->clean();
// 			break;
// 	}
// }

/**
 * 数据库缓存
 * 使用数据库缓存,迟点写垃圾回收机制
 * @param string $cmd get|set|rm
 * @param string $key
 * @param mixed $value
 * @param int $expire
 * @return mixed
 */
//function dbcache($cmd,$key,$value=null,$expire=600) {
//	$CI =& get_instance();
//	$CI->load->database();
//	switch ($cmd) {
//		case 'get':
//			if ($d = $CI->db->get('cache','data,expire,serialize',array('key'=>$key))->row()) {
//				if ($d['expire'] > TIMESTAMP OR $d['expire'] == 0) {
//					return $d['serialize'] ? unserialize($d['data']) : $d['data'];
//				} else {
//					$CI->db->delete('cache',array('key'=>$key));
//					return FALSE;
//				}
//			}
//			break;
//		case 'set':
//			$expire = $expire > 0 ? TIMESTAMP + $expire : 0;
//			if (in_array(gettype($value),array('array','object','NULL'))) {
//				$value = serialize($value);
//				$serialize = 1;
//			} else {
//				$serialize = 0;
//			}
//			$CI->db->replace('cache',array('key'=>$key,'data'=>$value,'expire'=>$expire,'serialize'=>$serialize));
//			return TRUE;
//			break;
//		case 'rm':
//			$CI->db->delete('cache',array('key'=>$key));
//			break;
//	}
//}

