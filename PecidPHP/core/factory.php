<?php
/**
 *	工厂类，获取controller和model单例
 *	@author lxm
 */
class Factory
{
	static function getController($class_name)
	{
		static $_instance = array();
		$identify = to_guid_string($class_name);

		if(!$_instance[$identify])
		{
			$c_file = CONTROLLER_PATH.'/'.$class_name.'.controller.php';
	        if(!is_file($c_file))
	        {
	        	trigger_error('文件 '.$c_file.' 没找到', E_USER_ERROR);
	        }

	        require($c_file);

	        $controller = new $class_name();

	        $_instance[$identify] = $controller;	
		}
		
        return $_instance[$identify];
	}

	static function getModel($class_name)
	{

	}

	static function getView()
	{
		require_once(CORE_PATH.'/core/view.php');
		static $_instance = null;
		if($_instance === null)
		{
			$_instance = new View();
		}
		return $_instance;
	}
}
?>