<?php 
if(!defined('ENV')){ exit('access deny'); }

/// get and set config
function Conf($k = null, $v = null){
    static $_config = array();
    if($k === null) return $_config;

    if(is_string($k)){
        if($v === null){
            return $_config[$k];
        }
        else{
            $_config[$k] = $v;
        }
    }

    if(is_array($k)){
        return $_config = array_merge($_config, array_change_key_case($k));
    }
    return null;
}

/// get controller instance
function C($class_name){
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

/// get db instance
function D($config){
    static $_instance = null;
    if($_instance === null){
        $_instance = new MysqlDb($config);
    }
    return $_instance;
}

/// get model instance
function M($class_name){
    $class_name = ucfirst($class_name);
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

/// get view
function V($file, $data = array(), $output = true){
    extract($data);
    $file = VIEW_PATH.'/'.$file.'.php';
    if(!is_file($file)){
        trigger_error('view not found: '.$file, E_USER_ERROR);
    }
    if($output){
        require $file;
    }else{
        ob_start();
        require View_PATH.'/'.$file;
        $out = ob_get_clean();
        return $out;
    }
}

/// include dir 
function import($file, $is_core = false){
    if($is_core){
        $file = CORE_PATH.'/include/'.$file;    
    }else{
        $file = CORE_PATH.'/'.$file;    
    }
    if(!is_file($file)){
        trigger_error('no file found. '.$file, E_USER_ERROR);
    }
    require $file;
}
?>