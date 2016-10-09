<?php 
if(!defined('ENV')){ exit('access deny'); }

return array(
	'mysql'	=>	array(
		'pref'	=>	'dh_',
		'host'	=>	'127.0.0.1',
		'port'	=>	'3306',
		'user'	=>	'vote_pro',
		'pass'	=>	'AD45%ffs5t',
		'dbnm'	=>	'vote',
		'char'	=>	'utf8',
	),

	'session_dir'	=>	'/tmp/session/',
);
?>