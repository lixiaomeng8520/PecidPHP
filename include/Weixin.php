<?php 
class Weixin{
	private static $_instance;
	private $_appid = '';
	private $_appsecret = '';
	private $_data_dir = '';
	private $_current_url = '';
	private function __construct($appid, $appsecret, $data_dir){
		$this->_appid = $appid;
		$this->_appsecret = $appsecret;
		$this->_data_dir = $data_dir;
		$this->_current_url = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://").$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		if($data_dir){
			@mkdir($data_dir, 0777,true);
		}
	}
	public static function getInstance($appid, $appsecret, $data_dir){
		if(!self::$_instance instanceof self){
			self::$_instance = new self($appid, $appsecret, $data_dir);
		}
		return self::$_instance;
	}

	/*获取网页授权用户信息*/
	public function getUserinfo($scope = 'snsapi_base'){
		if(!in_array($scope, array('snsapi_base', 'snsapi_userinfo'))){
			trigger_error('scope error', E_USER_ERROR);
		}

		/*return array(
			"openid"	=> "oPCeewuRjIFbPdUPEyulSy9sx1EQA",
		  	"nickname"	=> "河马牛",
		  	"sex"		=> 1,
		  	"language"	=> "zh_CN",
		  	"city"		=> "",
		  	"province"	=> "",
		  	"country"	=> "CN",
		  	"headimgurl"=> "http://wx.qlogo.cn/mmopen/Xmnun9Io49QLjIg3PzkcnyvRbRbavGF4wf6VjVnr74bWLtLGibNuj77PLHvpYKFmzn8iaiaWRpH3ZwHQibQvojxvMw/0",
		  	"privilege"	=>serialize(array()),
		);
*/
		if(isset($_GET['code'])){
			/*
				array(5) {
				  "access_token" => "mrABtZEciVV-tUuaXCU66FsHauLm3aSo4e2nO-91DL5n0_dytak3PVpYKUmu9jCQEfc3Oj1lODl9oS5LukUbG-3yecPLon0SjLR5lBXuWis",
				  "expires_in" => 7200,
				  "refresh_token" => "yCxtxqI86hMKq6RbSGrKlfOfcsitbDZ3_4VnKuVk0Ykkf-KJvda3cx53Ss0WHynsmbxyqg30sb-Xs1r_1UqRLM_AVxGtgtJ40c5KEB7WqqI",
				  "openid" => "oPCeewuRjIFbPUPEyulSy9sx1EQA",
				  "scope" => "snsapi_base"
				}

			*/

			/*
				array(9) {
				  ["openid"]=> "oPCeewuRjIFbPUPEyulSy9sx1EQA"
				  ["nickname"]=> "河马牛"
				  ["sex"]=> 1
				  ["language"]=> "zh_CN"
				  ["city"]=> ""
				  ["province"]=> ""
				  ["country"]=> "CN"
				  ["headimgurl"]=> "http://wx.qlogo.cn/mmopen/Xmnun9Io49QLjIg3PzkcnyvRbRbavGF4wf6VjVnr74bWLtLGibNuj77PLHvpYKFmzn8iaiaWRpH3ZwHQibQvojxvMw/0"
				  ["privilege"]=>array(0) {}
				}
			*/
			$url_access_token = 'https://api.weixin.qq.com/sns/oauth2/access_token?'.
					'appid='.$this->_appid.'&'.
					'secret='.$this->_appsecret.'&'.
					'code='.$_GET['code'].'&'.
					'grant_type=authorization_code';
			$access_token = $this->httpGet($url_access_token);
			$access_token = json_decode($access_token,true);
			if(!$access_token || isset($access_token['errcode'])){
				return false;
			}
			if($scope == 'snsapi_base'){
				return $access_token;
			}else{
				$url_userinfo = 'https://api.weixin.qq.com/sns/userinfo?'.
								'access_token='.$access_token['access_token'].'&'.
								'openid='.$access_token['openid'];
				$userinfo = $this->httpGet($url_userinfo);
				$userinfo = json_decode($userinfo, true);
				if(!$userinfo || isset($userinfo['errcode'])){
					return false;
				}
				return $userinfo;
			}
		}else{
			$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?'.
					'appid='.$this->_appid.'&'.
					'redirect_uri='.urlencode($this->_current_url).'&'.
					'response_type=code&'.
					'scope='.$scope.'&'.
					'state=STATE#wechat_redirect';
			header('Location: '.$url);
			exit;
		}
	}

	public function getSignPackage(){
		$jsapiTicket = $this->getJsApiTicket();

	    // 注意 URL 一定要动态获取，不能 hardcode.
	    // $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	    // $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	    $timestamp = time();
	    $nonceStr = $this->createNonceStr();

	    // 这里参数的顺序要按照 key 值 ASCII 码升序排序
	    $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=".$this->_current_url;

	    $signature = sha1($string);

	    $signPackage = array(
	      "appid"     => $this->_appid,
	      "nonceStr"  => $nonceStr,
	      "timestamp" => $timestamp,
	      "url"       => $this->_current_url,
	      "signature" => $signature,
	      "rawString" => $string
	    );
	    return $signPackage; 
	}

	private function createNonceStr($length = 16) {
	    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	    $str = "";
	    for ($i = 0; $i < $length; $i++) {
	      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
	    }
	    return $str;
	}

	private function getJsApiTicket() {
	    // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
	    $data = json_decode($this->get_php_file($this->_data_dir."/jsapi_ticket.php"));
	    if ($data === null || $data->expire_time < time()) {
	      	$accessToken = $this->getAccessToken();
	      	// 如果是企业号用以下 URL 获取 ticket
	      	// $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
	      	$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
	      	$res = json_decode($this->httpGet($url));
	      	$ticket = $res->ticket;
	      	if ($ticket) {
	      		$data = new stdClass;
	        	$data->expire_time = time() + 7000;
	        	$data->jsapi_ticket = $ticket;
	        	$this->set_php_file($this->_data_dir."/jsapi_ticket.php", json_encode($data));
	      	}
	    } else {
	      	$ticket = $data->jsapi_ticket;
	    }

	    return $ticket;
	}

	private function getAccessToken() {
	    // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
	    $data = json_decode($this->get_php_file($this->_data_dir."/access_token.php"));
	    if ($data === null || $data->expire_time < time()) {
	      	// 如果是企业号用以下URL获取access_token
	      	// $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->_appid&corpsecret=$this->_appsecret";
	      	$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->_appid&secret=$this->_appsecret";
	      	$res = json_decode($this->httpGet($url));
	      	$access_token = $res->access_token;
	      	if ($access_token) {
	      		$data = new stdClass;
	        	$data->expire_time = time() + 7000;
	        	$data->access_token = $access_token;
	        	$this->set_php_file($this->_data_dir."/access_token.php", json_encode($data));
	      	}
	    } else {
	      $access_token = $data->access_token;
	    }
	    return $access_token;
	}

	private function httpGet($url) {
	    $ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	    // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
	    // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	    curl_setopt($ch, CURLOPT_HEADER, FALSE);

	    $res = curl_exec($ch);
	    curl_close($ch);

	    return $res;
	}

	private function get_php_file($filename) {
		$content = @file_get_contents($filename);
	    return trim(substr($content === false ? '' : $content, 15));
	}
	  private function set_php_file($filename, $content) {
	    $fp = fopen($filename, "w");
	    fwrite($fp, "<?php exit();?>" . $content);
	    fclose($fp);
	}
}

?>