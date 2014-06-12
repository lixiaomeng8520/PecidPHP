<?php
class Index extends Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		//echo 'Hello PecidPHP~~';
		//$this->assign('hello', 'Hello PecidPHP~~!!');
		$this->assign('data', array('username'=>'李小蒙', 'age'=>'25'));
		$this->assign('now', time());
		$this->display('index.html');
	}

	function test()
	{
		//$str = 'abcdefg';

		//preg_match('/[^a|b]/', $str, $match);

		//preg_replace("/([aeg])/e", '$this->echostr(\'\1\');', $str);

		//var_dump($match);
		//$a = 0;
		//echo "\$a";

		//var_dump($_GET);

		//echo stripslashes('dd\d');

		//var_dump(@stat('e:/a.txt'));
		//var_dump(C());

		/*$a = 'aaa';
		$b = explode('|', $a);
		var_dump($b);*/
		//var_dump("\n", '\n');

		/*$model = Factory::getModel();

		$ret = $model->select('username, count(username) as count')->group_by('username')->having('count > 2')->get('user');
		//$ret = $model->insert('user', array('username' => "\n", 'password' => md5('111111')));
		var_dump($ret, $model->last_query());*/

		//dump($_GET['a']);
		//file_put_contents(ROOT_PATH.'/temps/'.'a.txt', $_GET['a']);
		$format = 'Y-m-d H:i:s';
		echo date($format, time());
	}



	function echostr($str)
	{
		echo $str;
	}
}
?>