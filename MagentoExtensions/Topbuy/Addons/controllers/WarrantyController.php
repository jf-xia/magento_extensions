<?php

class Topbuy_Addons_WarrantyController extends Mage_Core_Controller_Front_Action {
    
    public function getStepArray($warrid, $orderId, $pid, $serialno) {
        $stepHtml = array();
        $session = Mage::getSingleton("customer/session"); // current login customer 
        $customerId = $session->getCustomerId();
        $orderCollection = Mage::getModel('sales/order')->getCollection()->addFieldToFilter('status', 'complete');
        $orderCollection->getSelect()->where('customer_id =' . $customerId);
        $stepHtml = array(array(), array(), array(), array());
        foreach ($orderCollection as $order) {
            foreach ($order->getAllItems() as $item) {
                $warrantyMap = Mage::getModel('addons/warrantymap')->getCollection()->addFieldToFilter('idmagproduct', $item->getProductId())->getFirstItem();
                $warrantyReg = Mage::getModel('addons/warrantyregisterrecord')->getCollection()->addFieldToFilter('warrantyproductmag', $item->getItemId())->getFirstItem();
                if ($item->getQtyInvoiced() > 0 && $warrantyMap->hasData() && !$warrantyReg->getSerialsno()) {
                    if ($item->getProductId() == $warrid) {
                        $stepHtml[1][$item->getProductId()] = '<option selected value="' . $item->getProductId() . '|' . $item->getItemId() . '" >' . $item->getName() . '</option>';
                    } else {
                        $stepHtml[1][$item->getProductId()] = '<option value="' . $item->getProductId() . '|' . $item->getItemId() . '" >' . $item->getName() . '</option>';
                    }
                }
            }
        }
        if ($warrid) {
            $warrantyMapd = Mage::getModel('addons/warrantymap')->getCollection()->addFieldToFilter('idmagproduct', $warrid)->getFirstItem();
            foreach ($orderCollection as $order) {
                foreach ($order->getAllItems() as $item) {
                    if ($item->getPrice() > $warrantyMapd->getPricefrom() && $item->getPrice() < $warrantyMapd->getPriceto()) {
                        if ($order->getIncrementId() == $orderId) {
                            $stepHtml[2][$order->getIncrementId()] = '<option selected value="' . $order->getIncrementId() . '" >' . $order->getIncrementId() . '-' . $order->getCustomerFirstname() . '-' . $order->getUpdatedAt() . '-' . $item->getPrice() . '</option>';
                        } else {
                            $stepHtml[2][$order->getIncrementId()] = '<option value="' . $order->getIncrementId() . '" >' . $order->getIncrementId() . '-' . $order->getCustomerFirstname() . '-' . $order->getUpdatedAt() . '-' . $item->getPrice() . '</option>';
                        }
                    }
                }
            }
        } else {
            $stepHtml[2] = 0;
        }
        if ($warrid && $orderId) {
            $orderd = Mage::getModel('sales/order')->loadByIncrementId($orderId);
            foreach ($orderd->getAllItems() as $item) {
                if ($item->getPrice() > $warrantyMapd->getPricefrom() && $item->getPrice() < $warrantyMapd->getPriceto()) {
                    if ($item->getItemId() == $pid) {
                        $stepHtml[3][$item->getItemId()] = '<option selected value="' . $item->getItemId() . '" >' . $item->getName() . '-' . $item->getPrice() . '</option>';
                    } else {
                        $stepHtml[3][$item->getItemId()] = '<option value="' . $item->getItemId() . '" >' . $item->getName() . '-' . $item->getPrice() . '</option>';
                    }
                }
            }
        } else {
            $stepHtml[3] = 0;
        }
        if ($warrid && $orderId && $pid) {
            $stepHtml[4] = 1;
        } else {
            $stepHtml[4] = 0;
        }
        return $stepHtml;
    }

    public function IndexAction() {
        $session = Mage::getSingleton("customer/session");
        if (!$session->isLoggedIn()) {
            $session->setBeforeAuthUrl(Mage::helper("core/url")->getCurrentUrl());
            $this->_redirect('customer/account/login');
            return;
        } else {
            $warrid = $this->getRequest()->getParam('warrid');
            $warrArray = explode("|", $warrid);
            if (count($warrArray) > 1) {
                $warrid = $warrArray[0];
                $warrItemid = $warrArray[1];
            }
            $orderId = $this->getRequest()->getParam('orderid');
            $pid = $this->getRequest()->getParam('pid');
            $serialno = $this->getRequest()->getParam('serialno');
            $stepHtml = $this->getStepArray($warrid, $orderId, $pid, $serialno);
            Mage::register('stepHtml', $stepHtml);
            if ($warrid && $orderId && $pid && $serialno) {
                try {
                    $orderWarrantyItem = Mage::getModel('sales/order_item')->load($warrItemid);
                    $orderWarrantyItem->setName($orderWarrantyItem->getName() . " (Warranty Registed)")->save();
                    $orderItem = Mage::getModel('sales/order_item')->load($pid);
                    $orderItem->setName($orderItem->getName() . " (Registed " . $orderWarrantyItem->getName() . ")")->save();
                    Mage::getModel('addons/warrantyregisterrecord')
                            ->setWarrantyproductmag($warrItemid)
                            ->setItemproductmag($pid)
                            ->setEntrydate(now())
                            ->setSerialsno($serialno)
                            ->save();
                    //Create an array of variables to assign to template
                    $email = $session->getCustomer()->getEmail();
                    $name = $session->getCustomer()->getName();
                    $emailVariables = array();
                    $emailVariables['warranty'] = $orderWarrantyItem->getName();
                    $emailVariables['product'] = $orderItem->getName();
                    $emailVariables['serialno'] = $serialno;
                    $this->sendWarrantyEmail($email, $name, $emailVariables);

                    $message = $this->__('Warranty Register Success!');
                    $session->addSuccess($message);
                    session_write_close();
                    $this->_redirect('customer/account');
                    return;
                } catch (Mage_Core_Exception $e) {
                    $this->_fault('warrantyregisterrecord insert', $e->getMessage());
                    return false;
                }
            }
            $this->loadLayout();
            $this->getLayout()->getBlock("head")->setTitle($this->__("Extended Warranty Registration"));
            $this->renderLayout();
        }
    }
    
