<?php
/**
 * MageWorx
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MageWorx EULA that is bundled with
 * this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.mageworx.com/LICENSE-1.0.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://www.mageworx.com/ for more information
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @copyright  Copyright (c) 2011 MageWorx (http://www.mageworx.com/)
 * @license    http://www.mageworx.com/LICENSE-1.0.html
 */

/**
 * Customer Credit extension
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @author     MageWorx Dev Team
 */

class MageWorx_CustomerCredit_Block_Checkout_Onepage_Payment_Methods extends Mage_Checkout_Block_Onepage_Payment_Methods {

//    public function getCreditValue() {
//        return Mage::getModel('customercredit/credit')
//                ->setCustomerId(Mage::getSingleton('customer/session')->getCustomerId())
//                ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
//                ->loadCredit()
//                ->getValue();
//    }

//    public function useCheckbox($method){
//        if ($method!=='customercredit') {
//            return false;
//        }
//        
//        $creditValue = $this->getCreditValue();
//        
//        if(Mage::getSingleton('checkout/session')->getUseInternalCredit() && $this->getQuote()->getBaseCustomerCreditTotal() > $creditValue){
//            return $creditValue;
//        }
//
//        if($this->getQuote()->getBaseGrandTotal() > $creditValue){
//            return $creditValue;
//        }
//
//        return false;
//
//    }
    
    public function isPartialPayment() {
        return Mage::helper('customercredit')->isPartialPayment($this->getQuote(), Mage::getSingleton('customer/session')->getCustomerId(), Mage::app()->getStore()->getWebsiteId());
        
//        $creditValue = $this->getCreditValue();       
//        if(Mage::getSingleton('checkout/session')->getUseInternalCredit() && $this->getQuote()->getBaseCustomerCreditTotal() > $creditValue){
//            return $creditValue;
//        }
//
//        if($this->getQuote()->getBaseGrandTotal() > $creditValue){
//            return $creditValue;
//        }
//        return false;
    }
    
    

    public function getMethods() {       
        if(version_compare(Mage::getVersion(), '1.6.0.0', '<')){
            return parent::getMethods();
        }
        
        $methods = $this->getData('methods');
        if (is_null($methods)) {
            $quote = $this->getQuote();
            $store = $quote ? $quote->getStoreId() : null;
            $methods = $this->helper('payment')->getStoreMethods($store, $quote);
            $total = $quote->getGrandTotal();
            foreach ($methods as $key => $method) {
                if ($this->_canUseMethod($method)
                    && ($total >= 0
                        || $method->getCode() == 'free'
                        || ($quote->hasRecurringItems() && $method->canManageRecurringProfiles()))) {
                    $this->_assignMethod($method);
                } else {
                    unset($methods[$key]);
                }
            }
            $this->setData('methods', $methods);
        }
        return $methods;
    }

}