<?php
    class Topbuy_Homepage_Model_Mysql4_Promotioncrosssale extends Mage_Core_Model_Mysql4_Abstract
    {
        protected function _construct()
        {
            $this->_init("homepage/promotioncrosssale", "rowid");
            $this->_isPkAutoIncrement = false;
        }
    }
	 