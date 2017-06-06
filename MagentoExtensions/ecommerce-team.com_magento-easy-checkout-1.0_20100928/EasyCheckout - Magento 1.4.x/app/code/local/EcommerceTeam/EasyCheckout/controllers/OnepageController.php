<?php

/*
 * Magento EsayCheckout Extension
 *
 * @copyright:	EcommerceTeam (http://www.ecommerce-team.com)
 * @version:	1.0
 *
 */

class EcommerceTeam_EasyCheckout_OnepageController extends Mage_Checkout_Controller_Action{
	
	protected $_helper;
	protected $_session;
	protected $_checkout;
	
	public function dispatch($action){
		
		return parent::dispatch($action);
		
	}
	
	public function getHelper(){
		
		if (is_null($this->_helper)) {
            $this->_helper = Mage::helper('ecommerceteam_echeckout');
        }
		return $this->_helper;
		
	}
	
	public function getOnepage(){
		
		return $this->getHelper()->getOnepage();
		
	}
	public function getCustomerSession(){
		if (is_null($this->_session)) {
            $this->_session = Mage::getSingleton('customer/session');
        }
		return $this->_session;
	}
	public function getCheckout()
    {
        if (empty($this->_checkout)) {
            $this->_checkout = Mage::getSingleton('checkout/session');
        }
        return $this->_checkout;
    }
    
    public function indexAction(){
    	
    	//die();
    	
        $quote = $this->getOnepage()->getQuote();
        
        if (!$quote->hasItems() || $quote->getHasError()){
        	
            $this->_redirect('checkout/cart');
            
            return;
        }
        
        if (!$quote->validateMinimumAmount())
        {
            $error = Mage::getStoreConfig('sales/minimum_order/error_message');
            Mage::getSingleton('checkout/session')->addError($error);
            $this->_redirect('checkout/cart');
            return;
        }
        
        Mage::getSingleton('checkout/session')->setCartWasUpdated(false);
        Mage::getSingleton('customer/session')->setBeforeAuthUrl(Mage::getUrl('*/*/*', array('_secure'=>true)));
        
        $this->getOnepage()->initCheckout();
        
        $this->loadLayout();
        
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        
        
        $this->getLayout()->getBlock('head')->setTitle($this->getHelper()->getConfigData('options/title'));
        
        $this->renderLayout();
    }
    
    public function successAction()
    {
        if (!$this->getOnepage()->getCheckout()->getLastSuccessQuoteId()) {
            $this->_redirect('*/cart');
            return;
        }

        $lastQuoteId = $this->getOnepage()->getCheckout()->getLastQuoteId();
        $lastOrderId = $this->getOnepage()->getCheckout()->getLastOrderId();

        if (!$lastQuoteId || !$lastOrderId) {
            $this->_redirect('*/cart');
            return;
        }

        Mage::getSingleton('checkout/session')->clear();
        $this->loadLayout();
        $this->_initLayoutMessages('checkout/session');
        Mage::dispatchEvent('checkout_onepage_controller_success_action');
        $this->renderLayout();
    }

