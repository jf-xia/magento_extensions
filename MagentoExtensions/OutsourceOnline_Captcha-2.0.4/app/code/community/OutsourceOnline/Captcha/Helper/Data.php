<?php
/**
 * Outsource Online Captcha Extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category   Outsource Online
 * @package    OutsourceOnline_Captcha
 * @author     Sreekanth Dayanand
 * @copyright  Copyright (c) 2010 Outsource Online. (http://www.outsource-online.net)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
//require_once(dirname(__FILE__)."/OSOLmulticaptcha.php");
require_once(Mage::getModuleDir('', 'OutsourceOnline_Captcha')."/Helper/OSOLmulticaptcha.php");
class Outsourceonline_Captcha_Helper_Data extends Mage_Core_Helper_Abstract 
{
   
	
	var $bgColor = "#2C8007";
	var $textColor = "#FFFFFF";
		
	//var $params;
	var $botScoutProtection  = '';
	var $botscoutAPIKey  = '';
	var $redirectURLforSuspectedIPs  = '';
	var $reportBotscoutNegativeMail='';
	function display()
	{
		     $captcha = new OSOLmulticaptcha();
			
			$imageFunction =  'create_image'.Mage::getStoreConfig("OutsourceOnline_Captcha/setup/image_function");
		    $imageFunction = ((!method_exists($captcha,$imageFunction)))?'create_imageAdv':$imageFunction;
			$captcha->imageFunction = $imageFunction;
			$font_size = (int)Mage::getStoreConfig("OutsourceOnline_Captcha/setup/letter_size");
			$captcha->font_size = $font_size == 0 ? 24:$font_size;
			$font_file = Mage::getStoreConfig("OutsourceOnline_Captcha/setup/font_ttf");
			$font_file = $font_file == '' ?basename($captcha->font_ttf) :$font_file;
			$captcha->font_ttf  = $captcha->fontPNGLocation.DIRECTORY_SEPARATOR.'ttfs'.DIRECTORY_SEPARATOR.$font_file;
			$captcha->bgColor = Mage::getStoreConfig("OutsourceOnline_Captcha/setup/background_color");
			$captcha->textColor = Mage::getStoreConfig("OutsourceOnline_Captcha/setup/text_color");
			
			$captcha->symbolsToUse = Mage::getStoreConfig("OutsourceOnline_Captcha/setup/characters_allowed");
			//$captcha->fluctuation_amplitude = 4;//changing this creates unexpected issues
			$captcha->white_noise_density = Mage::getStoreConfig("OutsourceOnline_Captcha/setup/noise_in_bg") + 0 ;
			$captcha->black_noise_density = Mage::getStoreConfig("OutsourceOnline_Captcha/setup/noise_in_text") + 0;
			
			//die( "<pre>".print_r($captcha,true)."</pre>");
			
			$security_code = $captcha->displayCaptcha();
			//Set the session to store the security code
			
		    Mage::getSingleton('core/session')->setSecuriyCode($security_code);
		   exit;
			
		

		return true;
	}
	
	function validate()
	{
		$sessionSecurity_code= Mage::getSingleton('core/session')->getSecuriyCode();//die($sessionSecurity_code);
		$postedSecurityCode =Mage::getSingleton('core/app')->getRequest()->getParam('osolCatchaTxt');
		//$this->botscoutCheck();
		return ($sessionSecurity_code == $postedSecurityCode);
	}
	
	function validateBotScout($XNAME){
		$this->botscoutAPIKey  = Mage::getStoreConfig("OutsourceOnline_Captcha/botscout/botscout_api_key");
		$this->reportBotscoutNegativeMail  = Mage::getStoreConfig("OutsourceOnline_Captcha/botscout/botscout_report_email");
		if(trim($this->botscoutAPIKey) !='')
		{
			$this->botscoutCheck($XNAME);
			
		}
		
	}
	function botscoutCheck($XNAME)
	{
		/////////////////////////////////////////////////////
		// sample API code for use with the BotScout.com API
		// code by MrMike / version 2.0 / LDM 2-2009 
		/////////////////////////////////////////////////////
		
		/////////////////// START CONFIGURATION ////////////////////////
		// use diagnostic output? ('1' to use, '0' to suppress)
		// (normally set to '0')
		
		
		
		
		$diag = '0';
		
		/////////////////// END CONFIGURATION ////////////////////////
		
		
		////////////////////////
		// test values 
		// an email value...a bot, perhaps?
		// these would normally come from your 
		// web form or registration form code 
			
		//$XNAME = Mage::getSingleton('core/app')->getRequest()->getParam('email'); //contacts,signup
		// $_REQUEST['sender']['email']//send a friend
		//$_REQUEST['nickname'];//review
		
		// an IP address
		$XIP = $_SERVER['REMOTE_ADDR'];
		
		// a name, maybe a bot?
		$XNAME = urlencode($XNAME);
		
		////////////////////////
		// your optional API key (don't have one? get one here: http://botscout.com/
		$APIKEY=$this->botscoutAPIKey;
		
		$USEXML = 0;
		
		////////////////////////
		
		// sample query strings - you'd dynamically construct this 
		// string and use it as in the example below - these examples use the optional API 'key' field 
		// for more information on using the API key, please visit http://botscout.com
		
		// in most cases the BEST test is to use the "MULTI" query and test for the IP and email
		//$multi_test = "http://botscout.com/test/?multi&mail=$XMAIL&ip=$XIP&key=$APIKEY";
		
		/* you can use these but they're much less efficient and (possibly) not as reliable
		$test_string = "http://botscout.com/test/?mail=$XMAIL&key=$APIKEY";	// test email - reliable
		$test_string = "http://botscout.com/test/?ip=$XIP&key=$APIKEY";		// test IP - reliable
		$test_string = "http://botscout.com/test/?name=$XNAME&key=$APIKEY";	// test name (unreliable!)
		$test_string = "http://botscout.com/test/?all=$XNAME&key=$APIKEY";	// test all (see docs)
		*/
		
		// make the url compliant with urlencode()
		//$XMAIL = urlencode($XMAIL);
		
		// for this example we'll use the MULTI test 
		//$test_string = "http://botscout.com/test/?multi&mail=$XMAIL&ip=$XIP";
		$test_string = "http://botscout.com/test/?multi&all=$XNAME&ip=$XIP";
		
		// are using an API key? If so, append it.
		if($APIKEY != ''){
			$test_string = "$test_string&key=$APIKEY";
		}
		
		// are using XML responses? If so, append the XML format key.
		if($USEXML == '1'){
			$test_string = "$test_string&format=xml";
		}
		
		////////////////////////
		if($diag=='1'){print "Test String: $test_string";}
		////////////////////////
		
		
		////////////////////////
		// use file_get_contents() or cURL? 
		// we'll user file_get_contents() unless it's not available 
		
		if(function_exists('file_get_contents')&& (ini_get('allow_url_fopen')=='On')){
			// Use file_get_contents
			$data = file_get_contents($test_string);
		}else{
			$ch = curl_init($test_string);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$returned_data = curl_exec($ch);
			curl_close($ch);
		}
		
		// diagnostic output 
		if($diag=='1'){
			print "RETURNED DATA: $returned_data";
			// sanity check 
			if($returned_data==''){ print 'Error: No return data from API query.'; exit; } 
		} 
		//die($test_string."<br />".$returned_data);
		
		// take the returned value and parse it (standard API, not XML)
		$botdata = explode('|', $returned_data); 
		
		// sample 'MULTI' return string 
		// Y|MULTI|IP|4|MAIL|26|NAME|30
		
		// $botdata[0] - 'Y' if found in database, 'N' if not found, '!' if an error occurred 
		// $botdata[1] - type of test (will be 'MAIL', 'IP', 'NAME', or 'MULTI') 
		// $botdata[2] - descriptor field for item (IP)
		// $botdata[3] - how many times the IP was found in the database 
		// $botdata[4] - descriptor field for item (MAIL)
		// $botdata[5] - how many times the EMAIL was found in the database 
		// $botdata[6] - descriptor field for item (NAME)
		// $botdata[7] - how many times the NAME was found in the database 
		//$mainframe->redirect($this->redirectURLforSuspectedIPs);
		if($botdata[0] == 'Y'){
			
			//$this->botScoutProtection  = $this->params->get('botScoutProtection',$this->botScoutProtection);//Disable,Redirect,Stop
			//$this->redirectURLforSuspectedIPs  = $this->params->get('redirectURLforSuspectedIPs',$this->redirectURLforSuspectedIPs);
			if($this->reportBotscoutNegativeMail  !='')
			{
				$this->mailBotScoutResult();
			}
			
			echo "Sorry your IP :  $XIP and sender name/email : $XNAME was reported spam by http://www.botscout.com/.<br />So we cannot process your submission";
			exit;
			
		}
		
		if(($diag=='1') && substr($returned_data, 0,1) == '!'){
			// if the first character is an exclamation mark, an error has occurred  
			print "Error: $returned_data";
			exit;
		}
		
		
		// this example tests the email address and IP to see if either of them appear 
		// in the database at all. Either one is a fairly good indicator of bot identity. 
		if($botdata[3] > 0 || $botdata[5] > 0){ 
			if($diag=='1')print $data; 
		
			if($diag=='1'){ 
				print "Bot signature found."; 
				print "Type of test was: $botdata[1]"; 
				print "The {$botdata[2]} was found {$botdata[3]} times, the {$botdata[4]} was found {$botdata[5]} times"; 
			} 
		
			// your 'rejection' code would go here.... 
			// for example, print a fake error message and exit the process. 
			$errnum = round(rand(1100, 25000));
			if($diag=='1')print "Confabulation Error #$errnum, Halting.";
			exit;
		
		}
		////////////////////////
	}
	function mailBotScoutResult($isSecondLevel =  false)
	{
					$mailBody = "Following request from IP:{$_SERVER['REMOTE_ADDR']} returned a -ve result on $verificationType verification in ".Mage::helper('core/url')->getCurrentUrl()."\r\n Get vars =".var_export($_GET,true)."\r\n POST vars =".var_export($_POST,true)."\r\n REQUEST vars =".var_export($_REQUEST,true);
					$mail = new Zend_Mail();
					$mail->setBodyText($mailBody);
					
					$mail->setFrom( Mage::getStoreConfig('contacts/email/recipient_email'), Mage::getStoreConfig('design/head/default_title'));
					$mail->addTo($this->reportBotscoutNegativeMail, 'Some Recipient');
					$mail->setSubject(Mage::getStoreConfig('design/head/default_title').'" : Suspected spam attack from '.$_SERVER['REMOTE_ADDR'] .' botscout.com api -ve result');
					try {
						$mail->send();
					}        
					catch(Exception $ex) {
					   // Mage::getSingleton('core/session')->addError('Unable to send email. ');
						echo 'Unable to send email.Please try again after some time';			return;
					}
	}
	
	
}
?>
