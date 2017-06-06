<?php
    class Topbuy_Homepage_Model_Mysql4_Ordermap extends Mage_Core_Model_Mysql4_Abstract
    {
        protected function _construct()
        {
            $this->_init("homepage/ordermap","id_tborder");
            $this->_isPkAutoIncrement = false;
        }
    }
	 