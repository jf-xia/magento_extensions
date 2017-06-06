<?php
    class Topbuy_Homepage_Model_Mysql4_Customermap extends Mage_Core_Model_Mysql4_Abstract
    {
        protected function _construct()
        {
            $this->_init("homepage/customermap","id_tbcustomer");
            $this->_isPkAutoIncrement = false;
        }
    }
	 