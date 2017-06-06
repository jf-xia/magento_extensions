<?php
/**
*
* Copyright CKApps.com
* email: app@ckapps.com
*
*/
class CKApps_AlipayFastLogin_LoginController extends Mage_Core_Controller_Front_Action
{

	/*
	*Get return information
	*/
	public function returnAction()
	{
    $data=$_GET;
    $verify=Mage::getModel('alipayfastlogin/login')->verifyReturn($data);
    if($verify){
   	$information=array(
		"realname"  => $_GET['real_name'],
		"user_id"  => $_GET['user_id'],
		"token" => $_GET['token'],
		"notify_id" => $_GET['notify_id']);

		$users=array(
		"firstname"=>"Alipayuser",
		"lastname"=>$information['realname'],
		"email"=>$information['user_id']."@alipay.com",
		"password"=>"new".substr($information['user_id'],0,6));

		$login=Mage::getModel('alipayfastlogin/login');
		$login->addCustomer($users['firstname'],$users['lastname'],$users['email'],$users['password']);
		$login->saveToken();
		$url=$login->getHomeUrl();
		$login->toRediect($url);
		}
		else die('someting wrong ,please try again later!');		
	}
	/*
	*Direct to alipay fastlogin interface
	*/
	public function directtoalipayAction()
	{
		$url=Mage::getModel('alipayfastlogin/login')->createlink();
		header("Location: https://$url");
		exit;
	}

	
	
}