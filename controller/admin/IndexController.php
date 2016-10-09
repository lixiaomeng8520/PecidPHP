<?php
if(!defined('ENV')){ exit('access deny'); }

class IndexController extends BaseController{
	protected $_no_login_action = array('login');

	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$this->_display('admin/index');
	}

	public function login(){
		if(IS_POST){
			$m_admin = M('Admin');
			$password = md5($_POST['password'].'vote');
			$info = $m_admin->getByNameAndPass($_POST['adminname'], $password);
			if($info){
				$this->_admin = $_SESSION['admin'] = $info;
				$this->_message(1, '登录成功', G('redirect') ? G('redirect') : U('Index', 'index'));
			}else{
				$this->_message(0, '用户名密码错误');
			}
		}else{
			$this->_display('admin/login');
		}
	}

	public function logout(){
		unset($_SESSION['admin']);
		header('Location: '.U('Index', 'login'));
	}

	public function upload(){
		$file = isset($_FILES['files']) ? $_FILES['files'] : null;
		if(!$file){
			$this->_message(2, '没有上传信息');
		}elseif($file['error']){
			$this->_message(3, '上传错误');
		}

		if($file['size'] >= 2000000){ //2M
			$this->_message(4, '文件太大');
		}elseif($file['size'] <= 0){
			$this->_message(5, '文件太小');
		}
		$ext = explode('.', $file['name']);
		$ext = strtolower($ext[count($ext) - 1]);
		if(!in_array($ext, array('jpg', 'jpeg', 'png', 'gif'))){
			$this->_message(6, '文件类型错误');
		}

		$dir = 'image/'.date('Ym').'/'.date('d').'/';
		$dic_dir = UPLOAD_PATH.'/'.$dir;

		if(!is_dir($dic_dir) && @mkdir($dic_dir, 0777,true) === false){
			$this->_message(7, '上传错误');
		}

		$randomname = random();
		$filename = $randomname.'.'.$ext;
		$dic_filename = $dic_dir.$filename;
		$db_url = $dir.$filename;
		$full_url = UPLOAD_URL.'/'.$db_url;

		if(@move_uploaded_file($file['tmp_name'], $dic_filename) === false){
			$this->_message(8, '上传错误');
		}else{
			$finfo    = finfo_open(FILEINFO_MIME_TYPE);
			$mimetype = strtolower(finfo_file($finfo, $dic_filename));
			finfo_close($finfo);

			$mimetype_arr = array('image/jpeg', 'image/png', 'image/gif');
			if(!in_array($mimetype, $mimetype_arr)){
				unlink($dic_filename);
				$this->_message(9, '文件类型错误');
			}

			$this->_message(1, '上传成功', '', array('db_url'=>$db_url, 'full_url'=>$full_url));
		}
	}
}
?>