    public function failureAction()
    {
        $lastQuoteId = $this->getOnepage()->getCheckout()->getLastQuoteId();
        $lastOrderId = $this->getOnepage()->getCheckout()->getLastOrderId();

        if (!$lastQuoteId || !$lastOrderId) {
            $this->_redirect('*/cart');
            return;
        }

        $this->loadLayout();
        $this->renderLayout();
    }
    
    
	public function ajaxAction(){
		$action = $this->getRequest()->getParam('action');
		
		$result = new stdClass();
		$result->error = false;
		
		switch($action):
			
			default:
				
				if($billing_address_data = $this->getRequest()->getPost('billing')){
					
					$address = $this->getOnepage()->getQuote()->getBillingAddress();
					
					$address->addData($billing_address_data);
					$address->implodeStreetAddress();
					
					if (!$this->getOnepage()->getQuote()->isVirtual()) {
						
						
						
						if((!isset($billing_address_data['use_for_shipping']) || !intval($billing_address_data['use_for_shipping'])) && $this->getHelper()->differentShippingEnabled()){
							
							$shipping_address_data = $this->getRequest()->getPost('shipping');
							
						}else{
							
							$shipping_address_data = $billing_address_data;
							
						}
						
						$address = $this->getOnepage()->getQuote()->getShippingAddress();
						$address->addData($shipping_address_data);
						$address->implodeStreetAddress();
						
						$this->getOnepage()->getQuote()->collectTotals();
						
						$collectors = $address->getTotalCollector()->getCollectors();
						$collectors['shipping']->collect($address);
						/**/
						
	        			$address->setCollectShippingRates(true);
	        			$address->collectShippingRates();
	        			
	        			$this->getHelper()->initSingleShippingMethod($address);
	        			
	        			$result->shipping_rates = $this->_getShippingMethodsHtml();
	        			
	        			
	        			$address->setCollectShippingRates(true);
					}
					
					$this->getOnepage()->getQuote()->collectTotals();
					
					$result->payments	= $this->_getPaymentMethodsHtml();
					$result->review 	= $this->_getReviewHtml();
					
					$this->getOnepage()->getQuote()->save();
					
				}
			break;
			case('payment'):
				
				$address = $this->getOnepage()->getQuote()->getBillingAddress();
				$address->addData($this->getRequest()->getPost('billing'));
				$address->implodeStreetAddress();
				
				$this->getOnepage()->getQuote()->collectTotals();
				
				$result->payments	= $this->_getPaymentMethodsHtml();
				$result->review 	= $this->_getReviewHtml();
				
				$this->getOnepage()->getQuote()->save();
			break;
			case('shipping'):
				if (!$this->getOnepage()->getQuote()->isVirtual() && $this->getHelper()->differentShippingEnabled()) {
					
					$address = $this->getOnepage()->getQuote()->getShippingAddress();
					$address->addData($this->getRequest()->getPost('shipping'));
					$address->implodeStreetAddress();
					
					$this->getOnepage()->getQuote()->collectTotals();
					
					$collectors = $address->getTotalCollector()->getCollectors();
					$collectors['shipping']->collect($address);
					
        			$address->setCollectShippingRates(true);
        			$address->collectShippingRates();
        			
        			$this->getHelper()->initSingleShippingMethod($address);
        			
        			$result->shipping_rates = $this->_getShippingMethodsHtml();
        			
        			$address->setCollectShippingRates(true);
        			
					$this->getOnepage()->getQuote()->collectTotals();
					
					$result->review = $this->_getReviewHtml();
					
					$this->getOnepage()->getQuote()->save();
					
				}
			break;
			case('review'):
				
				if($shippingMethod = $this->getRequest()->getPost('shipping_method', false)){
        			
        			$this->getOnepage()->getQuote()->getShippingAddress()->setShippingMethod($shippingMethod);
        			
        		}
        		
        		if (($payment = $this->getRequest()->getPost('payment', false)) && is_array($payment) && isset($payment['method']) && $payment['method']) {
        			try{
        				
                		$this->getOnepage()->getQuote()->getPayment()->importData($payment);
                		
                	}catch(Exception $e){
                		
                		//continue
                		
                	}
            	}
				
				$this->getOnepage()->getQuote()->collectTotals();
				
				$result->review = $this->_getReviewHtml();
				
				$this->getOnepage()->getQuote()->save();
			break;
			case('login'):
				
				$username = $this->getRequest()->getPost('username');
				$password = $this->getRequest()->getPost('password');
				
				if($username && $password){
					
					try{
						
		                Mage::getSingleton('customer/session')->login($username, $password);
		                
		            }catch(Mage_Core_Exception $e) {
		            	
		                switch ($e->getCode()) {
		                    case Mage_Customer_Model_Customer::EXCEPTION_EMAIL_NOT_CONFIRMED:
		                        $message = $this->__('This account is not confirmed. <a href="%s">Click here</a> to resend confirmation email.', Mage::helper('customer')->getEmailConfirmationUrl($login['username']));
		                    break;
		                    default:
		                        $message = $e->getMessage();
		                    break;
		                }
		                
		                $result->error	= true;
		                $result->message	= $message;
		                
		            }catch(Exception $e){
		                $result->error	= true;
		                $result->message	= $e->getMessage();
		            }
					
				}else{
		        	$result->error = true;
		            $result->message = $this->__('Login and password are required');
		        }
				
			break;
			
		case('coupon'):
				
			if (!$this->getOnepage()->getQuote()->getItemsCount()) {
				return;
			}
			
			$couponCode = (string) $this->getRequest()->getParam('coupon_code');
			
			if ($this->getRequest()->getParam('remove') == 1) {
					$couponCode = '';
			}
        
			$oldCouponCode = $this->getOnepage()->getQuote()->getCouponCode();

			if (!strlen($couponCode) && !strlen($oldCouponCode)) {
				return;
			}
		        
			try {
            
				$this->getOnepage()->getQuote()->getShippingAddress()->setCollectShippingRates(true);
	            $this->getOnepage()->getQuote()->setCouponCode(strlen($couponCode) ? $couponCode : '')
	                ->collectTotals()
	                ->save();
				if ($couponCode) {
                if ($couponCode == $this->getOnepage()->getQuote()->getCouponCode()) {
					$result->message = $this->__('Coupon code "%s" was applied successfully.', Mage::helper('core')->htmlEscape($couponCode));
			    }
                else {
					$result->error	= true;
					$result->message = $this->__('Coupon code "%s" is not valid.', Mage::helper('core')->htmlEscape($couponCode));
			    }
			    
            } else {
				$result->message = $this->__('Coupon code was canceled successfully.');
		    }

        }
        catch (Mage_Core_Exception $e) {
			$result->error	= true;
		    $result->message = $e->getMessage();
            
        }
        catch (Exception $e) {
			$result->error	= true;
		    $result->message = $e->getMessage();
         }
		$result->shipping_rates = $this->_getShippingMethodsHtml();
		$result->review = $this->_getReviewHtml(); 
		$result->coupon = $this->_getCouponHtml(); 
							
		break;
			
		endswitch;
		
		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
		
	}
	
	
	
