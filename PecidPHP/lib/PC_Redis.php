<?php 
class PC_Redis extends PC_Lib{

	private $_redis;

	protected $_config = array(
		'host'		=> '',
		'password' 	=> '',
		'port'		=> '',
		'timeout' 	=> '',
	);
	

	public function __construct($config = array()){
		parent::__construct($config);

		if(!extension_loaded('redis')){
			trigger_error('redis extension not load', E_USER_ERROR);
		}
		
		$this->_redis = new Redis();
		if(!$this->_redis->connect($this->_config['host'], $this->_config['port'])){
			trigger_error('redis connect false', E_USER_ERROR);
		}

		$this->_redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);
	}

	public function get($k){
		$v = $this->_redis->get($k);
		return $v;
	}

	public function set($k, $v){
		return $this->_redis->set($k, $v);
	}

	public function delete($k){
		return $this->_redis->delete($k);
	}

	public function clear() {
		return $this->_redis->flushAll();
	}
}

?>