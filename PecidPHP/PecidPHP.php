<?php
if(!defined('ENV')){ exit('access deny'); }
/**
 *	系统入口文件
 *	@author lxm
 */
class PecidPHP
{
	static function start()
	{
        if(defined('ENV') && !in_array(ENV, array('dev', 'test', 'online'))){
            trigger_error('ENV must be one of [dev, test, online]', E_USER_ERROR);
        }elseif(!defined('ENV')){
            trigger_error('not defined ENV', E_USER_ERROR);
        }

        date_default_timezone_set('Asia/Shanghai');
        define('CORE_PATH', dirname(__FILE__));
        define('CONFIG_PATH', ROOT_PATH.'/config');
        define('CONTROLLER_PATH', ROOT_PATH.'/controller');
        define('MODEL_PATH', ROOT_PATH.'/model');
        define('VIEW_PATH', ROOT_PATH.'/view');
        define('TEMP_PATH', ROOT_PATH.'/temp');
        define('INCLUDE_PATH', ROOT_PATH.'/include');

		require(CORE_PATH.'/Controller.php');
        require(CORE_PATH.'/Model.php');
        require(CORE_PATH.'/MysqlDb.php');
        require(CORE_PATH.'/View.php');
        // require(CORE_PATH.'/Factory.php');
		require(CORE_PATH.'/function.php');

		/* 取消数据过滤 */
        if(get_magic_quotes_gpc()){
            trigger_error('please close magic', E_USER_ERROR);
            /*$_GET   = stripcslashes_deep($_GET);
            $_POST  = stripcslashes_deep($_POST);
            $_COOKIE= stripcslashes_deep($_COOKIE);*/
        }

        //获取配置
        if(is_file(CONFIG_PATH.'/'.ENV.'.php')){
            Conf(require(CONFIG_PATH.'/'.ENV.'.php'));
        }
        

        $controller = isset($_GET['_c']) && $_GET['_c'] ? $_GET['_c'] : 'index';
        $action = isset($_GET['_a']) && $_GET['_a'] ? $_GET['_a'] : 'index';

        /*define('CONTROLLER', ucfirst($controller));
        define('ACTION', $action);*/

        $controller = C($controller);
        $controller->doAction($action);
	}
}


?>
