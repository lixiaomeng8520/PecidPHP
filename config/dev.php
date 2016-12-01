<?php 
if(!defined('ENV')){ exit('access deny'); }

return array(
	'db'	=>	array(
		'driver'		=>	'Mysqli', // 驱动
		'hostname'		=>	'127.0.0.1',	// 主机
		'port'			=>	3306,	// 端口
		'username'		=>	'root',	// 用户名
		'password'		=>	'123456',	// 密码
		'dbname'		=>	'test',	// 数据库
		'charset'		=>	'utf8',	// 编码
		'prefix'		=>	'',	// 表前缀
	),

	'session_dir'	=>	ROOT_PATH.'/test',

	'Redis'	=>	array(
		'host'		=>	'127.0.0.1',
		'password'	=>	'',
		'port'		=>	'6379',
		'timeout'	=>	'',
	),
);
?>