	public function onepageAction(){
		$this->_redirect('checkout/onepage');
	}
	
	public function saveAction(){
		
		if ($this->getRequest()->isPost()) {
			
			$result = array('error'=>0, 'message'=>array());
			
        	try {
        		if ($requiredAgreements = Mage::helper('checkout')->getRequiredAgreementIds()) {
	                $postedAgreements = array_keys($this->getRequest()->getPost('agreement', array()));
	                if ($diff = array_diff($requiredAgreements, $postedAgreements)) {
	                    $result['error'] = true;
	                    $result['message'][] = $this->__('Please agree to all Terms and Conditions before placing the order.');
	                }
	            }
        		
        		if($this->getCustomerSession()->isLoggedIn()){
        			
        			$this->getOnepage()->saveCheckoutMethod(Mage_Sales_Model_Quote::CHECKOUT_METHOD_LOGIN_IN);
        				
        		}elseif($this->getRequest()->getParam('create_account') || $this->getOnepage()->getQuote()->hasVirtualItems() || (bool)$this->getHelper()->getConfigData('options/guest_checkout') == false){
        			
        			$this->getOnepage()->saveCheckoutMethod(Mage_Sales_Model_Quote::CHECKOUT_METHOD_REGISTER);
        			
	            }else{
	            	
	            	$this->getOnepage()->saveCheckoutMethod(Mage_Sales_Model_Quote::CHECKOUT_METHOD_GUEST);
	            	
	            }
	            
	            $billing_data = (array) $this->getRequest()->getPost('billing', array());
	            
	            if(!$this->getHelper()->differentShippingEnabled()){
	            	
	            	$billing_data['use_for_shipping'] = 1;
	            	
	            }
	            
				$_result = $this->getOnepage()->saveBillingAddress($billing_data);
				
				if(isset($_result['error']) && $_result['error'] != false){
					
					$result['error'] = true;
					$messages = array();
					
					foreach((array) $_result['message'] as $message){
						$messages[] = $this->__('Billing address error').': '.$message;
					}
					$result['message'] = array_merge($result['message'], $messages);
				}
				
				
				
				if (!$this->getOnepage()->getQuote()->isVirtual()) {
					
					if(!$this->getOnepage()->getQuote()->getBillingAddress()->getUseForShipping()){
						
						$_result = $this->getOnepage()->saveShippingAddress($this->getRequest()->getPost('shipping'));
						
						if(isset($_result['error']) && intval($_result['error'])){
						
							$result['error'] = true;
							$messages = array();
							
							foreach((array) $_result['message'] as $message){
								$messages[] = $this->__('Shipping address error').': '.$message;
							}
							$result['message'] = array_merge($result['message'], $messages);
						}
						
					}
					
					$this->getOnepage()->saveShippingMethod($this->getRequest()->getPost('shipping_method'));
					
				}
				
				if($this->getRequest()->getParam('subscribe', false)){
					
					if($this->getCustomerSession()->isLoggedIn()){
						
						$email = $this->getCustomerSession()->getCustomer()->getEmail();
						
					}else{
					
						$email = $this->getOnepage()->getQuote()->getBillingAddress()->getEmail();
					
					}
					
					Mage::getModel('newsletter/subscriber')->subscribe($email);
				
				}
				
				$this->getOnepage()->savePaymentMethod($this->getRequest()->getPost('payment'));
				
				$this->getOnepage()->getQuote()->save();
				
				$this->getOnepage()->savePaymentMethod($this->getRequest()->getPost('payment')); //fix for don’t delete original cc number after save quote
				
				if($redirectUrl = $this->getOnepage()->getQuote()->getPayment()->getCheckoutRedirectUrl()){
					
					$this->getOnepage()->getQuote()->save();
					
					return $this->_redirectUrl($redirectUrl);
					
				}
				
            	$this->getOnepage()->getQuote()->collectTotals();
        		
        		if(isset($result['error']) && (bool)$result['error'] === true){
        			
					throw new Mage_Core_Exception(implode('<br/>', $result['message']));
					
				}
				
				Mage::dispatchEvent('easycheckout_controller_onepage_save_order', array('request'=>$this->getRequest(), 'quote'=>$this->getOnepage()->getQuote()));
				
				$this->getOnepage()->saveOrder();
				$this->getOnepage()->getQuote()->save();
				
				$this->getCheckout()->setCustomerAssignedQuote(false);
				$this->getCheckout()->setCustomerAdressLoaded(false);
				
				$redirectUrl = $this->getOnepage()->getCheckout()->getRedirectUrl();
				
				if($redirectUrl){
				
					$this->_redirectUrl($redirectUrl);
				
				}else{
					
					$this->_redirect('*/*/success');
					
				}
				
            	
        	}catch(Mage_Core_Exception $e) {
        		
        		Mage::logException($e);
            	$this->getOnepage()->getQuote()->save();
            	$this->getCustomerSession()->addError($e->getMessage());
            	
            	$this->onepageAction();
            	
        	}catch(Exception $e) {
        		
        		Mage::logException($e);
        		$this->getOnepage()->getQuote()->save();
        		$this->getCustomerSession()->addError($this->__('There was an error processing your order. Please contact us or try again later.'));
        		
        		$this->onepageAction();
        		
        	}
			
		}
		
	}
	
	
	protected function _getShippingMethodsHtml()
    {
        $layout = Mage::getModel('core/layout');
        $layout->getUpdate()->load('checkout_onepage_shippingmethod');
        $layout->generateXml();
        $layout->generateBlocks();
        return $layout->getOutput();
    }
    
    protected function _getPaymentMethodsHtml()
    {
        $layout = Mage::getModel('core/layout');
        $layout->getUpdate()->load('checkout_onepage_paymentmethod');
        $layout->generateXml();
        $layout->generateBlocks();
        return $layout->getOutput();
    }

    protected function _getAdditionalHtml()
    {
        $layout = Mage::getModel('core/layout');
        $layout->getUpdate()->load('checkout_onepage_additional');
        $layout->generateXml();
        $layout->generateBlocks();
        return $layout->getOutput();
    }
    
    protected function _getReviewHtml()
    {
    	
        $layout = Mage::getModel('core/layout');
        $layout->getUpdate()->load('checkout_onepage_review');
        $layout->generateXml();
        $layout->generateBlocks();
        return $layout->getOutput();
    }
	
	protected function _getCouponHtml()
    {
    	
        $layout = Mage::getModel('core/layout');
        $layout->getUpdate()->load('ecommerceteam_echeckout_onepage_coupon');
        $layout->generateXml();
        $layout->generateBlocks();
        return $layout->getOutput();
    }
}
