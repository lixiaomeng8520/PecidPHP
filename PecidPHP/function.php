<?php 
if(!defined('ENV')){ exit('access deny'); }

/// get and set config
function Conf($k = null, $v = null){
    static $_config = array();
    if($k === null) return $_config;

    if(is_string($k)){
        if($v === null){
            if(!isset($_config[$k])){
                return null;
                // trigger_error('config key not found: '.$k, E_USER_ERROR);
            }
            return $_config[$k];
        }
        else{
            $_config[$k] = $v;
        }
    }

    if(is_array($k)){
        return $_config = array_merge($_config, $k);
    }
    return null;
}

/// get controller instance
function C($class){
    $class = ucfirst($class);
    static $_instance = array();

    if(!isset($_instance[$class]) || !$_instance[$class]){
        // $c_file = CONTROLLER_PATH.'/'.$class.'Controller.php';
        // if(!is_file($c_file)){
        //     trigger_error('file '.$c_file.' not found', E_USER_ERROR);
        // }

        // require($c_file);

        $class .= 'Controller';
        $controller = new $class();

        $_instance[$class] = $controller;  
    }
    
    return $_instance[$class];
}

/// get db instance
// function D($config){
//     static $_instance = null;
//     if($_instance === null){
//         $_instance = new MysqlDb($config);
//     }
//     return $_instance;
// }

/// get model instance
function M($class){
    $class = ucfirst($class);
    static $_instance = array();
    if(!isset($_instance[$class]) || !$_instance[$class]){
        // $c_file = MODEL_PATH.'/'.$class.'Model.php';
        // if(!is_file($c_file)){
        //     trigger_error('file '.$c_file.' not found', E_USER_ERROR);
        // }

        // require($c_file);

        $class .= 'Model';
        $model = new $class();

        $_instance[$class] = $model;   
    }
    
    return $_instance[$class];
}



/// include dir 
function import($file, $is_core = false){
    $file .= '.php';
    if($is_core){
        $file = CORE_PATH.'/include/'.$file;    
    }else{
        $file = ROOT_PATH.'/include/'.$file;    
    }
    if(!is_file($file)){
        trigger_error('no file found. '.$file, E_USER_ERROR);
    }
    require $file;
}

function U($controller, $action, $query = array(), $module = MODULE){
    if(!is_string($controller) || !is_string($action) || !is_array($query)){
        trigger_error('invalid params', E_USER_ERROR);
    }

    $controller = ucfirst($controller);

    $rewrite = Conf('rewrite');
    $rewrite_url = '';
    if(isset($rewrite[$module])){
        $query_keys = array_keys($query);
        foreach($rewrite[$module] as $rule){
            $from = $rule['from'];
            $to = $rule['to'];
            if($controller == $from['controller'] && $action == $from['action'] && !array_diff($from['query'], $query_keys)){    
                $rewrite_url = preg_replace_callback('/\[(.*?)\]/', function($matches) use ($query){
                    return $query[$matches[1]];
                }, $to);
                $other_query = array_diff($query_keys, $from['query']);
                if($other_query){
                    $rewrite_url .= '?';
                    foreach($other_query as $other_key){
                        $rewrite_url .= $other_key.'='.rawurlencode($query[$other_key]).'&';
                    }
                    $rewrite_url = rtrim($rewrite_url, '&');
                }
            }
        }
    }

    if($rewrite_url){
        $url = SITE_URL.'/'.$rewrite_url;
    }else{
        $query_str = '';
        if($query){
            foreach($query as $k => $v){
                $query_str .= '&'.rawurlencode($k).'='.rawurlencode($v);
            }
        }
        $url = SITE_URL.'/'.$module.'.php?_c='.$controller.'&_a='.$action.$query_str;
    }

    return $url;
}

function G($k){
    return isset($_GET[$k]) ? $_GET[$k] : null;
}

function P($k){
    return isset($_POST[$k]) ? $_POST[$k] : null;
}

function format_date($unixtimestamp, $format = 'Y-m-d H:i:s'){
    return date($format, intval($unixtimestamp));
}

function random($namespace = '') {     
    static $guid = '';
    $uid = uniqid("", true);
    $data = $namespace;
    $data .= microtime(true);
    $data .= $_SERVER['HTTP_USER_AGENT'];
    $data .= $_SERVER['REMOTE_ADDR'];
    $data .= $_SERVER['REMOTE_PORT'];
    $hash = strtolower(hash('ripemd128', $uid . $guid . md5($data)));
    return $hash;
}

function debug($data){
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    exit;
}
function dump($data){
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
}

function get_client_ip() {
    $ip = $_SERVER['REMOTE_ADDR'];
    if (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
        foreach ($matches[0] AS $xip) {
            if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
                $ip = $xip;
                break;
            }
        }
    }
    return $ip;
}

function get_data_dic($conf, $key = ''){
    $data_dic = Conf('data_dic');
    if(!is_string($conf) && !isset($data_dic[$conf])){
        trigger_error('conf not found: '.$conf, E_USER_ERROR);
    }
    if(!is_string($key)){
        trigger_error('get_data_dic param\'s key must be string ', E_USER_ERROR);
    }

    $conf = $data_dic[$conf];
    if($key == ''){
        return $conf;
    }else{
        $ret = array();
        foreach($conf as $k => $v){
            $ret[$v[$key]] = $v;
        }
        return $ret;
    }
}

function str_to_html($str){
    

    $str = htmlspecialchars($str);
    $str = str_replace(array(' '), array('&nbsp;'), $str);
    $str = nl2br($str);

    return $str;
}

function thumb_url($src, $w = 640){
    $url = '';
    if($src){
        $src = rawurlencode('/data/'.Conf('upload_dir').'/'.$src);
        $url = SITE_URL.'/phpThumb/phpThumb.php?w='.$w.'&src='.$src;    
    }
    // debug($url);
    return $url;
}

// 加载扩展类
function lib($class, $is_singleton = true, $config = array()){
    if(substr($class, 0, 3) === 'PC_'){
        require_once CORE_PATH.'/lib/'.$class.'.php';
        if($is_singleton){
            static $_instance = array();
            if(!isset($_instance[$class])){
                $_instance[$class] = new $class($config);
            }
            return $_instance[$class];
        }else{
            return new $class($config);
        }
    }
    
}

?>