<?php
/**
*
* Copyright CKApps.com
* email: app@ckapps.com
*
*/
class CKApps_TaobaoLogin_LoginController extends Mage_Core_Controller_Front_Action
{

	/*
	*Get return information
	*/
	public function connecttaobaoAction()
	{
  $login=Mage::getModel('taobaologin/login');
  $url=$login->getRequestUrl();
  $login->toRediect($url);
	}
	/*
	*Direct to alipay fastlogin interface
	*/
	public function returnAction()
	{	
		if ($this->getRequest()->isPost())
		{
			$getData = $this->getRequest()->getPost();
			$method = 'post';
		} else if ($this->getRequest()->isGet())
		{
			$getData = $this->getRequest()->getQuery();
			$method = 'get';

		} else
		{
			return;
		}
		$users=array(
		"firstname"=>"Taobaouser",
		"lastname"=>$getData['taobao_user_nick'],
		"email"=>$getData['taobao_user_id']."@taobao.com",
		"password"=>"new".$getData['taobao_user_id']
		);
		$login=Mage::getModel('taobaologin/login');
		$login->addCustomer($users['firstname'],$users['lastname'],$users['email'],$users['password']);
		$url=$login->getHomeUrl();
		$login->toRediect($url);	
	}

}