<?php
    class Topbuy_Customercare_Model_Mysql4_Pcfstatus extends Mage_Core_Model_Mysql4_Abstract
    {
        protected function _construct()
        {
            $this->_init("customercare/pcfstatus", "pcfstat_idstatus");
            $this->_isPkAutoIncrement = false;
        }
    }
	 