<?php 
if(!defined('ENV')){ exit('access deny'); }

class IndexController extends BaseController{
	private $_user = array();
	private $_weixin = null;
	public function __construct(){
		parent::__construct();
        define('PUBLIC_INDEX_URL', PUBLIC_URL.'/index');
		if(isset($_SESSION['index']['user']) && $_SESSION['index']['user']){
			$this->_user = $_SESSION['index']['user'];
		}
		// $this->_user = array('uid'=>1123292, 'openid'=>'abcdefg');

		import('Weixin');
		$conf_weixin = Conf('weixin');
		$this->_weixin = Weixin::getInstance($conf_weixin['appid'], $conf_weixin['appsecret'], ROOT_PATH.'/data/weixin');

		if(!$this->_user || !$this->_user['uid'] || !$this->_user['openid']){
			unset($_SESSION['index']['user']);
			if(IS_AJAX){
				$this->_message(0, '访问异常，请刷新当前页，重新投票');	
			}else{
				$wx_user = $this->_weixin->getUserinfo('snsapi_base');
				if(!$wx_user){
					exit('访问异常，请重新刷新当前页');
				}
				$m_user = M('User');
				$userinfo = $m_user->getByOpenid($wx_user['openid']);
				if($userinfo){
					$this->_user = $_SESSION['index']['user'] = array(
						'uid'	=>	$userinfo['uid'],
						'openid'=>	$userinfo['openid'],
					);
				}else{
					$wx_user['createtime'] = TIMESTAMP;
					$m_user->insert($wx_user);
					$uid = $m_user->getInsertId();
					$this->_user = $_SESSION['index']['user'] = array(
						'uid'	=>	$uid,
						'openid'=>	$wx_user['openid'],
					);
				}
			}
		}

		if(isset($_GET['code'])){
			$url_arr = explode('?', CURRENT_URL);
			header('Location: '.$url_arr[0]);
			exit();
		}
	}

	public function index(){exit('访问异常');
		if(IS_AJAX){
			$m_act = M('Act');
			$size = 10;
			$page = P('page') ? intval(P('page')) : 1;
			$act_list = $m_act->getAll('createtime', $page, $size);
			$html = $this->_display('index/index_act_item', array('act_list'=>$act_list), false);
			$data = array(
				'has_more'	=>	count($act_list) == $size ? 1 : 0,
				'html'	=>	$html,
			);
			$this->_message(1, '成功', '', $data);

		}else{
			$data = array(
				'sign_package'	=>	$this->_weixin->getSignPackage(),
			);
			$this->_display('index/index', $data);
		}
		
	}

	public function act(){
		$aid = G('aid');
		$m_act = M('Act');
		$act_info = is_numeric($aid) ? $m_act->get($aid) : null;
		if(!$act_info){
			exit('投票不存在');
		}

		if(IS_AJAX){
			$size = 10;
			$page = P('page') ? intval(P('page')) : 1;
			$m_player = M('Player');
			$player_list = $m_player->getByAid($aid, 'num', $page, $size);
			$html = $this->_display('index/act_player_item', array('player_list'=>$player_list), false);
			$data = array(
				'has_more'	=>	count($player_list) == $size ? 1 : 0,
				'html'	=> $html,
			);
			$this->_message(1, '获取成功', '', $data);
		}else{
			$data = array(
				'act_info'		=>	$act_info,	
				'sign_package'	=>	$this->_weixin->getSignPackage(),
			);
			$this->_display('index/act', $data);
		}

			
	}

	public function vote(){
		$pid = P('pid');

		$m_player = M('Player');
		$player_info = is_numeric($pid) ? $m_player->get($pid) : null;
		if(!$player_info){
			$this->_message(2, '选手不存在');
		}elseif($player_info['status'] == 2){
		 	$this->_message(3, '该选手已被冻结');
		}

		$m_act = M('Act');
		$act_info = $m_act->get($player_info['aid']);
		if(TIMESTAMP < $act_info['vote_start']){
			$this->_message(4, '投票还未开始');
		}elseif(TIMESTAMP > $act_info['vote_end']){
			$this->_message(5, '投票已结束');
		}elseif($act_info['status'] == 2){
			$this->_message(6, '投票已暂停');
		}
		$vote_interval = intval($act_info['vote_interval']);

		$m_vote = M('Vote');
		$last_vote = $m_vote->getLastVote($this->_user['uid'], $pid);
		if($last_vote){
			$time = $last_vote['time'] + $vote_interval * 3600;//debug($last_vote);
			if($time > TIMESTAMP){
				$this->_message(7, '投票时间间隔为'.$vote_interval.'小时');
			}
		}

		$vote_data = array(
			'uid'		=>	$this->_user['uid'],
			'openid'	=>	$this->_user['openid'],
			'pid'		=>	$player_info['pid'],
			'aid'		=>	$player_info['aid'],
			'ip'		=>	CLIENT_IP,
			'time'		=>	TIMESTAMP,
		);

		$m_vote->insert($vote_data);
		$num = $m_vote->getCountByPid($pid);
		$m_player->update($pid, array('num'=>$num));

		$this->_message(1, "恭喜您投票成功！分享出去让更多的好友一起来投票！", '', array('num'=>$num));
	}

	public function player(){
		$pid = G('pid');
		$m_player = M('Player');
		$player_info = $m_player->get($pid);
		if(!$player_info) exit('选手不存在');
		$player_info['gallery'] = $player_info['gallery'] ? explode(',', $player_info['gallery']) : array();

		$m_act = M('Act');
		$act_info = $m_act->get($player_info['aid']);

		$data = array(
			'player_info'	=>	$player_info, 
			'act_info'		=>	$act_info,
			'sign_package'	=>	$this->_weixin->getSignPackage()
		);
		$this->_display('index/player', $data);
	}
}
?>