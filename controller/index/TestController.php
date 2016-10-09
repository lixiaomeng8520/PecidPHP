<?php 
if(!defined('ENV')){ exit('access deny'); }

class TestController extends Controller{
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
}

?>