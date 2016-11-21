<?php 
class PC_Mysqli extends PC_Db_Driver{

	/**
	 * 使用real_connect可以在连接数据库之前，设置options参数。但是必须使用init返回的对象。
	 */
	public function connect(){
		// 如果host以/开头，则使用socket方式连接，这时候host和port可都设为null
		$host = $this->_config['host'];
		$port = $this->_config['port'];
		$socket = null;
		if($this->_config['host']{0} == '/'){
			$host = null;
			$port = null;
			$socket = $this->_config['host'];
		}

		// 初始化对象
		$mysqli = mysqli_init();

		# TODO 这里可以添加一些参数
		# $mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 10);
		
		// 连接数据库
		if($mysqli->real_connect($host, $this->_config['user'], $this->_config['pass'], $this->_config['dbnm'], $port, $socket)){
			if($mysqli->set_charset($this->_config['char'])){
				$this->_link = $mysqli;
				return true;
			}
			$mysqli->close();
		}
		return false;
	}

	/**
	 * override
	 * 返回最后一条错误
	 * @return string 错误信息
	 */
	public function get_error(){
		if($this->_driver->connect_errno){
			return $this->_driver->connect_errno.' : '.$this->_driver->connect_error;
		}
		return $this->_driver->errno.' : '.$this->_driver->error;
	}
}

?>