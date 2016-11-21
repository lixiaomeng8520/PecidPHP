<?php 
class PC_Db_Driver{
	/**
	 * 数据库标识
	 */
	public $_link;

	/**
	 * 数据库配置
	 */
	public $_config = array(
		'driver'		=>	'Mysqli', // 驱动
		'hostname'		=>	'',	// 主机
		'port'			=>	'',	// 端口
		'username'		=>	'',	// 用户名
		'password'		=>	'',	// 密码
		'dbname'		=>	'',	// 数据库
		'charset'		=>	'',	// 编码
		'prefix'		=>	'',	// 表前缀
	);

	public function __construct(){
		parent::__construct();
		$this->_init();
	}

	/**
	 * 连接数据库
	 * @return mixed 成功返回资源对象，失败返回false
	 */
	public function connect(){}

	/**
	 * 重连数据库
	 */
	public function reconnect(){}

	/**
	 * 选择数据库
	 */
	public function select_db(){}

	/**
	 * 返回影响行数
	 * @return int 影响的行数
	 */
	public function affected_rows(){
		return 0;
	}

	/**
	 * 返回自增ID
	 * @return int 自增的ID
	 */
	public function insert_id(){
		return 0;
	}

	/**
	 * 返回最后一条错误
	 * @return string 错误信息
	 */
	public function get_error(){
		return '';
	}

	/**
	 * 执行一个sql
	 * 如果是读操作，返回一个资源对象
	 * 如果是写操作，返回true
	 * 如果失败，返回false
	 * @param string sql字符串
	 * @param array 绑定的数据
	 * @return mixed
	**/
	public function query($sql, $bind_data = array()){
		
		$sql = $this->_parse_sql($sql, $bind_data);

		// 判断操作类型
		$is_write_sql = preg_match('/^\s*"?(SET|INSERT|UPDATE|DELETE|REPLACE|CREATE|DROP|TRUNCATE|LOAD|COPY|ALTER|RENAME|GRANT|REVOKE|LOCK|UNLOCK|REINDEX)\s/i', $sql) === 1 ? true : false;
	}
	

	/**
	 * 处理sql语句，使用绑定的数据，sql中以?作为标识
	 * @param string sql字符串
	 * @param array 绑定的数据
	**/
	public function _parse_sql($sql, $bind_data = array()){
		// 处理参数
		if(!is_string($sql) || $sql == ''){
			trigger_error('sql不能为空', E_USER_ERROR);
		}
		if(!is_array($bind_data)){
			trigger_error('bind_data应为数组', E_USER_ERROR);
		}

		// 定义替换标识
		$marker = '?';

		// 判断是否有绑定数据，没有直接返回sql
		if(empty($bind_data) || strpos($sql, $marker) === false){
			return $sql;
		}

		// 判断sql中的marker数和bind_data元素数是否匹配
		// preg_quote会在正则表达式特殊字符前增加反斜杠
		// PREG_OFFSET_CAPTURE用来记录每次匹配字符串在原字符串中的偏移
		$marker_count = preg_match_all('/'.preg_quote($marker).'/', $sql, $matches, PREG_OFFSET_CAPTURE);
		if($marker_count != count($bind_data)){
			trigger_error('占位符和绑定的数据数量不符', E_USER_ERROR);
		}

		// 开始进行替换。如果值是字符串，直接替换，如果是数组，则转化成英文逗号分割的字符串
		// 由于要用到偏移量，所以从最后开始替换
		for($i = $marker_count - 1; $i >= 0; $i--){
			$str = $this->_escape($bind_data[$i]);
			if(is_array($str)){
				$str = '('.implode(',', $str).')';
			}
			$sql = substr_replace($sql, $str, $matches[0][$i][1], strlen($marker));
		}
		return $sql;
	}

	/**
	 * 转义
	 * @param mixed 要转义的对象，如果是array，则递归
	**/
	protected function _escape($str){
		if(is_int($str) || is_float($str)){
			return $str;
		}elseif(is_string($str)){
			return $this->_escape_str($str);
		}elseif(is_array($str)){
			foreach($str as $k => $v){
				$str[$k] = $this->_escape($v);
			}
			return $str;
		}elseif(is_bool($str) || is_null($str) || is_object($str) || is_resource($str)){
			trigger_error('bool,null,object,resource类型不允许', E_USER_ERROR);
		}
	}

	/**
	 * 转义字符串特殊字符。需要被重写
	 * @param string 要转义的字符串
	 * @return string 转义过的字符串
	**/
	protected function _escape_str($str){
		return $str;
	}
}

?>