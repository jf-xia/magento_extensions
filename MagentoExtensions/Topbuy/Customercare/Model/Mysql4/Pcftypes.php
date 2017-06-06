<?php
    class Topbuy_Customercare_Model_Mysql4_Pcftypes extends Mage_Core_Model_Mysql4_Abstract
    {
        protected function _construct()
        {
            $this->_init("customercare/pcftypes", "pcftype_idtype");
            $this->_isPkAutoIncrement = false;
        }
    }
	 