<?php
    class Click2Customer_Analytics_Model_Config extends Varien_Object {
        protected $_config;
        
        protected function _construct () {
		    $helper = Mage::helper('analytics');
        }
        
        public function getConfig ($path, $store=null) {
		    return Mage::getStoreConfig('analytics/settings/' . $path, $store);
	    }
        
        public function getAccountId() {
            $storeId = Mage::app()->getStore()->getId();
		    return $this->getConfig('account_id', $storeId);
        }
        
        public function getURL() {
            $storeId = Mage::app()->getStore()->getId();
            return $this->getConfig('service_url', $storeId);
        }
    }
?>
