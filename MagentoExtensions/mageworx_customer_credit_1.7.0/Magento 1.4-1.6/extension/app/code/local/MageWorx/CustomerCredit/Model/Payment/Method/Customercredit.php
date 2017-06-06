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
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@mageworx.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://www.mageworx.com/ for more information
 * or send an email to sales@mageworx.com
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @copyright  Copyright (c) 2010 MageWorx (http://www.mageworx.com/)
 * @license    http://www.mageworx.com/LICENSE-1.0.html
 */

/**
 * Customer Credit extension
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @author     MageWorx Dev Team <dev@mageworx.com>
 */

class MageWorx_Customercredit_Model_Payment_Method_Customercredit extends Mage_Payment_Model_Method_Abstract
{
    protected $_code            = 'customercredit';
    protected $_formBlockType   = 'customercredit/payment_form';
    protected $_canRefund       = false;

    public function assignData($data)
    {
        return parent::assignData($data);
    }

    /**
     *
     * @param $quote Mage_Sales_Model_Quote
     */
    public function isAvailable($quote=null) {
        if (!Mage::getSingleton('customer/session')->getCustomerId() && !Mage::getSingleton('admin/session')->getUser()) {
            return false;
        }
        $credit = $this->_getCreditModel()->getValue();
        if (!$this->_getHelper()->isEnabled()) { // || $credit <= 0
            return false;
        }
        $address = $quote->getShippingAddress();
        if (!$address) {
            $address = $quote->getBillingAddress();
        }
        
//        if ($credit < $address->getGrandTotal() && !Mage::helper('customercredit')->isEnabledPartialPayment()) {
//            return false;
//        }
        return true;
    }

    public function isInputTypeCheckbox()
    {
        $quote = $this->getQuote();
        $credit = $this->_getCreditModel()->getValue();
        if($credit > 0 && $credit < $quote->getGrandTotal()){
            return true;
        }
        return false;
    }

    public function validate()
    {
        parent::validate();
        $errorMsg = false;

        if ($this->getInfoInstance() instanceof Mage_Sales_Model_Quote_Payment)
        {
            if (!$this->_checkCredit($this->getInfoInstance()->getQuote()))
                $errorMsg = $this->_getHelper()->__('Not enough Credit Amount to complete this operation.');
        }

        if ($errorMsg)
            Mage::throwException($errorMsg);

        return $this;
    }

    protected function _checkCredit($quote)
    {
        $credit = $this->_getCreditModel()->getValue();
        return $credit > 0;
    }

    /**
     *
     * @return MageWorx_CustomerCredit_Model_Credit
     */
    protected function _getCreditModel()
    {
        if (!Mage::getSingleton('admin/session')->getUser())
        {
            $customerId = Mage::getSingleton('customer/session')->getCustomerId();
            $websiteId  = Mage::app()->getStore()->getWebsiteId();
        }
        else
        {
            if ($order = Mage::registry('current_order'))
            {
                $customerId = $order->getCustomerId();
                $websiteId = Mage::app()->getStore($order->getStoreId())->getWebsiteId();
            }
            elseif ($invoice = Mage::registry('current_invoice'))
            {
                $customerId = $invoice->getCustomerId();
                $websiteId = Mage::app()->getStore($invoice->getStoreId())->getWebsiteId();
            }
            else
            {
            	$customerId = Mage::getSingleton('adminhtml/session_quote')->getCustomerId();
            	$websiteId = Mage::app()->getStore(Mage::getSingleton('adminhtml/session_quote')->getStoreId())->getWebsiteId();
            }
        }
        return Mage::getModel('customercredit/credit')
            ->setCustomerId($customerId)
            ->setWebsiteId($websiteId)
            ->loadCredit();
    }

    public function getInternalCredit(){
        return $this->_getCreditModel()->getValue();
    }

    /**
     * Retrieve model helper
     *
     * @return MageWorx_CustomerCredit_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('customercredit');
    }
}