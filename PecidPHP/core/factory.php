<?php
/**
 *	工厂类，获取controller和model单例
 *	@author lxm
 */
class Factory
{
	public static function getController($class_name){
		$class_name = ucfirst($class_name);
		static $_instance = array();
		$identify = to_guid_string($class_name);

		if(!isset($_instance[$identify]) || !$_instance[$identify]){
			$c_file = CONTROLLER_PATH.'/'.$class_name.'.controller.php';
	        if(!is_file($c_file)){
	        	trigger_error('file '.$c_file.' not found', E_USER_ERROR);
	        }

	        require($c_file);

	        $class_name .= 'Controller';
	        $controller = new $class_name();

	        $_instance[$identify] = $controller;	
		}
		
        return $_instance[$identify];
	}

	public static function getMysqlDb($config){
		require_once(CORE_PATH.'/core/MysqlDb.php');
		static $_instance = null;
		if($_instance === null){
			$_instance = new MysqlDb($config);
		}
		return $_instance;
	}


	static function getModel($class_name){
		$class_name = ucfirst($class_name);
		require_once(CORE_PATH.'/core/Model.php');
		static $_instance = array();
		$identify = to_guid_string($class_name);

		if(!isset($_instance[$identify]) || !$_instance[$identify]){
			$c_file = MODEL_PATH.'/'.$class_name.'.model.php';
	        if(!is_file($c_file)){
	        	trigger_error('file '.$c_file.' not found', E_USER_ERROR);
	        }

	        require($c_file);

	        $class_name .= 'Model';
	        $model = new $class_name();

	        $_instance[$identify] = $model;	
		}
		
        return $_instance[$identify];
	}

	static function getView($file, $data = array(), $output = true){
		extract($data);
		$file = VIEW_PATH.'/'.$file.'.php';
		if(!is_file($file)){
			trigger_error('view not found: '.$file, E_USER_ERROR);
		}
		if($output){
			include $file;
		}else{
			ob_start();
			include View_PATH.'/'.$file;
        	$out = ob_get_clean();
        	return $out;
		}
	}
}
?>