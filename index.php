<?php
error_reporting(E_ALL);

define('ROOT_PATH', dirname(__FILE__));
define('ENV', 'dev');
require(ROOT_PATH . '/PecidPHP/PecidPHP.php');
PecidPHP::start();
?>