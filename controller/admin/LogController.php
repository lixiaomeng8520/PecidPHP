<?php 
if(!defined('ENV')){ exit('access deny'); }

class LogController extends BaseController{
	protected $_no_login_action = array();

	public function __construct(){
		parent::__construct();
	}

	public function index(){
		if(IS_AJAX){
			$dd_log_type = get_data_dic('log_type', 'type');
			$m_log = M('Log');
			$list = $m_log->getAll();
			$data = array();
			foreach($list as $k => $v){
				$item = $v;
				$item['type'] = $dd_log_type[$v['type']]['str'];
				$item['data'] = '<a class="modal_log" href="javascript:void(0)" log_data="<pre>'.rawurlencode(var_export(unserialize($v['data']), 1)).'</pre>"><i class="fa fa-search"></i></a>';
				$item['time'] = format_date($v['time']);
				$data[] = $item;
			}
			$ret = array(
				'data'	=>	$data,
			);
			echo json_encode($ret); 
		}else{
			$this->_display('admin/log/index');
		}
	}
}
?>