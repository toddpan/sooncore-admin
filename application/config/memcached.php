<?php if (! defined ( 'BASEPATH' ))exit ( 'No direct script access allowed' );

// 配置单台缓存服务器
$config ['memcached'] = array (
		'hostname' => '127.0.0.1',
		'port' => 11211,
		'weight' => 1	
);

// 配置多台缓存服务器
// $config ['memcached'] = array (
// 		'default' =>array (
// 						'hostname' => '127.0.0.1',
// 						'port' => 11211,
// 						'weight' => 1
// 				),
// 		'server1' =>array (
// 						'hostname' => '127.0.0.1',
// 						'port' => 11211,
// 						'weight' => 1
// 				),
// );
