<?php
// if(!defined('ENV')){ exit('access deny'); }

function __autoload($name){
    PecidPHP::autoLoad($name);
}

set_error_handler('PecidPHP::errorHandler');
// register_shutdown_function('PecidPHP::trace');

PecidPHP::start();

/**
 *	系统入口文件
 *	@author lxm
 */
class PecidPHP
{
	public static function start()
	{  
        // 定义环境
        isset($_SERVER['env']) && define('ENV', $_SERVER['env']);
        $env_arr = array('dev', 'test', 'online');
        if(!defined('ENV') || !in_array(ENV, $env_arr)){
            trigger_error('ENV must be one of [dev, test, online]', E_USER_ERROR);
        }

        // 根据不同环境输出不同错误信息
        if(ENV == 'dev' || ENV == 'test'){
            ini_set('display_errors', 'on');
            error_reporting(-1);
        }else{
            ini_set('display_errors', 'off');
        }

        // 不开启magic_quotes_gpc
        if(get_magic_quotes_gpc()){
            trigger_error('please close magic_quotes_gpc', E_USER_ERROR);
        }

        // 定义入口模块常量
        $pathinfo = pathinfo($_SERVER['SCRIPT_FILENAME']);
        define('MODULE', $pathinfo['filename']);

        // 定义路径常量
        define('ROOT_PATH', dirname($_SERVER['SCRIPT_FILENAME']));
        define('CORE_PATH', dirname(__FILE__));
        define('CONFIG_PATH', ROOT_PATH.'/config');
        define('CONTROLLER_PATH', defined('MODULE') ? ROOT_PATH.'/controller/'.MODULE : ROOT_PATH.'/controller');
        define('DATA_PATH', ROOT_PATH.'/data');
        define('INCLUDE_PATH', ROOT_PATH.'/include');
        define('MODEL_PATH', ROOT_PATH.'/model');
        define('PUBLIC_PATH', ROOT_PATH.'/public');
        define('VIEW_PATH', ROOT_PATH.'/view');

        // 加载核心方法文件
        require(CORE_PATH.'/function.php');

        // 加载配置文件，系统核心，项目config目录下对应环境配置和其他配置
        Conf(require(CORE_PATH.'/config.php'));
        foreach(scandir(CONFIG_PATH) as $file){
            if($file == '.' || $file == '..'){
                continue;
            }
            $path = CONFIG_PATH .'/'. $file;
            if(is_file($path) && ($file == ENV.'.php' || !in_array(basename($path, '.php'), $env_arr))){
                Conf(require($path));
            }
        }

        // 定义请求类型常量
        define('IS_GET', $_SERVER['REQUEST_METHOD'] == 'GET' ? true : false);
        define('IS_POST', $_SERVER['REQUEST_METHOD'] == 'POST' ? true : false);
        define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ? true : false);

        // 定义URL常量
        define('HTTP', (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http");
        define('SITE_URL', HTTP.'://'.$_SERVER['HTTP_HOST']);
        define('SCRIPT_URL', SITE_URL.$_SERVER['SCRIPT_NAME']);
        define('CURRENT_URL', SITE_URL.$_SERVER['REQUEST_URI']);
        define('REFERER_URL', isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');
        define('PUBLIC_URL', SITE_URL.'/public');
        define('DATA_URL', SITE_URL.'/data');

        // 定义一些通用常量
        define('CLIENT_IP', get_client_ip());
        define('TIMESTAMP', $_SERVER['REQUEST_TIME']);

        // 定义时区
        date_default_timezone_set('Asia/Shanghai');
        session_start();
        
        // 包含核心文件
        require(CORE_PATH.'/PC_Controller.php');
        require(CORE_PATH.'/PC_Model.php');
        require(CORE_PATH.'/PC_Lib.php');
        
        // 定义当前controller和action常量
        define('CONTROLLER', isset($_GET['_c']) && $_GET['_c'] ? ucfirst($_GET['_c']) : 'Index');
        define('ACTION', isset($_GET['_a']) && $_GET['_a'] ? $_GET['_a'] : 'index');

        // 执行action
        C(CONTROLLER)->doAction(ACTION);
	}

    public static function autoLoad($file){
        if(strpos($file, 'Controller') > 0){
            $file = CONTROLLER_PATH.'/'.$file.'.php';
        }elseif(strpos($file, 'Model') > 0){
            $file = MODEL_PATH.'/'.$file.'.php';
        }
        if(!is_file($file)){
            trigger_error('file '.$file.' not found', E_USER_ERROR);
        }
        require($file);
    }

    public static function errorHandler($type, $msg, $file, $line){
        if(($type & error_reporting()) !== $type){
            return;
        }
        
        $levels = array(
            E_ERROR         =>  'Error',
            E_WARNING       =>  'Warning',
            E_PARSE         =>  'Parsing Error',
            E_NOTICE        =>  'Notice',
            E_CORE_ERROR        =>  'Core Error',
            E_CORE_WARNING      =>  'Core Warning',
            E_COMPILE_ERROR     =>  'Compile Error',
            E_COMPILE_WARNING   =>  'Compile Warning',
            E_USER_ERROR        =>  'User Error',
            E_USER_WARNING      =>  'User Warning',
            E_USER_NOTICE       =>  'User Notice',
            E_STRICT        =>  'Strict',
            E_RECOVERABLE_ERROR =>  'Recoverable error',
            E_DEPRECATED    =>  'Deprecated',
            E_USER_DEPRECATED    =>  'User Deprecated',
        );

        $error_arr = array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR);
        if(in_array($type, $error_arr)){
            echo '  <p>type: '.$levels[$type].'</p>
                    <p>msg:  '.$msg.'</p>
                    <p>file: '.$file.'</p>
                    <p>line: '.$line.'</p>';
            $table = '<table border="1">
                        <thead><tr>
                            <th>文件</th>
                            <th>行</th>
                            <th>方法</th>
                        </tr></th>
                        <tbody>';
            foreach(debug_backtrace() as $k => $v){
                if(isset($v['file'])){
                    $table .= '<tr>';
                    $table .= '<td>'.$v['file'].'</td>';
                    $table .= '<td>'.$v['line'].'</td>';
                    $table .= '<td>'.$v['function'].'</td>';
                    // $table .= '<td>'.$v['file'].'</td>';
                    $table .= '</tr>';
                }
            }
            $table .= '</tbody></table>';
            echo $table;
        }else{
            echo '<p>'.$levels[$type].' '.$msg.' '.$file.' '.$line.'</p>';
        }
        exit();
    }
}


?>
