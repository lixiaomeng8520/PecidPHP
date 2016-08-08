<?php
/**
 * base model, provides some common functions
 */ 
class Model{

	protected $_db = null;

	public function __construct(){
		$this->_db = Factory::getMysqlDb(C('db'));
	}

	public function get($id){
		$sql = 'select * from %t where '.$this->_pk.'=%s';
		$ret = $this->_db->getFirst($sql, array($this->_table, $id));
		return $ret;
	}

	public function test(){
		$ret = $this->_db->update('user', 'id=id+%s', 'id=%s', array(10, 18));
		var_dump($ret, $this->getAllSql());die;
	}

	public function getAllSql(){
		return $this->_db->getAllSql();
	}

	public function getLastSql(){
		return $this->_db->getLastSql();
	}
}





?>