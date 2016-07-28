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

		if(!isset($_instance[$class_name]) || !$_instance[$class_name]){
			$c_file = CONTROLLER_PATH.'/'.$class_name.'.controller.php';
	        if(!is_file($c_file)){
	        	trigger_error('file '.$c_file.' not found', E_USER_ERROR);
	        }

	        require($c_file);

	        $class_name .= 'Controller';
	        $controller = new $class_name();

	        $_instance[$class_name] = $controller;	
		}
		
        return $_instance[$class_name];
	}

	public static function getMysqlDb($config){
		require_once(CORE_PATH.'/core/MysqlDb.php');
		static $_instance = null;
		if($_instance === null){
			$_instance = new MysqlDb($config);
		}
		return $_instance;
	}


	public static function getModel($class_name){
		$class_name = ucfirst($class_name);
		require_once(CORE_PATH.'/core/Model.php');
		static $_instance = array();

		if(!isset($_instance[$class_name]) || !$_instance[$class_name]){
			$c_file = MODEL_PATH.'/'.$class_name.'.model.php';
	        if(!is_file($c_file)){
	        	trigger_error('file '.$c_file.' not found', E_USER_ERROR);
	        }

	        require($c_file);

	        $class_name .= 'Model';
	        $model = new $class_name();

	        $_instance[$class_name] = $model;	
		}
		
        return $_instance[$class_name];
	}

	public static function getView($file, $data = array(), $output = true){
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