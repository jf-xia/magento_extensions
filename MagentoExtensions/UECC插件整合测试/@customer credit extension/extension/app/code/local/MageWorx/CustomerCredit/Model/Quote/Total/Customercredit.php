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

class MageWorx_CustomerCredit_Model_Quote_Total_Customercredit extends Mage_Sales_Model_Quote_Address_Total_Abstract
{
    public function __construct()
    {
        $this->setCode('customercredit');
    }

    public function collect(Mage_Sales_Model_Quote_Address $address)
    {

        if (!Mage::helper('customercredit')->isEnabled())
        {
            return $this;
        }

        $address->setCustomerCreditAmount(0);
        $address->setBaseCustomerCreditAmount(0);

        $session = Mage::getSingleton('checkout/session');
        $quote = $address->getQuote();
        
        $data = Mage::app()->getRequest()->getPost('payment');
        
        if(Mage::getModel('checkout/cart')->getQuote()->getData('items_qty') == 0 && !Mage::getSingleton('adminhtml/session_quote')->getCustomerId()) {
			return $this;
		}
		
		if($quote->getPayment()->getMethod() == 'customercredit' || ($data && isset($data['use_internal_credit']) && $data['use_internal_credit'] > 0)) {
            $session->setUseInternalCredit(true);
        } elseif(Mage::getSingleton('adminhtml/session_quote')->getCustomerId()) {
            return $this;
        }
		
        if (!Mage::getSingleton('adminhtml/session_quote')->getCustomerId() && (!$quote->getCustomer()->getId() || !$session->getUseInternalCredit())){
            return $this;
        }
        if (!$quote->getCustomerCreditTotalsCollected())
        {
            $quote->setCustomerCreditTotalsCollected(true);
            $quote->setBaseCustomerCreditTotal(0);
            $quote->setCustomerCreditTotal(0);
        }
     	$store = Mage::app()->getStore($quote->getStoreId());
        $baseCredit = Mage::getModel('customercredit/credit')
            ->setCustomer($quote->getCustomer())
            ->setWebsiteId($store->getWebsiteId())
            ->loadCredit()
            ->getValue();
        $credit = $quote->getStore()->convertPrice($baseCredit);

        $baseCreditLeft = $baseCredit - $quote->getBaseCustomerCreditTotal();
        $creditLeft     = $credit - $quote->getCustomerCreditTotal();

        if (!$baseCreditLeft)
        {
            return $this;
        }

        if($baseCredit < $address->getBaseGrandTotal())
        {
            $quote->setBaseCustomerCreditTotal($address->getBaseGrandTotal() + $quote->getBaseCustomerCreditTotal());
            $quote->setCustomerCreditTotal($address->getGrandTotal() + $quote->getCustomerCreditTotal());

            $address->setBaseCustomerCreditAmount($baseCreditLeft);
            $address->setCustomerCreditAmount($creditLeft);

            $address->setBaseGrandTotal($address->getBaseGrandTotal() - $baseCreditLeft);
            $address->setGrandTotal($address->getGrandTotal() - $creditLeft);
        }
        else
        {
	        $quote->setBaseCustomerCreditTotal($address->getBaseGrandTotal() + $quote->getBaseCustomerCreditTotal());
	        $quote->setCustomerCreditTotal($address->getGrandTotal() + $quote->getCustomerCreditTotal());

	        $address->setBaseCustomerCreditAmount($address->getBaseGrandTotal());
	        $address->setCustomerCreditAmount($address->getGrandTotal());

	        $address->setBaseGrandTotal(0);
	        $address->setGrandTotal(0);
        }

        $session->setUseInternalCredit(true);

        return $this;
    }

    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
        if (!Mage::helper('customercredit')->isEnabled())
        {
            return $this;
        }

        if ($address->getCustomerCreditAmount())
        {
            $address->addTotal(array(
                'code'=>$this->getCode(),
                'title'=>Mage::helper('customercredit')->__('Internal Credit'),
                'value'=>-$address->getCustomerCreditAmount(),
            ));
        }
        return $this;
    }
}