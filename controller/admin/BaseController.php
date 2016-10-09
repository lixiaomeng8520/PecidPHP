<?php 
if(!defined('ENV')){ exit('access deny'); }

class BaseController extends Controller{
	protected $_no_login_action = array();
	protected $_admin = null;
	protected $_redirect = array();

	public function __construct(){
		define('PUBLIC_ADMIN_URL', PUBLIC_URL.'/admin');
		$this->_init_admin();
		if(!in_array(ACTION, $this->_no_login_action) && !$this->_admin){
			if(IS_AJAX){
				$login_url = U('Index', 'login', array('redirect'=>REFERER_URL));
				$this->ajaxReturn(0, '请登录', $login_url);
			}else{
				$query = empty($_GET) ? array() : array('redirect'=>CURRENT_URL);
				$login_url = U('Index', 'login', $query);
				header('Location: '.$login_url);	
			}
		}
	}

	protected function _init_admin(){
		if(isset($_SESSION['admin'])){
			$admin = $_SESSION['admin'];
			$m_admin = M('Admin');
			$info = $m_admin->getByNameAndPass($admin['adminname'], $admin['password']);
			if(!$info){
				$admin = null;
			}
			$this->_admin = $admin;
		}
	}

	protected function _message($code, $msg, $redirect = '', $data = null){
		if(IS_POST && $this->_admin && $code == 1){
			$dd_log_type = get_data_dic('log_type', 'action');
			if(isset($dd_log_type[CONTROLLER.'.'.ACTION])){
				$log_type = $dd_log_type[CONTROLLER.'.'.ACTION];
				$log = array(
					'adminid'	=>	$this->_admin['adminid'],
					'adminname'	=>	$this->_admin['adminname'],
					'type'		=>	$log_type['type'],
					'url'		=>	CURRENT_URL,
					'data'		=>	serialize($_POST),
					'ip'		=>	CLIENT_IP,
					'time'		=>	time(),
				);
				M('Log')->insert($log);
			}
		}

		parent::_message($code, $msg, $redirect, $data);
	}
}
?>