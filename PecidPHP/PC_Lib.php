<?php 
class PC_Lib{
	protected $_error = array();

	// 初始化类库，如果config是空，则用系统config
	public function __construct($config = array()){
		$class = get_class($this);
		$config_key = substr($class, 3);
		$config = $config ? $config : (Conf($config_key) ? Conf($config_key) : array());

		foreach($config as $k => $v){
			$this->_config[$k] = $v;
		}
	}

	// 设置错误信息
	public function set_error($error){
		if(is_string($error)){
			$this->_error[] = $error;	
		}elseif(is_array($error)){
			array_merge($this->_error, $error);	
		}else{
			trigger_error('错误信息必须为数组或字符串', E_USER_ERROR);
		}
	}

	// 获取错误信息
	public function get_error(){
		return $this->_error;
	}
}

?>