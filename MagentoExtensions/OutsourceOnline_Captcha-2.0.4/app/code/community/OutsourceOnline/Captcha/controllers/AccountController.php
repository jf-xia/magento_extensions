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
// include_once "Mage/Customer/controllers/AccountController.php";
require_once(Mage::getModuleDir('controllers', 'Mage_Customer')."/AccountController.php");
class OutsourceOnline_Captcha_AccountController extends Mage_Customer_AccountController
{
    public function createPostAction()
    {
        if (Mage::getStoreConfig("OutsourceOnline_Captcha/captcha/customer"))
        { // check that captcha is actually enabled

            $privatekey = Mage::getStoreConfig("OutsourceOnline_Captcha/setup/private_key");
            // check response
             $resp = Mage::helper("outsourceonline_captcha")->validate();
			

            //validate botscout
			Mage::helper("outsourceonline_captcha")->validateBotScout(Mage::getSingleton('core/app')->getRequest()->getParam('email'));
			if ($resp == true)
            { // if captcha response is correct, use core functionality
                parent::createPostAction();
            }
            else
            {
                $this->_getSession()->addError($this->__('Your CAPTCHA entry is incorrect. Please try again.'));
                $this->_getSession()->setCustomerFormData($this->getRequest()->getPost());
                $this->_redirectReferer();
                return;
            }
        }
        else
        { // if captcha is not enabled, use core function
            parent::createPostAction();
        }
    }
}
?>
