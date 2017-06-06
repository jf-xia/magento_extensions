<?php
    class Topbuy_Paymentverify_Model_Mysql4_PaymentverifyRecord extends Mage_Core_Model_Mysql4_Abstract
    {
        protected function _construct()
        {
            $this->_init("paymentverify/paymentverifyrecord", "id_paymentverify");
           // $this->_isPkAutoIncrement = false;
        }
        
    }
	 