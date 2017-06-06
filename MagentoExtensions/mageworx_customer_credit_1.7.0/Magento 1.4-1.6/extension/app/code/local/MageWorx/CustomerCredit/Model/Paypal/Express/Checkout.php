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
class  MageWorx_CustomerCredit_Model_Paypal_Express_Checkout extends Mage_Paypal_Model_Express_Checkout
{
    
    public function start($returnUrl, $cancelUrl) {
        $this->_quote->collectTotals();                        
                
        $address = $this->_quote->getIsVirtual() ? $this->_quote->getBillingAddress() : $this->_quote->getShippingAddress();        
        
        // get credit amount
        $baseCustomerCreditAmount = $address->getBaseCustomerCreditAmount();
        $customerCreditAmount = $address->getCustomerCreditAmount();
                
        // apply credit
        if ($baseCustomerCreditAmount>0) {
            $this->_quote->setBaseDiscountAmount($this->_quote->getBaseDiscountAmount() + $baseCustomerCreditAmount);
            $this->_quote->setDiscountAmount($this->_quote->getDiscountAmount() + $customerCreditAmount);

            $address->setBaseDiscountAmount($address->getBaseDiscountAmount() + $baseCustomerCreditAmount);
            $address->setDiscountAmount($address->getDiscountAmount() + $customerCreditAmount);
        }    
        
        return parent::start($returnUrl, $cancelUrl);
        
    }

    
}
