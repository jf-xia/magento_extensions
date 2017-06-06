<?php

/*
 * Magento EsayCheckout Extension
 *
 * @copyright:	EcommerceTeam (http://www.ecommerce-team.com)
 * @version:	1.0
 *
 */

class EcommerceTeam_EasyCheckout_Block_Onepage_Billing extends EcommerceTeam_EasyCheckout_Block_Onepage_Abstract{
	
	protected $prefix = 'billing';
	
    public function getCountries()
    {
        return Mage::getResourceModel('directory/country_collection')->loadByStore();
    }
	
    function getAddress() {
        return $this->getQuote()->getBillingAddress();
    }

    public function getFirstname()
    {
        $firstname = $this->getAddress()->getFirstname();
        if (empty($firstname) && $this->getQuote()->getCustomer()) {
            return $this->getQuote()->getCustomer()->getFirstname();
        }
        return $firstname;
    }

    public function getLastname()
    {
        $lastname = $this->getAddress()->getLastname();
        if (empty($lastname) && $this->getQuote()->getCustomer()) {
            return $this->getQuote()->getCustomer()->getLastname();
        }
        return $lastname;
    }

}
