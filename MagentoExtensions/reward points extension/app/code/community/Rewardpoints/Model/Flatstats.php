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
 * @copyright  Copyright (c) 2011 J2T DESIGN. (http://www.j2t-design.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Rewardpoints_Model_Flatstats extends Mage_Core_Model_Abstract
{
    protected $points_current;
    protected $points_collected;
    protected $points_received;
    protected $points_spent;
    protected $points_waiting;
    
    protected $points_lost;
    
    
    public function _construct()
    {
        parent::_construct();
        $this->_init('rewardpoints/flatstats');
    }
    
    
    public function processRecordFlat($customerId, $store_id, $check_date = false){
        if (Mage::getStoreConfig('rewardpoints/default/flatstats', $store_id)){
            $reward_model = Mage::getModel('rewardpoints/stats');
            $points_current = $reward_model->getPointsCurrent($customerId, $store_id);
            $points_received = $reward_model->getRealPointsReceivedNoExpary($customerId, $store_id);
            $points_spent = $reward_model->getPointsSpent($customerId, $store_id);
            $points_awaiting_validation = $reward_model->getPointsWaitingValidation($customerId, $store_id);
            $points_lost = $reward_model->getRealPointsLost($customerId, $store_id);

            $this->loadByCustomerStore($customerId, $store_id);
            $this->setPointsCollected($points_received);
            $this->setPointsUsed($points_spent);
            $this->setPointsWaiting($points_awaiting_validation);
            $this->setPointsCurrent($points_current);
            $this->setPointsLost($points_lost);
            $this->setStoreId($store_id);
            $this->setUserId($customerId);
            
            if ($check_date && ($date_check = $reward_flat_model->getLastCheck())){
                $date_array = explode("-", $reward_flat_model->getLastCheck());
                if ($this->getLastCheck() == date("Y-m-d")){
                    return false;
                }
            }            
            $this->setLastCheck(date("Y-m-d"));            
            $this->save();
        }
    }
    
    
    
    public function loadByCustomerStore($customerId, $storeId, $date=null)
    {
        $this->addData($this->getResource()->loadByCustomerStore($customerId, $storeId, $date=null));
        return $this;
    }
    
    
    protected function collectVariablesValues($customer_id, $store_id)
    {
        $this->loadByCustomerStore($customer_id, $store_id);
        $this->points_current = $this->getPointsCurrent();
        $this->points_collected = $this->getPointsCollected();
        $this->points_waiting = $this->getPointsWaiting();
        $this->points_spent = $this->getPointsUsed();
        $this->points_lost = $this->getPointsLost();
        
    }
    
    public function collectPointsCurrent($customer_id, $store_id){        
        if ($this->points_current != null){
            return $this->points_current;
        }        
        $this->collectVariablesValues($customer_id, $store_id);        
        return $this->points_current;
    }

    public function collectPointsReceived($customer_id, $store_id){
        if ($this->points_collected != null){
            return $this->points_collected;
        }        
        $this->collectVariablesValues($customer_id, $store_id);        
        return $this->points_collected;
    }

    public function collectPointsSpent($customer_id, $store_id){
        if ($this->points_spent != null){
            return $this->points_spent;
        }        
        $this->collectVariablesValues($customer_id, $store_id);        
        return $this->points_spent;
    }

    public function collectPointsWaitingValidation($customer_id, $store_id){
        if ($this->points_waiting != null){
            return $this->points_waiting;
        }        
        $this->collectVariablesValues($customer_id, $store_id);        
        return $this->points_waiting;
    }
    
    public function collectPointsLost($customer_id, $store_id) {
        if ($this->points_lost != null){
            return $this->points_lost;
        }        
        $this->collectVariablesValues($customer_id, $store_id);        
        return $this->points_lost;
    }
    
    

}

