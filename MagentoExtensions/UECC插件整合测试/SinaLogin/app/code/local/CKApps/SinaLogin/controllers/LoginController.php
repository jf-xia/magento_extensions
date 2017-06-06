<?php
/**
*
* Copyright CKApps.com
* email: app@ckapps.com
*
*/
class CKApps_SinaLogin_LoginController extends Mage_Core_Controller_Front_Action
{

	/*
	*Get return information
	*/
public function connecttosinaAction()
{
$login=Mage::getModel('sinalogin/login');
$request_link=$login->getAuthorizeURL(true);
$login->toRediect($request_link);
}

public function returnAction()
{
	$getdata=$_GET;
	if($getdata)
	{
		$oauth_verifier=$getdata['oauth_verifier'];
		$oauth_token=$getdata['oauth_token'];
		$login=Mage::getModel('sinalogin/login');
		$access_token=$login->getAccesstoken($oauth_token,$oauth_verifier);
		if($access_token['oauth_token'])
		{
			$user_information=$login->getSinauser($access_token['oauth_token'],$access_token['oauth_token_secret'],$access_token['user_id']);
			Mage::log($user_information);
			$users=array(
		     "firstname"=>"Sinauser",
		     "lastname"=>$user_information['SCREEN_NAME'],
		     "email"=>$user_information['ID']."@sina.com",
		     "password"=>"new".$user_information['ID']
		        );
		$login->addCustomer($users['firstname'],$users['lastname'],$users['email'],$users['password']);
		$url=$login->getHomeUrl();
		$login->toRediect($url);
			}
		}
	}
}