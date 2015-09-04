<?php if (! defined ( 'BASEPATH' ))exit ( 'No direct script access allowed' );

// 配置单台redis服务器
$config ['redis'] = array (
		'socket_type' 	=> 'tcp',
		'host' 			=> '127.0.0.1',
		'password' 		=> '',
		'port' 			=> 6379,
		'timeout' 		=> 0
);

// 配置多台redis服务器--暂不支持
// $config ['redis'] = array (
// 		'default' => array (
// 						'socket_type' 	=> 'tcp',
// 						'host' 			=> '127.0.0.1',
// 						'password' 		=> '',
// 						'port' 			=> 6379,
// 						'timeout' 		=> 0
// 				),
// 		'server1' => array (
// 						'socket_type' 	=> 'tcp',
// 						'host' 			=> '127.0.0.1',
// 						'password' 		=> '',
// 						'port' 			=> 6379,
// 						'timeout' 		=> 0
//				),
// );