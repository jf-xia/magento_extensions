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

class MageWorx_CustomerCredit_Model_Observer
{
    public function saveCodeAfter(Varien_Event_Observer $observer)
    {
        $code = $observer->getEvent()->getCode();
        $code->getLogModel()
            ->setCodeModel($code)
            ->save();
    }

    public function saveCreditAfter(Varien_Event_Observer $observer)
    {
        $credit = $observer->getEvent()->getCredit();
        $credit->getLogModel()
            ->setCreditModel($credit)
            ->save();
    }

    public function prepareCustomerSave(Varien_Event_Observer $observer)
    {
        $customer = $observer->getEvent()->getCustomer();
        $request  = $observer->getEvent()->getRequest();
        if ($data = $request->getPost('customercredit'))
        {
            $customer->setCustomerCreditData($data);
        }
    }

    public function saveCustomerAfter(Varien_Event_Observer $observer)
    {
        $customer = $observer->getEvent()->getCustomer();
        $customerCredit = Mage::getModel('customercredit/credit');
        if (($data = $customer->getCustomerCreditData()) && !empty($data['value_change']))
        {
            $customerCredit->setData($data)
                ->setCustomer($customer)
                ->save();
        }
    }

    public function collectQuoteTotalsBefore(Varien_Event_Observer $observer)
    {
        $quote = $observer->getEvent()->getQuote();
        $quote->setCustomerCreditTotalsCollected(false);
    }

    public function placeOrderBefore(Varien_Event_Observer $observer)
    {
        if (!Mage::helper('customercredit')->isEnabled()) {
            return;
        }

        $order = $observer->getEvent()->getOrder();
        /* @var $order Mage_Sales_Model_Order */
        if ($order->getBaseCustomerCreditAmount() > 0)
        {
            $credit = Mage::getModel('customercredit/credit')
                ->setCustomerId($order->getCustomerId())
                ->setWebsiteId(Mage::app()->getStore($order->getStoreId())->getWebsiteId())
                ->loadCredit()
                ->getValue();

            if (($order->getBaseCustomerCreditAmount() - $credit) >= 0.0001)
            {
                Mage::getSingleton('checkout/type_onepage')
                    ->getCheckout()
                    ->setUpdateSection('payment-method')
                    ->setGotoSection('payment');

                Mage::throwException(Mage::helper('customercredit')->__('Not enough Credit Amount to complete this Order.'));
            }
        }
    }

    public function reduceCustomerCreditValue(Varien_Event_Observer $observer)
    {
        if (!Mage::helper('customercredit')->isEnabled()) {
            return;
        }
        $order = $observer->getEvent()->getOrder();
        /* @var $order Mage_Sales_Model_Order */
        if ($order->getBaseCustomerCreditAmount() > 0)
        {
            //reduce credit value
            Mage::getModel('customercredit/credit')->useCredit($order);
        }
    }

    public function saveInvoiceAfter(Varien_Event_Observer $observer)
    {
        $invoice = $observer->getEvent()->getInvoice();
        $order = $invoice->getOrder();

        if ($invoice->getBaseCustomerCreditAmount()) {
            $order->setBaseCustomerCreditInvoiced($order->getBaseCustomerCreditInvoiced() + $invoice->getBaseCustomerCreditAmount());
            $order->setCustomerCreditInvoiced($order->getCustomerCreditInvoiced() + $invoice->getCustomerCreditAmount());
        }
    }

    public function loadOrderAfter(Varien_Event_Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();

        if ($order->canUnhold()) {
            return $this;
        }

        if ($order->getState() === Mage_Sales_Model_Order::STATE_CANCELED ||
            $order->getState() === Mage_Sales_Model_Order::STATE_CLOSED ) {
            return $this;
        }


        if (abs($order->getCustomerCreditInvoiced() - $order->getCustomerCreditRefunded())<.0001) {
            return $this;
        }
        $order->setForcedCanCreditmemo(true);

        return $this;
    }

    public function registerCreditmemoBefore(Varien_Event_Observer $observer)
    {
        $creditmemo = $observer->getEvent()->getCreditmemo();
        return $this;
    }

    public function refundCreditmemo(Varien_Event_Observer $observer)
    {
        $creditmemo = $observer->getEvent()->getCreditmemo();
        $order = $creditmemo->getOrder();
        if ($creditmemo->getBaseCustomerCreditAmount())
        {
            $order->setBaseCustomerCreditRefunded($order->getBaseCustomerCreditRefunded() + $creditmemo->getBaseCustomerCreditAmount());
            $order->setCustomerCreditRefunded($order->getCustomerCreditRefunded() + $creditmemo->getCustomerCreditAmount());

            if (abs($order->getCustomerCreditInvoiced() - $order->getCustomerCreditRefunded())<.0001) {
                $order->setForcedCanCreditmemo(false);
            }
        }
        return $this;
    }

