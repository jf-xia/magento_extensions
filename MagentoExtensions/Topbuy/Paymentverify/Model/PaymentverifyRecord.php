<?php

class Topbuy_Paymentverify_Model_PaymentverifyRecord extends Mage_Core_Model_Abstract
{
	protected $_payment_uuid;
	
	public function getUUID()
	{
		return $_payment_uuid; 
		}
		
	public function setUUID($_uuid)
	{
		$this->_payment_uuid = $_uuid;
		}
		
    protected function _construct(){

       $this->_init("paymentverify/paymentverifyrecord");

    }

}
	 