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

class MageWorx_CustomerCredit_Model_Credit extends Mage_Core_Model_Abstract
{
	protected $_eventPrefix	= 'customercredit_credit';
	protected $_eventObject  = 'credit';

	protected function _construct()
	{
		$this->_init('customercredit/credit');
	}

	public function loadCredit()
	{
	    $this->_prepare();
	    return $this;
	}

	public function getValue()
	{
	    return (float)$this->getData('value');
	}

	protected function _beforeSave()
	{
	    $this->_prepare();

	    //set log data            
            
            if ($this->hasCreditmemo()) {
                $this->getLogModel()->setActionType(MageWorx_CustomerCredit_Model_Credit_Log::ACTION_TYPE_REFUNDED);            
            } elseif ($this->hasOrder()) {
                $this->getLogModel()->setActionType(MageWorx_CustomerCredit_Model_Credit_Log::ACTION_TYPE_USED);
            }

            if (!$this->getLogModel()->hasActionType()) {
                $this->getLogModel()->setActionType(MageWorx_CustomerCredit_Model_Credit_Log::ACTION_TYPE_UPDATED);
            }

            if ($this->getActionType()) {
                $this->getLogModel()->setActionType($this->getActionType());
            }
            
            if ($this->hasOrder()) {
                $this->getLogModel()->setOrderId($this->getOrder()->getId());
            }
            
            if ($this->getRulesCustomerId()>0) $this->getLogModel()->setRulesCustomerId($this->getRulesCustomerId());

            $this->getLogModel()->setValue($this->getValue());
            $this->getLogModel()->setValueChange($this->getValueChange());

            return parent::_beforeSave();
	}

	/**
	 * Validate and prepare data before save
	 * @return unknown
	 */
	protected function _prepare()
	{
	    //validate customer
	    if (!$this->getCustomerId())
	    {
	        if ($this->getCustomer() && $this->getCustomer()->getId())
                $this->setCustomerId($this->getCustomer()->getId());
	    }
	    /*if ($this->getCustomerId() && !$this->getCustomer())
	    {
	        $this->setCustomer(Mage::getModel('customer/customer')->load($this->getCustomerId()));
	    }
	    else
	    {
	        Mage::throwException(Mage::helper('customercredit')->__('Customer ID is not set'));
	    }*/
	    if (!$this->getCustomerId() && !Mage::getSingleton('admin/session')->getUser())
	    {
		return false;
	        //Mage::throwException(Mage::helper('customercredit')->__('Customer ID is not set'));
	    }

	    //validate website
	    if (!$this->getWebsiteId())
	    {
	        if (!Mage::app()->getStore()->isAdmin())
	           $this->setWebsiteId(Mage::app()->getStore()->getWebsiteId());
	    }
        if (!$this->getWebsiteId() && !Mage::getSingleton('admin/session')->getUser())
	    {
	        Mage::throwException(Mage::helper('customercredit')->__('Website ID is not set'));
	    }

	    //load credit data
	    $this->getResource()->loadByCustomerAndWebsite($this, $this->getCustomerId(), $this->getWebsiteId());

	    //validate credit value
	    if ($this->hasValueChange())
	    {
            $value = (float)$this->getValue();
	        $add = (float)$this->getValueChange();
	        if ($value + $add < 0)
	        {
	            $add = -1 * $value;
	        }
	        $this->setValueChange($add);
            $this->setValue($value + $add);
	    }

	    return $this;
	}

	/**
	 * Refill the credit using Recharge Code
	 * Method should be called before changing recharge code credit value and saving
	 * @var MageWorx_CustomerCredit_Model_Code $code
	 * @return MageWorx_CustomerCredit_Model_Credit
	 */
	public function refill($code)
	{
	    $this->setValueChange($code->getCredit())
	         ->setRechargeCode($code->getCode())
	         ->setCustomerId($code->getCustomerId())
	         ->setWebsiteId($code->getWebsiteId())
	         ->save();
	    return $this;
	}

