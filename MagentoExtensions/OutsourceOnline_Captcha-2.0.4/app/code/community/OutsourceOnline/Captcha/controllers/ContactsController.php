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
 //include_once "Mage/Contacts/controllers/IndexController.php";
require_once(Mage::getModuleDir('controllers', 'Mage_Contacts')."/IndexController.php");
class OutsourceOnline_Captcha_ContactsController extends Mage_Contacts_IndexController
{
    
	
	public function postAction()
    {
		
		
        if( !(Mage::getStoreConfig("OutsourceOnline_Captcha/captcha/when_loggedin")  && (Mage::getSingleton('customer/session')->isLoggedIn())) )
        {
			
            if (Mage::getStoreConfig("OutsourceOnline_Captcha/captcha/contacts"))
            {
                
				//echo  "<pre>";print_r($_POST);echo "</pre>";
                // check response
                $resp = Mage::helper("outsourceonline_captcha")->validate();
				//validate botscout
				Mage::helper("outsourceonline_captcha")->validateBotScout(Mage::getSingleton('core/app')->getRequest()->getParam('email'));
                if ($resp == true)
                { // if captcha response is correct, use core functionality
                    parent::postAction();
                }
                else
                { // if captcha response is incorrect, reload the page

                    Mage::getSingleton('customer/session')->addError(Mage::helper('contacts')->__('Your CAPTCHA entry is incorrect. Please try again.'));

                    $_SESSION['contact_comment'] = $_POST['comment'];
                    $_SESSION['contact_name'] = $_POST['name'];
                    $_SESSION['contact_email'] = $_POST['email'];
                    $_SESSION['contact_telephone'] = $_POST['telephone'];

                    $this->_redirect('contacts/');
                    return;
                }
            }
            else
            { // if captcha is not enabled, use core function alone
                parent::postAction();
            }
        }
        else
        { // if captcha is not enabled, use core function alone
            parent::postAction();
        }
    }    
}
?>