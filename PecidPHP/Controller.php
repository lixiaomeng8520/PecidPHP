<?php
/**
 * base controller
 */
class Controller
{
	function __construct(){
		// $this->_init_session();
	}

	function doAction($action){
		if(method_exists($this, $action)){
			$this->$action();
		}
		else{
			trigger_error('action'.$action.'未找到', E_USER_ERROR);
		}
	}

	/**
	 * 默认session存在数据库	
	 */
	protected function _init_session(){
		import('session');
		$session = new Session();
		session_set_save_handler(array(&$session, "open"), 
		                         array(&$session, "close"), 
		                         array(&$session, "read"), 
		                         array(&$session, "write"), 
		                         array(&$session, "destroy"), 
		                         array(&$session, "gc"));
		session_start();
	}
}

?>