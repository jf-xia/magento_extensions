<?php 
require_once "Mage/Customer/controllers/AccountController.php";
class Topbuy_Customer_AccountController extends Mage_Customer_AccountController
{
	 /**
	 * Rewrite loginPostAction
     * Login post action
     */
    public function loginPostAction()
    {
        if ($this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }
        $session = $this->_getSession();

        if ($this->getRequest()->isPost()) {
            $login = $this->getRequest()->getPost('login');
            if (!empty($login['username']) && !empty($login['password'])) {
                try {
                    $session->login($login['username'], $login['password']);
                    if ($session->getCustomer()->getIsJustConfirmed()) {
                        $this->_welcomeCustomer($session->getCustomer(), true);
                    }
					 
                } catch (Mage_Core_Exception $e) {
					
					try{ 
						$guest_login_flag = 1;
						
					    $customer_email = $login['username'];
						$customer = Mage::getModel("customer/customer")
								->setWebsiteId(Mage::app()->getWebsite()->getId())
								->loadByEmail($customer_email);
						if ($customer->hasData()){
							//check customer has order or not
							//if no, login customer directly
							$orderCollection = Mage::getModel('sales/order')->getCollection()->addFieldToFilter('customer_id',$customer->getId());							 
							if (!$orderCollection->getSize())
							{
								Mage::getModel("customer/session")->setCustomer($customer);
								Mage::dispatchEvent('customer_login', array('customer'=>$customer));
								$session = Mage::getSingleton("customer/session");
								if ($session->getCustomer()->getIsJustConfirmed()) {
									$this->_welcomeCustomer($session->getCustomer(), true);
								}      
								$guest_login_flag = 0;
								} 
							 
							} 
						if ($guest_login_flag == 1)
						{
							//can not login then return to original error
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
								$session->addError($message);
								$session->setUsername($login['username']);							
						}
					
					}
					 catch (Mage_Core_Exception $e1) {						 
						
					 }
					 
                   
                } catch (Exception $e) {
                    // Mage::logException($e); // PA DSS violation: this exception log can disclose customer password
                }
            } else {
                $session->addError($this->__('Login and password are required.'));
            }
        }

        $this->_loginPostRedirect();
    }
	
}