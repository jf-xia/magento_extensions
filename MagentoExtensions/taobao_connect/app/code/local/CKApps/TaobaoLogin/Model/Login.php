<?php
/**
*
* Copyright CKApps.com
* email: app@ckapps.com
*
*/


class CKApps_TaobaoLogin_Model_Login extends Mage_Payment_Model_Method_Abstract
{
	protected $_code  = 'taobaologin_login';


	/*
	*Get RETURN URL
	*/
	public function getReturnUrl()
	{
		return Mage::getUrl('sinalogin/login/return');
	}
	/*
	*Get APP KEY
	*/

	public function getKey()
	{
		return $this->getConfigData('security_key');
	}
	/*
	*Get APP SECRET
	*/
	public function getSecret()
	{
		return $this->getConfigData('security_code');
	}

	/*
	*Get REQUEST URL
	*/
	public function getRequesURL()
	{
		return 'http://container.api.taobao.com/container/identify';
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



	/*
	*Remove the param sign&mysign and the NULL
	*/

	public function para_filter($parameter) {
		$para = array();
		while (list ($key, $val) = each ($parameter)) {
			if($val == "")continue;
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
			$arg.=$key."=".urlencode($val)."&";
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
	*CREATE PARAMS
	*/
	public function createParams($sign=NULL)
	{
		$infoparams=array(
		"timestamp"=>$this->getTimetamp(),
		"app_key"=>$this->getKey(),
		"sign_method"=>'md5',
		"sign"=>$sign
		);
		return $this->para_filter($infoparams);
	}
	/*
	*GET SIGN
	*/
	public function createSign($params)
	{  $secret=$this->getSecret();
		$signstring='';
		foreach($params as $key => $val){
			if($key != '' && $val != ''){
				$signstring .= $key.$val;
			}
		}
		$signstring=$secret.$signstring.$secret;
		$sign = strtoupper(md5($signstring));
		return $sign;
	}

	/*
	*GET REQUEEST URL
	*/
	public function getRequestUrl()
	{
		$params=$this->arg_sort($this->createParams());
		$sign=$this->createSign($params);
		$paramsadd=$this->arg_sort($this->createParams($sign));
		$link=$this->createLinkstring($paramsadd);
		$requesturl=$this->getRequesURL();
		return $requestURL=$requesturl.'?'.$link;

	}

	/*
	*GET TIMESTAMP
	*/
	public function getTimetamp()
	{
		date_default_timezone_set("Asia/Shanghai");
		return date("Y-m-d H:i:s",time());

	}

}