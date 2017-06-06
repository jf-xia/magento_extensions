<?php
class NeedTool_Paymentstub_Model_Stub
{

    public function getCheckout()
    {
        return Mage::getSingleton('checkout/session');
    }

    public function getQuote()
    {
        return $this->getCheckout()->getQuote();
    }
    
    
    public function savePayment($data)
    {
        if (empty($data)) {
            $res = array(
                'error' => -1,
                'message' => Mage::helper('checkout')->__('Invalid data')
            );
            return $res;
        }
        $payment = $this->getQuote()->getPayment();
        $payment->importData($data);

        $this->getQuote()->getShippingAddress()->setPaymentMethod($payment->getMethod());
        $this->getQuote()->collectTotals()->save();

        $this->getCheckout()
            ->setStepData('payment', 'complete', true)
            ->setStepData('review', 'allow', true);

        return array();
    }

    protected function validateOrder()
    {
        $helper = Mage::helper('paymentstub');
        if (!($this->getQuote()->getPayment()->getMethod())) {
            Mage::throwException($helper->__('Please select valid payment method.'));
        }
    }

    public function saveOrder()
    {
        $helper = Mage::helper('paymentstub');
        if (!($this->getQuote()->getPayment()->getMethod())) {
            Mage::throwException($helper->__('Please select valid payment method.'));
        }

	      $session = Mage::getSingleton('checkout/session');
				$order=Mage::getModel('sales/order')->loadByIncrementId($session->getLastRealOrderId());
			
        $convertQuote = Mage::getModel('sales/convert_quote');

        $order->setPayment($convertQuote->paymentToOrderPayment($this->getQuote()->getPayment()));

        $order->place();

//        /**
//         * a flag to set that there will be redirect to third party after confirmation
//         * eg: paypal standard ipn
//         */
        $redirectUrl = $this->getQuote()->getPayment()->getOrderPlaceRedirectUrl();
//        if(!$redirectUrl){
//            $order->setEmailSent(true);
//        }
//
        $order->save();
//
//        Mage::dispatchEvent('checkout_type_onepage_save_order_after', array('order'=>$order, 'quote'=>$this->getQuote()));
//
//        /**
//         * need to have somelogic to set order as new status to make sure order is not finished yet
//         * quote will be still active when we send the customer to paypal
//         */
//
        $orderId = $order->getIncrementId();
        $this->getCheckout()->setLastQuoteId($this->getQuote()->getId());
        $this->getCheckout()->setLastOrderId($order->getId());
        $this->getCheckout()->setLastRealOrderId($order->getIncrementId());
        $this->getCheckout()->setRedirectUrl($redirectUrl);
//
//        /**
//         * we only want to send to customer about new order when there is no redirect to third party
//         */
//        if(!$redirectUrl){
//            $order->sendNewOrderEmail();
//        }
//
//        if ($this->getQuote()->getCheckoutMethod(true)==Mage_Sales_Model_Quote::CHECKOUT_METHOD_REGISTER
//            && !Mage::getSingleton('customer/session')->isLoggedIn()) {
//            /**
//             * we need to save quote here to have it saved with Customer Id.
//             * so when loginById() executes checkout/session method loadCustomerQuote
//             * it would not create new quotes and merge it with old one.
//             */
//            $this->getQuote()->save();
//            if ($customer->isConfirmationRequired()) {
//                Mage::getSingleton('checkout/session')->addSuccess(Mage::helper('customer')->__('Account confirmation is required. Please, check your e-mail for confirmation link. To resend confirmation email please <a href="%s">click here</a>.',
//                    Mage::helper('customer')->getEmailConfirmationUrl($customer->getEmail())
//                ));
//            }
//            else {
//                Mage::getSingleton('customer/session')->loginById($customer->getId());
//            }
//        }
//
//        //Setting this one more time like control flag that we haves saved order
//        //Must be checkout on success page to show it or not.
        $this->getCheckout()->setLastSuccessQuoteId($this->getQuote()->getId());

        $this->getQuote()->setIsActive(false);
        $this->getQuote()->save();

        return $this;
    }

}
