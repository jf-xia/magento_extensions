<?php

class Topbuy_Ajax_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getLoginPostUrl()
    {
        $params = array();
        if ($this->_getRequest()->getParam(self::REFERER_QUERY_PARAM_NAME)) {
            $params = array(
                self::REFERER_QUERY_PARAM_NAME => $this->_getRequest()->getParam(self::REFERER_QUERY_PARAM_NAME)
            );
        }
        return $this->_getUrl('ajax/account/loginPost', $params);
    }
    
    public function newcouponAction($amount,$email='') {
        $couponCode = "newcu-".uniqid();
        
        /** @var Mage_SalesRule_Model_Rule_Condition_Product $conditionProduct */
        $conditionAddress = Mage::getModel('salesrule/rule_condition_address')
                ->setType('salesrule/rule_condition_address')
                ->setAttribute('base_subtotal')
                ->setOperator('>=')
                ->setValue($amount*10);        
        $condition = Mage::getModel('salesrule/rule_condition_combine')
                ->setConditions(array($conditionAddress));
        /** @var Mage_SalesRule_Model_Coupon $coupon */
        $coupon = Mage::getModel('salesrule/coupon');

        // create rule
        /** @var Mage_SalesRule_Model_Rule $rule */
        $rule = Mage::getModel('salesrule/rule');
        $rule->setName('Subscription$'.$amount.'Coupon-'.$email)
                ->setDescription('Subscription $'.$amount.' Discount Coupon, Conditions-$'.$amount.'0 minimum spend excluding postage')
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
    
    public function getTrackStatus($_item) {
        $order = Mage::getModel('sales/order')->load($_item->getOrderId());
        if ($_item->getQtyOrdered() - $_item->getQtyCanceled() != 0) {
//            if ($_item->getIsProcessed() == 1) {
//                if ($_item->getIsShipped() == 1) {
//                    $imgUrl = 8;
//                } else {
                    $ptime = (time() - strtotime($order->getUpdatedAt())) / 3600;
//                    if ($_item->getShippingtype() == 1) {
                        if ($ptime <= 6) {
                            $imgUrl = 1;
                        } else if (6 < $ptime && $ptime <= 12) {
                            $imgUrl = 2;
                        } else if (12 < $ptime && $ptime <= 18) {
                            $imgUrl = 3;
                        } else if (18 < $ptime && $ptime <= 28) {
                            $imgUrl = 4;
                        } else if (28 < $ptime && $ptime <= 38) {
                            $imgUrl = 5;
                        } else if (38 < $ptime && $ptime <= 48) {
                            $imgUrl = 6;
                        } else if ($ptime > 48) {
                            $imgUrl = 7;
                        }
//                    } else if ($_item->getShippingtype() == 2) {
//                        if ($_item->getDfdropshipflag() < 3) {
//                            $imgUrl = 1;
//                        } else if ($_item->getDfdropshipflag() == 3) {
//                            $imgUrl = 2;
//                        } else if ($_item->getDfdropshipflag() > 3) {
//                            if ($ptime < 12) {
//                                $imgUrl = 3;
//                            } else if (12 < $ptime && $ptime <= 18) {
//                                $imgUrl = 4;
//                            } else if (18 < $ptime && $ptime <= 24) {
//                                $imgUrl = 5;
//                            } else if (24 < $ptime && $ptime <= 48) {
//                                $imgUrl = 6;
//                            } else if ($ptime > 48) {
//                                $imgUrl = 7;
//                            }
//                        }
//                    } else {
//                        $imgUrl = 0;
//                    }
//                }
//            } else {
//                //    not Received, or not Payment Confirmed
//                $imgUrl = 0;
//            }
        }
        if ($order->getStatus()=='canceled'){
            $imgUrl = 0;
        } else if ($order->getStatus()=='complete'){
            $imgUrl = 8;
        }
        return $imgUrl;
    }
    
    /**
     * Create customer account action
     */
    public function createAccount($customer_email,$customer_fname=null,$customer_lname=null,$passwordLength=null,$password=null,$_custom_address=null)
    {
        if (!$customer_email) {
            return 0;
        }
        $customer_email = trim($customer_email);
        if (!$customer_fname){
            $uname = explode("@", $customer_email);
            $customer_fname = $uname[0];
        }
        if (!$passwordLength) {
            $passwordLength=10;
        }
        if (!$customer_lname) {
            $customer_lname=$customer_fname;
        }
//        $session=Mage::getSingleton('customer/session');
        $customer = Mage::getModel('customer/customer');
        $customer->setWebsiteId(Mage::app()->getWebsite()->getId());
        $customer->loadByEmail($customer_email);
        /*
        * Check if the email exist on the system.
        * If YES,  it will not create a user account. 
        */
        if(!$customer->getId()) {
            $customer->setEmail($customer_email); 
            $customer->setFirstname($customer_fname);
            $customer->setLastname($customer_lname);
            if($password){
                $customer->setPassword($password);
            }else{
                $customer->generatePassword($passwordLength);
            }
            //
            $customAddress = Mage::getModel('customer/address');
            try{
                //the save the data and send the new account email.
                $customer->save();
                $customer->sendNewAccountEmail( 'registered', '',Mage::app()->getStore()->getId());
//                $session->setCustomerAsLoggedIn($customer);
                if ($_custom_address) {
                    $customAddress->setData($_custom_address)
                                ->setCustomerId($customer->getId())
                                ->setIsDefaultBilling('1')
                                ->setIsDefaultShipping('1')
                                ->setSaveInAddressBook('1');
                    try {
                        $customAddress->save();
                    } catch (Exception $ex) {
                        //Zend_Debug::dump($ex->getMessage());
                    }                
                }
            } catch(Exception $ex){
                return $ex;
            }
        }
        return $customer;
    }
}
	 