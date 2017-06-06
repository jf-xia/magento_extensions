<?php
    class Topbuy_Addons_Model_Mysql4_Warrantyregisterrecord extends Mage_Core_Model_Mysql4_Abstract
    {
        protected function _construct()
        {
            $this->_init("addons/warrantyregisterrecord", "rowid");
//            $this->_isPkAutoIncrement = false;
        }
    }