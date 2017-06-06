<?php
    class Topbuy_Addons_Model_Mysql4_Csgroupproduct extends Mage_Core_Model_Mysql4_Abstract
    {
        protected function _construct()
        {
            $this->_init("addons/csgroupproduct", "rowid");
            $this->_isPkAutoIncrement = false;
        }
        
    }
	 