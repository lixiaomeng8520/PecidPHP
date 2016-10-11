<?php
if(!defined('ENV')){ exit('access deny'); }

class UserModel extends PC_Model{
	protected $_pk = 'uid';
	protected $_table = 'user';

	protected $_fields = array('uid', 'openid', 'nickname', 'sex', 'language', 'city', 'province', 'country', 'headimgurl', 'privilege', 'createtime');

	public function getByOpenid($openid){
		$sql = 'select * from %t where openid = %s';
		return $this->_db->getFirst($sql, array($this->_table, $openid));
	}
}

?>