<?php
/**
 *	工厂类，获取controller和model单例
 *	@author lxm
 */
class Factory
{
	public static function getController($class_name){
		static $_instance = array();
		$identify = to_guid_string($class_name);

		if(!isset($_instance[$identify]) || !$_instance[$identify]){
			$c_file = CONTROLLER_PATH.'/'.$class_name.'.controller.php';
	        if(!is_file($c_file)){
	        	trigger_error('文件 '.$c_file.' 没找到', E_USER_ERROR);
	        }

	        require($c_file);

	        $controller = new $class_name();

	        $_instance[$identify] = $controller;	
		}
		
        return $_instance[$identify];
	}

	public static function getMysqlDb(){
		require_once(CORE_PATH.'/core/MysqlDb.php');
		static $_instance = null;
		if($_instance === null){
			$_instance = new MysqlDb(C('db'));
		}
		return $_instance;
	}

	static function getModel(){
		require_once(CORE_PATH.'/core/model.php');
		static $_instance = null;
		if($_instance === null){	
			$db = Factory::getDb();
			$_instance = new Model($db);
		}
		return $_instance;
	}

	static function getDb(){
		require_once(CORE_PATH.'/include/dbdriver/mysql.php');
		static $_instance = null;
		if($_instance === null)
		{
			$_instance = new MysqlDb(C('db_host'), C('db_port'), C('db_user'), C('db_pass'), C('db_name'));
		}//var_dump($_instance);die;
		return $_instance;
	}

	static function getView(){
		require_once(CORE_PATH.'/core/view.php');
		static $_instance = null;
		if($_instance === null){
			$_instance = new View();
		}
		return $_instance;
	}
}
?>