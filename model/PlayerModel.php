<?php
if(!defined('ENV')){ exit('access deny'); }

class PlayerModel extends PC_Model{
	protected $_pk = 'pid';
	protected $_table = 'player';
	protected $_fields = array('pid', 'aid', 'number', 'mobile', 'name', 'num', 'status', 'cover', 'gallery', 'desc', 'createtime');
	protected $_validator = array(
		'name'	=>	array(
			'require'	=>	array(
				'message'	=>	'姓名不能为空',
			),
		),
		'number'	=>	array(
			'require'	=>	array(
				'message'	=>	'编号不能为空',
			),
			'positive_integer'	=>	array(
				'message'	=>	'编号请填写正整数',
			),
			'numberUnique'	=>	array(
				'message'	=>	'编号已存在',
			),
		),
		'mobile'	=>	array(
			/*'require'	=>	array(
				'message'	=>	'手机号不能为空',
			),
			'mobile'	=>	array(
				'message'	=>	'请填写正确手机号',
			),
			'mobileUnique'	=>	array(
				'message'	=>	'手机号已存在',
			),*/
		),
		'desc'	=>	array(
			'require'	=>	array(
				'message'	=>	'不能为空'
			),
		),
	);

	public function getCountByAid($aid){
		$sql = 'select count(*) from %t where aid = %s';
		return $this->_db->getOne($sql, array($this->_table, $aid));
	}

	public function getByAid($aid, $order = '', $page = 0, $num = 10){
		$order = $order ? $order : 'createtime';
		$sql = 'select * from %t where aid = %s order by '.$order.' desc';
		if($page > 0){
			$start = ($page - 1) * $num;
			$sql .= ' limit '.$start.','.$num;
		}
		return $this->_db->getAll($sql, array($this->_table, $aid));
	}


	public function mobileUnique($v, $other_data){
		if(isset($other_data['pid'])){
			$noteq = ' and pid <> %s';
			$sql = 'select * from %t where aid = %s and mobile = %s and pid <> %s';
			$ret = $this->_db->getAll($sql, array($this->_table, $other_data['aid'], $v, $other_data['pid']));
		}else{
			$sql = 'select * from %t where aid = %s and mobile = %s';
			$ret = $this->_db->getAll($sql, array($this->_table, $other_data['aid'], $v));
		}
		return $ret ? false : true;
		
	}

	public function numberUnique($v, $other_data){
		if(isset($other_data['pid'])){
			$noteq = ' and pid <> %s';
			$sql = 'select * from %t where aid = %s and number = %s and pid <> %s';
			$ret = $this->_db->getAll($sql, array($this->_table, $other_data['aid'], $v, $other_data['pid']));
		}else{
			$sql = 'select * from %t where aid = %s and number = %s';
			$ret = $this->_db->getAll($sql, array($this->_table, $other_data['aid'], $v));
		}
		return $ret ? false : true;
		
	}

	public function addNum($pid){
		return $this->_db->update($this->_table, '`num`=`num`+1', '`pid`=%s', array($pid));
	}
}

?>