    public function sendWarrantyEmail($email,$name,$emailVariables = Array()) {
        $templateId = Mage::getStoreConfig('addons/addons_email_warranty');
        $mailSubject = 'Warranty Registration';
//        $sender = Array('name' => 'Topbuy', 'email' => 'jackxia5@gmail.com');
        $storeId = Mage::app()->getStore()->getId();
        $translate = Mage::getSingleton('core/translate');
        Mage::getModel('core/email_template')
                ->setTemplateSubject($mailSubject)
                ->sendTransactional($templateId,
                        Mage::getStoreConfig('newsletter/subscription/confirm_email_identity'), 
                        $email, $name, $emailVariables, $storeId);        
        $translate->setTranslateInline(true);
    }

}
/**
 *

    public function sendBugEmail($email,$name,$emailVariables = Array()) {
        $templateId = 'addons_email_bug';
        $mailSubject = 'Please accept our $50 discount coupon';
        $storeId = Mage::app()->getStore()->getId();
        $translate = Mage::getSingleton('core/translate');
        $emailtemplate = Mage::getModel('core/email_template')
                ->setTemplateSubject($mailSubject)
                ->sendTransactional($templateId,
                        Mage::getStoreConfig('newsletter/subscription/confirm_email_identity'), 
                        $email, $name, $emailVariables, $storeId);        
        $translate->setTranslateInline(true);
        return $emailtemplate;
    }
            
    public function tAction() {
        $email = 'jackxia5@gmail.com';
        $name = 'Jack';
        $emailVariables = array();
        $emailVariables['name'] = $name;
        $emailVariables['coupon1'] = $this->bugcoupon(10,$email);
        $emailVariables['coupon2'] = $this->bugcoupon(20,$email);
        $emailVariables['coupon3'] = $this->bugcoupon(20,$email);
        $this->sendBugEmail($email, $name, $emailVariables) ;      
    }

    
    public function bugAction() {
        $feedback = Mage::getModel('fancyfeedback/fancyfeedback')->getCollection();
        foreach ($feedback as $item){
            $email = trim($item->getEmail());
            $name=explode('www',$item->getName());
            $name = $name[0];
            $emailVariables = array();
            $emailVariables['name'] = $name;
            $emailVariables['coupon1'] = $this->bugcoupon(10,$email);
            $emailVariables['coupon2'] = $this->bugcoupon(20,$email);
            $emailVariables['coupon3'] = $this->bugcoupon(20,$email);
            $this->sendBugEmail($email, $name, $emailVariables);     
            $item->setReply('sended coupon')->save();
        }  
    }
    
    public function bugcoupon($amount,$email='') {
        $couponCode = "bug-".uniqid();
        
        $conditionAddress = Mage::getModel('salesrule/rule_condition_address')
                ->setType('salesrule/rule_condition_address')
                ->setAttribute('base_subtotal')
                ->setOperator('>=')
                ->setValue($amount);        
        $condition = Mage::getModel('salesrule/rule_condition_combine')
                ->setConditions(array($conditionAddress));
        $coupon = Mage::getModel('salesrule/coupon');

        $rule = Mage::getModel('salesrule/rule');
        $rule->setName('Subscription$'.$amount.'Coupon-'.$email)
                ->setDescription('Subscription $'.$amount.' Discount Coupon, Conditions-$'.$amount.' minimum spend excluding postage')
                ->setFromDate(date('Y-m-d'))
                ->setToDate(date(strtotime(Mage::getModel('core/date')->date())+(2*7*24*3600)))
                ->setCustomerGroupIds(array(0,1,2,3,4))
                ->setUsesPerCustomer(1)
                ->setIsActive(1)
                ->setSimpleAction(Mage_SalesRule_Model_Rule::CART_FIXED_ACTION)
                ->setSortOrder(0)
                ->setDiscountAmount($amount)
                ->setDiscountQty(null)
                ->setDiscountStep('0')
                ->setSimpleFreeShipping('0')
                ->setApplyToShipping('0')
                ->setStopRulesProcessing(0)
                ->setIsRss(0)
                ->setWebsiteIds(array(1))
                ->setCouponType(Mage_SalesRule_Model_Rule::COUPON_TYPE_SPECIFIC)
                ->setConditions($condition)
                ->save();

        // create coupon
        $coupon->setId(null)
                ->setRuleId($rule->getRuleId())
                ->setCode($couponCode)
                ->setUsageLimit(1)
                ->setUsagePerCustomer(1)
                ->setIsPrimary(1)
                ->setCreatedAt(time())
                ->setType(2)
                ->save();
        return $couponCode;
    } 
 */