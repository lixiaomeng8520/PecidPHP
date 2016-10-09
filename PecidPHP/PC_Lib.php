<?php 
class PC_Lib{
	protected $_errors = array();

	public function __construct($config = array()){
		$class = get_class($this);
		$config_key = substr($class, 3);
		$config = $config ? $config : (Conf($config_key) ? Conf($config_key) : array());

		foreach($config as $k => $v){
			$this->_config[$k] = $v;
		}
	}
}

?>