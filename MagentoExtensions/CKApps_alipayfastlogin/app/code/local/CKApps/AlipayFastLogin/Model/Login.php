<?php
/**
*
* Copyright CKApps.com
* email: app@ckapps.com
*
*/


class CKApps_AlipayFastLogin_Model_Login extends Mage_Payment_Model_Method_Abstract
{
	protected $_code  = 'alipayfastlogin_login';

	/**
	* verify by https
	*/
	var $https_verify_url = 'https://www.alipay.com/cooperate/gateway.do?service=notify_verify&';
	/**
	* verify by https
	*/
	var $http_verify_url = 'http://notify.alipay.com/trade/notify_query.do?';
	/*
	*Get return url
	*/
	public function getReturnUrl()
	{
		return Mage::getUrl('alipayfastlogin/login/return');
	}


	/*
	*Get Success url
	*/
	public function getSuccessUrl()
	{
		return Mage::getUrl('alipayfastlogin/login/success');
	}

	/*
	*Get Error url
	*/

	public function getErrorUrl()
	{
		return Mage::getUrl('alipayfastlogin/login/error');
	}

	/*
	*Get Home url
	*/

	public function getHomeUrl()
	{
		return Mage::getUrl('');
	}
	
   /*
 	 *Rediect to the page by its $url
	 */

	public function toRediect($url)
	{
		echo "<script type='text/javascript'>";
		echo "window.location.href='$url'";
		echo "</script>";
	}
	
	public function saveToken()
	{
		$session = Mage::getSingleton('customer/session');
		$session['alipay_fastlogin_token']=$_GET['token'];
	}

	/*
	*Create the final URL to alipayInterface
	*/
	
	public function createLink()
	{
		$aliapy_config['partner']      = $this->getConfigData('fpartner_id');
		$aliapy_config['key']          = $this->getConfigData('fsecurity_code');
		$aliapy_config['return_url']   = $this->getReturnUrl();
		$aliapy_config['sign_type']    = 'MD5';
		$aliapy_config['input_charset']= 'utf-8';
		$aliapy_config['transport']    = $this->getConfigData('transport');
		$aliapy_config['getway']       = $this->getConfigData('fgateway');
		$parameter = array(
		"service"			=> "alipay.auth.authorize",
		"target_service"	=> 'user.auth.quick.login',
		"partner"			=> trim($aliapy_config['partner']),
		"_input_charset"	=> trim(strtolower($aliapy_config['input_charset'])),
		"return_url"		=> trim($aliapy_config['return_url']),
		);
		$parameter=$this->para_filter($parameter);

		$myparameter=$this->arg_sort($parameter);

		$prestr =$this->createLinkstring($myparameter);

		$sort_parameter=$prestr.$aliapy_config['key'];

		$mysign=$this->sign($sort_parameter);

		$link=$aliapy_config['getway'].$prestr."&sign=".$mysign."&sign_type=".$aliapy_config['sign_type'];

		return $link;
	}
	
	/*
	*Sign the request string by MD5 method
	*/
	
	public function sign($prestr) {
		$mysign = md5($prestr);
		return $mysign;
	}
	
	/*
	*Remove the param sign&mysign and the NULL
	*/
	
	public function para_filter($parameter) {
		$para = array();
		while (list ($key, $val) = each ($parameter)) {
			if($key == "sign" || $key == "sign_type" || $val == "")continue;
			else	$para[$key] = $parameter[$key];

		}
		return $para;
	}
	
	/*
	*Sort the array
	*/
	
	public function arg_sort($array) {
		ksort($array);
		reset($array);
		return $array;
	}

	/*
	*Create linkString
	*/
	function createLinkstring($para) {
		$arg  = "";
		while (list ($key, $val) = each ($para)) {
			$arg.=$key."=".$val."&";
		}
		$arg = substr($arg,0,count($arg)-2);
		if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
		return $arg;
	}
	
