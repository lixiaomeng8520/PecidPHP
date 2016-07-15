<?php
class Controller
{
	//var $_view = null;	//视图

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

	function assign($k, $v = null){
		$view = Factory::getView();
		$view->assign($k, $v);
	}

	function display($v){
		$view = Factory::getView($v);
		$view->display($v);
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