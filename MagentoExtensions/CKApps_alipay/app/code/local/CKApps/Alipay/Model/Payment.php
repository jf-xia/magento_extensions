<?php

/**
*
* Copyright CKApps.com
* email: app@ckapps.com
*
*/

class CKApps_Alipay_Model_Payment extends Mage_Payment_Model_Method_Abstract
{
	protected $_code  = 'alipay_payment';
	protected $_formBlockType = 'alipay/form';
	/**
	* verify by https
	*/
	var $https_verify_url = 'https://www.alipay.com/cooperate/gateway.do?service=notify_verify&';
	/**
	* verify by https
	*/
	var $http_verify_url = 'http://notify.alipay.com/trade/notify_query.do?';

	// Alipay return codes of payment
	const RETURN_CODE_ACCEPTED      = 'Success';
	const RETURN_CODE_TEST_ACCEPTED = 'Success';
	const RETURN_CODE_ERROR         = 'Fail';

	// Payment configuration
	protected $_isGateway               = false;
	protected $_canAuthorize            = true;
	protected $_canCapture              = true;
	protected $_canCapturePartial       = false;
	protected $_canRefund               = false;
	protected $_canVoid                 = false;
	protected $_canUseInternal          = false;
	protected $_canUseCheckout          = true;
	protected $_canUseForMultishipping  = false;

	// Order instance
	protected $_order = null;

	/**
	*  Returns Target URL
	*
	*  @return	  string Target URL
	*/

	public function getAlipayUrl()
	{
		$url = $this->getConfigData('transport').'://'.$this->getConfigData('gateway');
		return $url;
	}

	/**
	*  Return back URL
	*
	*  @return	  string URL
	*/
	protected function getReturnURL()
	{
		return Mage::getUrl('alipay/payment/return', array('_secure' => true));
	}

	/**
	*  Return URL for Alipay notify response
	*
	*  @return	  string URL
	*/
	protected function getNotifyURL()
	{
		return Mage::getUrl('alipay/payment/notify/', array('_secure' => true));
	}


	/**
	*  Form block description
	*
	*  @return	 object
	*/
	public function createFormBlock($name)
	{
		$block = $this->getLayout()->createBlock('alipay/form_payment', $name);
		$block->setMethod($this->_code);
		$block->setPayment($this->getPayment());

		return $block;
	}

	/**
	*  Return Order Place Redirect URL
	*
	*  @return	  string Order Redirect URL
	*/
	public function getOrderPlaceRedirectUrl()
	{
		return Mage::getUrl('alipay/payment/redirect');
	}

