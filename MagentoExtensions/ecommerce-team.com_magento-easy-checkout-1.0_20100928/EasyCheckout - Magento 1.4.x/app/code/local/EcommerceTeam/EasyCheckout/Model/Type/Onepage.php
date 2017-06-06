<?php

/*
 * Magento EsayCheckout Extension
 *
 * @copyright:	EcommerceTeam (http://www.ecommerce-team.com)
 * @version:	1.0
 *
 */

class EcommerceTeam_EasyCheckout_Model_Type_Onepage extends Mage_Checkout_Model_Type_Onepage{
	
	const METHOD_GUEST    = 'guest';
    const METHOD_REGISTER = 'register';
    const METHOD_CUSTOMER = 'customer';
    
    
	protected $_ehelper;
	
	public function getHelper(){
		
		if(is_null($this->_ehelper)){
			$this->_ehelper = Mage::helper('ecommerceteam_echeckout');
		}
		
		return $this->_ehelper;
		
	}
	
	/*public function someAsBilling(){
    	
    	if(is_null($this->getCheckout()->getShippingSameAsBilling())){
    		return true;
    	}
    	
    	return (bool)($this->getCheckout()->getShippingSameAsBilling());
    	
    }*/
	
	public function getConfigData($xpath){
		
		return $this->getHelper()->getConfigData($xpath);
		
	}
	
	public function getDefaultCountryId(){
		
        return $this->getHelper()->getDefaultCountryId();
        
	}
	
	public function getCheckoutMethod()
    {
        if ($this->getCustomerSession()->isLoggedIn()) {
            return self::METHOD_CUSTOMER;
        }
        
        if (!$this->getQuote()->getCheckoutMethod()) {
            if (Mage::helper('checkout')->isAllowedGuestCheckout($this->getQuote())) {
                $this->getQuote()->setCheckoutMethod(self::METHOD_GUEST);
            } else {
                $this->getQuote()->setCheckoutMethod(self::METHOD_REGISTER);
            }
        }
        return $this->getQuote()->getCheckoutMethod();
    }
	
	public function saveCheckoutMethod($method){
		
		$this->getQuote()->setCheckoutMethod($method);
		
	}
	
	public function saveBillingAddress($data, $customerAddressId = false){
		
		
		$result = array('error' => 0, 'message' => array());
		
        if (empty($data)) {
            return array('error' => -1, 'message' => $this->getHelper()->__('Invalid data.'));
        }
		
		
		
        $address = $this->getQuote()->getBillingAddress();
        
        $address->addData($data);
        $address->implodeStreetAddress();
        
        
        
        if($this->getCustomerSession()->isLoggedIn() == false){
        	
        	
        	
        	if($this->_customerEmailExists( $address->getEmail(), Mage::app()->getWebsite()->getId() )){
        		
            	$result =  array('error' => 1, 'message' => array_merge($result['message'], (array)$this->getHelper()->__('There is already a customer registered using this email address. Please login using this email address or enter a different email address.')));
            	
            }
            
        }
		
		if(is_null($address->getCountryId())){
        	
        	$address->setCountryId($this->getDefaultCountryId());
        	
        }
		
		$validate_result = $address->validate();
		
		
		
        if( $validate_result !== true ) {
            
            $result =  array('error' => 1, 'message' => array_merge($result['message'], (array)$validate_result));
            
        }else{
        	
        	$_result =  $this->_processValidateCustomer($address);
        	
        	if($_result['error'] > 0){
        	
        	$result =  array('error' => 1, 'message' => array_merge($result['message'], (array)$_result['message']));
        	
        	}
        	
        }
		
		
        if (!$this->getQuote()->isVirtual()) {
        	
            $usingCase = isset($data['use_for_shipping']) ? (int) $data['use_for_shipping'] : 0;
	        
            switch($usingCase) {
                case 0:
                    $shipping = $this->getQuote()->getShippingAddress();
                    $shipping->setSameAsBilling(0);
                    $this->getCheckout()->setShippingSameAsBilling(0);
                    
                break;
                case 1:
                    $billing = clone $address;
                    $billing->unsAddressId()->unsAddressType();
                    
                    $shipping = $this->getQuote()->getShippingAddress();
                    
                    $shipping
                    	->addData($billing->getData())
                        ->setSameAsBilling(1)
                        ->setCollectShippingRates(true);
                    
                    $this->getCheckout()->setShippingSameAsBilling(1);
                    
                    $validate_result = $shipping->validate();
		            
		            if ($validate_result !== true) {
			            $result =  array('error' => 1, 'message' => array_merge($result['message'], (array)$validate_result));
			        }
                    
				break;
            }
            
            
            
        }
        
        return $result;
    }
	
	public function saveShippingAddress($data, $customerAddressId = false){
		
        if (empty($data)) {
            return array('error' => -1, 'message' => $this->getHelper()->__('Invalid shipping address.'));
        }
        $address = $this->getQuote()->getShippingAddress();
        
		$address->addData($data);
		$address->implodeStreetAddress();
        
        if(is_null($address->getCountryId())){
        	$address->setCountryId($this->getDefaultCountryId());
        }
        
        $address->setCollectShippingRates(true);
        
        $this->getCheckout()->setShippingSameAsBilling(0);
        
		$validate_result = $address->validate();
        
        if ($validate_result !== true) {
            return array('error' => 1, 'message' => (array)$validate_result);
        }
        
        return array();
    }
    
