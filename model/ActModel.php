<?php
if(!defined('ENV')){ exit('access deny'); }

class ActModel extends Model{
	protected $_pk = 'aid';
	protected $_table = 'act';

	protected $_fields = array('aid', 'title', 'share_summary', 'share_pic', 'status', 'vote_start', 'vote_end', 'vote_interval', 'desc', 'banner', 'createtime');

	protected $_validator = array(
		'title'	=>	array(
			'require'	=>	array(
				'message'	=>	'标题不能为空',
			),
		),
		// 'status'	=> array(
		// 	'statusRange'	=>	array(
		// 		'message'	=>	'状态值错误',
		// 	),
		// 	'statusFinishChange'	=>	array(
		// 		'message'	=>	'结束状态不能改变',
		// 	),
		// ),
		'vote_start'	=>	array(
			'require'	=>	array(
				'message'	=>	'投票开始时间不能为空',
			),
		),
		'vote_end'	=>	array(
			'require'	=>	array(
				'message'	=>	'投票结束时间不能为空',
			),
		),
		'vote_interval'	=>	array(
			'positive_integer'	=>	array(
				'message'	=>	'投票间隔时间应该为正整数',
			),
		),
		'banner'	=>	array(
			'require'	=>	array(
				'message'	=>	'banner不能为空',
			),
		),
		'desc'	=>	array(
			'require'	=>	array(
				'message'	=>	'活动详情不能为空',
			),
		),
	);

	public function getAll($order = 'createtime', $page = 0, $num = 10){
		$sql = 'select * from %t order by '.$order.' desc';
		if($page > 0){
			$start = ($page - 1) * $num;
			$sql .= ' limit '.$start.','.$num;
		}
		return $this->_db->getAll($sql, array($this->_table));
	}

	public function statusRange($status, $other_data){
		return in_array($status, array('1', '2', '3'), true) ? true : false;
	}

	public function statusFinishChange($status, $other_data){
		return ($status != 3 && $other_data['status'] == 3) ? false : true;
	}
}

?>