<?php
/**
 * base controller
 */
class PC_Controller
{
	function __construct(){
		// $this->_init_session();
	}

	function doAction($action){
		if(method_exists($this, $action)){
			$this->$action();
		}else{
			trigger_error('action '.$action.' not found', E_USER_ERROR);
		}
	}

	function _display($file, $data = array(), $output = true){
	    if(!is_array($data)){
	        trigger_error('function _display params invalid', E_USER_ERROR);
	    }
	    extract($data);
	    $file = VIEW_PATH.'/'.$file.'.php';
	    if(!is_file($file)){
	        trigger_error('view not found: '.$file, E_USER_ERROR);
	    }
	    if($output){
	        require $file;
	    }else{
	        ob_start();
	        require $file;
	        $out = ob_get_clean();
	        return $out;
	    }
	}

	protected function _message($code, $msg, $redirect = '', $data = null){
		if(IS_AJAX){
			$ret = array(
				'code'		=>	$code,
				'msg'		=>	$msg,
				'redirect'	=>	$redirect,
				'data'		=>	$data,
			);
			echo json_encode($ret); 
		}else{
			echo $msg;
		}
		exit;
	}


	/*protected function _init_session(){
		import('session');
		$session = new Session();
		session_set_save_handler(array(&$session, "open"), 
		                         array(&$session, "close"), 
		                         array(&$session, "read"), 
		                         array(&$session, "write"), 
		                         array(&$session, "destroy"), 
		                         array(&$session, "gc"));
		session_start();
	}*/
}

?>