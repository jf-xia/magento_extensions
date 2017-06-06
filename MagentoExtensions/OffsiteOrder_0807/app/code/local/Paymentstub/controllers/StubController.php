<?php


class NeedTool_Paymentstub_StubController extends NeedTool_Paymentstub_Controller_Action
{
    public function preDispatch()
    {
        parent::preDispatch();
        if (!$this->_preDispatchValidateCustomer()) {
            return $this;
        }
        return $this;
    }

    public function getOnepage()
    {
        return Mage::getSingleton('paymentstub/stub');
    }
    
    /**
     * Checkout page
     */
    public function indexAction()
    {		

        //Mage::getSingleton('checkout/session')->setCartWasUpdated(false);
        Mage::getSingleton('customer/session')->setBeforeAuthUrl($this->getRequest()->getRequestUri());
        //$this->getOnepage()->initCheckout();

        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->getLayout()->getBlock('head')->setTitle($this->__('Quick Checkout'));
        $this->renderLayout();
        
    }

    public function successAction()
    {
      //  if (!$this->getOnepage()->getCheckout()->getLastSuccessQuoteId()) {
      //      $this->_redirect('checkout/cart');
      //      return;
      //  }
Mage::log("here");
        $lastQuoteId = $this->getOnepage()->getCheckout()->getLastQuoteId();
        $lastOrderId = $this->getOnepage()->getCheckout()->getLastOrderId();

      //  if (!$lastQuoteId || !$lastOrderId) {
      //      $this->_redirect('checkout/cart');
      //      return;
      //  }

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
            $this->_redirect('checkout/cart');
            return;
        }

        $this->loadLayout();
        $this->renderLayout();
    }

    public function savePaymentAction()
    {
        //$this->_expireAjax();
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost('payment', array());
            /*
            * first to check payment information entered is correct or not
            */

            try {
                $result = $this->getOnepage()->savePayment($data);
            }
            catch (Mage_Payment_Exception $e) {
                if ($e->getFields()) {
                    $result['fields'] = $e->getFields();
                }
                $result['error'] = $e->getMessage();
            }
            catch (Exception $e) {
                $result['error'] = $e->getMessage();
            }
            $redirectUrl = $this->getOnePage()->getQuote()->getPayment()->getCheckoutRedirectUrl();
            if (empty($result['error']) && !$redirectUrl) {

            }else{

	            if ($redirectUrl) {
	                $result['redirect'] = $redirectUrl;
	            }

            	$this->getResponse()->setBody(Zend_Json::encode($result));
          	}
        }
    }

    public function saveOrderAction()
    {
       // $this->_expireAjax();
				$this->savePaymentAction();
				
        $result = array();
        try {
        	
        	  //$data1 = $this->getRequest()->getPost('payment', array());
            /*
            * first to check payment information entered is correct or not
            */
            //Mage::log($data1);

            //$result = $this->getOnepage()->savePayment($data1);
            Mage::log($result);
            if ($data = $this->getRequest()->getPost('payment', false)) {
				      //temp call, move to js
			    		//$this->getOnepage()->savePayment($data);
            		
                $this->getOnepage()->getQuote()->getPayment()->importData($data);
            }
            
            $this->getOnepage()->saveOrder();
            $redirectUrl = $this->getOnepage()->getCheckout()->getRedirectUrl();
            $result['success'] = true;
            $result['error']   = false;
        }
        catch (Mage_Core_Exception $e) {
            Mage::logException($e);
            //Mage::helper('checkout')->sendPaymentFailedEmail($this->getOnepage()->getQuote(), $e->getMessage());
            $result['success'] = false;
            $result['error'] = true;
            $result['error_messages'] = $e->getMessage();

            $this->getOnepage()->getQuote()->save();
        }
        catch (Exception $e) {
            Mage::logException($e);
            //Mage::helper('checkout')->sendPaymentFailedEmail($this->getOnepage()->getQuote(), $e->getMessage());
            $result['success']  = false;
            $result['error']    = true;
            $result['error_messages'] = $this->__('There was an error processing your order. Please contact us or try again later.');
            $this->getOnepage()->getQuote()->save();
        }

        /**
         * when there is redirect to third party, we don't want to save order yet.
         * we will save the order in return action.
         */
        if (isset($redirectUrl)) {
            $result['redirect'] = $redirectUrl;
        }
				Mage::log($result);
        $this->getResponse()->setBody(Zend_Json::encode($result));
    }
    
    
    protected function orderLoad(){
    	/**
For compatiable to other payment methods,
load order fields ,
save to Quote, save to session
 */
			 
 
    }


}
