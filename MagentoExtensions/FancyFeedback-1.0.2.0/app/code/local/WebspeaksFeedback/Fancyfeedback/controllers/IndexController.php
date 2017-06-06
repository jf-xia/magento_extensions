<?php
class WebspeaksFeedback_Fancyfeedback_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
		$fancyfeedbackTable = Mage::getSingleton('core/resource')->getTableName('fancyfeedback');
		
		$write = Mage::getSingleton("core/resource")->getConnection("core_write");
		$query = "insert into ".$fancyfeedbackTable." (name, email, comment, ip, created_time) values (:name, :email, :comment, :ip, NOW())";

		$binds = array(
			'name'      		=> ($_REQUEST['name'])?$_REQUEST['name']:'',
			'email'  		    => ($_REQUEST['email'])?$_REQUEST['email']:'',
			'comment'		    => ($_REQUEST['msg'])?$_REQUEST['msg']:'',
			'ip'	    		=> $this->getRealIpAddr(),
		);
		$write->query($query, $binds);

		//echo json_encode(array('value'=>'Thanks for your response!'));
		echo 'Thanks for your response!';
		
		$this->sendMail($_REQUEST['email'], $_REQUEST['name'], Mage::getStoreConfig('fancyfeedbackconfig/fancyfeedback_group/fancyfeedback_subject'), $_REQUEST['msg']);
		die;
    }

	public function sendMail($email, $name, $subject='', $body='')
    {
        ini_set('SMTP', Mage::getStoreConfig('system/smtp/host'));
        ini_set('smtp_port', Mage::getStoreConfig('system/smtp/port'));

		$receiver_email = Mage::getStoreConfig('fancyfeedbackconfig/fancyfeedback_group/fancyfeedback_receiveremail');
		$receiver_subject = Mage::getStoreConfig('fancyfeedbackconfig/fancyfeedback_group/fancyfeedback_subject');

		$mail = new Zend_Mail(); //class for mail
		$mail->setBodyHtml($body); //for sending message containing html code
		$mail->setFrom($email, $name);
		$mail->addTo($receiver_email);
		//$mail->addCc($cc, $ccname);    //can set cc
		//$mail->addBCc($bcc, $bccname);    //can set bcc
		$mail->setSubject($receiver_subject);
		try {
			  if($mail->send())
			  {
				return true;
			  }
			}
		catch(Exception $ex) {
				// echo 'error->'.$error_msg = $ex->getMessage();
				return false;
		}
    } 
	
	public function getRealIpAddr()
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
		{
		  $ip=$_SERVER['HTTP_CLIENT_IP'];
		}
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
		{
		  $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
		  $ip=$_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
}