<?php

class NeedTool_OfflineM1_Model_Payment extends Mage_Payment_Model_Method_Abstract
{
    protected $_code  = 'offlinem1_payment';
    protected $_formBlockType = 'offlinem1/form';

    protected $_order = null;
    
    public function getPayimgurl()
    {
        return $this->getConfigData('payimgurl');
    }
    
    public function getPayinfo()
    {
        return $this->getConfigData('payinfo');
    }
    
    public function isAvailable($quote=null)
    {
    		Mage::log("offlinem1 payment isAvailable");
        if (is_null($quote)) {
           return false;
        }
		//		Mage::log($quote->getData());
				$address = $quote->getShippingAddress();
				if($address){
					$shippingmethod = $address->getData("shipping_method");
					Mage::log($shippingmethod);
	        if (strpos($shippingmethod, 'cod') !== false) {
							return true;
					}
				}

        return false;
    }

}