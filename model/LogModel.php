<?php
if(!defined('ENV')){ exit('access deny'); }

class LogModel extends PC_Model{
	protected $_pk = 'lid';
	protected $_table = 'log';

	protected $_fields = array('lid', 'adminid', 'adminname', 'type', 'url', 'data', 'ip', 'time');

	public function getAll(){
		$sql = 'select * from %t order by `time` desc';
		return $this->_db->getAll($sql, array($this->_table));
	}


}

?>