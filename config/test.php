<?php 
if(!defined('ENV')){ exit('access deny'); }

return array(
	'mysql'	=>	array(
		'pref'	=>	'dh_',
		'host'	=>	'192.168.1.136',
		'port'	=>	'3306',
		'user'	=>	'vote',
		'pass'	=>	'vote123',
		'dbnm'	=>	'vote',
		'char'	=>	'utf8',
	),

	'session_dir'	=>	'/tmp/session/',
);
?>