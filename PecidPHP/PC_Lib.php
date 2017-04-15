<?php 
class PC_Lib{
	protected $_msg = array();
	protected $_config = array();

	// 初始化类库，如果config是空，则用系统config
	public function __construct($config = array()){
		// $class = get_class($this);
		// $config_key = substr($class, 3);
		// $config = $config ? $config : (Conf($config_key) ? Conf($config_key) : array());

		foreach($config as $k => $v){
			$this->_config[$k] = $v;
		}
	}

	/**
	 * 设置信息
	 * @param	string|array	信息
	 * @return	void
	 */
	public function setMsg($msg){
		if(is_string($msg)){
			$this->_msg[] = $msg;
		}elseif(is_array($msg)){
			$this->_msg = array_merge($this->_msg, $msg);
		}else{
			trigger_error('参数错误', E_USER_ERROR);
		}
	}

	/**
	 * 获取所有信息
	 * @return	array
	 */
	public function getAllMsg(){
		return $this->_msg;
	}

	/**
	 * 获取最后一条信息，不存在则为空字符串
	 * @return	string
	 */
	public function getLastMsg(){
		return $this->_msg ? $this->_msg[count($this->_msg) - 1] : '';
	}

}

?>