    /*public function refundCustomerCredit(Varien_Event_Observer $observer)
    {
        $payment = $observer->getEvent()->getPayment();
        $creditmemo = $observer->getEvent()->getCreditmemo();
        return $this;
    }*/

    public function saveCreditmemoAfter(Varien_Event_Observer $observer)
    {                    
        Mage::getModel('customercredit/credit')->refund($observer->getEvent()->getCreditmemo());        
        return $this;
    }

    public function customercreditRule(Varien_Event_Observer $observer){    	       

        $order = $observer->getEvent()->getOrder();
        
        if ($customerId = Mage::getSingleton('customer/session')->getCustomerId()) {

            $customer =  Mage::getSingleton('customer/session')->getCustomer(); //$observer->getEvent()->getCustomer();
            $customerGroupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
	    $websiteId = Mage::app()->getStore()->getWebsiteId();
			
	    $ruleModel = Mage::getResourceModel('customercredit/rules_collection');                                    
            
	    $ruleModel->setValidationFilter($websiteId, $customerGroupId);
	    foreach ($ruleModel->getData() as $rule) {                                
	    	$conditions = unserialize($rule['conditions_serialized']);                
                foreach ($conditions['conditions'] as $key => $condition) {

                    $success[$key] = true;

                    if ($condition['attribute'] == 'registration'){

                            $regArr = explode(' ', $customer['created_at'], 2);
                            $regDate = explode('-', $regArr[0], 3);
                            $regTimestamp = mktime(0, 0, 0, $regDate[1], $regDate[2], $regDate[0]);

                            $ruleRegDate = explode('-', $condition['value'], 3);
                            $ruleRegTimestamp = mktime(0, 0, 0, $ruleRegDate[1], $ruleRegDate[2], $ruleRegDate[0]);

                            if (!version_compare($regTimestamp, $ruleRegTimestamp, $condition['operator'])){
                                    $success[$key] = false;
                            }


                    } elseif ($condition['attribute'] == 'total_amount'){

                            $orders = Mage::getResourceModel('sales/order_collection');
                            $orders->getSelect()
                                            ->reset(Zend_Db_Select::WHERE)
                                            ->columns(array('grand_subtotal' => 'SUM(subtotal)'))
                                            ->where('customer_id='.$customerId)
                                            ->group('customer_id');

                            $data = $orders->getData();

                            if (count($data) != 1){
                                    $success[$key] = false;
                            }
                            if (!version_compare($data[0]['grand_subtotal'], $condition['value'], $condition['operator'])){
                                    $success[$key] = false;
                            }

                    } else {                        
                        // product atributes:
                        $success[$key] = false;                        
                        $products = $order->getAllItems();
                        $conditionProductModel = Mage::getModel($condition['type'])->loadArray($condition);                                                                                                
                        foreach($products as $item) {
                            $product = Mage::getModel('catalog/product')->load($item->getProductId());                                                                                                                                             
                            if ($conditionProductModel->validate($product)) {                            
                                $success[$key] = true;
                                break;
                            }                                    
                        }                                                
                    }                    
                    
                }

	    	$result = true;
                switch ($conditions['aggregator']){
                    case 'any':
                        switch ($conditions['value']){
                            case '1':
                                if(!in_array(true, $success)){
                                        $result = false;
                                }
                                break;
                            case '0':
                                if (!in_array(false, $success)){
                                        $result = false;
                                }
                                break;
                        }
                        break;
                    case 'all':
                        switch ($conditions['value']){
                            case 1:
                                if (in_array(false, $success)){
                                        $result = false;
                                }
                                break;
                            case 0:
                                if (in_array(true, $success)){
                                        $result = false;
                                }
                                break;
                        }
                        break;
                }

                

                $ruleCustomerModel = Mage::getResourceModel('customercredit/rules_customer_collection');
                $ruleCustomerModel->getSelect()->where('rule_id='.$rule['rule_id'].' AND customer_id='.$customerId);
                if(count($ruleCustomerModel->getItems())){
                    $result = false;
                }

                if ($result){
                    
                    $rulesCustomer = Mage::getModel('customercredit/rules_customer')->setRuleId($rule['rule_id'])->setCustomerId($customerId)->save();                                        
                    Mage::getModel('customercredit/credit')
                                ->setCustomerId($customerId)
                                ->setWebsiteId($websiteId)
                                ->setOrder($order)
                                ->setRuleName($rule['name'])
                                ->setRulesCustomerId($rulesCustomer->getId())                            
                                ->setValueChange($rule['credit'])
                                ->setActionType(3)
                                ->save();                    
                    
                }

	    }

    	}

    }
    
    public function returnCredit(Varien_Event_Observer $observer){                                                                            
        Mage::getModel('customercredit/credit')->cancel($observer->getEvent()->getOrder());                
        return $this;        
    }
    
    public function placeOrderAfter(Varien_Event_Observer $observer) {
        $this->reduceCustomerCreditValue($observer);
        $this->customercreditRule($observer);        
        return $this;        
    }
    
    
    
}