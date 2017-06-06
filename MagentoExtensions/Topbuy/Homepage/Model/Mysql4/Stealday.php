<?php
    class Topbuy_Homepage_Model_Mysql4_Stealday extends Mage_Core_Model_Mysql4_Abstract
    {
        protected function _construct()
        {
            $this->_init("homepage/stealday", "rowid");
            $this->_isPkAutoIncrement = false;
        }
    }
	 