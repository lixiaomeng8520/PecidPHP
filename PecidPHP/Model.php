<?php
if(!defined('ENV')){ exit('access deny'); }

class Model{

	protected $_db = null;
	protected $_table = '';
	protected $_pk = '';
	protected $_fields = array();
	protected $_validator = array();

	public function __construct(){
		$this->_db = lib('PC_Mysql');
	}

	protected function _filter_fields(& $data){
		foreach($data as $k => $v){
			if(!in_array($k, $this->_fields)){
				unset($data[$k]);
			}
		}
	}

	public function validForm(& $data, $other_data = null){
		$error = array();
		foreach($this->_validator as $k => $validator){
			if(!isset($data[$k])){
				trigger_error('validate field not exist: '.$k, E_USER_ERROR);
			}
			foreach($validator as $rule => $param){
				$valid = $this->validate($data[$k], $rule, $other_data);
				if($valid == false) $error[] = $param['message'];
			}
		}
		return $error;
	}

	protected function validate($value,$rule, $other_data) {
        $regex = Conf('regex');
        if(isset($regex[strtolower($rule)])){
			$rule = $regex[strtolower($rule)];
			return preg_match($rule,$value) === 1;
        }else{
        	if(!method_exists($this, $rule)){
        		trigger_error('validate function not found: '.$rule, E_USER_ERROR);
        	}
        	return $this->$rule($value, $other_data);
        }
        
    }

	public function get($id){
		$sql = 'select * from %t where '.$this->_pk.'=%s';
		$ret = $this->_db->getFirst($sql, array($this->_table, $id));
		return $ret;
	}

	public function insert($data){
		$this->_filter_fields($data);
		$ret = $this->_db->insert($this->_table, $data);
	}

	public function getInsertId(){
		return $this->_db->getInsertId();
	}

	public function update($id, $data){
		$this->_filter_fields($data);
		$ret = $this->_db->update($this->_table, $data, $this->_pk.'=%s', array($id));
		return $ret;
	}

	public function getAllSql(){
		return $this->_db->getAllSql();
	}

	public function getLastSql(){
		return $this->_db->getLastSql();
	}
}
?>