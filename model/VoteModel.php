<?php
if(!defined('ENV')){ exit('access deny'); }

class VoteModel extends Model{
	// protected $_pk = 'aid';
	protected $_table = 'vote';

	protected $_fields = array('uid', 'openid', 'pid', 'aid', 'ip', 'time');

	public function getLastVote($uid, $pid){
		$sql = 'select * from %t where uid=%s and pid=%s order by time desc limit 1';
		return $this->_db->getFirst($sql, array($this->_table, $uid, $pid));
	}

	public function getCountByPid($pid){
		$sql = 'select count(*) from %t where pid = %s';
		return $this->_db->getOne($sql, array($this->_table, $pid));
	}
}
?>