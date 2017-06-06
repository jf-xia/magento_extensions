<?php
/**
 * Magento
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
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Customer
 * @copyright   Copyright (c) 2011 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Customer account controller
 *
 * @category   Mage
 * @package    Mage_Customer
 * @author      Magento Core Team <core@magentocommerce.com>
 */

require_once 'Mage/Customer/controllers/AccountController.php';

class Topbuy_Ajax_AccountController extends Mage_Customer_AccountController
{
    public function loginbymAction() {
        $customer_email = $this->getRequest()->getParam('email');
        $customer = Mage::getModel("customer/customer")
                ->setWebsiteId(Mage::app()->getWebsite()->getId())
                ->loadByEmail($customer_email);
        if ($customer->hasData()){
            Mage::getModel("customer/session")->setCustomer($customer);
            Mage::dispatchEvent('customer_login', array('customer'=>$customer));
        }
        $session = Mage::getSingleton("customer/session");
        if($session->isLoggedIn()){
            $this->_redirect('checkout/cart');
        }else {
            $this->_redirect('customer/account/login');
        }        
    }
    
    //http://www.topbuy.com.au/ajax/account/pwdreset?email=XXXXXXXXXXX&pwd=XXXXXXX
    public function pwdresetAction() {
        $customer_email = $this->getRequest()->getParam('email');
        $customer_password = $this->getRequest()->getParam('pwd');
        $customer = Mage::getModel("customer/customer")
                ->setWebsiteId(Mage::app()->getWebsite()->getId())
                ->loadByEmail($customer_email);
        if ($customer->hasData()){
            $customer->setPassword($customer_password)->save();
            echo 'Success! for Email: '.$customer_email.' and Password: '.$customer->getPassword();
        } else {
            echo 'error! try it again';
        }
    }
    
    public function loginAjaxAction()
    {
        if ($this->_getSession()->isLoggedIn()) {
            //$this->_redirect('*/*/');
            $message="OK";
            $login_arr = array(
                'loginName' => Mage::getSingleton('customer/session')->getCustomer()->getName(),
                'loginStatus' => $message,
            );
            echo json_encode($login_arr);
            return;
        }
        $session = $this->_getSession();

        $login = $this->getRequest()->getPost('login');
        if ($this->getRequest()->isPost()&&!empty($login['username'])) {
            if (!empty($login['username']) && !empty($login['password'])) {
                try {
                    $session->login($login['username'], $login['password']);
//                    if ($session->getCustomer()->getIsJustConfirmed()) {
//                        $this->_welcomeCustomer($session->getCustomer(), true);
//                    }
                    $message = "OK";
                } catch (Mage_Core_Exception $e) {
                    switch ($e->getCode()) {
                        case Mage_Customer_Model_Customer::EXCEPTION_EMAIL_NOT_CONFIRMED:
                            $value = Mage::helper('customer')->getEmailConfirmationUrl($login['username']);
                            $message = Mage::helper('customer')->__('This account is not confirmed. <a href="%s">Click here</a> to resend confirmation email.', $value);
                            break;
                        case Mage_Customer_Model_Customer::EXCEPTION_INVALID_EMAIL_OR_PASSWORD:
                            $message = $e->getMessage();
                            break;
                        default:
                            $message = $e->getMessage();
                    }
//                    $session->addError($message);
                    $session->setUsername($login['username']);
                } catch (Exception $e) {
                            $message = $e->getMessage();
                    // Mage::logException($e); // PA DSS violation: this exception log can disclose customer password
                }
            } else {
//                $session->addError($this->__('Login and password are required.'));
                $message=$this->__('Login and password are required.');
            }
//            $resultMsg="~~~~~~~~~~";
            $login_arr = array(
                'loginName' => Mage::getSingleton('customer/session')->getCustomer()->getName(),
                'loginStatus' => $message,
            );
        }	 
        echo json_encode($login_arr);
        return;
        //$this->_loginPostRedirect();
    }

//http://www1.topbuy.com.au/ajax/account/createaddress/?email=xxx@xx.com&fname=Richard&lname=Liu&password=1234&street1=Str1%2Dxxxx%2D&street2=Str2%2Dxxxx&city=CXXXXX&region=VIC&postcode=2222&country=AU&phone=22223245
    public function createAddressAction()
    {
        $customer_email = $this->getRequest()->getParam('email');  // email adress that will pass by the questionaire 
        $customer_fname = $this->getRequest()->getParam('fname');      // we can set a tempory firstname here 
        $customer_lname = $this->getRequest()->getParam('lname');       // we can set a tempory lastname here 
        $passwordLength = 10;                                         // the lenght of autogenerated password
        $password = $this->getRequest()->getParam('password');          // the lenght of autogenerated password

        $_custom_address = array (
            'firstname' => $this->getRequest()->getParam('fname'),
            'lastname' => $this->getRequest()->getParam('lname'),
            'street' => array (
                '0' => $this->getRequest()->getParam('street1'),
                '1' => $this->getRequest()->getParam('street2'),
            ),

            'city' => $this->getRequest()->getParam('city'),
            'region_id' => '',
            'region' => $this->getRequest()->getParam('region'),
            'postcode' => $this->getRequest()->getParam('postcode'),
            'country_id' => $this->getRequest()->getParam('country'),
            'telephone' => $this->getRequest()->getParam('phone'),
        );
        $customer=Mage::helper('ajax')->createAccount($customer_email,$customer_fname,$customer_lname,$passwordLength,$password,$_custom_address);
        return $customer->getId();
    }

