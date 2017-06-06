<?php

class NeedTool_Paymentstub_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getCheckout()
    {
        return Mage::getSingleton('checkout/session');
    }

    public function getQuote()
    {
        return $this->getCheckout()->getQuote();
    }

}