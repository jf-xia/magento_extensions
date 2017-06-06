<?php
    class Topbuy_Homepage_Model_Mysql4_Categoryspecial extends Mage_Core_Model_Mysql4_Abstract
    {
        protected function _construct()
        {
            $this->_init("homepage/categoryspecial", "rowid");
            $this->_isPkAutoIncrement = false;
        }
    }
	 