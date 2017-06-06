<?php
    class Topbuy_Homepage_Model_Mysql4_Justbought extends Mage_Core_Model_Mysql4_Abstract
    {
        protected function _construct()
        {
            $this->_init("homepage/justbought","rowid");
//            $this->_isPkAutoIncrement = false;
        }
    }
	 