	/**
	 * Use credit to purchase order
	 * @param Mage_Sales_Model_Order $order
	 * @return MageWorx_CustomerCredit_Model_Credit
	 */
	public function useCredit($order)
	{
	    Mage::getSingleton('checkout/session')->setInternalCredit();

	    $this->setValueChange(-$order->getBaseCustomerCreditAmount())
	       ->setOrder($order)
	       ->setCustomerId($order->getCustomerId())
	       ->setWebsiteId(Mage::app()->getStore($order->getStoreId())->getWebsiteId())
	       ->save();
        return $this;
	}

	/**
	 * Return ordered amount to customer's credit after refund
	 * @param Mage_Sales_Model_Order_Creditmemo $creditmemo
	 * @return MageWorx_CustomerCredit_Model_Credit
	 */
	public function refund($creditmemo)
	{
	    
            // cancel credit rule
            $order = $creditmemo->getOrder();            
            $orderCreditRules = Mage::getResourceModel('customercredit/credit_log_collection')->addOrderFilter($order->getId())->addActionTypeFilter(3);            
            if ($orderCreditRules) {
                $minusCredit = 0;
                foreach ($orderCreditRules as $rule) {                    
                    
                    $rulesCustomer = Mage::getModel('customercredit/rules_customer')->load($rule->getRulesCustomerId());
                    if ($rulesCustomer) {
                        $rulesCustomer->delete();
                        $minusCredit += $rule->getValueChange();
                    }
                    
                }
                if ($minusCredit>0) {
                    $this->setValueChange(-$minusCredit)
                        ->setActionType(2)
                        ->setCreditmemo($creditmemo)    
                        ->setCreditRule(1)
                        ->setOrder($order)
                        ->setCustomerId($order->getCustomerId())
                        ->setWebsiteId(Mage::app()->getStore($order->getStoreId())->getWebsiteId())
                        ->save();
                }                
            }
            
            
            // refund and return credit
            if ($creditmemo->getBaseCustomerCreditAmount()) {
                $this->setValueChange($creditmemo->getBaseCustomerCreditAmount())
                   ->setActionType(2)     
                   ->setCreditmemo($creditmemo)
                   ->setOrder($creditmemo->getOrder())
                   ->setCustomerId($creditmemo->getOrder()->getCustomerId())
                   ->setWebsiteId(Mage::app()->getStore($creditmemo->getOrder()->getStoreId())->getWebsiteId())
                   ->save();
            }    
	    return $this;
	}

        public function cancel($order)
	{	    
            
            // cancel credit rule
            $orderCreditRules = Mage::getResourceModel('customercredit/credit_log_collection')->addOrderFilter($order->getId())->addActionTypeFilter(3);            
            if ($orderCreditRules) {
                $minusCredit = 0;
                foreach ($orderCreditRules as $rule) {
                    
                    $rulesCustomer = Mage::getModel('customercredit/rules_customer')->load($rule->getRulesCustomerId());
                    if ($rulesCustomer) {
                        $rulesCustomer->delete();
                        $minusCredit += $rule->getValueChange();
                    }                    
                    
                }
                if ($minusCredit>0) {
                    $this->setValueChange(-$minusCredit)
                        ->setActionType(4)
                        ->setCreditRule(1)    
                        ->setOrder($order)
                        ->setCustomerId($order->getCustomerId())
                        ->setWebsiteId(Mage::app()->getStore($order->getStoreId())->getWebsiteId())
                        ->save();
                }                
            }
            
            // cancel and return credit
            if ($order->getBaseCustomerCreditAmount()>0) {
                $this->setValueChange($order->getBaseCustomerCreditAmount())
                    ->setActionType(4)
                    ->setOrder($order)
                    ->setCustomerId($order->getCustomerId())
                    ->setWebsiteId(Mage::app()->getStore($order->getStoreId())->getWebsiteId())
                    ->save();
            }
	    return $this;
	}
        
        
	protected function _afterSave()
	{
	    parent::_afterSave();
	    $this->getLogModel()->unsetData();
	}

	/**
     * Retreive log model instance
     *
     * @return MageWorx_CustomerCredit_Model_Credit_Log
     */
    public function getLogModel()
    {
        if (!$this->hasData('log_model'))
        {
            $this->setLogModel(Mage::getModel('customercredit/credit_log'));
        }
        return $this->getData('log_model');
    }
}