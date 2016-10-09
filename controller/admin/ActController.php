<?php 
if(!defined('ENV')){ exit('access deny'); }

class ActController extends BaseController{

	public function __construct(){
		parent::__construct();
	}

	public function actList(){
		if(IS_AJAX){
			$dd_status = get_data_dic('act_status', 'status');
			$m_act = M('Act');
			$m_player = M('Player');
			$list = $m_act->getAll();
			$data = array();
			foreach($list as $k => $v){
				$item = array();
				$item['title'] = $v['title'];
				$item['status'] = $dd_status[$v['status']]['str'];
				$item['vote_start'] = date('Y-m-d H:i:s', $v['vote_start']);
				$item['vote_end'] = date('Y-m-d H:i:s', $v['vote_end']);
				$item['vote_interval'] = $v['vote_interval'];
				$item['player_num'] = $m_player->getCountByAid($v['aid']);
				$item['op'] = '&nbsp;&nbsp;&nbsp;&nbsp;
								<a href="javascript:void(0)" url="'.U('Act', 'actEdit', array('aid'=>$v['aid'])).'" data-toggle="tooltip" data-placement="top" title="编辑" class="modal_form"><i class="fa fa-pencil"></i></a>
								&nbsp;&nbsp;&nbsp;&nbsp;
								<a href="'.U('Act', 'playerList', array('aid'=>$v['aid'])).'" data-toggle="tooltip" data-placement="top" title="选手"><i class="fa fa-users"></i></a>
								&nbsp;&nbsp;&nbsp;&nbsp;
								<a href="'.U('Index', 'act', array('aid'=>$v['aid']), 'index').'" data-toggle="tooltip" data-placement="top" title="投票列表页" target="_blank"><i class="fa fa-link"></i></a>';
				$data[] = $item;
			}
			// echo json_encode($ret); 
			$this->_message(1, '成功', '', $data);
		}else{
			$this->_display('admin/act/actList');
		}
	}

	public function actAdd(){
		$m_act = M('Act');
		$aid = G('aid');
		if(IS_POST){
			$data = $_POST;
			$data['vote_start'] = strtotime($data['vote_start']);
			$data['vote_end'] = strtotime($data['vote_end']);
			$data['createtime'] = time();
			$error = $m_act->validForm($data);
			if($error){
				$this->_message(2, $error);
			}else{
				$m_act->insert($data);
				$this->_message(1, '成功', U('Act', 'actList'));
			}
		}else{
			$now = format_date(time());
			$data = array(
				'title'	=>	'新增活动',
				'info'	=>	array(
					'title'			=>	'',
					'share_summary'	=>	'',
					'share_pic'	=>	'',
					'status'		=>	'1',
					'vote_start'	=>	$now,
					'vote_end'		=>	$now,
					'vote_interval'	=>	'1',
					'banner'		=>	'',
					'desc'			=>	'',
				),
				'status_arr'	=>	get_data_dic('act_status'),
				'form_action'	=>	U('Act', 'actAdd'),
			);
			$this->_display('admin/act/actForm', $data);
		}
	}

	public function actEdit(){
		$m_act = M('Act');
		$aid = G('aid');
		$info = $m_act->get($aid);
		if(!$info) exit('活动不存在');
		if(IS_POST){
			$data = $_POST;
			$data['vote_start'] = strtotime($data['vote_start']);
			$data['vote_end'] = strtotime($data['vote_end']);
			$error = $m_act->validForm($data, array('status'=>$info['status']));
			if($error){
				$this->_message(2, $error);
			}else{
				$m_act->update($aid, $data);
				$this->_message(1, '成功', U('Act', 'actList'));
			}
		}else{
			$info['vote_start'] = format_date($info['vote_start']);
			$info['vote_end'] = format_date($info['vote_end']);

			$data = array(
				'title'	=>	'编辑活动-'.$info['title'],
				'info'	=>	$info,
				'status_arr'	=>	get_data_dic('act_status'),
				'form_action'	=>	U('Act', 'actEdit', array('aid'=>$aid)),
			);
			$this->_display('admin/act/actForm', $data);
		}
	}

	public function playerList(){
		$aid = G('aid');
		if(IS_AJAX){
			$dd_player_status = get_data_dic('player_status', 'status');
			$m_player = M('Player');
			$list = $m_player->getByAid($aid);
			$data = array();
			foreach($list as $k => $v){
				$item = $v;
				$item['status'] = $dd_player_status[$v['status']]['str'];
				$item['op'] = '&nbsp;&nbsp;&nbsp;&nbsp;
								<a class="modal_form" href="javascript:void(0)" url="'.U('Act', 'playerEdit', array('pid'=>$v['pid'])).'" data-toggle="tooltip" data-placement="top" title="编辑"><i class="fa fa-pencil"></i></a>';
				$data[] = $item;
			}
			$ret = array(
				'data'	=>	$data,
			);
			echo json_encode($ret); 
		}else{
			$this->_display('admin/act/playerList', array('aid'=>$aid));
		}	
	}

	public function playerAdd(){
		$aid = G('aid');
		if(IS_POST){
			$m_player = M('Player');
			$data = $_POST;
			$data['aid'] = $aid;
			$data['num'] = 0;
			$data['status'] = 1;
			$data['createtime'] = time();
			$data['gallery'] = isset($data['gallery']) && $data['gallery'] ? implode(',', $data['gallery']) : '';
			
			$error = $m_player->validForm($data, array('aid'=>$aid));

			if($error){
				$this->_message(0, $error);
			}else{
				$m_player->insert($data);
				$this->_message(1, '成功', U('Act', 'playerList', array('aid'=>$aid)));
			}
		}else{
			$data = array(
				'info'	=>	array(
					'name'		=>	'',
					'number'	=>	'',
					'mobile'	=>	'',
					'status'	=>	1,
					'cover'		=>	'',
					'gallery'	=>	array(),
					'desc'		=>	'',
				),
				'title'	=>	'新增选手',
				'aid'	=>	$aid,
				'status_arr'	=>	get_data_dic('player_status'),
				'form_action'	=>	U('Act', 'playerAdd', array('aid'=>$aid)),
			);
			
			$this->_display('admin/act/playerForm', $data);
		}
	}

	public function playerEdit(){
		$pid = G('pid');
		$m_player = M('Player');
		$info = $m_player->get($pid);
		if(!$info) exit('选手不存在');
		if(IS_POST){
			$data = $_POST;
			$data['gallery'] = isset($data['gallery']) && $data['gallery'] ? implode(',', $data['gallery']) : '';
			$error = $m_player->validForm($data, array('aid'=>$info['aid'], 'pid'=>$info['pid']));
			if($error){
				$this->_message(0, $error);
			}else{
				$m_player->update($pid, $data);
				// debug($m_player->getAllSql());
				$this->_message(1, '成功', U('Act', 'playerList', array('aid'=>$info['aid'])));
			}
		}else{
			$info['gallery'] = $info['gallery'] ? explode(',', $info['gallery']) : array();
			$data = array(
				'info'			=>	$info,
				'title'			=>	'编辑选手-'.$info['name'],
				'aid'			=>	$info['aid'],
				'status_arr'	=>	get_data_dic('player_status'),
				'form_action'	=>	U('Act', 'playerEdit', array('pid'=>$pid)),
			);
			
			$this->_display('admin/act/playerForm', $data);
		}
	}
}
?>