    public function saveShippingMethod($shipping_method){
    	
    	if($shipping_method){
    	
    	$this->getQuote()->getShippingAddress()->setShippingMethod($shipping_method);
    	
    	}
    	
    }
    
    public function savePaymentMethod($payment_method){
    	
    	if($payment_method && is_array($payment_method) && !empty($payment_method)){
    	
    	$this->getQuote()->getPayment()->importData($payment_method);
    	
    	}
    	
    }
    
    protected function _customerEmailExists($email, $websiteId = null){
    	
        $customer = Mage::getModel('customer/customer');
        
        if($websiteId){
            $customer->setWebsiteId($websiteId);
        }
        if($customer->loadByEmail($email)->getId() > 0){
            return $customer;
        }
        
        return false;
    }
	
    public function initCheckout(){
    	
        $checkout	= $this->getCheckout();
        $session	= $this->getCustomerSession();
        
        $checkout->setCheckoutState(Mage_Checkout_Model_Session::CHECKOUT_STATE_BEGIN);
        
        if ($session->isLoggedIn() && !$checkout->getCustomerAssignedQuote()) {
        	
            $this->getQuote()->assignCustomer($session->getCustomer());
            
            $checkout->setCustomerAssignedQuote(true);
            
        }else{
        	
        	if(!$session->isLoggedIn()){
				
				$countryId = $this->getDefaultCountryId();
				
				if(!$this->getQuote()->getBillingAddress()->getCountryId()){
					
			        $this->getQuote()->getBillingAddress()->setCountryId($countryId);
			        
				}
				
				if (!$this->getQuote()->isVirtual()){
					
					$customer = $session->getCustomer();
					
					if($customer->getDefaultShippingAddress() == false || $this->getHelper()->shippingSameAsBilling()){
	            		
	            		if($billingAddress = $customer->getDefaultBillingAddress()){
	            		
	            			$shippingAddress = Mage::getModel('sales/quote_address')->importCustomerAddress($billingAddress);
	            			$this->getQuote()->setShippingAddress($shippingAddress);
	            		
	            		}
	            		
	            	}
					
					if(!$this->getQuote()->getShippingAddress()->getCountryId()){
						
				        $this->getQuote()->getShippingAddress()->setCountryId($countryId);
						
					}
					
				}
				
				
			}
        	
        }
        
        $this->getQuote()->collectTotals();
        
        if (!$this->getQuote()->isVirtual()){
        	
        	$address = $this->getQuote()->getShippingAddress();
        	
	        if(!$address->getShippingMethod()){
		        
		        $address->setCollectShippingRates(true);
		        $address->collectShippingRates();
				$this->getHelper()->initSingleShippingMethod($address);
				
				$this->getQuote()->setTotalsCollectedFlag(false);
				$this->getQuote()->collectTotals();
				
			}else{
				$address->setCollectShippingRates(true);
			}
			
			
        }
        
        
        $this->getQuote()->save();
        return $this;
	}
	
	protected function _processValidateCustomer(Mage_Sales_Model_Quote_Address $address)
    {
        // set customer date of birth for further usage
        $dob = '';
        if ($address->getDob()) {
            $dob = Mage::app()->getLocale()->date($address->getDob(), null, null, false)->toString('yyyy-MM-dd');
            $this->getQuote()->setCustomerDob($dob);
        }

        // set customer tax/vat number for further usage
        if ($address->getTaxvat()) {
            $this->getQuote()->setCustomerTaxvat($address->getTaxvat());
        }

        // set customer gender for further usage
        if ($address->getGender()) {
            $this->getQuote()->setCustomerGender($address->getGender());
        }

        // invoke customer model, if it is registering
        if (self::METHOD_REGISTER == $this->getQuote()->getCheckoutMethod()) {
            // set customer password hash for further usage
            $customer = Mage::getModel('customer/customer');
            
            $this->getQuote()->setPasswordHash($customer->encryptPassword($address->getPassword()));

            // validate customer
            foreach (array(
                'firstname'    => 'firstname',
                'lastname'     => 'lastname',
                'email'        => 'email',
                'password'     => 'password',
                'confirmation' => 'confirmation',
                'taxvat'       => 'taxvat',
                'gender'       => 'gender',
            ) as $key => $dataKey) {
                $customer->setData($key, $address->getData($dataKey));
            }
            if ($dob) {
                $customer->setDob($dob);
            }
            $validationResult = $customer->validate();
            if (true !== $validationResult && is_array($validationResult)) {
                return array(
                    'error'   => 1,
                    'message' => implode(', ', $validationResult)
                );
            }
        } elseif(self::METHOD_GUEST == $this->getQuote()->getCheckoutMethod()) {
            $email = $address->getData('email');
            if (!Zend_Validate::is($email, 'EmailAddress')) {
                return array(
                    'error'   => 1,
                    'message' => $this->_helper->__('Invalid email address "%s"', $email)
                );
            }
        }

        return true;
    }
	
}