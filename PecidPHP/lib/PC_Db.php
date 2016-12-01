<?php 
abstract class PC_Db extends PC_Lib{
	/**
	 * 数据库标识
	 */
	protected $_link = null;

	/**
	 * sql数组
	 */
	protected $_sqls = array();

	/**
	 * 站位符
	 */
	protected $_marker = '?';

	/**
	 * 字段保护符
	 */
	protected $_escape_column_char = '"';

	/**
	 * 数据库配置
	 */
	protected $_config = array(
		'driver'		=>	'Mysqli', // 驱动
		'host'			=>	'',	// 主机
		'port'			=>	'',	// 端口
		'username'		=>	'',	// 用户名
		'password'		=>	'',	// 密码
		'dbname'		=>	'',	// 数据库
		'charset'		=>	'',	// 编码
		'prefix'		=>	'',	// 表前缀
	);

	// ----------------------------构造，数据库操作，辅助-----------------------------------------

	public function __construct($config){
		parent::__construct($config);
		$this->connect();
	}

	/**
	 * 连接数据库
	 * @return mixed 成功返回资源对象，失败返回false
	 */
	public function connect(){
		return $this->_connect();
	}
	abstract protected function _connect();

	/**
	 * 重连数据库
	 * @return bool true成功，false失败
	 */
	public function reConnect(){
		return $this->_reConnect();
	}
	abstract protected function _reConnect();

	/**
	 * 选择数据库
	 * @param string 数据库名
	 * @return bool true成功，false失败
	 */
	public function selectDb($dbname){
		return $this->_selectDb();
	}
	abstract protected function _selectDb($dbname);

	/**
	 * 返回影响行数
	 * @return int 影响的行数
	 */
	public function affectedRows(){
		return $this->_affectedRows();
	}
	abstract protected function _affectedRows();

	/**
	 * 返回自增ID
	 * @return int 自增的ID
	 */
	public function insertId(){
		return $this->_insertId();
	}
	abstract protected function _insertId();

	/**
	 * 返回最后一条错误
	 * @return string 错误信息
	 */
	public function getError(){
		return $this->_getError();
	}
	abstract protected function _getError();


	// ----------------------------执行SQL-----------------------------------------

