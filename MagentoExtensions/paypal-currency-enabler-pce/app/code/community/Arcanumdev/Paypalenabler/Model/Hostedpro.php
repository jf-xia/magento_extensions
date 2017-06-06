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
class Arcanumdev_Paypalenabler_Model_Hostedpro extends Mage_Paypal_Model_Hostedpro{const BM_BUTTON_CODE='TOKEN';const BM_BUTTON_TYPE='PAYMENT';const BM_BUTTON_METHOD='BMCreateButton';protected $_code=Mage_Paypal_Model_Config::METHOD_HOSTEDPRO;protected $_formBlockType='paypal/hosted_pro_form';protected $_infoBlockType='paypal/hosted_pro_info';protected $_canUseInternal=false;protected $_canUseForMultishipping=false;protected $_canSaveCc =false;protected $_isInitializeNeeded=true;public function getAllowedCcTypes(){return true;}public function getMerchantCountry(){return $this->_pro->getConfig()->getMerchantCountry();}public function validate(){return true;}public function initialize($paymentAction,$stateObject){switch ($paymentAction){case Mage_Paypal_Model_Config::PAYMENT_ACTION_AUTH: case Mage_Paypal_Model_Config::PAYMENT_ACTION_SALE: $payment=$this->getInfoInstance();$order=$payment->getOrder();$order->setCanSendNewEmailFlag(false);$payment->setAmountAuthorized($order->getTotalDue());$payment->setAmountAuthorized($order->getTotalDue());$this->_setPaymentFormUrl($payment);$stateObject->setState(Mage_Sales_Model_Order::STATE_PENDING_PAYMENT);$stateObject->setStatus('pending_payment');$stateObject->setIsNotified(false);break;default: break;}}protected function _setPaymentFormUrl(Mage_Payment_Model_Info $payment){$request=$this->_buildFormUrlRequest($payment);$response=$this->_sendFormUrlRequest($request);if ($response){$payment->setAdditionalInformation('secure_form_url',$response);}else{Mage::throwException('Cannot get secure form URL from PayPal');}}protected function _buildFormUrlRequest(Mage_Payment_Model_Info $payment){$request=$this->_buildBasicRequest() ->setOrder($payment->getOrder()) ->setPaymentMethod($this);return $request;}protected function _sendFormUrlRequest(Mage_Paypal_Model_Hostedpro_Request $request){$api=$this->_pro->getApi();$response=$api->call(self::BM_BUTTON_METHOD,$request->getRequestData());if (!isset($response['EMAILLINK'])){return false;}return $response['EMAILLINK'];}protected function _buildBasicRequest(){$request=Mage::getModel('paypal/hostedpro_request');$request->setData(array( 'METHOD'=>self::BM_BUTTON_METHOD,'BUTTONCODE'=>self::BM_BUTTON_CODE,'BUTTONTYPE'=>self::BM_BUTTON_TYPE ));return $request;}public function getReturnUrl($storeId=null){return $this->_getUrl('paypal/hostedpro/return',$storeId);}public function getNotifyUrl($storeId=null){return $this->_getUrl('paypal/ipn',$storeId,false);}public function getCancelUrl($storeId=null){return $this->_getUrl('paypal/hostedpro/cancel',$storeId);}protected function _getUrl($path,$storeId,$secure=null){$store=Mage::app()->getStore($storeId);return Mage::getUrl($path,array( "_store"=>$store,"_secure"=>is_null($secure) ? $store->isCurrentlySecure() : $secure ));}}