<?php 
class PC_Upload extends PC_Lib{
	private $_real_upload_dir = '';
	protected $_config = array(
		'upload_dir'		=>	'',
		'allow_extension'	=>	array(),
		'allow_mine_type'	=>	array(),
		'max_size'			=>	2000000, // byte; 2mb;  0为不受限制
		'form_file_name'	=>	'file',
	);

	public function __construct($config = array()){
		parent::__construct($config);

		// 判断上传目录是否存在且可写
		if($this->_config['upload_dir'] === ''){
			trigger_error('上传目录不存在', E_USER_ERROR);
		}
		$this->_real_upload_dir = DATA_PATH.'/'.$this->_config['upload_dir'];
		if(!is_dir($this->_real_upload_dir)){
			trigger_error('上传目录不存在', E_USER_ERROR);
		}
		if(!is_readable($this->_real_upload_dir)){
			trigger_error('上传目录不可读', E_USER_ERROR);
		}
		if(!is_writable($this->_real_upload_dir)){
			trigger_error('上传目录不可写', E_USER_ERROR);
		}

	}

	// 执行上传,目前是单个上传
	// 成功返回file_name, 失败返回false
	public function upload(){
		if(!isset($_FILES[$this->_config['form_file_name']])){
			$this->set_error('上传文件不存在');
			return false;
		}

		$file = $_FILES[$this->_config['form_file_name']];

		// 检查是否是正常上传文件
		if(!is_uploaded_file($file['tmp_name'])){
			switch($file['error']){
				case 1:
					$this->set_error('上传文件太大');
					break;
				case 2:
					$this->set_error('上传文件太大');
					break;
				case 3:
					$this->set_error('上传文件异常');
					break;
				case 4:
				default:
					$this->set_error('上传文件不存在');
					break;
			}
			return false;
		}

		// 检查大小
		if($this->_config['max_size'] > 0 && $file['size'] > $this->_config['max_size']){
			$this->set_error('上传文件太大');
			return false;
		}

		// 检查扩展名, 扩展名全部改为小写
		$extension = explode('.', $file['name']);
		if(count($extension) === 1){
			$extension = '';
		}else{
			$extension = strtolower(end($extension));
		}
		if(!$extension || ($this->_config['allow_extension'] && !in_array($extension, $this->_config['allow_extension']))){
			$this->set_error('文件类型错误');
			return false;
		}

		// 检查mime类型
		if($this->_config['allow_mine_type']){
			// 获取mime类型
			$finfo = finfo_open(FILEINFO_MIME);
			if($finfo === false){
				trigger_error('无法打开finfo_open', E_USER_ERROR);
			}else{
				$mime = finfo_file($finfo, $file['tmp_name']);
				finfo_close($finfo);	
				if($mime === false){
					trigger_error('finfo_file获取mime类型错误', E_USER_ERROR);
				}
			}
			
			// image/jpeg; charset=binary
			$regexp = '/^([a-z\-]+\/[a-z0-9\-\.\+]+)(;\s.+)?$/';
			preg_match($regexp, $mime, $matches);
			$mime = $matches[1];
			if(!in_array($mime, $this->_config['allow_mine_type'])){
				$this->set_error('文件类型错误');
				return false;
			}
		}

		// 生成新的文件名
		$file_name = md5(uniqid(mt_rand())).'.'.$extension;
		$dir = substr($file_name, 0, 2).'/'.substr($file_name, 2, 2);
		$dir_path = $this->_real_upload_dir.'/'.$dir;
		if(!is_dir($dir_path)){
			mkdir($dir_path, 0755, true);
		}
		$file_path = $dir_path.'/'.$file_name;

		// 移动文件
		if(!move_uploaded_file($file['tmp_name'], $file_path)){
			$this->set_error('上传失败');
			return false;
		}

		return $file_name;
	}

	public function getFileUrl($file_name){
		$file_path = substr($file_name, 0, 2).'/'.substr($file_name, 2, 2).'/'.$file_name;
		$url = DATA_URL.'/'.$this->_config['upload_dir'].'/'.$file_path;
		return $url;
	}

}
?>