<?php

class Topbuy_Getaway_IndexController extends Mage_Core_Controller_Front_Action {

    public function IndexAction() {
        $this->_redirect('getaway/index/land?utm_source=getaway&utm_medium=getaway_draw&utm_content=printbanner&utm_campaign=getaway2012');
//        $this->loadLayout();
//        $this->getLayout()->getBlock("head")->setTitle($this->__("Getaway"));
//        $this->renderLayout();
    }

    public function LandAction() {
        $this->loadLayout();
        $this->getLayout()->getBlock("head")->setTitle($this->__("Getaway"));
        $this->renderLayout();
    }

    public function newAction() {
        if ($this->getRequest()->isPost() && $this->getRequest()->getPost('email')) {
            $customerSession = Mage::getSingleton('customer/session');
            $email = (string) $this->getRequest()->getPost('email');
            $subscriberType = (int) $this->getRequest()->getPost('pref');
            $wherecomefrom = (string) $this->getRequest()->getPost('wherecomefrom');
            if ($subscriberType == null) {
                $subscriberType = 2;
            }
            if ($wherecomefrom == null) {
                $wherecomefrom = '';
            }

            try {
                if (!Zend_Validate::is($email, 'EmailAddress')) {
                    Mage::throwException($message = $this->__('Please enter a valid email address.'));
                }

                if (Mage::getStoreConfig(Mage_Newsletter_Model_Subscriber::XML_PATH_ALLOW_GUEST_SUBSCRIBE_FLAG) != 1 &&
                        !$customerSession->isLoggedIn()) {
                    Mage::throwException($message = $this->__('Sorry, but administrator denied subscription for guests. Please <a href="%s">register</a>.', Mage::helper('customer')->getRegisterUrl()));
                }

                if ($customerSession->isLoggedIn()) {
                    $customer = Mage::getModel('customer/customer')->load($customerSession->getId());
                    $customer->setSubscribetype($subscriberType)->save();
                }

                $ownerId = Mage::getModel('customer/customer')
                        ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
                        ->loadByEmail($email)
                        ->getId();
                if ($ownerId !== null && $ownerId != $customerSession->getId()) {
                    Mage::throwException($message = $this->__('This email address is already assigned to another user.'));
                }
                if (Mage::getModel('newsletter/subscriber')->loadByEmail($email)->hasData()) {
                    Mage::throwException($message = $this->__('This email address is already subscriped.'));
                }

                $status = Mage::getModel('newsletter/subscriber')->subscribe($email, $subscriberType, $wherecomefrom);
                Mage::getModel('getaway/getawayrecord')
                        ->setEmail($this->getRequest()->getPost('email'))
                        ->setFirstname($this->getRequest()->getPost('firstname'))
                        ->setComments($this->getRequest()->getPost('comments'))
                        ->setWherecomefrom($this->getRequest()->getPost('wherecomefrom'))
                        ->setEntrydate(now())
                        ->setIpaddress(Mage::helper('core/http')->getRemoteAddr())
                        ->save();
                if ($status == Mage_Newsletter_Model_Subscriber::STATUS_NOT_ACTIVE) {
                    $message = "OK";
                } else {
                    $message = "OK";
                }
            } catch (Mage_Core_Exception $e) {
                $message = $this->__('There was a problem with the subscription: %s', $e->getMessage());
            } catch (Exception $e) {
                $message = $this->__('There was a problem with the subscription.');
            }
            $subscriber_arr = array(
                'email' => $email,
                'type' => $subscriberType,
                'status' => $message,
            );
        }
        echo json_encode($subscriber_arr);
    }

    public function winiphone5Action() {
        if ($this->getRequest()->isPost() && $this->getRequest()->getPost('email')) {
            $customerSession = Mage::getSingleton('customer/session');
            $email = (string) $this->getRequest()->getPost('email');
            $postcode = (string) $this->getRequest()->getPost('postcode');
            $subscriberType = (int) $this->getRequest()->getPost('pref');
            $wherecomefrom = (string) $this->getRequest()->getPost('wherecomefrom');
            if ($subscriberType == null) {
                $subscriberType = 2;
            }
            if ($wherecomefrom == null) {
                $wherecomefrom = '';
            }
            $client = new Zend_Http_Client(
                            'http://www.livingstyles.com.au/tbcart/pc/global_tab_ajax.asp?request_action=registerFromTopBuy&email='.$email.'&postcode='.$postcode,
                            array(
                                'maxredirects' => 0,
                                'timeout' => 30
                            )
            );
            $client->setConfig(array('strictredirects' => true));
            $response = $client->request(Zend_Http_Client::POST); 

            try {
                if (!Zend_Validate::is($email, 'EmailAddress')) {
                    Mage::throwException($message = $this->__('Please enter a valid email address.'));
                }

                if (Mage::getStoreConfig(Mage_Newsletter_Model_Subscriber::XML_PATH_ALLOW_GUEST_SUBSCRIBE_FLAG) != 1 &&
                        !$customerSession->isLoggedIn()) {
                    Mage::throwException($message = $this->__('Sorry, but administrator denied subscription for guests. Please <a href="%s">register</a>.', Mage::helper('customer')->getRegisterUrl()));
                }

                if ($customerSession->isLoggedIn()) {
                    $customer = Mage::getModel('customer/customer')->load($customerSession->getId());
                    $customer->setSubscribetype($subscriberType)->save();
                }

                $ownerId = Mage::getModel('customer/customer')
                        ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
                        ->loadByEmail($email)
                        ->getId();
                if ($ownerId !== null && $ownerId != $customerSession->getId()) {
                    Mage::throwException($message = $this->__('This email address is already assigned to another user.'));
                }
                if (Mage::getModel('newsletter/subscriber')->loadByEmail($email)->hasData()) {
                    Mage::throwException($message = $this->__('This email address is already subscriped.'));
                }
                $status = Mage::getModel('newsletter/subscriber')->subscribe($email, $subscriberType, $wherecomefrom);
                Mage::getModel('getaway/getawayrecord')
                        ->setEmail($this->getRequest()->getPost('email'))
                        ->setFirstname($this->getRequest()->getPost('firstname'))
                        ->setComments($this->getRequest()->getPost('comments'))
                        ->setWherecomefrom($this->getRequest()->getPost('wherecomefrom'))
                        ->setEntrydate(now())
                        ->setIpaddress(Mage::helper('core/http')->getRemoteAddr())
                        ->save();
                
                if ($status == Mage_Newsletter_Model_Subscriber::STATUS_NOT_ACTIVE) {
                    $message = "OK";
                } else {
                    $message = "OK";
                }
            } catch (Mage_Core_Exception $e) {
                $message = $this->__('There was a problem with the subscription: %s', $e->getMessage());
            } catch (Exception $e) {
                $message = $this->__('There was a problem with the subscription.');
            }
            $subscriber_arr = array(
                'email' => $email,
                'type' => $subscriberType,
                'status' => $message,
            );
        }
        echo json_encode($subscriber_arr);
    }

}