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

class OutsourceOnline_Captcha_AjaxController extends  Mage_Core_Controller_Front_Action
{
	public function indexAction(){
		/*
		echo "Controller:".Mage::getSingleton('core/app')->getRequest()->getParam('form_key'); 
		echo "Controller:".$this->getRequest()->getParam('form_key');
		echo "< pre >";print_r($this->getRequest());    echo "< /pre >";*/
		$this->loadLayout()->renderLayout();
	}
	public function freshcaptchaAction()
	{
		Mage::helper("outsourceonline_captcha")->display();
	}
	public function validateCaptchaAction()
	{
		
		
		echo Mage::helper("outsourceonline_captcha")->validate()?'correct':'wrong';
	}
}
