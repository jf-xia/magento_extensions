<?php
    class Topbuy_Addons_Model_Mysql4_Csproductmap extends Mage_Core_Model_Mysql4_Abstract
    {
        protected function _construct()
        {
            $this->_init("addons/csproductmap","rowid");
            $this->_isPkAutoIncrement = false;
        }
    }
	 