    /**
     * Create customer account action
     */
    public function createajaxAction()
    {
        $session = $this->_getSession();
        $message = "OK";
        $login_arr = array();
        if ($this->getRequest()->isPost()) {
            $errors = array();

            if (!$customer = Mage::registry('current_customer')) {
                $customer = Mage::getModel('customer/customer')->setId(null);
            }
            $customerForm = Mage::getModel('customer/form');
            $customerForm->setFormCode('customer_account_create')
                ->setEntity($customer);

            $customerData = $customerForm->extractData($this->getRequest());

            if ($this->getRequest()->getParam('is_subscribed', false)) {
                $customer->setIsSubscribed(1);
            }
            $customer->getGroupId();
            try {
                $customerErrors = $customerForm->validateData($customerData);
                if ($customerErrors !== true) {
                    $errors = array_merge($customerErrors, $errors);
                } else {
                    $customerForm->compactData($customerData);
                    $customer->setPassword($this->getRequest()->getPost('password'));
                    $customer->setConfirmation($this->getRequest()->getPost('confirmation'));
                    $customerErrors = $customer->validate();
                    if (is_array($customerErrors)) {
                        $errors = array_merge($customerErrors, $errors);
                    }
                }

                $validationResult = count($errors) == 0;

                if (true === $validationResult) {
                    $customer->save();
                    Mage::dispatchEvent('customer_register_success',
                        array('account_controller' => $this, 'customer' => $customer)
                    );
                    $session->setCustomerAsLoggedIn($customer);
                    $customer->sendNewAccountEmail( 'registered', '',Mage::app()->getStore()->getId());
                    $message = "OK";
                } else {
                    $message = "errors";
                    if (is_array($errors)) {
                        foreach ($errors as $errorMessage) {
                            $message = $errorMessage;
                        }
                    } else {
                        $message = 'Invalid customer data';
                    }
                }
            } catch (Mage_Core_Exception $e) {
                if ($e->getCode() === Mage_Customer_Model_Customer::EXCEPTION_EMAIL_EXISTS) {
                    $url = Mage::getUrl('customer/account/forgotpassword');
                    $message = $this->__('There is already an account with this email address. If you are sure that it is your email address, <a href="%s">click here</a> to get your password and access your account.', $url);
                } else {
                    $message = $e->getMessage();
                }
            } catch (Exception $e) {
                $message='Cannot save the customer.';
            }
        } else {
                $message='Try register again.';
        }
        $login_arr = array(
            'loginName' => Mage::getSingleton('customer/session')->getCustomer()->getName(),
            'loginStatus' => $message,
        );
        echo json_encode($login_arr);
        return;
    }
}
