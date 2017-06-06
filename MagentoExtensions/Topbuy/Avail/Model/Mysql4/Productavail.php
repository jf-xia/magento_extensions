<?php
    class Topbuy_Avail_Model_Mysql4_Productavail extends Mage_Core_Model_Mysql4_Abstract
    {
        protected function _construct()
        {
            $this->_init("avail/productavail", "rowid");
        }
    }
	 