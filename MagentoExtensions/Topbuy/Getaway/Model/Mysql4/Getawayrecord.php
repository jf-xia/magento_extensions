<?php
    class Topbuy_Getaway_Model_Mysql4_Getawayrecord extends Mage_Core_Model_Mysql4_Abstract
    {
        protected function _construct()
        {
            $this->_init("getaway/getawayrecord", "tablename_id");
        }
    }
	 