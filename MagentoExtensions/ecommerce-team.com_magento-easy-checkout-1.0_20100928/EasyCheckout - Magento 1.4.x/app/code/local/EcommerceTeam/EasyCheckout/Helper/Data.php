<?php

/*
 * Magento EsayCheckout Extension
 *
 * @copyright:	EcommerceTeam (http://www.ecommerce-team.com)
 * @version:	1.0
 *
 */

class EcommerceTeam_EasyCheckout_Helper_Data extends Mage_Core_Helper_Abstract{
	
	protected $mode;
	protected $_config_cache = array();
	protected $_onepage;
	protected $_checkout;
	
	public function getCheckout()
    {
        if (empty($this->_checkout)) {
            $this->_checkout = Mage::getSingleton('checkout/session');
        }
        return $this->_checkout;
    }
	
	public function getOnepage(){
		if (is_null($this->_onepage)) {
            $this->_onepage = Mage::getSingleton('checkout/type_onepage');
        }
		return $this->_onepage;
	}
	
    public function getConfigData($xmlnode){
    	
    	if(!isset($this->_config_cache[$xmlnode])){
    		$this->_config_cache[$xmlnode] = Mage::getStoreConfig('checkout/'.$xmlnode);
    	}
    	return $this->_config_cache[$xmlnode];
	}
	
	public function getDefaultCountryId(){
		
	    return Mage::getStoreConfig('general/country/default');
	    
	}
	
	public function initSingleShippingMethod($address){
		
		$rates = $address->getGroupedAllShippingRates();
		
        if(count($rates) == 1){
						
			foreach($rates as $rate_code=>$methods){
				
				if(count($methods) == 1){
					foreach($methods as $method){
						
						$address->setShippingMethod($method->getCode());
					}
				}
				
				break;
			}
			
		}
		
		return $address;
		
	}
	
	public function differentShippingEnabled(){
		
		return (bool)$this->getConfigData('options/different_shipping_enabled');
		
	}
	
	public function couponEnabled(){
		
		return (bool)$this->getConfigData('options/coupon_enabled');
	}
	public function showSubscribe(){
		
		if((bool)$this->getConfigData('options/subscibe_enabled')){
			
			$session = Mage::getSingleton('customer/session');
			
			if($session->isLoggedIn()){
				
				if(Mage::getModel('newsletter/subscriber')->loadByCustomer($session->getCustomer())->getStatus() == 1){
					
					return false;
					
				}
				
			}
			
			return true;
			
		}
	}
	
	
	
	public function shippingSameAsBilling(){
		
		
		if($this->differentShippingEnabled()){
			
			if(is_null($this->getCheckout()->getShippingSameAsBilling())){
	    		return true;
	    	}
	    	
	    	return (bool)($this->getCheckout()->getShippingSameAsBilling());
	    	
    	}else{
    		return true;
    	}
		
	}
	
}
