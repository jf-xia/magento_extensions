<?php
/**
*
* Copyright CKApps.com
* email: app@ckapps.com
*
*/
class CKApps_Codpay_PaymentController extends Mage_Core_Controller_Front_Action
{
	/**
	* Order instance
	*/
	protected $_order;

	/**
	* get order info
	*/
	public function getOrder()
	{
		if ($this->_order == null)
		{
			$session = Mage::getSingleton('checkout/session');
			$this->_order = Mage::getModel('sales/order');
			$this->_order->loadByIncrementId($session->getLastRealOrderId());
		}
		return $this->_order;
	}


	/**
	* When a customer chooses codpay on Checkout/Payment page
	*
	*/
	public function redirectAction()
	{
		$session = Mage::getSingleton('checkout/session');
		$session->setCodpayPaymentQuoteId($session->getQuoteId());
		$order = $this->getOrder();
		$status=Mage::getModel('codpay/payment')->getStatus();
		$order->addStatusToHistory(
		$status,
		Mage::helper('codpay')->__('Customer select COD paymethod!')
		);
		//$order->sendNewOrderEmail();
		$order->save();
		$session->unsQuoteId();
		$this->_redirect('checkout/onepage/success');
	}



}
