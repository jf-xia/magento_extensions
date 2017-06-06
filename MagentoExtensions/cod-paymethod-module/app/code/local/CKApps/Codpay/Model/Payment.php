<?php

/**
*
* Copyright CKApps.com
* email: app@ckapps.com
*
*/


class CKApps_Codpay_Model_Payment extends Mage_Payment_Model_Method_Abstract
{
	  protected $_code  = 'codpay_payment';
    protected $_formBlockType = 'codpay/form';
    
    	// Alipay return codes of payment
	const RETURN_CODE_ACCEPTED      = 'Success';
	const RETURN_CODE_TEST_ACCEPTED = 'Success';
	const RETURN_CODE_ERROR         = 'Fail';

		/**
	* declare the Action when customer click placeorder in checkoutpage
	*
	*/
  public function getOrderPlaceRedirectUrl()
	{
		return Mage::getUrl('codpay/payment/redirect');
	}
	
		/**
	* get orderstatus set in admin module
	*
	*/
  public function getStatus()
  {
  	return $this->getConfigData('order_status_new');}
}
