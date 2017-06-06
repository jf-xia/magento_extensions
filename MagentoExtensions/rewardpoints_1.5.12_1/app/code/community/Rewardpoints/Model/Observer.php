<?php
/**
 * J2T RewardsPoint2
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@j2t-design.com so we can send you a copy immediately.
 *
 * @category   Magento extension
 * @package    RewardsPoint2
 * @copyright  Copyright (c) 2009 J2T DESIGN. (http://www.j2t-design.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Rewardpoints_Model_Observer extends Mage_Core_Model_Abstract {
    
    const XML_PATH_NOTIFICATION_NOTIFICATION_DAYS       = 'rewardpoints/notifications/notification_days'; 
    const XML_PATH_POINTS_DURATION                      = 'rewardpoints/default/points_duration';
    
    //J2T Check referral
    public function checkReferral($observer){
        $event = $observer->getEvent();
        $invoice = $event->getInvoice();
        $order = $invoice->getOrder();
        //$order = $observer->getEvent()->getInvoice()->getOrder();
        
        //load referral by referral customer id
        $referralModel = Mage::getModel('rewardpoints/referral');
        $referralModel->loadByChildId($order->getCustomerId());
        if ($referral_id = $referralModel->getRewardpointsReferralId()){
            //load points by referral_id
            $pointsModel = Mage::getModel('rewardpoints/stats');
            $pointsModel->loadByReferralId($referral_id, $order->getCustomerId());
            if ($order_id = $pointsModel->getOrderId()){
                if ($order_id != $order->getIncrementId()){
                    //check if order has correct status
                    if($loadedOrder = Mage::getModel('sales/order')->loadByIncrementId($order_id)){
                        $statuses = Mage::getStoreConfig('rewardpoints/default/valid_statuses', $loadedOrder->getStoreId());
                        $order_states = explode(",", $statuses);                        
                        $status_state = Mage::getStoreConfig('rewardpoints/default/status_used', $loadedOrder->getStoreId());
                        
                        if (!in_array($loadedOrder->getStatus(),$order_states) && $status_state == 'status'){
                            //modify order_id to current order id (from invoice)
                            //if (in_array($order->getStatus(),$order_states)){
                                $pointsModel->setOrderId($order->getIncrementId());
                                $pointsModel->save();
                            //}
                        } else if(!in_array($loadedOrder->getState(),$order_states) && $status_state == 'state'){
                            //modify order_id to current order id (from invoice)
                            //if (in_array($order->getState(),$order_states)){
                                $pointsModel->setOrderId($order->getIncrementId());
                                $pointsModel->save();
                            //}
                        }
                    }
                }
            }
        }
    }
    
    
    public function aggregateRewardpointsData(){
        //1. Get all points per customer
        //1.1 Browse all store ids : $store_id
        $allStores = Mage::app()->getStores();
        foreach ($allStores as $_eachStoreId => $val) 
        {
            /*$duration = Mage::getStoreConfig(self::XML_PATH_POINTS_DURATION, $store_id);
            if ($duration){*/
                $store_id = Mage::app()->getStore($_eachStoreId)->getId();
                $days = Mage::getStoreConfig(self::XML_PATH_NOTIFICATION_NOTIFICATION_DAYS, $store_id);
                $points = Mage::getModel('rewardpoints/stats')
                        ->getResourceCollection()
                        ->addFinishFilter($days)
                        ->addValidPoints($store_id);
                if ($points->getSize()){
                    foreach ($points as $current_point){
                        $customer_id = $current_point->getCustomerId();
                        $points = $current_point->getNbCredit();
                        if (Mage::getStoreConfig('rewardpoints/default/flatstats', $store_id)){
                            $points_received = Mage::getModel('rewardpoints/flatstats')->collectPointsCurrent($customer_id, $store_id);
                        } else {
                            $points_received = Mage::getModel('rewardpoints/stats')->getPointsCurrent($customer_id, $store_id);
                        }
                        
                        //2. check if total points >= points available
                        if ($points_received >= $points){
                            //3. send notification email
                            $customer = Mage::getModel('customer/customer')->load($customer_id);
                            Mage::getModel('rewardpoints/stats')->sendNotification($customer, $store_id, $points, $days);
                        }
                    }
                }
            //}            
        }        
    }

    public function pointsRefresh($observer){
        $userId = Mage::getSingleton('rewardpoints/session')->getReferralUser();
        Mage::getSingleton('rewardpoints/session')->unsetAll();
        Mage::getSingleton('rewardpoints/session')->setReferralUser($userId);
    }

    public function recordPointsUponRegistration($observer){
        if (Mage::getStoreConfig('rewardpoints/registration/registration_points', Mage::app()->getStore()->getId()) > 0){
            //check if points already earned
            $customerId = $observer->getEvent()->getCustomer()->getEntityId();
            $points = Mage::getStoreConfig('rewardpoints/registration/registration_points', Mage::app()->getStore()->getId());
            //$orderId = -2;
            $this->recordPoints($points, $customerId, Rewardpoints_Model_Stats::TYPE_POINTS_REGISTRATION, false);
            
        }
    }
    
    
    public function recordPointsAdminEvent($observer) {
        $event = $observer->getEvent();
        $customer = $event->getCustomer();
        $request = $event->getRequest();
       
        if ($data = $request->getPost()){        
            if (isset($data['points_current']) || isset($data['points_spent'])){
                if ($data['points_current'] > 0 || $data['points_spent'] > 0){
                    $model = Mage::getModel('rewardpoints/stats');
                    if (trim($data['date_start'])){                    
                        $date = Mage::app()->getLocale()->date($data['date_start'], Zend_Date::DATE_SHORT, null, false);
                        $time = $date->getTimestamp();
                        $model->setDateStart(Mage::getModel('core/date')->gmtDate(null, $time));
                    } else {                    
                        $model->setDateStart(Mage::getModel('core/date')->gmtDate(null, Mage::getModel('core/date')->timestamp(time())));
                    }
                    if (trim($data['date_end'])){
                        if ($data['date_end'] != ""){
                            $date = Mage::app()->getLocale()->date($data['date_end'], Zend_Date::DATE_SHORT, null, false);
                            $time = $date->getTimestamp();
                            $model->setDateEnd(Mage::getModel('core/date')->gmtDate(null, $time));
                        }
                    }
                    if (trim($data['points_current'])){
                        $model->setPointsCurrent($data['points_current']);
                    }
                    if (trim($data['points_spent'])){
                        $model->setPointsSpent($data['points_spent']);
                    }

                    $store_ids = array();
                    if ($store_id = $customer->getStore()->getId()){
                        $model->setStoreId($store_id);
                    } else {
                        $allStores = Mage::app()->getStores();
                        foreach ($allStores as $_eachStoreId => $val) {
                            $store_ids[] = Mage::app()->getStore($_eachStoreId)->getId();
                        }
                        $model->setStoreId(implode(",",$store_ids));
                    }

                    $model->setCustomerId($customer->getId());
                    
                    $model->setOrderId(Rewardpoints_Model_Stats::TYPE_POINTS_ADMIN);                
                    $model->save();

                    //flatstats record
                    if ($store_id = $customer->getStore()->getId()){
                        Mage::getModel('rewardpoints/flatstats')->processRecordFlat($customer->getId(), $store_id);
                    } else {
                        $allStores = Mage::app()->getStores();
                        foreach ($allStores as $_eachStoreId => $val) {
                            Mage::getModel('rewardpoints/flatstats')->processRecordFlatAction($customer->getId(), Mage::app()->getStore($_eachStoreId)->getId());
                        }
                    }
                }
                
            }
        }
    }
    

    public function recordPointsForOrderEvent($observer) {
        
        //J2T magento 1.3.x fix
        if (version_compare(Mage::getVersion(), '1.4.0', '<')){
            //$order = new Mage_Sales_Model_Order();
            $order = Mage::getModel('sales/order');
            $incrementId = Mage::getSingleton('checkout/session')->getLastRealOrderId();
            $order->loadByIncrementId($incrementId);

            $quote = Mage::getModel('sales/quote');
            $quoteId = Mage::getSingleton('checkout/session')->getLastQuoteId();
            $quote->load($quoteId);
            $this->pointsOnOrder($order, $quote);

        } else {
            $event = $observer->getEvent();
            $order = $event->getOrder();
            $quote = $event->getQuote();

            $this->pointsOnOrder($order, $quote);
        }
        
        
        /*$event = $observer->getEvent();
        $order = $event->getOrder();
        $quote = $event->getQuote();
        
        $this->pointsOnOrder($order, $quote);*/
/*
        $rate = $order->getBaseToOrderRate();

        $order->setQuote($quote);
        $rewardPoints = Mage::helper('rewardpoints/data')->getPointsOnOrder($order, null, $rate);

        if (Mage::getStoreConfig('rewardpoints/default/max_point_collect_order', Mage::app()->getStore()->getId())){
            if ((int)Mage::getStoreConfig('rewardpoints/default/max_point_collect_order', Mage::app()->getStore()->getId()) < $rewardPoints){
                $rewardPoints = Mage::getStoreConfig('rewardpoints/default/max_point_collect_order', Mage::app()->getStore()->getId());
            }
        }


        $customerId = $order->getCustomerId();

        //record points for item into db
        if ($rewardPoints > 0){
            $this->recordPoints($rewardPoints, $customerId, $order->getIncrementId());
        }



        //subtract points for this order
        $points_apply = (int) Mage::helper('rewardpoints/event')->getCreditPoints();
        if ($points_apply > 0){
            $this->useCouponPoints($points_apply, $customerId, $order->getIncrementId());
        }

        //$this->sales_order_success_referral($order->getIncrementId());
        $this->sales_order_success_referral($order);
 */
    }

    protected function getMultishippingQuote($order) {
        $order_shipping_address = Mage::getModel('sales/order_address')->load($order->getShippingAddressId());
        $customer_shipping_address = $order_shipping_address->getCustomerAddressId();

        $order_billing_address = Mage::getModel('sales/order_address')->load($order->getBillingAddressId());
        $customer_billing_address = $order_billing_address->getCustomerAddressId();

        $quote_tmp = Mage::getModel('sales/quote');
        $quote = Mage::getModel('sales/quote')->load($order->getQuoteId());
        foreach($quote->getAddressesCollection() as $my_quote){
            if ($my_quote->getAddressType() == 'shipping' && $my_quote->getCustomerAddressId() == $customer_shipping_address){
                $quote_tmp->setShippingAddress($my_quote);
            } elseif($my_quote->getAddressType() == 'billing' && $my_quote->getCustomerAddressId() == $customer_billing_address) {
                $quote_tmp->setBillingAddress($my_quote);
            }
        }
        return $quote_tmp;
    }


    public function recordPointsForMultiOrderEvent($observer) {

        $event = $observer->getEvent();
        $orders = $event->getOrders();
        $quote = $event->getQuote();

        if ($orders == array()){
            $this->recordPointsForOrderEvent($observer);
            return true;
        }
        
        $customerId = "";
        $store_id = "";
        
        foreach($orders as $order){
            
            $order->setQuote($this->getMultishippingQuote($order));
            $rate = $order->getBaseToOrderRate();
            $customerId = $order->getCustomerId();            
            $store_id = Mage::app()->getStore()->getId();


            $rewardPoints = Mage::helper('rewardpoints/data')->getPointsOnOrder($order, null, $rate);

            if (Mage::getStoreConfig('rewardpoints/default/max_point_collect_order', Mage::app()->getStore()->getId())){
                if ((int)Mage::getStoreConfig('rewardpoints/default/max_point_collect_order', Mage::app()->getStore()->getId()) < $rewardPoints){
                    $rewardPoints = Mage::getStoreConfig('rewardpoints/default/max_point_collect_order', Mage::app()->getStore()->getId());
                }
            }

            //record points for item into db
            if ($rewardPoints > 0){
                $this->recordPoints($rewardPoints, $customerId, $order->getIncrementId());
            }

            //subtract points for this order
            $points_apply = (int) Mage::helper('rewardpoints/event')->getCreditPoints();
            if ($points_apply > 0){
                $this->useCouponPoints($points_apply, $customerId, $order->getIncrementId());
            }

            $this->sales_order_success_referral($order);
        }
        if ($customerId && $store_id){
            $this->processRecordFlat($customerId, $store_id);
        }        
    }




    public function useCouponPoints($pointsAmt, $customerId, $orderId) {
        $reward_model = Mage::getModel('rewardpoints/stats');
        //money_points
        //points_money

        $test_points = $reward_model->checkProcessedOrder($customerId, $orderId, false);
        if (!$test_points->getId()){
            $post = array('order_id' => $orderId, 'customer_id' => $customerId, 'store_id' => Mage::app()->getStore()->getId(), 'points_spent' => $pointsAmt, 'convertion_rate' => Mage::getStoreConfig('rewardpoints/default/points_money', Mage::app()->getStore()->getId()));
            $reward_model->setData($post);
            $reward_model->save();
            Mage::helper('rewardpoints/event')->setCreditPoints(0);
        }
    }
    
    public function processAddModelCallback($observer) {
        //J2T magento 1.3.x fix
        $object = $observer->getEvent()->getObject();
        if ($object instanceof Mage_Sales_Model_Order) { //check points on saving orders
            $order = $object;
            //$quote = $object->getQuote();
            $quote = Mage::getModel('sales/quote')->load($order->getQuoteId());
            $this->pointsOnOrder($order, $quote);
        }
    }
    
    public function processAddModelOrderSave($observer){
        $order = $observer->getEvent()->getOrder();
        $quote = Mage::getModel("sales/quote")->load($order->getQuote());
        $this->pointsOnOrder($order, $quote);
    }


    public function processAddCallback($observer){
        //if (!version_compare(Mage::getVersion(), '1.4.0', '>=')){
        $object = $observer->getEvent()->getObject();
        if ($object instanceof Mage_Review_Model_Review) {
            if ($object->getStatusId() == Mage_Review_Model_Review::STATUS_APPROVED){
                if ($pointsInt = Mage::getStoreConfig('rewardpoints/registration/review_points', $object->getStoreId())){
                    if ($object->getCustomerId()){
                        $reward_model = Mage::getModel('rewardpoints/stats');
                        $data = array('customer_id' => $object->getCustomerId(), 'store_id' => $object->getStoreId(), 'points_current' => $pointsInt, 'order_id' => Rewardpoints_Model_Stats::TYPE_POINTS_REVIEW);
                        $reward_model->setData($data);
                        $reward_model->save();
                    }
                }
            }
        }
        
        if ($object instanceof Mage_Sales_Model_Order) {
            if (($customer_id = $object->getCustomerId()) && ($store_id = $object->getStoreId())){
                $this->processRecordFlat($customer_id, $store_id);
            }            
        }
        
        //}
    }
    
    public function processLoadModelCallback($observer){
        $object = $observer->getEvent()->getObject();
        if ($object instanceof Mage_Customer_Model_Customer) {
            if (($customer_id = $object->getId()) && ($store_id = $object->getStoreId())){
                $this->processRecordFlat($customer_id, $store_id, true);
            }
        }
        if ($object instanceof Mage_Sales_Model_Quote) {
            if (($customer_id = $object->getCustomerId()) && ($store_id = $object->getStoreId())){
                $this->processRecordFlat($customer_id, $store_id, true);
            }
        }
    }
    
    protected function processRecordFlatAction ($customerId, $store_id, $check_date = false) {
        if (Mage::getStoreConfig('rewardpoints/default/flatstats', $store_id)){
            $reward_model = Mage::getModel('rewardpoints/stats');
            $points_current = $reward_model->getPointsCurrent($customerId, $store_id);
            $points_received = $reward_model->getRealPointsReceivedNoExpary($customerId, $store_id);
            $points_spent = $reward_model->getPointsSpent($customerId, $store_id);
            $points_awaiting_validation = $reward_model->getPointsWaitingValidation($customerId, $store_id);
            $points_lost = $reward_model->getRealPointsLost($customerId, $store_id);

            $reward_flat_model = Mage::getModel('rewardpoints/flatstats');
            $reward_flat_model->loadByCustomerStore($customerId, $store_id);
            $reward_flat_model->setPointsCollected($points_received);
            $reward_flat_model->setPointsUsed($points_spent);
            $reward_flat_model->setPointsWaiting($points_awaiting_validation);
            $reward_flat_model->setPointsCurrent($points_current);
            $reward_flat_model->setPointsLost($points_lost);
            $reward_flat_model->setStoreId($store_id);
            $reward_flat_model->setUserId($customerId);
            
            if ($check_date && ($date_check = $reward_flat_model->getLastCheck())){
                $date_array = explode("-", $reward_flat_model->getLastCheck());
                if ($reward_flat_model->getLastCheck() == date("Y-m-d")){
                    return false;
                }
            }
            
            $reward_flat_model->setLastCheck(date("Y-m-d"));
            
            $reward_flat_model->save();
        }
    }
    
    protected function processRecordFlat($customerId, $store_id, $check_date = false){
        if (!Mage::getStoreConfig('rewardpoints/default/store_scope', $store_id)){
            $this->processRecordFlatAction ($customerId, $store_id, $check_date);
        } else {
            //get all stores
            $allStores = Mage::app()->getStores();
            foreach ($allStores as $_eachStoreId => $val) {
                $this->processRecordFlatAction ($customerId, Mage::app()->getStore($_eachStoreId)->getId(), $check_date);
                
                /*$_storeCode = Mage::app()->getStore($_eachStoreId)->getCode();
                $_storeName = Mage::app()->getStore($_eachStoreId)->getName();
                $_storeId = Mage::app()->getStore($_eachStoreId)->getId();
                echo $_storeId;
                echo $_storeCode;
                echo $_storeName;*/
            }
        }
    }
    
    public function processOrderSaveRecordPoints($observer) {
        $object = $observer->getEvent()->getObject();
        if ($object instanceof Mage_Checkout_Model_Cart) {
            //refresh points
            $customerId = $object->getCustomerId();
            $store_id = $object->getStoreId();
            $this->processRecordFlat($customerId, $store_id);
        }
    }
    
    
    public function recordPointsMultiOrSingle($observer){   
        if ($order = $observer->getEvent()->getOrder()) {
            $this->pointsOnOrder($order, $order->getQuote());
        } elseif ($orders = $observer->getEvent()->getOrders()) {
            $this->recordPointsForMultiOrderEvent($observer);
        }
    }
    
    
    
    protected function pointsOnOrder($order, $quote){
        
        if ($order->getCustomerId() == 0){
            return;
        }
        
        $rate = $order->getBaseToOrderRate();
        $order->setQuote($quote);        
        $rewardPoints = Mage::helper('rewardpoints/data')->getPointsOnOrder($order, null, $rate);

        if (Mage::getStoreConfig('rewardpoints/default/max_point_collect_order', Mage::app()->getStore()->getId())){
            if ((int)Mage::getStoreConfig('rewardpoints/default/max_point_collect_order', Mage::app()->getStore()->getId()) < $rewardPoints){
                $rewardPoints = Mage::getStoreConfig('rewardpoints/default/max_point_collect_order', Mage::app()->getStore()->getId());
            }
        }
        $customerId = $order->getCustomerId();
        $store_id = Mage::app()->getStore()->getId();
        //record points for item into db
        if ($rewardPoints > 0){
            $this->recordPoints($rewardPoints, $customerId, $order->getIncrementId());
        }

        //subtract points for this order
        $points_apply = (int) Mage::helper('rewardpoints/event')->getCreditPoints();
        if ($points_apply > 0){
            $this->useCouponPoints($points_apply, $customerId, $order->getIncrementId());
        }

        //$this->sales_order_success_referral($order->getIncrementId());
        $this->sales_order_success_referral($order);
        
        $this->processRecordFlat($customerId, $store_id);
    }
    
    

    public function recordPoints($pointsInt, $customerId, $orderId, $no_check = true) {
        $reward_model = Mage::getModel('rewardpoints/stats');
        //check if points are already processed
        $test_points = $reward_model->checkProcessedOrder($customerId, $orderId, true);
        if (!$test_points->getId()){
            $post = array('order_id' => $orderId, 'customer_id' => $customerId, 'store_id' => Mage::app()->getStore()->getId(), 'points_current' => $pointsInt, 'convertion_rate' => Mage::getStoreConfig('rewardpoints/default/points_money', Mage::app()->getStore()->getId()));
            //v.2.0.0
            $add_delay = 0;
            if ($delay = Mage::getStoreConfig('rewardpoints/default/points_delay', Mage::app()->getStore()->getId())){
                if (is_numeric($delay)){
                    $post['date_start'] = $reward_model->getResource()->formatDate(mktime(0, 0, 0, date("m"), date("d")+$delay, date("Y")));
                    $add_delay = $delay;
                }
            }
            
            if ($duration = Mage::getStoreConfig('rewardpoints/default/points_duration', Mage::app()->getStore()->getId())){
                if (is_numeric($duration)){
                    if (!isset($post['date_start'])){
                        $post['date_start'] = $reward_model->getResource()->formatDate(time());
                    }
                    $post['date_end'] = $reward_model->getResource()->formatDate(mktime(0, 0, 0, date("m"), date("d")+$duration+$add_delay, date("Y")));
                }
            }
            $reward_model->setData($post);
            $reward_model->save();
        }
    }


    public function sales_order_success_referral($order)
    {
        if (!$order->getCustomerId()){
            return;
        }

        //check if referral from link...
        if ($userId = Mage::getSingleton('rewardpoints/session')->getReferralUser()){
            $referrer = Mage::getModel('customer/customer')->load($userId);
            $referree_email = $order->getCustomerEmail();
            $referree_name = $order->getCustomerName();

            $referralModel = Mage::getModel('rewardpoints/referral');
            if (!$referralModel->isSubscribed($referree_email) && $referrer->getEmail() != $referree_email) {
                $referralModel->setRewardpointsReferralParentId($userId)
                         ->setRewardpointsReferralEmail($referree_email)
                         ->setRewardpointsReferralName($referree_name);
                $referralModel->save();
            }
            Mage::getSingleton('rewardpoints/session')->setReferralUser(null);
            Mage::getSingleton('rewardpoints/session')->unsetAll();
        }

        $rewardPoints = Mage::getStoreConfig('rewardpoints/registration/referral_points', Mage::app()->getStore()->getId());
        $rewardPointsChild = Mage::getStoreConfig('rewardpoints/registration/referral_child_points', Mage::app()->getStore()->getId());

        if ($rewardPoints > 0 || $rewardPointsChild > 0 && $order->getCustomerEmail()){
            //$order = $observer->getEvent()->getInvoice()->getOrder();
            $referralModel = Mage::getModel('rewardpoints/referral');
            if ($referralModel->isSubscribed($order->getCustomerEmail())) {
                
                if (!$referralModel->isConfirmed($order->getCustomerEmail())) {
                    $referralModel->loadByEmail($order->getCustomerEmail());
                    $referralModel->setData('rewardpoints_referral_status', true);
                    $referralModel->setData('rewardpoints_referral_child_id', $order->getCustomerId());
                    $referralModel->save();

                    $parent = Mage::getModel('customer/customer')->load($referralModel->getData('rewardpoints_referral_parent_id'));
                    $child    = Mage::getModel('customer/customer')->load($referralModel->getData('rewardpoints_referral_child_id'));

                    try {
                        if ($rewardPoints > 0){
                            //$reward_points = Mage::getModel('rewardpoints/account');
                            //$reward_points->saveCheckedOrder($order->getIncrementId(), $referralModel->getData('rewardpoints_referral_parent_id'), $order->getStoreId(), $rewardPoints, $referralModel->getData('rewardpoints_referral_id'), true);


                            $reward_model = Mage::getModel('rewardpoints/stats');
                            $post = array('order_id' => $order->getIncrementId(), 'customer_id' => $referralModel->getData('rewardpoints_referral_parent_id'),
                                'store_id' => $order->getStoreId(), 'points_current' => $rewardPoints, 'rewardpoints_referral_id' => $referralModel->getData('rewardpoints_referral_id'));
                            $reward_model->setData($post);
                            $reward_model->save();
                        }

                        if ($rewardPointsChild > 0){
                            //$reward_points2 = Mage::getModel('rewardpoints/account');
                            //$reward_points2->saveCheckedOrder($order->getIncrementId(), $referralModel->getData('rewardpoints_referral_child_id'), $order->getStoreId(), $rewardPointsChild, $referralModel->getData('rewardpoints_referral_id'), true);



                            $reward_model = Mage::getModel('rewardpoints/stats');
                            $post = array('order_id' => $order->getIncrementId(), 'customer_id' => $referralModel->getData('rewardpoints_referral_child_id'),
                                'store_id' => $order->getStoreId(), 'points_current' => $rewardPointsChild, 'rewardpoints_referral_id' => $referralModel->getData('rewardpoints_referral_id'));
                            $reward_model->setData($post);
                            $reward_model->save();


                        }

                    } catch (Exception $e) {
                        //Mage::getSingleton('session')->addError($e->getMessage());
                    }
                    $referralModel->sendConfirmation($parent, $child, $parent->getEmail());
                }
            }
        }
    }

    public function sales_order_invoice_pay($observer)
    {
        $rewardPoints = Mage::getStoreConfig('rewardpoints/registration/referral_points', Mage::app()->getStore()->getId());
        $rewardPointsChild = Mage::getStoreConfig('rewardpoints/registration/referral_child_points', Mage::app()->getStore()->getId());
        if ($rewardPoints > 0 || $rewardPointsChild > 0){
            $order = $observer->getEvent()->getInvoice()->getOrder();
            $referralModel = Mage::getModel('rewardpoints/referral');
            if ($referralModel->isSubscribed($order->getCustomerEmail())) {
                if (!$referralModel->isConfirmed($order->getCustomerEmail())) {
                    $referralModel->loadByEmail($order->getCustomerEmail());
                    $referralModel->setData('rewardpoints_referral_status', true);
                    $referralModel->setData('rewardpoints_referral_child_id', $order->getCustomerId());
                    $referralModel->save();

                    $parent = Mage::getModel('customer/customer')->load($referralModel->getData('rewardpoints_referral_parent_id'));
                    $child    = Mage::getModel('customer/customer')->load($referralModel->getData('rewardpoints_referral_child_id'));
                    $referralModel->sendConfirmation($parent, $child, $parent->getEmail());

                    try {
                        if ($rewardPoints > 0){
                            //$reward_points = Mage::getModel('rewardpoints/account');
                            //$reward_points->saveCheckedOrder($order->getIncrementId(), $referralModel->getData('rewardpoints_referral_parent_id'), $order->getStoreId(), $rewardPoints, $referralModel->getData('rewardpoints_referral_id'), true);



                            $reward_model = Mage::getModel('rewardpoints/stats');
                            $post = array('order_id' => $order->getIncrementId(), 'customer_id' => $referralModel->getData('rewardpoints_referral_parent_id'),
                                'store_id' => $order->getStoreId(), 'points_current' => $rewardPoints, 'rewardpoints_referral_id' => $referralModel->getData('rewardpoints_referral_id'));
                            $reward_model->setData($post);
                            $reward_model->save();

                        }


                        if ($rewardPointsChild > 0){
                            //$reward_points2 = Mage::getModel('rewardpoints/account');
                            //$reward_points2->saveCheckedOrder($order->getIncrementId(), $referralModel->getData('rewardpoints_referral_child_id'), $order->getStoreId(), $rewardPointsChild, $referralModel->getData('rewardpoints_referral_id'), true);


                            $reward_model = Mage::getModel('rewardpoints/stats');
                            $post = array('order_id' => $order->getIncrementId(), 'customer_id' => $referralModel->getData('rewardpoints_referral_child_id'),
                                'store_id' => $order->getStoreId(), 'points_current' => $rewardPointsChild, 'rewardpoints_referral_id' => $referralModel->getData('rewardpoints_referral_id'));
                            $reward_model->setData($post);
                            $reward_model->save();

                        }

                    } catch (Exception $e) {
                        //Mage::getSingleton('session')->addError($e->getMessage());
                    }
                }
            }
        }
    }

    public function applyDiscount($observer)
    {
        /*try {

            $customer = Mage::getSingleton('customer/session');
            if ($customer->isLoggedIn()){
                return Mage::getModel('rewardpoints/discount')->apply($observer->getEvent()->getItem());
            } else return null;

            //return $this->_discount->apply($observer->getEvent()->getItem());
        } catch (Mage_Core_Exception $e) {
            Mage::getSingleton('checkout/session')->addError($e->getMessage());
        } catch (Exception $e) {
           Mage::getSingleton('checkout/session')->addError($e);
        }*/
    }
    
    
    /*
    public function attachRewardPointsAttributes($observer) {
        
        if($observer->getEvent()->getRequest()->isPost()) {
            $rewardpoints_description = $observer->getEvent()->getRequest()->getPost('rewardpoints_description', '');
            $rewardpoints = $observer->getEvent()->getRequest()->getPost('rewardpoints', '');
            $base_rewardpoints = $observer->getEvent()->getRequest()->getPost('base_rewardpoints', '');
            
            $quote = $observer->getEvent()->getQuote();
            $quote->setRewardpointsDescription($rewardpoints_description);
            $quote->setRewardpoints($rewardpoints);
            $quote->setBaseRewardpoints($base_rewardpoints);
        }
    }
    */
    
    
    

}
