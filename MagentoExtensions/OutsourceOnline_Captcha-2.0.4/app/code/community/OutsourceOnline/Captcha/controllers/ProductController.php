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
 //include_once "Mage/Sendfriend/controllers/ProductController.php";
require_once(Mage::getModuleDir('controllers', 'Mage_Sendfriend')."/ProductController.php");
class OutsourceOnline_Captcha_ProductController extends Mage_Sendfriend_ProductController
{
    public function sendmailAction()
    {
        if( !(Mage::getStoreConfig("OutsourceOnline_Captcha/captcha/when_loggedin")  && (Mage::getSingleton('customer/session')->isLoggedIn())) )
        {
            if (Mage::getStoreConfig("OutsourceOnline_Captcha/captcha/sendfriend"))
            {
               
                // check response
                 $resp = Mage::helper("outsourceonline_captcha")->validate();
				
                $data = $this->getRequest()->getPost();
				//validate botscout
				$sender = Mage::helper("outsourceonline_captcha")->validateBotScout(Mage::getSingleton('core/app')->getRequest()->getParam('sender'));
				$XNAME = $sender['email'];
				Mage::helper("outsourceonline_captcha")->validateBotScout($XNAME);
				
                if ($resp == true)
                { // if captcha response is correct, use core functionality
                    parent::sendmailAction();
                }
                else
                { // if captcha response is incorrect, reload the page
                    Mage::getSingleton('catalog/session')->addError($this->__('Your CAPTCHA entry is incorrect. Please try again.'));
                    Mage::getSingleton('catalog/session')->setFormData($data);
                    $this->_redirectReferer();
                    return;
                }
            }
            else
            { // if captcha is not enabled, use core function alone
                parent::sendmailAction();
            }
        }
        else
        { // if captcha is not enabled, use core function alone
            parent::sendmailAction();
        }
    }
}
?>