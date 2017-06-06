<?php
    class Topbuy_Noroute_Model_Mysql4_Norouterecord extends Mage_Core_Model_Mysql4_Abstract
    {
        protected function _construct()
        {
            $this->_init("noroute/norouterecord", "rowid");
        }
    }
	 