<?php

/*
 * Magento EsayCheckout Extension
 *
 * @copyright:	EcommerceTeam (http://www.ecommerce-team.com)
 * @version:	1.0
 *
 */

class EcommerceTeam_EasyCheckout_Block_Onepage_Shipping extends EcommerceTeam_EasyCheckout_Block_Onepage_Abstract{
	
	protected $prefix = 'shipping';
	
    public function getAddress(){
    	
    	if($this->someAsBilling()){
			
			$session = Mage::getSingleton('customer/session');
			
			if($session->isLoggedIn()){
				if($address = $session->getCustomer()->getDefaultShippingAddress()){
					return $address;
				}
			}
			
		}
		
	    return $this->getQuote()->getShippingAddress();
    	
    }
    
    	
    public function isShow(){
    	
        return !$this->getQuote()->isVirtual();
        
    }
    
    public function someAsBilling(){
    	
    	
    	return $this->helper->shippingSameAsBilling();
    	
    }
    
    public function canShow(){
    	
        if(!$this->getQuote()->isVirtual() && $this->helper->differentShippingEnabled()){
        	
        	return true;
        	
        }
        return false;
    }
}
