<?php

class Topbuy_Ajax_IndexController extends Mage_Core_Controller_Front_Action {
    
    public function tAction() {
        $onepage = Mage::getSingleton('checkout/type_onepage');
        $quote = $onepage->getQuote();
//        echo $quote->getShippingAddress()->getEmail().'---'.$quote->getShippingAddress()->getFirstname();
        $customer = Mage::helper('ajax')->createAccount($quote->getShippingAddress()->getEmail(),$quote->getShippingAddress()->getFirstname());
        $quote->setCustomerEmail($customer->getEmail())
                ->setCheckoutMethod('paypal express with customer')
                ->setCustomerId($customer->getId())
                ->setCustomerGroupId($customer->getGroupId())
                ->setCustomerFirstname($customer->getName())
                ->save();
    }
    public function ttAction() {
        $code = 'matrixrate';
        $customer = Mage::getSingleton("customer/session")->getCustomer();
        $onepage = Mage::getSingleton('checkout/type_onepage');
        $quote = $onepage->getQuote();
        $quote->assignCustomer($customer);
        print_r($customer->getPrimaryBillingAddress()->getData());
        $billingAddress = $quote->getBillingAddress()->addData($customer->getPrimaryBillingAddress()->getData());
        $shippingAddress = $quote->getShippingAddress()->addData($customer->getPrimaryShippingAddress()->getData());
//        print_r($billingAddress->getData());$address->getGroupedAllShippingRates()
        $quote->getShippingRateByCode('matrixrate');
        $result = $onepage->saveShippingMethod('matrixrate');
        $quote->collectTotals()->save();  

        Mage::getSingleton('onepagecheckout/type_geo')->useShipping($code);
    }

    public function getpcAction() {
        $sku = $this->getRequest()->getParam('sku');
        $product = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku)->getCategoryIds(); //->loadBySku();//
        print_r($product);
        foreach ($product as $catid) {
            echo Mage::getModel('catalog/category')->load($catid)->getName() . '<br>';
        }
    }

}

//Checkout_Model_Cart_Coupon_ApiTBLC-XX1022039
