<?php
if(!defined('ENV')){ exit('access deny'); }

class AdminModel extends PC_Model{
	protected $_pk = 'adminid';
	protected $_table = 'admin';
	protected $_fields = array('adminid', 'adminname', 'password');
	protected $_validator = array();

	public function getByNameAndPass($adminname, $password){
		$sql = 'select * from %t where `adminname`=%s and `password`=%s';
		return $this->_db->getFirst($sql, array($this->_table, $adminname, $password));
	}
}

?>