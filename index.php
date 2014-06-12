<?php
error_reporting(E_ALL & ~E_NOTICE);

date_default_timezone_set('Asia/Shanghai');
define('ROOT_PATH', dirname(__FILE__));
require(ROOT_PATH . '/PecidPHP/PecidPHP.php');
PecidPHP::start();
?>