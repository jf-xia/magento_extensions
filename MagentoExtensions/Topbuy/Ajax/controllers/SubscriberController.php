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
 * @package     Mage_Newsletter
 * @copyright   Copyright (c) 2011 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Newsletter subscribe controller
 *
 * @category    Mage
 * @package     Mage_Newsletter
 * @author      Magento Core Team <core@magentocommerce.com>
 */
//require_once 'Mage/Newsletter/controllers/SubscriberController.php';
//class Topbuy_Ajax_SubscriberController extends Mage_Newsletter_SubscriberController
//{
//    /**
//      * New subscription action
//      */
//    public function newAction()
//    {
//        if ($this->getRequest()->isPost() && $this->getRequest()->getPost('email')) {
//            $session            = Mage::getSingleton('core/session');
//            $customerSession    = Mage::getSingleton('customer/session');
//            $email              = (string) $this->getRequest()->getPost('email');
//            $subscriberType     = (int) $this->getRequest()->getPost('pref');
////Mage::log($subscriberType);
////Mage::log("~~~~~~~~~~~~~~~~~~~~~");
////            try {
//                if (!Zend_Validate::is($email, 'EmailAddress')) {
//                    $message = $this->__('Please enter a valid email address.');
//                }
//
//                if (Mage::getStoreConfig(Mage_Newsletter_Model_Subscriber::XML_PATH_ALLOW_GUEST_SUBSCRIBE_FLAG) != 1 && 
//                    !$customerSession->isLoggedIn()) {
//                    $message = $this->__('Sorry, but administrator denied subscription for guests. Please <a href="%s">register</a>.', Mage::helper('customer')->getRegisterUrl());
//                }
//
//                $ownerId = Mage::getModel('customer/customer')
//                        ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
//                        ->loadByEmail($email)
//                        ->getId();
//                if ($ownerId !== null && $ownerId != $customerSession->getId()) {
//                    $message = $this->__('This email address is already assigned to another user.');
//                }
//
//                $status = Mage::getModel('newsletter/subscriber')->subscribe($email,$subscriberType);
//                if ($status == Mage_Newsletter_Model_Subscriber::STATUS_NOT_ACTIVE) {
////                    $session->addSuccess($this->__('Confirmation request has been sent.'));
//                    $message = "OK";
//                }
//                else {
////                    $session->addSuccess($this->__('Thank you for your subscription.'));
//                    $message = "OK";
//                }
////            }
////            catch (Mage_Core_Exception $e) {
////                $message = $this->__('There was a problem with the subscription: %s', $e->getMessage());
////            }
////            catch (Exception $e) {
////                $message = $this->__('There was a problem with the subscription.');
////            }
//                $message.='1111';
//            $session->clear();
//            $subscriber_arr = array(
//                'email' => $email,
//                'type' => $subscriberType,
//                'status' => $message,
//            );
//        }	 
//        echo json_encode($subscriber_arr);
//    }
//
//    /**
//     * Subscription confirm action
//     */
//    public function confirmAction()
//    {
//        $id    = (int) $this->getRequest()->getParam('id');
//        $code  = (string) $this->getRequest()->getParam('code');
//
//        if ($id && $code) {
//            $subscriber = Mage::getModel('newsletter/subscriber')->load($id);
//            $session = Mage::getSingleton('core/session');
//
//            if($subscriber->getId() && $subscriber->getCode()) {
//                if($subscriber->confirm($code)) {
//                    $session->addSuccess($this->__('Your subscription has been confirmed.'));
//                } else {
//                    $session->addError($this->__('Invalid subscription confirmation code.'));
//                }
//            } else {
//                $session->addError($this->__('Invalid subscription ID.'));
//            }
//        }
//
//        $this->_redirectUrl(Mage::getBaseUrl());
//    }
//
//    /**
//     * Unsubscribe newsletter
//     */
//    public function unsubscribeAction()
//    {
//        $id    = (int) $this->getRequest()->getParam('id');
//        $code  = (string) $this->getRequest()->getParam('code');
//
//        if ($id && $code) {
//            $session = Mage::getSingleton('core/session');
//            try {
//                Mage::getModel('newsletter/subscriber')->load($id)
//                    ->setCheckCode($code)
//                    ->unsubscribe();
//                $session->addSuccess($this->__('You have been unsubscribed.'));
//            }
//            catch (Mage_Core_Exception $e) {
//                $session->addException($e, $e->getMessage());
//            }
//            catch (Exception $e) {
//                $session->addException($e, $this->__('There was a problem with the un-subscription.'));
//            }
//        }
//        $this->_redirectReferer();
//    }
//}
