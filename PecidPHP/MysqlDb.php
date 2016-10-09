<?php 
if(!defined('ENV')){ exit('access deny'); }

class MysqlDb {
	private $_type = 'mysql';
	private $_link;
	private $_escape_func;

	private $_all_sql = array();
	private $_last_sql = '';

	private $_db_pref = '';
	private $_db_host = '';
	private $_db_port = '';
	private $_db_user = '';
	private $_db_pass = '';
	private $_db_dbnm = '';
	private $_db_char = '';

	/*private static $_instance;

	private function __construct(){}

	public function __clone(){ trigger_error('can not clone', E_USER_ERROR); }

	public static function getInstance(){
		if(!self::$_instance instanceof self){
			self::$_instance = new self;
		}
		return self::$_instance;
	}*/

	/**
	 * $config = array(
	 *	'pref'	=>	'',
	 *	'host'	=>	'127.0.0.1',
	 *	'port'	=>	'3306',
	 *	'user'	=>	'root',
	 *	'pass'	=>	'root',
	 *	'dbnm'	=>	'test',
	 *	'char'	=>	'utf8',);
	 */
	public function __construct($config){
		$this->_init_escape_string_func();
		$this->_init_config($config);
		$this->_init_db();
	}

	public function getAllSql(){
		return $this->_all_sql;
	}

	public function getLastSql(){
		return $this->_last_sql;
	}

	/*-----------------------------start init------------------------------------------*/

	private function _init_escape_string_func(){
		if(function_exists('mysql_real_escape_string') && $this->_link){
			$this->_escape_func = 'mysql_real_escape_string';
		}elseif(function_exists('mysql_escape_string')){
			$this->_escape_func = 'mysql_escape_string';
		}else{
			trigger_error('check mysql escape_string func', E_USER_ERROR);
		}
	}

	private function _init_config($config){
		if(empty($config) || !is_array($config)){
			trigger_error('db config error', E_USER_ERROR);
		}
		foreach($config as $k => $v){
			$kn = '_db_'.$k;
			$this->$kn = $v;
		}
	}

	private function _init_db(){
		$this->_link = @mysql_connect($this->_db_host.':'.$this->_db_port, $this->_db_user, $this->_db_pass);
		if(!$this->_link){
			trigger_error('conn error: '.mysql_error(), E_USER_ERROR);
		}
		mysql_query('set names '.$this->_db_char, $this->_link);
		mysql_select_db($this->_db_dbnm, $this->_link);
	}

	/*-----------------------------end init--------------------------------------------*/

	/*---------------------------start opdb--------------------------------------------*/

	public function getAll($sql, $args = array()){
		$query = $this->query($sql, $args);
		$ret = array();
		while($row = mysql_fetch_assoc($query)){
			$ret[] = $row;
		}
		mysql_free_result($query);
		return $ret;
	}

	public function getFirst($sql, $args = array()){
		$query = $this->query($sql, $args);
		$ret = mysql_fetch_assoc($query);
		mysql_free_result($query);
		return $ret ? $ret : null;
	}

	public function getOne($sql, $args = array()){
		$ret = $this->getFirst($sql, $args);
		return count($ret) > 0 ? current($ret) : false;
	}

	public function insert($table, $data){
		$table = $this->_parseTable($table);
		$set_str = $this->_implodeArray($data);
		$sql = 'insert into '.$table.' '.$set_str;
		return $this->query($sql);
	}

	public function update($table, $data, $where, $args){
		$table = $this->_parseTable($table);
		if(is_string($data)){
			$set_str = 'set '.$data;
		}elseif(is_array($data)){
			$set_str = $this->_implodeArray($data);
		}
		$where = $this->_parseSql($where, $args);
		$sql = 'update '.$table.' '.$set_str.' where '.$where;
		return $this->query($sql, $args);
	}

	public function getInsertId() {
		return ($id = mysql_insert_id($this->_link)) >= 0 ? $id : $this->getOne("select last_insert_id()");
	}

	private function _implodeArray($data){
		if(!is_array($data) || count($data) == 0){
			trigger_error('data must be array and not empty', E_USER_ERROR);
		}
		$ret_arr = array();
		foreach($data as $k => $v){
			$item = '`'.$k.'` = '.$this->_parseValue($v);
			$ret_arr[] = $item;
		}
		$ret = 'set '.implode(',', $ret_arr);
		return $ret;
	}

	/*not return false*/
	public function query($sql, $args = array()){
		if(strpos($sql, 'update') !== 0 && strpos($sql, 'insert') !== 0){
			$sql = $this->_parseSql($sql, $args);	
		}
		$this->_all_sql[] = $sql;
		$this->_last_sql = $sql;

		$query = mysql_query($sql, $this->_link);
		if(!$query){
			trigger_error('mysql_query error, '.mysql_error($this->_link).'<br>'.$sql, E_USER_ERROR);
		}

		return $query;
	}

	/*-----------------------------end opdb--------------------------------------------*/	


	/*-----------------------------start parseSql--------------------------------------*/

	private function _parseSql($sql, $args = array()){
		if(!is_string($sql) || !is_array($args)){
			trigger_error('sql must be string, args must be array', E_USER_ERROR);
		}

		$sql = trim($sql);

		$count = substr_count($sql, '%');
		if($count != count($args)){
			trigger_error('% args count not the same', E_USER_ERROR);	
		}
		if($count == 0){
			return $sql;
		}

		$len = strlen($sql);
		$ret = '';

		for($i = $p = 0; $i < $len; $i++){
			if($sql{$i} == '%'){
				$next = $sql{$i+1};
				switch ($next) {
					case 't':
						$ret .= $this->_parseTable($args[$p]);
						break;
					case 's':
						$ret .= $this->_parseValue($args[$p]);
						break;
/*					case 'i':
						$ret .= intval($args[$p]);
						break;
					case 'f':
						$ret .= floatval($args[$p]);
						break;
					case 'a':
						$ret .= $this->_parseValue($args[$p]);
						break;*/
					default:
						trigger_error('not valid placeholder'.$next, E_USER_ERROR);
						break;
				}
				$i++;
				$p++;
			}else{
				$ret .= $sql{$i};
			}
		}
		
		return $ret;
	}

	private function _parseValue($value){
		$ret = '';
		if(is_int($value) || is_float($value)){
			return $value;
		}elseif(is_string($value)){
			$func = $this->_escape_func;
			$ret = '\''.@$func($value).'\'';
		}elseif(is_array($value)){
			foreach($value as $k => $v){
				$value[$k] = $this->_parseValue($v);	
			}
			$ret = '('.implode(',', $value).')';
		}else{
			trigger_error('value is not valid '.gettype($value), E_USER_ERROR);
		}
		return $ret;
	}

	private function _parseTable($table){
		if(!is_string($table) || empty($table = trim($table))){
			trigger_error('table name is invalid');
		}
		return $this->_db_pref.$table;
	}

	/*-----------------------------end parseSql----------------------------------------*/

}


?>