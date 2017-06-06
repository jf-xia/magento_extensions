<?php
 /*
 * Arcanum Dev AwardPoints
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to arcanumdev@wafunotamago.com so we can send you a copy immediately.
 *
 * @category   Magento Sale Extension
 * @package    AwardPoints
 * @copyright  Copyright (c) 2012 Arcanum Dev. Y.K. (http://arcanumdev.wafunotamago.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 class Arcanumdev_Awardpoints_IndexController extends Mage_Core_Controller_Front_Action{public function testAction(){echo '<pre>';$order=Mage::getModel('sales/order')->load(61);$order_shipping_address=Mage::getModel('sales/order_address')->load($order->getShippingAddressId());$customer_shipping_address=$order_shipping_address->getCustomerAddressId();$order_billing_address=Mage::getModel('sales/order_address')->load($order->getBillingAddressId());$customer_billing_address=$order_billing_address->getCustomerAddressId();echo ">>>>>>>>>>>>>>>>>>>> END OF THE ORDER <<<<<<<<<<<<<<<<<<<<<<<";$quote_tmp=Mage::getModel('sales/quote');$quote=Mage::getModel('sales/quote')->load($order->getQuoteId());foreach($quote->getAddressesCollection() as $my_quote){echo '>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> <br />';if($my_quote->getAddressType()=='shipping' && $my_quote->getCustomerAddressId()==$customer_shipping_address){$quote->setShippingAddress($my_quote);$quote_tmp->setShippingAddress($my_quote);} elseif($my_quote->getAddressType()=='billing' && $my_quote->getCustomerAddressId()==$customer_billing_address){$quote->setBillingAddress($my_quote);$quote_tmp->setBillingAddress($my_quote);}}$order->setQuote($quote_tmp);$address=$order->getQuote()->getShippingAddress();echo Mage::helper('awardpoints/data')->getPointsOnOrder($order);die;die;$convertQuote=Mage::getModel('sales/convert_order');$quote_tmp=$convertQuote->toQuoteShippingAddress($order);$address=$convertQuote->toQuoteShippingAddress($order);$quote_tmp->setBillingAddress($address);$quote_tmp->setShippingAddress($address);foreach ($order->getAllItems() as $item){$quote_tmp->addItem($convertQuote->itemToShipmentItem($item));}print_r($quote_tmp->getShippingAddress()->getBaseSubtotal());die;$quote=Mage::getModel('sales/quote')->load($order->getQuoteId());$shippingAddress=Mage::getModel('sales/quote_address')->setData($order->getShippingAddress());$quote->setShippingAddress($shippingAddress);$quote_order=Mage::getModel('sales/convert_order')->toQuote($order);print_r($quote_order->getShippingAddress()->getBaseSubtotal());die;$order->setQuote($quote);print_r($order);die;echo $order->getData('base_subtotal');echo '<br />';echo Mage::helper('awardpoints/data')->getPointsOnOrder($order);die;}public function indexAction(){if($this->getRequest()->isPost() && $this->getRequest()->getPost('email')){$session=Mage::getSingleton('core/session');$email  =trim((string) $this->getRequest()->getPost('email'));$name= trim((string) $this->getRequest()->getPost('name'));$customerSession=Mage::getSingleton('customer/session');try{if(!Zend_Validate::is($email, 'EmailAddress')){Mage::throwException($this->__('Please enter a valid email address.'));}if($name==''){Mage::throwException($this->__('Please enter your friend name.'));}$referralModel=Mage::getModel('awardpoints/referral');$customer=Mage::getModel('customer/customer')->setWebsiteId(Mage::app()->getStore()->getWebsiteId())->loadByEmail($email);if($referralModel->isSubscribed($email) || $customer->getEmail()==$email){Mage::throwException($this->__('This email has been already submitted.'));} else{if($referralModel->subscribe($customerSession->getCustomer(), $email, $name)){$session->addSuccess($this->__('This email was successfully invited.'));} else{Mage::throwException($this->__('There was a problem with the invitation.'));}}}catch (Mage_Core_Exception $e){$session->addException($e, $this->__('%s', $e->getMessage()));}catch (Exception $e){$session->addException($e, $this->__('There was a problem with the invitation.'));}}$this->loadLayout();$this->renderLayout();}public function referralAction(){$this->indexAction();}public function pointsAction(){$this->indexAction();}public function goReferralAction(){$userId=(int) $this->getRequest()->getParam('referrer');Mage::getSingleton('awardpoints/session')->setReferralUser($userId);$url=Mage::getUrl();$this->getResponse()->setRedirect($url);}public function removequotationAction(){Mage::getSingleton('customer/session')->setProductChecked(0);Mage::helper('awardpoints/event')->setCreditPoints(0);$refererUrl=$this->_getRefererUrl();if(empty($refererUrl)){$refererUrl=empty($defaultUrl) ? Mage::getBaseUrl() : $defaultUrl;}$this->getResponse()->setRedirect($refererUrl);}public function quotationAction(){$session=Mage::getSingleton('core/session');$points_value=$this->getRequest()->getPost('points_to_be_used');if(Mage::getStoreConfig('awardpoints/default/max_point_used_order',Mage::app()->getStore()->getId())){if((int)Mage::getStoreConfig('awardpoints/default/max_point_used_order',Mage::app()->getStore()->getId()) < $points_value){$points_max=(int)Mage::getStoreConfig('awardpoints/default/max_point_used_order',Mage::app()->getStore()->getId());$session->addError($this->__('You tried to use %s loyalty points, but you can use a maximum of %s points per shopping cart.', $points_value, $points_max));$points_value=$points_max;}}$quote_id=Mage::helper('checkout/cart')->getCart()->getQuote()->getId();Mage::getSingleton('customer/session')->setProductChecked(0);Mage::helper('awardpoints/event')->setCreditPoints($points_value);$refererUrl=$this->_getRefererUrl();if(empty($refererUrl)){$refererUrl=empty($defaultUrl) ? Mage::getBaseUrl() : $defaultUrl;}$this->getResponse()->setRedirect($refererUrl);}public function preDispatch(){parent::preDispatch();$action=$this->getRequest()->getActionName();if('referral'==$action){$loginUrl=Mage::helper('customer')->getLoginUrl();if(!Mage::getSingleton('customer/session')->authenticate($this, $loginUrl)){$this->setFlag('', self::FLAG_NO_DISPATCH, true);}}}}