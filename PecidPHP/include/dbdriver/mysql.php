<?php
class MysqliDriver
{
	var $_db_host = '';
	var $_db_port = 3306;
	var $_db_name = '';
	var $_db_user = '';
	var $_db_pass = '';

	var $_link = null;

	function __construct($db_host, $db_port, $db_user, $db_pass, $db_name)
	{
		$this->MysqlDb($db_host, $db_port, $db_user, $db_pass, $db_name);
	}

	function MysqlDb($db_host, $db_port, $db_user, $db_pass, $db_name)
	{
		$this->_db_host = $db_host;
		$this->_db_port = $db_port;
		$this->_db_user = $db_user;
		$this->_db_pass = $db_pass;
		$this->_db_name = $db_name;
	}

	/*连接数据库*/
	private function _connect()
	{
		/*如果连接已存在，则直接返回*/
		if($this->_link)
		{
			return true;
		}

		$this->_link = @mysql_connect($this->_db_host, $this->_db_user, $this->_db_pass);
		if($this->_link === false)
		{
			return false;
		}

		@mysql_set_charset('utf-8',$this->_link);

		if(@mysql_select_db($this->_db_name, $this->_link) === false)
		{
			return false;
		}

		return true;
	}

	function get($sql)
	{
		$this->_connect();
		if(($temp = mysql_query($sql, $this->_link)) === false)
		{
			return false;
		}

		$ret = array();
		while(($row = mysql_fetch_array($temp, MYSQL_ASSOC)) !== false)
		{
			array_push($ret, $row);
		}
		return $ret;
	}

	function get_one($sql)
	{
		if($ret = $this->get($sql) === false)
		{
			return false;
		}
		return current($ret);
	}

	function insert($sql)
	{
		$this->_connect();
		if(mysql_query($sql, $this->_link) === false)
		{
			return false;
		}
		return mysql_insert_id($this->_link);
	}

	function update($sql)
	{
		$this->_connect();
		if(mysql_query($sql) === false)
		{
			return false;
		}
		return mysql_affected_rows($this->_link);
	}

	function delete($sql)
	{
		$this->_connect();
		if(mysql_query($sql) === false)
		{
			return false;
		}
		return mysql_affected_rows($this->_link);
	}
}
?>