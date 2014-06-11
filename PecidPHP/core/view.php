<?php
/**
 *	模版类
 *	@author lxm
 */
class View
{
	var $_view_dir		= '';
	var $_compile_dir	= '';
	var $_var 			= array();
	var $_error_level	= 0;
	var $_now			= null;

	function __construct()
	{
		$this->_error_level		= error_reporting();
		$this->_now 			= time();
		$this->_view_dir		= VIEW_PATH;
		$this->_compile_dir 	= TEMP_PATH.'/compile';
	}

	function assign($k, $v = '')
	{
		if(is_array($k))
		{
			foreach($k as $m => $n)
			{
				if(is_string($m) && $m)
				{
					$this->_var[$m] = $n;
				}
			}
		}
		elseif(is_string($k) && $k)
		{
			$this->_var[$k] = $v;
		}
	}

	function display($file)
	{
		$file = $this->_view_dir.'/'.$file;
		/*if(!file_exists($file))
		{
			trigger_error('文件不存在:'.$file, E_USER_ERROR);
			return;
		}*/

		$out = $this->_fetch($file);
		eval('?>' . trim($out));
	}

	function _fetch($file)
	{
		$out = $this->_compile($file);

		return $out;
	}

	function _compile($file)
	{
		$compile = $this->_compile_dir.'/'.basename($file).'.php';
		$file_stat = @stat($file);
		$compile_stat = @stat($compile);
		
		if($compile_stat === false || $compile_stat['mtime'] < $file_stat['mtime'])
		{
			//进行编译
			$content = file_get_contents($file);//var_dump(htmlspecialchars($content));die;
			$source = preg_replace('/{([^\}\{\n]*)}/e', '$this->_brace(\'\1\');', $content);

			//var_dump(htmlspecialchars($source));die;
			if(!file_exists($this->_compile_dir))
			{
				//如果编译文件所在目录不存在，则创建
				mkdir($this->_compile_dir, 0777, true);
			}

			//写文件时锁定
			file_put_contents($compile, $source, LOCK_EX);
		}
		else
		{
			//直接获取
			$source = file_get_contents($compile);
		}

		return $source;
	}

	/*处理大括号*/
	function _brace($str)
	{
		if($str{0} == '$')	//变量
		{
			return '<?php echo '.$this->_get_var(substr($str, 1)).'; ?>';
		}
	}

	function _get_var($val)
	{
		$arr = explode('|', $val);
		$val = array_shift($arr);
		$val = $this->_make_var($val);

		foreach($arr as $k => $v)
		{
			$f_arr = explode(':', $v);
			switch($f_arr[0])
			{
				case 'escape':
					
			}
		}

		return $ret;
	}
	
	function _make_var($val)
	{
		if(strpos($val, '.') === false)
		{
			$ret = '$this->_var[\''.$val.'\']';
		}
		else
		{
			$arr = explode('.', $val);
			$first = array_shift($arr);
			if($first == 'smarty')
			{
				$ret = '';
			}
			else
			{
				$ret = '$this->_var[\''.$first.'\']';
			}
			foreach($arr as $k => $v)
			{
				$ret .= '[\''.$v.'\']';
			}
		}
//var_dump($this->_var,$ret);die;
		return $ret;
	}
}


?>