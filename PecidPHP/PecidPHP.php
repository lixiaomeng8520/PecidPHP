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
        define('CONTROLLER_PATH', ROOT_PATH.'/controllers');
        define('VIEW_PATH', ROOT_PATH.'/views');
        define('CONFIG_PATH', ROOT_PATH.'/configs');
        define('TEMP_PATH', ROOT_PATH.'/temps');

		require(CORE_PATH.'/core/controller.php');
		require(CORE_PATH.'/core/model.php');
        require(CORE_PATH.'/core/factory.php');
		require(CORE_PATH.'/functions.php');

		/* 数据过滤 */
        if (!get_magic_quotes_gpc())
        {
            $_GET   = addslashes_deep($_GET);
            $_POST  = addslashes_deep($_POST);
            $_COOKIE= addslashes_deep($_COOKIE);
        }

        //获取配置
        C(require(CONFIG_PATH.'/common.php'));

        $controller = $_GET['_c'] ? $_GET['_c'] : 'index';
        $action = $_GET['_a'] ? $_GET['_a'] : 'index';

        define('CONTROLLER', ucfirst($controller));
        define('ACTION', $action);

        $app = Factory::getController($controller);
        $app->do_action($action);
	}
}


?>