	/*
	*Set customer information if not exists
	*Make a login of this customer
	*/
	public function addCustomer($cfirstname,$clastname,$cemail,$cpassword)
	{
		$customer = Mage::getModel('customer/customer');

		$password = $cpassword;
		$email = $cemail;
		$firstname  = $cfirstname;
		$lastname = $clastname;

		$customer->setWebsiteId(Mage::app()->getWebsite()->getId());
		$customer->loadByEmail($email);
		if(!$customer->getId()) {

			$customer->setEmail($email);
			$customer->setFirstname($firstname);
			$customer->setLastname($lastname);
			$customer->setPassword($password);
		}

		try {
			$customer->save();
			$customer->setConfirmation(null);
			$customer->save();

			//Make a "login" of new customer
			Mage::getSingleton('customer/session')->loginById($customer->getId());
		}

		catch (Exception $e) {
			//Zend_Debug::dump($ex->getMessage());
			Mage::logException($e);
		}
	}
	/*
	*Get results from alipay ATN server
	*/


	function getResponse($notify_id) {
		$transport= strtolower(trim($this->getConfigData('ftransport')));
		$partner = trim($this->getConfigData('fpartner_id'));
		$veryfy_url = '';
		if($transport == 'https') {
			$veryfy_url = $this->https_verify_url;
		}
		else {
			$veryfy_url = $this->http_verify_url;
		}
		$veryfy_url = $veryfy_url."partner=" . $partner . "&notify_id=" . $notify_id;
		$responseTxt = $this->getHttpResponse($veryfy_url);
		return $responseTxt;


	}
	/*
	*BuildMysign
	*/

	function buildMysign($sort_para,$key) {
		$prestr = $this->createLinkstring($sort_para);
		$prestr = $prestr.$key;
		$mysgin = $this->sign($prestr);
		return $mysgin;
	}
	/*
	*Get response from somepage by its URL
	*/
	function getHttpResponse($url, $input_charset = '', $time_out = "60") {
		$urlarr     = parse_url($url);
		$errno      = "";
		$errstr     = "";
		$transports = "";
		$responseText = "";
		if($urlarr["scheme"] == "https") {
			$transports = "ssl://";
			$urlarr["port"] = "443";
		} else {
			$transports = "tcp://";
			$urlarr["port"] = "80";
		}
		$fp=@fsockopen($transports . $urlarr['host'],$urlarr['port'],$errno,$errstr,$time_out);
		if(!$fp) {
			die("ERROR: $errno - $errstr<br />\n");
		} else {
			if (trim($input_charset) == '') {
				fputs($fp, "POST ".$urlarr["path"]." HTTP/1.1\r\n");
			}
			else {
				fputs($fp, "POST ".$urlarr["path"].'?_input_charset='.$input_charset." HTTP/1.1\r\n");
			}
			fputs($fp, "Host: ".$urlarr["host"]."\r\n");
			fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
			fputs($fp, "Content-length: ".strlen($urlarr["query"])."\r\n");
			fputs($fp, "Connection: close\r\n\r\n");
			fputs($fp, $urlarr["query"] . "\r\n\r\n");
			while(!feof($fp)) {
				$responseText .= @fgets($fp, 1024);
			}
			fclose($fp);
			$responseText = trim(stristr($responseText,"\r\n\r\n"),"\r\n");
			return $responseText;

		}
	}

	/*
	*Verify the information return is sent by alipay
	*@para $data alipay post data
	*@return true or fasle
	*/
	public 	function verifyReturn($postData){
		if(empty($postData)) {
			return false;
		}
		else {

			$mysign = $this->getMysign($postData);
			$responseTxt = 'true';
			if (! empty($postData['notify_id'])) {$responseTxt = $this->getResponse($postData['notify_id']);}
			if (preg_match("/true$/i",$responseTxt) && $mysign == $postData["sign"]) {
				return true;
			} else {
				return false;
			}
		}
	}
	/*
	*Get sign
	*/

	function getMysign($para_temp) {

		$para_filter = $this->para_filter($para_temp);
		$para_sort = $this->arg_sort($para_filter);
		$security_code = $this->getConfigData('fsecurity_code');
		$mysign = $this->buildMysign($para_sort, trim($security_code));
		return $mysign;
	}

}