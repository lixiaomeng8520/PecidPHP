<?php 
if(!defined('ENV')){ exit('access deny'); }

return array(
	'Mysql'	=>	array(
		'pref'	=>	'dh_',
		'host'	=>	'127.0.0.1',
		'port'	=>	'3306',
		'user'	=>	'root',
		'pass'	=>	'123456',
		'dbnm'	=>	'vote',
		'char'	=>	'utf8',
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