	/**
	 * 执行一个sql
	 * 如果是读操作，返回一个资源对象
	 * 如果是写操作，返回true
	 * 如果失败，返回false
	 * @param string sql字符串
	 * @param array 绑定的数据
	 * @return mixed
	**/
	public function bindQuery($sql, $bind_data = array()){
		if(!is_string($sql) || ($sql = trim($sql)) == ''){
			trigger_error('sql不能为空', E_USER_ERROR);
		}
		if(!is_array($bind_data)){
			trigger_error('bind_data应为数组', E_USER_ERROR);
		}

		// 判断sql中的marker数和bind_data元素数是否匹配
		// preg_quote会在正则表达式特殊字符前增加反斜杠
		// PREG_OFFSET_CAPTURE用来记录每次匹配字符串在原字符串中的偏移
		$marker_count = preg_match_all('/'.preg_quote($this->_marker).'/', $sql, $matches, PREG_OFFSET_CAPTURE);
		if($marker_count != count($bind_data)){
			trigger_error('占位符和绑定的数据数量不符', E_USER_ERROR);
		}

		// 开始进行替换。如果值是字符串，直接替换，如果是数组，则转化成英文逗号分割的字符串
		// 由于要用到偏移量，所以从最后开始替换
		for($i = $marker_count - 1; $i >= 0; $i--){
			$str = $this->_escapeValue($bind_data[$i]);
			if(is_array($str)){
				$str = '('.implode(',', $str).')';
			}
			$sql = substr_replace($sql, $str, $matches[0][$i][1], strlen($this->_marker));
		}

		// 判断操作类型
		// $is_write_sql = preg_match('/^\s*"?(SET|INSERT|UPDATE|DELETE|REPLACE|CREATE|DROP|TRUNCATE|LOAD|COPY|ALTER|RENAME|GRANT|REVOKE|LOCK|UNLOCK|REINDEX)\s/i', $sql) === 1 ? true : false;

		// 执行sql
		return $this->query($sql);
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
	public function query($sql){
		if(!is_string($sql) || ($sql = trim($sql)) == ''){
			trigger_error('参数错误', E_USER_ERROR);
		}

		// 记录sql
		$this->_sqls[] = $sql;

		$ret = $this->_query($sql);

		if($ret === false){
			trigger_error($this->getError().". sql: ".$sql, E_USER_ERROR);
		}

		return $ret;
	}
	abstract protected function _query($sql);


	// ----------------------------处理输入-----------------------------------------

	/**
	 * 转义
	 * @param mixed 要转义的对象，如果是array，则递归
	**/
	protected function _escapeValue($str){
		if(is_int($str) || is_float($str)){
			return $str;
		}elseif(is_string($str)){
			return '\''.$this->_escapeStr($str).'\'';
		}elseif(is_array($str)){
			foreach($str as $k => $v){
				$str[$k] = $this->_escapeValue($v);
			}
			return $str;
		}elseif(is_bool($str) || is_null($str) || is_object($str) || is_resource($str)){
			trigger_error('bool,null,object,resource类型不允许', E_USER_ERROR);
		}
	}

	/**
	 * 对字段名进行处理
	 */
	protected function _escapeColumn($str){
		return $this->_escape_column_char.$str.$this->_escape_column_char;
	}


	/**
	 * 转义字符串特殊字符。需要被重写
	 * @param string 要转义的字符串
	 * @return string 转义过的字符串
	**/
	abstract protected function _escapeStr($str);

	// ----------------------------处理结果-----------------------------------------

	/**
	 * 处理结果集
	 */
	abstract protected function _fetchArray($result);


	// ----------------------------对外常用工具方法-----------------------------------------

	/**
	 * 获取结果数组
	 * @return array
	 */
	public function getRows($sql, $bind_data = array()){
		$result = $this->bindQuery($sql, $bind_data);
		$rows = $this->_fetchArray($result);
		return $rows;
	}

	/**
	 * 获取结果数组的第一条
	 * @param mixed array,null
	 */
	public function getRow($sql, $bind_data = array()){
		$rows = $this->getRows($sql, $bind_data);
		return count($rows) > 0 ? current($rows) : null;
	}

	/**
	 * 获取结果数组第一条的第一个字段
	 * @param mixed string,null
	 */
	public function getOne($sql, $bind_data = array()){
		$row = $this->getRow($sql, $bind_data);
		return count($row) > 0 ? current($row) : null;
	}

	/**
	 * 插入数据
	 */
	public function insert($table, $data){
		if(!is_string($table) || !is_array($data) || !($table = trim($table)) || !$data){
			trigger_error('参数错误', E_USER_ERROR);
		}
		$keys = array_map(array($this, '_escapeColumn'), array_keys($data));
		$values = array_map(array($this, '_escapeValue'), array_values($data));
		$table = $this->_escapeColumn($table);
		$sql = 'insert into '.$table.' ('.implode(',', $keys).') values ('.implode(',', $values).')';

		return $this->query($sql);
	}

	/**
	 * 更新
	 */
	public function update($table, $data, $where){
		// where必须不为空，防止全体更新。
		if(!is_string($table) || !is_array($data) || !is_array($where) || !($table = trim($table)) || !$data || !$where){
			trigger_error('参数错误', E_USER_ERROR);
		}
		// 处理table
		$table = $this->_escapeColumn($table);
		
		// 处理数据
		$escape_data = array();
		foreach($data as $k => $v){
			$escape_data[] = $this->_escapeColumn($k).' = '.$this->_escapeValue($v);
		}

		// 处理where条件
		$escape_where = array();
		foreach($where as $k => $v){
			$escape_where[] = $this->_escapeColumn($k).' = '.$this->_escapeValue($v);
		}

		$sql = 'update '.$table.' set '.implode(',', $escape_data).' where '.implode(' and ', $escape_where);

		return $this->query($sql);
	}

	/**
	 * 删除
	 */
	public function delete($table, $where){
		// where必须不为空，防止误删
		if(!is_string($table) || !is_array($where) || !($table = trim($table)) || !$where){
			trigger_error('参数错误', E_USER_ERROR);
		}

		// 处理table
		$table = $this->_escapeColumn($table);

		// 处理where条件
		$escape_where = array();
		foreach($where as $k => $v){
			$escape_where[] = $this->_escapeColumn($k).' = '.$this->_escapeValue($v);
		}

		$sql = 'delete from '.$table.' where '.implode(' and ', $escape_where);

		return $this->query($sql);
	}
	
}

?>