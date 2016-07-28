<?php
/**
 *	系统入口文件
 *	@author lxm
 */
class PecidPHP
{
	static function start()
	{
        define('CORE_PATH', dirname(__FILE__));
        define('CONFIG_PATH', ROOT_PATH.'/config');
        define('CONTROLLER_PATH', ROOT_PATH.'/controller');
        define('MODEL_PATH', ROOT_PATH.'/model');
        define('VIEW_PATH', ROOT_PATH.'/view');
        define('TEMP_PATH', ROOT_PATH.'/temp');

		require(CORE_PATH.'/core/Controller.php');
        require(CORE_PATH.'/core/Factory.php');
		require(CORE_PATH.'/include/functions.php');

		/* 取消数据过滤 */
        if (get_magic_quotes_gpc())
        {
            $_GET   = stripcslashes_deep($_GET);
            $_POST  = stripcslashes_deep($_POST);
            $_COOKIE= stripcslashes_deep($_COOKIE);
        }

        //获取配置
        C(require(CONFIG_PATH.'/common.php'));

        $controller = isset($_GET['_c']) && $_GET['_c'] ? $_GET['_c'] : 'index';
        $action = isset($_GET['_a']) && $_GET['_a'] ? $_GET['_a'] : 'index';

        /*define('CONTROLLER', ucfirst($controller));
        define('ACTION', $action);*/

        $controller = Factory::getController($controller);
        $controller->doAction($action);
	}
}


?>
