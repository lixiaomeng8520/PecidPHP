<?php 
if(!defined('ENV')){ exit('access deny'); }

class TestController extends PC_Controller{
	public function clearSession(){
		$dir = Conf('session_dir');
		foreach(scandir($dir) as $row){
            if($row == '.' || $row == '..'){
                continue;
            }
            $path = $dir .'/'. $row;
			@unlink($path);
        }
	}

	public function getSession(){
		$dir = Conf('session_dir');
		foreach(scandir($dir) as $row){
            if($row == '.' || $row == '..'){
                continue;
            }
            $path = $dir .'/'. $row;
			if(is_file($path)){
				$content = file_get_contents($path);
				// $content = unserialize($content);
				echo '<pre>';
				var_dump($content);
				echo '</pre>';
			}
        }
	}

	public function rewrite(){
		debug(U('Index', 'vote'));
	}

	public function openid(){
		$redirect = G('redirect');
		$redirect .= '?code=fdsf';
		header('Location: '.$redirect);
	}

	public function phpinfo(){
		phpinfo();
	}

	public function testRedis(){
		$redis = lib('PC_Redis');
		$ret = $redis->set('name', 'null');
		// debug($ret);
		debug($redis->get('name'));
	}

	public function testReplace(){
		$dir = DATA_PATH.'/tmp';
		if(!is_dir($dir)){
			mkdir($dir);
		}
		

		$config = array(
			'pref'	=>	'pre_',
			'host'	=>	'139.196.80.127',
			'port'	=>	'3306',
			'user'	=>	'root',
			'pass'	=>	'YanYumaster&881',
			'dbnm'	=>	'dahe32hb',
			'char'	=>	'utf8',
		);
		$db = lib('PC_Mysql', $config);

		$sql = 'select tid, subject, message from %t where first = 1 and  tid in (select tid from pre_forum_thread where dateline > 1420041600 and (subject like %s or subject like %s))';

		$list = $db->getAll($sql, array('forum_post', '%投诉%', '%举报%'));
		// debug($db->getLastSql());

		foreach($list as $k => $v){
			$bbcodes = 'b|i|u|p|color|size|font|align|list|indent|float';
			$bbcodesclear = 'email|code|free|table|tr|td|img|swf|flash|attach|media|audio|groupid|payto';
			$message = strip_tags(preg_replace(array(
					"/\[quote](.*?)\[\/quote]/si",
					"/\[url=?.*?\](.+?)\[\/url\]/si",
					"/\[($bbcodesclear)=?.*?\].+?\[\/\\1\]/si",
					"/\[($bbcodes)=?.*?\]/i",
					"/\[\/($bbcodes)\]/i",
				), array(
					'',
					'\\1',
					'',
					'',
					'',
				), $v['message']));
			$str = '标题：'.$v['subject']."\r\n";
			$str .= '内容：'.$message;

			file_put_contents($dir.'/'.$k.'.txt', $str);
		}
	}

	public function testUpload(){
		if(IS_POST){
			$upload = lib('PC_Upload');
			$ret = $upload->upload();	
			debug($upload->get_error());
			debug($ret);
		}else{
			$upload = lib('PC_Upload');
			debug($upload->getFileUrl('26ddea9639142886d99a790c5ab807d5.jpg'));
		}
		
	}

	// http://p.lxm.cn/index.php?_c=Test&_a=testModel
	public function testModel(){
		
		$m_user = M('User');

		debug($m_user);
	}

	public function testPagination(){
		$pagination = lib('Pagination');
		$pagination->show(11, 101);
	}

	public function testMysql(){
		$db = lib('Db');
		$sql = $db->_parse_sql('select * from table where id = ? and uid in ?', array(array(10, 11)));
		debug($sql);
	}
}

?>