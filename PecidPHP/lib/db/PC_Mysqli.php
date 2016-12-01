<?php 
class PC_Mysqli extends PC_Db{

	protected $_escape_column_char = '`';

	protected function _connect(){
		// 如果host以/开头，则使用socket方式连接，这时候host和port可都设为null
		$host = $this->_config['host'];
		$port = $this->_config['port'];
		$socket = null;
		if(@$this->_config['host']{0} == '/'){
			$host = null;
			$port = null;
			$socket = $this->_config['host'];
		}

		// 初始化对象
		$mysqli = mysqli_init();

		# TODO 这里可以添加一些参数
		# $mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 10);

		// 连接数据库 使用real_connect可以在连接数据库之前，设置options参数。但是必须使用init返回的对象。
		if($mysqli->real_connect($host, $this->_config['username'], $this->_config['password'], $this->_config['dbname'], $port, $socket)){
			if($mysqli->set_charset($this->_config['charset'])){
				$this->_link = $mysqli;
				return true;
			}
			$mysqli->close();
		}
		return false;
	}

	protected function _reConnect(){}

	protected function _selectDb($dbname){
		return $this->_link->select_db($dbname);
	}

	protected function _affectedRows(){
		return $this->_link->affected_rows;
	}

	protected function _insertId(){
		return $this->_link->insert_id;
	}

	protected function _getError(){
		if($this->_link->connect_errno){
			return $this->_link->connect_errno.' : '.$this->_link->connect_error;
		}
		return $this->_link->errno.' : '.$this->_link->error;
	}

	protected function _escapeStr($str){
		return $this->_link->real_escape_string($str);
	}

	protected function _query($sql){
		return $this->_link->query($sql);
	}

	protected function _fetchArray($result){
		$ret = array();
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$ret[] = $row;
		}
		return $ret;
	}

}

?>