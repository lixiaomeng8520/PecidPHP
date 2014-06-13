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

	//file是VIEW_PATH目录下的路径，最前面不带/
	function display($file)
	{
		
		/*if(!file_exists($file))
		{
			trigger_error('文件不存在:'.$file, E_USER_ERROR);
			return;
		}*/

		$out = $this->_fetch($file);//var_dump(htmlspecialchars($out));die;
		eval('?>' . trim($out));
	}

	private function _fetch($file)
	{
		$file = $this->_view_dir.'/'.$file;//生成物理路径
		$out = '';
		if(file_exists($file))
		{
			$out = $this->_compile($file);	
		}
		return $out;
	}

	private function _compile($file)
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
				@mkdir($this->_compile_dir, 0777, true);
			}

			//写文件时锁定
			@file_put_contents($compile, $source, LOCK_EX);
		}
		else
		{
			//直接获取
			$source = file_get_contents($compile);
		}

		return $source;
	}

	/*处理大括号 if */
	private function _brace($str)
	{
		if($str{0} == '$')	//变量
		{
			return '<?php echo '.$this->_get_var(substr($str, 1)).'; ?>';
		}
		elseif($str{0} == '/') //结束标签
		{
			switch(substr($str, 1)) 
			{
				case 'if':
					return '<?php endif;?>'; break;

				case 'foreach':
					return '<?php endforeach;?>'; break;
				
				default:
					# code...
					break;
			}
		}
		else
		{
			$str_arr = preg_split('/\s/', $str);
			$tag = array_shift($str_arr);
			$op = join(' ', $str_arr);
			switch($tag) 
			{
				case 'if':
					return $this->_get_if($op); break;

				case 'else':
					return '<?php else:?>'; break;

				case 'elseif':
					return '<?php elseif:?>'; break;

				case 'foreach':
					return $this->_get_foreach($op); break;

				case 'include':
					return $this->_fetch($op); break;
				
				default: break;
			}
		}
	}

	private function _get_var($var)
	{
		$arr = explode('|', $var);
		$var = array_shift($arr);
		$var = $this->_make_var($var);

		foreach($arr as $k => $v)
		{
			$f_arr = explode(':', $v);
			switch($f_arr[0])
			{
				case 'escape':
					if($_arr[1] == 'url')
					{
						$var = 'urlencode('.$var.')';
					}
					else
					{
						$var = 'htmlspecialchars('.$var.')';
					}
					break;

				case 'nl2br':
					$var = 'nl2br('.$var.')';
					break;

				case 'truncate':
					$var = 'msubstr('.$var.', 0, '.$f_arr[1].', \'utf-8\', true)';
					break;

				case 'strip_tags':
					$var = 'strip_tags('.$var.')';
					break;

				case 'date_format':
					unset($f_arr[0]);
					$format = implode(':', $f_arr);
					$var = 'date(\''.$format.'\', '.$var.')';
					break;

				default:
					break;
			}
		}

		return $var;
	}
	
	private function _make_var($var)
	{
		if(strpos($var, '.') === false)
		{
			$ret = '$this->_var[\''.$var.'\']';
		}
		else
		{
			$arr = explode('.', $var);
			$first = array_shift($arr);
			if($first == 'Pecid')
			{
				$second = array_shift($arr);
				switch($second)
				{
					case 'get':
						$ret = '$_GET'; break;

					case 'post':
						$ret = '$_POST'; break;

					case 'request':
						$ret = '$_REQUEST'; break;

					case 'session':
						$ret = '$_SESSION'; break;

					case 'cookie':
						$ret = '$_COOKIE'; break;

					case 'env':
						$ret = '$_ENV'; break;

					case 'server':
						$ret = '$_SERVER'; break;
				}
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

	private function _get_if($str, $elseif = false)
	{
		// == != > < >= <= ! % 都有的  ===   || &&      !==
		// 共计11个
		//preg_match_all('/\-?\d+[\.\d]+|\'[^\'|\s]*\'|"[^"|\s]*"|[\$\w\.]+|!==|===|==|!=|<>|<<|>>|<=|>=|&&|\|\||\(|\)|,|\!|\^|=|&|<|>|~|\||\%|\+|\-|\/|\*|\@|\S/', $tag_args, $match);
		$item_arr = preg_split('/\s/', $str);
		foreach($item_arr  as $k => $v)
		{
			$item = &$item_arr[$k];
			switch($item)
			{
				case 'eq':
					$item = '=='; break;

				case 'ne':
				case 'neq':
					$item = '!='; break;

				case 'gt':
					$item = '>'; break;

				case 'lt':
					$item = '<'; break;

				case 'ge':
				case 'gte':
					$item = '>='; break;

				case 'le':
				case 'lte':
					$item = '<='; break;

				case 'not':
					$item = '!'; break;

				case 'mod':
					$item = '%'; break;

				default:
					if($item{0} == '$')
					{
						$item = $this->_get_var(substr($item, 1));
					}
					break;
			}
		}

		if($elseif == true)
		{
			return '<?php elseif('.join(' ', $item_arr).'):?>';
		}
		else
		{
			return '<?php if('.join(' ', $item_arr).'):?>';
		}
	}

	private function _get_foreach($str)
	{
		//由于所有变量都被编译为 $this->_var['v'];所以key和value应该都为$this->_var['k']和$this->_var['v']

		$arr = explode(' as ', $str);
		
		$list = trim($arr[0]);
		$list = $this->_get_var(substr($list, 1));
		if(strpos($arr[1], '=>') === false)	//只有value
		{
			$val = trim($arr[1]);
			//$this->_foreach_key[] = '$this->_var[\''.$val.'\'] = \''.$val.'\'';
			$str = '<?php foreach('.$list.' as $this->_var[\''.substr($val, 1).'\']):?>';
		}
		else //key 和 value
		{
			$kv_arr = explode('=>', $arr[1]);
			$key = trim($kv_arr[0]);
			$val = trim($kv_arr[1]);
			$str = '<?php foreach('.$list.' as $this->_var[\''.substr($key, 1).'\'] => $this->_var[\''.substr($val, 1).'\']):?>';
		}

		return $str;
	}
}


?>