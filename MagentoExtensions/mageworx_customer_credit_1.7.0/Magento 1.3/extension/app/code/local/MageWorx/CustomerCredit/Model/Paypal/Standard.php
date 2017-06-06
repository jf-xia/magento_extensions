<?php

class MageWorx_CustomerCredit_Model_Paypal_Standard extends Mage_Paypal_Model_Standard
{

    public function getStandardCheckoutFormFields()
    {	
    	$rArr = parent::getStandardCheckoutFormFields();
    	
    	$orderIncrementId = $this->getCheckout()->getLastRealOrderId();
        $order = Mage::getModel('sales/order')->loadByIncrementId($orderIncrementId);
        $credit = $order->getBaseCustomerCreditAmount();
        
  		if (isset($rArr['discount_amount_cart'])){
  			$rArr['discount_amount_cart'] = sprintf('%.2f', $rArr['discount_amount_cart'] + $credit);
  		} else {
  			$rArr['discount_amount_cart'] = sprintf('%.2f', $credit);
  		}
    	
        return $rArr;
    }
}