	/*
	*Get the value of token from session
	*/
	public function getToken()
	{
		$session = Mage::getSingleton('customer/session');
		return $session['alipay_fastlogin_token'];
	}
	/**
	*  Return Standard Checkout Form Fields for request to Alipay
	*
	*  @return	  array Array of hidden form fields
	*/
	public function getStandardCheckoutFormFields()
	{
		$session = Mage::getSingleton('checkout/session');
		$order = $this->getOrder();
		if (!($order instanceof Mage_Sales_Model_Order)) {
			Mage::throwException($this->_getHelper()->__('Cannot retrieve order object'));
		}

		$parameter = array('service'           => $this->getConfigData('service_type'),
		'partner'           => $this->getConfigData('partner_id'),
		'return_url'        => $this->getReturnURL(),
		'notify_url'        => $this->getNotifyURL(),
		'_input_charset'    => 'utf-8',
		'subject'           => $order->getRealOrderId(),
		'body'              => $order->getRealOrderId(),
		'out_trade_no'      => $order->getRealOrderId(), // order ID
		'logistics_fee'     => '0.00', //because magento has shipping system, it has included shipping price
		'logistics_payment' => 'BUYER_PAY',  //always
		'logistics_type'    => 'EXPRESS', //Only three shipping method:POST,EMS,EXPRESS
		'price'             => sprintf('%.2f', $order->getBaseGrandTotal()) ,
		'payment_type'      => '1',
		'quantity'          => '1', // For the moment, the parameter of price is total price, so the quantity is 1.
		'show_url'          => Mage::getUrl(),
		'seller_email'      => $this->getConfigData('seller_email'),
		'token'             => $this->getToken()
		);
		$parameter = $this->para_filter($parameter);
		$security_code = $this->getConfigData('security_code');
		$sign_type = 'MD5';

		$sort_array = array();
		$arg = "";
		$sort_array = $this->arg_sort($parameter); //$parameter

		while (list ($key, $val) = each ($sort_array)) {
			$arg.=$key."=".$this->charset_encode($val,$parameter['_input_charset'])."&";
		}

		$prestr = substr($arg,0,count($arg)-2);

		$mysign = $this->sign($prestr.$security_code);

		$fields = array();
		$sort_array = array();
		$arg = "";
		$sort_array = $this->arg_sort($parameter); //$parameter
		while (list ($key, $val) = each ($sort_array)) {
			$fields[$key] = $this->charset_encode($val,'utf-8');
		}
		$fields['sign'] = $mysign;
		$fields['sign_type'] = $sign_type;
		return $fields;
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
	*Different input_charset
	*/
	public function charset_encode($input,$_output_charset ,$_input_charset ="GBK" ) {
		$output = "";
		if($_input_charset == $_output_charset || $input ==null) {
			$output = $input;
		} elseif (function_exists("mb_convert_encoding")){
			$output = mb_convert_encoding($input,$_output_charset,$_input_charset);
		} elseif(function_exists("iconv")) {
			$output = iconv($_input_charset,$_output_charset,$input);
		} else die("sorry, you have no libs support for charset change.");
		return $output;
	}

	/**
	* Return authorized languages by Alipay
	*
	* @param	none
	* @return	array
	*/
	protected function _getAuthorizedLanguages()
	{
		$languages = array();

		foreach (Mage::getConfig()->getNode('global/payment/alipay_payment/languages')->asArray() as $data)
		{
			$languages[$data['code']] = $data['name'];
		}

		return $languages;
	}

	/**
	* Return language code to send to Alipay
	*
	* @param	none
	* @return	String
	*/
	protected function _getLanguageCode()
	{
		// Store language
		$language = strtoupper(substr(Mage::getStoreConfig('general/locale/code'), 0, 2));

		// Authorized Languages
		$authorized_languages = $this->_getAuthorizedLanguages();

		if (count($authorized_languages) === 1)
		{
			$codes = array_keys($authorized_languages);
			return $codes[0];
		}

		if (array_key_exists($language, $authorized_languages))
		{
			return $language;
		}

		// By default we use language selected in store admin
		return $this->getConfigData('language');
	}

	/*
	*CreateLinkstring
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
	*BuildMysign
	*/

	function buildMysign($sort_para,$key) {
		$prestr = $this->createLinkstring($sort_para);
		$prestr = $prestr.$key;
		$mysgin = $this->sign($prestr);
		return $mysgin;
	}
	/*
	*Get sign
	*/

	function getMysign($para_temp) {

		$para_filter = $this->para_filter($para_temp);
		$para_sort = $this->arg_sort($para_filter);
		$security_code = $this->getConfigData('security_code');
		$mysign = $this->buildMysign($para_sort, trim($security_code));
		return $mysign;
	}
	/*
	*Get results from alipay ATN server
	*/


	function getResponse($notify_id) {
		$transport = strtolower(trim($this->getConfigData('transport')));
		$partner = trim($this->getConfigData('partner_id'));
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
	*verify the information got by notify_url was sent by alipay
	*/
	public 	function verifyNotify($postData){
		if(empty($postData)) {
			return false;
		}
		else {

			$mysign = $this->getMysign($postData);

			$responseTxt = 'true';
			if (! empty($postData["notify_id"])) {$responseTxt = $this->getResponse($postData["notify_id"]);}

			if (preg_match("/true$/i",$responseTxt) && $mysign == $postData["sign"]) {
				return true;
			} else {
				return false;
			}
		}
	}
	/*
	*Create seendgoods URL
	*/
	public function createSendGoodsUrl($out_trade_no)
	{
		$trade_no=$this->getTrade_no($out_trade_no);
		$sendinformation =array(
		'trade_no'        => $trade_no,
		'logistics_name'  => 'MyLogistics',
		'invoice_no'      => '',
		'transport_type'  => 'EXPRESS'
		);
		$parameter=array(
		'service'			          => 'send_goods_confirm_by_platform',
		'partner'		          => trim($this->getConfigData('partner_id')),
		'_input_charset'	    => 'utf-8',
		'trade_no'            => $sendinformation['trade_no'],
		'logistics_name'      => $sendinformation['logistics_name'],
		'invoice_no'          => $sendinformation['invoice_no'],
		'transport_type'      => $sendinformation['transport_type']
		);
		$getway='https://mapi.alipay.com/gateway.do?';
		$sign_type='MD5';
		$security_code = $this->getConfigData('security_code');
		$sign=$this->getMysign($parameter);
		$para_filter = $this->para_filter($parameter);
		$para_sort = $this->arg_sort($para_filter);
		$sendUrl=$getway.$this->createLinkstring($para_sort).'&sign='.$sign.'&sign_type='.$sign_type;
		$this->getInformationFromSendGoods($sendUrl);
	}
	/*
	*Get information from alipaySendGoodsInterface
	*/
	public function getInformationFromSendGoods($sendUrl)
	{
		$xml_data = $this->getHttpResponse($sendUrl,trim(strtolower('utf-8')));
		$pos = strpos($xml_data, 'xml');
		if ($pos) {
			$xmlCode =simplexml_load_string($xml_data);
			$judge=$xmlCode->is_success;
		  $out_trade_no =$xmlCode->response->tradeBase->out_trade_no;
			if($judge=="T")
			{
				$order = Mage::getModel('sales/order');
				$order->loadByIncrementId($out_trade_no);
				$order->addStatusToHistory(
				$order->getStatus(),
				Mage::helper('alipay')->__('Already sent goods,wait customer to confirm'));
				try
				{
					$order->save();
				} catch(Exception $e)
				{
					Mage::logException($e);
				}
			}
			else echo 'Faild to sendgoods!please try again!';
		}
	}
	/*
	*Get Trade_no from database by the  out_trade_no
	*/
	public function getTrade_no($out_trade_no)
	{ 
		$get = "SELECT trade_no FROM `order_trade_no`where out_trade_no='$out_trade_no'";
		$trade_no='';
		try{
			$connection = Mage::getSingleton('core/resource')->getConnection('alipay_read');
			$trade_no=$connection->fetchRow($get);
		}
		catch(Exception $e)
				{
					Mage::logException($e);
				}
				return $trade_no['trade_no'];
			}
	/*
	*Save the Trade_no and out_trade_no to database
	*if exists,then Update it
	*otherwise insert directly
	*/
	public function saveTrade_no($out_trade_no,$trade_no)
	{ 
		try
		{
		$write = Mage::getSingleton('core/resource')->getConnection('alipay_write');
	  $read = Mage::getSingleton('core/resource')->getConnection('alipay_read');
	  }
		catch(Exception $e)
				{
					Mage::logException($e);
				}
		$insert = "INSERT INTO order_trade_no (out_trade_no,trade_no) VALUES ('$out_trade_no','$trade_no')";
		$query="SELECT * FROM `order_trade_no`where out_trade_no='$out_trade_no'";
		$delete="DELETE FROM `order_trade_no`where out_trade_no='$out_trade_no'";		
		$row=$read->fetchRow($query);	
		if($row)
		{
			try
			{
			$write->query($delete);
			$write->query($insert);
			}
			catch(Exception $e)
				{
					Mage::logException($e);
				}
				}
				else 
				try
			{
			$write->query($insert);
			}
			catch(Exception $e)
				{
					Mage::logException($e);
				}

	}
	
	/*
	*Mate the trade_status sent by alipay server with order_status set in alipaymodel
	*/
	public function transformTrdadeStatus($trade_status)
	{
		$order_status='';
		if($trade_status=='WAIT_BUYER_PAY')
		{
			$order_status=$this->getConfigData('order_status_new');
		}
		if($trade_status=='WAIT_SELLER_SEND_GOODS')
		{
			$order_status= $this->getConfigData('order_status_paid');
		}
		if($trade_status=='WAIT_BUYER_CONFIRM_GOODS')
		{
			$order_status=$this->getConfigData('order_status_sent');
		}
		if($trade_status=='TRADE_FINISHED')
		{
			$order_status= $this->getConfigData('order_status_finished');
		}

		/*
		*make the below available when you allow buyer to refund goods
		*
		if($trade_status=='WAIT_SELLER_AGREE')
		{
		$order_status=$this->getConfigData('order_status_want_fefund');
		}
		if($trade_status=='WAIT_BUYER_RETURN_GOODS')
		{
		$order_status= $this->getConfigData('order_status_agree_fefund');
		}
		if($trade_status=='WAIT_SELLER_CONFIRM_GOODS')
		{
		$order_status=$this->getConfigData('order_status_confirm_goods');
		}
		if($trade_status=='REFUND_SUCCESS')
		{
		$order_status=$this->getConfigData('order_status_refund_finished');
		}
		*/
		return $order_status;
	}
}