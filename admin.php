<?php
switch($_SERVER['HTTP_HOST']){
	case 'vote.lxm.cn':
		define('ENV', 'dev');
		break;
	case 'votetest.dahe.cn':
		define('ENV', 'test');
		break;
	default:
		define('ENV', 'online');
		break;
}
require('PecidPHP/PecidPHP.php');
PecidPHP::start();
?>