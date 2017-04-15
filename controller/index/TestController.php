<?php 
use Qiniu\Storage\UploadManager;
use Qiniu\Auth;
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
		echo $pagination->show(3, 25);
	}

	public function testMysql(){
		$db = lib_db();
		// $ret = $db->query('select * from t_news where id = ?', array(1));
		// debug($db->affectedRows());

		// $ret = $db->update('test', array('username'=>'lxm', 'mobile'=>1, 'realname'=>'lixiaomeng'), array('username'=>'3'));
		// debug($ret);

		// $ret = $db->getOne('select count(*) from test where username=?', array('lxm'));
		$data = array(
			'username'	=>	'~!@#$%^&*()\'"?',
		);
		// $db->insert('test', $data);
		// debug($db->insertId());
		$ret = $db->getRow('select * from test where uid = ?', array(1));

		debug($db->getSqls(), $ret);
	}

	// http://p.lxm.cn/index.php?_c=Test&_a=test
	public function test(){

		$this->_display('test');
	}

	public function departments(){
		$data = array(
			array('name'	=>	'技术', 'id'	=>	1),
			array('name'	=>	'财务', 'id'	=>	2),
			array('name'	=>	'综合部', 'id'	=>	3),
			array('name'	=>	'综合部', 'id'	=>	3),
		);
		exit(json_encode($data));
	}

	public function httpCode(){
		$a = mt_rand();
		echo uniqid().'<br/>'.uniqid().'<br/>'.uniqid().'<br/>'.uniqid().'<br/>'.uniqid().'<br/>'.uniqid();
	}

	public function json(){
		$str = '[{"id":"200","channelId":"8","channel":"最现场","title":"山西新闻网\u0026视觉志图片精选（2016.11）","summary":"","type":"1","pubTime":"11月30日","isRecommend":"1","seq":"0","editor":"","editorId":"0","chiefEditor":"","content":"","contentImg":[],"imgCount":"20","url":"","adUrl":"","imgUrl":"http://img.sxrb.com/21bb6081a875ec968cc15b6dd3be5462","isEnlighten":"1","imgWidth":"950","imgHeight":"606","likeCount":"1","commentCount":"","storeState":false,"likeState":false,"totalPage":"0","camImgUrl":"","authorRole":"","donation":"","cover":"","keyword":"山西新闻网,视觉志,图片精选","state":"2","shareTitle":"","shareSummary":"","shareImg":"","editors":[{"id":"12","username":"王雪萍","imgUrl":"","imgWidth":"","imgHeight":"","intro":"","storeSum":"","clickSum":"","role":"特约摄影师","type":0,"shareTitle":"","shareSummary":"","shareImg":""},{"id":"110","username":"郭建华","imgUrl":"","imgWidth":"","imgHeight":"","intro":"","storeSum":"","clickSum":"","role":"特约摄影师","type":0,"shareTitle":"","shareSummary":"","shareImg":""},{"id":"135","username":"杨德新","imgUrl":"","imgWidth":"","imgHeight":"","intro":"","storeSum":"","clickSum":"","role":"特约摄影师","type":0,"shareTitle":"","shareSummary":"","shareImg":""},{"id":"13","username":"武伟","imgUrl":"","imgWidth":"","imgHeight":"","intro":"","storeSum":"","clickSum":"","role":"特约摄影师","type":0,"shareTitle":"","shareSummary":"","shareImg":""},{"id":"20","username":"周利平","imgUrl":"","imgWidth":"","imgHeight":"","intro":"","storeSum":"","clickSum":"","role":"特约摄影师","type":0,"shareTitle":"","shareSummary":"","shareImg":""},{"id":"14","username":"袁永平","imgUrl":"","imgWidth":"","imgHeight":"","intro":"","storeSum":"","clickSum":"","role":"特约摄影师","type":0,"shareTitle":"","shareSummary":"","shareImg":""},{"id":"96","username":"齐文辉","imgUrl":"","imgWidth":"","imgHeight":"","intro":"","storeSum":"","clickSum":"","role":"特约摄影师","type":0,"shareTitle":"","shareSummary":"","shareImg":""},{"id":"101","username":"席华昌","imgUrl":"","imgWidth":"","imgHeight":"","intro":"","storeSum":"","clickSum":"","role":"特约摄影师","type":0,"shareTitle":"","shareSummary":"","shareImg":""},{"id":"86","username":"张蕴强","imgUrl":"","imgWidth":"","imgHeight":"","intro":"","storeSum":"","clickSum":"","role":"特约摄影师","type":0,"shareTitle":"","shareSummary":"","shareImg":""},{"id":"120","username":"吴继才","imgUrl":"","imgWidth":"","imgHeight":"","intro":"","storeSum":"","clickSum":"","role":"特约摄影师","type":0,"shareTitle":"","shareSummary":"","shareImg":""},{"id":"37","username":"王红兵","imgUrl":"","imgWidth":"","imgHeight":"","intro":"","storeSum":"","clickSum":"","role":"特约摄影师","type":0,"shareTitle":"","shareSummary":"","shareImg":""},{"id":"8","username":"党永立","imgUrl":"","imgWidth":"","imgHeight":"","intro":"","storeSum":"","clickSum":"","role":"特约摄影师","type":0,"shareTitle":"","shareSummary":"","shareImg":""},{"id":"29","username":"郭翔","imgUrl":"","imgWidth":"","imgHeight":"","intro":"","storeSum":"","clickSum":"","role":"特约摄影师","type":0,"shareTitle":"","shareSummary":"","shareImg":""},{"id":"89","username":"张向东","imgUrl":"","imgWidth":"","imgHeight":"","intro":"","storeSum":"","clickSum":"","role":"特约摄影师","type":0,"shareTitle":"","shareSummary":"","shareImg":""},{"id":"11","username":"倪松","imgUrl":"","imgWidth":"","imgHeight":"","intro":"","storeSum":"","clickSum":"","role":"特约摄影师","type":0,"shareTitle":"","shareSummary":"","shareImg":""},{"id":"36","username":"杨利","imgUrl":"","imgWidth":"","imgHeight":"","intro":"","storeSum":"","clickSum":"","role":"特约摄影师","type":0,"shareTitle":"","shareSummary":"","shareImg":""},{"id":"72","username":"郝赫","imgUrl":"","imgWidth":"","imgHeight":"","intro":"","storeSum":"","clickSum":"","role":"特约摄影师","type":0,"shareTitle":"","shareSummary":"","shareImg":""},{"id":"112","username":"杨兴民","imgUrl":"","imgWidth":"","imgHeight":"","intro":"","storeSum":"","clickSum":"","role":"特约摄影师","type":0,"shareTitle":"","shareSummary":"","shareImg":""},{"id":"28","username":"付全智","imgUrl":"","imgWidth":"","imgHeight":"","intro":"","storeSum":"","clickSum":"","role":"特约摄影师","type":0,"shareTitle":"","shareSummary":"","shareImg":""},{"id":"88","username":"王辉耀","imgUrl":"","imgWidth":"","imgHeight":"","intro":"","storeSum":"","clickSum":"","role":"特约摄影师","type":0,"shareTitle":"","shareSummary":"","shareImg":""},{"id":"7","username":"宇巍","imgUrl":"","imgWidth":"","imgHeight":"","intro":"","storeSum":"","clickSum":"","role":"记者","type":1,"shareTitle":"","shareSummary":"","shareImg":""},{"id":"4","username":"阴豪","imgUrl":"","imgWidth":"","imgHeight":"","intro":"","storeSum":"","clickSum":"","role":"记者","type":1,"shareTitle":"","shareSummary":"","shareImg":""},{"id":"3","username":"苏航","imgUrl":"","imgWidth":"","imgHeight":"","intro":"","storeSum":"","clickSum":"","role":"记者","type":1,"shareTitle":"","shareSummary":"","shareImg":""},{"id":"2","username":"张春颖","imgUrl":"","imgWidth":"","imgHeight":"","intro":"","storeSum":"","clickSum":"","role":"编辑","type":2,"shareTitle":"","shareSummary":"","shareImg":""}]},{"id":"226","channelId":"8","channel":"最现场","title":"200余名跑者绿色“光猪跑”零下6度穿内衣“裸奔”","summary":"12月10日，一场绿色“光猪跑”比赛在青龙古镇内举行。200百余名造型各异的跑步爱跑者零下6度，只穿内衣“裸跑”。","type":"1","pubTime":"12月12日","isRecommend":"1","seq":"0","editor":"","editorId":"0","chiefEditor":"","content":"","contentImg":[],"imgCount":"14","url":"","adUrl":"","imgUrl":"http://img.sxrb.com/cfc62c3f4c54ca9e84a92860b2199e80","isEnlighten":"1","imgWidth":"750","imgHeight":"400","likeCount":"1","commentCount":"","storeState":false,"likeState":false,"totalPage":"0","camImgUrl":"","authorRole":"","donation":"","cover":"","keyword":"山西,青龙,古镇,跑步,内衣,裸奔,爱好者,光猪跑","state":"2","shareTitle":"","shareSummary":"","shareImg":"","editors":[{"id":"31","username":"田兆云","imgUrl":"","imgWidth":"","imgHeight":"","intro":"","storeSum":"","clickSum":"","role":"特约摄影师","type":0,"shareTitle":"","shareSummary":"","shareImg":""},{"id":"25","username":"赵大鹏","imgUrl":"","imgWidth":"","imgHeight":"","intro":"","storeSum":"","clickSum":"","role":"特约摄影师","type":0,"shareTitle":"","shareSummary":"","shareImg":""},{"id":"5","username":"王琪","imgUrl":"","imgWidth":"","imgHeight":"","intro":"","storeSum":"","clickSum":"","role":"编辑","type":2,"shareTitle":"","shareSummary":"","shareImg":""}]},{"id":"146","channelId":"6","channel":"摄氏度","title":"\"伍工队\"——传承手工制缸工艺","summary":"","type":"1","pubTime":"11月08日","isRecommend":"1","seq":"0","editor":"","editorId":"0","chiefEditor":"刘昱","content":"","contentImg":[],"imgCount":"14","url":"","adUrl":"","imgUrl":"http://img.sxrb.com/aebd6a4688d5b7fce40d6da0f1a22420","isEnlighten":"1","imgWidth":"1200","imgHeight":"798","likeCount":"0","commentCount":"","storeState":false,"likeState":false,"totalPage":"0","camImgUrl":"","authorRole":"","donation":"","cover":"","keyword":"制缸　传统技艺　手工","state":"2","shareTitle":"","shareSummary":"","shareImg":"","editors":[{"id":"90","username":"南志平","imgUrl":"","imgWidth":"","imgHeight":"","intro":"","storeSum":"","clickSum":"","role":"特约摄影师","type":0,"shareTitle":"","shareSummary":"","shareImg":""},{"id":"91","username":"胡波","imgUrl":"","imgWidth":"","imgHeight":"","intro":"","storeSum":"","clickSum":"","role":"特约摄影师","type":0,"shareTitle":"","shareSummary":"","shareImg":""},{"id":"3","username":"苏航","imgUrl":"","imgWidth":"","imgHeight":"","intro":"","storeSum":"","clickSum":"","role":"编辑","type":2,"shareTitle":"","shareSummary":"","shareImg":""}]},{"id":"100","channelId":"8","channel":"最现场","title":"山西太原：商家办庆典豪车+人体彩绘画面香艳引关注","summary":"11月5日，太原丽都整形医院举行上市一周年庆典活动。仪式现场，商家通过数十辆顶级跑车和人体彩绘美女展示吸引了大量围观市民，意在“金九银十”之后，通过此种方式进行年底业务再冲刺。","type":"1","pubTime":"11月05日","isRecommend":"0","seq":"0","editor":"","editorId":"0","chiefEditor":"","content":"","contentImg":[],"imgCount":"8","url":"","adUrl":"","imgUrl":"http://img.sxrb.com/0a1f89da03734dfad8089741f76d71c7","isEnlighten":"0","imgWidth":"950","imgHeight":"606","likeCount":"3","commentCount":"","storeState":false,"likeState":false,"totalPage":"0","camImgUrl":"","authorRole":"","donation":"","cover":"","keyword":"山西,太原,商家,彩绘,豪车","state":"2","shareTitle":"","shareSummary":"","shareImg":"","editors":[{"id":"7","username":"宇巍","imgUrl":"","imgWidth":"","imgHeight":"","intro":"","storeSum":"","clickSum":"","role":"记者","type":1,"shareTitle":"","shareSummary":"","shareImg":""}]}]';
		// exit(json_decode($str));

		// 
		// debug(json_decode($str, 1));
		exit($str);
	}

	public function vote_r(){
		// debug(floor(11 / 2));
		$db = lib_db();
		$arr = array('aqscjg','fp','fzgg','ga','gtzy','hjbh','jtys','jy','spypjg','wsjs','zfcxjs');
		$ret = array();
		foreach($arr as $v){
			$list_sql = 'select area, '.$v.', count(*) as cnt from t_vote_r group by area, '.$v;
			$sum_sql = 'select area, sum(a.cnt) as total from (select area, '.$v.', count(*) as cnt from t_vote_r group by area, '.$v.') a group by area';

			$list = $db->getRows($list_sql);
			$sum = $db->getRows($sum_sql);
			
			$list_tmp = array();
			foreach ($list as $value) {
				$list_tmp[$value['area']][] = $value;
			}
			

			foreach($sum as $n){
				

				foreach($list_tmp as $x => $y){
					$num = $n['total'] * 11;
					$a = mt_rand(0, floor($num / 2));
					$b = mt_rand(0, floor(($num - $a) / 2));
					$c = mt_rand(0, floor(($num - $a - $b) / 2));
					$d = mt_rand(0, floor(($num - $a - $b - $c) / 2));
					$e = $num - $a - $b - $c - $d;
					
					// $add_num = 5 - count($y);
					// for($i = 0; $i < $add_num; $i++){
					// 	$list_tmp[$x][] = $y[] = array(
					// 		'area'	=>	'',
					// 		'area'	=>	$v,
					// 		'cnt'	=>	0,
					// 	);
					// }

					if($x == $n['area']){

						foreach($y as $p => $o){


							if($p == 0){
								$list_tmp[$x][$p]['cnt'] = $o['cnt'] * 60 + $a;
							}elseif($p == 1){
								$list_tmp[$x][$p]['cnt'] = $o['cnt'] * 60 + $b;
							}elseif($p == 2){
								$list_tmp[$x][$p]['cnt'] = $o['cnt'] * 60 + $c;
							}elseif($p == 3){
								$list_tmp[$x][$p]['cnt'] = $o['cnt'] * 60 + $d;
							}elseif($p == 4){
								$list_tmp[$x][$p]['cnt'] = $o['cnt'] * 60 + $e;
							}
						}
					}
				}
			}

			$ret[$v] = $list_tmp;
		}

		debug($ret);
	}

	public function qn(){
		require_once ROOT_PATH.'/vendor/autoload.php';
		$ak = 'pB9jRZZyOwA1yYc9nAVp0S0SdgMtiBJglLE9wXuy';
		$secretKey = 'axWD61IzrwHdQ7iv3Y0meNrWd22QEyhXhE0Y2N_4';
		$hub = 'dahevideo';

		$videoKey = 'qiniu.mp4';
		$videoFilePath = "/Users/jemy/Documents/qiniu.mp4";
		$jediAuth = new Jedi\JediAuth($ak, $secretKey);
		$jediManager = new Jedi\JediManager($jediAuth);
		$upTokenResult = $jediManager->getUpToken($hub);
		$upToken = $upTokenResult['uptoken'];
		debug($upTokenResult);

		// 2xSltFpQacAV8VzwPLdXLmEqvZwzhXguTkPlAItG:5t47Y8Z1OEdu7zB_-hyPYw9vlWU=:eyJzY29wZSI6ImRhaGV2aWRlby1zcmMiLCJkZWFkbGluZSI6MTQ4Mjg1MDkyNiwiZnNpemVMaW1pdCI6MTA3Mzc0MTgyNDAwLCJ1cGhvc3RzIjpbImh0dHA6Ly91cC5xaW5pdS5jb20iLCJodHRwOi8vdXBsb2FkLnFpbml1LmNvbSIsIi1IIHVwLnFpbml1LmNvbSBodHRwOi8vMTgzLjEzNi4xMzkuMTYiXX0=
		// 2xSltFpQacAV8VzwPLdXLmEqvZwzhXguTkPlAItG:cnMAUh_Z1EP-0RbeTGg8aS8ue0M=:eyJzY29wZSI6ImRhaGV2aWRlby1zcmMiLCJkZWFkbGluZSI6MTQ4Mjg1MTIyMiwiZnNpemVMaW1pdCI6MTA3Mzc0MTgyNDAwLCJ1cGhvc3RzIjpbImh0dHA6Ly91cC5xaW5pdS5jb20iLCJodHRwOi8vdXBsb2FkLnFpbml1LmNvbSIsIi1IIHVwLnFpbml1LmNvbSBodHRwOi8vMTgzLjEzNi4xMzkuMTYiXX0=
	}

	public function jsonp(){
		debug($_POST, $_COOKIE, $_SERVER);
		setcookie('test', 'testtttt');
	}


	public function testFloat(){
		var_dump(intval(58.0));
	}


	public function op1(){
		$data = array('msg' => '登录');
		exit(json_encode($data));


		$data = array('msg' => 'op1');
		exit(json_encode($data));
	}

	public function op2(){
		// $data = array('msg' => 'op1');
		// exit(json_encode($data));

		$data = array('msg' => 'op2');
		exit(json_encode($data));
	}

	public function op3(){
		// $data = array('msg' => 'op1');
		// exit(json_encode($data));

		$data = array('msg' => 'op3');
		exit(json_encode($data));
	}

	public function testFiles(){
		foreach($_FILES['file'] as $index => $vals){
            foreach ($vals as $i => $val) {
                $file_map[$i]['file'][$index] = $val;
            }
        }
        $_FILES = '111';
		debug($file_map);
	}

}

?>