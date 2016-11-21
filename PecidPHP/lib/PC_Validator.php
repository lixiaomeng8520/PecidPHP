<?php 
class PC_Validator extends PC_Lib{
	/**
	 *	array(
	 *		'title'	=>	array(
	 *			'require'	=>	array(
	 *				'message'	=>	'标题不能为空',
	 *			),
	 *		),
	 *	)
	 */
	protected $_validator = array();

	public function setValidator($validator){
		$this->_validator = $validator;
	}

	public function validate(){
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

	public 
}

?>