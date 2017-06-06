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
 * @copyright  Copyright (c) 2012 MageWorx (http://www.mageworx.com/)
 * @license    http://www.mageworx.com/LICENSE-1.0.html
 */

/**
 * Customer Credit extension
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @author     MageWorx Dev Team
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
        if (!Mage::helper('customercredit')->isEnabled()) return false;                
        $customer = $observer->getEvent()->getCustomer();
        $customerCredit = Mage::getModel('customercredit/credit');
        if (($data = $customer->getCustomerCreditData()) && !empty($data['value_change'])) {
            
            // no minus
            if ((floatval($data['credit_value']) + floatval($data['value_change'])) < 0 ) $data['value_change'] = floatval($data['credit_value'])*-1;
            
            $customerCredit->setData($data)->setCustomer($customer)->save();
            
            // if send email
            if (Mage::helper('customercredit')->isSendNotificationBalanceChanged()) {                
                Mage::helper('customercredit')->sendNotificationBalanceChangedEmail($customer);
            }
            
        }
    }

    public function collectQuoteTotalsBefore(Varien_Event_Observer $observer)
    {
        $quote = $observer->getEvent()->getQuote();
        $quote->setCustomerCreditTotalsCollected(false);
    }

    public function placeOrderBefore(Varien_Event_Observer $observer) {
        
        if (!Mage::helper('customercredit')->isEnabled()) return;

        $order = $observer->getEvent()->getOrder();
        /* @var $order Mage_Sales_Model_Order */
        if ($order->getBaseCustomerCreditAmount() > 0) {
            
            $credit = Mage::helper('customercredit')->getCreditValue($order->getCustomerId(), Mage::app()->getStore($order->getStoreId())->getWebsiteId());            
            
            if (($order->getBaseCustomerCreditAmount() - $credit) >= 0.0001) {
                Mage::getSingleton('checkout/type_onepage')
                    ->getCheckout()
                    ->setUpdateSection('payment-method')
                    ->setGotoSection('payment');

                Mage::throwException(Mage::helper('customercredit')->__('Not enough Credit Amount to complete this Order.'));
            }
        }
    }

    public function reduceCustomerCreditValue(Varien_Event_Observer $observer) {
        if (!Mage::helper('customercredit')->isEnabled()) return false;
        $order = $observer->getEvent()->getOrder();
        /* @var $order Mage_Sales_Model_Order */
        if ($order->getBaseCustomerCreditAmount() > 0) {
            //reduce credit value
            Mage::getModel('customercredit/credit')->useCredit($order);
            return true;            
        }
        return false;
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

//    public function registerCreditmemoBefore(Varien_Event_Observer $observer)
//    {
//        $creditmemo = $observer->getEvent()->getCreditmemo();
//        return $this;
//    }

           
    public function refundCreditmemo(Varien_Event_Observer $observer) {                
        
        $creditmemo = $observer->getEvent()->getCreditmemo();
        $order = $creditmemo->getOrder();        
        
        // get real total
        $baseTotal = $creditmemo->getBaseGrandTotal();
        if ($order->getBaseCustomerCreditAmount()>$order->getBaseCustomerCreditRefunded()) $baseTotal += $order->getBaseCustomerCreditAmount() - $order->getBaseCustomerCreditRefunded();
        $baseTotal = floatval($baseTotal);
        
        // add message Returned credit amount..
        $post = Mage::app()->getRequest()->getParam('creditmemo');                        
        if (isset($post['credit_return'])) {
            $baseCreditAmountReturn = floatval($post['credit_return']);
            // validation
            if ($baseCreditAmountReturn>$baseTotal) $baseCreditAmountReturn = $baseTotal;
        } else {
            $baseCreditAmountReturn = $creditmemo->getBaseCustomerCreditAmount() - $order->getBaseCustomerCreditRefunded();            
        }
        
        if ($baseCreditAmountReturn>0) {
            // set CustomerCreditRefunded
            $order->setBaseCustomerCreditRefunded($order->getBaseCustomerCreditRefunded() + $baseCreditAmountReturn);            
            $creditAmountReturn = $creditmemo->getStore()->convertPrice($baseCreditAmountReturn, false, false);
            $order->setCustomerCreditRefunded($order->getCustomerCreditRefunded() + $creditAmountReturn);                                  

                        
            // set [base_]total_refunded
            $order->setBaseTotalRefunded(($order->getBaseTotalRefunded() - $creditmemo->getBaseGrandTotal()) + ($baseTotal - $baseCreditAmountReturn));
            $total = $creditmemo->getStore()->convertPrice($baseTotal, false, false);
            $order->setTotalRefunded(($order->getTotalRefunded() - $creditmemo->getGrandTotal()) + ($total - $creditAmountReturn));
            
            //print_r($order->getData()); exit;            
            
            // set creditmemo
            //$creditmemo->setBaseGrandTotal($order->getBaseTotalRefunded());
            //$creditmemo->setGrandTotal($order->getTotalRefunded());
            
            
            if (abs($order->getCustomerCreditInvoiced() - $order->getCustomerCreditRefunded())<.0001) {
                $order->setForcedCanCreditmemo(false);
            }
            
            // set message
            $payment = $order->getPayment();
            

            if ($creditmemo->getDoTransaction() && $creditmemo->getInvoice()) {
                // online
                $message = Mage::helper('sales')->__('Refunded amount of %s online.', $payment->getOrder()->getBaseCurrency()->formatTxt($baseTotal - $baseCreditAmountReturn));
            } else {
                // offline
                $message = Mage::helper('sales')->__('Refunded amount of %s offline.', $payment->getOrder()->getBaseCurrency()->formatTxt($baseTotal - $baseCreditAmountReturn));
            }            
            $message .= "<br/>".Mage::helper('customercredit')->__('Returned credit amount: %s.', $payment->getOrder()->getBaseCurrency()->formatTxt($baseCreditAmountReturn));            
            
            
            $historyRefund = $payment->getOrder()->getStatusHistoryCollection()->getLastItem();
            $historyRefund->setComment($message);
        }        
        
        return $this;
    }

    

    public function saveCreditmemoAfter(Varien_Event_Observer $observer) {
        Mage::getModel('customercredit/credit')->refund($observer->getEvent()->getCreditmemo(), Mage::app()->getRequest()->getParam('creditmemo'));        
        return $this;
    }

    public function customercreditRule(Varien_Event_Observer $observer){    	       

        $order = $observer->getEvent()->getOrder();
        
        if ($customerId = $order->getCustomerId()) {
            $store = $order->getStore();
            $customer = Mage::getModel('customer/customer')->setStore($store)->load($customerId);
            $customerGroupId = $customer->getGroupId();
	    $websiteId = $store->getWebsiteId();
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

                if (!$result) continue;
                
                // if onetime
                if (isset($rule['is_onetime'])) $isOnetime = $rule['is_onetime']; else $isOnetime = 1;
                $rulesCustomer = Mage::getModel('customercredit/rules_customer')->loadByRuleAndCustomer($rule['rule_id'], $customerId);
                                                
                if (!$rulesCustomer || !$rulesCustomer->getId()) {
                    $rulesCustomer = Mage::getModel('customercredit/rules_customer')->setRuleId($rule['rule_id'])->setCustomerId($customerId)->save();                    
                } else {
                    if ($isOnetime) continue;
                }                
                $creditLog = Mage::getModel('customercredit/credit_log')->loadByOrderAndAction($order->getId(), 3, $rulesCustomer->getId());                    
                if (!$creditLog || !$creditLog->getId()) {
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
        if ($this->reduceCustomerCreditValue($observer)) {            
            $order = $observer->getEvent()->getOrder();
            // if payment of credit is fully
            if ($order->getBaseTotalDue()==0 && $order->canInvoice()) {                
                //$invoiceId = Mage::getModel('sales/order_invoice_api')->create($order->getIncrementId(), array());                
                //$invoice = Mage::getModel('sales/order_invoice')->loadByIncrementId($invoiceId);
                //$invoice->save();
                //$order->setState(Mage_Sales_Model_Order::STATE_COMPLETE, true)->save();
                $savedQtys = array();
                foreach ($order->getAllItems() as $orderItem) {
                    $savedQtys[$orderItem->getId()] = $orderItem->getQtyToInvoice();
                }
                
                $invoice = Mage::getModel('sales/service_order', $order)->prepareInvoice($savedQtys);
                if (!$invoice->getTotalQty()) return $this;                
                $invoice->register();
                $invoice->getOrder()->setIsInProcess(true);
                
                $transactionSave = Mage::getModel('core/resource_transaction')
                    ->addObject($invoice)
                    ->addObject($invoice->getOrder());
                $transactionSave->save();                
            }
        }
        //$this->customercreditRule($observer);        
        return $this;  
        
        
    }       
    
    public function checkCompleteStatusOrder(Varien_Event_Observer $observer) {
        $order = $observer->getEvent()->getOrder();
        if ($order->getStatus()=='complete') {            
            $creditProductSku = Mage::helper('customercredit')->getCreditProductSku();
            $creditQty = 0;
            if ($creditProductSku) {
                $allItems = $order->getAllItems();
                foreach ($allItems as $item) {
                    if ($item->getSku()==$creditProductSku) {
                        $creditQty = intval($item->getQtyInvoiced());
                    }
                }
                if ($creditQty>0) {                    
                    $creditLog = Mage::getModel('customercredit/credit_log')->loadByOrderAndAction($order->getId(), 5);                    
                    if (!$creditLog || !$creditLog->getId()) {                    
                        Mage::getModel('customercredit/credit')
                            ->setCustomerId($order->getCustomerId())
                            ->setWebsiteId($order->getStore()->getWebsiteId())
                            ->setOrder($order)
                            ->setValueChange($creditQty)
                            ->setActionType(5)
                            ->save();
                    }    
                }
            }
            $this->customercreditRule($observer);
            
        }    
        
        return $this;        
    }
    
    
    
    
    
}