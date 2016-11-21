<?php 
class PC_Http extends PC_Lib{


	public function curlGet($url){
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		$ret = curl_exec($ch);
	    curl_close($ch);
	    return $ret;
	}

	public function curlPost($url, $data = array()){

	}

	protected function curl(){

	}
}

?>