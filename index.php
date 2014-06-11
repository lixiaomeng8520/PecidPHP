<?php
error_reporting(E_ALL & ~E_NOTICE);
define('ROOT_PATH', dirname(__FILE__));
require(ROOT_PATH . '/PecidPHP/PecidPHP.php');
PecidPHP::start();
?>