<?php
/*
 * Arcanum Dev PayPal Enabler
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
 * @category	Magento Checkout/Shopping Cart Extension
 * @package		Paypal Currency Enabler
 * @author		Amon Antiga 2012/02/26
 * @copyright	Copyright (c) 2012 Arcanum Dev. Y.K.
 * @license		http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Arcanumdev_Paypalenabler_Model_Standard extends Mage_Paypal_Model_Standard{protected $_code=Mage_Paypal_Model_Config::METHOD_WPS;protected $_formBlockType='paypal/standard_form';protected $_infoBlockType='paypal/payment_info';protected $_isInitializeNeeded=true;protected $_canUseInternal=false;protected $_canUseForMultishipping=false;protected $_config=null;public function canUseForCurrency($currencyCode){return $this->getConfig()->isCurrencyCodeSupported($currencyCode);}public function getSession(){return Mage::getSingleton('paypal/session');}public function getCheckout(){return Mage::getSingleton('checkout/session');}public function getQuote(){return $this->getCheckout()->getQuote();}public function createFormBlock($name){$block=$this->getLayout()->createBlock('paypal/standard_form', $name)->setMethod('paypal_standard')->setPayment($this->getPayment())->setTemplate('paypal/standard/form.phtml');return $block;}public function getOrderPlaceRedirectUrl(){return Mage::getUrl('paypal/standard/redirect', array('_secure'=>true));}public function getStandardCheckoutFormFields(){$orderIncrementId=$this->getCheckout()->getLastRealOrderId();$order=Mage::getModel('sales/order')->loadByIncrementId($orderIncrementId);$api=Mage::getModel('paypal/api_standard')->setConfigObject($this->getConfig());$api->setOrderId($orderIncrementId)->setCurrencyCode($order->getOrderCurrencyCode())->setOrder($order)->setNotifyUrl(Mage::getUrl('paypal/ipn/'))->setReturnUrl(Mage::getUrl('paypal/standard/success'))->setCancelUrl(Mage::getUrl('paypal/standard/cancel'));$isOrderVirtual=$order->getIsVirtual();$address=$isOrderVirtual ? $order->getBillingAddress():$order->getShippingAddress();if ($isOrderVirtual){$api->setNoShipping(true);}elseif ($address->validate()){$api->setAddress($address);}$api->setPaypalCart(Mage::getModel('paypal/cart', array($order)))->setIsLineItemsEnabled($this->_config->lineItemsEnabled) ;$api->setCartSummary($this->_getAggregatedCartSummary());$result=$api->getStandardCheckoutRequest();return $result;}public function initialize($paymentAction, $stateObject){$state=Mage_Sales_Model_Order::STATE_PENDING_PAYMENT;$stateObject->setState($state);$stateObject->setStatus('pending_payment');$stateObject->setIsNotified(false);}public function getConfig(){if (null === $this->_config){$params=array($this->_code);if ($store=$this->getStore()){$params[]=is_object($store) ? $store->getId():$store;}$this->_config=Mage::getModel('paypal/config', $params);}return $this->_config;}public function isAvailable($quote=null){if (parent::isAvailable($quote) && $this->getConfig()->isMethodAvailable()){return true;}return false;}public function getConfigData($field, $storeId=null){return $this->getConfig()->$field;}private function _getAggregatedCartSummary(){if ($this->_config->lineItemsSummary){return $this->_config->lineItemsSummary;}return Mage::app()->getStore($this->getStore())->getFrontendName();}}